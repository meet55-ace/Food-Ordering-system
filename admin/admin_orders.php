<?php
include 'link.php'; 

$date_filter = isset($_GET['order_date']) ? $_GET['order_date'] : '';

$query = "SELECT o.order_id, 
                 c.c_name AS customer_name, 
                 r.res_name AS restaurant_name, 
                 GROUP_CONCAT(DISTINCT i.item_name ORDER BY i.item_name SEPARATOR ', ') AS ordered_items,
                 o.total_amt, 
                 o.order_date, 
                 db.d_name AS delivery_boy
          FROM orders o
          JOIN c_register c ON o.c_id = c.c_id
          JOIN r_o_details r ON o.r_o_id = r.r_o_id
          JOIN order_details od ON o.order_id = od.order_id
          JOIN item i ON od.item_id = i.item_id
          LEFT JOIN delivery_orders do ON do.order_id = o.order_id
          LEFT JOIN delivery_assigned da ON da.d_o_id = do.d_o_id  
          LEFT JOIN d_details db ON da.d_id = db.d_id ";

$filters = [];

if ($date_filter) {
    $filters[] = "DATE(o.order_date) = '$date_filter'"; 
}

$filters[] = "do.delivery_status = 'delivered'";  

if (count($filters) > 0) {
    $query .= " WHERE " . implode(" AND ", $filters);
}

$query .= " GROUP BY o.order_id
            ORDER BY o.order_date DESC";





$result = mysqli_query($con, $query);


if ($date_filter && mysqli_num_rows($result) == 0) {
    echo '<script>
            alert("No orders found for the selected date.");
            window.location.href = "' . $_SERVER['PHP_SELF'] . '"; 
          </script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <style>
       
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
        }


        th, td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #3B1E54; 
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }

     
        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Highlighted Row */
        tr.highlight {
            background-color: #ffeb3b; /* Yellow highlight */
            font-weight: bold;
        }

        td {
            font-size: 14px;
            color: #333;
        }

        @media (max-width: 768px) {
            th, td {
                font-size: 14px;
                padding: 10px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="nav.css">
</head>
<body>
    <?php include 'a-header.php'; ?>

    <div class="container mt-5">
        <!-- Filter Form with only date -->
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="order_date" class="form-label">Select Date</label>
                    <input type="date" name="order_date" id="order_date" class="form-control" value="<?php echo isset($_GET['order_date']) ? $_GET['order_date'] : ''; ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <h2>Order Details</h2>
        <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Restaurant Name</th>
                    <th>Delivery Boy</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>View Items</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= $row['customer_name'] ?></td>
                    <td><?= $row['restaurant_name'] ?></td>
                    <td><?= $row['delivery_boy'] ?></td>
                    <td><?= $row['total_amt'] ?></td>
                    <td><?= $row['order_date'] ?></td>
                    <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="showItems(<?= $row['order_id'] ?>)">View Items</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <!-- Modal for viewing items -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Ordered Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalItemsContent">
                
                </div>
            </div>
        </div>
    </div>

    <script>
        function showItems(order_id) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_items.php?order_id=" + order_id, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById('modalItemsContent').innerHTML = xhr.responseText;
                }
            }
            xhr.send();
        }
    </script>
</body>
</html>
