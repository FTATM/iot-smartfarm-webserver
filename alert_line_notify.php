<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");

	$sToken = "rPxc9DxZ0TWWL5JeCTLejUkXQ5o9ETECF5QMPSb3ydC";
	// $sMessage = "\nMonitor ID : 13\nMonitor Name : -\nReal Value = 0\nLess than MIN Value = 757";
	$sMessage = "";

	if(isset($_POST['line_token']) && $_POST['line_token'] != ""){
		$sToken = $_POST['line_token'];
	}else{
		$msg = "line_token is NULL";
		echo json_encode(array('code'=>'103','msg'=>$msg));
		exit;
	}
	if(isset($_POST['msg']) && $_POST['msg'] != ""){
		$sMessage = $_POST['msg'];
	}else{
		$msg = "msg is NULL";
		echo json_encode(array('code'=>'104','msg'=>$msg));
		exit;
	}
	$sMessage = str_replace("<br>","\n",$sMessage);

	if($sMessage != ""){
		$chOne = curl_init(); 
		curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt( $chOne, CURLOPT_POST, 1); 
		curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
		$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$sToken.'', );
		curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec( $chOne ); 

		//Result error 
		if(curl_error($chOne)) 
		{ 
			echo 'error:' . curl_error($chOne); 
		} 
		else { 
			$result_ = json_decode($result, true); 
			echo "status : ".$result_['status']; echo "message : ". $result_['message'];
		} 
		curl_close( $chOne );  
	}
	
	 
?>