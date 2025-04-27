<?php
// session_start();
include 'inc-cus/link.php';

if (!isset($_SESSION['login']) || !isset($_GET['order_id'])) {
    echo "<script>alert('Unauthorized access.'); window.location = 'foodziee.php';</script>";
    exit();
}

$order_id = $_GET['order_id'];
$uid = $_SESSION['uid'];

$sql = "SELECT o.total_amt, r.res_name 
        FROM orders o
        JOIN r_o_details r ON o.r_o_id = r.r_o_id
        WHERE o.order_id = ? AND o.c_id = ?";
$result = selectt($sql, [$order_id, $uid], 'ii');
if (!$result || $result->num_rows == 0) {
    echo "<script>alert('Invalid order ID.'); window.location = 'foodziee.php';</script>";
    exit();
}
$order_data = $result->fetch_assoc();
$total_amount = $order_data['total_amt'];
$res_name = $order_data['res_name'];

$sql_customer = "SELECT c_name, c_email, c_phone FROM c_register WHERE c_id = ?";
$customer_result = selectt($sql_customer, [$uid], 'i');
if (!$customer_result || $customer_result->num_rows == 0) {
    echo "<script>alert('Customer details not found.'); window.location = 'foodziee.php';</script>";
    exit();
}
$customer_data = $customer_result->fetch_assoc();
$c_name = $customer_data['c_name'];
$c_email = $customer_data['c_email'];
$c_phone = $customer_data['c_phone'];

$appId = "TEST103994334ca066ff43e80d0f2dfa33499301";
$secretKey = "cfsk_ma_test_eebea8732b7df6b8ff23b373a1b8d3f0_4ea7d81a"; 
$returnUrl = "http://localhost/foodziee/success.php?order_id=$order_id";
$notifyUrl = "http://localhost/notify.php";

$postData = array(
    "appId" => $appId,
    "orderId" => $order_id,
    "orderAmount" => $total_amount,
    "orderCurrency" => "INR",
    "orderNote" => "Order for " . $res_name,
    "customerName" => $c_name,
    "customerPhone" => $c_phone,
    "customerEmail" => $c_email,
    "returnUrl" => $returnUrl,
    "notifyUrl" => $notifyUrl,
);
// echo "<pre>";
// echo "Raw Data (before signature):<br>";
// print_r($postData);
// echo "</pre>";

ksort($postData);

$signatureData = "";
foreach ($postData as $key => $value) {
    $signatureData .= $key . $value;
}

$signature = hash_hmac('sha256', $signatureData, $secretKey, true);
$signature = base64_encode($signature);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include 'inc-cus/c-header.php'; ?>

    <div class="container py-5">
        <h1 class="text-center mb-5">Payment Gateway</h1>
        <div class="card shadow-lg p-4">
            <form id="paymentForm" method="POST" action="https://test.cashfree.com/billpay/checkout/post/submit">
                <input type="hidden" name="appId" value="<?php echo $appId; ?>">
                <input type="hidden" name="orderId" value="<?php echo $order_id; ?>">
                <input type="hidden" name="orderAmount" value="<?php echo $total_amount; ?>">
                <input type="hidden" name="orderCurrency" value="INR">
                <input type="hidden" name="orderNote" value="Order for <?php echo $res_name; ?>">
                <input type="hidden" name="customerName" value="<?php echo htmlspecialchars($c_name); ?>">
                <input type="hidden" name="customerEmail" value="<?php echo htmlspecialchars($c_email); ?>">
                <input type="hidden" name="customerPhone" value="<?php echo htmlspecialchars($c_phone); ?>">
                <input type="hidden" name="returnUrl" value="<?php echo $returnUrl; ?>">
                <input type="hidden" name="notifyUrl" value="<?php echo $notifyUrl; ?>">
                <input type="hidden" name="signature" value="<?php echo $signature; ?>">

                <div class="form-group mb-4">
                    <label for="totalAmount">Total Amount:</label>
                    <p id="totalAmount" class="display-4">₹<?php echo number_format($total_amount, 2); ?></p>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg payment">Proceed to Pay</button>
            </form>
        </div>
    </div>

    <?php include 'inc-cus/c-footer.php'; ?>
</body>

</html>
