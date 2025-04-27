<?php
include 'link.php'; 

// Get the selected month from the form (default to current month if not selected)
$month = isset($_POST['month']) ? $_POST['month'] : date('m');  // Default to current month

// SQL Queries with month filter and delivery status filter (delivered)
$query_orders = "SELECT r.res_name AS restaurant_name, COUNT(o.order_id) AS total_orders
                 FROM orders o
                 JOIN r_o_details r ON o.r_o_id = r.r_o_id
                 LEFT JOIN delivery_orders do ON do.order_id = o.order_id
                 WHERE MONTH(o.order_date) = $month AND do.delivery_status = 'delivered'
                 GROUP BY r.res_name
                 ORDER BY total_orders DESC";

$query_revenue = "SELECT r.res_name AS restaurant_name, SUM(o.total_amt) AS total_revenue
                  FROM orders o
                  JOIN r_o_details r ON o.r_o_id = r.r_o_id
                  LEFT JOIN delivery_orders do ON do.order_id = o.order_id
                  WHERE MONTH(o.order_date) = $month AND do.delivery_status = 'delivered'
                  GROUP BY r.res_name
                  ORDER BY total_revenue DESC";

$query_delivery_boys = "SELECT db.d_name AS delivery_boy, COUNT(da.d_o_id) AS total_deliveries
                        FROM delivery_assigned da
                        JOIN d_details db ON da.d_id = db.d_id
                        JOIN delivery_orders do ON da.d_o_id = do.d_o_id
                        WHERE MONTH(do.assigned_date) = $month AND do.delivery_status = 'delivered'
                        GROUP BY db.d_name
                        ORDER BY total_deliveries DESC";

$result_orders = mysqli_query($con, $query_orders);
$result_revenue = mysqli_query($con, $query_revenue);
$result_delivery_boys = mysqli_query($con, $query_delivery_boys);

$restaurant_names = [];
$total_orders = [];
$restaurant_names_revenue = [];
$revenues = [];
$delivery_boy_names = [];
$total_deliveries = [];

$total_overall_orders = 0;  // Variable to calculate total orders across all restaurants

while ($row = mysqli_fetch_assoc($result_orders)) {
    $restaurant_names[] = $row['restaurant_name'];
    $total_orders[] = $row['total_orders'];
    $total_overall_orders += $row['total_orders'];  // Add to total orders
}

while ($row = mysqli_fetch_assoc($result_revenue)) {
    $restaurant_names_revenue[] = $row['restaurant_name'];
    $revenues[] = $row['total_revenue'];
}

while ($row = mysqli_fetch_assoc($result_delivery_boys)) {
    $delivery_boy_names[] = $row['delivery_boy'];
    $total_deliveries[] = $row['total_deliveries'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="nav.css">
    <style>
        .chart-container {
            background-color: #f8f9fa; 
            border: 1px solid #ccc;  
            border-radius: 8px;  
            padding: 20px;  
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            margin-bottom: 20px;  
        }
        #ordersChart, #deliveryBoyChart {
            width: 100%;
            height: 400px; 
        }
        #revenueChart {
            width: 50%; 
            height: 250px; 
            margin: 0 auto; 
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .total-revenue {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .total-orders {
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'a-header.php'?>

    <div class="container mt-5">
        <!-- Month Filter Form -->
        <form method="POST" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <option value="1" <?php echo $month == '1' ? 'selected' : ''; ?>>January</option>
                        <option value="2" <?php echo $month == '2' ? 'selected' : ''; ?>>February</option>
                        <option value="3" <?php echo $month == '3' ? 'selected' : ''; ?>>March</option>
                        <option value="4" <?php echo $month == '4' ? 'selected' : ''; ?>>April</option>
                        <option value="5" <?php echo $month == '5' ? 'selected' : ''; ?>>May</option>
                        <option value="6" <?php echo $month == '6' ? 'selected' : ''; ?>>June</option>
                        <option value="7" <?php echo $month == '7' ? 'selected' : ''; ?>>July</option>
                        <option value="8" <?php echo $month == '8' ? 'selected' : ''; ?>>August</option>
                        <option value="9" <?php echo $month == '9' ? 'selected' : ''; ?>>September</option>
                        <option value="10" <?php echo $month == '10' ? 'selected' : ''; ?>>October</option>
                        <option value="11" <?php echo $month == '11' ? 'selected' : ''; ?>>November</option>
                        <option value="12" <?php echo $month == '12' ? 'selected' : ''; ?>>December</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="total-orders">
            <h3>Total Orders: <?php echo number_format($total_overall_orders); ?></h3>
        </div>

        <div class="row">
            <div class="col-md-5 mx-auto">
                <div class="chart-container">
                    <h2>Restaurant Orders Analysis</h2>
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <div class="col-md-5 mx-auto">
                <div class="chart-container">
                    <h2>Delivery Boy Analysis</h2>
                    <canvas id="deliveryBoyChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="total-revenue">
                    <h3>Total Revenue: ₹<?php echo number_format(array_sum($revenues), 2); ?></h3>
                </div>
                <div class="chart-container">
                    <h2>Restaurant Revenue Analysis</h2>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        var ctx1 = document.getElementById('ordersChart').getContext('2d');
        var chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($restaurant_names); ?>,
                datasets: [{
                    label: 'Total Orders',
                    data: <?php echo json_encode($total_orders); ?>,
                    backgroundColor: '#FF8C42',  
                    borderColor: '#e36247', 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        stepSize: 5,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });

        var ctx2 = document.getElementById('deliveryBoyChart').getContext('2d');
        var chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($delivery_boy_names); ?>,
                datasets: [{
                    label: 'Total Deliveries',
                    data: <?php echo json_encode($total_deliveries); ?>,
                    backgroundColor: '#42D38A', 
                    borderColor: '#2c9a5a',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        stepSize: 5,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });

        var ctx3 = document.getElementById('revenueChart').getContext('2d');
        var chart3 = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($restaurant_names_revenue); ?>,
                datasets: [{
                    data: <?php echo json_encode($revenues); ?>,
                    backgroundColor: ['rgba(255, 99, 132, 1)', 'rgb(136, 205, 250)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 
                        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgb(87, 4, 22)', 'rgba(54, 162, 235, 1)', 
                        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgb(94, 24, 236)', 'rgba(255, 159, 64, 1)'],  
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                var total = <?php echo array_sum($revenues); ?>;
                                var percentage = ((tooltipItem.raw / total) * 100).toFixed(2);
                                return tooltipItem.label + ': ₹' + tooltipItem.raw + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
<?php include 'a-footer.php'?>
</body>
</html>
