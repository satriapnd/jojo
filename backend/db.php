<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jojo_final_db"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>