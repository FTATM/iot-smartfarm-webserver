<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once('config.php');

$TOKEN = $TMD_config['token']; // วาง token เต็มที่นี่

$params = http_build_query([
    'lat'      => $_GET['lat']      ?? 7.2021,
    'lon'      => $_GET['lon']      ?? 100.5972,
    'fields'   => $_GET['fields']   ?? '',
    'date'     => $_GET['date']     ?? date('Y-m-d'),
    'duration' => $_GET['duration'] ?? 6,
]);

$ch = curl_init("https://data.tmd.go.th/nwpapi/v1/forecast/location/hourly/at?{$params}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'accept: application/json',
        "authorization: Bearer {$TOKEN}",
    ],
]);

$body   = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($status);
echo $body;