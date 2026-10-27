const http = require('http');

function makeRequest(path, method = 'GET', body = null) {
  return new Promise((resolve, reject) => {
    const options = {
      hostname: 'localhost',
      port: 5173,
      path,
      method,
      headers: body ? {
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(JSON.stringify(body))
      } : {}
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        resolve({
          status: res.statusCode,
          headers: res.headers,
          body: data
        });
      });
    });

    req.on('error', reject);
    if (body) req.write(JSON.stringify(body));
    req.end();
  });
}

async function runTests() {
  console.log('--- Starting Route & API Verification ---');
  let failures = 0;

  // 1. Root page
  try {
    const res = await makeRequest('/');
    if (res.status === 200 && res.body.includes('Harsha Pest Control')) {
      console.log('✓ GET / -> 200 OK (Title verified)');
    } else {
      console.error('✗ GET / failed', res.status);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET / error', err.message);
    failures++;
  }

  // 2. robots.txt
  try {
    const res = await makeRequest('/robots.txt');
    if (res.status === 200 && res.body.includes('sitemap.xml')) {
      console.log('✓ GET /robots.txt -> 200 OK (Sitemap reference verified)');
    } else {
      console.error('✗ GET /robots.txt failed', res.status);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /robots.txt error', err.message);
    failures++;
  }

  // 3. sitemap.xml
  try {
    const res = await makeRequest('/sitemap.xml');
    if (res.status === 200 && res.body.includes('https://harshapestcontrol.store/')) {
      console.log('✓ GET /sitemap.xml -> 200 OK (Canonical URL verified)');
    } else {
      console.error('✗ GET /sitemap.xml failed', res.status);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /sitemap.xml error', err.message);
    failures++;
  }

  // 4. Custom 404
  try {
    const res = await makeRequest('/random-fake-url-test');
    if (res.status === 404 && res.body.includes('Page Not Found')) {
      console.log('✓ GET /random-fake-url-test -> 404 (Custom 404 handler verified)');
    } else {
      console.error('✗ GET /random-fake-url-test failed', res.status);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /random-fake-url-test error', err.message);
    failures++;
  }

  // 5. /api/health
  try {
    const res = await makeRequest('/api/health');
    const json = JSON.parse(res.body);
    if (res.status === 200 && json.status === 'ok') {
      console.log('✓ GET /api/health -> 200 OK (Status ok verified)');
    } else {
      console.error('✗ GET /api/health failed', res.status, json);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /api/health error', err.message);
    failures++;
  }

  // 6. /api/areas
  try {
    const res = await makeRequest('/api/areas');
    const json = JSON.parse(res.body);
    if (res.status === 200 && json.success && json.serviceArea.coreLocalities.length >= 5) {
      console.log(`✓ GET /api/areas -> 200 OK (${json.serviceArea.coreLocalities.length} core localities verified)`);
    } else {
      console.error('✗ GET /api/areas failed', res.status, json);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /api/areas error', err.message);
    failures++;
  }

  // 7. /api/products
  try {
    const res = await makeRequest('/api/products');
    const json = JSON.parse(res.body);
    if (res.status === 200 && json.count === 6) {
      console.log(`✓ GET /api/products -> 200 OK (${json.count} products verified)`);
    } else {
      console.error('✗ GET /api/products failed', res.status, json);
      failures++;
    }
  } catch (err) {
    console.error('✗ GET /api/products error', err.message);
    failures++;
  }

  // 8. POST /api/enquiries
  let createdEnqId = null;
  try {
    const res = await makeRequest('/api/enquiries', 'POST', {
      name: 'Ramesh Reddy',
      phone: '9848012345',
      colony: 'HMT Nagar, Nacharam 500076',
      service: 'Cockroach Control (2 BHK Plan ₹1,500*)',
      propertyType: '2 BHK Apartment',
      notes: 'Testing local SEO enquiry registration'
    });
    const json = JSON.parse(res.body);
    if (res.status === 201 && json.success && json.enquiry.id) {
      createdEnqId = json.enquiry.id;
      console.log(`✓ POST /api/enquiries -> 201 Created (ID: ${createdEnqId})`);
    } else {
      console.error('✗ POST /api/enquiries failed', res.status, json);
      failures++;
    }
  } catch (err) {
    console.error('✗ POST /api/enquiries error', err.message);
    failures++;
  }

  // 9. GET /api/track/:id
  if (createdEnqId) {
    try {
      const res = await makeRequest(`/api/track/${createdEnqId}`);
      const json = JSON.parse(res.body);
      if (res.status === 200 && json.success && json.item.id === createdEnqId) {
        console.log(`✓ GET /api/track/${createdEnqId} -> 200 OK (Tracking verified)`);
      } else {
        console.error('✗ GET /api/track failed', res.status, json);
        failures++;
      }
    } catch (err) {
      console.error('✗ GET /api/track error', err.message);
      failures++;
    }
  }

  console.log(`\nVerification Complete: ${failures === 0 ? 'ALL PASSED!' : `${failures} FAILURES`}`);
  process.exit(failures === 0 ? 0 : 1);
}

runTests();
