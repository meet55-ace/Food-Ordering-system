<?php
session_start();
include 'inc-res/link.php'; // Include the database connection

if (isset($_GET['category_name'])) {
    $categoryName = trim($_GET['category_name']);
    $restaurantOwnerId = $_SESSION['r_o_id']; // Fetch the restaurant owner ID from the session

    // Query to check if the category already exists for this restaurant owner
    $sql_check = "SELECT category_id FROM category WHERE category_name = ? AND r_o_id = ?";
    $values_check = [$categoryName, $restaurantOwnerId];
    $datatype_check = "si";

    $result_check = selectt($sql_check, $values_check, $datatype_check);

    // Prepare response
    $response = ['exists' => mysqli_num_rows($result_check) > 0];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
