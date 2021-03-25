<?php
include("../../connection.php");
$id = $_REQUEST["cost_id"];
$sql = "SELECT a.*, b.name_title, b.firstname, b.lastname, b.phone FROM cost a INNER JOIN roommember b ON a.member_id = b.member_id WHERE a.cost_id = $id";
$result = mysqli_query($conn, $sql)or die ("Error in query: $sql " . mysqli_error());
$row = mysqli_fetch_array($result);
if($row != null){
extract($row);
$payThai_date = strtotime($pay_date);
    $monthThai = Array("","ม.ค.", "ก.พ.", "มี.ค.","เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.","ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
}
function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate));
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("d",strtotime($strDate));
    $strMonthCut = Array("","มกราคม", "กุมภาพันธ์", "มีนาคม","เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม","สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strMonthThai $strYear";
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
    <link rel="stylesheet" href="../../../css/receipt_month.css">
    <title>Pingfah Apartment</title>
</head>

<body>
    <div class="letter">
        <div style="padding-bottom:32px;">
            <div class="header">
                <img src="../../../img/main_logo.png" alt="">
                <strong>
                    <p style="text-align:center;font-size:16px;">ใบเสร็จค่าเช่าห้องพัก</p>
                </strong>
                <div style="text-align:right;position:relative;">
                    <p>ประจำเดือน : ..................................</p>
                    <p class="code_text"><?php echo DateThai($date); ?></p>
                </div>
            </div>
            <div style="position:relative;padding-bottom:20px;line-height:30px;">
                <p>ชื่อ : ............................................................................................
                    เลขห้อง :
                    .............. เบอร์โทรศัพท์ :
                    ...........................................................</p>
                <p class="name"><?php echo $name_title.$firstname." ".$lastname; ?></p>
                <p class="email" style="left:416px;"><?php echo $room_id; ?></p>
                <p class="tel" style="right:120px;"><?php echo textFormat($phone,'___-_______','-'); ?></p>
            </div>
            <table>
                <tr>
                    <th>ลำดับ</th>
                    <th colspan="3">รายการ</th>
                    <th>จำนวนเงิน</th>
                </tr>
                <?php
                if($member_status == "กำลังเข้าพัก"){
                ?>
                <tr>
                    <td>1</td>
                    <td colspan="3">ค่าเช่าห้องพัก</td>
                    <td><?php echo number_format($room_cost); ?></td>
                </tr>
                <?php } ?>
                <?php
                if($deposit != null){
                ?>
                <tr>
                    <td>2</td>
                    <td colspan="3">ค่าประกันหอพัก</td>
                    <td><?php echo number_format($deposit); ?></td>
                </tr>
                <?php
                }else{
                    if($member_status == "กำลังเข้าพัก"){
                ?>
                <tr>
                    <td>2</td>
                    <td colspan="3">ค่าน้ำ</td>
                    <td><?php echo number_format($water_bill); ?></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td colspan="3">ค่าไฟ</td>
                    <td><?php echo number_format($elec_bill); ?></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td colspan="3">ค่าเคเบิล</td>
                    <td><?php echo number_format($cable_charge); ?></td>
                </tr>
                <?php 
                    }}
                ?>
                <tr>
                    <td colspan="3"><?php echo bathformat($total) ?></td>
                    <td style="text-align:center;">จำนวนเงินรวมทั้งสิ้น</td>
                    <td><?php echo number_format($total); ?></td>
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
                        <p style="text-align:right;padding: 8px 20px 0 0;">
                            (............................................)</p>
                        <div style="position:relative;">
                            <p style="text-align:right;padding: 16px 26px 0 0;">วันที่
                                ........../.........../.............</p>
                            <p style="position:absolute;top:13px;right:134px;"><?php echo date("d",$payThai_date); ?>
                            </p>
                            <p style="position:absolute;top:13px;right:84px;">
                                <?php echo $monthThai[date("n",$payThai_date)]; ?></p>
                            <p style="position:absolute;top:13px;right:34px;"><?php echo date("Y",$payThai_date); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p style="font-size:12px;">สอบถามเพิ่มเติม : 098-9132002 (เจ้าของหอพักบ้านพิงฟ้า)</p>
    </div>
    
</body>

</html>