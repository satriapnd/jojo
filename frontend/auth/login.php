<?php

session_start();
include "backend/db.php"; 

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = $conn->real_escape_string($_POST['username']); 
    $password = $_POST['password'];

    $result = $conn->query("SELECT id, username, password FROM users WHERE username='$username'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            
          
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
           
            header("Location: ../index.php"); 
            exit;
        } else {
            $msg = "Password salah!";
        }
    } else {
        $msg = "Username tidak ditemukan!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <link rel="stylesheet" href="frontend/style.css"> </head>
<body class="auth-page">

    <div class="auth-card">
        <h2>Login</h2>
        <?php if (!empty($msg)): ?>
            <p style="color: #FFD700; background: #333; padding: 10px; border-radius: 5px; margin-bottom: 20px;"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" class="auth-submit-btn">Login</button>
        </form>
        
        <p class="auth-link">
            Belum punya akun? <a href="register.php">Daftar</a>
        </p>
    </div>

</body>
</html>