<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) exit;

$from = $_SESSION['user_id'];
$to = (int)$_POST['to'];
$message = trim($_POST['message']);

if (!empty($message)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO messages (from_user, to_user, message) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iis", $from, $to, $message);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}