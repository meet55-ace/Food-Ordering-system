<?php
session_name('admin_session');
session_start();
// if(isset($_SESSION['name']))
// {
//     unset($_SESSION['name']);
// }

session_destroy();

header('Location: index.php');

?>