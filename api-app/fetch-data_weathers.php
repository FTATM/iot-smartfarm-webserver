<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "cannot connect to database"]);
    exit;
}

$sql = "SELECT * FROM weathers";
$result = pg_query($db, $sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    pg_close($db);
    exit;
}

$dataWeather = [];

while ($row = pg_fetch_assoc($result)) {

    $params = [
        'lat'      => $row['lat'],
        'lon'      => $row['lon'],
        'fields'   => $row['fields'],
        'date'     => date('Y-m-d')
    ];

    $baseUrl = rtrim($row['url_api'], '?');
    $url = $baseUrl . '?' . http_build_query($params);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer " . $row['token'],
        ],
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);

    if ($error || $httpCode !== 200) {
        $dataWeather[] = [
            'weather_id' => $row['id'],
            'success' => false,
            'error' => $error ?: "HTTP $httpCode"
        ];
        continue;
    }

    $apiData = json_decode($response, true);

    $dataWeather[] = [
        'weather_id' => $row['id'],
        'success'    => true,
        'name'       => $row['name'],
        'lat'        => $row['lat'],
        'lon'        => $row['lon'],
        'data'       => $apiData
    ];
}

if (!empty($dataWeather)) {
    echo json_encode([
        "status" => "success",
        "data" => $dataWeather
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "no data found"
    ]);
}

pg_close($db);
