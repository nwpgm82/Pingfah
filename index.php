<?php
include('pages/connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/my-style.css">
    <link rel="stylesheet" href="css/mainTop.css">
    <link rel="stylesheet" href="css/mainFoot.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="js/datedropper.pro.min.js"></script>
    <script src="js/mainTop.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <div id="l">
       <div id="loader" class="center"></div> 
    </div>
    <?php include('components/maintopbar.php') ?>
    <div style="position:relative;">
        <div class="banner">
            <img src="img/tool/GOPR1556.jpg" alt="" id="banner">
        </div>
        <div class="reservation" id="re">
            <div>
                <p class="topic">RESERVATION</p>
                <div class="re-flex">
                    <div style="display:flex;align-items:center;" id="from">
                        <label style="font-family: 'Playfair Display', serif;">Check In : </label>
                        <div style="position:relative;padding-left:8px;height:40px;">
                            <input id="check_in" class="roundtrip-input" type="text">
                            <p id="check_in_date" class="dateText"></p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;" id="to">
                        <label style="font-family: 'Playfair Display', serif;">Check Out : </label>
                        <div style="position:relative;padding-left:8px;height:40px;">
                            <input id="check_out" class="roundtrip-input" type="text">
                            <p id="check_out_date" class="dateText"></p>
                        </div>
                    </div>
                    <div style="min-width:52px;display:flex;align-items:center;" id="night">
                        <p id="summary">(1 คืน)</p>
                    </div>
                    <div style="display:flex;align-items:center;" id="p">
                        <label>จำนวนผู้พัก : </label>
                        <div style="position:relative;padding:0 8px;height:40px;">
                            <input id="people" type="text" value="1" maxlength="2">
                        </div>
                        <label>ท่าน</label>
                    </div>
                    <button type="button" id="checkRoom">CHECK AVAILABILITY</button>
                </div>
            </div>
        </div>
        <!-- <div class="reservation" id="re">
            <div class="card">
                <strong><label class="topic">RESERVATION</label></strong>
                <div style="padding-left:16px">
                    <div style="display:flex;align-items:center;">
                        <label style="font-family: 'Playfair Display', serif;">Check In : </label>
                        <div style="position:relative;padding-left:8px;height:40px;">
                            <input id="check_in" class="roundtrip-input" type="text">
                            <p id="check_in_date" class="dateText"></p>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;">
                    <label style="font-family: 'Playfair Display', serif;">Check Out : </label>
                    <div style="position:relative;padding-left:8px;height:40px;">
                        <input id="check_out" class="roundtrip-input" type="text">
                        <p id="check_out_date" class="dateText"></p>
                    </div>
                </div>
                <div style="min-width:52px;display:flex;align-items:center;">
                    <p id="summary">(1 คืน)</p>
                </div>
                <div style="display:flex;align-items:center;">
                    <label>จำนวนผู้พัก : </label>
                    <div style="position:relative;padding:0 8px;height:40px;">
                        <input id="people" type="text" value="1" maxlength="2">
                    </div>
                    <label>ท่าน</label>
                </div>
                <button type="button" id="checkRoom">CHECK AVAILABILITY</button>
            </div>
        </div> -->
    </div>
    <div class="about" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <div class="about-detail">
            <img src="img/tool/sub1.jpg" alt="">
            <div style="padding:24px 32px;">
                <h2>WELCOME TO PINGFAH</h2>
                <div style="padding-top:32px;">
                    <p>หอพักบ้านพิงฟ้าเป็นหอพักรายเดือนที่มีความสะอาดราคาย่อมเยา
                        บรรยากาศแวดล้อมไปด้วยความเงียบสงบเย็นสบาย และมีทิวทัศน์ที่สวยงาม ตั้งอยู่ห่างจากโรงพยาบาลกรุงเทพ
                        ประมาณ 2.9 กม. และอยู่ห่างจากเซ็นทรัลเฟสติวัล เชียงใหม่ ประมาณ 4.5 กม.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="hr"></div>
    <div class="room-box">
        <div class="room-detail" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            <h2>TYPE OF ROOM</h2>
            <div class="room-grid">
                <?php
                $roomData = "SELECT * FROM roomdetail";
                $result = $conn->query($roomData);
                    while($row = $result->fetch_assoc()) {
                ?>
                <div class="detail-grid">
                    <?php
                    if($row['type'] == "พัดลม"){
                        $type_show = "fan";
                        $getImg = "SELECT gal_name from fan_gal LIMIT 1";
                    }else if($row['type'] == "แอร์"){
                        $type_show = "air";
                        $getImg = "SELECT gal_name from air_gal LIMIT 1";
                    }
                    $resultImg = $conn->query($getImg);
                        while($row2 = $resultImg->fetch_assoc()) {
                    ?>
                    <img src="<?php if(isset($row2['gal_name'])){ echo "pages/images/roomdetail/$type_show/" .$row2['gal_name'];}else{ echo 'img/tool/no-img.png'; } ?>"
                        alt="">
                    <?php } ?>
                    <div class="detail">
                        <div>
                            <h3>ห้อง<?php echo $row['type']?></h3>
                            <p>- รายเดือน : <?php echo number_format($row['price']); ?> บาท / เดือน</p>
                            <p>- รายวัน : <?php echo number_format($row['daily_price']); ?> บาท / คืน</p>
                            <p></p>
                        </div>
                        <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et, esse nulla aliquam reprehenderit dignissimos qui laudantium minus, blanditiis obcaecati tenetur sequi placeat impedit quis similique magnam pariatur voluptatum delectus aspernatur?</p> -->
                        <div style="display:flex;justify-content:flex-end">
                            <a href="pages/roomList.php"><button>ดูเพิ่มเติม</button></a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="hr"></div>
    <div class="submain2-box" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <div>
            <h2 class="submain2-topic">SERVICES</h2>
            <div class="submain2-grid">
                <div>
                    <img src="img/tool/wifi2.png" alt="" class="icon-img">
                    <p>Wi-Fi</p>
                </div>
                <div>
                    <img src="img/tool/air2.png" alt="" class="icon-img">
                    <p>เครื่องปรับอากาศ</p>
                </div>
                <div>
                    <img src="img/tool/wash2.png" alt="" class="icon-img">
                    <p>บริการเครื่องซักผ้า</p>
                </div>
                <div>
                    <img src="img/tool/camera2.png" alt="" class="icon-img">
                    <p>กล้องวงจรปิด</p>
                </div>
                <div>
                    <img src="img/tool/park2.png" alt="" class="icon-img">
                    <p>ที่จอดรถ</p>
                </div>
                <div>
                    <img src="img/tool/key2.png" alt="" class="icon-img">
                    <p>กุญแจคีย์การ์ด</p>
                </div>
            </div>
        </div>
    </div>
    <div class="hr"></div>
    <div class="gallery" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
        <div>
            <h2>Gallery</h2>
            <div class="gallery-grid">
                <?php
                    $num = 1;
                    $sql = "SELECT * FROM gallery ORDER BY gallery_id ASC LIMIT 8";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                ?>
                <div class="img<?php echo $num; ?>">
                    <img src="pages/images/gallery/<?php echo $row['gallery_name']; ?>" alt="">
                </div>
                <?php
                            $num++;
                        }
                    }else{
                        echo "ไม่มีรูปภาพ";
                    }
                ?>

            </div>
            <div style="padding-top:24px;display:flex;justify-content:flex-end;">
                <a href="pages/gallery.php">ดูเพิ่มเติม >></a>
            </div>
        </div>
    </div>
    <?php include('components/mainFoot.php'); ?>
    <div class="clickTotop" style="display:none;">
        <div class="arrow-up"></div>
    </div>
    <script src="js/main.js"></script>
    <script>
    AOS.init();
    </script>
</body>

</html>