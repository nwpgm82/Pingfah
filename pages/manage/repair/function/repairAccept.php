<?php
session_start();
if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest') {
    include '../../../connection.php';
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if (isset($_POST['repair_accept'])) {
        if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
            $room_id = $_POST['room_select'];
        } else if ($_SESSION["level"] == "guest") {
            $room_id = $_SESSION["name"];
        }
        $app = $_POST['repair_appliance'];
        $cate = $_POST['repair_category'];
        $detail = $_POST['repair_detail'];
        $date = date("Y-m-d");
        // $status = $_POST['repair_status'];
        // echo $room_id .$app .$cate .$detail .$date .$status;
        $get_member_id = mysqli_query($conn, "SELECT member_id FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'");
        $get_result = mysqli_fetch_assoc($get_member_id);
        $sql = "INSERT INTO repair (member_id, room_id, repair_appliance, repair_category, repair_detail, repair_date, repair_status) VALUES (" . $get_result["member_id"] . ", '$room_id', '$app', '$cate', '$detail', '$date', 'รอคิวซ่อม')";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('แจ้งซ่อม', 'เพิ่มรายการแจ้งซ่อม (ห้อง $room_id)', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        if ($conn->query($sql) === true && $conn->query($addLogs) === true) {
            $token = "kD2hurm9Ehfe3SPEWJ49oP5LZytJ2cV9ZoX4BF9Ga40";
            $str = "\n"."***มีรายการแจ้งซ่อม***" . "\n" . "เลขห้อง : $room_id" . "\n" . "ประเภท : $cate" . "\n" . "อุปกรณ์ : $app"."\n"."วันที่แจ้งซ่อม : ".DateThai($date);
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
                )
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if($err){
              echo "cURL Error #:".$err;
            }else{
              echo "<script>";
              echo "alert('เพิ่มข้อมูลการแจ้งซ่อมเรียบร้อย');";
              echo "location.href = '../index.php';";
              echo "</script>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
} else {
    Header("Location: ../../../login.php");
}
