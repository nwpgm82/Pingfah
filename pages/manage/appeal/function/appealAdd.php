<?php
session_start();
if($_SESSION["level"] == "guest"){
    include("../../../connection.php");
    $room_id = $_SESSION["name"];
    $member_id = $_SESSION["member_id"];
    $topic = $_POST["topic"];
    $detail = $_POST["detail"];
    $appeal_date = date("Y-m-d");
    $sql = "INSERT INTO appeal (member_id, room_id, appeal_topic, appeal_detail, appeal_date) VALUES ($member_id, '$room_id', '$topic', '$detail', '$appeal_date')";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ร้องเรียน', 'เพิ่มรายการร้องเรียน', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
    if($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE){
        echo "<script>alert('ส่งคำร้องเรียนเรียบร้อยแล้ว');location.href = '../../index.php';</script>";
    }
}
?>