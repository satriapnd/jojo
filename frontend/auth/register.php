<?php
session_start();


include "backend/db.php"; 


$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $msg = "Username sudah digunakan!";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        $msg = "Akun berhasil dibuat. Silakan login.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link rel="stylesheet" href="../style.css"> </head>
<body class="auth-page"> <div class="auth-card">
        <h2>Register</h2>
        <?php if (!empty($msg)): ?>
            <p style="color: #FFD700; background: #333; padding: 10px; border-radius: 5px; margin-bottom: 20px;"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" class="auth-submit-btn">Daftar</button>
        </form>
        
        <p class="auth-link">
            Sudah punya akun? <a href="login.php">Login</a>
        </p>
    </div>

</body>
</html>