<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
    echo json_encode(["status" => "error", "message" => "can not connect to database"]);
    exit;
}

// รับ JSON จาก Flutter / Postman
$raw = $_POST['json'] ?? file_get_contents("php://input");
if (empty($raw)) {
    echo json_encode(["status" => "error", "message" => "need parameter json"]);
    exit;
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(["status" => "error", "message" => "invalid json"]);
    exit;
}

// {d_id: 6, d_name_table_id: 1, d_start_day: 29, d_end_day: 99, d_value: 22L : 2D, d_second_label: null}

// เริ่ม Transaction
pg_query($db, "BEGIN");

try {

    $id = $data['d_name_table_id'];

    $sql_select_parent = "SELECT id FROM table_names WHERE id = $1 OR child_of_table_id = $2";
    $res1 = pg_query_params($db, $sql_select_parent, [$id, $id]);

    while ($row = pg_fetch_assoc($res1)) {
        $sql_delete_data = "DELETE FROM table_datas WHERE name_table_id = $1 AND start_day = $2 AND end_day = $3";
        $res2 = pg_query_params($db, $sql_delete_data, [$row['id'], $data['d_start_day'], $data['d_end_day']]);

        if ($res2 === false) {
            throw new Exception("Delete row failed");
        }
    }




    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "Row deleted"
    ]);
} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
