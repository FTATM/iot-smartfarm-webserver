<?php
function checkLogin(){
    if (!isset($_SESSION['username'])) {
        header("Location:/iotsf/login.php");
        die();
    }
}
function checkIsAdmin(){
    if (!isset($_SESSION['username'])) {
        header("Location:/iotsf/login.php");
        die();
    }
    if ($_SESSION['is_admin'] !== true) {
    }
}