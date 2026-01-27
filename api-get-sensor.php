<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("includes/fn/pg_connect.php");

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$gid = $_POST['group_id'];
if (empty($gid)) {
    echo json_encode(["status" => "error", "message" => "need param group_id"]);
    exit;
}
$did = $_POST['device_id'];
if (empty($did)) {
    echo json_encode(["status" => "error", "message" => "need param device_id"]);
    exit;
}
$tid = $_POST['type_id'];
if (empty($tid)) {
    echo json_encode(["status" => "error", "message" => "need param type_id"]);
    exit;
}
$xid = $_POST['datax_id'];
if (empty($xid)) {
    echo json_encode(["status" => "error", "message" => "need param datax_id"]);
    exit;
}


// // ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql = "SELECT * FROM page_data_manage_monitor WHERE group_id = $1 AND device_id = $2 AND type_id = $3 AND datax_id = $4";

$result = pg_query_params($db, $sql, [$gid, $did, $tid, $xid]);

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
