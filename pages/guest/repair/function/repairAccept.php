<?php
session_start();
if($_SESSION['level'] == 'guest'){
  include('../../../connection.php');
  if(isset($_POST['repair_accept'])){
      $room_id = $_POST['room_select'];
      $app = $_POST['repair_appliance'];
      $cate = $_POST['repair_category'];
      $detail = $_POST['repair_detail'];
      $date = $_POST['repair_date'];
      $status = $_POST['repair_status'];
      // echo $room_id .$app .$cate .$detail .$date .$status;
      $sql = "INSERT INTO repair (room_id, repair_appliance, repair_category, repair_detail, repair_date, repair_status) VALUES ('$room_id', '$app', '$cate', '$detail', '$date', '$status')";
      if ($conn->query($sql) === TRUE) {
          echo "<script>";
          echo "alert('ลงรายการแจ้งซ่อมเรียบร้อย');";
          echo "location.href = '../index.php';";
          echo "</script>";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $conn->close();
  }
}else{
  Header("Location: ../../../login.php");
}


?>