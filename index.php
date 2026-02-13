<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = trim($_POST['content']);
    $image = null;

    // Handle image upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($file_info, $_FILES['post_image']['tmp_name']);
        if (in_array($mime, $allowed)) {
            $ext = pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = UPLOAD_DIR_POST . $filename;
            if (move_uploaded_file($_FILES['post_image']['tmp_name'], $destination)) {
                $image = $filename;
            }
        }
    }

    if (!empty($content)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $content, $image);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: index.php');
    exit;
}

// Fetch posts (from user and friends, newest first)
$user_id = $_SESSION['user_id'];

// Get friends list (accepted)
$stmt = mysqli_prepare($conn, "
    SELECT friend_id FROM friends WHERE user_id = ? AND status = 'accepted'
    UNION
    SELECT user_id FROM friends WHERE friend_id = ? AND status = 'accepted'
");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$friends = [];
while ($row = mysqli_fetch_assoc($result)) {
    $friends[] = $row['friend_id'] ?? $row['user_id'];
}
mysqli_stmt_close($stmt);
$friends[] = $user_id; // include own posts

// Build IN clause dynamically
$placeholders = implode(',', array_fill(0, count($friends), '?'));
$types = str_repeat('i', count($friends));
$query = "
    SELECT p.*, u.username, u.avatar
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.user_id IN ($placeholders)
    ORDER BY p.created_at DESC
";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, $types, ...$friends);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include 'includes/header.php';
?>

<h2>Home Feed</h2>

<!-- Post Form -->
<form method="POST" enctype="multipart/form-data" class="post-form">
    <textarea name="content" placeholder="What's on your mind?" required></textarea><br>
    <input type="file" name="post_image" accept="image/*"><br>
    <button type="submit">Post</button>
</form>

<!-- Posts -->
<div class="posts">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($post['avatar']) ?>" class="avatar-small">
                <a href="profile.php?id=<?= $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a>
                <span class="date"><?= $post['created_at'] ?></span>
            </div>
            <div class="post-content">
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <?php if ($post['image']): ?>
                    <img src="<?= BASE_URL ?>assets/uploads/posts/<?= htmlspecialchars($post['image']) ?>" class="post-image">
                <?php endif; ?>
            </div>
            <div class="post-actions">
                <a href="#" class="comment-toggle" data-post-id="<?= $post['id'] ?>">Comment</a>
            </div>
            <div class="comments" id="comments-<?= $post['id'] ?>" style="display:none;">
                <!-- Comments will be loaded via AJAX -->
                <form class="comment-form" data-post-id="<?= $post['id'] ?>">
                    <input type="text" name="comment" placeholder="Write a comment..." required>
                    <button type="submit">Post</button>
                </form>
                <div class="comment-list"></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>