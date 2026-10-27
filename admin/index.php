<?php
/**
 * Harsha Pest Control - Enquiries Dashboard & Lead Management
 */

define('HARSHA_APP', true);

$currentAdminNav = 'enquiries';
$adminTitle = 'Customer Enquiries';

require_once __DIR__ . '/admin-header.php';

$pdo = get_db_connection();

// Handle Status or Note Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_enquiry') {
    if (!verify_csrf()) {
        set_flash('error', 'Session expired. Please try again.');
    } else {
        $enquiryId  = (int)($_POST['enquiry_id'] ?? 0);
        $newStatus  = trim($_POST['status'] ?? 'new');
        $adminNotes = trim($_POST['admin_notes'] ?? '');

        $allowedStatuses = ['new', 'contacted', 'completed', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            $newStatus = 'new';
        }

        try {
            $upd = $pdo->prepare("UPDATE enquiries SET status = :status, admin_notes = :notes WHERE id = :id");
            $upd->execute([
                ':status' => $newStatus,
                ':notes'  => $adminNotes,
                ':id'     => $enquiryId
            ]);
            set_flash('success', "Enquiry #{$enquiryId} updated successfully.");
            header("Location: " . base_url('admin/index.php'));
            exit;
        } catch (Exception $e) {
            set_flash('error', "Failed to update enquiry: " . $e->getMessage());
        }
    }
}

// Filter
$filter = trim($_GET['filter'] ?? 'all');
$whereClause = "";
$params = [];

if (in_array($filter, ['new', 'contacted', 'completed', 'cancelled'])) {
    $whereClause = "WHERE status = :filter";
    $params[':filter'] = $filter;
}

// Fetch KPI stats
$totalEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
$newEnquiries   = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn();
$contactedCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'contacted'")->fetchColumn();
$completedCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'completed'")->fetchColumn();

// Fetch enquiries list
$stmt = $pdo->prepare("SELECT * FROM enquiries {$whereClause} ORDER BY id DESC LIMIT 100");
$stmt->execute($params);
$enquiries = $stmt->fetchAll();
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Customer Enquiries & Leads</h1>
        <p class="admin-page-subtitle">Incoming customer inspection requests and product inquiries stored securely.</p>
    </div>
    <div class="admin-page-actions">
        <a href="<?= base_url('admin/index.php') ?>" class="btn btn-sm btn-outline">Refresh Data ⟳</a>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="kpi-grid">
    <div class="kpi-card">
        <span class="kpi-label">Total Leads</span>
        <span class="kpi-value"><?= $totalEnquiries ?></span>
    </div>
    <div class="kpi-card kpi-card-highlight">
        <span class="kpi-label">New / Pending Call</span>
        <span class="kpi-value"><?= $newEnquiries ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">In Contact / Scheduled</span>
        <span class="kpi-value"><?= $contactedCount ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Completed Services</span>
        <span class="kpi-value"><?= $completedCount ?></span>
    </div>
</div>

<!-- Filter Tabs -->
<div class="admin-card">
    <div class="admin-filter-bar">
        <span class="filter-title">Filter By Status:</span>
        <a href="<?= base_url('admin/index.php?filter=all') ?>" class="filter-tab <?= $filter === 'all' ? 'active' : '' ?>">All (<?= $totalEnquiries ?>)</a>
        <a href="<?= base_url('admin/index.php?filter=new') ?>" class="filter-tab <?= $filter === 'new' ? 'active' : '' ?>">New (<?= $newEnquiries ?>)</a>
        <a href="<?= base_url('admin/index.php?filter=contacted') ?>" class="filter-tab <?= $filter === 'contacted' ? 'active' : '' ?>">Contacted (<?= $contactedCount ?>)</a>
        <a href="<?= base_url('admin/index.php?filter=completed') ?>" class="filter-tab <?= $filter === 'completed' ? 'active' : '' ?>">Completed (<?= $completedCount ?>)</a>
    </div>

    <?php if (empty($enquiries)): ?>
        <div class="empty-state">
            <p>No enquiries found matching this filter.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Service / Product</th>
                        <th>Locality</th>
                        <th>Message</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $row): ?>
                    <tr class="enquiry-row-<?= e($row['status']) ?>">
                        <td>#<?= (int)$row['id'] ?></td>
                        <td><strong><?= e($row['customer_name']) ?></strong></td>
                        <td>
                            <a href="<?= phone_href($row['customer_phone']) ?>" class="phone-link">
                                📞 <?= e($row['customer_phone']) ?>
                            </a>
                        </td>
                        <td><?= e($row['service_or_product'] ?: 'General') ?></td>
                        <td><?= e($row['locality'] ?: 'Hyderabad') ?></td>
                        <td>
                            <span class="cell-message" title="<?= e($row['message']) ?>">
                                <?= e(mb_strimwidth($row['message'] ?? '—', 0, 50, '...')) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                        <td>
                            <span class="status-badge status-<?= e($row['status']) ?>">
                                <?= ucfirst(e($row['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline" onclick="openEnquiryModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">
                                Update
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal for Viewing & Updating Status/Notes -->
<div id="enquiryModal" class="modal-backdrop" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" id="m_title">Update Lead Status</h3>
            <button type="button" class="modal-close" onclick="closeEnquiryModal()">&times;</button>
        </div>
        <form method="POST" action="<?= base_url('admin/index.php') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_enquiry">
            <input type="hidden" name="enquiry_id" id="m_id" value="">

            <div class="modal-body">
                <div class="modal-info-row">
                    <div><strong>Customer:</strong> <span id="m_name"></span></div>
                    <div><strong>Phone:</strong> <a id="m_phone_link" href=""></a></div>
                    <div><strong>Service/Product:</strong> <span id="m_service"></span></div>
                    <div><strong>Locality:</strong> <span id="m_locality"></span></div>
                </div>

                <div class="form-group" style="margin-top:16px;">
                    <label>Full Customer Message:</label>
                    <p class="modal-text-display" id="m_message"></p>
                </div>

                <div class="form-group">
                    <label for="m_status" class="form-label">Lead Status:</label>
                    <select name="status" id="m_status" class="form-select">
                        <option value="new">New (Needs Call)</option>
                        <option value="contacted">Contacted / Inspection Scheduled</option>
                        <option value="completed">Completed / Service Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="m_notes" class="form-label">Internal Follow-up Notes:</label>
                    <textarea name="admin_notes" id="m_notes" rows="3" class="form-textarea" placeholder="e.g., Called on Monday, scheduled inspection for Thursday 10 AM..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEnquiryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEnquiryModal(data) {
    document.getElementById('m_id').value = data.id;
    document.getElementById('m_title').innerText = 'Update Lead #' + data.id + ' - ' + data.customer_name;
    document.getElementById('m_name').innerText = data.customer_name;
    document.getElementById('m_phone_link').href = 'tel:+91' + data.customer_phone;
    document.getElementById('m_phone_link').innerText = data.customer_phone;
    document.getElementById('m_service').innerText = data.service_or_product || 'General';
    document.getElementById('m_locality').innerText = data.locality || 'Hyderabad';
    document.getElementById('m_message').innerText = data.message || '(No message provided)';
    document.getElementById('m_status').value = data.status;
    document.getElementById('m_notes').value = data.admin_notes || '';
    document.getElementById('enquiryModal').style.display = 'flex';
}

function closeEnquiryModal() {
    document.getElementById('enquiryModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/admin-footer.php'; ?>
