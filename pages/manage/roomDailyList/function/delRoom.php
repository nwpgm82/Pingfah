<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../../connection.php');
    $id = $_REQUEST["ID"];
    echo $id;
    // $room_data = "DELETE FROM roommember WHERE room_member = $id";
    $sql = "DELETE FROM roomDailyList WHERE room_id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('ลบห้อง $id เรียบร้อย')</script>";
        echo "<script type='text/javascript'>location.assign('../index.php')</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}else{
    Header("Location: ../../../login.php");
}

?>