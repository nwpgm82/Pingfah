<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../../connection.php");
    $vat = $_POST["vat"];
    $edit_vat = "UPDATE roomdetail SET daily_vat = $vat";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'แก้ไขภาษีมูลค่าเพิ่ม (VAT)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    $conn->query($edit_vat);
    $conn->query($addLogs);
}else{
    Header("Location: ../../../login.php");
}
?>