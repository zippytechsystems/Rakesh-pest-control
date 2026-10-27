<?php
/**
 * Harsha Pest Control - About Us Page Content
 */

$primaryPhone   = $primaryPhone ?? get_setting('primary_phone', '63052 35360');
$secondaryPhone = $secondaryPhone ?? get_setting('secondary_phone', '88979 06391');
?>

<!-- About Hero Header -->
<section class="page-hero">
    <div class="container text-center">
        <span class="page-eyebrow">20 Years of Reliable Service</span>
        <h1 class="page-title">About Harsha Pest Control</h1>
        <p class="page-lead">
            Delivering honest, targeted pest control solutions across Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda, and Taranaka for two decades.
        </p>
    </div>
</section>

<!-- About Story Section -->
<section class="section section-about-story">
    <div class="container">
        <div class="about-story-grid">
            <div class="about-image-col">
                <div class="about-img-frame">
                    <img src="<?= e(base_url('assets/images/inspection.jpg')) ?>" alt="Pest Control Inspection in Progress by Harsha Pest Control" class="about-feature-img" width="540" height="420" loading="lazy">
                    <div class="about-badge-card">
                        <span class="badge-num">20</span>
                        <span class="badge-lbl">Years of Field Experience in Hyderabad</span>
                    </div>
                </div>
            </div>

            <div class="about-text-col">
                <span class="section-eyebrow">Our Story & Values</span>
                <h2 class="section-title">Founded & Supervised Directly by Rakesh</h2>
                
                <p class="about-p">
                    For <strong>20 years</strong>, Harsha Pest Control has stood as a dependable local name for families and businesses facing pest disturbances in eastern Hyderabad. Rather than operating as an impersonal corporate call center, our services are led personally by <strong>Rakesh</strong>, who directly assesses infestations, prescribes the right treatment plan, and supervises on-site applications.
                </p>

                <p class="about-p">
                    Located permanently at <strong>Snehapuri Colony, Behind HDFC Bank, Nacharam</strong>, we understand the specific environmental and architectural conditions of our neighborhood homes—from sub-surface termite channels in older foundations to cockroach harborage in modular kitchen layouts.
                </p>

                <div class="about-commitments">
                    <div class="commitment-item">
                        <div class="com-icon">🎯</div>
                        <div>
                            <strong>Honest On-Site Assessment</strong>
                            <p>We do not exaggerate pest problems or force unnecessary packages. Every recommendation is based on real physical evidence found during inspection.</p>
                        </div>
                    </div>

                    <div class="commitment-item">
                        <div class="com-icon">📝</div>
                        <div>
                            <strong>Committed Written Warranties</strong>
                            <p>Our 2-year and 5-year warranties come with guaranteed free follow-up service visits if pest activity reoccurs within the coverage period.</p>
                        </div>
                    </div>

                    <div class="commitment-item">
                        <div class="com-icon">🤝</div>
                        <div>
                            <strong>Direct Owner Accountability</strong>
                            <p>Whenever you call 63052 35360 or 88979 06391, you speak directly with the business owner, ensuring prompt scheduling and clear communication.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Details Card -->
<section class="section section-about-meta">
    <div class="container">
        <div class="about-meta-box">
            <h3 class="meta-box-title">Verified Business Profile</h3>
            <div class="meta-box-grid">
                <div class="meta-entry">
                    <span class="meta-entry-label">Business Name</span>
                    <span class="meta-entry-val">Harsha Pest Control</span>
                </div>
                <div class="meta-entry">
                    <span class="meta-entry-label">Primary Owner</span>
                    <span class="meta-entry-val">Rakesh</span>
                </div>
                <div class="meta-entry">
                    <span class="meta-entry-label">Years of Experience</span>
                    <span class="meta-entry-val">20 Years</span>
                </div>
                <div class="meta-entry">
                    <span class="meta-entry-label">Direct Contact</span>
                    <span class="meta-entry-val"><a href="<?= phone_href($primaryPhone) ?>"><?= e($primaryPhone) ?></a>, <a href="<?= phone_href($secondaryPhone) ?>"><?= e($secondaryPhone) ?></a></span>
                </div>
                <div class="meta-entry meta-span-2">
                    <span class="meta-entry-label">Permanent Office Address</span>
                    <span class="meta-entry-val">Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076</span>
                </div>
                <div class="meta-entry meta-span-2">
                    <span class="meta-entry-label">Primary Service Localities</span>
                    <span class="meta-entry-val">HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About CTA -->
<section class="section section-about-cta">
    <div class="container text-center">
        <h2 class="section-title">Need Reliable Advice For Your Property?</h2>
        <p class="section-subtitle">
            Speak directly with Rakesh today. No obligation, just honest local guidance.
        </p>
        <div class="about-cta-btns">
            <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-lg">Call <?= e($primaryPhone) ?></a>
            <a href="<?= base_url('contact.php') ?>" class="btn btn-secondary btn-lg">Schedule Inspection</a>
        </div>
    </div>
</section>
