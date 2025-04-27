<?php
include 'inc-res/link.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemname'])) {
    $itemName = trim($_POST['itemname']);
    $category_id = $_POST['category'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $r_o_id = $_SESSION['r_o_id']; 

    $sql_check = "SELECT COUNT(*) FROM r_o_details WHERE r_o_id = ?";
    $stmt_check = $con->prepare($sql_check);
    $stmt_check->bind_param("i", $r_o_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count == 0) {
        echo "<script>alert('Error: r_o_id does not exist in r_o_details.');</script>";
        exit(); // Stop further execution if r_o_id doesn't exist
    }

    // Proceed with item insertion if r_o_id exists
    if (!empty($itemName) && !empty($price) && !empty($description) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imagePath = "Imgs/" . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $sql_item = "INSERT INTO item (item_name, category_id, item_price, item_description, item_image, r_o_id) VALUES (?, ?, ?, ?, ?, ?)";
            $values_item = [$itemName, $category_id, $price, $description, $imagePath, $r_o_id];
            $datatype_item = "sdsssi"; 

            $res = insert($sql_item, $values_item, $datatype_item);

            if ($res > 0) {
                echo "<script>alert('Item added successfully.');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Error inserting the item.');</script>";
            }
        } else {
            echo "<script>alert('Error uploading the image.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields and upload a valid image.');</script>";
    }
}
?>




<?php
// session_start(); // Ensure the session is started
// include 'inc-res/link.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryName'])) {
    $categoryName = trim($_POST['categoryName']);
    $restaurant_id = $_SESSION['r_o_id']; 

    if (!empty($categoryName)) {
        $sql_check = "SELECT COUNT(*) FROM category WHERE category_name = ? AND r_o_id = ?";
        $stmt_check = $con->prepare($sql_check);
        $stmt_check->bind_param("si", $categoryName, $restaurant_id);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {            
            echo "<script>alert('Category already exists for this restaurant.');</script>";
        } else {
            
            $sql_category = "INSERT INTO category (category_name, r_o_id) VALUES (?, ?)";
            $values_category = [$categoryName, $restaurant_id];
            $datatype_category = "si";
            insertt($sql_category, $values_category, $datatype_category);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        echo "<script>alert('Please enter a valid category name.');</script>";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemname'])) {
    $itemName = trim($_POST['itemname']);
    $category_id = $_POST['category'];
    $price = $_POST['price'];
    $description = trim($_POST['description']);

    if (!empty($itemName) && !empty($price) && !empty($description) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imagePath = "Imgs/" . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $sql_item = "INSERT INTO item (item_name, category_id, item_price, item_description, item_image) VALUES (?, ?, ?, ?, ?)";
            $values_item = [$itemName, $category_id, $price, $description, $imagePath];
            $datatype_item = "sssss";
            insertt($sql_item, $values_item, $datatype_item);

            
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error uploading the image.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields and upload a valid image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body class="bg-light">
    <?php include('inc-res/r-header.php'); ?>

    <section>
        <h1 class="menu-heading">Menu</h1>
        <div class="container">
            <div class="row">
               
                <div class="col-md-10">
                    <div class="container">
                        <div class="row">
                            <?php
                           
                            $sql_categories = "SELECT category_id, category_name FROM category WHERE r_o_id = ?";
                            $values_categories = [$_SESSION['r_o_id']];
                            $datatype_categories = "i";
                            $result_categories = selectt($sql_categories, $values_categories, $datatype_categories);

                            if ($result_categories) {
                                while ($category = mysqli_fetch_assoc($result_categories)) {
                                    $category_id = $category['category_id'];
                                    $category_name = $category['category_name'];

                                    echo "<h2 class='category-title'>" . htmlspecialchars($category_name) . "</h2>";

                                 
                                    $sql_items = "SELECT item_id, item_name, item_price FROM item WHERE category_id = ?";
                                    $values_items = [$category_id];
                                    $datatype_items = "i";
                                    $result_items = selectt($sql_items, $values_items, $datatype_items);

                                    if ($result_items) {
                                        echo "<table class='table table-bordered table-hover'>";
                                        echo "<thead class='thead-dark'>";
                                        echo "<tr><th>Item Name</th><th>Price (Rs)</th><th>Action</th></tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        while ($item = mysqli_fetch_assoc($result_items)) {
                                            $item_id = $item['item_id'];
                                            $item_name = htmlspecialchars($item['item_name']);
                                            $item_price = number_format($item['item_price'], 2);

                                            echo "<tr>";
                                            echo "<td>" . $item_name . "</td>";
                                            echo "<td>Rs " . $item_price . "</td>";
                                            echo "<td><a href='editmenuitem.php?id=$item_id' class='btn btn-warning btn-sm'>Edit</a></td>";
                                            echo "</tr>";
                                        }

                                        echo "</tbody>";
                                        echo "</table>";
                                    } else {
                                        echo "<p>No items available for this category.</p>";
                                    }
                                }
                            } else {
                                echo "<p>No categories found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-2">
                    <div class="text-center">
                        <button type="button" class="btn btn-orange mb-3" data-bs-toggle="modal" data-bs-target="#categoryModal">Add Category</button>
                        <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#menuModal">Add Item</button>
                    </div>

                   
                    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="categoryName" class="form-label">Category Name</label>
                                            <input type="text" class="form-control" name="categoryName" id="categoryName" placeholder="Category Name" required>
                                        </div>
                                        <button type="submit" class="btn btn-orange w-100">Add Category</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="menuModalLabel">Add Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="itemName" class="form-label">Item Name</label>
                                            <input type="text" class="form-control" name="itemname" id="itemName" placeholder="Item Name" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category</label>
                                            <select class="form-control" name="category" id="category" required>
                                                <?php
                                                
                                                $sql = "SELECT category_id, category_name FROM category WHERE r_o_id = ?";
                                                $stmt = $con->prepare($sql);
                                                $stmt->bind_param("i", $_SESSION['r_o_id']);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value='" . $row['category_id'] . "'>" . htmlspecialchars($row['category_name']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option>No categories found</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control" name="price" id="price" placeholder="Price" required step="0.01">
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image</label>
                                            <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
                                        </div>

                                        <button type="submit" class="btn btn-orange w-100">Add Item</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('inc-res/r-footer.php'); ?>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
