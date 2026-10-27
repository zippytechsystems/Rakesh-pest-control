<?php
/**
 * Harsha Pest Control - Products Page Content
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

$products = get_active_products();
$primaryPhone    = $primaryPhone ?? get_setting('primary_phone', '63052 35360');
$secondaryPhone  = $secondaryPhone ?? get_setting('secondary_phone', '88979 06391');
$whatsappNumber  = $whatsappNumber ?? get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = $whatsappEnabled ?? (int)get_setting('whatsapp_enabled', '1');
?>

<!-- Products Hero Header -->
<section class="page-hero">
    <div class="container text-center">
        <span class="page-eyebrow">Counter Pest Formulations</span>
        <h1 class="page-title">Pest Control Products Available in Nacharam</h1>
        <p class="page-lead">
            Authentic pest control products available for purchase directly at our Nacharam location. Call ahead to confirm stock availability.
        </p>
    </div>
</section>

<!-- Product Availability & Ordering Advisory -->
<section class="section-notice">
    <div class="container">
        <div class="policy-alert-card">
            <div class="alert-icon">📦</div>
            <div class="alert-content">
                <strong>Counter Purchase & Stock Availability:</strong>
                <p>
                    All listed products are available directly at our office in <strong>Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad</strong>. Because product batches move quickly, please call Rakesh at <strong>63052 35360</strong> before visiting to confirm current counter stock.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Products Stack -->
<section class="section section-products-catalog">
    <div class="container">
        <div class="products-grid-catalog">
            <?php foreach ($products as $prod): ?>
            <div class="product-item-card" id="<?= e($prod['slug']) ?>">
                <div class="product-card-top">
                    <span class="product-category-pill"><?= e($prod['category']) ?></span>
                    <span class="product-stock-pill <?= $prod['is_available'] ? 'stock-in' : 'stock-out' ?>">
                        <?= $prod['is_available'] ? 'Counter Available' : 'Call to Check Stock' ?>
                    </span>
                </div>

                <h2 class="product-item-title"><?= e($prod['name']) ?></h2>
                <div class="product-item-price"><?= e($prod['price_display']) ?></div>
                
                <p class="product-item-desc"><?= nl2br(e($prod['description'])) ?></p>

                <div class="product-card-bottom">
                    <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-block">
                        <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>Call to Order: <?= e($primaryPhone) ?></span>
                    </a>

                    <?php if ($whatsappEnabled && !empty($whatsappNumber)): ?>
                    <a href="<?= whatsapp_url($whatsappNumber, 'Hello Rakesh garu, I want to inquire about availability of product: ' . $prod['name'] . ' (' . $prod['price_display'] . ')') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-block">
                        <span>Check Stock on WhatsApp</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="product-pickup-info">
            <h3 class="pickup-title">Office Counter Collection Location</h3>
            <p class="pickup-text">
                <strong>Harsha Pest Control</strong><br>
                Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076<br>
                Primary Contact: <strong>63052 35360</strong> | Secondary: <strong>88979 06391</strong>
            </p>
        </div>
    </div>
</section>
