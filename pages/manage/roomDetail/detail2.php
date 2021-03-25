<?php 
session_start();
if($_SESSION['level'] == 'admin'){
    include('../../connection.php');
    $type = $_REQUEST['type'];
    if($type == "fan"){
        $type_show = "พัดลม";
    }else if($type == "air"){
        $type_show = "แอร์";
    }
    $num = 1;
    $sql = "SELECT * FROM roomdetail WHERE type = '$type_show'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/detail.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Document</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div style="padding:24px;">
        <div class="box">
            <div class="detail-box">
                <div style="padding:36px;">
                    <form action="function/addData.php?type=<?php echo $type; ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="header">
                            <h3>ห้อง<?php echo $type_show; ?></h3>
                            <div id="edit" style="display:flex;justify-content:center;align-items:center;">
                                <button type="button" class="edit-btn" onclick="edit()">แก้ไข</button>
                            </div>
                            <div id="accept" style="display:none;justify-content:center;align-items:center;">
                                <button type="submit" name="accept">ยืนยัน</button>
                                <button type="button" class="cancel-btn" onclick="cancelEdit()">ยกเลิก</button>
                            </div>
                        </div>
                        <div class="hr"></div>
                        <div class="roomDetail">
                            <div>
                                <p>ค่าเช่าห้อง(รายเดือน)</p>
                                <input type="text" name="price"
                                    value="<?php if(isset($row['price'])){ echo $row['price']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/เดือน</label>
                            </div>
                            <div>
                                <p>ค่าเช่าห้อง(รายวัน)</p>
                                <input type="text" name="daily_price"
                                    value="<?php if(isset($row['daily_price'])){ echo $row['daily_price']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/วัน</label>
                            </div>
                            <div>
                                <p>ค่าน้ำ</p>
                                <input type="text" name="water_bill"
                                    value="<?php if(isset($row['water_bill'])){ echo $row['water_bill']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/คน</label>
                            </div>
                            <div>
                                <p>ค่าไฟ (หน่วย)</p>
                                <input type="text" name="elec_bill"
                                    value="<?php if(isset($row['elec_bill'])){ echo $row['elec_bill']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/เดือน</label>
                            </div>
                            <div>
                                <p>ค่าเคเบิล</p>
                                <input type="text" name="cable_charge"
                                    value="<?php if(isset($row['cable_charge'])){ echo $row['cable_charge']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/เดือน</label>
                            </div>
                            <div>
                                <p>ค่าปรับ</p>
                                <input type="text" name="fines"
                                    value="<?php if(isset($row['fines'])){ echo $row['fines']; }else{ echo 0; } ?>"
                                    disabled>
                                <label>บาท/วัน</label>
                            </div>
                        </div>
                        <!-- <div class="hr"></div> -->
                        <div style="padding:32px 0;">
                            <h3>สิ่งอำนวยความสะดวก</h3>
                            <div class="hr"></div>
                            <div class="detail-grid">
                                <div class="sub-grid">
                                    <input type="checkbox" name="sv_air" <?php if($row['sv_air'] == 'on'){ echo "checked"; }?> disabled>
                                    <label>เครื่องปรับอากาศ</label>
                                    <img src="../../../img/tool/air2.png">
                                </div>
                                <div class="sub-grid">
                                    <input type="checkbox" name="sv_fan" <?php if($row['sv_fan'] == 'on'){ echo "checked"; }?> disabled>
                                    <label>พัดลม</label>
                                    <img src="../../../img/tool/fan.png">
                                </div>
                                <div class="sub-grid">
                                    <input type="checkbox" name="sv_wifi" <?php if($row['sv_wifi'] == 'on'){ echo "checked"; }?> disabled>
                                    <label>WI-FI</label>
                                    <img src="../../../img/tool/wifi2.png">
                                </div>
                                <div class="sub-grid">
                                    <input type="checkbox" name="sv_furniture" <?php if($row['sv_furniture'] == 'on'){ echo "checked"; }?> disabled>
                                    <label>เฟอร์นิเจอร์ - ตู้เสื้อผ้า, เตียง</label>
                                    <img src="../../../img/tool/clothes.png">
                                </div>
                                <div class="sub-grid">
                                    <input type="checkbox" name="sv_readtable" <?php if($row['sv_readtable'] == 'on'){ echo "checked"; }?> disabled>
                                    <label>โต๊ะอ่านหนังสือ</label>
                                    <img src="../../../img/tool/table.png">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="hr"></div>
                <div style="padding: 36px 36px 0 36px;">
                    <div class="header">
                        <h3>รูปภาพ</h3>
                        <div>
                            <input type="file" name="file" id="file" class="inputfile" accept="images/*" />
                            <label for="file">เพิ่มรูปภาพ</label>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div class="grid">
                        <?php
                            $perpage = 8;
                            if(isset($_GET['gal_page'])){
                                $page = $_GET['gal_page'];
                            }else{
                                $page = 1;
                            }
                            $start = ($page - 1) * $perpage;
                            if($type == "fan"){
                                $sql = "SELECT * FROM fan_gal ORDER BY gal_id DESC LIMIT {$start} , {$perpage}";
                            }else if($type == "air"){
                                $sql = "SELECT * FROM air_gal ORDER BY gal_id DESC LIMIT {$start} , {$perpage}";
                            }
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                            ?>
                        <div class="img-box">
                            <img src="../../images/roomdetail/<?php echo $type; ?>/<?php echo $row['gal_name']; ?>"
                                alt="">
                            <button type="button" class="del-btn" id="del-btn"
                                onclick="delImg('<?php echo $type; ?>',<?php echo $row['gal_id']; ?>,'<?php echo $row['gal_name']; ?>')">X</button>
                        </div>
                        <?php
                                    $num++;
                                }
                            }else{
                                echo "ไม่มีรูปภาพ";
                            }
                            ?>
                    </div>
                    <?php
                        ///////pagination
                        if($type == "fan"){
                            $sql2 = "SELECT * FROM fan_gal";
                        }else if($type == "air"){
                            $sql2 = "SELECT * FROM air_gal";
                        }
                        $query2 = mysqli_query($conn, $sql2);
                        $total_record = mysqli_num_rows($query2);
                        $total_page = ceil($total_record / $perpage);
                        if($total_page != 0){
                        ?>
                    <div style="display:flex;justify-content:flex-end">
                        <div class="pagination">
                            <?php
                                if($type == "fan"){
                                ?>
                            <a href="detail.php?type=fan">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="detail.php?type=fan&gal_page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="detail.php?type=fan&gal_page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php
                                }else if($type == "air"){
                                ?>
                            <a href="detail.php?type=air">&laquo;</a>
                            <?php for($i=1;$i<=$total_page;$i++){ ?>
                            <a href="detail.php?type=air&gal_page=<?php echo $i; ?>"
                                <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?>><?php echo $i; ?></a>
                            <?php } ?>
                            <a href="detail.php?type=air&gal_page=<?php echo $total_page; ?>">&raquo;</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>


            </div>
        </div>
    </div>
    <script src="../../../js/admin/detail.js"></script>
</body>

</html>
<?php
}
    }else{
        echo "0 results";
    }
}else{
    Header("Location: ../../../login.php"); 
}
?>