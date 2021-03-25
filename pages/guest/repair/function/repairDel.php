<?php
session_start();
if($_SESSION['level'] == 'employee'){
  include('../../../connection.php');
  $room_id = $_REQUEST['room_id'];
  $appliance = $_REQUEST['repairappliance'];
  $category = $_REQUEST['repaircategory'];
  $date = $_REQUEST['repairdate']; 

  $sql = "DELETE FROM repair WHERE room_id = '$room_id' AND repair_appliance = '$appliance' AND repair_category = '$category' AND repair_date = '$date' ";

  if ($conn->query($sql) === TRUE) {
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