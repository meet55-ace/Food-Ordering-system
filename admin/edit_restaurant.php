<?php
include 'link.php'; // Database connection

// Get restaurant ID from URL
$restaurant_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($restaurant_id == 0) {
    die("Invalid restaurant ID.");
}

// Fetch restaurant details
$sql = "SELECT * FROM r_o_details WHERE r_o_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();
$restaurant = $result->fetch_assoc();

if (!$restaurant) {
    die("Restaurant not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res_name = $_POST['res_name'];
    $r_o_phone = $_POST['r_o_phone'];
    $res_address = $_POST['res_address'];
    $approx = $_POST['approx'];

    // Handle image upload
    if (!empty($_FILES['profile']['name'])) {
        $target_dir = "../ajax/imgs/card(restaurant)/"; // Ensure this directory exists
        $profile_filename = basename($_FILES["profile"]["name"]);
        $target_file = $target_dir . $profile_filename;

        // Move the uploaded file
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            $profile = $profile_filename; // Save only the filename in the database
        } else {
            echo "<script>alert('Error uploading image.');</script>";
            $profile = $restaurant['profile']; // Keep existing image if upload fails
        }
    } else {
        $profile = $restaurant['profile']; // Keep existing image if no new one is uploaded
    }

    // Update restaurant details
    $sql_update = "UPDATE r_o_details SET res_name=?, r_o_phone=?, res_address=?, profile=?, approx=? WHERE r_o_id=?";
    $stmt_update = $con->prepare($sql_update);
    $stmt_update->bind_param("sssssi", $res_name, $r_o_phone, $res_address, $profile, $approx, $restaurant_id);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Restaurant updated successfully!'); window.location.href='view_resturants.php';</script>";
    } else {
        echo "Error updating restaurant: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="edit_restaurant.css">
</head>
<body>
    <?php include 'a-header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Restaurant</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Restaurant Name</label>
                <input type="text" name="res_name" class="form-control" value="<?= htmlspecialchars($restaurant['res_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="r_o_phone" class="form-control" value="<?= htmlspecialchars($restaurant['r_o_phone']) ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="res_address" class="form-control" value="<?= htmlspecialchars($restaurant['res_address']) ?>" required>
            </div>
            <div class="form-group">
                <label>Approximate Cost</label>
                <input type="text" name="approx" class="form-control" value="<?= htmlspecialchars($restaurant['approx']) ?>" required>
            </div>
            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" name="profile" class="form-control">
                <?php if (!empty($restaurant['profile'])) { ?>
                    <img src="../ajax/imgs/card(restaurant)/<?= htmlspecialchars($restaurant['profile']) ?>" alt="Profile Image" width="100" class="mt-2">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</body>
</html>
