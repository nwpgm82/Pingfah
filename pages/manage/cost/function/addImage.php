<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee" || $_SESSION["level"] == "guest"){
    include("../../../connection.php");
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strMonthThai $strYear";
    }
    $cost_id = $_REQUEST["cost_id"];
    $pay_img = $_FILES["pay_img"]["name"];
    $search = mysqli_query($conn, "SELECT * FROM cost WHERE cost_id = $cost_id");
    $result_search = mysqli_fetch_assoc($search);
    $cost_path = "../../../images/cost/";
    $sub_cost_path = "../../../images/cost/".$result_search["date"]."/";
    $sub2_cost_path  = "../../../images/cost/".$result_search["date"]."/".$result_search["room_id"]."/";
    $sub3_cost_path  = "../../../images/cost/".$result_search["date"]."/".$result_search["room_id"]."/payment/";
    $target = "../../../images/cost/".$result_search["date"]."/".$result_search["room_id"]."/payment/".basename($pay_img);
    if(!is_dir($cost_path)){
        mkdir($cost_path);
    }
    if(!is_dir($sub_cost_path)){
        mkdir($sub_cost_path);
    }
    if(!is_dir($sub2_cost_path)){
        mkdir($sub2_cost_path);
    }
    if(!is_dir($sub3_cost_path)){
        mkdir($sub3_cost_path);
    }
    if(move_uploaded_file($_FILES["pay_img"]["tmp_name"], $target)){
        $sql = "UPDATE cost SET pay_img = '$pay_img' WHERE cost_id = $cost_id";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'เพิ่มหลักฐานการชำระเงินค่าเช่าห้องพัก (ห้อง ".$result_search["room_id"].")(" .DateThai($result_search["date"]) .")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        if($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE){
            $token = "kD2hurm9Ehfe3SPEWJ49oP5LZytJ2cV9ZoX4BF9Ga40";
            $str = "\n"."***มีการเพิ่มหลักฐานการชำระเงินค่าห้องพัก***" . "\n" . "เลขห้อง : ".$result_search["room_id"]."\n"."ประจำเดือน : ".DateThai($result_search["date"])."\n"."ยอดรวม : ".number_format($result_search["total"],2)." บาท";
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
               echo "<script>
                alert('เพิ่มหลักฐานการชำระเงินค่าห้องพักเรียบร้อยแล้ว');
                location.href = '../costDetail.php?cost_id=$cost_id';
                </script>"; 
            }
            
        }
    }
    
}else{
    header("Location: ../../../login.php");
}
?>