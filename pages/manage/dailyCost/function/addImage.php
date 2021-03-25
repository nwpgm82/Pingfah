<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../../connection.php";
    $id = $_REQUEST['dailycost_id'];
    $search = mysqli_query($conn, "SELECT a.*, b.* FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE a.dailycost_id = $id");
    $result = mysqli_fetch_assoc($search);
    $folder_path = "../../../images/daily/";
    $folder_path2 = "../../../images/daily/" . $result["code"] . "/";
    if (!is_dir($folder_path)) {
        mkdir($folder_path);
    }
    if (!is_dir($folder_path2)) {
        mkdir($folder_path2);
    }
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['name'];
        $folder_path3 = "../../../images/daily/" . $result["code"] . "/payment/";
        if(!is_dir($folder_path3)){
            mkdir($folder_path3);
        }
        $target = "../../../images/daily/" . $result["code"] . "/payment/" . basename($file);
        move_uploaded_file($_FILES['file']['tmp_name'], $target);
        $sql = "UPDATE dailycost SET pay_img = '$file' WHERE dailycost_id = $id";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายวัน)', 'เพิ่มหลักฐานการชำระเงินค่าเช่าห้องพัก (" . $result["name_title"] . $result["firstname"] . " " . $result["lastname"] . ")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        $conn->query($sql);
        $conn->query($addLogs);
    }
    if (!empty($_FILES['file2']['name'])) {
        $file2 = $_FILES['file2']['name'];
        $folder_path4 = "../../../images/daily/" . $result["code"] . "/damages/";
        if(!is_dir($folder_path4)){
            mkdir($folder_path4);
        }
        $target2 = "../../../images/daily/" . $result["code"] . "/damages/" . basename($file2);
        move_uploaded_file($_FILES['file2']['tmp_name'], $target2);
        $sql2 = "UPDATE dailycost SET payafter_d_img = '$file2' WHERE dailycost_id = $id";
        $addLogs2 = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายวัน)', 'เพิ่มหลักฐานการชำระเงินค่าปรับ(ค่าเสียหาย) (" . $result["name_title"] . $result["firstname"] . " " . $result["lastname"] . ")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        $conn->query($sql2);
        $conn->query($addLogs2);
    }
    echo "<script>alert('เพิ่มหลักฐานการชำระเงินเรียบร้อยแล้ว');location.href = '../dailyCostDetail.php?dailycost_id=$id';</script>";
    // if ($conn->query($addLogs) === true) {
        
    // } else {
    //     echo "Error updating record: " . $conn->error;
    // }
    // if (!empty($_FILES['file']['name']) && empty($_FILES['file2']['name'])) {
    //     $file = $_FILES['file']['name'];
    //     $folder_path3 = "../../../images/daily/" . $result["code"] . "/payment/";
    //     if(!is_dir($folder_path3)){
    //         mkdir($folder_path3);
    //     }
    //     $target = "../../../images/daily/" . $result["code"] . "/payment/" . basename($file);
    //     $sql = "UPDATE dailycost SET pay_img = '$file' WHERE dailycost_id = $id";
    //     if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
    //         if ($conn->query($sql) === true && $conn->query($addLogs) === true) {
    //             echo "<script>alert('เพิ่มหลักฐานการชำระเงินค่าห้องพักเรียบร้อยแล้ว');location.href = '../dailyCostDetail.php?dailycost_id=$id';</script>";
    //         } else {
    //             echo "Error updating record: " . $conn->error;
    //         }
    //     }
    // }else if (empty($_FILES['file']['name']) && !empty($_FILES['file2']['name'])) {
    //     $file = $_FILES['file2']['name'];
    //     $folder_path4 = "../../../images/daily/" . $result["code"] . "/damages/";
    //     if(!is_dir($folder_path4)){
    //         mkdir($folder_path4);
    //     }
    //     $target = "../../../images/daily/" . $result["code"] . "/damages/" . basename($file);
    //     $sql = "UPDATE dailycost SET pay_img = '$file' WHERE dailycost_id = $id";
    //     if (move_uploaded_file($_FILES['file2']['tmp_name'], $target)) {
    //         if ($conn->query($sql) === true && $conn->query($addLogs) === true) {
    //             echo "<script>alert('เพิ่มหลักฐานการชำระเงินค่าห้องพักเรียบร้อยแล้ว');location.href = '../dailyCostDetail.php?dailycost_id=$id';</script>";
    //         } else {
    //             echo "Error updating record: " . $conn->error;
    //         }
    //     }
    // }else if(!empty($_FILES['file']['name']) && !empty($_FILES['file2']['name'])){

    // }
} else {
    Header("Location: ../../../login.php");
}
