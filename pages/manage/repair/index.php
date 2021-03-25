<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){
    include('../../connection.php');
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $check = @$_REQUEST['status'];
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
            $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') GROUP BY repair_category";
            $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') GROUP BY repair_status";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_category = 'เครื่องใช้ไฟฟ้า') as total_elec, SUM(repair_category = 'เฟอร์นิเจอร์') as total_fur, SUM(repair_category = 'สุขภัณฑ์') as total_suk FROM repair WHERE (repair_date BETWEEN '$from' AND '$to')");
            $query_detail2 = mysqli_query($conn, "SELECT SUM(repair_status = 'รอคิวซ่อม') as pending_s, SUM(repair_status = 'กำลังซ่อม') as inprogress_s, SUM(repair_status = 'ซ่อมเสร็จแล้ว') as success_s FROM repair WHERE (repair_date BETWEEN '$from' AND '$to')");
        }else if($_SESSION["level"] == "guest"){
            $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to') GROUP BY repair_category";
            $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to') GROUP BY repair_status";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_category = 'เครื่องใช้ไฟฟ้า') as total_elec, SUM(repair_category = 'เฟอร์นิเจอร์') as total_fur, SUM(repair_category = 'สุขภัณฑ์') as total_suk FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to')");
            $query_detail2 = mysqli_query($conn, "SELECT SUM(repair_status = 'รอคิวซ่อม') as pending_s, SUM(repair_status = 'กำลังซ่อม') as inprogress_s, SUM(repair_status = 'ซ่อมเสร็จแล้ว') as success_s FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to')");
        }
    }else{
        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
            $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair GROUP BY repair_category";
            $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair GROUP BY repair_status";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_category = 'เครื่องใช้ไฟฟ้า') as total_elec, SUM(repair_category = 'เฟอร์นิเจอร์') as total_fur, SUM(repair_category = 'สุขภัณฑ์') as total_suk FROM repair");
            $query_detail2 = mysqli_query($conn, "SELECT SUM(repair_status = 'รอคิวซ่อม') as pending_s, SUM(repair_status = 'กำลังซ่อม') as inprogress_s, SUM(repair_status = 'ซ่อมเสร็จแล้ว') as success_s FROM repair");
        }else if($_SESSION["level"] == "guest"){
            $query_data = "SELECT repair_category, COUNT(repair_category) as total_cate FROM repair WHERE member_id = ".$_SESSION["member_id"]." GROUP BY repair_category";
            $query_data2 = "SELECT repair_status, COUNT(repair_status) as total_cate_status FROM repair WHERE member_id = ".$_SESSION["member_id"]." GROUP BY repair_status";
            $query_detail = mysqli_query($conn, "SELECT SUM(repair_category = 'เครื่องใช้ไฟฟ้า') as total_elec, SUM(repair_category = 'เฟอร์นิเจอร์') as total_fur, SUM(repair_category = 'สุขภัณฑ์') as total_suk FROM repair WHERE member_id = ".$_SESSION["member_id"]);
            $query_detail2 = mysqli_query($conn, "SELECT SUM(repair_status = 'รอคิวซ่อม') as pending_s, SUM(repair_status = 'กำลังซ่อม') as inprogress_s, SUM(repair_status = 'ซ่อมเสร็จแล้ว') as success_s FROM repair WHERE member_id = ".$_SESSION["member_id"]);
        }
    }
   
    $result = mysqli_query($conn, $query_data);
    $result2 = mysqli_query($conn, $query_data2);
    $result_d = mysqli_fetch_assoc($query_detail);
    $result_d2 = mysqli_fetch_assoc($query_detail2);
    $datax = array();
    $datax2 = array();
    foreach ($result as $k) {
        $datax[] = "['".$k['repair_category']." (".$k['total_cate']." รายการ)'".", ".$k['total_cate']."]";
    }
    foreach ($result2 as $l) {
        $datax2[] = "['".$l['repair_status']." (".$l['total_cate_status']." รายการ)'".", ".$l['total_cate_status']."]";
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
            <div class="repair-box">
                <h3>ค้นหารายการแจ้งซ่อม</h3>
                <div class="search">
                    <div id="first" style="padding-right:16px">
                        <div id="sub-search" style="height:57px;display:flex;align-items:flex-start;">
                            <label class="search_topic" style="padding:10px 8px 0 0;">ค้นหาตามวันที่</label>
                            <div class="from_box" style="position:relative;">
                                <input type="text" class="roundtrip-input" id="date_from"
                                    value="<?php if(isset($from)){ echo DateThai($from); } ?>">
                                <h5 id="from_error" style="color:red;"></h5>
                            </div>
                            <label class="to_text" style="padding:10px 8px 0 8px;">~</label>
                            <div class="to_box" style="position:relative;">
                                <input type="text" class="roundtrip-input" id="date_to"
                                    value="<?php if(isset($to)){ echo DateThai($to); } ?>">
                                <h5 id="to_error" style="color:red;"></h5>
                            </div>
                            <button class="search_btn" type="button" id="searchDate" style="margin:0 16px;">ค้นหา</button>
                            <?php
                            if(isset($from) || isset($to) || isset($check)){
                            ?>
                            <div class="cancel_box">
                                <a href="index.php"><button type="button" class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <a id="second" href="addRepair.php"><button>เพิ่มรายการแจ้งซ่อม</button></a>
                </div>
                <div class="hr" style="margin-top:16px;"></div>
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
                    <div class="detail-grid">
                        <div>
                            <h3 style="color: rgb(131, 120, 47, 0.7);">รายการแจ้งซ่อมแยกตามประเภท</h3>
                            <div class="hr" style="margin:8px 0 16px 0;"></div>
                            <h3 style="color: rgb(255, 166, 0);">เครื่องใช้ไฟฟ้า : <?php if($result_d["total_elec"] != 0){ echo $result_d["total_elec"]; }else{ echo 0;} ?> รายการ</h3>
                            <h3 style="color: #966F33;">เฟอร์นิเจอร์ : <?php if($result_d["total_fur"] != 0){ echo $result_d["total_fur"]; }else{ echo 0;} ?> รายการ</h3>
                            <h3 style="color: darkturquoise;">สุขภัณฑ์ : <?php if($result_d["total_suk"] != 0){ echo $result_d["total_suk"]; }else{ echo 0;} ?> รายการ</h3>
                        </div>
                        <div>
                            <h3 style="color: rgb(131, 120, 47, 0.7);">รายการแจ้งซ่อมแยกตามสถานะ</h3>
                            <div class="hr" style="margin:8px 0 16px 0;"></div>
                            <h3 style="color: #B1D78A;">ซ่อมเสร็จแล้ว : <?php if($result_d2["success_s"] != 0){ echo $result_d2["success_s"]; }else{ echo 0;} ?> รายการ</h3>
                            <h3 style="color: #8ac5d7;">กำลังซ่อม : <?php if($result_d2["inprogress_s"] != 0){ echo $result_d2["inprogress_s"]; }else{ echo 0;} ?> รายการ</h3>
                            <h3 style="color: rgb(170, 170, 170);">รอคิวซ่อม : <?php if($result_d2["pending_s"] != 0){ echo $result_d2["pending_s"]; }else{ echo 0;} ?> รายการ</h3>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <h3>รายการแจ้งซ่อมทั้งหมด</h3>
                    <div id="checkbox-grid" style="display:flex;align-items:center;">
                        <div style="padding:32px 16px 32px 0;display:flex;">
                            <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked"; } ?>>
                            <label for="scales">ทั้งหมด</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="pending" <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                            <label for="scales">รอคิวซ่อม</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="inprogress" <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                            <label for="scales">กำลังซ่อม</label>
                        </div>
                        <div style="padding:32px 16px;display:flex;">
                            <input type="checkbox" id="success" <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                            <label for="scales">ซ่อมเสร็จแล้ว</label>
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
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') ORDER BY repair_id DESC LIMIT {$start} , {$perpage}";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT repair_id, room_id, repair_appliance, repair_category, repair_date, repair_successdate, repair_status, repair_income FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to') ORDER BY repair_id DESC LIMIT {$start} , {$perpage}";
                        }
                    }else if(!isset($from) && !isset($to) && isset($check)){
                        if($check == "success"){
                            $check_s = "ซ่อมเสร็จแล้ว";
                        }else if($check == "inprogress"){
                            $check_s = "กำลังซ่อม";
                        }else if($check == "pending"){
                            $check_s = "รอคิวซ่อม";
                        }
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair WHERE repair_status = '$check_s' ORDER BY repair_id DESC LIMIT {$start} , {$perpage}";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT repair_id, room_id, repair_appliance, repair_category, repair_date, repair_successdate, repair_status, repair_income FROM repair WHERE repair_status = '$check_s' AND member_id = ".$_SESSION["member_id"]." ORDER BY repair_id DESC LIMIT {$start} , {$perpage}";
                        }
                    }else if(isset($from) && isset($to) && isset($check)){
                        if($check == "success"){
                            $check_s = "ซ่อมเสร็จแล้ว";
                        }else if($check == "inprogress"){
                            $check_s = "กำลังซ่อม";
                        }else if($check == "pending"){
                            $check_s = "รอคิวซ่อม";
                        }
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND repair_status = '$check_s' ORDER BY repair_id DESC LIMIT {$start} , {$perpage}"; 
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT repair_id, room_id, repair_appliance, repair_category, repair_date, repair_successdate, repair_status, repair_income FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND repair_status = '$check_s' AND member_id = ".$_SESSION["member_id"]." ORDER BY repair_id DESC LIMIT {$start} , {$perpage}"; 
                        }  
                    }else{
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql = "SELECT * FROM repair ORDER BY repair_id DESC LIMIT {$start} , {$perpage} ";
                        }else if($_SESSION["level"] == "guest"){
                            $sql = "SELECT repair_id, room_id, repair_appliance, repair_category, repair_date, repair_successdate, repair_status, repair_income FROM repair WHERE member_id = ".$_SESSION["member_id"]." ORDER BY repair_id DESC LIMIT {$start} , {$perpage} ";
                        }
                    }
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                ?>
                    <div style="overflow-x:auto;overflow-y:hidden;">
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
                                <td><?php if(isset($row['repair_successdate'])){ echo DateThai($row['repair_successdate']); }?></td>
                                <td>
                                    <?php
                                    if($row['repair_status'] == 'รอคิวซ่อม'){
                                    ?>
                                    <div class="pending-status">
                                        <p><?php echo $row['repair_status']; ?></p>
                                    </div>
                                    <?php
                                    }else if($row['repair_status'] == 'กำลังซ่อม'){
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
                                        <div>
                                            <a href="repairDetail.php?repair_id=<?php echo $row['repair_id'];?>" title="ดูข้อมูลเพิ่มเติม"><button>ดูข้อมูลเพิ่มเติม</button></a>
                                        </div>
                                        <div>
                                            <button class="del-btn" id="<?php echo $row['repair_id']; ?>" title="ลบข้อมูล"></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $num++; } ?>
                        </table>
                    </div>
                    <?php
                    ///////pagination
                    if(isset($from) && isset($to) && !isset($check)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM repair WHERE repair_date BETWEEN '$from' AND '$to'";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND repair_date BETWEEN '$from' AND '$to'";
                        }
                    }else if(!isset($from) && !isset($to) && isset($check)){   
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM repair WHERE repair_status = '$check_s'";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND repair_status = '$check_s'";
                        }
                    }else if(isset($from) && isset($to) && isset($check)){
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM repair WHERE (repair_date BETWEEN '$from' AND '$to') AND repair_status = '$check_s'";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM repair WHERE member_id = ".$_SESSION["member_id"]." AND (repair_date BETWEEN '$from' AND '$to') AND repair_status = '$check_s'";
                        }
                    }else{
                        if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                            $sql2 = "SELECT * FROM repair";
                        }else if($_SESSION["level"] == "guest"){
                            $sql2 = "SELECT * FROM repair WHERE member_id = ".$_SESSION["member_id"];
                        }
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
                            <a href="index.php?status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                        }else if(isset($from) && isset($to) && isset($check)){
                        ?>
                            <a
                                href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a
                                href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
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
    <script src="../../../js/manage/repair.js"></script>
    <script>
    google.charts.load('current', {
        'packages': ['corechart']
    });
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>
    <?php
    if(strlen($datax2) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart2);
    <?php } ?>

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
    $(window).resize(function(){
        drawChart();
        drawChart2();
    });
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>