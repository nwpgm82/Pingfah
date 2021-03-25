<?php
include("../connection.php");
// $query = mysqli_query($conn,"SELECT SUM(total) as total_cost WHERE date = '".$_POST['value']."'");
// $result = mysqli_fetch_assoc($query);
// $data = $result['total_cost'];
// echo "<script>alert('".$data['total_cost']."');</script>";
if(isset($_POST['searchcost'])){
    $time = strtotime($_POST['value']);
    $sql = mysqli_query($conn,"SELECT SUM(total) as total_cost FROM cost WHERE date = '".$_POST['value']."' AND cost_status = 'ชำระเงินแล้ว'");
    $sql2 = mysqli_query($conn,"SELECT SUM(a.total_allprice) as total_dailycost FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE YEAR(b.check_in) = '".date("Y",$time)."' AND MONTH(b.check_in) = '".date("m",$time)."'");
    $sql3 = mysqli_query($conn,"SELECT SUM(repair_income - repair_expenses) as total_repaircost FROM repair WHERE YEAR(repair_successdate) = '".date("Y",$time)."' AND MONTH(repair_successdate) = '".date("m",$time)."'");
    $data = mysqli_fetch_assoc($sql);  
    $data2 = mysqli_fetch_assoc($sql2);
    $data3 = mysqli_fetch_assoc($sql3);
    $total_cost = floatval($data['total_cost']) + floatval($data2['total_dailycost']) + floatval($data3['total_repaircost']);
    echo $total_cost;
}

?>