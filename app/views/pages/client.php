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
            
            <!-- Learning Resources Section -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="resources-card">
                        <div class="card-header">
                            <h5><i class="fas fa-graduation-cap me-2"></i>Learning Resources</h5>
                            <span class="badge bg-success">New</span>
                        </div>
                        <div class="resources-container">
                            <div class="resource-categories">
                                <button class="resource-category-btn active" data-category="getting-started">
                                    <i class="fas fa-rocket"></i>
                                    <span>Getting Started</span>
                                </button>
                                <button class="resource-category-btn" data-category="working-with-freelancers">
                                    <i class="fas fa-users"></i>
                                    <span>Working with Freelancers</span>
                                </button>
                                <button class="resource-category-btn" data-category="project-management">
                                    <i class="fas fa-tasks"></i>
                                    <span>Project Management</span>
                                </button>
                                <button class="resource-category-btn" data-category="payments">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Payments & Billing</span>
                                </button>
                            </div>
                            
                            <div class="resource-content">
                                <!-- Getting Started Resources -->
                                <div class="resource-category-content active" id="getting-started">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=2YQHSGhQbDE" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Platform Overview</h6>
                                                    <p>Learn the basics of our platform in this 10-minute walkthrough</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">10:23</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=jyWUC-xVgMc" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Creating Your First Job</h6>
                                                    <p>Step-by-step guide to posting your first job</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">7:15</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.udemy.com/course/hire-the-right-freelancers/" target="_blank" class="resource-card">
                                                <div class="resource-card-icon document">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Getting Started Guide</h6>
                                                    <p>Complete documentation for new clients</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-book me-1"></i>Guide</span>
                                                        <span class="resource-duration">15 min read</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Working with Freelancers Resources -->
                                <div class="resource-category-content" id="working-with-freelancers">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=YZTZnqOASLQ" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Finding the Perfect Freelancer</h6>
                                                    <p>Tips on how to evaluate and choose the right freelancers</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">12:48</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=-Al_lwLAyDA" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Effective Communication</h6>
                                                    <p>How to communicate effectively with your freelancers</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">9:37</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.toptal.com/freelance/don-t-be-a-bad-client" target="_blank" class="resource-card">
                                                <div class="resource-card-icon document">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Building Relationships</h6>
                                                    <p>How to build strong relationships with freelancers</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-book me-1"></i>Article</span>
                                                        <span class="resource-duration">8 min read</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Project Management Resources -->
                                <div class="resource-category-content" id="project-management">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=Vj-41zFdgkg" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Milestone Management</h6>
                                                    <p>How to set up and manage project milestones</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">11:52</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=KLkXPD6x7K4" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Tracking Project Progress</h6>
                                                    <p>Tools and methods for effective project tracking</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">14:05</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://blog.hubspot.com/service/project-management-tips" target="_blank" class="resource-card">
                                                <div class="resource-card-icon document">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Project Management Guide</h6>
                                                    <p>Best practices for managing remote projects</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-book me-1"></i>Guide</span>
                                                        <span class="resource-duration">12 min read</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payments Resources -->
                                <div class="resource-category-content" id="payments">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=8UmUuaAzMJU" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Payment Methods Overview</h6>
                                                    <p>Learn about different payment options available</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">8:27</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.youtube.com/watch?v=rQUuGDKlZPk" target="_blank" class="resource-card">
                                                <div class="resource-card-icon video">
                                                    <i class="fab fa-youtube"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Escrow Services</h6>
                                                    <p>How to use escrow services for secure payments</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-video me-1"></i>Video</span>
                                                        <span class="resource-duration">6:42</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <a href="https://www.investopedia.com/terms/e/escrow.asp" target="_blank" class="resource-card">
                                                <div class="resource-card-icon document">
                                                    <i class="fas fa-file-alt"></i>
                                                </div>
                                                <div class="resource-card-content">
                                                    <h6>Billing & Invoicing</h6>
                                                    <p>Understanding billing cycles and invoices</p>
                                                    <div class="resource-meta">
                                                        <span class="resource-type"><i class="fas fa-book me-1"></i>Guide</span>
                                                        <span class="resource-duration">10 min read</span>
                                                    </div>
                                                </div>
                                            </a>
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
        // ...existing code...
    }
    
    /* General Styles */
    body {
        // ...existing code...
    }
    
    // ...existing code...
    
    /* Services Slider Styles */
    // ...existing code...
    
    /* Learning Resources Styles */
    .resources-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: transform var(--transition-normal), box-shadow var(--transition-normal);
        margin-bottom: 1.5rem;
    }
    
    .resources-card .card-header {
        background-color: var(--white);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .resources-card .card-header h5 {
        margin-bottom: 0;
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        display: flex;
        align-items: center;
    }
    
    .badge.bg-success {
        background-color: var(--primary-light) !important;
        font-weight: 500;
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
    }
    
    .resources-container {
        padding: 1.5rem;
    }
    
    .resource-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        padding-bottom: 1rem;
    }
    
    .resource-category-btn {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        color: var(--secondary);
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        padding: 0.6rem 1.2rem;
        border-radius: var(--border-radius-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all var(--transition-fast);
        cursor: pointer;
    }
    
    .resource-category-btn:hover {
        background-color: var(--gray-100);
    }
    
    .resource-category-btn.active {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--white);
    }
    
    .resource-category-content {
        display: none;
    }
    
    .resource-category-content.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }
    
    .resource-card {
        display: flex;
        background-color: var(--white);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: all var(--transition-normal);
        height: 100%;
        text-decoration: none;
        color: var(--secondary);
        border: 1px solid var(--gray-200);
    }
    
    .resource-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .resource-card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        font-size: 1.5rem;
    }
    
    .resource-card-icon.video {
        background-color: rgba(255, 0, 0, 0.1);
        color: #ff0000;
    }
    
    .resource-card-icon.document {
        background-color: rgba(0, 123, 255, 0.1);
        color: #0d6efd;
    }
    
    .resource-card-content {
        flex-grow: 1;
        padding: 1rem;
    }
    
    .resource-card-content h6 {
        font-weight: var(--font-weight-semibold);
        margin-bottom: 0.5rem;
        color: var(--secondary);
    }
    
    .resource-card-content p {
        font-size: 0.85rem;
        color: var(--gray-600);
        margin-bottom: 0.75rem;
    }
    
    .resource-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--gray-500);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .resource-category-btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
        
        .resource-categories {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
    }
    
    /* Existing responsive styles */
    @media (min-width: 992px) {
        // ...existing code...
    }
    
    @media (max-width: 992px) {
        // ...existing code...
    }
    
    @media (max-width: 768px) {
        // ...existing code...
    }
    
    @media (max-width: 576px) {
        // ...existing code...
    }
</style>

<!-- JavaScript for Interactive Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Services Slider Functionality
    // ...existing code...
    
    // Resource Category Tab Navigation
    const resourceCategoryBtns = document.querySelectorAll('.resource-category-btn');
    const resourceCategoryContents = document.querySelectorAll('.resource-category-content');
    
    resourceCategoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            resourceCategoryBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get category from data attribute
            const category = this.getAttribute('data-category');
            
            // Hide all content sections
            resourceCategoryContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected content
            document.getElementById(category).classList.add('active');
        });
    });
    
    // Track resource clicks for analytics (could be expanded in the future)
    const resourceLinks = document.querySelectorAll('.resource-card');
    resourceLinks.forEach(link => {
        link.addEventListener('click', function() {
            const resourceTitle = this.querySelector('h6').textContent;
            const resourceCategory = this.closest('.resource-category-content').getAttribute('id');
            const resourceType = this.querySelector('.resource-type').textContent;
            
            // This could send data to an analytics endpoint in the future
            console.log(`Resource clicked: ${resourceTitle} (${resourceType}) in ${resourceCategory}`);
        });
    });
});
</script>