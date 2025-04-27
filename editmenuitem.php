<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <?php include('inc-res/link.php') ?>
    <link rel="stylesheet" href="css/editmenu.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include('inc-res/r-header.php') ?>

    <?php
    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];

        // Fetch item details by item_id
        $sql = "SELECT item_id, item_name, category_id, item_price, item_description, item_image FROM item WHERE item_id = ?";
        $values = [$item_id];
        $datatype = "i";
        $result = selectt($sql, $values, $datatype);

        if ($result) {
            $item = mysqli_fetch_assoc($result);
        } else {
            echo "Item not found.";
            exit;
        }
    } else {
        echo "Invalid item ID.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemName = $_POST['itemname'];
        $category_id = $_POST['category'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $imagePath = "Imgs/" . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);
        } else {
            $imagePath = $item['item_image'];
        }

        // Update item details in the database
        $sql_update = "UPDATE item SET item_name = ?, category_id = ?, item_price = ?, item_description = ?, item_image = ? WHERE item_id = ?";
        $values_update = [$itemName, $category_id, $price, $description, $imagePath, $item_id];
        $datatype_update = "sdsssi";

        $result_update = update($sql_update, $values_update, $datatype_update);

        if ($result_update > 0) {
            echo "<script>alert('Item updated successfully.'); window.location.href='menu.php';</script>";
        } else {
            echo "<script>alert('No changes were made or error occurred.');</script>";
        }
        exit();
    }
    if (isset($_GET['delete']) && $_GET['delete'] === 'true' && isset($_GET['id'])) {
        $item_id = $_GET['id'];

        // Delete the item from the database
        $sql_delete = "DELETE FROM item WHERE item_id = ?";
        $values_delete = [$item_id];
        $datatype_delete = "i";

        $result_delete = update($sql_delete, $values_delete, $datatype_delete);

        if ($result_delete > 0) {
            // Redirect to the menu page or any other desired page
            echo "<script>alert('Item deleted successfully.'); window.location.href='menu.php';</script>";
        } else {
            echo "<script>alert('Error occurred while deleting the item.'); window.location.href='editmenu.php';</script>";
        }
        exit();
    }
    ?>

    <div class="container custom-container mt-5">
        <h1 class="edit-item-header">Edit Item</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Item Name -->
                <div class="col-md-6 mb-3">
                    <label for="itemName" class="form-label">Item Name</label>
                    <input type="text" class="form-control" name="itemname" id="itemName" placeholder="Item Name"
                        required value="<?= htmlspecialchars($item['item_name']) ?>">
                </div>

                <!-- Category -->
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" name="category" id="category" required>
                        <?php
                        // Fetch categories specific to the logged-in restaurant
                        $sql_categories = "SELECT category_id, category_name FROM category WHERE r_o_id = ?";
                        $stmt = $con->prepare($sql_categories);
                        $stmt->bind_param("i", $_SESSION['r_o_id']); // Use r_o_id from session
                        $stmt->execute();
                        $result_categories = $stmt->get_result();

                        if ($result_categories) {
                            while ($category = $result_categories->fetch_assoc()) {
                                $selected = ($category['category_id'] == $item['category_id']) ? 'selected' : '';
                                echo "<option value='" . $category['category_id'] . "' $selected>" . htmlspecialchars($category['category_name']) . "</option>";
                            }
                        } else {
                            echo "<option>No categories found</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Price -->
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" class="form-control" name="price" id="price" placeholder="Price" required
                        value="<?= htmlspecialchars($item['item_price']) ?>">
                </div>

                <!-- Description -->
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" placeholder="Item Description"
                        required><?= htmlspecialchars($item['item_description']) ?></textarea>
                </div>
            </div>

            <div class="row">
                <!-- Image -->
                <div class="col-md-6 mb-3">
                    <label for="imageURL" class="form-label">Image</label>
                    <input type="file" class="form-control" name="image" id="imageURL" accept="image/*">
                    <small>Current Image: <img src="<?= $item['item_image'] ?>" alt="Current Image"
                            style="width: 100px; height: auto;"></small>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-dark">Update Item</button>
            <a href="editmenuitem.php?id=<?= $item_id ?>&delete=true" class="btn btn-outline-danger">Delete Item</a>
            <a href="menu.php" class="btn btn-outline-danger">Cancel</a>
        </form>
    </div>

    <footer>
        <?php include 'inc-res/r-footer.php';?>
    </footer>
</body>

</html>