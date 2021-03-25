<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../connection.php";
    $id = $_REQUEST["member_id"];
    $sql = mysqli_query($conn, "SELECT cost_id FROM cost WHERE member_id = $id ORDER BY cost_id DESC LIMIT 1");
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
                <h1 style="text-align:center;color:rgb(131, 120, 47, 1)">แจ้งออกสำเร็จ</h1>
                <div style="max-width:860px;">
                    <p style="color:rgb(131, 120, 47, 1);text-align:center;"><strong>*** สามารถพิมพ์ใบเสร็จเช่าห้องพักได้ที่ปุ่มข้างล่าง ***</strong></p>
                    <div class="print-box">
                        <div>
                            <p>ใบเสร็จค่าเช่าห้องพัก</p>
                            <a href="../cost/receipt_quit.php?cost_id=<?php echo $result["cost_id"]; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                    </div>
                </div>
                <div style="display:flex;justify-content:center;align-items:center;padding-top:32px;">
                    <a href="index.php"><button>กลับไปหน้ารายการห้องพัก</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
} else {
    header("Location: ../../login.php");
}
?>