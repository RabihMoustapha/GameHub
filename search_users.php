<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) exit;

$q = '%' . $_GET['q'] . '%';
$stmt = mysqli_prepare($conn, "SELECT id, username, avatar FROM users WHERE username LIKE ? AND id != ? LIMIT 10");
mysqli_stmt_bind_param($stmt, "si", $q, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
echo json_encode($users);