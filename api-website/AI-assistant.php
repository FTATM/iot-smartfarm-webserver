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
$input   = json_decode(file_get_contents("php://input"), true);
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

// ============================================================
// 👉 เรียก AI ตาม Mode
// ============================================================
if ($AI_MODE === 0) {
    // ─── Local Ollama ───────────────────────────────────────
    $request_body = json_encode([
        "model"  => $AI_config['model'],
        "prompt" => $prompt,
        "stream" => false,
        "format" => "json",
    ]);

    $ch = curl_init($AI_config['api_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $request_body,
        CURLOPT_HTTPHEADER     => ["Content-Type: application/json"],
        CURLOPT_TIMEOUT        => 120,
    ]);
    $response    = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    $reply   = $decoded['response'] ?? '';

} else {
    // ─── External API (Groq / OpenAI-compatible) ────────────
    $request_body = json_encode([
        "model"           => $AI_EXTERNAL_config['model'],
        "temperature"     => 0.1,
        "messages"        => [
            ["role" => "user", "content" => $prompt]
        ],
        "response_format" => ["type" => "json_object"],
    ]);

    $ch = curl_init($AI_EXTERNAL_config['api_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $request_body,
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "Authorization: Bearer {$AI_EXTERNAL_config['api_key']}",
        ],
        CURLOPT_TIMEOUT        => 60,
    ]);
    $response    = curl_exec($ch);
    curl_close($ch);

    $decoded = json_decode($response, true);
    $reply   = $decoded['choices'][0]['message']['content'] ?? '';
}

// ============================================================
// 👉 Response
// ============================================================
echo json_encode([
    "status" => "success",
    "data"   => [
        "reply" => trim($reply)
    ]
], JSON_UNESCAPED_UNICODE);

pg_close($db);