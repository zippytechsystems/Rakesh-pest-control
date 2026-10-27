<?php
/**
 * Harsha Pest Control - Home Page Content
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);

$services = get_active_services();
$products = get_active_products();

$primaryPhone    = $primaryPhone ?? get_setting('primary_phone', '63052 35360');
$secondaryPhone  = $secondaryPhone ?? get_setting('secondary_phone', '88979 06391');
$whatsappNumber  = $whatsappNumber ?? get_setting('whatsapp_number', '6305235360');
$whatsappEnabled = $whatsappEnabled ?? (int)get_setting('whatsapp_enabled', '1');
?>

<!-- Hero Section -->
<section class="hero-section" aria-label="Hero Introduction">
    <div class="container hero-grid">
        <div class="hero-text-block">
            <div class="hero-badge">
                <span class="badge-icon">🛡️</span>
                <span class="badge-text">20 Years of Reliable Local Service</span>
            </div>
            <h1 class="hero-heading">
                Professional Pest Control in <span>Nacharam</span> & Nearby Areas
            </h1>
            <p class="hero-lead">
                Safe, targeted pest solutions for homes, apartments, and businesses across <strong>Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda, and Taranaka</strong>. Supervised directly by <strong>Rakesh</strong> with two decades of trusted field experience.
            </p>

            <div class="hero-actions">
                <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-lg" title="Call Rakesh directly">
                    <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>Call Now: <?= e($primaryPhone) ?></span>
                </a>

                <?php if ($whatsappEnabled && !empty($whatsappNumber)): ?>
                <a href="<?= whatsapp_url($whatsappNumber, 'Hello Rakesh garu, I need pest control service for my property in Nacharam/nearby area.') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">
                    <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.586 1.838.892 2.796.893 3.182 0 5.77-2.587 5.771-5.768 0-3.181-2.588-5.778-5.771-5.778zm3.385 8.167c-.145.409-.834.774-1.161.823-.326.05-.752.072-2.378-.588-1.956-.795-3.212-2.8-3.309-2.932-.097-.133-.79-1.052-.79-2.008 0-.956.497-1.427.674-1.621.176-.193.388-.242.518-.242.13 0 .258.002.37.008.117.006.275-.045.43.328.158.384.542 1.325.589 1.423.048.097.08.21.016.338-.064.129-.096.21-.192.323-.096.113-.203.252-.29.338-.097.097-.198.202-.085.396.113.193.504.832 1.082 1.347.744.662 1.37.868 1.564.965.194.097.307.081.42-.048.113-.13.484-.565.613-.758.129-.194.258-.162.435-.097.177.065 1.13.533 1.324.63.194.097.323.145.371.226.049.081.049.468-.096.877z"/></svg>
                    <span>WhatsApp Enquiry</span>
                </a>
                <?php endif; ?>
            </div>

            <div class="hero-trust-bar">
                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    <span class="trust-text">20 Years Local Experience</span>
                </div>
                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    <span class="trust-text">Up to 5 Years Written Warranty</span>
                </div>
                <div class="trust-item">
                    <span class="trust-check">✓</span>
                    <span class="trust-text">One Free Service on Eligible Plans</span>
                </div>
            </div>
        </div>

        <div class="hero-media-block">
            <div class="hero-image-wrapper">
                <img src="<?= e(base_url('assets/images/hero.jpg')) ?>" alt="Professional Pest Control Inspection in Nacharam" class="hero-img" width="580" height="420" loading="eager">
                <div class="hero-overlay-card">
                    <div class="overlay-icon">📍</div>
                    <div class="overlay-text">
                        <strong>Local Office in Nacharam</strong>
                        <span>Snehapuri Colony, Behind HDFC Bank</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Indicative Price & Warranty Callout Section -->
<section class="section section-offers" aria-labelledby="offersHeading">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-eyebrow">Transparent Pricing</span>
            <h2 id="offersHeading" class="section-title">Indicative Treatment Plans & Warranties</h2>
            <p class="section-subtitle">
                Clear indicative packages with committed warranty periods. Final scope and quotation are confirmed upon physical inspection of property size and pest infestation.
            </p>
        </div>

        <div class="offers-grid">
            <!-- Offer 1: Cockroach -->
            <div class="offer-card">
                <div class="offer-badge">Kitchen & Home</div>
                <h3 class="offer-title">Cockroach Treatment</h3>
                <div class="offer-property">Standard 2 BHK Apartment</div>
                <div class="offer-price">₹1,500 <span class="price-type">(Indicative)</span></div>
                <ul class="offer-features">
                    <li><span class="feat-check">✓</span> <strong>2-Year Warranty</strong></li>
                    <li><span class="feat-check">✓</span> <strong>One Service Free</strong> within warranty</li>
                    <li><span class="feat-check">✓</span> Herbal gel & drain barrier application</li>
                    <li><span class="feat-check">✓</span> Non-messy, kitchen friendly</li>
                </ul>
                <div class="offer-footer">
                    <a href="<?= base_url('contact.php?service=Cockroach+Control') ?>" class="btn btn-outline btn-block">Book Inspection</a>
                </div>
            </div>

            <!-- Offer 2: Termite -->
            <div class="offer-card featured-offer">
                <div class="offer-badge badge-popular">Most Requested</div>
                <h3 class="offer-title">Termite Treatment</h3>
                <div class="offer-property">Standard 2 BHK Apartment</div>
                <div class="offer-price">₹4,500 <span class="price-type">(Indicative)</span></div>
                <ul class="offer-features">
                    <li><span class="feat-check">✓</span> <strong>5-Year Warranty</strong></li>
                    <li><span class="feat-check">✓</span> <strong>One Service Free</strong> within warranty</li>
                    <li><span class="feat-check">✓</span> Deep drill, fill & seal perimeter defense</li>
                    <li><span class="feat-check">✓</span> Protects wooden frames & foundations</li>
                </ul>
                <div class="offer-footer">
                    <a href="<?= base_url('contact.php?service=Termite+Control') ?>" class="btn btn-primary btn-block">Book Inspection</a>
                </div>
            </div>

            <!-- Offer 3: Mosquito -->
            <div class="offer-card">
                <div class="offer-badge">Outdoor Protection</div>
                <h3 class="offer-title">Mosquito Treatment</h3>
                <div class="offer-property">Outdoor Garden & Compound</div>
                <div class="offer-price">₹2,500 <span class="price-type">(Indicative)</span></div>
                <ul class="offer-features">
                    <li><span class="feat-check">✓</span> <strong>6-Month Warranty</strong></li>
                    <li><span class="feat-check">✓</span> <strong>One Service Free</strong> within warranty</li>
                    <li><span class="feat-check">✓</span> Outdoor fogging & boundary residual spray</li>
                    <li><span class="feat-check">✓</span> Larval breeding source neutralization</li>
                </ul>
                <div class="offer-footer">
                    <a href="<?= base_url('contact.php?service=Mosquito+Control') ?>" class="btn btn-outline btn-block">Book Inspection</a>
                </div>
            </div>

            <!-- Offer 4: Rat -->
            <div class="offer-card">
                <div class="offer-badge">Basement & Garden</div>
                <h3 class="offer-title">Rat & Rodent Treatment</h3>
                <div class="offer-property">Garden, Ground Floor, or Cellar</div>
                <div class="offer-price">₹3,500 <span class="price-type">(Indicative)</span></div>
                <ul class="offer-features">
                    <li><span class="feat-check">✓</span> <strong>2-Year Warranty</strong></li>
                    <li><span class="feat-check">✓</span> <strong>One Service Free</strong> within warranty</li>
                    <li><span class="feat-check">✓</span> Professional bait stations & exclusion</li>
                    <li><span class="feat-check">✓</span> Cellar, drain & ceiling run protection</li>
                </ul>
                <div class="offer-footer">
                    <a href="<?= base_url('contact.php?service=Rat+Control') ?>" class="btn btn-outline btn-block">Book Inspection</a>
                </div>
            </div>
        </div>

        <div class="disclaimer-banner">
            <span class="disclaimer-icon">ℹ️</span>
            <p class="disclaimer-text">
                <strong>Price & Warranty Clarification:</strong> Package prices listed above are indicative for standard layouts and typical residential infestations. Property dimensions, structural conditions, and level of infestation will be physically evaluated to confirm the final scope and quotation. Warranty terms remain strictly as committed.
            </p>
        </div>
    </div>
</section>

<!-- Full Services Section -->
<section class="section section-services" id="services" aria-labelledby="servicesHeading">
    <div class="container">
        <div class="section-header">
            <div class="section-header-row">
                <div>
                    <span class="section-eyebrow">Our Expertise</span>
                    <h2 id="servicesHeading" class="section-title">Pest Control Services</h2>
                    <p class="section-subtitle">
                        Proven control methods applied by experienced local technicians with 20 years of experience.
                    </p>
                </div>
                <div class="section-header-action">
                    <a href="<?= base_url('services.php') ?>" class="btn btn-secondary">View All Services →</a>
                </div>
            </div>
        </div>

        <div class="services-grid">
            <?php foreach ($services as $service): ?>
            <article class="service-card" id="<?= e($service['slug']) ?>">
                <div class="service-card-header">
                    <div class="service-icon-box">
                        <span class="service-icon-sym">🛡️</span>
                    </div>
                    <div class="service-warranty-pill"><?= e($service['warranty_terms']) ?></div>
                </div>
                <h3 class="service-name"><?= e($service['name']) ?></h3>
                <p class="service-short-desc"><?= e($service['short_desc']) ?></p>
                
                <div class="service-meta-box">
                    <div class="service-price-tag"><?= e($service['indicative_price']) ?></div>
                    <div class="service-free-tag">🎁 <?= e($service['free_service_offer']) ?></div>
                </div>

                <div class="service-actions">
                    <a href="<?= base_url('contact.php?service=' . urlencode($service['name'])) ?>" class="btn btn-primary btn-sm">Request Inspection</a>
                    <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-link btn-sm">Call Rakesh</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="section section-products" id="products" aria-labelledby="productsHeading">
    <div class="container">
        <div class="section-header">
            <div class="section-header-row">
                <div>
                    <span class="section-eyebrow">Counter Products</span>
                    <h2 id="productsHeading" class="section-title">Pest Control Products</h2>
                    <p class="section-subtitle">
                        Genuine formulations available directly at our Nacharam location. Please call to confirm counter stock before visiting.
                    </p>
                </div>
                <div class="section-header-action">
                    <a href="<?= base_url('products.php') ?>" class="btn btn-secondary">View All Products →</a>
                </div>
            </div>
        </div>

        <div class="products-grid">
            <?php foreach ($products as $prod): ?>
            <div class="product-card">
                <div class="product-badge"><?= e($prod['category']) ?></div>
                <h3 class="product-title"><?= e($prod['name']) ?></h3>
                <div class="product-price"><?= e($prod['price_display']) ?></div>
                <p class="product-desc"><?= e($prod['description']) ?></p>
                
                <div class="product-stock-note">
                    <?= $prod['is_available'] ? '<span class="status-available">● Counter Available</span>' : '<span class="status-call">● Call to Check Stock</span>' ?>
                </div>

                <div class="product-actions">
                    <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-sm btn-block">
                        <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>Call to Order: <?= e($primaryPhone) ?></span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Areas We Serve Summary Section -->
<section class="section section-areas" aria-labelledby="areasHeading">
    <div class="container">
        <div class="areas-card">
            <div class="areas-card-content">
                <span class="section-eyebrow">Local Service Coverage</span>
                <h2 id="areasHeading" class="section-title">Local Presence & Fast Service Areas</h2>
                <p class="section-subtitle">
                    Our permanent physical office is located at <strong>Snehapuri Colony, Behind HDFC Bank, Nacharam</strong>. From here, we provide prompt on-site pest inspection and service across our confirmed service localities:
                </p>

                <div class="locality-chips-grid">
                    <div class="locality-chip office-chip">
                        <span class="chip-pin">🏢</span>
                        <div class="chip-text">
                            <strong>Nacharam (Physical Office)</strong>
                            <span>Behind HDFC Bank, Snehapuri Colony</span>
                        </div>
                    </div>
                    <div class="locality-chip">
                        <span class="chip-pin">📍</span>
                        <div class="chip-text">
                            <strong>Snehapuri Colony</strong>
                            <span>Immediate residential doorstep service</span>
                        </div>
                    </div>
                    <div class="locality-chip">
                        <span class="chip-pin">📍</span>
                        <div class="chip-text">
                            <strong>HMT Nagar</strong>
                            <span>Fast response for apartments & homes</span>
                        </div>
                    </div>
                    <div class="locality-chip">
                        <span class="chip-pin">📍</span>
                        <div class="chip-text">
                            <strong>Habsiguda</strong>
                            <span>Houses, commercial shops, institutions</span>
                        </div>
                    </div>
                    <div class="locality-chip">
                        <span class="chip-pin">📍</span>
                        <div class="chip-text">
                            <strong>Taranaka</strong>
                            <span>Residential colonies & nearby premises</span>
                        </div>
                    </div>
                    <div class="locality-chip">
                        <span class="chip-pin">📍</span>
                        <div class="chip-text">
                            <strong>Nearby Hyderabad Areas</strong>
                            <span>Accessible by prior telephone confirmation</span>
                        </div>
                    </div>
                </div>

                <div class="areas-cta-row">
                    <p class="areas-notice">
                        * We do not operate remote branch offices. All inspections are scheduled directly from our Nacharam base.
                    </p>
                    <a href="<?= base_url('areas.php') ?>" class="btn btn-secondary">Read Detailed Area Guide →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inspection & Process Section -->
<section class="section section-process" aria-labelledby="processHeading">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-eyebrow">How We Work</span>
            <h2 id="processHeading" class="section-title">Our 4-Step Honest Treatment Process</h2>
            <p class="section-subtitle">
                Clear communication and verified results without unnecessary upsells.
            </p>
        </div>

        <div class="process-grid">
            <div class="process-step">
                <div class="step-num">01</div>
                <h3 class="step-title">Direct Consultation</h3>
                <p class="step-desc">
                    Speak directly with Rakesh on 63052 35360 to describe your pest issue, property type, and urgent requirements.
                </p>
            </div>
            <div class="process-step">
                <div class="step-num">02</div>
                <h3 class="step-title">Physical Inspection</h3>
                <p class="step-desc">
                    We inspect nesting areas, entry channels, wall hollows, and wooden frames to determine exact infestation severity.
                </p>
            </div>
            <div class="process-step">
                <div class="step-num">03</div>
                <h3 class="step-title">Safe Treatment</h3>
                <p class="step-desc">
                    Application of government-approved, targeted herbal gels or barrier chemicals adhering to safety guidelines.
                </p>
            </div>
            <div class="process-step">
                <div class="step-num">04</div>
                <h3 class="step-title">Written Warranty</h3>
                <p class="step-desc">
                    Receive your confirmed warranty card. Enjoy eligible free follow-up service if pests reappear during the period.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact & Inspection Booking Form -->
<section class="section section-contact-form" id="book" aria-labelledby="formHeading">
    <div class="container">
        <div class="contact-form-layout">
            <div class="contact-info-col">
                <span class="section-eyebrow">Get in Touch</span>
                <h2 id="formHeading" class="section-title">Schedule a Property Inspection</h2>
                <p class="section-subtitle">
                    Leave your contact details and locality. Rakesh will personally call you back to discuss your problem and visit your premises.
                </p>

                <div class="contact-details-box">
                    <div class="c-detail-item">
                        <span class="c-icon">📞</span>
                        <div>
                            <strong>Primary Contact Number</strong>
                            <a href="<?= phone_href($primaryPhone) ?>"><?= e($primaryPhone) ?></a>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <span class="c-icon">📱</span>
                        <div>
                            <strong>Secondary Number</strong>
                            <a href="<?= phone_href($secondaryPhone) ?>"><?= e($secondaryPhone) ?></a>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <span class="c-icon">📍</span>
                        <div>
                            <strong>Office Address</strong>
                            <span>Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076</span>
                        </div>
                    </div>
                    <div class="c-detail-item">
                        <span class="c-icon">⏱️</span>
                        <div>
                            <strong>Industry Experience</strong>
                            <span>20 Years Serving Nacharam & Surrounding Areas</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-col">
                <div class="form-card">
                    <h3 class="form-title">Request a Call Back</h3>
                    <p class="form-subtitle">Fill out the quick form below. We will never share your number.</p>

                    <form action="<?= base_url('submit-enquiry.php') ?>" method="POST" class="pest-enquiry-form" id="enquiryForm" novalidate>
                        <?= csrf_field() ?>

                        <!-- Anti-Spam Honeypot Field (Must remain empty) -->
                        <div class="honeypot-field" style="display:none !important;" aria-hidden="true">
                            <label for="website_url">Leave this empty</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="customer_name" class="form-label">Your Name <span class="required">*</span></label>
                            <input type="text" name="customer_name" id="customer_name" class="form-input" placeholder="e.g., Ramesh Kumar" required minlength="2" maxlength="100">
                        </div>

                        <div class="form-group">
                            <label for="customer_phone" class="form-label">10-Digit Mobile Number <span class="required">*</span></label>
                            <input type="tel" name="customer_phone" id="customer_phone" class="form-input" placeholder="e.g., 63052 35360" required pattern="[0-9]{10}">
                            <small class="form-help">Enter 10-digit mobile number for callback.</small>
                        </div>

                        <div class="form-group">
                            <label for="service_or_product" class="form-label">Service or Product Needed</label>
                            <select name="service_or_product" id="service_or_product" class="form-select">
                                <option value="">-- Select Requirement (Optional) --</option>
                                <option value="Cockroach Control">Cockroach Control (2 BHK ₹1,500 indicative / 2-Yr Warranty)</option>
                                <option value="Termite Control">Termite Control (2 BHK ₹4,500 indicative / 5-Yr Warranty)</option>
                                <option value="Mosquito Control">Mosquito Control (Outdoor ₹2,500 indicative / 6-Mo Warranty)</option>
                                <option value="Rat Control">Rat Control (Garden/Cellar ₹3,500 indicative / 2-Yr Warranty)</option>
                                <option value="Bed Bug Control">Bed Bug Control (Inspection & Quote)</option>
                                <option value="Spider Control">Spider Control (Inspection & Quote)</option>
                                <option value="Product: Cockroach Gel">Product: Cockroach Gel (₹550)</option>
                                <option value="Product: Cockroach Spray">Product: Cockroach Spray Bottle (₹1,200)</option>
                                <option value="Product: Rat Medicine">Product: Rat Medicine (₹250)</option>
                                <option value="Product: Snake Medicine">Product: Snake Medicine (₹1,800)</option>
                                <option value="Product: Termite Spray">Product: Termite Spray Bottle (₹2,000)</option>
                                <option value="General Inspection">General Inspection / Other Pests</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="locality" class="form-label">Your Locality in Hyderabad</label>
                            <input type="text" name="locality" id="locality" class="form-input" placeholder="e.g., Nacharam / HMT Nagar / Habsiguda">
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Property Details / Message (Optional)</label>
                            <textarea name="message" id="message" rows="3" class="form-textarea" placeholder="Briefly describe property type (e.g. 2 BHK, individual house, shop) and severity..."></textarea>
                        </div>

                        <div class="form-group form-consent">
                            <label class="consent-checkbox-label">
                                <input type="checkbox" name="consent" id="consent" value="1" required checked>
                                <span class="checkbox-custom"></span>
                                <span class="consent-text">
                                    I consent to Harsha Pest Control storing my contact details solely to call me back regarding this pest control enquiry.
                                </span>
                            </label>
                        </div>

                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary btn-block btn-lg" id="submitBtn">
                                <span>Submit Inspection Request</span>
                                <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </button>
                        </div>

                        <div class="form-status" id="formStatus" role="status" aria-live="polite"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
