<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

// ── Helper ────────────────────────────────────────────────────────────────────
function respond(array $data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── DB Connect ────────────────────────────────────────────────────────────────
include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    respond(['status' => 'error', 'message' => 'Cannot connect to database.'], 500);
}

// ── Validate input ────────────────────────────────────────────────────────────
$branch_id = isset($_GET['bid']) ? (int) $_GET['bid'] : 0;
if ($branch_id <= 0) {
    pg_close($db);
    respond(['status' => 'error', 'message' => 'branch_id (bid) is required.'], 400);
}

// ── Query ─────────────────────────────────────────────────────────────────────
$sql = " SELECT
        bi.branch_id,
        bi.branch_name,
        (SELECT value FROM home_branch WHERE branch_id = bi.branch_id::text AND home_row_id = 3 LIMIT 1) AS start_date_farm,
        (SELECT value FROM home_branch WHERE branch_id = bi.branch_id::text AND home_row_id = 4 LIMIT 1) AS total,
        (SELECT value FROM home_branch WHERE branch_id = bi.branch_id::text AND home_row_id = 5 LIMIT 1) AS remain,
        (SELECT value FROM home_branch WHERE branch_id = bi.branch_id::text AND home_row_id = 6 LIMIT 1) AS round,
        COALESCE(SUM(exp.amount), 0)                    AS sum_expense,
        COALESCE(SUM(inc.amount), 0)                    AS sum_income,
        COALESCE(ROUND(AVG(exp.amount)::numeric, 2), 0) AS avg_expense,
        COALESCE(ROUND(AVG(inc.amount)::numeric, 2), 0) AS avg_income
    FROM branch_info bi
    LEFT JOIN expense exp ON exp.branch_id = bi.branch_id
    LEFT JOIN income  inc ON inc.branch_id = bi.branch_id
    WHERE bi.branch_id = $1
    GROUP BY bi.branch_id, bi.branch_name
";

$result = pg_query_params($db, $sql, [$branch_id]);

if ($result === false) {
    $err = pg_last_error($db); // ดึงก่อน
    pg_close($db);             // แล้วค่อยปิด
    respond(['status' => 'error', 'message' => 'Query failed: ' . $err], 500);
}

// ── Fetch home data ───────────────────────────────────────────────────────────
$ch = curl_init('http://localhost/iotsf/api-website/fetch_homeBybid.php?bid=' . $branch_id);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 20,
]);

$homeRaw = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

if ($homeRaw === false || $curlErr) {
    respond(['status' => 'error', 'message' => 'Failed to fetch home data.', 'error' => $curlErr], 502);
}

$homeRes = json_decode($homeRaw, true);
if (!is_array($homeRes)) {
    respond(['status' => 'error', 'message' => 'Home API returned invalid JSON.'], 502);
}


// ── Build response ────────────────────────────────────────────────────────────
$data = [];
while ($row = pg_fetch_assoc($result)) {
    $data['info'] = $row;
}
$home = $homeRes['data'] ?? [];

$data['info']['calculated'] = ($home['round'] ?? 0) . " round(s) passed and "
    . ($home['day']   ?? 0) . " day(s) "
    . ($home['hour']  ?? 0) . " hour(s) "
    . ($home['min']   ?? 0) . " min(s)";

pg_close($db);

if (empty($data)) {
    respond(['status' => 'error', 'message' => 'No data found for this branch.', 'data' => []], 404);
}

// ── Merge ─────────────────────────────────────────────────────────────────────
respond(['status' => 'success', 'data' => $data,]);
