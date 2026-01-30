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

$branch_id = $_POST['bid'] ?? '';
// ✅ ตรวจสอบว่ามีค่ามาครบไหม
if (empty($branch_id)) {
    echo json_encode(["status" => "error", "message" => "please input branch id"]);
    pg_close($db);
    exit;
}

$sql = "SELECT * FROM home_branch WHERE branch_id = $1 ORDER BY  home_row_id ASC";

$result = pg_query_params($db, $sql, [$branch_id]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}
$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

if ($result) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
