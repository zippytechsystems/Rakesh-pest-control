-- =====================================================================
-- Harsha Pest Control - Production Database Schema & Seed Data
-- Target: MySQL 5.7+ / MySQL 8.0+ / MariaDB 10.3+ (Hostinger Compatible)
-- Character Set: utf8mb4 (Full Unicode / Emojis / Indian Name support)
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `rate_limits`;
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `page_seo`;
DROP TABLE IF EXISTS `admin_users`;
DROP TABLE IF EXISTS `settings`;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Table 1: Settings (Key-Value configuration for contact and business)
-- ---------------------------------------------------------------------
CREATE TABLE `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(64) NOT NULL,
  `setting_value` TEXT NULL,
  `description` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 2: Page SEO Metadata (Customizable per page)
-- ---------------------------------------------------------------------
CREATE TABLE `page_seo` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_slug` VARCHAR(50) NOT NULL,
  `page_name` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `meta_description` TEXT NOT NULL,
  `h1_heading` VARCHAR(255) NOT NULL,
  `canonical_url` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_page_slug` (`page_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 3: Services (Editable pricing, warranties, descriptions)
-- ---------------------------------------------------------------------
CREATE TABLE `services` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(100) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `short_desc` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `indicative_price` VARCHAR(100) NOT NULL,
  `warranty_terms` VARCHAR(255) NOT NULL,
  `free_service_offer` VARCHAR(255) NOT NULL,
  `price_note` VARCHAR(255) NOT NULL DEFAULT 'Indicative price for standard layouts. Final price confirmed after inspection.',
  `icon_name` VARCHAR(50) NOT NULL DEFAULT 'shield',
  `display_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_service_slug` (`slug`),
  KEY `idx_service_active_order` (`is_active`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 4: Products (Editable names, prices, availability)
-- ---------------------------------------------------------------------
CREATE TABLE `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(100) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `price_display` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'Pest Control Product',
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_product_slug` (`slug`),
  KEY `idx_product_active_order` (`is_active`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 5: Enquiries (Customer leads stored securely)
-- ---------------------------------------------------------------------
CREATE TABLE `enquiries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `service_or_product` VARCHAR(150) NULL,
  `locality` VARCHAR(100) NULL,
  `message` TEXT NULL,
  `consent_given` TINYINT(1) NOT NULL DEFAULT 1,
  `consent_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `status` ENUM('new', 'contacted', 'completed', 'cancelled') NOT NULL DEFAULT 'new',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_enquiry_status` (`status`),
  KEY `idx_enquiry_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 6: Admin Users (Securely hashed passwords)
-- ---------------------------------------------------------------------
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table 7: Rate Limits (Protects form submissions and login attempts)
-- ---------------------------------------------------------------------
CREATE TABLE `rate_limits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ip_action_time` (`ip_address`, `action`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- SEED DATA (Only Confirmed Business Information)
-- =====================================================================

-- Settings seed data
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('business_name', 'Harsha Pest Control', 'Official business legal name'),
('experience_years', '20', 'Years of verified industry experience'),
('contact_person', 'Rakesh', 'Primary business owner/contact person'),
('primary_phone', '63052 35360', 'Primary telephone contact number'),
('primary_phone_clean', '6305235360', 'Primary telephone digits for tel: links'),
('secondary_phone', '88979 06391', 'Secondary telephone contact number'),
('secondary_phone_clean', '8897906391', 'Secondary telephone digits for tel: links'),
('address_street', 'Snehapuri Colony, Behind HDFC Bank', 'Physical office street address'),
('address_locality', 'Nacharam', 'Local postal neighborhood'),
('address_city', 'Hyderabad', 'City name'),
('address_state', 'Telangana', 'State name'),
('address_pincode', '500 076', 'Postal PIN code'),
('service_areas_text', 'HMT Nagar, Snehapuri Colony, Nacharam, Habsiguda, Taranaka, and nearby Hyderabad areas', 'Confirmed operational coverage locations'),
('whatsapp_number', '6305235360', 'Confirmed WhatsApp number (must be confirmed by business owner)'),
('whatsapp_enabled', '1', '1 to display WhatsApp enquiry link, 0 to hide until confirmed'),
('site_title', 'Harsha Pest Control | 20 Years Experience | Nacharam, Hyderabad', 'Global fallback site title'),
('meta_description', 'Professional pest control in Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda & Taranaka. 20 years experience. Call Rakesh on 63052 35360.', 'Global fallback meta description');

-- Page SEO seed data
INSERT INTO `page_seo` (`page_slug`, `page_name`, `title`, `meta_description`, `h1_heading`) VALUES
('home', 'Home', 'Harsha Pest Control | 20 Years Experience | Nacharam, Hyderabad', 'Reliable pest control in Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda & Taranaka. 20 years of trusted service. Call Rakesh at 63052 35360 for an honest inspection.', 'Professional Pest Control in Nacharam & Nearby Hyderabad Areas'),
('services', 'Pest Control Services', 'Pest Control Services in Nacharam & Hyderabad | Harsha Pest Control', 'Professional cockroach, termite, mosquito, bed bug, rat, and spider control services around Nacharam, HMT Nagar, Snehapuri Colony, Habsiguda & Taranaka. 20 years experience.', 'Pest Control Services in Nacharam and Nearby Areas'),
('products', 'Pest Control Products', 'Pest Control Products in Nacharam, Hyderabad | Harsha Pest Control', 'Genuine pest control products including cockroach gel, spray bottles, rat medicine, snake medicine, and termite spray. Call 63052 35360 to confirm counter availability.', 'Pest Control Products Available in Nacharam'),
('areas', 'Areas We Serve', 'Areas We Serve | Nacharam, HMT Nagar, Habsiguda, Taranaka | Harsha Pest Control', 'Locally serving Nacharam, Snehapuri Colony, HMT Nagar, Habsiguda, Taranaka, and nearby Hyderabad areas. Genuine local response with 20 years experience.', 'Areas We Serve in Hyderabad'),
('about', 'About Us', 'About Harsha Pest Control | 20 Years Experience in Nacharam', 'Learn about Harsha Pest Control and owner Rakesh. Delivering honest, dependable pest control solutions in Nacharam and surrounding Hyderabad neighborhoods for 20 years.', 'About Harsha Pest Control'),
('contact', 'Contact Us', 'Contact Harsha Pest Control | Call Rakesh: 63052 35360 | Nacharam', 'Get in touch with Rakesh at Harsha Pest Control. Located at Snehapuri Colony, Behind HDFC Bank, Nacharam, Hyderabad – 500 076. Call 63052 35360 or 88979 06391.', 'Contact Harsha Pest Control in Nacharam'),
('privacy', 'Privacy Policy', 'Privacy Policy | Harsha Pest Control', 'Privacy information regarding how contact details submitted through our enquiry form are handled responsibly by Harsha Pest Control.', 'Privacy Policy');

-- Services seed data
INSERT INTO `services` (`slug`, `name`, `short_desc`, `description`, `indicative_price`, `warranty_terms`, `free_service_offer`, `price_note`, `icon_name`, `display_order`, `is_active`) VALUES
('cockroach-control', 'Cockroach Control', 'Targeted herbal and gel treatments for kitchens, cabinets, and drainage points.', 'Comprehensive cockroach management using safe, targeted gel baits and spray techniques specifically designed for kitchens, storage cabinets, and drainage points. Effective against German and American cockroach infestations with long-term control.', '2 BHK: ₹1,500 (Indicative)', '2-year warranty', 'One service free', 'Indicative rate for standard 2 BHK layout. Final price and scope confirmed after physical inspection of property.', 'cockroach', 1, 1),
('termite-control', 'Termite Control', 'Deep drill, fill, and barrier treatment for walls, wooden frames, and foundations.', 'Thorough anti-termite treatment utilizing specialized drilling and chemical injection along skirting lines, door frames, and foundation perimeters to eradicate active colonies and protect wooden structural elements.', '2 BHK: ₹4,500 (Indicative)', '5-year warranty', 'One service free', 'Indicative rate for standard 2 BHK residential property. Final quotation depends on wood perimeter and infestation severity.', 'termite', 2, 1),
('mosquito-control', 'Mosquito Control', 'Targeted outdoor fogging and residual surface spray for garden and perimeter areas.', 'Specialized mosquito abatement focusing on outdoor garden perimeters, standing water surroundings, and building exterior margins to significantly reduce adult mosquito populations and disrupt breeding habitats.', 'Outdoor garden: ₹2,500 (Indicative)', '6-month warranty', 'One service free', 'Indicative price for outdoor garden spaces. Final scope determined by garden size and property layout.', 'mosquito', 3, 1),
('rat-control', 'Rat & Rodent Control', 'Strategic baiting, trapping, and entry-point barrier solutions for basements and gardens.', 'Effective rodent eradication and prevention for residential basements, apartment cellars, and garden grounds utilizing professional-grade bait stations and humane trapping protocols.', 'Garden / cellar: ₹3,500 (Indicative)', '2-year warranty', 'One service free', 'Indicative price for garden, ground floor, or apartment cellar. Property inspection required for exact quotation.', 'rat', 4, 1),
('bed-bug-control', 'Bed Bug Control', 'Intensive multi-stage spray and heat treatment for mattresses, bed frames, and furniture.', 'Specialized bed bug eradication involving multi-step contact spraying and crevice treatments across bed frames, mattresses, sofa seams, and room baseboards to break the insect life cycle completely.', 'Contact for inspection & quote', 'Warranty based on treatment scope', 'Follow-up inspection included', 'Pricing depends on the number of bedrooms, level of infestation, and room contents. Free phone consultation.', 'bedbug', 5, 1),
('spider-control', 'Spider Control', 'Web removal, spot treatment, and perimeter protection for ceilings, corners, and balconies.', 'Systematic cobweb clearance followed by targeted surface treatments along ceilings, window frames, balconies, and shaded corners to deter spiders and other crawling pests from re-establishing nests.', 'Contact for inspection & quote', 'Targeted seasonal protection', 'Follow-up check included', 'Rates depend on premise dimensions, ceiling heights, and outdoor exposure. Contact us for an accurate quote.', 'spider', 6, 1);

-- Products seed data
INSERT INTO `products` (`slug`, `name`, `price`, `price_display`, `description`, `category`, `is_available`, `is_active`, `display_order`) VALUES
('cockroach-gel', 'Cockroach Gel', 550.00, '₹550', 'Professional cockroach gel applicator for discreet, odorless application inside kitchen cabinets, drawers, and behind appliances.', 'Household Pest Products', 1, 1, 1),
('cockroach-spray-bottle', 'Cockroach Spray Bottle', 1200.00, '₹1,200', 'Ready-to-use cockroach spray bottle for quick knockdown and barrier protection along kitchen baseboards and bathroom drains.', 'Spray Solutions', 1, 1, 2),
('rat-medicine', 'Rat Medicine', 250.00, '₹250', 'Effective rodent control bait formulation designed for residential basements, cellars, and compound gardens.', 'Rodenticides', 1, 1, 3),
('snake-medicine', 'Snake Medicine', 1800.00, '₹1,800', 'Specialized repellent formulation for outdoor boundary application around plots, gardens, and farm houses to deter reptiles.', 'Repellents', 1, 1, 4),
('termite-spray-bottle', 'Termite Spray Bottle', 2000.00, '₹2,000', 'Direct-application anti-termite spray formulation suitable for spot protection on exposed wooden frames and furniture surfaces.', 'Wood Protection', 1, 1, 5);
