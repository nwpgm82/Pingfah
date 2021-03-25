<?php
session_start();
if($_SESSION['level'] == 'guest'){
    include('../../connection.php');
    include('../../../components/sidebarGuest.php');
    $repair_id = $_REQUEST["repair_id"];
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/repairDetail.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <title>Document</title>
</head>

<body>
    <?php
    $sql = "SELECT * FROM repair WHERE repair_id = $repair_id ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
    ?>
    <div class="box">
        <div style="padding:24px;">
            <div class="repairDetail-box">
                <h3>รายละเอียดความเสียหาย</h3>
                <div class="hr"></div>
                <div style="margin-top: 32px;">
                    <p>เลขห้อง</p>
                    <input type="text" value="<?php echo $row['room_id']; ?>" disabled>
                </div>
                <div class="flex-detail">
                    <div>
                        <p>อุปกรณ์</p>
                        <input type="text" value="<?php echo $row['repair_appliance']; ?>" disabled>
                    </div>
                    <div>
                        <p>ประเภท</p>
                        <input type="text" value="<?php echo $row['repair_category']; ?>" disabled>
                    </div>
                </div>
                <div style="margin-top: 32px;">
                    <p>รายละเอียด</p>
                    <textarea name="" id="" cols="30" rows="10" disabled><?php echo $row['repair_detail']; ?></textarea>
                </div>
                <form>
                    <div class="flex-detail">
                        <div>
                            <p>เวลาที่ลง</p>
                            <input type="text" value="<?php echo DateThai($row['repair_date']); ?>" disabled>
                        </div>
                        <div>
                            <p>สถานะ</p>
                            <input type="text" value="<?php echo $row['repair_status']; ?>" disabled>
                        </div>
                    </div>
                    <div class="flex-detail2" id="success_status" <?php if($row['repair_status'] != "ซ่อมเสร็จแล้ว"){ echo "style='display:none;'"; } ?>>
                        <div style="position:relative;">
                            <p>เวลาที่ซ่อมเสร็จ</p>
                            <input type="text" id="success_date" value="<?php echo $row['repair_successdate']; ?>"
                                name="success_date" <?php if($row['repair_status'] == 'ซ่อมเสร็จแล้ว'){ echo "style='background: #fafafa' disabled"; } ?>>
                            <p class="dateText" id="repair_successdate" <?php if($row['repair_status'] == 'ซ่อมเสร็จแล้ว'){ echo "style='background: #fafafa'"; } ?>></p>
                        </div>
                    </div>
                    <div class="hr" style="margin:32px 0;"></div>
                </form>
            </div>
        </div>
    </div>
    <?php } } ?>
    <script src="../../../js/guest/repairDetail.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>