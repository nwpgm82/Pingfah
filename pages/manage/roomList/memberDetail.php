<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../connection.php");
    $member_id = $_REQUEST["member_id"];
    @$get_people = $_REQUEST["people"];
    function DateThai($strDate){
        $strYear = date("Y",strtotime($strDate));
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    if(intval($get_people) == 1 || $get_people == ""){
        $sql = "SELECT member_status, room_id, come_date, name_title, firstname, lastname, nickname, id_card, phone, email, birthday, age, race, nationality, job, address, pic_idcard, pic_home, people FROM roommember WHERE member_id = '$member_id'";
    }else if(intval($get_people) == 2 ){
        $sql = "SELECT member_status, room_id, come_date, name_title2, firstname2, lastname2, nickname2, id_card2, phone2, email2, birthday2, age2, race2, nationality2, job2, address2, pic_idcard2, pic_home2, people FROM roommember WHERE member_id = '$member_id'";
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
                <div id="form-box">
                    <form>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                           <h3>ห้อง <?php echo $room_id; ?></h3>
                            <?php if($people != 1){ ?>
                                <a href="memberDetail.php?member_id=<?php echo $member_id; ?>&people=<?php if(intval($get_people) == 1 || $get_people == ""){ echo "2"; }else if(intval($get_people) == 2){ echo "1"; } ?>"><button type="button">คนที่ <?php if(intval($get_people) == 1 || $get_people == ""){ echo "2"; }else if(intval($get_people) == 2){ echo "1"; } ?></button></a>
                            <?php } ?>
                        </div>
                        <div class="hr"></div>
                        <div class="grid-container">
                            <div class="come" style="position:relative;">
                                <p>วันที่เริ่มเข้าพัก</p>
                                <input type="text" name="come" id="come_date" placeholder="วันที่เริ่มเข้าพัก" value="<?php if($row != null){ echo DateThai($come_date); }?>" disabled>
                                <h5 id="come_error" style="color:red;"></h5>
                            </div>
                            <div class="status">
                                <p>สถานะการเข้าพัก</p>
                                <input type="text" value="<?php echo $member_status; ?>" disabled>
                            </div>
                            <div class="title_name">
                                <p>คำนำหน้าชื่อ</p>
                                <select name="title_name" id="title_name" disabled>
                                    <option value="นาย" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นาย"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นาย"){ echo "selected"; }}}?> >นาย</option>
                                    <option value="นาง" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นาง"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นาง"){ echo "selected"; }}}?> >นาง</option>
                                    <option value="นางสาว" <?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ if($name_title == "นางสาว"){ echo "selected"; }}else if(intval($get_people) == 2){ if($name_title2 == "นางสาว"){ echo "selected"; }}}?> >นางสาว</option>
                                </select>
                            </div>
                            <div class="firstname">
                                <p>ชื่อ</p>
                                <input type="text" name="firstname" id="firstname" placeholder="ชื่อ" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $firstname; }else if(intval($get_people) == 2){ echo $firstname2; }}?>" disabled>
                            </div>
                            <div class="lastname">
                                <p>นามสกุล</p>
                                <input type="text" name="lastname" id="lastname" placeholder="นามสกุล" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $lastname; }else if(intval($get_people) == 2){ echo $lastname2; }}?>" disabled>
                            </div>
                            <div class="nickname">
                                <p>ชื่อเล่น</p>
                                <input type="text" name="nickname" id="nickname" placeholder="ชื่อเล่น" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $nickname; }else if(intval($get_people) == 2){ echo $nickname2; }}?>" disabled>
                            </div>
                            <div class="id_card">
                                <p>เลขบัตรประชาชน / Passport No.</p>
                                <input type="text" name="id_card" id="id_card"
                                    placeholder="เลขบัตรประชาชน / Passport No." maxlength="13" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $id_card; }else if(intval($get_people) == 2){ echo $id_card2; }}?>" disabled>
                            </div>
                            <div class="birthday">
                                <p>วัน / เดือน / ปีเกิด</p>
                                <input type="text" name="birthday" id="birthday" placeholder="วัน / เดือน / ปีเกิด" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo DateThai($birthday); }else if(intval($get_people) == 2){ echo DateThai($birthday2); }}?>" disabled>
                            </div>
                            <div class="age">
                                <p>อายุ</p>
                                <input type="number" name="age" id="age"
                                    oninput="this.value = this.value > 60 ? 60 : Math.abs(this.value)"
                                    placeholder="อายุ" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $age; }else if(intval($get_people) == 2){ echo $age2; }}?>" disabled>
                            </div>
                            <div class="phone">
                                <p>เบอร์โทรศัพท์</p>
                                <input type="text" name="tel" id="tel" maxlength="10" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $phone; }else if(intval($get_people) == 2){ echo $phone2; }}?>" placeholder="เบอร์โทรศัพท์" disabled>
                            </div>
                            <div class="email">
                                <p>อีเมล</p>
                                <input type="email" name="email" id="email" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $email; }else if(intval($get_people) == 2){ echo $email2; }} ?>" placeholder="อีเมล" disabled>
                            </div>
                            <div class="race">
                                <p>เชื้อชาติ</p>
                                <input type="text" name="race" id="race"  value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $race; }else if(intval($get_people) == 2){ echo $race2; }}else{ echo "ไทย"; } ?>" placeholder="เชื้อชาติ" disabled>
                            </div>
                            <div class="nationality">
                                <p>สัญชาติ</p>
                                <input type="text" name="nation" id="nation"  value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $nationality; }else if(intval($get_people) == 2){ echo $nationality2; }}else{ echo "ไทย"; } ?>" placeholder="สัญชาติ" disabled>
                            </div>
                            <div class="job">
                                <p>อาชีพ</p>
                                <input type="text" name="job" id="job" value="<?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $job; }else if(intval($get_people) == 2){ echo $job2; }} ?>" placeholder="อาชีพ" disabled>
                            </div>
                        </div>
                        <div id="address-box" style="padding-top:16px;height:146px;">
                            <p>ที่อยู่</p>
                            <textarea name="address" id="address" placeholder="ที่อยู่" disabled><?php if($row != null){ if(intval($get_people) == 1 || $get_people == ""){ echo $address; }else if(intval($get_people) == 2){ echo $address2; }} ?></textarea>
                        </div>
                        <div id="copy-box" style="padding-top:32px;">
                            <h3>สำเนาเอกสาร</h3>
                            <div class="hr"></div>
                            <div class="img-grid">
                                <div>
                                    <p>สำเนาบัตรประชาชน</p>
                                    <div class="img-box" id="id_box">
                                        <?php
                                        if(intval($get_people) == 1 || $get_people == ""){
                                            if($pic_idcard != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_idcard")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_id" <?php if($pic_idcard != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard'"; } ?>>
                                        <?php
                                                }else{
                                        ?>
                                            <iframe id="img_id" <?php if($pic_idcard != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }else if(intval($get_people) == 2){
                                            if($pic_idcard2 != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_idcard2")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_id" <?php if($pic_idcard2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard2'"; } ?>>
                                        <?php
                                                }else{
                                        ?>
                                            <iframe id="img_id" <?php if($pic_idcard2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_idcard2'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div>
                                    <p>สำเนาทะเบียนบ้าน</p>
                                    <div class="img-box" id="home_box">
                                        <?php
                                        if(intval($get_people) == 1 || $get_people == ""){
                                            if($pic_home != ""){
                                                if(pathinfo("../../images/roommember/$room_id/$come_date/$pic_home")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_home" <?php if($pic_home != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home'"; } ?>>
                                        <?php
                                                }else{
                                        ?>
                                            <iframe id="img_home" <?php if($pic_home != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }else if(intval($get_people) == 2){
                                            if($pic_home2 != ""){
                                                if(pathinfo("../../images/roomember/$room_id/$come_date/$pic_home2")["extension"] != "pdf"){
                                        ?>
                                            <img id="img_home" <?php if($pic_home2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home2'"; } ?>>
                                        <?php
                                                }else{
                                        ?>
                                            <iframe id="img_home" <?php if($pic_home2 != ""){ echo "src='../../images/roommember/$room_id/$come_date/$pic_home2'"; } ?> width="100%" height="100%"></iframe>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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