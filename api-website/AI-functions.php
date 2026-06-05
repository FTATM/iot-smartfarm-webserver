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
                $data = setSensorOutput($branch_id, $output_list, $status);

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
        return json_encode(["success" => false, "message" => "no output to set or output format invalid"]);
    }
    if (!$status) {
        $status = "normal";
        return json_encode(["success" => false, "message" => "no status provided, default to normal. output list: " . json_encode($outputList)]);
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
    return json_encode(["success" => $result['message'] ? true : false, "message" => $result['message'] ? $result['message'] : "Failed to set sensor output"]);
}

function CallAI($prompt, $mode, $lo_config, $ex_config, $timeout = 3600)
{
    if ($mode === 0) {
        // ─── Local Ollama ───────────────────────────────────────
        $request_body = json_encode([
            "model" => $lo_config['model'],
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

        $ch = curl_init($lo_config['api_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $request_body,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_TIMEOUT => $timeout,
        ]);
        $ai_response = curl_exec($ch);
        $ai_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $ai_curl_err = curl_error($ch);
        curl_close($ch);

        // Parse Ollama response
        $ai_decoded = json_decode($ai_response, true);
        if (isset($ai_decoded['response'])) {
            $reply = json_encode(["success" => true, "message" => $ai_decoded['response']]);
            // } else if (isset($ai_decoded['error'])) {
            //     $reply = json_encode(["success" => false, "message" => 'Error from AI: ' . $ai_decoded['error']['message'], "error" => $ai_decoded['error']]);
        } else {
            $reply = json_encode(["success" => false, "message" => 'No valid response from AI', "error" => "AI response missing expected fields"]);
        }
    } else {
        // ─── External API (Groq / OpenAI-compatible) ────────────
        $request_body = json_encode([
            "model" => $ex_config['model'],
            "temperature" => 0.1,
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt,
                ]
            ],
            "response_format" => ["type" => "json_object"], // บังคับ JSON
        ]);

        $ch = curl_init($ex_config['api_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $request_body,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$ex_config['api_key']}",
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

function INTO_log($db, $BRANCH_ID, $AI_MODE, $AI_MODEL, $CATEGORY = 'assistant', $QUESTION = null, $PLANNER_PROMPT = null, $PLANNER_RESPONSE = null, $COLLECTED_DATA = null, $DECISION_PROMPT = null, $DECISION_RESPONSE = null, $EXECUTION_RESULT = null)
{
    if (!$db || !$BRANCH_ID || !$QUESTION) {
        return json_encode(["success" => false, "message" => "Invalid input parameters for logging INTO interaction"]);
    }
    if (is_array($PLANNER_RESPONSE)) $PLANNER_RESPONSE = json_encode($PLANNER_RESPONSE, JSON_UNESCAPED_UNICODE);

    if (is_array($DECISION_RESPONSE)) $DECISION_PROMPT = json_encode($DECISION_RESPONSE, JSON_UNESCAPED_UNICODE);

    if (is_array($EXECUTION_RESULT)) $EXECUTION_RESULT = json_encode($EXECUTION_RESULT, JSON_UNESCAPED_UNICODE);


    $sql = "INSERT INTO ai_log (branch_id, ai_mode, ai_model, question, planner_prompt, planner_response, collected_data, decision_prompt, decision_response, execution_result, category) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
    $payload = [
        $BRANCH_ID,
        $AI_MODE,
        $AI_MODEL,
        $QUESTION ?? '',
        $PLANNER_PROMPT ?? '',
        $PLANNER_RESPONSE,
        $COLLECTED_DATA ?? '',
        $DECISION_PROMPT ?? '',
        $DECISION_RESPONSE,
        $EXECUTION_RESULT,
        $CATEGORY
    ];
    $result = pg_query_params($db, $sql, $payload);

    if (!$result) {
        error_log("Failed to log INTO interaction: " . pg_last_error($db));
        return json_encode(["success" => false, "message" => "Failed to log INTO interaction", "error" => pg_last_error($db)]);
    }

    if (pg_affected_rows($result) > 0) {
        return json_encode(["success" => true, "message" => "Logged INTO interaction successfully"]);
    } else {
        return json_encode(["success" => false, "message" => "Failed to log INTO interaction, no rows affected"]);
    }
}
