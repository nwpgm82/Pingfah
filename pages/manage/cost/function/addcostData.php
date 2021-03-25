<?php
session_start();
if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee') {
    include '../../../connection.php';
    require_once "../../../../lib/PromptPayQR.php";
    // $PromptPayQR = new PromptPayQR(); // new object
    // $PromptPayQR->size = 6;
    // $PromptPayQR->id = "0620477145"; // PromptPay ID
    // $PromptPayQR->amount = 300; // Set amount (not necessary)
    // $data = $PromptPayQR->generate();
    // list($type, $data) = explode(';', $data);
    // list(, $data) = explode(',', $data);
    // $data = base64_decode($data);
    // file_put_contents('../../../images/image.png', $data);
    // echo '<img src="' . $PromptPayQR->generate() . '" />';
    $room_id = $_POST['room_select'];
    $room_type = $_POST['room_type'];
    $room_price = $_POST['room_price'];
    $cable_price = $_POST['cable_price'];
    $water_price = $_POST['water_price'];
    $elec_price = $_POST['elec_price'];
    $total_price = $_POST['total_price'];
    $date = date("Y-m");
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'เพิ่มรายการชำระเงินค่าเช่าห้องพัก (ห้อง $room_id)($date)', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
    $searchData = mysqli_query($conn, "SELECT member_id FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'");
    $result_Data = mysqli_fetch_assoc($searchData);
    $sql = "INSERT INTO cost (room_id, member_id, room_type, member_status, cost_status, date, room_cost, water_bill, elec_bill, cable_charge, total) VALUES ('$room_id', " . $result_Data["member_id"] . ", '$room_type', 'กำลังเข้าพัก', 'รอการชำระเงิน', '$date', '$room_price', '$water_price', '$elec_price', '$cable_price', '$total_price')";
    $folder_cost = "../../../images/cost";
    $folder_date = "../../../images/cost/$date/";
    $folder_prompt = "../../../images/cost/$date/$room_id/promptpay/qr-code.png";
    if (!is_dir($folder_cost)) {
        mkdir($folder_cost);
    }
    if (!is_dir($folder_date)) {
        mkdir($folder_date);
    }
    if(!is_dir("../../../images/cost/$date/$room_id/")){
        mkdir("../../../images/cost/$date/$room_id/");
    }
    if(!is_dir("../../../images/cost/$date/$room_id/promptpay/")){
        mkdir("../../../images/cost/$date/$room_id/promptpay/");
    }
    $promptData = mysqli_query($conn, "SELECT prompt_num FROM promptpay");
    $promptData_result = mysqli_fetch_assoc($promptData);
    $PromptPayQR = new PromptPayQR(); // new object
    $PromptPayQR->size = 6;
    $PromptPayQR->id = $promptData_result["prompt_num"]; // PromptPay ID
    $PromptPayQR->amount = $total_price; // Set amount (not necessary)
    $data = $PromptPayQR->generate();
    list($type, $data) = explode(';', $data);
    list(, $data) = explode(',', $data);
    $data = base64_decode($data);
    file_put_contents($folder_prompt, $data);
    if ($conn->query($sql) === true && $conn->query($addLogs) === true) {
        echo "<script>";
        echo "alert('บันทึกค่าใช้จ่ายของห้อง $room_id เรียบร้อยแล้ว');";
        echo "location.href = '../index.php';";
        echo "</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
} else {
    Header("Location: ../../../login.php");
}
