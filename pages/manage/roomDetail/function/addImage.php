<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    $type = $_REQUEST["type"];
    if($type == "fan"){
        $type_show = "พัดลม";
    }else if($type == "air"){
        $type_show = "แอร์";
    }
    $main_path = "../../../images/roomdetail/";
    if(!is_dir($main_path)){
        mkdir($main_path);
    }
    if($type == "fan"){
        $folder_path = "../../../images/roomdetail/fan/";
    }else if($type == "air"){
        $folder_path = "../../../images/roomdetail/air/";
    }
    if(!is_dir($folder_path)){
        mkdir($folder_path);
    }
    if(!empty($_FILES['file']['name'])){
        $file = $_FILES['file']['name'];
        $target = $folder_path.basename($file);
        if($type == "fan"){
            $sql = "INSERT INTO fan_gal (gal_name) VALUES ('$file')";
        }else if($type == "air"){
            $sql = "INSERT INTO air_gal (gal_name) VALUES ('$file')";
        }
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'เพิ่มรูปภาพ $file (ห้อง$type_show)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        if(move_uploaded_file($_FILES['file']['tmp_name'], $target)){
            if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
                echo $file; 
                exit;   
            } else {
              echo "Error updating record: " . $conn->error;
            }
        }
    }
}else{
    Header("Location: ../../../login.php");
}
    
?>