<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    $daily_id = $_REQUEST['daily_id'];
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $sql = "SELECT * FROM daily WHERE daily_id = $daily_id";
    $result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
    $row = mysqli_fetch_array($result);
    if($row != null){
        extract($row);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/dailyDetail.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/dailyDetail.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="dailyDetail-box">
                <!-- <h3>รายละเอียดการจอง</h3> -->
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <h3>เลขที่การจอง : </strong><?php echo $code; ?></h3>
                    <!-- <button type="button" class="edit-btn" id="edit"></button>
                        <div id="edit-option" style="width:90px;display:none;justify-content:space-between;align-items:center;">
                            <button type="submit" class="correct-btn" id="accept-edit" name="accept-edit"
                                title="ยืนยันการแก้ไข"></button>
                            <button type="button" class="cancel-btn" id="cancel-edit" title="ยกเลิกการแก้ไข"></button>
                        </div> -->
                </div>
                <div class="hr"></div>
                <div>
                    <div class="grid-container">
                        <div class="room_select">
                            <p>ห้องที่เลือก</p>
                            <input type="text" value="<?php echo $room_select; ?>" disabled>
                        </div>
                        <div class="name_title">
                            <p>คำนำหน้าชื่อ</p>
                            <input type="text" name="name_title" value="<?php echo $name_title; ?>" disabled>
                        </div>
                        <div class="firstname">
                            <p>ชื่อ</p>
                            <input type="text" name="firstname" value="<?php echo $firstname; ?>" disabled>
                        </div>
                        <div class="lastname">
                            <p>นามสกุล</p>
                            <input type="text" name="lastname" value="<?php echo $lastname; ?>" disabled>
                        </div>
                        <div class="id_card">
                            <p>เลขบัตรประชาชน / Passport No.</p>
                            <input type="text" name="id_card" value="<?php echo $id_card; ?>" disabled>
                        </div>
                        <div class="email">
                            <p>อีเมล</p>
                            <input type="email" name="email" value="<?php echo $email; ?>" disabled>
                        </div>
                        <div class="tel">
                            <p>เบอร์โทรศัพท์</p>
                            <input type="tel" name="tel" value="<?php echo $tel; ?>" disabled>
                        </div>
                        <div class="check_in">
                            <p>เช็คอิน</p>
                            <input type="text" class="roundtrip-input" id="check_in" name="check_in"
                                value="<?php echo DateThai($check_in); ?>" disabled>
                        </div>
                        <div class="check_out">
                            <p>เช็คเอาท์</p>
                            <input type="text" class="roundtrip-input" id="check_out" name="check_out"
                                value="<?php echo DateThai($check_out); ?>" disabled>
                        </div>
                        <div class="night">
                            <p>จำนวนวันที่พัก (คืน)</p>
                            <input type="text" id="night" name="night" value="<?php echo $night; ?>" disabled>
                        </div>
                        <div class="people">
                            <p>จำนวน (ท่าน)</p>
                            <input type="text" id="people" name="people" value="<?php echo $people; ?>" disabled>
                        </div>
                        <div class="air">
                            <p>ห้องแอร์ (ห้อง)</p>
                            <input type="text" id="air" name="air" value="<?php echo $air_room; ?>" disabled>
                        </div>
                        <div class="fan">
                            <p>ห้องพัดลม (ห้อง)</p>
                            <input type="text" id="fan" name="fan" value="<?php echo $fan_room; ?>" disabled>
                        </div>
                        <div class="total_price">
                            <p>จำนวนเงินรวมทั้งสิ้น (บาท)</p>
                            <input type="text" name="total_price" value="<?php echo number_format($total_price); ?>" disabled>
                            <a href="receipt_price.php?code=<?php echo $code; ?>" target="_blank"><button class="print"></button></a>
                        </div>
                        <div class="deposit">
                            <p>ค่ามัดจำคีย์การ์ด (บาท)</p>
                            <input type="text" name="total_price" value="<?php echo number_format($payment_price); ?>" disabled>
                            <?php
                            if($daily_status != "รอการยืนยัน" && $daily_status != "รอการเข้าพัก"){
                            ?>
                            <a href="receipt_deposit.php?code=<?php echo $code; ?>" target="_blank"><button class="print"></button></a>
                            <?php
                            }else{
                            ?>
                            <button class="un-print"></button>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="room_status">
                            <p>สถานะการเข้าพัก</p>
                            <input type="text" value="<?php echo $daily_status; ?>" disabled>
                        </div>
                    </div>
                    <div id="copy-box" style="padding-top:32px;">
                        <h3>หลักฐานการชำระเงินค่าเช่าห้องพัก</h3>
                        <div class="hr"></div>
                        <div class="img-box">
                            <?php
                            if(isset($payment_img)){
                                if(pathinfo("../../images/daily/$code/deposit/$payment_img")["extension"] != "pdf"){
                            ?>
                            <img src="../../images/daily/<?php echo $code; ?>/deposit/<?php echo $payment_img; ?>" alt="">
                            <?php
                                }else{
                            ?>
                            <iframe src="../../images/daily/<?php echo $code; ?>/deposit/<?php echo $payment_img; ?>" alt="" width="100%" height="100%"></iframe>
                            <?php
                                }
                                if($daily_status == "รอการยืนยัน"){
                            ?>
                            <button type="button" class="del-btn" onclick="delImg(<?php echo $daily_id; ?>,'<?php echo $payment_img; ?>')"></button>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
}else{
    Header("Location: ../../login.php"); 
}

?>