<?php
session_start();
if($_SESSION["level"] == "admin"){
    include("../../connection.php");
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $from = @$_REQUEST["from"];
    $to = @$_REQUEST["to"];
    $check = @$_REQUEST["status"];
    if(isset($from) && isset($to)){
        // $query = "SELECT come_date, COALESCE(SUM(employee_status = 'กำลังทำงาน'),0) AS work_total, COALESCE(SUM(employee_status = 'ลาออก'),0) AS quit_total FROM employee WHERE come_date BETWEEN '$from' AND '$to' GROUP BY come_date ORDER BY come_date ASC";
        $query = "SELECT * FROM (SELECT come_date, COALESCE(SUM(employee_status = 'กำลังทำงาน'),0) AS work_total, COALESCE(SUM(employee_status = 'ลาออก'),0) AS quit_total FROM employee WHERE come_date BETWEEN '$from' AND '$to' GROUP BY come_date ORDER BY come_date DESC LIMIT 5) sub ORDER BY come_date ASC";
    }else{
        // $query = "SELECT come_date, COALESCE(SUM(employee_status = 'กำลังทำงาน'),0) AS work_total, COALESCE(SUM(employee_status = 'ลาออก'),0) AS quit_total FROM employee GROUP BY come_date ORDER BY come_date ASC";
        $query = "SELECT * FROM (SELECT come_date, COALESCE(SUM(employee_status = 'กำลังทำงาน'),0) AS work_total, COALESCE(SUM(employee_status = 'ลาออก'),0) AS quit_total FROM employee GROUP BY come_date ORDER BY come_date DESC LIMIT 5) sub ORDER BY come_date ASC";
    }
    $query_result = mysqli_query($conn, $query);
    $datax = array();
    foreach ($query_result as $k) {
        $datax[] = "['".DateThai($k['come_date'])."',".$k['work_total'].",".$k['quit_total']."]";
    }
    $datax = implode(",", $datax);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/history2.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/emHistory.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="history-box">
                <h3>ค้นประวัติการเข้าทำงานของพนักงาน</h3>
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
                    <button class="search-btn" type="button" id="searchHistory" style="margin-left:16px;">ค้นหา</button>
                    <?php
                    if(isset($from) || isset($to) || isset($check)){
                    ?>
                    <div class="cancel-box" style="padding:0 16px;">
                        <a href="emHistory.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="hr" style="margin-top:16px;"></div>
                <div>
                    <div class="card">
                        <?php
                        if(strlen($datax) != 0){
                        ?>
                        <div id="columnchart_material1" class="chart"></div>
                        <?php 
                        }else{
                            echo "<p style='margin:auto;'>ไม่มีข้อมูล</p>";
                        } 
                        ?>
                    </div>
                    <div class="hr"></div>
                    <div>
                        <h3>รายการประวัติการเข้าทำงานทั้งหมด</h3>
                        <?php
                        $perpage = 10;
                        if(isset($_GET['page'])){
                            $page = $_GET['page'];
                        }else{
                            $page = 1;
                        }
                        $start = ($page - 1) * $perpage;
                        $num = $start + 1;
                        if(isset($from) && isset($to) && !isset($check)){
                            $sql = "SELECT employee_id, position, come_date, out_date, employee_status, title_name, firstname, lastname FROM employee WHERE come_date BETWEEN '$from' AND '$to' ORDER BY come_date DESC";
                        }else if(!isset($from) && !isset($to) && isset($check)){
                            if($check == "work"){
                                $check_s = "กำลังทำงาน";
                            }else if($check == "quit"){
                                $check_s = "ลาออก";
                            }
                            $sql = "SELECT employee_id, position, come_date, out_date, employee_status, title_name, firstname, lastname FROM employee WHERE employee_status = '$check_s' ORDER BY come_date DESC";
                        }else if(isset($from) && isset($to) && isset($check)){
                            if($check == "work"){
                                $check_s = "กำลังทำงาน";
                            }else if($check == "quit"){
                                $check_s = "ลาออก";
                            }
                            $sql = "SELECT employee_id, position, come_date, out_date, employee_status, title_name, firstname, lastname FROM employee WHERE (come_date BETWEEN '$from' AND '$to') AND employee_status = '$check_s' ORDER BY come_date DESC";
                        }else{
                            $sql = "SELECT employee_id, position, come_date, out_date, employee_status, title_name, firstname, lastname FROM employee ORDER BY come_date DESC";
                        }
                        $result = $conn->query($sql);
                        ?>
                        <div style="display:flex;align-items:center;">
                            <div style="padding:32px 16px 32px 0;display: flex;align-items: center;">
                                <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked"; } ?>>
                                <label for="scales">ทั้งหมด</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="work_check" <?php if($check == "work"){ echo "checked"; } ?>>
                                <label for="scales">กำลังทำงาน</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="quit_check" <?php if($check == "quit"){ echo "checked"; } ?>>
                                <label for="scales">ลาออก</label>
                            </div>
                        </div>
                        <?php
                        if($result->num_rows > 0){
                        ?>
                        <div style="overflow-x: auto;overflow-y:hidden;">
                            <table>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อพนักงาน</th>
                                    <th>ตำแหน่ง</th>
                                    <th>วันที่เข้าทำงาน</th>
                                    <th>วันที่ลาออก</th>
                                    <th>สถานะ</th>
                                    <th>เพิ่มเติม</th>
                                </tr>
                                <?php
                                while($row = $result->fetch_assoc()){
                                ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $row["title_name"].$row["firstname"] ." " .$row["lastname"]; ?></td>
                                    <td><?php if($row["position"] == "employee"){ echo "พนักงาน"; }else{ echo $row["position"]; }?></td>
                                    <td><?php echo DateThai($row["come_date"]); ?></td>
                                    <td><?php if(isset($row["out_date"])){ echo DateThai($row["out_date"]); } ?></td>
                                    <td><?php echo $row["employee_status"]; ?></td>
                                    <td>
                                        <div class="option-grid">
                                            <a href="emDetail.php?employee_id=<?php echo $row["employee_id"];?>" title="ดูข้อมูลเพิ่มเติม"><button>ดูข้อมูลเพิ่มเติม</button></a>
                                            <button class="del-btn" id="<?php echo $row["employee_id"]; ?>" title="ลบข้อมูล"></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php $num++; } ?>
                            </table>
                        </div>
                        <?php
                         ///////pagination
                        $sql2 = "SELECT * FROM employee";
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
                                    href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $i; ?>"
                                    <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a
                                    href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php
                                }else if(!isset($from) && !isset($to) && isset($check)){
                                ?>
                                <a href="emHistory.php?status=<?php echo $check; ?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="emHistory.php?status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                    <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a
                                    href="emHistory.php?status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php
                                }else if(isset($from) && isset($to) && isset($check)){
                                ?>
                                <a
                                    href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                    <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a
                                    href="emHistory.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php
                                }else{
                                ?>
                                <a href="emHistory.php?page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="emHistory.php?page=<?php echo $i; ?>"
                                    <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a href="emHistory.php?page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        }else{
                            echo "<div style='padding-top:32px;'>ไม่มีรายการประวัติการเข้าทำงาน</div>"; 
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['วัน / เดือน / ปี', 'กำลังทำงาน', 'ลาออก'],
            <?php echo $datax;?>
        ]);
        var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                        2,
                        { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" }]);
        var options = {
            title: 'จำนวนการเข้าพักในแต่ละวัน',
            colors: ['rgb(131, 120, 47)', '#c6b66b'],
            fontName: "Sarabun",
            vAxis: {
                format: "decimal",
            },
            annotations:{
                alwaysOutside: true
            },
            width: "100%",
            height: "100%",
            bar: {groupWidth: "50%"},
            legend: { position: "bottom" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material1'));

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
    Header("Location : ../../login.php");
}
?>