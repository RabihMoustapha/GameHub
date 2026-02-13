<?php
require_once 'includes/config.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    die("User not found.");
}

// Fetch user's scores with game titles
$stmt = mysqli_prepare($conn, "
    SELECT g.title, s.score, s.achieved_at
    FROM scores s
    JOIN games g ON s.game_id = g.id
    WHERE s.user_id = ?
    ORDER BY s.achieved_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$scores = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include 'includes/header.php';
?>

<div class="profile-header">
    <img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="avatar-large">
    <h2><?= htmlspecialchars($user['username']) ?></h2>
    <p class="bio"><?= nl2br(htmlspecialchars($user['bio'] ?? 'No bio yet.')) ?></p>
    <?php if ($user_id == $_SESSION['user_id']): ?>
        <a href="edit_profile.php" class="btn">Edit Profile</a>
    <?php endif; ?>
</div>

<h3>Game Stats</h3>
<?php if (count($scores) > 0): ?>
    <table>
        <tr><th>Game</th><th>Score</th><th>Achieved</th></tr>
        <?php foreach ($scores as $score): ?>
        <tr>
            <td><?= htmlspecialchars($score['title']) ?></td>
            <td><?= (int)$score['score'] ?></td>
            <td><?= $score['achieved_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No scores yet. Play some games!</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>