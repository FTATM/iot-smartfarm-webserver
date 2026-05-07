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
// 👉 Build Prompt
$prompt = "SYSTEM:\n"
        . "You are a helpful AI assistant.\n"
        . "You MUST answer with ONLY valid JSON.\n\n"
        . "RULES:\n"
        . "- Output ONLY valid JSON\n"
        . "- Output ONLY short Answer\n"
        . "- Answer Only thai language\n"
        . "- No markdown\n"
        . "- No explanation outside JSON\n"
        . "- If unsure, answer UNKNOWN\n"
        . "- Keep answer concise\n\n"
        . "SCHEMA:\n"
        . "{\n"
        . "  \"topic\": \"string\",\n"
        . "  \"answer\": \"string\",\n"
        . "  \"confidence\": 0-100\n"
        . "}\n\n"
        . "USER QUESTION:\n"
        . $message . "\n\n"
        . "OUTPUT:";

// 👉 เรียก Ollama
$data = [
    "model"  => $AI_config['model'],
    "prompt" => $prompt,
    "stream" => false,
    "format" => "json"
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