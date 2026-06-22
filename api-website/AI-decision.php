<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../services/logs/decistion-php-error-' . date('Y-m-d') . '.log');

error_reporting(E_ALL);


register_shutdown_function(function () {
  $error = error_get_last();

  if ($error !== null) {
    file_put_contents(
      __DIR__ . '/../services/logs/decision-shutdown-' . date('Y-m-d') . '.log',
      print_r($error, true),
      FILE_APPEND
    );
  }
});

date_default_timezone_set('Asia/Bangkok');

// ============================================================
// 1. เรียกได้จาก: Cron, CLI, หรือ trigger endpoint
// ============================================================
// header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: *");
require_once __DIR__ . '/../services/helper.php';

try {
  $branch_id = 1; // ทดสอบกับ branch เดียวก่อน

  // ================================ Update status ======================================
  echo json_encode(["checkpoint" => 1, "msg" => "start"]) . "\n";
  flush();
  updateScriptStatus('ai', true, 'started', 1);


  // ============================================================
  // 2.โหลดไฟล์ที่จำเป็น config, function ต่างๆ
  // ============================================================
  updateScriptStatus('ai', true, 'กำลังนำเข้าไฟล์ Config', 5);
  include_once(__DIR__ . '/../includes/fn/pg_connect.php');
  require_once __DIR__ . '/config.php';
  require_once __DIR__ . '/AI-functions.php';
  $AI_MODEL = $AI_MODE == 0 ? $AI_config['model'] : $AI_EXTERNAL_config['model'];
  echo json_encode(["checkpoint" => 2, "msg" => "config loaded"]) . "\n";
  flush();




  // ============================================================
  // 3.เช็คการเชื่อมต่อ DB
  // ============================================================
  $db = pg_connect("$host $port $dbname $credentials");
  $error = error_get_last();
  if (!$db) {
    echo json_encode(["checkpoint" => 3, "msg" => "DB connection failed", "error" => $error]) . "\n";
    flush();
    updateScriptStatus('ai', false, 'ไม่สามารถเชื่อมต่อ database โปรดเช็ค logs.', 10);
    exit;
  }



  // ============================================================
  // 3.1 เช็คการเชื่อมต่อ DB
  // ============================================================
  $ch = curl_init('http://127.0.0.1/iotsf/api-website/fetch_branch.php');

  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
  ]);

  $response = curl_exec($ch);

  if ($response === false) {
    $error = curl_error($ch);

    echo json_encode([
      "checkpoint" => 3.1,
      "msg" => "fetch branch failed",
      "error" => $error
    ]) . "\n";
    flush();

    curl_close($ch);

    updateScriptStatus('ai', false, 'ไม่สามารถดึงข้อมูล Branch ได้ โปรดเช็ค logs.', 10);
    exit;
  }

  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  if ($httpCode !== 200) {
    echo json_encode([
      "checkpoint" => 3.1,
      "msg" => "fetch branch failed",
      "error" => "HTTP Status: {$httpCode}"
    ]) . "\n";
    flush();

    updateScriptStatus('ai', false, 'ไม่สามารถดึงข้อมูล Branch ได้ โปรดเช็ค logs.', 10);
    exit;
  }

  $raw_branchs = json_decode($response, true);

  if (!is_array($raw_branchs)) {
    echo json_encode([
      "checkpoint" => 3.1,
      "msg" => "invalid response",
      "error" => "Invalid JSON response"
    ]) . "\n";
    flush();

    updateScriptStatus('ai', false, 'ข้อมูล Branch ไม่ถูกต้อง โปรดเช็ค logs.', 10);
    exit;
  }

  $branchs = $raw_branchs['data'] ?? [];

  if (empty($branchs)) {
    echo json_encode([
      "checkpoint" => 3.1,
      "msg" => "no branch found",
      "error" => "Empty branch list"
    ]) . "\n";
    flush();

    updateScriptStatus('ai', false, 'ไม่พบข้อมูล Branch', 10);
    exit;
  }

  echo json_encode(["checkpoint" => 3.1, "msg" => "db ok"]) . "\n";
  flush();
  updateScriptStatus('ai', true, 'กำลังเช็คความพร้อมของ AI', 10);

  foreach ($branchs as $row) {
    $branch_id = $row['branch_id'];

    // ============================================================
    // 3.2.เช็คโหมด AI 
    // ============================================================
    if ($AI_MODE == 0) {

      echo json_encode([
        "checkpoint" => 3.2,
        "msg"        => "Local mode does not support AI decision.",
        "error"      => "AI Decision requires External mode (AI_MODE = 1). Please upgrade to Pro for faster response."
      ]) . "\n";
      flush();
      updateScriptStatus('ai', false, 'ไม่รองรับการประมวลผล AI ในโหมดนี้', 12);

      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, "AI Decision is not available in Local mode.");
      exit;
    }

    updateScriptStatus('ai', true, 'กำลังดึงข้อมูลจากฐานข้อมูล', 15);

    // ============================================================
    // 4. ดึงค่า sensor ของ branch ที่ต้องการ
    // ============================================================
    $sensor_list = getmonitorList($branch_id);
    $sensor_list_json = json_encode($sensor_list['sensor'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $output_list_json = json_encode($sensor_list['output'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (empty($sensor_list['sensor'])) {
      echo json_encode(["checkpoint" => 4, "msg" => "no sensors found for branch", "error" => "No sensors available"]) . "\n";
      flush();
      updateScriptStatus('ai', false, 'ไม่พบอุปกรณ์ที่พร้อมใช้งาน โปรดเช็ค logs.', 37);
      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, "No sensors available");
      exit;
    }
    echo json_encode(["checkpoint" => 4, "msg" => "sensor data fetched"]) . "\n";
    flush();
    updateScriptStatus('ai', true, 'เตรียมความพร้อมของข้อมูล', 37);

    // ============================================================
    // 5. ตั้งคำถามและเก็บคำตอบจาก AI 
    // ============================================================
    $now = date('H:i');

    // $question = "ตอนนี้เวลา $now ควรรดน้ำไหมในช่วงเวลา 6 ชั่วโมงข้างหน้า? พิจารณาจากข้อมูลสภาพอากาศและความชื้น";
    $question = "ตอนนี้เวลา $now ควรเปิดหรือปิด อุปกรณ์อะไรไหม พิจารณาจากข้อมูลสภาพอากาศและข้อมูลจากsensorที่มีให้";

    $prompt = <<<PROMPT
วิเคราะห์ว่าต้องใช้ข้อมูลอะไรบ้างในการตัดสินใจ
Question: {$question}
Available Sensor: {$sensor_list_json}
Available Tools:get_weather_summary, get_history_sensor_summary, set_sensor_output
Schema of Answer:
{
  "required_sensors": [
  ],
  "required_tools": [
  ],
  "reason": ""
}
You must respond with valid JSON.
PROMPT;

    updateScriptStatus('ai', true, "กำลังรอการตอบกลับจาก AI model planner", 41);
    // ================================================================
    // 5. เรียก AI เพื่อวิเคราะห์ว่าต้องใช้ข้อมูลอะไรบ้างในการตัดสินใจ
    // ================================================================

    $ai_response = CallAI($prompt, $AI_MODE, $AI_config, $AI_EXTERNAL_config, 60);
    $raw = json_decode($ai_response, true);

    if (isset($raw['success']) && !$raw['success'] && $raw['success'] == false) {
      echo json_encode(["checkpoint" => 5, "msg" => $raw['message'], "error" => $raw['error']]) . "\n";
      flush();
      updateScriptStatus('ai', false, "เกิดข้อผิดพลาดในการตอบกลับจาก AI planner", 50);
      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, $raw['error'], $question, $prompt, $ai_response);
      exit;
    }

    $answer = json_decode($raw['message'], true);

    echo json_encode(["checkpoint" => 5, "msg" => "AI analysis done"]) . "\n";
    flush();
    updateScriptStatus('ai', true, "กำลังเรียกใช้งานเครื่องมือ", 50);

    // ============================================================
    // 6. เรียกเครื่องมือที่ AI บอกว่าต้องใช้ เพื่อดึงข้อมูลมาให้ AI ตัดสินใจอีกครั้ง
    // ============================================================
    $data_tools = callTools($branch_id, $answer['required_sensors'], $answer['required_tools']);
    if (isset($answer['required_tools']) && count($answer['required_tools']) == 0) {
      echo json_encode(["checkpoint" => 6, "msg" => "not found required tools", "error" => "AI not required Tools"]) . "\n";
      flush();
      updateScriptStatus('ai', false, "เกิดข้อผิดพลาดในการเรียกใช้งานเครื่องมือ", 65);
      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, "Fail to load tools, AI not required tools.", $question, $prompt, $ai_response);
    }
    echo json_encode(["checkpoint" => 6, "msg" => "tools called"]) . "\n";
    flush();
    updateScriptStatus('ai', true, "เตรียมความพร้อมของข้อมูล", 65);

    // ============================================================
    // 7. เตรียม prompt สำหรับให้ AI ตัดสินใจ
    // ============================================================
    $prompt_decision = <<<PROMPT
Question: {$question}
ข้อมูลจากเครื่องมือ: {$data_tools}

available output: {$output_list_json}
available tools: set_sensor_output
Schema of Answer:
{
  "required_tools": ["tools ที่ต้องใช้ถ้ามี"],
  "sensor_output": ["ชื่อ output ที่ต้องการ"],
  "status": "ON/OFF",
  "reason": "เหตุผลในการตัดสินใจ"
}
Example :
{
  "required_tools": ["set_sensor_output"],
  "sensor_output": ["VWT_1","VWT_2"],
  "status": "OFF",
  "reason": "สภาพอากาศในช่วงเวลา 6 ชั่วโมงข้างหน้าไม่มีฝน และความชื้นสัมพันธ์กันสูงกว่า 78% จึงไม่จำเป็นต้องรดน้ำ"
}
You must respond with valid JSON.
PROMPT;

    updateScriptStatus('ai', true, "กำลังรอการตอบกลับจาก AI model decision", 65);
    // ================================================================
    // 8. เรียก AI เพื่อตัดสินใจ
    // ================================================================
    $ai_decision_response = CallAI($prompt_decision, $AI_MODE, $AI_config, $AI_EXTERNAL_config, 60);
    $ai_decision_answer = json_decode($ai_decision_response, true);

    if (isset($ai_decision_answer['success']) && !$ai_decision_answer['success'] && $ai_decision_answer['success'] == false) {
      echo json_encode(["checkpoint" => 8, "msg" => $ai_decision_answer['message'], "error" => $ai_decision_answer['error']]) . "\n";
      flush();
      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, $ai_decision_answer['error'], $question, $prompt, $ai_response, $data_tools, $prompt_decision, $ai_decision_response, null);
      updateScriptStatus('ai', false, "เกิดข้อผิดพลาดในการตอบกลับจาก AI decision", 88);
      exit;
    }

    $awnser_decision = json_decode($ai_decision_answer['message'], true);

    echo json_encode(["checkpoint" => 8, "msg" => "decision made"]) . "\n";
    flush();
    updateScriptStatus('ai', true, "กำลังเรียกใช้งานเครื่องมือ", 88);
    // ================================================================
    // 9. นำผลลัพธ์จาก AI ไปตั้งค่า output จริงๆ ผ่าน callTools อีกครั้ง 
    // ================================================================
    $set_output = callTools($branch_id, null, $awnser_decision['required_tools'], $awnser_decision['sensor_output'], $awnser_decision['status']);
    $response_set_output = json_decode($set_output, true);

    if (isset($response_set_output) && !$response_set_output['success']) {
      echo json_encode(["checkpoint" => 9, "msg" => $response_set_output['message'], "error" => $response_set_output['error']]) . "\n";
      flush();
      INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 0, $response_set_output['error'], $question, $prompt, $ai_response, $data_tools, $prompt_decision, $ai_decision_response, $response_set_output);
      updateScriptStatus('ai', false, 'เกิดข้อผิดพลาดในการเรียกใช้งานเครื่องมือ', 91);
      exit;
    }
    echo json_encode(["checkpoint" => 9, "msg" => "action executed"]) . "\n";
    flush();
    updateScriptStatus('ai', true, "กำลังบันทึกการทำงาน", 91);


    // ================================================================
    // 10. บันทึกข้อมูลทั้งหมดที่ใช้ตัดสินใจไป ลง Log 
    // ================================================================
    echo json_encode(["checkpoint" => 10, "msg" => "Insert logs"]) . "\n";
    $raw_logs = INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, 'Decision', 1, "Success", $question, $prompt, $ai_response, $data_tools, $prompt_decision, $ai_decision_response, $response_set_output);
    $response_log = json_decode($raw_logs, true);

    // echo "ผลการเพิ่มเข้า log : " . $response_log['message'] . "\n";
    // ================================================================
    // 11. สรุปผลลัพธ์ทั้งหมดและปิดการเชื่อมต่อ DB
    // ================================================================
    echo json_encode(["checkpoint" => 11, "msg" => "process completed"]) . "\n";
    updateScriptStatus('ai', true, 'เสร็จสิ้นการทำงานใน branch [' . $branch_id . ']', 99);


    //end foreach loop
  }

  // เสร็จงาน
  updateScriptStatus('ai', false, 'เสร็จสิ้น', 100);
} catch (Exception $e) {

  updateScriptStatus('ai', false, 'เกิดข้อผิดพลาด : ' . $e->getMessage(), 0);
}
pg_close($db);
