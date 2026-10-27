/**
 * HARSHA SYSTEM MANAGEMENT — Admin Dashboard Controller
 * Handles secure authentication (M.Linga@123 / mr.yadav), live KPI metrics,
 * enquiry status management, product order management, and reference ID tracking.
 */

document.addEventListener('DOMContentLoaded', () => {
  // --------------------------------------------------------------------------
  // State & Cache
  // --------------------------------------------------------------------------
  let authToken = localStorage.getItem('harsha_admin_token') || '';
  let enquiriesCache = [];
  let ordersCache = [];
  let currentEnquiryFilter = 'all';
  let currentOrderFilter = 'all';

  // DOM Elements - Auth
  const loginSection = document.getElementById('login-section');
  const dashboardSection = document.getElementById('dashboard-section');
  const loginForm = document.getElementById('admin-login-form');
  const usernameInput = document.getElementById('admin-username');
  const passwordInput = document.getElementById('admin-password');
  const togglePwdBtn = document.getElementById('toggle-pwd-btn');
  const loginErrorMsg = document.getElementById('login-error-msg');
  const loginSubmitBtn = document.getElementById('btn-login-submit');
  const logoutBtn = document.getElementById('btn-logout');
  const refreshBtn = document.getElementById('btn-refresh');
  const adminDisplayName = document.getElementById('admin-display-name');

  // DOM Elements - KPIs
  const kpiTotalEnquiries = document.getElementById('kpi-total-enquiries');
  const kpiPendingEnquiries = document.getElementById('kpi-pending-enquiries');
  const kpiTotalOrders = document.getElementById('kpi-total-orders');
  const kpiPendingOrdersBadge = document.getElementById('kpi-pending-orders-badge');
  const kpiRevenue = document.getElementById('kpi-revenue');

  // DOM Elements - Tracker
  const adminTrackInput = document.getElementById('admin-track-input');
  const adminTrackBtn = document.getElementById('admin-track-btn');
  const dashTrackResult = document.getElementById('dash-track-result');

  // DOM Elements - Tabs
  const tabBtnEnquiries = document.getElementById('tab-btn-enquiries');
  const tabBtnOrders = document.getElementById('tab-btn-orders');
  const tabBtnSystem = document.getElementById('tab-btn-system');
  const panelEnquiries = document.getElementById('panel-enquiries');
  const panelOrders = document.getElementById('panel-orders');
  const panelSystem = document.getElementById('panel-system');
  const tabCountEnquiries = document.getElementById('tab-count-enquiries');
  const tabCountOrders = document.getElementById('tab-count-orders');

  // DOM Elements - Data Lists
  const enquiriesTbody = document.getElementById('enquiries-tbody');
  const ordersTbody = document.getElementById('orders-tbody');
  const enquirySearchInput = document.getElementById('enquiry-search-input');
  const orderSearchInput = document.getElementById('order-search-input');
  const enquiryStatusFilters = document.getElementById('enquiry-status-filters');
  const orderStatusFilters = document.getElementById('order-status-filters');

  // --------------------------------------------------------------------------
  // Toast Notification System
  // --------------------------------------------------------------------------
  function showToast(message, type = 'success') {
    const toastBox = document.getElementById('toast-box');
    if (!toastBox) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'toast-error' : (type === 'warning' ? 'toast-warning' : '')}`;
    
    let iconSvg = `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;
    if (type === 'error') {
      iconSvg = `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
    } else if (type === 'warning') {
      iconSvg = `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`;
    }

    toast.innerHTML = `<span>${iconSvg}</span> <span>${message}</span>`;
    toastBox.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  // --------------------------------------------------------------------------
  // Password Visibility Toggle
  // --------------------------------------------------------------------------
  if (togglePwdBtn && passwordInput) {
    togglePwdBtn.addEventListener('click', () => {
      const isPwd = passwordInput.type === 'password';
      passwordInput.type = isPwd ? 'text' : 'password';
      togglePwdBtn.innerHTML = isPwd
        ? `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
        : `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
    });
  }

  // --------------------------------------------------------------------------
  // Authentication Engine
  // --------------------------------------------------------------------------
  async function checkExistingAuth() {
    if (!authToken) {
      showLoginView();
      return;
    }

    try {
      const res = await fetch('/api/admin/verify', {
        headers: {
          'Authorization': `Bearer ${authToken}`
        }
      });

      if (res.ok) {
        const data = await res.json();
        if (data.authenticated) {
          if (adminDisplayName && data.user) {
            adminDisplayName.textContent = data.user.name || 'M. Linga';
          }
          showDashboardView();
          await loadDashboardData();
          return;
        }
      }
      // If invalid
      clearAuth();
      showLoginView();
    } catch (err) {
      // Offline fallback: allow local cached session if token exists
      showDashboardView();
      await loadDashboardData();
    }
  }

  function showLoginView() {
    if (loginSection) loginSection.style.display = 'flex';
    if (dashboardSection) dashboardSection.style.display = 'none';
  }

  function showDashboardView() {
    if (loginSection) loginSection.style.display = 'none';
    if (dashboardSection) dashboardSection.style.display = 'flex';
  }

  function clearAuth() {
    authToken = '';
    localStorage.removeItem('harsha_admin_token');
  }

  // Handle Login Form Submit
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      loginErrorMsg.style.display = 'none';
      loginErrorMsg.textContent = '';

      const username = usernameInput.value.trim();
      const password = passwordInput.value.trim();

      if (!username || !password) {
        loginErrorMsg.style.display = 'block';
        loginErrorMsg.textContent = 'Please enter both admin username and password.';
        return;
      }

      loginSubmitBtn.disabled = true;
      loginSubmitBtn.innerHTML = `<span>Authenticating...</span>`;

      try {
        const res = await fetch('/api/admin/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password })
        });

        const data = await res.json();

        if (res.ok && data.success) {
          authToken = data.token;
          localStorage.setItem('harsha_admin_token', authToken);

          if (adminDisplayName && data.user) {
            adminDisplayName.textContent = data.user.name || 'M. Linga';
          }

          showToast('Authentication successful. Welcome, M. Linga!', 'success');
          showDashboardView();
          await loadDashboardData();
        } else {
          loginErrorMsg.style.display = 'block';
          loginErrorMsg.textContent = data.error || 'Invalid credentials. Please verify username and password.';
        }
      } catch (err) {
        loginErrorMsg.style.display = 'block';
        loginErrorMsg.textContent = `Server connection error: ${err.message}. Ensure backend is running.`;
      } finally {
        loginSubmitBtn.disabled = false;
        loginSubmitBtn.innerHTML = `<span>Sign In to Admin Portal</span><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>`;
      }
    });
  }

  // Handle Logout
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      if (confirm('Are you sure you want to sign out from Harsha System Management?')) {
        try {
          await fetch('/api/admin/logout', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${authToken}` }
          });
        } catch (e) {
          // Ignore network errors on logout
        }
        clearAuth();
        showToast('Signed out successfully.', 'warning');
        showLoginView();
        passwordInput.value = '';
      }
    });
  }

  // Handle Manual Refresh
  if (refreshBtn) {
    refreshBtn.addEventListener('click', async () => {
      refreshBtn.disabled = true;
      showToast('Refreshing live system data...', 'success');
      await loadDashboardData();
      refreshBtn.disabled = false;
    });
  }

  // --------------------------------------------------------------------------
  // Data Loader & KPI Metrics
  // --------------------------------------------------------------------------
  async function loadDashboardData() {
    try {
      const headers = {
        'Authorization': `Bearer ${authToken}`
      };

      const [statsRes, enquiriesRes, ordersRes] = await Promise.all([
        fetch('/api/stats', { headers }).then(r => r.json()),
        fetch('/api/enquiries?status=all&limit=100', { headers }).then(r => r.json()),
        fetch('/api/orders?status=all&limit=100', { headers }).then(r => r.json())
      ]);

      // Cache data
      enquiriesCache = enquiriesRes.enquiries || [];
      ordersCache = ordersRes.orders || [];

      // Update KPI Cards
      const stats = statsRes.stats || {};
      const totalEnq = stats.totalEnquiries || enquiriesCache.length;
      const pendingEnq = stats.pendingEnquiries || enquiriesCache.filter(e => e.status === 'pending').length;
      const totalOrd = stats.totalOrders || ordersCache.length;
      const pendingOrd = stats.pendingOrders || ordersCache.filter(o => o.status === 'pending').length;
      const totalRevenue = stats.totalOrderRevenueEstimated || ordersCache.reduce((sum, o) => sum + (o.totalAmount || 0), 0);

      if (kpiTotalEnquiries) kpiTotalEnquiries.textContent = totalEnq;
      if (kpiPendingEnquiries) kpiPendingEnquiries.textContent = pendingEnq;
      if (kpiTotalOrders) kpiTotalOrders.textContent = totalOrd;
      if (kpiPendingOrdersBadge) kpiPendingOrdersBadge.textContent = `${pendingOrd} Pending Action`;
      if (kpiRevenue) kpiRevenue.textContent = `₹${totalRevenue.toLocaleString('en-IN')}`;

      // Update tab counter badges
      if (tabCountEnquiries) tabCountEnquiries.textContent = totalEnq;
      if (tabCountOrders) tabCountOrders.textContent = totalOrd;

      // Render Tables
      renderEnquiries();
      renderOrders();
    } catch (err) {
      console.error('Failed to load dashboard data:', err);
      showToast(`Error loading data: ${err.message}`, 'error');
    }
  }

  // --------------------------------------------------------------------------
  // Render Enquiries Table
  // --------------------------------------------------------------------------
  function renderEnquiries() {
    if (!enquiriesTbody) return;

    const searchTerm = (enquirySearchInput ? enquirySearchInput.value : '').toLowerCase().trim();

    let list = enquiriesCache.filter(e => {
      // Status filter
      if (currentEnquiryFilter !== 'all' && e.status !== currentEnquiryFilter) {
        return false;
      }
      // Text search
      if (searchTerm) {
        const matchesName = (e.name || '').toLowerCase().includes(searchTerm);
        const matchesPhone = (e.phone || '').toLowerCase().includes(searchTerm);
        const matchesColony = (e.colony || '').toLowerCase().includes(searchTerm);
        const matchesId = (e.id || '').toLowerCase().includes(searchTerm);
        const matchesService = (e.service || '').toLowerCase().includes(searchTerm);
        if (!matchesName && !matchesPhone && !matchesColony && !matchesId && !matchesService) {
          return false;
        }
      }
      return true;
    });

    if (list.length === 0) {
      enquiriesTbody.innerHTML = `
        <tr>
          <td colspan="6" class="empty-state">
            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <p>No service enquiries match the selected filter.</p>
          </td>
        </tr>
      `;
      return;
    }

    enquiriesTbody.innerHTML = list.map(e => {
      const rawDate = new Date(e.createdAt);
      const dateStr = !isNaN(rawDate) ? rawDate.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Recent';
      const cleanPhone = (e.phone || '').replace(/\D/g, '');
      const waText = encodeURIComponent(`Hello ${e.name} garu, this is from Harsha Pest Control regarding your inspection enquiry (${e.id}) for ${e.service} in ${e.colony}.`);

      return `
        <tr id="row-enq-${e.id}">
          <td>
            <span class="id-badge">${e.id}</span>
          </td>
          <td>
            <div style="font-weight: 700; color: #fff;">${escapeHtml(e.name)}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
              <a href="tel:+91${cleanPhone}" style="color: #60A5FA; text-decoration: none;">📞 ${e.phone}</a>
            </div>
            <div class="action-links" style="margin-top: 6px;">
              <a href="tel:+91${cleanPhone}" class="btn-action">Call</a>
              <a href="https://wa.me/91${cleanPhone}?text=${waText}" target="_blank" rel="noopener noreferrer" class="btn-action btn-action-wa">WhatsApp</a>
            </div>
          </td>
          <td>
            <div style="font-weight: 600; color: #F1F5F9;">${escapeHtml(e.service)}</div>
            <div style="font-size: 0.775rem; color: var(--text-muted); margin-top: 2px;">
              📍 ${escapeHtml(e.colony)} &bull; ${escapeHtml(e.propertyType || 'Residential')}
            </div>
          </td>
          <td>
            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 200px; white-space: normal; line-height: 1.3;">
              ${e.notes ? `<em>${escapeHtml(e.notes)}</em>` : '<span style="color: var(--text-subtle);">None</span>'}
            </div>
          </td>
          <td>
            <span style="font-size: 0.775rem; color: var(--text-subtle);">${dateStr}</span>
          </td>
          <td>
            <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
              <select class="status-select enq-status-select" data-id="${e.id}">
                <option value="pending" ${e.status === 'pending' ? 'selected' : ''}>⏳ Pending</option>
                <option value="contacted" ${e.status === 'contacted' ? 'selected' : ''}>📞 Contacted</option>
                <option value="inspected" ${e.status === 'inspected' ? 'selected' : ''}>🔍 Inspected</option>
                <option value="completed" ${e.status === 'completed' ? 'selected' : ''}>✅ Completed</option>
                <option value="cancelled" ${e.status === 'cancelled' ? 'selected' : ''}>❌ Cancelled</option>
              </select>
              <span class="status-badge status-${e.status}">${e.status}</span>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    // Attach event listeners to status selects
    document.querySelectorAll('.enq-status-select').forEach(sel => {
      sel.addEventListener('change', async (ev) => {
        const id = sel.getAttribute('data-id');
        const newStatus = sel.value;
        await updateStatus('enquiry', id, newStatus, sel);
      });
    });
  }

  // --------------------------------------------------------------------------
  // Render Product Orders Table
  // --------------------------------------------------------------------------
  function renderOrders() {
    if (!ordersTbody) return;

    const searchTerm = (orderSearchInput ? orderSearchInput.value : '').toLowerCase().trim();

    let list = ordersCache.filter(o => {
      // Status filter
      if (currentOrderFilter !== 'all' && o.status !== currentOrderFilter) {
        return false;
      }
      // Text search
      if (searchTerm) {
        const matchesName = (o.customerName || '').toLowerCase().includes(searchTerm);
        const matchesProduct = (o.productName || '').toLowerCase().includes(searchTerm);
        const matchesId = (o.id || '').toLowerCase().includes(searchTerm);
        const matchesPhone = (o.phone || '').toLowerCase().includes(searchTerm);
        const matchesAddr = (o.deliveryAddress || '').toLowerCase().includes(searchTerm);
        if (!matchesName && !matchesProduct && !matchesId && !matchesPhone && !matchesAddr) {
          return false;
        }
      }
      return true;
    });

    if (list.length === 0) {
      ordersTbody.innerHTML = `
        <tr>
          <td colspan="7" class="empty-state">
            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <p>No product orders match the selected filter.</p>
          </td>
        </tr>
      `;
      return;
    }

    ordersTbody.innerHTML = list.map(o => {
      const rawDate = new Date(o.createdAt);
      const dateStr = !isNaN(rawDate) ? rawDate.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Recent';
      const cleanPhone = (o.phone || '').replace(/\D/g, '');
      const waText = encodeURIComponent(`Hello ${o.customerName} garu, this is from Harsha Pest Control regarding your product order (${o.id}) for ${o.productName} (Qty: ${o.quantity}).`);

      return `
        <tr id="row-ord-${o.id}">
          <td>
            <span class="id-badge" style="color: #34D399;">${o.id}</span>
          </td>
          <td>
            <div style="font-weight: 700; color: #fff;">${escapeHtml(o.customerName)}</div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">
              <a href="tel:+91${cleanPhone}" style="color: #60A5FA; text-decoration: none;">📞 ${o.phone}</a>
            </div>
            <div class="action-links" style="margin-top: 6px;">
              <a href="tel:+91${cleanPhone}" class="btn-action">Call</a>
              <a href="https://wa.me/91${cleanPhone}?text=${waText}" target="_blank" rel="noopener noreferrer" class="btn-action btn-action-wa">WhatsApp</a>
            </div>
          </td>
          <td>
            <div style="font-weight: 600; color: #F1F5F9;">${escapeHtml(o.productName)}</div>
            <span style="font-size: 0.8rem; color: #94A3B8;">Qty: <strong style="color: #fff;">${o.quantity}</strong> &bull; ${o.priceDisplay || ''}</span>
          </td>
          <td>
            <div style="font-weight: 800; color: #34D399; font-size: 1rem;">${o.totalAmountDisplay || `₹${o.totalAmount || 0}`}</div>
          </td>
          <td>
            <div style="font-size: 0.8rem; color: var(--text-muted); max-width: 220px; white-space: normal; line-height: 1.3;">
              📍 ${escapeHtml(o.deliveryAddress || '')}
              ${o.notes ? `<div style="margin-top: 3px; font-style: italic; color: var(--text-subtle);">Note: ${escapeHtml(o.notes)}</div>` : ''}
            </div>
          </td>
          <td>
            <span style="font-size: 0.775rem; color: var(--text-subtle);">${dateStr}</span>
          </td>
          <td>
            <div style="display: flex; flex-direction: column; gap: 6px; align-items: flex-start;">
              <select class="status-select ord-status-select" data-id="${o.id}">
                <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>⏳ Pending</option>
                <option value="confirmed" ${o.status === 'confirmed' ? 'selected' : ''}>📋 Confirmed</option>
                <option value="dispatched" ${o.status === 'dispatched' ? 'selected' : ''}>🚚 Dispatched</option>
                <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>✅ Delivered</option>
                <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>❌ Cancelled</option>
              </select>
              <span class="status-badge status-${o.status}">${o.status}</span>
            </div>
          </td>
        </tr>
      `;
    }).join('');

    // Attach event listeners to status selects
    document.querySelectorAll('.ord-status-select').forEach(sel => {
      sel.addEventListener('change', async (ev) => {
        const id = sel.getAttribute('data-id');
        const newStatus = sel.value;
        await updateStatus('order', id, newStatus, sel);
      });
    });
  }

  // --------------------------------------------------------------------------
  // Real-Time Status Update Function
  // --------------------------------------------------------------------------
  async function updateStatus(type, id, newStatus, selectElement) {
    selectElement.disabled = true;

    try {
      const endpoint = type === 'enquiry' ? `/api/enquiries/${encodeURIComponent(id)}` : `/api/orders/${encodeURIComponent(id)}`;
      const res = await fetch(endpoint, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${authToken}`
        },
        body: JSON.stringify({ status: newStatus })
      });

      const data = await res.json();

      if (res.ok && data.success) {
        showToast(`${type === 'enquiry' ? 'Enquiry' : 'Order'} ${id} updated to "${newStatus.toUpperCase()}"`, 'success');

        // Update in-memory cache
        if (type === 'enquiry') {
          const item = enquiriesCache.find(e => e.id === id);
          if (item) item.status = newStatus;
        } else {
          const item = ordersCache.find(o => o.id === id);
          if (item) item.status = newStatus;
        }

        // Recalculate KPIs and re-render
        loadDashboardData();
      } else {
        showToast(`Failed to update status: ${data.error || 'Server error'}`, 'error');
        loadDashboardData();
      }
    } catch (err) {
      showToast(`Network error updating status: ${err.message}`, 'error');
    } finally {
      selectElement.disabled = false;
    }
  }

  // --------------------------------------------------------------------------
  // Reference ID Inspector ("Track by Reference ID")
  // --------------------------------------------------------------------------
  async function performAdminTrack() {
    if (!adminTrackInput || !dashTrackResult) return;
    const ref = adminTrackInput.value.trim().toUpperCase();

    if (!ref) {
      alert('Please enter a Reference ID (e.g. ENQ-20261027-XXXX or ORD-20261027-XXXX)');
      adminTrackInput.focus();
      return;
    }

    dashTrackResult.style.display = 'block';
    dashTrackResult.innerHTML = `
      <div style="background: rgba(15, 23, 42, 0.6); padding: 16px; border-radius: var(--radius-md); text-align: center; color: var(--text-muted);">
        Searching system database for <code>${ref}</code>...
      </div>
    `;

    try {
      const res = await fetch(`/api/track/${encodeURIComponent(ref)}`);
      const data = await res.json();

      if (!res.ok || !data.success) {
        dashTrackResult.innerHTML = `
          <div style="background: var(--red-bg); border: 1px solid var(--red-border); color: #FCA5A5; padding: 14px; border-radius: var(--radius-md); font-size: 0.9rem;">
            <strong>Record Not Found:</strong> No enquiry or order found with Reference ID <code>${ref}</code>.
          </div>
        `;
        return;
      }

      const isEnq = data.type === 'enquiry';
      const record = data.record;
      const cleanPhone = (isEnq ? record.phone : record.phone || '').replace(/\D/g, '');

      dashTrackResult.innerHTML = `
        <div style="background: rgba(15, 23, 42, 0.85); border: 1px solid var(--border-medium); border-radius: var(--radius-md); padding: 18px; margin-top: 10px;">
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
            <div>
              <span class="brand-badge" style="background: ${isEnq ? 'rgba(59, 130, 246, 0.2)' : 'rgba(16, 185, 129, 0.2)'}; color: ${isEnq ? '#60A5FA' : '#34D399'};">
                ${isEnq ? 'SERVICE ENQUIRY' : 'PRODUCT ORDER'}
              </span>
              <h3 style="font-size: 1.15rem; font-weight: 800; color: #fff; margin-top: 4px; font-family: var(--font-mono);">${record.id}</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
              <span class="status-badge status-${record.status}">${record.status}</span>
              <select id="track-inline-status" class="status-select" style="padding: 4px 8px;">
                ${isEnq ? `
                  <option value="pending" ${record.status === 'pending' ? 'selected' : ''}>Pending</option>
                  <option value="contacted" ${record.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                  <option value="inspected" ${record.status === 'inspected' ? 'selected' : ''}>Inspected</option>
                  <option value="completed" ${record.status === 'completed' ? 'selected' : ''}>Completed</option>
                  <option value="cancelled" ${record.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                ` : `
                  <option value="pending" ${record.status === 'pending' ? 'selected' : ''}>Pending</option>
                  <option value="confirmed" ${record.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                  <option value="dispatched" ${record.status === 'dispatched' ? 'selected' : ''}>Dispatched</option>
                  <option value="delivered" ${record.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                  <option value="cancelled" ${record.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                `}
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 0.875rem;">
            <div><strong style="color: var(--text-muted);">Customer:</strong> <span style="color: #fff;">${escapeHtml(isEnq ? record.name : record.customerName)}</span></div>
            <div><strong style="color: var(--text-muted);">Phone:</strong> <a href="tel:+91${cleanPhone}" style="color: #60A5FA;">${record.phone}</a></div>
            <div><strong style="color: var(--text-muted);">${isEnq ? 'Service' : 'Product'}:</strong> <span style="color: #fff;">${escapeHtml(isEnq ? record.service : `${record.productName} (Qty: ${record.quantity})`)}</span></div>
            <div><strong style="color: var(--text-muted);">Locality:</strong> <span style="color: #fff;">${escapeHtml(isEnq ? record.colony : record.deliveryAddress)}</span></div>
            ${!isEnq ? `<div><strong style="color: var(--text-muted);">Total Amount:</strong> <strong style="color: #34D399;">${record.totalAmountDisplay}</strong></div>` : ''}
            <div><strong style="color: var(--text-muted);">Registered Date:</strong> <span style="color: #94A3B8;">${new Date(record.createdAt).toLocaleString('en-IN')}</span></div>
          </div>

          ${record.notes ? `<div style="margin-top: 10px; font-size: 0.85rem; color: #CBD5E1; background: rgba(0,0,0,0.2); padding: 8px 12px; border-radius: var(--radius-sm);"><strong>Customer Notes:</strong> ${escapeHtml(record.notes)}</div>` : ''}
        </div>
      `;

      // Attach inline status change
      const inlineStatusSelect = document.getElementById('track-inline-status');
      if (inlineStatusSelect) {
        inlineStatusSelect.addEventListener('change', async () => {
          await updateStatus(isEnq ? 'enquiry' : 'order', record.id, inlineStatusSelect.value, inlineStatusSelect);
          performAdminTrack();
        });
      }
    } catch (err) {
      dashTrackResult.innerHTML = `
        <div style="background: var(--red-bg); border: 1px solid var(--red-border); color: #FCA5A5; padding: 14px; border-radius: var(--radius-md); font-size: 0.9rem;">
          Failed to fetch record: ${err.message}
        </div>
      `;
    }
  }

  if (adminTrackBtn) adminTrackBtn.addEventListener('click', performAdminTrack);
  if (adminTrackInput) {
    adminTrackInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        performAdminTrack();
      }
    });
  }

  // --------------------------------------------------------------------------
  // Tab Switching
  // --------------------------------------------------------------------------
  function switchTab(target) {
    [tabBtnEnquiries, tabBtnOrders, tabBtnSystem].forEach(b => b.classList.remove('active'));
    [panelEnquiries, panelOrders, panelSystem].forEach(p => p.style.display = 'none');

    if (target === 'enquiries') {
      tabBtnEnquiries.classList.add('active');
      panelEnquiries.style.display = 'block';
    } else if (target === 'orders') {
      tabBtnOrders.classList.add('active');
      panelOrders.style.display = 'block';
    } else if (target === 'system') {
      tabBtnSystem.classList.add('active');
      panelSystem.style.display = 'block';
    }
  }

  if (tabBtnEnquiries) tabBtnEnquiries.addEventListener('click', () => switchTab('enquiries'));
  if (tabBtnOrders) tabBtnOrders.addEventListener('click', () => switchTab('orders'));
  if (tabBtnSystem) tabBtnSystem.addEventListener('click', () => switchTab('system'));

  // --------------------------------------------------------------------------
  // Filtering & Live Searching
  // --------------------------------------------------------------------------
  if (enquiryStatusFilters) {
    enquiryStatusFilters.addEventListener('click', (e) => {
      const btn = e.target.closest('.filter-btn');
      if (!btn) return;
      enquiryStatusFilters.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentEnquiryFilter = btn.getAttribute('data-status');
      renderEnquiries();
    });
  }

  if (enquirySearchInput) {
    enquirySearchInput.addEventListener('input', () => renderEnquiries());
  }

  if (orderStatusFilters) {
    orderStatusFilters.addEventListener('click', (e) => {
      const btn = e.target.closest('.filter-btn');
      if (!btn) return;
      orderStatusFilters.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentOrderFilter = btn.getAttribute('data-status');
      renderOrders();
    });
  }

  if (orderSearchInput) {
    orderSearchInput.addEventListener('input', () => renderOrders());
  }

  // Helper
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // --------------------------------------------------------------------------
  // Initial Boot
  // --------------------------------------------------------------------------
  checkExistingAuth();
});
