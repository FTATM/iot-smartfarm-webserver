<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Image Banner</title>
    
    <link rel="stylesheet" href="includes/css/style.css">
    <!-- <link rel="stylesheet" href="https://fgelinas.com/code/timepicker/include/ui-1.10.0/ui-lightness/jquery-ui-1.10.0.custom.min.css" type="text/css">
    <link rel="stylesheet" href="https://fgelinas.com/code/timepicker/jquery.ui.timepicker.css?v=0.3.3" type="text/css"> -->
    <style>
      #digital_sel{
        display:none;
      }

.panel {margin: 100px auto 40px; max-width: 500px; text-align: center;}
.panel_list {margin-top: 80px; text-align: center;}
.button_outer {background: #83ccd3; border-radius:30px; text-align: center; height: 50px; width: 200px; display: inline-block; transition: .2s; position: relative; overflow: hidden;}
.btn_upload {padding: 17px 30px 17px; color: #fff; text-align: center; position: relative; display: inline-block; overflow: hidden; z-index: 3; white-space: nowrap;}
.btn_upload input {position: absolute; width: 100%; left: 0; top: 0; width: 100%; height: 105%; cursor: pointer; opacity: 0;}
.file_uploading {width: 100%; height: 10px; margin-top: 20px; background: #ccc;}
.file_uploading .btn_upload {display: none;}
.processing_bar {position: absolute; left: 0; top: 0; width: 0; height: 100%; border-radius: 30px; background:#83ccd3; transition: 3s;}
.file_uploading .processing_bar {width: 100%;}
.success_box {display: none; width: 50px; height: 50px; position: relative;}
.success_box:before {content: ''; display: block; width: 9px; height: 18px; border-bottom: 6px solid #fff; border-right: 6px solid #fff; -webkit-transform:rotate(45deg); -moz-transform:rotate(45deg); -ms-transform:rotate(45deg); transform:rotate(45deg); position: absolute; left: 17px; top: 10px;}
.file_uploaded .success_box {display: inline-block;}
.file_uploaded {margin-top: 0; width: 50px; background:#83ccd3; height: 50px;}
.uploaded_file_view {max-width: 300px; margin: 40px auto; text-align: center; position: relative; transition: .2s; opacity: 0; border: 2px solid #ddd; padding: 15px;}
.file_remove{width: 30px; height: 30px; border-radius: 50%; display: block; position: absolute; background: #aaa; line-height: 30px; color: #fff; font-size: 12px; cursor: pointer; right: -15px; top: -15px;}
.file_remove:hover {background: #222; transition: .2s;}
.uploaded_file_view img {max-width: 100%;}
.uploaded_file_view.show {opacity: 1;}
.error_msg {text-align: center; color: #f00}
    </style>
    
</head>
<body>
  <?php
  //nav bar
    include_once("nav_bar.php");
  ?>
  <?php
  
    $arrayData = array(

    ); 
    if(!isset($_GET['monitor_id']) || $_GET['monitor_id']==''){
      echo "<br><br><br><br><br><br><br><center><b>You came in the wrong way.</b><center><br><br><br><br><br><br><br>";
      echo '<a href="page1.php">
                  <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                </a>';
      exit;
    }

  include_once("includes/fn/pg_connect.php");
  $db = pg_connect( "$host $port $dbname $credentials"  );
  if(!$db) {
      echo "Error : Unable to open database\n";
      exit;
  }
      $monitor_id = $_GET['monitor_id'];
      $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,is_analog_min_work,is_analog_max_work,datax_id,datax_value,monitor_name,is_digital_work
                FROM page_data_manage_monitor  WHERE status='1' AND monitor_id='{$monitor_id}' ORDER BY monitor_id DESC,sort ASC;";
                // echo $sql_monitor;exit;
      $rs_monitor = select($sql_monitor,$db);

      // echo "<pre>";
      // print_r($rs_monitor);

      if(count($rs_monitor)==0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Monitor ID : ".$monitor_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $monitor_id             = $rs_monitor[0]->monitor_id;
      $monitor_name           = $rs_monitor[0]->monitor_name==""?"-":$rs_monitor[0]->monitor_name;
      $group_id               = $rs_monitor[0]->group_id;
      $device_id              = $rs_monitor[0]->device_id;
      $type_id                = $rs_monitor[0]->type_id;
      $datax_id               = $rs_monitor[0]->datax_id;
      $is_min                 = $rs_monitor[0]->is_min;
      $min_value              = $rs_monitor[0]->min_value;
      $is_max                 = $rs_monitor[0]->is_max;
      $max_value              = $rs_monitor[0]->max_value;
      $is_input_digi          = $rs_monitor[0]->is_input_digi;
      $is_digital_zero        = $rs_monitor[0]->is_digital_zero;
      $is_line                = $rs_monitor[0]->is_line;
      $input_line             = $rs_monitor[0]->input_line;
      $is_email               = $rs_monitor[0]->is_email;
      $input_email            = $rs_monitor[0]->input_email;
      $is_sms                 = $rs_monitor[0]->is_sms;
      $input_sms              = $rs_monitor[0]->input_sms;
      $status                 = $rs_monitor[0]->status;
      $list_time_of_work      = $rs_monitor[0]->list_time_of_work; // date('w'); w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
      $is_condition           = $rs_monitor[0]->is_condition;
      $config_value_1         = $rs_monitor[0]->config_value_1;
      $config_value_2         = $rs_monitor[0]->config_value_2;
      $config_value_3         = $rs_monitor[0]->config_value_3;
      $config_value_4         = $rs_monitor[0]->config_value_4;
      $is_analog_min_work     = $rs_monitor[0]->is_analog_min_work;
      $is_analog_max_work     = $rs_monitor[0]->is_analog_max_work;
      $is_digital_work        = $rs_monitor[0]->is_digital_work;
      $array_newListTimeOfWork = array();
      if($list_time_of_work!=""){
        $array_newListTimeOfWork = explode(",",$list_time_of_work);
      }
      


      $sql_sel_group          = "SELECT group_id, group_name,value_map_volte_censor
	                        FROM page_data_manage_group WHERE group_id='{$group_id}' ORDER BY group_id ASC,sort ASC;";
      $rs_sel_group           = select($sql_sel_group,$db);
      if(count($rs_sel_group) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Group ID : ".$group_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $group_name             = $rs_sel_group[0]->group_name;
      $value_map_volte_censor = $rs_sel_group[0]->value_map_volte_censor;
      
      $sql_sel_device  = "SELECT device_id, divice_name
	                        FROM page_data_manage_device WHERE  device_id='{$device_id}' ORDER BY device_id ASC,sort ASC;";
      $rs_sel_device   = select($sql_sel_device,$db);
      if(count($rs_sel_device) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Device ID : ".$device_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $device_name             = $rs_sel_device[0]->divice_name;
      $sql_sel_type  = "SELECT type_id, type_name, image_src, data_col_name,state,operation
	                        FROM page_data_manage_type WHERE type_id='{$type_id}' ORDER BY type_id ASC,sort ASC;";
      $rs_sel_type   = select($sql_sel_type,$db);
      if(count($rs_sel_type) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find Type ID : ".$type_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }
      $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
	                          FROM page_data_manage_datax WHERE datax_id='{$datax_id}' ORDER BY datax_id ASC,sort ASC;";
      $rs_sel_datax   = select($sql_sel_datax,$db);
      if(count($rs_sel_datax) == 0){
        echo "<br><br><br><br><br><br><br><center><b>Cannot find DATA ID : ".$datax_id."</b><center><br><br><br><br><br><br><br>";
        echo '<a href="page1.php">
                    <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-46">Back to Page 1</button>
                  </a>';
        exit;
      }

      $sql_time_of_work = "SELECT monitor_sub_id,monitor_id, TO_CHAR(start_work,'HH24:MI') as start_work, TO_CHAR(end_work,'HH24:MI') as end_work FROM page_data_manage_monitor_sub WHERE status='1' AND monitor_id ='{$monitor_id}' ORDER BY monitor_sub_id ASC,updatetime ASC;";
      $rs_time_of_work   = select($sql_time_of_work,$db);


      $type_name             = $rs_sel_type[0]->type_name;
      $data_col_name         = $rs_sel_type[0]->data_col_name;
      $type_state            = $rs_sel_type[0]->state;  // A=analog, D=digital
      $type_operation        = $rs_sel_type[0]->operation; //IN = input, OUT = output
      $datax_name            = $rs_sel_datax[0]->datax_name;

      // $sql_real_value       = "SELECT  \"$data_col_name\" as data_real FROM volte_censor WHERE name ='{$device_name}' and location ='{$value_map_volte_censor}' ORDER BY create_date desc limit 1;";
      // // echo $sql_real_value;exit;
      // $rs_real_value        = select($sql_real_value,$db);


              $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                            FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
              $rs_sel_datax   = select($sql_sel_datax,$db);
              $array_datax    = array();
              if(count($rs_sel_datax)>0){
                for($j=0;$j<count($rs_sel_datax);$j++){
                  $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
                }
              }

        $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[0]->datax_id])."\" as datax
                                                            FROM volte_censor a
                                                            LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                            LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                                          WHERE b.device_id='{$rs_monitor[0]->device_id}' AND c.group_id ='{$rs_monitor[0]->group_id}'
                                                          ORDER BY a.id DESC LIMIT 1;
                                  ";
        $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);

        $analog_real_value      = 0;
        $digital_real_value     = 0;
      if(count($rs_get_datax_from_volte_censor)=='1'){
        $analog_real_value      = $rs_get_datax_from_volte_censor[0]->datax;
        $digital_real_value     = $rs_get_datax_from_volte_censor[0]->datax;
      }
      
  ?>
  <div class="bg-background text-foreground p-4">
  <p class="text-sm text-muted-foreground mb-4">Monitor ID : <?php echo $monitor_id;?></p>
  <p class="text-sm text-muted-foreground mb-4">Monitor Name : <?php echo $monitor_name;?></p>
    <h2 class="text-2xl font-bold mb-4">Change Image Banner</h2>
    <form action="page1_change_banner_process.php" name="form1" id="form1" method="POST" enctype="multipart/form-data">
    <div class="w-3/4 m-auto rounded border ">
          <div class="panel">
            <div class="button_outer">
              <div class="btn_upload">
                <input type="file" id="upload_file" name="upload_file">
                เลือกรูปภาพ
              </div>
              <div class="processing_bar"></div>
              <div class="success_box"></div>
            </div>
          </div>
          <div class="error_msg"></div>
          <div class="uploaded_file_view" id="uploaded_view">
            <span class="file_remove">X</span>
          </div>
          <div class="panel" style="margin-top: -50px;">
          <div id="interactive" class="viewport"></div>
          <br><br>
          <button type="submit" id="submit-btn" class="text-primary-foreground p-2 rounded-md hover:bg-primary/80 focus:outline-none focus:ring ring-ring" style="background-color:#000000;">Submit</button>
    </div>
    <input type="hidden" id="monitor_id" name="monitor_id" value="<?php echo $monitor_id;?>" />
  </div>
  </form>
    </div>
  
  <!-- <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/jquery-1.9.0.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.widget.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/include/ui-1.10.0/jquery.ui.position.min.js"></script>
    <script type="text/javascript" src="https://fgelinas.com/code/timepicker/jquery.ui.timepicker.js?v=0.3.3"></script> -->
  <script>
    $( document ).ready(function() {
     
    });

    var btnUpload = $("#upload_file"),
		btnOuter = $(".button_outer");
    btnUpload.on("change", function(e){
      var ext = btnUpload.val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        $(".error_msg").text("Not an Image...");
      } else {

        $(".error_msg").text("");
        btnOuter.addClass("file_uploading");
        setTimeout(function(){
          btnOuter.addClass("file_uploaded");
        },3000);
        var uploadedFile = URL.createObjectURL(e.target.files[0]);
        setTimeout(function(){
          $("#uploaded_view").append('<img src="'+uploadedFile+'" />').addClass("show");
          upload_ajax_v2(btnUpload);
        },3500);
      }
    });
	$(".file_remove").on("click", function(e){
		$("#uploaded_view").removeClass("show");
		$("#uploaded_view").find("img").remove();
		btnOuter.removeClass("file_uploading");
		btnOuter.removeClass("file_uploaded");
    $("#upload_file").val("");
	});

  $("#submit-btn").click(function( event ) {	
	  var upload_file = $("#upload_file").val();	 
    if(upload_file==""){
      alert("กรุณาเลือกรูปภาพสินค้าที่ต้องการอัพโหลดค่ะ");
      $("#upload_file").click();
      return false;
    }
	});
   
  </script>
  
</body>
</html>