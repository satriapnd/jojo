<?php
session_start();
include 'db.php'; 


if (!isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit;
}

$msg = "";
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $content = $conn->real_escape_string($_POST['content'] ?? '');
    $image_path_db = null; 
    
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = $_FILES['image'];
        $uploadDir = 'assets/uploads/';
        
       
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '-' . basename($file['name']);
        $targetFile = $uploadDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if ($file['size'] > 5000000) { 
            $msg = "Ukuran file terlalu besar. Maksimal 5MB.";
        } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $msg = "Hanya file JPG, JPEG, PNG, & GIF yang diperbolehkan.";
        } elseif (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $image_path_db = $targetFile;
        } else {
            $msg = "Gagal memindahkan file yang diunggah.";
        }
    }
   


    if (empty($content)) {
        $msg = "Konten Post tidak boleh kosong!";
    }

    

    if (empty($msg)) {
      
        $sql = "INSERT INTO posts (user_id, username, title, content_text, image_path, created_at)
                VALUES ('$user_id', '$username', '$title', '$content', " . 
                ($image_path_db ? "'$image_path_db'" : "NULL") . ", NOW())";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: community.php?status=success");
            exit;
        } else {
            $msg = "Gagal menyimpan Post: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Post Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  
    
    <div class="container" style="max-width: 600px;">
        <h2>Buat Post Baru</h2>
        
        <?php if (!empty($msg)): ?>
            <p style="color: red; background: #333; padding: 10px; border-radius: 5px;"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST" action="create_post.php" enctype="multipart/form-data"> 
            <input type="text" name="title" placeholder="Judul Post (Opsional)" 
                   style="width: 98%; padding: 10px; margin-bottom: 15px; background: #333; color: white; border: 1px solid #555; border-radius: 5px;">
            
            <textarea name="content" required placeholder="Tulis konten Post Anda di sini..." 
                      style="width: 98%; height: 200px; padding: 10px; margin-bottom: 15px; background: #333; color: white; border: 1px solid #555; border-radius: 5px; resize: vertical;"></textarea>
            
            <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #444; border-radius: 5px; background: #1E1E1E;">
                <label for="post-image" style="display: block; margin-bottom: 8px; font-weight: bold; color: #ccc;">Unggah Gambar (Maks 5MB):</label>
                <input type="file" id="post-image" name="image" accept="image/*" 
                       style="display: block; width: 100%;">
            </div>

            <button type="submit" style="background-color: #00BFFF; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;">Kirim Post</button>
            <a href="community.php" style="margin-left: 10px; color: #aaa;">Batal</a>
        </form>
    </div>
    
</body>
</html>