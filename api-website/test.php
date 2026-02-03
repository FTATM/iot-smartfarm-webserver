<?php


$path = '../../../../baseProjectClone/iot-smartfarm-webserver/includes/fn/pg_connect.php'; 

if (file_exists($path)) {
    echo "เจอไฟล์แล้ว 🎉";
} else {
    echo "ไม่เจอไฟล์ ❌";
}

echo $path ;