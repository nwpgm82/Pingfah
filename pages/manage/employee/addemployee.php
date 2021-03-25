<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../connection.php");
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/addEmployee.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/addEmployee.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="addEmployee-box">
                <h3>ข้อมูลพนักงาน</h3>
                <div class="hr"></div>
                <form action="function/addEm.php" method="POST" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div style="position:relative">
                            <div class="profile-box" id="profile_box">
                                <img id="img_profile" style="display:none;">
                            </div>
                            <h5 id="profileimg_error" style="color:red;"></h5>
                            <label style="color:red;text-align:right;position:absolute;right:0px;top:160px;">*</label>
                            <input type="file" name="profile_img" id="profile_img">
                        </div>
                        <div>
                            <div class="grid-container">
                                <div class="come">
                                    <p>วันที่เริ่มงาน<label style="color:red;"> *</label></p>
                                    <input type="text" name="come" id="come_date" value="<?php echo DateThai(date("Y-m-d")); ?>">
                                    <h5 id="come_error" style="color:red;"></h5>
                                </div>
                                <div class="title_name">
                                    <p>คำนำหน้าชื่อ<label style="color:red;"> *</label></p>
                                    <select name="title_name" id="title_name">
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="firstname">
                                    <p>ชื่อ<label style="color:red;"> *</label></p>
                                    <input type="text" name="firstname" id="firstname" placeholder="ชื่อ">
                                    <h5 id="fs_error" style="color:red;"></h5>
                                </div>
                                <div class="lastname">
                                    <p>นามสกุล<label style="color:red;"> *</label></p>
                                    <input type="text" name="lastname" id="lastname" placeholder="นามสกุล">
                                    <h5 id="ls_error" style="color:red;"></h5>
                                </div>
                                <div class="nickname">
                                    <p>ชื่อเล่น<label style="color:red;"> *</label></p>
                                    <input type="text" name="nickname" id="nickname" placeholder="ชื่อเล่น">
                                    <h5 id="nk_error" style="color:red;"></h5>
                                </div>
                                <div class="id_card">
                                    <p>เลขบัตรประชาชน / Passport No.<label style="color:red;"> *</label></p>
                                    <input type="text" name="id_card" id="id_card"
                                        placeholder="เลขบัตรประชาชน / Passport No." maxlength="13">
                                    <h5 id="id_error" style="color:red;"></h5>
                                </div>
                                <div class="birthday">
                                    <p>วัน / เดือน / ปีเกิด<label style="color:red;"> *</label></p>
                                    <input type="text" name="birthday" id="birthday" placeholder="วัน / เดือน / ปีเกิด">
                                    <h5 id="bd_error" style="color:red;"></h5>
                                </div>
                                <div class="age">
                                    <p>อายุ<label style="color:red;"> *</label></p>
                                    <input type="number" name="age" id="age"
                                        oninput="this.value = this.value > 60 ? 60 : Math.abs(this.value)"
                                        placeholder="อายุ" readonly>
                                    <h5 id="ag_error" style="color:red;"></h5>
                                </div>
                                <div class="phone">
                                    <p>เบอร์โทรศัพท์<label style="color:red;"> *</label></p>
                                    <input type="text" name="tel" id="tel" maxlength="10" placeholder="เบอร์โทรศัพท์">
                                    <h5 id="tel_error" style="color:red;"></h5>
                                </div>
                                <div class="email">
                                    <p>อีเมล<label style="color:red;"> *</label></p>
                                    <input type="email" name="email" id="email" placeholder="อีเมล">
                                    <h5 id="em_error" style="color:red;"></h5>
                                </div>
                                <div class="race">
                                    <p>เชื้อชาติ<label style="color:red;"> *</label></p>
                                    <input type="text" name="race" id="race" value="ไทย" placeholder="เชื้อชาติ">
                                    <h5 id="rc_error" style="color:red;"></h5>
                                </div>
                                <div class="nation">
                                    <p>สัญชาติ<label style="color:red;"> *</label></p>
                                    <input type="text" name="nation" id="nation" value="ไทย" placeholder="สัญชาติ">
                                    <h5 id="na_error" style="color:red;"></h5>
                                </div>
                                <div class="position">
                                    <p>ตำแหน่ง<label style="color:red;"> *</label></p>
                                    <select name="position" id="position">
                                        <option value="employee">พนักงาน</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div id="address-box" style="padding-top:16px;height:146px;">
                                <p>ที่อยู่<label style="color:red;"> *</label></p>
                                <textarea name="address" id="address" placeholder="ที่อยู่"></textarea>
                                <h5 id="ad_error" style="color:red;"></h5>
                            </div>
                            <div id="copy-box" style="padding-top:32px;">
                                <h3>สำเนาเอกสาร</h3>
                                <div class="hr"></div>
                                <div class="img-grid">
                                    <div>
                                        <p>สำเนาบัตรประชาชน<label style="color:red;"> *</label></p>
                                        <div class="img-box" id="id_box">
                                            <img id="img_id" style="display:none;">
                                            <iframe id="img_id" style="display:none;" width="100%" height="100%"></iframe>
                                        </div>
                                        <h5 id="idimg_error" style="color:red;"></h5>
                                        <input type="file" name="id_img" id="id_img">
                                    </div>
                                    <div>
                                        <p>สำเนาทะเบียนบ้าน<label style="color:red;"> *</label></p>
                                        <div class="img-box" id="home_box">
                                            <img id="img_home" style="display:none;">
                                            <iframe id="img_home" style="display:none;" width="100%" height="100%"></iframe>
                                        </div>
                                        <h5 id="homeimg_error" style="color:red;"></h5>
                                        <input type="file" name="home_img" id="home_img">
                                    </div>
                                </div>
                            </div>
                            <div class="hr"></div>
                            <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                                <button type="submit" name="addData-btn" id="addData-btn">เพิ่มพนักงาน</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php");
}