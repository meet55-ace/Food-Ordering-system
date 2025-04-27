<?php
// session_start();
include 'inc-cus/link.php'; // Make sure link.php is correctly included for DB connection

// Check if user is logged in (assumes user_id is stored in the session)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the 'action' is set to 'add'
if (isset($data['action']) && $data['action'] == 'add') {
    $item = $data['item'];
    $item_id = $item['id'];
    $quantity = $item['quantity'];
    $session_id = session_id(); // Get the session ID to track cart items

    // Check if the item already exists in the user's cart in the database
    $query = "SELECT * FROM user_cart WHERE c_id = ? AND item_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item exists, update the quantity
        $update_query = "UPDATE user_cart SET quantity = quantity + ? WHERE c_id = ? AND item_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('iii', $quantity, $user_id, $item_id);
        $update_stmt->execute();
    } else {
        // Item doesn't exist, insert a new record
        $insert_query = "INSERT INTO user_cart (session_id, c_id, item_id, quantity) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('siii', $session_id, $user_id, $item_id, $quantity);
        $insert_stmt->execute();
    }

    // Get updated cart count for the user
    $cart_count_query = "SELECT SUM(quantity) AS cart_count FROM user_cart WHERE c_id = ?";
    $cart_count_stmt = $conn->prepare($cart_count_query);
    $cart_count_stmt->bind_param('i', $user_id);
    $cart_count_stmt->execute();
    $cart_count_result = $cart_count_stmt->get_result();
    $cart_count = $cart_count_result->fetch_assoc()['cart_count'];

    echo json_encode(['success' => true, 'cartCount' => $cart_count]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
