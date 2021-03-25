<?php
session_start();
if($_SESSION['level'] == 'admin'){
  include('../../../connection.php');
  $employee_id = $_REQUEST['employee_id'];
  $get_data = mysqli_query($conn,"SELECT * FROM employee WHERE employee_id = $employee_id");
  $result_get = mysqli_fetch_assoc($get_data);
  $sql = "DELETE FROM employee WHERE employee_id = $employee_id";
  $login = "DELETE FROM login WHERE username = '".$result_get["email"]."'";
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('พนักงาน', 'ลบข้อมูลพนักงาน (".$result_get["title_name"].$result_get["firstname"]." ".$result_get["lastname"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($conn->query($sql) === TRUE && $conn->query($login) === TRUE && $conn->query($addLogs) === TRUE) {
      echo "<script>";
      echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
      echo "location.href = '../index.php';";
      echo "</script>";
  } else {
      echo "Error deleting record: " . $conn->error;
  }
}else{
  Header("Location: ../../../login.php");
}
?>