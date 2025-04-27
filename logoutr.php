<?php
session_name('restuarant_session');
session_start();
if(isset($_SESSION['name']))
{
    unset($_SESSION['name']);
}

session_destroy();

header('Location: res-lo.php');
exit();

?>