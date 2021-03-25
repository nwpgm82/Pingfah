<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../connection.php");
    $id = $_REQUEST["appeal_id"];
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
    <link rel="stylesheet" href="../../../css/appealDetail.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="appealDetail-box">
                <h3>รายละเอียดการร้องเรียน</h3>
                <div class="hr"></div>
                <?php
                $sql = "SELECT * FROM appeal WHERE appeal_id = $id";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()) {
                ?>
                <div class="grid-container">
                    <div>
                        <p>เลขห้อง</p>
                        <input type="text" value="<?php echo $row['room_id']; ?>" disabled>
                    </div>
                    <div>
                        <p>หัวข้อร้องเรียน</p>
                        <input type="text" value="<?php echo $row['appeal_topic']; ?>" disabled>
                    </div>
                    <div>
                        <p>วันที่ร้องเรียน</p>
                        <input type="text" value="<?php echo DateThai($row['appeal_date']); ?>" disabled>
                    </div>
                </div>
                <div style="padding-top:32px;">
                <label>เนื้อหาการร้องเรียน</label>
                    <textarea disabled><?php echo $row['appeal_detail']; ?></textarea>
                </div>
                <?php
                    }
                }else{
                    echo "ไม่มีรายละเอียดการร้องเรียน";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>