<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../../connection.php');
    $package_id = $_REQUEST["package_id"];
    $received = $_POST["received"]; 
    $re_date = date("Y-m-d");
    $sql = "UPDATE package SET package_status ='รับพัสดุแล้ว', package_received = '$received', package_receiveddate = '$re_date' WHERE package_id = '$package_id'";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('พัสดุ', 'เปลี่ยนสถานะพัสดุเป็น รับพัสดุแล้ว รับโดย $received', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
        echo "<script>";
        echo "alert('รับพัสดุเรียบร้อย');";
        echo "location.href = '../index.php';";
        echo "</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}else{
    Header("Location: ../../../login.php");
}

?>