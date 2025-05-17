-- Sample Data SQL with checks to prevent duplicate entries

-- First, alter the users table to add admin role if not already exists
-- Check if the account_type column needs to be modified
SET @columnType = (SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'account_type');

SET @alterTable = IF(
    @columnType NOT LIKE '%admin%',
    'ALTER TABLE users MODIFY COLUMN account_type ENUM("client", "freelancer", "admin") NOT NULL DEFAULT "freelancer"',
    'SELECT "Account type already includes admin - no change needed"'
);

PREPARE stmt FROM @alterTable;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Sample admin user - Only insert if email doesn't exist
INSERT INTO users (name, email, password, account_type, bio, country, terms_accepted)
SELECT 'Admin User', 'admin@lensi.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'admin', 'System administrator', 'United States', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@lensi.com');

-- Sample freelancer users - Only insert if email doesn't exist
INSERT INTO users (name, email, password, account_type, bio, country, skills, terms_accepted)
SELECT 'John Smith', 'john@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'freelancer', 'Professional web developer with 5 years of experience', 'United Kingdom', 'HTML, CSS, JavaScript, PHP, WordPress', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'john@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, skills, terms_accepted)
SELECT 'Sarah Johnson', 'sarah@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'freelancer', 'Graphic designer specializing in branding and identity', 'Canada', 'Adobe Photoshop, Illustrator, InDesign, Logo Design', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'sarah@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, skills, terms_accepted)
SELECT 'Michael Chen', 'michael@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'freelancer', 'Digital marketing specialist and social media expert', 'Australia', 'SEO, Content Marketing, Facebook Ads, Google Ads', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'michael@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, skills, terms_accepted)
SELECT 'Emily Rodriguez', 'emily@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'freelancer', 'Content writer and SEO specialist with a journalism background', 'Spain', 'Content Writing, Copywriting, SEO, Research', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'emily@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, skills, terms_accepted)
SELECT 'David Kumar', 'david@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'freelancer', 'Mobile app developer for iOS and Android platforms', 'India', 'Swift, Java, React Native, Flutter', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'david@example.com');

-- Sample client users - Only insert if email doesn't exist
INSERT INTO users (name, email, password, account_type, bio, country, terms_accepted)
SELECT 'Jane Doe', 'jane@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'client', 'Small business owner looking for design and marketing help', 'United States', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'jane@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, terms_accepted)
SELECT 'Robert Wilson', 'robert@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'client', 'E-commerce entrepreneur expanding to new markets', 'Germany', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'robert@example.com');

INSERT INTO users (name, email, password, account_type, bio, country, terms_accepted)
SELECT 'Lisa Martinez', 'lisa@example.com', '$2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e', 'client', 'Tech startup founder looking for development talent', 'France', TRUE
FROM dual
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'lisa@example.com');

-- Get user IDs for services and jobs (safely handling cases where users may not exist)
SET @john_id = (SELECT id FROM users WHERE email = 'john@example.com' LIMIT 1);
SET @sarah_id = (SELECT id FROM users WHERE email = 'sarah@example.com' LIMIT 1);
SET @michael_id = (SELECT id FROM users WHERE email = 'michael@example.com' LIMIT 1);
SET @emily_id = (SELECT id FROM users WHERE email = 'emily@example.com' LIMIT 1);
SET @david_id = (SELECT id FROM users WHERE email = 'david@example.com' LIMIT 1);
SET @jane_id = (SELECT id FROM users WHERE email = 'jane@example.com' LIMIT 1);
SET @robert_id = (SELECT id FROM users WHERE email = 'robert@example.com' LIMIT 1);
SET @lisa_id = (SELECT id FROM users WHERE email = 'lisa@example.com' LIMIT 1);

-- Sample services offered by freelancers - Check if user exists before inserting
INSERT INTO services (user_id, title, description, price, category)
SELECT @john_id, 'Professional Responsive Website Development', 'I will create a modern, mobile-friendly website for your business or personal brand using the latest technologies. Includes 5 pages, contact form, and basic SEO optimization.', 299.99, 'Web Development'
FROM dual
WHERE @john_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @john_id AND title = 'Professional Responsive Website Development'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @john_id, 'WordPress Website Customization', 'I will customize your WordPress theme to match your brand identity and requirements. Includes plugin installation, customization, and responsive design.', 149.99, 'Web Development'
FROM dual
WHERE @john_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @john_id AND title = 'WordPress Website Customization'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @sarah_id, 'Professional Logo Design Package', 'I will create a modern, memorable logo for your brand with unlimited revisions. Package includes logo files in multiple formats suitable for print and digital use.', 89.99, 'Design'
FROM dual
WHERE @sarah_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @sarah_id AND title = 'Professional Logo Design Package'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @sarah_id, 'Complete Brand Identity Package', 'I will design a comprehensive brand identity including logo, business cards, letterhead, social media templates, and brand guidelines document.', 349.99, 'Design'
FROM dual
WHERE @sarah_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @sarah_id AND title = 'Complete Brand Identity Package'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @michael_id, 'Social Media Marketing Campaign', 'I will create and manage a 30-day social media marketing campaign across Facebook, Instagram, and Twitter to increase your engagement and followers.', 199.99, 'Digital Marketing'
FROM dual
WHERE @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @michael_id AND title = 'Social Media Marketing Campaign'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @michael_id, 'SEO Optimization Package', 'I will optimize your website for search engines with keyword research, on-page SEO, meta tags, and content recommendations to improve your rankings.', 149.99, 'Digital Marketing'
FROM dual
WHERE @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @michael_id AND title = 'SEO Optimization Package'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @emily_id, 'SEO-Optimized Blog Content', 'I will write 5 high-quality, SEO-optimized blog posts (1000 words each) tailored to your industry and target keywords to boost your organic traffic.', 175.00, 'Writing'
FROM dual
WHERE @emily_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @emily_id AND title = 'SEO-Optimized Blog Content'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @emily_id, 'Product Description Writing', 'I will craft 20 compelling product descriptions (250 words each) that convert browsers into buyers, highlighting features and benefits effectively.', 125.00, 'Writing'
FROM dual
WHERE @emily_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @emily_id AND title = 'Product Description Writing'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @david_id, 'Custom iOS Mobile App Development', 'I will develop a custom iOS mobile application based on your requirements. Includes UI design, development, testing, and App Store submission support.', 799.99, 'Mobile Development'
FROM dual
WHERE @david_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @david_id AND title = 'Custom iOS Mobile App Development'
);

INSERT INTO services (user_id, title, description, price, category)
SELECT @david_id, 'Cross-Platform Mobile App', 'I will build a cross-platform mobile app that works on both iOS and Android using React Native or Flutter, with a clean, intuitive user interface.', 999.99, 'Mobile Development'
FROM dual
WHERE @david_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM services 
    WHERE user_id = @david_id AND title = 'Cross-Platform Mobile App'
);

-- Sample jobs posted by clients - Check if user exists before inserting
INSERT INTO jobs (user_id, title, description, budget, skills, category)
SELECT @jane_id, 'Website Redesign for Small Business', 'Looking for an experienced web developer to redesign our company website. Need modern design, mobile responsiveness, and integration with our booking system.', 750.00, 'HTML, CSS, JavaScript, PHP, Responsive Design', 'Web Development'
FROM dual
WHERE @jane_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM jobs 
    WHERE user_id = @jane_id AND title = 'Website Redesign for Small Business'
);

INSERT INTO jobs (user_id, title, description, budget, skills, category)
SELECT @jane_id, 'Logo Design for New Product Line', 'Need a creative designer to create a logo for our new eco-friendly product line. Looking for something modern, clean, and aligned with our brand values.', 200.00, 'Logo Design, Adobe Illustrator, Branding', 'Design'
FROM dual
WHERE @jane_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM jobs 
    WHERE user_id = @jane_id AND title = 'Logo Design for New Product Line'
);

INSERT INTO jobs (user_id, title, description, budget, skills, category)
SELECT @robert_id, 'E-commerce Product Listings Optimization', 'Seeking a copywriter to optimize 50 product descriptions for our online store. Must have experience with e-commerce copy and SEO.', 300.00, 'Copywriting, SEO, E-commerce', 'Writing'
FROM dual
WHERE @robert_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM jobs 
    WHERE user_id = @robert_id AND title = 'E-commerce Product Listings Optimization'
);

INSERT INTO jobs (user_id, title, description, budget, skills, category)
SELECT @robert_id, 'Facebook Advertising Campaign', 'Looking for a digital marketing expert to create and manage a Facebook advertising campaign for our new product launch. Budget is for 2-week campaign.', 400.00, 'Facebook Ads, Digital Marketing, Copy Writing', 'Digital Marketing'
FROM dual
WHERE @robert_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM jobs 
    WHERE user_id = @robert_id AND title = 'Facebook Advertising Campaign'
);

INSERT INTO jobs (user_id, title, description, budget, skills, category)
SELECT @lisa_id, 'Mobile App for Service Booking', 'We need a mobile app developer to create a simple booking app for our service business. Should work on both iOS and Android with clean interface.', 1200.00, 'Mobile Development, React Native, UI Design', 'Mobile Development'
FROM dual
WHERE @lisa_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM jobs 
    WHERE user_id = @lisa_id AND title = 'Mobile App for Service Booking'
);

-- Now we need to get the job IDs for the applications
-- Since job IDs will be auto-generated, we need to get them dynamically
SET @job_web_redesign = (SELECT id FROM jobs WHERE title = 'Website Redesign for Small Business' LIMIT 1);
SET @job_logo_design = (SELECT id FROM jobs WHERE title = 'Logo Design for New Product Line' LIMIT 1);
SET @job_ecommerce = (SELECT id FROM jobs WHERE title = 'E-commerce Product Listings Optimization' LIMIT 1);
SET @job_facebook_ads = (SELECT id FROM jobs WHERE title = 'Facebook Advertising Campaign' LIMIT 1);
SET @job_mobile_app = (SELECT id FROM jobs WHERE title = 'Mobile App for Service Booking' LIMIT 1);

-- Sample applications from freelancers to jobs - Check if job and freelancer exist
INSERT INTO applications (job_id, freelancer_id, proposal, price, status)
SELECT @job_web_redesign, @john_id, 'I have extensive experience with website redesigns for small businesses and can deliver a modern, responsive site that meets all your requirements. I would approach this by first analyzing your current site and understanding your brand goals.', 700.00, 'pending'
FROM dual
WHERE @job_web_redesign IS NOT NULL AND @john_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM applications 
    WHERE job_id = @job_web_redesign AND freelancer_id = @john_id
);

INSERT INTO applications (job_id, freelancer_id, proposal, price, status)
SELECT @job_logo_design, @sarah_id, 'As a brand identity specialist, I can create a distinctive and memorable logo for your eco-friendly product line. I would start with a discovery session to understand your brand values and target audience.', 180.00, 'accepted'
FROM dual
WHERE @job_logo_design IS NOT NULL AND @sarah_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM applications 
    WHERE job_id = @job_logo_design AND freelancer_id = @sarah_id
);

INSERT INTO applications (job_id, freelancer_id, proposal, price, status)
SELECT @job_ecommerce, @emily_id, 'I specialize in e-commerce copywriting that converts browsers to buyers. I can optimize your product descriptions with SEO best practices while maintaining your brand voice and highlighting key selling points.', 275.00, 'pending'
FROM dual
WHERE @job_ecommerce IS NOT NULL AND @emily_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM applications 
    WHERE job_id = @job_ecommerce AND freelancer_id = @emily_id
);

INSERT INTO applications (job_id, freelancer_id, proposal, price, status)
SELECT @job_facebook_ads, @michael_id, 'I have run successful Facebook ad campaigns for product launches with ROIs of 300%+. I would create a targeted campaign strategy with compelling ad copy and visuals to reach your ideal customers.', 375.00, 'pending'
FROM dual
WHERE @job_facebook_ads IS NOT NULL AND @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM applications 
    WHERE job_id = @job_facebook_ads AND freelancer_id = @michael_id
);

INSERT INTO applications (job_id, freelancer_id, proposal, price, status)
SELECT @job_mobile_app, @david_id, 'I can develop a cross-platform booking app using Flutter that will work seamlessly on both iOS and Android. I focus on intuitive UX design and efficient backend integration.', 1150.00, 'pending'
FROM dual
WHERE @job_mobile_app IS NOT NULL AND @david_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM applications 
    WHERE job_id = @job_mobile_app AND freelancer_id = @david_id
);

-- Get service IDs for orders
SET @service_wordpress = (SELECT id FROM services WHERE title = 'WordPress Website Customization' LIMIT 1);
SET @service_brand_identity = (SELECT id FROM services WHERE title = 'Complete Brand Identity Package' LIMIT 1);
SET @service_seo = (SELECT id FROM services WHERE title = 'SEO Optimization Package' LIMIT 1);
SET @service_product_desc = (SELECT id FROM services WHERE title = 'Product Description Writing' LIMIT 1);

-- Sample orders for services - Check if service, client and provider exist
INSERT INTO orders (service_id, client_id, provider_id, requirements, price, status)
SELECT @service_wordpress, @jane_id, @john_id, 'I need my existing WordPress site updated with a new theme and customized to match my updated brand colors. Please include WooCommerce integration for my online store.', 149.99, 'pending'
FROM dual
WHERE @service_wordpress IS NOT NULL AND @jane_id IS NOT NULL AND @john_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM orders 
    WHERE service_id = @service_wordpress AND client_id = @jane_id AND provider_id = @john_id
);

INSERT INTO orders (service_id, client_id, provider_id, requirements, price, status)
SELECT @service_brand_identity, @robert_id, @sarah_id, 'I am launching a new tech startup and need a complete brand identity package. Our brand colors are blue and gray, and we want a modern, innovative feel.', 349.99, 'accepted'
FROM dual
WHERE @service_brand_identity IS NOT NULL AND @robert_id IS NOT NULL AND @sarah_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM orders 
    WHERE service_id = @service_brand_identity AND client_id = @robert_id AND provider_id = @sarah_id
);

INSERT INTO orders (service_id, client_id, provider_id, requirements, price, status)
SELECT @service_seo, @lisa_id, @michael_id, 'I need SEO optimization for my new e-commerce website selling handmade crafts. Main keywords include "handmade gifts", "artisan crafts", and "unique home decor".', 149.99, 'completed'
FROM dual
WHERE @service_seo IS NOT NULL AND @lisa_id IS NOT NULL AND @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM orders 
    WHERE service_id = @service_seo AND client_id = @lisa_id AND provider_id = @michael_id
);

INSERT INTO orders (service_id, client_id, provider_id, requirements, price, status)
SELECT @service_product_desc, @jane_id, @emily_id, 'I need 20 product descriptions for my new line of organic skincare products. Each should highlight natural ingredients and benefits.', 125.00, 'pending'
FROM dual
WHERE @service_product_desc IS NOT NULL AND @jane_id IS NOT NULL AND @emily_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM orders 
    WHERE service_id = @service_product_desc AND client_id = @jane_id AND provider_id = @emily_id
);

-- Get order ID for completed order for review
SET @order_seo = (SELECT id FROM orders WHERE service_id = @service_seo AND status = 'completed' LIMIT 1);

-- Sample reviews for completed orders - Check if order exists
INSERT INTO reviews (order_id, service_id, client_id, provider_id, rating, comment)
SELECT @order_seo, @service_seo, @lisa_id, @michael_id, 5, 'Michael did an excellent job optimizing my website! Rankings have already improved in just a few weeks, and the recommendations were clear and actionable.'
FROM dual
WHERE @order_seo IS NOT NULL AND @service_seo IS NOT NULL AND @lisa_id IS NOT NULL AND @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM reviews 
    WHERE order_id = @order_seo
);

-- Sample notifications
INSERT INTO notifications (user_id, type, title, message)
SELECT @john_id, 'order', 'New Order Received', 'You have received a new order for WordPress Website Customization'
FROM dual
WHERE @john_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM notifications 
    WHERE user_id = @john_id AND title = 'New Order Received'
);

INSERT INTO notifications (user_id, type, title, message)
SELECT @sarah_id, 'order', 'Order Status Update', 'Your order for Complete Brand Identity Package has been accepted'
FROM dual
WHERE @sarah_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM notifications 
    WHERE user_id = @sarah_id AND title = 'Order Status Update'
);

INSERT INTO notifications (user_id, type, title, message)
SELECT @michael_id, 'review', 'New Review Received', 'You have received a 5-star review from Lisa Martinez'
FROM dual
WHERE @michael_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM notifications 
    WHERE user_id = @michael_id AND title = 'New Review Received'
);

INSERT INTO notifications (user_id, type, title, message)
SELECT @jane_id, 'order', 'Order Update', 'Your order for WordPress Website Customization is being processed'
FROM dual
WHERE @jane_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM notifications 
    WHERE user_id = @jane_id AND title = 'Order Update'
);

INSERT INTO notifications (user_id, type, title, message)
SELECT @robert_id, 'order', 'Order Accepted', 'Your order for Complete Brand Identity Package has been accepted by the freelancer'
FROM dual
WHERE @robert_id IS NOT NULL AND NOT EXISTS (
    SELECT 1 FROM notifications 
    WHERE user_id = @robert_id AND title = 'Order Accepted'
);

-- Note: All passwords are set to "password123" (hashed with bcrypt)
-- The hash $2y$10$0GFTiMwgVr7QKE3C.uXV.eAqPOCJVa5WKTRpv9w5vvYxJQFnpQD6e is for "password123"