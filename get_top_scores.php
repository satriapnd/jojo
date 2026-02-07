<?php
include 'db.php'; 

header('Content-Type: application/json');


$sql = "SELECT username, score AS high_score FROM highscores ORDER BY score DESC, updated_at ASC LIMIT 5";
$result = $conn->query($sql);

$top_scores = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $top_scores[] = $row;
    }
    echo json_encode(["status" => "ok", "scores" => $top_scores]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
}
?>