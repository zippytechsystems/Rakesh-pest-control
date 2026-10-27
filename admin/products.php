<?php
/**
 * Harsha Pest Control - Products Catalog Management
 */

define('HARSHA_APP', true);

$currentAdminNav = 'products';
$adminTitle = 'Manage Products';

require_once __DIR__ . '/admin-header.php';

$pdo = get_db_connection();

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        set_flash('error', 'Security session expired. Please refresh and try again.');
        header("Location: " . base_url('admin/products.php'));
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'save_product') {
        $id           = (int)($_POST['product_id'] ?? 0);
        $name         = trim($_POST['name'] ?? '');
        $slug         = trim($_POST['slug'] ?? '');
        $price        = (float)($_POST['price'] ?? 0);
        $priceDisplay = trim($_POST['price_display'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $category     = trim($_POST['category'] ?? 'Pest Control Product');
        $isAvailable  = isset($_POST['is_available']) ? 1 : 0;
        $isActive     = isset($_POST['is_active']) ? 1 : 0;
        $order        = (int)($_POST['display_order'] ?? 0);

        if (empty($name)) {
            set_flash('error', 'Product name is required.');
        } else {
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }
            if (empty($priceDisplay)) {
                $priceDisplay = '₹' . number_format($price, 0);
            }

            try {
                if ($id > 0) {
                    $stmt = $pdo->prepare("
                        UPDATE products SET 
                            name = :name, 
                            slug = :slug, 
                            price = :price, 
                            price_display = :price_disp, 
                            description = :description, 
                            category = :category, 
                            is_available = :avail, 
                            is_active = :active, 
                            display_order = :ord 
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        ':name'        => $name,
                        ':slug'        => $slug,
                        ':price'       => $price,
                        ':price_disp'  => $priceDisplay,
                        ':description' => $description,
                        ':category'    => $category,
                        ':avail'       => $isAvailable,
                        ':active'      => $isActive,
                        ':ord'         => $order,
                        ':id'          => $id
                    ]);
                    set_flash('success', "Product '{$name}' updated successfully.");
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO products (name, slug, price, price_display, description, category, is_available, is_active, display_order)
                        VALUES (:name, :slug, :price, :price_disp, :description, :category, :avail, :active, :ord)
                    ");
                    $stmt->execute([
                        ':name'        => $name,
                        ':slug'        => $slug,
                        ':price'       => $price,
                        ':price_disp'  => $priceDisplay,
                        ':description' => $description,
                        ':category'    => $category,
                        ':avail'       => $isAvailable,
                        ':active'      => $isActive,
                        ':ord'         => $order
                    ]);
                    set_flash('success', "New product '{$name}' added successfully.");
                }
            } catch (Exception $e) {
                set_flash('error', "Database error: " . $e->getMessage());
            }
        }
        header("Location: " . base_url('admin/products.php'));
        exit;
    }

    if ($action === 'toggle_availability') {
        $id = (int)($_POST['product_id'] ?? 0);
        $curr = (int)($_POST['current_avail'] ?? 0);
        $new = ($curr === 1) ? 0 : 1;
        $upd = $pdo->prepare("UPDATE products SET is_available = :avail WHERE id = :id");
        $upd->execute([':avail' => $new, ':id' => $id]);
        set_flash('success', "Product counter availability updated.");
        header("Location: " . base_url('admin/products.php'));
        exit;
    }
}

$products = get_all_products();
$editingProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    foreach ($products as $p) {
        if ((int)$p['id'] === $editId) {
            $editingProduct = $p;
            break;
        }
    }
}
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Pest Control Products Catalog</h1>
        <p class="admin-page-subtitle">Manage counter pest formulations, pricing, and live stock availability flags.</p>
    </div>
    <div class="admin-page-actions">
        <a href="#productFormCard" class="btn btn-primary" onclick="resetProductForm()">+ Add New Product</a>
    </div>
</div>

<div class="admin-grid-layout">
    <!-- Left: Products List Table -->
    <div class="admin-card">
        <h2 class="admin-card-title">All Products (<?= count($products) ?>)</h2>
        
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Counter Stock</th>
                        <th>Visibility</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $pr): ?>
                    <tr>
                        <td><?= (int)$pr['display_order'] ?></td>
                        <td>
                            <strong><?= e($pr['name']) ?></strong><br>
                            <small class="text-muted"><?= e($pr['slug']) ?></small>
                        </td>
                        <td><strong><?= e($pr['price_display']) ?></strong></td>
                        <td><?= e($pr['category']) ?></td>
                        <td>
                            <form method="POST" action="<?= base_url('admin/products.php') ?>" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="toggle_availability">
                                <input type="hidden" name="product_id" value="<?= (int)$pr['id'] ?>">
                                <input type="hidden" name="current_avail" value="<?= (int)$pr['is_available'] ?>">
                                <button type="submit" class="stock-toggle-btn <?= $pr['is_available'] ? 'in-stock' : 'out-stock' ?>" title="Click to toggle counter availability">
                                    <?= $pr['is_available'] ? '✓ In Stock' : '✗ Call Ahead' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <span class="status-badge <?= $pr['is_active'] ? 'status-completed' : 'status-cancelled' ?>">
                                <?= $pr['is_active'] ? 'Active' : 'Hidden' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/products.php?edit=' . (int)$pr['id'] . '#productFormCard') ?>" class="btn btn-sm btn-outline">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Add / Edit Form -->
    <div class="admin-card" id="productFormCard">
        <h2 class="admin-card-title" id="pFormTitle"><?= $editingProduct ? 'Edit Product: ' . e($editingProduct['name']) : 'Add New Product' ?></h2>
        
        <form method="POST" action="<?= base_url('admin/products.php') ?>" class="admin-edit-form">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="save_product">
            <input type="hidden" name="product_id" id="p_id" value="<?= (int)($editingProduct['id'] ?? 0) ?>">

            <div class="form-group">
                <label for="p_name" class="form-label">Product Name <span class="required">*</span></label>
                <input type="text" name="name" id="p_name" class="form-input" required value="<?= e($editingProduct['name'] ?? '') ?>" placeholder="e.g., Cockroach Gel">
            </div>

            <div class="form-group">
                <label for="p_slug" class="form-label">URL Slug</label>
                <input type="text" name="slug" id="p_slug" class="form-input" value="<?= e($editingProduct['slug'] ?? '') ?>" placeholder="e.g., cockroach-gel">
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="p_price" class="form-label">Numeric Price (₹) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="price" id="p_price" class="form-input" required value="<?= (float)($editingProduct['price'] ?? 0) ?>" placeholder="550">
                </div>
                <div class="form-group col-6">
                    <label for="p_disp" class="form-label">Display Price Text</label>
                    <input type="text" name="price_display" id="p_disp" class="form-input" value="<?= e($editingProduct['price_display'] ?? '') ?>" placeholder="e.g., ₹550">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="p_cat" class="form-label">Category</label>
                    <input type="text" name="category" id="p_cat" class="form-input" value="<?= e($editingProduct['category'] ?? 'Household Pest Products') ?>">
                </div>
                <div class="form-group col-6">
                    <label for="p_order" class="form-label">Display Order</label>
                    <input type="number" name="display_order" id="p_order" class="form-input" value="<?= (int)($editingProduct['display_order'] ?? 0) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="p_desc" class="form-label">Product Description</label>
                <textarea name="description" id="p_desc" rows="3" class="form-textarea" placeholder="Objective, factual description of formulation..."><?= e($editingProduct['description'] ?? '') ?></textarea>
                <small class="form-help">Do not invent brands, sizes, chemical ingredients, or safety claims.</small>
            </div>

            <div class="form-group">
                <label class="consent-checkbox-label">
                    <input type="checkbox" name="is_available" id="p_avail" value="1" <?= (!isset($editingProduct['is_available']) || $editingProduct['is_available']) ? 'checked' : '' ?>>
                    <span class="checkbox-custom"></span>
                    <span class="consent-text">Currently Available at Counter (In Stock)</span>
                </label>
            </div>

            <div class="form-group">
                <label class="consent-checkbox-label">
                    <input type="checkbox" name="is_active" id="p_active" value="1" <?= (!isset($editingProduct['is_active']) || $editingProduct['is_active']) ? 'checked' : '' ?>>
                    <span class="checkbox-custom"></span>
                    <span class="consent-text">Publish on Website (Active)</span>
                </label>
            </div>

            <div class="form-submit-row">
                <button type="submit" class="btn btn-primary"><?= $editingProduct ? 'Update Product' : 'Save New Product' ?></button>
                <?php if ($editingProduct): ?>
                    <a href="<?= base_url('admin/products.php') ?>" class="btn btn-secondary">Cancel Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function resetProductForm() {
    document.getElementById('p_id').value = '0';
    document.getElementById('pFormTitle').innerText = 'Add New Product';
    document.getElementById('p_name').value = '';
    document.getElementById('p_slug').value = '';
    document.getElementById('p_price').value = '0';
    document.getElementById('p_disp').value = '';
    document.getElementById('p_desc').value = '';
    document.getElementById('p_order').value = '0';
    document.getElementById('p_avail').checked = true;
    document.getElementById('p_active').checked = true;
}
</script>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
