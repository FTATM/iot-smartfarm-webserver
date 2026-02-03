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
      
      $dashboard_id = "";
      if(isset($_GET['dashboard_id']) && $_GET['dashboard_id']!=""){
        $dashboard_id = $_GET['dashboard_id'];
      }

      $sql_sel_group  = "SELECT a.id as dashbord_id, a.item_type_id, a.item_name, a.sort, a.status,b.type_path,b.type_name,b.max_data
                FROM dashboard_item a left join dashboard_type b on a.item_type_id = b.id
                where a.id = '{$dashboard_id}';";
      $rs_sel_group   = select($sql_sel_group,$db);
      if(count($rs_sel_group)!='1'){
        echo "wrong dashboard_id parameter!";
        exit;
      }

      ?>    

    <section class="bg-card text-card-foreground py-6">
      <div class="flex flex-col">
        <div class="py-4 w-full">
          <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500">
          <tbody>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Dashbord ID : </td>
              <td class="border border-slate-600">
                <input type="text" id="dashboard_id" name="dashboard_id" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $rs_sel_group[0]->dashbord_id;?>" disabled>
              </td>
            </tr>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Dashbord Name : </td>
              <td class="border border-slate-600">
                <input type="text" id="group_name" name="group_name" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $rs_sel_group[0]->item_name;?>" >
              </td>
            </tr>
            <tr>
              <td class="text-right"></td>
              <td>
              <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="edit_group();">
                Update name
              </button>
              </td>
            </tr>
          </tbody>
          </table>
        </div>
        <hr>
        <div class="py-4 w-full">
          <!-- SUB data -->
           <?php
             $sql_sel_dash_sub  = "SELECT id as dashbord_id, label_name, dashboard_item_id, monitor_id, sort, createtime, updatetime, status, label_color_code
             FROM dashboard_item_sub_data where dashboard_item_id='{$dashboard_id}'  ORDER BY sort DESC,id asc;";
            $rs_sel_dash_sub   = select($sql_sel_dash_sub,$db);
           
           ?>
          <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500">
          <tbody>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Data Monitor : </td>
              <td class="border border-slate-600">
                <?php
                    $sql_sel_monitor  = "SELECT monitor_id, monitor_name
                                      FROM page_data_manage_monitor where status = '1' AND branch_id='".$_SESSION['branch_id']."' order by monitor_id asc    ;";
                    $rs_sel_monitor   = select($sql_sel_monitor,$db);
                  ?>
                <select id="sel_group_" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="-">Choose a DashbordType</option>
                      <?php
                        if(count($rs_sel_monitor)>0){
                          for($g=0;$g<count($rs_sel_monitor);$g++){
                            
                            echo '<option value="'.$rs_sel_monitor[$g]->monitor_id.'">[MonitorID : '.$rs_sel_monitor[$g]->monitor_id.']-'.$rs_sel_monitor[$g]->monitor_name.'</option>';
                          }
                        }
                      ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
              </td>
            </tr>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Label Name : </td>
              <td class="border border-slate-600">
                <input type="text" id="label_name" name="label_name" placeholder="Enter Label Name" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="" >
              </td>
            </tr>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Label Color : </td>
              <td class="border border-slate-600">
              
                <input type="color" name="label_color" class="p-1 h-10 w-full block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700" id="label_color" value="#2563eb" placeholder="Choose your color" title="Choose your color">
              </td>
            </tr>
            <tr>
              <td class="text-right"></td>
              <td>
                <?php
                  if($rs_sel_group[0]->max_data > count($rs_sel_dash_sub)){

                 
                ?>
              <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="add_dash_sub();">
                Add Data
              </button>

              <?php
                 }else{
                  echo "MAX LIMIT DATA SUB";
                 }
              ?>
              </td>
            </tr>
          </tbody>
          </table>
        </div>
        <div class="py-4 w-full">
        <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500 text-center">
          <thead>
            <tr>
              <th class="border border-slate-600 py-2 px-4">No.</th>
              <th class="border border-slate-600 py-2 px-4">Monitor ID</th>
              <th class="border border-slate-600 py-2 px-4">Label name</th>
              <th class="border border-slate-600 py-2 px-4">Label Color</th>
              <th class="border border-slate-600 py-2 px-4">Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php
           
              if(count($rs_sel_dash_sub)>0){
                for($i=0;$i<count($rs_sel_dash_sub);$i++){

               
            ?>
            <tr id="tr_list_<?php echo $rs_sel_dash_sub[$i]->dashbord_id;?>">
              <td class="border border-slate-600 py-2 px-4"><?php echo ($i+1);?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $rs_sel_dash_sub[$i]->monitor_id;?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $rs_sel_dash_sub[$i]->label_name;?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $rs_sel_dash_sub[$i]->label_color_code == "" ? "-" : $rs_sel_dash_sub[$i]->label_color_code;?></td>
             
              
              <td class="border border-slate-600">
                <button
                  class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[32px] h-10 max-h-[32px] rounded-lg text-xs bg-red-500 text-white shadow-md shadow-red-500/20 hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none "
                  type="button"
                  onclick="confirm_del_group('<?php echo $rs_sel_dash_sub[$i]->dashbord_id;?>');"
                >✖️</button></td>
              
            </tr>
            <?php
               }
              }else{
                echo '<td class="border border-slate-600 py-2 px-4" colspan="7">No data.</td>';
              }
            ?>
          </tbody>
        </table>
        </div>
      </div>
      <div class="py-4 w-full text-center">
      <button type="button" class="bg-primary hover:bg-primary-700 text-white font-bold py-2 px-4 rounded" onclick="window.location.href='manage_data_dashboard.php';">
                Back to manage
              </button>
      </div>        
   
    </section>

  <?php    pg_close($db); ?>

  <script>
    $(document).ready(function() {
      // document.getElementById("label_color").onchange = function() {
      //   backRGB = this.value;
      //   $("#label_color").val(backRGB);
      //   console.log(backRGB);
      // }
    });

    
    
    function edit_group(){
      var group_name              = $("#group_name").val();
      var dashboard_id                = $("#dashboard_id").val();
      
      var dataString = 'data_group_manage_type=5'+'&group_name='+group_name+'&dashboard_id='+dashboard_id; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_manage_data_dashbord_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
                window.location.replace("manage_data_dashboard.php");
              }else if(jsonData.ret=='111'){
                alert(jsonData.msg);
              }else{
                alert(jsonData.msg+"3");
                console.log(jsonData.msg);
              }
            } 
          });
    }

    function confirm_del_group(elm_index){
      if(confirm("ต้องการลบ Dashbord นี้?") == true){
        var dataString = 'data_group_manage_type=6'+'&group_id='+elm_index; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_manage_data_dashbord_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                $("#tr_list_"+elm_index).hide();
                console.log(jsonData.msg);
                window.location.replace("manage_data_dashboard_edit.php?dashboard_id=<?php echo $dashboard_id;?>");
              }
              else{
                alert(jsonData.msg+"1");
              }
            } 
          });
         
        
      }else{
        console.log("0");
      }
    }

    function add_dash_sub(){
      
      var label_name = $("#label_name").val();
      var dashboard_item_id = $("#dashboard_id").val();
      var monitor_id = $("#sel_group_").find(":selected").val();
      var label_color_code = $("#label_color").val();
      if(monitor_id == '-'){
          alert('Please select Dashbord Type');
          $("#sel_group_").focus();
          return false;
        }
      
      var dataString = 'data_group_manage_type=7'+'&label_name='+label_name+'&dashboard_item_id='+dashboard_item_id+'&monitor_id='+monitor_id+'&label_color_code='+label_color_code; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_manage_data_dashbord_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
                window.location.replace("manage_data_dashboard_edit.php?dashboard_id=<?php echo $dashboard_id;?>");
              }else if(jsonData.ret=='111'){
                alert(jsonData.msg);
              }else{
                alert(jsonData.msg+"3");
                console.log(jsonData.msg);
              }
            } 
          });
    }
  </script>
</body>
</html>