<?php
// session_start();
include 'inc-cus/link.php'; // Include the database connection

// Check if user is logged in
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];

    // Fetch cart items and their details (item name, price, quantity, restaurant name, image, r_o_id)
    $sql = "SELECT uc.cart_id, uc.item_id, uc.quantity, i.item_name, i.item_price, r.res_name, i.item_image, r.r_o_id
            FROM user_cart uc
            JOIN item i ON uc.item_id = i.item_id
            JOIN r_o_details r ON i.r_o_id = r.r_o_id
            WHERE uc.c_id = ?";
    $cart_result = selectt($sql, [$uid], 'i');
} else {
    // If the user is not logged in, show an alert and then redirect
    if (!isset($_SESSION['uid'])) {
        echo "<script>alert('You need to login first');</script>";
        echo "<script>window.location = 'foodziee.php';</script>";
        exit(); // Ensure the script stops executing after the redirect
    }
}

// Handle item removal from the cart
if (isset($_GET['remove_item_id'])) {
    $remove_item_id = (int) $_GET['remove_item_id'];
    $sql_remove = "DELETE FROM user_cart WHERE c_id = ? AND cart_id = ?";
    selectt($sql_remove, [$uid, $remove_item_id], 'ii');
    // Redirect back to cart page to refresh after removal
    header("Location: cart.php");
    exit();
}

// Handle clear cart logic with confirmation
if (isset($_POST['clear_cart'])) {
    // Confirmation before clearing the cart
    echo "<script>
            if (confirm('Are you sure you want to clear the cart?')) {
                // Proceed to clear the cart
                window.location.href = 'cart.php?confirm_clear=true';
            }
          </script>";
    exit();
}

// Perform actual cart clearing if confirmed
if (isset($_GET['confirm_clear']) && $_GET['confirm_clear'] == 'true') {
    // Delete all items in the user's cart
    $sql_clear_cart = "DELETE FROM user_cart WHERE c_id = ?";
    selectt($sql_clear_cart, [$uid], 'i');
    // Redirect to the cart page to reflect the changes
    header("Location: cart.php");
    exit();
}

// Handle order now logic
if (isset($_POST['order_now'])) {
    // Start a database transaction
    $con->begin_transaction();
    try {
        // Calculate total price and get restaurant ID
        $total_price = 0;
        $r_o_id = null;

        mysqli_data_seek($cart_result, 0); // Reset result pointer
        while ($row = mysqli_fetch_assoc($cart_result)) {
            if ($r_o_id === null) {
                $r_o_id = $row['r_o_id']; // All items from the same restaurant
            }
            $total_price += $row['item_price'] * $row['quantity'];
        }

        // Insert a new order into the orders table
        $sql_insert_order = "INSERT INTO orders (c_id, r_o_id, total_amt, order_status) VALUES (?, ?, ?, 'pending')";
        selectt($sql_insert_order, [$uid, $r_o_id, $total_price], 'iis');

        // Get the last inserted order_id
        $order_id = $con->insert_id;

        // Insert order details for each cart item
        mysqli_data_seek($cart_result, 0); // Reset result pointer
        while ($row = mysqli_fetch_assoc($cart_result)) {
            $item_id = $row['item_id'];
            $quantity = $row['quantity'];
            $subtotal = $row['item_price'] * $quantity;

            $sql_insert_order_details = "INSERT INTO order_details (order_id, item_id, qty, sub_total, restaurant_status) VALUES (?, ?, ?, ?, 'pending')";
            selectt($sql_insert_order_details, [$order_id, $item_id, $quantity, $subtotal], 'iiis');
        }

        // Clear the user's cart
        $sql_clear_cart = "DELETE FROM user_cart WHERE c_id = ?";
        selectt($sql_clear_cart, [$uid], 'i');

        // Commit the transaction
        $con->commit();

        // Redirect to the bill page with the order_id
        header("Location: bill.php?order_id=$order_id");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo "<script>alert('Failed to place the order. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <?php include 'inc-cus/c-header.php'; ?>

    <!-- Cart Page Content -->
    <div class="cart-container">
        <div class="cart-header">
            <h2>Your Cart</h2>
            <div>
                <!-- Form for Clear Cart Button -->
                <form method="POST">
                    <button type="submit" name="clear_cart" class="clear-cart-btn">Clear Cart</button>
                </form>
            </div>
        </div>

        <?php if (mysqli_num_rows($cart_result) > 0): ?>
        <div id="cart-items">
            <?php 
                $total_price = 0;

                // Loop through each cart item
                while ($row = mysqli_fetch_assoc($cart_result)) {
                    $item_name = htmlspecialchars($row['item_name']); // Safe output
                    $item_price = $row['item_price'];
                    $quantity = $row['quantity'];
                    $res_name = htmlspecialchars($row['res_name']); // Safe output
                    $cart_id = $row['cart_id'];
                    $item_image = htmlspecialchars($row['item_image']); // Safe output for image URL

                    // Calculate the total price for this item
                    $item_total = $item_price * $quantity;
                    $total_price += $item_total;
                ?>

            <!-- Cart Item (Horizontal Layout) -->
            <div class="cart-item d-flex align-items-center">
                <div class="cart-item-image">
                    <img src="<?php echo $item_image; ?>" alt="<?php echo $item_name; ?>" />
                </div>
                <div class="cart-item-details">
                    <h5><?php echo $item_name; ?></h5>
                    <p><strong>Restaurant:</strong> <?php echo $res_name; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo number_format($item_price, 2); ?></p>
                    <p><strong>Subtotal:</strong> ₹<?php echo number_format($item_total, 2); ?></p>
                </div>
                <div class="cart-item-actions">
                    <!-- Quantity Update Form -->
                    <form method="POST" action="update-quantity.php" class="d-flex align-items-center">
                        <button type="submit" name="update_quantity" value="decrease" class="btn-minus">-</button>
                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1"
                            class="form-control quantity-input mx-2" />
                        <button type="submit" name="update_quantity" value="increase" class="btn-plus">+</button>
                        <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>" />
                    </form>

                    <!-- Remove Item Button -->
                    <a href="cart.php?remove_item_id=<?php echo $cart_id; ?>"
                        class="btn btn-danger btn-remove">Remove</a>
                </div>
            </div>

            <?php } ?>
        </div>

        <!-- Total Price Section -->
        <div class="total-section">
            <h4>Total: ₹<?php echo number_format($total_price, 2); ?></h4>
        </div>

        <!-- Order Now Button (Only for Logged-in Users) -->
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] == true): ?>
        <div class="cart-footer">
            <form method="POST">
                <button type="submit" name="order_now" class="btn btn-success" id="orderNowBtn">Order Now</button>
            </form>
        </div>
        <?php else: ?>
        <div class="cart-footer">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#loginModal">Login to Order</button>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <p>Your cart is empty. Start shopping!</p>
        <?php endif; ?>

        <!-- Back to Menu Link (or Home if cart is empty) -->
        <div class="cart-footer">
            <?php if (mysqli_num_rows($cart_result) > 0): ?>
            <a href="displayresitems.php?res_name=<?php echo urlencode($res_name); ?>" class="btn btn-primary">Go back to the menu</a>
            <?php else: ?>
            <a href="foodziee.php" class="btn btn-primary">Home</a>
            <?php endif; ?>
        </div>

    </div>

    <!-- Footer -->
    <?php include 'inc-cus/c-footer.php'; ?>

</body>

</html>
