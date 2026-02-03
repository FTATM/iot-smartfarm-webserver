<?php
header("Content-Type: application/json; charset=utf-8");
require __DIR__ . "/../../includes/fn/pg_connect.php";
// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
// include_once("../includes/fn/pg_connect.php");

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$conn = pg_connect("$host $port $dbname $credentials");
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["Error" => "Method not allowed"], JSON_UNESCAPED_UNICODE);
    exit();
}

// สมมติว่า $conn = pg_connect(...) แล้ว

$sql = "
    SELECT
    m.monitor_id,
    CASE
        WHEN d.divice_name ILIKE 'ph%' THEN 'ph'
        WHEN d.divice_name ILIKE 'temp%' THEN 'temp'
        WHEN d.divice_name ILIKE 'do%' THEN 'do'
        WHEN d.divice_name ILIKE 'ec%' THEN 'ec'
        ELSE lower(d.divice_name)
    END AS divice_name,
    m.datax_value
FROM page_data_manage_monitor m
LEFT JOIN page_data_manage_device d
    ON m.device_id = d.device_id
";

$result = pg_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(
        ["Error" => pg_last_error($conn)],
        JSON_UNESCAPED_UNICODE
    );
    exit();
}

$data = pg_fetch_all($result) ?: [];

echo json_encode($data, JSON_UNESCAPED_UNICODE);