<?php
session_start();
include 'db.php'; 
$sql = "SELECT 
            p.id, 
            p.user_id, 
            p.username, 
            p.title, 
            p.content_text, 
            p.image_path, 
            p.created_at,
            COUNT(c.id) AS comment_count 
        FROM 
            posts p
        LEFT JOIN 
            comments c ON p.id = c.post_id 
        GROUP BY 
            p.id, p.user_id, p.username, p.title, p.content_text, p.image_path, p.created_at
        ORDER BY 
            p.created_at DESC";
            
$result = $conn->query($sql);

$posts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
} else {
    $posts_error = "Error fetching posts: " . $conn->error;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Feed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div style="max-width: 750px; margin: 80px auto 0px; padding: 20px;">
        <div style="background: #1E1E1E; padding: 25px; border-radius: 10px; text-align: center; border: 1px solid #333;">
            <h2 style="color: #FFD700; margin-bottom: 10px; font-size: 24px;"> Community Feed</h2>
            <p style="color: #AAAAAA; font-size: 14px; line-height: 1.6;">
                Bergabunglah dengan komunitas penggemar JoJo! Bagikan teori, diskusi episode terbaru, 
                fan art, atau apapun yang berkaitan dengan JoJo's Bizarre Adventure. 
                Mari kita bicarakan Stand favoritmu dan momen paling berkesan!
            </p>
        </div>
    </div>
    
    <div class="community-feed-container" style="margin-top: 0;"> 
        
        <?php if (isset($_SESSION['username'])): ?>
            <a href="create_post.php" class="add-post-btn" 
               style="text-decoration: none; display: block; text-align: center; margin-bottom: 20px; ">
                Buat Post Baru
            </a>
        <?php else: ?>
            <p style="text-align: center; color: #FFD700; margin-bottom: 20px;">Silakan <a href="auth/login.php">Login</a> untuk membuat Post baru.</p>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <p style="background: #28a745; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">Post berhasil dikirim!</p>
        <?php endif; ?>

        <div class="posts-list">
            <?php if (isset($posts_error)): ?>
                <p class="error-message" style="color:red;"><?= $posts_error ?></p>
            <?php elseif (empty($posts)): ?>
                <p style="text-align: center; color: #999;">Belum ada Post. Jadilah yang pertama!</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-card" data-post-id="<?= $post['id'] ?>">
                        <div class="post-header">
                            <strong><?= htmlspecialchars($post['username']) ?></strong>
                            <span><?= $post['created_at'] ?></span>
                        </div>
                        
                        <h3><?= htmlspecialchars($post['title'] ? $post['title'] : "Tanpa Judul") ?></h3>
                        
                        <?php if (!empty($post['image_path'])): ?>
                            <div class="post-image-container" style="margin-bottom: 15px;">
                                <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" 
                                     style="max-width: 100%; height: auto; border-radius: 5px; display: block;">
                            </div>
                        <?php endif; ?>
                        <p><?= nl2br(htmlspecialchars($post['content_text'])) ?></p>
                        
                        <div class="post-stats" style="margin-bottom: 15px; font-size: 13px; color: #aaa; border-bottom: 1px solid #333; padding-bottom: 10px;">
                            <span class="comment-stats">
                                <?= $post['comment_count'] ?> Komentar
                            </span>
                        </div>
                        
                        <div class="post-actions">
                            <button class="comment-btn" 
                                onclick="openCommentPanel(<?= $post['id'] ?>, '<?= htmlspecialchars($post['title'] ? $post['title'] : "Tanpa Judul", ENT_QUOTES) ?>')">
                                💬 Komentar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'floating_panel_template.php'; ?>
    
    <script src="script.js"></script>
    <script>
      
        function openCommentPanel(postId, postTitle) {
            document.getElementById('current-post-id').value = postId;
            document.getElementById('post-title-detail').textContent = postTitle;
            
            document.getElementById('floating-panel').style.display = 'block';
            loadComments(postId); 
        }
    </script>
</body>
</html>