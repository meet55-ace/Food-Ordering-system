<?php
session_name('delivery_session');
session_start();
if(isset($_SESSION['name']))
{
    unset($_SESSION['name']);
}

session_destroy();

header('Location: dev-lo.php');
exit();

?>