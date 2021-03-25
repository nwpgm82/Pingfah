<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    include('../../../components/sidebar.php');
    $date = @$_REQUEST['Date'];
    $check = @$_REQUEST['Status'];
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/repair.css">
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="repair-box">
                <h3>ค้นหารายการแจ้งซ่อม</h3>
                <div style="padding-top:32px">
                    <label>ค้นหาตามวันที่</label>
                    <input type="date" id="repair_date" value="<?php if(isset($date)){ echo $date; }else{ echo ""; } ?>"
                        onchange="searchDate(value)">
                </div>
                <div class="hr"></div>
                <h3>รายการแจ้งซ่อมทั้งหมด</h3>
                <?php
                    $perpage = 5;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    if(!isset($date) && !isset($check)){
                        $sql = "SELECT * FROM repair ORDER BY repair_date LIMIT {$start} , {$perpage} ";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <th>อุปกรณ์</th>
                        <th>หมวดหมู่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาที่ลง</th>
                        <th>สถานะ</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $row['room_id']; ?></td>
                        <td><?php echo $row['repair_appliance']; ?></td>
                        <td><?php echo $row['repair_category']; ?></td>
                        <td><?php echo $row['repair_detail']; ?></td>
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
                                }else if($row['repair_status'] == 'ดำเนินการเสร็จสิ้น'){
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
                                <a
                                    href="../repair/repairDetail.php?room_id=<?php echo $row['room_id'];?>&repairappliance=<?php echo $row['repair_appliance'];?>&repaircategory=<?php echo $row['repair_category'];?>&repairdate=<?php echo $row['repair_date'];?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                            </div>
                            <div>
                                <button class="del-btn"
                                    onclick="repair_del(<?php echo "'".$row['room_id']."','".$row['repair_appliance']."','".$row['repair_category']."','".$row['repair_date']."'"?>)">ลบ</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
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
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>
                <?php
                }else{
                ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>
                <?php
                        }
                    }else if(isset($date) && !isset($check)){
                        ///เมื่อค้นหาด้วยวันที่/////
                        $repairData = "SELECT * FROM repair WHERE repair_date = '$date' ORDER BY room_id";
                        $repairCount = "SELECT COUNT(*) FROM repair WHERE repair_date = '$date'";
                        $resultCount = $conn->query($repairCount);
                        $rowCount = $resultCount->fetch_row();
                        $result = $conn->query($repairData);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <th>อุปกรณ์</th>
                        <th>หมวดหมู่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาที่ลง</th>
                        <th>สถานะ</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php 
                         while($row = $result->fetch_assoc()) {
                        ?>
                    <tr>
                        <td><?php echo $row['room_id']; ?></td>
                        <td><?php echo $row['repair_appliance']; ?></td>
                        <td><?php echo $row['repair_category']; ?></td>
                        <td><?php echo $row['repair_detail']; ?></td>
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
                                }else if($row['repair_status'] == 'ดำเนินการเสร็จสิ้น'){
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
                                <a
                                    href="../repair/repairDetail.php?room_id=<?php echo $row['room_id'];?>&repairappliance=<?php echo $row['repair_appliance'];?>&repaircategory=<?php echo $row['repair_category'];?>&repairdate=<?php echo $row['repair_date'];?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                            </div>
                            <div>
                                <button class="del-btn"
                                    onclick="repair_del(<?php echo "'".$row['room_id']."','".$row['repair_appliance']."','".$row['repair_category']."','".$row['repair_date']."'"?>)">ลบ</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM repair WHERE repair_date = '$date' ORDER BY room_id";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>
                <?php }else{ ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "inprogress"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "pending"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>
                <?php
                    }}else if(isset($check) && !isset($date)){
                        if($check == "success"){
                            $check = "ดำเนินการเสร็จสิ้น";
                        }else if($check == "inprogress"){
                            $check = "กำลังดำเนินการ";
                        }else if($check == "pending"){
                            $check = "รอดำเนินการ";
                        }
                        $repairDataCheck = "SELECT * FROM repair WHERE repair_status = '$check' ORDER BY room_id LIMIT {$start} , {$perpage}";
                        $result = $conn->query($repairDataCheck);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "ดำเนินการเสร็จสิ้น"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "กำลังดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "รอดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <th>อุปกรณ์</th>
                        <th>หมวดหมู่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาที่ลง</th>
                        <th>สถานะ</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php 
                         while($row = $result->fetch_assoc()) {
                        ?>
                    <tr>
                        <td><?php echo $row['room_id']; ?></td>
                        <td><?php echo $row['repair_appliance']; ?></td>
                        <td><?php echo $row['repair_category']; ?></td>
                        <td><?php echo $row['repair_detail']; ?></td>
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
                                }else if($row['repair_status'] == 'ดำเนินการเสร็จสิ้น'){
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
                                <a
                                    href="../repair/repairDetail.php?room_id=<?php echo $row['room_id'];?>&repairappliance=<?php echo $row['repair_appliance'];?>&repaircategory=<?php echo $row['repair_category'];?>&repairdate=<?php echo $row['repair_date'];?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                            </div>
                            <div>
                                <button class="del-btn"
                                    onclick="repair_del(<?php echo "'".$row['room_id']."','".$row['repair_appliance']."','".$row['repair_category']."','".$row['repair_date']."'"?>)">ลบ</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM repair WHERE repair_status = '$check' ORDER BY room_id";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>

                <?php
                        }else{
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "ดำเนินการเสร็จสิ้น"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "กำลังดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "รอดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>
                <?php
                        }
                    }else if(isset($date) && isset($check)){
                        if($check == "success"){
                            $check = "ดำเนินการเสร็จสิ้น";
                        }else if($check == "inprogress"){
                            $check = "กำลังดำเนินการ";
                        }else if($check == "pending"){
                            $check = "รอดำเนินการ";
                        }
                        $repairDataCheck2 = "SELECT * FROM repair WHERE repair_date = '$date' AND repair_status = '$check' ORDER BY room_id LIMIT {$start} , {$perpage}";   
                        $result = $conn->query($repairDataCheck2);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "ดำเนินการเสร็จสิ้น"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "กำลังดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "รอดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <th>อุปกรณ์</th>
                        <th>หมวดหมู่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาที่ลง</th>
                        <th>สถานะ</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php 
                         while($row = $result->fetch_assoc()) {
                        ?>
                    <tr>
                        <td><?php echo $row['room_id']; ?></td>
                        <td><?php echo $row['repair_appliance']; ?></td>
                        <td><?php echo $row['repair_category']; ?></td>
                        <td><?php echo $row['repair_detail']; ?></td>
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
                                }else if($row['repair_status'] == 'ดำเนินการเสร็จสิ้น'){
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
                                <a
                                    href="../repair/repairDetail.php?room_id=<?php echo $row['room_id'];?>&repairappliance=<?php echo $row['repair_appliance'];?>&repaircategory=<?php echo $row['repair_category'];?>&repairdate=<?php echo $row['repair_date'];?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                            </div>
                            <div>
                                <button class="del-btn"
                                    onclick="repair_del(<?php echo "'".$row['room_id']."','".$row['repair_appliance']."','".$row['repair_category']."','".$row['repair_date']."'"?>)">ลบ</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM repair WHERE repair_date = '$date' AND repair_status = '$check' ORDER BY room_id";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>
                <?php 
                    }else{
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "ดำเนินการเสร็จสิ้น"){ echo "checked";}} ?>>
                        <label for="scales">ดำเนินการเสร็จสิ้น</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="inprogress" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "กำลังดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">กำลังดำเนินการ</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="pending" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "รอดำเนินการ"){ echo "checked";}} ?>>
                        <label for="scales">รอดำเนินการ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการแจ้งซ่อม</div>
                <?php
                        }
                    }else{
                        echo "<div style='margin:32px 0'>0 results</div>";
                    }
                    ?>
                <div style="display:flex;justify-content:flex-end;">
                    <a href="../repair/addRepair.php"><button>เพิ่มรายการ</button></a>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/repair.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>