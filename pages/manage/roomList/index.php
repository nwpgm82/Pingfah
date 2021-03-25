<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../connection.php");
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $check = @$_REQUEST['Status'];
    $check2 = @$_REQUEST['style'];
    $from = @$_REQUEST['from'];
    $to = @$_REQUEST['to'];
    $people = @$_REQUEST['people'];
    if($people > 8){
        $people = 8;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/roomList.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/roomList.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="box-grid">
                <div class="roomList-box">
                    <div class="header">
                        <h3>รายการห้องพักทั้งหมด</h3>
                        <div style="position:relative;">
                            <div class="option-grid" <?php if($_SESSION["level"] == "employee"){ echo "style='display:flex;'"; } ?>>
                                <a href="roomHistory.php"><button class="history-btn"></button></a>
                                <?php
                                if($_SESSION["level"] == "admin"){
                                ?>
                                <button id="addRoom">เพิ่มห้องพัก</button>
                                <?php } ?>
                            </div>
                            <?php
                            if($_SESSION["level"] == "admin"){
                            ?>
                            <div id="add" style="position:absolute;top:45px;right:0;display:none;">
                                <div class="arrow-up"></div>
                                <div class="popover">
                                    <h3>เพิ่มห้องพัก</h3>
                                    <div class="hr"></div>
                                    <form action="function/addRoom.php" method="POST">
                                        <div class="input-grid">
                                            <div>
                                                <p>เลขห้อง</p>
                                                <input type="text" id="room_id" name="room_id" maxlength="3"
                                                    placeholder="เลขห้อง">
                                                <h5 id="room_id_check" style="color:red;"></h5>
                                            </div>
                                            <div>
                                                <p>ประเภทห้องพัก</p>
                                                <select name="room_type" id="">
                                                    <option value="แอร์">แอร์</option>
                                                    <option value="พัดลม">พัดลม</option>
                                                </select>
                                            </div>
                                            <div>
                                                <p>ลักษณะห้องพัก</p>
                                                <select name="room_cat" id="">
                                                    <option value="รายวัน">รายวัน</option>
                                                    <option value="รายเดือน">รายเดือน</option>
                                                </select>
                                            </div>
                                            <div>
                                                <button type="submit">เพิ่ม</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div class="checkbox-grid">
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="all" <?php if($check == ""){ echo "checked"; }?>>
                            <label>ทั้งหมด</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="avai_all"
                                <?php if($check == "avai_all"){ echo "checked"; }?>>
                            <label>ว่าง (ทั้งหมด)</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="unavai_all"
                                <?php if($check == "unavai_all"){ echo "checked"; }?>>
                            <label>ไม่ว่าง (ทั้งหมด)</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="avai_daily"
                                <?php if($check == "avai_daily"){ echo "checked"; }?>>
                            <label>รายวัน (ว่าง)</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="unavai_daily"
                                <?php if($check == "unavai_daily"){ echo "checked"; }?>>
                            <label>รายวัน (ไม่ว่าง)</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="avai_month"
                                <?php if($check == "avai_month"){ echo "checked"; }?>>
                            <label>รายเดือน (ว่าง)</label>
                        </div>
                        <div class="sub-checkbox-grid">
                            <input type="checkbox" name="" id="unavai_month"
                                <?php if($check == "unavai_month"){ echo "checked"; }?>>
                            <label>รายเดือน (ไม่ว่าง)</label>
                        </div>
                    </div>
                    <?php
                    $perpage = 8;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    if($check == "avai_all"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ว่าง' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else if($check == "unavai_all"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ไม่ว่าง' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else if($check == "avai_daily"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายวัน' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else if($check == "unavai_daily"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ไม่ว่าง' AND room_cat = 'รายวัน' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else if($check == "avai_month"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายเดือน' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else if($check == "unavai_month"){
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist WHERE room_status = 'ไม่ว่าง' AND room_cat = 'รายเดือน' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }else{
                        $sql = "SELECT room_id, room_type, room_cat, room_status FROM roomlist ORDER BY room_id LIMIT {$start} , {$perpage}";
                    }
                    
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                    ?>
                    <div style="overflow-x: auto;overflow-y:hidden;">
                        <table>
                            <tr>
                                <th>เลขห้อง</th>
                                <th>ประเภท</th>
                                <th>ลักษณะ</th>
                                <th>สถานะ</th>
                                <?php
                                if($_SESSION["level"] == "admin"){
                                ?>
                                <th>เพิ่มเติม</th>
                                <?php } ?>
                            </tr>
                            <?php
                            while($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><a href="<?php if($row["room_cat"] == "รายเดือน"){ echo "room_id.php?ID=".$row['room_id']; }else if($row["room_cat"] == "รายวัน"){ echo "roomdaily_id.php?ID=".$row['room_id']; } ?>"><?php echo $row["room_id"]; ?></a>
                                </td>
                                <td><?php echo $row["room_type"]; ?></td>
                                <td>
                                    <img id="cat" src="<?php if($row['room_cat'] == 'รายวัน'){ echo '../../../img/tool/clock-icon.png'; }else if($row['room_cat'] == 'รายเดือน'){ echo '../../../img/tool/calendar-icon.png'; } ?>" alt="category-icon">
                                </td>
                                <td><?php if($row["room_status"] == "ว่าง"){ echo "<div class='status-available'></div>"; }else{ echo "<div class='status-unavailable'></div>"; } ?></td>
                                <?php
                                if($_SESSION["level"] == "admin"){
                                ?>
                                <td>
                                    <div style="display:flex;justify-content:center;">
                                        <button id="<?php echo $row['room_id']; ?>" class="del-btn"></button>
                                    </div>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <?php
                    ///////pagination
                    if($check == "avai_all"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ว่าง'";
                    }else if($check == "unavai_all"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ไม่ว่าง'";
                    }else if($check == "avai_daily"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายวัน'";
                    }else if($check == "unavai_daily"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ไม่ว่าง' AND room_cat = 'รายวัน'";
                    }else if($check == "avai_month"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายเดือน'";
                    }else if($check == "unavai_month"){
                        $sql2 = "SELECT * FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายเดือน'";
                    }else{
                        $sql2 = "SELECT * FROM roomlist";
                    }
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <?php
                            if($check == "avai_all"){
                            ?>
                            <a href="index.php?Status=avai_all&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=avai_all&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=avai_all&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else if($check == "unavai_all"){
                            ?>
                            <a href="index.php?Status=unavai_all&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=unavai_all&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=unavai_all&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php 
                            }else if($check == "avai_daily"){
                            ?>
                            <a href="index.php?Status=avai_daily&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=avai_daily&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=avai_daily&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else if($check == "unavai_daily"){
                            ?>
                            <a href="index.php?Status=unavai_daily&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=unavai_daily&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=unavai_daily&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php 
                            }else if($check == "avai_month"){
                            ?>
                            <a href="index.php?Status=avai_month&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=avai_month&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=avai_month&page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                            }else if($check == "unavai_month"){
                            ?>
                            <a href="index.php?Status=unavai_month&page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?Status=unavai_month&page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?Status=unavai_month&page=<?php echo $total_page; ?>">&raquo;</a>
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
                        if($check == "avai_all"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักที่ว่าง</div>";
                        }else if($check == "unavai_all"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักที่ไม่ว่าง</div>";
                        }else if($check == "avai_daily"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักรายวัน</div>";
                        }else if($check == "unavai_daily"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักรายวันที่ไม่ว่าง</div>";
                        }else if($check == "avai_month"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักรายเดือน</div>";
                        }else if($check == "unavai_month"){
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพักรายเดือนที่ไม่ว่าง</div>";
                        }else{
                            echo "<div style='padding-top:32px;'>ไม่มีรายการห้องพัก</div>";
                        }
                    }
                    ?>
                </div>
                <div class="roomList-box">
                    <h3>ค้นหาห้องพักที่ว่าง</h3>
                    <div class="hr"></div>
                    <div id="padding-searchbox" style="padding-top:32px;">
                        <div class="search">
                            <p style="padding:10px 8px 0 0;">ค้นหาตามวันที่</p>
                            <div id="from_box" style="position:relative;">
                                <input type="text" id="date_from" class="roundtrip-input"
                                    value="<?php if(isset($from)){ echo DateThai($from); }?>">
                                <!-- <p id="from_date" class="dateText"></p> -->
                                <h5 id="error-text" style="color:red;padding-top:4px;"></h5>
                            </div>
                            <label style="padding:10px 8px 0 8px;">~</label>
                            <div id="to_box" style="position:relative;">
                                <input type="text" id="date_to" class="roundtrip-input"
                                    value="<?php if(isset($to)){ echo DateThai($to); }?>">
                                <!-- <p id="to_date" class="dateText"></p> -->
                            </div>
                        </div>
                        <div style="height:81px;padding-top:20px;display:flex;align-items:flex-start;">
                            <label style="padding-top:8px;">จำนวนผู้พัก :</label>
                            <div>
                                <input id="people" type="text" style="margin:0 8px;" min="1"
                                    value="<?php if(isset($people)){ echo $people; }else{ echo 1; } ?>"
                                    maxlength="2">
                                <h5 id="error-number" style="color:red;padding:4px 0 0 8px;"></h5>
                            </div>
                            <button id="search_room" type="button">ค้นหา</button>
                        </div>
                        <?php
                        if(isset($from) && isset($to) && isset($people)){
                        ?>
                        <div class="checkbox-grid">
                            <div class="sub-checkbox-grid">
                                <input type="checkbox" name="" id="daily_search"
                                    <?php if(!isset($check2) || $check2 == "daily"){ echo "checked"; }?>>
                                <label>รายวัน</label>
                            </div>
                            <div class="sub-checkbox-grid">
                                <input type="checkbox" name="" id="month_search"
                                    <?php if($check2 == "month"){ echo "checked"; }?>>
                                <label>รายเดือน</label>
                            </div>
                        </div>
                        <?php } ?>
                        <div>
                            <?php
                            $total_air_avai = 0;
                            $total_fan_avai = 0;
                            if(isset($from) && isset($to) && $check2 == "daily"){
                                $count_all_dailyRoom = mysqli_query($conn, "SELECT SUM(room_type = 'แอร์') AS room_daily_air, SUM(room_type = 'พัดลม') AS room_daily_fan FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายวัน' ");
                                $result_all_dailyRoom = mysqli_fetch_assoc($count_all_dailyRoom);
                                $count_daily = mysqli_query($conn, "SELECT SUM(air_room) AS daily_count_air, SUM(fan_room) AS daily_count_fan FROM daily WHERE (check_in BETWEEN '$from' AND '$to') OR (check_out BETWEEN '$from' AND '$to') OR ('$from' BETWEEN check_in AND check_out) OR ('$to' BETWEEN check_in AND check_out )");
                                $result_daily = mysqli_fetch_assoc($count_daily);
                                $total_air_avai = intval($result_all_dailyRoom["room_daily_air"]) - intval($result_daily["daily_count_air"]);
                                $total_fan_avai = intval($result_all_dailyRoom["room_daily_fan"]) - intval($result_daily["daily_count_fan"]); 
                                
                            }else if(isset($from) && isset($to) && $check2 == "month"){
                                $count_all_monthRoom = mysqli_query($conn, "SELECT SUM(room_type = 'แอร์') AS room_month_air, SUM(room_type = 'พัดลม') AS room_month_fan FROM roomlist WHERE room_status = 'ว่าง' AND room_cat = 'รายเดือน' ");
                                $result_all_monthRoom = mysqli_fetch_assoc($count_all_monthRoom);
                                $total_air_avai = intval($result_all_monthRoom["room_month_air"]);
                                $total_fan_avai = intval($result_all_monthRoom["room_month_fan"]);
                            }else{
                                echo '<div style="padding-top:32px;">กรุณาคลิกปุ่ม "ค้นหา" เพื่อทำการค้นหาห้องว่าง</div>';
                            }
                            if($people <= ($total_air_avai + $total_fan_avai)*2){
                                if($total_air_avai > 0 && $total_fan_avai > 0){
                                    $get_room = "SELECT * FROM roomdetail ORDER BY type DESC";
                                }else if($total_air_avai > 0 && $total_fan_avai <= 0){
                                    $get_room = "SELECT * FROM roomdetail WHERE type = 'แอร์' ORDER BY type DESC";
                                }else if($total_air_avai <= 0 && $total_fan_avai > 0){
                                    $get_room = "SELECT * FROM roomdetail WHERE type = 'พัดลม' ORDER BY type DESC";
                                }else{
                                    // echo "<div style='padding-top:32px;'>ไม่มีห้องว่าง</div>";
                                }
                                if($total_air_avai > 0 || $total_fan_avai > 0){
                                    $get_result = $conn->query($get_room);
                                    if ($get_result->num_rows > 0) {
                                        while($roomdetail = $get_result->fetch_assoc()) {
                            ?>
                            <div class="card">
                                <?php
                                    if($roomdetail["type"] == "แอร์"){
                                        $get_img = "SELECT * FROM air_gal LIMIT 1";
                                    }else if($roomdetail["type"] == "พัดลม"){
                                        $get_img = "SELECT * FROM fan_gal LIMIT 1";
                                    }
                                    $get_img_result = $conn->query($get_img);
                                    if ($get_img_result->num_rows > 0) {
                                        while($room_img = $get_img_result->fetch_assoc()) {
                                ?>
                                <img id="room_img" src="../../images/roomdetail/<?php if($roomdetail['type'] == 'แอร์'){ echo 'air'; }else if($roomdetail['type'] == 'พัดลม'){ echo 'fan'; } ?>/<?php echo $room_img['gal_name']; ?>"
                                    alt="">
                                <?php } } ?>
                                <div style="padding:16px;">
                                    <h3>ห้อง<?php echo $roomdetail['type']; ?></h3>
                                    <div style="padding-top:16px;line-height:30px;">
                                        <div class="user-grid">
                                            <img src="../../../img/tool/user.png" alt="">
                                            <label>2 คน</label>
                                        </div>
                                        <div class="room-text">
                                            <div>
                                                <?php
                                                if($check2 == "daily"){
                                                ?>
                                                <p>รายวัน : <strong
                                                        style="color:rgb(131, 120, 47, 1);"><?php echo number_format($roomdetail['daily_price']); ?></strong>
                                                    บาท / คืน</p>
                                                <?php
                                                }else if($check2 == "month"){
                                                ?>
                                                <p>รายเดือน : <strong
                                                        style="color:rgb(131, 120, 47, 1);"><?php echo number_format($roomdetail['price']); ?></strong>
                                                    บาท / เดือน</p>
                                                <?php } ?>
                                            </div>
                                            <div>
                                                <p>จำนวนห้องพักคงเหลือ
                                                    <?php if($roomdetail['type'] == 'แอร์'){ echo $total_air_avai; }else if($roomdetail['type'] == 'พัดลม'){ echo $total_fan_avai; } ?>
                                                    ห้อง</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }}}}else{ echo "<div style='padding-top:32px;'>ไม่มีห้องว่าง</div>"; } ?>
                        </div>
                    </div>
                </div>
                <div class="roomList-box">
                    <h3>สัญลักษณ์</h3>
                    <div class="hr"></div>
                    <div style="padding-top:32px;">
                        <h4>ลักษณะ</h4>
                        <div class="icon-grid">
                            <img src="../../../img/tool/clock-icon.png" alt="">
                            <p>ห้องพักรายวัน</p>
                        </div>
                        <div class="icon-grid">
                            <img src="../../../img/tool/calendar-icon.png" alt="">
                            <p>ห้องพักรายเดือน</p>
                        </div>
                    </div>
                    <div style="padding-top:32px;">
                        <h4>สถานะ</h4>
                        <div class="icon-grid">
                            <div class="status-available"></div>
                            <p>ห้องว่าง</p>
                        </div>
                        <div class="icon-grid">
                            <div class="status-unavailable"></div>
                            <p>ห้องไม่ว่าง</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="add2">
        <div class="bg"></div>
        <div class="card">
            <h3>เพิ่มห้องพัก</h3>
            <div class="hr"></div>
            <form action="function/addRoom.php" method="POST">
                <div class="input-grid">
                    <div>
                        <p>เลขห้อง</p>
                        <input type="text" id="room_id" name="room_id" maxlength="3" placeholder="เลขห้อง">
                        <h5 id="room_id_check" style="color:red;"></h5>
                    </div>
                    <div>
                        <p>ประเภทห้องพัก</p>
                        <select name="room_type" id="">
                            <option value="แอร์">แอร์</option>
                            <option value="พัดลม">พัดลม</option>
                        </select>
                    </div>
                    <div>
                        <p>ลักษณะห้องพัก</p>
                        <select name="room_cat" id="">
                            <option value="รายวัน">รายวัน</option>
                            <option value="รายเดือน">รายเดือน</option>
                        </select>
                    </div>
                </div>
                <div style="padding-top:24px;">
                    <button type="submit">เพิ่ม</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
}else{
    Header("Location: ../../login.php");
}