<?php
include 'inc-del/link.php';

if (!isset($_SESSION['d_id'])) {
    echo "<script>alert('You need to login first');</script>";
    echo "<script>window.location = 'dev-lo.php';</script>";
    exit();
}

$d_id = $_SESSION['d_id'];

if (isset($_POST['accept_order'])) {
    $d_o_id = (int)$_POST['d_o_id'];

    $sql_check_active_order = "SELECT * FROM delivery_orders WHERE d_o_id = ? AND delivery_status IN ('assigned', 'pending')";
    $active_order = selectt($sql_check_active_order, [$d_id], 'i');
    if (mysqli_num_rows($active_order) > 0) {
        echo "<script>alert('You already have an active order. Please complete it before accepting a new one.');</script>";
    } else {
        $sql_insert_assigned = "INSERT INTO delivery_assigned (d_o_id, d_id) VALUES (?, ?)";
        selectt($sql_insert_assigned, [$d_o_id, $d_id], 'ii');

        $sql_update_status = "UPDATE delivery_orders SET delivery_status = 'assigned' WHERE d_o_id = ?";
        selectt($sql_update_status, [$d_o_id], 'i');

        echo "<script>alert('Order accepted successfully');</script>";
    }
}

if (isset($_POST['validate_otp'])) {
    $d_o_id = (int)$_POST['d_o_id'];
    $entered_otp = $_POST['otp'];

    $sql_fetch_otp = "
        SELECT o.otp_delivery 
        FROM orders o
        JOIN delivery_orders do ON o.order_id = do.order_id
        WHERE do.d_o_id = ?";
    $otp_result = selectt($sql_fetch_otp, [$d_o_id], 'i');

    if ($otp_row = mysqli_fetch_assoc($otp_result)) {
        $correct_otp = $otp_row['otp_delivery'];

        if ($entered_otp === $correct_otp) {
            $sql_complete_delivery = "UPDATE delivery_orders SET delivery_status = 'delivered' WHERE d_o_id = ?";
            selectt($sql_complete_delivery, [$d_o_id], 'i');

            echo "<script>alert('Delivery completed successfully!');</script>";
        } else {
            echo "<script>alert('Incorrect OTP. Please try again.');</script>";
        }
    }
}

function fetch_order_items($order_id, $con)
{
    $sql_order_items = "
        SELECT od.item_id, od.qty, od.sub_total, i.item_name
        FROM order_details od
        JOIN item i ON od.item_id = i.item_id
        WHERE od.order_id = ?";
    $stmt = $con->prepare($sql_order_items);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

$sql_fetch_assigned_orders = "
    SELECT DISTINCT do.d_o_id, do.order_id, do.c_address, do.res_address, do.delivery_status, do.assigned_date 
    FROM delivery_orders do
    JOIN orders o ON o.order_id = do.order_id
    LEFT JOIN delivery_assigned da ON da.d_o_id = do.d_o_id
    WHERE o.order_status = 'confirmed' 
      AND (da.d_id = ? OR da.d_id IS NULL) 
      AND (do.delivery_status = 'pending' OR do.delivery_status = 'assigned')
";
$assigned_orders = selectt($sql_fetch_assigned_orders, [$d_id], 'i');

$sql_check_active_order = "
    SELECT * FROM delivery_assigned 
    WHERE d_id = ? 
    AND d_o_id IN (SELECT d_o_id FROM delivery_orders WHERE delivery_status IN ('assigned', 'pending'))
";
$active_order_check = selectt($sql_check_active_order, [$d_id], 'i');
$has_active_order = mysqli_num_rows($active_order_check) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Orders</title>
    <link rel="stylesheet" href="css/delivery.css">
    <link rel="stylesheet" href="css/font.css">
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
</head>
<body>
    <?php include 'inc-del/d-header.php'; ?>

    <div class="container mt-4">
        <h2>Assigned Orders</h2>

        <?php if (!empty($assigned_orders)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Address</th>
                        <th>Restaurant Address</th>
                        <th>View Items</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assigned_orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['c_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['res_address']); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#itemModal<?php echo $order['order_id']; ?>">View</button>
                            </td>
                            <td><?php echo ucfirst($order['delivery_status']); ?></td>
                            <td>
                                <?php if ($order['delivery_status'] == 'pending' && !$has_active_order): ?>
                                    <form method="POST">
                                        <input type="hidden" name="d_o_id" value="<?php echo $order['d_o_id']; ?>">
                                        <button type="submit" name="accept_order" class="btn btn-success">Accept</button>
                                    </form>
                                <?php elseif ($order['delivery_status'] == 'assigned'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="d_o_id" value="<?php echo $order['d_o_id']; ?>">
                                        <input type="text" name="otp" placeholder="Enter OTP" required>
                                        <button type="submit" name="validate_otp" class="btn btn-primary">Submit OTP</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo ucfirst($order['delivery_status']); ?></span>
                                <?php endif; ?>
                            </td>
                            
                        </tr>

                        <!-- Modal for Item Details -->
                        <div class="modal fade" id="itemModal<?php echo $order['order_id']; ?>" tabindex="-1" aria-labelledby="itemModalLabel<?php echo $order['order_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="itemModalLabel<?php echo $order['order_id']; ?>">Item Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $items = fetch_order_items($order['order_id'], $con);
                                        if ($items->num_rows > 0):
                                            while ($item = $items->fetch_assoc()): ?>
                                                <p><strong>Item Name:</strong> <?php echo htmlspecialchars($item['item_name']); ?></p>
                                                <p><strong>Quantity:</strong> <?php echo $item['qty']; ?></p>
                                                <p><strong>Subtotal:</strong> <?php echo $item['sub_total']; ?> rs</p>
                                                <hr>
                                            <?php endwhile; 
                                        else: ?>
                                            <p>No items found for this order.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders assigned or pending.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
