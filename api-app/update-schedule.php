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

// เริ่ม Transaction
pg_query($db, "BEGIN");

try {

    $tempMap = [];
    $rowMap = [];

    foreach ($data as $item) {


        $id = $item["id"];
        $name = $item["name"] ?? null;
        $label = $item["label"] ?? null;
        $branch_id = $item["branch_id"] ?? null;
        $is_deleted = $item["is_deleted"] ?? 0;
        $child_of_table_id = $item["child_of_table_id"] ?? null;

        $realParentId = $id;
        // 🟡 map temp id → real id
        if ($child_of_table_id !== null && isset($tempMap[$child_of_table_id])) {
            $child_of_table_id = $tempMap[$child_of_table_id];
        }

        // 🟡 ป้องกัน string หลุดเข้า DB
        if ($child_of_table_id !== null && !ctype_digit((string)$child_of_table_id)) {
            $child_of_table_id = null;
        }

        // ---------- 🟡 ถ้าเป็น Table ใหม่ (temp id) ----------
        if (!ctype_digit((string)$id)) {

            // INSERT แล้วรับ real id
            $insertParent = pg_query_params(
                $db,
                "INSERT INTO names_table (name, label, branch_id, child_of_table_id, is_deleted)
             VALUES ($1,$2,$3,$4,$5)
             RETURNING id",
                [$name, $label, $branch_id, $child_of_table_id, $is_deleted]
            );

            $row = pg_fetch_assoc($insertParent);
            $realParentId = $row["id"];

            // บันทึก mapping
            $tempMap[$id] = $realParentId;
        } else {

            // ---------- 🟢 ถ้ามี id จริง → update / insert ปกติ ----------
            $checkParent = pg_query_params(
                $db,
                "SELECT id FROM names_table WHERE id=$1",
                [$id]
            );

            if (pg_num_rows($checkParent) > 0) {

                pg_query_params(
                    $db,
                    "UPDATE names_table 
                 SET name=$1,label=$2,branch_id=$3,child_of_table_id=$4,is_deleted=$5
                 WHERE id=$6",
                    [$name, $label, $branch_id, $child_of_table_id, $is_deleted, $id]
                );
            } else {

                $insertParent = pg_query_params(
                    $db,
                    "INSERT INTO names_table (name,label,branch_id,child_of_table_id,is_deleted)
                 VALUES ($1,$2,$3,$4,$5)
                 RETURNING id",
                    [$name, $label, $branch_id, $child_of_table_id, $is_deleted]
                );

                $row = pg_fetch_assoc($insertParent);
                $realParentId = $row["id"];
            }
        }


        if (!empty($item["rows"])) {
            foreach ($item["rows"] as $row) {

                $tempRowId = $row["d_id"];
                $d_start_day = $row["d_start_day"] ?? null;
                $d_end_day = $row["d_end_day"] ?? null;
                $d_value = $row["d_value"] ?? null;
                $d_second_label = $row["d_second_label"] ?? null;

                // ผูกกับ table จริง
                $d_name_table_id = $realParentId;

                // 🔗 row_parent_id (เฉพาะ table ลูก)
                $row_parent_id = null;
                if ($child_of_table_id !== null && !empty($row["d_row_parent_id"])) {
                    $tempParentRowId = $row["d_row_parent_id"];

                    if (isset($rowMap[$tempParentRowId])) {
                        $row_parent_id = $rowMap[$tempParentRowId];
                    }
                }

                /**
                 * =========================
                 * 🟢 UPDATE กรณี id จริง
                 * =========================
                 */
                if (ctype_digit((string)$tempRowId)) {

                    $checkRow = pg_query_params(
                        $db,
                        "SELECT id FROM datas_table WHERE id=$1",
                        [$tempRowId]
                    );

                    if (pg_num_rows($checkRow) > 0) {

                        pg_query_params(
                            $db,
                            "UPDATE datas_table
                     SET name_table_id=$1,
                         start_day=$2,
                         end_day=$3,
                         value=$4,
                         second_label=$5,
                         row_parent_id=$6
                     WHERE id=$7",
                            [
                                $d_name_table_id,
                                $d_start_day,
                                $d_end_day,
                                $d_value,
                                $d_second_label,
                                $row_parent_id,
                                $tempRowId
                            ]
                        );

                        // map id เดิม
                        $rowMap[$tempRowId] = $tempRowId;
                        continue;
                    }
                }

                /**
                 * =========================
                 * ➕ INSERT ใหม่
                 * =========================
                 */
                $insertRow = pg_query_params(
                    $db,
                    "INSERT INTO datas_table
             (name_table_id,start_day,end_day,value,second_label,row_parent_id)
             VALUES ($1,$2,$3,$4,$5,$6)
             RETURNING id",
                    [
                        $d_name_table_id,
                        $d_start_day,
                        $d_end_day,
                        $d_value,
                        $d_second_label,
                        $row_parent_id
                    ]
                );

                $realRow = pg_fetch_assoc($insertRow);
                $rowMap[$tempRowId] = $realRow["id"];
            }
        }
    }

    pg_query($db, "COMMIT");

    echo json_encode([
        "status" => "success",
        "message" => "upsert completed"
    ]);
} catch (Exception $e) {

    pg_query($db, "ROLLBACK");

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}

pg_close($db);
