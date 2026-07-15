<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require_once 'config.php';

if (empty($_POST['email'])) {
    http_response_code(400);
    echo "❌ ไม่มีอีเมลปลายทาง";
    exit;
}

$email = $_POST['email'];

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $MAIL_config['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $MAIL_config['username'];
    $mail->Password = $MAIL_config['password'];
    $mail->SMTPSecure = $MAIL_config['encryption'];
    $mail->Port = $MAIL_config['port'];

    $mail->setFrom($MAIL_config['username'], 'FIELDTECH AUTOMATION CO.,LTD.');
    $mail->addAddress($email);

    // แนบไฟล์จาก JavaScript
    if (!empty($_FILES['file']['tmp_name'])) {
        $mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
    }

    $mail->isHTML(true);
    $mail->Subject = 'รายงานข้อมูลจากระบบ Export';
    $mail->Body = '
    <p>เรียน ผู้รับ ' . $email . '</p>
    <p>โปรดดูไฟล์แนบที่ส่งมาพร้อมกับอีเมลนี้ ซึ่งเป็นรายงานข้อมูลที่คุณได้ร้องขอจากระบบ Export ของเรา</p>
    ';
    $mail->send();

    echo "✅ ส่งไฟล์แนบไปยังอีเมล $email สำเร็จ";
} catch (Exception $e) {
    echo "❌ ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}";
}