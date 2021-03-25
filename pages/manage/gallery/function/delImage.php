<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    $id = $_POST["id"];
    $name = $_POST["img_name"];
    $pic_location = "../../../images/gallery/$name";
    $countCheck = mysqli_query($conn,"SELECT COUNT(gallery_name) AS total FROM gallery WHERE gallery_name = '$name' GROUP BY gallery_name HAVING COUNT(gallery_name)");
    $countTotal = mysqli_fetch_assoc($countCheck);
    if($countTotal['total'] > 1){
        $del = "DELETE FROM gallery WHERE gallery_id = $id";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('แกลเลอรี่', 'ลบรูปภาพ $name', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        if($conn->query($del) === TRUE && $conn->query($addLogs) === TRUE){
            echo "<script>";
            echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
            echo "window.location.assign('../index.php');";
            echo "</script>";
        }else{
            echo "Error deleting record: " . $conn->error;
        } 
    }else{
        $del = "DELETE FROM gallery WHERE gallery_id = $id";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('แกลเลอรี่', 'ลบรูปภาพ $name', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        if($conn->query($del) === TRUE && unlink($pic_location) && $conn->query($addLogs) === TRUE){
            echo "<script>";
            echo "alert('ลบรูปภาพเรียบร้อยแล้ว');";
            echo "window.location.assign('../index.php');";
            echo "</script>";
        }else{
            echo "Error deleting record: " . $conn->error;
        } 
    }
}else{
    Header("Location: ../../../login.php");
}

?>