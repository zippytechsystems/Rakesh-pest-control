<?php
/**
 * Harsha Pest Control - Configuration Template
 * 
 * INSTRUCTIONS FOR HOSTINGER DEPLOYMENT:
 * 1. Copy this file and rename it to 'config.php' in the same directory.
 * 2. Log in to your Hostinger hPanel -> Databases -> Management.
 * 3. Copy the exact Database Name, Database Username, and MySQL Password you created.
 * 4. Replace the placeholder values below with your actual credentials.
 * 5. Set 'APP_ENV' to 'production' on your live server.
 * 6. Set a secret random string for 'SETUP_SECRET_KEY' (used once to create the first admin).
 * 
 * IMPORTANT: NEVER commit your actual 'config.php' with real passwords to GitHub!
 */

// Prevent direct script execution if accessed outside framework
defined('HARSHA_APP') or define('HARSHA_APP', true);

return [
    // -------------------------------------------------------------
    // Database Connection Settings (from Hostinger hPanel -> Databases)
    // -------------------------------------------------------------
    'db' => [
        // On Hostinger, host is usually 'localhost' or '127.0.0.1'
        'host'     => 'localhost',
        'port'     => 3306,
        'dbname'   => 'u123456789_harsha_db',     // Replace with your Hostinger DB name (e.g., u123456789_dbname)
        'username' => 'u123456789_harsha_user',   // Replace with your Hostinger DB user
        'password' => 'YOUR_STRONG_DB_PASSWORD',  // Replace with the password created in Hostinger
        'charset'  => 'utf8mb4',
    ],

    // -------------------------------------------------------------
    // Application & Domain Settings
    // -------------------------------------------------------------
    'app' => [
        // Environment: 'development' (shows detailed errors) or 'production' (hides DB errors)
        'env'      => 'production',
        
        // Full canonical URL of your website with HTTPS (no trailing slash)
        // Example: 'https://harshapestcontrol.com'
        // Leave empty string '' to auto-detect from incoming request
        'base_url' => '',

        // Setup Secret Key: Enter any random secure passphrase here.
        // Required when accessing setup.php to create the initial admin user.
        // Once setup is complete, delete setup.php from the server for maximum security.
        'setup_key' => 'CHANGE_THIS_TO_A_RANDOM_SECRET_KEY_BEFORE_SETUP',
        
        // Timezone for enquiry timestamps and logs (Indian Standard Time)
        'timezone' => 'Asia/Kolkata',
    ],

    // -------------------------------------------------------------
    // Security & Spam Prevention Configuration
    // -------------------------------------------------------------
    'security' => [
        // Maximum enquiry submissions allowed per IP within the rate limit window
        'max_enquiries_per_window' => 5,
        'rate_limit_window_minutes' => 15,

        // Maximum admin login attempts before temporary lockout
        'max_login_attempts'       => 5,
        'login_lockout_minutes'    => 15,

        // Session lifetime in seconds (default 2 hours = 7200 seconds)
        'session_lifetime'         => 7200,
    ]
];
