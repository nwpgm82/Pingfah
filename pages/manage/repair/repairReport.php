<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee" || $_SESSION["level"] == "guest"){
    include("../../connection.php");
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
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
    if(isset($from) && isset($to)){
        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            // $query_data = "SELECT repair_successdate, SUM(repair_profit) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to') GROUP BY repair_successdate";
            $query_data = "SELECT * FROM (SELECT repair_successdate, SUM(repair_profit) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to') GROUP BY repair_successdate ORDER BY repair_successdate DESC LIMIT 5) sub ORDER BY repair_successdate ASC";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_income) as income_d, SUM(repair_expenses) as ex_d, SUM(repair_profit) as pro_d FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to')");
        }else if($_SESSION["level"] == "guest"){
            // $query_data = "SELECT repair_successdate, SUM(repair_income) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to') AND member_id = ".$_SESSION["member_id"]." GROUP BY repair_successdate";
            $query_data = "SELECT * FROM (SELECT repair_successdate, SUM(repair_income) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to') AND member_id = ".$_SESSION["member_id"]." GROUP BY repair_successdate ORDER BY repair_successdate DESC LIMIT 5) sub ORDER BY repair_successdate ASC";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_income) as ex_d FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND (repair_successdate BETWEEN '$from' AND '$to') AND member_id = ".$_SESSION["member_id"]);
        }
    }else{
        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            // $query_data = "SELECT repair_successdate, SUM(repair_profit) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' GROUP BY repair_successdate";
            $query_data = "SELECT * FROM (SELECT repair_successdate, SUM(repair_profit) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' GROUP BY repair_successdate ORDER BY repair_successdate DESC LIMIT 5) sub ORDER BY repair_successdate ASC";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_income) as income_d, SUM(repair_expenses) as ex_d, SUM(repair_profit) as pro_d FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว'");
        }else if($_SESSION["level"] == "guest"){
            // $query_data = "SELECT repair_successdate, SUM(repair_income) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND member_id = ".$_SESSION["member_id"]." GROUP BY repair_successdate";
            $query_data = "SELECT * FROM (SELECT repair_successdate, SUM(repair_profit) as profit FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND member_id = ".$_SESSION["member_id"]." GROUP BY repair_successdate ORDER BY repair_successdate DESC LIMIT 5) sub ORDER BY repair_successdate ASC";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_income) as ex_d FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND member_id = ".$_SESSION["member_id"]);
        }
    }
    $result = mysqli_query($conn, $query_data);
    $result_d = mysqli_fetch_assoc($query_detail);
    $datax = array();
    foreach ($result as $k) {
        $datax[] = "['".DateThai($k['repair_successdate'])."'".", ".$k['profit'] ."]";
    }
    $datax = implode(",", $datax);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/repairReport.css">
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
            <div class="repairReport-box">
                <h3>ค้นหารายการแจ้งซ่อมที่ซ่อมเสร็จแล้ว</h3>
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
                        <a href="repairReport.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="hr" style="margin-top:16px;"></div>
                <div>
                    <div class="card">
                        <?php
                        if(strlen($datax) != 0){
                        ?>
                        <div id="curve_chart" class="chart"></div>
                        <?php }else{  echo "<p style='margin:auto;'>ไม่มีข้อมูล</p>"; } ?>
                    </div>
                    <div style="padding-top:32px;line-height:40px;">
                        <?php
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                        ?>
                        <h3 style="color: rgb(131, 120, 47, 0.7);">รายรับทั้งหมด : <?php echo number_format($result_d["income_d"]); ?> บาท</h3>
                        <h3 style="color: #D68B8B;">ค่าใช้จ่ายทั้งหมด : <?php echo number_format($result_d["ex_d"]); ?> บาท</h3>
                        <h3 style="color: #B1D78A;">กำไรทั้งหมด : <?php echo number_format($result_d["pro_d"]); ?> บาท</h3>
                        <?php
                        }else if($_SESSION["level"] == "guest"){
                        ?>
                        <h3 style="color: rgb(131, 120, 47, 0.7);">ค่าใช้จ่ายทั้งหมด : <?php echo number_format($result_d["ex_d"], 2); ?> บาท</h3>
                        <?php } ?>
                    </div>
                    <div class="hr"></div>
                    <h3>รายการแจ้งซ่อมที่ซ่อมเสร็จแล้วทั้งหมด</h3>
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
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair WHERE (repair_successdate BETWEEN '$from' AND '$to') AND repair_status = 'ซ่อมเสร็จแล้ว' ORDER BY repair_id DESC LIMIT {$start} , {$perpage} "; 
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT * FROM repair WHERE (repair_successdate BETWEEN '$from' AND '$to') AND repair_status = 'ซ่อมเสร็จแล้ว' AND member_id = ".$_SESSION["member_id"]." ORDER BY repair_id DESC LIMIT {$start} , {$perpage} "; 
                        }  
                    }else{
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' ORDER BY repair_id DESC LIMIT {$start} , {$perpage} ";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT * FROM repair WHERE repair_status = 'ซ่อมเสร็จแล้ว' AND member_id = ".$_SESSION["member_id"]." ORDER BY repair_id DESC LIMIT {$start} , {$perpage} ";
                        }
                    }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                ?>
                    <div style="overflow-x:scroll;">
                        <table>
                            <tr>
                                <th>ลำดับ</th>
                                <th>เลขห้อง</th>
                                <th>อุปกรณ์</th>
                                <th>หมวดหมู่</th>
                                <?php
                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                ?>
                                <th>รายรับ</th>
                                <?php } ?>
                                <th>ค่าใช้จ่าย</th>
                                <?php
                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                ?>
                                <th>กำไร</th>
                                <?php } ?>
                                <th>วันที่แจ้งซ่อม</th>
                                <th>วันที่ซ่อมเสร็จ</th>
                                <th>สถานะ</th>
                                <th>เพิ่มเติม</th>
                            </tr>
                            <?php while($row = $result->fetch_assoc()){ ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $row['room_id']; ?></td>
                                <td><?php echo $row['repair_appliance']; ?></td>
                                <td><?php echo $row['repair_category']; ?></td>
                                <td><?php echo number_format($row['repair_income']); ?></td>
                                <?php
                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                ?>
                                <td><?php echo number_format($row['repair_expenses']); ?></td>
                                <td><?php echo number_format($row['repair_profit']); ?></td>
                                <?php } ?>
                                <td><?php echo DateThai($row['repair_date']); ?></td>
                                <td><?php echo DateThai($row['repair_successdate']); ?></td>
                                <td>
                                    <div class="success-status">
                                        <p><?php echo $row['repair_status']; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex-more">
                                    <?php
                                        if($row["repair_income"] != 0){
                                        ?>
                                        <div>
                                            <a href="receipt_repair.php?repair_id=<?php echo $row["repair_id"]; ?>" target="_blank"><button class="print"></button></a>
                                        </div>
                                        <?php
                                        }else{
                                        ?>
                                        <div>
                                            <button class="print" disabled></button>
                                        </div>
                                        <?php } ?>
                                        <a href="repairDetail.php?repair_id=<?php echo $row['repair_id'];?>" title="ดูข้อมูลเพิ่มเติม"><button>ดูข้อมูลเพิ่มเติม</button></a>
                                        <button class="del-btn" id="<?php echo $row['repair_id']; ?>" title="ลบข้อมูล"></button>
                                    </div>
                                </td>
                            </tr>
                            <?php $num++; } ?>
                        </table>
                    </div>
                    <?php
                    ///////pagination
                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                        $sql2 = "SELECT * FROM repair";
                    }else if($_SESSION["level"] == "guest"){
                        $sql2 = "SELECT * FROM repair WHERE member_id = ".$_SESSION["member_id"];
                    }
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <?php
                            if(isset($from) && isset($to) && !isset($check)){
                            ?>
                            <a
                                href="repairReport.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="repairReport.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a
                                href="repairReport.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else{
                            ?>
                            <a href="repairReport.php?page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="repairReport.php?page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="repairReport.php?page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                    }else{
                        echo "<div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/repairReport.js"></script>
    <script>
    google.charts.load("current", {packages: ["corechart"]});
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            <?php
            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            ?>
            ['วัน / เดือน / ปี', 'กำไรทั้งหมด (บาท)'],
            <?php
            }else if($_SESSION["level"] == "guest"){
            ?>
            ['วัน / เดือน / ปี', 'ค่าใช้จ่ายทั้งหมด (บาท)'],
            <?php } ?>
            <?php echo $datax;?>
        ]);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);
        var options = {
            title: 'ค่าใช้จ่ายจากการแจ้งซ่อม',
            colors: ['rgb(131, 120, 47)'],
            fontName: "Sarabun",
            vAxis: { format: "decimal"},
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