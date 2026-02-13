<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$req_id = (int)$_GET['id']; // this is the friend request record id
$stmt = mysqli_prepare($conn, "UPDATE friends SET status = 'accepted' WHERE id = ? AND friend_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $req_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: friends.php');