<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// ✅ รับค่าจาก Flutter หรือ Postman
$branch_id = $_GET['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($branch_id)) {
    echo json_encode(["status" => "error", "message" => "please input branch id"]);
    pg_close($db);
    exit;
}

// // ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT * FROM page_data_manage_monitor
        WHERE branch_id = $1 AND status = '1'
        ORDER BY monitor_id DESC
        ";


$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}


$data = [];

$data = [];

while ($row = pg_fetch_assoc($result)) {
    $row['list_time'] = [];   // 👈 เตรียม child
    $data[] = $row;
}

foreach ($data as $index => $monitor) {

    $sql_listTime = "SELECT * FROM page_data_manage_monitor_sub WHERE monitor_id = $1";

    $result_Time = pg_query_params($db, $sql_listTime, [$monitor['monitor_id']]);

    while ($row = pg_fetch_assoc($result_Time)) {
        $data[$index]['list_time'][] = $row;
    }
}


if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
