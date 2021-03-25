<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee" || $_SESSION["level"] == "guest"){
    include("../../connection.php");
    $room_id = $_REQUEST["ID"];
    @$get_people = $_REQUEST["people"];
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("d",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
        if(intval($get_people) == 1 || $get_people == ""){
            $sql = "SELECT room_id, come_date, member_status, name_title, firstname, lastname, nickname, id_card, phone, email, birthday, age, race, nationality, job, address, pic_idcard, pic_home, people FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'";
        }else if(intval($get_people) == 2 ){
            $sql = "SELECT room_id, come_date, member_status, name_title2, firstname2, lastname2, nickname2, id_card2, phone2, email2, birthday2, age2, race2, nationality2, job2, address2, pic_idcard2, pic_home2, people FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'";
        }
    }else if($_SESSION["level"] == "guest"){
        if(intval($get_people) == 1 || $get_people == ""){
            $sql = "SELECT room_id, come_date, member_status, name_title, firstname, lastname, nickname, id_card, phone, email, birthday, age, race, nationality, job, address, pic_idcard, pic_home, people FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' AND member_id = ".$_SESSION["member_id"];
        }else if(intval($get_people) == 2 ){
            $sql = "SELECT room_id, come_date, member_status, name_title2, firstname2, lastname2, nickname2, id_card2, phone2, email2, birthday2, age2, race2, nationality2, job2, address2, pic_idcard2, pic_home2, people FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' AND member_id = ".$_SESSION["member_id"];
        }
    }
    $result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
    $row = mysqli_fetch_array($result);
    if($row != null){
        extract($row);
    }    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/roomform.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <script src="../../../js/manage/room_id_form.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="roomform-box">
                <?php if($row == null){ ?>
                <div class="new_customer">
                    <div>
                        <h3>ห้อง <?php echo $room_id; ?> ยังว่างอยู่ กดปุ่ม "เพิ่มผู้พัก" เพื่อเพิ่มข้อมูลผู้เข้าพัก</h3>
                        <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                            <button id="add-btn">เพิ่มผู้พัก</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div id="form-box" <?php if($row == null){ echo "style='display:none;'"; }?> >
                    <form action="function/action.php?ID=<?php echo $room_id; ?>&people=<?php if($get_people != ""){ echo $get_people; }else{ echo "1"; } ?>" method="POST"
                        enctype="multipart/form-data" <?php if($row == null){ ?> onsubmit="return confirm('คุณต้องการเพิ่มข้อมูลผู้พักใช่หรือไม่ ?'); " <?php } ?>>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                           <h3>ห้อง <?php echo $room_id; ?></h3>
                           <?php if($row != null && ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee")){ ?>
                           <div id="option-btn" class="option-grid" <?php if($_SESSION["level"] == "employee"){ echo 'style="grid-template-columns: auto 40px 40px;grid-template-areas:'."'plus quit edit';".'"'; } ?>>
                                <?php if($people == 1){ ?>
                                <a href="addmember.php?ID=<?php echo $room_id; ?>" class="plus"><button type="button" class="plus-btn" title="เพิ่มข้อมูล (คนที่ 2)"></button></a>
                                <?php }else{ ?>
                                <a href="room_id.php?ID=<?php echo $room_id; ?>&people=<?php if(intval($get_people) == 1 || $get_people == ""){ echo "2"; }else if(intval($get_people) == 2){ echo "1"; } ?>"><button type="button">คนที่ <?php if(intval($get_people) == 1 || $get_people == ""){ echo "2"; }else if(intval($get_people) == 2){ echo "1"; } ?></button></a>
                                <?php } ?>
                                <a href="member_quit.php?ID=<?php echo $room_id; ?>"><button type="button" class="quit quit-btn" id="quit" name="quit" title="แจ้งออก"></button></a>
                                <button type="button" class="edit edit-btn" id="edit" title="แก้ไข"></button>
                                <?php
                                if($_SESSION["level"] == "admin"){
                                ?>
                                <button type="submit" class="del del-btn" id="del_data" name="del_data" title="ลบข้อมูล"></button>
                                <?php } ?>
                           </div> 
                           <div id="edit-option" style="width:90px;display:none;justify-content:space-between;align-items:center;">
                                <button type="submit" class="correct-btn" id="accept-edit" name="accept-edit" title="ยืนยันการแก้ไข"></button>
                                <button type="button" class="cancel-btn" id="cancel-edit" title="ยกเลิกการแก้ไข"></button>
                           </div>
                           <?php } ?>
                        </div>
                        <div class="hr"></div>
                        <div class="grid-container">
                            <div class="come" style="position:relative;">
                                <p>วันที่เริ่มเข้าพัก<label style="color:red;"> *</label></p>
                                <input type="text" <?php if($row != null){ echo "style='background-image: none'"; } ?> name="come" id="come_date" placeholder="วันที่เริ่มเข้าพัก" value="<?php if($row != null){ echo DateThai($come_date); }else{ echo DateThai(date("Y-m-d")); }?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="come_error" style="color:red;"></h5>
                            </div>
                            <?php
                            if($row != null){
                            ?>
                            <div class="status">
                                <p>สถานะการเข้าพัก</p>
                                <input type="text" value="<?php echo $member_status; ?>" disabled>
                            </div>
                            <?php } ?>
                            <div class="title_name">
                                <p>คำนำหน้าชื่อ<label style="color:red;"> *</label></p>
                                <select name="title_name" id="title_name" <?php if($row != null){ echo "disabled"; } ?>>
                                    <option value="นาย" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นาย"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นาย"){ echo "selected"; }}}?> >นาย</option>
                                    <option value="นาง" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นาง"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นาง"){ echo "selected"; }}}?> >นาง</option>
                                    <option value="นางสาว" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นางสาว"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นางสาว"){ echo "selected"; }}}?> >นางสาว</option>
                                </select>
                            </div>
                            <div class="firstname">
                                <p>ชื่อ<label style="color:red;"> *</label></p>
                                <input type="text" name="firstname" id="firstname" placeholder="ชื่อ" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $firstname; }else if(intval($get_people) == 2){ echo $firstname2; }}?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="fs_error" style="color:red;"></h5>
                            </div>
                            <div class="lastname">
                                <p>นามสกุล<label style="color:red;"> *</label></p>
                                <input type="text" name="lastname" id="lastname" placeholder="นามสกุล" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $lastname; }else if(intval($get_people) == 2){ echo $lastname2; }}?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="ls_error" style="color:red;"></h5>
                            </div>
                            <div class="nickname">
                                <p>ชื่อเล่น<label style="color:red;"> *</label></p>
                                <input type="text" name="nickname" id="nickname" placeholder="ชื่อเล่น" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $nickname; }else if(intval($get_people) == 2){ echo $nickname2; }}?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="nk_error" style="color:red;"></h5>
                            </div>
                            <div class="id_card">
                                <p>เลขบัตรประชาชน / Passport No.<label style="color:red;"> *</label></p>
                                <input type="text" name="id_card" id="id_card"
                                    placeholder="เลขบัตรประชาชน / Passport No." maxlength="13" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $id_card; }else if(intval($get_people) == 2){ echo $id_card2; }}?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="id_error" style="color:red;"></h5>
                            </div>
                            <div class="birthday">
                                <p>วัน / เดือน / ปีเกิด<label style="color:red;"> *</label></p>
                                <input type="text" name="birthday" id="birthday" placeholder="วัน / เดือน / ปีเกิด" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo DateThai($birthday); }else if(intval($get_people) == 2){ echo DateThai($birthday2); }}?>" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="bd_error" style="color:red;"></h5>
                            </div>
                            <div class="age">
                                <p>อายุ<label style="color:red;"> *</label></p>
                                <input type="number" name="age" id="age"
                                    oninput="this.value = this.value > 60 ? 60 : Math.abs(this.value)"
                                    placeholder="อายุ" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $age; }else if(intval($get_people) == 2){ echo $age2; }}?>" <?php if($row != null){ echo "disabled"; }else{ echo "readonly"; } ?> >
                                <h5 id="ag_error" style="color:red;"></h5>
                            </div>
                            <div class="phone">
                                <p>เบอร์โทรศัพท์<label style="color:red;"> *</label></p>
                                <input type="text" name="tel" id="tel" maxlength="10" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $phone; }else if(intval($get_people) == 2){ echo $phone2; }}?>" placeholder="เบอร์โทรศัพท์" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="tel_error" style="color:red;"></h5>
                            </div>
                            <div class="email">
                                <p>อีเมล<label style="color:red;"> *</label></p>
                                <input type="email" name="email" id="email" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $email; }else if(intval($get_people) == 2){ echo $email2; }} ?>" placeholder="อีเมล" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="em_error" style="color:red;"></h5>
                            </div>
                            <div class="race">
                                <p>เชื้อชาติ<label style="color:red;"> *</label></p>
                                <input type="text" name="race" id="race"  value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $race; }else if(intval($get_people) == 2){ echo $race2; }}else{ echo "ไทย"; } ?>" placeholder="เชื้อชาติ" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="rc_error" style="color:red;"></h5>
                            </div>
                            <div class="nationality">
                                <p>สัญชาติ<label style="color:red;"> *</label></p>
                                <input type="text" name="nation" id="nation"  value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $nationality; }else if(intval($get_people) == 2){ echo $nationality2; }}else{ echo "ไทย"; } ?>" placeholder="สัญชาติ" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="na_error" style="color:red;"></h5>
                            </div>
                            <div class="job">
                                <p>อาชีพ<label style="color:red;"> *</label></p>
                                <input type="text" name="job" id="job" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $job; }else if(intval($get_people) == 2){ echo $job2; }} ?>" placeholder="อาชีพ" <?php if($row != null){ echo "disabled"; } ?>>
                                <h5 id="job_error" style="color:red;"></h5>
                            </div>
                        </div>
                        <div id="address-box" style="padding-top:16px;height:146px;">
                            <p>ที่อยู่<label style="color:red;"> *</label></p>
                            <textarea name="address" id="address" placeholder="ที่อยู่" <?php if($row != null){ echo "disabled"; } ?>><?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $address; }else if(intval($get_people) == 2){ echo $address2; }} ?></textarea>
                            <h5 id="ad_error" style="color:red;"></h5>
                        </div>
                        <div id="copy-box" style="padding-top:32px;">
                            <h3>สำเนาเอกสาร</h3>
                            <div class="hr"></div>
                            <div class="img-grid">
                                <div>
                                    <p>สำเนาบัตรประชาชน<label style="color:red;"> *</label></p>
                                    <div class="img-box" id="id_box">
                                        <?php
                                        if($row != null){
                                        if(intval($get_people) == 1 || $get_people == ""){
                                            if($pic_idcard != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_idcard")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_id" <?php if($pic_idcard != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard'"; } ?>>
                                            <iframe  id="img_id" width="100%" height="100%" style="display:none;"></iframe>
                                        <?php
                                                }else{
                                        ?>
                                            <img id="img_id" style="display:none;">
                                            <iframe id="img_id" <?php if($pic_idcard != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }else if(intval($get_people) == 2){
                                            if($pic_idcard2 != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_idcard2")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_id" <?php if($pic_idcard2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard2'"; } ?>>
                                            <iframe  id="img_id" width="100%" height="100%" style="display:none;"></iframe>
                                        <?php
                                                }else{
                                        ?>
                                            <img id="img_id" style="display:none;">
                                            <iframe id="img_id" <?php if($pic_idcard2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard2'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }
                                    }else{
                                    ?>
                                    <img id="img_id" style="display:none;">
                                    <iframe  id="img_id" width="100%" height="100%" style="display:none;"></iframe>
                                    <?php }
                                        ?>
                                    </div>
                                    <h5 id="idimg_error" style="color:red;"></h5>
                                    <?php
                                    if($row != null){
                                    ?>
                                    <input type="file" name="id_img" id="id_img" <?php if($row != null){ echo "disabled"; } ?> >
                                    <?php }else{ ?>
                                    <input type="file" name="id_img" id="id_img">
                                    <?php } ?>
                                </div>
                                <div>
                                    <p>สำเนาทะเบียนบ้าน<label style="color:red;"> *</label></p>
                                    <div class="img-box" id="home_box">
                                        <?php
                                        if($row != null){
                                        if(intval($get_people) == 1 || $get_people == ""){
                                            if($pic_home != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_home")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_home" <?php if($pic_home != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home'"; } ?>>
                                            <iframe  id="img_home" width="100%" height="100%" style="display:none;"></iframe>
                                        <?php
                                                }else{
                                        ?>
                                            <img id="img_home" style="display:none;">
                                            <iframe id="img_home" <?php if($pic_home != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }else if(intval($get_people) == 2){
                                            if($pic_home2 != ""){
                                                if(pathinfo("../../images/roomember/$room_id/$come_date/$pic_home2")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_home" <?php if($pic_home2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home2'"; } ?>>
                                            <iframe  id="img_home" width="100%" height="100%" style="display:none;"></iframe>
                                        <?php
                                                }else{
                                        ?>
                                            <img id="img_home" style="display:none;">
                                            <iframe id="img_home" <?php if($pic_home2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home2'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }
                                    }else{
                                        ?>
                                        <img id="img_home" style="display:none;">
                                        <iframe  id="img_home" width="100%" height="100%" style="display:none;"></iframe>
                                        <?php } ?>
                                    </div>
                                    <h5 id="homeimg_error" style="color:red;"></h5>
                                    <?php
                                    if($row != null){
                                    ?>
                                    <input type="file" name="home_img" id="home_img" <?php if($row != null){ echo "disabled"; } ?>>
                                    <?php }else{ ?>
                                    <input type="file" name="home_img" id="home_img">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        if($row == null){
                        ?>
                        <div class="hr"></div>
                        <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                            <button type="submit" name="addData-btn" id="addData-btn" >เพิ่มผู้พัก</button>
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function resizeIFrameToFitContent( iFrame ) {

iFrame.width  = iFrame.contentWindow.document.body.scrollWidth;
iFrame.height = iFrame.contentWindow.document.body.scrollHeight;
}

window.addEventListener('DOMContentLoaded', function(e) {

var iFrame = document.getElementById( 'iFrame1' );
resizeIFrameToFitContent( iFrame );

// or, to resize all iframes:
var iframes = document.querySelectorAll("iframe");
for( var i = 0; i < iframes.length; i++) {
    resizeIFrameToFitContent( iframes[i] );
}
} );
    </script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php");
}
?>