# Google Search Console & Local SEO Handoff Guide
## Harsha Pest Control — Nacharam, Hyderabad

> **[Owner Action Required]**  
> Google accounts, domain verification, and Google Business Profile management require direct access to your personal/company Google account. No external tool or developer can claim these without your authorization. Follow this step-by-step handoff guide to establish your search presence.

---

## 1. Google Search Console Setup

### Step 1.1: Add Property to Search Console
1. Go to [search.google.com/search-console](https://search.google.com/search-console) and sign in with your primary Google account.
2. In the top-left dropdown, select **Add property**.
3. Choose **Domain** property (recommended):
   - Enter your bare domain (e.g., `harshapestcontrol.store`).
   - Google will provide a **TXT record** (e.g. `google-site-verification=abc123xyz...`).
4. In Hostinger hPanel -> **DNS Zone Editor**:
   - Add a new **TXT record**:
     - Name / Host: `@`
     - TXT value: paste the verification code from Google
     - TTL: `14400`
   - Click **Add Record**.
5. Back in Search Console, click **Verify**. (If DNS hasn't propagated yet, wait 15 minutes and retry).

---

### Step 1.2: Submit the XML Sitemap
1. Inside Google Search Console, click **Sitemaps** under the *Indexing* menu on the left.
2. In the **Add a new sitemap** input field, type:
   `sitemap.xml`
3. Click **Submit**.
4. Check back after 24–48 hours. Google should show:
   - Status: **Success**
   - Discovered URLs: `7` (Home, Services, Products, Areas, About, Contact, Privacy).

---

### Step 1.3: Inspect URL & Request Initial Indexing
1. In the top search bar of Search Console (*"Inspect any URL in '...' "*), enter your live homepage URL:
   `https://harshapestcontrol.store/`
2. Wait for Google to test the URL.
3. Click the **Request Indexing** button.
4. Repeat this for your key service and area pages:
   - `https://harshapestcontrol.store/services.php`
   - `https://harshapestcontrol.store/products.php`
   - `https://harshapestcontrol.store/areas.php`
   - `https://harshapestcontrol.store/contact.php`

---

### Step 1.4: Monitor Performance & Search Queries
After 1–2 weeks, Search Console will begin collecting live data:
- Navigate to **Performance** -> **Search results**.
- Review:
  - **Total clicks & impressions:** How often people see and click your listing.
  - **Queries:** Real search phrases used (e.g., *"pest control in nacharam"*, *"cockroach control hmt nagar"*, *"termite treatment habsiguda"*).
  - **Pages:** Which pages receive the most organic local traffic.

---

## 2. Google Business Profile (Google Maps) Setup

Having a verified Google Business Profile linked to your website is the single most important factor for appearing in the local **Google 3-Pack Map** when nearby residents search for pest control.

### Step 2.1: Claim or Create Profile
1. Go to [google.com/business](https://www.google.com/business) and sign in.
2. Search for **Harsha Pest Control**.
   - If not found, select **Create a business with this name**.
3. **Business Category:** Select `Pest Control Service`.

---

### Step 2.2: Maintain Consistent Business Information (NAP Consistency)
To build strong local search signals, the details in Google Business Profile must **exactly match** the website:

| Field | Exact Value to Enter |
| :--- | :--- |
| **Business Name** | `Harsha Pest Control` |
| **Primary Phone** | `063052 35360` (or `+91 63052 35360`) |
| **Secondary Phone** | `088979 06391` |
| **Street Address** | `Snehapuri Colony, Behind HDFC Bank` |
| **Locality** | `Nacharam` |
| **City** | `Hyderabad` |
| **State** | `Telangana` |
| **PIN Code** | `500076` |
| **Website URL** | `https://harshapestcontrol.store` |

---

### Step 2.3: Set Up Confirmed Service Areas
Because you travel to customers' homes, check the option: *"Yes, I also serve customers outside my physical location"*.
Add your exact service areas:
1. `Nacharam, Hyderabad`
2. `Snehapuri Colony, Hyderabad`
3. `HMT Nagar, Hyderabad`
4. `Habsiguda, Hyderabad`
5. `Taranaka, Hyderabad`
6. `Hyderabad, Telangana`

---

### Step 2.4: Profile Verification & Photos
1. Complete verification using the method requested by Google (video verification of your office/tools or phone OTP).
2. **Upload Genuine Photos:**
   - Exterior view showing the building near HDFC Bank, Snehapuri Colony.
   - Work photos of technician gear, spray equipment, and vehicle.
   - Genuine team and inspection photos.
   *Avoid generic stock photos, as Google prioritizes authentic business imagery.*

---

### Step 2.5: Collecting Authentic Customer Reviews
- Never buy reviews, offer cash incentives, or post fabricated testimonials.
- After successfully treating a cockroach, termite, or rodent problem, share your direct Google Review shortlink with the satisfied customer via WhatsApp:
  > *"Thank you for trusting Harsha Pest Control! If you were satisfied with our service, could you take 30 seconds to share your honest feedback on Google? It helps our local business tremendously: [Your Google Review Link]"*

---

## 3. Validating Structured Data (Schema Markup)

The website includes embedded `LocalBusiness` JSON-LD schema with only verified business information.

### How to Test:
1. Visit the [Google Rich Results Test](https://search.google.com/test/rich-results).
2. Enter `https://harshapestcontrol.store/` and click **Test URL**.
3. Verify that the crawler detects the `LocalBusiness` entity without errors.
4. Also test on [validator.schema.org](https://validator.schema.org/) to confirm all address, phone, and areaServed attributes parse cleanly.
