<?php
include 'link.php'; // Database connection

// Get the order_id from the request
$order_id = $_GET['order_id'];

// Query to fetch items for this specific order
$query = "SELECT i.item_name, od.qty, od.sub_total
          FROM order_details od
          JOIN item i ON od.item_id = i.item_id
          WHERE od.order_id = $order_id";

$result = mysqli_query($con, $query);

// Output item details
if (mysqli_num_rows($result) > 0) {
    echo "<table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".$row['item_name']."</td>
                <td>".$row['qty']."</td>
                <td>".$row['sub_total']."</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No items found for this order.";
}
?>
