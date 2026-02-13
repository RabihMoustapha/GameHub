<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$with = isset($_GET['with']) ? (int)$_GET['with'] : 0;

// Get list of friends for sidebar
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

include 'includes/header.php';
?>

<div class="messages-layout">
    <div class="friends-list">
        <h3>Friends</h3>
        <ul>
        <?php foreach ($friends as $friend): ?>
            <li>
                <a href="messages.php?with=<?= $friend['id'] ?>">
                    <img src="<?= BASE_URL ?>assets/uploads/avatars/<?= htmlspecialchars($friend['avatar']) ?>" class="avatar-tiny">
                    <?= htmlspecialchars($friend['username']) ?>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-area">
        <?php if ($with): ?>
            <?php
            // Get friend's name
            $stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $with);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $friend_name = mysqli_fetch_assoc($result)['username'] ?? '';
            mysqli_stmt_close($stmt);
            ?>
            <h3>Chat with <?= htmlspecialchars($friend_name) ?></h3>
            <div id="chat-messages" data-with="<?= $with ?>" style="height: 400px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;"></div>
            <form id="chat-form">
                <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        <?php else: ?>
            <p>Select a friend to start chatting.</p>
        <?php endif; ?>
    </div>
</div>

<script>
const chatWith = <?= $with ?: 'null' ?>;
if (chatWith) {
    const messagesDiv = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');

    function loadMessages() {
        fetch('fetch_messages.php?with=' + chatWith)
            .then(res => res.json())
            .then(messages => {
                let html = '';
                messages.forEach(msg => {
                    const align = msg.from_user == <?= $user_id ?> ? 'right' : 'left';
                    html += `<div style="text-align: ${align}; margin: 5px;"><strong>${msg.username}:</strong> ${msg.message}<br><small>${msg.sent_at}</small></div>`;
                });
                messagesDiv.innerHTML = html;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
    }

    form.addEventListener('submit', e => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;
        fetch('send_message.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'to=' + chatWith + '&message=' + encodeURIComponent(msg)
        })
        .then(() => {
            input.value = '';
            loadMessages();
        });
    });

    loadMessages();
    setInterval(loadMessages, 3000); // Poll every 3 sec
}
</script>

<?php include 'includes/footer.php'; ?>