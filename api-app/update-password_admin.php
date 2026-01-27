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
$h_password = md5($decode->new_password);

// ✅ ตรวจสอบว่ามี monitor_id หรือไม่
if (empty($decode->id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $json]);
    pg_close($db);
    exit;
}
$now = date('Y-m-d H:i:s');

// ✅ เตรียมคำสั่ง SQL UPDATE
$sql = "UPDATE user_account SET  password = $1, updatetime = $2 WHERE id = $3 ";

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $h_password,
    $now,
    $decode->id
];

// ✅ รันคำสั่ง SQL
$result = pg_query_params($db, $sql, $params);

if ($result) {
    echo json_encode(["status" => "success", "message" => "update success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
