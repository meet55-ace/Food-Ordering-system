<?php
// session_start();
include 'inc-res/link.php';

// Redirect if not logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: res-lo.php');
    exit;
}

$restaurant_id = $_SESSION['r_o_id'];

// Fetch restaurant open/close status
$query = "SELECT is_open FROM r_o_details WHERE r_o_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$is_open = $row['is_open'];

// Toggle restaurant status
if (isset($_POST['toggle_status'])) {
    $new_status = $is_open ? 0 : 1;
    $update_query = "UPDATE r_o_details SET is_open = ? WHERE r_o_id = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param('ii', $new_status, $restaurant_id);
    $update_stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Confirm Order
if (isset($_POST['confirm_order'])) {
    $order_id_to_confirm = $_POST['confirm_order'];
    $update_order_query = "UPDATE orders SET order_status = 'confirmed' WHERE order_id = ?";
    $update_stmt = $con->prepare($update_order_query);
    $update_stmt->bind_param('i', $order_id_to_confirm);
    $update_stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/restuarant.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include 'inc-res/r-header.php'; ?>

    <div class="container mt-4">
        <h2 class="text-center">Restaurant Dashboard</h2>

        <!-- Toggle Open/Closed Button -->
        <form method="POST" id="toggleForm" class="text-center my-3">
    <label class="switch">
        <input type="checkbox" id="restaurantToggle" <?php echo $is_open ? 'checked' : ''; ?>>
        <span class="slider round"></span>
    </label>
    <p id="statusText" class="mt-2 font-weight-bold">
        <?php echo $is_open ? '🟢 Open' : '🔴 Closed'; ?>
    </p>
</form>


        <div class="row">
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Order No.</th>
                            <th class="text-center">Token No. (OTP)</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Item details</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Order Requests</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orders_query = "SELECT orders.order_id, orders.otp_delivery, orders.total_amt, orders.order_status, c_register.c_name
                                         FROM orders
                                         JOIN c_register ON orders.c_id = c_register.c_id
                                         WHERE orders.r_o_id = ? AND orders.order_status = 'pending'  
                                         ORDER BY orders.order_id DESC";
                        $stmt = $con->prepare($orders_query);
                        $stmt->bind_param('i', $restaurant_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($order = $result->fetch_assoc()) {
                            $order_id = $order['order_id'];
                            $order_status = $order['order_status'];
                            $customer_name = $order['c_name'];
                            echo "<tr>";
                            echo "<td class='text-center'>{$order['order_id']}</td>";
                            echo "<td class='text-center'>{$order['otp_delivery']}</td>";
                            echo "<td class='text-center'>{$customer_name}</td>";
                            echo "<td class='text-center'>{$order['total_amt']}.rs</td>";
                            echo "<td class='text-center'>
                                    <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#orderModal{$order_id}'>View</button>
                                  </td>";

                            if ($order_status === 'pending') {
                                echo "<td class='text-center'>{$order_status}</td>";
                                echo "<td class='text-center'>
                                        <form method='POST'>
                                            <button type='submit' name='confirm_order' value='{$order_id}' class='btn btn-success btn-sm'>Confirm</button>
                                        </form>
                                      </td>";
                            } else {
                                echo "<td class='text-center'>{$order_status}</td>";
                                echo "<td class='text-center'><span class='badge badge-warning'>Confirmed</span></td>";
                            }

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
                                        <div class='modal-body'>";

                            // Fetch order items for this specific order
                            $order_details_query = "SELECT order_details.item_id, order_details.qty, order_details.sub_total, item.item_name
                                                    FROM order_details
                                                    JOIN item ON order_details.item_id = item.item_id
                                                    WHERE order_details.order_id = ?";
                            $order_stmt = $con->prepare($order_details_query);
                            $order_stmt->bind_param('i', $order_id);
                            $order_stmt->execute();
                            $order_details = $order_stmt->get_result();

                            while ($item = $order_details->fetch_assoc()) {
                                echo "<p><strong>Item:</strong> " . htmlspecialchars($item['item_name']) . "</p>";
                                echo "<p><strong>Quantity:</strong> " . $item['qty'] . "</p>";
                                echo "<hr style='border: 1px solid black;'>";
                            }

                            echo "</div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#restaurantToggle").change(function() {
        var is_open = $(this).is(":checked") ? 1 : 0;
        
        $.ajax({
            url: "update_restaurant_status.php", // Backend PHP file to update DB
            type: "POST",
            data: { is_open: is_open },
            success: function(response) {
                $("#statusText").html(is_open ? "🟢 Open" : "🔴 Closed");
            }
        });
    });
});


// $(document).ready(function() {
//     $("#logoutBtn").click(function(e) {
//         e.preventDefault(); // Prevent logout action

//         $.ajax({
//             url: "check_restaurant_status.php",
//             type: "GET",
//             success: function(response) {
//                 if (response.trim() === "open") {
//                     alert("⚠️ Please close your restaurant before logging out!");
//                 } else {
//                     window.location.href = "logoutr.php"; // Allow logout
//                 }
//             }
//         });
//     });
// });
</script>

</body>

</html>
