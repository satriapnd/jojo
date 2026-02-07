<?php

session_start();
include 'db.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    echo "NOT_LOGGED_IN";
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0); 
$comment = $conn->real_escape_string($_POST['comment'] ?? '');
$user = $_SESSION['username'];
$parent_id = intval($_POST['parent_id'] ?? 0);

if ($post_id <= 0 || empty($comment)) {
    http_response_code(400);
    echo "INVALID_DATA";
    exit;
}


$sql = "INSERT INTO comments (post_id, parent_id, username, comment, created_at)
        VALUES ($post_id, $parent_id, '$user', '$comment', NOW())";

if ($conn->query($sql)) {
    echo "OK";
} else {
    http_response_code(500);
 
    echo "ERROR_DB_INSERT: " . $conn->error;
}
?>