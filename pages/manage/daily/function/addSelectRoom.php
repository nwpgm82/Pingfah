<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    include("../../../connection.php");
    $room_str = $_POST["room_select"];
    $room = explode(", ",$room_str);
    // echo sizeof($room);
    $daily_id = $_REQUEST['daily_id'];
    $pic_idcard = $_FILES["id_img"]["name"];
    $folder_member = "../../../images/roommember/";
    if(!is_dir($folder_member)){
        mkdir($folder_member);
    }
    $search = mysqli_query($conn,"SELECT * FROM daily WHERE daily_id = $daily_id");
    $resultSearch = mysqli_fetch_assoc($search);
    $total = $resultSearch["total_price"] + $resultSearch["payment_price"];
    // $sql = "INSERT INTO dailycost (dailycost_id, room_id, name_title, firstname, lastname, id_card, email, tel, check_in, check_out, total_price, pay_status, code) VALUES ($daily_id, '$room_str', '".$resultSearch["name_title"]."', '".$resultSearch["firstname"]."', '".$resultSearch["lastname"]."', '".$resultSearch["id_card"]."', '".$resultSearch["email"]."', '".$resultSearch["tel"]."', '".$resultSearch["check_in"]."', '".$resultSearch["check_out"]."', ".$resultSearch["total_price"].", 'ชำระเงินแล้ว', '".$resultSearch["code"]."')";
    $sql = "UPDATE daily SET daily_status = 'เข้าพักแล้ว', deposit_date = '".date("Y-m-d")."', room_select = '$room_str' WHERE daily_id = $daily_id";
    $sql2 = "INSERT INTO dailycost (dailycost_id, code, total_allprice, pay_status) VALUES ($daily_id, '".$resultSearch["code"]."', ".$total.", 'ชำระเงินแล้ว')";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เปลี่ยนสถานะเป็น เข้าพักแล้ว (".$resultSearch["name_title"].$resultSearch["firstname"]." ".$resultSearch["lastname"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    $uploaded = false;
    for($i = 0; $i < sizeof($room) ; $i++){
        mkdir("../../../images/roommember/$room[$i]/".$resultSearch["check_in"]."/");
        $img_path = "../../../images/roommember/$room[$i]/".$resultSearch["check_in"]."/".basename($pic_idcard);
        $addUser = "INSERT INTO roommember (room_id, come_date, out_date, member_cat, member_status, name_title, firstname, lastname, id_card, phone, email, pic_idcard) VALUES ('$room[$i]','".$resultSearch["check_in"]."', '".$resultSearch["check_out"]."', 'รายวัน', 'กำลังเข้าพัก', '".$resultSearch["name_title"]."', '".$resultSearch["firstname"]."', '".$resultSearch["lastname"]."', '".$resultSearch["id_card"]."', '".$resultSearch["tel"]."', '".$resultSearch["email"]."', '$pic_idcard')";
        $updateStatus = "UPDATE roomlist SET room_status = 'ไม่ว่าง' WHERE room_id = '$room[$i]'";
        $conn->query($addUser);
        $conn->query($updateStatus);
        if($uploaded){
            copy($uploaded, $img_path);
        }else{
            if(move_uploaded_file($_FILES["id_img"]["tmp_name"], $img_path)){
                $uploaded = $img_path;
            }
        }
    }
    if($conn->query($sql) === TRUE && $conn->query($sql2) && $conn->query($addLogs) === TRUE){
        echo "<script>";
        echo "alert('เลือกห้องเรียบร้อยแล้ว');";
        echo "location.href = '../rent_success.php?daily_id=$daily_id';";
        echo "</script>";
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo "Error: " . $sql2 . "<br>" . $conn->error;
    }
    $conn->close();
    // $search = "SELECT * FROM daily WHERE daily_id = $daily_id";
    // $result = mysqli_query($conn, $search)or die ("Error in query: $search " . mysqli_error());
    // $row = mysqli_fetch_array($result);
    // if($row != null){
    //     extract($row);
    // }
    // $search2 = "SELECT * FROM roomdetail";
    // $result2 = $conn->query($search2);
    // if ($result2->num_rows > 0) {
    //     // output data of each row
    //     while($row2 = $result2->fetch_assoc()) {
    //         if($row2["type"] == "แอร์"){
    //             $airtotal_price = $row2["daily_price"] * $air_room;
    //         }else if($row2["type"] == "พัดลม"){
    //             $fantotal_price = $row2["daily_price"] * $fan_room;
    //         }
    //     }
    // }
    // $total_price = $airtotal_price + $fantotal_price;
    // $sql2 = "INSERT INTO dailycost (dailycost_id, room_id, firstname, lastname, id_card, email, tel, check_in, check_out, price_total, daily_status,code) VALUES ($daily_id, '$room_str', '$firstname', '$lastname', '$id_card', '$email', '$tel', '$check_in','$check_out',$total_price,'ชำระเงินแล้ว','$code')";
    // $update2 = "UPDATE daily SET daily_status = 'เข้าพักแล้ว', room_select = '$room_str' WHERE daily_id = $daily_id";
    // for($i = 0 ; $i < sizeof($room) ; $i++){
    //     $sql = "INSERT INTO roommember (room_member, firstname, lastname, id_card, phone, email) VALUES ('$room[$i]','$firstname','$lastname','$id_card','$tel','$email')";
    //     $update = "UPDATE roomlist SET room_status = 'เช่ารายวัน', check_in = '$check_in', check_out = '$check_out' WHERE room_id = '$room[$i]'";
    //     if($conn->query($sql) === TRUE && $conn->query($update) === TRUE){
            
    //     }else{
    //         echo $conn->error;
    //         echo "</br>";
    //         exit();
    //     }
    // }
    // if($conn->query($sql2) === TRUE && $conn->query($update2)){
    //     echo "<script>";
    //     echo "alert('เลือกห้องเรียบร้อยแล้ว');";
    //     echo "location.href = '../index.php';";
    //     echo "</script>";
    // }else{
    //     echo $conn->error;
    //     echo "</br>";
    //     exit();
    // }
}else{
    Header("Location: ../../../login.php");
}

?>