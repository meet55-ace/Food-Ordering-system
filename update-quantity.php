<?php
// session_start();
include 'inc-cus/link.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('You need to log in to update your cart.');</script>";
    echo "<script>window.location = 'login.php';</script>";
    exit();
}

$c_id = $_SESSION['uid']; // Use the c_id (user ID) from session

if (isset($_POST['cart_id']) && isset($_POST['update_quantity']) && isset($_POST['quantity'])) {
    $cart_id = (int) $_POST['cart_id'];
    $action = $_POST['update_quantity'];
    $new_quantity = (int) $_POST['quantity'];

    // Fetch the current quantity
    $sql = "SELECT quantity FROM user_cart WHERE cart_id = ? AND c_id = ?";
    $result = selectt($sql, [$cart_id, $c_id], 'ii');

    if ($row = mysqli_fetch_assoc($result)) {
        $current_quantity = $row['quantity'];

        // Update quantity based on action
        if ($action == 'increase') {
            $current_quantity++;
        } elseif ($action == 'decrease' && $current_quantity > 1) {
            $current_quantity--;
        } elseif ($action == 'set') {
            if ($new_quantity > 0) {
                $current_quantity = $new_quantity;
            } else {
                echo "<script>alert('Quantity must be greater than 0.');</script>";
                echo "<script>window.location = 'cart.php';</script>";
                exit();
            }
        }

        // Update the database
        $sql_update = "UPDATE user_cart SET quantity = ? WHERE cart_id = ? AND c_id = ?";
        selectt($sql_update, [$current_quantity, $cart_id, $c_id], 'iii');

        echo "<script>window.location = 'cart.php';</script>";
    } else {
        echo "<script>alert('Item not found in your cart.');</script>";
        echo "<script>window.location = 'cart.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
    echo "<script>window.location = 'cart.php';</script>";
}
?>
