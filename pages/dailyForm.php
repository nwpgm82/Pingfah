<?php
    session_start();
    include('connection.php');
    require_once("../lib/PromptPayQR.php");
    function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $getAir_price = mysqli_query($conn,"SELECT daily_price, daily_deposit FROM roomdetail WHERE type='แอร์'");
    $getAir_result = mysqli_fetch_assoc($getAir_price); 
    $getFan_price = mysqli_query($conn,"SELECT daily_price, daily_deposit FROM roomdetail WHERE type='พัดลม'");
    $getFan_result = mysqli_fetch_assoc($getFan_price);
    $_SESSION["check_in"] = @$_POST['check_in'];
    $_SESSION["check_out"] = @$_POST['check_out'];
    // $_SESSION["people"] = @$_POST['people'];
    if(isset($_POST["air"])){
        $_SESSION["air"] = $_POST['air'];
    }else{
        $_SESSION["air"] = 0;
    }
    if(isset($_POST["fan"])){
        $_SESSION["fan"] = $_POST['fan'];
    }else{
        $_SESSION["fan"] = 0;
    }
    $_SESSION["air_price"] = (intval($_SESSION["air"]) * $getAir_result["daily_price"]) * $_SESSION["night"];
    $_SESSION["fan_price"] = (intval($_SESSION["fan"]) * $getFan_result["daily_price"]) * $_SESSION["night"];
    $_SESSION["total_price"] = $_SESSION["air_price"] + $_SESSION["fan_price"];
    $_SESSION["total_room"] = (intval($_SESSION["air"]) * $getAir_result["daily_deposit"]) + (intval($_SESSION["fan"]) * $getFan_result["daily_deposit"]);
    $getPrompt = mysqli_query($conn, "SELECT * FROM promptpay");
    $getPrompt_result = mysqli_fetch_assoc($getPrompt);
    $PromptPayQR = new PromptPayQR(); // new object
    $PromptPayQR->size = 6;
    $PromptPayQR->id = $getPrompt_result["prompt_num"]; // PromptPay ID
    $PromptPayQR->amount = $_SESSION["total_price"]; // Set amount (not necessary)
    // echo '<img src="' . $PromptPayQR->generate() . '" />';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dailyForm.css">
    <link rel="stylesheet" href="../css/mainTop.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../js/dailyForm.js"></script>
    <script src="../js/mainTop.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../components/maintopbar.php'); ?>
    <div class="box">
        <div class="dailyForm">
            <h2>แบบฟอร์มจองห้องพัก</h2>
            <div class="hr"></div>
            <form action="mainpage_function/addDailyForm.php" method="POST" enctype="multipart/form-data" onsubmit="return confirm('คุณต้องการจองห้องพักใช่หรือไม่ ?'); ">
                <div class="grid-container">
                    <div>
                        <p>คำนำหน้าชื่อ<label style="color:red;"> *</label></p>
                        <select name="name_title" id="">
                            <option value="นาย">นาย</option>
                            <option value="นาง">นาง</option>
                            <option value="นางสาว">นางสาว</option>
                        </select>
                    </div>
                    <div>
                        <p>ชื่อ<label style="color:red;"> *</label></p>
                        <input type="text" name="firstname" id="firstname" maxlength="50" placeholder="ชื่อ">
                        <h5 id="fs_error" style="color:red;"></h5>
                    </div>
                    <div>
                        <p>นามสกุล<label style="color:red;"> *</label></p>
                        <input type="text" name="lastname" id="lastname" maxlength="50" placeholder="นามสกุล">
                        <h5 id="ls_error" style="color:red;"></h5>
                    </div>
                    <div>
                        <p>เลขบัตรประชาชน / Passport No.<label style="color:red;"> *</label></p>
                        <input type="text" name="id_card" id="id_card" maxlength="13" placeholder="เลขบัตรประชาชน / Passport No.">
                        <h5 id="id_error" style="color:red;"></h5>
                    </div>
                    <div>
                        <p>อีเมล<label style="color:red;"> *</label></p>
                        <input type="text" name="email" id="email" maxlength="100" placeholder="อีเมล">
                        <h5 id="em_error" style="color:red;"></h5>
                    </div>
                    <div>
                        <p>เบอร์โทรศัพท์<label style="color:red;"> *</label></p>
                        <input type="text" name="tel" id="tel" maxlength="10" placeholder="เบอร์โทรศัพท์">
                        <h5 id="tel_error" style="color:red;"></h5>
                    </div>
                    <!-- <div class="check_in">
                        <p>เช็คอิน</p>
                        <input type="text" name="check_in" id="check_in" value="<?php echo DateThai($_SESSION["check_in"]); ?>" disabled>
                    </div>
                    <div class="check_out">
                        <p>เช็คเอาท์</p>
                        <input type="text" name="check_out" id="check_out" value="<?php echo DateThai($_SESSION["check_out"]); ?>" disabled>
                    </div>
                    <div class="night">
                        <p>จำนวนวันที่พัก (คืน)</p>
                        <input type="text" name="night" id="night" value="<?php echo $_SESSION["night"]; ?>" disabled>
                    </div>
                    <div class="people">
                        <p>จำนวนผู้พัก(ท่าน)</p>
                        <input type="text" name="people" id="people" value="<?php echo $_SESSION["people"]; ?>" disabled>
                    </div>
                    <div class="air">
                        <p>ห้องแอร์(ห้อง)</p>
                        <input type="text" name="air" id="air" value="<?php echo $_SESSION["air"]; ?>" disabled>
                    </div>
                    <div class="fan">
                        <p>ห้องพัดลม(ห้อง)</p>
                        <input type="text" name="fan" id="fan" value="<?php echo $_SESSION["fan"]; ?>" disabled>
                    </div>
                    <div class="total_price">
                        <p>จำนวนเงินรวมทั้งสิ้น (บาท)</p>
                        <input type="text" name="total_price" id="total_price" value="<?php echo number_format($_SESSION["total_price"],2); ?>" disabled>
                    </div> -->
                </div>
                <div class="hr"></div>
                <div>
                    <h3>รายละเอียดราคา</h3>
                    <div class="hr"></div>
                    <div class="price_detail">
                        <div class="bill_header">
                            <h4>วันที่เข้าพัก : <?php echo DateThai($_SESSION["check_in"]); ?> - <?php echo DateThai($_SESSION["check_out"]); ?></h4>
                            <h4>(<?php echo $_SESSION["night"]; ?> คืน)</h4>
                        </div>
                        <table>
                            <?php
                            if($_SESSION["air"] != 0){
                            ?>
                            <tr>
                                <td>
                                    <p><?php echo $_SESSION["air"]; ?> x ห้องแอร์</p>
                                </td>
                                <td>
                                    <p>฿ <?php echo number_format($_SESSION["air_price"] - (($_SESSION["air_price"] * 7)/100),2); ?></p>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php
                            if($_SESSION["fan"] != 0){
                            ?>
                            <tr>
                                <td>
                                    <p><?php echo $_SESSION["fan"]; ?> x ห้องพัดลม</p>
                                </td>
                                <td>
                                    <p>฿ <?php echo number_format($_SESSION["fan_price"] - (($_SESSION["fan_price"] * 7)/100),2); ?></p>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td>
                                    <p>ภาษีมูลค่าเพิ่ม 7%</p>
                                </td>
                                <td>
                                    <p>฿ <?php echo number_format(($_SESSION["total_price"] * 7)/100,2); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>ราคารวมทั้งหมด</p>
                                </td>
                                <td>
                                    <p>฿ <?php echo number_format(ceil($_SESSION["total_price"]),2); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                <div style="padding-top:32px;">
                    <h3>ชำระค่าห้องพัก</h3>
                    <div class="hr"></div>
                    <div class="payment-grid">
                        <div>
                            <h4>1. โปรดชำระเงินจำนวน <?php echo number_format($_SESSION["total_price"]); ?> บาท เป็นค่าเช่าห้องพักผ่าน QR Code ด้านล่าง</h4>
                            <div class="qr-box">
                                <div>
                                  <?php
                                    echo '<img src="' . $PromptPayQR->generate() . '" />';
                                    ?>
                                    <div style="line-height:30px;text-align:center;">
                                        <p><strong>จำนวนเงิน <?php echo number_format($_SESSION["total_price"]); ?> บาท</strong></p>
                                        <p><strong>( <?php echo $getPrompt_result["prompt_num"]." : ".$getPrompt_result["prompt_name"]; ?> )</strong></p>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4>2. เมื่อชำระเงินค่าเช่าห้องพักแล้ว ให้อัปโหลดหลักฐานการชำระเงินด้านล่าง<label style="color:red;"> *</label></h4>
                            <div class="qr-box2">
                                <div class="img-box">
                                    <img id="img_id" style="display:none;">
                                    <iframe id="img_id" style="display:none;" width="100%" height="100%"></iframe>
                                </div>
                                <h5 id="qr_error" style="color:red;"></h5>
                                <input type="file" name="deposit_img" id="deposit_img">
                            </div>
                        </div>
                    </div>
                    <!-- <h3>ขั้นตอนในการจองห้องพัก</h3>
                    <div style="line-height:40px;padding-top:16px;">
                        <p>1. เมื่อจองห้องพักแล้ว ให้โอนเงินจำนวน <strong style="color:red;"><?php echo number_format($_SESSION["total_room"]); ?> บาท</strong> มาที่บัญชีพร้อมเพย์ <strong><?php echo $getPrompt_result["prompt_num"]; ?> (<?php echo $getPrompt_result["prompt_name"]; ?>)</strong> หรือ สแกน QR code ได้<a href="../img/tool/qr-code.png" target="_blank">ที่นี่</a> ก่อนวันที่ <strong style="color:red;"><?php echo $datetime_result; ?></strong> มิเช่นนั้นการจองห้องพักจะถือว่าเป็นโมฆะ</p>
                        <p>2. เมื่อโอนเงินแล้วให้อัปโหลดสลิปในเมนู <a href="checkCode.php" target="_blank">ตรวจสอบการจอง</a> </p>
                        <p>3. เมื่ออัปโหลดสลิปแล้วให้แจ้งเจ้าของหอพัก หรือพนักงานเพื่อแจ้งให้ทราบว่าท่านได้โอนเงินแล้ว</p>
                        <p>4. รอการยืนยันจากเจ้าของหอพัก หรือพนักงาน</p>
                        <p>5. เมื่อได้รับการยืนยันแล้ว ให้ท่านชำระเงิน ณ ที่พัก และเข้าพักตามวันที่ท่านได้จองห้องพักไว้ <strong>(เข้าพักได้ในเวลา 14.00 น. เป็นต้นไป)</strong></p>
                    </div> -->
                </div>
                <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                    <button type="submit" id="confirm_daily" name="accept_daily">ยืนยันการจองห้องพัก</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>