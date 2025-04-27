<?php

include 'inc-res/link.php';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $r_o_id = $_SESSION['r_o_id']; 
    
    $sql_most_sold_items = "
        SELECT i.item_name, SUM(od.qty) AS total_qty
        FROM order_details od
        JOIN item i ON i.item_id = od.item_id
        WHERE od.order_id IN (SELECT order_id FROM orders WHERE order_status = 'confirmed' AND r_o_id = ?)
        GROUP BY i.item_name
        ORDER BY total_qty DESC"; 

  
    $result_items = selectt($sql_most_sold_items, [$r_o_id], 'i');
    $item_data = [];
    while ($row_item = mysqli_fetch_assoc($result_items)) {
        $item_data[] = ['label' => $row_item['item_name'], 'value' => (int)$row_item['total_qty']];
    }
    

    $sql_monthly_orders = "
        SELECT 
            MONTH(order_date) AS order_month,
            COUNT(order_id) AS total_orders
        FROM orders
        WHERE YEAR(order_date) = ? AND order_status = 'confirmed' AND r_o_id = ?
        GROUP BY MONTH(order_date)
        ORDER BY MONTH(order_date)";
    
    
    $selected_year = isset($_GET['year_filter']) ? (int)$_GET['year_filter'] : date('Y');
    
    
    $result = selectt($sql_monthly_orders, [$selected_year, $r_o_id], 'ii');
    $chart_data = [];
    $total_orders_per_month = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $chart_data[(int)$row['order_month']] = $row['total_orders'];
        $total_orders_per_month[(int)$row['order_month']] = $row['total_orders'];
    }

    
    for ($i = 1; $i <= 12; $i++) {
        if (!isset($chart_data[$i])) {
            $chart_data[$i] = 0;
            $total_orders_per_month[$i] = 0;
        }
    }
    ksort($chart_data);
} else {
    
    echo "You need to log in to view this data.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Orders and Most Sold Items</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/analysis_r.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include 'inc-res/r-header.php'; ?>

    <div class="container mt-4">
        <h2>Monthly Orders Overview</h2>

       
        <form method="GET" class="mb-3">
            <label for="year_filter" class="form-label">Select Year:</label>
            <select name="year_filter" id="year_filter" class="form-select form-select-sm w-25 d-inline-block" onchange="this.form.submit()">
                <?php
                $current_year = date('Y');
                for ($y = $current_year - 5; $y <= $current_year; $y++) {
                    echo "<option value='$y' " . ($y == $selected_year ? 'selected' : '') . ">$y</option>";
                }
                ?>
            </select>
        </form>

        
        <div class="chart-container w-100" style="position: relative; height: 50vh;">
            <canvas id="ordersChart"></canvas>
        </div>
<hr>
        <h2 class="mt-4">Most Sold Items</h2>
       
        <div class="chart-container w-100" style="position: relative; height: 50vh;">
            <canvas id="itemsChart"></canvas>
        </div>

    </div>

    <script>
  
    const chartLabels = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    const chartData = <?php echo json_encode(array_values($chart_data)); ?>;

    const ctx1 = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar', 
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Number of Orders',
                data: chartData,
                backgroundColor: [
                    'rgb(8, 83, 83)',
                    // 'rgba(255, 99, 132, 1)', 'rgb(8, 74, 118)', 'rgba(255, 206, 86, 1)', 'rgb(10, 88, 88)', 
                    // 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 99, 132, 1)', 'rgb(8, 65, 104)', 
                    // 'rgba(255, 206, 86, 1)', 'rgb(8, 83, 83)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                ],
                // borderColor: [
                //     'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 
                //     'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 
                //     'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                // ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true, 
                    max: 20, 
                    ticks: {
                        stepSize: 5, 
                        precision: 0, 
                        color: 'rgba(75, 192, 192, 1)' 
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', 
                        borderColor: 'rgba(0, 0, 0, 0.2)' 
                    }
                }
            }
        }
    });

    
        const itemLabels = <?php echo json_encode(array_column($item_data, 'label')); ?>;
        const itemValues = <?php echo json_encode(array_column($item_data, 'value')); ?>;

        const ctx2 = document.getElementById('itemsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie', 
            data: {
                labels: itemLabels,
                datasets: [{
                    data: itemValues,
                    backgroundColor: ['rgba(255, 99, 132, 1)', 'rgb(136, 205, 250)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 
                        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgb(87, 4, 22)', 'rgba(54, 162, 235, 1)', 
                        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgb(94, 24, 236)', 'rgba(255, 159, 64, 1)'],  // Set custom colors
                    borderColor: [ 'rgba(255, 99, 132, 1)', 'rgb(136, 205, 250)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 
                        'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgb(87, 4, 22)', 'rgba(54, 162, 235, 1)', 
                        'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgb(94, 24, 236)', 'rgba(255, 159, 64, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>

    <?php include 'inc-res/r-footer.php'; ?>
</body>

</html>
