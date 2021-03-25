<?php 
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    
    function textFormat( $text = '', $pattern = '', $ex = '' ) {
        $cid = ( $text == '' ) ? '0000000000000' : $text;
        $pattern = ( $pattern == '' ) ? '_-____-_____-__-_' : $pattern;
        $p = explode( '-', $pattern );
        $ex = ( $ex == '' ) ? '-' : $ex;
        $first = 0;
        $last = 0;
        for ( $i = 0; $i <= count( $p ) - 1; $i++ ) {
           $first = $first + $last;
           $last = strlen( $p[$i] );
           $returnText[$i] = substr( $cid, $first, $last );
        }
      
        return implode( $ex, $returnText );
     }
     $firstname = @$_REQUEST["firstname"];
     $lastname = @$_REQUEST["lastname"];
     $check = @$_REQUEST["position"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/employee.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/manage/employee.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="employee-box">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <h3>รายชื่อพนักงาน</h3>
                    <div class="option-grid">
                        <a href="emHistory.php"><button class="history-btn"></button></a>
                        <a href="../employee/addemployee.php"><button>เพิ่มพนักงาน</button></a>
                    </div>
                </div>
                <div class="hr"></div>
                <div id="search-box" style="height:86px;">
                    <div class="search">
                        <div style="padding-right:8px;">
                            <p>ชื่อ</p>
                            <input type="text" id="firstname" value="<?php echo $firstname; ?>"
                                placeholder="ค้นหาชื่อพนักงาน">
                        </div>
                        <div style="padding-right:8px;">
                            <p>นามสกุล</p>
                            <input type="text" id="lastname" value="<?php echo $lastname; ?>"
                                placeholder="ค้นหานามสกุลพนักงาน">
                        </div>
                        <button id="search_em">ค้นหาพนักงาน</button>
                        <?php
                        if(isset($firstname) || isset($lastname)){
                        ?>
                        <div style="padding:0 16px;">
                            <a href="index.php"><button type="button"
                                    class="cancel-sort">ยกเลิกการกรองทั้งหมด</button></a>
                        </div>
                        <?php } ?>
                    </div>
                    <h5 id="input_error" style="color:red;"></h5>
                </div>
                <div class="checkbox-flex">
                    <div>
                        <input type="checkbox" id="all" <?php if(!isset($check)){ echo "checked"; } ?>>
                        <label>ทั้งหมด</label>
                    </div>
                    <div>
                        <input type="checkbox" id="c_admin" <?php if($check == "admin"){ echo "checked"; } ?>>
                        <label>admin</label>
                    </div>
                    <div>
                        <input type="checkbox" id="c_employee" <?php if($check == "employee"){ echo "checked"; } ?>>
                        <label>พนักงาน</label>
                    </div>
                </div>
                <div style="padding-top:32px;">
                    <?php
                    if(isset($firstname) && !isset($lastname)){
                        echo '<h3>ผลการค้นหาชื่อ "'.$firstname.'"</h3>';
                    }else if(!isset($firstname) && isset($lastname)){
                        echo '<h3>ผลการค้นหานามสกุล "'.$lastname.'"</h3>';
                    }else if(isset($firstname) && isset($lastname)){
                        echo '<h3>ผลการค้นหาชื่อ "'.$firstname.' '.$lastname.'"</h3>';
                    }else{
                        echo "<h3>รายชื่อพนักงานทั้งหมด</h3>";
                    }
                    $perpage = 10;
                    if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    } else {
                    $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    $num = $start + 1;
                    if(isset($firstname) && !isset($lastname) && !isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE firstname = '$firstname' AND employee_status = 'กำลังทำงาน' limit {$start} , {$perpage}";
                    }else if(!isset($firstname) && isset($lastname) && !isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE lastname = '$lastname' AND employee_status = 'กำลังทำงาน' limit {$start} , {$perpage}";
                    }else if(isset($firstname) && isset($lastname) && !isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE firstname = '$firstname' AND lastname = '$lastname' AND employee_status = 'กำลังทำงาน' limit {$start} , {$perpage}";
                    }else if(isset($firstname) && !isset($lastname) && isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE firstname = '$firstname' AND employee_status = 'กำลังทำงาน' AND position = '$check' limit {$start} , {$perpage}";
                    }else if(!isset($firstname) && isset($lastname) && isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE lastname = '$lastname' AND employee_status = 'กำลังทำงาน' AND position = '$check' limit {$start} , {$perpage}";
                    }else if(isset($firstname) && isset($lastname) && isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE firstname = '$firstname' AND lastname = '$lastname' AND employee_status = 'กำลังทำงาน' AND position = '$check' limit {$start} , {$perpage}";
                    }else if(!isset($firstname) && !isset($lastname) && isset($check)){
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE employee_status = 'กำลังทำงาน' AND position = '$check' limit {$start} , {$perpage}";
                    }else{
                        $sql = "SELECT employee_id, title_name, firstname, lastname, position, id_card, tel, email, profile_img FROM employee WHERE employee_status = 'กำลังทำงาน' limit {$start} , {$perpage}";
                    }
                    $result = $conn->query($sql);  
                    if ($result->num_rows > 0) {
                    ?>
                    <div style="overflow-x: auto;overflow-y:hidden;">
                        <table>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อพนักงาน</th>
                                <th>ตำแหน่ง</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>อีเมล</th>
                                <th>เพิ่มเติม</th>
                            </tr>
                            <?php while($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td>
                                    <div style="display:flex;justify-content:center;">
                                        <?php echo $num; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="grid">
                                        <img src="../../images/employee/<?php echo $row['id_card']; ?>/<?php echo $row["profile_img"]; ?>"
                                            alt="">
                                        <p><?php echo $row["firstname"]; ?> <?php echo $row["lastname"]; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <p><?php if($row["position"] == "employee"){ echo "พนักงาน"; }else{ echo $row["position"]; } ?>
                                        </p>
                                    </div>
                                </td>
                                <td><?php echo textFormat($row["tel"],"___-_______"); ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td>
                                    <div class="grid-btn">
                                        <a href="../employee/emDetail.php?employee_id=<?php echo $row['employee_id']; ?>"><button type="button" class="more-btn" title="แสดงข้อมูลเพิ่มเติม">แสดงข้อมูลเพิ่มเติม</button></a>
                                        <button type="button" class="del-btn" id="<?php echo $row["employee_id"]; ?>" title="ลบข้อมูล">ลบ</button>
                                    </div>
                                </td>
                            </tr>
                            <?php $num++; } ?>
                        </table>
                    </div>
                    <?php
                    $sql2 = "SELECT * FROM employee";
                    $query2 = mysqli_query($conn, $sql2);
                    $total_record = mysqli_num_rows($query2);
                    $total_page = ceil($total_record / $perpage);
                    ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <a href="index.php?page=1">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="index.php?page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                        </div>
                    </div>
                    <?php }else{
                        echo "<div style='padding-top:32px;'>ไม่มีรายชื่อพนักงานที่ค้นหา</div>";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>