<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Pingfah/css/hometopbar.css">
    <title>Document</title>
</head>

<body>
    <div class="topbar" id="topbar" onscroll="scrollWin()">
        <div class="sub-topbar">
            <img src="/Pingfah/img/logo.png" alt="" class="logo">
            <div onclick="burgerShow()">
                <div class="burger"></div>
                <div class="burger"></div>
                <div class="burger"></div>
            </div>
        </div>
        <ul id="ulShow">
            <a href="#main">
                <li>เริ่มต้น</li>
            </a>
            <a href="#main2">
                <li>รายละเอียด</li>
            </a>
            <a href="#services">
                <li>บริการ</li>
            </a>
            <a href="#gallery">
                <li>แกลลอรี่</li>
            </a>
            <a href="#contact">
                <li>ติดต่อเรา</li>
            </a>
            <a href="/Pingfah/pages/login.php" class="key">
                <img src="/Pingfah/img/tool/login-btn.png" alt="">
            </a>
        </ul>

    </div>
</body>
<script src="/Pingfah/js/hometopbar.js"></script>

</html>