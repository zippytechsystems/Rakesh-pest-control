<?php
/**
 * Harsha Pest Control - Services & Pricing Management
 */

define('HARSHA_APP', true);

$currentAdminNav = 'services';
$adminTitle = 'Manage Services';

require_once __DIR__ . '/admin-header.php';

$pdo = get_db_connection();

// Handle Add / Edit / Toggle / Delete POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security session expired. Please refresh and try again.');
        header("Location: " . base_url('admin/services.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'save_service') {
        $id          = (int)($_POST['service_id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $slug        = trim($_POST['slug'] ?? '');
        $shortDesc   = trim($_POST['short_desc'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = trim($_POST['indicative_price'] ?? '');
        $warranty    = trim($_POST['warranty_terms'] ?? '');
        $freeOffer   = trim($_POST['free_service_offer'] ?? '');
        $priceNote   = trim($_POST['price_note'] ?? '');
        $order       = (int)($_POST['display_order'] ?? 0);
        $isActive    = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name)) {
            set_flash('error', 'Service name is required.');
        } else {
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }

            try {
                if ($id > 0) {
                    $stmt = $pdo->prepare("
                        UPDATE services SET 
                            name = :name, 
                            slug = :slug, 
                            short_desc = :short_desc, 
                            description = :description, 
                            indicative_price = :price, 
                            warranty_terms = :warranty, 
                            free_service_offer = :free_offer, 
                            price_note = :price_note, 
                            display_order = :ord, 
                            is_active = :active 
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        ':name'        => $name,
                        ':slug'        => $slug,
                        ':short_desc'  => $shortDesc,
                        ':description' => $description,
                        ':price'       => $price,
                        ':warranty'    => $warranty,
                        ':free_offer'  => $freeOffer,
                        ':price_note'  => $priceNote,
                        ':ord'         => $order,
                        ':active'      => $isActive,
                        ':id'          => $id
                    ]);
                    set_flash('success', "Service '{$name}' updated successfully.");
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO services (name, slug, short_desc, description, indicative_price, warranty_terms, free_service_offer, price_note, display_order, is_active)
                        VALUES (:name, :slug, :short_desc, :description, :price, :warranty, :free_offer, :price_note, :ord, :active)
                    ");
                    $stmt->execute([
                        ':name'        => $name,
                        ':slug'        => $slug,
                        ':short_desc'  => $shortDesc,
                        ':description' => $description,
                        ':price'       => $price,
                        ':warranty'    => $warranty,
                        ':free_offer'  => $freeOffer,
                        ':price_note'  => $priceNote,
                        ':ord'         => $order,
                        ':active'      => $isActive
                    ]);
                    set_flash('success', "New service '{$name}' added successfully.");
                }
            } catch (Exception $e) {
                set_flash('error', "Database error: " . $e->getMessage());
            }
        }
        header("Location: " . base_url('admin/services.php'));
        exit;
    }

    if ($action === 'toggle_active') {
        $id = (int)($_POST['service_id'] ?? 0);
        $curr = (int)($_POST['current_status'] ?? 0);
        $new = ($curr === 1) ? 0 : 1;
        $upd = $pdo->prepare("UPDATE services SET is_active = :act WHERE id = :id");
        $upd->execute([':act' => $new, ':id' => $id]);
        set_flash('success', "Service status updated.");
        header("Location: " . base_url('admin/services.php'));
        exit;
    }
}

$services = get_all_services();
$editingService = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    foreach ($services as $s) {
        if ((int)$s['id'] === $editId) {
            $editingService = $s;
            break;
        }
    }
}
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pest Control Services & Warranties</h1>
        <p class="admin-page-subtitle">Configure treatment descriptions, indicative prices, and committed warranty terms.</p>
    </div>
    <div class="admin-page-actions">
        <a href="#serviceFormCard" class="btn btn-primary" onclick="resetServiceForm()">+ Add New Service</a>
    </div>
</div>

<div class="admin-grid-layout">
    <!-- Left: Services List Table -->
    <div class="admin-card">
        <h2 class="admin-card-title">Existing Services (<?= count($services) ?>)</h2>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Service Name</th>
                        <th>Indicative Price</th>
                        <th>Warranty</th>
                        <th>Free Offer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $srv): ?>
                    <tr>
                        <td><?= (int)$srv['display_order'] ?></td>
                        <td>
                            <strong><?= e($srv['name']) ?></strong><br>
                            <small class="text-muted"><?= e($srv['slug']) ?></small>
                        </td>
                        <td><?= e($srv['indicative_price']) ?></td>
                        <td><span class="badge-warranty"><?= e($srv['warranty_terms']) ?></span></td>
                        <td><?= e($srv['free_service_offer']) ?></td>
                        <td>
                            <form method="POST" action="<?= base_url('admin/services.php') ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="toggle_active">
                                <input type="hidden" name="service_id" value="<?= (int)$srv['id'] ?>">
                                <input type="hidden" name="current_status" value="<?= (int)$srv['is_active'] ?>">
                                <button type="submit" class="status-toggle-btn <?= $srv['is_active'] ? 'active' : 'inactive' ?>" title="Click to toggle">
                                    <?= $srv['is_active'] ? '● Active' : '○ Hidden' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/services.php?edit=' . (int)$srv['id'] . '#serviceFormCard') ?>" class="btn btn-sm btn-outline">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Add / Edit Form -->
    <div class="admin-card" id="serviceFormCard">
        <h2 class="admin-card-title" id="formCardTitle"><?= $editingService ? 'Edit Service: ' . e($editingService['name']) : 'Add New Service' ?></h2>
        
        <form method="POST" action="<?= base_url('admin/services.php') ?>" class="admin-edit-form">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_service">
            <input type="hidden" name="service_id" id="f_id" value="<?= (int)($editingService['id'] ?? 0) ?>">

            <div class="form-group">
                <label for="f_name" class="form-label">Service Name <span class="required">*</span></label>
                <input type="text" name="name" id="f_name" class="form-input" required value="<?= e($editingService['name'] ?? '') ?>" placeholder="e.g., Cockroach Control">
            </div>

            <div class="form-group">
                <label for="f_slug" class="form-label">URL Slug</label>
                <input type="text" name="slug" id="f_slug" class="form-input" value="<?= e($editingService['slug'] ?? '') ?>" placeholder="e.g., cockroach-control">
                <small class="form-help">Leave empty to auto-generate from service name.</small>
            </div>

            <div class="form-group">
                <label for="f_short" class="form-label">Short Summary (Card Preview)</label>
                <input type="text" name="short_desc" id="f_short" class="form-input" value="<?= e($editingService['short_desc'] ?? '') ?>" placeholder="Brief 1-sentence summary">
            </div>

            <div class="form-group">
                <label for="f_desc" class="form-label">Full Treatment Description</label>
                <textarea name="description" id="f_desc" rows="4" class="form-textarea" placeholder="Detailed treatment methodology and area coverage..."><?= e($editingService['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="f_price" class="form-label">Indicative Price</label>
                    <input type="text" name="indicative_price" id="f_price" class="form-input" value="<?= e($editingService['indicative_price'] ?? '') ?>" placeholder="e.g., 2 BHK: ₹1,500 (Indicative)">
                </div>
                <div class="form-group col-6">
                    <label for="f_warranty" class="form-label">Warranty Terms</label>
                    <input type="text" name="warranty_terms" id="f_warranty" class="form-input" value="<?= e($editingService['warranty_terms'] ?? '') ?>" placeholder="e.g., 2-year warranty">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="f_free" class="form-label">Free Service Offer</label>
                    <input type="text" name="free_service_offer" id="f_free" class="form-input" value="<?= e($editingService['free_service_offer'] ?? '') ?>" placeholder="e.g., One service free">
                </div>
                <div class="form-group col-6">
                    <label for="f_order" class="form-label">Display Order</label>
                    <input type="number" name="display_order" id="f_order" class="form-input" value="<?= (int)($editingService['display_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="f_note" class="form-label">Price & Scope Clarification Note</label>
                <input type="text" name="price_note" id="f_note" class="form-input" value="<?= e($editingService['price_note'] ?? 'Indicative price for standard layouts. Final price confirmed after inspection.') ?>">
            </div>

            <div class="form-group">
                <label class="consent-checkbox-label">
                    <input type="checkbox" name="is_active" id="f_active" value="1" <?= (!isset($editingService['is_active']) || $editingService['is_active']) ? 'checked' : '' ?>>
                    <span class="checkbox-custom"></span>
                    <span class="consent-text">Publish on public website (Active)</span>
                </label>
            </div>

            <div class="form-submit-row">
                <button type="submit" class="btn btn-primary"><?= $editingService ? 'Update Service' : 'Save New Service' ?></button>
                <?php if ($editingService): ?>
                    <a href="<?= base_url('admin/services.php') ?>" class="btn btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function resetServiceForm() {
    document.getElementById('f_id').value = '0';
    document.getElementById('formCardTitle').innerText = 'Add New Service';
    document.getElementById('f_name').value = '';
    document.getElementById('f_slug').value = '';
    document.getElementById('f_short').value = '';
    document.getElementById('f_desc').value = '';
    document.getElementById('f_price').value = '';
    document.getElementById('f_warranty').value = '';
    document.getElementById('f_free').value = '';
    document.getElementById('f_order').value = '0';
    document.getElementById('f_active').checked = true;
}
</script>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
