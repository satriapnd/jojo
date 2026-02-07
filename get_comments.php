<?php
session_start();
include 'db.php'; 


ini_set('display_errors', 1);
error_reporting(E_ALL);


$post_id = intval($_GET['post_id'] ?? 0); 


header('Content-Type: application/json');

if ($post_id <= 0) {
    http_response_code(400);
  
    echo json_encode(["error" => "Post ID is required or invalid."]);
    exit;
}


$sql = "SELECT id, post_id, parent_id, username, comment, created_at FROM comments WHERE post_id=$post_id ORDER BY created_at ASC";
$result = $conn->query($sql);

$comments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database Query Failed: " . $conn->error]);
    exit;
}


function buildTree(array &$elements, $parentId = 0) {
    $branch = array();
    foreach ($elements as $key => &$element) { 
        if ($element['parent_id'] == $parentId) { 
            $children = buildTree($elements, $element['id']);
            if ($children) {
                $element['replies'] = $children;
            }
            $branch[] = $element; 
           
            unset($elements[$key]); 
        }
    }
    return $branch;
}

$commentTree = buildTree($comments, 0);

echo json_encode($commentTree);
?>