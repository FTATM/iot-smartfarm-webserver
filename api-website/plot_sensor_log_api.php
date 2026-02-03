<?php
header("Content-Type: application/json; charset=utf-8"); 
include_once("../includes/fn/pg_connect.php");


$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$monitor_name = isset($_POST['sensorLog']) ? (string)$_POST['sensorLog'] : null;

if ($monitor_name === '') {
  echo json_encode(["status"=>"error","message"=>"monitor_name is required"], JSON_UNESCAPED_UNICODE);
  exit;
}

// 1) หา parent ด้วยชื่อ
$sql_parent = "SELECT monitor_id,monitor_name, device_id FROM page_data_manage_monitor WHERE monitor_name = $1";
$res_parent = pg_query_params($db, $sql_parent, [$monitor_name]);
$parent = pg_fetch_assoc($res_parent);

if (!$parent) {
  echo json_encode(["status"=>"error","message"=>"parent not found"], JSON_UNESCAPED_UNICODE);
  exit;
}

// 2) ดึง logs ด้วย device_id (หรือจะใช้ id แล้วแต่ schema จริง)
$device_id = $parent['device_id'];  // ถ้า data_log เก็บเป็น device_id
$sql_logs = "SELECT data_value, createtime FROM data_log WHERE device_id = $1 ORDER BY createtime DESC";
$res_logs = pg_query_params($db, $sql_logs, [$device_id]);
$logs = pg_fetch_all($res_logs) ?: [];

// รวมผลลัพธ์
$result = [
  "status" => "ok",
  "parent" => $parent,
   "logs" => $logs,
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

pg_close($db);
