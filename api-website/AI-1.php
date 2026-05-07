<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");
require_once 'config.php';

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// รับ JSON
$input = json_decode(file_get_contents("php://input"), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(["status" => "error", "message" => "empty message"]);
    exit;
}

// 👉 Prompt แบบ System + JSON Schema
$prompt = "
SYSTEM:
You are an agricultural assistant AI.
You MUST answer with ONLY valid JSON.

RULES:
- Output ONLY valid JSON
- Output ONLY short Answer
- Answer ONLY Thai language
- No markdown
- No explanation outside JSON
- If unsure, answer UNKNOWN
- Keep answer concise
- Confidence must be integer 0-100

SCHEMA:
{
  \"topic\": \"string\",
  \"answer\": \"string\",
  \"days_estimate\": \"string\",
  \"confidence\": 0-100
}

USER QUESTION:
$message

OUTPUT:
";

// 👉 เรียก Ollama
$data = [
    "model" => $AI_config['model'],
    "stream" => false,
    "format" => "json",
    "options" => [
        "temperature" => 0.2,
        "top_p" => 0.9
    ],
    "prompt" => $prompt
];

$ch = curl_init($AI_config['api_url']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

$res = json_decode($response, true);

// ดึงข้อความจาก AI
$reply = $res['response'] ?? '';

echo json_encode([
    "status" => "success",
    "data" => [
        "reply" => trim($reply)
    ]
], JSON_UNESCAPED_UNICODE);

// ปิด DB
pg_close($db);