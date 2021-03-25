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
        return "$strDay $strMonthThai $strYear";
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/package.css">
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="package-box">
                <h3>ค้นหารายการพัสดุ</h3>
                <div style="padding-top:32px">
                    <label>ค้นหาตามวันที่</label>
                    <input type="date" id="package_date"
                        value="<?php if(isset($date)){ echo $date; }else{ echo ""; } ?>" onchange="searchDate(value)">
                </div>
                <div class="hr"></div>
                <h3>รายการพัสดุทั้งหมด</h3>
                <?php
                    $perpage = 5;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    if(!isset($date) && !isset($check)){
                        $sql = "SELECT * FROM package ORDER BY package_arrived LIMIT {$start} , {$perpage} ";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขพัสดุ</th>
                        <th>บริษัท</th>
                        <th>พัสดุมาถึง</th>
                        <th>สถานะ</th>
                        <th>เจ้าของพัสดุ</th>
                        <th>เลขห้อง</th>
                        <th>รับโดย</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php
                        while($row = $result->fetch_assoc()) {
                        ?>
                    <form action="../package/function/receivedPackage.php?ID=<?php echo $row["package_num"]; ?>"
                        method="POST">
                        <tr>
                            <!-- <td style="width:70px;text-align:center"></td> -->
                            <td><?php echo $row["package_num"] ?></td>
                            <td><?php echo $row["package_company"] ?></td>
                            <td><?php echo DateThai($row["package_arrived"]) ?></td>
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
                            <td><?php echo $row["package_name"] ?></td>
                            <td><?php echo $row["package_room"] ?></td>
                            <td>
                                <?php
                                        if(strlen($row["package_received"]) == 0){
                                            echo "<input type='text' name='received' required>";
                                        }else{
                                            echo $row["package_received"];
                                        }
                                    ?>
                            </td>
                            <td>
                                <?php
                                            if(strlen($row["package_received"]) == 0){
                                                echo "<button type='submit' class='received-btn'>รับ</button>";
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }else{
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }
                                        ?>

                            </td>
                        </tr>
                    </form>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM package";
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
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการพัสดุ</div>
                <?php
                        }
                    }else if(isset($date) && !isset($check)){
                        ///เมื่อค้นหาด้วยวันที่/////
                        $packageData = "SELECT * FROM package WHERE package_arrived = '$date' ORDER BY package_arrived";
                        // $repairCount = "SELECT COUNT(*) FROM repair WHERE repair_date = '$date'";
                        // $resultCount = $conn->query($repairCount);
                        // $rowCount = $resultCount->fetch_row();
                        $result = $conn->query($packageData);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "success"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขพัสดุ</th>
                        <th>บริษัท</th>
                        <th>พัสดุมาถึง</th>
                        <th>สถานะ</th>
                        <th>เจ้าของพัสดุ</th>
                        <th>เลขห้อง</th>
                        <th>รับโดย</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php
                        while($row = $result->fetch_assoc()) {
                        ?>
                    <form action="../package/function/receivedPackage.php?ID=<?php echo $row["package_num"]; ?>"
                        method="POST">
                        <tr>
                            <!-- <td style="width:70px;text-align:center"></td> -->
                            <td><?php echo $row["package_num"] ?></td>
                            <td><?php echo $row["package_company"] ?></td>
                            <td><?php echo DateThai($row["package_arrived"]) ?></td>
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
                            <td><?php echo $row["package_name"] ?></td>
                            <td><?php echo $row["package_room"] ?></td>
                            <td>
                                <?php
                                        if(strlen($row["package_received"]) == 0){
                                            echo "<input type='text' name='received' required>";
                                        }else{
                                            echo $row["package_received"];
                                        }
                                    ?>
                            </td>
                            <td>
                                <?php
                                            if(strlen($row["package_received"]) == 0){
                                                echo "<button type='submit' class='received-btn'>รับ</button>";
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }else{
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }
                                        ?>

                            </td>
                        </tr>
                    </form>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM package WHERE package_arrived = '$date' ORDER BY package_arrived";
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
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "unsuccess"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการพัสดุ</div>
                <?php
                    }}else if(isset($check) && !isset($date)){
                        if($check == "success"){
                            $check = "รับพัสดุแล้ว";
                        }else if($check == "unsuccess"){
                            $check = "ยังไม่ได้รับพัสดุ";
                        }
                        $packageDataCheck = "SELECT * FROM package WHERE package_status = '$check' ORDER BY package_arrived LIMIT {$start} , {$perpage}";
                        $result = $conn->query($packageDataCheck);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "รับพัสดุแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "ยังไม่ได้รับพัสดุ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขพัสดุ</th>
                        <th>บริษัท</th>
                        <th>พัสดุมาถึง</th>
                        <th>สถานะ</th>
                        <th>เจ้าของพัสดุ</th>
                        <th>เลขห้อง</th>
                        <th>รับโดย</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php
                        while($row = $result->fetch_assoc()) {
                        ?>
                    <form action="../package/function/receivedPackage.php?ID=<?php echo $row["package_num"]; ?>"
                        method="POST">
                        <tr>
                            <!-- <td style="width:70px;text-align:center"></td> -->
                            <td><?php echo $row["package_num"] ?></td>
                            <td><?php echo $row["package_company"] ?></td>
                            <td><?php echo DateThai($row["package_arrived"]) ?></td>
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
                            <td><?php echo $row["package_name"] ?></td>
                            <td><?php echo $row["package_room"] ?></td>
                            <td>
                                <?php
                                        if(strlen($row["package_received"]) == 0){
                                            echo "<input type='text' name='received' required>";
                                        }else{
                                            echo $row["package_received"];
                                        }
                                    ?>
                            </td>
                            <td>
                                <?php
                                            if(strlen($row["package_received"]) == 0){
                                                echo "<button type='submit' class='received-btn'>รับ</button>";
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }else{
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }
                                        ?>

                            </td>
                        </tr>
                    </form>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM package WHERE package_status = '$check' ORDER BY package_arrived";
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
                            <?php if(isset($check)){ if($check == "รับพัสดุแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck(this.id)"
                            <?php if(isset($check)){ if($check == "ยังไม่ได้รับพัสดุ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการพัสดุ</div>
                <?php
                        }
                    }else if(isset($date) && isset($check)){
                        if($check == "success"){
                            $check = "รับพัสดุแล้ว";
                        }else if($check == "unsuccess"){
                            $check = "ยังไม่ได้รับพัสดุ";
                        }
                        $packageDataCheck2 = "SELECT * FROM package WHERE package_arrived = '$date' AND package_status = '$check' ORDER BY package_arrived LIMIT {$start} , {$perpage}";   
                        $result = $conn->query($packageDataCheck2);
                        if ($result->num_rows > 0) {
                    ?>
                <div style="display:flex;align-items:center;">
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="success" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "รับพัสดุแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "ยังไม่ได้รับพัสดุ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <table>
                    <tr>
                        <th>เลขพัสดุ</th>
                        <th>บริษัท</th>
                        <th>พัสดุมาถึง</th>
                        <th>สถานะ</th>
                        <th>เจ้าของพัสดุ</th>
                        <th>เลขห้อง</th>
                        <th>รับโดย</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php
                        while($row = $result->fetch_assoc()) {
                        ?>
                    <form action="../package/function/receivedPackage.php?ID=<?php echo $row["package_num"]; ?>"
                        method="POST">
                        <tr>
                            <!-- <td style="width:70px;text-align:center"></td> -->
                            <td><?php echo $row["package_num"] ?></td>
                            <td><?php echo $row["package_company"] ?></td>
                            <td><?php echo DateThai($row["package_arrived"]) ?></td>
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
                            <td><?php echo $row["package_name"] ?></td>
                            <td><?php echo $row["package_room"] ?></td>
                            <td>
                                <?php
                                        if(strlen($row["package_received"]) == 0){
                                            echo "<input type='text' name='received' required>";
                                        }else{
                                            echo $row["package_received"];
                                        }
                                    ?>
                            </td>
                            <td>
                                <?php
                                            if(strlen($row["package_received"]) == 0){
                                                echo "<button type='submit' class='received-btn'>รับ</button>";
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }else{
                                                echo "<a href='../package/function/delPackage.php?ID=" .$row["package_num"] ."'><button type='button' class='del-btn'>ลบ</button></a>";
                                            }
                                        ?>

                            </td>
                        </tr>
                    </form>
                    <?php } ?>
                </table>
                <?php
                    ///////pagination
                    $sql2 = "SELECT * FROM package WHERE package_arrived = '$date' AND package_status = '$check' ORDER BY package_arrived";
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
                            <?php if(isset($check)){ if($check == "รับพัสดุแล้ว"){ echo "checked";}} ?>>
                        <label for="scales">รับพัสดุแล้ว</label>
                    </div>
                    <div style="padding:32px 16px;">
                        <input type="checkbox" id="unsuccess" onchange="searchCheck2('<?php echo $date ?>',this.id)"
                            <?php if(isset($check)){ if($check == "ยังไม่ได้รับพัสดุ"){ echo "checked";}} ?>>
                        <label for="scales">ยังไม่ได้รับพัสดุ</label>
                    </div>
                    <button onclick="unCheckAll()">ยกเลิกการกรองทั้งหมด</button>
                </div>
                <div style='margin:32px 0'>ไม่มีรายการพัสดุ</div>
                <?php
                        }
                    }else{
                        echo "<div style='margin:32px 0'>0 results</div>";
                    }
                    ?>
                <div id="addPackage">
                    <form action="/Pingfah/pages/admin/package/function/addPackage.php" method="POST">
                        <div style="padding-right:8px">
                            <p>เลขพัสดุ</p>
                            <input type="text" name="num" required>
                        </div>
                        <div style="padding-right:8px">
                            <p>บริษัท</p>
                            <input type="text" name="company" required>
                        </div>
                        <div style="padding-right:8px">
                            <p>เวลาที่พัสดุมาถึง</p>
                            <input type="date" name="arrived" required>
                        </div>
                        <div style="padding-right:8px">
                            <p>ชื่อเจ้าของ</p>
                            <input type="text" name="name" required>
                        </div>
                        <div style="padding-right:8px">
                            <p>เลขห้อง</p>
                            <input type="text" name="room" required>
                        </div>
                        <div style="display:block;margin-top:auto">
                            <button type="submit">ยืนยัน</button>
                        </div>
                    </form>
                </div>
                <div style="display:flex;justify-content:flex-end">
                    <button onclick="addPackage()">เพิ่มรายการพัสดุ</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/package.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>