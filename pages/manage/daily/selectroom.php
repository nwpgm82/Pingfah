<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    include('../../../components/sidebar.php');
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
    <link rel="stylesheet" href="../../../css/selectroom.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/manage/select_room.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="selectRoom-box">
                <!-- <h3>รายละเอียดการจอง</h3> -->
                <h3>เลขที่การจอง : </strong><?php echo $code; ?></h3>
                <div class="hr"></div>
                <div>
                    <div class="grid-container">
                        <div class="room_select">
                            <p>ห้องที่เลือก</p>
                            <input type="text" value="<?php echo $room_select; ?>" disabled>
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
                            <p>เลขบัตรประชาชน / Passport</p>
                            <input type="text" value="<?php echo $id_card; ?>" disabled>
                        </div>
                        <div class="email">
                            <p>อีเมล</p>
                            <input type="email" value="<?php echo $email; ?>" disabled>
                        </div>
                        <div class="tel">
                            <p>เบอร์โทรศัพท์</p>
                            <input type="tel" value="<?php echo $tel; ?>" disabled>
                        </div>
                        <div class="check_in">
                            <p>เช็คอิน</p>
                            <input type="text" value="<?php echo DateThai($check_in); ?>" disabled>
                        </div>
                        <div class="check_out">
                            <p>เช็คเอาท์</p>
                            <input type="text" value="<?php echo DateThai($check_out); ?>" disabled>
                        </div>
                        <div class="night">
                            <p>จำนวนวันที่พัก (คืน)</p>
                            <input type="text" value="<?php echo $night; ?>" disabled>
                        </div>
                        <div class="people">
                            <p>จำนวน (ท่าน)</p>
                            <input type="number" value="<?php echo $people; ?>" disabled>
                        </div>
                        <div class="air">
                            <p>ห้องแอร์ (ห้อง)</p>
                            <input type="number" value="<?php echo $air_room; ?>" disabled>
                        </div>
                        <div class="fan">
                            <p>ห้องพัดลม (ห้อง)</p>
                            <input type="number" value="<?php echo $fan_room; ?>" disabled>
                        </div>
                        <div class="room_status">
                            <p>สถานะการเข้าพัก</p>
                            <input type="email"
                                value="<?php if(isset($daily_status)){ echo $daily_status; }else{ echo "ยังไม่ได้เข้าพัก"; } ?>"
                                disabled>
                        </div>
                        <div class="total_price">
                            <p>ราคารวม (บาท)</p>
                            <input type="number" value="<?php echo number_format($total_price); ?>" disabled>
                        </div>
                    </div>
                    <form action="function/addSelectRoom.php?daily_id=<?php echo $daily_id; ?>" method="POST" enctype="multipart/form-data">
                        <div id="copy-box" style="padding-top:32px;">
                            <h3>เอกสาร</h3>
                            <div class="hr"></div>
                            <div>
                                <p>สำเนาบัตรประชาชน<label style="color:red;"> *</label></p>
                                <div class="img-box" id="id_box">
                                    <img id="img_id" style="display:none;">
                                    <iframe id="img_id" style="display:none;" width="100%" height="100%"></iframe>
                                </div>
                                <h5 id="idimg_error" style="color:red;"></h5>
                                <input type="file" name="id_img" id="id_img">
                            </div>
                        </div>
                        <div style="padding-top:32px;">
                            <h3>เลือกห้องที่ต้องการเข้าพัก<label style="color:red;"> *</label></h3>
                            <div class="hr"></div>
                            <div id="roomselect_box" style="display:flex;align-items:center;width:100%;">
                                <p><strong>ห้องที่ท่านเลือก :</strong></p>
                                <input id="room_select" name="room_select">
                            </div>
                            <div class="grid-box">
                                <div style="border: 1px solid rgb(131, 120, 47, 0.7);border-radius:4px;padding:16px 0;">
                                    <div style="padding:0 16px;">
                                        <label><strong>ห้องแอร์</strong> (จำนวนที่เลือกได้ : <label id="air_count"><?php echo $air_room; ?></label> ห้อง)</label>
                                    </div>
                                    <div class="hr" style="margin:16px 0"></div>
                                    <div style="padding:0 16px;">
                                    <?php
                                    $get_air = "SELECT room_id FROM roomlist WHERE room_type = 'แอร์' AND room_cat = 'รายวัน' AND room_status = 'ว่าง'";
                                    $result_air = $conn->query($get_air);
                                    if ($result_air->num_rows > 0) {
                                        while($air_data = $result_air->fetch_assoc()) {
                                            echo "<button type='button' class='air' id='".$air_data["room_id"]."'>".$air_data["room_id"]."</button>";
                                        }
                                    } else {
                                        echo "ไม่มีห้องว่างให้เช่า";
                                    }
                                    ?>
                                    </div>
                                </div>
                                <div style="border: 1px solid rgb(131, 120, 47, 0.7);border-radius:4px;padding:16px 0;">
                                    <div style="padding:0 16px;">
                                        <label><strong>ห้องพัดลม</strong> (จำนวนที่เลือกได้ : <label id="fan_count"><?php echo $fan_room; ?></label> ห้อง)</label>
                                    </div>
                                    <div class="hr" style="margin:16px 0"></div>
                                    <div style="padding:0 16px;">
                                    <?php
                                    $get_fan = "SELECT room_id FROM roomlist WHERE room_type = 'พัดลม' AND room_cat = 'รายวัน' AND room_status = 'ว่าง'";
                                    $result_fan = $conn->query($get_fan);
                                    if ($result_fan->num_rows > 0) {
                                        while($fan_data = $result_fan->fetch_assoc()) {
                                            echo "<button type='button' class='fan' id='".$fan_data["room_id"]."'>".$fan_data["room_id"]."</button>";
                                        }
                                    } else {
                                        echo "ไม่มีห้องว่างให้เช่า";
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr"></div>
                        <div style="display:flex;justify-content:center;align-items:center;">
                            <button type="submit" id="confirmRent">ยืนยันการเข้าพัก</button>
                        </div>
                    </form>
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