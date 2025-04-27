<?php
include 'db/config.php';

$sql = "SELECT i.item_name, i.item_price, i.item_description, i.item_image, c.category_name
        FROM item i
        JOIN category c ON i.category_id = c.category_id";

$result = mysqli_query($con, $sql);
$items = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
}

echo json_encode($items);
mysqli_close($con);
?>
