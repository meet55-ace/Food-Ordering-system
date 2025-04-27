<?php
include 'inc-cus/link.php'; // Include the database connection

// Fetch order details
if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];

    // Check if the order already has an OTP
    $sql_check_otp = "SELECT otp_delivery FROM orders WHERE order_id = ?";
    $otp_result = selectt($sql_check_otp, [$order_id], 'i');
    $otp = null;

    if (mysqli_num_rows($otp_result) > 0) {
        $otp_row = mysqli_fetch_assoc($otp_result);

        // If OTP exists, fetch it
        if (!empty($otp_row['otp_delivery'])) {
            $otp = $otp_row['otp_delivery'];
        } else {
            // If OTP doesn't exist, generate and store it
            $otp = random_int(100000, 999999); // Generate a 6-digit OTP
            $sql_update_otp = "UPDATE orders SET otp_delivery = ? WHERE order_id = ?";
            selectt($sql_update_otp, [$otp, $order_id], 'ii');
        }
    }

    // Fetch the main order details
    $sql_order = "SELECT o.order_id, o.total_amt, o.order_date, o.order_status, r.res_name, o.otp_delivery
                  FROM orders o
                  JOIN r_o_details r ON o.r_o_id = r.r_o_id
                  WHERE o.order_id = ?";
    $order_result = selectt($sql_order, [$order_id], 'i');

    // Fetch the item details for the order
    $sql_order_items = "SELECT od.item_id, i.item_name, od.qty, od.sub_total
                        FROM order_details od
                        JOIN item i ON od.item_id = i.item_id
                        WHERE od.order_id = ?";
    $items_result = selectt($sql_order_items, [$order_id], 'i');
} else {
    echo "<script>alert('Invalid order ID.'); window.location = 'cart.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="css/bill.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <?php include 'inc-cus/c-header.php'; ?>

    <div class="bill-container">
        <h2>Order Summary</h2>

        <?php if (mysqli_num_rows($order_result) > 0): 
            $order = mysqli_fetch_assoc($order_result);
        ?>
        <!-- OTP Display -->
        <div class="otp-display">
            <h1>Delivery OTP: <span><?php echo $order['otp_delivery']; ?></span></h1>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Restaurant:</strong> <?php echo htmlspecialchars($order['res_name']); ?></p>
            <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amt'], 2); ?></p>
            <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>

            <!-- Items List -->
            <h3>Items:</h3>
            <ul>
                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['item_name']); ?></strong> - 
                    Qty: <?php echo $item['qty']; ?> - 
                    Subtotal: ₹<?php echo number_format($item['sub_total'], 2); ?>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <?php else: ?>
        <p>Invalid order details. Please try again.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'inc-cus/c-footer.php'; ?>

</body>

</html>
