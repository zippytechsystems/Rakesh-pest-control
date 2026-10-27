# Harsha Pest Control — Production Website & Management System
## Target Platform: Hostinger Web Hosting (PHP & MySQL/MariaDB)

A complete, production-ready, mobile-first website for **Harsha Pest Control**, serving residents and commercial property owners across **Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda, Taranaka, and nearby Hyderabad areas**.

Built with a native PHP 8 backend, MySQL/MariaDB database, secure admin portal, anti-spam enquiry processing, and white-hat local SEO.

---

## 🏢 Confirmed Business Details (NAP Consistency)

| Attribute | Verified Information |
| :--- | :--- |
| **Business Name** | Harsha Pest Control |
| **Experience** | 20 Years of hands-on local field experience |
| **Contact Person** | Rakesh |
| **Primary Phone** | `63052 35360` (`+91 63052 35360`) |
| **Secondary Phone** | `88979 06391` (`+91 88979 06391`) |
| **Office Address** | Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076, Telangana |
| **Confirmed Service Areas** | HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas |

*Note: No fake reviews, invented opening hours, false certifications, or duplicate doorway locations are used.*

---

## 🛠️ Technology Stack

- **Server-Side Runtime:** PHP 8.0 / 8.1 / 8.2 / 8.3 (Standard on Hostinger Shared & Cloud Hosting).
- **Database Engine:** MySQL 5.7+ / 8.0+ or MariaDB 10.3+ with `utf8mb4` encoding via PDO.
- **Frontend Architecture:** Semantic HTML5, Vanilla CSS3 (Custom Responsive Design System), Vanilla JavaScript (No heavy frameworks for maximum Core Web Vitals speed).
- **Web Server:** Apache / LiteSpeed with `.htaccess` security rules, Gzip compression, and clean URL rewrites.
- **Admin Management:** Session-authenticated staff dashboard with CSRF protection, password hashing (`PASSWORD_DEFAULT`), and enquiry status tracking.

---

## 📁 Repository & File Structure

```text
├── .htaccess                     # Security headers, file blocks, Gzip compression, rewrite rules
├── .gitignore                    # Prevents committing config.php, logs, and sensitive data
├── config.example.php            # Secure configuration template with Hostinger placeholders
├── schema.sql                    # Full MySQL/MariaDB schema + verified seed data
├── setup.php                     # Secure one-time initial admin setup script (auto-locking)
├── index.php                     # Main front controller & page dispatcher
├── services.php                  # Dedicated Pest Control Services page
├── products.php                  # Dedicated Pest Control Products catalog
├── areas.php                     # Areas We Serve (local coverage guide)
├── about.php                     # About Us & verified 20-year history
├── contact.php                   # Contact page with interactive booking form & direct dial
├── privacy.php                   # Customer data privacy notice
├── submit-enquiry.php            # Validated backend endpoint for customer leads
├── 404.php                       # Custom branded 404 error recovery page
├── robots.txt                    # Search crawler rules (blocks /admin/ and private scripts)
├── sitemap.xml                   # Canonical XML sitemap for Google Search Console
│
├── includes/                     # Private framework includes (protected from direct web access)
│   ├── db.php                    # PDO connection manager with error handling
│   ├── functions.php             # Core security helpers, sanitization, CSRF, rate-limiting
│   ├── header.php                # Semantic HTML head, SEO meta tags, navigation
│   └── footer.php                # Footer columns, sticky mobile action bar, JSON-LD schema
│
├── pages/                        # Individual page content templates
│   ├── home.php                  # Home page layout with indicative pricing & services
│   ├── services.php              # Comprehensive treatment breakdowns & warranties
│   ├── products.php              # Product cards with Call to Order actions
│   ├── areas.php                 # Local coverage and office distinction
│   ├── about.php                 # Authentic background & Rakesh's experience
│   ├── contact.php               # Contact cards & inspection form
│   └── privacy.php               # Privacy details
│
├── admin/                        # Protected Staff Administration Portal (noindex)
│   ├── admin-header.php          # Shared authenticated header & nav tabs
│   ├── admin-footer.php          # Shared authenticated footer
│   ├── index.php                 # Lead management dashboard (view & update status)
│   ├── services.php              # CRUD manager for services, prices, and warranties
│   ├── products.php              # CRUD manager for products, prices, and counter stock
│   ├── settings.php              # Business details, WhatsApp number & SEO metadata
│   ├── password.php              # Secure password change with verification
│   ├── login.php                 # Rate-limited admin login with CSRF
│   └── logout.php                # Clean session destruction
│
└── assets/                       # Static public assets
    ├── css/
    │   ├── style.css             # Main responsive stylesheet & design system
    │   └── admin.css             # Admin dashboard styling
    ├── js/
    │   └── main.js               # Client validation, mobile menu, AJAX form handling
    └── images/                   # Branded icons & verified photos
        ├── logo.svg              # Custom Harsha Pest Control emblem
        ├── favicon.svg           # High-DPI browser icon
        ├── hero.jpg              # Professional pest inspection image
        └── inspection.jpg        # Field application photo
```

---

## 🔒 Security Hardening Implemented

1. **Prepared Statements with PDO:** SQL injection is eliminated across every enquiry insert, admin login, and content query.
2. **Output Escaping:** All user-facing strings pass through `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` to prevent Cross-Site Scripting (XSS).
3. **CSRF Tokens:** All form submissions (enquiries, admin login, service edits, settings updates, password change) require valid session-bound cryptographic tokens.
4. **Honeypot Anti-Spam Trap:** Hidden `website_url` field catches automated spam bots without burdening real users with annoying CAPTCHAs.
5. **Rate Limiting:** Form submissions and login attempts are strictly capped (max 5 attempts per 15-minute window per IP) to prevent spam flooding and brute-force attacks.
6. **Password Hashing:** Passwords are encrypted using PHP's native `password_hash()` with `PASSWORD_DEFAULT` (Bcrypt/Argon2).
7. **Session Security:** Cookies configured with `HttpOnly`, `SameSite=Lax`, strict mode, and session regeneration on privilege escalation.
8. **Sensitive File Protection:** The `.htaccess` file explicitly returns `403 Forbidden` if anyone attempts to access `config.php`, `schema.sql`, `.env`, or the `includes/` directory.

---

## 🚀 Quick Deployment Summary for Hostinger

For full, beginner-friendly instructions, refer to [`HOSTINGER_DEPLOYMENT_GUIDE.md`](./HOSTINGER_DEPLOYMENT_GUIDE.md).

1. **Before Deployment Check:** If you see *"Domain suspended / Verify your email"*, sign into Hostinger hPanel directly, click **Domains**, and complete ICANN email verification.
2. **Create Database:** In Hostinger hPanel -> **Databases** -> **Management**, create a MySQL database (e.g. `u123456789_harsha_db`) and user.
3. **Import Database:** In phpMyAdmin, select your database -> click **Import** -> select [`schema.sql`](./schema.sql) -> click **Import**.
4. **Configure Credentials:** Copy [`config.example.php`](./config.example.php) to `config.php` and enter your database name, user, password, and live domain.
5. **Upload Files:** Upload all project files to Hostinger's `public_html` directory via File Manager or FTP.
6. **Run First-Admin Setup:** Open `https://harshapestcontrol.store/setup.php` in your browser, create your master admin account, and then **DELETE `setup.php`** from `public_html`.
7. **Verify & Test:** Visit your site, test the phone links (`63052 35360`), submit an inspection request, and verify it in `/admin/`.

---

## 📋 Essential Documentation Links

- **Deployment Guide:** [`HOSTINGER_DEPLOYMENT_GUIDE.md`](./HOSTINGER_DEPLOYMENT_GUIDE.md) — Step-by-step setup, database import, file permissions, and backup/restore steps.
- **Search Console & Google Business Profile:** [`SEO_SEARCH_CONSOLE_GUIDE.md`](./SEO_SEARCH_CONSOLE_GUIDE.md) — Step-by-step owner instructions for domain verification, sitemap submission, and Google Maps listing.
- **Owner Launch Checklist:** [`OWNER_CONFIRMATION_CHECKLIST.md`](./OWNER_CONFIRMATION_CHECKLIST.md) — Price, warranty, product availability, and WhatsApp confirmation for Rakesh.
