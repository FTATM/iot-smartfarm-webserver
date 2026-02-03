<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Data Group</title>
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
      

      ?>    

    <section class="bg-card text-card-foreground py-16">
      <div class="flex flex-col">
       
        <div class="py-4 w-full">
        <div class="item-center text-center w-full"><h2 class="text-lg font-bold mb-2">Simulate Push DATA By Hardware</h2></div>
          <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500">
          <tbody>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Group </td>
              <td class="border border-slate-600">
              <select id="sel_group_" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="-">Choose a Group</option>
                    <?php
                      if(count($rs_sel_group)>0){
                        for($g=0;$g<count($rs_sel_group);$g++){
                          
                          echo '<option value="'.$rs_sel_group[$g]->group_id.'">'.$rs_sel_group[$g]->group_name.'['.$rs_sel_group[$g]->value_map_volte_censor.']</option>';
                        }
                      }
                    ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
              </td>
            </tr>
            <tr>
            <td class="text-right border border-slate-600">Device : </td>
              <td class="border border-slate-600">
              <select id="sel_device_" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="-">Choose a Device</option>
                    <?php
                      if(count($rs_sel_device)>0){
                        for($d=0;$d<count($rs_sel_device);$d++){
                          echo '<option value="'.$rs_sel_device[$d]->device_id.'">'.$rs_sel_device[$d]->divice_name.'</option>';
                        }
                      }
                    ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
              </td>
            </tr>
            <tr>
            <td class="text-right border border-slate-600">Type : </td>
              <td class="border border-slate-600">
              <select id="sel_type_" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                  <option value="-">Choose a Type</option>
                    <?php
                      if(count($rs_sel_type)>0){
                        for($t=0;$t<count($rs_sel_type);$t++){
                          echo '<option value="'.$rs_sel_type[$t]->type_id.'" >'.$rs_sel_type[$t]->type_name.'</option>';
                        }
                      }
                    ?>
                </select>
              </td>
            </tr>
            <tr>
            <td class="text-right border border-slate-600">Data : </td>
              <td class="border border-slate-600">
              <select id="sel_datax_" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="-">Choose a Data</option>
                    <?php
                      if(count($rs_sel_datax)>0){
                        for($dx=0;$dx<count($rs_sel_datax);$dx++){
                          echo '<option value="'.$rs_sel_datax[$dx]->datax_id.'" >'.$rs_sel_datax[$dx]->datax_name.'</option>';
                        }
                      }
                    ?>
                </select>
              </td>
            </tr>
            <tr>
            <tr>
            <td class="text-right border border-slate-600">Data value : </td>
              <td class="border border-slate-600">
              <input type="number" id="data_value" name="data_value" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter number value">   
              </td>
            </tr>
            <tr>
              <td class="text-right"></td>
              <td>
              <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="add_group();">
                Submit
              </button>
              </td>
            </tr>
          </tbody>
          </table>
        </div>
        
      </div>
    </section>

  <?php    pg_close($db); ?>
 
  <script>
    function add_group(){
        var sel_group_ = $("#sel_group_").find(":selected").val();
        var sel_device_ = $("#sel_device_").find(":selected").val();
        var sel_type_ = $("#sel_type_").find(":selected").val();
        var sel_datax_ = $("#sel_datax_").find(":selected").val();
        var data_value = $("#data_value").val();
        if(sel_group_ == '-'){
          alert('Please select Group');
          $("#sel_group_").focus();
          return false;
        }
        if(sel_device_ == '-'){
          alert('Please select device');
          $("#sel_device_").focus();
          return false;
        }
        if(sel_type_ == '-'){
          alert('Please select type');
          $("#sel_type_").focus();
          return false;
        }
        if(sel_datax_ == '-'){
          alert('Please select data');
          $("#sel_datax_").focus();
          return false;
        }
        if(data_value == ""){
          alert('Please input data');
          $("#data_value").focus();
          return false;
        }
        if((sel_type_ == '2' || sel_type_ =='4') && (data_value != 0 && data_value != 1)){
          alert('Please input data for \ndigital "0" OR "1"');
          $("#data_value").focus();
          return false;
        }
      
      var dataString = 'data_sim_type=1'+'&group_id='+sel_group_+'&type_id='+sel_type_+'&device_id='+sel_device_+'&datax_id='+sel_datax_+'&data_value='+ data_value; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_simulate_push_data_by_hardware_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
                location.reload();
              }else if(jsonData.ret=='111'){
                alert(jsonData.msg);
              }else{
                alert(jsonData.msg);
                // console.log(jsonData.msg);
              }
            } 
          });
    }
  </script>
</body>
</html>