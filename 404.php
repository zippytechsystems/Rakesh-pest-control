<?php
/**
 * Harsha Pest Control - Custom 404 Page
 */

define('HARSHA_APP', true);

http_response_code(404);

$currentPage = 'home';
$adminTitle = 'Page Not Found';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section text-center" style="padding: 90px 20px;">
    <div class="container container-readable">
        <span class="section-eyebrow">404 Error</span>
        <h1 class="section-title" style="font-size: 2.75rem; margin-top: 12px;">Page Not Found</h1>
        <p class="section-subtitle" style="margin-bottom: 32px;">
            The page you are looking for may have been moved, renamed, or is temporarily unavailable.
        </p>

        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="<?= base_url() ?>" class="btn btn-primary btn-lg">Return to Home</a>
            <a href="<?= base_url('services.php') ?>" class="btn btn-outline btn-lg">View Services</a>
            <a href="<?= base_url('contact.php') ?>" class="btn btn-secondary btn-lg">Contact Rakesh</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
