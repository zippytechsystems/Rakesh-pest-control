<?php
/**
 * Harsha Pest Control - Core Functions & Helpers
 * Includes security, database helpers, sanitization, and session management.
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

require_once __DIR__ . '/db.php';

/**
 * Initialize secure session
 */
function init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        $cookieParams = [
            'lifetime' => 7200, // 2 hours
            'path'     => '/',
            'domain'   => '',
            'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        session_set_cookie_params($cookieParams);
        session_start();
    }
}

/**
 * Safe HTML escaping
 */
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate or get CSRF token
 */
function csrf_token() {
    init_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate hidden CSRF form input field
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF token from POST
 */
function verify_csrf($token = null) {
    init_session();
    $token = $token ?? ($_POST['csrf_token'] ?? '');
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get Client IP Address (respecting common reverse proxy headers safely)
 */
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($parts[0]);
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
}

/**
 * Rate Limiting Checker
 * Returns true if allowed, false if limit exceeded
 */
function check_rate_limit($action, $max_attempts = 5, $window_minutes = 15) {
    try {
        $pdo = get_db_connection();
        $ip = get_client_ip();

        // Delete expired records
        $cutoff = date('Y-m-d H:i:s', time() - ($window_minutes * 60));
        $delStmt = $pdo->prepare("DELETE FROM rate_limits WHERE created_at < :cutoff");
        $delStmt->execute([':cutoff' => $cutoff]);

        // Count recent attempts
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM rate_limits WHERE ip_address = :ip AND action = :action AND created_at >= :cutoff");
        $countStmt->execute([
            ':ip'     => $ip,
            ':action' => $action,
            ':cutoff' => $cutoff
        ]);
        $attempts = (int)$countStmt->fetchColumn();

        if ($attempts >= $max_attempts) {
            return false;
        }

        // Record this attempt
        $insStmt = $pdo->prepare("INSERT INTO rate_limits (ip_address, action, created_at) VALUES (:ip, :action, NOW())");
        $insStmt->execute([
            ':ip'     => $ip,
            ':action' => $action
        ]);

        return true;
    } catch (Exception $e) {
        // If DB fails, allow to prevent locking legitimate users out in misconfig
        error_log("Rate limiting check failed: " . $e->getMessage());
        return true;
    }
}

/**
 * Get single setting with fallback
 */
function get_setting($key, $default = '') {
    static $cache = null;

    if ($cache === null) {
        try {
            $pdo = get_db_connection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
            $cache = [];
            while ($row = $stmt->fetch()) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            return $default;
        }
    }

    return $cache[$key] ?? $default;
}

/**
 * Update single setting
 */
function update_setting($key, $value) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("
        INSERT INTO settings (setting_key, setting_value) 
        VALUES (:key, :val) 
        ON DUPLICATE KEY UPDATE setting_value = :val2
    ");
    return $stmt->execute([
        ':key'  => $key,
        ':val'  => $value,
        ':val2' => $value
    ]);
}

/**
 * Get Page SEO Metadata
 */
function get_page_seo($slug) {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT title, meta_description, h1_heading, canonical_url FROM page_seo WHERE page_slug = :slug LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $data = $stmt->fetch();
        if ($data) {
            return $data;
        }
    } catch (Exception $e) {
        // Fallback below
    }

    // Default fallbacks matching confirmed business details
    return [
        'title'            => get_setting('site_title', 'Harsha Pest Control | 20 Years Experience | Nacharam, Hyderabad'),
        'meta_description' => get_setting('meta_description', 'Professional pest control in Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda & Taranaka. Call Rakesh on 63052 35360.'),
        'h1_heading'       => 'Professional Pest Control in Nacharam & Nearby Hyderabad Areas',
        'canonical_url'    => ''
    ];
}

/**
 * Get all active services for public display
 */
function get_active_services() {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get all services (for admin)
 */
function get_all_services() {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT * FROM services ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get all active products for public display
 */
function get_active_products() {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get all products (for admin)
 */
function get_all_products() {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->query("SELECT * FROM products ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Clean phone numbers to digits only
 */
function clean_phone($phone) {
    return preg_replace('/[^\d]/', '', (string)$phone);
}

/**
 * Formatted href for telephone links
 */
function phone_href($phone) {
    $clean = clean_phone($phone);
    if (strlen($clean) === 10) {
        return 'tel:+91' . $clean;
    }
    return 'tel:' . $clean;
}

/**
 * WhatsApp chat URL with pre-filled message
 */
function whatsapp_url($phone, $message = '') {
    $clean = clean_phone($phone);
    if (strlen($clean) === 10) {
        $clean = '91' . $clean;
    }
    $url = 'https://wa.me/' . $clean;
    if (!empty($message)) {
        $url .= '?text=' . urlencode($message);
    }
    return $url;
}

/**
 * Set session flash message
 */
function set_flash($type, $message) {
    init_session();
    $_SESSION['flash'] = [
        'type'    => $type, // 'success', 'error', 'info', 'warning'
        'message' => $message
    ];
}

/**
 * Get and clear session flash message
 */
function get_flash() {
    init_session();
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Base URL helper
 */
function base_url($path = '') {
    static $base = null;

    if ($base === null) {
        $configFile = __DIR__ . '/../config.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
            if (!empty($config['app']['base_url'])) {
                $base = rtrim($config['app']['base_url'], '/');
            }
        }

        if ($base === null) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
            $scriptDir = str_replace('\\', '/', $scriptDir);
            $scriptDir = rtrim($scriptDir, '/');
            $base = $protocol . $host . $scriptDir;
        }
    }

    $path = ltrim($path, '/');
    return empty($path) ? $base : $base . '/' . $path;
}

/**
 * Verify Admin Authentication
 */
function is_admin_logged_in() {
    init_session();
    return !empty($_SESSION['admin_user_id']) && !empty($_SESSION['admin_username']);
}

/**
 * Require Admin Auth or Redirect
 */
function require_admin_login() {
    if (!is_admin_logged_in()) {
        header("Location: " . base_url('admin/login.php'));
        exit;
    }
}
