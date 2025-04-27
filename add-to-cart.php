<?php
// session_start();
include 'inc-cus/link.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('You need to log in to add items to the cart.');</script>";
    echo "<script>window.location = 'foodziee.php';</script>";
    exit();
}

$c_id = $_SESSION['uid']; // Use the c_id (user ID) from session

if (isset($_GET['item_id'])) {
    $item_id = (int) $_GET['item_id'];

    // Fetch the item and restaurant details
    $sql = "SELECT i.item_id, i.item_name, i.item_price, i.r_o_id, r.res_name
            FROM item i
            JOIN r_o_details r ON i.r_o_id = r.r_o_id
            WHERE i.item_id = ?";
    $item_result = selectt($sql, [$item_id], 'i');

    if (mysqli_num_rows($item_result) > 0) {
        $item_data = mysqli_fetch_assoc($item_result);
        $r_o_id = $item_data['r_o_id'];

        // Check if the cart contains items from a different restaurant
        $sql_cart_check = "SELECT i.r_o_id FROM user_cart c
                           JOIN item i ON c.item_id = i.item_id
                           WHERE c.c_id = ? LIMIT 1";
        $cart_result = selectt($sql_cart_check, [$c_id], 'i');

        if (mysqli_num_rows($cart_result) > 0) {
            $cart_data = mysqli_fetch_assoc($cart_result);
            if ($cart_data['r_o_id'] != $r_o_id) {
                echo "<script>alert('You can only add items from one restaurant. Clear your cart first.');</script>";
                echo "<script>window.location = 'cart.php';</script>";
                exit();
            }
        }

        // Check if the item is already in the cart
        $sql_check = "SELECT * FROM user_cart WHERE c_id = ? AND item_id = ?";
        $check_result = selectt($sql_check, [$c_id, $item_id], 'ii');

        if (mysqli_num_rows($check_result) > 0) {
            // If the item already exists, increase the quantity
            $sql_update = "UPDATE user_cart SET quantity = quantity + 1 WHERE c_id = ? AND item_id = ?";
            selectt($sql_update, [$c_id, $item_id], 'ii');
        } else {
            // Add the item to the cart
            $sql_insert = "INSERT INTO user_cart (c_id, item_id, quantity) VALUES (?, ?, ?)";
            selectt($sql_insert, [$c_id, $item_id, 1], 'iii');
        }

        echo "<script>alert('Item added to cart successfully.');</script>";
        echo "<script>window.location = 'cart.php';</script>";
    } else {
        echo "<script>alert('Item not found.');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
    echo "<script>window.location = 'menu.php';</script>";
}
?>
