<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    include 'admin_credentials.php';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;

        setcookie("admin_username", $username, time() + 86400, "/");

        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Öztürk – Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="admin-login-main">
        <div class="card admin-login-card">

            <h2 class="admin-login-title">Admin Access</h2>

            <?php if ($error_message): ?>
                <p class="error-message">
                    <?php echo $error_message; ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="admin_login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>

            <div class="back-btn">
                <a href="index.php" class="github-link">← Back to Portfolio</a>
            </div>

        </div>
    </main>
</body>
</html>