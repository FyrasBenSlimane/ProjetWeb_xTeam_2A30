<?php
// Client dashboard view for logged-in client users
?>

<!-- Client Indicator Badge -->
<div class="client-indicator">
    <div class="indicator-badge">
        <i class="fas fa-briefcase"></i> Client Account
    </div>
</div>

<!-- Mobile search form -->
<div class="d-block d-lg-none container-fluid py-3 mobile-search-container">
    <form class="search-form" action="<?php echo URL_ROOT; ?>/services/browse" method="GET">
        <input type="text" name="search" placeholder="What service are you looking for today?">
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>

<!-- Main container with custom styling -->
<main class="main-container">
    <section class="welcome-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="welcome-title mb-3">Welcome back, <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>!</h1>
                    <p class="welcome-subtitle text-muted">Your client dashboard is ready. Find talented freelancers for your projects.</p>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <a href="<?php echo URL_ROOT; ?>/jobs/post" class="btn-post-job">
                        <i class="fas fa-plus me-2"></i> Post a Job
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="client-dashboard py-4">
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
                                <div class="stat-icon jobs-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="stat-details">
                                    <h3>0</h3>
                                    <p>Posted Jobs</p>
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
                                <p>Browse services to find talented freelancers</p>
                                <a href="<?php echo URL_ROOT; ?>/services/browse" class="browse-services-btn">Browse Services</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Recommended Services Widget -->
                <div class="col-12 mb-4">
                    <div class="services-card">
                        <div class="card-header">
                            <h5>Recommended Services</h5>
                            <a href="<?php echo URL_ROOT; ?>/services/browse" class="text-decoration-none view-all">Browse All</a>
                        </div>
                        
                        <div class="services-slider-container">
                            <div class="services-slider-controls">
                                <button class="slider-control prev-service" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div class="slider-pagination">
                                    <span class="current-slide">1</span> / <span class="total-slides">4</span>
                                </div>
                                <button class="slider-control next-service">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            
                            <div class="services-slider">
                                <div class="row active-slide">
                                    <!-- Service Card 1 -->
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?website" alt="Web Development" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">James Wilson</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will design and develop a responsive website</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.9</span> <span class="text-muted">(124)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$150</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Card 2 -->
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?logo" alt="Logo Design" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Sarah Johnson</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will create a modern logo design for your brand</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.8</span> <span class="text-muted">(87)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$85</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Card 3 -->
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?marketing" alt="Digital Marketing" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Michael Chen</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will create a full social media marketing strategy</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.7</span> <span class="text-muted">(56)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$120</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Service Card 4 -->
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?copywriting" alt="Content Writing" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/22.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Emily Rodriguez</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will write SEO-optimized articles and blog posts</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>5.0</span> <span class="text-muted">(42)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$75</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Add another slide with different services -->
                                <div class="row">
                                    <!-- Additional service cards for slide 2 -->
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?app" alt="Mobile App" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">David Kumar</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will develop a custom mobile app for iOS and Android</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.9</span> <span class="text-muted">(78)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$250</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?video" alt="Video Editing" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/52.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Rebecca Santos</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will edit and produce professional videos</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.8</span> <span class="text-muted">(63)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$120</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?illustration" alt="Illustration" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/men/77.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Alex Mercer</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will create custom digital illustrations for your project</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>5.0</span> <span class="text-muted">(92)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$95</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <div class="service-card-mini">
                                            <img src="https://source.unsplash.com/random/300x200/?seo" alt="SEO" class="img-fluid rounded">
                                            <div class="service-details p-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Provider" class="avatar-xs me-2">
                                                    <span class="small text-muted">Sophia Wang</span>
                                                </div>
                                                <h6 class="service-title mb-1 small fw-semibold">I will optimize your website for search engines</h6>
                                                <div class="rating small">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span>4.7</span> <span class="text-muted">(48)</span>
                                                </div>
                                                <div class="price mt-2">
                                                    <span class="small text-muted">From</span>
                                                    <span class="fw-bold price-value">$150</span>
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
        </div>
    </section>
</main>

<!-- Styles for Client Dashboard -->
<style>
    /* Root variables */
    :root {
        /* Main color palette - professional green scheme for clients */
        --primary: #108a00;
        --primary-light: #27ae60;
        --primary-dark: #0e7800;
        --primary-accent: #e6f7e6;
        
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
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        --shadow-md: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        --shadow-lg: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        --shadow-xl: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        
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
    
    h1, h2, h3, h4, h5, h6, p {
        margin: 0;
    }
    
    /* Client Indicator Badge */
    .client-indicator {
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
        background: linear-gradient(135deg, rgba(16, 138, 0, 0.03) 0%, rgba(16, 138, 0, 0.01) 100%);
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
        box-shadow: 0 0 0 3px rgba(16, 138, 0, 0.1);
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
        margin-top: 0; /* Adjusted for search bar */
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
    
    .btn-post-job {
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
    
    .btn-post-job:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
        color: var(--white);
    }
    
    /* Dashboard Cards Common Styles */
    .stats-card, .orders-card, .services-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        height: 100%;
        border: none;
    }
    
    .stats-card:hover, .orders-card:hover, .services-card:hover {
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
        background-color: rgba(16, 138, 0, 0.1);
        color: var(--primary);
    }
    
    .completed-icon {
        background-color: rgba(46, 204, 113, 0.1);
        color: var(--accent-green);
    }
    
    .jobs-icon {
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
            box-shadow: 0 0 0 0 rgba(16, 138, 0, 0.2);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(16, 138, 0, 0);
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
    
    .browse-services-btn {
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
    
    .browse-services-btn:hover {
        background-color: var(--primary);
        color: var(--white);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    /* Services Card Styles */
    .services-slider-container {
        padding: 1.5rem;
    }
    
    .services-slider-controls {
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
    
    .services-slider {
        position: relative;
        overflow: hidden;
    }
    
    .services-slider .row {
        display: none;
        opacity: 0;
        transition: opacity 0.35s ease;
    }
    
    .services-slider .row.active-slide {
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
    
    /* Service Card Mini Styles */
    .service-card-mini {
        border-radius: var(--border-radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-normal);
        height: 100%;
        border: 1px solid var(--gray-200);
        background-color: var(--white);
    }
    
    .service-card-mini:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    
    .service-card-mini img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        transition: transform var(--transition-normal);
    }
    
    .service-card-mini:hover img {
        transform: scale(1.05);
    }
    
    .service-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
        font-size: 0.85rem;
        color: var(--secondary);
        transition: color var(--transition-fast);
    }
    
    .service-card-mini:hover .service-title {
        color: var(--primary);
    }
    
    .avatar-xs {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .price-value {
        color: var(--primary);
        font-size: 0.95rem;
    }
    
    /* Responsive styles */
    @media (min-width: 992px) {
        .mobile-search-container {
            display: none;
        }
        
        .welcome-section {
            margin-top: 70px; /* Restore margin for desktop */
        }
    }
    
    @media (max-width: 992px) {
        .welcome-subtitle {
            max-width: 100%;
        }
        
        .service-card-mini {
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
    }
    
    @media (max-width: 576px) {
        .stats-container {
            gap: 1rem;
            padding: 1rem;
        }
        
        .services-slider-container {
            padding: 1rem;
        }
    }
</style>

<!-- JavaScript for Interactive Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Services Slider Functionality
    const serviceSlides = document.querySelectorAll('.services-slider .row');
    const nextServiceBtn = document.querySelector('.next-service');
    const prevServiceBtn = document.querySelector('.prev-service');
    const currentSlideElement = document.querySelector('.current-slide');
    const totalSlidesElement = document.querySelector('.total-slides');
    
    let currentSlideIndex = 0;
    const totalSlides = serviceSlides.length;
    
    // Set total slides count
    totalSlidesElement.textContent = totalSlides;
    
    // Function to update services visibility
    function updateServicesVisibility() {
        // Hide all slides
        serviceSlides.forEach(slide => {
            slide.classList.remove('active-slide');
        });
        
        // Show current slide
        serviceSlides[currentSlideIndex].classList.add('active-slide');
        
        // Update pagination
        currentSlideElement.textContent = currentSlideIndex + 1;
        
        // Update button states
        prevServiceBtn.disabled = currentSlideIndex === 0;
        nextServiceBtn.disabled = currentSlideIndex === totalSlides - 1;
    }
    
    // Next slide button click
    nextServiceBtn.addEventListener('click', function() {
        if (currentSlideIndex < totalSlides - 1) {
            currentSlideIndex++;
            updateServicesVisibility();
        }
    });
    
    // Previous slide button click
    prevServiceBtn.addEventListener('click', function() {
        if (currentSlideIndex > 0) {
            currentSlideIndex--;
            updateServicesVisibility();
        }
    });
    
    // Initialize services slider
    updateServicesVisibility();
    
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
    
    // Service cards hover effect
    const serviceCards = document.querySelectorAll('.service-card-mini');
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.price-value').style.color = '#0e7800';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.price-value').style.color = '#108a00';
        });
    });
});
</script>