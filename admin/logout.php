<?php
/**
 * Harsha Pest Control - Admin Logout Handler
 */

define('HARSHA_APP', true);

require_once __DIR__ . '/../includes/functions.php';

init_session();

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Start fresh session to set flash message
init_session();
set_flash('info', 'You have been successfully logged out.');

header("Location: " . base_url('admin/login.php'));
exit;
