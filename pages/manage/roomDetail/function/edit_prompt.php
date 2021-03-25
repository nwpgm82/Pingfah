<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../../connection.php");
    $prompt_num = $_POST["prompt_num"];
    $prompt_img = @$_FILES["prompt_img"]["name"];
    $prompt_name = $_POST["prompt_name"];
    $main_target = "../../../images/promptpay/";
    $target = "../../../images/promptpay/".basename($prompt_img);
    if(!is_dir($main_target)){
        mkdir($main_target);
    }
    if(isset($prompt_img)){
        if(move_uploaded_file($_FILES['prompt_img']['tmp_name'], $target)){
            $searchData = "SELECT * FROM promptpay";
            $result = $conn->query($searchData);
            if ($result->num_rows <= 0) {
                $sql = "INSERT INTO promptpay (prompt_num, prompt_img, prompt_name) VALUES ('$prompt_num', '$prompt_img','$prompt_name')";
            }else{
                $sql = "UPDATE promptpay SET prompt_num = '$prompt_num', prompt_img = '$prompt_img', prompt_name = '$prompt_name'";
            }
            $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'แก้ไขรายละเอียดการชำระเงิน (PromptPay)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
            $conn->query($sql);
            $conn->query($addLogs);
        }
    }else{
        $searchData = "SELECT * FROM promptpay";
        $result = $conn->query($searchData);
        if ($result->num_rows <= 0) {
            $sql = "INSERT INTO promptpay (prompt_num, prompt_name) VALUES ('$prompt_num', '$prompt_name')";
        }else{
            $sql = "UPDATE promptpay SET prompt_num = '$prompt_num', prompt_name = '$prompt_name'"; 
        }
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'แก้ไขรายละเอียดการชำระเงิน (PromptPay)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        $conn->query($sql);
        $conn->query($addLogs);
    }
    // echo $prompt_img;
}
?>