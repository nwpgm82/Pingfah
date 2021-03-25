<?php
include("../../connection.php");
$code_request = $_REQUEST["code"];
$sql = "SELECT a.*, b.* FROM dailycost a INNER JOIN daily b ON a.dailycost_id = b.daily_id WHERE b.code = '$code_request'";
$result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
$row = mysqli_fetch_array($result);
if($row != null){
extract($row);
$total_room = sizeof(explode(", ",$room_select));
$calculate = strtotime($check_out) - strtotime($check_in);
$night = floor($calculate / 86400);
$checkout_date = strtotime($check_out);
$monthThai = Array("","ม.ค.", "ก.พ.", "มี.ค.","เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.","ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
}
function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate));
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("d",strtotime($strDate));
    $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}
function textFormat( $text = '', $pattern = '', $ex = '' ) {
    $cid = ( $text == '' ) ? '0000000000000' : $text;
    $pattern = ( $pattern == '' ) ? '_-____-_____-__-_' : $pattern;
    $p = explode( '-', $pattern );
    $ex = ( $ex == '' ) ? '-' : $ex;
    $first = 0;
    $last = 0;
    for ( $i = 0; $i <= count( $p ) - 1; $i++ ) {
       $first = $first + $last;
       $last = strlen( $p[$i] );
       $returnText[$i] = substr( $cid, $first, $last );
    }
  
    return implode( $ex, $returnText );
 }
 function bathformat($number) {
    $numberstr = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
    $digitstr = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
  
    $number = str_replace(",","",$number); // ลบ comma
    $number = explode(".",$number); // แยกจุดทศนิยมออก
  
    // เลขจำนวนเต็ม
    $strlen = strlen($number[0]);
    $result = '';
    for($i=0;$i<$strlen;$i++) {
      $n = substr($number[0], $i,1);
      if($n!=0) {
        if($i==($strlen-1) AND $n==1){ $result .= 'เอ็ด'; }
        elseif($i==($strlen-2) AND $n==2){ $result .= 'ยี่'; }
        elseif($i==($strlen-2) AND $n==1){ $result .= ''; }
        else{ $result .= $numberstr[$n]; }
        $result .= $digitstr[$strlen-$i-1];
      }
    }
    
    // จุดทศนิยม
    $strlen = strlen($number[1]);
    if ($strlen>2) { // ทศนิยมมากกว่า 2 ตำแหน่ง คืนค่าเป็นตัวเลข
      $result .= 'จุด';
      for($i=0;$i<$strlen;$i++) {
        $result .= $numberstr[(int)$number[1][$i]];
      }
    } else { // คืนค่าเป็นจำนวนเงิน (บาท)
      $result .= 'บาท';
      if ($number[1]=='0' OR $number[1]=='00' OR $number[1]=='') {
        $result .= 'ถ้วน';
      } else {
        // จุดทศนิยม (สตางค์)
        for($i=0;$i<$strlen;$i++) {
          $n = substr($number[1], $i,1);
          if($n!=0){
            if($i==($strlen-1) AND $n==1){$result .= 'เอ็ด';}
            elseif($i==($strlen-2) AND $n==2){$result .= 'ยี่';}
            elseif($i==($strlen-2) AND $n==1){$result .= '';}
            else{ $result .= $numberstr[$n];}
            $result .= $digitstr[$strlen-$i-1];
          }
        }
        $result .= 'สตางค์';
      }
    }
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/receipt_deposit.css">
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="letter">
        <div style="padding-bottom:32px;">
        <div class="header">
                <img src="../../../img/main_logo.png" alt="">
                <strong><p style="text-align:center;font-size:16px;">ใบเสร็จค่าเสียหาย</p></strong>
                <div style="text-align:right;position:relative;">
                    <p>เลขที่ในการจอง : ..................................</p>
                    <p class="code_text"><?php echo $code_request; ?></p>
                </div>
            </div>
            <div style="position:relative;padding-bottom:20px;line-height:30px;">
                <p>ชื่อ : ............................................................... อีเมล : .................................................................... เบอร์โทรศัพท์ : ......................................</p>
                <p>เช็คอิน : ........................................... เช็คเอาท์ : ........................................... (<?php echo $night; ?> คืน)</p>
                <p class="name"><?php echo $name_title.$firstname." ".$lastname; ?></p>
                <p class="email"><?php echo $email; ?></p>
                <p class="tel"><?php echo textFormat($tel,'___-_______','-'); ?></p>
                <p class="check_in"><?php echo DateThai($check_in); ?></p>
                <p class="check_out"><?php echo DateThai($check_out); ?></p>
            </div>
            <table>
                <tr>
                    <th>ลำดับ</th>
                    <th colspan="3">รายการ</th>
                    <th>จำนวนเงิน</th>
                </tr>
                <?php
                    $i = 1;
                    $sql2 = "SELECT * FROM daily_damage WHERE daily_id = $daily_id";
                    $result2 = $conn->query($sql2);
                      while($row2 = $result2->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td colspan="3"><?php echo $row2["damage_topic"]; ?></td>
                    <td><?php echo number_format($row2["damage_price"]); ?></td>
                </tr>
                <?php $i++; } ?>
                <tr>
                    <td colspan="3"><?php echo bathformat($damages) ?></td>
                    <td style="text-align:center;">จำนวนเงินรวมทั้งสิ้น</td>
                    <td><?php echo number_format($damages); ?></td>
                </tr>
            </table>
            <div style="padding-top:64px;">
                <div class="sig">
                    <div>
                        <p>ลายเซ็นเจ้าของหอพัก ......................................................</p>
                        <p style="text-align:right;padding: 8px 26px 0 0;">(นายพิชัย นรเดชานันท์)</p>
                    </div>
                    <div>
                        <p>ลายเซ็นผู้รับเงิน ...........................................................</p>
                        <p style="text-align:right;padding: 8px 20px 0 0;">(............................................)</p>
                        <div style="position:relative;">
                            <p style="text-align:right;padding: 16px 26px 0 0;">วันที่ ........../.........../.............</p>
                            <p style="position:absolute;bottom:3px;right:134px;"><?php echo date("d",$checkout_date); ?></p>
                            <p style="position:absolute;bottom:3px;right:88px;"><?php echo $monthThai[date("n",$checkout_date)]; ?></p>
                            <p style="position:absolute;bottom:3px;right:36px;"><?php echo date("Y",$checkout_date); ?></p>
                        </div>
                    </div>
                </div>
                <!-- <div class="sig">
                    <div class="sub-sig">
                        <p>เจ้าของหอพักบ้านพิงฟ้า</p>
                        <div class="sub-sub-sig" style="padding-bottom:28px;">
                            <p style="text-align:center;">............................................................
                            </p>
                            <p style="text-align:center;">(.....................................................)</p>
                            <p style="width:max-content;position:absolute;bottom:31px;left:50%;transform: translateX(-50%);">นายพิชัย นรเดชานันท์</p>
                        </div>
                    </div>
                    <div class="sub-sig">
                        <p>ผู้รับเงิน</p>
                        <div class="sub-sub-sig">
                            <p style="text-align:center;">............................................................
                            </p>
                            <p style="text-align:center;">(.....................................................)</p>
                            <div>
                                <p style="text-align:center;">วันที่........../........../...............</p>
                                <p style="position:absolute;bottom:3px;right:134px;"><?php echo date("d",$checkout_date); ?></p>
                                <p style="position:absolute;bottom:3px;right:88px;"><?php echo $monthThai[date("n",$checkout_date)]; ?></p>
                                <p style="position:absolute;bottom:3px;right:36px;"><?php echo date("Y",$checkout_date); ?></p>
                            </div>
                            
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        <p style="font-size:12px;">สอบถามเพิ่มเติม : 098-9132002 (เจ้าของหอพักบ้านพิงฟ้า)</p>
    </div>
</body>

</html>