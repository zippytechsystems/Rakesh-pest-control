<?php
/**
 * Harsha Pest Control - Admin Shared Header
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$adminUser = $_SESSION['admin_username'] ?? 'Admin';
$currentAdminNav = $currentAdminNav ?? 'enquiries';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle ?? 'Admin Dashboard') ?> - Harsha Pest Control</title>
    <!-- Prevent search engines from indexing the admin area -->
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(base_url('assets/css/admin.css')) ?>">
</head>
<body class="admin-body">

    <header class="admin-topbar">
        <div class="admin-container admin-topbar-flex">
            <div class="admin-brand-group">
                <a href="<?= base_url('admin/index.php') ?>" class="admin-brand-link">
                    <img src="<?= e(base_url('assets/images/logo.svg')) ?>" alt="Logo" width="32" height="32">
                    <span class="admin-brand-title">Harsha Pest Control</span>
                    <span class="admin-brand-pill">Admin</span>
                </a>
            </div>

            <div class="admin-user-group">
                <span class="admin-greeting">👤 Logged in as: <strong><?= e($adminUser) ?></strong></span>
                <a href="<?= base_url() ?>" target="_blank" class="admin-ext-link" title="Open public site in new tab">View Live Website ↗</a>
                <a href="<?= base_url('admin/logout.php') ?>" class="admin-logout-btn">Log Out</a>
            </div>
        </div>
    </header>

    <nav class="admin-navbar">
        <div class="admin-container">
            <ul class="admin-nav-tabs">
                <li><a href="<?= base_url('admin/index.php') ?>" class="<?= $currentAdminNav === 'enquiries' ? 'active' : '' ?>">📋 Customer Enquiries</a></li>
                <li><a href="<?= base_url('admin/services.php') ?>" class="<?= $currentAdminNav === 'services' ? 'active' : '' ?>">🛡️ Services & Pricing</a></li>
                <li><a href="<?= base_url('admin/products.php') ?>" class="<?= $currentAdminNav === 'products' ? 'active' : '' ?>">📦 Products Catalog</a></li>
                <li><a href="<?= base_url('admin/settings.php') ?>" class="<?= $currentAdminNav === 'settings' ? 'active' : '' ?>">⚙️ Site Settings & SEO</a></li>
                <li><a href="<?= base_url('admin/password.php') ?>" class="<?= $currentAdminNav === 'password' ? 'active' : '' ?>">🔑 Change Password</a></li>
            </ul>
        </div>
    </nav>

    <main class="admin-main">
        <div class="admin-container">
            <?php $flash = get_flash(); if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?> alert-admin" role="alert">
                    <?= e($flash['message']) ?>
                </div>
            <?php endif; ?>
