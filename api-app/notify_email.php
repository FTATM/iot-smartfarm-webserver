<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/../vendor/autoload.php');

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ftatm.it.1@gmail.com';
        $mail->Password   = 'cwuc cljl lrnv pztw'; // ใช้ App Password เท่านั้น
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipient
        $mail->setFrom('FTA@gmail.com', 'IoTSF');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        $mail->send();
        return "Email sent";
    } catch (Exception $e) {
        return "Failed: {$mail->ErrorInfo}";
    }
}

