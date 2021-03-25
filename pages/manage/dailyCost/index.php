<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include("../../connection.php");
    $check_in = @$_REQUEST['check_in'];
    $check_out = @$_REQUEST['check_out'];
    $code = @$_REQUEST['code'];
    $num = 1;
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if($check_in != "" && $check_out != ""){
        // $query = "SELECT b.check_in ,SUM(a.total_allprice) as daily_price FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) GROUP BY check_in";
        $query = "SELECT * FROM (SELECT b.check_in ,SUM(a.total_allprice) as daily_price FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) GROUP BY b.check_in ORDER BY b.check_in DESC LIMIT 5) sub ORDER BY check_in ASC";
    }else{
        // $query = "SELECT b.check_in ,SUM(a.total_allprice) as daily_price FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id GROUP BY b.check_in";
        $query = "SELECT * FROM (SELECT b.check_in ,SUM(a.total_allprice) as daily_price FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id GROUP BY b.check_in ORDER BY b.check_in DESC LIMIT 5) sub ORDER BY check_in ASC";
    }
    
    $query_result = mysqli_query($conn, $query);
    $datax = array();
    foreach ($query_result as $k) {
        $datax[] = "['".DateThai($k['check_in'])."'".", ".$k['daily_price']."]";
    }
    $datax = implode(",", $datax);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/dailyCost.css">
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
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="dailycost-box">
                <h3>ค้นหารายการชำระเงินรายวัน</h3>
                <div class="search">
                    <label class="search-topic" style="padding:10px 8px 0 0;">ค้นหาตามวันที่</label>
                    <div class="from-box" style="position:relative;">
                        <input type="text" class="roundtrip-input" id="check_in"
                            value="<?php if(isset($check_in)){ echo DateThai($check_in); } ?>">
                        <h5 id="checkIn_error" style="color:red;"></h5>
                    </div>
                    <label class="to-text" style="padding:10px 8px 0 8px;">~</label>
                    <div class="to-box" style="position:relative;">
                        <input type="text" class="roundtrip-input" id="check_out"
                            value="<?php if(isset($check_out)){ echo DateThai($check_out); } ?>">
                        <h5 id="checkOut_error" style="color:red;"></h5>
                    </div>
                    <button class="search-btn" type="button" id="searchDate">ค้นหา</button>
                    <label class="searchcode-topic" style="padding:10px 8px 0 0;">ค้นหาเลขที่การจอง</label>
                    <div class="searchcode-box" >
                        <input type="text" id="code" value="<?php echo $code?>">
                        <h5 id="code_error" style="color:red;"></h5>
                    </div>
                    <button class="searchcode-btn" type="button" id="searchCode">ค้นหา</button>
                    <?php
                    if(isset($check_in) || isset($check_out) || isset($code)){
                    ?>
                    <div class="cancel-box">
                        <a href="index.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="hr"></div>
                <div class="card">
                    <?php
                    if(strlen($datax) != 0){
                    ?>
                    <div id="curve_chart" class="chart"></div>
                    <?php
                    }else{
                        echo "<p style='margin:auto;'>ไม่มีข้อมูล</p>";
                    }
                    ?>
                </div>
                <div style="padding-top:32px;">
                    <?php
                    if(isset($check_in) && isset($check_out)){
                        $search_totalprice = mysqli_query($conn, "SELECT SUM(a.total_allprice) as allprice FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out ))");
                    }else{
                        $search_totalprice = mysqli_query($conn, "SELECT SUM(total_allprice) as allprice FROM dailycost");
                    }
                    $result_totalprice = mysqli_fetch_assoc($search_totalprice);
                    ?>
                    <h3 style="color: rgb(131, 120, 47, 0.7);">ยอดรวมทั้งหมด : <?php echo number_format($result_totalprice["allprice"]); ?> บาท</h3>
                </div>
                <div class="hr"></div>
                <?php
                $perpage = 10;
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                }else{
                    $page = 1;
                }
                $start = ($page - 1) * $perpage;
                $num = $start + 1;
                if(isset($check_in) && isset($check_out)){
                    $sql = "SELECT a.*, b.* FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE (check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out ) ORDER BY dailycost_id DESC LIMIT {$start} , {$perpage}";
                }else if(isset($code)){
                    $sql = "SELECT a.*, b.* FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE a.code = '$code' ORDER BY dailycost_id DESC";
                }else{
                    $sql = "SELECT a.*, b.* FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id ORDER BY dailycost_id DESC LIMIT {$start} , {$perpage}";
                }
                $result = $conn->query($sql);
                if(isset($code)){
                    echo "<h3>ผลลัพธ์การค้นหา : $code</h3>";
                }else{
                    echo "<h3>รายการชำระเงินห้องพักรายวันทั้งหมด</h3>";
                }
                if ($result->num_rows > 0) {
                ?>
                <div style="overflow-x: auto;overflow-y: hidden;">
                    <table>
                        <tr>
                            <th>ลำดับ</th>
                            <!-- <th>เลขห้องที่จอง</th> -->
                            <th>ชื่อผู้เช่า</th>
                            <th>วันที่เข้าพัก</th>
                            <th>เลขที่การจอง</th>
                            <th>ราคารวม</th>
                            <th>สถานะการชำระเงิน</th>
                            <th>เพิ่มเติม</th>
                        </tr>
                        <?php
                            while($row = $result->fetch_assoc()) {
                            $calculate = strtotime($row["check_out"]) - strtotime($row["check_in"]);
                            $night = floor($calculate / 86400);
                        ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <!-- <td><?php echo $row['room_select']; ?></td> -->
                            <td><?php echo $row["name_title"].$row['firstname'] ." " .$row['lastname']; ?></td>
                            <td><?php echo DateThai($row['check_in']) ."&nbsp; ~ &nbsp;" .DateThai($row['check_out'])." (".$night." คืน)"; ?>
                            </td>
                            <td><?php echo $row['code']; ?></td>
                            <td><?php echo number_format($row['total_allprice']); ?></td>
                            <td><button class="status-success"><?php echo $row['pay_status']; ?></button></td>
                            <td>
                                <div class="option-grid">
                                    <a href="receipt_deposit_back.php?code=<?php echo $row["code"]; ?>" target="_blank"><button type="button" class="print" title="ใบเสร็จคืนค่ามัดจำคีย์การ์ด"></button></a>
                                    <a href="dailyCostDetail.php?dailycost_id=<?php echo $row['dailycost_id']; ?>"><button>รายละเอียด</button></a>
                                    <?php
                                    if($_SESSION["level"] == "admin"){
                                    ?>
                                    <button type="button" class="del" id="<?php echo $row['daily_id']; ?>"></button>
                                    <?php } ?>
                                </div> 
                            </td>
                        </tr>
                        <?php $num++; } ?>
                    </table>
                </div>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM dailycost";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>"  <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>"  <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>>&raquo;</a>
                    </div>
                </div>
                <?php 
                }else{
                    echo "<div style='padding-top:32px;'>ไม่มีรายการชำระเงินเช่ารายวัน</div>";
                } 
                ?>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/dailyCost.js"></script>
    <script>
    google.charts.load("current", {packages: ["corechart"]});
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>
    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['วัน / เดือน / ปี', 'ยอดรวมทั้งหมด (บาท)'],
            <?php echo $datax;?>
        ]);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

        var options = {
            title: 'รายการชำระเงินรายวัน',
            colors: ['rgb(131, 120, 47)'],
            fontName: "Sarabun",
            vAxis: {
                format: "decimal"
            },
            annotations:{
                alwaysOutside: true
            },
            width: "100%",
            height: "100%",
            bar: {groupWidth: "50%"},
            legend: { position: "bottom" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('curve_chart'));
        chart.draw(view, options);
    }
    $(window).resize(function(){
        drawChart();
    });
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>