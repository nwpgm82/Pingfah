<?php
session_start();
include "../connection.php";
if (isset($_POST['accept_daily'])) {
    function DateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("d", strtotime($strDate));
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    function createRandomPassword()
    {
        global $tel;
        $date_split = explode("-", $_SESSION["check_in"]);
        $tel_split = str_split($tel);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = "";
        while ($i < 3) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        if (intval($_SESSION["air"]) != 0 && intval($_SESSION["fan"]) == 0) {
            return "AIR" . $pass . $date_split[2] . $date_split[1] . $tel_split[2] . $tel_split[4] . $tel_split[6];
        } else if (intval($_SESSION["air"]) == 0 && intval($_SESSION["fan"]) != 0) {
            return "FAN" . $pass . $date_split[2] . $date_split[1] . $tel_split[2] . $tel_split[4] . $tel_split[6];
        } else {
            return "ALL" . $pass . $date_split[2] . $date_split[1] . $tel_split[2] . $tel_split[4] . $tel_split[6];
        }
    }
    $name_title = $_POST["name_title"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $id_card = $_POST["id_card"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $deposit = $_FILES["deposit_img"]["name"];
    $total_room = intval($_SESSION["air"]) + intval($_SESSION["fan"]);
    $code = createRandomPassword();
    do {
        $checkCode_repeat = "SELECT * FROM daily WHERE code = '$code'";
        $checkCode_result = $conn->query($checkCode_repeat);
        if ($checkCode_result->num_rows > 0) {
            $code = createRandomPassword();
        }
    } while ($checkCode_result->num_rows > 0);
    $daily_folder = "../images/daily/";
    if (!is_dir($daily_folder)) {
        mkdir($daily_folder);
    }
    mkdir("../images/daily/$code/");
    mkdir("../images/daily/$code/deposit/");
    $target_file = "../images/daily/$code/deposit/" . basename($deposit);
    $countRoom = mysqli_query($conn, "SELECT SUM(room_type = 'แอร์') AS airTotal, SUM(room_type = 'พัดลม') AS fanTotal FROM roomlist WHERE room_cat = 'รายวัน'");
    $roomData = mysqli_fetch_assoc($countRoom);
    $countDaily = mysqli_query($conn, "SELECT SUM(air_room) AS daily_airTotal, SUM(fan_room) AS daily_fanTotal FROM daily WHERE ((check_in BETWEEN '" . $_SESSION["check_in"] . "' AND '" . $_SESSION["check_out"] . "') OR (check_out BETWEEN '" . $_SESSION["check_in"] . "' AND '" . $_SESSION["check_out"] . "') OR ('" . $_SESSION["check_in"] . "' BETWEEN check_in AND check_out) OR ('" . $_SESSION["check_out"] . "' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง'");
    $dailyData = mysqli_fetch_assoc($countDaily);
    $airTotal = intval($roomData["airTotal"]) - intval($dailyData["daily_airTotal"]);
    $fanTotal = intval($roomData["fanTotal"]) - intval($dailyData["daily_fanTotal"]);
    if (intval($_SESSION["air"]) <= $airTotal && intval($_SESSION["fan"]) <= $fanTotal) {
        $sql = "INSERT INTO daily (name_title, firstname, lastname, id_card, email, tel, code, check_in, check_out, night, people, air_room, fan_room, daily_status,air_price, fan_price, total_price, payment_price, payment_img) VALUES ('$name_title', '$firstname', '$lastname', '$id_card', '$email', '$tel', '$code', '" . $_SESSION["check_in"] . "', '" . $_SESSION["check_out"] . "'," . $_SESSION["night"] . "," . $_SESSION["people"] . "," . $_SESSION["air"] . "," . $_SESSION["fan"] . ", 'รอการยืนยัน',".$_SESSION["air_price"].",".$_SESSION["fan_price"]."," . $_SESSION["total_price"] . "," . $_SESSION["total_room"] . ", '$deposit')";
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

        $subject = "คำสั่งในการจองห้องพัก"; // หัวข้อเมล์

        $mail->Username = $gmail_username;
        $mail->Password = $gmail_password;
        $mail->setFrom($email_sender, $sender);
        $mail->addAddress($email_receiver);
        $mail->Subject = $subject;
        $mail->AddEmbeddedImage("../../img/logo.png", "logo", "logo.png");
        // $mail->addEmbeddedImage("../../img/tool/qr-code.png","qr_code","qr-code.png");
        if($_SESSION["air"] != 0 && $_SESSION["fan"] == 0){
            $email_content = "
        	<!DOCTYPE html>
        	<html>
        		<head>
        			<meta charset=utf-8'/>
                    <title>คำสั่งในการจองห้องพัก</title>
                    <style>
                    *{
                        padding: 0;
                        margin: 0;
                        box-sizing: border-box;
                        line-height: 30px;
                    }
                    </style>
                </head>
                <body>
        			<div style='background-color: #edeadb;width:1000px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
        				<img src='cid:logo' style='width:250px;height:60px;'>
        			</div>
        			<div style='background-color: #f6f4ec;width:1000px;margin:0 auto;padding:16px;'>
                        <div style='padding-top:32px;'>
                            <h2 style='text-align:center;color:#000'><strong>เลขที่การจอง :</strong> $code</h2>
                            <h3 style='text-align:center;color:#000;padding-top:16px;'><strong>สถานะ : รอการยืนยัน</strong></h3>
                        </div>
                        <h2 style='color:#000'>รายละเอียดการจองห้องพัก</h2>
                        <div>
                            <p style='font-size:16px;color:#000'><strong>ชื่อ :</strong> $name_title$firstname  $lastname</p>
                            <p style='font-size:16px;color:#000'><strong>เลขบัตรประชาชน / Passport No. :</strong> $id_card</p>
                            <p style='font-size:16px;color:#000'><strong>อีเมล :</strong> $email</p>
                            <p style='font-size:16px;color:#000'><strong>เบอร์โทรศัพท์ :</strong> $tel</p>
                        </div>
                        <div style='width: 600px;margin: 32px auto;'>
                            <h4>วันที่เข้าพัก : ".DateThai($_SESSION["check_in"])." - ".DateThai($_SESSION["check_out"])." (".$_SESSION["night"]." คืน)</h4>
                            <table style='padding-top: 16px;width: 100%;border-collapse: collapse;margin: auto;'>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>".$_SESSION["air"]." x ห้องแอร์</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format($_SESSION["air_price"] - (($_SESSION["air_price"] * 7)/100),2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>ภาษีมูลค่าเพิ่ม 7%</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(($_SESSION["total_price"]*7)/100,2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;border-top: 1px solid rgb(131, 120, 47, 0.7);'>
                                    <td>
                                        <p style='color:#000;'>ราคารวมทั้งหมด</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(ceil($_SESSION["total_price"]),2)."</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <p style='text-align:center;color:#000;'>*** รอการตรวจสอบข้อมูล และยืนยันข้อมูลประมาณ 10-30 นาทีในช่วงเวลาหอพักเปิดทำการ (10:00 - 16:00 น.) เมื่อสถานะยืนยันแล้วท่านจะได้รับอีเมลอีกครั้ง หากสถานะยังไม่เปลี่ยนแปลงภายในเวลาที่กำหนดโปรดติดต่อเจ้าของหอพัก ***</p>
                            <p style='text-align:center;color:#000;'>*** สามารถตรวจสอบสถานะได้ที่เมนู ตรวจสอบการจอง ***</p>
                        </div>
        			</div>
        			<div style='background-color: #edeadb;width:1000px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
        				<p style='font-size:16px;color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
        			</div>
        		</body>
        	</html>
        ";
        }else if($_SESSION["air"] == 0 && $_SESSION["fan"] != 0){
            $email_content = "
        	<!DOCTYPE html>
        	<html>
        		<head>
        			<meta charset=utf-8'/>
                    <title>คำสั่งในการจองห้องพัก</title>
                    <style>
                    *{
                        padding: 0;
                        margin: 0;
                        box-sizing: border-box;
                        line-height: 30px;
                    }
                    </style>
                </head>
                <body>
        			<div style='background-color: #edeadb;width:1000px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
        				<img src='cid:logo' style='width:250px;height:60px;'>
        			</div>
        			<div style='background-color: #f6f4ec;width:1000px;margin:0 auto;padding:16px;'>
                        <div style='padding-top:32px;'>
                            <h2 style='text-align:center;color:#000'><strong>เลขที่การจอง :</strong> $code</h2>
                            <h3 style='text-align:center;color:#000;padding-top:16px;'><strong>สถานะ : รอการยืนยัน</strong></h3>
                        </div>
                        <h2 style='color:#000'>รายละเอียดการจองห้องพัก</h2>
                        <div>
                            <p style='font-size:16px;color:#000'><strong>ชื่อ :</strong> $name_title$firstname  $lastname</p>
                            <p style='font-size:16px;color:#000'><strong>เลขบัตรประชาชน / Passport No. :</strong> $id_card</p>
                            <p style='font-size:16px;color:#000'><strong>อีเมล :</strong> $email</p>
                            <p style='font-size:16px;color:#000'><strong>เบอร์โทรศัพท์ :</strong> $tel</p>
                        </div>
                        <div style='width: 600px;margin: 32px auto;'>
                            <h4>วันที่เข้าพัก : ".DateThai($_SESSION["check_in"])." - ".DateThai($_SESSION["check_out"])." (".$_SESSION["night"]." คืน)</h4>
                            <table style='padding-top: 16px;width: 100%;border-collapse: collapse;margin: auto;'>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>".$_SESSION["fan"]." x ห้องพัดลม</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format($_SESSION["fan_price"] - (($_SESSION["fan_price"] * 7)/100),2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>ภาษีมูลค่าเพิ่ม 7%</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(($_SESSION["total_price"]*7)/100,2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;border-top: 1px solid rgb(131, 120, 47, 0.7);'>
                                    <td>
                                        <p style='color:#000;'>ราคารวมทั้งหมด</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(ceil($_SESSION["total_price"]),2)."</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <p style='text-align:center;color:#000;'>*** รอการตรวจสอบข้อมูล และยืนยันข้อมูลประมาณ 10-30 นาทีในช่วงเวลาหอพักเปิดทำการ (10:00 - 16:00 น.) เมื่อสถานะยืนยันแล้วท่านจะได้รับอีเมลอีกครั้ง หากสถานะยังไม่เปลี่ยนแปลงภายในเวลาที่กำหนดโปรดติดต่อเจ้าของหอพัก ***</p>
                            <p style='text-align:center;color:#000;'>*** สามารถตรวจสอบสถานะได้ที่เมนู ตรวจสอบการจอง ***</p>
                        </div>
        			</div>
        			<div style='background-color: #edeadb;width:1000px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
        				<p style='font-size:16px;color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
        			</div>
        		</body>
        	</html>
        ";
        }else{
            $email_content = "
        	<!DOCTYPE html>
        	<html>
        		<head>
        			<meta charset=utf-8'/>
                    <title>คำสั่งในการจองห้องพัก</title>
                    <style>
                    *{
                        padding: 0;
                        margin: 0;
                        box-sizing: border-box;
                        line-height: 30px;
                    }
                    </style>
                </head>
                <body>
        			<div style='background-color: #edeadb;width:1000px;margin:0 auto;padding:16px;display:flex;align-items:center;' >
        				<img src='cid:logo' style='width:250px;height:60px;'>
        			</div>
        			<div style='background-color: #f6f4ec;width:1000px;margin:0 auto;padding:16px;'>
                        <div style='padding-bottom:32px;'>
                            <h2 style='text-align:center;color:#000'><strong>เลขที่การจอง :</strong> $code</h2>
                            <h3 style='text-align:center;color:#000;padding-top:16px;'><strong>สถานะ : รอการยืนยัน</strong></h3>
                        </div>
                        <h2 style='color:#000'>รายละเอียดการจองห้องพัก</h2>
                        <div>
                            <p style='font-size:16px;color:#000'><strong>ชื่อ :</strong> $name_title$firstname  $lastname</p>
                            <p style='font-size:16px;color:#000'><strong>เลขบัตรประชาชน / Passport No. :</strong> $id_card</p>
                            <p style='font-size:16px;color:#000'><strong>อีเมล :</strong> $email</p>
                            <p style='font-size:16px;color:#000'><strong>เบอร์โทรศัพท์ :</strong> $tel</p>
                        </div>
                        <div style='width: 600px;margin: 32px auto;'>
                            <h4>วันที่เข้าพัก : ".DateThai($_SESSION["check_in"])." - ".DateThai($_SESSION["check_out"])." (".$_SESSION["night"]." คืน)</h4>
                            <table style='padding-top: 16px;width: 100%;border-collapse: collapse;margin: auto;'>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>".$_SESSION["air"]." x ห้องแอร์</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format($_SESSION["air_price"] - (($_SESSION["air_price"] * 7)/100),2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>".$_SESSION["fan"]." x ห้องพัดลม</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format($_SESSION["fan_price"] - (($_SESSION["fan_price"] * 7)/100),2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;'>
                                    <td>
                                        <p style='color:#000;'>ภาษีมูลค่าเพิ่ม 7%</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(($_SESSION["total_price"]*7)/100,2)."</p>
                                    </td>
                                </tr>
                                <tr style='height: 50px;border-top: 1px solid rgb(131, 120, 47, 0.7);'>
                                    <td>
                                        <p style='color:#000;'>ราคารวมทั้งหมด</p>
                                    </td>
                                    <td style='text-align: right;'>
                                        <p style='color:#000;'>฿ ".number_format(ceil($_SESSION["total_price"]),2)."</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <p style='text-align:center;color:#000;'>*** รอการตรวจสอบข้อมูล และยืนยันข้อมูลประมาณ 10-30 นาทีในช่วงเวลาหอพักเปิดทำการ (10:00 - 16:00 น.) เมื่อสถานะยืนยันแล้วท่านจะได้รับอีเมลอีกครั้ง หากสถานะยังไม่เปลี่ยนแปลงภายในเวลาที่กำหนดโปรดติดต่อเจ้าของหอพัก ***</p>
                            <p style='text-align:center;color:#000;'>*** สามารถตรวจสอบสถานะได้ที่เมนู ตรวจสอบการจอง ***</p>
                        </div>
        			</div>
        			<div style='background-color: #edeadb;width:1000px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
        				<p style='font-size:16px;color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
        			</div>
        		</body>
        	</html>
        ";
        }
        ///////////////////////////////////////////////////
        //  ถ้ามี email ผู้รับ
        if ($email_receiver) {
            $mail->msgHTML($email_content);
            if ($mail->send() && $conn->query($sql) === true && move_uploaded_file($_FILES["deposit_img"]["tmp_name"], $target_file)) {
                $token = "kD2hurm9Ehfe3SPEWJ49oP5LZytJ2cV9ZoX4BF9Ga40";
                $str = "\n" . "***มีรายการเช่ารายวัน (ใหม่)***" . "\n" . "ชื่อ : $name_title$firstname $lastname" . "\n" . "เบอร์โทรศัพท์ : $tel" . "\n" . "จำนวนผู้พัก : " . $_SESSION["people"] . " ท่าน" . "\n" . "จำนวนห้องพัก : ห้องแอร์ " . $_SESSION["air"] . " ห้อง | ห้องพัดลม " . $_SESSION["fan"] . " ห้อง" . "\n" . "ยอดรวม : " . number_format($_SESSION["total_price"],2) . " บาท" . "\n" . "วันที่เข้าพัก : " . DateThai($_SESSION["check_in"]) . " ถึง " . DateThai($_SESSION["check_out"]) . "(" . $_SESSION["night"] . " คืน)" . "\n" . "เลขที่การจอง : $code" . "\n" . "***โปรดตรวจสอบหลักฐานชำระเงินค่ามัดจำห้องพัก***";
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
                    echo "alert('จองห้องเรียบร้อยแล้ว กรุณาดูคำสั่งในการจองได้ในอีเมล');";
                    echo "location.href = '../successRent.php?code=$code'";
                    echo "</script>";
                }
            } else {
                echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "<script>";
        echo "alert('ไม่สามารถจองพักได้เนื่องจากห้องพักเต็มแล้ว');";
        echo "location.href = '../checkRoom.php?check_in=" . $_SESSION["check_in"] . "&check_out=" . $_SESSION["check_out"] . "&people=" . $_SESSION["people"] . "';";
        echo "</script>";
    }
}
