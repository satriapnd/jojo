function loadComments(postId) {
    
    if (!postId || isNaN(postId)) {
        console.error("Invalid Post ID received for loading comments.");
        return;
    }

    fetch(`get_comments.php?post_id=${postId}`) 
    .then(res => res.json())
    .then(comments => {
        const commentList = document.getElementById("comment-list");
        if (Array.isArray(comments)) {
           
            commentList.innerHTML = renderComments(comments);
        } else if (comments && comments.error) {
            
            commentList.innerHTML = `<p style="color:red;">Gagal memuat diskusi: ${comments.error}</p>`;
        } else {
            
             commentList.innerHTML = `<p>Belum ada komentar.</p>`;
        }
    })
    .catch(error => {
        console.error('Error fetching comments:', error);
        document.getElementById("comment-list").innerHTML = 
            `<p style="color:red;">Terjadi kesalahan jaringan saat memuat komentar.</p>`;
    });
}


function renderComments(comments, level = 0) {
    let html = '';
    
    comments.forEach(c => {
        const margin = level * 25;
        const commentId = c.id;
        
       
        html += `
            <div class="comment-item" style="margin-left: ${margin}px; padding-left: ${level > 0 ? '10px' : '0'}; border-left: ${level > 0 ? '2px solid #333' : 'none'};">
                <p><b>${c.username}</b>: ${c.comment}</p>
                <div class="time">${c.created_at}</div>
                
                <button class="reply-btn" onclick="showReplyInput(${commentId})">Balas</button>
                
                <div id="reply-to-${commentId}" class="reply-input-box">
                    <textarea id="reply-text-${commentId}" placeholder="Balas ke ${c.username}..." style="width: 100%; height: 50px;"></textarea>
                    <button onclick="submitComment(${commentId})">Kirim Balasan</button>
                </div>
            `;
            
        
        if (c.replies && c.replies.length > 0) {
            html += renderComments(c.replies, level + 1);
        }
        
        html += `</div>`;
    });
    
    return html;
}


function showReplyInput(commentId) {
    const replyBox = document.getElementById(`reply-to-${commentId}`);
    if (replyBox) {
        if (replyBox.style.display === 'none' || replyBox.style.display === '') {
            replyBox.style.display = 'block';
        } else {
            replyBox.style.display = 'none';
        }
    }
}


function submitComment(parentId = 0) {
    let text;
    let inputId;

    if (parentId === 0) {
        inputId = "comment-input"; 
    } else {
        inputId = `reply-text-${parentId}`; 
    }
    
    const inputElement = document.getElementById(inputId);
    if (!inputElement) return; 
    text = inputElement.value;

    const post_id = document.getElementById("current-post-id").value;

    if (text.trim() === "" || post_id === "") {
        alert("Komentar atau ID Post tidak ditemukan.");
        return; 
    }

    const form = new FormData();
    form.append("comment", text);
    form.append("post_id", post_id); 
    
    if (parentId !== 0) { 
        form.append("parent_id", parentId);
    }

    fetch("add_comment.php", { method: "POST", body: form })
    .then(res => res.text())
    .then(result => {
        if (result.trim() === "OK") { 
            inputElement.value = ""; 
            
            
            if (parentId !== 0) {
                 const replyBox = document.getElementById(`reply-to-${parentId}`);
                 if (replyBox) replyBox.style.display = 'none';
            }
            loadComments(post_id); 
            
        } else {
            alert("Gagal mengirim komentar. Cek Network Tab. Pesan: " + result); 
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        alert("Terjadi kesalahan teknis saat mengirim komentar.");
    });
}



function openNewPostModal() {
    const modal = document.getElementById('new-post-modal');
    if (modal) {
        modal.style.display = 'block'; 
    } else {
        console.error("Modal 'new-post-modal' tidak ditemukan!");
    }
}

function closeNewPostModal() {
    const modal = document.getElementById('new-post-modal');
    const form = document.getElementById('add-post-form');
    
    if (modal) modal.style.display = 'none';
    if (form) form.reset();
}

