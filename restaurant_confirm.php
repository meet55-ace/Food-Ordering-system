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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmed Orders</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<link rel="stylesheet" href="css/restuarant.css">
<link rel="stylesheet" href="css/font.css">

<body class="bg-light">
    <?php include 'inc-res/r-header.php'; ?>
    <div class="container" id="table-container">
        <div class="row">
            <div class="col">
                <h3 class="text-center my-4">Confirmed Orders</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Order No.</th>
                            <th class="text-center">Token No. (OTP)</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Item details</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch confirmed orders with customer name by joining orders and c_register tables
                        $confirmed_orders_query = "SELECT orders.order_id, orders.otp_delivery, orders.total_amt, orders.order_status, c_register.c_name
                                                    FROM orders
                                                    JOIN c_register ON orders.c_id = c_register.c_id
                                                    WHERE orders.r_o_id = ? AND orders.order_status = 'confirmed'
                                                    ORDER BY orders.order_id DESC";
                        $stmt = $con->prepare($confirmed_orders_query);
                        $stmt->bind_param('i', $restaurant_id); // Bind the restaurant ID from the session
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($order = $result->fetch_assoc()) {
                            $order_id = $order['order_id'];
                            $order_status = $order['order_status'];
                            $customer_name = $order['c_name'];  // Retrieve customer name from the join
                            echo "<tr>";
                            echo "<td class='text-center'>{$order['order_id']}</td>";
                            echo "<td class='text-center'>{$order['otp_delivery']}</td>";  // Displaying token_no as OTP
                            echo "<td class='text-center'>{$customer_name}</td>";    // Displaying customer name
                            echo "<td class='text-center'>{$order['total_amt']}.rs</td>";
                            echo "<td class='text-center'>
                                    <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#orderModal{$order_id}'>View</button>
                                  </td>";
                            echo "<td class='text-center'>
                                    <span class='badge badge-success'>Confirmed</span>
                                  </td>";
                            echo "</tr>";

                            // Order Details Modal
                            echo "
                            <div class='modal fade' id='orderModal{$order_id}' tabindex='-1' role='dialog' aria-labelledby='orderModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='orderModalLabel'>Order Details for Order #{$order_id}</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                        ";

                                        // Fetch order items for this specific order
                                        $order_details_query = "SELECT order_details.item_id, order_details.qty, order_details.sub_total, item.item_name
                                        FROM order_details
                                        JOIN item ON order_details.item_id = item.item_id
                                        WHERE order_details.order_id = ?";
                                        $order_stmt = $con->prepare($order_details_query);
                                        $order_stmt->bind_param('i', $order_id);
                                        $order_stmt->execute();
                                        $order_details = $order_stmt->get_result();

                                        // Dynamically display each item in the order
                                        while ($item = $order_details->fetch_assoc()) {
                                            echo "<p><strong>Item:</strong> " . htmlspecialchars($item['item_name']) . "</p>";
                                            echo "<p><strong>Quantity:</strong> " . $item['qty'] . "</p>";
                                            echo "<hr style='border: 1px solid black;'>";
                                        }

                                        echo "
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <?php include 'inc-res/r-footer.php'; ?>
    </footer>
</body>

</html>
