<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  if (!isset($_SESSION['role_id']) && basename($_SERVER['PHP_SELF']) !== 'dashboard_view_v3.php' || !in_array($_SESSION['role_id'], array('1','55','88','99'))) {
    echo "<script>alert('Sorry, You Are Not Allowed to Access This Page i!!');window.location.href='dashboard_view_v3.php';</script>";
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Password</title>
    
    <link rel="stylesheet" href="includes/css/style.css">
  
    
    
</head>
<body class="bg-background text-foreground">
<?php
  //nav bar
    include_once("nav_bar.php");
  ?>
 
<section class="bg-gray-50 dark:bg-gray-900"> <br><br><br><br>
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        <img class="w-8 h-8 mr-2" src="img/logo.png" alt="logo">
        Change password  
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
              <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                  Change password an account
              </h1>
              <form class="space-y-4 md:space-y-6" action="register_process.php" METHOD="POST">
                  <div>
                      <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">UserName</label>
                      <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?=$_SESSION['username']?>" autocomplete="new-password" disabled="disabled">
                  </div>
                  <div>
                      <label for="old_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Old-Password</label>
                      <input type="password" name="old_password" id="old_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="" autocomplete="new-password">
                  </div>
                  <div>
                      <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                      <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="" autocomplete="new-password">
                  </div>
                  
                  <div>
                      <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                      <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="" autocomplete="new-password">
                  </div>
                  
                  <button type="button" onclick="check_submit();" class="w-full text-white bg-gray-900 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Change password</button>
                  <!-- <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      Already have an account? <a href="login.php" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</a>
                  </p> -->
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
    var old_password = encodeURI($("#old_password").val());
    var password = encodeURI($("#password").val());
    var confirm_password = encodeURI($("#confirm_password").val());
    
    if(username == ""){
      alert("username is not empty");
        $("#username").focus();
        return false;
    }
    if(old_password == ""){
      alert("old password is not empty");
        $("#old_password").focus();
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
    if(old_password==password){
      alert("The new password and the old password must not be the same.");
        $("#password").focus();
        return false;
    }

    var dataString = "old_password="+ old_password+"&username="+username+"&password="+password+"&confirm_password="+confirm_password; //ค่าที่จะส่งไปพร้อมตัวแปร
      $.ajax ({
        type: "POST", //METHOD "GET","POST"
        url: "renew_password_process.php", //File ที่ส่งค่าไปหา
        data: dataString,
        //cache: false,
        success: function(data) {
        //alert(data);
        var data_res = JSON.parse(data);
        // console.log(data_res.ret);
        if(data_res.ret==101){
          alert("Change password success");
          window.location.href='dashboard_view_v3.php';
        }
        else{
          alert(data_res.msg);
          console.log(data_res.data);
          }
        } 
      });



  }
  
</script>
</body>
</html>

