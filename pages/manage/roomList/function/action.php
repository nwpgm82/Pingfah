<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    function BasicDate($tdate){
        $search = ["มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        $replace = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        $edate = str_replace($search, $replace, $tdate);
        $str_date = strtotime($edate);
        $year =  date("Y",$str_date);
        $month = date("m",$str_date);
        $day = date("d",$str_date);
        return "$year-$month-$day"; 
    }
    @$get_people = $_REQUEST["people"];
    @$room_id = $_REQUEST["ID"];
    @$title_name = $_POST["title_name"];
    @$firstname = $_POST["firstname"];
    @$lastname = $_POST["lastname"];
    @$nickname = $_POST["nickname"];
    @$id_card = $_POST["id_card"];
    @$birthday = BasicDate($_POST["birthday"]);
    @$age = $_POST["age"];
    @$tel = $_POST["tel"];
    @$email = $_POST["email"];
    @$race = $_POST["race"];
    @$nation = $_POST["nation"];
    @$job = $_POST["job"];
    @$address = $_POST["address"];
    @$id_img = $_FILES["id_img"]["name"];
    @$home_img = $_FILES["home_img"]["name"];
    $search_comeDate = mysqli_query($conn,"SELECT come_date FROM roommember WHERE room_id = '$room_id' AND member_status='กำลังเข้าพัก' ");
    $result_search = mysqli_fetch_assoc($search_comeDate);
    if(isset($_POST["addData-btn"])){
        $search_data = mysqli_query($conn,"SELECT COUNT(*) AS count_data FROM roommember WHERE room_id ='$room_id' AND member_status = 'กำลังเข้าพัก'");
        $result_data = mysqli_fetch_assoc($search_data);
        $searchDetail = mysqli_query($conn, "SELECT a.deposit, a.price FROM roomdetail a INNER JOIN roomlist b ON a.type = b.room_type WHERE b.room_id = '$room_id'");
        $result_detail = mysqli_fetch_assoc($searchDetail);
        $total_price = $result_detail["price"] + $result_detail["deposit"];
        if(intval($result_data["count_data"]) <= 0){
            $come = BasicDate($_POST["come"]);
            $roommember_folder_path = "../../../images/roommember/";
            $room_folder = "../../../images/roommember/$room_id/";
            $come_folder = "../../../images/roommember/$room_id/$come";
            $id_target = "../../../images/roommember/$room_id/$come/".basename($id_img);
            $home_target = "../../../images/roommember/$room_id/$come/".basename($home_img);
            if(!is_dir($roommember_folder_path)){
                mkdir($roommember_folder_path);
            }
            if(!is_dir($room_folder)){
                mkdir($room_folder);
            }
            if(!is_dir($come_folder)){
                mkdir($come_folder);
            } 
            if(is_dir($roommember_folder_path) && is_dir($room_folder) && is_dir($come_folder)){
                if(move_uploaded_file($_FILES["id_img"]["tmp_name"], $id_target) && move_uploaded_file($_FILES["home_img"]["tmp_name"], $home_target)){
                    $addData = "INSERT INTO roommember (room_id, come_date, member_cat, member_status, member_deposit, name_title, firstname, lastname, nickname, id_card, phone, email, birthday, age, race, nationality, job, address, pic_idcard, pic_home, people) VALUES ('$room_id','$come','รายเดือน','กำลังเข้าพัก',".$result_detail["deposit"].", '$title_name','$firstname', '$lastname', '$nickname', '$id_card', '$tel', '$email', '$birthday' , $age, '$race', '$nation', '$job', '$address', '$id_img', '$home_img', 1)";
                    $change_status = "UPDATE roomlist SET room_status = 'ไม่ว่าง' WHERE room_id = '$room_id' ";
                    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เพิ่มข้อมูลลูกค้า(ห้อง $room_id)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
                    if($conn->query($addData) === TRUE && $conn->query($change_status) === TRUE && $conn->query($addLogs) === TRUE){
                        $l = mysqli_query($conn, "SELECT a.member_id, a.member_status, b.room_type FROM roommember a INNER JOIN roomlist b ON a.room_id = b.room_id WHERE a.room_id = '$room_id' AND a.member_status = 'กำลังเข้าพัก'");
                        $get_l = mysqli_fetch_assoc($l);
                        $addcost = "INSERT INTO cost (member_id, room_id, room_type, member_status, cost_status, date, pay_date, room_cost, deposit, total) VALUES (".$get_l["member_id"].", '$room_id', '".$get_l["room_type"]."', '".$get_l["member_status"]."', 'ชำระเงินแล้ว', '".date("Y-m")."', '".date("Y-m-d")."', ".$result_detail["price"].",".$result_detail["deposit"].",$total_price)";
                        $addlogin = "INSERT INTO login (username, member_id, name, password, email, level) VALUES ('$room_id', ".$get_l["member_id"].", '$room_id', MD5('$id_card'), '$email', 'guest')";
                        if( $conn->query($addcost) === TRUE && $conn->query($addlogin) === TRUE){
                            echo "<script>";
                            echo "alert('เพิ่มข้อมูลผู้พักห้อง $room_id เรียบร้อยแล้ว');";
                            echo "location.href = '../index.php';";
                            echo "</script>";
                        }else{
                            echo "Error: " . $addlogin . "<br>" . $conn->error;
                        }  
                    }else{
                        echo "Error: " . $addData . "<br>" . $conn->error;
                    }
                }else{
                    echo $conn->error;
                }
            }else{
                echo $conn->error;
            }   
        }else{
            $id_target = "../../../images/roommember/$room_id/".$result_search["come_date"]."/".basename($id_img);
            $home_target = "../../../images/roommember/$room_id/".$result_search["come_date"]."/".basename($home_img);
            if(move_uploaded_file($_FILES["id_img"]["tmp_name"], $id_target) && move_uploaded_file($_FILES["home_img"]["tmp_name"], $home_target)){
                $addData = "UPDATE roommember SET name_title2 = '$title_name', firstname2 = '$firstname', lastname2 = '$lastname', nickname2 = '$nickname', id_card2 = '$id_card', phone2 = '$tel', email2 = '$email', birthday2 = '$birthday', age2 = $age, race2 = '$race', nationality2 = '$nation', job2 = '$job', address2 = '$address', pic_idcard2 = '$id_img', pic_home2 = '$home_img', people = 2 WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
                $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เพิ่มข้อมูลลูกค้า(ห้อง $room_id)(คนที่ 2)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
                if($conn->query($addData) === TRUE && $conn->query($addLogs) === TRUE){
                    echo "<script>";
                    echo "alert('เพิ่มข้อมูลผู้พักห้อง $room_id (คนที่ 2) เรียบร้อยแล้ว');";
                    echo "location.href = '../index.php';";
                    echo "</script>";
                }else{
                    echo "Error: " . $addData . "<br>" . $conn->error;
                }
            }else{
                echo $conn->error;
            }
        }
    }
    // if(isset($_POST["del-idimg"])){
    //     if(intval($get_people) == 1){
    //         $delimg_update = "UPDATE roommember SET pic_idcard = '' WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
    //     }else if(intval($get_people) == 2){
    //         $delimg_update = "UPDATE roommember SET pic_idcard2 = null WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
    //     }
    //     if($conn->query($delimg_update) === TRUE){
    //         echo "<script>";
    //         echo "alert('ลบรูปสำเนาบัตรประชาชนเรียบร้อยแล้ว');";
    //         echo "location.href = '../room_id.php?ID=$room_id';";
    //         echo "</script>";
    //     }
    // }
    // if(isset($_POST["del-homeimg"])){
    //     if(intval($get_people) == 1){
    //         $delimg_update = "UPDATE roommember SET pic_home = '' WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
    //     }else if(intval($get_people) == 2){
    //         $delimg_update = "UPDATE roommember SET pic_home2 = null WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
    //     }
    //     if($conn->query($delimg_update) === TRUE){
    //         echo "<script>";
    //         echo "alert('ลบรูปสำเนาทะเบียนบ้านเรียบร้อยแล้ว');";
    //         echo "location.href = '../room_id.php?ID=$room_id';";
    //         echo "</script>";
    //     }
    // }
    if(isset($_POST["accept-edit"])){
        $idimg_path = "../../../images/roommember/$room_id/".$result_search["come_date"]."/".basename($id_img);
        $homeimg_path = "../../../images/roommember/$room_id/".$result_search["come_date"]."/".basename($home_img);
        if($id_img == "" && $home_img == ""){
            if(intval($get_people) == 1){
                $update_data = "UPDATE roommember SET name_title = '$title_name', firstname = '$firstname', lastname = '$lastname', nickname = '$nickname', id_card = '$id_card', phone = '$tel', email = '$email', birthday = '$birthday', age = $age, race = '$race', nationality = '$nation', job = '$job', address = '$address' WHERE room_id = '$room_id'";
            }else if(intval($get_people) == 2){
                $update_data = "UPDATE roommember SET name_title2 = '$title_name', firstname2 = '$firstname', lastname2 = '$lastname', nickname2 = '$nickname', id_card2 = '$id_card', phone2 = '$tel', email2 = '$email', birthday2 = '$birthday', age2 = $age, race2 = '$race', nationality2 = '$nation', job2 = '$job', address2 = '$address' WHERE room_id = '$room_id'";
            }
        }else if($id_img != "" && $home_img == ""){
            if(move_uploaded_file($_FILES["id_img"]["tmp_name"], $idimg_path)){
                if(intval($get_people) == 1){
                    $update_data = "UPDATE roommember SET name_title = '$title_name', firstname = '$firstname', lastname = '$lastname', nickname = '$nickname', id_card = '$id_card', phone = '$tel', email = '$email', birthday = '$birthday', age = $age, race = '$race', nationality = '$nation', job = '$job', address = '$address', pic_idcard = '$id_img' WHERE room_id = '$room_id'";
                }else if(intval($get_people) == 2){
                    $update_data = "UPDATE roommember SET name_title2 = '$title_name', firstname2 = '$firstname', lastname2 = '$lastname', nickname2 = '$nickname', id_card2 = '$id_card', phone2 = '$tel', email2 = '$email', birthday2 = '$birthday', age2 = $age, race2 = '$race', nationality2 = '$nation', job2 = '$job', address2 = '$address', pic_idcard2 = '$id_img' WHERE room_id = '$room_id'";
                }
            }else{
                echo $conn->error;
            }
        }else if($id_img == "" && $home_img != ""){
            if(move_uploaded_file($_FILES["home_img"]["tmp_name"], $homeimg_path)){
                if(intval($get_people) == 1){
                    $update_data = "UPDATE roommember SET name_title = '$title_name', firstname = '$firstname', lastname = '$lastname', nickname = '$nickname', id_card = '$id_card', phone = '$tel', email = '$email', birthday = '$birthday', age = $age, race = '$race', nationality = '$nation', job = '$job', address = '$address', pic_home = '$home_img' WHERE room_id = '$room_id'";
                }else if(intval($get_people) == 2){
                    $update_data = "UPDATE roommember SET name_title2 = '$title_name', firstname2 = '$firstname', lastname2 = '$lastname', nickname2 = '$nickname', id_card2 = '$id_card', phone2 = '$tel', email2 = '$email', birthday2 = '$birthday', age2 = $age, race2 = '$race', nationality2 = '$nation', job2 = '$job', address2 = '$address', pic_home2 = '$home_img' WHERE room_id = '$room_id'";
                }
            }else{
                echo $conn->error;
            }
        }else{
            if(move_uploaded_file($_FILES["id_img"]["tmp_name"], $idimg_path) && move_uploaded_file($_FILES["home_img"]["tmp_name"], $homeimg_path)){
                if(intval($get_people) == 1){
                    $update_data = "UPDATE roommember SET name_title = '$title_name', firstname = '$firstname', lastname = '$lastname', nickname = '$nickname', id_card = '$id_card', phone = '$tel', email = '$email', birthday = '$birthday', age = $age, race = '$race', nationality = '$nation', job = '$job', address = '$address', pic_idcard = '$id_img', pic_home = '$home_img' WHERE room_id = '$room_id'";
                }else if(intval($get_people) == 2){
                    $update_data = "UPDATE roommember SET name_title2 = '$title_name', firstname2 = '$firstname', lastname2 = '$lastname', nickname2 = '$nickname', id_card2 = '$id_card', phone2 = '$tel', email2 = '$email', birthday2 = '$birthday', age2 = $age, race2 = '$race', nationality2 = '$nation', job2 = '$job', address2 = '$address', pic_idcard2 = '$id_img', pic_home2 = '$home_img' WHERE room_id = '$room_id'";
                }
            }else{
                echo $conn->error;
            }
        }
        if(intval($get_people) == 1){
            $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'แก้ไขข้อมูลลูกค้า(ห้อง $room_id)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        }else if(intval($get_people) == 2){
            $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'แก้ไขข้อมูลลูกค้า(ห้อง $room_id)(คนที่ 2)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        }
        if($conn->query($update_data) === TRUE && $conn->query($addLogs) === TRUE){
            echo "<script>";
            echo "alert('อัปเดตข้อมูลเรียบร้อยแล้ว');";
            echo "location.href = '../room_id.php?ID=$room_id&people=$get_people';";
            echo "</script>";
        }else{
            echo "Error: " . $update_data . "<br>" . $conn->error;
        }
    }
    if(isset($_POST["del_data"]) && $_SESSION["level"] == "admin"){
        $del_data = "DELETE FROM roommember WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก'";
        $del_login = "DELETE FROM login WHERE username = '$room_id'";
        $roomlist = "UPDATE roomlist SET room_status = 'ว่าง' WHERE room_id = '$room_id'";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'ลบข้อมูลลูกค้า(ห้อง $room_id)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        if($conn->query($del_data) === TRUE && $conn->query($del_login) === TRUE && $conn->query($roomlist) === TRUE && $conn->query($addLogs) === TRUE){
            echo "<script>";
            echo "alert('ลบข้อมูลเรียบร้อยแล้ว');";
            echo "location.href = '../room_id.php?ID=$room_id';";
            echo "</script>";
        }else{
            echo $conn->error;
        }
    }
    if(isset($_POST["quit"])){
        $out_date = date('Y-m-d');
        $current_date = date("Y-m");
        $water = $_POST["water_price"];
        $elec = $_POST["elec_price"];
        $cable = $_POST["cable_charge"];
        $damage = $_POST["damage_price"];
        $damage_list = json_decode($_POST["data"]);
        $deposit = $_POST["deposit_after"];
        $total = $_POST["total_price"];
        $search_roomlist = mysqli_query($conn, "SELECT a.member_id ,b.room_type FROM roommember a INNER JOIN roomlist b ON a.room_id = b.room_id WHERE a.room_id = '$room_id' AND a.member_status = 'กำลังเข้าพัก'");
        $result_roomlist = mysqli_fetch_assoc($search_roomlist);
        for($i = 0; $i < count($damage_list); $i++){
            $topic = $damage_list[$i]->topic;
            $damage_price = $damage_list[$i]->price;
            // echo $topic;
            if(floatval($damage_price) != 0){
                $cost_damage = "INSERT INTO cost_damage (member_id, damage_topic, damage_price) VALUES (".$result_roomlist["member_id"].", '$topic', $damage_price)";
                $conn->query($cost_damage);
            }
        }
        if(intval($deposit) > 0){
            $total = -1 * $deposit;
        }
        $cost = "INSERT INTO cost (member_id, room_id, room_type, member_status, cost_status, date, pay_date, water_bill, elec_bill, cable_charge, fines, deposit_after, total) VALUES (".$result_roomlist["member_id"].", '$room_id', '".$result_roomlist["room_type"]."', 'แจ้งออกแล้ว', 'ชำระเงินแล้ว', '$current_date', '$out_date', $water, $elec, $cable, $damage, $deposit, $total)";
        $del_login = "DELETE FROM login WHERE username = '$room_id'";
        $update_status = "UPDATE roommember SET member_status = 'แจ้งออกแล้ว', out_date = '$out_date' WHERE room_id = '$room_id' AND member_status = 'กำลังเข้าพัก' ";
        $update_room_status = "UPDATE roomlist SET room_status = 'ว่าง' WHERE room_id = '$room_id' ";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เปลี่ยนสถานะเป็น แจ้งออกแล้ว (ห้อง $room_id)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        $addLogs2 = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ชำระเงิน(รายเดือน)', 'เพิ่มรายการชำระเงินค่าเช่าห้องพัก (ห้อง $room_id)($current_date)', '" . $_SESSION["name"] . "', '" . $_SESSION["level"] . "')";
        if($conn->query($cost) === TRUE && $conn->query($del_login) === TRUE && $conn->query($update_status) === TRUE && $conn->query($update_room_status) === TRUE && $conn->query($addLogs) === TRUE && $conn->query($addLogs2) === TRUE){
            echo $result_roomlist["member_id"];
        }
    }
}
?>