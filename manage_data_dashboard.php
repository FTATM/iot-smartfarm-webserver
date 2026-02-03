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
      if(!in_array($_SESSION['role_id'], array('88','99'))){
        $sql_plus = " AND  a.branch_id='".$_SESSION['branch_id']."' ";
      }
      $sql_sel_group  = "SELECT a.id as dashbord_id, a.item_type_id, a.item_name, a.sort, a.status,b.type_path,b.type_name,a.branch_id
                FROM dashboard_item a 
                left join dashboard_type b on a.item_type_id = b.id
                where (a.status ='1' or a.status ='0') $sql_plus order by a.sort desc,a.id asc;";
      $rs_sel_group   = select($sql_sel_group,$db);
      // echo $sql_sel_group;exit;
      // var_dump($rs_sel_group);
      ?>    

    <section class="bg-card text-card-foreground py-16">
      <div class="flex flex-col">
        <div class="py-4 w-full">
          <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500">
          <tbody>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Dashbord Name : </td>
              <td class="border border-slate-600">
                <input type="text" id="group_name" name="group_name" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
              </td>
            </tr>
            <tr>
            <td class="text-right border border-slate-600">Dashbord type : </td>
              <td class="border border-slate-600">
                <?php
                  $sql_sel_type  = "SELECT id, type_name, type_path, createtime, updatetime, sort, status
                                      FROM dashboard_type where status ='1' ORDER BY sort DESC,id asc ;";
                  $rs_sel_type   = select($sql_sel_type,$db);
                ?>
              <select id="sel_group_" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="-">Choose a DashbordType</option>
                    <?php
                      if(count($rs_sel_type)>0){
                        for($g=0;$g<count($rs_sel_type);$g++){
                          
                          echo '<option value="'.$rs_sel_type[$g]->id.'">'.$rs_sel_type[$g]->type_name.'</option>';
                        }
                      }
                    ?>
                    <!-- <option selected>Choose a country</option>
                    <option value="US">United States</option> -->
                </select>
              </td>
            </tr>
            <tr>
              <td class="text-right"></td>
              <td>
              <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="add_group();">
                Add Dashbord
              </button>
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
              <th class="border border-slate-600 py-2 px-4">Branch name</th>
              <th class="border border-slate-600 py-2 px-4">Dashbord name</th>
              <th class="border border-slate-600 py-2 px-4">Dashbord type name</th>
              <th class="border border-slate-600 py-2 px-4">Status</th>
              <th class="border border-slate-600 py-2 px-4">Edit</th>
              <th class="border border-slate-600 py-2 px-4">Remove</th>
              <th class="border border-slate-600 py-2 px-4">Action Sort ↕️</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($rs_sel_group)>0){
          
                  $arrBranch = array();
                  $sql = "SELECT branch_id, branch_name	FROM branch_info ORDER BY branch_id asc;";
                  $rsBranch   = select($sql,$db);
                  if(count($rsBranch)>0){
                    for($i=0;$i<count($rsBranch);$i++){
                      $arrBranch[$rsBranch[$i]->branch_id] = $rsBranch[$i]->branch_name;
                    }
                    
                  }
                for($i=0;$i<count($rs_sel_group);$i++){

               
            ?>
            <tr id="tr_list_<?php echo $rs_sel_group[$i]->dashbord_id;?>">
              <td class="border border-slate-600 py-2 px-4"><?php echo ($i+1);?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $arrBranch[$rs_sel_group[$i]->branch_id]; ?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $rs_sel_group[$i]->item_name;?></td>
              <td class="border border-slate-600 py-2 px-4"><?php echo $rs_sel_group[$i]->type_name;?></td>
              <td class="border border-slate-600 py-2 px-4">
                <label class="inline-flex items-center cursor-pointer">
                     <input type="checkbox" value=""  id="status_group_<?php echo $rs_sel_group[$i]->dashbord_id;?>" class="sr-only peer" <?php if($rs_sel_group[$i]->status=='1'){echo "checked";} ?> onclick="sent_sw_change('<?php echo $rs_sel_group[$i]->dashbord_id;?>')" /><div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </td>
              <td class="border border-slate-600">
                <a href="manage_data_dashboard_edit.php?dashboard_id=<?php echo $rs_sel_group[$i]->dashbord_id;?>">  
                  <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 my-2 mx-2 rounded">
                    Edit
                  </button>
                </a>
              </td>
              <td class="border border-slate-600">
                <button
                  class="relative align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[32px] h-10 max-h-[32px] rounded-lg text-xs bg-red-500 text-white shadow-md shadow-red-500/20 hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none "
                  type="button"
                  onclick="confirm_del_group('<?php echo $rs_sel_group[$i]->dashbord_id;?>');"
                >✖️</button></td>
              <td class="border border-slate-600">
              <input type="number" pattern="[0-9]*" inputmode="numeric" id="sort_id_<?php echo $rs_sel_group[$i]->dashbord_id;?>" name="sort_id_<?php echo $rs_sel_group[$i]->dashbord_id;?>" class="w-20 m-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center" value="<?php echo $rs_sel_group[$i]->sort;?>" >
              <button type="button" onclick="update_sort('<?php echo $rs_sel_group[$i]->dashbord_id;?>');" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 my-2 mx-2 rounded">
                    Sent
                  </button>
              </td>
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
    </section>

  <?php    pg_close($db); ?>

  <script>
    function confirm_del_group(elm_index){
      if(confirm("ต้องการลบ Dashbord นี้?") == true){
        var dataString = 'data_group_manage_type=1'+'&group_id='+elm_index; //ค่าที่จะส่งไปพร้อมตัวแปร
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
    function sent_sw_change(elm_index){ //page1_sql_type = 2
      //Update "monitor" id = elm_index,config_value_2 = isChecked 
      //|insert "volte_censor" location = group, name = device, DATA02
      var sent_sw_isChecked     = $("#status_group_"+elm_index).is(":checked");
      var isChecked = 0;
      if(sent_sw_isChecked){
        isChecked = 1;
      }
      // console.log(sent_sw_isChecked);
      var dataString = 'data_group_manage_type=2'+'&group_id='+elm_index+'&isChecked='+ isChecked; //ค่าที่จะส่งไปพร้อมตัวแปร
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
              }
              else{
                alert(jsonData.msg+"2");
                // console.log(jsonData.msg);
              }
            } 
          });

    }
    function update_sort(elm_index){ //page1_sql_type = 2
      //Update "monitor" id = elm_index,config_value_2 = isChecked 
      //|insert "volte_censor" location = group, name = device, DATA02
      var sent_sw_isChecked     = $("#sort_id_"+elm_index).val();
      var isChecked = sent_sw_isChecked;
      if(isNaN(isChecked) || isChecked==null || isChecked==''){
          isChecked = 0;
      }
      // console.log(sent_sw_isChecked);
      var dataString = 'data_group_manage_type=4'+'&group_id='+elm_index+'&isChecked='+ isChecked; //ค่าที่จะส่งไปพร้อมตัวแปร
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
                window.location.reload();
                // console.log(jsonData.msg);
              }
              else{
                alert(jsonData.msg+"2");
                // console.log(jsonData.msg);
              }
            } 
          });

    }
    function add_group(){
      var branch      = '<?php echo $_SESSION['branch_id'];?>';
      var group_name  = $("#group_name").val();
      var sel_type_id = $("#sel_group_").find(":selected").val();
      if(sel_type_id == '-'){
          alert('Please select Dashbord Type');
          $("#sel_group_").focus();
          return false;
        }
      var dataString = 'data_group_manage_type=3'+'&group_name='+group_name+'&sel_type_id='+ sel_type_id+"&branch_id="+branch; //ค่าที่จะส่งไปพร้อมตัวแปร
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
                location.reload();
              }else if(jsonData.ret=='111'){
                alert(jsonData.msg);
              }else{
                alert(jsonData.msg+"3");
                // console.log(jsonData.msg);
              }
            } 
          });
    }
  </script>
</body>
</html>