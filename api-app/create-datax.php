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


// ✅ เตรียมคำสั่ง SQL INSERT
$sql = "INSERT INTO page_data_manage_datax ( datax_name, sort, status, branch_id, create_time, update_time ) VALUES ($1,$2,$3,$4,$5,$6)";

$now = date('Y-m-d H:i:s'); // แปลงเป็น string

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $decode->newname ?? "New Datax",
    $decode->sort ?? 0,
    $decode->status ?? 1,
    $decode->branch_id ?? 1,
    $now,
    $now
];

// ✅ รันคำสั่ง SQL
$result = pg_query_params($db, $sql, $params);

if ($result) {
    echo json_encode(["status" => "success", "message" => "create success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
