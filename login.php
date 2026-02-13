<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid username/email or password.";
    }
    mysqli_stmt_close($stmt);
}
?>
<?php include 'includes/header.php'; ?>

<h2>Login</h2>
<?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username or Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

<?php include 'includes/footer.php'; ?>