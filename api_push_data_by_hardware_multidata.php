<?php

/**
 * Sensor Data Receiver API
 * รับข้อมูล sensor หลายรายการพร้อมกัน (batch)
 * 
 * POST Body (JSON Array):
 * [
 *   { "group_id": "...", "device_id": "...", "type_id": "...", "datax_id": "...", "data_value": "..." },
 *   ...
 * ]
 */

include_once("includes/fn/pg_connect.php");
require 'api-app/notify_email.php';
require 'api-app/notify_line.php';

// ─────────────────────────────────────────
//  Parse Input
// ─────────────────────────────────────────
$jsonInput = file_get_contents('php://input');
$items     = json_decode($jsonInput, true);

if (!is_array($items) || empty($items)) {
    respondAndExit(400, 'INPUT_INVALID', 'Request body must be a non-empty JSON array');
}

// ─────────────────────────────────────────
//  Connect DB
// ─────────────────────────────────────────
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    respondAndExit(500, 'DB_ERROR', 'Unable to connect to database');
}

// ─────────────────────────────────────────
//  Process Each Item
// ─────────────────────────────────────────
$results = [];

foreach ($items as $index => $item) {

    $label = "item[{$index}]";

    // --- 1. Validate required fields ---
    $required = ['group_id', 'device_id', 'type_id', 'datax_id', 'data_value'];
    $missing  = [];
    foreach ($required as $field) {
        if (!array_key_exists($field, $item)) {
            $missing[] = $field;
        }
    }
    if (!empty($missing)) {
        $results[] = errorResult($index, 'MISSING_PARAMS', 'Missing: ' . implode(', ', $missing));
        continue;
    }

    $group_id   = $item['group_id'];
    $device_id  = $item['device_id'];
    $type_id    = $item['type_id'];
    $datax_id   = $item['datax_id'];
    $data_value = (float) $item['data_value'];

    // // --- 2. Lookup device ---
    // $row_device = queryOne(
    //     "SELECT device_id, divice_name FROM page_data_manage_device WHERE device_id = $1",
    //     [$device_id],
    //     $db
    // );
    // if (!$row_device) {
    //     $results[] = errorResult($index, 'DEVICE_NOT_FOUND', "device_id '{$device_id}' not found");
    //     continue;
    // }
    // $divice_name = $row_device['divice_name'];

    // // --- 3. Lookup datax ---
    // $row_datax = queryOne(
    //     "SELECT datax_id, datax_name FROM page_data_manage_datax WHERE datax_id = $1",
    //     [$datax_id],
    //     $db
    // );
    // if (!$row_datax) {
    //     $results[] = errorResult($index, 'DATAX_NOT_FOUND', "datax_id '{$datax_id}' not found");
    //     continue;
    // }
    // $datax_name = $row_datax['datax_name'];

    // // --- 4. Lookup group ---
    // $row_group = queryOne(
    //     "SELECT group_id, group_name, value_map_volte_censor FROM page_data_manage_group WHERE group_id = $1",
    //     [$group_id],
    //     $db
    // );
    // if (!$row_group) {
    //     $results[] = errorResult($index, 'GROUP_NOT_FOUND', "group_id '{$group_id}' not found");
    //     continue;
    // }
    // $value_map_volte_censor = $row_group['value_map_volte_censor'];

    // // --- 5. Save data (in transaction) ---
    // $col = strtoupper($datax_name);   // dynamic column name (trusted from DB, not user input)

    // pg_query($db, 'BEGIN');

    // $ins_volte = pg_query_params($db,
    //     "INSERT INTO volte_censor
    //         (create_uid, write_uid, name, location, date_mornitor, create_date, write_date,
    //          volte, sensor, unit, value, voltemax, \"{$col}\")
    //      VALUES ($1, $2, $3, $4, $5, now(), now(), $6, $7, $8, $9, $10, $11)",
    //     ['4', '4', $divice_name, $value_map_volte_censor, date('Y-m-d'),
    //      '0', '1', '0', '0', '0', $data_value]
    // );

    $upd_monitor = pg_query_params(
        $db,
        "UPDATE page_data_manage_monitor
         SET datax_value = $1
         WHERE group_id = $2 AND device_id = $3 AND type_id = $4 AND datax_id = $5",
        [$data_value, $group_id, $device_id, $type_id, $datax_id]
    );

    $ins_log = pg_query_params(
        $db,
        "INSERT INTO data_log (group_id, device_id, type_id, datax_id, data_value, createtime)
         VALUES ($1, $2, $3, $4, $5, NOW())",
        [$group_id, $device_id, $type_id, $datax_id, $data_value]
    );

    // if (!$ins_volte || !$upd_monitor || !$ins_log) {
    if (!$upd_monitor || !$ins_log) {
        pg_query($db, 'ROLLBACK');
        $results[] = errorResult($index, 'SAVE_FAILED', pg_last_error($db));
        continue;
    }

    pg_query($db, 'COMMIT');

    // --- 6. Check thresholds & notify ---
    $alerts = checkThresholds($db, $group_id, $device_id, $type_id, $datax_id, $data_value);

    $results[] = [
        'index'   => $index,
        'status'  => 'ok',
        'alerts'  => count($alerts),
    ];
}

// ─────────────────────────────────────────
//  Response
// ─────────────────────────────────────────
pg_close($db);

$hasError = count(array_filter($results, fn($r) => $r['status'] === 'error')) > 0;

http_response_code($hasError ? 207 : 200);   // 207 Multi-Status ถ้ามีบางรายการ error
header('Content-Type: application/json');
echo json_encode([
    'ret'     => $hasError ? '207' : '101',
    'msg'     => $hasError ? 'Partial success' : 'All items saved successfully',
    'results' => $results,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;


// ═══════════════════════════════════════════
//  HELPER FUNCTIONS
// ═══════════════════════════════════════════

/**
 * Query และคืนแถวเดียว (assoc array) หรือ null ถ้าไม่เจอ
 */
function queryOne(string $sql, array $params, $db): ?array
{
    $result = pg_query_params($db, $sql, $params);
    if (!$result || pg_num_rows($result) !== 1) {
        return null;
    }
    return pg_fetch_assoc($result);
}

/**
 * ตรวจ threshold ของ monitor ที่ตรงกับ item นี้ และส่งแจ้งเตือน
 */
function checkThresholds($db, $group_id, $device_id, $type_id, $datax_id, float $data_value): array
{
    $sql = "SELECT m.*, b.branch_name, g.group_name, d.divice_name, x.datax_name
            FROM page_data_manage_monitor m
            LEFT JOIN branch_info            b ON m.branch_id = b.branch_id
            LEFT JOIN page_data_manage_group g ON m.group_id  = g.group_id
            LEFT JOIN page_data_manage_device d ON m.device_id = d.device_id
            LEFT JOIN page_data_manage_datax  x ON m.datax_id  = x.datax_id
            WHERE m.group_id  = $1
              AND m.device_id = $2
              AND m.type_id   = $3
              AND m.datax_id  = $4
              AND (m.is_min = 1 OR m.is_max = 1)";

    $result = pg_query_params($db, $sql, [$group_id, $device_id, $type_id, $datax_id]);
    if (!$result) return [];

    $alerts = [];

    while ($row = pg_fetch_assoc($result)) {

        $minValue = (float) $row['min_value'];
        $maxValue = (float) $row['max_value'];
        $isMin    = $row['is_min'] === '1';
        $isMax    = $row['is_max'] === '1';
        $isEmail  = $row['is_email'] === '1';
        $isLine   = $row['is_line'] === '1';

        $triggered = [];
        if ($isMin && $data_value <= $minValue) $triggered[] = "ค่าต่ำกว่าที่กำหนด!! (MIN: {$minValue})";
        if ($isMax && $data_value >= $maxValue) $triggered[] = "ค่าสูงกว่าที่กำหนด!! (MAX: {$maxValue})";

        if (empty($triggered)) continue;

        $msg = implode("\n", $triggered) . "\n";
        $msg .= "ข้อมูลที่วัดได้: {$data_value}\n\n";
        $msg .= "============================\n";
        $msg .= "ตำแหน่ง Sensor:\n";
        $msg .= " - ที่ตั้ง  : {$row['branch_name']}\n";
        $msg .= " - โรงเรือน: {$row['group_name']}\n";
        $msg .= " - อุปกรณ์ : {$row['divice_name']}\n";
        $msg .= " - ช่องข้อมูล: {$row['datax_name']}\n";
        $msg .= "============================\n";

        if ($isEmail && !empty($row['input_email'])) {
            sendEmail($row['input_email'], "Monitor Alert #{$row['monitor_name']}", $msg);
        }
        if ($isLine && !empty($row['input_line'])) {
            sendLineOA($row['input_line'], $msg);
        }

        $alerts[] = ['monitor_id' => $row['monitor_id'], 'triggers' => $triggered];
    }

    return $alerts;
}

/**
 * สร้าง error result สำหรับ item หนึ่งรายการ
 */
function errorResult(int $index, string $code, string $message): array
{
    return ['index' => $index, 'status' => 'error', 'code' => $code, 'message' => $message];
}

/**
 * ตอบ response แล้วจบ (ใช้สำหรับ fatal error ก่อนเข้า loop)
 */
function respondAndExit(int $httpCode, string $code, string $message): void
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode(['ret' => (string)$httpCode, 'code' => $code, 'msg' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * ดึง Client IP (รองรับ Cloudflare / Proxy)
 */
function getClientIP(): string
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))  return $_SERVER['HTTP_CF_CONNECTING_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    if (!empty($_SERVER['HTTP_CLIENT_IP']))         return $_SERVER['HTTP_CLIENT_IP'];
    return $_SERVER['REMOTE_ADDR'];
}
