<?php
  $url = "https://iot-demo.tikky.xyz/api_get_data_notify.php";
  $post_data = array(
    "chksum" => "168999494"
  );
  $url_send_notify = "https://iot-demo.tikky.xyz/alert_line_notify.php";
  $url_send_email_notify = "https://iot-demo.tikky.xyz/alert_email_notify.php";
  $url_send_sms_notify = "https://iot-demo.tikky.xyz/alert_sms_notify.php";

  $response = curl_func($url, $post_data);
  $res_json = json_decode($response,TRUE);
  if($res_json['ret']=='101'){
    if(count($res_json['data_alert'])>0){
      for($i=0;$i<count($res_json['data_alert']);$i++){
        /**Line Alert */
        $is_line_alert = $res_json['data_alert'][$i]['is_line_alert'];
        $msg_alert = $res_json['data_alert'][$i]['msg_alert'];
        $line_alert_value = $res_json['data_alert'][$i]['line_alert_value'];
        if($is_line_alert=='YES'){
          if($line_alert_value!=""){
            if($msg_alert!=""){
              $post_data = array(
                "line_token" => $line_alert_value,
                "msg" => $msg_alert
              );
              $res_api = curl_func($url_send_notify,$post_data);
              // var_dump($res_api);exit;
              echo $res_api."<br>";

            }
          }
        }

        /**Email Alert */
        $is_email_alert = $res_json['data_alert'][$i]['is_email_alert'];
        $msg_alert = $res_json['data_alert'][$i]['msg_alert'];
        $email_alert_value = $res_json['data_alert'][$i]['email_alert_value'];
        if($is_email_alert=='YES'){
          if($email_alert_value!=""){
            if($msg_alert!=""){
              $post_data = array(
                "email_target" => $email_alert_value,
                "msg" => $msg_alert
              );
              $res_api = curl_func($url_send_email_notify,$post_data);
              // var_dump($res_api);exit;
              echo $res_api."<br>";

            }
          }
        }

        /**SMS Alert */
        $is_sms_alert = $res_json['data_alert'][$i]['is_sms_alert'];
        $msg_alert = $res_json['data_alert'][$i]['msg_alert'];
        $sms_alert_value = $res_json['data_alert'][$i]['sms_alert_value'];
        if($is_sms_alert=='YES'){
          if($sms_alert_value!=""){
            if($msg_alert!=""){
              $post_data = array(
                "phoneNumber" => $sms_alert_value,
                "msg" => $msg_alert
              );
              $res_api = curl_func($url_send_sms_notify,$post_data);
              // var_dump($res_api);exit;
              echo $res_api."<br>";

            }
          }
        }

      }
    }
  }

 function curl_func($url="",$post_data = array()){
    $chOne = curl_init(); 
		curl_setopt( $chOne, CURLOPT_URL, $url); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt( $chOne, CURLOPT_POST, 1); 
		curl_setopt( $chOne, CURLOPT_POSTFIELDS, http_build_query($post_data)); 
		$headers = array( 'Content-type: application/x-www-form-urlencoded');
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