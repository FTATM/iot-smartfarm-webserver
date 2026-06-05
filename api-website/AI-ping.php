// ping.php
<?php
header('Content-Type: application/json');
require_once '../config.php';

// ลอง connect AI จริงๆ
$ch = curl_init($AI_MODE === 0 ? $AI_config['api_url'] : $AI_EXTERNAL_config['api_url']);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 5,
    CURLOPT_NOBODY         => true, // HEAD request ไม่ดึง body
]);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$ai_ok = $httpCode > 0; // ตอบกลับมาก็ถือว่า online

echo json_encode([
    'status'  => $ai_ok ? 'ok' : 'degraded',
    'ai_mode' => $AI_MODE,
]);