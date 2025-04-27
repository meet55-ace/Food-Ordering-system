<?php
include 'link.php';

if(isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status']; // 1 = Enabled, 0 = Disabled

    $sql = "UPDATE r_o_details SET status = '$status' WHERE r_o_id = '$id'";
    if(mysqli_query($con, $sql)) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>
