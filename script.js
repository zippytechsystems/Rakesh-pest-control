/**
 * Harsha Pest Control - Complete Frontend REST API Integration & UI Script
 * 
 * Features:
 * 1. REST API Client for all /api/* endpoints (health, services, products, enquiries, orders, stats, tracking).
 * 2. Live server status ping & indicator badge.
 * 3. Inspection enquiry form submission to POST /api/enquiries with Reference ID & WhatsApp confirmation.
 * 4. Online product ordering modal with live price calculator and POST /api/orders.
 * 5. Unified tracker & Admin activity dashboard with real-time status management (PATCH).
 * 6. Mobile navigation drawer, accessible <dialog> modals, and responsive controls.
 */

// REST API Client Module
const api = {
  async getHealth() {
    try {
      const res = await fetch('/api/health');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return await res.json();
    } catch (err) {
      return { status: 'offline', error: err.message };
    }
  },

  async getBusiness() {
    const res = await fetch('/api/business');
    return await res.json();
  },

  async getServices() {
    const res = await fetch('/api/services');
    return await res.json();
  },

  async getProducts() {
    const res = await fetch('/api/products');
    return await res.json();
  },

  async submitEnquiry(payload) {
    const res = await fetch('/api/enquiries', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.errors ? data.errors.join(' ') : (data.error || 'Failed to submit enquiry'));
    }
    return data;
  },

  async getEnquiries(status = 'all', limit = 20) {
    const res = await fetch(`/api/enquiries?status=${status}&limit=${limit}`);
    return await res.json();
  },

  async updateEnquiryStatus(id, status) {
    const res = await fetch(`/api/enquiries/${encodeURIComponent(id)}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status })
    });
    return await res.json();
  },

  async submitOrder(payload) {
    const res = await fetch('/api/orders', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.errors ? data.errors.join(' ') : (data.error || 'Failed to submit order'));
    }
    return data;
  },

  async getOrders(status = 'all', limit = 20) {
    const res = await fetch(`/api/orders?status=${status}&limit=${limit}`);
    return await res.json();
  },

  async updateOrderStatus(id, status) {
    const res = await fetch(`/api/orders/${encodeURIComponent(id)}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status })
    });
    return await res.json();
  },

  async track(referenceId) {
    const res = await fetch(`/api/track/${encodeURIComponent(referenceId)}`);
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.error || 'Reference ID not found.');
    }
    return data;
  },

  async getStats() {
    const res = await fetch('/api/stats');
    return await res.json();
  }
};

window.HARSHA_API = api;

// Main UI Controller
document.addEventListener('DOMContentLoaded', () => {
  // 1. Current Year in Footer
  const yearEl = document.getElementById('current-year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  // 2. Check Backend Server Health on Load
  const statusPill = document.getElementById('backend-status-pill');
  const statusText = document.getElementById('backend-status-text');

  async function checkServerHealth() {
    if (!statusPill || !statusText) return;
    const health = await api.getHealth();
    if (health.status === 'ok') {
      statusPill.classList.remove('offline');
      statusText.textContent = 'REST API Live (Connected)';
      statusPill.title = `Harsha Pest Control Backend Live | Uptime: ${health.uptime}s`;
    } else {
      statusPill.classList.add('offline');
      statusText.textContent = 'Offline / Standalone';
      statusPill.title = 'Backend server unreachable. Falling back to direct WhatsApp / calls.';
    }
  }
  checkServerHealth();

  // 3. Mobile Navigation Drawer Controls
  const mobileToggle = document.getElementById('mobile-menu-toggle');
  const mobileDrawer = document.getElementById('mobile-drawer');

  if (mobileToggle && mobileDrawer) {
    mobileToggle.addEventListener('click', () => {
      const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';
      mobileToggle.setAttribute('aria-expanded', !isExpanded);
      mobileDrawer.hidden = isExpanded;
    });

    mobileDrawer.querySelectorAll('a, button').forEach(el => {
      el.addEventListener('click', () => {
        mobileToggle.setAttribute('aria-expanded', 'false');
        mobileDrawer.hidden = true;
      });
    });
  }

  // 4. Owner Verification Checklist Modal
  const checklistModal = document.getElementById('owner-checklist-modal');
  const checklistContainer = document.getElementById('checklist-container');
  const checklistTriggers = [
    document.getElementById('open-checklist-btn'),
    document.getElementById('view-owner-checklist-link'),
    document.getElementById('mobile-checklist-btn'),
    document.getElementById('disclaimer-checklist-btn'),
    document.getElementById('footer-checklist-link')
  ].filter(Boolean);

  if (checklistContainer && typeof HARSHA_DATA !== 'undefined' && HARSHA_DATA.ownerVerificationChecklist) {
    checklistContainer.innerHTML = HARSHA_DATA.ownerVerificationChecklist.map((item, idx) => `
      <div class="checklist-item">
        <div class="checklist-item-title">
          <span>${idx + 1}. ${item.label}</span>
          <span class="checklist-status-badge">Action Required</span>
        </div>
        <p class="checklist-item-detail">${item.detail}</p>
      </div>
    `).join('');
  }

  function openModal(modalEl) {
    if (!modalEl) return;
    if (typeof modalEl.showModal === 'function') {
      modalEl.showModal();
    } else {
      modalEl.setAttribute('open', '');
    }
  }

  function closeModal(modalEl) {
    if (!modalEl) return;
    if (typeof modalEl.close === 'function') {
      modalEl.close();
    } else {
      modalEl.removeAttribute('open');
    }
  }

  checklistTriggers.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal(checklistModal);
    });
  });

  const modalCloseBtn = document.getElementById('modal-close-btn');
  const modalDoneBtn = document.getElementById('modal-done-btn');
  if (modalCloseBtn) modalCloseBtn.addEventListener('click', () => closeModal(checklistModal));
  if (modalDoneBtn) modalDoneBtn.addEventListener('click', () => closeModal(checklistModal));

  // 5. Connect Service & Pricing plan selections to Contact Form
  const planButtons = document.querySelectorAll('.select-plan-btn');
  const serviceDropdown = document.getElementById('client-service');

  function selectOptionByPartialText(selectEl, text) {
    if (!selectEl) return;
    const lower = text.toLowerCase();
    for (let i = 0; i < selectEl.options.length; i++) {
      if (selectEl.options[i].text.toLowerCase().includes(lower)) {
        selectEl.selectedIndex = i;
        return;
      }
    }
  }

  planButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const planName = btn.getAttribute('data-plan');
      if (planName && serviceDropdown) {
        selectOptionByPartialText(serviceDropdown, planName.split(' ')[0]);
      }
    });
  });

  document.querySelectorAll('.service-action-link').forEach(link => {
    link.addEventListener('click', () => {
      const serviceName = link.getAttribute('data-service');
      if (serviceName && serviceDropdown) {
        selectOptionByPartialText(serviceDropdown, serviceName.split(' ')[0]);
      }
    });
  });

  // 6. Inspection Enquiry Form Submission (POST /api/enquiries)
  const inspectionForm = document.getElementById('inspection-form');
  const formSubmitBtn = document.getElementById('form-submit-btn');
  const sendWhatsAppBtn = document.getElementById('send-whatsapp-form-btn');
  const previewModal = document.getElementById('enquiry-preview-modal');
  const previewCloseBtn = document.getElementById('enquiry-close-btn');
  const previewDataEl = document.getElementById('enquiry-preview-data');
  const previewSendWaBtn = document.getElementById('preview-send-whatsapp-btn');

  function validateEnquiryForm() {
    let isValid = true;
    const nameInput = document.getElementById('client-name');
    const phoneInput = document.getElementById('client-phone');
    const colonyInput = document.getElementById('client-colony');
    const serviceInput = document.getElementById('client-service');

    const nameError = document.getElementById('name-error');
    const phoneError = document.getElementById('phone-error');
    const colonyError = document.getElementById('colony-error');

    if (nameError) nameError.textContent = '';
    if (phoneError) phoneError.textContent = '';
    if (colonyError) colonyError.textContent = '';

    if (!nameInput.value.trim() || nameInput.value.trim().length < 2) {
      if (nameError) nameError.textContent = 'Please enter your name (at least 2 letters).';
      nameInput.focus();
      isValid = false;
    }

    const cleanPhone = phoneInput.value.replace(/\D/g, '');
    if (!cleanPhone || cleanPhone.length < 10) {
      if (phoneError) phoneError.textContent = 'Please enter a valid 10-digit mobile number.';
      if (isValid) phoneInput.focus();
      isValid = false;
    }

    if (!colonyInput.value.trim()) {
      if (colonyError) colonyError.textContent = 'Please enter your colony / area in Nacharam or nearby.';
      if (isValid) colonyInput.focus();
      isValid = false;
    }

    if (!serviceInput.value) {
      serviceInput.focus();
      isValid = false;
    }

    return isValid;
  }

  function getEnquiryFormData() {
    return {
      name: document.getElementById('client-name').value.trim(),
      phone: document.getElementById('client-phone').value.trim(),
      service: document.getElementById('client-service').value,
      propertyType: document.getElementById('client-property').value,
      colony: document.getElementById('client-colony').value.trim(),
      notes: document.getElementById('client-notes').value.trim()
    };
  }

  function buildWhatsAppUrl(data, referenceId = null) {
    const targetNumber = (typeof HARSHA_DATA !== 'undefined' && HARSHA_DATA.business && HARSHA_DATA.business.whatsapp)
      ? HARSHA_DATA.business.whatsapp.number.replace(/\D/g, '')
      : '916305235360';

    const isProduct = data.service.includes('Gel') || data.service.includes('Spray') || data.service.includes('Medicine');
    const titleHeader = isProduct ? 'Pest Control Product Order' : 'Pest Control Enquiry';
    const refText = referenceId ? ` (Ref ID: ${referenceId})` : '';

    let text = `*${titleHeader}${refText} - Harsha Pest Control*\n`;
    text += `--------------------------\n`;
    text += `*Name:* ${data.name}\n`;
    text += `*Phone:* ${data.phone}\n`;
    text += `*Locality:* ${data.colony}\n`;
    text += `*Requirement:* ${data.service}\n`;
    text += `*Property Type:* ${data.propertyType}\n`;
    if (data.notes) {
      text += `*Notes:* ${data.notes}\n`;
    }
    text += `--------------------------\n`;
    if (isProduct) {
      text += `_Please confirm product availability, packaging, and delivery in Nacharam._`;
    } else {
      text += `_Please confirm inspection schedule and preliminary quote._`;
    }

    return `https://wa.me/${targetNumber}?text=${encodeURIComponent(text)}`;
  }

  // Quick WhatsApp button (direct send without waiting)
  if (sendWhatsAppBtn) {
    sendWhatsAppBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (!validateEnquiryForm()) return;
      const data = getEnquiryFormData();
      const url = buildWhatsAppUrl(data);
      window.open(url, '_blank', 'noopener,noreferrer');
    });
  }

  // Main Form Submit -> Asynchronous REST API POST /api/enquiries
  if (inspectionForm) {
    inspectionForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!validateEnquiryForm()) return;

      const data = getEnquiryFormData();
      const originalBtnHtml = formSubmitBtn.innerHTML;

      // Show loading spinner
      formSubmitBtn.disabled = true;
      formSubmitBtn.innerHTML = `<span class="btn-spinner"></span> Registering with Server...`;

      try {
        const response = await api.submitEnquiry(data);
        const enquiry = response.enquiry || { id: 'ENQ-' + Date.now(), ...data };

        // Populate Confirmation Modal
        if (previewDataEl) {
          previewDataEl.innerHTML = `
            <div class="confirmation-card">
              <div class="confirmation-icon-wrap">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <h3 style="font-size: 1.25rem; font-weight: 800; color: #15803D; margin-bottom: 2px;">Inspection Registered!</h3>
              <p style="font-size: 0.85rem; color: #166534;">Your request is saved in Harsha Pest Control database and assigned to Rakesh.</p>
              <div class="confirmation-id-badge">${enquiry.id}</div>
            </div>

            <div class="preview-row">
              <span class="preview-label">Client Name:</span>
              <span class="preview-value">${enquiry.name}</span>
            </div>
            <div class="preview-row">
              <span class="preview-label">Phone:</span>
              <span class="preview-value">${enquiry.phone}</span>
            </div>
            <div class="preview-row">
              <span class="preview-label">Locality:</span>
              <span class="preview-value">${enquiry.colony}</span>
            </div>
            <div class="preview-row">
              <span class="preview-label">Requirement:</span>
              <span class="preview-value">${enquiry.service}</span>
            </div>
            <div class="preview-row">
              <span class="preview-label">Status:</span>
              <span class="preview-value"><span class="status-pill status-pending">Pending - Assigned to Rakesh</span></span>
            </div>
            ${enquiry.notes ? `
            <div class="preview-row">
              <span class="preview-label">Notes:</span>
              <span class="preview-value">${enquiry.notes}</span>
            </div>` : ''}
          `;
        }

        // Configure WhatsApp button with Reference ID embedded
        if (previewSendWaBtn) {
          previewSendWaBtn.href = buildWhatsAppUrl(data, enquiry.id);
        }

        // Open Confirmation Modal
        openModal(previewModal);

        // Reset form
        inspectionForm.reset();
      } catch (err) {
        console.error('Submission error:', err);
        alert(`Server registration error: ${err.message}\n\nYou can still send your details directly via WhatsApp using the green button.`);
      } finally {
        formSubmitBtn.disabled = false;
        formSubmitBtn.innerHTML = originalBtnHtml;
      }
    });
  }

  if (previewCloseBtn && previewModal) {
    previewCloseBtn.addEventListener('click', () => closeModal(previewModal));
  }

  // 7. Dedicated Product Online Order Modal & Live Calculator (POST /api/orders)
  const productOrderModal = document.getElementById('product-order-modal');
  const orderModalCloseBtn = document.getElementById('order-modal-close-btn');
  const productSelect = document.getElementById('order-product-select');
  const qtyInput = document.getElementById('order-qty-input');
  const qtyMinus = document.getElementById('qty-minus');
  const qtyPlus = document.getElementById('qty-plus');
  const unitPriceDisplay = document.getElementById('order-unit-price-display');
  const calcProductName = document.getElementById('calc-product-name');
  const calcQty = document.getElementById('calc-qty');
  const calcTotalAmount = document.getElementById('calc-total-amount');
  const productOrderForm = document.getElementById('product-order-form');
  const orderSubmitBtn = document.getElementById('order-submit-btn');
  const orderSuccessView = document.getElementById('order-success-view');
  const orderSuccessRefId = document.getElementById('order-success-ref-id');
  const orderSuccessDetails = document.getElementById('order-success-details');
  const orderSuccessWaBtn = document.getElementById('order-success-whatsapp-btn');
  const orderSuccessResetBtn = document.getElementById('order-success-reset-btn');

  // Product Catalog Prices map
  const productPrices = {
    'cockroach-gel': { name: 'Cockroach Gel', price: 550, display: '₹550' },
    'cockroach-spray': { name: 'Cockroach Spray Bottle', price: 1200, display: '₹1,200' },
    'rat-medicine': { name: 'Rat Medicine', price: 250, display: '₹250' },
    'snake-medicine': { name: 'Snake Medicine', price: 1800, display: '₹1,800' },
    'termite-spray': { name: 'Termite Spray Bottle', price: 2000, display: '₹2,000' },
    'lizard-medicine': { name: 'Lizard Medicine (Liquid Deterrent)', price: 350, display: '₹350' }
  };

  function updateOrderCalculation() {
    if (!productSelect) return;
    const prodId = productSelect.value;
    const prod = productPrices[prodId] || { name: 'Pest Product', price: 500, display: '₹500' };
    const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

    const total = prod.price * qty;

    if (unitPriceDisplay) unitPriceDisplay.textContent = prod.display;
    if (calcProductName) calcProductName.textContent = prod.name;
    if (calcQty) calcQty.textContent = `${qty} unit${qty > 1 ? 's' : ''}`;
    if (calcTotalAmount) calcTotalAmount.textContent = `₹${total.toLocaleString('en-IN')}`;
  }

  if (productSelect) productSelect.addEventListener('change', updateOrderCalculation);
  if (qtyMinus) {
    qtyMinus.addEventListener('click', () => {
      const current = parseInt(qtyInput.value || '1', 10);
      if (current > 1) {
        qtyInput.value = current - 1;
        updateOrderCalculation();
      }
    });
  }
  if (qtyPlus) {
    qtyPlus.addEventListener('click', () => {
      const current = parseInt(qtyInput.value || '1', 10);
      qtyInput.value = current + 1;
      updateOrderCalculation();
    });
  }
  if (qtyInput) qtyInput.addEventListener('input', updateOrderCalculation);

  // Open Product Order Modal from any "Order Online" button
  document.querySelectorAll('.btn-order-online').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const prodId = btn.getAttribute('data-product-id');
      if (prodId && productSelect) {
        productSelect.value = prodId;
      }
      if (qtyInput) qtyInput.value = 1;
      updateOrderCalculation();

      // Show form view, hide success view
      if (productOrderForm) productOrderForm.style.display = 'block';
      if (orderSuccessView) orderSuccessView.style.display = 'none';

      openModal(productOrderModal);
    });
  });

  if (orderModalCloseBtn) {
    orderModalCloseBtn.addEventListener('click', () => closeModal(productOrderModal));
  }

  // Handle Product Order Submit -> POST /api/orders
  if (productOrderForm) {
    productOrderForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const nameInput = document.getElementById('order-client-name');
      const phoneInput = document.getElementById('order-client-phone');
      const addressInput = document.getElementById('order-client-address');
      const notesInput = document.getElementById('order-client-notes');

      const nameError = document.getElementById('order-name-error');
      const phoneError = document.getElementById('order-phone-error');
      const addressError = document.getElementById('order-address-error');
      const alertBox = document.getElementById('order-form-alert');

      if (nameError) nameError.textContent = '';
      if (phoneError) phoneError.textContent = '';
      if (addressError) addressError.textContent = '';
      if (alertBox) alertBox.style.display = 'none';

      let isValid = true;
      if (!nameInput.value.trim() || nameInput.value.trim().length < 2) {
        if (nameError) nameError.textContent = 'Please enter your name.';
        nameInput.focus();
        isValid = false;
      }

      const cleanPhone = phoneInput.value.replace(/\D/g, '');
      if (!cleanPhone || cleanPhone.length < 10) {
        if (phoneError) phoneError.textContent = 'Please enter a valid 10-digit mobile number.';
        if (isValid) phoneInput.focus();
        isValid = false;
      }

      if (!addressInput.value.trim()) {
        if (addressError) addressError.textContent = 'Please enter your locality / address in Nacharam.';
        if (isValid) addressInput.focus();
        isValid = false;
      }

      if (!isValid) return;

      const prodId = productSelect.value;
      const prod = productPrices[prodId];
      const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

      const payload = {
        customerName: nameInput.value.trim(),
        phone: cleanPhone,
        productId: prodId,
        productName: prod ? prod.name : prodId,
        quantity: qty,
        deliveryAddress: addressInput.value.trim(),
        notes: notesInput ? notesInput.value.trim() : ''
      };

      const originalBtnHtml = orderSubmitBtn.innerHTML;
      orderSubmitBtn.disabled = true;
      orderSubmitBtn.innerHTML = `<span class="btn-spinner"></span> Placing Order...`;

      try {
        const response = await api.submitOrder(payload);
        const order = response.order;

        if (orderSuccessRefId) orderSuccessRefId.textContent = order.id;
        if (orderSuccessDetails) {
          orderSuccessDetails.innerHTML = `
            <div><strong>Product:</strong> ${order.productName}</div>
            <div><strong>Quantity:</strong> ${order.quantity} unit(s)</div>
            <div><strong>Total Amount:</strong> <span style="font-weight: 800; color: var(--primary);">${order.totalAmountDisplay}</span></div>
            <div><strong>Customer:</strong> ${order.customerName} (${order.phone})</div>
            <div><strong>Delivery Locality:</strong> ${order.deliveryAddress}</div>
            <div><strong>Status:</strong> <span class="status-pill status-pending">Pending - Rakesh will call to confirm</span></div>
          `;
        }

        // WhatsApp message generator
        if (orderSuccessWaBtn) {
          const waMsg = `*Product Order (ID: ${order.id}) - Harsha Pest Control*\n--------------------------\n*Product:* ${order.productName}\n*Qty:* ${order.quantity}\n*Total:* ${order.totalAmountDisplay}\n*Customer:* ${order.customerName}\n*Phone:* ${order.phone}\n*Delivery Area:* ${order.deliveryAddress}\n--------------------------\n_Please confirm stock and arrange delivery / pickup in Nacharam._`;
          orderSuccessWaBtn.href = `https://wa.me/916305235360?text=${encodeURIComponent(waMsg)}`;
        }

        // Switch view to success
        productOrderForm.style.display = 'none';
        if (orderSuccessView) orderSuccessView.style.display = 'block';

        productOrderForm.reset();
      } catch (err) {
        console.error('Order submission error:', err);
        if (alertBox) {
          alertBox.style.display = 'block';
          alertBox.style.backgroundColor = '#FEE2E2';
          alertBox.style.color = '#991B1B';
          alertBox.textContent = `Error: ${err.message}. Please call Rakesh directly on 63052 35360.`;
        }
      } finally {
        orderSubmitBtn.disabled = false;
        orderSubmitBtn.innerHTML = originalBtnHtml;
      }
    });
  }

  if (orderSuccessResetBtn) {
    orderSuccessResetBtn.addEventListener('click', () => {
      if (productOrderForm) productOrderForm.style.display = 'block';
      if (orderSuccessView) orderSuccessView.style.display = 'none';
      if (qtyInput) qtyInput.value = 1;
      updateOrderCalculation();
    });
  }

  // 8. Unified Tracker & Admin Modal Controls (GET /api/track/:id, GET /api/stats, PATCH)
  const trackerModal = document.getElementById('booking-tracker-modal');
  const trackerTriggers = [
    document.getElementById('open-tracker-btn'),
    document.getElementById('mobile-tracker-btn')
  ].filter(Boolean);
  const trackerCloseBtn = document.getElementById('tracker-close-btn');
  const trackerDoneBtn = document.getElementById('tracker-done-btn');

  const tabBtnTrack = document.getElementById('tab-btn-track');
  const tabBtnAdmin = document.getElementById('tab-btn-admin');
  const tabPanelTrack = document.getElementById('tab-panel-track');
  const tabPanelAdmin = document.getElementById('tab-panel-admin');

  const trackIdInput = document.getElementById('track-id-input');
  const trackSearchBtn = document.getElementById('track-search-btn');
  const trackResultContainer = document.getElementById('track-result-container');

  trackerTriggers.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal(trackerModal);
    });
  });

  if (trackerCloseBtn) trackerCloseBtn.addEventListener('click', () => closeModal(trackerModal));
  if (trackerDoneBtn) trackerDoneBtn.addEventListener('click', () => closeModal(trackerModal));

  // Tab switching
  if (tabBtnTrack && tabBtnAdmin) {
    tabBtnTrack.addEventListener('click', () => {
      tabBtnTrack.classList.add('active');
      tabBtnAdmin.classList.remove('active');
      if (tabPanelTrack) tabPanelTrack.style.display = 'block';
      if (tabPanelAdmin) tabPanelAdmin.style.display = 'none';
    });

    tabBtnAdmin.addEventListener('click', async () => {
      tabBtnAdmin.classList.add('active');
      tabBtnTrack.classList.remove('active');
      if (tabPanelTrack) tabPanelTrack.style.display = 'none';
      if (tabPanelAdmin) tabPanelAdmin.style.display = 'block';
      await loadAdminDashboard();
    });
  }

  // Track search action
  async function performTrackSearch() {
    if (!trackIdInput || !trackResultContainer) return;
    const ref = trackIdInput.value.trim();
    if (!ref) {
      alert('Please enter a Reference ID (e.g. ENQ-20261027-XXXX or ORD-20261027-XXXX)');
      trackIdInput.focus();
      return;
    }

    trackResultContainer.style.display = 'block';
    trackResultContainer.innerHTML = `<div style="text-align: center; padding: 20px;"><span class="btn-spinner" style="border-top-color: var(--primary);"></span> Checking status...</div>`;

    try {
      const res = await api.track(ref);
      const record = res.record;
      const isEnq = res.type === 'enquiry';

      trackResultContainer.innerHTML = `
        <div style="background: var(--bg-surface); border: 1px solid var(--border-medium); border-radius: var(--radius-lg); padding: 18px;">
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px; margin-bottom: 12px;">
            <div>
              <span class="modal-badge">${isEnq ? 'SERVICE ENQUIRY' : 'PRODUCT ORDER'}</span>
              <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-top: 4px;">${record.id}</h3>
            </div>
            <span class="status-pill status-${record.status}">${record.status}</span>
          </div>

          <div style="display: grid; grid-template-columns: 1fr; gap: 8px; font-size: 0.9rem;">
            <div><strong>Customer:</strong> ${isEnq ? record.name : record.customerName} (${record.phone})</div>
            <div><strong>${isEnq ? 'Service Requested' : 'Product'}:</strong> ${isEnq ? record.service : `${record.productName} (Qty: ${record.quantity})`}</div>
            <div><strong>Locality:</strong> ${isEnq ? record.colony : record.deliveryAddress}</div>
            ${!isEnq ? `<div><strong>Total Amount:</strong> <strong style="color: var(--primary);">${record.totalAmountDisplay}</strong></div>` : ''}
            <div><strong>Registered Date:</strong> ${new Date(record.createdAt).toLocaleString('en-IN')}</div>
            ${record.notes ? `<div><strong>Notes:</strong> <em>${record.notes}</em></div>` : ''}
          </div>

          <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
            <span>Contact Rakesh on <strong>63052 35360</strong> for updates.</span>
            <a href="tel:+916305235360" class="btn btn-primary btn-sm" style="padding: 4px 10px;">Call Now</a>
          </div>
        </div>
      `;
    } catch (err) {
      trackResultContainer.innerHTML = `
        <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; padding: 14px; border-radius: var(--radius-md); font-size: 0.9rem;">
          <strong>Record Not Found:</strong> ${err.message}. Please verify the ID or call Rakesh directly on 63052 35360.
        </div>
      `;
    }
  }

  if (trackSearchBtn) trackSearchBtn.addEventListener('click', performTrackSearch);
  if (trackIdInput) {
    trackIdInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        performTrackSearch();
      }
    });
  }

  // Load Admin Dashboard
  async function loadAdminDashboard() {
    try {
      const [statsRes, enquiriesRes, ordersRes] = await Promise.all([
        api.getStats(),
        api.getEnquiries('all', 15),
        api.getOrders('all', 15)
      ]);

      const stats = statsRes.stats || {};
      const elEnq = document.getElementById('stat-enquiries-count');
      const elPenEnq = document.getElementById('stat-pending-enquiries');
      const elOrd = document.getElementById('stat-orders-count');
      const elRev = document.getElementById('stat-order-revenue');

      if (elEnq) elEnq.textContent = stats.totalEnquiries || 0;
      if (elPenEnq) elPenEnq.textContent = stats.pendingEnquiries || 0;
      if (elOrd) elOrd.textContent = stats.totalOrders || 0;
      if (elRev) elRev.textContent = `₹${(stats.totalOrderRevenueEstimated || 0).toLocaleString('en-IN')}`;

      // Render Enquiries List
      const enqListEl = document.getElementById('admin-enquiries-list');
      if (enqListEl) {
        const enquiries = enquiriesRes.enquiries || [];
        if (enquiries.length === 0) {
          enqListEl.innerHTML = `<p style="font-size: 0.85rem; color: var(--text-muted);">No enquiries registered yet.</p>`;
        } else {
          enqListEl.innerHTML = enquiries.map(e => `
            <div class="admin-item-card" id="admin-enq-${e.id}">
              <div class="admin-item-header">
                <div>
                  <span class="admin-item-title">${e.name}</span>
                  <span style="font-size: 0.75rem; color: var(--text-subtle);">(${e.id})</span>
                </div>
                <div>
                  <select class="status-select" data-id="${e.id}" data-type="enquiry">
                    <option value="pending" ${e.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="contacted" ${e.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                    <option value="inspected" ${e.status === 'inspected' ? 'selected' : ''}>Inspected</option>
                    <option value="completed" ${e.status === 'completed' ? 'selected' : ''}>Completed</option>
                    <option value="cancelled" ${e.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </div>
              </div>
              <div style="font-size: 0.825rem; color: var(--text-muted);">
                📞 ${e.phone} | 📍 ${e.colony} | 🛠️ ${e.service} (${e.propertyType})
              </div>
            </div>
          `).join('');
        }
      }

      // Render Orders List
      const ordListEl = document.getElementById('admin-orders-list');
      if (ordListEl) {
        const orders = ordersRes.orders || [];
        if (orders.length === 0) {
          ordListEl.innerHTML = `<p style="font-size: 0.85rem; color: var(--text-muted);">No product orders registered yet.</p>`;
        } else {
          ordListEl.innerHTML = orders.map(o => `
            <div class="admin-item-card" id="admin-ord-${o.id}">
              <div class="admin-item-header">
                <div>
                  <span class="admin-item-title">${o.customerName}</span>
                  <span style="font-size: 0.75rem; color: var(--text-subtle);">(${o.id})</span>
                </div>
                <div>
                  <select class="status-select" data-id="${o.id}" data-type="order">
                    <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="confirmed" ${o.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                    <option value="dispatched" ${o.status === 'dispatched' ? 'selected' : ''}>Dispatched</option>
                    <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                    <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                  </select>
                </div>
              </div>
              <div style="font-size: 0.825rem; color: var(--text-muted);">
                📦 ${o.productName} (Qty: ${o.quantity}) — <strong>${o.totalAmountDisplay}</strong><br>
                📞 ${o.phone} | 📍 ${o.deliveryAddress}
              </div>
            </div>
          `).join('');
        }
      }

      // Attach status change event listeners
      document.querySelectorAll('.status-select').forEach(sel => {
        sel.addEventListener('change', async () => {
          const id = sel.getAttribute('data-id');
          const type = sel.getAttribute('data-type');
          const newStatus = sel.value;

          sel.disabled = true;
          try {
            if (type === 'enquiry') {
              await api.updateEnquiryStatus(id, newStatus);
            } else {
              await api.updateOrderStatus(id, newStatus);
            }
          } catch (err) {
            alert(`Failed to update status: ${err.message}`);
          } finally {
            sel.disabled = false;
          }
        });
      });
    } catch (err) {
      console.error('Error loading admin dashboard:', err);
    }
  }
});
