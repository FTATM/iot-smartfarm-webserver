<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

if (!$db) {
    echo json_encode([
        "status" => "error",
        "error" => "Database connection failed."
    ]);
    exit;
}

// ======================== รับ JSON ========================
$body = file_get_contents('php://input');
$input = json_decode($body, true);

if (!$input || !is_array($input)) {
    echo json_encode([
        "status" => "error",
        "error" => "Invalid or missing JSON body."
    ]);
    pg_close($db);
    exit;
}

// ======================== สร้าง VALUES ========================
$values = [];
$params = [];
$index = 1;

foreach ($input as $item) {

    if (
        !isset(
            $item['group_id'],
            $item['device_id'],
            $item['type_id'],
            $item['datax_id']
        )
    ) {
        continue;
    }

    // $values[] = "($".$index++.", $".$index++.", $".$index++.", $".$index++.")";
    $values[] = "(
    $".$index++."::integer,
    $".$index++."::integer,
    $".$index++."::integer,
    $".$index++."::integer
)";

    $params[] = (int)$item['group_id'];
    $params[] = (int)$item['device_id'];
    $params[] = (int)$item['type_id'];
    $params[] = (int)$item['datax_id'];
}

if (empty($values)) {
    echo json_encode([
        "status" => "error",
        "error" => "No valid input data."
    ]);
    pg_close($db);
    exit;
}

// ======================== Query ครั้งเดียว ========================
$sql = "
SELECT DISTINCT ON (
    dl.group_id,
    dl.device_id,
    dl.type_id,
    dl.datax_id
)
    dl.group_id,
    dl.device_id,
    dl.type_id,
    dl.datax_id,
    dl.data_value,
    dl.createtime,
    pdmd.divice_name AS device_name

FROM public.data_log dl

LEFT JOIN public.page_data_manage_device pdmd
    ON dl.device_id = pdmd.device_id

JOIN (
    VALUES " . implode(',', $values) . "
) AS req(group_id, device_id, type_id, datax_id)

ON dl.group_id  = req.group_id
AND dl.device_id = req.device_id
AND dl.type_id   = req.type_id
AND dl.datax_id  = req.datax_id

ORDER BY
    dl.group_id,
    dl.device_id,
    dl.type_id,
    dl.datax_id,
    dl.createtime DESC
";

$result = pg_query_params($db, $sql, $params);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "error" => pg_last_error($db)
    ]);

    pg_close($db);
    exit;
}

// ======================== แปลงผลลัพธ์ ========================
$data = [];

while ($row = pg_fetch_assoc($result)) {

    $data[] = [
        "device_name" => $row['device_name'],
        "device_id"   => (int)$row['device_id'],
        "group_id"    => (int)$row['group_id'],
        "type_id"     => (int)$row['type_id'],
        "datax_id"    => (int)$row['datax_id'],
        "data_value"  => $row['data_value'] !== null
                            ? (float)$row['data_value']
                            : null,
        "createtime"  => $row['createtime']
    ];
}

// ======================== Response ========================
if (!empty($data)) {

    echo json_encode([
        "status" => "success",
        "count"  => count($data),
        "data"   => $data
    ], JSON_UNESCAPED_UNICODE);

} else {

    echo json_encode([
        "status" => "error",
        "count"  => 0,
        "data"   => [],
        "message"=> "No data found."
    ], JSON_UNESCAPED_UNICODE);
}

pg_close($db);
?>