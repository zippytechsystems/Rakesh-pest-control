<?php
/**
 * Harsha Pest Control - Admin Password Change
 */

define('HARSHA_APP', true);

$currentAdminNav = 'password';
$adminTitle = 'Change Password';

require_once __DIR__ . '/admin-header.php';

$pdo = get_db_connection();
$adminId = $_SESSION['admin_user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security session expired. Please try again.');
        header("Location: " . base_url('admin/password.php'));
        exit;
    }

    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
        set_flash('error', 'All password fields are required.');
    } elseif (strlen($newPass) < 8) {
        set_flash('error', 'New password must be at least 8 characters long.');
    } elseif ($newPass !== $confirmPass) {
        set_flash('error', 'New password and confirmation do not match.');
    } else {
        try {
            $stmt = $pdo->prepare("SELECT password_hash FROM admin_users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $adminId]);
            $hash = $stmt->fetchColumn();

            if (!$hash || !password_verify($currentPass, $hash)) {
                set_flash('error', 'The current password you entered is incorrect.');
            } else {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE admin_users SET password_hash = :hash WHERE id = :id");
                $upd->execute([':hash' => $newHash, ':id' => $adminId]);

                set_flash('success', 'Your password has been changed securely.');
            }
        } catch (Exception $e) {
            set_flash('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    header("Location: " . base_url('admin/password.php'));
    exit;
}
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Change Admin Password</h1>
        <p class="admin-page-subtitle">Keep your portal access secure by using a strong, unique password.</p>
    </div>
</div>

<div class="admin-card" style="max-width: 520px; margin: 0 auto;">
    <h2 class="admin-card-title">Update Your Login Credentials</h2>

    <form method="POST" action="<?= base_url('admin/password.php') ?>" class="admin-edit-form">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="current_password" class="form-label">Current Password <span class="required">*</span></label>
            <input type="password" name="current_password" id="current_password" class="form-input" required autocomplete="current-password">
        </div>

        <div class="form-group">
            <label for="new_password" class="form-label">New Password (Min 8 Characters) <span class="required">*</span></label>
            <input type="password" name="new_password" id="new_password" class="form-input" required minlength="8" autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm New Password <span class="required">*</span></label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-input" required minlength="8" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:20px;">Update Password</button>
    </form>
</div>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
