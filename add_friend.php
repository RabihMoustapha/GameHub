<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$friend_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if not already friends or pending
$stmt = mysqli_prepare($conn, "SELECT id FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
mysqli_stmt_bind_param($stmt, "iiii", $user_id, $friend_id, $friend_id, $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) == 0) {
    mysqli_stmt_close($stmt);
    $stmt = mysqli_prepare($conn, "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $friend_id);
    mysqli_stmt_execute($stmt);
}
mysqli_stmt_close($stmt);
header('Location: friends.php');