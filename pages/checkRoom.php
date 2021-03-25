<?php
    session_start();
    include("connection.php");
    $check_in = $_REQUEST['check_in'];
    $check_out = $_REQUEST['check_out'];
    $calculate = strtotime($check_out) - strtotime($check_in);
    $summary = floor($calculate / 86400);
    $_SESSION["night"] = $summary;
    $_SESSION["people"] = $_REQUEST['people'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/checkRoom.css">
    <link rel="stylesheet" href="../css/my-style.css">
    <link rel="stylesheet" href="../css/mainTop.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../js/datedropper.pro.min.js"></script>
    <script src="../js/mainTop.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../components/maintopbar.php"); ?>
    <div class="box">
        <div class="checkRoom">
            <form action="dailyForm.php" method="POST">
                <div class="searchDate">
                    <div id="from">
                            <label class="checkText">Check In : </label>
                            <div style="position:relative;padding-left:8px;height:40px;">
                                <input id="check_in" class="roundtrip-input" name="check_in" type="text"
                                    value="<?php echo $check_in; ?>">
                                <p id="check_in_date" class="dateText"></p>
                            </div>
                    </div>
                    <div id="to" style="padding:0 16px">
                            <label class="checkText">Check Out : </label>
                            <div style="position:relative;padding-left:8px;height:40px;">
                                <input id="check_out" class="roundtrip-input" name="check_out" type="text"
                                    value="<?php echo $check_out; ?>">
                                <p id="check_out_date" class="dateText"></p>
                            </div>
                    </div>
                    <div id="night" style="min-width:52px;display:flex;align-items:center;">
                        <p id="summary"><?php echo "(".$_SESSION["night"]." คืน)"?></p>
                    </div>
                    <div id="p" style="padding:0 16px">
                        <div style="display:flex;align-items:center;">
                            <label>จำนวนผู้พัก : </label>
                            <div style="position:relative;padding:0 8px;height:40px;">
                                <input type="text" id="people" name="people" value="<?php echo $_SESSION["people"]; ?>" maxlength="2">
                            </div>
                            <label>ท่าน</label>
                        </div>
                    </div>
                    <button type="button" id="search">ค้นหา</button>
                </div>

                <div class="hr"></div>
                <?php 
            if(isset($check_in) || isset($check_out)){
                echo "<h2>ประเภทห้องพักที่ว่างให้บริการ</h2><div class='hr'></div>";
            }
            
            ?>
                <div>
                    <?php
                $countDailyAll = mysqli_query($conn,"SELECT SUM(air_room+fan_room) AS dailyTotal FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง'");
                $roomDailyAlldata= mysqli_fetch_assoc($countDailyAll);  
                $roomDailyAlltotal_int = intval($roomDailyAlldata['dailyTotal']);
                $countroomAll = mysqli_query($conn,"SELECT COUNT(*) AS roomtotal FROM roomlist WHERE room_cat = 'รายวัน'");
                $roomAlldata= mysqli_fetch_assoc($countroomAll);  
                $roomAlltotal_int = intval($roomAlldata['roomtotal']);
                $totalAll_int = $roomAlltotal_int - $roomDailyAlltotal_int;

                if($_SESSION["people"] <= ($totalAll_int * 2)){
                    if(isset($check_in) || isset($check_out)){
                        $countAir = mysqli_query($conn,"SELECT SUM(air_room) AS airTotal FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง'");
                        $roomDailyAirdata= mysqli_fetch_assoc($countAir);  
                        $roomDailyAirtotal_int = intval($roomDailyAirdata['airTotal']);
                        $countroom = mysqli_query($conn,"SELECT COUNT(*) AS roomtotal FROM roomlist WHERE room_type = 'แอร์' AND room_cat = 'รายวัน'");
                        $roomdata= mysqli_fetch_assoc($countroom);  
                        $roomtotal_int = intval($roomdata['roomtotal']);
                        $total_int = $roomtotal_int - $roomDailyAirtotal_int;
                        if($total_int > 0){
                            $sql = "SELECT * FROM roomdetail WHERE type = 'แอร์'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                ?>
                    <div id="air" class="card">
                        <div class="container">
                            <?php
                            $column1 = 1;
                            $getImg = "SELECT * FROM air_gal";
                            $resultImg = $conn->query($getImg);
                            if ($resultImg->num_rows > 0) {
                                while($row2 = $resultImg->fetch_assoc()) {
                            ?>
                            <div class="mySlides1">
                                <img src="images/roomdetail/air/<?php echo $row2['gal_name']; ?>" alt="">
                            </div>
                            <?php } } ?>
                            <a class="prev" onclick="plusSlides1(-1)">❮</a>
                            <a class="next" onclick="plusSlides1(1)">❯</a>
                            <div class="row" id="row1">
                                <?php
                                $getImg2 = "SELECT * FROM air_gal";
                                $resultImg2 = $conn->query($getImg2);
                                if ($resultImg2->num_rows > 0) {
                                    while($row3 = $resultImg2->fetch_assoc()) {
                                ?>
                                <div class="column">
                                    <img class="demo1 cursor"
                                        src="images/roomdetail/air/<?php echo $row3['gal_name']; ?>" style="width:100%"
                                        onclick="currentSlide1(<?php echo $column1; ?>)">
                                </div>
                                <?php $column1++; } } ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h2>ห้องแอร์</h2>
                            <div class="hr" style="margin:16px 0;"></div>
                            <div class="detail">
                                <div>
                                    <h3>รายละเอียดห้องพัก</h3>
                                    <div class="user-grid">
                                        <img src="../img/tool/user.png" alt="">
                                        <label>2 คน</label>
                                    </div>
                                    <label>รายวัน : <label
                                            style="font-size:24px;color: rgb(131, 120, 47, 1);"><strong><?php echo number_format($row['daily_price']); ?></strong></label>
                                        บาท</label>
                                </div>
                                <div>
                                    <h3>สิ่งอำนวยความสะดวก</h3>
                                    <div class="convenient-grid">
                                        <?php
                                        if($row["sv_air"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/air2.png">
                                            <label>เครื่องปรับอากาศ</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_fan"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/fan.png">
                                            <label>พัดลม</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_wifi"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/wifi2.png">
                                            <label>WI-FI</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_furniture"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/clothes.png">
                                            <label>เฟอร์นิเจอร์ - ตู้เสื้อผ้า, เตียง</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_readtable"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/table.png">
                                            <label>โต๊ะอ่านหนังสือ</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_telephone"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/telephone.png">
                                            <label>โทรศัพท์</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_television"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/television.png">
                                            <label>โทรทัศน์ดาวเทียม / เคเบิล</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_refrigerator"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/refrigerator.png">
                                            <label>ตู้เย็น</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_waterbottle"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/waterbottle.png">
                                            <label>น้ำบรรจุขวด</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_toilet"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/toilet-items.png">
                                            <label>ของใช้ในห้องน้ำ</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_hairdryer"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/hairdryer.png">
                                            <label>ไดร์เป่าผม</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row["sv_towel"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/towel.png">
                                            <label>ผ้าเช็ดตัว</label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="f" style="display: flex;justify-content: space-between;align-items:center;">
                                <p>จำนวนห้องพักที่เหลือ : <?php echo $total_int; ?> ห้อง</p>
                                <div class="subf" style="display: flex;align-items: center;gap: 8px;">
                                    <label>จำนวนห้องพักที่ต้องการ : </label>
                                    <button type="button" id="DesAir">-</button>
                                    <input type="number" id="people1" min="0" max="<?php echo $total_int; ?>" value="0"
                                        name="air" readonly>
                                    <button type="button" id="InsAir">+</button>
                                    <label>ห้อง</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }} ?>
                    <?php
                    if(isset($check_in) || isset($check_out)){
                        $countFan = mysqli_query($conn,"SELECT SUM(fan_room) AS fanTotal FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง'");
                        $roomDailyFandata= mysqli_fetch_assoc($countFan);  
                        $roomDailyFantotal_int = intval($roomDailyFandata['fanTotal']);
                        $countroom2 = mysqli_query($conn,"SELECT COUNT(*) AS roomtotal2 FROM roomlist WHERE room_type = 'พัดลม' AND room_cat = 'รายวัน'");
                        $roomdata2= mysqli_fetch_assoc($countroom2);  
                        $roomtotal_int2 = intval($roomdata2['roomtotal2']);
                        $total_int2 = $roomtotal_int2 - $roomDailyFantotal_int;
                        if($total_int2 > 0){
                            $sql2 = "SELECT * FROM roomdetail WHERE type = 'พัดลม'";
                            $result2 = $conn->query($sql2);
                            $row2 = $result2->fetch_assoc();
                    ?>
                    <div id="fan" class="card">
                        <div class="container">
                            <?php
                            $column2 = 1;
                            $getImg3 = "SELECT * FROM fan_gal";
                            $resultImg3 = $conn->query($getImg3);
                            if ($resultImg3->num_rows > 0) {
                                while($row3 = $resultImg3->fetch_assoc()) {
                            ?>
                            <div class="mySlides2">
                                <img src="images/roomdetail/fan/<?php echo $row3['gal_name']; ?>" alt="">
                            </div>
                            <?php } } ?>
                            <a class="prev" onclick="plusSlides2(-1)">❮</a>
                            <a class="next" onclick="plusSlides2(1)">❯</a>
                            <div class="row" id="row2">
                                <?php
                                $getImg4 = "SELECT * FROM fan_gal";
                                $resultImg4 = $conn->query($getImg4);
                                if ($resultImg4->num_rows > 0) {
                                    while($row4 = $resultImg4->fetch_assoc()) {
                                ?>
                                <div class="column">
                                    <img class="demo2 cursor"
                                        src="images/roomdetail/fan/<?php echo $row4['gal_name']; ?>" style="width:100%"
                                        onclick="currentSlide2(<?php echo $column2; ?>)">
                                </div>
                                <?php $column2++; } } ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h2>ห้องพัดลม</h2>
                            <div class="hr" style="margin:16px 0;"></div>
                            <div class="detail">
                                <div>
                                    <h3>รายละเอียดห้องพัก</h3>
                                    <div class="user-grid">
                                        <img src="../img/tool/user.png" alt="">
                                        <label>2 คน</label>
                                    </div>
                                    <label>รายวัน : <label
                                            style="font-size:24px;color: rgb(131, 120, 47, 1);"><strong><?php echo number_format($row2['daily_price']); ?></strong></label>
                                        บาท</label>
                                </div>
                                <div>
                                    <h3>สิ่งอำนวยความสะดวก</h3>
                                    <div class="convenient-grid">
                                        <?php
                                        if($row2["sv_air"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/air2.png">
                                            <label>เครื่องปรับอากาศ</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row2["sv_fan"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/fan.png">
                                            <label>พัดลม</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row2["sv_wifi"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/wifi2.png">
                                            <label>WI-FI</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row2["sv_furniture"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/clothes.png">
                                            <label>เฟอร์นิเจอร์ - ตู้เสื้อผ้า, เตียง</label>
                                        </div>
                                        <?php } ?>
                                        <?php
                                        if($row2["sv_readtable"] == "on"){
                                        ?>
                                        <div class="sub-grid">
                                            <img src="../img/tool/table.png">
                                            <label>โต๊ะอ่านหนังสือ</label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="f" style="display: flex;justify-content: space-between;align-items:center;">
                                <p>จำนวนห้องพักที่เหลือ : <?php echo $total_int2; ?> ห้อง</p>
                                <div class="subf" style="display: flex;align-items: center;gap: 8px;">
                                    <label>จำนวนห้องพักที่ต้องการ : </label>
                                    <button type="button" id="DesFan">-</button>
                                    <input type="number" id="people2" min="0" max="<?php echo $total_int2; ?>" value="0"
                                        name="fan" readonly>
                                    <button type="button" id="InsFan">+</button>
                                    <label>ห้อง</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div style="display:flex;justify-content:flex-end;align-items:center;">
                        <h2>ราคารวม : <label id="total_price">0</label> บาท</h2>
                    </div>
                    <div id="r" style="padding-top:32px;display:flex;justify-content:flex-end;align-items:center">
                        <button type="submit" class="rent">จองเลย</button>
                    </div>
                    <?php
                    }}else{
                        echo "ไม่มีห้องว่างให้เช่า";
                    } ?>
                </div>
            </form>
        </div>
    </div>
    <script src="../js/checkRoom.js"></script>
    <script>
    <?php
    $getAir_price = mysqli_query($conn,"SELECT daily_price FROM roomdetail WHERE type='แอร์'");
    $getAir_result = mysqli_fetch_assoc($getAir_price); 
    $getFan_price = mysqli_query($conn,"SELECT daily_price FROM roomdetail WHERE type='พัดลม'");
    $getFan_result = mysqli_fetch_assoc($getFan_price);  
    ?>
    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    let people = <?php echo $_SESSION["people"]; ?>;
    let total_price = 0
    $("#InsAir").click(function(){
        if(people != 0 && parseInt($("#people1").val()) < <?php echo $total_int; ?>){
            $("#people1").val(parseInt($("#people1").val()) + 1)
            people = people - 1
            total_price = total_price + <?php echo intval($getAir_result["daily_price"]) * $_SESSION["night"]; ?>;
        }
        $("#total_price").html(formatNumber(total_price))
    })
    $("#DesAir").click(function(){
        if(parseInt($("#people1").val()) != 0){
            $("#people1").val(parseInt($("#people1").val()) - 1)
            people = people + 1
            total_price = total_price - <?php echo intval($getAir_result["daily_price"]) * $_SESSION["night"]; ?>;
        }
        $("#total_price").html(formatNumber(total_price))
    })
    $("#InsFan").click(function(){
        if(people != 0 && parseInt($("#people2").val()) < <?php echo $total_int2; ?>){
            $("#people2").val(parseInt($("#people2").val()) + 1)
            people = people - 1
            total_price = total_price + <?php echo intval($getFan_result["daily_price"]) * $_SESSION["night"]; ?>;
        }
        $("#total_price").html(formatNumber(total_price))
    })
    $("#DesFan").click(function(){
        if(parseInt($("#people2").val()) != 0){
            $("#people2").val(parseInt($("#people2").val()) - 1)
            people = people + 1
            total_price = total_price - <?php echo intval($getFan_result["daily_price"]) * $_SESSION["night"]; ?>;
        }
        $("#total_price").html(formatNumber(total_price))
    })
    </script>
</body>

</html>