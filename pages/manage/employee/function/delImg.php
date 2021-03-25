<?php
session_start();
if($_SESSION['level'] == 'admin'){
  include('../../../connection.php');
  $username = $_REQUEST['username'];
  $pic = $_REQUEST['pic'];
  $searchpic = "SELECT $pic FROM employee WHERE username = '$username'";
  $pic_result = $conn->query($searchpic);
  if ($pic_result->num_rows > 0) {
    $row = $pic_result->fetch_assoc();
    $picData = $row["$pic"];
  }
  $sql = "UPDATE employee SET $pic = NULL WHERE username = '$username' ";
  if ($conn->query($sql) === TRUE) {
    echo "<script>";
    echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
    echo "window.history.back();";
    echo "</script>";
  } else {
    echo "Error updating record: " . $conn->error;
  }
}else{
  Header("Location: ../../../login.php");
}

?>