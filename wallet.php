<?php
// Start the session
include 'inc-res/link.php'; 

// Ensure the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: res-lo.php');
    exit;
}

// Retrieve the restaurant ID from the session
$restaurant_id = $_SESSION['r_o_id']; 

// Fetch total earnings for the restaurant (sum of confirmed orders)
$total_earnings_query = "SELECT SUM(total_amt) AS total_earnings FROM orders WHERE r_o_id = ? AND order_status = 'confirmed'";
$stmt = $con->prepare($total_earnings_query);
$stmt->bind_param('i', $restaurant_id); // Bind the restaurant ID from the session
$stmt->execute();
$result = $stmt->get_result();
$total_earnings = 0;

if ($row = $result->fetch_assoc()) {
    $total_earnings = $row['total_earnings'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - Restaurant</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/wallet.css"> 
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="bg-light">
<?php include 'inc-res/r-header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card wallet-card">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="card-text">
                        <strong>Total Earnings:</strong> ₹<?php echo number_format($total_earnings, 2); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <?php include 'inc-res/r-footer.php'; ?>
</footer>

</body>
</html>
