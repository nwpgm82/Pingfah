<?php
include "../connection.php";
$daily_id = $_REQUEST["daily_id"];
$img = $_FILES['payment_img']['name'];
$result = mysqli_query($conn, "SELECT * FROM daily WHERE daily_id = $daily_id LIMIT 1");
$row = mysqli_fetch_assoc($result);
$sql = "UPDATE daily SET payment_img = '$img' WHERE daily_id = $daily_id";
$path_file = "../images/daily/";
$path_file2 = "../images/daily/" . $row["code"] . "/";
$path_file3 = "../images/daily/" . $row["code"] . "/deposit/";
$target = "../images/daily/" . $row["code"] . "/deposit/" . basename($img);
if (!is_dir($path_file)) {
    mkdir($path_file);
    mkdir($path_file2);
    mkdir($path_file3);
}
if (!empty($img)) {
    if (move_uploaded_file($_FILES['payment_img']['tmp_name'], $target)) {
        if ($conn->query($sql) === true) {

            $token = "kD2hurm9Ehfe3SPEWJ49oP5LZytJ2cV9ZoX4BF9Ga40";
            $str = "\n" . "***มีหลักฐานการชำระเงินค่ามัดจำห้องพัก (อีกรอบ)***"."\n"."ชื่อ : ".$row["name_title"].$row["firstname"]." ".$row["lastname"]."\n"."เบอร์โทรศัพท์ : ".$row["tel"]."\n"."เลขที่การจอง : ".$row["code"]."\n"."***โปรดตรวจสอบหลักฐานชำระเงินค่ามัดจำห้องพัก***";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "message=" . $str,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $token,
                    "Cache-Control: no-cache",
                    "Content-Type: application/x-www-form-urlencoded",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo "<script>";
                echo "alert('เพิ่มรูปภาพหลักฐานค่ามัดจำห้องพัก และแจ้งให้พนักงานทราบเรียบร้อยแล้ว');";
                echo "location.href = '../checkCode.php?code=" . $row['code'] . "';";
                echo "</script>";
            }
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "<script>";
    echo "alert('เพิ่มรูปภาพเรียบร้อยแล้ว');";
    echo "location.href = '../checkCode.php?code=" . $row['code'] . "';";
    echo "</script>";
}
