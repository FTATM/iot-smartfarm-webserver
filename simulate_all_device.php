<?php

include_once("includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    respondAndExit(500, 'DB_ERROR', 'Unable to connect to database');
}

// ── ดึง monitor พร้อมชื่อ device เพื่อใช้กำหนด range ──
$sql = "
    SELECT 
        m.group_id, m.device_id, m.type_id, m.datax_id,
        m.min_value, m.max_value,
        d.divice_name
    FROM page_data_manage_monitor m
    JOIN page_data_manage_device d ON d.device_id = m.device_id
    WHERE m.status = 1
";

$result = pg_query($db, $sql);
$monitors = [];
while ($row = pg_fetch_assoc($result)) {
    $monitors[] = $row;
}

function getRealisticValue(string $deviceName, int $typeId, float $minVal, float $maxVal): float
{
    // ── Digital type_id = 3 หรือ 4 → 0 หรือ 1 สลับกัน ──
    if ($typeId === 3 || $typeId === 4) {
        return mt_rand(0, 1); // 50/50
    }

    // ── Analog ranges ──
    // เรียงจาก specific → general เพื่อไม่ให้ "P" match "PPFD" ก่อน
    $ranges = [
        'EC_T'        => [1.4,  2.4],
        'pH_T'        => [5.6,  6.4],
        'EC'          => [1.4,  2.4],
        'pH'          => [5.6,  6.4],
        'PPFD'        => [120,  380],
        'Temp'        => [20.0, 27.0],
        'RH'          => [62.0, 83.0],
        'CO2'         => [500,  1000],   // CO2_tank, CO2 จะ match ตรงนี้
        'O2'          => [7.0,  11.0],
        'WT'          => [19.0, 25.0],   // WT_RO จะ match ตรงนี้
        'WL'          => [30.0, 95.0],
        'V_meter'     => [218,  225],
        'Voltage'     => [218,  225],
        'I_meter'     => [2.0,  25.0],
        'Current'     => [2.0,  25.0],
        'kW_meter'    => [50,   3000],
        'Power'       => [50,   3000],
        'Frequency'   => [49.8, 50.2],
        'Pf'          => [0.75, 0.98],
        'water_meter' => [100,  5000],
        'Energy'      => [10,   500],    // min=10 ป้องกันติดลบ
        'FLOW'        => [5.0,  80.0],
        'Alarm'       => [0,    1],
    ];

    foreach ($ranges as $key => $range) {
        if (stripos($deviceName, $key) !== false) {
            [$lo, $hi] = $range;
            // noise ±3% แต่ไม่ให้ต่ำกว่า lo จริง
            $noise = ($hi - $lo) * 0.03;
            $lo    = max($lo, $lo - $noise); // ไม่ให้ต่ำกว่า lo เดิม
            $hi    = $hi + $noise;
            return round($lo + mt_rand(0, 1000) / 1000 * ($hi - $lo), 2);
        }
    }

    // fallback
    if ($minVal != 0 || $maxVal != 0) {
        return round($minVal + mt_rand(0, 1000) / 1000 * ($maxVal - $minVal), 2);
    }

    return round(mt_rand(0, 10), 2);
}

function maybeAnomaly(float $value, string $deviceName, int $typeId): float
{
    // Digital ไม่ทำ anomaly
    if ($typeId === 3 || $typeId === 4) return $value;

    // 5% โอกาสเกิดค่าผิดปกติ
    if (mt_rand(1, 100) > 5) return $value;

    // เรียงจาก specific → general เช่นกัน
    $spikes = [
        'EC_T'      => 3.5,
        'pH_T'      => 7.5,
        'EC'        => 3.5,
        'pH'        => 7.5,
        'PPFD'      => 500.0,
        'Temp'      => 35.0,
        'RH'        => 95.0,
        'CO2'       => 1500.0,
        'O2'        => 4.0,
        'WT'        => 30.0,
        'Voltage'   => 245.0,
        'Frequency' => 52.0,
        'Pf'        => 0.5,
        'Energy'    => 600.0,  // spike สูง ไม่ติดลบ
    ];

    foreach ($spikes as $key => $spikeVal) {
        if (stripos($deviceName, $key) !== false) {
            return $spikeVal;
        }
    }

    return $value;
}

// ── loop ส่งทุก 10 วินาที ──
while (true) {
    $data_update = [];

    foreach ($monitors as $row) {
        $val = getRealisticValue(
            $row['divice_name'],
            (int)$row['type_id'],
            (float)$row['min_value'],
            (float)$row['max_value']
        );

        $val = maybeAnomaly($val, $row['divice_name'], (int)$row['type_id']);

        $data_update[] = [
            'group_id'   => $row['group_id'],
            'device_id'  => $row['device_id'],
            'type_id'    => $row['type_id'],
            'datax_id'   => $row['datax_id'],
            'data_value' => $val,
        ];
    }

    if (empty($data_update)) {
        echo "No monitor records found. Exiting.\n";
        break;
    }

    $ch = curl_init('http://localhost/iotsf/api_push_data_by_hardware_multidata.php');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($data_update),
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo date('Y-m-d H:i:s')
        . " | items=" . count($data_update)
        . " | HTTP={$httpCode}\n";
    sleep(10);
}

function respondAndExit(int $httpCode, string $code, string $message): void
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode(['ret' => (string)$httpCode, 'code' => $code, 'msg' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}