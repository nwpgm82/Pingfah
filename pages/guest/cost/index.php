<?php
session_start();
if($_SESSION['level'] == 'guest'){
    include('../../connection.php');
    include('../../../components/sidebarGuest.php');
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $check = @$_REQUEST['Status'];
    $num = 1;
    function DateThai($strDate){
    	$strYear = date("Y",strtotime($strDate))+543;
    	$strMonth= date("n",strtotime($strDate));
    	$strDay= date("j",strtotime($strDate));
    	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    	$strMonthThai=$strMonthCut[$strMonth];
    	return "$strMonthThai $strYear";
    }
    if(isset($from) && isset($to)){
        $query = "SELECT date ,SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as total_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระ' THEN total ELSE 0 END) as untotal_price FROM cost WHERE (date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' GROUP BY date";
        $total_cost = mysqli_query($conn,"SELECT SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as total_cost, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระ' THEN total ELSE 0 END) as untotal_cost FROM cost WHERE (date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' ");
        $totalresult = mysqli_fetch_assoc($total_cost);
    }else{
        $query = "SELECT date ,SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as total_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระ' THEN total ELSE 0 END) as untotal_price FROM cost WHERE room_id = '".$_SESSION['ID']."' GROUP BY date";
        $total_cost = mysqli_query($conn,"SELECT SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as total_cost, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระ' THEN total ELSE 0 END) as untotal_cost FROM cost WHERE room_id = '".$_SESSION['ID']."' ");
        $totalresult = mysqli_fetch_assoc($total_cost);
    }
    $result = mysqli_query($conn, $query);
    $datax = array();
    foreach ($result as $k) {
        $datax[] = "['".DateThai($k['date'])."'".", ".$k['total_price'] ."," .$k['untotal_price'] ."]";
    }
    $datax = implode(",", $datax);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/cost.css">
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
            <div class="cost-box">
                <h3>ค้นหารายการชำระเงินห้องพักรายเดือน</h3>
                <div style="padding-top:32px;display:flex;justify-content:space-between;align-items:center;">
                    <div style="display:flex;align-items:center;">
                        <label>ค้นหาตามเดือน</label>
                        <div style="position:relative;">
                            <input type="text" id="date_from" value="<?php echo $from; ?>">
                            <p id="from_date" class="dateText"></p>
                        </div>
                        <label>~</label>
                        <div style="position:relative;">
                            <input type="text" id="date_to" value="<?php echo $to; ?>">
                            <p id="to_date" class="dateText"></p>
                            <button type="button" style="margin:0 8px;" onclick="searchDate()">ค้นหา</button>
                        </div>
                        <?php
                        if(isset($from) || isset($to) || isset($check)){
                        ?>
                        <button style="margin:0 8px;" onclick="unCheckAll()" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="hr"></div>
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
                    <div style="padding-top:32px;">
                        <div style="line-height:40px;">
                            <p>ชำระเงินแล้ว : <?php echo number_format($totalresult["total_cost"]); ?> บาท</p>
                            <p>ยังไม่ได้ชำระเงิน : <?php echo number_format($totalresult["untotal_cost"]); ?> บาท
                            </p>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <h3>รายการชำระเงินห้องพักรายเดือนทั้งหมด</h3>
                    <div style="display:flex;align-items:center;">
                        <div style="padding:32px 16px 32px 0px;display: flex;align-items: center;">
                            <input type="checkbox" id="all"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(!isset($check)){ echo "checked";} ?>>
                            <label for="scales">ทั้งหมด</label>
                        </div>
                        <div style="padding:32px 16px;display: flex;align-items: center;">
                            <input type="checkbox" id="success"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                            <label for="scales">ชำระเงินแล้ว</label>
                        </div>
                        <div style="padding:32px 16px;display: flex;align-items: center;">
                            <input type="checkbox" id="unsuccess"
                                onchange="<?php if(isset($from) && isset($to)){ echo "searchCheck2('$from','$to',this.id)"; }else{ echo "searchCheck(this.id)"; } ?>"
                                <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                            <label for="scales">ยังไม่ได้ชำระ</label>
                        </div>
                    </div>
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
                            $sql ="SELECT * FROM cost WHERE (date BETWEEN '$from' AND '$to') AND room_id = '".$_SESSION['ID']."' ORDER BY date LIMIT {$start} , {$perpage}";
                        }else if(!isset($from) && !isset($to) && isset($check)){
                            if($check == "success"){
                                $check = "ชำระเงินแล้ว";
                            }else if($check == "unsuccess"){
                                $check = "ยังไม่ได้ชำระ";
                            }
                            $sql = "SELECT * FROM cost WHERE cost_status = '$check' AND room_id = '".$_SESSION['ID']."' ORDER BY date LIMIT {$start} , {$perpage}";
                        }else if(isset($from) && isset($to) && isset($check)){
                            if($check == "success"){
                                $check = "ชำระเงินแล้ว";
                            }else if($check == "unsuccess"){
                                $check = "ยังไม่ได้ชำระ";
                            }
                            $sql = "SELECT * FROM cost WHERE (date BETWEEN '$from' AND '$to') AND cost_status = '$check' AND room_id = '".$_SESSION['ID']."' ORDER BY date LIMIT {$start} , {$perpage}";
                        }else{
                            $sql = "SELECT * FROM cost WHERE room_id = '".$_SESSION['ID']."' ORDER BY date LIMIT {$start} , {$perpage} ";
                        }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        ?>
                    <table>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขห้อง</th>
                            <!-- <th>ประเภท</th> -->
                            <th>ประจำเดือน</th>
                            <th>ค่าห้อง</th>
                            <th>ค่าน้ำ</th>
                            <th>ค่าไฟ</th>
                            <th>ค่าเคเบิล</th>
                            <th>ค่าปรับ</th>
                            <th>ราคารวม</th>
                            <th>สถานะการจ่ายเงิน</th>
                        </tr>
                        <?php while($row = $result->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $num; ?></td>
                            <td><?php echo $row["room_id"];?></td>
                            <!-- <td><?php echo $row["type"]?></td> -->
                            <td><?php echo DateThai($row["date"]);?></td>
                            <td><?php echo number_format($row["room_cost"]);?></td>
                            <td><?php echo number_format($row["water_bill"]);?></td>
                            <td><?php echo number_format($row["elec_bill"]);?></td>
                            <td><?php echo number_format($row["cable_charge"]);?></td>
                            <td><?php echo number_format($row["fines"]);?></td>
                            <td><?php echo number_format($row["total"]);?></td>
                            <?php
                            if($row['cost_status'] == 'ชำระเงินแล้ว'){
                            ?>
                            <td>
                                <div class="status-success">
                                    <p><?php echo $row["cost_status"]?></p>
                                </div>
                            </td>
                            <?php
                            }else{
                            ?>
                            <td>
                                <div class="status-nosuccess">
                                    <p><?php echo $row["cost_status"]?></p>
                                </div>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php $num++; } ?>
                    </table>
                    <?php
                        ///////pagination
                        if(isset($date) && !isset($check)){
                            $sql2 ="SELECT * FROM cost WHERE date = '$date'";
                        }else if(!isset($date) && isset($check)){
                            $sql2 = "SELECT * FROM cost WHERE cost_status = '$check'";
                        }else if(isset($date) && isset($check)){
                            $sql2 = "SELECT * FROM cost WHERE date = '$date' AND cost_status = '$check'";   
                        }else{
                            $sql2 = "SELECT * FROM cost";
                        }
                        $query2 = mysqli_query($conn, $sql2);
                        $total_record = mysqli_num_rows($query2);
                        $total_page = ceil($total_record / $perpage);
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
                        }else{
                            echo "<div style='margin:32px 0'>ไม่มีรายการชำระเงินรายเดือน</div>";
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/employee/costDate.js"></script>
    <script>
    google.charts.load('current', {
        'packages': ['bar']
    });
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['เดือน / ปี', 'ชำระเงินแล้ว', 'ยังไม่ได้ชำระ'],
            <?php echo $datax;?>
        ]);
        var options = {
            title: 'รายการชำระเงินห้องพักรายเดือน',
            colors: ['rgb(131, 120, 47)', '#c6b66b'],
            fontName: "Sarabun",
            vAxis: { format: "decimal"}
        };
        var chart = new google.charts.Bar(document.getElementById('columnchart_material1'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>