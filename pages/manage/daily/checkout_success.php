<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../connection.php";
    $id = $_REQUEST["daily_id"];
    $sql = mysqli_query($conn, "SELECT code FROM daily WHERE daily_id = $id");
    $result = mysqli_fetch_assoc($sql);
    $sql2 = mysqli_query($conn,"SELECT damages FROM dailycost WHERE dailycost_id = $id");
    $result2 = mysqli_fetch_assoc($sql2);
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
                <h1 style="text-align:center;color:rgb(131, 120, 47, 1)">เช็คเอาท์สำเร็จ</h1>
                <h2 style="text-align:center;color:rgb(131, 120, 47, 1)">เลขที่การจอง : <?php echo $result["code"]; ?></h2>
                <div style="max-width:860px;">
                    <p style="color:rgb(131, 120, 47, 1);text-align:center;"><strong>*** สามารถพิมพ์ใบเสร็จคืนค่ามัดจำคีย์การ์ด และค่าเสียหายได้ที่ปุ่มข้างล่าง ***</strong></p>
                    <div class="print-box">
                        <div>
                            <p>คืนค่ามัดจำคีย์การ์ด</p>
                            <a href="../dailyCost/receipt_deposit_back.php?code=<?php echo $result["code"]; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                        <?php
                        if($result2["damages"] != 0){
                        ?>
                        <div>
                            <p>ค่าเสียหาย</p>
                            <a href="../dailyCost/receipt_damages.php?code=<?php echo $result["code"]; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                        <?php } ?>
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