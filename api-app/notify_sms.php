<?php

require (__DIR__ . '/../vendor/autoload.php');
require (__DIR__ . '/../api-website/config.php');

function sendSMS($phone, $message)
{
    global $SMS_config;

    // แปลงเบอร์ 08xxxxxxxx เป็น 668xxxxxxxx
    if (preg_match('/^0\d{9}$/', $phone)) {
        $phone = '66' . substr($phone, 1);
    }

    $postData = [
        'msisdn'  => $phone,
        'message' => $message,
        'sender'  => $SMS_config['sender'],
        'force'   => 'standard'
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $SMS_config['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode(
                $SMS_config['api_key'] . ':' . $SMS_config['api_secret']
            )
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return "Failed: {$error}";
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status >= 200 && $status < 300) {
        return "SMS sent";
    }

    return "Failed: {$response}";
}