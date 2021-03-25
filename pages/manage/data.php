<?php
header('Content-Type: application/json');
require_once '../connection.php';
$sqlQuery = "SELECT * FROM cost WHERE cost_status = 'ชำระเงินแล้ว' ORDER BY room_id";
$result = mysqli_query($conn,$sqlQuery);
$data = array();
foreach($result as $row){
    $data[] = $row;
}
mysqli_close($conn);
echo json_encode($data);
?>