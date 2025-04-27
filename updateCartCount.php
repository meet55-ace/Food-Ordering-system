<?php
// session_start();
include 'inc-cus/link.php';
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
echo json_encode(['cartCount' => $cartCount]);
?>
