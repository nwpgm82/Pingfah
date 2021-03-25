<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $sql = "SELECT * FROM employee WHERE email = '".$_SESSION['ID']."' AND employee_status = 'กำลังทำงาน'";
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
    <link rel="stylesheet" href="../../../css/emDetail.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="emDetail-box">
                <form action="function/editEm.php" method='POST' enctype="multipart/form-data">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h3>ข้อมูลพนักงาน</h3>
                        <?php
                        if($_SESSION["level"] == "guest"){
                        ?>
                        <div class="option-grid" id="option-btn" style="display:flex;">
                            <button type="button" class="edit edit-btn" id="edit" title="แก้ไข"></button>
                        </div>
                        <div id="edit-option" style="width:90px;display:none;justify-content:space-between;align-items:center;">
                            <button type="submit" class="correct-btn" id="accept-edit" name="accept-edit" title="ยืนยันการแก้ไข"></button>
                            <button type="button" class="cancel-btn" id="cancel-edit" title="ยกเลิกการแก้ไข"></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="hr"></div>
                    <div class="form-grid">
                        <div>
                            <div class="profile-box" id="profile_box">
                                <img <?php if($profile_img != ""){ echo "src='../../images/employee/$id_card/$profile_img'"; }?> id="img_profile" <?php if($profile_img == ""){ echo "style='display:none;'"; } ?>>
                            </div>
                            <h5 id="profileimg_error" style="color:red;"></h5>
                            <input type="file" name="profile_img" id="profile_img" disabled>
                        </div>
                        <div>
                            <div class="grid-container">
                                <div class="come">
                                    <p>วันที่เริ่มงาน</p>
                                    <input type="text" name="come" id="come_date"
                                        value="<?php echo DateThai($come_date); ?>" disabled>
                                    <h5 id="come_error" style="color:red;"></h5>
                                </div>
                                <div class="out">
                                    <p>วันที่ลาออก</p>
                                    <input type="text" id="out_date" value="<?php if(isset($out_date)){ echo DateThai($out_date); } ?>" disabled>
                                </div>
                                <div class="status">
                                    <p>สถานะการทำงาน</p>
                                    <input type="text" id="employee_status" value="<?php echo $employee_status; ?>" disabled>
                                </div>
                                <div class="title_name">
                                    <p>คำนำหน้าชื่อ</p>
                                    <select name="title_name" id="title_name" disabled>
                                        <option value="นาย" <?php if($title_name == "นาย"){ echo "selected"; }?>>นาย
                                        </option>
                                        <option value="นาง" <?php if($title_name == "นาง"){ echo "selected"; }?>>นาง
                                        </option>
                                        <option value="นางสาว" <?php if($title_name == "นางสาว"){ echo "selected"; }?>>
                                            นางสาว</option>
                                    </select>
                                </div>
                                <div class="firstname">
                                    <p>ชื่อ</p>
                                    <input type="text" name="firstname" id="firstname" placeholder="ชื่อ"
                                        value="<?php echo $firstname; ?>" disabled>
                                    <h5 id="fs_error" style="color:red;"></h5>
                                </div>
                                <div class="lastname">
                                    <p>นามสกุล</p>
                                    <input type="text" name="lastname" id="lastname" placeholder="นามสกุล"
                                        value="<?php echo $lastname; ?>" disabled>
                                    <h5 id="ls_error" style="color:red;"></h5>
                                </div>
                                <div class="nickname">
                                    <p>ชื่อเล่น</p>
                                    <input type="text" name="nickname" id="nickname" placeholder="ชื่อเล่น"
                                        value="<?php echo $nickname; ?>" disabled>
                                    <h5 id="nk_error" style="color:red;"></h5>
                                </div>
                                <div class="id_card">
                                    <p>เลขบัตรประชาชน / Passport No.</p>
                                    <input type="text" name="id_card" id="id_card"
                                        placeholder="เลขบัตรประชาชน / Passport No." maxlength="13"
                                        value="<?php echo $id_card; ?>" disabled>
                                    <h5 id="id_error" style="color:red;"></h5>
                                </div>
                                <div class="birthday">
                                    <p>วัน / เดือน / ปีเกิด</p>
                                    <input type="text" name="birthday" id="birthday" placeholder="วัน / เดือน / ปีเกิด"
                                        value="<?php echo DateThai($birthday); ?>" disabled>
                                    <h5 id="bd_error" style="color:red;"></h5>
                                </div>
                                <div class="age">
                                    <p>อายุ</p>
                                    <input type="number" name="age" id="age"
                                        oninput="this.value = this.value > 60 ? 60 : Math.abs(this.value)"
                                        placeholder="อายุ" value="<?php echo $age; ?>" disabled>
                                    <h5 id="ag_error" style="color:red;"></h5>
                                </div>
                                <div class="phone">
                                    <p>เบอร์โทรศัพท์</p>
                                    <input type="text" name="tel" id="tel" maxlength="10" placeholder="เบอร์โทรศัพท์"
                                        value="<?php echo $tel; ?>" disabled>
                                    <h5 id="tel_error" style="color:red;"></h5>
                                </div>
                                <div class="email">
                                    <p>อีเมล</p>
                                    <input type="email" name="email" id="email" placeholder="อีเมล"
                                        value="<?php echo $email; ?>" disabled>
                                    <h5 id="em_error" style="color:red;"></h5>
                                </div>
                                <div class="race">
                                    <p>เชื้อชาติ</p>
                                    <input type="text" name="race" id="race" placeholder="เชื้อชาติ"
                                        value="<?php echo $race; ?>" disabled>
                                    <h5 id="rc_error" style="color:red;"></h5>
                                </div>
                                <div class="nation">
                                    <p>สัญชาติ</p>
                                    <input type="text" name="nation" id="nation" placeholder="สัญชาติ"
                                        value="<?php echo $nationality; ?>" disabled>
                                    <h5 id="na_error" style="color:red;"></h5>
                                </div>
                                <div class="position">
                                    <p>ตำแหน่ง</p>
                                    <select name="position" id="position" disabled>
                                        <option value="employee"
                                            <?php if($position == "employee"){ echo "selected"; } ?>>พนักงาน</option>
                                        <option value="admin" <?php if($position == "admin"){ echo "selected"; } ?>>
                                            Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div id="address-box" style="padding-top:16px;height:146px;">
                                <p>ที่อยู่</p>
                                <textarea name="address" id="address" placeholder="ที่อยู่"
                                    disabled><?php echo $address; ?></textarea>
                                <h5 id="ad_error" style="color:red;"></h5>
                            </div>
                            <div id="copy-box" style="padding-top:32px;">
                                <h3>สำเนาเอกสาร</h3>
                                <div class="hr"></div>
                                <div class="img-grid">
                                    <div>
                                        <p>สำเนาบัตรประชาชน</p>
                                        <div class="img-box" id="id_box">
                                            <img <?php if($pic_idcard != ""){ echo "src='../../images/employee/$id_card/$pic_idcard'"; } ?> id="img_id" <?php if($pic_idcard == ""){ echo "style='display:none;'"; } ?>>
                                        </div>
                                        <h5 id="idimg_error" style="color:red;"></h5>
                                    </div>
                                    <div>
                                        <p>สำเนาทะเบียนบ้าน</p>
                                        <div class="img-box" id="home_box">
                                            <img <?php if($pic_home != ""){ echo "src='../../images/employee/$id_card/$pic_home'"; } ?> id="img_home" <?php if($pic_home == ""){ echo "style='display:none;'"; } ?>>
                                        </div>
                                        <h5 id="homeimg_error" style="color:red;"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/emDetail2.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>