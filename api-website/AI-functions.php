<?php

function callTools($branch_id, $required_sensors, $required_tools, $output_list = [], $status = null)
{
    if (!$required_tools) {
        return "no tools required";
    }
    $data = "";

    foreach ($required_tools as $tool) {
        switch ($tool) {
            case 'get_weather_summary':
                $data .= "Weather:" . json_encode(getWeatherSummary()) . "\n";
                break;
            case 'get_history_sensor_summary':
                $data .= "History:" . json_encode(getHistorySensorSummary($branch_id, $required_sensors)) . "\n";
                break;
            case 'set_sensor_output':
                $data .= "Action:" . json_encode(setSensorOutput($branch_id, $output_list, $status)) . "\n";

                break;
        }
    }

    return $data;
}

function getmonitorList($branch_id)
{
    $ch = curl_init("http://localhost/iotsf/api-website/AI-monitors-list.php?bid={$branch_id}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['data'] ?? [];
}

function getWeatherSummary()
{
    $ch = curl_init('http://localhost/iotsf/api-website/AI-TMD-summary.php');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    $tmd_raw = curl_exec($ch);
    curl_close($ch);

    $tmd_data = json_decode($tmd_raw, true);
    $forecasts = $tmd_data['data'] ?? [];

    return $forecasts;
}

function getHistorySensorSummary(int $branchId, array $requiredSensors)
{
    $ch = curl_init("http://localhost/iotsf/api-website/AI-monitors-summary.php?bid={$branchId}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => json_encode(['required_sensors' => $requiredSensors]),
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    return $result['data'] ?? [];
}

function setSensorOutput(int $branchId, array $outputList, string $status = null)
{
    if (!$outputList && !is_array($outputList)) {
        return "no output to set or output format invalid";
    }
    if (!$status) {
        $status = "normal";
        return "no status provided, default to normal. output list: " . json_encode($outputList);
    }

    $ch = curl_init("http://localhost/iotsf/api-website/AI-set-output.php?bid={$branchId}");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_POSTFIELDS => json_encode(['output_list' => $outputList, 'status' => $status]),
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    return $result['message'] ?? ["Failed to set sensor output"];
}

function CallAI($prompt)
{
    global $AI_MODE, $AI_config, $AI_EXTERNAL_config;

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
        $reply = $ai_decoded['response'] ?? 'No response';
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

        $ai_decoded = json_decode($ai_response, true);

        if (isset($ai_decoded['choices'][0]['message']['content'])) {
            $reply = json_encode(["success" => true, "message" => $ai_decoded['choices'][0]['message']['content']]);
        } else if (isset($ai_decoded['error'])) {
            $reply = json_encode(["success" => false, "message" => 'Error from AI: ' . $ai_decoded['error']['message'], "error" => $ai_decoded['error']]);
        } else {
            $reply = json_encode(["success" => false, "message" => 'No valid response from AI', "error" => "AI response missing expected fields"]);
        }
    }

    return $reply;
}
