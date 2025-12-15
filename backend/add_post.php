<?php
session_start();
include 'db.php'; 


ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo "NOT_LOGGED_IN";
    exit;
}


$username = $_SESSION['username'];
$title = $conn->real_escape_string($_POST['title'] ?? '');
$content = $conn->real_escape_string($_POST['content'] ?? ''); 

if (empty($content)) {
    http_response_code(400);
    echo "CONTENT_REQUIRED";
    exit;
}

$user_id = null;


$sql_user = "SELECT id FROM users WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if ($result_user && $result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $user_id = $user_row['id'];
    $_SESSION['user_id'] = $user_id; 
} else {
    http_response_code(403);
    echo "USER_NOT_FOUND_IN_DB"; 
    exit;
}


$sql = "INSERT INTO posts (user_id, username, title, content_text, created_at)
        VALUES ('$user_id', '$username', '$title', '$content', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "OK"; 
} else {
    http_response_code(500);
   
    echo "ERROR_DB_INSERT: " . $conn->error; 
}
?>