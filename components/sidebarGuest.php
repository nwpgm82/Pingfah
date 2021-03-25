<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Pingfah/css/navbar.css">
    <title>Pingfah</title>
</head>

<body onload="menubar()">
    <div>
        <div class="sidebar">
            <div class="logo_box">
                <img src="/Pingfah/img/logo.png" alt="logo" class="logo">
            </div>
            <ul>
                <a href="/Pingfah/pages/guest/index.php">
                    <li id="main">หน้าหลัก</li>
                </a>
                <a href="/Pingfah/pages/guest/cost/index.php">
                    <li id="cost">ตรวจสอบค่าใช้จ่าย</li>
                </a>
                <a href="/Pingfah/pages/guest/repair/index.php">
                    <li id="repair">รายการแจ้งซ่อม</li>
                </a>
                <a href="/Pingfah/pages/guest/package/index.php">
                    <li id="package">รายการพัสดุ</li>
                </a>
                <a href="/Pingfah/pages/guest/appeal/index.php">
                    <li id="appeal">รายการร้องเรียน</li>
                </a>
                <a href="/Pingfah/pages/guest/rule/index.php">
                    <li id="rule">กฎระเบียบหอพัก</li>
                </a>
                <a href="/Pingfah/pages/logout.php">
                    <li class="logout">ออกจากระบบ</li>
                </a>
            </ul>
        </div>
        <div class="topbar">
            <div style="padding:16px 24px">
                <h3 id="topbar-page"></h3>
            </div>
            <a href="/Pingfah/pages/guest/myprofile/index.php">
                <div class="profile">
                    <img src="https://sites.google.com/site/rabbiteieicom/_/rsrc/1467891358746/home/image8.jpg"
                        alt="profile_logo" class="profile-logo">
                    <p class="profile-text">ยินดีต้อนรับ <?php echo $_SESSION['ID']; ?></p>
                </div>
            </a>
        </div>
    </div>



    <!--- JavaScript Field --->
    <script src="/Pingfah/js/sidebarGuest.js"></script>
</body>

</html>
<?php
// }else{
//     Header("Location: ../login.php");
// }
?>