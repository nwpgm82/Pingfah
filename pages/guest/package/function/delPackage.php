<?php
session_start();
if($_SESSION['level'] == 'employee'){
  include('../../../connection.php');
  $num = $_REQUEST["ID"];
  $sql = "DELETE FROM package WHERE package_num = '$num'";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('เพิ่มพัสดุเรียบร้อยแล้ว');location.assign('/Pingfah/pages/admin/package/index.php');</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}else{
  Header("Location: ../../../login.php");
}

?>