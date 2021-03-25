<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../connection.php");
    $room_id = $_REQUEST["ID"];
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $sql = "SELECT room_id, come_date, out_date, name_title, firstname, lastname, nickname, id_card, phone, email, pic_idcard FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'";
    $result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
    $row = mysqli_fetch_array($result);
    if($row != null){
    extract($row);
    $calculate = strtotime($out_date) - strtotime($come_date);
    $night = floor($calculate / 86400);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/roomform2.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="roomform-box">
                <h3>ห้อง <?php echo $room_id; ?></h3>
                <div class="hr"></div>
                <?php
                if($row != null){
                ?>
                <div class="grid-container">
                    <div class="check_in">
                        <p>เช็คอิน</p>
                        <input type="text" value="<?php echo DateThai($come_date); ?>" disabled>
                    </div>
                    <div class="check_out">
                        <p>เช็คเอ้าท์</p>
                        <input type="text" value="<?php echo DateThai($out_date); ?>" disabled>
                    </div>
                    <div class="night">
                        <p>จำนวนคืนที่พัก (คืน)</p>
                        <input type="text" value="<?php echo $night; ?>" disabled>
                    </div>
                    <div class="name_title">
                        <p>คำนำหน้าชื่อ</p>
                        <input type="text" value="<?php echo $name_title; ?>" disabled>
                    </div>
                    <div class="firstname">
                        <p>ชื่อ</p>
                        <input type="text" value="<?php echo $firstname; ?>" disabled>
                    </div>
                    <div class="lastname">
                        <p>นามสกุล</p>
                        <input type="text" value="<?php echo $lastname; ?>" disabled>
                    </div>
                    <div class="id_card">
                        <p>เลขบัตรประชาชน / Passport No.</p>
                        <input type="text" value="<?php echo $id_card; ?>" disabled>
                    </div>
                    <div class="email">
                        <p>อีเมล</p>
                        <input type="text" value="<?php echo $email; ?>" disabled>
                    </div>
                    <div class="tel">
                        <p>เบอร์โทรศัพท์</p>
                        <input type="text" value="<?php echo $phone; ?>" disabled>
                    </div>
                </div>
                <div style="padding-top:32px;">
                    <h3>สำเนาเอกสาร</h3>
                    <div class="hr"></div>
                    <div style="padding-top:32px;">
                        <p>สำเนาบัตรประชาชน</p>
                        <div class="img-box">
                            <?php
                            if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_idcard")["extension"] != "pdf"){
                            ?>
                            <img src="../../images/roommember/<?php echo $room_id; ?>/<?php echo $come_date; ?>/<?php echo $pic_idcard;?>">
                            <?php
                            }else{
                            ?>
                            <iframe src="../../images/roommember/<?php echo $room_id; ?>/<?php echo $come_date; ?>/<?php echo $pic_idcard;?>" alt="" width="100%" height="100%"></iframe>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
                }else{
                    echo "<div class='new_customer'><h3>ยังไม่มีผู้พักเข้ามาพัก</h3></div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php
}else{
    Header("Location : ../../login.php");
}