<?php
include 'inc-cus/link.php';

$search_term = isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : '';

// Fetch most-selling items when no search is performed
$sql_most_selling = "SELECT 
                        item.item_id, 
                        item.item_name, 
                        item.item_price, 
                        item.item_description, 
                        item.item_image, 
                        r_o_details.res_name, 
                        category.category_name,
                        r_o_details.is_open,
                        r_o_details.status,
                        COUNT(order_details.order_id) AS total_orders
                    FROM item
                    JOIN category ON item.category_id = category.category_id
                    JOIN r_o_details ON category.r_o_id = r_o_details.r_o_id
                    JOIN order_details ON item.item_id = order_details.item_id
                    JOIN orders ON order_details.order_id = orders.order_id
                    WHERE r_o_details.status = 1
                    GROUP BY item.item_id
                    ORDER BY total_orders DESC
                    LIMIT 8";

$most_selling_items = selecttt($sql_most_selling, [], '');

$sql_restaurants = "SELECT DISTINCT res_name, profile, approx, res_address, is_open, status 
                    FROM r_o_details 
                    WHERE status = 1 AND res_name LIKE ?";
$values_restaurants = ["%$search_term%"];
$datatype_restaurants = "s"; 
$restaurants = selecttt($sql_restaurants, $values_restaurants, $datatype_restaurants);

$sql_items = "SELECT 
                    item.item_id, 
                    item.item_name, 
                    item.item_price, 
                    item.item_description, 
                    item.item_image, 
                    r_o_details.res_name, 
                    category.category_name,
                    r_o_details.is_open,
                    r_o_details.status
                FROM item 
                JOIN category ON item.category_id = category.category_id
                JOIN r_o_details ON category.r_o_id = r_o_details.r_o_id
                WHERE (category.category_name LIKE ? OR item.item_name LIKE ?) 
                AND r_o_details.status = 1";
$values_items = ["%$search_term%", "%$search_term%"];
$datatype_items = "ss"; 
$items = selecttt($sql_items, $values_items, $datatype_items);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include 'inc-cus/c-header.php'; ?>

    <div class="container">
        <div class="search-bar">
            <form action="search.php" method="get" class="d-flex">
                <input type="text" name="search_term" class="form-control" value="<?php echo $search_term; ?>"
                    placeholder="Search for restaurants, categories, or food items...">
                <button type="submit" class="btn">Search</button>
            </form>
        </div>
    </div>

    <div class="container mt-4">
        <?php if (!$search_term && count($most_selling_items) > 0): ?>
        <h3 class="mb-4">Most Selling Items</h3>
        <div class="row">
            <?php foreach ($most_selling_items as $row): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card modern-card">
                    <img src="<?php echo htmlspecialchars($row['item_image']); ?>" class="card-img-top"
                        alt="Food Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                        <h6 class="card-sub-title">₹<?php echo htmlspecialchars($row['item_price']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($row['item_description']); ?></p>
                        <div class="d-flex flex-column">
                            <a href="displayresitems.php?res_name=<?php echo urlencode($row['res_name']); ?>"
                                class="btn btn-outline-dark mb-2">View in
                                <?php echo htmlspecialchars($row['res_name']); ?></a>

                            <?php if ($row['status'] == 1 && $row['is_open'] == 1): ?>
                            <a href="add-to-cart.php?item_id=<?php echo htmlspecialchars($row['item_id']); ?>"
                                class="btn btn-outline-dark">Add to Cart</a>
                            <?php else: ?>
                            <button class="btn btn-danger" disabled>Restaurant Closed</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Search Results -->
        <?php if ($search_term && count($restaurants) > 0): ?>
        <h3 class="mb-4">Restaurants</h3>
        <div class="row">
            <?php foreach ($restaurants as $row): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card modern-card">
                    <img src="ajax/imgs/card(restaurant)/<?php echo htmlspecialchars($row['profile']); ?>"
                        class="card-img-top img-fluid" alt="Restaurant Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['res_name']); ?></h5>
                        <h6 class="card-sub-title">~ <?php echo htmlspecialchars($row['approx']); ?></h6>
                        <p class="card-text"><i class="fa-solid fa-location-dot"></i>&nbsp;<?php echo htmlspecialchars($row['res_address']); ?>
                        </p>

                        <?php if ($row['is_open'] == 1): ?>
                        <a href="displayresitems.php?res_name=<?php echo urlencode($row['res_name']); ?>"
                            class="btn btn-outline-dark">Visit</a>
                        <?php else: ?>
                        <button class="btn btn-danger" disabled>Restaurant Closed</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($search_term && count($items) > 0): ?>
        <h3 class="mb-4">Food Items</h3>
        <div class="row">
            <?php foreach ($items as $row): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card modern-card">
                    <img src="<?php echo htmlspecialchars($row['item_image']); ?>" class="card-img-top"
                        alt="Food Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                        <h6 class="card-sub-title">₹<?php echo htmlspecialchars($row['item_price']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($row['item_description']); ?></p>
                        <div class="d-flex flex-column">
                            <a href="displayresitems.php?res_name=<?php echo urlencode($row['res_name']); ?>"
                                class="btn btn-outline-dark mb-2 btn-sm">View in
                                <?php echo htmlspecialchars($row['res_name']); ?></a>

                            <?php if ($row['is_open'] == 1): ?>
                            <a href="add-to-cart.php?item_id=<?php echo htmlspecialchars($row['item_id']); ?>"
                                class="btn btn-outline-dark btn-sm">Add to Cart</a>
                            <?php else: ?>
                            <button class="btn btn-danger btn-sm" disabled>Restaurant Closed</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php include 'inc-cus/c-footer.php';?>
</body>
</html>
