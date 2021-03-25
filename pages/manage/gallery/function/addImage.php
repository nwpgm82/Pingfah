<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../../connection.php");
    $folder_path = "../../../images/gallery/";
    var_dump(is_dir($folder_path));
    if(!is_dir($folder_path)){
        mkdir($folder_path);
    }
    if(!empty($_FILES['file']['name'])){
        $file = $_FILES['file']['name'];
        $target = "../../../images/gallery/".basename($file);
        $sql = "INSERT INTO gallery (gallery_name) VALUES ('$file')";
        $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('แกลเลอรี่', 'เพิ่มรูปภาพ $file', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
        if(move_uploaded_file($_FILES['file']['tmp_name'], $target)){
            if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {    
            } else {
              echo "Error updating record: " . $conn->error;
            }
        }
    }
    
}else{
    Header("Location: ../../../login.php");
}
    
?>