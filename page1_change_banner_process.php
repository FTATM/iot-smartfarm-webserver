<?php
 include_once("includes/fn/pg_connect.php");
 
 $db = pg_connect( "$host $port $dbname $credentials"  );

 if(!$db) {
     $msg = "Error : Unable to open database";
     $respose = array('ret'=>'100','msg'=>$msg);
     echo json_encode($respose);
     exit;
 }
    
    $monitor_id = "";
    if(isset($_POST['monitor_id']) && $_POST['monitor_id'] !=""){
      $monitor_id = $_POST['monitor_id'];
    }else{
      $msg = "Param monitor_id is NULL";
      $respose = array('ret'=>'105','msg'=>$msg);
      echo json_encode($respose);
      pg_close($db);
      exit;
    }
   
    if($monitor_id != ''){
      if(isset($_FILES)){
        $target_dir = "img/monitor_banner/";
         $file=$_FILES["upload_file"];
         $target_file = basename($file["name"]);
      
         if(($file["size"] > 10000000)){
           //10000000 = 10 MB
          $dataReturn = json_encode(array("ret_code" => "201","status" => "Sorry file size limit 10MB."));
          echo $dataReturn;
          pg_close($db);
          exit;
        }
        
      
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         //µÃÇ¨ÊÍº ¹ÒÁÊ¡ØÅä¿Åì
         if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "mp4" && $imageFileType != "zip" && $imageFileType != "svga") {
          $dataReturn = json_encode(array("ret_code" => "200","status" => "Sorry, only JPG, JPEG, PNG, GIF, SVGA & MP4 files are allowed.","This file"=>$imageFileType));
          echo $dataReturn;
          pg_close($db);
          exit;
        }
        $randNum = RandomString();
        $goto = $target_dir.date('Ymdhis').$randNum.md5($file["name"]).".".$imageFileType;
         $gotoUrl = $target_dir."/".date('Ymdhis').$randNum.md5($file["name"]).".".$imageFileType;
         //@move_uploaded_file($file["tmp_name"],$goto);  //ÂéÒÂä¿Åì¨Ò¡ tmp ÁÒäÇé·Õè directory ·ÕèµéÍ§¡ÒÃ
         copy($file["tmp_name"],$goto);
        $returnDir = $goto;
        // echo $file["tmp_name"];
        // echo "<hr>";
        // echo $goto;
        // exit;
        //$returnDir = "https://image.takeme.in.th/".$gotoUrl;
        //ÍÑ¾âËÅ´ÊÓàÃç¨
        $dataReturn = json_encode(array("ret_code" => "101","status" => "Success","image" => $returnDir));
        echo $dataReturn;
         //$myfile = file_put_contents('/home/www/takeme_web_images/upload/logs/logs'.date("Ymd").'.txt', $dataReturn.PHP_EOL , FILE_APPEND | LOCK_EX);
        //exit;
        
      }else{
          $dataReturn = json_encode(array("ret_code" => "202","status" => "Sorry file not empty."));
          echo $dataReturn;
          pg_close($db);
          exit;
      }
      $update_monitor = "UPDATE page_data_manage_monitor
                  SET  image_banner='{$returnDir}'
                  WHERE monitor_id='{$monitor_id}';";
      $rs_update_monitor = execute($update_monitor,$db);
      if(!$rs_update_monitor){
        $msg = "Update monitor fail";
        $respose = array('ret'=>'103','msg' => $msg);
        echo json_encode($respose);
        exit;
      }
    }

    // $msg = "success";
    // $respose = array('ret'=>'101','msg' => $msg);
    // echo json_encode($respose);
    

pg_close($db);
header('Location: page1.php');
exit();

function RandomString(){
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randstring = '';
  for ($i = 0; $i < 10; $i++) {
      $randstring = $characters[rand(0, strlen($characters))];
  }
  return $randstring;
}
?>  