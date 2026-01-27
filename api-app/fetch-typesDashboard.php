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
// $branch_id = $_POST['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
// if (empty($branch_id)) {
//     echo json_encode(["status" => "error", "message" => "please input branch id"]);
//     pg_close($db);
//     exit;
// }

// // ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT * FROM dashboard_type ORDER BY id ASC ";

$result = pg_query_params($db, $sql, []);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}
$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
    // $data[] = $row;
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
