<?php
session_start();
if($_SESSION["level"] == "guest"){
    include("../../connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/appealDetail.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/manage/appeal_detail.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <form action="function/appealAdd.php" method="POST">
                <div class="appealDetail-box">
                    <h3>รายละเอียดการร้องเรียน</h3>
                    <div class="hr"></div>
                    <div class="grid-container">
                        <div>
                            <p>เลขห้อง</p>
                            <input type="text" value="<?php echo $_SESSION["name"]; ?>" disabled>
                        </div>
                        <div>
                            <p>หัวข้อร้องเรียน<label style="color:red;"> *</label></p>
                            <input type="text" id="topic" name="topic">
                            <h5 id="t_error" style="color:red;"></h5>
                        </div>
                    </div>
                    <div style="padding-top:32px;height:382px;">
                        <label>เนื้อหาการร้องเรียน<label style="color:red;"> *</label></label>
                        <textarea name="detail" id="detail"></textarea>
                        <h5 id="d_error" style="color:red;"></h5>
                    </div>
                    <div style="display:flex;justify-content:flex-end;align-items:center;color:red;">
                        <strong style="text-align:right;">*** เจ้าของหอพัก/แอดมิน เห็นได้เท่านั้น ***</strong>
                    </div>
                    <div class="hr"></div>
                    <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                        <button type="submit" id="confirm_appeal">ยืนยัน</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
}else{
    header("Location: ../../login.php");
}