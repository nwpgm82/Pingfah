<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){
    include('../../connection.php');
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $code = @$_REQUEST['code'];
    $check = @$_REQUEST['status'];
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
            // $query = "SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package WHERE (package_arrived BETWEEN '$from' AND '$to') GROUP BY package_arrived";
            $query = "SELECT * FROM (SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package WHERE (package_arrived BETWEEN '$from' AND '$to') GROUP BY package_arrived ORDER BY package_arrived DESC LIMIT 5) sub ORDER BY package_arrived ASC";
        }else if($_SESSION["level"] == "guest"){
            $query = "SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package WHERE member_id = ".$_SESSION["member_id"]." AND (package_arrived BETWEEN '$from' AND '$to') GROUP BY package_arrived";
        }
    }else{
        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            // $query = "SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package GROUP BY package_arrived";
            $query = "SELECT * FROM (SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package GROUP BY package_arrived ORDER BY package_arrived DESC LIMIT 5) sub ORDER BY package_arrived ASC";
        }else if($_SESSION["level"] == "guest"){
            $query = "SELECT package_arrived ,SUM(case WHEN package_status = 'รับพัสดุแล้ว' THEN 1 ELSE 0 END) as total_package, SUM(case WHEN package_status = 'ยังไม่ได้รับพัสดุ' THEN 1 ELSE 0 END) as untotal_package FROM package WHERE member_id = ".$_SESSION["member_id"]." GROUP BY package_arrived";
        }
    }
    // $query = "SELECT COUNT(*) as total_package FROM package WHERE package_status = 'ยังไม่ได้รับพัสดุ' ORDER BY package_arrived";
    $result = mysqli_query($conn, $query);
    $datax = array();
    foreach ($result as $k) {
        $datax[] = "['".DateThai($k['package_arrived'])."'".", ".$k['total_package'] ."," .$k['untotal_package'] ."]";
    }
    $datax = implode(",", $datax);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/package.css">
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
            <div class="package-box">
                <h3>ค้นหารายการพัสดุ</h3>
                <div class="search">
                    <div class="sub-search">
                        <div id="first-sub" class="sub-searchsub">
                            <label class="search_topic" style="padding:10px 8px 0 0;">ค้นหาตามวันที่</label>
                            <div class="from_box" style="position:relative;">
                                <input type="text" class="roundtrip-input" id="date_from" value="<?php if(isset($from)){ echo DateThai($from); } ?>">
                                <h5 id="from_error" style="color:red;"></h5>
                            </div>
                            <label class="to_text" style="padding:10px 8px 0 8px;">~</label>
                            <div class="to_box" style="position:relative;">
                                <input type="text" class="roundtrip-input" id="date_to" value="<?php if(isset($to)){ echo DateThai($to); } ?>">
                                <h5 id="to_error" style="color:red;"></h5>
                            </div>
                            <button class="search_btn" type="button" id="searchDate" style="margin:0 16px;">ค้นหา</button>
                        </div>
                        <div id="second-sub" class="sub-searchsub">
                            <label style="padding:10px 8px 0 0;">ค้นหาเลขพัสดุ</label>
                            <div>
                                <input type="text" class="searchPackage" id="code">
                                <h5 id="code_error" style="color:red"></h5>
                            </div>
                            <button style="margin:0 16px;" id="searchCode">ค้นหาเลขพัสดุ</button> 
                        </div>
                        <?php
                        if(isset($from) || isset($to) || isset($code) || isset($check)){
                        ?>
                        <div id="cancel_box">
                            <a href="index.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                        </div>
                        <?php } ?>
                    </div>
                    <?php
                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                    ?>
                    <a id="addPackage" href="addPackage.php"><button>เพิ่มรายการพัสดุ</button></a>    
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
                    <?php
                    if(!isset($code)){
                    ?>
                    <h3>รายการพัสดุทั้งหมด</h3>
                    <div style="display:flex;align-items:center;">
                        <div style="padding:32px 16px 32px 0;">
                            <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked"; } ?>>
                            <label for="scales">ทั้งหมด</label>
                        </div>
                        <div style="padding:32px 16px;">
                            <input type="checkbox" id="success" <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                            <label for="scales">รับพัสดุแล้ว</label>
                        </div>
                        <div style="padding:32px 16px;">
                            <input type="checkbox" id="unsuccess" <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                            <label for="scales">ยังไม่ได้รับพัสดุ</label>
                        </div>
                    </div>
                    <?php 
                    }else{
                    ?>
                    <h3 style="padding-bottom:32px;">ผลการค้นหาเลขพัสดุ "<?php echo $code; ?>"</h3>
                    <?php
                    }
                    ?>
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
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM package WHERE (package_arrived BETWEEN '$from' AND '$to') ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND (package_arrived BETWEEN '$from' AND '$to') ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";
                        }
                    }else if(!isset($from) && !isset($to) && isset($check)){
                        if($check == "success"){
                            $check = "รับพัสดุแล้ว";
                        }else if($check == "unsuccess"){
                            $check = "ยังไม่ได้รับพัสดุ";
                        }
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM package WHERE package_status = '$check' ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND package_status = '$check' ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";
                        }
                    }else if(isset($from) && isset($to) && isset($check)){
                        if($check == "success"){
                            $check = "รับพัสดุแล้ว";
                        }else if($check == "unsuccess"){
                            $check = "ยังไม่ได้รับพัสดุ";
                        }
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM package WHERE (package_arrived BETWEEN '$from' AND '$to') AND package_status = '$check' ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";   
                        }else if($_SESSION["level"] == "guest"){    
                            $sql = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND (package_arrived BETWEEN '$from' AND '$to') AND package_status = '$check' ORDER BY package_arrived DESC LIMIT {$start} , {$perpage}";   
                        }
                    }else if(isset($code)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM package WHERE package_num = '$code' LIMIT {$start} , {$perpage}";
                        }else if($_SESSION["level"] == "guest"){ 
                            $sql = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND package_num = '$code' LIMIT {$start} , {$perpage}";   
                        } 
                    }else{
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM package ORDER BY package_arrived DESC LIMIT {$start} , {$perpage} ";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." ORDER BY package_arrived DESC LIMIT {$start} , {$perpage} ";
                        }
                    }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                    ?>
                    <div style="overflow-x:auto;overflow-y:hidden;">
                        <table>
                            <tr>
                                <th>ลำดับ</th>
                                <th>เลขพัสดุ</th>
                                <th>บริษัทขนส่ง</th>
                                <th>วันที่พัสดุมาถึง</th>
                                <th>วันที่รับพัสดุ</th>
                                <th>เจ้าของพัสดุ</th>
                                <th>เลขห้อง</th>
                                <th>สถานะ</th>
                                <th>ชื่อผู้รับพัสดุ</th>
                                <?php
                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                ?>
                                <th>เพิ่มเติม</th>
                                <?php } ?>
                            </tr>
                            <?php
                            while($row = $result->fetch_assoc()) {
                            ?>
                            <form action="../package/function/receivedPackage.php?package_id=<?php echo $row["package_id"]; ?>"
                                onsubmit="return confirm('คุณต้องการยืนยันการรับพัสดุใช่หรือไม่ ? ');" method="POST">
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $row["package_num"]; ?></td>
                                    <td><?php echo $row["package_company"]; ?></td>
                                    <td><?php echo DateThai($row["package_arrived"]); ?></td>
                                    <td><?php if(isset($row["package_receiveddate"])){ echo DateThai($row["package_receiveddate"]); } ?>
                                    </td>
                                    <td><?php echo $row["package_name"]; ?></td>
                                    <td><?php echo $row["package_room"]; ?></td>
                                    <td style="width:200px">
                                        <?php 
                                            if($row["package_status"] == 'รับพัสดุแล้ว'){
                                                echo "<div class='status_received'>";
                                                echo "<p>" .$row["package_status"] ."</p>";
                                                echo "</div>";
                                            }else{
                                                echo "<div class='status_pending'>";
                                                echo "<p>" .$row["package_status"] ."</p>";
                                                echo "</div>";
                                            }
                                        ?>
                                    </td>
                                    <td <?php if($_SESSION["level"] == "guest"){ echo "style='text-align:left;'"; } ?>>
                                        <?php
                                        if(!isset($row["package_received"])){
                                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                                echo "<input type='text' name='received' placeholder='ชื่อผู้รับ' required>";
                                            }
                                        }else{
                                            echo $row["package_received"];
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                    ?>
                                    <td>
                                        <div class="option">
                                            <?php
                                            if(!isset($row["package_received"])){
                                            ?>
                                            <button type="submit" class="received-btn" title="รับพัสดุ">รับพัสดุ</button>
                                            <a href="editPackage.php?package_id=<?php echo $row["package_id"]; ?>" title="แก้ไขพัสดุ"><button type="button" class="edit-btn"></button></a>
                                            <?php 
                                            }else{
                                            ?>
                                            <button type="button" class="package-received">รับพัสดุแล้ว</button>
                                            <button type="button" class="edit-btn" style="background-color:#ddd;cursor:default;"></button>
                                            <?php
                                            } 
                                            ?>
                                            <button type="button" class="del-btn" id="<?php echo $row["package_id"]; ?>" title="ลบพัสดุ"></button>
                                        </div>
                                    </td>
                                    <?php } ?>
                                </tr>
                            </form>
                            <?php $num++; } ?>
                        </table>
                    </div>
                    <?php
                    ///////pagination
                    if(isset($from) && isset($to) && !isset($check)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM package WHERE (package_arrived BETWEEN '$from' AND '$to')";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND (package_arrived BETWEEN '$from' AND '$to')";
                        }
                    }else if(!isset($from) && !isset($to) && isset($check)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM package WHERE package_status = '$check'";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND package_status = '$check'";
                        }
                    }else if(isset($from) && isset($to) && isset($check)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM package WHERE (package_arrived BETWEEN '$from' AND '$to') AND package_status = '$check'";
                        }else if($_SESSION["level"] == "guest"){  
                            $sql2 = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"]." AND (package_arrived BETWEEN '$from' AND '$to') AND package_status = '$check'";
                        }
                    }else{
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM package";
                        }else if($_SESSION["level"] == "guest"){ 
                            $sql2 = "SELECT * FROM package WHERE member_id = ".$_SESSION["member_id"];
                        }
                    }
                    
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    if(!isset($code)){
                    ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <?php
                            if(isset($date) && !isset($check)){
                            ?>
                            <a href="index.php?Date=<?php echo $date; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Date=<?php echo $date; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Date=<?php echo $date; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else if(!isset($date) && isset($check)){
                            ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else if(isset($date) && isset($check)){
                            ?>
                            <a
                                href="index.php?Date=<?php echo $date; ?>&Status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Date=<?php echo $date; ?>&Status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a
                                href="index.php?Date=<?php echo $date; ?>&Status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else{
                            ?>
                            <a href="index.php?page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                        }
                    }else{
                        echo "<div style='margin:32px 0'>ไม่มีรายการพัสดุ</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/package.js"></script>
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
            ['วัน / เดือน / ปี', 'รับพัสดุแล้ว (ชิ้น)', 'ยังไม่ได้รับพัสดุ (ชิ้น)'],
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
            title: 'รายการพัสดุ',
            colors: ['rgb(131, 120, 47)', '#a8a06d'],
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

        var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material1'));

        chart.draw(view, options);
        $(window).resize(function(){
            drawChart();
        })
    }
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>