document.addEventListener('DOMContentLoaded', function() {
    // Toggle comment section
    document.querySelectorAll('.comment-toggle').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const commentsDiv = document.getElementById('comments-' + postId);
            if (commentsDiv.style.display === 'none') {
                commentsDiv.style.display = 'block';
                loadComments(postId);
            } else {
                commentsDiv.style.display = 'none';
            }
        });
    });

    // Handle comment form submission
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const input = this.querySelector('input[name="comment"]');
            const comment = input.value.trim();
            if (!comment) return;

            fetch('comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'post_id=' + postId + '&comment=' + encodeURIComponent(comment)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    loadComments(postId);
                }
            });
        });
    });

    function loadComments(postId) {
        fetch('comment.php?post_id=' + postId)
            .then(res => res.json())
            .then(comments => {
                let html = '';
                comments.forEach(c => {
                    html += `<div class="comment"><img src="assets/uploads/avatars/${c.avatar}" class="avatar-tiny"> <strong>${c.username}:</strong> ${c.comment} <small>${c.created_at}</small></div>`;
                });
                document.querySelector(`#comments-${postId} .comment-list`).innerHTML = html;
            });
    }
});