<?php
/**
 * Harsha Pest Control - Site Settings & Page SEO Manager
 */

define('HARSHA_APP', true);

$currentAdminNav = 'settings';
$adminTitle = 'Site Settings & SEO';

require_once __DIR__ . '/admin-header.php';

$pdo = get_db_connection();

// Handle POST Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security session expired. Please refresh and try again.');
        header("Location: " . base_url('admin/settings.php'));
        exit;
    }

    $section = $_POST['section'] ?? '';

    // 1. Business Info & WhatsApp Settings
    if ($section === 'business_settings') {
        $settingsToUpdate = [
            'business_name'        => trim($_POST['business_name'] ?? 'Harsha Pest Control'),
            'experience_years'     => trim($_POST['experience_years'] ?? '20'),
            'contact_person'       => trim($_POST['contact_person'] ?? 'Rakesh'),
            'primary_phone'        => trim($_POST['primary_phone'] ?? '63052 35360'),
            'primary_phone_clean'  => clean_phone($_POST['primary_phone'] ?? '6305235360'),
            'secondary_phone'      => trim($_POST['secondary_phone'] ?? '88979 06391'),
            'secondary_phone_clean'=> clean_phone($_POST['secondary_phone'] ?? '8897906391'),
            'address_street'       => trim($_POST['address_street'] ?? 'Snehapuri Colony, Behind HDFC Bank'),
            'address_locality'     => trim($_POST['address_locality'] ?? 'Nacharam'),
            'address_city'         => trim($_POST['address_city'] ?? 'Hyderabad'),
            'address_pincode'      => trim($_POST['address_pincode'] ?? '500 076'),
            'service_areas_text'   => trim($_POST['service_areas_text'] ?? 'HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas'),
            'whatsapp_number'      => clean_phone($_POST['whatsapp_number'] ?? '6305235360'),
            'whatsapp_enabled'     => isset($_POST['whatsapp_enabled']) ? '1' : '0',
        ];

        foreach ($settingsToUpdate as $key => $val) {
            update_setting($key, $val);
        }

        set_flash('success', 'Business contact and WhatsApp settings updated successfully.');
        header("Location: " . base_url('admin/settings.php'));
        exit;
    }

    // 2. Page SEO Metadata
    if ($section === 'page_seo') {
        $pageSlug = trim($_POST['page_slug'] ?? '');
        $title    = trim($_POST['title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $h1       = trim($_POST['h1_heading'] ?? '');
        $canon    = trim($_POST['canonical_url'] ?? '');

        if (!empty($pageSlug) && !empty($title)) {
            $stmt = $pdo->prepare("
                UPDATE page_seo SET 
                    title = :title, 
                    meta_description = :meta, 
                    h1_heading = :h1, 
                    canonical_url = :canon 
                WHERE page_slug = :slug
            ");
            $stmt->execute([
                ':title' => $title,
                ':meta'  => $metaDesc,
                ':h1'    => $h1,
                ':canon' => $canon,
                ':slug'  => $pageSlug
            ]);
            set_flash('success', "SEO metadata for page '{$pageSlug}' updated.");
        } else {
            set_flash('error', 'Page slug and title tag are required.');
        }
        header("Location: " . base_url('admin/settings.php?tab=seo&page=' . urlencode($pageSlug)));
        exit;
    }
}

// Fetch all SEO pages
$seoPages = $pdo->query("SELECT * FROM page_seo ORDER BY id ASC")->fetchAll();
$selectedSlug = $_GET['page'] ?? ($seoPages[0]['page_slug'] ?? 'home');
$selectedPage = null;
foreach ($seoPages as $sp) {
    if ($sp['page_slug'] === $selectedSlug) {
        $selectedPage = $sp;
        break;
    }
}
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Website Settings & SEO Configuration</h1>
        <p class="admin-page-subtitle">Manage company contact info, WhatsApp integration, and search engine title/meta tags.</p>
    </div>
</div>

<div class="admin-grid-layout">
    <!-- Col 1: Business Profile & WhatsApp -->
    <div class="admin-card">
        <h2 class="admin-card-title">🏢 Business Information & Contact Numbers</h2>
        <p class="admin-card-sub">Consistent business details across headers, footers, schema, and local listings.</p>

        <form method="POST" action="<?= base_url('admin/settings.php') ?>" class="admin-edit-form">
            <?= csrf_field() ?>
            <input type="hidden" name="section" value="business_settings">

            <div class="form-group">
                <label for="s_bname" class="form-label">Business Name</label>
                <input type="text" name="business_name" id="s_bname" class="form-input" required value="<?= e(get_setting('business_name', 'Harsha Pest Control')) ?>">
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="s_exp" class="form-label">Years of Experience</label>
                    <input type="text" name="experience_years" id="s_exp" class="form-input" required value="<?= e(get_setting('experience_years', '20')) ?>">
                </div>
                <div class="form-group col-6">
                    <label for="s_person" class="form-label">Contact Person / Owner</label>
                    <input type="text" name="contact_person" id="s_person" class="form-input" required value="<?= e(get_setting('contact_person', 'Rakesh')) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="s_phone1" class="form-label">Primary Phone Number</label>
                    <input type="text" name="primary_phone" id="s_phone1" class="form-input" required value="<?= e(get_setting('primary_phone', '63052 35360')) ?>">
                </div>
                <div class="form-group col-6">
                    <label for="s_phone2" class="form-label">Secondary Phone Number</label>
                    <input type="text" name="secondary_phone" id="s_phone2" class="form-input" required value="<?= e(get_setting('secondary_phone', '88979 06391')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="s_street" class="form-label">Office Address (Street & Landmark)</label>
                <input type="text" name="address_street" id="s_street" class="form-input" required value="<?= e(get_setting('address_street', 'Snehapuri Colony, Behind HDFC Bank')) ?>">
            </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="s_loc" class="form-label">Locality</label>
                    <input type="text" name="address_locality" id="s_loc" class="form-input" required value="<?= e(get_setting('address_locality', 'Nacharam')) ?>">
                </div>
                <div class="form-group col-4">
                    <label for="s_city" class="form-label">City</label>
                    <input type="text" name="address_city" id="s_city" class="form-input" required value="<?= e(get_setting('address_city', 'Hyderabad')) ?>">
                </div>
                <div class="form-group col-4">
                    <label for="s_pin" class="form-label">PIN Code</label>
                    <input type="text" name="address_pincode" id="s_pin" class="form-input" required value="<?= e(get_setting('address_pincode', '500 076')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="s_areas" class="form-label">Confirmed Service Areas (Text)</label>
                <textarea name="service_areas_text" id="s_areas" rows="2" class="form-textarea"><?= e(get_setting('service_areas_text', 'HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas')) ?></textarea>
            </div>

            <hr style="margin:24px 0; border:none; border-top:1px solid #e2e8f0;">

            <h3 style="font-size:1.1rem; margin-bottom:12px; color:#047857;">💬 WhatsApp Destination Configuration</h3>
            <p class="admin-card-sub">Select which confirmed number receives customer WhatsApp inquiries.</p>

            <div class="form-group">
                <label for="s_wa" class="form-label">WhatsApp Mobile Digits</label>
                <input type="text" name="whatsapp_number" id="s_wa" class="form-input" value="<?= e(get_setting('whatsapp_number', '6305235360')) ?>">
                <small class="form-help">Enter 10 digits without country code. Owner must confirm whether 6305235360 or 8897906391 has an active WhatsApp Business account.</small>
            </div>

            <div class="form-group">
                <label class="consent-checkbox-label">
                    <input type="checkbox" name="whatsapp_enabled" value="1" <?= get_setting('whatsapp_enabled', '1') === '1' ? 'checked' : '' ?>>
                    <span class="checkbox-custom"></span>
                    <span class="consent-text"><strong>Enable WhatsApp buttons across the website</strong></span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save Business & WhatsApp Settings</button>
        </form>
    </div>

    <!-- Col 2: SEO Metadata per Page -->
    <div class="admin-card">
        <h2 class="admin-card-title">🔍 Search Engine Optimization (SEO)</h2>
        <p class="admin-card-sub">Customize HTML title tags and meta descriptions for each page.</p>

        <!-- Page Selector Tabs -->
        <div class="admin-seo-tabs">
            <?php foreach ($seoPages as $pg): ?>
                <a href="<?= base_url('admin/settings.php?page=' . urlencode($pg['page_slug'])) ?>" class="seo-tab-btn <?= $pg['page_slug'] === $selectedSlug ? 'active' : '' ?>">
                    <?= e($pg['page_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($selectedPage): ?>
        <form method="POST" action="<?= base_url('admin/settings.php') ?>" class="admin-edit-form" style="margin-top:20px;">
            <?= csrf_field() ?>
            <input type="hidden" name="section" value="page_seo">
            <input type="hidden" name="page_slug" value="<?= e($selectedPage['page_slug']) ?>">

            <div class="form-group">
                <label for="seo_title" class="form-label">Browser Title Tag (&lt;title&gt;) <span class="required">*</span></label>
                <input type="text" name="title" id="seo_title" class="form-input" required value="<?= e($selectedPage['title']) ?>" maxlength="70">
                <small class="form-help">Recommended length: 50-65 characters. Keep location & brand visible.</small>
            </div>

            <div class="form-group">
                <label for="seo_h1" class="form-label">Primary H1 Heading on Page</label>
                <input type="text" name="h1_heading" id="seo_h1" class="form-input" required value="<?= e($selectedPage['h1_heading']) ?>">
            </div>

            <div class="form-group">
                <label for="seo_desc" class="form-label">Meta Description</label>
                <textarea name="meta_description" id="seo_desc" rows="3" class="form-textarea" maxlength="160"><?= e($selectedPage['meta_description']) ?></textarea>
                <small class="form-help">Recommended: 120-155 characters summarizing genuine local services.</small>
            </div>

            <div class="form-group">
                <label for="seo_canon" class="form-label">Canonical URL Override (Optional)</label>
                <input type="url" name="canonical_url" id="seo_canon" class="form-input" value="<?= e($selectedPage['canonical_url'] ?? '') ?>" placeholder="https://yourdomain.com/page">
                <small class="form-help">Leave empty to use automatic canonical URL generation.</small>
            </div>

            <!-- Live Google Search Snippet Preview -->
            <div class="google-snippet-preview">
                <div class="snippet-title-preview" id="previewTitle"><?= e($selectedPage['title']) ?></div>
                <div class="snippet-url-preview"><?= e(base_url($selectedPage['page_slug'] === 'home' ? '' : ($selectedPage['page_slug'] . '.php'))) ?></div>
                <div class="snippet-desc-preview" id="previewDesc"><?= e($selectedPage['meta_description']) ?></div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top:20px;">Update SEO for <?= e($selectedPage['page_name']) ?></button>
        </form>

        <script>
        document.getElementById('seo_title').addEventListener('input', function() {
            document.getElementById('previewTitle').innerText = this.value;
        });
        document.getElementById('seo_desc').addEventListener('input', function() {
            document.getElementById('previewDesc').innerText = this.value;
        });
        </script>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
