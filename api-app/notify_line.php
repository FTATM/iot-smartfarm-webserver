<?php
require (__DIR__ . '/../api-website/config.php');

function sendLineOA($to, $message)
{
    $access_token = $LINE_config['token'];

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
