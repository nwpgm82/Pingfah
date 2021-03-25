<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    $check_in = @$_REQUEST['check_in'];
    $check_out = @$_REQUEST['check_out'];
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
    if($check_in != "" && $check_out != ""){
        $query = "SELECT check_in, SUM(people) as total_people FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง' GROUP BY check_in";
        $query = "SELECT * FROM (SELECT check_in, SUM(people) as total_people FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง' GROUP BY check_in ORDER BY check_in DESC LIMIT 5) sub ORDER BY check_in ASC";
        $query2 = "SELECT SUM(people) as total_people, SUM(air_room) as total_air, SUM(fan_room) as total_fan FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status != 'ยกเลิกการจอง'";
    }else{
        // $query = "SELECT check_in, SUM(people) as total_people FROM daily WHERE daily_status != 'ยกเลิกการจอง' GROUP BY check_in";
        $query = "SELECT * FROM (SELECT check_in, SUM(people) as total_people FROM daily WHERE daily_status != 'ยกเลิกการจอง' GROUP BY check_in ORDER BY check_in DESC LIMIT 5) sub ORDER BY check_in ASC";
        $query2 = "SELECT SUM(people) as total_people, SUM(air_room) as total_air, SUM(fan_room) as total_fan FROM daily WHERE daily_status != 'ยกเลิกการจอง'";
    }
    $query_result = mysqli_query($conn, $query);
    $query2_result = mysqli_query($conn, $query2);
    $q2 = mysqli_fetch_assoc($query2_result);
    $datax = array();
    foreach ($query_result as $k) {
        $datax[] = "['".DateThai($k['check_in'])."'".",".$k['total_people']."]";
    }
    $datax = implode(",", $datax);
    // echo $datax;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/daily.css">
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
            <div class="daily-box">
                <h3>ค้นหารายการเช่ารายวัน</h3>
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
                    if(isset($check_in) || isset($check_out) || isset($check) || isset($code)){
                    ?>
                    <div class="cancel-box">
                        <a href="index.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="hr" style="margin-top:16px;"></div>
                <div>
                    <div class="card">
                        <div class="sub-grid">
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
                    </div>
                    <div style="padding-top:32px;">
                        <div style="line-height:40px;">
                            <h3 style="color: rgb(131, 120, 47, 0.7);">ยอดรวมผู้เข้าพักทั้งหมด : <?php echo $q2["total_people"]; ?> ท่าน</h3>
                            <h3 style="color: darkturquoise;">ยอดรวมผู้เข้าพักห้องแอร์ : <?php echo $q2["total_air"] ?> ห้อง</h3>
                            <h3 style="color: #B1D78A;">ยอดรวมผู้เข้าพักห้องพัดลม : <?php echo $q2["total_fan"]; ?> ห้อง</h3>
                        </div>   
                    </div>
                    <div class="hr"></div>
                    <div>
                        <?php
                    $perpage = 10;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    $num = $start + 1;
                    if(isset($check_in) && isset($check_out) && !isset($check)){
                        $sql = "SELECT * FROM daily WHERE (check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out ) ORDER BY daily_id DESC LIMIT {$start} , {$perpage}";
                    }else if(!isset($check_in) && !isset($check_out) && isset($check)){
                        if($check == "come"){
                            $check_s = "เข้าพักแล้ว";
                        }else if($check == "checkout"){
                            $check_s = "เช็คเอาท์แล้ว";
                        }else if($check == "pending"){
                            $check_s = "รอการเข้าพัก";
                        }else if($check == "waiting"){
                            $check_s = "รอการยืนยัน";
                        }else if($check == "cancel"){
                            $check_s = "ยกเลิกการจอง";
                        }
                        $sql = "SELECT * FROM daily WHERE daily_status = '$check_s' ORDER BY daily_id DESC LIMIT {$start} , {$perpage}";
                    }else if(isset($check_in) && isset($check_out) && isset($check)){
                        if($check == "come"){
                            $check_s = "เข้าพักแล้ว";
                        }else if($check == "checkout"){
                            $check_s = "เช็คเอาท์แล้ว";
                        }else if($check == "pending"){
                            $check_s = "รอการเข้าพัก";
                        }else if($check == "waiting"){
                            $check_s = "รอการยืนยัน";
                        }else if($check == "cancel"){
                            $check_s = "ยกเลิกการจอง";
                        }
                        $sql = "SELECT * FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status = '$check_s' ORDER BY daily_id DESC LIMIT {$start} , {$perpage}"; 
                    }else if(isset($code)){
                        $sql = "SELECT * FROM daily WHERE code = '$code' ORDER BY daily_id DESC";
                    }else{
                        $sql = "SELECT * FROM daily ORDER BY daily_id DESC LIMIT {$start} , {$perpage}";
                    }
                    $result = $conn->query($sql);
                    if(isset($code)){
                        echo "<h3 style='padding-bottom:32px;'>ผลลัพธ์การค้นหา : $code</h3>";
                    }else{
                        echo "<h3>รายการเช่าห้องพักทั้งหมด</h3>";
                    }
                    ?>
                    <?php
                    if(!isset($code)){
                    ?>
                        <div class="checkbox-grid">
                            <div style="padding:32px 16px 32px 0;display: flex;align-items: center;">
                                <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked";} ?>>
                                <label for="scales">ทั้งหมด</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="waiting"
                                    <?php if(isset($check)){ if($check == "waiting"){ echo "checked";}} ?>>
                                <label for="scales">รอการยืนยัน</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="pending"
                                    <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                                <label for="scales">รอการเข้าพัก</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="come"
                                    <?php if(isset($check)){ if($check == "come"){ echo "checked";}} ?>>
                                <label for="scales">เข้าพักแล้ว</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="checkout"
                                    <?php if(isset($check)){ if($check == "checkout"){ echo "checked";}} ?>>
                                <label for="scales">เช็คเอาท์แล้ว</label>
                            </div>
                            <div style="padding:32px 16px;display: flex;align-items: center;">
                                <input type="checkbox" id="cancel"
                                    <?php if(isset($check)){ if($check == "cancel"){ echo "checked";}} ?>>
                                <label for="scales">ยกเลิกการจอง</label>
                            </div>
                        </div>
                        <?php } ?>
                        <?php
                        if ($result->num_rows > 0) {
                        ?>
                        <div style="overflow-x: auto;overflow-y: hidden;">
                            <table>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อผู้เช่า</th>
                                    <th>วันที่เข้าพัก</th>
                                    <!-- <th>จำนวนผู้พัก (ท่าน)</th>
                                    <th>ห้องแอร์ (ห้อง)</th>
                                    <th>ห้องพัดลม (ห้อง)</th> -->
                                    <th>เลขที่การจอง</th>
                                    <th>สถานะ</th>
                                    <th>เพิ่มเติม</th>
                                </tr>
                                <?php
                                while($row = $result->fetch_assoc()) {
                                ?>
                                <form action="function/acceptRent.php?daily_id=<?php echo $row["daily_id"]; ?>" method="POST">
                                    <tr>
                                        <td><?php echo $num; ?></td>
                                        <td><?php echo $row['name_title'].$row['firstname'] ." " .$row['lastname']; ?></td>
                                        <td><?php echo DateThai($row['check_in']) ."&nbsp; ~ &nbsp;" .DateThai($row['check_out']) ." (".$row['night']." คืน)"?></td>
                                        <td><?php echo $row['code']; ?></td>
                                        <td><?php if($row['daily_status'] == 'เข้าพักแล้ว'){ echo "<button type='button' class='confirmed-btn'>เข้าพักแล้ว</button>"; }else if($row['daily_status'] == "เช็คเอาท์แล้ว"){ echo "<button type='button' class='checkoutStatus-btn'>เช็คเอาท์แล้ว</button>"; }else if($row['daily_status'] == "ยกเลิกการจอง"){ echo "<button type='button' class='canceldaily-btn'>ยกเลิกการจอง</button>"; }else if($row['daily_status'] == "รอการเข้าพัก"){ echo "<button type='button' class='pending-btn'>รอการเข้าพัก</button>"; }else{ echo "<button type='button' class='waiting-btn'>รอการยืนยัน</button>";} ?></td>
                                        <td>
                                            <div class="option-grid">
                                                <?php
                                                if($row['daily_status'] == 'รอการยืนยัน'){
                                                ?>
                                                    <button type="submit" class="acceptRent-btn">ยืนยัน</button>
                                                
                                                <?php
                                                }else if($row['daily_status'] == 'รอการเข้าพัก'){
                                                ?>
                                                    <button type="button" class="select_room" onclick="window.open('selectroom.php?daily_id=<?php echo $row['daily_id']; ?>','_blank')">เลือกห้อง</button>
                                                <?php 
                                                }else if($row['daily_status'] == 'เข้าพักแล้ว'){
                                                ?>
                                                    <button type="button" class="checkout-btn" onclick="window.open('confirm_checkout.php?daily_id=<?php echo $row['daily_id']; ?>','_blank')">เช็คเอาท์</button>
                                                <?php 
                                                }
                                                ?>
                                                <?php
                                                if($row["daily_status"] == "รอการเข้าพัก"){
                                                ?>
                                                <a href="../../images/daily/<?php echo $row["code"]; ?>/promptpay/qr-code.png" target="_blank"><button type="button" class="qr" title="QR Code สำหรับชำระค่ามัดจำคีย์การ์ด"></button></a>
                                                <?php
                                                }else{
                                                ?>
                                                <button class="qr" disabled></button>
                                                <?php } ?>
                                                <a href="dailyDetail.php?daily_id=<?php echo $row['daily_id']; ?>"><button type="button">รายละเอียด</button></a>
                                                <?php
                                                if($_SESSION["level"] == "admin"){
                                                ?>
                                                <button type="button" class="del-btn" id="<?php echo $row['daily_id']; ?>"></button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                                <?php $num++; } ?>
                            </table>
                        </div>
                        <?php
                        ///////pagination
                        if(isset($check_in) && isset($check_out) && !isset($check)){
                            $sql2 ="SELECT * FROM daily WHERE (check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )";
                        }else if(!isset($check_in) && !isset($check_out) && isset($check)){
                            $sql2 = "SELECT * FROM daily WHERE daily_status = '$check_s'";
                        }else if(isset($check_in) && isset($check_out) && isset($check)){
                            $sql2 = "SELECT * FROM daily WHERE ((check_in BETWEEN '$check_in' AND '$check_out') OR (check_out BETWEEN '$check_in' AND '$check_out') OR ('$check_in' BETWEEN check_in AND check_out) OR ('$check_out' BETWEEN check_in AND check_out )) AND daily_status = '$check_s'";   
                        }else{
                            $sql2 = "SELECT * FROM daily";
                        }
                        $query2 = mysqli_query($conn, $sql2);
                        $total_record = mysqli_num_rows($query2);
                        $total_page = ceil($total_record / $perpage);
                        ?>
                        <div style="display:flex;justify-content:flex-end">
                            <div class="pagination">
                                <?php
                                if(isset($check_in) && isset($check_out) && !isset($check)){
                                ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php
                                }else if(!isset($check_in) && !isset($check_out) && isset($check)){
                                ?>
                                <a href="index.php?status=<?php echo $check; ?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="index.php?status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                    <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a href="index.php?status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                                <?php
                                }else if(isset($check_in) && isset($check_out) && isset($check)){
                                ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&status=<?php echo $check; ?>&page=1">&laquo;</a>
                                <?php for($i=1;$i<=$total_page;$i++){ ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                                <?php } ?>
                                <a href="index.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out;?>&status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
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
                        }else{
                            echo "<div style='padding-top:32px;'>ไม่มีรายการเช่ารายวัน</div>";
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="../../../js/manage/daily.js"></script>
    <script>
    google.charts.load("current", {packages: ["corechart"]});
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>
    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['วัน / เดือน / ปี', 'จำนวนผู้พัก (ท่าน)'],
            <?php echo $datax;?>
        ]);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);

        var options = {
            title: 'รายการชำระเงินห้องพักรายวัน',
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