<?php
/**
 * Harsha Pest Control - Initial Admin Setup Script
 * 
 * SECURITY RULES:
 * 1. Only runs if ZERO admin accounts exist in the database.
 * 2. As soon as an admin account is created, this route is automatically locked.
 * 3. Once setup completes, DELETE this file from your Hostinger public_html folder.
 */

define('HARSHA_APP', true);

$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    die("Error: config.php not found. Please copy config.example.php to config.php and set your Hostinger database credentials first.");
}

$config = require $configFile;
require_once __DIR__ . '/includes/functions.php';

$error = '';
$success = '';
$isLocked = false;

try {
    $pdo = get_db_connection();
    
    // Check if tables exist
    $checkTable = $pdo->query("SHOW TABLES LIKE 'admin_users'")->fetch();
    if (!$checkTable) {
        die("Error: Table 'admin_users' does not exist yet. Please import 'schema.sql' using Hostinger phpMyAdmin before running setup.");
    }

    // Check if any admin already exists
    $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    if ($count > 0) {
        $isLocked = true;
    }
} catch (Exception $e) {
    die("Database Connection Error: " . htmlspecialchars($e->getMessage()));
}

// Handle Form Submission
if (!$isLocked && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $setupKey = trim($_POST['setup_key'] ?? '');

    $expectedKey = $config['app']['setup_key'] ?? '';

    // If setup_key is configured in config.php and not default, verify it
    if (!empty($expectedKey) && $expectedKey !== 'CHANGE_THIS_TO_A_RANDOM_SECRET_KEY_BEFORE_SETUP') {
        if ($setupKey !== $expectedKey) {
            $error = "Incorrect Setup Secret Key. Please enter the setup_key configured in your config.php.";
        }
    }

    if (empty($error)) {
        if (strlen($username) < 4 || !preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            $error = "Username must be at least 4 characters and contain only letters, numbers, underscores, or dashes.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long for strong security.";
        } elseif ($password !== $confirm) {
            $error = "The password and confirmation password do not match.";
        } else {
            // Hash password securely
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, created_at) VALUES (:user, :email, :hash, NOW())");
                $stmt->execute([
                    ':user'  => $username,
                    ':email' => $email,
                    ':hash'  => $hash
                ]);

                $success = "Initial Administrator account '{$username}' has been successfully created!";
                $isLocked = true;
            } catch (Exception $e) {
                $error = "Failed to create administrator: " . $e->getMessage();
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
    <title>First Admin Setup - Harsha Pest Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #047857;
            --primary-dark: #065f46;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --danger: #dc2626;
            --success: #16a34a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text); padding: 40px 20px; line-height: 1.5; }
        .setup-container { max-width: 520px; margin: 0 auto; background: var(--surface); padding: 36px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border: 1px solid var(--border); }
        .setup-logo { text-align: center; margin-bottom: 24px; }
        .setup-logo img { width: 56px; height: 56px; }
        h1 { font-size: 1.5rem; text-align: center; margin-bottom: 8px; color: var(--primary); }
        .setup-sub { text-align: center; color: var(--text-muted); font-size: 0.95rem; margin-bottom: 28px; }
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-danger { background: #fef2f2; color: var(--danger); border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .alert-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 6px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 1rem; transition: border-color 0.2s; }
        input:focus { outline: none; border-color: var(--primary); }
        .help-text { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }
        .btn-submit { width: 100%; background: var(--primary); color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: var(--primary-dark); }
        .btn-login { display: inline-block; background: var(--primary); color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; margin-top: 12px; }
        .lock-box { text-align: center; padding: 20px 0; }
        .lock-icon { font-size: 3rem; margin-bottom: 12px; }
        .step-list { margin: 16px 0; padding-left: 20px; font-size: 0.9rem; color: var(--text-muted); }
        .step-list li { margin-bottom: 6px; }
    </style>
</head>
<body>

<div class="setup-container">
    <div class="setup-logo">
        <img src="assets/images/logo.svg" alt="Harsha Pest Control">
    </div>

    <?php if ($isLocked && empty($success)): ?>
        <div class="lock-box">
            <div class="lock-icon">🔒</div>
            <h1>Setup is Locked</h1>
            <p class="setup-sub">An administrator account is already configured in the database.</p>
            
            <div class="alert alert-warning">
                <strong>Security Notice:</strong> For maximum security, please <strong>delete setup.php</strong> from your Hostinger public_html folder using File Manager.
            </div>

            <a href="admin/login.php" class="btn-login">Go to Staff Login →</a>
        </div>
    <?php elseif (!empty($success)): ?>
        <div class="lock-box">
            <div class="lock-icon">✅</div>
            <h1>Setup Completed!</h1>
            
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>

            <div class="alert alert-warning">
                <strong>Next Crucial Security Step:</strong><br>
                Please log in to Hostinger File Manager and <strong>DELETE <code>setup.php</code></strong> immediately so no one else can access this page.
            </div>

            <a href="admin/login.php" class="btn-login">Proceed to Admin Login →</a>
        </div>
    <?php else: ?>
        <h1>Create Initial Administrator</h1>
        <p class="setup-sub">Set up your secure master login for Harsha Pest Control.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="setup.php">
            <?php if (!empty($config['app']['setup_key']) && $config['app']['setup_key'] !== 'CHANGE_THIS_TO_A_RANDOM_SECRET_KEY_BEFORE_SETUP'): ?>
            <div class="form-group">
                <label for="setup_key">Setup Secret Key <span style="color:var(--danger)">*</span></label>
                <input type="password" name="setup_key" id="setup_key" required placeholder="Found in config.php setup_key">
                <p class="help-text">Enter the setup secret key configured in your private config.php file.</p>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Admin Username <span style="color:var(--danger)">*</span></label>
                <input type="text" name="username" id="username" required minlength="4" value="<?= htmlspecialchars($_POST['username'] ?? 'rakesh_admin') ?>">
                <p class="help-text">Minimum 4 characters (letters, numbers, underscore).</p>
            </div>

            <div class="form-group">
                <label for="email">Owner Email (Optional)</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="owner@example.com">
                <p class="help-text">Used for system notification references.</p>
            </div>

            <div class="form-group">
                <label for="password">Strong Admin Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password" id="password" required minlength="8" placeholder="At least 8 characters">
                <p class="help-text">Use a mix of uppercase, numbers, and symbols.</p>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="confirm_password" id="confirm_password" required minlength="8" placeholder="Re-enter password">
            </div>

            <button type="submit" class="btn-submit">Create Admin Account</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
