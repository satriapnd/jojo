<?php

?>

<div id="floating-panel">
    <div style="background-color: #1E1E1E; margin: 5% auto; padding: 25px; border: 1px solid #444; width: 90%; max-width: 550px; border-radius: 10px; color: white;">
        
        <span class="close-btn" onclick="document.getElementById('floating-panel').style.display='none'">&times;</span>
        
        <input type="hidden" id="current-post-id" value=""> 
        
        <div id="comment-section">
            <span id="post-title-detail" style="font-size: 0.8em; color: #999;"></span></h3> 
            
            <?php if (isset($_SESSION['username'])): ?>
                <textarea id="comment-input" placeholder="Tulis komentar"></textarea>
                <button onclick="submitComment()">Kirim</button> 
            <?php else: ?>
                <p><i>Sign In untuk menulis komentar.</i></p>
            <?php endif; ?>

            <div id="comment-list">
                
            </div>
        </div>
    </div>
</div>