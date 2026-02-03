<?php
 
      // echo "<pre>";
      // print_r($_POST);exit;

      $username = "";
      $password = "";
      $confirm_password = "";
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
      if(isset($_POST['password']) && $_POST['password'] !=""){
        $password = md5(urlencode(trim($_POST['password'])));
      }else{
        $response = array("ret"=>"103","msg"=>"password is empty.");
        echo json_encode($response);
        exit;
      }
      // echo urlencode(trim($_POST['password']));
      // echo "|";
      // echo $_POST['password'];
      // echo "|";
      // echo md5(trim($_POST['password']));
      // echo "|";
      // exit;
      if(isset($_POST['confirm-password']) && $_POST['confirm-password'] !=""){
        $confirm_password = md5(urlencode(trim($_POST['confirm-password'])));
      }else{
        $response = array("ret"=>"104","msg"=>"confirm-password is empty.");
        echo json_encode($response);
        exit;
      }
      if($_POST['confirm-password'] != $_POST['password']){
        $response = array("ret"=>"106","msg"=>"password and confirm-password mismatch.");
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
      if(isset($_POST['sel_branch']) && $_POST['sel_branch'] !=""){
        $sel_branch = trim(pg_escape_string($_POST['sel_branch']));
      }else{
        // $response = array("ret"=>"105","msg"=>"name is empty.");
        // echo json_encode($response);
        // exit;
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
      if(count($rs)=='0'){
        $insert = "INSERT INTO user_account(
	                  username, password, name, email, phone_number, address, role_id, createtime, updatetime, status,branch_id)
	            VALUES ('{$username}', '{$password}', '{$nickname}', '{$email}', '{$phone_number}', '{$address_txt}', '1', '{$date_now}', '{$date_now}', '1','{$sel_branch}');";

        $rs_insert = execute($insert,$db);
        pg_close($db);
        if(!$rs_insert){
          $msg = "Create account not success";
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