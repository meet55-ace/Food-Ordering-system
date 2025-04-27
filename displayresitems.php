<?php
include 'inc-cus/link.php'; 
include 'inc-cus/c-header.php'; 

if (isset($_GET['res_name'])) {
    $res_name = urldecode($_GET['res_name']);
} else {
    die('Restaurant name is missing.');
}

// Fetch restaurant details
$sql_res = "SELECT r_o_id, is_open FROM r_o_details WHERE res_name = ?";
$res_result = selectt($sql_res, [$res_name], 's');

if (mysqli_num_rows($res_result) > 0) {
    $row = mysqli_fetch_assoc($res_result);
    $r_o_id = $row['r_o_id'];
    $is_open = $row['is_open']; // Fetch the open/closed status
} else {
    die('Restaurant not found.');
}

// Fetch categories
$sql_categories = "SELECT category_name FROM category WHERE r_o_id = ?";
$category_result = selectt($sql_categories, [$r_o_id], 'i'); 

// Fetch items
$sql_items = "SELECT i.item_id, i.item_name, i.item_price, i.item_description, i.item_image, c.category_name
              FROM r_o_details r_o
              JOIN category c ON r_o.r_o_id = c.r_o_id
              JOIN item i ON c.category_id = i.category_id
              WHERE r_o.r_o_id = ?
              ORDER BY c.category_name, i.item_name";

$item_result = selectt($sql_items, [$r_o_id], 'i'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($res_name); ?> - Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/displayitems.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">

    <div class="container">
        <h2 class="text-center my-4 text-primary"><?php echo htmlspecialchars($res_name); ?> - Menu</h2>

        <!-- Show restaurant status -->
        <div class="alert <?php echo ($is_open) ? 'alert-success' : 'alert-danger'; ?> text-center">
            <?php echo ($is_open) ? "Restaurant is Open ✅" : "Restaurant is Closed ❌"; ?>
        </div>

        <div class="text-center mb-4">
            <button class="btn btn-outline-dark category-btn" data-category="all">All Items</button>
            <?php
            if ($category_result && mysqli_num_rows($category_result) > 0) {
                while ($category_row = mysqli_fetch_assoc($category_result)) {
                    $category_name = $category_row['category_name'];
                    echo '<button class="btn btn-outline-dark category-btn" data-category="' . htmlspecialchars($category_name) . '">' . htmlspecialchars($category_name) . '</button>';
                }
            }
            ?>
        </div>

        <?php if ($item_result && mysqli_num_rows($item_result) > 0): ?>
            <div class="row" id="items-container">
                <?php while ($row = mysqli_fetch_assoc($item_result)): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 item-card" data-category="<?php echo htmlspecialchars($row['category_name']); ?>">
                        <div class="card shadow-sm">
                            <div class="card-img-wrapper">
                                <img src="<?php echo htmlspecialchars($row['item_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['item_description']); ?></p>
                                <p><strong>Price:</strong> ₹<?php echo number_format($row['item_price'], 2); ?></p>
                                
                                <!-- Show "Add to Cart" button only if restaurant is open -->
                                <?php if ($is_open): ?>
                                    <a href="add-to-cart.php?item_id=<?php echo $row['item_id']; ?>" class="btn btn-outline-dark">Add to Cart</a>
                                <?php else: ?>
                                    <button class="btn btn-danger" disabled>Restaurant Closed</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No items found for this restaurant.</p>
        <?php endif; ?>

    </div>

    <?php include 'inc-cus/c-footer.php'; ?>

    <script>
        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function () {
                const category = this.getAttribute('data-category');
                const items = document.querySelectorAll('.item-card');

                items.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>

</body>
</html>
