<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include_once("../includes/fn/pg_connect.php");

// ================= Connect Database =================
$db = pg_connect("$host $port $dbname $credentials");

if (!$db) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "cannot connect to database"
    ]);
    exit;
}

// ================= Get JSON =================
$json = $_POST['json'] ?? '';

if (empty($json)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "need parameter json"
    ]);
    pg_close($db);
    exit;
}

// ================= Decode JSON =================
$decode = json_decode($json);

if (!$decode) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "invalid json format"
    ]);
    pg_close($db);
    exit;
}

try {

    // ================= START TRANSACTION =================
    pg_query($db, "BEGIN");

    // ================= Insert Branch =================
    $sql = "INSERT INTO branch_info 
            (branch_name, status, createtime, updatetime) 
            VALUES ($1,$2,$3,$4) 
            RETURNING branch_id";

    $now = date('Y-m-d H:i:s');

    $params = [
        $decode->newname ?? "New Branch",
        $decode->status ?? 1,
        $now,
        $now
    ];

    $result = pg_query_params($db, $sql, $params);

    if (!$result) {
        throw new Exception(pg_last_error($db));
    }

    $row = pg_fetch_assoc($result);
    $branch_id = $row['branch_id'];

    // ================= Copy Dashboard =================
    $sql_home = "SELECT id, value FROM dashboard_main";
    $result_home = pg_query($db, $sql_home);

    if (!$result_home) {
        throw new Exception(pg_last_error($db));
    }

    while ($row_home = pg_fetch_assoc($result_home)) {

        $sql_homeb = "INSERT INTO home_branch 
                      (branch_id, home_row_id, value) 
                      VALUES ($1,$2,$3)";

        $insert_home = pg_query_params($db, $sql_homeb, [
            $branch_id,
            $row_home['id'],
            $row_home['value'] ?? 0
        ]);

        if (!$insert_home) {
            throw new Exception(pg_last_error($db));
        }
    }

    // ================= COMMIT =================
    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "Create Success",
        "branch_id" => $branch_id
    ]);

} catch (Throwable $e) {

    // ================= ROLLBACK =================
    pg_query($db, "ROLLBACK");

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

pg_close($db);