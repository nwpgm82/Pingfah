<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/addcost.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/manage/addcost.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="addcost-box">
                <h3>รายละเอียดการชำระเงิน</h3>
                <div id="hr" class="hr"></div>
                <form action="function/addcostData.php" method="POST" onsubmit="return confirm('คุณต้องการบันทึกรายการชำระเงินใช่หรือไม่ ?'); ">
                    <div class="grid">
                        <div>
                            <p>เลขห้อง<label style="color:red;"> *</label></p>
                            <select name="room_select" id="room_select">
                                <option value="">--</option>
                                <?php
                                $select = $_REQUEST['room_select'];
                                $sql = "SELECT * FROM roomlist WHERE room_status = 'ไม่ว่าง' AND room_cat = 'รายเดือน' ";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                  while($row = $result->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $row['room_id']; ?>"
                                    <?php if($select == $row['room_id']){ echo "selected"; } ?>>
                                    <?php echo $row['room_id']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <?php
                        if($select != ""){
                            $searchRoom = mysqli_query($conn,"SELECT * FROM roomlist WHERE room_id = '$select'");
                            $room_result = mysqli_fetch_assoc($searchRoom);
                            $searchDetail = mysqli_query($conn,"SELECT * FROM roomdetail WHERE type = '".$room_result['room_type']."'");
                            $detail_result = mysqli_fetch_assoc($searchDetail);
                            $searchRoommember = mysqli_query($conn,"SELECT * FROM roommember WHERE room_id = '$select' AND member_status = 'กำลังเข้าพัก'");
                            $member_result = mysqli_fetch_assoc($searchRoommember);
                        }
                        ?>
                        <div>
                            <p>ประเภทห้องพัก<label style="color:red;"> *</label></p>
                            <input type="text" id="room_type" name="room_type"
                                value="<?php echo @$room_result['room_type']; ?>" readonly>
                        </div>
                        <div>
                            <p>ค่าห้องพัก (บาท)<label style="color:red;"> *</label></p>
                            <input type="text" id="room_price" name="room_price"
                                value="<?php echo @$detail_result['price']; ?>" readonly>
                        </div>
                        <div>
                            <p>ค่าเคเบิล (บาท)<label style="color:red;"> *</label></p>
                            <input type="text" id="cable_price" name="cable_price"
                                value="<?php echo @$detail_result['cable_charge']; ?>" readonly>
                        </div>
                        <div>
                            <p>ค่าน้ำ (จำนวนคน | บาท)<label style="color:red;"> *</label></p>
                            <div style="display:flex;">
                                <input type="text" id="water_people" style="width:80px;margin-right:8px;" value="<?php echo @$member_result["people"]; ?>" placeholder="คน" readonly>
                                <input type="text" id="water_price" name="water_price" placeholder="รวม (ค่าน้ำ)"
                                    readonly>
                            </div>
                        </div>
                        <div>
                            <p>ค่าไฟ (หน่วย | บาท)<label style="color:red;"> *</label></p>
                            <div style="display:flex;">
                                <input type="text" id="elec_unit" style="width:80px;margin-right:8px;"
                                    placeholder="หน่วย">
                                <input type="text" id="elec_price" name="elec_price" placeholder="รวม (ค่าไฟ)"
                                    value="0.00" readonly>
                            </div>
                        </div>
                    </div>
                    <div style="padding-top:32px;display:flex;justify-content:flex-end;align-items:center;">
                        <label>ยอดรวม</label>
                        <input id="total_price" name="total_price" style="width:100px;margin:0 8px;" readonly>
                        <label>บาท</label>
                    </div>
                    <div class="hr"></div>
                    <div style="display:flex;justify-content:center;align-items:center;">
                        <button type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    if ($("#room_select").val() != "") {
        $("#water_price").val((parseFloat($("#water_people").val()) * <?php if(isset($detail_result["water_bill"])){ echo $detail_result["water_bill"]; }else{ echo 0; } ?>).toFixed(2))
    }
    $("#elec_unit").keyup(function() {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#elec_unit").val() != "") {
            $("#elec_unit").css("border-color", "")
            $("#total_price").css("border-color", "")
            $("#elec_price").val(Math.ceil((parseFloat($("#elec_unit").val()) * <?php if(isset($detail_result["elec_bill"])){ echo $detail_result["elec_bill"]; }else{ echo 0; } ?>).toFixed(2)))
            $("#total_price").val((parseFloat($("#room_price").val()) + parseFloat($("#cable_price").val()) + parseFloat($("#water_price").val()) + parseFloat($("#elec_price").val())).toFixed(2))
        } else {
            $("#elec_price").val("0.00")
            $("#total_price").val("")
            $("#elec_unit").css("border-color", "red")
            $("#total_price").css("border-color", "red")
        }
    })
    </script>
</body>

</html>
<?php
}else{
   Header("Location: ../../login.php"); 
}

?>