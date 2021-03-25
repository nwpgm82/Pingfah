<?php
include "connection.php";
$token = $_REQUEST["key"];
$sql = "SELECT * FROM reset_password_temp WHERE token = '$token'";
$result = mysqli_query($conn, $sql) or die("Error in query: $sql " . mysqli_error());
$row = mysqli_fetch_array($result);
if ($row != null) {
    extract($row);
    $exp = date_create($expDate);
    $current = date("Y-m-d H:i:s");
    if ($current < $exp) {
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/resetPass.css">
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="resetPass">
        <a href="../index.php"><img src="../img/main_logo.png" alt=""></a>
        <form method="POST">
            <h3>รีเซ็ตรหัสผ่าน</h3>
            <div>
                <p>รหัสผ่านใหม่ / New password</p>
                <input type="password" name="pass">
            </div>
            <div>
                <p>ยืนยันรหัสผ่าน / Confirm password</p>
                <input type="password" name="confirm-pass">
            </div>
            <div style="padding-top:32px;display:flex;justify-content:flex-end;align-items:flex-end;">
                <button type="submit" name="confirm-reset">ยืนยัน</button>
            </div>
        </form>
    </div>
</body>

</html>
<?php
    } else {
        echo "<script>alert('ลิ้งค์นี้ได้หมดอายุแล้ว');window.close()</script>";
    }
} else {
    echo "<script>alert('ไม่มีลิ้งค์');window.close()</script>";
}
?>
<?php
if (isset($_POST["confirm-reset"])) {
    if ($_POST["pass"] == $_POST["confirm-pass"]) {
        $resetPass = "UPDATE login SET password = md5('" . $_POST["pass"] . "') WHERE email = '$email'";
        $delTemp = "DELETE FROM reset_password_temp WHERE token = '$token'";
        if ($conn->query($resetPass) === true && $conn->query($delTemp) === true) {
            echo "<script>";
            echo "alert('รีเซ็ตรหัสผ่านเรียบร้อยแล้ว');";
            echo "location.href = 'login.php';";
            echo "</script>";
        } else {
            echo "Error: " . $resetPass . "<br>" . $conn->error;
            echo "Error: " . $delTemp . "<br>" . $conn->error;
        }
    } else {
        echo "<script>";
        echo "alert('รหัสผ่านไม่ตรงกัน');";
        echo "</script>";
    }
}
?>