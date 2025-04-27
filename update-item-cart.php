<?php
// session_start();
include 'inc-cus/link.php'; // Include the database connection

if (isset($_GET['cart_id'], $_GET['action']) && isset($_SESSION['user_id'])) {
    $cart_id = (int) $_GET['cart_id'];
    $action = $_GET['action'];
    $user_id = $_SESSION['user_id'];

    // Get the current quantity
    $sql_check = "SELECT quantity FROM cart WHERE cart_id = ? AND user_id = ?";
    $result = selectt($sql_check, [$cart_id, $user_id], 'ii');
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $current_quantity = $row['quantity'];
        
        // Update quantity based on action
        if ($action === 'increment') {
            $new_quantity = $current_quantity + 1;
        } elseif ($action === 'decrement' && $current_quantity > 1) {
            $new_quantity = $current_quantity - 1;
        } else {
            $new_quantity = $current_quantity;
        }

        // Update the quantity in the cart
        $sql_update = "UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?";
        selectt($sql_update, [$new_quantity, $cart_id, $user_id], 'iii');
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
} else {
    echo "Invalid request!";
}
?>
