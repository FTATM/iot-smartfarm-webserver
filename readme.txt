# IoT Smart Farm Web Server

ระบบบริหารจัดการฟาร์มอัจฉริยะ (Smart Farm) สำหรับรับข้อมูลจากอุปกรณ์ IoT แสดงผลผ่าน Dashboard แบบ Real-time พร้อมระบบแจ้งเตือนและจัดการอุปกรณ์ภายในฟาร์ม

## Features

- Dashboard แสดงข้อมูล Sensor แบบ Real-time
- รองรับการรับข้อมูลจาก IoT Hardware ผ่าน API
- ระบบจัดการ Device
- ระบบจัดการ User และสิทธิ์การเข้าถึง
- ระบบจัดการ Farm / Branch
- ระบบแจ้งเตือนผ่าน Email
- ระบบแจ้งเตือนผ่าน LINE Notify
- ระบบแจ้งเตือนผ่าน SMS
- ระบบ Cron Job สำหรับประมวลผลข้อมูลอัตโนมัติ
- รองรับ PostgreSQL Database

---

## Technology Stack

### Backend

- PHP
- PostgreSQL
- Composer

### Frontend

- HTML
- CSS
- JavaScript

### Additional Services

- Email Notification
- LINE Notification
- SMS Notification

---

## Project Structure

```text
.
├── api-app/
├── api-website/
├── dashboard/
├── database/
├── db_structure/
├── file_script/
├── includes/
├── local_js/
├── python/
├── vendor/
│
├── api_push_data_by_hardware_process.php
├── api-get-sensor.php
├── dashboard_view.php
├── login.php
├── register.php
├── manage_data.php
├── crontab_task_all.php
└── migrate.php
```

Repository ประกอบด้วยส่วนสำคัญดังนี้ :contentReference[oaicite:1]{index=1}

- **Dashboard** สำหรับแสดงผลข้อมูล
- **API** สำหรับรับส่งข้อมูลกับอุปกรณ์ IoT
- **Database Script** สำหรับติดตั้งฐานข้อมูล
- **Notification Service** สำหรับแจ้งเตือน
- **Cron Job** สำหรับงานอัตโนมัติ

---

# Requirements

- PHP 8.x หรือใหม่กว่า
- PostgreSQL 13+
- Apache หรือ Nginx
- Composer

PHP Extensions

```ini
pgsql
pdo_pgsql
curl
json
mbstring
openssl
```

---

# Installation

## 1. Clone Repository

```bash
git clone https://github.com/FTATM/iot-smartfarm-webserver.git
cd iot-smartfarm-webserver
```

---

## 2. Install Composer Packages

```bash
composer install
```

---

## 3. Create Environment File

คัดลอกไฟล์ตัวอย่าง

```bash
cp .env.example .env
```

หรือบน Windows

```cmd
copy .env.example .env
```

---

## 4. Setup PostgreSQL

สร้าง Database

```sql
CREATE DATABASE smartfarm;
```

Restore Database จากไฟล์ Dump

```bash
pg_restore -U postgres -d smartfarm database/20241222_lastest.dump
```

> แนะนำให้ Restore ลง Database ใหม่ก่อน เพื่อป้องกันข้อมูลเดิมถูกทับ :contentReference[oaicite:2]{index=2}

---

## 5. Configure Database Connection

แก้ไขไฟล์

```text
includes/fn/pg_connect.php
```

ตัวอย่าง

```php
$database_name = "smartfarm";
$host          = "host=127.0.0.1";
$port          = "port=5432";
$user_pg       = "postgres";
$pass_pg       = "password";
```

:contentReference[oaicite:3]{index=3}

---

## 6. Configure Web Server

Apache

```apache
DocumentRoot /var/www/html/iot-smartfarm-webserver
```

Nginx

```nginx
server {
    listen 80;

    root /var/www/iot-smartfarm-webserver;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

# Device Registration

ระบบใช้ Token ผูกกับเครื่อง Host Server

ไฟล์

```text
get_device.php
```

ใช้สำหรับสร้าง Device Token

ตัวอย่าง

```text
http://your-domain/get_device.php?os_type=0&expire_token=2027-01-01%2000:00:00
```

Parameters

| Parameter | Description |
|------------|------------|
| os_type | 0 = Linux |
| os_type | 1 = Windows |
| expire_token | วันหมดอายุ Token |

เมื่อได้ Token แล้วให้นำไปใส่ใน

```php
define("DEVICE_TOKEN", "YOUR_TOKEN");
```

และกำหนด

```php
define("HOST_DEVICE_OS", "0");
```

หลังจากสร้าง Token แล้วควรลบไฟล์ `get_device.php` ออกจากเครื่อง Production ทันทีเพื่อความปลอดภัย :contentReference[oaicite:4]{index=4}

---

# API Example

## Push Sensor Data

```http
POST /api_push_data_by_hardware_process.php
```

Example Payload

```json
{
    "device_id": "ESP32-001",
    "temperature": 28.5,
    "humidity": 72.1,
    "soil_moisture": 65
}
```

---

## Get Sensor Data

```http
GET /api-get-sensor.php
```

---

# Scheduled Tasks

Cron Job หลัก

```bash
php crontab_task_all.php
```

Cron Job แจ้งเตือน

```bash
php crontab_data_notify.php
```

ตัวอย่าง Linux Crontab

```cron
*/5 * * * * php /var/www/iot-smartfarm-webserver/crontab_task_all.php
```

---

# Notification Channels

ระบบรองรับ

- Email Notification
- LINE Notification
- SMS Notification

ไฟล์ที่เกี่ยวข้อง

```text
alert_email_notify.php
alert_line_notify.php
alert_sms_notify.php
```

:contentReference[oaicite:5]{index=5}

---

# Security Recommendations

- เปลี่ยนรหัสผ่าน PostgreSQL ก่อนใช้งานจริง
- ลบไฟล์ `get_device.php` หลังสร้าง Token
- จำกัดสิทธิ์ Database User
- เปิด HTTPS
- จำกัดการเข้าถึง API ด้วย Firewall หรือ Reverse Proxy

---

# Backup Database

สร้าง Backup

```bash
pg_dump -U postgres smartfarm > smartfarm_backup.sql
```

Restore

```bash
psql -U postgres smartfarm < smartfarm_backup.sql
```

---

# License

This project is developed for Smart Farm IoT monitoring and management systems.

---

# Author

FTATM Team

GitHub Repository:

:contentReference[oaicite:6]{index=6}
