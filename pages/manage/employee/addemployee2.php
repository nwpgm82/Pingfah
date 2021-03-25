<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    include('../../../components/sidebar.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/addEmployee.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="addEmployee-box">
                <h3>เพิ่มพนักงาน</h3>
                <form action="../employee/function/addEm.php" method='POST'
                    enctype="multipart/form-data">
                    <div class="row" style="padding-top: 32px;">
                        <div class="col-2">
                            <p>อัพโหลดรูปภาพ</p>
                            <div style="padding-top:8px">
                                <div class="profile-box">
                                    <img id="output_imagepic1" class="profile">
                                </div>
                                <input type="file" accept="image/*" name="profile_img"
                                    onchange="preview_image(event,'pic1')" style="padding-top:16px" required>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-2">
                                    <p>คำนำหน้าชื่อ</p>
                                    <select name="title_name">
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <p>ชื่อ</p>
                                    <input type="text" name="firstname" id="" placeholder="ชื่อ" required>
                                </div>
                                <div class="col-4">
                                    <p>นามสกุล</p>
                                    <input type="text" name="lastname" id="" placeholder="นามสกุล" required>
                                </div>
                                <div class="col-2">
                                    <p>ชื่อเล่น</p>
                                    <input type="text" name="nickname" id="" placeholder="ชื่อเล่น" required>
                                </div>
                            </div>
                            <div class="row" style="padding-top: 32px;">
                                <div class="col-3">
                                    <p>เลขบัตรประชาชน</p>
                                    <input type="text" name="id_card" id="" placeholder="เลขบัตรประชาชน" required>
                                </div>
                                <div class="col-3">
                                    <p>เบอรโทรศัพท์</p>
                                    <input type="tel" name="tel" id="" placeholder="เบอร์โทรศัพท์" required>
                                </div>
                                <div class="col-3">
                                    <p>อีเมล์</p>
                                    <input type="email" name="email" id="" placeholder="อีเมล์" required>
                                </div>
                                <div class="col-3">
                                    <p>Line</p>
                                    <input type="text" name="id_line" id="" placeholder="ID Line" required>
                                </div>
                            </div>
                            <div class="row" style="padding-top: 32px;">
                                <div class="col-3">
                                    <p>เกิดวันที่</p>
                                    <div style="position:relative;">
                                        <input id="birthday" name="birthday" type="text" required>
                                        <p id="birth_date" class="dateText"></p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <p>อายุ</p>
                                    <input type="text" name="age" id="" placeholder="อายุ" required>
                                </div>
                                <div class="col-3">
                                    <p>เชื้อชาติ</p>
                                    <input type="text" name="race" id="" placeholder="เชื้อชาติ" required>
                                </div>
                                <div class="col-3">
                                    <p>สัญชาติ</p>
                                    <input type="text" name="nationality" id="" placeholder="สัญชาติ" required>
                                </div>
                            </div>
                            <div style="padding: 32px 8px 0 0;">
                                <p>ที่อยู่</p>
                                <textarea name="address" id="" placeholder="ที่อยู่" required></textarea>
                            </div>
                            <div style="padding-top:32px;">
                                <h3>สำเนาเอกสาร</h3>
                                <div class="row" style="padding-top:16px">
                                    <div class="col-6">
                                        <p>สำเนาบัตรประชาชน</p>
                                        <div class="img-box">
                                            <img id="output_imagepic2">
                                        </div>
                                        <input type="file" accept="image/*" name="pic_idcard"
                                            onchange="preview_image(event,'pic2')" style="padding-top:16px" required>
                                    </div>
                                    <div class="col-6">
                                        <p>สำเนาทะเบียนบ้าน</p>
                                        <div class="img-box">
                                            <img id="output_imagepic3">
                                        </div>
                                        <input type="file" accept="image/*" name="pic_home"
                                            onchange="preview_image(event,'pic3')" style="padding-top:16px" required>
                                    </div>
                                </div>
                            </div>
                            <div style="padding-top:32px;">
                                <h3>เพิ่มบัญชีผู้ใช้และรหัสผ่าน</h3>
                                <div style="padding-top:16px;">
                                    <div class="col-6">
                                        <p>Username</p>
                                        <input type="text" name="username" placeholder="บัญชีผู้ใช้" required>
                                    </div>
                                    <div class="col-6" style="padding-top:16px;">
                                        <p>รหัสผ่าน</p>
                                        <input type="password" name="password" pattern=".{6,20}" placeholder="รหัสผ่าน" required>
                                    </div>
                                    <div class="col-6" style="padding-top:16px;">
                                        <p>ยืนยันรหัสผ่าน</p>
                                        <input type="password" name="confirm_password" pattern=".{6,20}" placeholder="ยืนยันรหัสผ่าน" required>
                                    </div>
                                </div>
                            </div>
                            <div style="padding-top:32px;display:flex;justify-content:center;">
                                <button name="addEm">ยืนยัน</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/addEmployee.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>