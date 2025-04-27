<?php
include 'link.php'; // Database connection

// Get restaurant ID from URL
$restaurant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($restaurant_id == 0) {
    die("Invalid restaurant ID.");
}

// Fetch restaurant details
$sql_restaurant = "SELECT res_name FROM r_o_details WHERE r_o_id = ?";
$stmt_restaurant = $con->prepare($sql_restaurant);
$stmt_restaurant->bind_param("i", $restaurant_id);
$stmt_restaurant->execute();
$result_restaurant = $stmt_restaurant->get_result();
$restaurant = $result_restaurant->fetch_assoc();

if (!$restaurant) {
    die("Restaurant not found.");
}

// Fetch categories for this restaurant
$sql_categories = "SELECT DISTINCT category.category_id, category.category_name 
                   FROM category 
                   JOIN item ON category.category_id = item.category_id 
                   WHERE category.r_o_id = ?";
$stmt_categories = $con->prepare($sql_categories);
$stmt_categories->bind_param("i", $restaurant_id);
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items - <?= htmlspecialchars($restaurant['res_name']); ?></title>
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="view_items.css">
</head>
<body>
<?php include 'a-header.php'; ?>

<div class="container mt-5">
<h2 class="restaurant-name"><?= htmlspecialchars($restaurant['res_name']); ?></h2>


    <?php if ($result_categories->num_rows > 0) { ?>
        <?php while ($category = $result_categories->fetch_assoc()) { ?>
            <h3 class="category-title"><?= htmlspecialchars($category['category_name']) ?></h3>
            <div class="items-grid">
                <?php
                // Fetch items for this category
                $sql_items = "SELECT item_name, item_price, item_image 
                              FROM item 
                              WHERE category_id = ?";
                $stmt_items = $con->prepare($sql_items);
                $stmt_items->bind_param("i", $category['category_id']);
                $stmt_items->execute();
                $result_items = $stmt_items->get_result();

                if ($result_items->num_rows > 0) {
                    while ($row = $result_items->fetch_assoc()) { ?>
                        <div class="item-card">
                        <img src="../<?= htmlspecialchars($row['item_image']) ?>" alt="<?= htmlspecialchars($row['item_name']) ?>">
                            <h4><?= htmlspecialchars($row['item_name']) ?></h4>
                            <p class="price">₹<?= number_format($row['item_price'], 2) ?></p>
                        </div>
                    <?php }
                } else { ?>
                    <p class="no-items">No items available in this category.</p>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p class="no-items">No items available for this restaurant.</p>
    <?php } ?>
</div>

</body>
</html>
