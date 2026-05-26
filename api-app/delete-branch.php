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
if (empty($decode->branch_id)) {
    echo json_encode(["status" => "error", "message" => "missing id", "data" => $json]);
    pg_close($db);
    exit;
}
pg_query($db, "BEGIN");

try {

    // ลบจาก branch_info
    $sql = "DELETE FROM branch_info WHERE branch_id = $1";
    $result = pg_query_params($db, $sql, [$decode->branch_id]);

    if (!$result) {
        throw new Exception("Delete branch_info failed");
    }

    // ลบจาก home_branch
    $sql_homebranch = "DELETE FROM home_branch WHERE branch_id = $1";
    $result = pg_query_params($db, $sql_homebranch, [$decode->branch_id]);

    if (!$result) {
        throw new Exception("Delete home_branch failed");
    }

    // ✅ ยืนยันการทำงาน
    pg_query($db, "COMMIT");
} catch (Exception $e) {

    // ❌ ยกเลิกทั้งหมดถ้ามี error
    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
}

if ($result) {
    echo json_encode(["status" => "success", "message" => "deleted success"]);
} else {
    echo json_encode(["status" => "error", "message" => pg_last_error($db)]);
}

pg_close($db);
// ✅ ปิดการเชื่อมต่อ
