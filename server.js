/**
 * Harsha Pest Control - Backend REST API & Static Server
 * 
 * Serves:
 * 1. REST APIs (/api/*) for enquiries, product orders, services, products, pricing plans, stats, and tracking.
 * 2. Static website frontend files (HTML, CSS, JS, Assets) on port 5173.
 * 
 * Data persistence: JSON storage in ./data/enquiries.json and ./data/orders.json
 */

const http = require('http');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

// Import business data catalog
const HARSHA_DATA = require('./data.js');

const PORT = process.env.PORT || 5173;
const DATA_DIR = path.join(__dirname, 'data');
const ENQUIRIES_FILE = path.join(DATA_DIR, 'enquiries.json');
const ORDERS_FILE = path.join(DATA_DIR, 'orders.json');

// MIME types for static assets
const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.svg': 'image/svg+xml',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.png': 'image/png',
  '.webp': 'image/webp',
  '.ico': 'image/x-icon',
  '.xml': 'application/xml; charset=utf-8',
  '.txt': 'text/plain; charset=utf-8'
};

// Ensure data directory and files exist
if (!fs.existsSync(DATA_DIR)) {
  fs.mkdirSync(DATA_DIR, { recursive: true });
}
if (!fs.existsSync(ENQUIRIES_FILE)) {
  fs.writeFileSync(ENQUIRIES_FILE, '[]', 'utf8');
}
if (!fs.existsSync(ORDERS_FILE)) {
  fs.writeFileSync(ORDERS_FILE, '[]', 'utf8');
}

// --------------------------------------------------------------------------
// Admin Authentication & Session Management (HARSHA SYSTEM MANAGEMENT)
// --------------------------------------------------------------------------
const ADMIN_CONFIG = {
  username: 'M.Linga@123',
  password: 'mr.yadav',
  name: 'M. Linga',
  role: 'System Administrator'
};

// In-memory active sessions: token -> { username, name, role, loginTime }
const adminSessions = new Map();

function createAdminSession(username) {
  const token = 'adm_' + crypto.randomBytes(24).toString('hex');
  adminSessions.set(token, {
    username,
    name: ADMIN_CONFIG.name,
    role: ADMIN_CONFIG.role,
    loginTime: new Date().toISOString()
  });
  return token;
}

function verifyAdminToken(token) {
  if (!token) return null;
  return adminSessions.get(token) || null;
}

function getBearerToken(req) {
  const authHeader = req.headers['authorization'] || '';
  if (authHeader.toLowerCase().startsWith('bearer ')) {
    return authHeader.substring(7).trim();
  }
  return req.headers['x-admin-token'] || '';
}


// Helper: Read JSON file safely
function readJsonFile(filePath, defaultVal = []) {
  try {
    const raw = fs.readFileSync(filePath, 'utf8');
    return JSON.parse(raw);
  } catch (err) {
    console.error(`Error reading ${filePath}:`, err.message);
    return defaultVal;
  }
}

// Helper: Write JSON file safely
function writeJsonFile(filePath, data) {
  try {
    fs.writeFileSync(filePath, JSON.stringify(data, null, 2), 'utf8');
    return true;
  } catch (err) {
    console.error(`Error writing ${filePath}:`, err.message);
    return false;
  }
}

// Helper: Generate unique human-readable ID
function generateId(prefix = 'ENQ') {
  const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
  const randomHex = crypto.randomBytes(2).toString('hex').toUpperCase();
  return `${prefix}-${dateStr}-${randomHex}`;
}

// Helper: Parse request body JSON
function parseRequestBody(req) {
  return new Promise((resolve, reject) => {
    let body = '';
    req.on('data', chunk => {
      body += chunk.toString();
      // Guard against payloads larger than 1MB
      if (body.length > 1e6) {
        req.connection.destroy();
        reject(new Error('Payload Too Large'));
      }
    });
    req.on('end', () => {
      if (!body.trim()) return resolve({});
      try {
        const parsed = JSON.parse(body);
        resolve(parsed);
      } catch (err) {
        reject(new Error('Invalid JSON'));
      }
    });
    req.on('error', err => reject(err));
  });
}

// Helper: Send JSON response with CORS
function sendJson(res, statusCode, payload) {
  res.writeHead(statusCode, {
    'Content-Type': 'application/json; charset=utf-8',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type, Authorization'
  });
  res.end(JSON.stringify(payload));
}

// Helper: Send CORS preflight response
function handleCorsPreflight(res) {
  res.writeHead(204, {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type, Authorization',
    'Access-Control-Max-Age': '86400'
  });
  res.end();
}

// Server Request Handler
const server = http.createServer(async (req, res) => {
  const parsedUrl = new URL(req.url, `http://${req.headers.host || 'localhost'}`);
  const pathname = parsedUrl.pathname;
  const method = req.method.toUpperCase();

  // Handle CORS Preflight
  if (method === 'OPTIONS') {
    return handleCorsPreflight(res);
  }

  // --------------------------------------------------------------------------
  // REST API Routes (/api/*)
  // --------------------------------------------------------------------------

  // 0a. Admin Login (POST /api/admin/login)
  if (pathname === '/api/admin/login' && method === 'POST') {
    try {
      const payload = await parseRequestBody(req);
      const username = (payload.username || '').trim();
      const password = (payload.password || '').trim();

      if (username === ADMIN_CONFIG.username && password === ADMIN_CONFIG.password) {
        const token = createAdminSession(username);
        return sendJson(res, 200, {
          success: true,
          message: 'Admin authentication successful. Welcome, M. Linga.',
          token,
          user: {
            username: ADMIN_CONFIG.username,
            name: ADMIN_CONFIG.name,
            role: ADMIN_CONFIG.role
          }
        });
      } else {
        return sendJson(res, 401, {
          success: false,
          error: 'Invalid admin username or password. Please verify credentials.'
        });
      }
    } catch (err) {
      return sendJson(res, 400, { success: false, error: err.message });
    }
  }

  // 0b. Admin Verify Token (GET /api/admin/verify)
  if (pathname === '/api/admin/verify' && method === 'GET') {
    const token = getBearerToken(req);
    const session = verifyAdminToken(token);
    if (!session) {
      return sendJson(res, 401, {
        success: false,
        authenticated: false,
        error: 'Unauthorized or session expired.'
      });
    }
    return sendJson(res, 200, {
      success: true,
      authenticated: true,
      user: session
    });
  }

  // 0c. Admin Logout (POST /api/admin/logout)
  if (pathname === '/api/admin/logout' && method === 'POST') {
    const token = getBearerToken(req);
    if (token && adminSessions.has(token)) {
      adminSessions.delete(token);
    }
    return sendJson(res, 200, { success: true, message: 'Logged out successfully.' });
  }

  // 1. Health check
  if (pathname === '/api/health' && method === 'GET') {
    return sendJson(res, 200, {
      status: 'ok',
      service: 'Harsha Pest Control API',
      version: '1.0.0',
      uptime: Math.floor(process.uptime()),
      timestamp: new Date().toISOString()
    });
  }

  // 2. Business profile
  if (pathname === '/api/business' && method === 'GET') {
    return sendJson(res, 200, {
      success: true,
      business: HARSHA_DATA.business,
      aboutUs: HARSHA_DATA.aboutUs
    });
  }

  // 3. Pest control services list
  if (pathname === '/api/services' && method === 'GET') {
    return sendJson(res, 200, {
      success: true,
      count: HARSHA_DATA.services.length,
      services: HARSHA_DATA.services
    });
  }

  // 4. Single service by ID (/api/services/:id)
  if (pathname.startsWith('/api/services/') && method === 'GET') {
    const serviceId = pathname.replace('/api/services/', '').toLowerCase();
    const service = HARSHA_DATA.services.find(s => s.id.toLowerCase() === serviceId);
    if (!service) {
      return sendJson(res, 404, { success: false, error: 'Service not found' });
    }
    return sendJson(res, 200, { success: true, service });
  }

  // 5. Pricing plans
  if (pathname === '/api/plans' && method === 'GET') {
    return sendJson(res, 200, {
      success: true,
      count: HARSHA_DATA.pricingPlans.length,
      plans: HARSHA_DATA.pricingPlans
    });
  }

  // 6. Pest control products catalog
  if (pathname === '/api/products' && method === 'GET') {
    return sendJson(res, 200, {
      success: true,
      count: HARSHA_DATA.products.length,
      products: HARSHA_DATA.products
    });
  }

  // 7. Single product by ID (/api/products/:id)
  if (pathname.startsWith('/api/products/') && method === 'GET') {
    const productId = pathname.replace('/api/products/', '').toLowerCase();
    const product = HARSHA_DATA.products.find(p => p.id.toLowerCase() === productId);
    if (!product) {
      return sendJson(res, 404, { success: false, error: 'Product not found' });
    }
    return sendJson(res, 200, { success: true, product });
  }

  // 7b. Service areas & local SEO coverage (GET /api/areas)
  if (pathname === '/api/areas' && method === 'GET') {
    return sendJson(res, 200, {
      success: true,
      serviceArea: HARSHA_DATA.serviceArea
    });
  }

  // 8. Enquiries: Submit Inspection Request (POST /api/enquiries)
  if (pathname === '/api/enquiries' && method === 'POST') {
    try {
      const payload = await parseRequestBody(req);
      const name = (payload.name || '').trim();
      const phone = (payload.phone || '').trim().replace(/\D/g, '');
      const colony = (payload.colony || '').trim();
      const service = (payload.service || '').trim();
      const propertyType = (payload.propertyType || 'Residential Flat').trim();
      const notes = (payload.notes || '').trim();

      // Validation
      const errors = [];
      if (!name || name.length < 2) errors.push('Please enter a valid customer name (at least 2 characters).');
      if (!phone || phone.length < 10) errors.push('Please enter a valid 10-digit mobile number.');
      if (!colony) errors.push('Please enter your locality in Nacharam or nearby Hyderabad area.');
      if (!service) errors.push('Please select a pest control service.');

      if (errors.length > 0) {
        return sendJson(res, 400, { success: false, errors });
      }

      // Check matched service metadata
      const matchedService = HARSHA_DATA.services.find(s => 
        s.name.toLowerCase() === service.toLowerCase() || 
        service.toLowerCase().includes(s.name.toLowerCase().split(' ')[0])
      );

      const newEnquiry = {
        id: generateId('ENQ'),
        createdAt: new Date().toISOString(),
        name,
        phone,
        colony,
        service,
        serviceId: matchedService ? matchedService.id : 'general',
        propertyType,
        notes,
        status: 'pending', // pending, contacted, inspected, completed, cancelled
        assignedTo: 'Rakesh (Harsha Pest Control)',
        source: 'Website Online Booking'
      };

      const enquiries = readJsonFile(ENQUIRIES_FILE, []);
      enquiries.unshift(newEnquiry);
      writeJsonFile(ENQUIRIES_FILE, enquiries);

      return sendJson(res, 201, {
        success: true,
        message: 'Inspection request registered successfully. Rakesh will contact you shortly.',
        enquiry: newEnquiry
      });
    } catch (err) {
      return sendJson(res, 400, { success: false, error: err.message });
    }
  }

  // 9. Enquiries: List all enquiries (GET /api/enquiries)
  if (pathname === '/api/enquiries' && method === 'GET') {
    const statusFilter = parsedUrl.searchParams.get('status');
    const limit = parseInt(parsedUrl.searchParams.get('limit') || '50', 10);
    let enquiries = readJsonFile(ENQUIRIES_FILE, []);

    if (statusFilter && statusFilter !== 'all') {
      enquiries = enquiries.filter(e => e.status === statusFilter);
    }

    return sendJson(res, 200, {
      success: true,
      total: enquiries.length,
      enquiries: enquiries.slice(0, limit)
    });
  }

  // 10. Enquiries: Get single enquiry (GET /api/enquiries/:id)
  if (pathname.startsWith('/api/enquiries/') && method === 'GET') {
    const id = pathname.replace('/api/enquiries/', '').trim();
    const enquiries = readJsonFile(ENQUIRIES_FILE, []);
    const enquiry = enquiries.find(e => e.id.toLowerCase() === id.toLowerCase());
    if (!enquiry) {
      return sendJson(res, 404, { success: false, error: `Enquiry ${id} not found` });
    }
    return sendJson(res, 200, { success: true, enquiry });
  }

  // 11. Enquiries: Update status (PATCH /api/enquiries/:id)
  if (pathname.startsWith('/api/enquiries/') && method === 'PATCH') {
    const id = pathname.replace('/api/enquiries/', '').trim();
    try {
      const updates = await parseRequestBody(req);
      const enquiries = readJsonFile(ENQUIRIES_FILE, []);
      const index = enquiries.findIndex(e => e.id.toLowerCase() === id.toLowerCase());

      if (index === -1) {
        return sendJson(res, 404, { success: false, error: `Enquiry ${id} not found` });
      }

      const validStatuses = ['pending', 'contacted', 'inspected', 'completed', 'cancelled'];
      if (updates.status && !validStatuses.includes(updates.status)) {
        return sendJson(res, 400, {
          success: false,
          error: `Invalid status. Allowed: ${validStatuses.join(', ')}`
        });
      }

      enquiries[index] = {
        ...enquiries[index],
        ...updates,
        updatedAt: new Date().toISOString()
      };

      writeJsonFile(ENQUIRIES_FILE, enquiries);
      return sendJson(res, 200, {
        success: true,
        message: `Enquiry ${id} updated.`,
        enquiry: enquiries[index]
      });
    } catch (err) {
      return sendJson(res, 400, { success: false, error: err.message });
    }
  }

  // 12. Orders: Submit Product Order (POST /api/orders)
  if (pathname === '/api/orders' && method === 'POST') {
    try {
      const payload = await parseRequestBody(req);
      const customerName = (payload.customerName || '').trim();
      const phone = (payload.phone || '').trim().replace(/\D/g, '');
      const productId = (payload.productId || '').trim();
      const quantity = Math.max(1, parseInt(payload.quantity || '1', 10));
      const deliveryAddress = (payload.deliveryAddress || '').trim();
      const notes = (payload.notes || '').trim();

      const errors = [];
      if (!customerName || customerName.length < 2) errors.push('Please enter your name.');
      if (!phone || phone.length < 10) errors.push('Please enter a valid 10-digit mobile number.');
      if (!deliveryAddress) errors.push('Please enter delivery or pickup address in Nacharam.');

      // Match product from catalog
      const matchedProduct = HARSHA_DATA.products.find(p => 
        p.id.toLowerCase() === productId.toLowerCase() ||
        p.name.toLowerCase() === (payload.productName || '').toLowerCase()
      );

      if (!matchedProduct) {
        errors.push('Please select a valid product from our catalog.');
      }

      if (errors.length > 0) {
        return sendJson(res, 400, { success: false, errors });
      }

      const unitPrice = matchedProduct.rawPrice;
      const totalAmount = unitPrice * quantity;

      const newOrder = {
        id: generateId('ORD'),
        createdAt: new Date().toISOString(),
        customerName,
        phone,
        productId: matchedProduct.id,
        productName: matchedProduct.name,
        unitPrice,
        priceDisplay: matchedProduct.price,
        quantity,
        totalAmount,
        totalAmountDisplay: `₹${totalAmount.toLocaleString('en-IN')}`,
        deliveryAddress,
        notes,
        status: 'pending', // pending, confirmed, dispatched, delivered, cancelled
        vendorContact: 'Rakesh - 63052 35360',
        pickupAddress: HARSHA_DATA.business.address.full
      };

      const orders = readJsonFile(ORDERS_FILE, []);
      orders.unshift(newOrder);
      writeJsonFile(ORDERS_FILE, orders);

      return sendJson(res, 201, {
        success: true,
        message: `Order for ${matchedProduct.name} placed successfully. Rakesh will call to confirm pickup/delivery.`,
        order: newOrder
      });
    } catch (err) {
      return sendJson(res, 400, { success: false, error: err.message });
    }
  }

  // 13. Orders: List all orders (GET /api/orders)
  if (pathname === '/api/orders' && method === 'GET') {
    const statusFilter = parsedUrl.searchParams.get('status');
    const limit = parseInt(parsedUrl.searchParams.get('limit') || '50', 10);
    let orders = readJsonFile(ORDERS_FILE, []);

    if (statusFilter && statusFilter !== 'all') {
      orders = orders.filter(o => o.status === statusFilter);
    }

    return sendJson(res, 200, {
      success: true,
      total: orders.length,
      orders: orders.slice(0, limit)
    });
  }

  // 14. Orders: Get single order (GET /api/orders/:id)
  if (pathname.startsWith('/api/orders/') && method === 'GET') {
    const id = pathname.replace('/api/orders/', '').trim();
    const orders = readJsonFile(ORDERS_FILE, []);
    const order = orders.find(o => o.id.toLowerCase() === id.toLowerCase());
    if (!order) {
      return sendJson(res, 404, { success: false, error: `Order ${id} not found` });
    }
    return sendJson(res, 200, { success: true, order });
  }

  // 15. Orders: Update status (PATCH /api/orders/:id)
  if (pathname.startsWith('/api/orders/') && method === 'PATCH') {
    const id = pathname.replace('/api/orders/', '').trim();
    try {
      const updates = await parseRequestBody(req);
      const orders = readJsonFile(ORDERS_FILE, []);
      const index = orders.findIndex(o => o.id.toLowerCase() === id.toLowerCase());

      if (index === -1) {
        return sendJson(res, 404, { success: false, error: `Order ${id} not found` });
      }

      const validStatuses = ['pending', 'confirmed', 'dispatched', 'delivered', 'cancelled'];
      if (updates.status && !validStatuses.includes(updates.status)) {
        return sendJson(res, 400, {
          success: false,
          error: `Invalid status. Allowed: ${validStatuses.join(', ')}`
        });
      }

      orders[index] = {
        ...orders[index],
        ...updates,
        updatedAt: new Date().toISOString()
      };

      writeJsonFile(ORDERS_FILE, orders);
      return sendJson(res, 200, {
        success: true,
        message: `Order ${id} updated.`,
        order: orders[index]
      });
    } catch (err) {
      return sendJson(res, 400, { success: false, error: err.message });
    }
  }

  // 16. Unified Tracking (GET /api/track/:referenceId)
  if (pathname.startsWith('/api/track/') && method === 'GET') {
    const ref = pathname.replace('/api/track/', '').trim().toUpperCase();
    const enquiries = readJsonFile(ENQUIRIES_FILE, []);
    const orders = readJsonFile(ORDERS_FILE, []);

    const enquiryMatch = enquiries.find(e => e.id.toUpperCase() === ref);
    if (enquiryMatch) {
      return sendJson(res, 200, {
        success: true,
        type: 'enquiry',
        record: enquiryMatch,
        item: enquiryMatch
      });
    }

    const orderMatch = orders.find(o => o.id.toUpperCase() === ref);
    if (orderMatch) {
      return sendJson(res, 200, {
        success: true,
        type: 'order',
        record: orderMatch,
        item: orderMatch
      });
    }

    return sendJson(res, 404, {
      success: false,
      error: `Reference ID ${ref} not found in system.`
    });
  }

  // 17. Overall Stats & Dashboard Summary (GET /api/stats)
  if (pathname === '/api/stats' && method === 'GET') {
    const enquiries = readJsonFile(ENQUIRIES_FILE, []);
    const orders = readJsonFile(ORDERS_FILE, []);

    const totalOrderRevenue = orders.reduce((sum, o) => sum + (o.totalAmount || 0), 0);

    return sendJson(res, 200, {
      success: true,
      stats: {
        totalEnquiries: enquiries.length,
        pendingEnquiries: enquiries.filter(e => e.status === 'pending').length,
        totalOrders: orders.length,
        pendingOrders: orders.filter(o => o.status === 'pending').length,
        totalOrderRevenueEstimated: totalOrderRevenue,
        servicesCount: HARSHA_DATA.services.length,
        productsCount: HARSHA_DATA.products.length,
        plansCount: HARSHA_DATA.pricingPlans.length,
        serverUptimeSec: Math.floor(process.uptime())
      }
    });
  }

  // --------------------------------------------------------------------------
  // Static File Serving (Frontend)
  // --------------------------------------------------------------------------

  if (method === 'GET') {
    let reqPath = pathname;
    if (reqPath === '/' || reqPath === '') reqPath = '/index.html';
    if (reqPath === '/admin' || reqPath === '/admin/') reqPath = '/admin.html';

    // Prevent directory traversal
    const safePath = path.normalize(reqPath).replace(/^(\.\.[\/\\])+/, '');
    const filePath = path.join(__dirname, safePath);

    // Verify file stays within workspace
    if (!filePath.startsWith(__dirname)) {
      res.writeHead(403, { 'Content-Type': 'text/plain; charset=utf-8' });
      return res.end('403 Forbidden');
    }

    fs.readFile(filePath, (err, data) => {
      if (err) {
        if (err.code === 'ENOENT') {
          const errorPagePath = path.join(__dirname, '404.html');
          fs.readFile(errorPagePath, (e404, notFoundHtml) => {
            if (!e404) {
              res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
              res.end(notFoundHtml);
            } else {
              res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' });
              res.end('404 Not Found');
            }
          });
        } else {
          res.writeHead(500, { 'Content-Type': 'text/plain; charset=utf-8' });
          res.end('500 Internal Server Error');
        }
        return;
      }

      const ext = path.extname(filePath).toLowerCase();
      const contentType = MIME_TYPES[ext] || 'application/octet-stream';

      res.writeHead(200, {
        'Content-Type': contentType,
        'Cache-Control': ext === '.html' ? 'no-cache' : 'public, max-age=3600'
      });
      res.end(data);
    });
    return;
  }

  // Fallback for unsupported methods
  res.writeHead(405, { 'Content-Type': 'text/plain; charset=utf-8' });
  res.end('405 Method Not Allowed');
});

server.listen(PORT, () => {
  console.log(`====================================================`);
  console.log(` Harsha Pest Control REST Server & Frontend `);
  console.log(` URL: http://localhost:${PORT}/`);
  console.log(` REST APIs: http://localhost:${PORT}/api/health`);
  console.log(`====================================================`);
});

module.exports = server;
