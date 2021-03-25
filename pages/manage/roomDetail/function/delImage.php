<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
  include('../../../connection.php');
  $type = $_REQUEST['type'];
  if($type == "fan"){
    $type_show = "พัดลม";
  }else if($type == "air"){
    $type_show = "แอร์";
  }
  $gal_name = $_POST["img_name"];
  $folder_path = "../../../images/roomdetail/$type/$gal_name";
  if($type == "fan"){
    $countCheck = mysqli_query($conn,"SELECT COUNT(gal_name) AS total FROM fan_gal WHERE gal_name = '$gal_name' GROUP BY gal_name HAVING COUNT(gal_name)");
  }else if($type == "air"){
    $countCheck = mysqli_query($conn,"SELECT COUNT(gal_name) AS total FROM air_gal WHERE gal_name = '$gal_name' GROUP BY gal_name HAVING COUNT(gal_name)");
  }
  $countTotal = mysqli_fetch_assoc($countCheck);
  if($type == "fan"){
    $sql = "DELETE FROM fan_gal WHERE gal_name = '$gal_name' LIMIT 1";
  }else if($type == "air"){
    $sql = "DELETE FROM air_gal WHERE gal_name = '$gal_name' LIMIT 1";
  }
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'ลบรูปภาพ $gal_name (ห้อง$type_show)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if($countTotal['total'] > 1){
    if($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE){
      echo "<script>";      
      echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
      echo "location.assign('../detail.php?type=$type');";
      echo "</script>";
    }else{
      echo "Error deleting record: " . $conn->error;
    }
  }else{
    if($conn->query($sql) === TRUE && unlink($folder_path) && $conn->query($addLogs) === TRUE){
      echo "<script>";      
      echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
      echo "location.assign('../detail.php?type=$type');";
      echo "</script>";
    }else{
      echo "Error deleting record: " . $conn->error;
    }
  }
  $conn->close();
}else{
  header("Location : ../../../login.php");
}
?>