<?php
session_start();
if($_SESSION['level'] == 'employee'){
    include('../../../connection.php');
    // $status = $_POST['status'];
    $num = $_REQUEST["ID"];
    $received = $_POST['received']; 
    $sql = "UPDATE package SET package_status ='รับพัสดุแล้ว', package_received = '$received' WHERE package_num = '$num'";
    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('รับพัสดุเรียบร้อย')</script>";
        echo "<script type='text/javascript'>location.assign('/Pingfah/pages/admin/package/index.php')</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}else{
    Header("Location: ../../../login.php");
}

?>