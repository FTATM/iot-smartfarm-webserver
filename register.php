<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  if (!isset($_SESSION['role_id']) && basename($_SERVER['PHP_SELF']) !== 'dashboard_view_v3.php' || !in_array($_SESSION['role_id'], array('55','88','99'))) {
    echo "<script>alert('Sorry, You Are Not Allowed to Access This Page!!');window.location.href='dashboard_view_v3.php';</script>";
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <link rel="stylesheet" href="includes/css/style.css">
  
    
    
</head>
<body class="bg-background text-foreground">
<?php
  //nav bar
    include_once("nav_bar.php");
  ?>
 
<section class="bg-gray-50 dark:bg-gray-900"> <br><br><br><br>
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0 mt-20">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-8 h-8 mr-2" src="img/logo.png" alt="logo">
        Register  
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  Create an account
              </h1>
              <form class="space-y-4 md:space-y-6" action="register_process.php" METHOD="POST">
                  <div>
                      <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UserName</label>
                      <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter Username" required="" autocomplete="new-password">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="" autocomplete="new-password">
                  </div>
                  
                  <div>
                      <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                      <input type="password" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="" autocomplete="new-password">
                  </div>
                  <?php
                    if(in_array($_SESSION['role_id'], array('88','99'))){
                      include_once("includes/fn/pg_connect.php");
    

                      $db = pg_connect( "$host $port $dbname $credentials"  );
                  
                        if(!$db) {
                            echo "Error : Unable to open database\n";
                            exit;
                        }


                      $sql = "SELECT branch_id, branch_name, createtime, updatetime, status	FROM branch_info ORDER BY branch_id ASC;";
                      $rs_branch   = select($sql,$db);
                      pg_close($db);
                      ?>
                      <div>
                      <label for="sel_branch" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User for Branch</label>
                          <select id="sel_branch" name="sel_branch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-44 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="sel_group_change()" >
                        <?php
                          if(count($rs_branch)>0){
                            for($g=0;$g<count($rs_branch);$g++){
                              if($_SESSION['branch_id']==$rs_branch[$g]->branch_id){$selected = "selected";}else{$selected = "";}
                              echo '<option value="'.$rs_branch[$g]->branch_id.'" '.$selected.'>'.$rs_branch[$g]->branch_name.'</option>';
                            }
                          }
                        ?>
                    </select>
                      </div>
                      <?php
                    }
                  ?>
                  <input type="hidden" id="sel_branch_val" name="sel_branch_val" value="<?php echo isset($_SESSION['branch_id'])?$_SESSION['branch_id'] : 1;?>">
                  <div>
                      <label for="nickname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                      <input type="text" name="nickname" id="nickname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex. Name Surname" required="">
                  </div>
                  <div>
                      <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                      <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com">
                  </div>
                  <div>
                      <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                      <input type="text" name="phone_number" id="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex. 085xxxxxxx" >
                  </div>
                  <div>
                      <label for="address_txt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                      <textarea rows="4" cols="50" name="address_txt" id="address_txt" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex. Address" ></textarea>
                  </div>
                  <!-- <div class="flex items-start">
                      <div class="flex items-center h-5">
                        <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required="">
                      </div>
                      <div class="ml-3 text-sm">
                        <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="#">Terms and Conditions</a></label>
                      </div>
                  </div> -->
                  <button type="button" onclick="check_submit();" class="w-full text-white bg-gray-900 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create an account</button>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      Already have an account? <a href="login.php" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>

<script>
      tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {DEFAULT: 'hsl(var(--primary))',
                    foreground: 'hsl(var(--primary-foreground))',"600":"#000000","700":"#222222"}
          }
        }
      }
    }
</script>

<script>
  function check_submit(){
    var username = $("#username").val();
    var password = encodeURI($("#password").val());
    var confirm_password = encodeURI($("#confirm-password").val());
    var nickname = $("#nickname").val();
    var email = $("#email").val();
    var phone_number = $("#phone_number").val();
    var address_txt = $("#address_txt").val();
    var sel_branch_val = $("#sel_branch_val").val();
    

    if(!username.match(/^[0-9a-zA-Z]+$/)){
        alert("Username only Alphabet or numberic");
        $("#username").focus();
        return false;
    }
    if(password == ""){
      alert("Password and confirm password is not empty");
        $("#password").focus();
        return false;
    }
    if(password != confirm_password){
      alert("Password and confirm password mismatch");
        $("#password").focus();
        return false;
    }
    if(nickname == ""){
      alert("Name is not NULL");
        $("#nickname").focus();
        return false;
    }

    var dataString = 'username='+ username+"&password="+password+"&confirm-password="+confirm_password+"&nickname="+nickname+"&email="+email+"&phone_number="+phone_number+"&address_txt="+address_txt+"&sel_branch="+sel_branch_val; //ค่าที่จะส่งไปพร้อมตัวแปร
      $.ajax ({
        type: "POST", //METHOD "GET","POST"
        url: "register_process.php", //File ที่ส่งค่าไปหา
        data: dataString,
        //cache: false,
        success: function(data) {
        //alert(data);
        // console.log(data);
        // return false;
        var data_res = JSON.parse(data);
        // console.log(data_res.ret);
        if(data_res.ret==101){
          // onsole.log(data_res.data);
          alert("Create account success");
          window.location.href='dashboard_view_v3.php';
        }
        else{
          alert(data_res.msg);
          console.log(data_res.data);
          }
        } 
      });



  }

  function sel_group_change(){ 
      //page1_sql_type = 4
      //Update "monitor" id = elm_index,group_id = sel_group
      var sel_branch         = $("#sel_branch").find(":selected").val();
      if(sel_branch=='-'){
        return false;
      }
      $("#sel_branch_val").val(sel_branch);
      
    }
  
</script>
</body>
</html>

