
# plot_all_enpoint_api.php
ข้อมูลของ กุ้ง , ไก่ 
ส่งที่ละ 1 id
ต้องรับข้อมูลเป็น POST 
 {key: requestApi 
 , value: id}



# plot_sensor_log_api.php 
ข้อมูลของ sensor เช่น EC, pH, Temp
ส่งที่ละ 1 id
ต้องรับข้อมูลเป็น POST 
 {
    key: sensorLog
,   value: id
}


# plot_sensor_log_multiple.php 
ข้อมูลของ sensor เช่น EC, pH, Temp
แต่ส่งได้มากกว่า 1 id
ต้องรับข้อมูลเป็น POST 
  {
    key: sensorLog 
,   value: [
    33,
    24,

    ]
}

# ไฟล์ id-Columns-เส้น API.txt จะบอก ID ของตาราง


