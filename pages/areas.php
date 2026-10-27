<?php
/**
 * Harsha Pest Control - Areas We Serve Page Content
 */

defined('HARSHA_APP') or define('HARSHA_APP', true);
?>

<!-- Areas Hero Header -->
<section class="page-hero">
    <div class="container text-center">
        <span class="page-eyebrow">Local Service Coverage</span>
        <h1 class="page-title">Pest Control Coverage Across Nacharam & Nearby Hyderabad Areas</h1>
        <p class="page-lead">
            Fast, reliable doorstep pest control services dispatched directly from our permanent local base in Snehapuri Colony, Nacharam.
        </p>
    </div>
</section>

<!-- Office Location vs Service Area Clarification -->
<section class="section section-office-clarification">
    <div class="container">
        <div class="clarification-card">
            <div class="clarification-header">
                <span class="clarification-badge">Physical Office Base</span>
                <h2 class="clarification-title">Our Single Office Location: Nacharam</h2>
            </div>
            <p class="clarification-text">
                Harsha Pest Control operates from our registered physical address at <strong>Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076</strong>. We do not claim physical offices or branches in areas where none exist. All inspections, service teams, and customer calls are managed directly by <strong>Rakesh</strong> from our Nacharam base.
            </p>
            <div class="clarification-contact">
                <span>Direct Line: <a href="<?= phone_href($primaryPhone) ?>"><strong><?= e($primaryPhone) ?></strong></a></span>
                <span class="sep">•</span>
                <span>Alternate: <a href="<?= phone_href($secondaryPhone) ?>"><strong><?= e($secondaryPhone) ?></strong></a></span>
            </div>
        </div>
    </div>
</section>

<!-- Localities Covered in Detail -->
<section class="section section-localities">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-eyebrow">Where We Travel For Service</span>
            <h2 class="section-title">Confirmed Service Localities</h2>
            <p class="section-subtitle">
                Because our technicians work daily in eastern Hyderabad, we provide prompt visits to the following neighborhoods:
            </p>
        </div>

        <div class="localities-grid">
            <!-- Locality 1: Nacharam -->
            <article class="locality-card featured-locality">
                <div class="locality-badge badge-primary">HQ Location</div>
                <h3 class="locality-name">Nacharam</h3>
                <p class="locality-desc">
                    Home to our office behind HDFC Bank. We offer immediate response times for individual houses, gated communities, industrial units, and shops across old and new Nacharam layouts.
                </p>
                <ul class="locality-features">
                    <li>✓ Immediate same-day inspection availability</li>
                    <li>✓ Full residential & commercial pest coverage</li>
                    <li>✓ Direct counter product pick-up at office</li>
                </ul>
                <a href="<?= base_url('contact.php?locality=Nacharam') ?>" class="btn btn-primary btn-sm btn-block">Request Nacharam Inspection</a>
            </article>

            <!-- Locality 2: Snehapuri Colony -->
            <article class="locality-card">
                <div class="locality-badge">Immediate Neighborhood</div>
                <h3 class="locality-name">Snehapuri Colony</h3>
                <p class="locality-desc">
                    Right in our immediate vicinity. We regularly treat homes and apartment complexes in Snehapuri Colony for cockroach gel treatments, anti-termite piping, and seasonal mosquito barrier sprays.
                </p>
                <ul class="locality-features">
                    <li>✓ Quick doorstep visits within minutes</li>
                    <li>✓ 2 BHK special indicative plans</li>
                    <li>✓ Regular colony follow-ups</li>
                </ul>
                <a href="<?= base_url('contact.php?locality=Snehapuri+Colony') ?>" class="btn btn-outline btn-sm btn-block">Request Snehapuri Inspection</a>
            </article>

            <!-- Locality 3: HMT Nagar -->
            <article class="locality-card">
                <div class="locality-badge">Active Service Zone</div>
                <h3 class="locality-name">HMT Nagar</h3>
                <p class="locality-desc">
                    Serving independent houses, villas, and apartments along HMT Nagar main roads and interior sectors. Special focus on sub-floor termite protection and garden rodent/rat exclusion.
                </p>
                <ul class="locality-features">
                    <li>✓ Rapid scheduling for HMT Nagar residences</li>
                    <li>✓ Multi-year warranty packages</li>
                    <li>✓ Termite drill-and-fill specialists</li>
                </ul>
                <a href="<?= base_url('contact.php?locality=HMT+Nagar') ?>" class="btn btn-outline btn-sm btn-block">Request HMT Nagar Inspection</a>
            </article>

            <!-- Locality 4: Habsiguda -->
            <article class="locality-card">
                <div class="locality-badge">Active Service Zone</div>
                <h3 class="locality-name">Habsiguda</h3>
                <p class="locality-desc">
                    Just a short distance from Nacharam. We cater to homes, educational institutions, retail shops, and commercial offices located across Habsiguda Street Numbers 1 through 8 and main junction areas.
                </p>
                <ul class="locality-features">
                    <li>✓ Commercial and domestic treatment plans</li>
                    <li>✓ Odorless cockroach gel for kitchens</li>
                    <li>✓ Flexible scheduling for working professionals</li>
                </ul>
                <a href="<?= base_url('contact.php?locality=Habsiguda') ?>" class="btn btn-outline btn-sm btn-block">Request Habsiguda Inspection</a>
            </article>

            <!-- Locality 5: Taranaka -->
            <article class="locality-card">
                <div class="locality-badge">Active Service Zone</div>
                <h3 class="locality-name">Taranaka</h3>
                <p class="locality-desc">
                    Convenient doorstep assistance for residential apartments, independent homes, and academic/institutional quarters around Taranaka flyover, White House area, and Osmania University borders.
                </p>
                <ul class="locality-features">
                    <li>✓ Bed bug multi-stage heat/spray treatments</li>
                    <li>✓ Cellar rodent control for apartment buildings</li>
                    <li>✓ Reliable weekend appointment slots</li>
                </ul>
                <a href="<?= base_url('contact.php?locality=Taranaka') ?>" class="btn btn-outline btn-sm btn-block">Request Taranaka Inspection</a>
            </article>

            <!-- Locality 6: Nearby Hyderabad Areas -->
            <article class="locality-card">
                <div class="locality-badge">Surrounding Localities</div>
                <h3 class="locality-name">Nearby Hyderabad Areas</h3>
                <p class="locality-desc">
                    We also attend to appointments in nearby localities like Mallapur, Ramanthapur, and Uppal by prior phone consultation. Contact Rakesh directly to check technician availability for your area.
                </p>
                <ul class="locality-features">
                    <li>✓ Doorstep service with prior appointment</li>
                    <li>✓ Transparent indicative rates confirmed on-site</li>
                    <li>✓ Written warranty card provided</li>
                </ul>
                <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-outline btn-sm btn-block">Call to Confirm Area</a>
            </article>
        </div>
    </div>
</section>

<!-- Callout Banner -->
<section class="section section-area-cta">
    <div class="container text-center">
        <h2 class="area-cta-title">Living Around Nacharam or Nearby Localities?</h2>
        <p class="area-cta-text">
            Call Rakesh directly on <strong>63052 35360</strong> to discuss your pest problem and schedule a prompt on-site visit.
        </p>
        <div class="area-cta-actions">
            <a href="<?= phone_href($primaryPhone) ?>" class="btn btn-primary btn-lg">Call <?= e($primaryPhone) ?></a>
            <a href="<?= base_url('contact.php') ?>" class="btn btn-secondary btn-lg">Book Property Inspection</a>
        </div>
    </div>
</section>
