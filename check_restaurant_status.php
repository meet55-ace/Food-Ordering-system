<?php
session_name('restuarant_session');
session_start();
include 'inc-res/link.php';

if (!isset($_SESSION['r_o_id'])) {
    echo "closed"; // No restaurant logged in
    exit;
}

$restaurant_id = $_SESSION['r_o_id'];
$query = "SELECT is_open FROM r_o_details WHERE r_o_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $restaurant_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo ($row['is_open'] == 1) ? "open" : "closed";
?>
