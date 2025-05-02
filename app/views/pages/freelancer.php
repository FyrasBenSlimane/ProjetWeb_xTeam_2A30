<?php
// Freelancer dashboard view for logged-in freelancer users
?>


<!-- Freelancer Indicator Badge -->
<div class="freelancer-indicator">
    <div class="indicator-badge">
        <i class="fas fa-laptop-code"></i> Freelancer Account
    </div>
</div>

<!-- Mobile search form -->
<div class="d-block d-lg-none container-fluid py-3 mobile-search-container">
    <form class="search-form" action="<?php echo URL_ROOT; ?>/jobs/browse" method="GET">
        <input type="text" name="search" placeholder="Search for jobs that match your skills...">
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>

<main class="main-container">
    <section class="welcome-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="welcome-title mb-3">Welcome back, <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>!</h1>
                    <p class="welcome-subtitle text-muted">Your freelancer profile is ready. Find projects and showcase your skills.</p>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <a href="<?php echo URL_ROOT; ?>/services/create" class="btn-post-service">
                        <i class="fas fa-plus me-2"></i> Create Service
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="freelancer-dashboard py-4">
        <div class="container">
            <div class="row">
                <!-- Quick Stats Widget -->
                <div class="col-md-4 mb-4">
                    <div class="stats-card">
                        <div class="card-header">
                            <h5>Your Stats</h5>
                            <span class="refresh-icon"><i class="fas fa-sync-alt"></i></span>
                        </div>
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-icon orders-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="stat-details">
                                    <h3>0</h3>
                                    <p>Active Orders</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon completed-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-details">
                                    <h3>0</h3>
                                    <p>Completed</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon services-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="stat-details">
                                    <h3>0</h3>
                                    <p>Active Services</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Orders Widget -->
                <div class="col-md-8 mb-4">
                    <div class="orders-card">
                        <div class="card-header">
                            <h5>Active Orders</h5>
                            <a href="<?php echo URL_ROOT; ?>/orders" class="text-decoration-none view-all">See All</a>
                        </div>

                        <div class="orders-container">
                            <div class="order-empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h6>You don't have any active orders</h6>
                                <p>Create services to showcase your skills and attract clients</p>
                                <a href="<?php echo URL_ROOT; ?>/services/create" class="create-service-btn">Create a Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recommended Jobs Widget -->
                <div class="col-12 mb-4">
                    <div class="jobs-card">
                        <div class="card-header">
                            <h5>Jobs That Match Your Skills</h5>
                            <a href="<?php echo URL_ROOT; ?>/jobs" class="text-decoration-none view-all">Browse All Jobs</a>
                        </div>

                        <div class="jobs-slider-container">
                            <div class="jobs-slider-controls">
                                <button class="slider-control prev-job" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="slider-pagination">
                                    <span class="current-slide">1</span> / <span class="total-slides">4</span>
                                </div>
                                <button class="slider-control next-job">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="jobs-slider">
                                <div class="row active-slide">
                                    <!-- Job Card 1 -->
                                    <div class="col-md-6 mb-3">
                                        <div class="job-card-mini">
                                            <div class="job-card-header">
                                                <div class="job-category">Web Development</div>
                                                <div class="job-budget">$750</div>
                                            </div>
                                            <div class="job-details p-3">
                                                <h5 class="job-title">Website Redesign for Small Business</h5>
                                                <div class="client-info d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Client" class="avatar-xs me-2">
                                                    <span class="client-name">Jane Doe</span>
                                                    <span class="location-badge ms-auto"><i class="fas fa-map-marker-alt"></i> United States</span>
                                                </div>
                                                <p class="job-description">Looking for an experienced web developer to redesign our company website. Need modern design, mobile responsiveness, and integration with our booking system.</p>
                                                <div class="skills-container">
                                                    <span class="skill-tag">HTML</span>
                                                    <span class="skill-tag">CSS</span>
                                                    <span class="skill-tag">JavaScript</span>
                                                    <span class="skill-tag">PHP</span>
                                                </div>
                                                <div class="job-actions mt-3">
                                                    <a href="<?php echo URL_ROOT; ?>/jobs/view/1" class="btn-view-job">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Job Card 2 -->
                                    <div class="col-md-6 mb-3">
                                        <div class="job-card-mini">
                                            <div class="job-card-header">
                                                <div class="job-category">Design</div>
                                                <div class="job-budget">$200</div>
                                            </div>
                                            <div class="job-details p-3">
                                                <h5 class="job-title">Logo Design for New Product Line</h5>
                                                <div class="client-info d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Client" class="avatar-xs me-2">
                                                    <span class="client-name">Jane Doe</span>
                                                    <span class="location-badge ms-auto"><i class="fas fa-map-marker-alt"></i> United States</span>
                                                </div>
                                                <p class="job-description">Need a creative designer to create a logo for our new eco-friendly product line. Looking for something modern, clean, and aligned with our brand values.</p>
                                                <div class="skills-container">
                                                    <span class="skill-tag">Logo Design</span>
                                                    <span class="skill-tag">Adobe Illustrator</span>
                                                    <span class="skill-tag">Branding</span>
                                                </div>
                                                <div class="job-actions mt-3">
                                                    <a href="<?php echo URL_ROOT; ?>/jobs/view/2" class="btn-view-job">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Job Card 3 -->
                                    <div class="col-md-6 mb-3">
                                        <div class="job-card-mini">
                                            <div class="job-card-header">
                                                <div class="job-category">Writing</div>
                                                <div class="job-budget">$300</div>
                                            </div>
                                            <div class="job-details p-3">
                                                <h5 class="job-title">E-commerce Product Listings Optimization</h5>
                                                <div class="client-info d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Client" class="avatar-xs me-2">
                                                    <span class="client-name">Robert Wilson</span>
                                                    <span class="location-badge ms-auto"><i class="fas fa-map-marker-alt"></i> Germany</span>
                                                </div>
                                                <p class="job-description">Seeking a copywriter to optimize 50 product descriptions for our online store. Must have experience with e-commerce copy and SEO.</p>
                                                <div class="skills-container">
                                                    <span class="skill-tag">Copywriting</span>
                                                    <span class="skill-tag">SEO</span>
                                                    <span class="skill-tag">E-commerce</span>
                                                </div>
                                                <div class="job-actions mt-3">
                                                    <a href="<?php echo URL_ROOT; ?>/jobs/view/3" class="btn-view-job">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Job Card 4 -->
                                    <div class="col-md-6 mb-3">
                                        <div class="job-card-mini">
                                            <div class="job-card-header">
                                                <div class="job-category">Digital Marketing</div>
                                                <div class="job-budget">$400</div>
                                            </div>
                                            <div class="job-details p-3">
                                                <h5 class="job-title">Facebook Advertising Campaign</h5>
                                                <div class="client-info d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Client" class="avatar-xs me-2">
                                                    <span class="client-name">Robert Wilson</span>
                                                    <span class="location-badge ms-auto"><i class="fas fa-map-marker-alt"></i> Germany</span>
                                                </div>
                                                <p class="job-description">Looking for a digital marketing expert to create and manage a Facebook advertising campaign for our new product launch. Budget is for 2-week campaign.</p>
                                                <div class="skills-container">
                                                    <span class="skill-tag">Facebook Ads</span>
                                                    <span class="skill-tag">Digital Marketing</span>
                                                    <span class="skill-tag">Copy Writing</span>
                                                </div>
                                                <div class="job-actions mt-3">
                                                    <a href="<?php echo URL_ROOT; ?>/jobs/view/4" class="btn-view-job">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Job Card 5 -->
                                    <div class="col-md-6 mb-3">
                                        <div class="job-card-mini">
                                            <div class="job-card-header">
                                                <div class="job-category">Mobile Development</div>
                                                <div class="job-budget">$1,200</div>
                                            </div>
                                            <div class="job-details p-3">
                                                <h5 class="job-title">Mobile App for Service Booking</h5>
                                                <div class="client-info d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Client" class="avatar-xs me-2">
                                                    <span class="client-name">Lisa Martinez</span>
                                                    <span class="location-badge ms-auto"><i class="fas fa-map-marker-alt"></i> France</span>
                                                </div>
                                                <p class="job-description">We need a mobile app developer to create a simple booking app for our service business. Should work on both iOS and Android with clean interface.</p>
                                                <div class="skills-container">
                                                    <span class="skill-tag">Mobile Development</span>
                                                    <span class="skill-tag">React Native</span>
                                                    <span class="skill-tag">UI Design</span>
                                                </div>
                                                <div class="job-actions mt-3">
                                                    <a href="<?php echo URL_ROOT; ?>/jobs/view/5" class="btn-view-job">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Your Services Widget -->
                <div class="col-12 mb-4">
                    <div class="services-card">
                        <div class="card-header">
                            <h5>Your Services</h5>
                            <a href="<?php echo URL_ROOT; ?>/services/manage" class="text-decoration-none view-all">Manage All</a>
                        </div>

                        <div class="services-empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h6>You don't have any services yet</h6>
                            <p>Create services to showcase your skills and attract potential clients</p>
                            <a href="<?php echo URL_ROOT; ?>/services/create" class="create-service-btn">Create a Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Styles for Freelancer Dashboard -->
<style>
    /* Root variables */
    :root {
        /* Main color palette - professional purple scheme for freelancers */
        --primary: #7952b3;
        --primary-light: #9166cc;
        --primary-dark: #5e3e8e;
        --primary-accent: #f3eeff;

        /* Secondary colors */
        --secondary: #222325;
        --secondary-light: #404145;
        --secondary-dark: #0e0e10;
        --secondary-accent: #f1f1f2;

        /* Accent colors */
        --accent-blue: #3498db;
        --accent-green: #2ecc71;
        --accent-orange: #e67e22;
        --accent-purple: #9b59b6;

        /* UI colors */
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --info: #3498db;

        /* Neutrals */
        --white: #ffffff;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --black: #000000;

        /* Typography */
        --font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        --font-weight-light: 300;
        --font-weight-normal: 400;
        --font-weight-medium: 500;
        --font-weight-semibold: 600;
        --font-weight-bold: 700;

        /* Spacing */
        --spacing-xs: 0.25rem;
        --spacing-sm: 0.5rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
        --spacing-2xl: 3rem;

        /* Borders */
        --border-radius-sm: 0.25rem;
        --border-radius-md: 0.5rem;
        --border-radius-lg: 0.75rem;
        --border-radius-xl: 1rem;
        --border-radius-circle: 50%;

        /* Shadows */
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        --shadow-md: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        --shadow-lg: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        --shadow-xl: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);

        /* Transitions */
        --transition-fast: 0.15s ease;
        --transition-normal: 0.25s ease;
        --transition-slow: 0.35s ease;
    }

    /* Base styles */
    body {
        font-family: var(--font-family);
        color: var(--secondary);
        background-color: var(--gray-100);
        margin: 0;
        padding: 0;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 0;
    }

    /* Freelancer Indicator Badge */
    .freelancer-indicator {
        position: fixed;
        top: 70px;
        right: 0;
        z-index: 1000;
        pointer-events: none;
    }

    .indicator-badge {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--white);
        font-size: 0.75rem;
        font-weight: var(--font-weight-medium);
        padding: 0.25rem 1rem;
        border-radius: 0 0 0 var(--border-radius-md);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.9;
        animation: slideinRight 0.5s forwards;
    }

    @keyframes slideinRight {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(0);
        }
    }

    /* Main container styles */
    .main-container {
        min-height: calc(100vh - 70px);
        background: linear-gradient(135deg, var(--gray-100) 0%, var(--white) 100%);
        position: relative;
        overflow: hidden;
    }

    .main-container::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 40%;
        height: 100%;
        background: linear-gradient(135deg, rgba(121, 82, 179, 0.03) 0%, rgba(121, 82, 179, 0.01) 100%);
        z-index: 0;
        pointer-events: none;
    }

    /* Mobile search container */
    .mobile-search-container {
        background-color: var(--white);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 70px;
        z-index: 990;
        margin-top: 70px;
    }

    .search-form {
        display: flex;
        position: relative;
        width: 100%;
        box-shadow: var(--shadow-sm);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
    }

    .search-form input {
        flex: 1;
        padding: 0.8rem 1.2rem;
        border: 1px solid var(--gray-200);
        border-right: none;
        border-radius: var(--border-radius-lg) 0 0 var(--border-radius-lg);
        font-size: 0.95rem;
        background-color: var(--white);
        transition: all var(--transition-normal);
        color: var(--secondary);
    }

    .search-form input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(121, 82, 179, 0.1);
    }

    .search-form button {
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: var(--white);
        border: none;
        padding: 0 1.5rem;
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-form button:hover {
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
    }

    /* Welcome section */
    .welcome-section {
        background-color: var(--white);
        padding: 2rem 0;
        border-bottom: 1px solid var(--gray-200);
        margin-top: 0;
        /* Adjusted for search bar */
        position: relative;
        z-index: 1;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .welcome-title {
        font-size: 1.9rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        animation: fadeInUp 0.5s ease 0.2s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .user-name {
        background: linear-gradient(to right, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        position: relative;
    }

    .welcome-subtitle {
        font-size: 1rem;
        color: var(--gray-600);
        max-width: 80%;
        animation: fadeInUp 0.5s ease 0.3s both;
    }

    .btn-post-service {
        background: linear-gradient(to right, var(--primary), var(--primary-dark));
        color: var(--white);
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        padding: 0.7rem 1.5rem;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-sm);
        animation: fadeIn 0.5s ease 0.5s both;
    }

    .btn-post-service:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
        color: var(--white);
    }

    /* Dashboard Cards Common Styles */
    .stats-card,
    .orders-card,
    .jobs-card,
    .services-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        height: 100%;
        border: none;
    }

    .stats-card:hover,
    .orders-card:hover,
    .jobs-card:hover,
    .services-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .view-all {
        font-size: 0.85rem;
        color: var(--primary);
        text-decoration: none;
        transition: color var(--transition-fast);
    }

    .view-all:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Stats Card Styles */
    .refresh-icon {
        width: 28px;
        height: 28px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--gray-100);
        cursor: pointer;
        transition: background-color var(--transition-fast), transform var(--transition-fast);
    }

    .refresh-icon:hover {
        background-color: var(--gray-200);
        transform: rotate(30deg);
    }

    .stats-container {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform var(--transition-fast);
    }

    .stat-item:hover {
        transform: translateX(5px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }

    .orders-icon {
        background-color: rgba(121, 82, 179, 0.1);
        color: var(--primary);
    }

    .completed-icon {
        background-color: rgba(46, 204, 113, 0.1);
        color: var(--accent-green);
    }

    .services-icon {
        background-color: rgba(52, 152, 219, 0.1);
        color: var(--accent-blue);
    }

    .stat-details h3 {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }

    .stat-details p {
        font-size: 0.85rem;
        color: var(--gray-600);
        margin: 0;
    }

    /* Orders Card Styles */
    .orders-container {
        padding: 1.5rem;
    }

    .order-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: var(--border-radius-circle);
        background-color: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        color: var(--gray-600);
        font-size: 1.8rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(121, 82, 179, 0.2);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(121, 82, 179, 0);
        }

        100% {
            transform: scale(1);
        }
    }

    .order-empty-state h6 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .order-empty-state p {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }

    .create-service-btn {
        background-color: transparent;
        color: var(--primary);
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        padding: 0.6rem 1.5rem;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        border: 2px solid var(--primary);
        transition: all var(--transition-normal);
    }

    .create-service-btn:hover {
        background-color: var(--primary);
        color: var(--white);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* Jobs Card Styles */
    .jobs-slider-container {
        padding: 1.5rem;
    }

    .jobs-slider-controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .slider-control {
        width: 36px;
        height: 36px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--gray-100);
        border: none;
        cursor: pointer;
        transition: background-color var(--transition-fast), color var(--transition-fast);
        color: var(--secondary);
    }

    .slider-control:not(:disabled):hover {
        background-color: var(--primary);
        color: var(--white);
    }

    .slider-control:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .slider-pagination {
        font-size: 0.85rem;
        color: var(--gray-600);
    }

    .jobs-slider {
        position: relative;
        overflow: hidden;
    }

    .jobs-slider .row {
        display: none;
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .jobs-slider .row.active-slide {
        display: flex;
        opacity: 1;
        animation: fadeSlideIn 0.5s ease;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Job Card Mini Styles */
    .job-card-mini {
        border-radius: var(--border-radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        height: 100%;
        border: 1px solid var(--gray-200);
        background-color: var(--white);
    }

    .job-card-mini:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .job-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background-color: var(--primary-accent);
        border-bottom: 1px solid var(--gray-200);
    }

    .job-category {
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
        color: var(--primary);
        background-color: rgba(121, 82, 179, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
    }

    .job-budget {
        font-size: 0.9rem;
        font-weight: var(--font-weight-semibold);
        color: var(--primary-dark);
    }

    .job-title {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .client-name {
        font-size: 0.85rem;
        color: var(--gray-700);
        font-weight: var(--font-weight-medium);
    }

    .location-badge {
        font-size: 0.75rem;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .job-description {
        font-size: 0.85rem;
        color: var(--gray-700);
        margin-bottom: 1rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .skill-tag {
        font-size: 0.75rem;
        color: var (--gray-700);
        background-color: var(--gray-200);
        padding: 0.15rem 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: all var(--transition-fast);
    }

    .job-card-mini:hover .skill-tag {
        background-color: var(--primary-accent);
        color: var(--primary-dark);
    }

    .btn-view-job {
        display: inline-block;
        background-color: var(--white);
        color: var(--primary);
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        padding: 0.5rem 1.25rem;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        border: 1px solid var(--primary);
        transition: all var(--transition-normal);
    }

    .btn-view-job:hover {
        background-color: var(--primary);
        color: var(--white);
        transform: translateY(-2px);
    }

    /* Services Empty State */
    .services-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .avatar-xs {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Responsive styles */
    @media (min-width: 992px) {
        .mobile-search-container {
            display: none;
        }

        .welcome-section {
            margin-top: 70px;
            /* Restore margin for desktop */
        }
    }

    @media (max-width: 992px) {
        .welcome-subtitle {
            max-width: 100%;
        }

        .job-card-mini {
            margin-bottom: 15px;
        }
    }

    @media (max-width: 768px) {
        .welcome-title {
            font-size: 1.6rem;
        }

        .stat-details h3 {
            font-size: 1.25rem;
        }

        .job-card-mini {
            height: auto;
        }
    }

    @media (max-width: 576px) {
        .stats-container {
            gap: 1rem;
            padding: 1rem;
        }

        .jobs-slider-container {
            padding: 1rem;
        }

        .job-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<!-- JavaScript for Interactive Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jobs Slider Functionality
        const jobSlides = document.querySelectorAll('.jobs-slider .row');
        const nextJobBtn = document.querySelector('.next-job');
        const prevJobBtn = document.querySelector('.prev-job');
        const currentSlideElement = document.querySelector('.current-slide');
        const totalSlidesElement = document.querySelector('.total-slides');

        let currentSlideIndex = 0;
        const totalSlides = jobSlides.length;

        // Set total slides count
        totalSlidesElement.textContent = totalSlides;

        // Function to update jobs visibility
        function updateJobsVisibility() {
            // Hide all slides
            jobSlides.forEach(slide => {
                slide.classList.remove('active-slide');
            });

            // Show current slide
            jobSlides[currentSlideIndex].classList.add('active-slide');

            // Update pagination
            currentSlideElement.textContent = currentSlideIndex + 1;

            // Update button states
            prevJobBtn.disabled = currentSlideIndex === 0;
            nextJobBtn.disabled = currentSlideIndex === totalSlides - 1;
        }

        // Next slide button click
        nextJobBtn.addEventListener('click', function() {
            if (currentSlideIndex < totalSlides - 1) {
                currentSlideIndex++;
                updateJobsVisibility();
            }
        });

        // Previous slide button click
        prevJobBtn.addEventListener('click', function() {
            if (currentSlideIndex > 0) {
                currentSlideIndex--;
                updateJobsVisibility();
            }
        });

        // Initialize jobs slider
        updateJobsVisibility();

        // Refresh stats icon animation
        const refreshIcon = document.querySelector('.refresh-icon');
        if (refreshIcon) {
            refreshIcon.addEventListener('click', function() {
                this.style.transform = 'rotate(180deg)';
                setTimeout(() => {
                    this.style.transform = 'rotate(0deg)';
                }, 500);
            });
        }

        // Add hover effect to stats items
        const statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const icon = this.querySelector('.stat-icon');
                icon.style.transform = 'scale(1.1)';
            });

            item.addEventListener('mouseleave', function() {
                const icon = this.querySelector('.stat-icon');
                icon.style.transform = 'scale(1)';
            });
        });

        // Job cards hover effect
        const jobCards = document.querySelectorAll('.job-card-mini');
        jobCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.querySelector('.job-title').style.color = '#7952b3';
            });

            card.addEventListener('mouseleave', function() {
                this.querySelector('.job-title').style.color = '#222325';
            });
        });
    });
</script>