<?php 
include('../../components/sidebar.php');
include('../../connection.php');
if($_SESSION['level'] == 'admin'){
$roomDetail = "SELECT * FROM roomdetail";
$result = mysqli_query($conn,$roomDetail) or die(mysql_error());
while($row = mysqli_fetch_array($result)) { 
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Pingfah/css/admin/roomDetail.css">
    <title>Document</title>
</head>

<body>
    <div style="padding:24px;">
        <div class="box">
            <div class="roomDetail-box">
                <form id="form" action="/Pingfah/pages/admin/roomDetail/function/addData.php" method="POST">
                    <div>
                        <h3>ค่าห้องพัก</h3>
                        <div class="rent-box">
                            <div class="form-box">
                                <p>ห้องพัดลม</p>
                                <input type="text" id="input" name="fan" <?php echo "value='" .$row['fan'] ."'"; ?> disabled>
                            </div>
                            <div class="form-box">
                                <p>ห้องแอร์</p>
                                <input type="text" id="input" name="air" <?php echo "value='" .$row['air'] ."'"; ?> disabled>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:32px;">
                        <h3>ค่าใช้จ่ายต่างๆ</h3>
                        <div class="rent-box">
                            <div class="form-box">
                                <p>ค่าน้ำ</p>
                                <input type="text" id="input" name="water" <?php echo "value='" .$row['water_bill'] ."'"; ?> disabled>
                            </div>
                            <div class="form-box">
                                <p>ค่าไฟ (หน่วย)</p>
                                <input type="text" id="input" name="elec" <?php echo "value='" .$row['elec_bill'] ."'"; ?> disabled>
                            </div>
                            <div class="form-box">
                                <p>ค่าเคเบิลทีวี</p>
                                <input type="text" id="input" name="cable" <?php echo "value='" .$row['cable_charge'] ."'"; ?> disabled>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:32px;">
                        <h3>ค่าใช้จ่ายอื่นๆ</h3>
                        <div class="rent-box">
                            <div class="form-box">
                                <p>ค่าปรับ</p>
                                <input type="text" id="input" name="fines" <?php echo "value='" .$row['fines'] ."'"; ?> disabled>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:32px;">
                        <button type="button" style="display:block" id="edit_btn" onclick="edit()">แก้ไข</button>
                        <div id="accept_btn" style="display:none">
                            <button type="submit">ยืนยัน</button>
                            <button type="button" onclick="edit()">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function confirmData() {
        if (confirm("คุณต้องการบันทึกข้อมูลหอพักใช่หรือไม่ ?")) {
            location.href = '/Pingfah/pages/admin/roomDetail/function/addData.php';
        }
    }

    function edit() {
        var edit = document.getElementById("edit_btn")
        var accept = document.getElementById("accept_btn")
        var form = document.getElementById("form");
        var elements = form.elements;
        if (accept.style.display == 'none') {
            accept.style.display = 'block'
            edit.style.display = 'none'
            for (var i = 0, len = elements.length; i < len-3; ++i) {
                elements[i].disabled = false;
            }
        } else {
            accept.style.display = 'none'
            edit.style.display = 'block'
            for (var i = 0, len = elements.length; i < len-3; ++i) {
                elements[i].disabled = true;
            }
        }
    }
    </script>
</body>

</html>

<?php
}
}else{
    if($_SESSION['level'] == 'employee'){
        Header("Location: /Pingfah/pages/employee/roomDetail/index.php");
    }else{
       Header("Location: ../login.php"); 
    }
}
?>