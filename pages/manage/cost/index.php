<?php
session_start();

if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){
    include('../../connection.php');
    //////////// กรณีไม่ได้ชำระเงินค่าห้องพักรายเดือน ///////////////////////
    $get_cost = "SELECT * FROM cost WHERE cost_status != 'ชำระเงินแล้ว'";
    $get_result = $conn->query($get_cost);
    if ($get_result->num_rows > 0) {
        while ($cost = $get_result->fetch_assoc()) {
            $cost_date = strtotime($cost["date"]);
            $month = date("m", $cost_date);
            $inc_month = sprintf("%02d", intval($month + 1));
            $current_date = date("Y", $cost_date) . "-" . $inc_month . "-05";
            $deadline_dateCheck = strtotime($current_date);
            $diff = abs($deadline_dateCheck - strtotime(date("Y-m-d")));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            // echo date("Y-m-d",$cost_date)."/".date("Y-m-d",$deadline_dateCheck);     
            if (strtotime(date("Y-m-d")) > $deadline_dateCheck) {
                if ($cost["room_type"] == "แอร์") {
                    $get_detail = mysqli_query($conn, "SELECT fines FROM roomdetail WHERE type = 'แอร์'");
                } else if ($cost["room_type"] == "พัดลม") {
                    $get_detail = mysqli_query($conn, "SELECT fines FROM roomdetail WHERE type = 'พัดลม'");
                }
                $detail_result = mysqli_fetch_assoc($get_detail);
                $total_fines = floatval($detail_result["fines"] * $days);
                $cost_totalprice = $cost["room_cost"] + $cost["water_bill"] + $cost["elec_bill"] + $cost["cable_charge"] + floatval($total_fines);
                $update_cost = "UPDATE cost SET fines = $total_fines, total = $cost_totalprice, cost_status = 'ยังไม่ได้ชำระเงิน' WHERE cost_id = " . $cost["cost_id"];
                if ($conn->query($update_cost) === true) {
                    require_once "../../../lib/PromptPayQR.php";
                    $date = date("Y-m");
                    $folder_prompt = "../../images/cost/".$cost["date"]."/" . $cost["room_id"] . "/promptpay/qr-code.png";
                    $promptData = mysqli_query($conn, "SELECT prompt_num FROM promptpay");
                    $promptData_result = mysqli_fetch_assoc($promptData);
                    $PromptPayQR = new PromptPayQR(); // new object
                    $PromptPayQR->size = 6;
                    $PromptPayQR->id = $promptData_result["prompt_num"]; // PromptPay ID
                    $PromptPayQR->amount = $cost_totalprice; // Set amount (not necessary)
                    $data = $PromptPayQR->generate();
                    list($type, $data) = explode(';', $data);
                    list(, $data) = explode(',', $data);
                    $data = base64_decode($data);
                    file_put_contents($folder_prompt, $data);
                }
            }
        }
    }
    ///////////////////////////////////////////////////////////////////
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $check = @$_REQUEST['status'];
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strMonthThai $strYear";
    }
    function DateThai2($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
        if(isset($from) && isset($to)){
            // $query = "SELECT date ,SUM(total) as totalPrice FROM cost WHERE (date BETWEEN '$from' AND '$to') GROUP BY date";
            $query = "SELECT * FROM (SELECT date, SUM(total) AS totalPrice FROM cost WHERE (date BETWEEN '$from' AND '$to') GROUP BY date ORDER BY date DESC LIMIT 5) sub ORDER BY date ASC";
            $total_cost = mysqli_query($conn,"SELECT SUM(total) as total_price, SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as successtotal_cost, SUM(case WHEN cost_status = 'รอการชำระเงิน' THEN total ELSE 0 END) as pendingtotal_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระเงิน' THEN total ELSE 0 END) as untotal_cost FROM cost WHERE (date BETWEEN '$from' AND '$to')");
            $totalresult = mysqli_fetch_assoc($total_cost);
        }else{
            // $query = "SELECT date ,SUM(total) as totalPrice FROM cost GROUP BY date ORDER BY date";
            $query = "SELECT * FROM (SELECT date, SUM(total) AS totalPrice FROM cost GROUP BY date ORDER BY date DESC LIMIT 5) sub ORDER BY date ASC";
            $total_cost = mysqli_query($conn,"SELECT SUM(total) as total_price, SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as successtotal_cost, SUM(case WHEN cost_status = 'รอการชำระเงิน' THEN total ELSE 0 END) as pendingtotal_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระเงิน' THEN total ELSE 0 END) as untotal_cost FROM cost ");
            $totalresult = mysqli_fetch_assoc($total_cost);
        }
    }else if($_SESSION["level"] == "guest"){
        if(isset($from) && isset($to)){
            // $query = "SELECT date ,SUM(total) as totalPrice FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND (date BETWEEN '$from' AND '$to') GROUP BY date";
            $query = "SELECT * FROM ( SELECT date, SUM(total) as totalPrice FROM cost WHERE member_id = ".$_SESSION['member_id']." AND (date BETWEEN '$from' AND '$to') GROUP BY date ORDER BY date DESC LIMIT 5 ) sub ORDER BY date ASC";
            $total_cost = mysqli_query($conn,"SELECT SUM(total) as total_price, SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as successtotal_cost, SUM(case WHEN cost_status = 'รอการชำระเงิน' THEN total ELSE 0 END) as pendingtotal_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระเงิน' THEN total ELSE 0 END) as untotal_cost FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND (date BETWEEN '$from' AND '$to')");
            $totalresult = mysqli_fetch_assoc($total_cost);
        }else{
            // $query = "SELECT date ,SUM(total) as totalPrice FROM cost WHERE member_id = ".$_SESSION["member_id"]." GROUP BY date ORDER BY date";
            $query = "SELECT * FROM ( SELECT date, SUM(total) as totalPrice FROM cost WHERE member_id = ".$_SESSION['member_id']." GROUP BY date ORDER BY date DESC LIMIT 5 ) sub ORDER BY date ASC";
            $total_cost = mysqli_query($conn,"SELECT SUM(total) as total_price, SUM(case WHEN cost_status = 'ชำระเงินแล้ว' THEN total ELSE 0 END) as successtotal_cost, SUM(case WHEN cost_status = 'รอการชำระเงิน' THEN total ELSE 0 END) as pendingtotal_price, SUM(case WHEN cost_status = 'ยังไม่ได้ชำระเงิน' THEN total ELSE 0 END) as untotal_cost FROM cost WHERE member_id = ".$_SESSION["member_id"]);
            $totalresult = mysqli_fetch_assoc($total_cost);
        }
    }
    $result = mysqli_query($conn, $query);
    $datax = array();
    foreach ($result as $k) {
        $datax[] = "['".DateThai($k['date'])."'".", ".$k['totalPrice']."]";
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
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/costDate.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="cost-box">
                <h3>ค้นหารายการชำระเงินห้องพักรายเดือน</h3>
                <div class="search">
                    <div id="first" style="padding-right:16px">
                        <div id="sub-search" style="height:57px;display:flex;align-items:flex-start;flex-flow: wrap;">
                            <label class="search_topic" style="padding:10px 8px 0 0;">ค้นหาตามวันที่</label>
                            <div class="from_box" style="position:relative;">
                                <input type="text" class="roundtrip-input" id="date_from"
                                    value="<?php if(isset($from)){ echo DateThai($from); } ?>">
                                <h5 id="from_error" style="color:red;"></h5>
                            </div>
                            <label class="to_text" style="padding:10px 8px 0 8px;">~</label>
                            <div class="to_box" class="" style="position:relative;">
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
                    <?php
                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                    ?>
                    <a id="second" href="addcost.php"><button>เพิ่มรายการชำระเงิน</button></a>
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
                    <?php
                    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                    ?>
                    <div style="padding-top:32px;">
                        <div style="line-height:40px;">
                            <h3 style="color: rgb(131, 120, 47, 0.7);">ยอดรวมทั้งหมด : <?php echo number_format($totalresult["total_price"]); ?> บาท</h3>
                            <h3 style="color: #B1D78A;">ชำระเงินแล้ว : <?php echo number_format($totalresult["successtotal_cost"]); ?> บาท</h3>
                            <h3 style="color: rgb(170, 170, 170);">รอการชำระเงิน : <?php echo number_format($totalresult["pendingtotal_price"]); ?> บาท</h3>
                            <h3 style="color: #D68B8B;">ยังไม่ได้ชำระเงิน : <?php echo number_format($totalresult["untotal_cost"]); ?> บาท</h3>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="hr"></div>
                    <h3>รายการชำระเงินห้องพักรายเดือนทั้งหมด</h3>
                    <div id="checkbox-grid" style="display:flex;align-items:center;">
                        <div style="padding:32px 16px 32px 0px;display: flex;align-items: center;">
                            <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked";} ?>>
                            <label for="scales">ทั้งหมด</label>
                        </div>
                        <div style="padding:32px 16px;display: flex;align-items: center;">
                            <input type="checkbox" id="unsuccess" <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                            <label for="scales">ยังไม่ได้ชำระเงิน</label>
                        </div>
                        <div style="padding:32px 16px;display: flex;align-items: center;">
                            <input type="checkbox" id="pending" <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                            <label for="scales">รอการชำระเงิน</label>
                        </div>
                        <div style="padding:32px 16px;display: flex;align-items: center;">
                            <input type="checkbox" id="success" <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                            <label for="scales">ชำระเงินแล้ว</label>
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
                                $sql ="SELECT * FROM cost WHERE (date BETWEEN '$from' AND '$to') ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }else if($_SESSION["level"] == "guest"){
                                $sql ="SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND (date BETWEEN '$from' AND '$to') ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }
                        }else if(!isset($from) && !isset($to) && isset($check)){
                            if($check == "success"){
                                $check_s = "ชำระเงินแล้ว";
                            }else if($check == "unsuccess"){
                                $check_s = "ยังไม่ได้ชำระเงิน";
                            }else if($check = "pending"){
                                $check_s = "รอการชำระเงิน";
                            }
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql = "SELECT * FROM cost WHERE cost_status = '$check_s' ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }else if($_SESSION["level"] == "guest"){
                                $sql = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND cost_status = '$check_s' ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }
                        }else if(isset($from) && isset($to) && isset($check)){
                            if($check == "success"){
                                $check_s = "ชำระเงินแล้ว";
                            }else if($check == "unsuccess"){
                                $check_s = "ยังไม่ได้ชำระเงิน";
                            }else if($check == "pending"){
                                $check_s = "รอการชำระเงิน";
                            }
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql = "SELECT * FROM cost WHERE (date BETWEEN '$from' AND '$to') AND cost_status = '$check_s' ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }else if($_SESSION["level"] == "guest"){
                                $sql = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND (date BETWEEN '$from' AND '$to') AND cost_status = '$check_s' ORDER BY cost_id DESC LIMIT {$start} , {$perpage}";
                            }
                        }else{
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql = "SELECT * FROM cost ORDER BY cost_id DESC LIMIT {$start} , {$perpage} ";
                            }else if($_SESSION["level"] == "guest"){
                                $sql = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." ORDER BY cost_id DESC LIMIT {$start} , {$perpage} ";
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
                                    <th>ประจำเดือน</th>
                                    <th>วันที่ชำระเงิน</th>
                                    <th>ยอดรวม</th>
                                    <th>ค่าปรับ</th>
                                    <th>สถานะการเข้าพัก</th>
                                    <th>สถานะการชำระเงิน</th>
                                    <th>เพิ่มเติม</th>
                                </tr>
                                <?php while($row = $result->fetch_assoc()){ ?>
                                <?php if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){ ?>
                                <form action="function/confirmStatus.php?cost_id=<?php echo $row["cost_id"]; ?>" onsubmit="return confirm('คุณต้องการยืนยันการชำระเงินใช่หรือไม่ ?')" method="POST">
                                <?php } ?>
                                    <tr>
                                        <td><?php echo $num; ?></td>
                                        <td><?php echo $row["room_id"];?> <?php if($row["deposit"] != null){ echo "<label style='color: rgb(82, 185, 82);'>(ใหม่)</label>"; }?></td>
                                        <td><?php echo DateThai($row["date"]);?></td>
                                        <td><?php if(isset($row["pay_date"])){ echo DateThai2($row["pay_date"]); }?></td>
                                        <td><?php echo number_format($row["total"]);?></td>
                                        <td><?php if(isset($row["fines"])){ echo number_format($row["fines"]); }else{ echo "0"; }?></td>
                                        <?php
                                        if($row['member_status'] == 'กำลังเข้าพัก'){
                                        ?>
                                        <td>
                                            <div class="status-come">
                                                <p><?php echo $row["member_status"]; ?></p>
                                            </div>    
                                        </td>
                                        <?php
                                        }else if($row['member_status'] == 'แจ้งออกแล้ว'){
                                        ?>
                                        <td>
                                            <div class="status-out">
                                                <p><?php echo $row["member_status"]; ?></p>
                                            </div>    
                                        </td>
                                        <?php } ?>
                                        <?php
                                        if($row['cost_status'] == 'ชำระเงินแล้ว'){
                                        ?>
                                        <td>
                                            <div class="status-success">
                                                <p><?php echo $row["cost_status"]?></p>
                                            </div>
                                        </td>
                                        <?php
                                        }else if($row['cost_status'] == 'รอการชำระเงิน'){
                                        ?>
                                        <td>
                                            <div class="status-pending">
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
                                        <?php
                                        if($row['cost_status'] != 'ชำระเงินแล้ว'){
                                        ?>
                                        <td>
                                            <div class="option">
                                                <button type="button" class="print" disabled></button>
                                                <a href="../../images/cost/<?php echo $row["date"]; ?>/<?php echo $row["room_id"]; ?>/promptpay/qr-code.png" target="_blank"><button type="button" class="qr" title="QR Code สำหรับชำระเงิน"></button></a>
                                                <?php
                                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                                ?>
                                                <button type="submit" class="confirm-status" title="ยืนยันการชำระเงิน">ยืนยันการชำระเงิน</button>
                                                <?php 
                                                }else if($_SESSION["level"] == "guest"){
                                                    if($row["pay_img"] == null){
                                                ?>
                                                <button type="button" class="confirmed-status" title="รอการชำระเงิน">รอการชำระเงิน</button>
                                                <?php 
                                                    }else{
                                                ?>
                                                <button type="button" class="confirmed-status" title="รอการตรวจสอบ">รอการตรวจสอบ</button>
                                                <?php        
                                                    }
                                                 } ?>
                                                <a href="costDetail.php?cost_id=<?php echo $row["cost_id"]; ?>"><button type="button" class="more" title="ดูข้อมูลเพิ่มเติม">ดูข้อมูลเพิ่มเติม</button></a>
                                                <button type="button" class="del-btn" id="<?php echo $row["cost_id"]; ?>" title="ลบข้อมูล"></button>
                                            </div>

                                        </td>
                                        <?php 
                                        }else{
                                        ?>
                                        <td>
                                            <div class="option">
                                                <?php
                                                if($row["member_status"] == "กำลังเข้าพัก"){
                                                ?>
                                                <a href="receipt_month.php?cost_id=<?php echo $row["cost_id"]; ?>" target="_blank"><button type="button" class="print" title="ใบเสร็จค่าเช่าห้องพัก"></button></a>
                                                <?php 
                                                }else{
                                                ?>
                                                <a href="receipt_quit.php?cost_id=<?php echo $row["cost_id"]; ?>" target="_blank"><button type="button" class="print" title="ใบเสร็จค่าเช่าห้องพัก"></button></a>
                                                <?php } ?>
                                                <button type="button" class="qr" disabled></button>
                                                <button type="button" class="confirmed-status">ชำระเงินแล้ว</button>
                                                <a href="costDetail.php?cost_id=<?php echo $row["cost_id"]; ?>"><button type="button" class="more" title="ดูข้อมูลเพิ่มเติม">ดูข้อมูลเพิ่มเติม</button></a>
                                                <button type="button" class="del-btn" id="<?php echo $row["cost_id"]; ?>" title="ลบข้อมูล"></button>
                                            </div>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php
                                if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                ?>
                                </form>
                                <?php } ?>
                                <?php $num++; } ?>
                            </table>
                        </div>
                        <?php
                        ///////pagination
                        if(isset($from) && isset($to) && !isset($check)){
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql2 ="SELECT * FROM cost WHERE date BETWEEN '$from' AND '$to'";
                            }else if($_SESSION["level"] == "guest"){
                                $sql2 ="SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND date BETWEEN '$from' AND '$to'";
                            }
                        }else if(!isset($from) && !isset($to) && isset($check)){
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql2 = "SELECT * FROM cost WHERE cost_status = '$check_s'";
                            }else if($_SESSION["level"] == "guest"){
                                $sql2 = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND cost_status = '$check_s'";
                            }
                        }else if(isset($from) && isset($to) && isset($check)){
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql2 = "SELECT * FROM cost WHERE (date BETWEEN '$from' AND '$to') AND cost_status = '$check_s'";   
                            }else if($_SESSION["level"] == "guest"){
                                $sql2 = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"]." AND (date BETWEEN '$from' AND '$to') AND cost_status = '$check_s'";  
                            }
                        }else{
                            if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
                                $sql2 = "SELECT * FROM cost";
                            }else if($_SESSION["level"] == "guest"){
                                $sql2 = "SELECT * FROM cost WHERE member_id = ".$_SESSION["member_id"];
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
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
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
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $i; ?>"
                            <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?from=<?php echo $from; ?>&to=<?php echo $to; ?>&status=<?php echo $check; ?>&page=<?php echo $total_page; ?>">&raquo;</a>
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
    <script>
    google.charts.load('current', {'packages':['corechart']});
    <?php
    if(strlen($datax) != 0){
    ?>
    google.charts.setOnLoadCallback(drawChart);
    <?php } ?>

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['เดือน / ปี', 'ยอดรวมทั้งหมด (บาท)'],
            <?php echo $datax;?>
        ]);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" }]);
        var options = {
            title: 'รายการชำระเงินห้องพักรายเดือน',
            colors: ['rgb(131, 120, 47)', '#c6b66b', '#ffefab'],
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