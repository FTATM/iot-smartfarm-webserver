<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
echo json_encode(["checkpoint" => 1, "msg" => "start"]);
flush();

require_once 'config.php';
echo json_encode(["checkpoint" => 2, "msg" => "config loaded"]);
flush();
// ============================================================
// 4. Build Prompt รวมทุก branch ในครั้งเดียว
// ============================================================
$current_time = date('Y-m-d H:i:s');

$prompt = <<<PROMPT
SYSTEM:
คุณคือระบบ AI ตัดสินใจควบคุม Sensor อัตโนมัติ แยกตาม branch
เวลาปัจจุบัน: {$current_time}

FIELD forecast: cond(1=แจ่มใส...8=ฝนฟ้าคะนอง), rain(มม./ชม.), rh(%), tc(°C)
เกณฑ์ฝน: cond >= {$TMD_config['rain_cond_threshold']} หรือ rain > 0

กฎการตัดสินใจ (ทุก branch ใช้กฎเดียวกัน):
1. มีฝน → action: STOP
2. ไม่มีฝน + อยู่ในช่วง start_work/end_work → action: RUN
3. ไม่มีฝน + นอกช่วงเวลา หรือ list_time ว่าง → action: IDLE
4. list_time_of_work คือวันที่ทำงาน (0=อาทิตย์...6=เสาร์)
   วันปัจจุบัน: {$current_time} ตรวจสอบด้วยว่าวันนี้อยู่ใน list_time_of_work ไหม

ตอบ JSON เท่านั้น

SCHEMA:
{
  "weather_assessment": {
    "will_rain": true|false,
    "max_rain_mm": number,
    "overall_condition": "string"
  },
  "branches": {
    "<branch_id>": {
      "summary": "string",
      "sensor_decisions": [
        {
          "monitor_id": "string",
          "monitor_name": "string",
          "action": "RUN|STOP|IDLE",
          "reason": "string"
        }
      ]
    }
  },
  "confidence": 0-100
}

ข้อมูล SENSOR แยกตาม branch_id:
{$sensors_json}

ข้อมูลพยากรณ์อากาศ TMD:
{$forecast_json}

OUTPUT:
PROMPT;

// ============================================================
// 5. เรียก AI ครั้งเดียว (รองรับ Local Ollama + External API)
// ============================================================
$prompt_size = strlen($prompt);
$token_est = (int) ($prompt_size / 4);
echo json_encode(["checkpoint" => 7, "prompt_chars" => $prompt_size, "tokens_est" => $token_est]);
flush();

if ($AI_MODE === 0) {
    // ─── Local Ollama ───────────────────────────────────────
    $request_body = json_encode([
        "model" => $AI_config['model'],
        "prompt" => $prompt,
        "stream" => false,
        "format" => "json",
        "options" => [
            "temperature" => 0.1,
            "num_thread" => 7,
            "num_ctx" => 8192,
            "num_predict" => 2048,
        ],
    ]);

    $ch = curl_init($AI_config['api_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $request_body,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_TIMEOUT => 3600,
    ]);
    $ai_response = curl_exec($ch);
    $ai_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ai_curl_err = curl_error($ch);
    curl_close($ch);

    // Parse Ollama response
    $ai_decoded = json_decode($ai_response, true);
    $reply = $ai_decoded['response'] ?? '';
} else {
    // ─── External API (Groq / OpenAI-compatible) ────────────
    $request_body = json_encode([
        "model" => $AI_EXTERNAL_config['model'],
        "temperature" => 0.1,
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt,
            ]
        ],
        "response_format" => ["type" => "json_object"], // บังคับ JSON
    ]);

    $ch = curl_init($AI_EXTERNAL_config['api_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $request_body,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer {$AI_EXTERNAL_config['api_key']}",
        ],
        CURLOPT_TIMEOUT => 120,
    ]);
    $ai_response = curl_exec($ch);
    $ai_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ai_curl_err = curl_error($ch);
    curl_close($ch);

    // Parse Groq response (OpenAI format)
    $ai_decoded = json_decode($ai_response, true);
    $reply = $ai_decoded['choices'][0]['message']['content'] ?? '';
}

// ─── Parse JSON จาก reply (ใช้ร่วมกันทั้ง 2 mode) ──────────
$decision = json_decode(trim($reply), true);

echo json_encode([
    "debug" => [
        "mode" => $AI_MODE === 0 ? "local_ollama" : "external_api",
        "ai_http_code" => $ai_http,
        "ai_curl_error" => $ai_curl_err,
        "ai_response_raw" => $ai_response,
        "reply_extracted" => $reply,
        "json_last_error" => json_last_error_msg(),
        "decision" => $decision,
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


// ============================================================
// 6. Log ทุก branch
// ============================================================
if ($decision && !empty($decision['branches'])) {
    foreach ($decision['branches'] as $bid => $branch_result) {
        foreach ($branch_result['sensor_decisions'] as $sd) {
            pg_query_params($db, "
                INSERT INTO sensor_decision_log
                    (sensor_id, action, reason, weather_will_rain, weather_max_rain_mm, decided_at)
                VALUES ($1, $2, $3, $4, $5, NOW())
            ", [
                $sd['monitor_id'],
                $sd['action'],
                $sd['reason'],
                $decision['weather_assessment']['will_rain'] ? 'true' : 'false',
                $decision['weather_assessment']['max_rain_mm'],
            ]);
        }
    }
}

echo json_encode([
    "status" => "success",
    "branches" => count($branches),
    "evaluated_at" => $current_time,
    "decision" => $decision,
], JSON_UNESCAPED_UNICODE);

pg_close($db);
