<?php
// Start the session
// session_start();

// Include the database connection file
include 'inc-cus/link.php'; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo "You need to log in to remove items from your cart.";
    exit();
}

// Get the user ID from the session
$uid = $_SESSION['uid'];

// Check if the cart_id is passed via the URL
if (isset($_GET['remove_item_id'])) {
    // Get the cart_id from the URL
    $cart_id = (int) $_GET['remove_item_id'];

    // SQL query to delete the item from the user_cart table
    $sql_remove = "DELETE FROM user_cart WHERE c_id = ? AND cart_id = ?";

    // Execute the query to remove the item from the cart
    selectt($sql_remove, [$uid, $cart_id], 'ii');

    // Redirect to the cart page to refresh after removal
    header("Location: cart.php");
    exit();
} else {
    // If no cart_id is provided, display an error message
    echo "Invalid request. Cart ID not provided.";
}
?>
