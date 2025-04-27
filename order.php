<?php
include 'inc-cus/link.php'; // Include the database connection

// Fetch user ID from the session
$user_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

if (!$user_id) {
    echo "<script>alert('Please log in to view your orders.'); window.location = 'foodziee.php';</script>";
    exit();
}

// Get the selected month from the form
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m'); // Default to current month

// Fetch order details for the user based on the selected month
$sql_orders = "SELECT o.order_id, r.res_name, o.total_amt, o.otp_delivery, o.order_date, o.order_status
               FROM orders o
               JOIN r_o_details r ON o.r_o_id = r.r_o_id
               WHERE o.c_id = ? AND DATE_FORMAT(o.order_date, '%Y-%m') = ? 
               ORDER BY o.order_date DESC";
$orders_result = selectt($sql_orders, [$user_id, $selected_month], 'is');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <?php include 'inc-cus/c-header.php'; ?>

    <div class="orders-container">
        <h2 class="order-header">Your Orders</h2>

        <!-- Month Filter Form -->
        <form method="GET">
            <label for="month">Filter by Month:</label>
            <input type="month" id="month" name="month" value="<?php echo htmlspecialchars($selected_month); ?>">
            <button type="submit">Filter</button>
        </form>

        <?php if (mysqli_num_rows($orders_result) > 0): ?>
            <!-- Display each order -->
            <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                <div class="order-item">
                    <div class="order-details">
                        <p><strong>Restaurant: </strong><?php echo htmlspecialchars($order['res_name']); ?></p>
                        <p><strong>Total Amount: </strong>₹<?php echo number_format($order['total_amt'], 2); ?></p>
                        <p><strong>Order Date: </strong><?php echo date('d M Y', strtotime($order['order_date'])); ?></p>
                        <p><strong>Status: </strong><?php echo ucfirst($order['order_status']); ?></p>
                    </div>
                    <div class="order-otp">
                        <p><strong>OTP for Delivery: </strong><span style="color: red;"><?php echo $order['otp_delivery']; ?></span></p>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <p>No orders found for the selected month.</p>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <?php include 'inc-cus/c-footer.php'; ?>

</body>

</html>
