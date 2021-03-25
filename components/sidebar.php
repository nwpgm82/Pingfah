<div>
    <div class="sidebar">
        <div class="logo_box">
            <img src="/Pingfah/img/logo.png" alt="logo" class="logo">
        </div>
        <ul>
            <a href="/Pingfah/pages/manage/index.php">
                <li id="main">หน้าหลัก</li>
            </a>
            <?php
            if($_SESSION["level"] == "admin"){
            ?>
            <a href="/Pingfah/pages/manage/employee/index.php">
                <li id="employee">จัดการพนักงาน</li>
            </a>
            <?php } ?>
            <?php
            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            ?>
            <li class="dropdown-btn">
                ข้อมูลหอพัก
                <div id="arrow-down" class="arrow-down"></div>
                <div id="arrow-up" class="arrow-up" style="display:none;"></div>
            </li>
            <div id="dropdown" class="dropdown-container">
                <ul>
                    <a href="/Pingfah/pages/manage/roomDetail/index.php">
                        <li id="roomdetail">- ประเภทห้องพัก</li>
                    </a>
                    <a href="/Pingfah/pages/manage/gallery/index.php">
                        <li id="gallery">- แกลเลอรี่</li>
                    </a>
                </ul>
            </div>
            <a href="/Pingfah/pages/manage/roomList/index.php">
                <li id="roomlist">รายการห้องพัก</li>
            </a>
            <a href="/Pingfah/pages/manage/daily/index.php">
                <li id="daily">รายการเช่ารายวัน</li>
            </a>
            <?php } ?>
            <li class="dropdown-btn2">
                รายการชำระเงิน
                <div id="arrow-down2" class="arrow-down"></div>
                <div id="arrow-up2" class="arrow-up" style="display:none;"></div>
            </li>
            <div id="dropdown2" class="dropdown-container">
                <ul>
                    <?php
                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                    ?>
                    <a href="/Pingfah/pages/manage/dailyCost/index.php">
                        <li id="dailycost">- รายวัน</li>
                    </a>
                    <?php } ?>
                    <a href="/Pingfah/pages/manage/cost/index.php?status=unsuccess">
                        <li id="cost">- รายเดือน</li>
                    </a>
                    <a href="/Pingfah/pages/manage/repair/repairReport.php">
                        <li id="repair_report">- การซ่อมแซม</li>
                    </a>
                </ul>
            </div>
            <a href="/Pingfah/pages/manage/repair/index.php">
                <li id="repair">รายการแจ้งซ่อม</li>
            </a>
            <a href="/Pingfah/pages/manage/package/index.php">
                <li id="package">รายการพัสดุ</li>
            </a>
            <?php
            if($_SESSION["level"] == "admin"){
            ?>
            <a href="/Pingfah/pages/manage/appeal/index.php">
                <li id="appeal">รายการร้องเรียน</li>
            </a>
            <?php } ?>
            <?php
            if($_SESSION["level"] == "guest"){
            ?>
            <a href="/Pingfah/pages/manage/appeal/addAppeal.php">
                <li id="appeal">แจ้งร้องเรียน</li>
            </a>
            <?php } ?>
            <a href="/Pingfah/pages/manage/rule/index.php">
                <li id="rule">กฎระเบียบหอพัก</li>
            </a>
            <?php
            if($_SESSION["level"] == "admin"){
            ?>
            <a href="/Pingfah/pages/manage/logs.php">
                <li id="logs">ประวัติการใช้งาน</li>
            </a>
            <?php } ?>
            <a href="/Pingfah/pages/logout.php">
                <li class="logout">ออกจากระบบ</li>
            </a>
        </ul>
    </div>
    <div class="topbar">
        <div id="topbar-box" style="padding:16px 24px">
            <div class="topbar-flex">
                <div class="burger">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <h3 id="topbar-page"></h3>
            </div>
        </div>
        <a <?php if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){ echo "href='/Pingfah/pages/manage/myprofile/index.php'"; }else{ echo "href='/Pingfah/pages/manage/roomList/room_id.php?ID=".$_SESSION["name"]."'"; } ?>>
            <div class="profile">
                <?php
                $user_email = $_SESSION["ID"];
                $searchUser_img = mysqli_query($conn, "SELECT id_card, profile_img FROM employee WHERE email = '$user_email' AND employee_status = 'กำลังทำงาน'");
                $searchUser_result = mysqli_fetch_assoc($searchUser_img);
                ?>
                <img src="<?php if($searchUser_result != null && $searchUser_result["profile_img"] != "" && $searchUser_result["profile_img"] != null){ echo "/Pingfah/pages/images/employee/".$searchUser_result["id_card"]."/".$searchUser_result["profile_img"]; }else{ echo "https://sites.google.com/site/rabbiteieicom/_/rsrc/1467891358746/home/image8.jpg"; }?>"
                    alt="profile_logo" class="profile-logo">
                <p class="profile-text">ยินดีต้อนรับ <?php echo $_SESSION['name']; ?></p>
            </div>
        </a>
    </div>
    <div class="bg-close"></div>
</div>