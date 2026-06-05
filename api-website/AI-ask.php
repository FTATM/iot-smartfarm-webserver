<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once(__DIR__ . '/../includes/fn/pg_connect.php');
require_once 'config.php';
require_once 'AI-functions.php';

$AI_MODEL = $AI_MODE == 0 ? $AI_config['model'] : $AI_EXTERNAL_config['model'];
// ── DB Connect (optional) ─────────────────────────────────────────────────────
$db    = null;
$error = null;

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    $error = error_get_last()['message'] ?? 'Cannot connect to database.';
    $db    = null; // ชัดเจนว่าไม่มี connection
}
// ── Helper ──────────────────────────────────────────────────────────────────
function respond(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── Input validation ─────────────────────────────────────────────────────────
$body     = file_get_contents('php://input');
$question = json_decode($body, true);

if (empty($question)) {
    respond(['success' => false, 'message' => 'Request body is missing or invalid JSON'], 400);
}

$message = $question['message'] ?? '';
if ($message === '') {
    respond(['success' => false, 'message' => 'Param message is required'], 400);
}

// ── Session & branch validation ───────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$branch_id = (int) ($_SESSION['branch_id'] ?? 0);
if ($branch_id <= 0) {
    respond(['success' => false, 'message' => 'Not found session, please login.'], 401);
}

// ── Fetch farm information ────────────────────────────────────────────────────
$url = 'http://localhost/iotsf/api-website/AI-infomation_farm.php?bid=' . urlencode($branch_id);
$ch  = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FAILONERROR    => true,
]);

$farmRaw  = curl_exec($ch);
$curlErr  = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($farmRaw === false || $curlErr) {
    respond(['success' => false, 'message' => 'Failed to fetch farm data.', 'error' => $curlErr], 502);
}

if ($httpCode !== 200) {
    respond(['success' => false, 'message' => "Farm API returned HTTP {$httpCode}."], 502);
}

$farmData = json_decode($farmRaw, true);
if (!isset($farmData) || !is_array($farmData)) {
    respond(['success' => false, 'message' => 'Farm API returned invalid JSON.'], 502);
}
$raw_data = $farmData['data'];
$info     = $raw_data['info'];

$farmData_text = implode(' ', [
    "ข้อมูลฟาร์มในปัจจุบัน",
    $info['calculated'] . ".",
    "ฟาร์ม: "          . $info['branch_name']     . ".",
    "เริ่มเลี้ยง: "     . $info['start_date_farm'] . ".",
    "จำนวนทั้งหมด: "   . $info['total']            . " ตัว.",
    "เหลืออยู่: "       . $info['remain']           . " ตัว.",
    "รอบที่: "          . $info['round']            . ".",
    "รายจ่ายรวม: "     . $info['sum_expense']      . " บาท.",
    "รายรับรวม: "      . $info['sum_income']       . " บาท.",
    "รายจ่ายเฉลี่ย: "  . $info['avg_expense']      . " บาท.",
    "รายรับเฉลี่ย: "   . $info['avg_income']       . " บาท.",
]);

// ── Build prompt ─────────────────────────────────────────────────────────────
$prompt = <<<PROMPT
SYSTEM:
คุณคือผู้ช่วย AI ที่คอยตอบคำถามกับผู้ใช้ที่ถามเข้ามา
- ตอบเป็นภาษาไทยเท่านั้น
- ตอบสั้นที่สุดเท่าที่จะทำได้ ไม่เกิน 2 ประโยค
- ห้ามอธิบายซ้ำหรือพูดถึงคำถามซ้ำอีก
- จำกัดขอบเขตการตอบให้ตรงประเด็น ไม่มีคำนำหรือคำลงท้ายเสริม
- หากไม่รู้หรือไม่สามารถตอบคำถามได้ ให้ตอบว่า "ไม่มีข้อมูล"
- หากไม่พบข้อมูลในฐานข้อมูล ให้ตอบว่า "ไม่พบข้อมูลในฐานข้อมูล"
- แนะนำให้ผู้ใช้พิจารณาอัพเกรดเป็นเวอร์ชัน Pro ที่เร็วกว่า
- ตอบข้อมูลในรูปแบบ JSON เท่านั้น ห้ามมี text นอก JSON

data : {
$farmData_text
}

question : $message

SCHEMA:
{
    "answer": "string",
    "suggestion": "string"
}
PROMPT;

// ── Call AI ──────────────────────────────────────────────────────────────────
$timeout  = ($AI_MODE === 0) ? 3600 : 60;
$response = CallAI($prompt, $AI_MODE, $AI_config, $AI_EXTERNAL_config, $timeout);

// ── Parse outer envelope from CallAI ────────────────────────────────────────
$decoded = json_decode(trim($response), true);

if (!isset($decoded['success']) || $decoded['success'] === false) {
    respond([
        'success' => false,
        'message' => 'AI call failed',
        'error'   => $decoded['message'] ?? 'Unknown error',
    ], 502);
}

// ── Parse AI content (the actual answer JSON) ────────────────────────────────
$aiRaw = $decoded['message'] ?? '';

// Strip possible markdown fences e.g. ```json ... ```
$aiRaw = preg_replace('/^```(?:json)?\s*/i', '', trim($aiRaw));
$aiRaw = preg_replace('/\s*```$/', '', $aiRaw);

$aiContent = json_decode($aiRaw, true);

if (!isset($aiContent['answer'])) {
    respond([
        'success' => false,
        'message' => 'AI returned unexpected format',
        'raw'     => $aiRaw,
    ], 502);
}

// ── Log (ถ้า DB พร้อม) ───────────────────────────────────────────────────────
$response_log = null;

if ($db) {
    $raw_logs     = INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Assistant', 1, $message, $prompt, $response);
    $response_log = json_decode($raw_logs, true);
    pg_close($db);
}

// ── Success ───────────────────────────────────────────────────────────────────
respond([
    'success'    => true,
    'answer'     => $aiContent['answer'],
    'suggestion' => $aiContent['suggestion'] ?? '',
    'log'        => $response_log['message'] ?? null,
    'db_error'   => $error, // null ถ้า connect ได้, มี message ถ้าไม่ได้
]);
