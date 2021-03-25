<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../../connection.php');
    $id = $_REQUEST["ID"];
    $type = $_POST['type'];
    echo $id;
    echo $type;
    $sql = "UPDATE roomDailyList SET room_type ='$type' WHERE room_id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('แก้ไขประเภทห้อง $id เป็นห้อง $type เรียบร้อย')</script>";
        echo "<script type='text/javascript'>location.assign('../index.php')</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}else{
    Header("Location: ../../../login.php");
}
?>