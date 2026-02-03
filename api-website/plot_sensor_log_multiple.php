
<?php
header("Content-Type: application/json; charset=utf-8"); 
include_once("../includes/fn/pg_connect.php");


$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$sensorNames = $input['sensorLog'] ?? [];

// validate
if (!is_array($sensorNames) || empty($sensorNames)) {
  echo json_encode([
    "status" => "error",
    "message" => "sensorLog must be array"
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

// 1) หา parent ทั้งหมด
$sql_parent = "
  SELECT monitor_id, monitor_name, device_id
  FROM page_data_manage_monitor
  WHERE monitor_name = ANY($1)
";
$res_parent = pg_query_params($db,$sql_parent,['{' . implode(',', $sensorNames) . '}']);
$parents = pg_fetch_all($res_parent) ?: [];

if (empty($parents)) {
  echo json_encode(["status"=>"error","message"=>"parent not found"], JSON_UNESCAPED_UNICODE);
  exit;
}

// เตรียม group
$resultSensors = [];
$deviceIds = [];

foreach ($parents as $p) {
  $resultSensors[$p['monitor_name']] = [
    "monitor_id" => $p['monitor_id'],
    "device_id"  => $p['device_id'],
    "logs" => []
  ];
  $deviceIds[] = $p['device_id'];
}

// 2) ดึง logs ทุก device ทีเดียว
$sql_logs = "
  SELECT dl.data_value, dl.createtime, pm.monitor_name
  FROM data_log dl
  JOIN page_data_manage_monitor pm ON pm.device_id = dl.device_id
  WHERE dl.device_id = ANY($1)
  ORDER BY dl.createtime DESC
";
$res_logs = pg_query_params(
  $db,
  $sql_logs,
  ['{' . implode(',', $deviceIds) . '}']
);

while ($row = pg_fetch_assoc($res_logs)) {
  $resultSensors[$row['monitor_name']]['logs'][] = [
    "data_value" => $row['data_value'],
    "createtime" => $row['createtime']
  ];
}

// output
echo json_encode([
  "status" => "ok",
  "sensors" => $resultSensors
], JSON_UNESCAPED_UNICODE);
exit;

pg_close($db);
