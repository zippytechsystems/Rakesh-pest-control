# Harsha Pest Control — Official Full-Stack Website & REST API

A complete, mobile-first, local-SEO-optimized web application with a lightweight Node.js REST API backend for **Harsha Pest Control**, a pest management and pest product business based in Nacharam, Hyderabad with 20 years of hands-on experience.

---

## Confirmed Business Profile

- **Business Name:** Harsha Pest Control
- **Experience:** 20 Years of localized pest control experience
- **Contact Person:** Rakesh
- **Phone Numbers:**
  - Primary / Direct Call: `+91 63052 35360`
  - Alternative Contact: `+91 88979 06391`
- **Physical Operating Base:** Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076, Telangana, India
- **Core Priority Service Areas (500 076):**
  - **Nacharam (500 076)** — Operating headquarters & central dispatch
  - **Snehapuri Colony (500 076)** — Immediate neighborhood base behind HDFC Bank
  - **HMT Nagar & Babuji Nagar (500 076)** — Residential colony corridors
  - **Habsiguda (500 076 / 500 007)** — Street No. 1 to 8 & Habsiguda X Roads
  - **Tarnaka (500 076 / 500 017)** — University quarters, railway colony & residential sector
- **Extended Neighboring Areas (Scheduled Visits):**
  - Mallapur, Boduppal, Uppal, Mettuguda, Secunderabad, Chengicherla, Narapally, LB Nagar

---

## Local SEO & Search Architecture

This website is designed specifically to maximize local relevance on Google Search and Google Maps for people in and around the 500076 cluster while adhering strictly to white-hat SEO practices:

1. **No Keyword Stuffing or Fake Claims:**  
   Natural, informative copy grounded in real local pest situations (modular kitchen cockroaches, wardrobe termites, garden mosquitoes, cellar rodents). No fake reviews, doorway pages, or false guarantees of #1 search ranking.
2. **Schema.org Structured Data (`LocalBusiness` & `PestControl`):**  
   Valid JSON-LD metadata embedded in `index.html` detailing exact Name, Operating Address, Postal Code (`500076`), GeoCoordinates (`17.4325, 78.5583`), Phone numbers, Founder (`Rakesh`), and comprehensive `areaServed` locality list.
3. **Crawlability & Indexability:**  
   - `robots.txt`: Directs search engines to index canonical content while excluding private storage/API routes.
   - `sitemap.xml`: Complete canonical XML sitemap pointing to `https://harshapestcontrol.in/`.
   - `404.html`: Custom branded error recovery page with direct call buttons for Rakesh.
   - Canonical URL tag: `<link rel="canonical" href="https://harshapestcontrol.in/">`.
4. **Google Business Profile & Search Console Owner Guide:**  
   Detailed step-by-step instructions for Rakesh in [`GOOGLE_SEO_GUIDE.md`](./GOOGLE_SEO_GUIDE.md), highlighting tasks requiring owner actions like profile verification, review requests, and sitemap submission.

---

## Navigation Structure

Both desktop (`#primary-nav`) and mobile (`#mobile-drawer`) navigation feature exact dedicated links:
- **Home** (`#hero`)
- **Pest Control Services** (`#services`)
- **Pest Control Products** (`#products`)
- **Areas We Serve** (`#areas`)
- **About Us** (`#about`)
- **Contact** (`#contact`)

---

## Pest Control Services & Indicative Offers

All service offers are derived from verified notes and clearly marked as indicative estimates subject to property inspection:

1. **Cockroach Treatment:** 2 BHK Residential Flat — **₹1,500** | 2-Year Warranty | One Service Free (Gel baiting & crevice spray)
2. **Termite Treatment:** 2 BHK Woodwork & Cupboards — **₹4,500** | 5-Year Warranty | One Service Free (Precision drill-and-inject barrier)
3. **Mosquito Management:** Outdoor Garden / Shaded Cellar — **₹2,500** | 6-Month Warranty | One Service Free (Foliage barrier spray)
4. **Rat & Rodent Treatment:** Garden, Ground Floor, or Apartment Cellar — **₹3,500** | 2-Year Warranty | One Service Free (Secured bait stations & traps)
5. **Bed Bug Treatment:** Meticulous spot treatment for cot joints, mattress beading, and headboards
6. **Spider Control:** Web clearance and perimeter barrier application for high ceilings and balconies

---

## Pest Control Products Catalog (6 Products)

Customers can order products online or call Rakesh directly:

1. **Cockroach Gel** — **₹550** (Precision dot gel bait for kitchen cabinets)
2. **Cockroach Spray Bottle** — **₹1,200** (Ready-to-use spray for pipes, gullies, and baseboards)
3. **Rat Medicine** — **₹250** (Rodent control bait formulation for cellars and gardens)
4. **Snake Medicine** — **₹1,800** (Reptile deterrent formulation for compound perimeters)
5. **Termite Spray Bottle** — **₹2,000** (Specialized spray bottle for localized wooden cupboard care)
6. **Lizard Medicine (Liquid Deterrent)** — **₹350** (Liquid repellent spray for walls, tube-light corners, and window frames)

Each product card includes:
- **Order Online** button with dynamic quantity stepper and live price calculator modal.
- **Call to Order** button (`tel:+916305235360`) for immediate telephone order.
- **WhatsApp Order** button with pre-filled message text.

---

## File Structure

```
Rakesh pest control/
├── assets/
│   ├── favicon.svg          # Branded crimson shield favicon with Capital "H"
│   ├── logo.svg             # High-res Capital "H" vector logo with security diamond & stars
│   ├── hero.jpg             # Home inspection hero photo
│   └── inspection.jpg       # Woodwork & skirting inspection photo
├── data/
│   ├── enquiries.json       # Persistent JSON storage for service inspection bookings
│   └── orders.json          # Persistent JSON storage for product orders
├── 404.html                 # Branded accessible custom 404 page
├── data.js                  # Central business catalog (Services, Products, Local SEO Areas, Checklist)
├── GOOGLE_SEO_GUIDE.md      # Owner setup guide for Google Business Profile & Search Console
├── index.html               # Semantic HTML5 frontend with Schema.org JSON-LD & accessible modals
├── package.json             # NPM package configuration & scripts
├── README.md                # Master documentation
├── robots.txt               # Search engine crawler directives
├── script.js                # Frontend REST API integration, modals, calculators & tracker
├── serve.js                 # Proxy entry point
├── server.js                # Node.js REST API server + Static asset server (port 5173)
├── sitemap.xml              # Canonical XML sitemap for Google Search Console
├── styles.css               # WCAG AA compliant stylesheet with responsive local SEO grid
└── test_endpoints.js        # Automated verification script testing all routes & REST endpoints
```

---

## REST API Endpoints

The server runs on `http://localhost:5173/` and provides:

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/health` | Server uptime & status check |
| `GET` | `/api/business` | Business details, 20-year experience profile, address, and phones |
| `GET` | `/api/services` | List of 6 core pest control services |
| `GET` | `/api/services/:id` | Single service details (e.g. `/api/services/termite`) |
| `GET` | `/api/plans` | 4 indicative service pricing plans and warranty terms |
| `GET` | `/api/products` | All 6 pest control products with pricing |
| `GET` | `/api/products/:id` | Single product details (e.g. `/api/products/cockroach-gel`) |
| `GET` | `/api/areas` | Local SEO coverage: 5 core localities & 8 extended areas |
| `POST` | `/api/enquiries` | Submit service inspection enquiry & receive `ENQ-...` ID |
| `GET` | `/api/enquiries` | List enquiries (filterable by status) |
| `PATCH` | `/api/enquiries/:id`| Update enquiry status (`pending`, `contacted`, `inspected`, `completed`) |
| `POST` | `/api/orders` | Place product order & receive `ORD-...` ID |
| `GET` | `/api/orders` | List product orders |
| `PATCH` | `/api/orders/:id` | Update order status (`pending`, `confirmed`, `dispatched`, `delivered`) |
| `GET` | `/api/track/:id` | Unified reference ID tracking for customers |
| `GET` | `/api/stats` | Dashboard KPIs (total enquiries, orders, estimated revenue) |

---

## Running & Testing

### 1. Start Server
```bash
npm start
# or: node server.js
```
The server will start on `http://localhost:5173/`.

### 2. Run Route & API Verification Tests
```bash
node test_endpoints.js
```

---
*Developed for Harsha Pest Control — Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad 500 076.*
