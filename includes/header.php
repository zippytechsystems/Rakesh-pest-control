<?php
/**
 * Harsha Pest Control - Header Template
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

require_once __DIR__ . '/functions.php';

$currentPage = $currentPage ?? 'home';
$pageSeo = get_page_seo($currentPage);

$siteTitle       = $pageSeo['title'] ?? get_setting('site_title', 'Harsha Pest Control | 20 Years Experience | Nacharam, Hyderabad');
$metaDescription = $pageSeo['meta_description'] ?? get_setting('meta_description', '');
$canonicalUrl    = !empty($pageSeo['canonical_url']) ? $pageSeo['canonical_url'] : base_url($currentPage === 'home' ? '' : ($currentPage . '.php'));

$primaryPhone    = get_setting('primary_phone', '63052 35360');
$secondaryPhone  = get_setting('secondary_phone', '88979 06391');
$businessName    = get_setting('business_name', 'Harsha Pest Control');
$addressLine     = get_setting('address_street', 'Snehapuri Colony, Behind HDFC Bank') . ', ' . get_setting('address_locality', 'Nacharam') . ', ' . get_setting('address_city', 'Hyderabad') . ' – ' . get_setting('address_pincode', '500 076');
$whatsappNumber  = get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = (int)get_setting('whatsapp_enabled', '1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($siteTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    
    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($siteTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:site_name" content="<?= e($businessName) ?>">
    <meta property="og:locale" content="en_IN">
    <meta property="og:image" content="<?= e(base_url('assets/images/hero.jpg')) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= e(base_url('assets/images/favicon.svg')) ?>">
    <link rel="alternate icon" href="<?= e(base_url('assets/favicon.svg')) ?>">

    <!-- Google Fonts: Inter / Outfit for high legibility -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
</head>
<body>

    <!-- Top Utility Bar: Direct Phone & Verified Experience -->
    <div class="top-bar" role="region" aria-label="Quick Contact Bar">
        <div class="container top-bar-content">
            <div class="top-bar-left">
                <span class="badge-exp">★ 20 Years Experience</span>
                <span class="top-location">📍 Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad</span>
            </div>
            <div class="top-bar-right">
                <span class="top-phone-label">Direct Call:</span>
                <a href="<?= phone_href($primaryPhone) ?>" class="top-phone-link" title="Call Rakesh directly"><?= e($primaryPhone) ?></a>
                <span class="phone-separator">|</span>
                <a href="<?= phone_href($secondaryPhone) ?>" class="top-phone-link" title="Call alternate number"><?= e($secondaryPhone) ?></a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="site-header" id="siteHeader">
        <div class="container header-content">
            <a href="<?= base_url() ?>" class="brand-link" aria-label="Harsha Pest Control Home">
                <img src="<?= e(base_url('assets/images/logo.svg')) ?>" alt="Harsha Pest Control Logo" class="brand-logo" width="46" height="46">
                <div class="brand-text">
                    <span class="brand-name">Harsha Pest Control</span>
                    <span class="brand-tagline">20 Years of Reliable Service • Nacharam</span>
                </div>
            </a>

            <!-- Mobile Navigation Trigger -->
            <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Toggle navigation menu">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>

            <!-- Navigation Links -->
            <nav class="primary-nav" id="primaryNav" aria-label="Main Navigation">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="<?= base_url() ?>" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('services.php') ?>" class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>">Pest Control Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('products.php') ?>" class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>">Pest Control Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('areas.php') ?>" class="nav-link <?= $currentPage === 'areas' ? 'active' : '' ?>">Areas We Serve</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('about.php') ?>" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('contact.php') ?>" class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>">Contact</a>
                    </li>
                </ul>

                <div class="nav-cta-wrapper">
                    <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-call-nav" title="Call Rakesh directly">
                        <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>Call <?= e($primaryPhone) ?></span>
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Flash Message Container -->
    <?php $flash = get_flash(); if ($flash): ?>
        <div class="container flash-message-container">
            <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
                <?= e($flash['message']) ?>
            </div>
        </div>
    <?php endif; ?>

    <main id="mainContent">
