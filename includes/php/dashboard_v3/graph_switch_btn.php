<style>
 /* From Uiverse.io by vinodjangid07 */ 
#checkbox_btn<?php echo $btn_id;?> {
  /* display: none; */
}

.switch_btn1 {
  position: relative;
  width: 70px;
  height: 70px;
  background-color: rgb(99, 99, 99);
  border-radius: 50%;
  z-index: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid rgb(126, 126, 126);
  box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
}
.switch_btn1 svg {
  width: 1.2em;
}
.switch_btn1 svg path {
  fill: rgb(48, 48, 48);
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 {
  box-shadow: 0px 0px 1px rgb(151, 243, 255) inset,
    0px 0px 2px rgb(151, 243, 255) inset, 0px 0px 10px rgb(151, 243, 255) inset,
    0px 0px 40px rgb(151, 243, 255), 0px 0px 100px rgb(151, 243, 255),
    0px 0px 5px rgb(151, 243, 255);
  border: 2px solid rgb(255, 255, 255);
  background-color: rgb(146, 180, 184);
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg {
  filter: drop-shadow(0px 0px 5px rgb(151, 243, 255));
}
#checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg path {
  fill: rgb(255, 255, 255);
}
@media screen and (max-width: 1024px) {
  .switch_btn1 {
      position: relative;
      width: 35px;
      height: 35px;
      background-color: rgb(99, 99, 99);
      border-radius: 50%;
      z-index: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgb(126, 126, 126);
      box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
    }
}
@media screen and (max-width: 768px) {
  .switch_btn1 {
      position: relative;
      width: 35px;
      height: 35px;
      background-color: rgb(99, 99, 99);
      border-radius: 50%;
      z-index: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgb(126, 126, 126);
      box-shadow: 0px 0px 3px rgb(2, 2, 2) inset;
    }
    .switch_btn1 svg {
      width: 1.2em;
    }
    .switch_btn1 svg path {
      fill: rgb(48, 48, 48);
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 {
      box-shadow: 0px 0px 1px rgb(151, 243, 255) inset,
        0px 0px 2px rgb(151, 243, 255) inset, 0px 0px 10px rgb(151, 243, 255) inset,
        0px 0px 40px rgb(151, 243, 255), 0px 0px 100px rgb(151, 243, 255),
        0px 0px 5px rgb(151, 243, 255);
      border: 2px solid rgb(255, 255, 255);
      background-color: rgb(146, 180, 184);
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg {
      filter: drop-shadow(0px 0px 5px rgb(151, 243, 255));
    }
    #checkbox_btn<?php echo $btn_id;?>:checked + .switch_btn1 svg path {
      fill: rgb(255, 255, 255);
    }
}
</style>
<?php
  
  /**
   * $dashboard_id
   * 
   */
  // $dashboard_id=7;
  $monitor_id = array();
  $data_show = array();
  $label_name = array();
  $label_color_code = array();
  $sql_sub   = "SELECT monitor_id,label_name,label_color_code
                   FROM dashboard_item_sub_data WHERE dashboard_item_id ='{$dashboard_id}';";
  $rs_sub    = select($sql_sub,$db);
 //  print_r($rs_sub);
  if(count($rs_sub)>0){
     for($j=0;$j<count($rs_sub);$j++){
       $monitor_id[] = $rs_sub[$j]->monitor_id;
       $label_name[$rs_sub[$j]->monitor_id][]        = $rs_sub[$j]->label_name;
       $label_color_code[$rs_sub[$j]->monitor_id][]  = $rs_sub[$j]->label_color_code;
     }
  }
 //  print_r($monitor_id);exit;
 //  echo join(",",$monitor_id);exit;
 $monitor_id_x  = join(",",$monitor_id);
  $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,is_analog_min_work,is_analog_max_work,datax_id,datax_value,monitor_name,is_digital_work
               FROM page_data_manage_monitor  WHERE status='1' AND monitor_id in({$monitor_id_x}) ORDER BY monitor_id DESC,sort ASC;";
               // echo $sql_monitor;exit;
 $rs_monitor = select($sql_monitor,$db);
 if(count($rs_monitor)>0){
   $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                           FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
     $rs_sel_datax   = select($sql_sel_datax,$db);
     $array_datax    = array();
     if(count($rs_sel_datax)>0){
       for($j=0;$j<count($rs_sel_datax);$j++){
         $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
       }
     }
     for($j=0;$j<count($rs_monitor);$j++){
         $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" as datax
               FROM volte_censor a
               LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
               LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
             WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}'
             ORDER BY a.id DESC LIMIT 1;
           ";
           $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
           if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax != ""){
             $data_show[$rs_monitor[$j]->monitor_id][]  = $rs_get_datax_from_volte_censor[0]->datax;
           }else{
             $data_show[$rs_monitor[$j]->monitor_id][]  = 0;
           } 
     }
 }

 if(count($monitor_id)>0){
   for($j=0;$j<count($monitor_id);$j++){
     $temp_value = $data_show[$monitor_id[$j]][0];
     $label_name_show = $label_name[$monitor_id[$j]][0];
   }
 }


 $status_bool = $temp_value;

  if($status_bool == '1'){
    $status_display = "On";
    $checked_state = "checked";
  }else{
    $status_display = "Off";
    $checked_state = "";
  }
?>
<div class="w-11/12 
            min-[320px]:w-[25.9%] min-[320px]:h-[100px]
            xl:w-44 2xl:w-[11.5%] 
            xl:min-h-[309px] 
            bg-white rounded-lg shadow dark:bg-gray-800 m-3 float-left justify-center">
    <div class="justify-center text-center min-[320px]:text-[10px] text-[#777777] xl:text-[20px]">
      <!-- <div><p>efan<?php echo $btn_id;?></p></div>
      <div><b>(sensor<?php echo $btn_id;?>)</b></div> -->
     <div><p><?php echo $item_name;?><?php echo $branch_name;?></p></div>
      
    </div>
    <input id="checkbox_btn<?php echo $btn_id;?>" type="checkbox" style="display:none;" <?php echo $checked_state;?> onclick="sent_sw_change_<?php echo $dashboard_id;?>('<?php echo $dashboard_id;?>')" onclick="sent_sw_change_<?php echo $dashboard_id;?>('<?php echo $dashboard_id;?>')" />
    <label class="m-auto xl:mt-[80px] min-[320px]:mt-[5px] switch_btn1 " for="checkbox_btn<?php echo $btn_id;?>">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="slider">
        <path
          d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"
        ></path>
      </svg>
    </label>
    <div class="flex justify-center min-[320px]:mt-[1px] mt-[1px] text-[#555555] xl:mt-[20px]">
      <div class="min-[320px]:mt-[2px] min-[320px]:text-[10px] xl:text-[20px]"><p id="status_sent_sw_<?php echo $dashboard_id;?>"><?php echo $status_display;?></p></div>
    </div>
</div>
<?php
 /**
  * clear data
  */
  $label_name_show = "";
  $temp_value = 0;

?>

<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  function sent_sw_change_<?php echo $dashboard_id;?>(elm_index){ //page1_sql_type = 2
      //Update "monitor" id = elm_index,config_value_2 = isChecked 
      //|insert "volte_censor" location = group, name = device, DATA02
      var sent_sw_isChecked     = $("#checkbox_btn"+elm_index).is(":checked");
        // if(sent_sw_isChecked){
        //   $("#sent_sw_"+elm_index).prop('checked', false);
        // }else{
        //   $("#sent_sw_"+elm_index).prop('checked', true);
        // }
      

      var isChecked = 0;
      if(sent_sw_isChecked){
        isChecked = 1;
      }
      if(isChecked=='1'){
        $("#status_sent_sw_<?php echo $dashboard_id;?>").html('On');
      }else{
        $("#status_sent_sw_<?php echo $dashboard_id;?>").html('Off');
      }
      // console.log(sent_sw_isChecked);
      var dataString = 'page1_sql_type=2'+'&dashboard_id='+elm_index+'&config_value_2='+ isChecked; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_dashboard_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"2");
              }
            } 
          });

    }
</script>
