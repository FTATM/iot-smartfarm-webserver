<?php
$url = "https://api-v2.thaibulksms.com/sms";
$THAIBULKSMS_USERNAME = "PlpsjNR0iLofhBz6Xh_a7D9RIXeEeB"; // กำหนดชื่อผู้ใช้ที่สมัครในเว็บ ThaiBulksms.com
$THAIBULKSMS_PASSWORD = "VvRwddqGZ1F3rZf8eoixJmyyCxGsEs"; // กำหนดรหัสผ่านที่สมัครในเว็บ ThaiBulksms.com
$phoneNumber = '';
$message = "";

if(isset($_POST['phoneNumber']) && $_POST['phoneNumber'] != ""){
	$phoneNumber = $_POST['phoneNumber'];
}else{
	$msg = "phoneNumber is NULL";
	echo json_encode(array('code'=>'102','msg'=>$msg));
	exit;
}
if(isset($_POST['msg']) && $_POST['msg'] != ""){
	$message = $_POST['msg'];
}else{
	$msg = "message is NULL";
	echo json_encode(array('code'=>'103','msg'=>$msg));
	exit;
}

$post_data = array(
					"username" => $THAIBULKSMS_USERNAME,
					"password" => $THAIBULKSMS_PASSWORD,
					"msisdn"   => $phoneNumber,
					"message"  => $message
				);
	$res_sms = curl_func($url,$post_data);
	echo $res_sms;
	// echo base64_encode($THAIBULKSMS_USERNAME.":".$THAIBULKSMS_PASSWORD);exit;
	// echo http_build_query($post_data);exit;
	
	// echo "<pre>";
	// $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Basic '.base64_encode($THAIBULKSMS_USERNAME.":".$THAIBULKSMS_PASSWORD));
	// print_r($headers);
	// exit;
	// print_r($res_sms);
function curl_func($url="",$post_data = array()){
    	$chOne = curl_init(); 
		curl_setopt( $chOne, CURLOPT_URL, $url); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt( $chOne, CURLOPT_POST, 1); 
		curl_setopt( $chOne, CURLOPT_POSTFIELDS, http_build_query($post_data)); 
		// $headers = ['Content-type: application/x-www-form-urlencoded', 
		// 				  'Authorization: Basic '.base64_encode($THAIBULKSMS_USERNAME.":".$THAIBULKSMS_PASSWORD)
		// 			];
		$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Basic '.base64_encode($GLOBALS['THAIBULKSMS_USERNAME'].":".$GLOBALS['THAIBULKSMS_PASSWORD']));
		curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec( $chOne ); 

		//Result error 
		if(curl_error($chOne)) 
		{ 
			echo 'error:' . curl_error($chOne); 
		} 
		else { 
			// $result_ = json_decode($result, true); 
			// echo "status : ".$result_['status']; echo "message : ". $result_['message'];
      return $result;
		} 
		curl_close( $chOne );  
 }
 ?>
