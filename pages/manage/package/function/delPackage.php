<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
  include('../../../connection.php');
  $package_id = $_REQUEST["package_id"];
  $sql = "DELETE FROM package WHERE package_id = '$package_id'";
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('พัสดุ', 'ลบรายการพัสดุ', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
    echo "<script>";
    echo "alert('เพิ่มพัสดุเรียบร้อยแล้ว');";
    echo "location.href = '../index.php';";
    echo "</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}else{
  Header("Location: ../../../login.php");
}

?>