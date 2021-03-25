<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    require_once "../../../../lib/PromptPayQR.php";
    $cost_id = $_REQUEST["cost_id"];
    $room_price = $_POST["room_price"];
    $cable_price = $_POST["cable_price"];
    $water_price = $_POST["water_price"];
    $elec_price = $_POST["elec_price"];
    $total_price = $_POST["total_price"];
    if(strpos($cost_id, $searchForValue) !== false) {
        // echo "Found";
        $cost_id = str_replace(',', '', $cost_id);
      }
      if(strpos($room_price, $searchForValue) !== false) {
        // echo "Found";
        $room_price = str_replace(',', '', $room_price);
      }
      if(strpos($cable_price, $searchForValue) !== false) {
        // echo "Found";
        $cable_price = str_replace(',', '', $cable_price);
      }
      if(strpos($daily_price, $searchForValue) !== false) {
        // echo "Found";
        $water_price = str_replace(',', '', $water_price);
      }
      if(strpos($daily_price, $searchForValue) !== false) {
        // echo "Found";
        $elec_price = str_replace(',', '', $elec_price);
      }
      if(strpos($daily_price, $searchForValue) !== false) {
        // echo "Found";
        $total_price = str_replace(',', '', $total_price);
      }
    $sql = "UPDATE cost SET room_cost = $room_price, water_bill = $water_price, elec_bill = $elec_price, cable_charge = $cable_price, total = $total_price WHERE cost_id = $cost_id";
    $search = mysqli_query($conn, "SELECT room_id, date FROM cost WHERE cost_id = $cost_id");
    $result = mysqli_fetch_assoc($search);
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'แก้ไขข้อมูลการชำระเงินค่าเช่าห้องพัก (ห้อง ".$result["room_id"].")(".$result["date"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    if($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE){
        $folder_prompt = "../../../images/cost/".$result["date"]."/".$result["room_id"]."/promptpay/qr-code.png";
        $promptData = mysqli_query($conn,"SELECT prompt_num FROM promptpay");
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
        echo "<script>";
        echo "alert('แก้ไขข้อมูลการชำระเงินเรียบร้อยแล้ว');";
        echo "window.history.back()";
        echo "</script>";
    }
}else{
    Header("Location: ../../../login.php");
}
?>