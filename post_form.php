<?php 
if (!isset($_SESSION['username'])) {
    echo '<div id="new-post-modal" style="display:none;"><p style="color:red; text-align:center;">Harap Sign In terlebih dahulu untuk membuat Post baru.</p></div>';
    exit;
}
?>
<div id="new-post-modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);">
    <div style="background-color: #222; margin: 15% auto; padding: 30px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 10px; color: white;">
        
        <span onclick="closeNewPostModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        
        <h2>Buat Post Baru</h2>
        
        <form id="add-post-form">
            <input type="text" id="post-title" name="title" placeholder="Judul Post (Opsional)" 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; background: #333; color: white; border: 1px solid #555; border-radius: 5px;">
            
            <textarea id="post-content" name="content" required placeholder="Tulis konten Post Anda di sini..." 
                      style="width: 100%; height: 150px; padding: 10px; margin-bottom: 15px; background: #333; color: white; border: 1px solid #555; border-radius: 5px; resize: vertical;"></textarea>
            
            <button type="submit" style="background-color: #00BFFF; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">Kirim Post</button>
        </form>
    </div>
</div>