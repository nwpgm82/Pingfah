<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../connection.php";
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $daily_id = $_REQUEST["daily_id"];
    $sql = mysqli_query($conn, "SELECT * FROM daily WHERE daily_id = $daily_id");
    $result = mysqli_fetch_assoc($sql);
    if ($result["daily_status"] == "เข้าพักแล้ว") {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/confirm_checkout.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <script src="../../../js/manage/confirm-checkout.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="c-box">
                <div class="header">
                    <h3>ข้อมูลผู้เช่า</h3>
                    <h3 id="code_text">เลขที่ในการจอง : </strong><?php echo $result["code"]; ?></h3>
                </div>
                <div class="hr"></div>
                <div class="grid-container">
                    <div class="room_select">
                        <p>ห้องที่เลือก</p>
                        <input type="text" value="<?php echo $result["room_select"]; ?>" disabled>
                    </div>
                    <div class="name_title">
                        <p>คำนำหน้าชื่อ</p>
                        <input type="text" name="name_title" value="<?php echo $result["name_title"]; ?>" disabled>
                    </div>
                    <div class="firstname">
                        <p>ชื่อ</p>
                        <input type="text" name="firstname" value="<?php echo $result["firstname"]; ?>" disabled>
                    </div>
                    <div class="lastname">
                        <p>นามสกุล</p>
                        <input type="text" name="lastname" value="<?php echo $result["lastname"]; ?>" disabled>
                    </div>
                    <div class="id_card">
                        <p>เลขบัตรประชาชน / Passport No.</p>
                        <input type="text" name="id_card" value="<?php echo $result["id_card"]; ?>" disabled>
                    </div>
                    <div class="email">
                        <p>อีเมล</p>
                        <input type="email" name="email" value="<?php echo $result["email"]; ?>" disabled>
                    </div>
                    <div class="tel">
                        <p>เบอร์โทรศัพท์</p>
                        <input type="tel" name="tel" value="<?php echo $result["tel"]; ?>" disabled>
                    </div>
                    <div class="check_in">
                        <p>เช็คอิน</p>
                        <input type="text" class="roundtrip-input" id="check_in" name="check_in"
                            value="<?php echo DateThai($result["check_in"]); ?>" disabled>
                    </div>
                    <div class="check_out">
                        <p>เช็คเอาท์</p>
                        <input type="text" class="roundtrip-input" id="check_out" name="check_out"
                            value="<?php echo DateThai($result["check_out"]); ?>" disabled>
                    </div>
                    <div class="night">
                        <p>จำนวนวันที่พัก (คืน)</p>
                        <input type="text" id="night" name="night" value="<?php echo $result["night"]; ?>" disabled>
                    </div>
                    <div class="people">
                        <p>จำนวน (ท่าน)</p>
                        <input type="number" id="people" name="people" value="<?php echo $result["people"]; ?>" disabled>
                    </div>
                    <div class="air">
                        <p>ห้องแอร์ (ห้อง)</p>
                        <input type="number" id="air" name="air" value="<?php echo $result["air_room"]; ?>" disabled>
                    </div>
                    <div class="fan">
                        <p>ห้องพัดลม (ห้อง)</p>
                        <input type="number" id="fan" name="fan" value="<?php echo $result["fan_room"]; ?>" disabled>
                    </div>
                    <div class="room_status">
                        <p>สถานะการเข้าพัก</p>
                        <input type="text" value="<?php echo $result["daily_status"]; ?>" disabled>
                    </div>
                    <div class="total_price">
                        <p>ราคารวม (บาท)</p>
                        <input type="number" name="total_price" value="<?php echo number_format($result["total_price"]); ?>" disabled>
                    </div>
                </div>
                <!-- <form action="function/checkout.php?daily_id=<?php echo $daily_id; ?>" method="POST"> -->
                    <div style="padding-top:32px;">
                        <h3>ค่าเสียหาย</h3>
                        <div class="hr"></div>
                        <div class="damage-grid">
                            <div class="add-box">
                                <div id="add_form">
                                    <div class="add-form">
                                        <div>
                                            <p>หัวข้อรายการ</p>
                                            <input type="text" id="damage_topic">
                                        </div>
                                        <button type="button" id="addDamage_form">เพิ่ม</button>
                                    </div>
                                    <div class="arrow-down"></div>
                                </div>
                                <button type="button" class="plus-btn" id="add_damage">เพิ่มรายการค่าเสียหาย</button>
                            </div>
                        </div>
                        <!-- <div style="height:82px;">
                            <p>ค่าเสียหาย (บาท)</p>
                            <input type="text" id="damages" name="damages" style="width:200px;">
                            <h5 id="damage_error" style="color:red;"></h5>
                        </div> -->
                    </div>
                    <div class="hr" style="margin:32px 0;"></div>
                    <div style="display:flex;justify-content:center;align-items:center;">
                        <button id="checkout-btn" type="button">เช็คเอาท์</button>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
</body>

</html>
<?php
} else {
        echo "<script>
                alert('เกิดข้อผิดพลาด')
                window.history.back()
              </script>";
    }
} else {
    header("Location: ../../login.php");
}