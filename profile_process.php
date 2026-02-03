<?php
 
      // echo "<pre>";
      // print_r($_POST);exit;

      $username = "";
      $nickname = "";
      $email = "";
      $phone_number = "";
      $address_txt = "";

      if(isset($_POST['username']) && $_POST['username'] !=""){
        $username = trim(pg_escape_string($_POST['username']));
      }else{
        $response = array("ret"=>"102","msg"=>"username is empty.");
        echo json_encode($response);
        exit;
      }
      if(isset($_POST['nickname']) && $_POST['nickname'] !=""){
        $nickname = trim(pg_escape_string($_POST['nickname']));
      }else{
        $response = array("ret"=>"105","msg"=>"name is empty.");
        echo json_encode($response);
        exit;
      }
      if(isset($_POST['email']) && $_POST['email'] !=""){
        $email = trim(pg_escape_string($_POST['email']));
      }else{
        // $response = array("ret"=>"106","msg"=>"email is empty.");
        // echo json_encode($response);
        // exit;
      }
      if(isset($_POST['phone_number']) && $_POST['phone_number'] !=""){
        $phone_number = trim(pg_escape_string($_POST['phone_number']));
      }else{
        // $response = array("ret"=>"107","msg"=>"phone number is empty.");
        // echo json_encode($response);
        // exit;
      }
      if(isset($_POST['address_txt']) && $_POST['address_txt'] !=""){
        $address_txt = trim(pg_escape_string($_POST['address_txt']));
      }else{
        // $response = array("ret"=>"108","msg"=>"address number is empty.");
        // echo json_encode($response);
        // exit;
      }
      // echo $password;exit;
   
     
      include_once("includes/fn/pg_connect.php");
      $db = pg_connect( "$host $port $dbname $credentials"  );
      if(!$db) {
          $response = array("ret"=>"201","msg"=>"Error : Unable to open database.");
          echo json_encode($response);
          exit;
      }
      
      $sql ="SELECT username, password, name, url_logo, role_id
	          FROM user_account WHERE username='{$username}';";
      // echo $sql;exit;
      $rs = select($sql,$db);
      
      // print_r($rs);exit;
      $date_now = date('Y-m-d H:i:s');
      if(count($rs)=='1'){
        $insert = "UPDATE user_account
                  SET  name='{$nickname}', email='{$email}', phone_number='{$phone_number}', address='{$address_txt}', updatetime='{$date_now}'
                  WHERE username='{$username}';";
        $rs_insert = execute($insert,$db);
        pg_close($db);
        if(!$rs_insert){
          $msg = "Update profile not success";
          $respose = array('ret'=>'203','msg' => $msg,'data'=>$insert);
          echo json_encode($respose);
          exit;
        }else{
          $msg = "Success";
          $respose = array('ret'=>'101','msg' => $msg);
          echo json_encode($respose);
          exit;
        }
        
      }else{
        $response = array("ret"=>"202","msg"=>"Duplicate User please use new username");
        echo json_encode($response);
        exit;
      }
?>