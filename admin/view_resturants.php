<?php
include 'link.php'; 


$sql = "SELECT * FROM r_o_details WHERE Requests='Approved'";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Restaurants</title>
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="view_resturants.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>

<body>
    <?php include 'a-header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">All Restaurants</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Restaurant Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Enable/Disable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['r_o_id'] ?></td>
                    <td><?= $row['res_name'] ?></td>
                    <td><?= $row['res_email'] ?></td>
                    <td><?= $row['r_o_phone'] ?></td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="toggle-status" data-id="<?= $row['r_o_id'] ?>"
                                <?= ($row['status'] == 1) ? 'checked' : ''; ?>>
                            <span class="slider"></span>
                        </label>
                    </td>

                    <td>
                        <a href="edit_restaurant.php?id=<?= $row['r_o_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="view_items.php?id=<?= $row['r_o_id'] ?>" class="btn btn-info btn-sm">View Items</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
    $(document).ready(function() {
        $(".toggle-status").change(function() {
            let id = $(this).data('id');
            let status = $(this).prop('checked') ? 1 : 0; 

            $.ajax({
                url: 'toggle_restaurant_status.php',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    console.log(response);
                }
            });
        });
    });
    </script>
</body>

</html>