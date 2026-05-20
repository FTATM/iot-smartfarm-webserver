<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");
require_once 'config.php';

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// ============================================================
// 👉 เช็ค parameter ที่จำเป็น
// ============================================================
if (empty($_SESSION['branch_id'])) {
    echo json_encode(["status" => "error", "message" => "ยังไม่ได้เข้าสู่ระบบ กรุณา Login ก่อน"]);
    // exit;
}

// รับ JSON
$input = json_decode(file_get_contents("php://input"), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(["status" => "error", "message" => "empty message"]);
    exit;
}

// ============================================================
// 👉 ดึงข้อมูลจาก Database ผ่าน API
// ============================================================
$bid = $_SESSION['branch_id'] ?? 2;

$ch_db = curl_init("http://localhost/iotsf/api-app/fetch-tableknowledge.php");
curl_setopt_array($ch_db, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(["bid" => $bid]),
    CURLOPT_TIMEOUT => 30,
]);
$db_response = curl_exec($ch_db);
$db_curl_error = curl_error($ch_db);
curl_close($ch_db);

if (!$db_response) {
    echo json_encode(["status" => "error", "message" => "fetch db failed: $db_curl_error"]);
    exit;
}

$db_data = json_decode($db_response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "invalid json from db api", "raw" => $db_response]);
    exit;
}

$data_from_db = json_encode($db_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);



// 👉 Build Prompt
$prompt = "SYSTEM:\n"
    . "You are a helpful AI assistant.\n"
    . "You MUST answer with ONLY valid JSON.\n\n"
    . "RULES:\n"
    . "- Output ONLY valid JSON\n"
    . "- Answer Only thai language\n"
    . "- No markdown\n"
    . "- No explanation outside JSON\n"
    . "- If unsure, answer UNKNOWN\n"
    . "- Keep answer concise\n"
    . "- You have access to the following data from my database. Use this data to answer the user's question.\n\n"
    . "DATA FROM MY DATABASE:\n"
    . $data_from_db . "\n\n"
    . "SCHEMA (ห้ามเพิ่ม key อื่น ห้ามห่อใน array):\n"
    . "{\n"
    . "  \"topic\": \"string\",\n"
    . "  \"answer\": \"string\",\n"
    . "  \"confidence\": 0-100\n"
    . "}\n\n"
    . "ตัวอย่าง OUTPUT ที่ถูกต้อง:\n"
    . "{\"topic\":\"สภาพอากาศ\",\"answer\":\"วันนี้อากาศดี\",\"confidence\":90}\n\n"
    . "USER QUESTION:\n"
    . $message . "\n\n"
    . "OUTPUT:";

    echo $prompt;
// ============================================================
// 👉 เรียก AI ตาม Mode
// ============================================================
// if ($AI_MODE === 0) {
//     // ─── Local Ollama ───────────────────────────────────────
//     $request_body = json_encode([
//         "model" => $AI_config['model'],
//         "prompt" => $prompt,
//         "stream" => false,
//         "format" => "json",
//         "options" => [
//             "temperature" => 0.1,
//             "num_thread" => 6,  // ใช้ทุก thread (4 core = 8 thread)
//             "num_ctx" => 8192,
//             "num_predict" => 2048,
//         ]
//     ]);

//     $ch = curl_init($AI_config['api_url']);
//     curl_setopt_array($ch, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_POST => true,
//         CURLOPT_POSTFIELDS => $request_body,
//         CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
//         CURLOPT_TIMEOUT => 0,
//     ]);
//     $response = curl_exec($ch);
//     $curl_error = curl_error($ch);
//     curl_close($ch);

//     if (!$response) {
//         echo json_encode(["status" => "error", "message" => "curl failed: $curl_error"]);
//         exit;  // ✅ ต้อง exit
//     }

//     $decoded = json_decode($response, true);

//     if (json_last_error() !== JSON_ERROR_NONE) {
//         echo json_encode(["status" => "error", "message" => "invalid json from AI", "raw" => $response]);
//         exit;  // ✅ ต้อง exit
//     }

//     $reply = $decoded['response'] ?? '';  // ✅ restore บรรทัดนี้กลับมา

// } else {
//     // ─── External API (Groq / OpenAI-compatible) ────────────
//     $request_body = json_encode([
//         "model" => $AI_EXTERNAL_config['model'],
//         "temperature" => 0.1,
//         "messages" => [
//             ["role" => "user", "content" => $prompt]
//         ],
//         "response_format" => ["type" => "json_object"],
//     ]);

//     $ch = curl_init($AI_EXTERNAL_config['api_url']);
//     curl_setopt_array($ch, [
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_POST => true,
//         CURLOPT_POSTFIELDS => $request_body,
//         CURLOPT_HTTPHEADER => [
//             "Content-Type: application/json",
//             "Authorization: Bearer {$AI_EXTERNAL_config['api_key']}",
//         ],
//         CURLOPT_TIMEOUT => 60,
//     ]);
//     $response = curl_exec($ch);
//     curl_close($ch);

//     $decoded = json_decode($response, true);

//     $reply = $decoded['choices'][0]['message']['content'] ?? $decoded['error']['message'] ?? 'UNKNOWN';  // ✅ restore บรรทัดนี้กลับมา
// }

// // ============================================================
// // 👉 Response
// // ============================================================
// echo json_encode([
//     "status" => "success",
//     "data" => [
//         "reply" => trim($reply)
//     ]
// ], JSON_UNESCAPED_UNICODE);

pg_close($db);
