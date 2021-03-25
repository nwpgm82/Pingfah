<!-- หน้าลูกค้าแจ้งออกจากที่พัก ***ทำพรุ่งนี้*** -->
<?php
session_start();
if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee") {
    include "../../connection.php";
    // function dateDifference($date_1, $date_2, $differenceFormat = '%h')
    // {
    //     $datetime1 = date_create($date_1);
    //     $datetime2 = date_create($date_2);

    //     $interval = date_diff($datetime1, $datetime2);

    //     return $interval->format($differenceFormat);

    // }
    function DateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate));
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("d", strtotime($strDate));
        $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $strMonthThai = $strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }
    $id = $_REQUEST["ID"];
    $member_data = mysqli_query($conn, "SELECT a.*, b.room_type, c.price, c.water_bill, c.elec_bill, c.cable_charge FROM roommember a INNER JOIN roomlist b ON a.room_id = b.room_id INNER JOIN roomdetail c ON b.room_type = c.type WHERE a.room_id = '$id' AND a.member_status = 'กำลังเข้าพัก'");
    $member_result = mysqli_fetch_assoc($member_data);
    $d1 = $member_result["come_date"];
    $d2 = date("Y-m-d");
    $month_count = (int) abs((strtotime($d1) - strtotime($d2)) / (60 * 60 * 24 * 30));
    if ($month_count >= 6) {
        $room_deposit = "ได้";
        $deposit = $member_result["member_deposit"];
    } else {
        $room_deposit = "ไม่ได้";
        $deposit = 0;
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/member_quit.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <script src="../../../js/manage/member_quit.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include "../../../components/sidebar.php";?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <!-- <form action="function/action.php?ID=<?php echo $id ?>" method="POST"> -->
            <div class="quit-box">
                <h3>ห้อง <?php echo $id; ?> | ประเภท : <?php echo $member_result["room_type"]; ?></h3>
                <div class="hr"></div>
                <div class="grid-container">
                    <div>
                        <p>ชื่อ</p>
                        <input type="text" value="<?php echo $member_result["firstname"]; ?>" disabled>
                    </div>
                    <div>
                        <p>นามสกุล</p>
                        <input type="text" value="<?php echo $member_result["lastname"]; ?>" disabled>
                    </div>
                    <div>
                        <p>เลขบัตรประชาชน / Passport No.</p>
                        <input type="text" value="<?php echo $member_result["id_card"]; ?>" disabled>
                    </div>
                    <div>
                        <p>เบอร์โทรศัพท์</p>
                        <input type="text" value="<?php echo $member_result["phone"]; ?>" disabled>
                    </div>
                    <div>
                        <p>อีเมล</p>
                        <input type="text" value="<?php echo $member_result["email"]; ?>" disabled>
                    </div>
                </div>
                <div style="padding-top:32px;">
                    <h3>ข้อมูลการเข้าพัก</h3>
                    <div class="hr"></div>
                    <div class="grid-container">
                        <div>
                            <p>วันที่เริ่มเข้าพัก</p>
                            <input type="text" value="<?php echo DateThai($member_result["come_date"]); ?>" disabled>
                        </div>
                        <div>
                            <p>วันที่แจ้งออก</p>
                            <input type="text" value="<?php echo DateThai(date("Y-m-d")); ?>" disabled>
                        </div>
                        <div>
                            <p>จำนวนเดือนที่เข้าพัก (เดือน)</p>
                            <input type="text" value="<?php echo $month_count; ?>" disabled>
                        </div>
                        <div>
                            <p>สิทธิ์การได้เงินประกัน</p>
                            <input type="text" value="<?php echo "$room_deposit (".number_format($member_result["member_deposit"]).")"; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="quit-detail">
                    <h3>รายละเอียดค่าใช้จ่าย</h3>
                    <div class="hr"></div>
                    <table class="main-table">
                        <tr>
                            <th>ลำดับ</th>
                            <th>รายการ</th>
                            <th>หน่วย/รายการ</th>
                            <th>จำนวนเงิน (บาท)</th>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div class="list">
                                    <table class="sub-table">
                                        <tr>
                                            <td>1</td>
                                            <td>ค่าเคเบิล</td>
                                            <td>1</td>
                                            <td id="cable_price">105</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>ค่าน้ำ</td>
                                            <td><?php echo $member_result["people"]; ?></td>
                                            <td id="water_price"><?php echo $member_result["water_bill"] * $member_result["people"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>ค่าไฟ</td>
                                            <td><input type="text" id="elec_unit" placeholder="หน่วย"></td>
                                            <td id="elec_price">0</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="damage">
                                                    <p>ค่าเสียหาย</p>
                                                    <div id="add_form">
                                                        <div class="add-form">
                                                            <div>
                                                                <p>หัวข้อรายการ</p>
                                                                <input type="text" id="damage_topic">
                                                            </div>
                                                            <div>
                                                                <p>ค่าใช้จ่าย</p>
                                                                <input type="text" id="d_price">
                                                            </div>
                                                            <button type="button" id="addDamage_form">เพิ่ม</button>
                                                        </div>
                                                        <div class="arrow-down"></div>
                                                    </div>
                                                    <button type="button" class="plus-btn">เพิ่มค่าเสียหาย</button>
                                                </div>
                                                <div id="damage-list" style="line-height:40px;padding-top:16px;">
                                                    
                                                </div>
                                            </td>
                                            <td id="damage_unit">0</td>
                                            <td id="damage_price">0</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <p>ยอดรวมทั้งหมด</p>
                            </td>
                            <td>
                                <p id="total_price">0</p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="2">
                                <h4>สรุปค่าใช้จ่ายทั้งหมด</h4>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <p>ค่าประกันหอพัก</p>
                            </td>
                            <td>
                                <p id="deposit"><?php echo ceil($deposit); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <p>ค่าประกันหอพักหลังจากหักค่าใช้จ่ายในรายการต่างๆ</p>
                            </td>
                            <td>
                                <p id="b_deposit">100</p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <p>จำนวนเงินที่ต้องชำระทั้งสิ้น</p>
                            </td>
                            <td>
                                <p id="b_total">0</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="hr"></div>
                <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                    <button id="confirm_quit" name="quit">ยืนยัน</button>
                </div>
            </div>
            
            <!-- </form> -->
        </div>
    </div>
    <script>
    let total_price = $("#total_price")
    let cable = $("#cable_price")
    let water = $("#water_price")
    let elec = $("#elec_price")
    let damage = $("#damage_price")
    const deposit = $("#deposit")
    total_price.html(parseFloat(cable.html()) + parseFloat(water.html()) + parseFloat(elec.html()) + parseFloat(damage.html()))
    $("#b_deposit").html(parseFloat(deposit.html()) - parseFloat(total_price.html()))
    if(parseFloat($("#b_deposit").html()) <= 0){
        $("#b_total").html(Math.abs(parseFloat($("#b_deposit").html())))
        $("#b_deposit").html(0)
    }
    $("#elec_unit").keyup(function(event){
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if($("#elec_unit").val() != ""){
            $("#elec_unit").css("border-color", "")
            elec.html(Math.ceil((parseFloat($("#elec_unit").val()) * <?php if (isset($member_result["elec_bill"])) {echo $member_result["elec_bill"];} else {echo 0;}?>).toFixed(2)))
        }else{
            elec.html(0)
            $("#elec_unit").css("border-color", "red")
        }
        total_price.html(parseFloat(cable.html()) + parseFloat(water.html()) + parseFloat(elec.html()) + parseFloat(damage.html()))
        $("#b_deposit").html(parseFloat(deposit.html()) - parseFloat(total_price.html()))
        if(parseFloat($("#b_deposit").html()) <= 0){
            $("#b_total").html(Math.abs(parseFloat($("#b_deposit").html())))
            $("#b_deposit").html(0)
        }else{
            $("#b_total").html(0)
        }
    })
    // const deposit = $("#deposit").val()
    // $("#total_price").html(   parseFloat($("#cable_price").html()) + parseFloat($("#water_price").html()) + parseFloat(elec.html())    )
    // $("#elec_unit").keyup(function(event) {
    //     $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
    //     if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    //         event.preventDefault();
    //     }
    //     if ($("#elec_unit").val() != "") {
    //         $("#elec_unit").css("border-color", "")
    //         elec.html(Math.ceil((parseFloat($("#elec_unit").val()) *
    //             <?php if (isset($member_result["elec_bill"])) {echo $member_result["elec_bill"];} else {echo 0;}?>
    //         ).toFixed(2)))
    //     } else {
    //         elec.html(0)
    //         $("#elec_unit").css("border-color", "red")
    //     }
    //     if (parseFloat($("#deposit").html()) <= 0) {
    //         $("total_price").html($("#total_price").html((parseFloat($("#cable_price").html()) + parseFloat($(
    //                 "#water_price").html()) + parseFloat(elec.html()) + Math.abs($("#deposit").html()))
    //             .toFixed(2)))
    //     } else {
    //         $("total_price").html($("#total_price").html((parseFloat($("#cable_price").html()) + parseFloat($(
    //             "#water_price").html()) + parseFloat(elec.html()) - parseFloat($("#deposit")
    //             .html())).toFixed(2)))
    //     }
    // })
    // $("#fines").keyup(function(event) {
    //     $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
    //     if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    //         event.preventDefault();
    //     }
    //     if ($("#fines").val() != "") {
    //         $("#fines").css("border-color", "")
    //         $("#deposit").val((parseFloat(deposit) - parseFloat($("#fines").val())).toFixed(2))
    //         if (parseFloat($("#deposit").val()) <= 0) {
    //             $("total_price").val($("#total_price").val((parseFloat($("#cable_price").val()) + parseFloat($(
    //                 "#water_price").val()) + parseFloat(elec.val()) + Math.abs($("#deposit")
    //                 .val())).toFixed(2)))
    //         } else {
    //             $("total_price").val($("#total_price").val((parseFloat($("#cable_price").val()) + parseFloat($(
    //                 "#water_price").val()) + parseFloat(elec.val()) - parseFloat($("#deposit")
    //                 .val())).toFixed(2)))
    //         }
    //     } else {
    //         $("#fines").css("border-color", "red")
    //         $("#deposit").val(deposit)
    //         $("total_price").val($("#total_price").val((parseFloat($("#cable_price").val()) + parseFloat($(
    //             "#water_price").val()) + parseFloat(elec.val()) - parseFloat($("#deposit")
    //             .val())).toFixed(2)))
    //     }
    // })
    </script>
</body>

</html>
<?php
} else {
    header("Location: ../../login.php");
}
?>