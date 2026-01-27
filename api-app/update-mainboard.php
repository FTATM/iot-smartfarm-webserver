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
if (empty($decode->id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $json]);
    pg_close($db);
    exit;
}

// ✅ เตรียมคำสั่ง SQL UPDATE
$sql = "UPDATE dashboard_main SET 
        label_text = $1,
        value = $2,
        is_active = $3,
        type_values_id = $4,
        icon_id = $5,
        unitofvalue = $6
        
    WHERE id = $7
";

// ✅ เตรียมค่าพารามิเตอร์
$params = [
    $decode->label_text ?? '',
    $decode->value ?? '',
    $decode->is_active ?? '1',
    $decode->type_values_id ?? 1,
    $decode->icon_id ?? 1,
    $decode->unitofvalue ?? '',
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
