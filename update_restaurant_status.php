<?php
include 'inc-res/link.php';
// session_start();

if (isset($_POST['is_open'])) {
    $restaurant_id = $_SESSION['r_o_id'];
    $is_open = $_POST['is_open'];

    $query = "UPDATE r_o_details SET is_open = ? WHERE r_o_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ii', $is_open, $restaurant_id);
    $stmt->execute();
}
?>
