<?php
session_name('customer_session');
session_start();

// Unset the specific session variable
if (isset($_SESSION['uName'])) {
    unset($_SESSION['uName']);
}

// Destroy the session
session_destroy();

// Redirect to another page
header('Location: foodziee.php');
exit(); // Ensure no further code executes
?>
