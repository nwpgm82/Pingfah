<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee' || $_SESSION['level'] == 'guest'){
    include('../../connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/rule.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include('../../../components/sidebar.php'); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="rule-box">
                <h3>กฎระเบียบหอพัก " บ้านพิงฟ้า "</h3>
                <div class="hr"></div>
                <form action="../rule/function/ruleAccept.php" method="POST">
                    <textarea id="rule_detail" name="rule_detail" disabled><?php
                    $sql = "SELECT * FROM rule";
                    $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                          // output data of each row
                          while($row = $result->fetch_assoc()) {
                            echo $row['rule_detail'];
                          }
                        } else {
                          echo "0 results";
                        }
                    ?></textarea>
                    <?php
                    if($_SESSION['level'] == 'admin'){
                    ?>
                    <div id="edit_btn" style="display:flex;justify-content:center;margin-top:32px">
                        <button type="button" class="edit_btn" onclick="edit_rule()">แก้ไข</button>
                    </div>
                    <div id="accept_btn" style="justify-content:center;flex-flow:wrap;column-gap:16px;margin-top:32px;display:none">
                        <button type="submit" name="rule_accept">ยืนยันการแก้ไข</button>
                        <button type="button" class="cancel_btn" onclick="edit_rule()">ยกเลิก</button>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/rule.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>