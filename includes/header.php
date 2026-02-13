<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>" class="logo">GameHub</a>
            <ul class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?= BASE_URL ?>">Home</a></li>
                    <li><a href="<?= BASE_URL ?>games.php">Games</a></li>
                    <li><a href="<?= BASE_URL ?>friends.php">Friends</a></li>
                    <li><a href="<?= BASE_URL ?>messages.php">Messages</a></li>
                    <li><a href="<?= BASE_URL ?>profile.php?id=<?= $_SESSION['user_id'] ?>">Profile</a></li>
                    <li><a href="<?= BASE_URL ?>logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>login.php">Login</a></li>
                    <li><a href="<?= BASE_URL ?>register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">