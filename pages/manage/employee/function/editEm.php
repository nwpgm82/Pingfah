<?php
session_start();
if($_SESSION['level'] == 'admin'){
    if(isset($_POST['accept_edit'])){
        include('../../../connection.php');
        $username = $_REQUEST['username'];
        $title_name = $_POST['title_name'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $nickname = $_POST['nickname'];
        $position = $_POST['position'];
        $id_card = $_POST['id_card'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $id_line = $_POST['id_line'];
        $birthday = $_POST['birthday'];
        $age = $_POST['age'];
        $race = $_POST['race'];
        $nat = $_POST['nationality'];
        $add = $_POST['address'];
        @$profile_img = $_FILES['profile_img']['name'];
        @$pic_idcard = $_FILES['pic_idcard']['name'];
        @$pic_home = $_FILES['pic_home']['name'];
        @$target1 = "../../../images/employee/$username/".basename($pic_idcard);
        @$target2 = "../../../images/employee/$username/".basename($pic_home);
        @$target3 = "../../../images/employee/$username/".basename($profile_img);
        $update = "UPDATE employee SET title_name = '$title_name', firstname = '$firstname', lastname = '$lastname', nickname = '$nickname', position = 'พนักงาน', id_card = '$id_card', tel = '$tel', email = '$email', id_line = '$id_line', birthday = '$birthday', age = '$age', race = '$race', nationality = '$nat', address = '$add' WHERE username = '$username' ";
        if ($conn->query($update) === TRUE) {
            if($pic_idcard != ""){
                $update_pic1 = "UPDATE employee SET pic_idcard = '$pic_idcard' WHERE username = '$username' ";
                if(move_uploaded_file($_FILES['pic_idcard']['tmp_name'], $target1)){
                    if ($conn->query($update_pic1) === TRUE) {    
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                }
            }
            if($pic_home != ""){
                $update_pic2 = "UPDATE employee SET pic_home = '$pic_home' WHERE username = '$username' ";
                if(move_uploaded_file($_FILES['pic_home']['tmp_name'], $target2)){
                    if ($conn->query($update_pic2) === TRUE) {    
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                }
            }
            if($profile_img != ""){
                $update_pic3 = "UPDATE employee SET profile_img = '$profile_img' WHERE username = '$username' ";
                if(move_uploaded_file($_FILES['profile_img']['tmp_name'], $target3)){
                    if ($conn->query($update_pic3) === TRUE) {    
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                }
            }
            echo "<script>";
            echo "alert('แก้ไขข้อมูลพนักงานสำเร็จ');";
            echo "location.href = '../../employee/emDetail.php?username=$username';";
            echo "</script>";
            echo "success!!";
        }else{
            echo "Yz";
        }
    }
}else{
    Header("Location: ../../../login.php");
}

?>