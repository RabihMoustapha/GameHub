<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];
$with = (int)$_GET['with'];

$stmt = mysqli_prepare($conn, "
    SELECT m.*, u.username
    FROM messages m
    JOIN users u ON m.from_user = u.id
    WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?)
    ORDER BY sent_at ASC
");
mysqli_stmt_bind_param($stmt, "iiii", $user_id, $with, $with, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
echo json_encode($messages);