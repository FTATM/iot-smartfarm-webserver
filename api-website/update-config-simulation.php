<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");

if (!$db) {
    echo json_encode([
        "status" => "error",
        "message" => "can not connect to database"
    ]);
    exit;
}
// Example input json
// [
//     {
//         "id": 1,
//         "is_sim": 1,
//         "sim_min": 10,
//         "sim_max": 50
//     },
//     {
//         "id": 2,
//         "sim_step": 0.5,
//         "sim_noise": 2
//     }
// ]

$raw = $_POST['json'] ?? file_get_contents("php://input");

if (empty($raw)) {
    echo json_encode([
        "status" => "error",
        "message" => "need parameter json"
    ]);
    exit;
}

$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        "status" => "error",
        "message" => "invalid json"
    ]);
    exit;
}

$allowColumns = [
    'is_sim',
    'sim_min',
    'sim_max',
    'sim_base',
    'sim_step',
    'sim_noise',
    'sim_pull'
];

pg_query($db, "BEGIN");

try {

    $updatedRows = 0;

    foreach ($data as $row) {

        if (!isset($row['id'])) {
            continue;
        }

        $id = (int)$row['id'];

        $sets = [];

        foreach ($allowColumns as $col) {

            if (!array_key_exists($col, $row)) {
                continue;
            }

            if ($col === 'is_sim') {
                $value = ((int)$row[$col]) ? 1 : 0;
            } else {
                if (!is_numeric($row[$col])) {
                    continue;
                }
                $value = $row[$col];
            }

            $sets[] = "$col = $value";
        }

        if (empty($sets)) {
            continue;
        }

        $sql = "
            UPDATE page_data_manage_monitor
            SET " . implode(", ", $sets) . "
            WHERE monitor_id = $id
        ";

        $result = pg_query($db, $sql);

        if (!$result) {
            throw new Exception(pg_last_error($db));
        }

        $updatedRows += pg_affected_rows($result);
    }

    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "patch completed",
        "updated_rows" => $updatedRows
    ]);
} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

pg_close($db);
