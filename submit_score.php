<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = (int)$_POST['game_id'];
    $score = (int)$_POST['score'];
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "INSERT INTO scores (user_id, game_id, score) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $game_id, $score);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to save score']);
    }
    mysqli_stmt_close($stmt);
}