<?php
$conn = mysqli_connect("localhost", "root", "", "Pingfah") or die("Error: " . mysqli_error($conn));
mysqli_query($conn, "SET NAMES 'utf8' ");
date_default_timezone_set('Asia/Bangkok');
// //////////// กรณีไม่ได้ชำระเงินค่าห้องพักรายเดือน ///////////////////////
// $get_cost = "SELECT * FROM cost WHERE cost_status != 'ชำระเงินแล้ว'";
// $get_result = $conn->query($get_cost);
// if ($get_result->num_rows > 0) {
//     while ($cost = $get_result->fetch_assoc()) {
//         $cost_date = strtotime($cost["date"]);
//         $month = date("m", $cost_date);
//         $inc_month = sprintf("%02d", intval($month + 1));
//         $current_date = date("Y", $cost_date) . "-" . $inc_month . "-05";
//         $deadline_dateCheck = strtotime($current_date);
//         $diff = abs($deadline_dateCheck - strtotime(date("Y-m-d")));
//         $years = floor($diff / (365 * 60 * 60 * 24));
//         $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
//         $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
//         // echo date("Y-m-d",$cost_date)."/".date("Y-m-d",$deadline_dateCheck);     
//         if (strtotime(date("Y-m-d")) > $deadline_dateCheck) {
//             if ($cost["room_type"] == "แอร์") {
//                 $get_detail = mysqli_query($conn, "SELECT fines FROM roomdetail WHERE type = 'แอร์'");
//             } else if ($cost["room_type"] == "พัดลม") {
//                 $get_detail = mysqli_query($conn, "SELECT fines FROM roomdetail WHERE type = 'พัดลม'");
//             }
//             $detail_result = mysqli_fetch_assoc($get_detail);
//             $total_fines = floatval($detail_result["fines"] * $days);
//             $cost_totalprice = $cost["room_cost"] + $cost["water_bill"] + $cost["elec_bill"] + $cost["cable_charge"] + floatval($total_fines);
//             $update_cost = "UPDATE cost SET fines = $total_fines, total = $cost_totalprice WHERE cost_id = " . $cost["cost_id"];
//             if ($conn->query($update_cost) === true) {
//                 require_once "../../../lib/PromptPayQR.php";
//                 $date = date("Y-m");
//                 $folder_prompt = "../../images/cost/".$cost["date"]."/" . $cost["room_id"] . "/promptpay/qr-code.png";
//                 $promptData = mysqli_query($conn, "SELECT prompt_num FROM promptpay");
//                 $promptData_result = mysqli_fetch_assoc($promptData);
//                 $PromptPayQR = new PromptPayQR(); // new object
//                 $PromptPayQR->size = 6;
//                 $PromptPayQR->id = $promptData_result["prompt_num"]; // PromptPay ID
//                 $PromptPayQR->amount = $cost_totalprice; // Set amount (not necessary)
//                 $data = $PromptPayQR->generate();
//                 list($type, $data) = explode(';', $data);
//                 list(, $data) = explode(',', $data);
//                 $data = base64_decode($data);
//                 file_put_contents($folder_prompt, $data);
//             }
//         }
//     }
// }
// ///////////////////////////////////////////////////////////////////
///////////// กรณียกเลิกการจองเนื่องจากไม่ได้เข้าพักในวันที่ที่กำหนด และยกเลิกการจองเนื่องจากไม่ได้ชำระเงินค่ามัดจำห้องพัก ///////////////////////////
$sql = "SELECT * FROM daily";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['daily_status'] == "รอการเข้าพัก") {
            $date_paymentbefore = date("Y-m-d", strtotime($row['check_in']));
            if (date("Y-m-d") > $date_paymentbefore) {
                $update = "UPDATE daily SET daily_status = 'ยกเลิกการจอง' WHERE daily_id = '" . $row['daily_id'] . "'";
                $conn->query($update);
                ///////////////////// อีเมล ////////////////////////
                require $_SERVER['DOCUMENT_ROOT'] . "/Pingfah/phpmailer/PHPMailerAutoload.php";
                header('Content-Type: text/html; charset=utf-8');
                $mail = new PHPMailer;
                $mail->CharSet = "utf-8";
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $gmail_username = "pingfah.apartment@gmail.com"; // gmail ที่ใช้ส่ง
                $gmail_password = "Cresta5182"; // รหัสผ่าน gmail
                // ตั้งค่าอนุญาตการใช้งานได้ที่นี่ https://myaccount.google.com/lesssecureapps?pli=1
                $sender = "Pingfah Apartment"; // ชื่อผู้ส่ง
                $email_sender = "noreply.pingfah@gmail.com"; // เมล์ผู้ส่ง
                $email_receiver = $row['email']; // เมล์ผู้รับ ***
                $subject = "ยกเลิกคำสั่งการจองห้องพัก"; // หัวข้อเมล์
                $mail->Username = $gmail_username;
                $mail->Password = $gmail_password;
                $mail->setFrom($email_sender, $sender);
                $mail->addAddress($email_receiver);
                $mail->Subject = $subject;
                $email_content = "
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset=utf-8'/>
                        <title>ยกเลิกคำสั่งการจองห้องพัก</title>
                    </head>
                    <body>
                        <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
                            <img src='https://pingfah-apartment.000webhostapp.com/img/logo.png' style='width:250px;height:60px;'>
                        </div>
                        <div style='background-color: #f6f4ec;width:900px;height:424px;margin:0 auto;padding:16px;'>
                            <p><strong>ยกเลิกการจองห้องพักเนื่องจากท่านไม่ได้เข้าพักในวันที่ท่านกำหนดไว้</strong></p>
                        </div>
                        <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
                            <p style='color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
                        </div>
                    </body>
                </html>
                ";
                //  ถ้ามี email ผู้รับ
                if ($email_receiver) {
                    $mail->msgHTML($email_content);
                    if ($mail->send()) {
                        // echo "<script>";
                        // echo "alert('จองห้องเรียบร้อยแล้ว กรุณาดูคำสั่งในการจองได้ในอีเมล์');";
                        // echo "location.href = '../dailyDetail.php?daily_id=$id'";
                        // echo "</script>";
                    } else {
                        echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
                    }
                }
            }
        }
        if ($row["daily_status"] == "รอการยืนยัน" && $row["warning_date"] != null) {
            $date_warning = date("Y-m-d", strtotime($row['warning_date']));
            if (date("Y-m-d") > $date_warning) {
                $update = "UPDATE daily SET daily_status = 'ยกเลิกการจอง' WHERE daily_id = '" . $row['daily_id'] . "'";
                $conn->query($update);
                ///////////////////// อีเมล ////////////////////////
                require $_SERVER['DOCUMENT_ROOT'] . "/Pingfah/phpmailer/PHPMailerAutoload.php";
                header('Content-Type: text/html; charset=utf-8');
                $mail = new PHPMailer;
                $mail->CharSet = "utf-8";
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $gmail_username = "pingfah.apartment@gmail.com"; // gmail ที่ใช้ส่ง
                $gmail_password = "Cresta5182"; // รหัสผ่าน gmail
                // ตั้งค่าอนุญาตการใช้งานได้ที่นี่ https://myaccount.google.com/lesssecureapps?pli=1
                $sender = "Pingfah Apartment"; // ชื่อผู้ส่ง
                $email_sender = "noreply.pingfah@gmail.com"; // เมล์ผู้ส่ง
                $email_receiver = $row['email']; // เมล์ผู้รับ ***
                $subject = "ยกเลิกคำสั่งการจองห้องพัก"; // หัวข้อเมล์
                $mail->Username = $gmail_username;
                $mail->Password = $gmail_password;
                $mail->setFrom($email_sender, $sender);
                $mail->addAddress($email_receiver);
                $mail->Subject = $subject;
                $email_content = "
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset=utf-8'/>
                        <title>ยกเลิกคำสั่งการจองห้องพัก</title>
                    </head>
                    <body>
                        <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
                            <img src='https://pingfah-apartment.000webhostapp.com/img/logo.png' style='width:250px;height:60px;'>
                        </div>
                        <div style='background-color: #f6f4ec;width:900px;height:424px;margin:0 auto;padding:16px;'>
                            <p><strong>ยกเลิกการจองห้องพักเนื่องจากท่านไม่ได้ชำระเงินค่ามัดจำห้องพักก่อนวันที่ทางหอพักกำหนด</strong></p>
                        </div>
                        <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
                            <p style='color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก), 093-2266753 (แม่บ้าน)</p>
                        </div>
                    </body>
                </html>
                ";
                if ($email_receiver) {
                    $mail->msgHTML($email_content);
                    if ($mail->send()) {
                        // echo "<script>";
                        // echo "alert('จองห้องเรียบร้อยแล้ว กรุณาดูคำสั่งในการจองได้ในอีเมล์');";
                        // echo "location.href = '../dailyDetail.php?daily_id=$id'";
                        // echo "</script>";
                    } else {
                        echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
                    }
                }
            }
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////
/////////////////////////// ปรับอายุพนักงานตามวันเกิด ////////////////////////////////
$embirth = "SELECT employee_id, birthday FROM employee";
$embirth_result = $conn->query($embirth);
if ($embirth_result->num_rows > 0) {
    while ($emB = $embirth_result->fetch_assoc()) {
        $Emtoday = date("Y-m-d");
        $birthEm_diff = date_diff(date_create($emB["birthday"]), date_create($Emtoday));
        $currentEm_age = $birthEm_diff->format("%y");
        $updateEm_age = "UPDATE employee SET age = $currentEm_age WHERE employee_id = ".$emB["employee_id"];
        $conn->query($updateEm_age);
    }
}
////////////////////////////////////////////////////////////////////////////////
/////////////////////////// ปรับอายุลูกค้าตามวันเกิด ////////////////////////////////
$membirth = "SELECT member_id, birthday, birthday2 FROM roommember";
$membirth_result = $conn->query($membirth);
if ($membirth_result->num_rows > 0) {
    while ($memB = $membirth_result->fetch_assoc()) {
        $Mem_today = date("Y-m-d");
        $birthMem_diff = date_diff(date_create($memB["birthday"]), date_create($Mem_today));
        $birthMem2_diff = date_diff(date_create($memB["birthday"]), date_create($Mem_today));
        $currentMem_age = $birthMem_diff->format("%y");
        $currentMem_age2 = $birthMem2_diff->format("%y");
        $updateMem_age = "UPDATE roommember SET age = $currentMem_age, age2 = $currentMem_age2 WHERE member_id = ".$memB["member_id"];
        $conn->query($updateMem_age);
    }
}
////////////////////////////////////////////////////////////////////////////////
