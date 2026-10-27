<?php
/**
 * Harsha Pest Control - Contact Us Page Content
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

$prefilledService = trim($_GET['service'] ?? '');
$prefilledLocality = trim($_GET['locality'] ?? '');
$statusSuccess = (isset($_GET['status']) && $_GET['status'] === 'success');

$primaryPhone    = $primaryPhone ?? get_setting('primary_phone', '63052 35360');
$secondaryPhone  = $secondaryPhone ?? get_setting('secondary_phone', '88979 06391');
$whatsappNumber  = $whatsappNumber ?? get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = $whatsappEnabled ?? (int)get_setting('whatsapp_enabled', '1');
?>

<!-- Contact Hero Header -->
<section class="page-hero">
    <div class="container text-center">
        <span class="page-eyebrow">Direct Contact & Scheduling</span>
        <h1 class="page-title">Contact Harsha Pest Control</h1>
        <p class="page-lead">
            Speak directly with owner <strong>Rakesh</strong> to book an inspection or inquire about treatment plans across Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda, and Taranaka.
        </p>
    </div>
</section>

<!-- Main Contact Section -->
<section class="section section-contact-main">
    <div class="container">
        <?php if ($statusSuccess): ?>
        <div class="alert alert-success alert-prominent" role="alert">
            <h3 class="alert-title">✓ Thank You! Your Inspection Request Has Been Received.</h3>
            <p class="alert-desc">
                Rakesh will personally review your request and call you back shortly on your mobile number to confirm an inspection time slot.
            </p>
        </div>
        <?php endif; ?>

        <div class="contact-two-column-layout">
            <!-- Left Column: Direct Phone & Office Details -->
            <div class="contact-details-column">
                <div class="contact-card-premium">
                    <span class="contact-card-tag">Direct Owner Contact</span>
                    <h2 class="contact-card-title">Call or Visit Our Office</h2>
                    <p class="contact-card-sub">
                        No intermediate call center agents. You connect directly with Rakesh for honest advice and fast dispatch.
                    </p>

                    <div class="direct-phone-stack">
                        <a href="<?= phone_href($primaryPhone) ?>" class="phone-call-card primary-phone-card">
                            <div class="phone-icon-box">📞</div>
                            <div class="phone-info">
                                <span class="phone-lbl">Primary Number (Direct Call):</span>
                                <span class="phone-num"><?= e($primaryPhone) ?></span>
                                <span class="phone-action">Tap to call Rakesh directly</span>
                            </div>
                        </a>

                        <a href="<?= phone_href($secondaryPhone) ?>" class="phone-call-card secondary-phone-card">
                            <div class="phone-icon-box">📱</div>
                            <div class="phone-info">
                                <span class="phone-lbl">Alternate Number:</span>
                                <span class="phone-num"><?= e($secondaryPhone) ?></span>
                                <span class="phone-action">Tap to call secondary line</span>
                            </div>
                        </a>

                        <?php if ($whatsappEnabled && !empty($whatsappNumber)): ?>
                        <a href="<?= whatsapp_url($whatsappNumber, 'Hello Rakesh garu, I need pest control service for my property.') ?>" target="_blank" rel="noopener noreferrer" class="phone-call-card whatsapp-call-card">
                            <div class="phone-icon-box">💬</div>
                            <div class="phone-info">
                                <span class="phone-lbl">WhatsApp Enquiry:</span>
                                <span class="phone-num"><?= e($whatsappNumber) ?></span>
                                <span class="phone-action">Chat with Rakesh on WhatsApp</span>
                            </div>
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="location-detail-block">
                        <div class="loc-icon">📍</div>
                        <div class="loc-body">
                            <strong>Physical Office Address:</strong>
                            <p>
                                Harsha Pest Control<br>
                                Snehapuri Colony, Behind HDFC Bank,<br>
                                Nacharam, Hyderabad – 500 076
                            </p>
                        </div>
                    </div>

                    <div class="service-zones-block">
                        <div class="loc-icon">🗺️</div>
                        <div class="loc-body">
                            <strong>Confirmed Doorstep Service Areas:</strong>
                            <p>
                                HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Inspection Booking Form -->
            <div class="contact-form-column">
                <div class="contact-form-panel">
                    <h2 class="form-panel-title">Send An Inspection Request</h2>
                    <p class="form-panel-sub">
                        Submit your details below and we will call you back to confirm property inspection availability.
                    </p>

                    <form action="<?= base_url('submit-enquiry.php') ?>" method="POST" class="pest-enquiry-form" id="contactPageForm" novalidate>
                        <?= csrf_field() ?>

                        <!-- Anti-Spam Honeypot Field -->
                        <div class="honeypot-field" style="display:none !important;" aria-hidden="true">
                            <label for="contact_website_url">Leave this empty</label>
                            <input type="text" name="website_url" id="contact_website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="c_customer_name" class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="customer_name" id="c_customer_name" class="form-input" placeholder="e.g., Ramesh Kumar" required minlength="2" maxlength="100">
                        </div>

                        <div class="form-group">
                            <label for="c_customer_phone" class="form-label">10-Digit Mobile Number <span class="required">*</span></label>
                            <input type="tel" name="customer_phone" id="c_customer_phone" class="form-input" placeholder="e.g., 63052 35360" required pattern="[0-9]{10}">
                            <small class="form-help">Your number will only be used to call you back regarding your pest control request.</small>
                        </div>

                        <div class="form-group">
                            <label for="c_service_or_product" class="form-label">Service or Product Required</label>
                            <select name="service_or_product" id="c_service_or_product" class="form-select">
                                <option value="">-- Select Your Pest Concern --</option>
                                <option value="Cockroach Control" <?= ($prefilledService === 'Cockroach Control' || stripos($prefilledService, 'cockroach') !== false) ? 'selected' : '' ?>>Cockroach Control (2 BHK ₹1,500 indicative / 2-Yr Warranty)</option>
                                <option value="Termite Control" <?= ($prefilledService === 'Termite Control' || stripos($prefilledService, 'termite') !== false) ? 'selected' : '' ?>>Termite Control (2 BHK ₹4,500 indicative / 5-Yr Warranty)</option>
                                <option value="Mosquito Control" <?= ($prefilledService === 'Mosquito Control' || stripos($prefilledService, 'mosquito') !== false) ? 'selected' : '' ?>>Mosquito Control (Outdoor ₹2,500 indicative / 6-Mo Warranty)</option>
                                <option value="Rat Control" <?= ($prefilledService === 'Rat Control' || stripos($prefilledService, 'rat') !== false) ? 'selected' : '' ?>>Rat & Rodent Control (Garden/Cellar ₹3,500 indicative / 2-Yr Warranty)</option>
                                <option value="Bed Bug Control" <?= ($prefilledService === 'Bed Bug Control' || stripos($prefilledService, 'bed bug') !== false) ? 'selected' : '' ?>>Bed Bug Control (Property Inspection & Custom Quote)</option>
                                <option value="Spider Control" <?= ($prefilledService === 'Spider Control' || stripos($prefilledService, 'spider') !== false) ? 'selected' : '' ?>>Spider Control (Property Inspection & Custom Quote)</option>
                                <option value="Product: Cockroach Gel" <?= (stripos($prefilledService, 'gel') !== false) ? 'selected' : '' ?>>Product: Cockroach Gel (₹550)</option>
                                <option value="Product: Cockroach Spray">Product: Cockroach Spray Bottle (₹1,200)</option>
                                <option value="Product: Rat Medicine">Product: Rat Medicine (₹250)</option>
                                <option value="Product: Snake Medicine">Product: Snake Medicine (₹1,800)</option>
                                <option value="Product: Termite Spray">Product: Termite Spray Bottle (₹2,000)</option>
                                <option value="General Inspection">General Inspection / Other Pests</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="c_locality" class="form-label">Property Locality</label>
                            <input type="text" name="locality" id="c_locality" class="form-input" value="<?= e($prefilledLocality) ?>" placeholder="e.g., Nacharam / Snehapuri Colony / HMT Nagar / Habsiguda / Taranaka">
                        </div>

                        <div class="form-group">
                            <label for="c_message" class="form-label">Message / Infestation Details (Optional)</label>
                            <textarea name="message" id="c_message" rows="4" class="form-textarea" placeholder="Describe the problem, property size, or preferred callback time..."></textarea>
                        </div>

                        <div class="form-group form-consent">
                            <label class="consent-checkbox-label">
                                <input type="checkbox" name="consent" id="c_consent" value="1" required checked>
                                <span class="checkbox-custom"></span>
                                <span class="consent-text">
                                    I agree to let Harsha Pest Control save my phone number and name so Rakesh can call me back regarding this inquiry. (Read our <a href="<?= base_url('privacy.php') ?>" target="_blank">Privacy Policy</a>).
                                </span>
                            </label>
                        </div>

                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="c_submitBtn">
                                <span>Submit Inspection Request</span>
                                <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>

                        <div class="form-status" id="c_formStatus" role="status" aria-live="polite"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
