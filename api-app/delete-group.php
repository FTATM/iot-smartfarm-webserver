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
$json = $_POST['json'] ?? '';
// $json = file_get_contents("php://input");
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($json)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    pg_close($db);
    exit;
}

$decode = json_decode($json);

// ✅ ตรวจสอบว่ามี monitor_id หรือไม่
if (empty($decode->group_id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $json]);
    pg_close($db);
    exit;
}

$sql = "DELETE FROM page_data_manage_group WHERE group_id = $1";

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $decode->group_id
];

// ✅ รันคำสั่ง SQL
$result = pg_query_params($db, $sql, $params);

if ($result) {
    echo json_encode(["status" => "success", "message" => "deleted success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
