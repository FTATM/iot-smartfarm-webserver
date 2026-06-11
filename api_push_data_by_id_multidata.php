<?php

/**
 * Sensor Data Receiver API
 * รับข้อมูล sensor หลายรายการพร้อมกัน (batch)
 * 
 * POST Body (JSON Array):
 * [
 *   { "monitor_id": "...", "data_value": "..." },
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
    respondAndExit(false, 400, 'INPUT_INVALID', 'Request body must be a non-empty JSON array');
}

// ─────────────────────────────────────────
//  Connect DB
// ─────────────────────────────────────────
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    respondAndExit(false, 500, 'DB_ERROR', 'Unable to connect to database');
}

// ─────────────────────────────────────────
//  Process Each Item
// ─────────────────────────────────────────
$results = [];

foreach ($items as $index => $item) {

    $label = "item[{$index}]";

    // --- 1. Validate required fields ---
    $required = ['monitor_id', 'data_value'];
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

    $monitor_id   = $item['monitor_id'];
    $data_value = (float) $item['data_value'];

    pg_query($db, 'BEGIN');

    $upd_monitor = pg_query_params(
        $db,
        "UPDATE page_data_manage_monitor
        SET datax_value = $1
        WHERE monitor_id = $2
        RETURNING group_id, device_id, type_id, datax_id",
        [$data_value, $monitor_id]
    );

    if (!$upd_monitor || pg_num_rows($upd_monitor) === 0) {
        pg_query($db, 'ROLLBACK');

        $results[] = errorResult(
            $index,
            'MONITOR_NOT_FOUND',
            "monitor_id {$monitor_id} not found"
        );

        continue;
    }

    $monitorInfo = pg_fetch_assoc($upd_monitor);

    $group_id  = $monitorInfo['group_id'];
    $device_id = $monitorInfo['device_id'];
    $type_id   = $monitorInfo['type_id'];
    $datax_id  = $monitorInfo['datax_id'];

    $ins_log = pg_query_params(
        $db,
        "INSERT INTO data_log (group_id, device_id, type_id, datax_id, data_value, createtime)
         VALUES ($1, $2, $3, $4, $5, NOW())",
        [$group_id, $device_id, $type_id, $datax_id, $data_value]
    );

    if (!$ins_log) {
        pg_query($db, 'ROLLBACK');
        $results[] = errorResult($index, 'SAVE_FAILED', pg_last_error($db));
        continue;
    }

    pg_query($db, 'COMMIT');

    // --- 6. Check thresholds & notify ---
    // $alerts = checkThresholds($db, $group_id, $device_id, $type_id, $datax_id, $data_value);

    $results[] = [
        'index'   => $index,
        'status'  => 'ok',
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
    'success' => $hasError ? false : true,
    'msg'     => $hasError ? 'Partial success' : 'All items saved successfully',
    'results' => $results,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;


// ═══════════════════════════════════════════
//  HELPER FUNCTIONS
// ═══════════════════════════════════════════


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
function respondAndExit(bool $status, int $httpCode, string $code, string $message): void
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode(['success' => $status, 'ret' => (string)$httpCode, 'code' => $code, 'msg' => $message], JSON_UNESCAPED_UNICODE);
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
