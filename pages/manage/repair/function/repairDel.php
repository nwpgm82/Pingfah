<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){
  include('../../../connection.php');
  $repair_id = $_REQUEST["repair_id"];
  $sql = "DELETE FROM repair WHERE repair_id = $repair_id ";
  $searchData = mysqli_query($conn, "SELECT room_id FROM repair WHERE repair_id = $repair_id");
  $result = mysqli_fetch_assoc($searchData);
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('แจ้งซ่อม', 'ลบรายการแจ้งซ่อม (ห้อง ".$result["room_id"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
      echo "<script>";
      echo "alert('ลบรายการแจ้งซ่อมสำเร็จ');";
      echo "window.location.assign('../index.php');";
      echo "</script>";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  
    $conn->close();
}else{
  Header("Location: ../../../login.php");
}

?>