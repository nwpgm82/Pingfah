<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Pingfah/css/navbar.css">
    <title>Pingfah</title>
</head>

<body onload="menubar()">
    <div >
        <div class="sidebar">
            <div class="logo_box">
                <img src="/Pingfah/img/logo.png" alt="logo" class="logo">
            </div>
            <ul>
                <a href="/Pingfah/pages/employee/index.php">
                    <li id="main">หน้าหลัก</li>
                </a>
                <li class="dropdown-btn">
                    ข้อมูลหอพัก
                    <div id="arrow-down" class="arrow-down"></div>
                    <div id="arrow-up" class="arrow-up" style="display:none;"></div>
                </li>
                <div id="dropdown" class="dropdown-container">
                    <ul>
                        <a href="/Pingfah/pages/employee/roomDetail/index.php">
                            <li id="roomdetail">- ประเภทห้องพัก</li>
                        </a>
                        <a href="/Pingfah/pages/employee/gallery/index.php">
                            <li id="gallery">- แกลอรี่</li>
                        </a>
                    </ul>
                </div>
                <a href="/Pingfah/pages/employee/roomList/index.php">
                    <li id="roomlist">รายการห้องพัก</li>
                </a>
                <a href="/Pingfah/pages/employee/daily/index.php">
                    <li id="daily">รายการเช่ารายวัน</li>
                </a>
                <li class="dropdown-btn2">
                    รายการชำระเงิน 
                    <div id="arrow-down2" class="arrow-down"></div>
                    <div id="arrow-up2" class="arrow-up" style="display:none;"></div>
                </li>
                <div id="dropdown2" class="dropdown-container">
                    <ul>
                        <a href="/Pingfah/pages/employee/dailyCost/index.php">
                            <li id="dailycost">- รายวัน</li>
                        </a>
                        <a href="/Pingfah/pages/employee/cost/index.php">
                            <li id="cost">- รายเดือน</li>
                        </a>
                        <a href="/Pingfah/pages/employee/repair/repairReport.php">
                            <li id="repair_report">- การซ่อมแซม</li>
                        </a>
                    </ul>
                </div>
                <a href="/Pingfah/pages/employee/repair/index.php">
                    <li id="repair">รายการแจ้งซ่อม</li>
                </a>
                <a href="/Pingfah/pages/employee/package/index.php">
                    <li id="package">รายการพัสดุ</li>
                </a>
                <a href="/Pingfah/pages/employee/rule/index.php">
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
            <a href="/Pingfah/pages/employee/myprofile/index.php">
                <div class="profile">
                    <?php
                    $user = $_SESSION['ID'];
                    $sql = "SELECT * FROM employee WHERE username = '$user'";
                    $profile_result = $conn->query($sql);
                    if ($profile_result->num_rows > 0) {
                        while($profile = $profile_result->fetch_assoc()) {
                    ?>
                    <img src="<?php if(isset($profile['profile_img'])){ echo "/Pingfah/pages/images/employee/".$_SESSION['ID']."/".$profile['profile_img']; }else{ echo "https://sites.google.com/site/rabbiteieicom/_/rsrc/1467891358746/home/image8.jpg"; } ?>"
                        alt="profile_logo" class="profile-logo">  
                    <p class="profile-text">ยินดีต้อนรับ <?php echo $_SESSION['name']; ?></p>
                    <?php }} ?>
                </div>
            </a>
        </div>
    </div>



    <!--- JavaScript Field --->
    <script src="/Pingfah/js/sidebarEPY.js"></script>
</body>

</html>
<?php
// }else{
//     Header("Location: ../login.php");
// }
?>