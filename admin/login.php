<?php
/**
 * Harsha Pest Control - Secure Staff Login
 */

define('HARSHA_APP', true);

require_once __DIR__ . '/../includes/functions.php';

init_session();

// If already logged in, redirect to dashboard
if (is_admin_logged_in()) {
    header("Location: " . base_url('admin/index.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF
    if (!verify_csrf()) {
        $error = "Session expired. Please try logging in again.";
    } elseif (!check_rate_limit('admin_login', 5, 15)) {
        // 2. Rate Limiting Protection (5 failed logins per 15 mins)
        $error = "Too many login attempts from this network. Please wait 15 minutes before trying again.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = "Please enter both username and password.";
        } else {
            try {
                $pdo = get_db_connection();
                $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = :user LIMIT 1");
                $stmt->execute([':user' => $username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Successful login
                    session_regenerate_id(true);
                    $_SESSION['admin_user_id']  = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];

                    // Update last login
                    $upd = $pdo->prepare("UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = :id");
                    $upd->execute([':id' => $user['id']]);

                    header("Location: " . base_url('admin/index.php'));
                    exit;
                } else {
                    $error = "Invalid username or password.";
                }
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                $error = "A system error occurred. Please verify database connection.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Harsha Pest Control</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/admin.css')) ?>">
</head>
<body class="admin-login-body">

<div class="login-card-container">
    <div class="login-card">
        <div class="login-header">
            <img src="<?= e(base_url('assets/images/logo.svg')) ?>" alt="Harsha Pest Control" class="login-logo" width="60" height="60">
            <h1 class="login-title">Staff Portal Login</h1>
            <p class="login-sub">Harsha Pest Control Management</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <?php $flash = get_flash(); if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>" role="alert"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('admin/login.php') ?>" class="login-form">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-input" required autocomplete="username" autofocus value="<?= e($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Log In to Portal</button>
        </form>

        <div class="login-footer">
            <a href="<?= base_url() ?>" class="back-link">← Return to Public Website</a>
        </div>
    </div>
</div>

</body>
</html>
