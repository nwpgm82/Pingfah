<?php
include('connection.php');
include('../components/maintopbar.php');
$room_type = $_REQUEST['room_type'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/detailroompage.css">
    <title>Document</title>
</head>

<body>
    <div class="box">
        <?php
        $sql = "SELECT * FROM roomdetail WHERE type = '$room_type'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
        <h2>ประเภทห้องพัก</h2>
        <div class="hr"></div>
        <div class="roomPage">
            <div class="container">
                <?php
                    for($num=1;$num<=6;$num++){
                        if($row["pic".$num] != null){
                ?>
                <div class="mySlides">
                    <img src="images/roomdetail/<?php echo $row["pic".$num]; ?>" style="width:100%">
                </div>
                <?php 
                        }
                    } 
                ?>
                <a class="prev" onclick="plusSlides(-1)">❮</a>
                <a class="next" onclick="plusSlides(1)">❯</a>
                <div class="row">
                    <?php
                for($num2=1;$num2<=6;$num2++){
                    if($row["pic".$num2] != null){ 
                ?>
                    <div class="column">
                        <img class="demo cursor" src="images/roomdetail/<?php echo $row["pic".$num2]; ?>"
                            style="width:100%" onclick="currentSlide(<?php echo $num2; ?>)">
                    </div>
                    <?php
                    }
                }
                ?>
                </div>
            </div>
            <div class="room-detail">
                <h2>ห้อง<?php echo $row['type']; ?></h2>
                <div style="padding-top:32px;line-height:50px;">
                    <p><strong>- รายเดือน :</strong> <?php echo number_format($row['price']); ?> บาท / เดือน</p>
                    <p><strong>- รายวัน :</strong> <?php echo number_format($row['daily_price']); ?> บาท / วัน</p>
                    <p><strong>- ค่าน้ำ :</strong> <?php echo $row['water_bill']; ?> บาท / คน / เดือน</p>
                    <p><strong>- ค่าไฟ (หน่วย) :</strong> <?php echo $row['elec_bill']; ?> บาท / เดือน</p>
                    <p><strong>- ค่าเคเบิล :</strong> <?php echo $row['cable_charge']; ?> บาท / เดือน</p>
                </div>
                <div style="padding-top:32px;">
                    <a href="checkRoom.php"><button>จองห้องพักรายวัน</button></a>
                </div>
            </div>
        </div>
        <?php 
            }
        } 
        ?>
    </div>
    <script src="../js/roomDetailpage.js"></script>
</body>

</html>