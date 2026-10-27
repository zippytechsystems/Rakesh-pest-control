<?php
/**
 * Harsha Pest Control - Enquiry Submission Handler
 * 
 * Includes honeypot spam trap, rate limiting, phone format validation,
 * consent verification, CSRF verification, and prepared statement insertion.
 */

define('HARSHA_APP', true);

require_once __DIR__ . '/includes/functions.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
          (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

function respond($success, $message, $isAjax, $redirectUrl = null) {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'message' => $message
        ]);
        exit;
    } else {
        set_flash($success ? 'success' : 'error', $message);
        $target = $redirectUrl ?? base_url('contact.php');
        header("Location: " . $target);
        exit;
    }
}

// 1. Verify CSRF Token
if (!verify_csrf()) {
    respond(false, "Security session expired. Please refresh the page and try again.", $isAjax);
}

// 2. Honeypot Anti-Spam Trap
// The 'website_url' field is hidden via CSS/ARIA. Real users leave it blank.
if (!empty($_POST['website_url'])) {
    // Pretend success to fool bot without storing spam
    respond(true, "Thank you! Your enquiry has been received.", $isAjax);
}

// 3. Rate Limiting Protection (Max 5 submissions per 15 minutes per IP)
if (!check_rate_limit('enquiry', 5, 15)) {
    respond(false, "Too many enquiry requests from this network. Please wait a few minutes or call Rakesh directly at 63052 35360.", $isAjax);
}

// 4. Validate Inputs
$name     = trim($_POST['customer_name'] ?? '');
$phone    = trim($_POST['customer_phone'] ?? '');
$service  = trim($_POST['service_or_product'] ?? '');
$locality = trim($_POST['locality'] ?? '');
$message  = trim($_POST['message'] ?? '');
$consent  = isset($_POST['consent']) && ($_POST['consent'] === '1' || $_POST['consent'] === 'on');

if (empty($name) || mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    respond(false, "Please provide your full name (between 2 and 100 characters).", $isAjax);
}

// Clean phone digits
$cleanPhone = clean_phone($phone);
// If it starts with 91 and has 12 digits, strip country code
if (strlen($cleanPhone) === 12 && substr($cleanPhone, 0, 2) === '91') {
    $cleanPhone = substr($cleanPhone, 2);
}

// Validate 10-digit Indian mobile number starting with 6, 7, 8, or 9
if (!preg_match('/^[6-9]\d{9}$/', $cleanPhone)) {
    respond(false, "Please enter a valid 10-digit Indian mobile number (e.g., 63052 35360).", $isAjax);
}

if (!$consent) {
    respond(false, "Please check the consent box allowing us to store your contact number to reply to your enquiry.", $isAjax);
}

// 5. Save to MySQL Database using Prepared Statement
try {
    $pdo = get_db_connection();
    
    $stmt = $pdo->prepare("
        INSERT INTO enquiries (
            customer_name, 
            customer_phone, 
            service_or_product, 
            locality, 
            message, 
            consent_given, 
            consent_timestamp, 
            ip_address, 
            user_agent, 
            status, 
            created_at
        ) VALUES (
            :name, 
            :phone, 
            :service, 
            :locality, 
            :message, 
            1, 
            NOW(), 
            :ip, 
            :ua, 
            'new', 
            NOW()
        )
    ");

    $saved = $stmt->execute([
        ':name'     => $name,
        ':phone'    => $cleanPhone,
        ':service'  => mb_substr($service, 0, 150),
        ':locality' => mb_substr($locality, 0, 100),
        ':message'  => mb_substr($message, 0, 1000),
        ':ip'       => get_client_ip(),
        ':ua'       => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
    ]);

    if ($saved) {
        respond(
            true, 
            "Thank you, {$name}! Your enquiry has been received. Rakesh will call you shortly on {$cleanPhone} to discuss your pest control requirement.", 
            $isAjax, 
            base_url('contact.php?status=success')
        );
    } else {
        respond(false, "Could not save your enquiry at this moment. Please call us directly at 63052 35360.", $isAjax);
    }

} catch (Exception $e) {
    error_log("Enquiry Insert Error: " . $e->getMessage());
    respond(false, "A temporary server error occurred. Please call Rakesh directly at 63052 35360 for immediate assistance.", $isAjax);
}
