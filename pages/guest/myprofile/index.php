<?php
session_start();
if($_SESSION['level'] == 'guest'){
    include('../../connection.php');
    include('../../../components/sidebarGuest.php'); 
    $path1 = "../../images/roommember/".$_SESSION['ID']."/";
    $subpath1 = "../../images/roommember/".$_SESSION['ID']."/1/";
    $subpath2 = "../../images/roommember/".$_SESSION['ID']."/2/";
    if(is_dir($path1)){
        // echo "<script>console.log('มีโฟลเดอร์ $path1')</script>";
    }else{
        // echo "<script>console.log('ไม่มีโฟลเดอร์ $path1')</script>";
        mkdir($path1);
        if(is_dir($path1)){
            mkdir($subpath1);
            mkdir($subpath2);
        }
        
    }
    // echo $status['room_status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/roomform.css">
    <link rel="stylesheet" href="../../../css/my-style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datedropper.com/get/f81yq0gdfse6par55j0enfmfmlk99n5y"></script>
    <script src="../../../js/datedropper.pro.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div id="month" class="roomform-box">
                <div id="first">
                    <?php
                $sql = "SELECT room_member, name_title, firstname, lastname, nickname, id_card, phone, email, birthday, age, race, nationality, job, address, pic_idcard, pic_home, id_line FROM roommember WHERE room_member='".$_SESSION['ID']."' ";
                $result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
                $row = mysqli_fetch_array($result);
                if($row != null){
                extract($row);
                }    
                ?>
                    <form>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <h3>ห้อง <?php echo $_SESSION['ID']; ?> (คนที่ 1)</h3>
                        </div>
                        <div class="hr"></div>
                        <div class="row">
                            <div class="col-2 select-box">
                                <p>คำนำหน้าชื่อ</p>
                                <?php
                            if(!isset($name_title)){
                            ?>
                                <select name="name_title" id="name_title" disabled>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                                <?php 
                            }else{
                            ?>
                                <select name="name_title" id="name_title" disabled>
                                    <option value="นาย" <?php if($name_title == 'นาย'){ echo "selected";} ?>>นาย
                                    </option>
                                    <option value="นาง" <?php if($name_title == 'นาง'){ echo "selected";} ?>>นาง
                                    </option>
                                    <option value="นางสาว" <?php if($name_title == 'นางสาว'){ echo "selected";} ?>>
                                        นางสาว</option>
                                </select>
                                <?php } ?>
                            </div>
                            <div class="col-4 input-box">
                                <p>ชื่อ</p>
                                <input type="text" required name="firstname" id="firstname"
                                    value="<?php if(isset($firstname)){echo $firstname;}; ?>" placeholder="ชื่อ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-4 input-box">
                                <p>นามสกุล</p>
                                <input type="text" required name="lastname" id="lastname"
                                    value="<?php if(isset($lastname)){echo $lastname;}; ?>" placeholder="นามสกุล"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>ชื่อเล่น</p>
                                <input type="text" required name="nickname" id="nickname"
                                    value="<?php if(isset($nickname)){echo $nickname;}; ?>" placeholder="ชื่อเล่น"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3 input-box">
                                <p>เลขบัตรประชาชน</p>
                                <input type="text" required name="id_card" id="id_card"
                                    value="<?php if(isset($id_card)){echo $id_card;}; ?>" placeholder="เลขบัตรประชาชน"
                                    minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>เบอร์โทรศัพท์</p>
                                <input type="text" required name="phone" id="phone"
                                    value="<?php if(isset($phone)){echo $phone;}; ?>" placeholder="เบอร์โทรศัพท์"
                                    minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>อีเมล์</p>
                                <input type="text" required name="email" id="email"
                                    value="<?php if(isset($email)){echo $email;}; ?>" placeholder="Email" minlength="2"
                                    disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>Line</p>
                                <input type="text" required name="line" id="line"
                                    value="<?php if(isset($id_line)){echo $id_line;}; ?>" placeholder="Line ID"
                                    minlength="2" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3 input-box">
                                <p>เกิดวันที่</p>
                                <div style="position:relative;">
                                    <input id="birthday" name="birthday" type="text"
                                        value="<?php if(isset($birthday)){ echo $birthday; }?>" disabled required>
                                    <p id="birth_date" class="dateText"></p>
                                </div>
                            </div>
                            <div class="col-2 input-box">
                                <p>อายุ</p>
                                <input type="text" required name="age" id="age"
                                    value="<?php if(isset($age)){echo $age;}; ?>" placeholder="อายุ"
                                    title="ตัวเลขเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>เชื้อชาติ</p>
                                <input type="text" required name="race" id="race"
                                    value="<?php if(isset($race)){echo $race;}; ?>" placeholder="เชื้อชาติ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>สัญชาติ</p>
                                <input type="text" required name="nationality" id="nationality"
                                    value="<?php if(isset($nationality)){echo $nationality;}; ?>" placeholder="สัญชาติ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>อาชีพ</p>
                                <input type="text" required name="job" id="job"
                                    value="<?php if(isset($job)){echo $job;}; ?>" placeholder="อาชีพ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                        </div>
                        <div style="padding:32px 8px 0 0;">
                            <p>ที่อยู่</p>
                            <textarea cols="30" rows="10" required name="address" id="address" placeholder="ที่อยู่"
                                minlength="2" disabled><?php if(isset($address)){echo $address;} ?>
                        </textarea>
                        </div>
                        <div style="padding-top:32px;">
                            <h3>สำเนาเอกสาร</h3>
                            <div class="grid">
                                <div>
                                    <p>สำเนาบัตรประชาชน</p>
                                    <div class="img-box">
                                        <img id="output_imagepic1"
                                            src="<?php if(isset($pic_idcard)){ echo '../../images/roommember/'.$_SESSION['ID'].'/1/'.$pic_idcard;} ?>" />
                                        <?php
                                        if(isset($pic_idcard)){ ?>
                                        <button class="del-btn" type="button" id="del-btn1" style="display:none;"
                                            onclick="delImg('<?php echo $room_id ?>','pic_idcard',1)">X</button>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if(!isset($pic_idcard)){ ?>
                                    <input type="file" id="pic_idcard1" accept="image/*"
                                        onchange="preview_image(event,'pic1')" name="pic_idcard" disabled>
                                    <?php } ?>
                                </div>
                                <div>
                                    <p>สำเนาทะเบียนบ้าน</p>
                                    <div class="img-box">
                                        <img id="output_imagepic2"
                                            src="<?php if(isset($pic_home)){ echo '../../images/roommember/'.$_SESSION['ID'].'/1/'.$pic_home;} ?>" />
                                        <?php
                                    if(isset($pic_home)){ ?>
                                        <button class="del-btn" type="button" id="del-btn2" style="display:none;"
                                            onclick="delImg('<?php echo $room_id; ?>','pic_home',1)">X</button>
                                        <?php } ?>
                                    </div>
                                    <?php
                                if(!isset($pic_home)){ ?>
                                    <input type="file" id="pic_home1" accept="image/*"
                                        onchange="preview_image(event,'pic2')" name="pic_home" disabled>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="hr" style="margin: 32px 0;"></div>
                        <?php if($row != null){ ?>
                        <div style="display:flex;justify-content:flex-end;">
                            <button type="button" onclick="navigation()">คนที่ 2</button>
                        </div>
                        <?php } ?>
                        <!-- <div style="display:flex;justify-content:center;">
                            <button type="submit" name='formSubmit' class="confirm">ยืนยัน</button>
                        </div> -->


                    </form>
                </div>
                <!--  -->
                <?php if($row != null){ ?>
                <div id="second" style="display:none">
                <?php
                $sql2 = "SELECT room_member, name_title2, firstname2, lastname2, nickname2, id_card2, phone2, email2, birthday2, age2, race2, nationality2, job2, address2, pic_idcard2, pic_home2, id_line2 FROM roommember WHERE room_member='".$_SESSION['ID']."' ";
                $result2 = mysqli_query($conn, $sql2)or die ("Error in query: $sql2 " . mysqli_error());
                $row2 = mysqli_fetch_array($result2);
                if($row2 != null){
                extract($row2);
                }     
                ?>
                    <form>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h3>ห้อง <?php echo $_SESSION['ID']; ?> (คนที่ 2)</h3>
                        </div>
                        
                        <div class="hr"></div>
                        <div class="row">
                            <div class="col-2 select-box">
                                <p>คำนำหน้าชื่อ</p>
                                <?php
                            if(!isset($name_title2)){
                            ?>
                                <select name="name_title2" id="name_title2" disabled>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                                <?php 
                            }else{
                            ?>
                                <select name="name_title2" id="name_title2" disabled>
                                    <option value="นาย" <?php if($name_title2 == 'นาย'){ echo "selected";} ?>>นาย
                                    </option>
                                    <option value="นาง" <?php if($name_title2 == 'นาง'){ echo "selected";} ?>>นาง
                                    </option>
                                    <option value="นางสาว" <?php if($name_title2 == 'นางสาว'){ echo "selected";} ?>>
                                        นางสาว</option>
                                </select>
                                <?php } ?>
                            </div>
                            <div class="col-4 input-box">
                                <p>ชื่อ</p>
                                <input type="text" required name="firstname2" id="firstname2"
                                    value="<?php if(isset($firstname2)){echo $firstname2;}; ?>" placeholder="ชื่อ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-4 input-box">
                                <p>นามสกุล</p>
                                <input type="text" required name="lastname2" id="lastname2"
                                    value="<?php if(isset($lastname2)){echo $lastname2;}; ?>" placeholder="นามสกุล"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>ชื่อเล่น</p>
                                <input type="text" required name="nickname2" id="nickname2"
                                    value="<?php if(isset($nickname2)){echo $nickname2;}; ?>" placeholder="ชื่อเล่น"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3 input-box">
                                <p>เลขบัตรประชาชน</p>
                                <input type="text" required name="id_card2" id="id_card2"
                                    value="<?php if(isset($id_card2)){echo $id_card2;}; ?>" placeholder="เลขบัตรประชาชน"
                                    minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>เบอร์โทรศัพท์</p>
                                <input type="text" required name="phone2" id="phone2"
                                    value="<?php if(isset($phone2)){echo $phone2;}; ?>" placeholder="เบอร์โทรศัพท์"
                                    minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>อีเมล์</p>
                                <input type="text" required name="email2" id="email2"
                                    value="<?php if(isset($email2)){echo $email2;}; ?>" placeholder="Email"
                                    minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>Line</p>
                                <input type="text" required name="line2" id="line2"
                                    value="<?php if(isset($id_line2)){echo $id_line2;}; ?>" placeholder="Line ID"
                                    minlength="2" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3 input-box">
                                <p>เกิดวันที่</p>
                                <div style="position:relative;">
                                    <input id="birthday2" name="birthday2" type="text"
                                        value="<?php if(isset($birthday2)){ echo $birthday2; }?>" disabled required>
                                    <p id="birth_date2" class="dateText"></p>
                                </div>
                            </div>
                            <div class="col-2 input-box">
                                <p>อายุ</p>
                                <input type="text" required name="age2" id="age2"
                                    value="<?php if(isset($age2)){echo $age2;}; ?>" placeholder="อายุ"
                                    title="ตัวเลขเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>เชื้อชาติ</p>
                                <input type="text" required name="race2" id="race2"
                                    value="<?php if(isset($race2)){echo $race2;}; ?>" placeholder="เชื้อชาติ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-2 input-box">
                                <p>สัญชาติ</p>
                                <input type="text" required name="nationality2" id="nationality2"
                                    value="<?php if(isset($nationality2)){echo $nationality2;}; ?>"
                                    placeholder="สัญชาติ" title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                            <div class="col-3 input-box">
                                <p>อาชีพ</p>
                                <input type="text" required name="job2" id="job2"
                                    value="<?php if(isset($job2)){echo $job2;}; ?>" placeholder="อาชีพ"
                                    title="ภาษาอังกฤษหรือภาษาไทยเท่านั้น" minlength="2" disabled>
                            </div>
                        </div>
                        <div style="padding:32px 8px 0 0;">
                            <p>ที่อยู่</p>
                            <textarea cols="30" rows="10" required name="address2" id="address2" placeholder="ที่อยู่"
                                minlength="2" disabled><?php if(isset($address2)){echo $address2;} ?>
                        </textarea>
                        </div>
                        <div style="padding-top:32px;">
                            <h3>สำเนาเอกสาร</h3>
                            <div class="grid">
                                <div>
                                    <p>สำเนาบัตรประชาชน</p>
                                    <div class="img-box">
                                        <img id="output_imagepic3"
                                            src="<?php if(isset($pic_idcard2)){ echo '../../images/roommember/'.$_SESSION['ID'].'/2/'.$pic_idcard2;} ?>>" />
                                        <?php
                                        if(isset($pic_idcard2)){ ?>
                                        <button class="del-btn" type="button" id="del-btn3" style="display:none;"
                                            onclick="delImg('<?php echo $room_id ?>','pic_idcard2',2)">X</button>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if(!isset($pic_idcard2)){ ?>
                                    <input type="file" id="pic_idcard2" accept="image/*"
                                        onchange="preview_image(event,'pic3')" name="pic_idcard2" disabled>
                                    <?php } ?>
                                </div>
                                <div>
                                    <p>สำเนาทะเบียนบ้าน</p>
                                    <div class="img-box">
                                        <img id="output_imagepic4"
                                            src="<?php if(isset($pic_home2)){ echo '../../images/roommember/'.$_SESSION['ID'].'/2/'.$pic_home2;} ?>" />
                                        <?php
                                        if(isset($pic_home2)){ ?>
                                        <button class="del-btn" type="button" id="del-btn4" style="display:none;"
                                            onclick="delImg('<?php echo $room_id; ?>','pic_home2',2)">X</button>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    if(!isset($pic_home2)){ ?>
                                    <input type="file" id="pic_home2" accept="image/*"
                                        onchange="preview_image(event,'pic4')" name="pic_home2" disabled>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div style="display:flex;justify-content:flex-start;">
                            <button type="button" onclick="navigation()">คนที่ 1</button>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script src="../../../js/admin/room_id_form.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>