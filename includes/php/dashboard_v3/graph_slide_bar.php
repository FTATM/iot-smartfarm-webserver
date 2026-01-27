<style>
input[type="range"]::-webkit-slider-thumb {
  appearance: none;
  width: 55px;
  height: 55px;
  background: #333333;
  border-radius: 50%;
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
  margin-top: 0px;
}

input[type="range"]:hover::-webkit-slider-thumb {
  transform: scale(1.2);
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
             WHERE b.device_id='{$rs_monitor[$j]->device_id}' AND c.group_id ='{$rs_monitor[$j]->group_id}' AND a.\"".strtoupper($array_datax[$rs_monitor[$j]->datax_id])."\" IS NOT NULL
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


//  $status_bool = $temp_value;

//   if($status_bool == '1'){
//     $status_display = "On";
//     $checked_state = "checked";
//   }else{
//     $status_display = "Off";
//     $checked_state = "";
//   }
?>

<?php $monitor_id = $btn_id;?>
<div class="w-11/12 
            min-[320px]:max-h-[180px]
            xl:w-[20%]
            xl:min-h-[309px] 
            bg-white rounded-lg 
            text-[#333333] shadow dark:bg-gray-800 p-4 md:p-6 m-3 float-left justify-center">
            <h2 class="text-[#555555]"><?php echo $item_name;?><?php echo $branch_name;?></h2>
    <label for="input_slide_<?php echo $monitor_id;?>">
      <div id="display_slide_<?php echo $monitor_id;?>" class="w-6/6 h-10 min-[320px]:text-sm min-[320px]:font-medium m-auto flex items-center justify-center border-slate-800 xl:text-5xl xl:mt-[70px]"><?php echo number_format($temp_value);?></div>
    </label>
    <br>
    <input type="range" id="input_slide_<?php echo $monitor_id;?>" class="w-[100%] h-10 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 m-auto" name="input_slide_<?php echo $monitor_id;?>" min="0" max="100" step="1" value="<?php echo $temp_value;?>" oninput="update_slidebar_val_<?php echo $monitor_id;?>('<?php echo $monitor_id;?>');" onchange="change_slidebar_val_<?php echo $monitor_id;?>('<?php echo $monitor_id;?>');" >
    <div class="xl:text-sm min-[320px]:text-[10px] font-medium text-gray-900 dark:text-gray-300 justify-center text-center"> Range 0 to 100 </div>
</div>


<!--https://uiverse.io/vinodjangid07/quick-moth-22-->

<script>
  function update_slidebar_val_<?php echo $monitor_id;?>(elm_index){
        var slide_val     = $("#input_slide_"+elm_index).val();
        // console.log(slide_val);
        var slide_display = $("#display_slide_"+elm_index).html(numberWithCommas(slide_val));
  }
  function change_slidebar_val_<?php echo $monitor_id;?>(elm_index){ 
    //Update "monitor" id = elm_index,config_value_1 = slide_val, 
      //|insert "volte_censor" location = group, name = device, DATA01
      var slide_val     = $("#input_slide_"+elm_index).val();
      // console.log(slide_val+"|"+location+"|"+name);
        var dataString = 'page1_sql_type=1'+'&dashboard_id='+elm_index+'&config_value_1='+ slide_val; //ค่าที่จะส่งไปพร้อมตัวแปร
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
              alert(jsonData.msg+"1");
            }
            } 
          });
  }
  function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
</script>
