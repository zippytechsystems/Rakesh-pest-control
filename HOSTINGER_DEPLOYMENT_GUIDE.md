# Hostinger Deployment & Database Setup Guide
## Harsha Pest Control — Production Website

This guide walks you through deploying the complete PHP + MySQL website for **Harsha Pest Control** to **Hostinger Web Hosting**.

---

## ⚠️ STEP 0: Before Deployment — Domain Suspended / Email Verification Check
> **[Owner Action Required]**  
> If you have seen a notice such as *"Domain suspended / Verify your email address"*, follow these steps before uploading or expecting the website to load:

1. **Sign in directly through your official provider website:**
   - Go to [hostinger.com](https://www.hostinger.com) (or your domain registrar website if purchased elsewhere).
   - Sign in using your registered email and password. *Never click suspicious email links, and never share your password, OTPs, recovery codes, or payment details with anyone.*
2. **Check your Domain Status:**
   - In Hostinger hPanel, click **Domains** in the top navigation.
   - Look at the status next to your domain name:
     - **Active:** Domain is healthy and ready.
     - **Pending Verification / Suspended:** ICANN requires domain contact email verification within 15 days of registration.
3. **Resend / Complete Email Verification:**
   - If suspended due to pending verification, look for a banner: **"Resend verification email"** or check the mailbox of the email address listed in the WHOIS contact details.
   - Open the official verification email and click the confirmation link.
   - Verification typically updates status within 15–60 minutes.
4. **Confirm DNS / Nameservers:**
   - If the domain was registered on Hostinger, ensure it is pointing to Hostinger nameservers (e.g., `ns1.dns-parking.com`, `ns2.dns-parking.com` or standard Hostinger hosting nameservers).
   - If registered through a third party (GoDaddy, Namecheap, etc.), ensure the A Record points to your Hostinger Hosting IP address.

---

## STEP 1: Confirm Hosting Plan Capabilities

Before beginning, check your Hostinger plan settings in hPanel:
- **Hosting Type:** Hostinger Single, Premium, Business, or Cloud Hosting.
- **PHP Version:** PHP 8.1, 8.2, or 8.3 (Go to **hPanel -> Advanced -> PHP Configuration** to verify/switch to PHP 8.2).
- **Database Engine:** MySQL 5.7+ / MySQL 8.0 or MariaDB 10.3+ (all Hostinger plans natively support this).
- **Document Root:** Usually `/home/u123456789/domains/yourdomain.com/public_html` (or simply `public_html`).

---

## STEP 2: Create MySQL Database & Database User in Hostinger

1. Open **hPanel** -> navigate to **Databases** -> **Management**.
2. Under **Create a New MySQL Database And Database User**, fill in:
   - **MySQL Database name:** enter a suffix (e.g. `harsha_db`). The full name will be like `u123456789_harsha_db`.
   - **MySQL Username:** enter a suffix (e.g. `harsha_user`). The full username will be like `u123456789_harsha_user`.
   - **Password:** Generate or enter a strong database password (store this safely in a password manager).
3. Click **Create**.
4. Note down the exact values:
   - Database Name: `u123456789_harsha_db`
   - Database Username: `u123456789_harsha_user`
   - Database Host: `localhost` (Hostinger's standard internal host)
   - Database Password: `[YOUR_CHOSEN_PASSWORD]`

---

## STEP 3: Import `schema.sql` via phpMyAdmin

1. On the same **Databases** page in hPanel, look at the **List of Current MySQL Databases And Users**.
2. Click the **Enter phpMyAdmin** button next to your newly created database.
3. In phpMyAdmin, click the database name in the left sidebar to select it.
4. Click the **Import** tab in the top navigation bar.
5. Under **File to import**, click **Choose File** and select [`schema.sql`](file:///c:/Users/madya/OneDrive/Microsoft%20Copilot%20Chat%20Files/Rakesh%20pest%20control/schema.sql) from your local project folder.
6. Ensure **Character set of the file** is set to `utf-8`.
7. Scroll to the bottom and click **Import** (or **Go**).
8. You should see a green success banner: *"Import has been successfully finished, 7 tables created."*

Tables created:
- `settings` (pre-seeded with Harsha Pest Control details)
- `page_seo` (pre-seeded with custom page titles and meta descriptions)
- `services` (pre-seeded with 6 services, indicative prices, and warranties)
- `products` (pre-seeded with 5 products and prices)
- `enquiries` (table to receive customer inspection requests)
- `admin_users` (table for authorized staff login)
- `rate_limits` (table for anti-spam and brute-force protection)

---

## STEP 4: Configure `config.php` Securely

1. In your local project folder, make a copy of [`config.example.php`](file:///c:/Users/madya/OneDrive/Microsoft%20Copilot%20Chat%20Files/Rakesh%20pest%20control/config.example.php) and rename it to `config.php`.
2. Open `config.php` in a text editor.
3. Update the database credentials with your exact Hostinger details:
   ```php
   'db' => [
       'host'     => 'localhost',
       'port'     => 3306,
       'dbname'   => 'u123456789_harsha_db',     // Your exact DB name from Step 2
       'username' => 'u123456789_harsha_user',   // Your exact DB user from Step 2
       'password' => 'YOUR_STRONG_DB_PASSWORD',  // Your exact DB password
       'charset'  => 'utf8mb4',
   ],
   ```
4. Update application settings:
   ```php
   'app' => [
       'env'       => 'production',
       'base_url'  => 'https://yourdomain.com', // Replace with your live HTTPS domain
       'setup_key' => 'ChooseARandomSecretPassphraseHere123!', // Used once in setup.php
       'timezone'  => 'Asia/Kolkata',
   ],
   ```
5. Save `config.php`.

---

## STEP 5: Upload Files to Hostinger `public_html`

You can upload using **Hostinger File Manager** (recommended for simplicity) or **FTP / FileZilla**:

### Method A: Hostinger File Manager (Quickest)
1. In hPanel, go to **Files** -> **File Manager** -> **Access files of [your domain]**.
2. Double-click to enter the `public_html` directory.
3. If there is a default Hostinger placeholder file (`default.php` or `index.html`), delete it.
4. Compress your project files on your computer into a `.zip` file (excluding `node_modules` or local git folders).
5. In File Manager, click the **Upload** icon (top-right) -> choose your `.zip` archive.
6. Once uploaded, right-click the zip file in File Manager and select **Extract** -> choose `public_html` as destination.
7. Verify that files are directly in `public_html`:
   - `public_html/index.php`
   - `public_html/services.php`
   - `public_html/products.php`
   - `public_html/areas.php`
   - `public_html/about.php`
   - `public_html/contact.php`
   - `public_html/privacy.php`
   - `public_html/setup.php`
   - `public_html/config.php`
   - `public_html/.htaccess`
   - `public_html/robots.txt`
   - `public_html/sitemap.xml`
   - `public_html/includes/`
   - `public_html/pages/`
   - `public_html/admin/`
   - `public_html/assets/`

---

## STEP 6: Configure HTTPS / SSL & File Permissions

1. **Activate Free SSL:**
   - In hPanel, go to **Security** -> **SSL**.
   - If not already active, click **Install SSL** (Hostinger provides free Let's Encrypt lifetime SSL).
   - Toggle **Force HTTPS** to ON.
2. **Check File Permissions:**
   - Hostinger sets standard permissions automatically:
     - Directories: `0755`
     - PHP & static files: `0644`
   - Verify `.htaccess` is present in `public_html`. The `.htaccess` file prevents anyone from downloading `config.php`, `schema.sql`, or reading files in `includes/`.

---

## STEP 7: Run First-Admin Setup & Lock Route

1. Open your browser and navigate to:  
   `https://yourdomain.com/setup.php`
2. You will see the **Create Initial Administrator** form.
3. If you configured a `setup_key` in `config.php`, enter it.
4. Choose an **Admin Username** (e.g. `rakesh_admin`) and enter a **Strong Password** (minimum 8 characters).
5. Click **Create Admin Account**.
6. A success message will appear confirming account creation.

> 🔒 **MANDATORY SECURITY ACTION [Owner Action Required]:**  
> Immediately open Hostinger File Manager and **DELETE <code>setup.php</code>** from `public_html`.  
> Even though `setup.php` automatically locks once an admin user exists, physically deleting the file removes all risk of unauthorized access.

---

## STEP 8: Verification & Testing Checklist

Once deployed, perform these verification tests:

| Test Item | What to Check | Expected Result |
| :--- | :--- | :--- |
| **Home Page** | Visit `https://yourdomain.com/` | Loads fast with hero, 20 yrs badge, indicative pricing, and services |
| **Direct Call Links** | Tap `63052 35360` and `88979 06391` on mobile | Prompts phone dialer with `+916305235360` |
| **Pest Services** | Visit `/services.php` | 6 service cards display indicative prices and warranty tags |
| **Products Page** | Visit `/products.php` | 5 products display prices and "Call to Order" buttons |
| **Areas We Serve** | Visit `/areas.php` | Highlights Nacharam office and service localities |
| **Enquiry Submission** | Fill out inspection form on `/contact.php` | Success message displays; lead is stored in MySQL |
| **Admin Portal** | Visit `/admin/login.php` | Log in with credentials created in Step 7 |
| **Enquiry Record** | Check `/admin/index.php` | Newly submitted test lead appears in table with timestamp |
| **Change Status** | Update status to "Contacted" with a note | Status updates cleanly in database |
| **Robots & Sitemap** | Visit `/robots.txt` & `/sitemap.xml` | Displays public URLs; `/admin/` is blocked |
| **Security Check** | Visit `/config.php` directly in browser | Returns `403 Forbidden` (protected by `.htaccess`) |

---

## STEP 9: Backup & Restore Procedures

### How to Back Up Your Website (Monthly or Before Major Changes)

#### 1. Database Backup via phpMyAdmin:
1. Log in to Hostinger hPanel -> **Databases** -> **phpMyAdmin**.
2. Click your database (`u123456789_harsha_db`).
3. Click the **Export** tab.
4. Choose **Quick** export method and Format **SQL**.
5. Click **Export**. Save the `.sql` backup file to your local computer with a date (e.g. `harsha_backup_2026_10.sql`).

#### 2. Website Files Backup via hPanel:
1. In hPanel, go to **Files** -> **Backups**.
2. Select **Files backups** -> choose the date -> click **Prepare to download** or use the **Generate new backup** tool.
3. Download the zip archive to your secure storage.

### How to Restore Your Website from Backup

#### 1. Restoring Database:
1. Open phpMyAdmin for your database.
2. Select all existing tables and click **Drop** (with foreign key checks disabled).
3. Click the **Import** tab -> choose your `.sql` backup file -> click **Go**.

#### 2. Restoring Files:
1. In File Manager, upload your backup `.zip` to `public_html`.
2. Extract all files, replacing older files.
3. Ensure `config.php` has the correct database credentials.
