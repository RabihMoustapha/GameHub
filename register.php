<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    $errors = [];

    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Check if username/email exists
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Username or email already taken.";
    }
    mysqli_stmt_close($stmt);

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Registration failed. Try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<?php include 'includes/header.php'; ?>

<h2>Register</h2>
<?php if (!empty($errors)): ?>
    <div class="error"><?= implode('<br>', $errors) ?></div>
<?php endif; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <button type="submit">Register</button>
</form>

<?php include 'includes/footer.php'; ?>