<?php
include 'inc-del/link.php'; // Include database connection file

// Check if delivery person is logged in
if (!isset($_SESSION['d_id'])) {
    echo "<script>alert('You need to login first');</script>";
    echo "<script>window.location = 'dev-lo.php';</script>";
    exit();
}

$d_id = $_SESSION['d_id'];

// Fetch only delivered orders assigned to the logged-in delivery person
$sql_fetch_delivered_orders = "
    SELECT do.d_o_id, do.order_id, do.c_address, do.res_address, do.delivery_status, do.assigned_date
    FROM delivery_orders do
    JOIN orders o ON o.order_id = do.order_id
    JOIN delivery_assigned da ON da.d_o_id = do.d_o_id
    WHERE o.order_status = 'confirmed' 
      AND do.delivery_status = 'delivered' 
      AND da.d_id = ?  -- Filter by logged-in delivery person ID
";

// Pass $d_id as a parameter
$delivered_orders = selectt($sql_fetch_delivered_orders, [$d_id], 'i');


// $delivered_orders = selectt($sql_fetch_delivered_orders, [$d_id], ''); // Pass the delivery person ID to filter the results

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Orders</title>
    <link rel="stylesheet" href="css/delivery.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body>
    <?php include 'inc-del/d-header.php'; ?>

    <div class="container">
        <h2>All Delivered Orders</h2>

        <?php if (!empty($delivered_orders)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Address</th>
                        <th>Restaurant Address</th>
                        <th>Status</th>
                        <th>Assigned Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($delivered_orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['c_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['res_address']); ?></td>
                            <td>
                                <span class="badge bg-success">Delivered</span>
                            </td>
                            <td><?php echo $order['assigned_date'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No delivered orders available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
