<?php
session_start();
if($_SESSION["level"] == "admin" || $_SESSION["level"] == "employee"){
    include("../../connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/gallery_detail.css">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../../../js/sidebar.js"></script>
    <title>Pingfah Apartment</title>
</head>

<body>
    <?php include("../../../components/sidebar.php"); ?>
    <div class="box">
        <div id="box-padding" style="padding:24px;">
            <div class="gallery-card">
                <div class="header">
                    <h3>รายการแกลเลอรี่ทั้งหมด</h3>
                    <form id="submitForm" enctype="multipart/form-data">
                        <input type="file" name="file" id="file" class="inputfile"/>
                        <label for="file">เพิ่มรูปภาพ</label>
                    </form>
                </div>
                <div class="hr"></div>
                <div class="grid">
                    <?php
                    $perpage = 8;
                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    }else{
                        $page = 1;
                    }
                    $start = ($page - 1) * $perpage;
                    $sql = "SELECT * FROM gallery ORDER BY gallery_id DESC LIMIT {$start} , {$perpage}";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="img-box">
                        <img src="../../images/gallery/<?php echo $row['gallery_name']; ?>" alt="">
                        <button class="del-btn" id="<?php echo $row['gallery_id']; ?>" name="<?php echo $row['gallery_name']; ?>"></button>
                    </div>
                    <?php
                        }
                    }else{
                        echo "ไม่มีรูปภาพ";
                    }
                    ?>
                </div>
                <?php
                ///////pagination
                $sql2 = "SELECT * FROM gallery";
                $query2 = mysqli_query($conn, $sql2);
                $total_record = mysqli_num_rows($query2);
                $total_page = ceil($total_record / $perpage);
                ?>
                <div style="display:flex;justify-content:flex-end">
                    <div class="pagination">
                        <a href="index.php?page=1">&laquo;</a>
                        <?php for($i=1;$i<=$total_page;$i++){ ?>
                        <a href="index.php?page=<?php echo $i; ?>" <?php if($page == $i){ echo "style='background-color: rgb(131, 120, 47, 1);color:#fff;'"; }?> ><?php echo $i; ?></a>
                        <?php } ?>
                        <a href="index.php?page=<?php echo $total_page; ?>">&raquo;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../../js/manage/gallery.js"></script>
</body>

</html>
<?php
}else{
    Header("Location: ../../login.php"); 
}