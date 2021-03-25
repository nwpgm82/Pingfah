<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    $package_id = $_REQUEST["package_id"];
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $sql = "SELECT * FROM package WHERE package_id = $package_id";
    $result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
    $row = mysqli_fetch_array($result);
    if($row != null){
        extract($row);
    }   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/addPackage.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/addPackage.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Document</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div style="padding:24px;">
            <div class="addPackage-box">
                <div>
                   <h3>รายละเอียดพัสดุ</h3> 
                </div>
                <div class="hr"></div>
                <form action="function/editPackage.php?package_id=<?php echo $package_id; ?>" method="POST" onsubmit="return confirm('คุณต้องการบันทึกการแก้ไขใช่หรือไม่ ?')">
                    <div class="flex-detail">
                        <div>
                            <p>เลขพัสดุ<label style="color:red;"> *</label></p>
                            <input type="text" name="num" id="code" value="<?php echo $package_num; ?>">
                            <h5 id="code_error" style="color:red;"></h5>
                        </div>
                        <div>
                            <p>บริษัทขนส่ง<label style="color:red;"> *</label></p>
                            <input type="text" name="company" id="package_company"
                                value="<?php echo $package_company; ?>">
                            <h5 id="company_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="flex-detail">
                        <div>
                            <p>ชื่อเจ้าของพัสดุ</p>
                            <input type="text" name="name" id="package_name" value="<?php echo $package_name; ?>" disabled>
                            <h5 id="name_error" style="color:red;"></h5>
                        </div>
                        <div>
                            <p>เลขห้อง</p>
                            <input type="text" value="<?php echo $package_room ?>" disabled>
                            <h5 id="room_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="flex-detail">
                        <div>
                            <p>วันที่พัสดุมาถึง<label style="color:red;"> *</label></p>
                            <input type="text" id="package_arrived" name="arrived" value="<?php echo DateThai($package_arrived); ?>">
                            <h5 id="date_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                        <button type="submit">ยืนยันการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    let name = $("#package_name")
    let room = $("#package_room")
    if (name.val() != "") {
        name.css("border-color", "")
        $("#name_error").html("")
        let value = name.val()
        $.post('room_ajax.php', {
            name: value,
        }, function(data) {
            room.empty()
            room.append("<option value=''>--</option>")
            room.append(data)
            $("select option[value='<?php echo $package_room; ?>']").attr("selected", true)
        });
    }
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php");
}
?>