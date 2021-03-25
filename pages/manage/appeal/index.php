<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $num = 1;
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
    <link rel="stylesheet" href="../../../css/appeal.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="appeal-box">
                <h3>ค้นหารายการร้องเรียน</h3>
                <div class="search">
                    <label class="search-topic" style="padding:10px 8px 0 0;">ค้นหาตามวันที่</label>
                    <div class="from-box" style="position:relative;">
                        <input type="text" class="roundtrip-input" id="date_from"
                            value="<?php if(isset($from)){ echo DateThai($from); } ?>">
                        <h5 id="from_error" style="color:red;"></h5>
                    </div>
                    <label class="to-text" style="padding:10px 8px 0 8px;">~</label>
                    <div class="to-box" style="position:relative;">
                        <input type="text" class="roundtrip-input" id="date_to"
                            value="<?php if(isset($to)){ echo DateThai($to); } ?>">
                        <h5 id="to_error" style="color:red;"></h5>
                    </div>
                    <button class="search-btn" type="button" id="searchDate" style="margin-left:16px;">ค้นหา</button>
                    <?php
                    if(isset($from) || isset($to) || isset($check)){
                    ?>
                    <div class="cancel-box" style="padding:0 16px;">
                        <a href="index.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="hr"></div>
                <h3>รายการร้องเรียนทั้งหมด</h3>
                <?php
                    $perpage = 10;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    $num = $start + 1;
                    if(isset($from) && isset($to)){
                        $sql = "SELECT * FROM appeal WHERE (appeal_date BETWEEN '$from' AND '$to') ORDER BY appeal_id DESC LIMIT {$start} , {$perpage}";
                    }else{
                        $sql = "SELECT * FROM appeal ORDER BY appeal_id DESC LIMIT {$start} , {$perpage}";
                    }
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                    ?>
                <div style="overflow-x:auto;overflow-y:hidden;">
                    <table>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขห้อง</th>
                            <th>หัวข้อร้องเรียน</th>
                            <th>วันที่ร้องเรียน</th>
                            <th>เพิ่มเติม</th>
                        </tr>
                        <?php
                        while($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['appeal_topic']; ?></td>
                            <td><?php echo DateThai($row['appeal_date']); ?></td>
                            <td>
                                <div class="option-grid">
                                    <a href="appealDetail.php?appeal_id=<?php echo $row['appeal_id']; ?>"><button>รายละเอียด</button></a>
                                    <button class="del-btn" id="<?php echo $row['appeal_id']; ?>"></button>
                                </div>
                            </td>
                        </tr>
                        <?php $num++; } ?>
                    </table>
                </div>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM appeal";
                $query2 = mysqli_query($conn, $sql2);
                $total_record = mysqli_num_rows($query2);
                $total_page = ceil($total_record / $perpage);
                ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>" <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>
                <?php
                }else{
                    echo "<div style='padding-top:32px;'>ไม่มีรายการร้องเรียน</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/appeal.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>