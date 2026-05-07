<?php

$host = "127.0.0.1";
$port = "3307";
$username = "root";
$password = "";
$database = "portfolio_db";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
} 

?>