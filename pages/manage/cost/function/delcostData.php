<?php
session_start();
if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee') {
    include "../../../connection.php";
    $cost_id = $_REQUEST['cost_id'];
    $del = "DELETE FROM cost WHERE cost_id = $cost_id";
    $search = mysqli_query($conn, "SELECT room_id, date, cost_status FROM cost WHERE cost_id = $cost_id");
    $result = mysqli_fetch_assoc($search);
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'ลบรายการชำระเงินค่าเช่าห้องพัก (ห้อง " . $result["room_id"] . ")(" . $result["date"] . ")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
    if ($conn->query($del) === true && $conn->query($addLogs) === true) {
        if ($result["cost_status"] != "ชำระเงินแล้ว") {
            $folder_path = glob("../../../images/cost/" . $result["date"] . "/" . $result["room_id"] . "/promptpay/*");
            foreach ($folder_path as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir("../../../images/cost/" . $result["date"] . "/" . $result["room_id"] . "/promptpay");
            rmdir("../../../images/cost/" . $result["date"] . "/" . $result["room_id"]);
        }
        echo "<script>";
        echo "alert('ลบรายการชำระเงินเรียบร้อยแล้ว');";
        echo "location.href = '../index.php';";
        echo "</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $conn->close();
} else {
    Header("Location: ../../../login.php");
}
