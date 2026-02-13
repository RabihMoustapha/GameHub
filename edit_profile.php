<?php
require_once 'includes/config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($file_info, $_FILES['avatar']['tmp_name']);
        if (in_array($mime, $allowed)) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $destination = UPLOAD_DIR_AVATAR . $filename;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                // Update avatar in DB
                $stmt = mysqli_prepare($conn, "UPDATE users SET avatar = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "si", $filename, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Update bio
    $stmt = mysqli_prepare($conn, "UPDATE users SET bio = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $bio, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: profile.php?id=' . $user_id);
    exit;
}

// Fetch current user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

include 'includes/header.php';
?>

<h2>Edit Profile</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Bio:</label><br>
    <textarea name="bio" rows="5"><?= htmlspecialchars($user['bio']) ?></textarea><br>

    <label>Avatar:</label><br>
    <input type="file" name="avatar" accept="image/*"><br>
    <small>Current: <?= htmlspecialchars($user['avatar']) ?></small><br>

    <button type="submit">Save Changes</button>
</form>

<?php include 'includes/footer.php'; ?>