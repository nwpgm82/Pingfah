<?php
// include("../connection.php");
if($_SESSION["rule"] == 0){
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="/Pingfah/css/modal.css">
    <title>Document</title>
</head>

<body>
    <div class="modal">
        <div class="card">
            <div class="rule-box">
                <h3>กฎระเบียบหอพัก " บ้านพิงฟ้า "</h3>
                <div class="hr"></div>
                <div>
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
                </div>
                <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                    <form id="submitForm">
                        <div style="display:flex;">
                            <input type="checkbox" id="check" onclick="checked_btn()">
                            <label>ข้าพเจ้ารับทราบและพร้อมปฏิบัติตามตามกฎระเบียบหอพักทุกข้อ</label>
                        </div>
                        <div style="padding-top:32px;display:flex;justify-content:center;align-items:center;">
                            <button type="submit" id="accept-rule" disabled>ตกลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/Pingfah/js/guest/rule-modal.js"></script>
</body>

</html>
<?php
}
?>