<?php
session_start();
if($_SESSION['level'] == 'admin'){
  include('../../../connection.php');
  $rule = $_POST['rule_detail'];

  $sql = "UPDATE rule SET rule_detail ='$rule' WHERE 1";

  if ($conn->query($sql) === TRUE) {
    echo "<script>";
    echo "alert('แก้ไขกฎระเบียบเรียบร้อย');";
    echo "location.href = '../index.php';";
    echo "</script>";
  } else {
    echo "Error updating record: " . $conn->error;
  }

  $conn->close();
}else{
  Header("Location: ../../../login.php");
}

?>