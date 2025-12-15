<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="main-header fixed-header">
    <h1><a href="index.php" class="logo">AboutJo</a></h1>
    
    <nav>
        <a href="index.php">Home</a>
        <a href="minigames_landing.php">Arena</a> 
        <a href="community.php">Community Feed</a> 
        <a href="about.php">About</a>
    </nav>

    <div class="top-right">
        <?php if (isset($_SESSION['username'])): ?>
            <span style="font-size:14px; margin-right:10px;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span> 
            <a href="auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn-login">Sign In</a>
            <a href="auth/register.php" class="btn-register">Register</a>
        <?php endif; ?>
    </div>
</header>