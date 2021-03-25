<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../../connection.php";
    require_once "../../../../lib/PromptPayQR.php";
    function DateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("d", strtotime($strDate));
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $id = $_REQUEST["daily_id"];
    $name = $_REQUEST["name"];
    $search = mysqli_query($conn, "SELECT * FROM daily WHERE daily_id = $id");
    $result = mysqli_fetch_assoc($search);
    $pic_location = "../../../images/daily/" . $result["code"] . "/deposit/$name";
    $warning_date = date("Y-m-d");
    $promptData = mysqli_query($conn, "SELECT prompt_num, prompt_name FROM promptpay");
    $promptData_result = mysqli_fetch_assoc($promptData);
    $PromptPayQR = new PromptPayQR(); // new object
    $PromptPayQR->size = 4;
    $PromptPayQR->id = $promptData_result["prompt_num"]; // PromptPay ID
    $PromptPayQR->amount = $result["payment_price"]; // Set amount (not necessary)
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
    $email_receiver = $result["email"]; // เมล์ผู้รับ ***

    $subject = "โปรดอัปโหลดหลักฐานการชำระเงินค่ามันจำห้องพักใหม่"; // หัวข้อเมล์

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
                    <title>โปรดอัปโหลดหลักฐานการชำระเงินค่ามันจำห้องพักใหม่</title>
                </head>
                <body>
                    <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
                        <img src='https://pingfah-apartment.000webhostapp.com/img/logo.png' style='width:250px;height:60px;'>
                    </div>
                    <div style='background-color: #f6f4ec;width:900px;margin:0 auto;padding:16px;'>
                        <p><strong>โปรดอัปโหลดหลักฐานการชำระเงินใหม่อีกครั้ง เนื่องจากข้อมูลเป็นเท็จ (ภายในวันที่ " . DateThai($warning_date) . " มิเช่นนั้นการจองห้องพักจะถูกยกเลิก)</strong></p>
                        <img src='" . $PromptPayQR->generate() . "'>
                        <p><strong>จำนวนเงิน " . number_format($result["payment_price"]) . " บาท</strong></p>
                        <p><strong>(" . $promptData_result["prompt_num"] . " : " . $promptData_result["prompt_name"] . ")</strong></p>
                    </div>
                    <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
                        <p style='color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก), 093-2266753 (แม่บ้าน)</p>
                    </div>
                </body>
            </html>
        ";
    ///////////////////////////////////////////////////
    //  ถ้ามี email ผู้รับ
    if ($email_receiver) {
        $mail->msgHTML($email_content);
        $del = "UPDATE daily SET payment_img = NULL, warning_date = '$warning_date' WHERE daily_id = $id";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'ลบหลักฐานค่ามัดจำห้องพัก (" . $result["name_title"] . $result["firstname"] . " " . $result["lastname"] . ")', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        if ($mail->send() && $conn->query($del) === true && unlink($pic_location) && $conn->query($addLogs) === true) {
            echo "<script>";
            echo "alert('ลบรูปภาพหลักฐานค่ามัดจำห้องพัก และแจ้งให้ลูกค้าทราบเรียบร้อยแล้ว');";
            echo "location.href = '../dailyDetail.php?daily_id=$id';";
            echo "</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
            echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
        }
    }

} else {
    Header("Location: ../../../login.php");
}
