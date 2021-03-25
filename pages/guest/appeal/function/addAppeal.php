<?php
session_start();
if($_SESSION["level"] == 'guest'){
    include("../../../connection.php");
    $room_id = $_SESSION["ID"];
    $appeal_topic = $_POST["appeal_topic"];
    $appeal_date = date("Y-m-d");
    $appeal_detail = $_POST["appeal_detail"];
    $sql = "INSERT INTO appeal (room_id, appeal_topic, appeal_detail, appeal_date) VALUES ('$room_id','$appeal_topic','$appeal_detail','$appeal_date')";
    if($conn->query($sql) === TRUE){
        echo "<script>";
        echo "alert('ส่งคำร้องเรียนเรียบร้อยแล้ว');";
        echo "location.href = '../index.php';";
        echo "</script>";
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}else{
    header("Location : ../../../login.php");
}
?>