<?php
// include "../connection.php";
// function DateThai($strDate){
//   $strYear = date("Y",strtotime($strDate));
//   $strMonth= date("n",strtotime($strDate));
//   $strDay= date("d",strtotime($strDate));
//   $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
//   $strMonthThai=$strMonthCut[$strMonth];
//   return "$strDay $strMonthThai $strYear";
// }
// $date = null;
// $addRow = $_POST["addRow"] * 20;
// $sql_logs = "SELECT * FROM (SELECT * FROM logs ORDER BY log_id DESC LIMIT $addRow,20) sub ORDER BY log_id ASC";
// $result = $conn->query($sql_logs);
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $datetime = explode(" ", $row["log_timestamp"]);
//         $time = $datetime[1];
//         $dt = new DateTime($row["log_timestamp"]);
//         $current_date = $dt->format("Y-m-d");
//         if ($date == null) {
//             $date = $current_date;
//             echo "<h3>" . DateThai($date) . "</h3>";
//         } else if ($date != $current_date) {
//             $date = $current_date;
//             echo "----------------------------------------------";
//             echo "<h3>" . DateThai($date) . "</h3>";
//         }
//         echo "<p>[".$row["log_id"]."]<strong>[".$time."] ".$row["log_name"]."(".$row["log_position"].") : &nbsp;&nbsp;[หมวด".$row["log_topic"]."]&nbsp;&nbsp;</strong>".$row["log_detail"]."<p>";
//     }
// }

?>