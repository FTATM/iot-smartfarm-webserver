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

$name = $_GET['name'];
$start_time = $_GET['start_time'] ?? null;

if (!$name) {
    echo json_encode(["status" => "error", "message" => "require parameter name."]);
    exit;
}

if (!$start_time) {
    echo json_encode(["status" => "error", "message" => "require parameter start_time."]);
    exit;
}

// ตรวจสอบรูปแบบเวลา (YYYY-MM-DD HH:MM:SS)
if (!preg_match("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $start_time)) {
    echo json_encode(["status" => "error", "message" => "invalid datetime format. use YYYY-MM-DD HH:MM:SS"]);
    exit;
}

// ✅ เขียน SQL (ใช้ pg_query_params เพื่อป้องกัน SQL Injection)
$sql_specified = "SELECT * FROM page_data_manage_specified WHERE name = $1 LIMIT 1";

$result_spec = pg_query_params($db, $sql_specified, [$name]);

if (!$result_spec) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

$sensor = pg_fetch_assoc($result_spec);

$gid = $sensor['group_id'];
$did = $sensor['device_id'];
$tid = $sensor['type_id'];
$xid = $sensor['datax_id'];


$sql_logs = "SELECT
            (
                SELECT data_value
                FROM data_log
                WHERE group_id = $1
                AND device_id = $2
                AND type_id = $3
                AND datax_id = $4
                AND createtime >= $5::date
                AND createtime <  ($5::date + INTERVAL '1 day')
                ORDER BY createtime DESC
                LIMIT 1
            )
            -
            (
                SELECT data_value
                FROM data_log
                WHERE group_id = $1
                AND device_id = $2
                AND type_id = $3
                AND datax_id = $4
                AND createtime >= $5::date
                AND createtime <  ($5::date + INTERVAL '1 day')
                ORDER BY createtime ASC
                LIMIT 1
            )
            AS diff;
            ";

$result_log = pg_query_params($db, $sql_logs, [$gid, $did, $tid, $xid, $start_time]);

if (!$result_log) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

$data = [];

while ($row = pg_fetch_assoc($result_log)) {
    $data[] = $row;
    // $data[] = $row;
}

if (count($data) > 0) {
    echo json_encode(["status" => "success", "data" => array_values($data)], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "data" => [], "message" => "no data found for this name"]);
}

// ✅ ปิดการเชื่อมต่อ
pg_close($db);
