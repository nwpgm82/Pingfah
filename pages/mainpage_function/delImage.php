<?php
session_start();
include("../connection.php");
$id = $_REQUEST["daily_id"];
$name = $_REQUEST["name"];
$get_code = mysqli_query($conn,"SELECT code FROM daily WHERE daily_id = $id");
$result_code = mysqli_fetch_assoc($get_code);
$pic_location = "../images/daily/".$result_code["code"]."/deposit/$name";
$del = "UPDATE daily SET payment_img = NULL WHERE daily_id = $id";
if($conn->query($del) === TRUE && unlink($pic_location)){
    echo "<script>";
    echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
    echo "location.href = '../checkCode.php?code=".$result_code['code']."';";
    echo "</script>";
}else{
    echo "Error deleting record: " . $conn->error;
} 
?>