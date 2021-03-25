<?php include('connection.php'); ?>

<?php 
    $room_id = $_POST['room_id'];
    $room_type = $_POST['room_type'];
    $room_status = $_POST['room_status'];
    $sql = "INSERT INTO roomlist (room_id, room_type, room_status) VALUES ('$room_id','$room_type', '$room_status')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
      
      $conn->close();

?>