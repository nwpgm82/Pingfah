<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    function DateThai($strDate){
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
        <div id="box-padding" style="padding:24px;">
            <div class="addPackage-box">
                <h3>เพิ่มรายการพัสดุ</h3>
                <div class="hr"></div>
                <form action="function/addPackage.php" method="POST">
                    <div class="flex-detail">
                        <div>
                            <p>เลขพัสดุ<label style="color:red;"> *</label></p>
                            <input type="text" name="num" id="code">
                            <h5 id="code_error" style="color:red;"></h5>
                        </div>
                        <div>
                            <p>บริษัทขนส่ง<label style="color:red;"> *</label></p>
                            <input type="text" name="company" id="package_company">
                            <h5 id="company_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="flex-detail">
                        <div>
                            <p>ชื่อเจ้าของพัสดุ<label style="color:red;"> *</label></p>
                            <input type="text" name="name" id="package_name">
                            <h5 id="name_error" style="color:red;"></h5>
                        </div>
                        <div>
                            <p>เลขห้อง<label style="color:red;"> *</label></p>
                            <select name="room" id="package_room">
                                <option value="">--</option>
                            </select>
                            <h5 id="room_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="flex-detail">
                        <div>
                            <p>วันที่พัสดุมาถึง<label style="color:red;"> *</label></p>
                            <input type="text" id="package_arrived" name="arrived">
                            <h5 id="date_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                        <button type="submit">ยืนยัน</button>
                    </div>
                </form>
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