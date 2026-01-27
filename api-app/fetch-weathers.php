<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "cannot connect to database"]);
    exit;
}

$sql = "SELECT * FROM weathers";
$result = pg_query($db, $sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

$data = [];

while ($row = pg_fetch_assoc($result)) {
    $data[] = $row;
}

// ✅ ตรวจสอบผลลัพธ์
if (pg_num_rows($result) > 0) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "message" => "username or password wrong!"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
