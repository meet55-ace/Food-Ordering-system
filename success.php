<?php
include 'inc-cus/link.php';
function regenrate_session($uid){
    $user_q = selectt("SELECT * FROM `c_register` WHERE `c_id`=? LIMIT 1", [$uid], 'i');
    $user_fetch = mysqli_fetch_assoc($user_q);

    if ($user_fetch) {
        $_SESSION['login'] = true;
        $_SESSION['uid'] = $user_fetch['c_id']; 
        $_SESSION['uName'] = $user_fetch['c_name']; 
        $_SESSION['uphone'] = $user_fetch['c_phone'];
    } else {
        echo "<script>alert('Failed to regenerate session. User not found.'); window.location = 'foodziee.php';</script>";
        exit();
    }
}

if (!isset($_GET['order_id'])) {
    echo "<script>alert('Unauthorized access.'); window.location = 'foodziee.php';</script>";
    exit();
}

$order_id = $_GET['order_id'];

$sql_order = "SELECT o.order_id, o.total_amt, o.order_date, o.order_status, r.res_name, o.otp_delivery, o.c_id, o.r_o_id
              FROM orders o
              JOIN r_o_details r ON o.r_o_id = r.r_o_id
              WHERE o.order_id = ?";
$order_result = selectt($sql_order, [$order_id], 'i');

if ($order_result->num_rows == 0) {
    echo "<script>alert('Invalid order ID.'); window.location = 'cart.php';</script>";
    exit();
}

$order = $order_result->fetch_assoc();

if (empty($order['otp_delivery'])) {
    $otp = random_int(100000, 999999);
    $sql_update_otp = "UPDATE orders SET otp_delivery = ? WHERE order_id = ?";
    selectt($sql_update_otp, [$otp, $order_id], 'ii');
} else {
    $otp = $order['otp_delivery'];
}

$sql_order_items = "SELECT i.item_name, od.qty, od.sub_total
                    FROM order_details od
                    JOIN item i ON od.item_id = i.item_id
                    WHERE od.order_id = ?";
$items_result = selectt($sql_order_items, [$order_id], 'i');

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    regenrate_session($order['c_id']);  

}

$secretKey = "cfsk_ma_test_eebea8732b7df6b8ff23b373a1b8d3f0_4ea7d81a";
$orderAmount = $order['total_amt']; 

$orderId = trim($_POST["orderId"] ?? '');
$referenceId = trim($_POST["referenceId"] ?? '');
$txStatus = trim($_POST["txStatus"] ?? '');
$paymentMode = trim($_POST["paymentMode"] ?? '');
$txMsg = trim($_POST["txMsg"] ?? '');
$txTime = trim($_POST["txTime"] ?? '');

// Debugging: Print received values
// echo "<h3>Received Payment Data:</h3>";
// echo "Order ID: $orderId <br>";
// echo "Reference ID: $referenceId <br>";
// echo "Transaction Status: $txStatus <br>";
// echo "Payment Mode: $paymentMode <br>";
// echo "Transaction Message: $txMsg <br>";
// echo "Transaction Time: $txTime <br>";

if (!$orderId || !$orderAmount || !$referenceId || !$txStatus || !$paymentMode || !$txTime) {
    echo "<script>alert('Payment data missing. Please contact support.'); window.location = 'cart.php';</script>";
    exit();
}

$sql_insert_payment = "INSERT INTO payment_detail (order_id, c_id, r_o_id, payment_type, amount, payment_date)
                       VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql_insert_payment);
$stmt->bind_param("iiisss", $order_id, $order['c_id'], $order['r_o_id'], $paymentMode, $orderAmount, $txTime);

if ($stmt->execute()) {
    // echo "<h3>✅ Payment Recorded Successfully!</h3>";
} else {
    echo "<h3>❌ Database Error: " . $stmt->error . "</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="css/bill.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="bg-light">
    <?php include 'inc-cus/c-header.php'; ?>
    <div class="bill-container">
        <h2>Payment Successful!</h2>
        <h1>Delivery OTP: <span><?php echo $otp; ?></span></h1>
        <p>Your OTP is required for delivery. Please keep it safe.</p>

        <h3>Order Details:</h3>
        <p>Order ID: <?php echo $order['order_id']; ?></p>
        <p>Restaurant: <?php echo htmlspecialchars($order['res_name']); ?></p>
        <p>Total Amount: ₹<?php echo number_format($order['total_amt'], 2); ?></p>
        <p>Order Date: <?php echo $order['order_date']; ?></p>
        <p>Status: <?php echo ucfirst($order['order_status']); ?></p>

        <h3>Items:</h3>
        <ul>
            <?php while ($item = $items_result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($item['item_name']); ?> - Qty: <?php echo $item['qty']; ?> - 
                ₹<?php echo number_format($item['sub_total'], 2); ?></li>
            <?php endwhile; ?>
        </ul>
        <p>Thank you for your order!</p>
    </div>

    <?php include 'inc-cus/c-footer.php'; ?>
</body>
</html>

