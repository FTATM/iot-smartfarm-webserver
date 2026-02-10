
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
  SELECT monitor_id, monitor_name,group_id, device_id, type_id, datax_id, unit, min_value, max_value
  FROM page_data_manage_monitor
  WHERE monitor_name = ANY($1)
  ORDER BY monitor_name
";
$res_parent = pg_query_params($db, $sql_parent, ['{' . implode(',', $sensorNames) . '}']);
$parents = pg_fetch_all($res_parent) ?: [];

if (empty($parents)) {
  echo json_encode(["status" => "error", "message" => "parent not found"], JSON_UNESCAPED_UNICODE);
  exit;
}

// เตรียม group
$resultSensors = [];
$deviceIds = [];

foreach ($parents as $p) {
  $resultSensors[$p['monitor_name']] = [
    "monitor_id" => $p['monitor_id'],
    "monitor_name" => $p['monitor_name'],
    "min" => $p['min_value'],
    "max" => $p['max_value'],
    "unit" => $p['unit'],
    "logs" => []
  ];
  $groupsId = $p['group_id'];
  $deviceId = $p['device_id'];
  $typesId = $p['type_id'];
  $dataxId = $p['datax_id'];

  $sql_logs = "
    SELECT data_value, createtime
    FROM data_log
    WHERE group_id = $1 AND device_id = $2 AND type_id = $3 AND datax_id = $4
    ORDER BY createtime DESC
  ";
  $res_logs = pg_query_params(
    $db,
    $sql_logs,
    [$groupsId, $deviceId, $typesId, $dataxId]
  );

  while ($row = pg_fetch_assoc($res_logs)) {
    $resultSensors[$p['monitor_name']]['logs'][] = [
      "data_value" => $row['data_value'],
      "createtime" => $row['createtime']
    ];
  }
}





// output
echo json_encode([
  "status" => "ok",
  "sensors" => $resultSensors
], JSON_UNESCAPED_UNICODE);
exit;

pg_close($db);
