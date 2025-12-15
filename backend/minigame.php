<?php
session_start();
include 'db.php'; 

$is_logged_in = isset($_SESSION['username']);

$leaderboard = [];
$sql_leaderboard = "SELECT username, score as high_score 
                    FROM highscores 
                    ORDER BY high_score DESC 
                    LIMIT 5";
$result_leaderboard = $conn->query($sql_leaderboard);

if ($result_leaderboard) {
    while ($row = $result_leaderboard->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arena</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
       
        body.game-page {
            padding-top: 60px; 
            margin: 0;
            overflow-x: hidden;
            background-color: black;
            cursor: none; 
        }
        
    
        
        canvas {
            display: block;
        }
        
        #game-container {
            position: relative;
            height: calc(100vh - 60px); 
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #game-canvas-area {
            position: relative;
            width: 100%;
            height: 100%;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
            z-index: 10;
            text-align: center;
        }
        #overlay h1 {
            font-size: 64px;
            margin-bottom: 20px;
        }
        #overlay p {
            font-size: 24px;
            margin-top: 20px;
        }

       
        .leaderboard-container {
            max-width: 400px;
            margin: 20px auto; 
            padding: 20px;
            background: #1E1E1E;
            border-radius: 8px;
            border: 1px solid #00BFFF;
        }
        .leaderboard-container h2 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .leaderboard-container ol {
            list-style: none;
            padding: 0;
        }
        .leaderboard-container li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #333;
            font-size: 14px;
        }
    </style>
</head>
<body class="game-page">

    <?php include 'header.php'; ?>
    
    <div id="game-container">
        
        <?php if (!$is_logged_in): ?>
            <p style="color: red; font-size: 1.2em; margin-top: 50px;">Anda harus <a href="auth/login.php">Login</a> untuk memainkan Mini Game!</p>
        <?php else: ?>
            <div id="game-canvas-area">
                <canvas id="gameCanvas"></canvas>
                <div id="overlay">
                    <h1 id="overlayTitle">JOJO MINI GAME</h1>
                    <p id="keyboardInstructions">Tekan P untuk Mulai</p>
                    
                    <div id="score-status" style="margin-top: 20px; font-size: 1.2em; display: none;"></div>
                    <div id="leaderboard-display-area" style="margin-top: 20px; display: none;"></div>
                </div>
            </div>
        <?php endif; ?>
        
    </div>

    <script>
    <?php if ($is_logged_in): ?>
        const leaderboardData = <?= json_encode($leaderboard) ?>;

        const urlParams = new URLSearchParams(window.location.search);
        const viewMode = urlParams.get('view');

       
        function loadAndUpdateLeaderboard(statusMessage) {
            const displayArea = document.getElementById('leaderboard-display-area');
            displayArea.style.display = 'block';
            displayArea.innerHTML = '<p style="color: #00BFFF;">Mengambil Leaderboard terbaru...</p>';

            fetch('get_top_scores.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status === "ok") {
                        displayArea.innerHTML = renderLeaderboard(data.scores);
                    } else {
                        displayArea.innerHTML = '<p style="color: red;">Gagal memuat leaderboard.</p>';
                    }
                    
                    const scoreStatusDiv = document.getElementById('score-status');
                    scoreStatusDiv.textContent = statusMessage; 
                    scoreStatusDiv.style.display = 'block';
                    
                    document.getElementById('keyboardInstructions').textContent = "Tekan R untuk RESTART atau Q untuk KELUAR"; 
                })
                .catch(error => {
                    displayArea.innerHTML = '<p style="color: red;">Error jaringan saat memuat leaderboard.</p>';
                });
        }
        
        function sendScore(score) {
            const formData = new FormData();
            formData.append('score', score);

            fetch('save_score.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.status === 401) {
                    return { status: "info", message: "Sesi habis, skor tidak dapat disimpan." };
                }
                return response.json();
            })
            .then(data => {
                let message;
                if (data.status === "ok") {
                    message = "✅ Skor baru (Top Score) anda berhasil disimpan!";
                } else if (data.status === "info") {
                    message = "ℹ️ " + data.message;
                } else {
                    message = "POKEEEEEE " + (' ' || 'Error tidak diketahui.');
                }
                
                loadAndUpdateLeaderboard(message);
            })
            .catch(error => {
                console.error('Error mengirim skor:', error);
                document.getElementById('score-status').textContent = '❌ Kesalahan jaringan!';
                loadAndUpdateLeaderboard('❌ Kesalahan jaringan saat mencoba menyimpan skor.');
            });
        }
        
        function renderLeaderboard(data) {
            if (!data || data.length === 0) {
                return '<p style="color: #ccc;">Belum ada skor yang tercatat.</p>';
            }
            
            let html = '<div class="leaderboard-container"><h2>🏆 TOP 5 HIGH SCORES</h2><ol style="list-style: none; padding: 0;">';
            data.forEach((entry, index) => {
                const isCurrentUser = entry.username === '<?= $_SESSION['username'] ?>';
                const highlightStyle = isCurrentUser ? 'style="color: #FFD700; font-weight: bold;"' : '';

                html += `
                    <li ${highlightStyle} style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #444;">
                        <span class="rank">${index + 1}.</span>
                        <span>${entry.username}</span>
                        <span>${entry.high_score} Kills</span> 
                    </li>
                `;
            });
            html += '</ol></div>';
            return html;
        }

        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");
        const overlay = document.getElementById("overlay");
        const overlayTitle = document.getElementById("overlayTitle");
        const keyboardInstructions = document.getElementById("keyboardInstructions");
        const gameCanvasArea = document.getElementById('game-canvas-area');

       
        canvas.width = gameCanvasArea.clientWidth;
        canvas.height = gameCanvasArea.clientHeight;
        
        window.addEventListener('resize', () => {
            canvas.width = gameCanvasArea.clientWidth;
            canvas.height = gameCanvasArea.clientHeight;
        });

        
        const fistIdle = new Image();
        fistIdle.src = "fist_idle.png";

        const fistPunch = new Image();
        fistPunch.src = "fist_punch.png";

        const enemyImg = new Image();
        enemyImg.src = "enemy.png";

      
        let hp;
        let kills;
        let punching;
        let gameRunning = false;
        let animationFrameId;

        
        let yaw;
        let pitch;
        const sensitivity = 0.002;

        
        class Enemy {
          constructor(offsetX) {
            this.offsetX = offsetX;
            this.reset();
          }

          reset() {
            this.x = this.offsetX;
            this.y = 0;
            this.z = 800 + Math.random() * 500;
            this.hit = 0;
            this.speed = 1 + Math.random();
          }

          update() {
            if (!gameRunning) return;

            const speedMul = 1 + Math.floor(kills / 15);
            this.z -= this.speed * speedMul;

            if (this.z < 100) {
              hp -= 5;
              this.reset();
            }
          }

          draw() {
            const relX = this.x - yaw * 500;
            const relY = this.y + pitch * 500;

            const scale = 400 / this.z;
            const screenX = canvas.width / 2 + relX * scale;
            const screenY = canvas.height / 2 + relY * scale;
            const size = 120 * scale;

            this.screenX = screenX;
            this.screenY = screenY;
            this.hitSize = size / 2;

            if (enemyImg.complete) {
                ctx.drawImage(
                    enemyImg,
                    screenX - size / 2,
                    screenY - size / 2,
                    size,
                    size
                );
            } else {
                ctx.fillStyle = 'red';
                ctx.fillRect(screenX - size/2, screenY - size/2, size, size);
            }
          }

          hitCheck() {
            const cx = canvas.width / 2;
            const cy = canvas.height / 2;

            const dx = cx - this.screenX;
            const dy = cy - this.screenY;

            if (Math.sqrt(dx * dx + dy * dy) < this.hitSize) {
              this.hit++;
              spawnStar(this.screenX, this.screenY); 

              if (this.hit >= 2) {
                kills++;
                this.reset();
              }
            }
          }
        }

        let enemies;

       
        let stars = [];
        function spawnStar(x, y) {
          stars.push({ x, y, life: 10 });
        }

        function drawStars() {
          stars.forEach(s => {
            ctx.fillStyle = "yellow";
            ctx.beginPath();
            ctx.arc(s.x, s.y, 8, 0, Math.PI * 2);
            ctx.fill();
            s.life--;
          });
          stars = stars.filter(s => s.life > 0);
        }

        

        const handleKeyDown = e => {
          if (!gameRunning && e.key.toLowerCase() === 'p') {
            startGame();
          } else if (!gameRunning && e.key.toLowerCase() === 'r') {
            window.location.reload(); 
          } else if (!gameRunning && e.key.toLowerCase() === 'q') {
            window.location.href = 'minigames_landing.php'; 
          }
        };

        const handleCanvasClick = () => {
          if (gameRunning) {
            canvas.requestPointerLock();
          }
        };

        const handleMouseMove = e => {
          if (document.pointerLockElement === canvas && gameRunning) {
            yaw += e.movementX * sensitivity;
            pitch -= e.movementY * sensitivity;
            pitch = Math.max(-0.5, Math.min(0.5, pitch));
          }
        };

        const handleMouseDown = e => {
          if (e.button === 0 && gameRunning) {
            punching = true;
            enemies.forEach(en => en.hitCheck());
          }
        };

        const handleMouseUp = () => punching = false;

        function toggleInput(enable) {
          if (enable) {
            document.addEventListener("mousemove", handleMouseMove);
            document.addEventListener("mousedown", handleMouseDown);
            document.addEventListener("mouseup", handleMouseUp);
            canvas.addEventListener("click", handleCanvasClick);
          } else {
            document.removeEventListener("mousemove", handleMouseMove);
            document.removeEventListener("mousedown", handleMouseDown);
            document.removeEventListener("mouseup", handleMouseUp);
            canvas.removeEventListener("click", handleCanvasClick);
          }
        }

        document.addEventListener("keydown", handleKeyDown);

       
        function drawCrosshair() {
          ctx.strokeStyle = "white";
          ctx.beginPath();
          ctx.moveTo(canvas.width / 2 - 10, canvas.height / 2);
          ctx.lineTo(canvas.width / 2 + 10, canvas.height / 2);
          ctx.moveTo(canvas.width / 2, canvas.height / 2 - 10);
          ctx.lineTo(canvas.width / 2, canvas.height / 2 + 10);
          ctx.stroke();
        }

        function drawHands() {
          const img = punching ? fistPunch : fistIdle;
          const w = canvas.width * 0.6;
          const h = canvas.height * 0.5;
          
          if (img.complete) {
              ctx.drawImage(
                img,
                canvas.width / 2 - w / 2,
                canvas.height - h,
                w,
                h
              );
          }
        }

        function drawUI() {
          ctx.fillStyle = "white";
          ctx.font = "18px Arial";
          ctx.fillText("HP: " + hp, 20, 30);
          ctx.fillText("Kill: " + kills, 20, 55);
          ctx.fillText("HP: " + hp, 20, 30);
        }

        

        function initGame() {
          hp = 50;
          kills = 0;
          punching = false;
          yaw = 0;
          pitch = 0;
          stars = [];
          enemies = [
            new Enemy(-200),
            new Enemy(0),
            new Enemy(200)
          ];
        }

        function startGame() {
          initGame();
          overlay.style.display = 'none';
          toggleInput(true);
          gameRunning = true;
          
         
          canvas.width = gameCanvasArea.clientWidth;
          canvas.height = gameCanvasArea.clientHeight;

          loop();
        }

        function initScreen() {
          overlay.style.display = 'flex';
          overlayTitle.textContent = "WELCOME";
          keyboardInstructions.textContent = "Tekan P untuk Mulai";
          
          document.getElementById('score-status').style.display = 'none';
          document.getElementById('leaderboard-display-area').style.display = 'none';
          
          toggleInput(false);
          document.exitPointerLock();
          ctx.fillStyle = "black";
          ctx.fillRect(0, 0, canvas.width, canvas.height);
          
        
          if (viewMode === 'leaderboard') {
              
              overlayTitle.textContent = "TOP 5 HIGH SCORES";
              keyboardInstructions.textContent = "Tekan Q untuk kembali dan P untuk play game";
              
             
              const displayArea = document.getElementById('leaderboard-display-area');
              displayArea.innerHTML = renderLeaderboard(leaderboardData);
              displayArea.style.display = 'block';
          }
        }

        function gameOverScreen() {
          gameRunning = false;
          cancelAnimationFrame(animationFrameId);
          toggleInput(false);
          document.exitPointerLock();

          overlayTitle.textContent = "GAME OVER! Kills: " + kills;
          keyboardInstructions.textContent = "Menyimpan skor..."; 
          overlay.style.display = 'flex';
          
          sendScore(kills); 
        }

        function loop() {
          ctx.clearRect(0, 0, canvas.width, canvas.height);

          ctx.fillStyle = "black";
          ctx.fillRect(0, 0, canvas.width, canvas.height);

          enemies.forEach(e => {
            e.update();
            e.draw();
          });

          drawStars();
          drawHands();
          drawCrosshair();
          drawUI();

          if (hp > 0) {
            animationFrameId = requestAnimationFrame(loop);
          } else {
            gameOverScreen();
          }
        }

  
        initScreen();
        
    <?php endif; ?>
    </script>
</body>
</html>