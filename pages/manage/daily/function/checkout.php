<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    $id = $_REQUEST["daily_id"];
    $sql = mysqli_query($conn, "SELECT * FROM daily WHERE daily_id = $id");
    $result = mysqli_fetch_assoc($sql);
    $room_arr = explode(", ",$result["room_select"]);
    $damages = 0;
    $damage_list = json_decode($_POST["data"]);
    for($i = 0; $i < count($damage_list); $i++){
        $topic = $damage_list[$i]->topic;
        $damage_price = $damage_list[$i]->price;
        if(floatval($damage_price) != 0){
            $daily_damage = "INSERT INTO daily_damage (daily_id, damage_topic, damage_price) VALUES ($id, '$topic', $damage_price)";
            $conn->query($daily_damage);
            $damages = $damages + floatval($damage_price);
        }
    }
    $s_cost = mysqli_query($conn,"SELECT total_allprice FROM dailycost WHERE dailycost_id = $id");
    $s_result = mysqli_fetch_assoc($s_cost);
    $total = ($s_result["total_allprice"] + $damages) - $result["payment_price"];
    // echo $damages;
    $sql2 = "UPDATE daily SET daily_status = 'เช็คเอาท์แล้ว' WHERE daily_id = $id";
    $sql3 = "UPDATE dailycost SET damages = $damages, total_allprice = $total WHERE dailycost_id = $id";
    $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลลูกค้า', 'เปลี่ยนสถานะเป็น เช็คเอาท์แล้ว (".$result["name_title"].$result["firstname"]." ".$result["lastname"].")', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
    for($i = 0 ; $i < sizeof($room_arr) ; $i++){
        $update_roomlist = "UPDATE roomlist SET room_status = 'ว่าง' WHERE room_id = '$room_arr[$i]'";
        $update_roommember = "UPDATE roommember SET member_status = 'แจ้งออกแล้ว' WHERE room_id = '$room_arr[$i]' AND member_status = 'กำลังเข้าพัก'";
        $conn->query($update_roomlist);
        $conn->query($update_roommember);
    }
    if($conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE && $conn->query($addLogs) === TRUE){
        echo $id;
    }
    // echo $sql3;
    $conn->close();
}else{
    header("Location : ../../../login.php");
}
?>