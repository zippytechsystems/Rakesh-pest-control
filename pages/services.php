<?php
/**
 * Harsha Pest Control - Services Page Content
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

$services = get_active_services();
$primaryPhone    = $primaryPhone ?? get_setting('primary_phone', '63052 35360');
$secondaryPhone  = $secondaryPhone ?? get_setting('secondary_phone', '88979 06391');
$whatsappNumber  = $whatsappNumber ?? get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = $whatsappEnabled ?? (int)get_setting('whatsapp_enabled', '1');
?>

<!-- Services Hero Header -->
<section class="page-hero">
    <div class="container text-center">
        <span class="page-eyebrow">Professional Treatment Solutions</span>
        <h1 class="page-title">Pest Control Services in Nacharam & Nearby Hyderabad Areas</h1>
        <p class="page-lead">
            Targeted pest management for residences, commercial properties, and garden spaces. Backed by <strong>20 years of hands-on experience</strong> and committed written warranties.
        </p>
    </div>
</section>

<!-- Indicative Price Policy Notice -->
<section class="section-notice">
    <div class="container">
        <div class="policy-alert-card">
            <div class="alert-icon">ℹ️</div>
            <div class="alert-content">
                <strong>Important Note on Pricing & Warranties:</strong>
                <p>
                    All service rates shown below are indicative estimates for standard residential configurations. Because pest infestation depth, property construction, and square footage vary, the final price and exact scope are confirmed during a physical inspection. Warranty terms and complimentary follow-up services remain strictly honored as specified.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Services Detailed List -->
<section class="section section-services-list">
    <div class="container">
        <div class="services-detail-stack">
            <?php foreach ($services as $srv): ?>
            <article class="service-detail-card" id="<?= e($srv['slug']) ?>">
                <div class="service-detail-grid">
                    <div class="service-detail-main">
                        <div class="service-tag-row">
                            <span class="warranty-badge">🛡️ <?= e($srv['warranty_terms']) ?></span>
                            <span class="free-badge">🎁 <?= e($srv['free_service_offer']) ?></span>
                        </div>
                        <h2 class="service-detail-title"><?= e($srv['name']) ?></h2>
                        <p class="service-detail-desc"><?= nl2br(e($srv['description'])) ?></p>

                        <div class="service-detail-highlights">
                            <div class="highlight-item">
                                <span class="hl-label">Indicative Pricing:</span>
                                <span class="hl-value hl-price"><?= e($srv['indicative_price']) ?></span>
                            </div>
                            <div class="highlight-item">
                                <span class="hl-label">Scope Notice:</span>
                                <span class="hl-value"><?= e($srv['price_note']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="service-detail-sidebar">
                        <div class="sidebar-box">
                            <h3 class="sidebar-box-title">Ready to Treat?</h3>
                            <p class="sidebar-box-text">Call Rakesh directly or schedule an on-site property inspection.</p>
                            
                            <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-block">
                                <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>Call <?= e($primaryPhone) ?></span>
                            </a>
                            
                            <a href="<?= base_url('contact.php?service=' . urlencode($srv['name'])) ?>" class="btn btn-outline btn-block">
                                <span>Book Inspection</span>
                            </a>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Service FAQs -->
<section class="section section-faqs">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-eyebrow">Helpful Answers</span>
            <h2 class="section-title">Common Questions About Our Services</h2>
        </div>

        <div class="faq-accordion">
            <details class="faq-item">
                <summary class="faq-question">How does the free follow-up service work?</summary>
                <div class="faq-answer">
                    <p>
                        For qualifying treatments with written warranties (such as 2-year cockroach or 5-year termite protection), if persistent pest activity is observed during the warranty period, we provide one scheduled service visit free of charge to re-apply treatment to affected zones.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary class="faq-question">Do I need to vacate my house during cockroach treatment?</summary>
                <div class="faq-answer">
                    <p>
                        Our primary indoor cockroach treatment uses specialized odorless herbal gels and targeted bait placements. You do not need to empty kitchen containers or leave the home. For severe cases requiring spray application in drainage channels, standard brief ventilation is recommended.
                    </p>
                </div>
            </details>

            <details class="faq-item">
                <summary class="faq-question">How soon can Rakesh visit for an inspection?</summary>
                <div class="faq-answer">
                    <p>
                        Since our office is based locally in Snehapuri Colony, Nacharam, we can typically schedule same-day or next-day visits for Nacharam, HMT Nagar, Habsiguda, and Taranaka. Call directly at 63052 35360 to confirm the next available time slot.
                    </p>
                </div>
            </details>
        </div>
    </div>
</section>
