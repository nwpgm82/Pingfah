<?php
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    include('../../components/sidebar.php'); 
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
    <link rel="stylesheet" href="../../../css/roomDailyList.css">
    <title>Document</title>
</head>

<body>
    <div class="box" id="roomDaily">
        <div style="padding:24px;">
            <div class="roomDaily-box">
                <?php
                    $perpage = 8;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                        $roomDailyList = "SELECT * FROM  roomDailyList ORDER BY room_id LIMIT {$start} , {$perpage}";
                        $result = $conn->query($roomDailyList);
                        if ($result->num_rows > 0) {
                ?>
                <table>
                    <tr>
                        <th>เลขห้อง</th>
                        <th>ประเภท</th>
                        <th>เช็คอิน</th>
                        <th>เช็คเอ้าท์</th>
                        <th>สถานะ</th>
                        <th>เพิ่มเติม</th>
                    </tr>
                    <?php
                            while($row = mysqli_fetch_array($result)) { 
                    ?>
                    <form action="function/editType.php?ID=<?php echo $row["room_id"]; ?>" method="POST">
                        <tr>
                            <td>
                                <a
                                    href="room_id.php?ID=<?php echo $row[0] ?>"><?php echo $row["room_id"]; ?></a>
                            </td>
                            <td <?php echo "id='typeShow" .$row["room_id"] . "'" ?>><?php echo $row["room_type"]; ?>
                            </td>
                            <td <?php echo "id='typeEdit" .$row["room_id"] . "'" ."style='display:none'"; ?>>
                                <select name="type">
                                    <option value="พัดลม">พัดลม</option>
                                    <option value="แอร์">แอร์</option>
                                </select>
                            </td>
                            <td><?php if($row['check_in'] != null){ echo DateThai($row['check_in']); } ?></td>
                            <td><?php if($row['check_out'] != null){ echo DateThai($row['check_out']); } ?></td>
                            <td>
                                <?php
                                        if($row["status"] == 'ว่าง'){
                                            echo "<div class='status-available'></div>";
                                        }else{
                                            echo "<div class='status-unavailable'></div>";
                                        }
                                    ?>
                            </td>
                            <td <?php echo "id='typeShow-btn" .$row["room_id"] . "'" ?>>
                                <button type="button" class="edit-btn"
                                    <?php echo "onclick='editType(" .$row["room_id"] .")'" ?>>แก้ไข</button>
                                <button type="button" class="del-btn"
                                    <?php echo "onclick='del(" .$row["room_id"] .")'" ?>>ลบ</button>
                            </td>
                            <td <?php echo "id='typeEdit-btn" .$row["room_id"] . "' style='display:none' " ?>>
                                <button type="submit" class="accept-btn"
                                    <?php echo "onclick='acceptEdit(" .$row["room_id"] .")'" ?>>ยืนยัน</button>
                                <button type="button" class="cancel-btn"
                                    <?php echo "onclick='cancelEdit(" .$row["room_id"] .")'" ?>>ยกเลิก</button>
                            </td>
                        </tr>
                    </form>
                    <?php } ?>
                </table>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM roomDailyList";
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
                        echo "ไม่มีรายการห้องพักรายวัน";
                    }
                    mysqli_close($conn);
                ?>
                <div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button onclick="addRoom()" class="addRoom-btn">เพิ่มห้องพัก</button>
                    </div>
                    <div id="addRoom" style="justify-content:flex-end;">
                        <form action="../roomDailyList/function/addRoom.php" method="POST">
                            <div style="padding-right:8px">
                                <p>ห้อง</p>
                                <input type="text" name="room" required>
                            </div>
                            <div style="padding-right:8px">
                                <p>ประเภท</p>
                                <select name="type">
                                    <option value="พัดลม">พัดลม</option>
                                    <option value="แอร์">แอร์</option>
                                </select>
                            </div>
                            <div style="display:block;margin-top:auto">
                                <button type="submit">ยืนยัน</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/roomDailyList.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>