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
if (empty($decode->monitor_id)) {
    echo json_encode(["status" => "error", "message" => "missing monitor_id"]);
    pg_close($db);
    exit;
}

// ✅ เตรียมคำสั่ง SQL UPDATE
$sql = "UPDATE weathers SET url_api = $1, lat = $2, lon = $3, token = $4 WHERE id = $5";

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $decode->url_api ?? "https://example.com/api",
    $decode->lat ?? 7.20,
    $decode->lon ?? 100.2134,
    $decode->token ?? "",
    $decode->id ?? 1
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
