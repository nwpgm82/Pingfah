<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../connection.php";
    $id = $_REQUEST["daily_id"];
    $sql = mysqli_query($conn, "SELECT code, room_select FROM daily WHERE daily_id = $id");
    $result = mysqli_fetch_assoc($sql);
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/successRent.css">
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="box">
        <div class="success-box">
            <div style="line-height:60px;">
                <h1 style="text-align:center;color:rgb(131, 120, 47, 1)">การเข้าพักสำเร็จ</h1>
                <h2 style="text-align:center;color:rgb(131, 120, 47, 1)">เลขที่การจอง : <?php echo $result["code"]; ?></h2>
                <h3 style="text-align:center;color:rgb(131, 120, 47, 1)">ห้องที่ท่านเลือก : <?php echo $result["room_select"]; ?></h3>
                <div style="max-width:860px;">
                    <p style="color:rgb(131, 120, 47, 1);text-align:center;"><strong>*** สามารถพิมพ์ใบเสร็จค่าเช่าห้องพัก และค่ามัดจำคีย์การ์ดได้ที่ปุ่มข้างล่าง ***</strong></p>
                    <div class="print-box">
                        <div>
                            <p>ค่าเช่าห้องพัก</p>
                            <a href="receipt_price.php?code=<?php echo $result["code"]; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                        <div>
                            <p>ค่ามัดจำคีย์การ์ด</p>
                            <a href="receipt_deposit.php?code=<?php echo $result["code"]; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                    </div>
                </div>
                <div style="display:flex;justify-content:center;align-items:center;padding-top:32px;">
                    <button id="close-window">ปิดหน้านี้</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    document.getElementById("close-window").addEventListener("click", function() {
        window.close();
    });
    </script>
</body>

</html>
<?php
} else {
    header("Location: ../../login.php");
}
?>