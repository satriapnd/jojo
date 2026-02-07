<?php
session_start();
include 'db.php'; 

header('Content-Type: application/json');


if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["status" => "info", "message" => "Anda harus Sign In untuk menyimpan skor!"]);
    exit;
}

$username = $_SESSION['username'];
$score = intval($_POST['score'] ?? 0);

if ($score <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Skor tidak valid."]);
    exit;
}


$sql = "INSERT INTO highscores (username, score) 
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE 
        score = GREATEST(score, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sii", $username, $score, $score);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "ok", "message" => "Skor berhasil disimpan dan diperbarui!"]);
        } else {
            echo json_encode(["status" => "info", "message" => "Skor Anda tidak lebih tinggi dari yang sudah ada."]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Prepare statement gagal: " . $conn->error]);
}
?>