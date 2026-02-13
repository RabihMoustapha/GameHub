<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add comment
    $post_id = (int)$_POST['post_id'];
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iis", $post_id, $_SESSION['user_id'], $comment);
        mysqli_stmt_execute($stmt);
        $comment_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Fetch new comment with user details
        $stmt = mysqli_prepare($conn, "
            SELECT c.*, u.username, u.avatar
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        mysqli_stmt_bind_param($stmt, "i", $comment_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $new = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        echo json_encode(['success' => true, 'comment' => $new]);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    // Fetch comments for a post
    $post_id = (int)$_GET['post_id'];
    $stmt = mysqli_prepare($conn, "
        SELECT c.*, u.username, u.avatar
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at ASC
    ");
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    echo json_encode($comments);
    exit;
}