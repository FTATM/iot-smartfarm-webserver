<?php
// ============================================================
// 1. เรียกได้จาก: Cron, CLI, หรือ trigger endpoint
// ============================================================
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
echo json_encode(["checkpoint" => 1, "msg" => "start"]) . "\n";
flush();

// ============================================================
// 2.โหลดไฟล์ที่จำเป็น config, function ต่างๆ
// ============================================================
include_once(__DIR__ . '/../includes/fn/pg_connect.php');
require_once 'config.php';
require_once 'AI-functions.php';
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
  exit;
} else {
  echo json_encode(["checkpoint" => 3, "msg" => "db ok"]) . "\n";
  flush();
}

// ============================================================
// 4. ดึงค่า sensor ของ branch ที่ต้องการ
// ============================================================
$branch_id = 3; // ทดสอบกับ branch เดียวก่อน
$sensor_list = getmonitorList($branch_id);

$sensor_list_json = json_encode($sensor_list['sensor'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$output_list_json = json_encode($sensor_list['output'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if (empty($sensor_list['sensor'])) {
  echo json_encode(["checkpoint" => 4, "msg" => "no sensors found for branch", "error" => "No sensors available"]) . "\n";
  flush();
  INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, "No sensors available");
  exit;
} else {
  echo json_encode(["checkpoint" => 4, "msg" => "sensor data fetched"]) . "\n";
  flush();
}

// ============================================================
// 5. ตั้งคำถามและเก็บคำตอบจาก AI 
// ============================================================

$question = "ตอนนี้เวลา 06.00 ควรรดน้ำไหมในช่วงเวลา 6 ชั่วโมงข้างหน้า? พิจารณาจากข้อมูลสภาพอากาศและความชื้น";

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

// ================================================================
// 5. เรียก AI เพื่อวิเคราะห์ว่าต้องใช้ข้อมูลอะไรบ้างในการตัดสินใจ
// ================================================================

$ai_response = CallAI($prompt);
$raw = json_decode($ai_response, true);

if (isset($raw['success']) && !$raw['success'] && $raw['success'] == false) {
  echo json_encode(["checkpoint" => 5, "msg" => $raw['message'], "error" => $raw['error']]) . "\n";
  flush();
  INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, $question, $prompt, $raw['error']);
  exit;
}

$answer = json_decode($raw['message'], true);

echo json_encode(["checkpoint" => 5, "msg" => "AI analysis done"]) . "\n";
flush();

// ============================================================
// 6. เรียกเครื่องมือที่ AI บอกว่าต้องใช้ เพื่อดึงข้อมูลมาให้ AI ตัดสินใจอีกครั้ง
// ============================================================
$data_tools = callTools($branch_id, $answer['required_sensors'], $answer['required_tools']);
if (isset($answer['required_tools']) && count($answer['required_tools']) == 0) {
  echo json_encode(["checkpoint" => 6, "msg" => "not found required tools", "error" => "AI not required Tools"]) . "\n";
  flush();
}
echo json_encode(["checkpoint" => 6, "msg" => "tools called"]) . "\n";
flush();

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
You must respond with valid JSON.
PROMPT;

echo json_encode(["checkpoint" => 7, "msg" => "prompt for decision ready"]) . "\n";
flush();
// ================================================================
// 8. เรียก AI เพื่อตัดสินใจ
// ================================================================
$ai_decision_response = CallAI($prompt_decision);
$ai_decision_answer = json_decode($ai_decision_response, true);

if (isset($ai_decision_answer['success']) && !$ai_decision_answer['success'] && $ai_decision_answer['success'] == false) {
  echo json_encode(["checkpoint" => 8, "msg" => $ai_decision_answer['message'], "error" => $ai_decision_answer['error']]) . "\n";
  flush();
  INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, $question, $prompt, $raw, $data_tools, $prompt_decision, $ai_decision_answer, null);
  exit;
}

$awnser_decision = json_decode($ai_decision_answer['message'], true);

echo json_encode(["checkpoint" => 8, "msg" => "decision made"]) . "\n";
flush();
// ================================================================
// 9. นำผลลัพธ์จาก AI ไปตั้งค่า output จริงๆ ผ่าน callTools อีกครั้ง 
// ================================================================
$set_output = callTools($branch_id, null, $awnser_decision['required_tools'], $awnser_decision['sensor_output'], $awnser_decision['status']);
$response_set_output = json_decode($set_output, true);

if (isset($response_set_output) && !$response_set_output['success']) {
  echo json_encode(["checkpoint" => 9, "msg" => $response_set_output['message'], "error" => $response_set_output['error']]) . "\n";
  flush();
  INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, $question, $prompt, $raw, $data_tools, $prompt_decision, $ai_decision_answer, $response_set_output);
  exit;
}
echo json_encode(["checkpoint" => 9, "msg" => "action executed"]) . "\n";
flush();


// ================================================================
// 10. บันทึกข้อมูลทั้งหมดที่ใช้ตัดสินใจไป ลง Log 
// ================================================================
echo json_encode(["checkpoint" => 10, "msg" => "Insert logs"]) . "\n";
$raw_logs = INTO_log($db, $branch_id, $AI_MODE, $AI_MODEL, $question, $prompt, $ai_response, $data_tools, $prompt_decision, $ai_decision_response, $response_set_output);
$response_log = json_decode($raw_logs, true);

echo "ผลการเพิ่มเข้า log : " . $response_log['message'] . "\n";
// ================================================================
// 11. สรุปผลลัพธ์ทั้งหมดและปิดการเชื่อมต่อ DB
// ================================================================
echo json_encode(["checkpoint" => 11, "msg" => "process completed"]) . "\n";
pg_close($db);
