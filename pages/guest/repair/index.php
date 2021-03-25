<?php
session_start();
if($_SESSION['level'] == 'guest'){
    include('../../connection.php');
    include('../../../components/sidebarGuest.php');
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $check = @$_REQUEST['Status'];
    $num = 1;
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if(isset($from) && isset($to)){
        $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' GROUP BY repair_category";
        $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' GROUP BY repair_status";
    }else{
        $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair WHERE room_id = '".$_SESSION['ID']."' GROUP BY repair_category";
        $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair WHERE room_id = '".$_SESSION['ID']."' GROUP BY repair_status";
    }
   
    $result = mysqli_query($conn, $query_data);
    $result2 = mysqli_query($conn, $query_data2);
    $datax = array();
    $datax2 = array();
    foreach ($result as $k) {
        $datax[] = "['".$k['repair_category']."'".", ".$k['total_cate']."]";
    }
    foreach ($result2 as $l) {
        $datax2[] = "['".$l['repair_status']."'".", ".$l['total_cate_status']."]";
    }
    $datax = implode(",", $datax);
    $datax2 = implode(",", $datax2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/repair.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="repair-box">
                <h3>ค้นหารายการแจ้งซ่อม</h3>
                <div style="padding-top:32px;display:flex;justify-content:space-between;align-items:center;">
                    <div style="display:flex;align-items:center;">
                        <label>ค้นหาตามวันที่</label>
                        <div style="position:relative;">
                            <input type="text" id="date_from" class="roundtrip-input" value="<?php echo $from; ?>">
                            <p id="from_date" class="dateText"></p>
                        </div>
                        <label>~</label>
                        <div style="position:relative;">
                            <input type="text" id="date_to" class="roundtrip-input" value="<?php echo $to; ?>">
                            <p id="to_date" class="dateText"></p>
                            <button type="button" style="margin:0 8px;" onclick="searchDate()">ค้นหา</button>
                        </div>
                        <?php
                        if(isset($from) || isset($to) || isset($check)){
                        ?>
                        <button class="cancel-sort" style="margin:0 8px;" onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                        <?php } ?>
                    </div>
                    <a href="addRepair.php"><button>เพิ่มรายการแจ้งซ่อม</button></a>
                </div>
                <div class="hr"></div>
                <div>
                    <div class="card">
                        <div class="sub-grid">
                            <?php
                            if(strlen($datax) != 0){
                            ?>
                            <div id="piechart" class="chart"></div>
                            <?php 
                            }else{
                                 echo "<p style='margin:auto;'>ไม่มีข้อมูล</p>";
                            } 
                            ?>
                            <?php
                            if(strlen($datax2) != 0){
                            ?>
                            <div id="piechart2" class="chart"></div>
                            <?php 
                            }else{
                                 echo "<p style='margin:auto;'>ไม่มีข้อมูล</p>";
                            } 
                            ?>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <h3>รายการแจ้งซ่อมทั้งหมด</h3>
                    <div style="display:flex;align-items:center;">
                        <div style="padding:32px 16px 32px 0;display:flex;">
                            <input type="checkbox" id="all"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(!isset($check)){ echo "checked"; } ?>>
                            <label for="scales">ทั้งหมด</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="success"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                            <label for="scales">ซ่อมเสร็จแล้ว</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="inprogress"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                            <label for="scales">กำลังดำเนินการ</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="pending"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                            <label for="scales">รอดำเนินการ</label>
                        </div>

                    </div>
                    <?php
                    $perpage = 5;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    $num = $start + 1;
                    if(isset($from) && isset($to) && !isset($check)){
                        $sql = "SELECT * FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' ORDER BY repair_date";
                    }else if(!isset($from) && !isset($to) && isset($check)){
                        if($check == "success"){
                            $check = "ซ่อมเสร็จแล้ว";
                        }else if($check == "inprogress"){
                            $check = "กำลังดำเนินการ";
                        }else if($check == "pending"){
                            $check = "รอดำเนินการ";
                        }
                        $sql = "SELECT * FROM repair WHERE repair_status = '$check' AND room_id = '".$_SESSION['ID']."' ORDER BY repair_date LIMIT {$start} , {$perpage}";
                    }else if(isset($from) && isset($to) && isset($check)){
                        if($check == "success"){
                            $check = "ซ่อมเสร็จแล้ว";
                        }else if($check == "inprogress"){
                            $check = "กำลังดำเนินการ";
                        }else if($check == "pending"){
                            $check = "รอดำเนินการ";
                        }
                        $sql = "SELECT * FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND repair_status = '$check' AND room_id = '".$_SESSION['ID']."' ORDER BY repair_date LIMIT {$start} , {$perpage}";   
                    }else{
                        $sql = "SELECT * FROM repair WHERE room_id = '".$_SESSION['ID']."' ORDER BY repair_date LIMIT {$start} , {$perpage} ";
                    }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                ?>
                    <table>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขห้อง</th>
                            <th>อุปกรณ์</th>
                            <th>หมวดหมู่</th>
                            <th>เวลาที่ลง</th>
                            <th>สถานะ</th>
                            <th>เพิ่มเติม</th>
                        </tr>
                        <?php while($row = $result->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <td><?php echo $row['room_id']; ?></td>
                            <td><?php echo $row['repair_appliance']; ?></td>
                            <td><?php echo $row['repair_category']; ?></td>
                            <td><?php echo DateThai($row['repair_date']); ?></td>
                            <td>
                                <?php
                                if($row['repair_status'] == 'รอดำเนินการ'){
                            ?>
                                <div class="pending-status">
                                    <p><?php echo $row['repair_status']; ?></p>
                                </div>
                                <?php
                                }else if($row['repair_status'] == 'กำลังดำเนินการ'){
                            ?>
                                <div class="inprogress-status">
                                    <p><?php echo $row['repair_status']; ?></p>
                                </div>
                                <?php
                                }else if($row['repair_status'] == 'ซ่อมเสร็จแล้ว'){
                            ?>
                                <div class="success-status">
                                    <p><?php echo $row['repair_status']; ?></p>
                                </div>
                                <?php
                                }else{
                                    echo "error!";
                                }
                            ?>
                            </td>
                            <td class="flex-more">
                                <div>
                                    <a href="repairDetail.php?repair_id=<?php echo $row['repair_id'];?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                                </div>
                                <div>
                                    <button class="del-btn" onclick="repair_del(<?php echo $row['repair_id']; ?>)">ลบ</button>
                                </div>
                            </td>
                        </tr>
                        <?php $num++; } ?>
                    </table>
                    <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM repair";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <?php
                        if(isset($from) && isset($to) && !isset($check)){
                        ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a
                                href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                        }else if(!isset($from) && !isset($to) && isset($check)){
                        ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                        }else if(isset($from) && isset($to) && isset($check)){
                        ?>
                            <a
                                href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&Status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&Status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a
                                href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&Status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
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
                    echo "<div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>";
                }
                ?>
                </div>

            </div>
        </div>
    </div>
    <script src="../../../js/guest/repair.js"></script>
    <script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['ประเภท', 'รายการ'],
            <?php echo $datax;?>
        ]);
        var options = {
            title: 'รายการแจ้งซ่อมแยกตามประเภท',
            is3D: true,
            fontName: "Sarabun",
            slices: {
                0: { color: 'rgb(131, 120, 47)' },
                1: { color: '#c6b66b' },
                2: { color: '#e5d170'}
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }

    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['ประเภท', 'รายการ'],
            <?php echo $datax2;?>
        ]);
        var options = {
            title: 'รายการแจ้งซ่อมแยกตามสถานะ',
            is3D: true,
            fontName: "Sarabun",
            slices: {
                0: { color: 'rgb(131, 120, 47)' },
                1: { color: '#c6b66b' },
                2: { color: '#e5d170'}
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart.draw(data, options);
    }
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>