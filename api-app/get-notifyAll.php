<?php

use function PHPSTORM_META\type;

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once(__DIR__ . "/../includes/fn/pg_connect.php");
include 'notify_email.php';
include 'notify_line.php';

// ✅ ใช้ตัวแปรเดียวกันกับที่เชื่อมต่อ
$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
  echo json_encode(["status" => "error", "message" => "can not connect to database"]);
  exit;
}

$sql = "SELECT * FROM page_data_manage_monitor 
        WHERE is_min = 1 OR is_max = 1 
        ORDER BY monitor_id ASC";

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

if (count($data) > 0) {

  foreach ($data as $key => $value) {
    print($value['monitor_id'] . " | " . gettype($value['is_email']) . " | " . $value['input_email'] . "\n");

    $monitorId = $value['monitor_id'];
    $isMin = $value['is_min'];
    $isMax = $value['is_max'];
    $isemail = $value['is_email'];
    $isline = $value['is_line'];
    $currentValue = $value['datax_value'];    // สมมติว่ามีคอลัมน์นี้
    $thresholdMin = $value['min_value'];    // สมมติว่ามีคอลัมน์นี้
    $thresholdMax = $value['max_value'];    // สมมติว่ามีคอลัมน์นี้

    // สร้างข้อความแจ้งเตือน
    $msg = "Monitor: $monitorId\n";

    if ($isMin == '1') {
      $msg .= "🔥 Alert: LOWER than MIN\nCurrent: $currentValue\nMin: $thresholdMin\n";
    }
    if ($isMax == '1') {
      $msg .= "🚨 Alert: HIGHER than MAX\nCurrent: $currentValue\nMax: $thresholdMax\n";
    }

    if ($isemail == '1') {
      // 🔔 ส่งอีเมล
      $res = sendEmail($value['input_email'], "Monitor Alert #$monitorId", $msg);
      echo $res;
    }

    if ($isline == '1') {
      // 🔔 ส่ง LINE Notify
      sendLineOA($value['input_line'], $msg);
    }
  }
} else {
  echo "No alert data.";
}
