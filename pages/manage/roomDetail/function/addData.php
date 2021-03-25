<?php
session_start();
if($_SESSION['level'] == 'admin'){
  include('../../../connection.php');
  $type = $_REQUEST['type'];
  if($type == "fan"){
    $type_show = "พัดลม";
  }else if($type == "air"){
    $type_show = "แอร์";
  }
  $daily_price = $_POST['daily_price'];
  $daily_deposit = $_POST['daily_deposit'];
  $price = $_POST['price'];
  $water = $_POST['water_bill'];
  $elec = $_POST['elec_bill'];
  $cable = $_POST['cable_charge'];
  $fines = $_POST['fines'];
  $deposit = $_POST['deposit'];
  $sv_fan = $_POST['sv_fan'];
  $sv_air = $_POST['sv_air'];
  $sv_wifi = $_POST['sv_wifi'];
  $sv_furniture = $_POST['sv_furniture'];
  $sv_readtable = $_POST['sv_readtable'];
  $sv_telephone = $_POST['sv_telephone'];
  $sv_television = $_POST['sv_television'];
  $sv_refrigerator = $_POST['sv_refrigerator'];
  $sv_waterbottle = $_POST['sv_waterbottle'];
  $sv_toilet = $_POST['sv_toilet'];
  $sv_hairdryer = $_POST['sv_hairdryer'];
  $sv_towel = $_POST['sv_towel'];
  $searchForValue = ',';
  if(strpos($daily_price, $searchForValue) !== false) {
    // echo "Found";
    $daily_price = str_replace(',', '', $daily_price);
  }
  if(strpos($daily_deposit, $searchForValue) !== false) {
    // echo "Found";
    $daily_deposit = str_replace(',', '', $daily_deposit);
  }
  if(strpos($price, $searchForValue) !== false) {
    // echo "Found";
    $price = str_replace(',', '', $price);
  }
  if(strpos($water, $searchForValue) !== false) {
    // echo "Found";
    $water = str_replace(',', '', $water);
  }
  if(strpos($elec, $searchForValue) !== false) {
    // echo "Found";
    $elec = str_replace(',', '', $elec);
  }
  if(strpos($cable, $searchForValue) !== false) {
    // echo "Found";
    $cable = str_replace(',', '', $cable);
  }
  if(strpos($fines, $searchForValue) !== false) {
    // echo "Found";
    $fines = str_replace(',', '', $fines);
  }
  if(strpos($deposit, $searchForValue) !== false) {
    // echo "Found";
    $deposit = str_replace(',', '', $deposit);
  }
  $sql = "UPDATE roomdetail SET water_bill = $water, elec_bill = $elec, cable_charge = $cable, fines = $fines, deposit = $deposit, price = $price, daily_price = $daily_price, daily_deposit = $daily_deposit, sv_fan ='$sv_fan', sv_air ='$sv_air', sv_wifi ='$sv_wifi', sv_furniture ='$sv_furniture', sv_readtable ='$sv_readtable', sv_telephone = '$sv_telephone', sv_television = '$sv_television', sv_refrigerator = '$sv_refrigerator', sv_waterbottle = '$sv_waterbottle', sv_toilet = '$sv_toilet', sv_hairdryer = '$sv_hairdryer', sv_towel = '$sv_towel' WHERE type = '$type_show' ";
  $addLogs = "INSERT INTO logs (log_topic, log_detail, log_name, log_position) VALUES ('ข้อมูลหอพัก', 'แก้ไขข้อมูลห้องพัก (ห้อง$type_show)', '".$_SESSION["name"]."', '".$_SESSION["level"]."')";
  if ($conn->query($sql) === TRUE && $conn->query($addLogs) === TRUE) {
    echo "<script>";
    echo "alert('แก้ไขข้อมูลสำเร็จ');";
    echo "location.href = '../detail.php?type=$type'";
    echo "</script>";
    echo $sql;
  } else {
    echo "Error updating record: " . $conn->error;
  }
  $conn->close();
}else{
  Header("Location: ../../../login.php"); 
}

?>