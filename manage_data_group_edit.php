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
      
      $group_id = "";
      if(isset($_GET['group_id']) && $_GET['group_id']!=""){
        $group_id = $_GET['group_id'];
      }

      $sql_sel_group  = "SELECT group_id, group_name,value_map_volte_censor,status
	                        FROM page_data_manage_group WHERE group_id='{$group_id}' ORDER BY group_id ASC,sort ASC;";
      $rs_sel_group   = select($sql_sel_group,$db);
      if(count($rs_sel_group)!='1'){
        echo "wrong group_id parameter!";
        exit;
      }

      ?>    

    <section class="bg-card text-card-foreground py-16">
      <div class="flex flex-col">
        <div class="py-4 w-full">
          <table class="table-auto w-4/4 m-auto border border-collapse  border-slate-500">
          <tbody>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Group ID : </td>
              <td class="border border-slate-600">
                <input type="text" id="group_id" name="group_id" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $rs_sel_group[0]->group_id;?>" disabled>
              </td>
            </tr>
            <tr>
              <td class="text-right border border-slate-600 w-1/3">Group Name : </td>
              <td class="border border-slate-600">
                <input type="text" id="group_name" name="group_name" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $rs_sel_group[0]->group_name;?>">
              </td>
            </tr>
            <tr>
            <td class="text-right border border-slate-600">Vulue Parameter Map volte_censor : </td>
              <td class="border border-slate-600">
                <input type="text" id="vulue_map_volte_censer" name="vulue_map_volte_censer" class="w-full p-2 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo $rs_sel_group[0]->value_map_volte_censor;?>">
              </td>
            </tr>
            <tr>
              <td class="text-right"></td>
              <td>
              <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="edit_group();">
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
    
    
    function edit_group(){
      var branch      = '<?php echo $_SESSION['branch_id'];?>';
      var group_name              = $("#group_name").val();
      var vulue_map_volte_censer  = $("#vulue_map_volte_censer").val();
      var group_id                = $("#group_id").val();
      
      var dataString = 'data_group_manage_type=4'+'&group_name='+group_name+'&vulue_map_volte_censer='+ vulue_map_volte_censer+'&group_id='+group_id+"&branch_id="+branch; //ค่าที่จะส่งไปพร้อมตัวแปร
          $.ajax ({
              type: "POST", //METHOD "GET","POST"
              url: "ajax_manage_data_group_process.php", //File ที่ส่งค่าไปหา
              data: dataString,
              //cache: false,
              success: function(data) {
              // alert(data);
              var jsonData = JSON.parse(data);
              if(jsonData.ret=='101'){
                // alert(jsonData.msg);
                console.log(jsonData.msg);
                window.location.replace("manage_data_group.php");
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