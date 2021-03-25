<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include('../../connection.php');
    $num = 1;  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../css/roomDetail.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="roomDetail-box">
                <h3>ประเภทห้องพักทั้งหมด</h3>
                <div class="hr"></div>
                <div class="grid">
                    <?php 
                    $sql = "SELECT * FROM roomDetail";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {  
                    while($row = $result->fetch_assoc()) { 
                    ?>
                    <div class="card">
                        <div class="container">
                            <?php
                            if($row['type'] == "พัดลม"){
                                $type_show = "fan";
                                $getImg = "SELECT gal_name FROM fan_gal";
                            }else if($row['type'] == "แอร์"){
                                $type_show = "air";
                                $getImg = "SELECT gal_name FROM air_gal";
                            }
                            $resultImg = $conn->query($getImg);
                            if ($resultImg->num_rows > 0) {
                                while($row2 = $resultImg->fetch_assoc()) { 
                            ?>
                            <div class="mySlides<?php echo $num; ?>">
                                <img src="../../images/roomdetail/<?php echo $type_show; ?>/<?php echo $row2['gal_name']; ?>">
                            </div>
                            <?php } }else{ ?>
                            <div class="mySlides<?php echo $num; ?>">
                                <img src="../../../img/tool/no-img.png">
                            </div>
                            <?php } ?>
                            <?php
                            if($resultImg->num_rows > 1){
                            ?>
                            <a class="prev" onclick="plusSlides<?php echo $num; ?>(-1)">&#10094;</a>
                            <a class="next" onclick="plusSlides<?php echo $num; ?>(1)">&#10095;</a>
                            <?php } ?>
                        </div>
                        <div class="detail">
                            <div>
                                <h3>ห้อง<?php echo $row['type'];?></h3>
                                <p>รายเดือน : <?php echo number_format($row['price']); ?> บาท / เดือน</p>
                                <p>รายวัน : <?php echo number_format($row['daily_price']); ?> บาท / คืน</p>
                            </div>
                            <div style="display:flex;justify-content:flex-end;">
                                <a
                                    href="detail.php?type=<?php if($row['type'] == 'พัดลม'){ echo 'fan'; }else if($row['type'] == 'แอร์'){ echo 'air'; }?>"><button>ดูข้อมูลเพิ่มเติม</button></a>
                            </div>
                        </div>
                    </div>
                    <?php $num++; } ?>
                </div>
            </div>
            <div id="prompt-box">
                <div class="roomDetail-box" id="prompt-content" style="margin-top:32px;">
                    <div class="header">
                        <h3>รายละเอียดอื่น ๆ</h3>
                        <?php
                        if($_SESSION["level"] == "admin"){
                        ?>
                        <button type="button" class="edit-btn" id="edit-prompt"></button>
                        <div class="option-grid" id="prompt-option" style="display:none;">
                            <button type="button" class="correct-btn" id="correct-prompt"></button>
                            <button type="button" class="cancel-btn" id="cancel-prompt"></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="hr"></div>
                    <div>
                        <?php
                        $searchPrompt = mysqli_query($conn, "SELECT * FROM promptpay");
                        $resultPrompt = mysqli_fetch_assoc($searchPrompt);
                        ?>
                        <h3>รายละเอียดการชำระเงิน</h3>
                        <div class="content">
                            <div class="topic-box">
                                <div>
                                    <p>เลขพร้อมเพย์ (PromptPay No.)</p>
                                    <input type="text" id="prompt_num" value="<?php if($resultPrompt != null){ echo $resultPrompt["prompt_num"]; } ?>" maxlength="10" disabled>
                                    <h5 id="prompt_error" style="color:red;"></h5>
                                </div>
                                <div>
                                    <p>ชื่อเจ้าของพร้อมเพย์ (PromptPay Name.)</p>
                                    <input type="text" id="prompt_name" value="<?php if($resultPrompt != null){ echo $resultPrompt["prompt_name"]; } ?>" disabled>
                                    <h5 id="prompt_name_error" style="color:red;"></h5>
                                </div>
                            </div>
                            <div class="hr" style="margin:0;"></div>
                            <div style="padding:16px;">
                                <div class="img-box">
                                    <img src="<?php if($resultPrompt != null){ echo "../../images/promptpay/".$resultPrompt["prompt_img"]; } ?>" alt="" id="img_prompt" <?php if($resultPrompt == null){ echo "style='display:none;'"; } ?>>
                                </div>
                                <h5 id="img_error" style="color:red;"></h5>
                                <?php
                                if($_SESSION["level"] == "admin"){
                                ?>
                                <input type="file" name="" id="prompt_img" accept="images/*" disabled>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/roomDetail.js"></script>
</body>

</html>
<?php
    }else{
        echo "0 results";
    }
}else{
    Header("Location: ../../login.php"); 
}
?>