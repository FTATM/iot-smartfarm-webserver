<?php
require_once("web.config.php");
require_once __DIR__ . '/config.php';
#########CONFIG ZONE HOST#########
define("HOST_DEVICE_OS", '1'); //0= linux, 1= Windows
// define("DEVICE_TOKEN", "5pdKx3CmIY4kzVAbf7dhh6/w02XOknW3elFWEjszQ7RmmgDJ0C6tOv2q3P4bufwcK2yFT4wvA9vMr+gN4xj9A7KM2QnzinSz9IsgEvo7AAE=");
// define("DEVICE_TOKEN", "5pdKx3CmIY4kzVAbf7dhhzEIVY+eU6GyZGOp7nrNJmEjzizdegPKYZWI4pFhSiNQDBmj3jBBtPz19kVe7IEKSyZaIg6KuxBm7bmD4Z0jceQ=");
define("DEVICE_TOKEN", "5pdKx3CmIY4kzVAbf7dhhzEIVY+eU6GyZGOp7nrNJmGK6oJz6KTk8nltwMhA4fBnJRtsK1gw49+d1fxKMQBRjndUJV72u+Dk0LXGVqA7588=");
#########CONFIG ZONE HOST END#####

#####PGSQL#######
$host   = "host = " .$db_config['host']   ?? '127.0.0.1';
$port   = "port = " .$db_config['port']   ?? '5432';
$dbname = "dbname = " .$db_config['name'] ?? 'smartfarm';
$user   = $db_config['user']   ?? 'postgres';
$pass   = $db_config['pass']   ?? 'postgres';
$credentials = "user=$user password=$pass";
date_default_timezone_set("Asia/Bangkok");


function select($sql, $Connect)
{
  $array_result = array();
  $Query = pg_query($Connect, $sql);
  if ($Query) {
    while ($Row = pg_fetch_object($Query)) {
      array_push($array_result, $Row);
    }
  } else {
    echo pg_last_error($db);
    return $array_result;
  }
  return $array_result;
}

function execute($sql, $Connect)
{

  $Query = pg_query($Connect, $sql);
  if ($Query) {
    return true;
  } else {
    echo pg_last_error($db);
    return false;
  }
}
