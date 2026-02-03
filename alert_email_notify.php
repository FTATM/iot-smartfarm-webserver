<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$email_target ="";
$bodyMessage  = "";
if(isset($_POST['email_target']) && $_POST['email_target'] != ""){
	$email_target = $_POST['email_target'];
}else{
	$msg = "email_target is NULL";
	echo json_encode(array('code'=>'103','msg'=>$msg));
	exit;
}
if(isset($_POST['msg']) && $_POST['msg'] != ""){
	$bodyMessage = $_POST['msg'];
}else{
	$msg = "msg is NULL";
	echo json_encode(array('code'=>'104','msg'=>$msg));
	exit;
}

// $email_target ="sungwhell@gmail.com";
// $bodyMessage  = "168 TEST Send Email 168 | ".date('Y-m-d H:i:s');

try {
	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'ft.experimenter@gmail.com';                     //SMTP username
    $mail->Password   = 'ujvsgawhrkbwlaqq';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;  

    $mail->From = "ft.experimenter@gmail.com"; 
	$mail->FromName = "IOT Monitor Alert"; 
	$mail->addAddress($email_target, "System admin"); //Provide file path and name of the attachments 
	// $mail->addAttachment("file.txt", "File.txt");    
	// $mail->addAttachment("images/profile.png"); //Filename is optional 
	$mail->isHTML(true); 
	$mail->Subject = "Sent by System IOT Monitoring"; 
	$mail->Body = $bodyMessage; 
	$mail->AltBody = $bodyMessage; 
	if(!$mail->send()) 
	{ 
	echo "Mailer Error: " . $mail->ErrorInfo;
	} 
	else 
	{ 
	echo "Message has been sent successfully"; 
	}
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}