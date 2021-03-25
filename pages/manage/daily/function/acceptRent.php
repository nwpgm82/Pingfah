<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../../connection.php";
    require_once "../../../../lib/PromptPayQR.php";
    $daily_id = $_REQUEST["daily_id"];
    $sql = "UPDATE daily SET daily_status = 'รอการเข้าพัก' WHERE daily_id = $daily_id";
    $search = mysqli_query($conn, "SELECT name_title, firstname, lastname, email, code, payment_price FROM daily WHERE daily_id = $daily_id");
    $result_search = mysqli_fetch_assoc($search);
    $email = $result_search["email"];
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เปลี่ยนสถานะเป็น รอการเข้าพัก (" . $result_search["name_title"] . $result_search["firstname"] . " " . $result_search["lastname"] . ")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
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
    $email_receiver = "$email"; // เมล์ผู้รับ ***

    $subject = "ยืนยันการเข้าพัก"; // หัวข้อเมล์

    $mail->Username = $gmail_username;
    $mail->Password = $gmail_password;
    $mail->setFrom($email_sender, $sender);
    $mail->addAddress($email_receiver);
    $mail->Subject = $subject;
    $mail->AddEmbeddedImage("../../../../img/logo.png", "logo", "logo.png");

    $email_content = "
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset='utf-8'/>
                <title>ยืนยันการเข้าพัก</title>
            </head>
            <body>
                <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
                    <img src='cid:logo' style='width:250px;height:60px;'>
                </div>
                <div style='background-color: #f6f4ec;width:900px;height:424px;margin:0 auto;padding:16px;'>
                    <p>หลักฐานการชำระเงินค่ามัดจำห้องพักได้รับการยืนยันแล้ว สามารถเข้าพักในวันที่ท่านได้จองห้องพักไว้ได้ในเวลา 14.00 น. เป็นต้นไป</p>
                    <p>*** ท่านสามารถดาวน์โหลดหลักฐานการชำระเงินค่ามัดจำห้องพักได้ที่เมนูตรวจสอบการจอง ***</p>
                </div>
                <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
                    <p style='color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
                </div>
            </body>
        </html>
    ";
    ///////////////////////////////////////////////////
    //  ถ้ามี email ผู้รับ
    if ($email_receiver) {
        $mail->msgHTML($email_content);
        if ($mail->send() && $conn->query($sql) === true && $conn->query($addLogs) === true) {
            mkdir("../../../images/daily/".$result_search["code"]."/promptpay/");
            $folder_prompt = "../../../images/daily/".$result_search["code"]."/promptpay/qr-code.png";
            $promptData = mysqli_query($conn,"SELECT prompt_num FROM promptpay");
            $promptData_result = mysqli_fetch_assoc($promptData);
            $PromptPayQR = new PromptPayQR(); // new object
            $PromptPayQR->size = 6;
            $PromptPayQR->id = $promptData_result["prompt_num"]; // PromptPay ID
            $PromptPayQR->amount = $result_search["payment_price"]; // Set amount (not necessary)
            $data = $PromptPayQR->generate();
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents($folder_prompt, $data);
            echo "<script>";
            echo "alert('ยืนยันการเข้าพักเรียบร้อยแล้ว');";
            echo "location.href = '../index.php';";
            echo "</script>";
        } else {
            echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
        }
    }
}
