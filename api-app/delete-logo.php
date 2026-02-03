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
$sql = "DELETE FROM logos WHERE id=$1";

// ✅ เตรียมค่าพารามิเตอร์
$params = [$decode->id];

// ✅ รันคำสั่ง SQL
$result = pg_query_params($db, $sql, $params);

if ($result) {
    $path = $decode->path; // เช่น ph_copy.png
    $filePath = __DIR__ . "/../" . $path; // ตำแหน่งไฟล์จริง

    // ===== ลบไฟล์จากโฟลเดอร์ =====
    if (file_exists($filePath)) {
        unlink($filePath); // ลบไฟล์
        $fileDeleted = true;
    } else {
        $fileDeleted = false;
    }

    echo json_encode(["status" => "success", "message" => "delete success " . $decode->id]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
