<?php
/**
 * Harsha Pest Control - Footer Template & Schema Markup
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

$primaryPhone    = get_setting('primary_phone', '63052 35360');
$secondaryPhone  = get_setting('secondary_phone', '88979 06391');
$businessName    = get_setting('business_name', 'Harsha Pest Control');
$addressStreet   = get_setting('address_street', 'Snehapuri Colony, Behind HDFC Bank');
$addressLocality = get_setting('address_locality', 'Nacharam');
$addressCity     = get_setting('address_city', 'Hyderabad');
$addressPincode  = get_setting('address_pincode', '500 076');
$whatsappNumber  = get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = (int)get_setting('whatsapp_enabled', '1');
?>
    </main>

    <!-- Footer Section -->
    <footer class="site-footer">
        <div class="container footer-grid">
            <!-- Col 1: About & Experience -->
            <div class="footer-col footer-col-brand">
                <div class="footer-brand">
                    <img src="<?= e(base_url('assets/images/logo.svg')) ?>" alt="Harsha Pest Control" width="40" height="40" class="footer-logo">
                    <span class="footer-title"><?= e($businessName) ?></span>
                </div>
                <p class="footer-text">
                    Serving Nacharam and nearby Hyderabad localities for over <strong>20 years</strong>. Owned and personally supervised by <strong>Rakesh</strong>, providing reliable residential and commercial pest management.
                </p>
                <div class="footer-contact-block">
                    <p class="footer-address">
                        <strong>Office Location:</strong><br>
                        <?= e($addressStreet) ?>,<br>
                        <?= e($addressLocality) ?>, <?= e($addressCity) ?> – <?= e($addressPincode) ?>
                    </p>
                </div>
            </div>

            <!-- Col 2: Services -->
            <div class="footer-col">
                <h2 class="footer-heading">Pest Control Services</h2>
                <ul class="footer-links">
                    <li><a href="<?= base_url('services.php#cockroach-control') ?>">Cockroach Control (2-Yr Warranty)</a></li>
                    <li><a href="<?= base_url('services.php#termite-control') ?>">Termite Control (5-Yr Warranty)</a></li>
                    <li><a href="<?= base_url('services.php#mosquito-control') ?>">Mosquito Control (6-Mo Warranty)</a></li>
                    <li><a href="<?= base_url('services.php#rat-control') ?>">Rat & Rodent Control (2-Yr Warranty)</a></li>
                    <li><a href="<?= base_url('services.php#bed-bug-control') ?>">Bed Bug Treatment</a></li>
                    <li><a href="<?= base_url('services.php#spider-control') ?>">Spider Control</a></li>
                </ul>
            </div>

            <!-- Col 3: Products -->
            <div class="footer-col">
                <h2 class="footer-heading">Pest Control Products</h2>
                <ul class="footer-links">
                    <li><a href="<?= base_url('products.php#cockroach-gel') ?>">Cockroach Gel (₹550)</a></li>
                    <li><a href="<?= base_url('products.php#cockroach-spray') ?>">Cockroach Spray Bottle (₹1,200)</a></li>
                    <li><a href="<?= base_url('products.php#rat-medicine') ?>">Rat Medicine (₹250)</a></li>
                    <li><a href="<?= base_url('products.php#snake-medicine') ?>">Snake Medicine (₹1,800)</a></li>
                    <li><a href="<?= base_url('products.php#termite-spray') ?>">Termite Spray Bottle (₹2,000)</a></li>
                </ul>
                <p class="footer-note">* Call to confirm counter availability before visiting.</p>
            </div>

            <!-- Col 4: Service Areas & Direct Contact -->
            <div class="footer-col">
                <h2 class="footer-heading">Areas We Serve</h2>
                <ul class="footer-areas-list">
                    <li><span>📍</span> Snehapuri Colony (Office)</li>
                    <li><span>📍</span> Nacharam</li>
                    <li><span>📍</span> HMT Nagar</li>
                    <li><span>📍</span> Habsiguda</li>
                    <li><span>📍</span> Taranaka</li>
                    <li><span>📍</span> Nearby Hyderabad Areas</li>
                </ul>
                <div class="footer-cta-box">
                    <span class="cta-box-label">Direct Contact:</span>
                    <a href="<?= phone_href($primaryPhone) ?>" class="footer-phone-btn"><?= e($primaryPhone) ?></a>
                    <a href="<?= phone_href($secondaryPhone) ?>" class="footer-phone-btn-alt"><?= e($secondaryPhone) ?></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-content">
                <p class="copyright-text">
                    &copy; <?= date('Y') ?> <?= e($businessName) ?>. All rights reserved. 20 Years of Trusted Service in Nacharam, Hyderabad.
                </p>
                <div class="footer-meta-links">
                    <a href="<?= base_url('privacy.php') ?>">Privacy Policy</a>
                    <span class="sep">•</span>
                    <a href="<?= base_url('areas.php') ?>">Areas We Serve</a>
                    <span class="sep">•</span>
                    <a href="<?= base_url('admin/login.php') ?>" class="admin-login-link" rel="nofollow">Staff Login</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Quick-Action Sticky Bar -->
    <aside class="mobile-action-bar" aria-label="Quick Actions">
        <a href="<?= phone_href($primaryPhone) ?>" class="bar-btn bar-btn-call" aria-label="Call Rakesh directly">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            <span>Call Now</span>
        </a>
        
        <?php if ($whatsappEnabled && !empty($whatsappNumber)): ?>
        <a href="<?= whatsapp_url($whatsappNumber, 'Hello Rakesh garu, I need pest control service for my property.') ?>" target="_blank" rel="noopener noreferrer" class="bar-btn bar-btn-whatsapp" aria-label="Enquire via WhatsApp">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.586 1.838.892 2.796.893 3.182 0 5.77-2.587 5.771-5.768 0-3.181-2.588-5.778-5.771-5.778zm3.385 8.167c-.145.409-.834.774-1.161.823-.326.05-.752.072-2.378-.588-1.956-.795-3.212-2.8-3.309-2.932-.097-.133-.79-1.052-.79-2.008 0-.956.497-1.427.674-1.621.176-.193.388-.242.518-.242.13 0 .258.002.37.008.117.006.275-.045.43.328.158.384.542 1.325.589 1.423.048.097.08.21.016.338-.064.129-.096.21-.192.323-.096.113-.203.252-.29.338-.097.097-.198.202-.085.396.113.193.504.832 1.082 1.347.744.662 1.37.868 1.564.965.194.097.307.081.42-.048.113-.13.484-.565.613-.758.129-.194.258-.162.435-.097.177.065 1.13.533 1.324.63.194.097.323.145.371.226.049.081.049.468-.096.877z"/></svg>
            <span>WhatsApp</span>
        </a>
        <?php endif; ?>

        <a href="<?= base_url('contact.php') ?>" class="bar-btn bar-btn-enquire" aria-label="Book a property inspection">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            <span>Book Visit</span>
        </a>
    </aside>

    <!-- Schema.org JSON-LD LocalBusiness Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "<?= e($businessName) ?>",
      "image": "<?= e(base_url('assets/images/hero.jpg')) ?>",
      "logo": "<?= e(base_url('assets/images/logo.svg')) ?>",
      "description": "Professional pest control services in Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda, and Taranaka with 20 years of experience.",
      "url": "<?= e(base_url()) ?>",
      "telephone": ["+916305235360", "+918897906391"],
      "priceRange": "₹₹",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= e($addressStreet) ?>",
        "addressLocality": "<?= e($addressLocality) ?>",
        "addressRegion": "Telangana",
        "postalCode": "500076",
        "addressCountry": "IN"
      },
      "founder": {
        "@type": "Person",
        "name": "Rakesh"
      },
      "areaServed": [
        {
          "@type": "AdministrativeArea",
          "name": "Nacharam"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Snehapuri Colony"
        },
        {
          "@type": "AdministrativeArea",
          "name": "HMT Nagar"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Habsiguda"
        },
        {
          "@type": "AdministrativeArea",
          "name": "Taranaka"
        },
        {
          "@type": "City",
          "name": "Hyderabad"
        }
      ]
    }
    </script>

    <!-- Main JS Application Logic -->
    <script src="<?= e(base_url('assets/js/main.js')) ?>"></script>
</body>
</html>
