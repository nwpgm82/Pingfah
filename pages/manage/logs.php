<?php
session_start();
if($_SESSION["level"] == "admin"){
  include("../connection.php");
  function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate));
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("d",strtotime($strDate));
    $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
  }
  $topic = @$_REQUEST["topic"];
  if($topic == "employee"){
    $topic_str = "พนักงาน";
  }else if($topic == "month"){
    $topic_str = "ชำระเงิน(รายเดือน)";
  }else if($topic == "daily"){
    $topic_str = "ชำระเงิน(รายวัน)";
  }else if($topic == "repair"){
    $topic_str = "การซ่อมแซม";
  }else if($topic == "package"){
    $topic_str = "พัสดุ";
  }
  $date = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/logs.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="../../js/manage/logs.js"></script> -->
    <script src="../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="logs-box">
                <div class="logs-detail">
                    <?php
                    if(isset($topic)){
                      $sql_logs = "SELECT * FROM logs WHERE log_topic = '$topic_str'";
                    }else{
                      $sql_logs = "SELECT * FROM (SELECT * FROM logs ORDER BY log_id DESC) sub ORDER BY log_id ASC";
                    }
                    $result = $conn->query($sql_logs);
                    if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        $datetime = explode(" ",$row["log_timestamp"]);
                        $time = $datetime[1];
                        $dt =  new DateTime($row["log_timestamp"]);
                        $current_date = $dt->format("Y-m-d");
                        if($date == null){
                          $date = $current_date;
                          echo "<h3>".DateThai($date)."</h3>";
                        }else if($date != $current_date){
                          $date = $current_date;
                          echo "----------------------------------------------";
                          echo "<h3>".DateThai($date)."</h3>";
                        }
                    ?>
                    <p><strong><?php echo "[$time] ".$row["log_name"]."(".$row["log_position"].") : "?>&nbsp;&nbsp;[หมวด<?php echo $row["log_topic"]; ?>]&nbsp;&nbsp;</strong><?php echo $row["log_detail"]; ?>
                    </p>
                    <?php
                      }
                    }else{
                      echo "ไม่มีข้อมูล";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>
      $('.logs-detail').scrollTop($('.logs-detail')[0].scrollHeight);
    </script>
</body>

</html>
<?php
}else{
  Header("Location: login.php");
}
?>