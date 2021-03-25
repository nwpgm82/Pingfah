<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    include('../../../components/sidebar.php');
    $date = @$_REQUEST['Date'];
    $check = @$_REQUEST['Status'];
    function DateThai($strDate){
    	$strYear = date("Y",strtotime($strDate))+543;
    	$strMonth= date("n",strtotime($strDate));
    	$strDay= date("j",strtotime($strDate));
    	$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    	$strMonthThai=$strMonthCut[$strMonth];
    	return "$strMonthThai $strYear";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/cost.css">
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="cost-box">
                <h3>ค้นหารายการชำระเงิน</h3>
                <div style="padding-top:32px">
                    <label>ค้นหาตามเดือน</label>
                    <input type="month" id="cost_date" value="<?php if(isset($date)){ echo $date; }else{ echo ""; } ?>"
                        onchange="searchDate(value)">
                </div>
                <div class="hr"></div>
                <h3>รายการชำระเงินทั้งหมด</h3>
                <?php
                $perpage = 5;
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                }else{
                    $page = 1;
                }
                $start = ($page - 1) * $perpage;
                if(!isset($date) && !isset($check)){
                    $sql = "SELECT * FROM cost ORDER BY date LIMIT {$start} , {$perpage} ";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
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
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                    <tr>
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
                        <?php
                            if($row['cost_status'] != 'ชำระเงินแล้ว'){
                            ?>
                        <td>
                            <button class="confirm-status"
                                onclick="confirmStatus(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ยืนยันการชำระ</button>
                                <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php 
                            }else{
                            ?>
                        <td>
                            <button class="confirmed-status">ยืนยันการชำระ</button>
                            <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM cost";
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
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการชำระเงินรายเดือน</div>
                <?php
                    }
                }else if(isset($date) && !isset($check)){
                    ///เมื่อค้นหาด้วยวันที่/////
                    $costData = "SELECT * FROM cost WHERE date = '$date' ORDER BY room_id";
                    // $repairCount = "SELECT COUNT(*) FROM repair WHERE repair_date = '$date'";
                    // $resultCount = $conn->query($repairCount);
                    // $rowCount = $resultCount->fetch_row();
                    $result = $conn->query($costData);
                    if ($result->num_rows > 0) {
                ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                        <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                        <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <!-- <th>ประเภท</th> -->
                        <th>เวลาที่ออกบิล</th>
                        <th>ค่าห้อง</th>
                        <th>ค่าน้ำ</th>
                        <th>ค่าไฟ</th>
                        <th>ค่าเคเบิล</th>
                        <th>ค่าปรับ</th>
                        <th>ราคารวม</th>
                        <th>สถานะการจ่ายเงิน</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                    <tr>
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
                        <?php
                            if($row['cost_status'] != 'ชำระเงินแล้ว'){
                            ?>
                        <td>
                            <button class="confirm-status"
                                onclick="confirmStatus(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ยืนยันการชำระ</button>
                                <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php 
                            }else{
                            ?>
                        <td>
                            <button class="confirmed-status">ยืนยันการชำระ</button>
                            <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM cost WHERE date = '$date' ORDER BY room_id";
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
                        <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                        <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการชำระเงินรายเดือน</div>
                <?php
                }}else if(isset($check) && !isset($date)){
                    if($check == "success"){
                        $check = "ชำระเงินแล้ว";
                    }else if($check == "unsuccess"){
                        $check = "ยังไม่ได้ชำระ";
                    }
                    $costDataCheck = "SELECT * FROM cost WHERE cost_status = '$check' ORDER BY room_id LIMIT {$start} , {$perpage}";
                    $result = $conn->query($costDataCheck);
                    if ($result->num_rows > 0) {
                ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <!-- <th>ประเภท</th> -->
                        <th>เวลาที่ออกบิล</th>
                        <th>ค่าห้อง</th>
                        <th>ค่าน้ำ</th>
                        <th>ค่าไฟ</th>
                        <th>ค่าเคเบิล</th>
                        <th>ค่าปรับ</th>
                        <th>ราคารวม</th>
                        <th>สถานะการจ่ายเงิน</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                    <tr>
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
                        <?php
                            if($row['cost_status'] != 'ชำระเงินแล้ว'){
                            ?>
                        <td>
                            <button class="confirm-status" onclick="confirmStatus(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ยืนยันการชำระ</button>
                            <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php 
                            }else{
                            ?>
                        <td>
                            <button class="confirmed-status">ยืนยันการชำระ</button>
                            <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM cost WHERE cost_status = '$check' ORDER BY room_id";
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
                        <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                        <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการชำระเงินรายเดือน</div>
                <?php
                    }
                }else if(isset($date) && isset($check)){
                    if($check == "success"){
                        $check = "ชำระเงินแล้ว";
                    }else if($check == "unsuccess"){
                        $check = "ยังไม่ได้ชำระ";
                    }
                    $costDataCheck2 = "SELECT * FROM cost WHERE date = '$date' AND cost_status = '$check' ORDER BY room_id LIMIT {$start} , {$perpage}";   
                    $result = $conn->query($costDataCheck2);
                    if ($result->num_rows > 0) {
                ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                        <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                        <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <!-- <th>ประเภท</th> -->
                        <th>เวลาที่ออกบิล</th>
                        <th>ค่าห้อง</th>
                        <th>ค่าน้ำ</th>
                        <th>ค่าไฟ</th>
                        <th>ค่าเคเบิล</th>
                        <th>ค่าปรับ</th>
                        <th>ราคารวม</th>
                        <th>สถานะการจ่ายเงิน</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()){ ?>
                    <tr>
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
                        <?php
                            if($row['cost_status'] != 'ชำระเงินแล้ว'){
                            ?>
                        <td>
                            <button class="confirm-status"
                                onclick="confirmStatus(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ยืนยันการชำระ</button>
                                <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php 
                            }else{
                            ?>
                        <td>
                            <button class="confirmed-status">ยืนยันการชำระ</button>
                            <button class="del" onclick="delcost(<?php echo $row['room_id'] .",'" .$row['date'] ."'"; ?>)">ลบ</button>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM cost WHERE date = '$date' AND cost_status = '$check' ORDER BY room_id";
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
                            <?php if(isset($check)){ if($check == "ชำระเงินแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">ชำระเงินแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "ยังไม่ได้ชำระ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้ชำระ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการชำระเงินรายเดือน</div>
                <?php
                    }
                }else{
                    echo "<div style='margin:32px 0'>0 results</div>";
                }
                ?>
                <div style="display:flex;justify-content:flex-end;">
                    <a href="../cost/addcost.php"><button>เพิ่มรายการ</button></a>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/costDate.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>