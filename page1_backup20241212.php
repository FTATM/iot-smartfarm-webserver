<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 1</title>
    
    <link rel="stylesheet" href="includes/css/style.css">
</head>
<body class="bg-background text-foreground">
<?php
  //nav bar
    include_once("nav_bar.php");
  ?>
  <?php
  include_once("includes/fn/pg_connect.php");
    

    $db = pg_connect( "$host $port $dbname $credentials"  );

      if(!$db) {
          echo "Error : Unable to open database\n";
          exit;
      }

      $sql_monitor ="SELECT monitor_id, group_id, device_id, type_id, is_min, min_value, is_max, max_value, is_input_digi, is_digital_zero, is_line, input_line, is_email, input_email, is_sms, input_sms, createtime, updatetime, status, list_time_of_work,is_condition, config_value_1, config_value_2, config_value_3, config_value_4,datax_id,datax_value,monitor_name
                FROM page_data_manage_monitor  WHERE status='1' ORDER BY monitor_id DESC,sort ASC;";
                // echo $sql_monitor;exit;
      $rs_monitor = select($sql_monitor,$db);
      
      $sql_sel_group  = "SELECT group_id, group_name,value_map_volte_censor
	                        FROM page_data_manage_group WHERE status='1' ORDER BY group_id ASC,sort ASC;";
      $rs_sel_group   = select($sql_sel_group,$db);
      $sql_sel_device  = "SELECT device_id, divice_name
	                        FROM page_data_manage_device WHERE status='1' ORDER BY device_id ASC,sort ASC;";
      $rs_sel_device   = select($sql_sel_device,$db);
      $sql_sel_type  = "SELECT type_id, type_name, image_src, data_col_name
	                        FROM public.page_data_manage_type WHERE status='1' ORDER BY type_id ASC,sort ASC;";
      $rs_sel_type   = select($sql_sel_type,$db);
      $array_type    = array();
      if(count($rs_sel_type)>0){
        for($j=0;$j<count($rs_sel_type);$j++){
          $array_type[$rs_sel_type[$j]->type_id] = $rs_sel_type[$j]->image_src;
        }
      }

      $sql_sel_datax  = "SELECT datax_id, datax_name, create_time, update_time, sort, status
                            FROM page_data_manage_datax WHERE status='1' ORDER BY datax_id ASC,sort ASC;";
      $rs_sel_datax   = select($sql_sel_datax,$db);
      $array_datax    = array();
      if(count($rs_sel_datax)>0){
        for($j=0;$j<count($rs_sel_datax);$j++){
          $array_datax[$rs_sel_datax[$j]->datax_id] = $rs_sel_datax[$j]->datax_name;
        }
      }
      // echo "<pre>";
      // print_r($array_datax);
      ?>
    <header class="container mx-auto py-16 text-center">
        <!-- <h1 class="text-4xl md:text-6xl font-bold mb-4">Find Your Perfect Item</h1>
        <p class="text-lg md:text-xl text-secondary">Book from a wide range of items for your needs</p>
        <div class="mt-8">
            <input type="text" placeholder="Search for items" class="w-full md:w-1/2 px-4 py-2 rounded-lg focus:outline-none focus:ring focus:ring-primary" />
            <button class="bg-primary text-primary-foreground px-4 py-2 ml-2 rounded-lg">Search</button>
        </div> -->
        <button class="bg-primary text-primary-foreground px-4 py-2 rounded-lg w-56"><a href="page1_add_box_monitor.php">Add Task Monitoring</a></button>
    </header>
    

    <section class="bg-card text-card-foreground py-16">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
              if(count($rs_monitor)>0){  
                for($i=0;$i<count($rs_monitor);$i++){

               
            ?>
            <div class="bg-white rounded-lg shadow-md p-4" id="monitor_group_elm_<?php echo $rs_monitor[$i]->monitor_id;?>">
            
                <img src="includes/images/<?php echo $array_type[$rs_monitor[$i]->type_id];?>" alt="Item 1" id="img_device_<?php echo $rs_monitor[$i]->monitor_id;?>" class="w-full h-40 object-cover rounded-lg mb-4" />
                <p class="text-sm text-muted-foreground mb-4">
                  Monitor ID : <?php echo $rs_monitor[$i]->monitor_id;?>
                    
                </p>
                <label class="inline-flex items-center cursor-pointer">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Enable Edit </span>
                <input type="checkbox" value=""  id="enable_config_<?php echo $rs_monitor[$i]->monitor_id;?>" class="sr-only peer" onclick="isEnableDropDown('<?php echo $rs_monitor[$i]->monitor_id;?>')" />
                &nbsp;&nbsp;
                  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
                </label>
                <br><br>
                <h2 class="text-lg font-bold mb-2">Monitor Name : 
                <input type="text" id="monitor_name_input_<?php echo $rs_monitor[$i]->monitor_id;?>" class="w-2/4 p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" oninput="update_monitor_name_val('<?php echo $rs_monitor[$i]->monitor_id;?>');" value="<?php echo $rs_monitor[$i]->monitor_name==""?"-":$rs_monitor[$i]->monitor_name;?>" disabled>
                </h2>
                <div id="hiden_zone_<?php echo $rs_monitor[$i]->monitor_id;?>" class="hidden">
                <h2 class="text-lg font-bold mb-2">
                  1. Group : <select id="sel_group_<?php echo $rs_monitor[$i]->monitor_id;?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="sel_group_change('<?php echo $rs_monitor[$i]->monitor_id;?>');" disabled>
                  <option value="-">Choose a Group</option>
                    <?php
                      if(count($rs_sel_group)>0){
                        for($g=0;$g<count($rs_sel_group);$g++){
                          if($rs_sel_group[$g]->group_id==$rs_monitor[$i]->group_id){$selected = "selected";}else{$selected = "";}
                          echo '<option value="'.$rs_sel_group[$g]->group_id.'" '.$selected.'>'.$rs_sel_group[$g]->group_name.'['.$rs_sel_group[$g]->value_map_volte_censor.']</option>';
                        }
                      }
                    ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
                </h2>
                <h2 class="text-lg font-bold mb-2">
                  2. Device : <select id="sel_device_<?php echo $rs_monitor[$i]->monitor_id;?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="sel_device_change('<?php echo $rs_monitor[$i]->monitor_id;?>');" disabled>
                  <option value="-">Choose a Device</option>
                    <?php
                      if(count($rs_sel_device)>0){
                        for($d=0;$d<count($rs_sel_device);$d++){
                          if($rs_sel_device[$d]->device_id==$rs_monitor[$i]->device_id){$selected = "selected";}else{$selected = "";}
                          echo '<option value="'.$rs_sel_device[$d]->device_id.'" '.$selected.'>'.$rs_sel_device[$d]->divice_name.'</option>';
                        }
                      }
                    ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
                </h2>
                <h2 class="text-lg font-bold mb-2">
                  3. Type : <select id="sel_type_<?php echo $rs_monitor[$i]->monitor_id;?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="sel_type_change('<?php echo $rs_monitor[$i]->monitor_id;?>');" disabled>
                  <option value="-">Choose a Type</option>
                    <?php
                      if(count($rs_sel_type)>0){
                        for($t=0;$t<count($rs_sel_type);$t++){
                          if($rs_sel_type[$t]->type_id==$rs_monitor[$i]->type_id){$selected = "selected";}else{$selected = "";}
                          echo '<option value="'.$rs_sel_type[$t]->type_id.'" '.$selected.'>'.$rs_sel_type[$t]->type_name.'</option>';
                        }
                      }
                    ?>
                </select>
                <h2 class="text-lg font-bold mb-2">
                  4. Data : <select id="sel_datax_<?php echo $rs_monitor[$i]->monitor_id;?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="sel_datax_change('<?php echo $rs_monitor[$i]->monitor_id;?>');" disabled>
                  <option value="-">Choose a Data</option>
                    <?php
                      if(count($rs_sel_datax)>0){
                        for($dx=0;$dx<count($rs_sel_datax);$dx++){
                          if($rs_sel_datax[$dx]->datax_id==$rs_monitor[$i]->datax_id){$selected = "selected";}else{$selected = "";}
                          echo '<option value="'.$rs_sel_datax[$dx]->datax_id.'" '.$selected.'>'.$rs_sel_datax[$dx]->datax_name.'</option>';
                        }
                      }
                    ?>
                </select>
                </h2>     
              </div>
                <hr>
                <div id="action_zone_<?php echo $rs_monitor[$i]->monitor_id;?>">
                    <?php
                      if($rs_monitor[$i]->type_id=='1'){ //DATA01
                    ?>
                        <label for="input_slide_<?php echo $rs_monitor[$i]->monitor_id;?>">
                          <div id="display_slide_<?php echo $rs_monitor[$i]->monitor_id;?>" class="w-2/4 m-auto flex items-center justify-center border-slate-800 text-5xl"><?php echo number_format($rs_monitor[$i]->datax_value);?></div>
                        </label>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-300"> Range 0 to 9999 : </span>
                        <input type="range" id="input_slide_<?php echo $rs_monitor[$i]->monitor_id;?>" class="w-2/4 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" name="input_slide_<?php echo $rs_monitor[$i]->monitor_id;?>" min="0" max="9999" value="<?php echo $rs_monitor[$i]->datax_value;?>" oninput="update_slidebar_val('<?php echo $rs_monitor[$i]->monitor_id;?>');" onchange="change_slidebar_val('<?php echo $rs_monitor[$i]->monitor_id;?>');" disabled>
                    <?php
                      }else if($rs_monitor[$i]->type_id=='2'){ //DATA02
                        ?>
                        <br>
                        <label class="inline-flex items-center cursor-pointer">
                      <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Sent S.W. </span>
                      <input type="checkbox" value=""  id="sent_sw_<?php echo $rs_monitor[$i]->monitor_id;?>" class="sr-only peer" <?php if($rs_monitor[$i]->datax_value=='1'){echo "checked";} ?> onclick="sent_sw_change('<?php echo $rs_monitor[$i]->monitor_id;?>')" disabled />
                      &nbsp;&nbsp;
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
                      </label>
                      <br><br>
                        <?php
                      }else if($rs_monitor[$i]->type_id=='3'){ //DATA03
                        ?>
                        <br>
                        <div class="font-bold mb-2 float-left w-1/4 text-2xl">Display:</div>
                        <div class="font-medium text-gray-900 dark:text-gray-300 w-3/4 text-center float-left text-5xl" id="display_value_<?php echo $rs_monitor[$i]->monitor_id;?>">
                          <?php 
                            if($rs_monitor[$i]->datax_id!='0'){
                              if(count($array_datax)==0){
                                echo "ไม่สามารถดึงค่า DATAx ได้";
                              }
                              $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$i]->datax_id])."\" as datax
                                                          FROM volte_censor a
                                                          LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                          LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                                        WHERE b.device_id='{$rs_monitor[$i]->device_id}' AND c.group_id ='{$rs_monitor[$i]->group_id}' AND a.\"".strtoupper($array_datax[$rs_monitor[$i]->datax_id])."\" IS NOT NULL
                                                        ORDER BY a.id DESC LIMIT 1;
                                ";
                                $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
                              if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax != ""){
                                echo number_format($rs_get_datax_from_volte_censor[0]->datax);
                              }else{
                                echo '0';
                              }
                            }else{
                              echo "[-กรุณาเลือก DATA-]";
                            }
                          ?>
                        </div>
                        <br>
                        <br>
                        <?php
                      }else if($rs_monitor[$i]->type_id=='4'){ //DATA04
                        ?>
                        <br>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Lamp : 
                          <span id="show_lamp_state_<?php echo $rs_monitor[$i]->monitor_id;?>" class="text-5xl">
                          <?php 
                              if($rs_monitor[$i]->datax_id!='0'){
                                if(count($array_datax)==0){
                                  echo "ไม่สามารถดึงค่า DATAx ได้";
                                }
                                $sql_get_datax_from_volte_censor = "SELECT a.name as device,a.location as group,a.\"".strtoupper($array_datax[$rs_monitor[$i]->datax_id])."\" as datax
                                                            FROM volte_censor a
                                                            LEFT JOIN page_data_manage_device b ON a.name = b.divice_name
                                                            LEFT JOIN page_data_manage_group c ON a.location = c.value_map_volte_censor
                                                          WHERE b.device_id='{$rs_monitor[$i]->device_id}' AND c.group_id ='{$rs_monitor[$i]->group_id}'
                                                          ORDER BY a.id DESC LIMIT 1;
                                  ";
                                  $rs_get_datax_from_volte_censor = select($sql_get_datax_from_volte_censor,$db);
                                if(count($rs_get_datax_from_volte_censor)=='1' && $rs_get_datax_from_volte_censor[0]->datax =='1'){
                                  echo "🟢";
                                }else{
                                  echo "🔴";
                                }
                              }else{
                                echo "[-กรุณาเลือก DATA-]";
                              }
                          ?>
                          </span>
                        </span>
                        <?php
                      }
                    ?>
                </div>
                <hr>
                <br>
                <label class="inline-flex items-center cursor-pointer">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Active setting </span>
                <input type="checkbox" value=""  id="action_config_<?php echo $rs_monitor[$i]->monitor_id;?>" class="sr-only peer" <?php if($rs_monitor[$i]->is_condition=='1'){echo "checked";} ?> onclick="active_set_change('<?php echo $rs_monitor[$i]->monitor_id;?>')" />
                &nbsp;&nbsp;
                  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                  &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>
                </label>
                
                <!-- <p class="text-sm text-muted-foreground mb-4">Category</p> -->
                 <br><br>

                <div style="text-align:center;">
                <a href="page2.php?monitor_id=<?php echo $rs_monitor[$i]->monitor_id;?>">
                  <button class=" align-middle bg-primary text-primary-foreground px-4 py-2 rounded-lg w-1/3 self-auto">⚙️Setting</button>
                </a>
                <button
                    class="align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-1/3 max-w-[350px] h-10 max-h-[350px] rounded-lg text-xs bg-red-500 text-white shadow-md shadow-red-500/20 hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none self-auto"
                    type="button"
                    onclick="confirm_del_monitor('<?php echo $rs_monitor[$i]->monitor_id;?>');"
                  >✖️Remove</button>
                  </div>
            </div>
            <?php
               }  
            }else{
                ?>
                <p class="text-sm text-muted-foreground w-full">No data monitoring</p>
                <?php
              }
            ?>
        </div>
    </section>

  <?php    pg_close($db); ?>
  
  <script>
    $( document ).ready(function() {
      
      // timer_get_realtime_data();

      var refresh_data_every_sec = 3; 

      setInterval(function() {
        timer_get_realtime_data();
      }, refresh_data_every_sec*1000 );
      
    });

    const types_data = [];
    <?php
      if(count($rs_sel_type)>0){
        for($i=0;$i<count($rs_sel_type);$i++){
          echo "types_data[".$rs_sel_type[$i]->type_id."] = 'includes/images/".$rs_sel_type[$i]->image_src."';\n";
        }
      }
    ?>
   
    function update_slidebar_val(elm_index){
      var slide_val     = $("#input_slide_"+elm_index).val();
      var slide_display = $("#display_slide_"+elm_index).html(numberWithCommas(slide_val));
    }
    function change_slidebar_val(elm_index){ //page1_sql_type = 1
      //Update "monitor" id = elm_index,config_value_1 = slide_val, 
      //|insert "volte_censor" location = group, name = device, DATA01
      var slide_val     = $("#input_slide_"+elm_index).val();
      var data_location      = $("#sel_group_"+elm_index).find(":selected").val();
      var data_name          = $("#sel_device_"+elm_index).find(":selected").val();
      var datax_id          = $("#sel_datax_"+elm_index).find(":selected").val();
      if(datax_id=='-'){
        $("#sel_datax_"+elm_index).focus();
        alert('Please select DATA Column! 1');
        return false;
      }
      // console.log(slide_val+"|"+location+"|"+name);
        var dataString = 'page1_sql_type=1'+'&monitor_id='+elm_index+'&config_value_1='+ slide_val+'&data_location='+data_location+'&data_name='+data_name+'&datax_id='+datax_id; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
            type: "POST", //METHOD "GET","POST"
            url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
    function sent_sw_change(elm_index){ //page1_sql_type = 2
      //Update "monitor" id = elm_index,config_value_2 = isChecked 
      //|insert "volte_censor" location = group, name = device, DATA02
      var sent_sw_isChecked     = $("#sent_sw_"+elm_index).is(":checked");
      var data_location         = $("#sel_group_"+elm_index).find(":selected").val();
      var data_name             = $("#sel_device_"+elm_index).find(":selected").val();
      var datax_id              = $("#sel_datax_"+elm_index).find(":selected").val();
      if(datax_id=='-'){
        // console.log(sent_sw_isChecked);
        if(sent_sw_isChecked){
          $("#sent_sw_"+elm_index).prop('checked', false);
        }else{
          $("#sent_sw_"+elm_index).prop('checked', true);
        }
        $("#sel_datax_"+elm_index).focus();
        alert('Please select DATA Column! 2');
        return false;
      }
      var isChecked = 0;
      if(sent_sw_isChecked){
        isChecked = 1;
      }
      // console.log(sent_sw_isChecked);
      var dataString = 'page1_sql_type=2'+'&monitor_id='+elm_index+'&config_value_2='+ isChecked+'&data_location='+data_location+'&data_name='+data_name+'&datax_id='+datax_id; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
    function active_set_change(elm_index){ //page1_sql_type = 3
      //Update "monitor" id = elm_index,is_condition = isChecked
      var action_config_isChecked     = $("#action_config_"+elm_index).is(":checked");
      var isChecked                   = 0;
      if(action_config_isChecked){
        isChecked = 1;
      }
      // console.log(action_config_isChecked);
      var dataString = 'page1_sql_type=3'+'&monitor_id='+elm_index+'&is_condition='+ isChecked; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
                alert(jsonData.msg+"3");
              }
            } 
          });

    }
    function sel_group_change(elm_index){ 
      //page1_sql_type = 4
      //Update "monitor" id = elm_index,group_id = sel_group
      var sel_group         = $("#sel_group_"+elm_index).find(":selected").val();
      if(sel_group=='-'){
        return false;
      }
      var dataString = 'page1_sql_type=4'+'&monitor_id='+elm_index+'&group_id='+ sel_group; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
                alert(jsonData.msg+"4");
              }
            } 
          });
    }
    function sel_device_change(elm_index){ 
      //page1_sql_type = 5
      //Update "monitor" id = elm_index,device_id = sel_device
      var sel_device         = $("#sel_device_"+elm_index).find(":selected").val();
      if(sel_device=='-'){
        return false;
      }
      var dataString = 'page1_sql_type=5'+'&monitor_id='+elm_index+'&device_id='+ sel_device; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
                alert(jsonData.msg+"5");
              }
            } 
          });
    }
    function sel_type_change(elm_index){ 
      //page1_sql_type = 6
      //Update "monitor" id = elm_index,type_id = sel_device
      var sel_type         = $("#sel_type_"+elm_index).find(":selected").val();
      if(sel_type=='-'){
        return false;
      }
      var dataString = 'page1_sql_type=6'+'&monitor_id='+elm_index+'&type_id='+ sel_type; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
                $("#img_device_"+elm_index).attr("src",types_data[sel_type]);
                var html_type_change = "";

                if(sel_type=='1'){
                  html_type_change = '<label for="input_slide_'+elm_index+'"><div id="display_slide_'+elm_index+'" class="w-2/4 m-auto flex items-center justify-center border-slate-800 text-5xl">0</div></label><span class="text-sm font-medium text-gray-900 dark:text-gray-300"> Range 0 to 9999 : </span><input type="range" id="input_slide_'+elm_index+'" class="w-2/4 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" name="input_slide_'+elm_index+'" min="0" max="9999" value="0" oninput="update_slidebar_val(\''+elm_index+'\');" onchange="change_slidebar_val(\''+elm_index+'\');">';
                }else if(sel_type=='2'){
                  html_type_change = '<br>';
                  html_type_change +='     <label class="inline-flex items-center cursor-pointer">';
                  html_type_change +='    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">Sent S.W. </span>';
                  html_type_change +='    <input type="checkbox" value=""  id="sent_sw_'+elm_index+'" class="sr-only peer" onclick="sent_sw_change(\''+elm_index+'\')" />';
                  html_type_change +='    &nbsp;&nbsp;';
                  html_type_change +='      <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>';
                  html_type_change +='      &nbsp;&nbsp;<span class="text-sm font-medium text-gray-900 dark:text-gray-300">Off/On</span>';
                   html_type_change +='   </label><br><br>';
                }else if(sel_type=='3'){
                   html_type_change =' <br><div class="text-lg font-bold mb-2 float-left w-1/4">Display:</div><div class="font-medium text-gray-900 dark:text-gray-300 w-3/4 text-center float-left" id="display_value_'+elm_index+'"> '+numberWithCommas(jsonData.data_state)+' </div><br><br>';
                }else if(sel_type=='4'){
                  if(jsonData.data_state=='1'){
                    html_type_change ='<br><span class="text-sm font-medium text-gray-900 dark:text-gray-300 text-5xl">Lamp : 🟢</span>';
                  }else{
                    html_type_change ='<br><span class="text-sm font-medium text-gray-900 dark:text-gray-300 text-5xl">Lamp : 🔴</span>';
                  }
                   
                }else{
                  alert("This type is not found!");
                  return false;
                }


                $("#action_zone_"+elm_index).html(html_type_change);
              }
              else{
                alert(jsonData.msg+"6");
              }
            } 
          });
    }
    function sel_datax_change(elm_index){ 
      //page1_sql_type = 7
      //Update "monitor" id = elm_index,datax_id = sel_device
      var sel_datax         = $("#sel_datax_"+elm_index).find(":selected").val();
      if(sel_datax=='-'){
        return false;
      }
      var dataString = 'page1_sql_type=7'+'&monitor_id='+elm_index+'&datax_id='+ sel_datax; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
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
                alert(jsonData.msg+"7");
              }
            } 
          });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function isEnableDropDown(elm_index){
      var isEnableDropDownChecked     = $("#enable_config_"+elm_index).is(":checked");
      if(isEnableDropDownChecked){
        $("#sel_group_"+elm_index).prop("disabled", false);  
        $("#sel_device_"+elm_index).prop("disabled", false);
        $("#sel_type_"+elm_index).prop("disabled", false);
        $("#sel_datax_"+elm_index).prop("disabled", false);
        $("#monitor_name_input_"+elm_index).prop("disabled", false);
        $("#input_slide_"+elm_index).prop("disabled", false);
        $("#sent_sw_"+elm_index).prop("disabled", false);
        $("#hiden_zone_"+elm_index).show();
      }else{
        $("#sel_group_"+elm_index).prop("disabled", true);
        $("#sel_device_"+elm_index).prop("disabled", true);
        $("#sel_type_"+elm_index).prop("disabled", true);
        $("#sel_datax_"+elm_index).prop("disabled", true);
        $("#monitor_name_input_"+elm_index).prop("disabled", true);
        $("#input_slide_"+elm_index).prop("disabled", true);
        $("#sent_sw_"+elm_index).prop("disabled", true);
        $("#hiden_zone_"+elm_index).hide();
      }
    }
    function confirm_del_monitor(elm_index){
      if(confirm("ต้องการลบ Group monitor ID : "+elm_index) == true){
        var dataString = 'page1_sql_type=8'+'&monitor_id='+elm_index; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                $("#monitor_group_elm_"+elm_index).hide();
                console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"8");
              }
            } 
          });
         
        
      }else{
        console.log("0");
      }
    }
    function update_monitor_name_val(elm_index){
      var monitor_name_input = "";
      monitor_name_input = $("#monitor_name_input_"+elm_index).val();
      if (typeof(monitor_name_input) == 'undefined' || monitor_name_input == null){
        monitor_name_input = '-';
      }
      console.log(monitor_name_input);
      var dataString = 'page1_sql_type=9'+'&monitor_id='+elm_index+'&monitor_name_input='+monitor_name_input; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_page1_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"9");
              }
            } 
          });
    }
    function timer_get_realtime_data(){
      // var display_value_ = $("#display_value_").html();
      $('*[id*=display_value_]:visible').each(function() {
        var elm_id = $(this).attr('id');
        var array_elm_id = elm_id.split("_");
        // console.log(array_elm_id[2]);
        var data_realtime = 0;
        var monitor_id = array_elm_id[2];
        var dataString = 'get_realtime_type=1'+'&monitor_id='+monitor_id; //ค่าที่จะส่งไปพร้อมตัวแปร
            $.ajax ({
                type: "POST", //METHOD "GET","POST"
                url: "ajax_get_realtime_data_process.php", //File ที่ส่งค่าไปหา
                data: dataString,
                //cache: false,
                success: function(data) {
                // alert(data);
                var jsonData = JSON.parse(data);
                if(jsonData.ret=='101'){
                  // alert(jsonData.msg);
                  var data_realtime = jsonData.data_realtime;
                  // console.log(jsonData.data_realtime);
                  $("#display_value_"+monitor_id).html(numberWithCommas(data_realtime));
                  // $("#digital_real_value").val(data_realtime);
                }
                else{
                  alert(jsonData.msg+"7");
                }
              } 
            });
      });
      $('*[id*=show_lamp_state_]:visible').each(function() {
        var elm_id = $(this).attr('id');
        var array_elm_id = elm_id.split("_");
        // console.log(array_elm_id[3]);
        var monitor_id = array_elm_id[3];
        var dataString = 'get_realtime_type=1'+'&monitor_id='+monitor_id; //ค่าที่จะส่งไปพร้อมตัวแปร
            $.ajax ({
                type: "POST", //METHOD "GET","POST"
                url: "ajax_get_realtime_data_process.php", //File ที่ส่งค่าไปหา
                data: dataString,
                //cache: false,
                success: function(data) {
                // alert(data);
                var jsonData = JSON.parse(data);
                if(jsonData.ret=='101'){
                  // alert(jsonData.msg);
                  var data_realtime = jsonData.data_realtime;
                  // console.log(jsonData.data_realtime);
                  $state_led = '0';
                  if(data_realtime=='1'){
                    $state_led = '🟢';
                  }else if(data_realtime=='0'){
                    $state_led = '🔴';
                  }else{
                    $state_led = data_realtime;
                  }
                  $("#show_lamp_state_"+monitor_id).html($state_led);
                  // $("#digital_real_value").val(data_realtime);
                }
                else{
                  alert(jsonData.msg+"7");
                }
              } 
            });
      });
    }
  </script>
</body>
</html>

