<?php
session_start();
if($_SESSION['level'] == 'guest'){
    include('../../connection.php');
    include("../rule-modal.php");
    include('../../../components/sidebarGuest.php'); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/rule.css">
    <title>Document</title>
</head>

<body>
    <div class="box">
        <div style="padding:24px;">
            <div class="rule-box">
                <h3>กฎระเบียบหอพัก " บ้านพิงฟ้า "</h3>
                <div class="hr"></div>
                <form>
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
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}
?>