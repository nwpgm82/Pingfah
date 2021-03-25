<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){ 
    include('../../connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/addRepair.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="addRepair-box">
                <h3>รายละเอียดความเสียหาย</h3>
                <div class="hr"></div>
                <form action="../repair/function/repairAccept.php" method="POST">
                    <div style="margin-top: 32px;">
                        <p>เลขห้อง<label style="color:red;"> *</label></p>
                        <?php
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                        ?>
                        <select id="room_select" name="room_select">
                            <option value="">---</option>
                            <?php
                            $roomlist = "SELECT * FROM roomlist";
                            $result = mysqli_query($conn,$roomlist) or die(mysql_error());
                            while($row = mysqli_fetch_array($result)) {
                                if($row['room_status'] == 'ไม่ว่าง'){
                                    if($select != ''){
                                        echo "<option value='" .$select ."' selected>" .$select ."</option>";
                                    }else{
                                        echo "<option value='" .$row['room_id'] ."'>" .$row['room_id'] ."</option>";
                                    }  
                                }
                            }
                        ?>
                        </select>
                        <h5 id="room_error" style="color:red;"></h5>
                        <?php 
                        }else if($_SESSION["level"] == "guest"){
                        ?>
                        <input type="text" value="<?php echo $_SESSION["name"]; ?>" disabled>
                        <?php } ?>
                    </div>
                    <div class="flex-detail">
                        <div>
                            <p>ประเภท<label style="color:red;"> *</label></p>
                            <select type="text" id="select" name="repair_category" onchange="categoryList()">
                                <option value="">---</option>
                                <option value="เครื่องใช้ไฟฟ้า">เครื่องใช้ไฟฟ้า</option>
                                <option value="เฟอร์นิเจอร์">เฟอร์นิเจอร์</option>
                                <option value="สุขภัณฑ์">สุขภัณฑ์</option>
                            </select>
                            <h5 id="cat_error" style="color:red;"></h5>
                        </div>
                        <div>
                            <p>อุปกรณ์<label style="color:red;"> *</label></p>
                            <select type="text" id="list" name="repair_appliance">
                                <!-- <option value=""></option>
                            <option value=""></option>
                            <option value=""></option> -->
                            </select>
                        </div>
                    </div>
                    <div>
                        <p>รายละเอียด<label style="color:red;"> *</label></p>
                        <textarea name="repair_detail" id="repair_detail" cols="30" rows="10"></textarea>
                        <h5 id="detail_error" style="color:red"></h5>
                    </div>
                    <!-- <div class="flex-detail">
                        <div>
                            <p>เวลาที่ลง</p>
                            <input type="text" id="repairDate" name="repair_date" readonly>
                            <h5 id="date_error" style="color:red"></h5>
                        </div>
                        <div>
                            <p>สถานะ</p>
                            <select name="repair_status" id="status">
                                <option value="รอดำเนินการ">รอดำเนินการ</option>
                                <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
                                <option value="ดำเนินการเสร็จสิ้น">ดำเนินการเสร็จสิ้น</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="hr"></div>
                    <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                        <button type="submit" name="repair_accept">ยืนยัน</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="../../../js/manage/addRepair.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>