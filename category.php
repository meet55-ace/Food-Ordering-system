<?php
include 'inc-cus/link.php';

$category_name = isset($_GET['category_name']) ? $_GET['category_name'] : null;

if (empty($category_name)) {
    header("Location: foodziee.php"); 
    exit;
}

$sql_category_ids = "SELECT category_id FROM category WHERE category_name = ?";
$stmt_categories = $con->prepare($sql_category_ids);
$stmt_categories->bind_param('s', $category_name);
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();

$category_ids = [];
while ($row = $result_categories->fetch_assoc()) {
    $category_ids[] = $row['category_id'];
}

if (!empty($category_ids)) {
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));

    $sql_items = "SELECT 
                    item.item_id, 
                    item.item_name, 
                    item.item_price, 
                    item.item_description, 
                    item.item_image, 
                    r_o_details.res_name, 
                    category.category_name,
                    r_o_details.is_open
                FROM item 
                JOIN category ON item.category_id = category.category_id
                JOIN r_o_details ON category.r_o_id = r_o_details.r_o_id
                WHERE category.category_id IN ($placeholders) AND r_o_details.status = 1";

    $stmt_items = $con->prepare($sql_items);
    $types = str_repeat('i', count($category_ids));
    $stmt_items->bind_param($types, ...$category_ids); 
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
} else {
    $result_items = null; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_name); ?></title>
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body class="bg-light">
   
    <?php include 'inc-cus/c-header.php'; ?>

    <div class="container mt-4">
        <?php if ($result_items && $result_items->num_rows > 0): ?>
            <h3 class="mb-4"><?php echo htmlspecialchars($category_name); ?></h3>
            <div class="row">
                <?php while ($row = $result_items->fetch_assoc()): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card modern-card">
                            <img src="<?php echo htmlspecialchars($row['item_image']); ?>" class="card-img-top" alt="Food Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                                <h6 class="card-sub-title">₹<?php echo htmlspecialchars($row['item_price']); ?></h6>
                                <p class="card-text"><?php echo htmlspecialchars($row['item_description']); ?></p>
                                
                                <div class="d-flex flex-column">
                                    <a href="displayresitems.php?res_name=<?php echo urlencode($row['res_name']); ?>" class="btn btn-outline-dark mb-2">
                                        View in <?php echo htmlspecialchars($row['res_name']); ?>
                                    </a>

                                    <?php if ($row['is_open'] == 1): ?>
                                        <a href="add-to-cart.php?item_id=<?php echo htmlspecialchars($row['item_id']); ?>" class="btn btn-sm btn-outline-dark">Add to Cart</a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-danger" disabled>Restaurant Closed</button>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No items found for "<?php echo htmlspecialchars($category_name); ?>".</p>
        <?php endif; ?>
    </div>

    <?php include 'inc-cus/c-footer.php';?>
</body>
</html>
