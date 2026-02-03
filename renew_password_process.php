<?php
 
      // echo "<pre>";
      // print_r($_POST);exit;

      $username = "";
      $old_password = "";
      $password = "";
      $confirm_password = "";
      
   

      if(isset($_POST['username']) && $_POST['username'] !=""){
        $username = trim(pg_escape_string($_POST['username']));
      }else{
        $response = array("ret"=>"102","msg"=>"username is empty.");
        echo json_encode($response);
        exit;
      }
      if(isset($_POST['old_password']) && $_POST['old_password'] !=""){
        $old_password = md5(urlencode(trim($_POST['old_password'])));
      }else{
        $response = array("ret"=>"105","msg"=>"old password is empty.");
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
      if(isset($_POST['confirm_password']) && $_POST['confirm_password'] !=""){
        $confirm_password = md5(urlencode(trim($_POST['confirm_password'])));
      }else{
        $response = array("ret"=>"104","msg"=>"confirm-password is empty.");
        echo json_encode($response);
        exit;
      }
      if($_POST['confirm_password'] != $_POST['password']){
        $response = array("ret"=>"106","msg"=>"password and confirm_password mismatch.");
        echo json_encode($response);
        exit;
      }
         
     
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
        if($old_password != $rs[0]->password){
          pg_close($db);
          $msg = "Old password mismatch";
          $respose = array('ret'=>'204','msg' => $msg);
          echo json_encode($respose);
          exit;
        }
        $update = "UPDATE user_account
                    SET password='{$password}'
                    WHERE username='{$username}';";

        $rs_update = execute($update,$db);
        pg_close($db);
        if(!$rs_update){
          $msg = "Cannot change password";
          $respose = array('ret'=>'203','msg' => $msg,'data'=>$update);
          echo json_encode($respose);
          exit;
        }else{
          $msg = "Success";
          $respose = array('ret'=>'101','msg' => $msg);
          echo json_encode($respose);
          exit;
        }
        
      }else{
        $response = array("ret"=>"202","msg"=>"Username Mismatch please try again");
        echo json_encode($response);
        exit;
      }
?>