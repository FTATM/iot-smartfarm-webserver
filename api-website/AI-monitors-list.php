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

$branch_id = $_GET['bid'] ?? null;

if (!$branch_id) {
    echo json_encode(["status" => "error", "message" => "branch_id (bid) is required"]);
    pg_close($db);
    exit;
}

$sql = "SELECT monitor_name, type_id FROM page_data_manage_monitor WHERE branch_id = $1";

$result = pg_query_params($db, $sql, [$branch_id]);

$data = [];
while ($row = pg_fetch_assoc($result)) {

    if ($row['type_id'] == '1' || $row['type_id'] == '3') {
        $data['sensor'][] = $row["monitor_name"];
    }
    if ($row['type_id'] == '4') {
        $data['output'][] = $row["monitor_name"];
    }
}

if ($data) {
    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this branch id"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
