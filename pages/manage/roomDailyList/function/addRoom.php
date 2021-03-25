<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../../connection.php');
    $room = $_POST['room'];
    $type = $_POST['type'];
    echo $room;
    echo $type;
    $sql = "INSERT INTO roomDailyList (room_id, room_type, status) VALUES ('$room', '$type', 'ว่าง')";
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('เพิ่มห้อง $room ประเภท $type เรียบร้อย')</script>";
        echo "<script type='text/javascript'>location.assign('../index.php')</script>";
    } else {
        // echo "Error updating record: " . $conn->error;
        echo "<script>";
        echo "alert('ไม่สามารถเพิ่มห้องได้เนื่องจากมีเลขห้องนี้อยู่แล้ว');";
        echo "window.history.back();";
        echo "</script>";
    }
}else{
    Header("Location: ../../../login.php");
}

?>