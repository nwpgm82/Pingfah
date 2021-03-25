<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
  include("../../../connection.php");
  $cost_id = $_REQUEST["cost_id"];
  $pay_date = date("Y-m-d");
  $sql = "SELECT * FROM cost WHERE cost_id = $cost_id AND cost_status = 'ชำระเงินแล้ว' " or die(mysqli_error());
  $result = $conn->query($sql);
  $search2 = mysqli_query($conn, "SELECT room_id, date FROM cost WHERE cost_id = $cost_id");
  $result2 = mysqli_fetch_assoc($search2);
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'ยืนยันการชำระเงินค่าเช่าห้องพัก (ห้อง ".$result2["room_id"].")(".$result2["date"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($result->num_rows > 0) {
      echo "<script>";
      echo "alert('ไม่สามารถยืนยันได้เนื่องจากได้ยืนยันการชำระเรียบร้อยแล้วแล้ว');";
      echo "location.href = '../index.php';";
      echo "</script>";
  } else {
      $sql2 = "UPDATE cost SET cost_status = 'ชำระเงินแล้ว', pay_date = '$pay_date' WHERE cost_id = $cost_id ";
      if ($conn->query($sql2) === TRUE && $conn->query($addLogs) === TRUE) {
          echo "<script>";
          echo "alert('ยืนยันการชำระเงินเรียบร้อยแล้ว');";
          echo "location.href = '../index.php';";
          echo "</script>";
        } else {
          echo "Error updating record: " . $conn->error;
        }
  }
}else{
  Header("Location: ../../../login.php");
}
?>