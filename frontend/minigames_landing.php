<?php
session_start();
include 'backend/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arena</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .lobby-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 40px;
            background-color: #1E1E1E;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #333;
        }
        .lobby-container h2 {
            color: #FFD700;
            margin-bottom: 10px;
        }
        .lobby-container p {
            color: #ccc;
            margin-bottom: 30px;
        }
        
        .button-group a {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
            margin: 10px; 
        }
        
        .start-btn {
            background-color: #277affff;
            color: white;
        }
        .start-btn:hover {
            background-color: #0099CC;
        }
      
        .leaderboard-btn {
            background-color: #555; 
            color: white;
            border: 1px solid #777;
        }
        .leaderboard-btn:hover {
            background-color: #777;
        }

    </style>
</head>
<body>

    <?php include 'backend/header.php'; ?>

    <div class="lobby-container">
    <h2>Selamat Datang di Arena</h2>
    <p style="margin-bottom: 30px;">Jangan biarkan musuh melewati garis pertahanan Anda!</p>
    <div style="background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px dashed #1e1e1e;">
        
        
    </div>
    <div class="button-group">
        <?php if (isset($_SESSION['username'])): ?>
            <p style="color: #AAAAAA; font-weight: 500; margin-bottom: 20px; width: 100%;">
                Anda sebagai: <span style="color: #00BFFF;"><?= htmlspecialchars($_SESSION['username']) ?></span>
            </p>
            <a href="minigame.php" class="start-btn">PLAY</a>
        <?php else: ?>
            <p style="color: #FF6B6B; font-weight: 500; margin-bottom: 20px; width: 100%;">
                Silakan login terlebih dahulu
            </p>
            <a href="auth/login.php" class="start-btn">Login</a>
        <?php endif; ?>
        
        <a href="minigame.php?view=leaderboard" class="leaderboard-btn">Leaderboard</a>
    </div>
</div>

    </div>

</body>
</html>