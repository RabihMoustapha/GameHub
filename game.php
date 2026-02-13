<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$game_id = (int)$_GET['id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM games WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$game = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$game) {
    die("Game not found.");
}

// Fetch top scores for this game
$stmt = mysqli_prepare($conn, "
    SELECT s.score, u.username, u.avatar
    FROM scores s
    JOIN users u ON s.user_id = u.id
    WHERE s.game_id = ?
    ORDER BY s.score DESC
    LIMIT 10
");
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$scores = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include 'includes/header.php';
?>

<h2><?= htmlspecialchars($game['title']) ?></h2>
<div class="game-container">
    <iframe src="<?= BASE_URL . $game['file_path'] ?>" frameborder="0" width="800" height="600"></iframe>
</div>

<h3>Leaderboard</h3>
<table>
    <tr><th>User</th><th>Score</th></tr>
    <?php foreach ($scores as $score): ?>
    <tr>
        <td><img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($score['avatar']) ?>" class="avatar-tiny"> <?= htmlspecialchars($score['username']) ?></td>
        <td><?= (int)$score['score'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
// Function to submit score (called from game iframe)
function submitScore(score) {
    fetch('submit_score.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'game_id=<?= $game_id ?>&score=' + encodeURIComponent(score)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Score submitted!');
            location.reload(); // refresh leaderboard
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>