<?php
// session_start();
include 'inc-cus/link.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cart'])) {
    // Save cart to session
    $_SESSION['cart'] = $data['cart'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
