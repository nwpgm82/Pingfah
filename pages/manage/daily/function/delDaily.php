<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include("../../../connection.php");
    $id = $_REQUEST['daily_id'];
    $search = mysqli_query($conn, "SELECT * FROM daily WHERE daily_id = $id");
    $result = mysqli_fetch_assoc($search);
    $sql = "DELETE FROM daily WHERE daily_id = $id";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'ลบรายการเช่าห้องพัก (".$result["name_title"].$result["firstname"]." ".$result["lastname"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
        echo "<script>";
        echo "alert('ลบรายการเช่าห้องพักเรียบร้อยแล้ว');";
        echo "location.assign('../index.php');";
        echo "</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}else{
    Header("Location: ../../../login.php");
}
?>