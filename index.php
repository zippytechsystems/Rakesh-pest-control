<?php
/**
 * Harsha Pest Control - Main Website Controller & Entry Point
 * 
 * Supports both clean URL routing and direct page loads.
 */

define('HARSHA_APP', true);

require_once __DIR__ . '/includes/functions.php';

// Determine requested page
$allowedPages = [
    'home'     => 'pages/home.php',
    'services' => 'pages/services.php',
    'products' => 'pages/products.php',
    'areas'    => 'pages/areas.php',
    'about'    => 'pages/about.php',
    'contact'  => 'pages/contact.php',
    'privacy'  => 'pages/privacy.php',
];

// Check if page was set before including index.php or passed as GET param
if (empty($currentPage)) {
    $requested = $_GET['page'] ?? 'home';
    $currentPage = array_key_exists($requested, $allowedPages) ? $requested : 'home';
}

$pageFile = $allowedPages[$currentPage] ?? 'pages/home.php';

// Check if configuration exists or if user needs setup
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    // Show a clean, helpful guide to copy config.example.php
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Setup Required - Harsha Pest Control</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 20px; text-align: center; }
            .box { max-width: 650px; margin: 40px auto; background: #fff; padding: 36px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); text-align: left; }
            h1 { color: #047857; margin-top: 0; }
            code { background: #f1f5f9; padding: 3px 6px; border-radius: 4px; font-size: 0.9em; }
            .btn { display: inline-block; background: #047857; color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 15px; }
            ol { padding-left: 20px; line-height: 1.7; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1>Harsha Pest Control - Initial Setup Required</h1>
            <p>Welcome! The website code is installed, but <code>config.php</code> has not been created yet.</p>
            <ol>
                <li>In Hostinger File Manager, copy <code>config.example.php</code> and rename it to <code>config.php</code>.</li>
                <li>Enter your Hostinger MySQL database name, database user, and password in <code>config.php</code>.</li>
                <li>Import <code>schema.sql</code> via Hostinger phpMyAdmin.</li>
                <li>Visit <a href="setup.php" class="btn">Run Admin Setup (setup.php)</a> to create your primary login.</li>
            </ol>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Render Page
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/' . $pageFile;
require_once __DIR__ . '/includes/footer.php';
