<?php
  //Script for linux OR Set Env for php
  shell_exec('php -q /home/www/tikkyddns/iot-demo/crontab_data_notify.php');

  //Script for Windows
  shell_exec('C:\AppServ\php7\php.exe -q C:\AppServ\www\testmail\alert_email_notify.php');
?>  