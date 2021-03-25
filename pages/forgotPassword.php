<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/forgotPass.css">
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="forgotPass">
        <a href="../index.php"><img src="../img/main_logo.png" alt=""></a>
        <form method="POST">
            <h3>ลืมรหัสผ่าน ?</h3>
            <p>กรุณากรอกอีเมลของท่านที่ได้ทำการลงทะเบียน</p>
            <input type="email" name="email" placeholder="อีเมล / Email">
            <div style="padding-top:32px;display:flex;justify-content:flex-end;align-items:flex-end;">
                <button type="submit" name="confirm">ยืนยัน</button>
            </div>
        </form>
    </div>
</body>

</html>
<?php
if (isset($_POST["confirm"])) {
    include "connection.php";
    function createRandomPassword()
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 20) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
    $email = $_POST["email"];
    $searchEmail = "SELECT * FROM login WHERE email = '$email'";
    $result = $conn->query($searchEmail);
    if ($result->num_rows > 0) {
        $expFormat = mktime(date("H"), date("i") + 15, date("s"), date("m"), date("d"), date("Y"));
        $expDate = date("Y-m-d H:i:s", $expFormat);
        $key = createRandomPassword();
        $re = "INSERT INTO reset_password_temp (email, token, expDate) VALUES ('$email', '$key', '$expDate')";
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

        $subject = "รีเซ็ตรหัสผ่าน"; // หัวข้อเมล์

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
    			<title>รีเซ็ตรหัสผ่าน</title>
    		</head>
    		<body>
    			<div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
    				<img src='https://pingfah-apartment.000webhostapp.com/img/logo.png' style='width:250px;height:60px;'>
    			</div>
    			<div style='background-color: #f6f4ec;width:900px;margin:0 auto;padding:16px;line-height:40px;'>
                    <h3><strong>รีเซ็ตรหัสผ่าน</strong></h3>
                    <p style='font-size:16px;'>คลิกที่ปุ่ม 'รีเซ็ตรหัสผ่าน' เพื่อตั้งค่ารหัสใหม่ของคุณ (ลิ้งค์นี้มีอายุการใช้งาน 15 นาที)</p>
                    <div style='padding-top:32px;text-align:center;'>
                        <a href='localhost/Pingfah/pages/createNewPassword.php?key=$key' target='_blank'><button style='padding: 0 16px;width: auto;height: 40px;border-radius: 4px;border: none;background-color: rgb(131, 120, 47, 0.7);font-size: 16px;font-weight: 500;color: #fff;cursor:pointer;'>รีเซ็ตรหัสผ่าน</button><a>
                    </div>
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
            if ($mail->send() && $conn->query($re) === true) {
                echo "<script>";
                echo "alert('กรุณาตรวจสอบอีเมล์ของท่าน');";
                echo "location.href = 'login.php';";
                echo "</script>";
            } else {
                echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
                echo "Error: " . $re . "<br>" . $conn->error;
            }
        }
    } else {
        echo "<script>";
        echo "alert('ไม่พบบัญชีผู้ใช้');";
        echo "</script>";
    }
}
?>