<?php
session_start();

$_SESSION = array();

session_destroy();

setcookie("admin_username", "", time() - 3600, "/");

header("Location: index.php");
exit();