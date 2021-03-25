<?php
session_start();
if($_SESSION["level"] == 'guest'){
    $_SESSION["rule"] = 1;
}else{
    header("Location : ../login.php");
}