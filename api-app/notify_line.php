<?php

function sendLineOA($to, $message)
{
    $access_token = "K/6Bhwmk7rWnncchwC7QKoibeei28mY1Zk7iAgPi+WYJ2Gp662CIWdUbRkg6UMn8lBqrWbLAike4kP2TGTQo5aguktJ9YE/EOddoZ35NK0gWgka8xvQAcKZ3pVoI2W42VrK8goPB/YgH2LqXH1fpugdB04t89/1O/w1cDnyilFU=";

    $url = "https://api.line.me/v2/bot/message/push";

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer {$access_token}"
    ];

    $postData = [
        "to" => $to,              // userId หรือ groupId
        "messages" => [
            [
                "type" => "text",
                "text" => $message
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
