<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get accepted friends
$stmt = mysqli_prepare($conn, "
    SELECT u.id, u.username, u.avatar
    FROM friends f
    JOIN users u ON (f.friend_id = u.id AND f.user_id = ?)
    WHERE f.status = 'accepted'
    UNION
    SELECT u.id, u.username, u.avatar
    FROM friends f
    JOIN users u ON (f.user_id = u.id AND f.friend_id = ?)
    WHERE f.status = 'accepted'
");
mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$friends = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get pending requests sent to me
$stmt = mysqli_prepare($conn, "
    SELECT f.id as req_id, u.id, u.username, u.avatar
    FROM friends f
    JOIN users u ON f.user_id = u.id
    WHERE f.friend_id = ? AND f.status = 'pending'
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pending_requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include 'includes/header.php';
?>

<h2>Friends</h2>

<h3>Search Users</h3>
<input type="text" id="search" placeholder="Search by username">
<div id="search-results"></div>

<h3>Pending Friend Requests</h3>
<?php if ($pending_requests): ?>
    <ul>
    <?php foreach ($pending_requests as $req): ?>
        <li>
            <img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($req['avatar']) ?>" class="avatar-tiny">
            <?= htmlspecialchars($req['username']) ?>
            <a href="accept_friend.php?id=<?= $req['req_id'] ?>">Accept</a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No pending requests.</p>
<?php endif; ?>

<h3>Your Friends</h3>
<?php if ($friends): ?>
    <ul>
    <?php foreach ($friends as $friend): ?>
        <li>
            <img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($friend['avatar']) ?>" class="avatar-tiny">
            <a href="profile.php?id=<?= $friend['id'] ?>"><?= htmlspecialchars($friend['username']) ?></a>
            <a href="messages.php?with=<?= $friend['id'] ?>">Message</a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have no friends yet.</p>
<?php endif; ?>

<script>
document.getElementById('search').addEventListener('input', function() {
    let query = this.value;
    if (query.length < 2) return;
    fetch('search_users.php?q=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(users => {
            let html = '';
            users.forEach(u => {
                html += `<div><img src="<?= BASE_URL ?>assets/uploads/avatars/${u.avatar}" class="avatar-tiny"> ${u.username} <a href="add_friend.php?id=${u.id}">Add Friend</a></div>`;
            });
            document.getElementById('search-results').innerHTML = html;
        });
});
</script>

<?php include 'includes/footer.php'; ?>