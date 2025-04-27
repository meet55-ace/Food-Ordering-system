<?php
// Include database connection
include 'inc-cus/link.php'; 

// Start the session
if (!isset($_SESSION['uid'])) {
    echo "<script>alert('You need to log in first');</script>";
    echo "<script>window.location = 'foodziee.php';</script>";
    exit();
}

// Get user ID from session
$uid = $_SESSION['uid'];

// Clear Cart functionality
if (isset($_POST['clear_cart'])) {
    // SQL query to delete all items from the user's cart
    $sql_clear = "DELETE FROM user_cart WHERE c_id = ?";
    
    // Execute query to remove all items from the cart
    if (selectt($sql_clear, [$uid], 'i')) {
        echo "<script>alert('Your cart has been cleared.');</script>";
        echo "<script>window.location = 'cart.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to clear your cart. Please try again later.');</script>";
        echo "<script>window.location = 'cart.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Cart</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    
    <?php include 'inc-cus/c-header.php'; ?>

    <!-- <div class="clear-cart-container">
        <h2>Are you sure you want to clear your cart?</h2>
        
        <form method="POST" onsubmit="return confirmClearCart()">
            <button type="submit" name="clear_cart" class="clear-cart-btn btn btn-danger">
                Yes, Clear My Cart
            </button>
        </form>
        <a href="cart.php" class="btn btn-secondary">No, Go Back</a>
    </div> -->


    <?php include 'inc-cus/c-footer.php'; ?>

    <!-- <script>
        
        function confirmClearCart() {
            return confirm("Are you sure you want to clear your entire cart?");
        }
    </script> -->
</body>
</html>
