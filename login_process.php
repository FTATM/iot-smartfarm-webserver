<?php
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
     

      // echo "<pre>";
      // print_r($_POST);exit;

      $username = "";
      $password = "";

      if(isset($_POST['username']) && $_POST['username'] !=""){
        $username = pg_escape_string($_POST['username']);
      }else{
        echo "<script>alert('UserName is not NULL');window.location.href='login.php';</script>";
        exit;
      }
      if(isset($_POST['password']) && $_POST['password'] !=""){
        $password = md5($_POST['password']);
      }else{
        echo "<script>alert('PassWord is not NULL');window.location.href='login.php';</script>";
        exit;
      }
      // echo $password;exit;
   
     
      include_once("includes/fn/pg_connect.php");
      $db = pg_connect( "$host $port $dbname $credentials"  );
      if(!$db) {
          echo "Error : Unable to open database\n";
          exit;
      }
      
      $sql ="SELECT username, password, name, url_logo, role_id,branch_id
	          FROM user_account WHERE username='{$username}' and password='{$password}' and status='1';";
      // echo $sql;exit;
      $rs = select($sql,$db);
      
      // print_r($rs);exit;
      if(count($rs)=='1'){
        $sql ="SELECT branch_id, branch_name, createtime, updatetime, status	FROM branch_info WHERE branch_id='{$rs[0]->branch_id}';";
        $rs_branch = select($sql,$db);
        if(count($rs_branch)!='1'){
          pg_close($db);
          echo "<script>alert('Invalid Branch id!');window.location.href='login.php';</script>";
          exit;
        }else if($rs_branch[0]->status!='1'){
          pg_close($db);
          echo "<script>alert('This Branch Not Active!');window.location.href='login.php';</script>";
          exit;
        }else if($rs_branch[0]->status=='1'){
          $_SESSION['username'] = $rs[0]->username;
          $_SESSION['nick_name'] = $rs[0]->name;
          $_SESSION['url_logo'] = $rs[0]->url_logo;
          $_SESSION['role_id'] = $rs[0]->role_id;
          $_SESSION['branch_id'] = $rs[0]->branch_id;
          $_SESSION['branch_name'] = $rs_branch[0]->branch_name;
          header("location: dashboard_view_v3.php");
        }
        
      }else{
        pg_close($db);
        echo "<script>alert('Username or Password wrong!!');window.location.href='login.php';</script>";
        exit;
      }
      pg_close($db);
?>