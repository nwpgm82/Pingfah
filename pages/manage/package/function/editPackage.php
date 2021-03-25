<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
  include('../../../connection.php');
  function BasicDate($tdate){
    $search = ["มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    $replace = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    $edate = str_replace($search, $replace, $tdate);
    $str_date = strtotime($edate);
    $year =  date("Y",$str_date);
    $month = date("m",$str_date);
    $day = date("d",$str_date);
    return "$year-$month-$day"; 
  }
  $package_id = $_REQUEST["package_id"];
  $num = $_POST['num'];
  $company = $_POST['company'];
  $arrived = BasicDate($_POST['arrived']);
  $sql = "UPDATE package SET package_num = '$num', package_company = '$company', package_arrived = '$arrived' WHERE package_id = $package_id";
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('พัสดุ', 'แก้ไขข้อมูลพัสดุ', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
    echo "<script>";
    echo "alert('แก้ไขพัสดุเรียบร้อยแล้ว');";
    echo "location.href = '../index.php';";
    echo "</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}else{
  Header("Location: ../../../login.php");
}

?>