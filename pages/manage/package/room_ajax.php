<?php
include("../../connection.php");
$name = $_POST["name"];
if($name != ""){
    $sql = "SELECT room_id FROM roommember WHERE CONCAT(firstname,' ',lastname) LIKE '%$name%' AND member_status = 'กำลังเข้าพัก'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='".$row["room_id"]."'>".$row["room_id"]."</option>";
        }
    }
}
?>