<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../../connection.php");
    $id = $_REQUEST["appeal_id"];
    $sql = "DELETE FROM appeal WHERE appeal_id = $id";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ร้องเรียน', 'ลบรายการร้องเรียน ($id)', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
    if($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE){
        echo "<script>";
        echo "alert('ลบรายการร้องเรียนเรียบร้อยแล้ว');";
        echo "window.location.assign('../index.php');";
        echo "</script>";
    }
}else{
    Header("Location: ../../../login.php");
}
?>