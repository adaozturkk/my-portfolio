<?php
session_start();

// Clear session and logout admin
$_SESSION = array();

session_destroy();

setcookie("admin_username", "", time() - 3600, "/");

header("Location: index.php");
exit();