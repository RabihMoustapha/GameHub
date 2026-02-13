<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM games");
$games = mysqli_fetch_all($result, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<h2>Game Library</h2>
<div class="game-grid">
    <?php foreach ($games as $game): ?>
        <div class="game-card">
            <h3><?= htmlspecialchars($game['title']) ?></h3>
            <p><?= htmlspecialchars($game['description']) ?></p>
            <a href="game.php?id=<?= $game['id'] ?>" class="btn">Play</a>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>