<?php
include_once("includes/fn/pg_connect.php");
require 'api-app/notify_email.php';
require 'api-app/notify_line.php';



$jsonInput = file_get_contents('php://input');

$txt = "************************************\n";
$txt .= "START TIME\t" . strtotime(date("Y-m-d H:i:s")) . "\n";
$txt .= "DATETIME\t " . date('Y-m-d H:i:s') . "\n";
$txt .= "INPUT METHOD\t" . $_SERVER["REQUEST_METHOD"] . "\n";
$txt .= "INPUT\t" . $jsonInput . "\n";
$txt .= "REMOTE IP\t" . getClientIP() . "\n";

// $log_file = '/home/www/tikkyddns/iot-demo/logs/logs_';

// $myfile = file_put_contents($log_file . date("Ymd") . '.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);


$_POST = json_decode($jsonInput, true);

$data_sim_type = "1";
$_POST['data_sim_type'] = '1';
if (isset($_POST['data_sim_type']) && $_POST['data_sim_type'] != "") {
  $data_sim_type = $_POST['data_sim_type'];
} else {
  $msg = "Param data_sim_type is NULL";
  $respose = array('ret' => '102', 'msg' => $msg);
  echo json_encode($respose);
  exit;
}

if ($data_sim_type == '1') {
  $group_id = "";
  if (isset($_POST['group_id']) && $_POST['group_id'] != "") {
    $group_id = $_POST['group_id'];
  } else {
    $msg = "Param group_id is NULL";
    $respose = array('ret' => '105', 'msg' => $msg, 'post' => $_POST);
    echo json_encode($respose);
    exit;
  }
  $type_id = "";
  if (isset($_POST['type_id']) && $_POST['type_id'] != "") {
    $type_id = $_POST['type_id'];
  } else {
    $msg = "Param type_id is NULL";
    $respose = array('ret' => '106', 'msg' => $msg);
    echo json_encode($respose);
    exit;
  }
  $device_id = "";
  if (isset($_POST['device_id']) && $_POST['device_id'] != "") {
    $device_id = $_POST['device_id'];
  } else {
    $msg = "Param device_id is NULL";
    $respose = array('ret' => '106', 'msg' => $msg);
    echo json_encode($respose);
    exit;
  }
  $datax_id = "";
  if (isset($_POST['datax_id']) && $_POST['datax_id'] != "") {
    $datax_id = $_POST['datax_id'];
  } else {
    $msg = "Param datax_id is NULL";
    $respose = array('ret' => '107', 'msg' => $msg);
    echo json_encode($respose);
    exit;
  }
  $data_value = "";
  if (isset($_POST['data_value']) && $_POST['data_value'] != "") {
    $data_value = $_POST['data_value'];
  } else {
    $msg = "Param data_value is NULL";
    $respose = array('ret' => '108', 'msg' => $msg);
    echo json_encode($respose);
    exit;
  }
  $db = pg_connect("$host $port $dbname $credentials");
  if (!$db) {
    $msg = "Error : Unable to open database";
    $respose = array('ret' => '100', 'msg' => $msg);
    echo json_encode($respose);
    exit;
  }

  $sql_device = "SELECT device_id, divice_name FROM page_data_manage_device where device_id='{$device_id}';";
  $rs_device   = select($sql_device, $db);
  if (count($rs_device) != '1') {
    $msg = "device_id is wrong" . $sql_device;
    $respose = array('ret' => '109', 'msg' => $msg);
    echo json_encode($respose);
    pg_close($db);
    exit;
  }
  $divice_name = $rs_device[0]->divice_name;

  $sql_datax = "SELECT datax_id, datax_name FROM page_data_manage_datax where datax_id='{$datax_id}';";
  $rs_datax   = select($sql_datax, $db);
  if (count($rs_datax) != '1') {
    $msg = "datax_id is wrong";
    $respose = array('ret' => '1099', 'msg' => $msg);
    echo json_encode($respose);
    pg_close($db);
    exit;
  }
  $datax_name = $rs_datax[0]->datax_name;

  $sql_group = "SELECT group_id, group_name, value_map_volte_censor FROM page_data_manage_group WHERE group_id='{$group_id}';";
  $rs_group   = select($sql_group, $db);
  if (count($rs_group) != '1') {
    $msg = "group_id is wrong";
    $respose = array('ret' => '110', 'msg' => $msg);
    echo json_encode($respose);
    pg_close($db);
    exit;
  }
  $value_map_volte_censor = $rs_group[0]->value_map_volte_censor;
  //'page1_sql_type=1'+'&monitor_id='+elm_index+'&config_value_1='+ slide_val+'&data_location='+data_location+'&data_name='+data_name
  $insert_volte = "INSERT INTO volte_censor
                      (create_uid, write_uid, name, location, date_mornitor, create_date, write_date, volte, sensor, unit, value, voltemax, \"" . strtoupper($datax_name) . "\")
                      VALUES 
                      ('4', '4', '{$divice_name}', '{$value_map_volte_censor}', '" . date('Y-m-d') . "', now(), now(), '0', '1', '0', '0', '0', '{$data_value}');";
  $rs_insert_volte = execute($insert_volte, $db);
  if (!$rs_insert_volte) {
    $msg = "insert volte fail";
    $respose = array('ret' => '108', 'msg' => $msg);
    echo json_encode($respose);
    pg_close($db);
    exit;
  } else {
    $sql_update = " UPDATE page_data_manage_monitor SET datax_value = '{$data_value}' WHERE group_id = '{$group_id}' AND device_id = '{$device_id}' AND type_id = '{$type_id}' AND datax_id = '{$datax_id}'";
    $rs_sql_update = execute($sql_update, $db);
    $sql_insert_log = " INSERT INTO data_log (group_id, device_id, type_id, datax_id, data_value, createtime ) VALUES ('{$group_id}','{$device_id}','{$type_id}','{$datax_id}','{$data_value}',NOW())";
    $rs_sql_insert_log = execute($sql_insert_log, $db);

    if ($rs_sql_update) {
      $sql_select = "SELECT monitor_id, is_min, is_max, min_value, max_value, is_email, input_email, is_line, input_line FROM page_data_manage_monitor WHERE (is_min = 1 OR is_max = 1) ORDER BY monitor_id ASC";
      $result = pg_query($db, $sql_select);

      if (!$result) {
        echo json_encode([
          "status" => "error",
          "message" => pg_last_error($db)
        ]);
        exit;
      }

      while ($row = pg_fetch_assoc($result)) {

        $monitorId    = $row['monitor_id'];
        $currentValue = $data_value;
        $minValue     = (float)$row['min_value'];
        $maxValue     = (float)$row['max_value'];

        $isMin   = $row['is_min'] == '1';
        $isMax   = $row['is_max'] == '1';
        $isEmail = $row['is_email'] == '1';
        $isLine  = $row['is_line'] == '1';

        $alert = false;
        $msg = "📊 Monitor ID: {$monitorId}\n";
        $msg .= "Current Value: {$currentValue}\n";

        // 🔽 ต่ำกว่า MIN
        if ($isMin && $currentValue <= $minValue) {
          $alert = true;
          $msg .= "🔥 ALERT: ต่ำกว่าค่าต่ำสุด\n";
          $msg .= "MIN: {$minValue}\n";
        }

        // 🔼 สูงกว่า MAX
        if ($isMax && $currentValue >= $maxValue) {
          $alert = true;
          $msg .= "🚨 ALERT: สูงกว่าค่าสูงสุด\n";
          $msg .= "MAX: {$maxValue}\n";
        }

        // ❌ ไม่เข้าเงื่อนไข → ข้าม
        if (!$alert) {
          continue;
        }

        // -------------------------
        // 🔔 แจ้งเตือน
        // -------------------------

        if ($isEmail && !empty($row['input_email'])) {
          sendEmail(
            $row['input_email'],
            "Monitor Alert #{$monitorId}",
            $msg
          );
        }

        if ($isLine && !empty($row['input_line'])) {
          sendLineOA($row['input_line'], $msg);
        }
      }
    }
  }
}

$msg = "success";
$respose = array('ret' => '101', 'msg' => $msg);
echo json_encode($respose);

pg_close($db);


function getClientIP()
{
  // Cloudflare-specific header
  if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    return $_SERVER['HTTP_CF_CONNECTING_IP'];
  }

  // Standard headers for proxies
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    // HTTP_X_FORWARDED_FOR can contain multiple IPs (comma-separated), get the first one
    $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    return trim($ipList[0]);
  }

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  }

  // Fallback to REMOTE_ADDR
  return $_SERVER['REMOTE_ADDR'];
}
