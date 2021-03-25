<?php
 include("../connection.php");
 $id = $_REQUEST["daily_id"];
 $sql = "UPDATE daily SET daily_status = 'ยกเลิกการจอง' WHERE daily_id = $id";
 $sql2 = "SELECT * FROM daily WHERE daily_id = $id";
 $result = mysqli_query($conn, $sql2)or die ("Error in query: $sql2 " . mysqli_error());
 $row = mysqli_fetch_array($result);
 if($row != null){
 extract($row);
 }
 $payment_datebeforeEmail = DateThai($payment_datebefore);  
 ///////////////////// อีเมล ////////////////////////
 require($_SERVER['DOCUMENT_ROOT']."/Pingfah/phpmailer/PHPMailerAutoload.php");
 header('Content-Type: text/html; charset=utf-8');

 $mail = new PHPMailer;
 $mail->CharSet = "utf-8";
 $mail->isSMTP();
 $mail->Host = 'smtp.gmail.com';
 $mail->Port = 587;
 $mail->SMTPSecure = 'tls';
 $mail->SMTPAuth = true;


 $gmail_username = "pingfah.apartment@gmail.com"; // gmail ที่ใช้ส่ง
 $gmail_password = "Cresta82"; // รหัสผ่าน gmail
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
             <div style='background-color: #f6f4ec;width:900px;margin:0 auto;padding:16px;'>
                 <p><strong>คุณได้ยกเลิกการจองห้องพักแล้ว</strong></p>
             </div>
             <div style='background-color: #edeadb;width:900px;height:60px;margin:0 auto;padding:16px;display:flex;align-items:center;'>
                 <p style='color:#000'><strong>ติดต่อสอบถาม :</strong> 098-9132002 (เจ้าของหอพัก)</p>
             </div>
         </body>
     </html>
 ";
 ///////////////////////////////////////////////////
 //  ถ้ามี email ผู้รับ
 if($email_receiver){
     $mail->msgHTML($email_content);
     if ($mail->send() && $conn->query($sql)) {
         // echo "<script>";
         // echo "alert('จองห้องเรียบร้อยแล้ว กรุณาดูคำสั่งในการจองได้ในอีเมล์');";
         // echo "location.href = '../dailyDetail.php?daily_id=$id'";
         // echo "</script>";
     } else {
         echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
     }
 }
?>