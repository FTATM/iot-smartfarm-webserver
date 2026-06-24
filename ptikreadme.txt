วิธี Deploy เบื้องต้น
ส่วนของ Database 
*********ก่อน Run script ทุกครั้งควร backup Database ไว้ก่อน และควรเปิดเช็คความถูกต้องของ script ที่จะ Run ก่อน**
restore database ด้วยไฟล์ 20241222_lastest.dump ควรสร้าง database ก้อนใหม่ก่อนทำการ restore เพราะในนี้จะมีตาราง volt_sencor อยู่หาก restore ไป database ที่ใช้งานอยู่อาจจะไปทับของเดิมได้
===========================================================================================
ส่วนของ Souce code PHP
1.  นำ Source code ทั้งหมดใน folder "iot-demo" ไปวางที่ Root directory ของ www ชี้ไปเพื่อให้เว็บรัน (Code จะ Run ด้วย PHP) 
    และต้องเปิดการงาน module pg_connect ที่อยู่ใน php.ini ถ้ายังไม่ติดตั้งให้ทำการติดตั้งก่อน จากนั้น restart web service ของ Web server เช่น nginx หรือ apache;
2.  แก้ไขข้อมูล config การเชื่อมต่อ db postgres ในไฟล์ "pg_connect.php" อยู่ใน path "~\iot-demo\includes\fn\pg_connect.php" ค่าที่ต้องแก้จะมี
    $database_name  = "postgres"; // DB name
    $host           = "host = 127.0.0.1"; //DB Host
    $port           = "port = 5432"; // DB post 
    $user_pg        = "postgres"; // user for connect db
    $pass_pg        = "postgres";  // passowrd for connect db
===========================================================================================
ส่วนของการ Get Mac Address และการปรับวันหมดอายุ token
ในโปรเจคจะมีไฟล์ get_device.php เอาไว้ดึงค่า Mac Address และสร้าง token expaire
****#ถ้าใช้เสร็จแล้วให้เก็บไว้ที่อื่น และทำการลบออกจากเครื่องลูกค้าทันที#****
วิธีการคือให้ดูว่าเครื่อง host เป็น linix หรือ windows โดยในไฟล์จะรับค่า GET parameter อยู่2ตัวคือ
expire_token และ os_type
ถ้า host เป็น Linux ให้ส่งค่า os_type=0 หรือ Windows=1 //0= linux, 1= Windows
ส่วน expire_token เป็นตัวกำหนดวันหมดอายุของ token เช่น expire_token=2025-01-01 00:00:00 หมายความว่า token ที่ออกจะหมดวันที่ 1 มกราคม ค.ศ. 2025 เวลา 00:00:00 นั่นเอง
http://domainหรือIP/get_device.php?os_type=0&expire_token=2025-01-01 00:00:00

เสร็จแล้วจะได้ค่า token เอาไปใส่ที่ไฟล์ /includes/fn/pg_connect.php
เอา token ไปแทรกใน "token here" define("DEVICE_TOKEN", "token here");
ส่วน define("HOST_DEVICE_OS", '0');//0= linux, 1= Windows ก็ให้ใส่ตาม OS ของ host

****เสร็จแล้วอย่าลืมเก็บ get_device.php ไว้ที่อื่น แล้วลบ get_device.php***

===========================================================================================
ไฟล์ nav_bar.php
บรรทัดที่ 43 แก้จาก
if($_SERVER['SCRIPT_NAME'] == "/login.php"){
แก้เป็นใช้แบบนี้แทน
if(basename($_SERVER['PHP_SELF']) == "login.php"){

