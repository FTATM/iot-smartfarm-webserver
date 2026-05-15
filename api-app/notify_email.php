<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/../vendor/autoload.php');
require (__DIR__ . '/../api-website/config.php');

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $MAIL_config['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $MAIL_config['username'];
        $mail->Password   = $MAIL_config['password'];
        $mail->SMTPSecure = $MAIL_config['encryption'];
        $mail->Port       = $MAIL_config['port'];

        // Recipient
        $mail->setFrom($MAIL_config['username'], 'FIELDTECH AUTOMATION CO.,LTD.');
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

