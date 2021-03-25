<?php
session_start();
if($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'employee'){
    if(isset($_POST['accept-edit'])){
        include('../../../connection.php');
        @$profile_img = $_FILES['profile_img']['name'];
        $searchData = mysqli_query($conn, "SELECT id_card FROM employee WHERE email = '".$_SESSION['ID']."'");
        $result = mysqli_fetch_assoc($searchData);
        @$target = "../../../images/employee/".$result['id_card']."/".basename($profile_img);
        if($profile_img != ""){
            $update_pic = "UPDATE employee SET profile_img = '$profile_img' WHERE email = '".$_SESSION['ID']."' ";
            if(move_uploaded_file($_FILES['profile_img']['tmp_name'], $target)){
                if ($conn->query($update_pic) === TRUE) {
                    echo "<script>";
                    echo "alert('แก้ไขข้อมูลพนักงานสำเร็จ');";
                    echo "location.href = '../index.php';";
                    echo "</script>";    
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
        }else{
            echo "<script>";
            echo "alert('แก้ไขข้อมูลพนักงานสำเร็จ');";
            echo "location.href = '../index.php';";
            echo "</script>"; 
        }  
    }
}else{
    Header("Location: ../../../login.php");
}

?>