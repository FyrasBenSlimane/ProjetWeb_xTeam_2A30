<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="learning-resources-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Learning Resources</h1>
                    <p class="hero-subtitle">Enhance your skills with our curated collection of tutorials, guides, and courses to boost your career.</p>
                </div>
                <div class="col-lg-6">
                    <img src="<?php echo URLROOT; ?>/public/images/learning-hero.svg" alt="Learning Resources" class="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="resources-section">
        <div class="container">
            <!-- Category Navigation -->
            <div class="resource-categories">
                <button class="category-btn active" data-target="all">All Resources</button>
                <button class="category-btn" data-target="tech">Tech & Programming</button>
                <button class="category-btn" data-target="design">Design</button>
                <button class="category-btn" data-target="business">Business & Marketing</button>
                <button class="category-btn" data-target="career">Career Development</button>
            </div>

            <!-- Filter Options -->
            <div class="filter-options">
                <div class="dropdown">
                    <button class="btn btn-filter dropdown-toggle" type="button" id="typeFilterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-layer-group me-2"></i> Type
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="typeFilterBtn">
                        <li><a class="dropdown-item filter-option active" href="#" data-type="all">All Types</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-type="video">Videos</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-type="document">Articles & Guides</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-filter dropdown-toggle" type="button" id="levelFilterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-signal me-2"></i> Level
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="levelFilterBtn">
                        <li><a class="dropdown-item filter-option active" href="#" data-level="all">All Levels</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-level="Beginner">Beginner</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-level="Intermediate">Intermediate</a></li>
                        <li><a class="dropdown-item filter-option" href="#" data-level="Advanced">Advanced</a></li>
                    </ul>
                </div>
                <div class="search-filter">
                    <input type="text" id="resourceSearch" placeholder="Search resources..." class="form-control">
                </div>
            </div>

            <!-- Resource Cards - Tech & Programming -->
            <div class="resource-category-section" id="tech-resources">
                <h2 class="section-title">Tech & Programming</h2>
                <div class="row">
                    <?php foreach ($data['techResources'] as $resource): ?>
                        <div class="col-md-6 col-lg-3 mb-4 resource-card-container" 
                             data-type="<?php echo $resource['type']; ?>" 
                             data-level="<?php echo $resource['level']; ?>">
                            <div class="resource-card">
                                <div class="resource-thumbnail">
                                    <img src="<?php echo $resource['thumbnail']; ?>" alt="<?php echo $resource['title']; ?>" class="img-fluid">
                                    <div class="resource-overlay">
                                        <span class="resource-type-badge <?php echo $resource['type']; ?>">
                                            <?php if ($resource['type'] == 'video'): ?>
                                                <i class="fas fa-play"></i> Video
                                            <?php else: ?>
                                                <i class="fas fa-file-alt"></i> Article
                                            <?php endif; ?>
                                        </span>
                                        <span class="resource-duration">
                                            <i class="far fa-clock"></i> <?php echo $resource['duration']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="resource-content">
                                    <h3 class="resource-title"><?php echo $resource['title']; ?></h3>
                                    <p class="resource-description"><?php echo $resource['description']; ?></p>
                                    <div class="resource-meta">
                                        <span class="resource-provider"><i class="fas fa-user"></i> <?php echo $resource['provider']; ?></span>
                                        <span class="resource-level"><i class="fas fa-chart-line"></i> <?php echo $resource['level']; ?></span>
                                    </div>
                                    <a href="<?php echo $resource['url']; ?>" class="btn btn-resource" target="_blank" data-resource-id="tech-<?php echo key($data['techResources']); ?>">
                                        <?php if ($resource['type'] == 'video'): ?>
                                            <i class="fas fa-play-circle me-2"></i> Watch Now
                                        <?php else: ?>
                                            <i class="fas fa-book me-2"></i> Read Now
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Resource Cards - Design -->
            <div class="resource-category-section" id="design-resources">
                <h2 class="section-title">Design</h2>
                <div class="row">
                    <?php foreach ($data['designResources'] as $resource): ?>
                        <div class="col-md-6 col-lg-3 mb-4 resource-card-container" 
                             data-type="<?php echo $resource['type']; ?>" 
                             data-level="<?php echo $resource['level']; ?>">
                            <div class="resource-card">
                                <div class="resource-thumbnail">
                                    <img src="<?php echo $resource['thumbnail']; ?>" alt="<?php echo $resource['title']; ?>" class="img-fluid">
                                    <div class="resource-overlay">
                                        <span class="resource-type-badge <?php echo $resource['type']; ?>">
                                            <?php if ($resource['type'] == 'video'): ?>
                                                <i class="fas fa-play"></i> Video
                                            <?php else: ?>
                                                <i class="fas fa-file-alt"></i> Article
                                            <?php endif; ?>
                                        </span>
                                        <span class="resource-duration">
                                            <i class="far fa-clock"></i> <?php echo $resource['duration']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="resource-content">
                                    <h3 class="resource-title"><?php echo $resource['title']; ?></h3>
                                    <p class="resource-description"><?php echo $resource['description']; ?></p>
                                    <div class="resource-meta">
                                        <span class="resource-provider"><i class="fas fa-user"></i> <?php echo $resource['provider']; ?></span>
                                        <span class="resource-level"><i class="fas fa-chart-line"></i> <?php echo $resource['level']; ?></span>
                                    </div>
                                    <a href="<?php echo $resource['url']; ?>" class="btn btn-resource" target="_blank" data-resource-id="design-<?php echo key($data['designResources']); ?>">
                                        <?php if ($resource['type'] == 'video'): ?>
                                            <i class="fas fa-play-circle me-2"></i> Watch Now
                                        <?php else: ?>
                                            <i class="fas fa-book me-2"></i> Read Now
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Resource Cards - Business & Marketing -->
            <div class="resource-category-section" id="business-resources">
                <h2 class="section-title">Business & Marketing</h2>
                <div class="row">
                    <?php foreach ($data['businessResources'] as $resource): ?>
                        <div class="col-md-6 col-lg-3 mb-4 resource-card-container" 
                             data-type="<?php echo $resource['type']; ?>" 
                             data-level="<?php echo $resource['level']; ?>">
                            <div class="resource-card">
                                <div class="resource-thumbnail">
                                    <img src="<?php echo $resource['thumbnail']; ?>" alt="<?php echo $resource['title']; ?>" class="img-fluid">
                                    <div class="resource-overlay">
                                        <span class="resource-type-badge <?php echo $resource['type']; ?>">
                                            <?php if ($resource['type'] == 'video'): ?>
                                                <i class="fas fa-play"></i> Video
                                            <?php else: ?>
                                                <i class="fas fa-file-alt"></i> Article
                                            <?php endif; ?>
                                        </span>
                                        <span class="resource-duration">
                                            <i class="far fa-clock"></i> <?php echo $resource['duration']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="resource-content">
                                    <h3 class="resource-title"><?php echo $resource['title']; ?></h3>
                                    <p class="resource-description"><?php echo $resource['description']; ?></p>
                                    <div class="resource-meta">
                                        <span class="resource-provider"><i class="fas fa-user"></i> <?php echo $resource['provider']; ?></span>
                                        <span class="resource-level"><i class="fas fa-chart-line"></i> <?php echo $resource['level']; ?></span>
                                    </div>
                                    <a href="<?php echo $resource['url']; ?>" class="btn btn-resource" target="_blank" data-resource-id="business-<?php echo key($data['businessResources']); ?>">
                                        <?php if ($resource['type'] == 'video'): ?>
                                            <i class="fas fa-play-circle me-2"></i> Watch Now
                                        <?php else: ?>
                                            <i class="fas fa-book me-2"></i> Read Now
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Resource Cards - Career Development -->
            <div class="resource-category-section" id="career-resources">
                <h2 class="section-title">Career Development</h2>
                <div class="row">
                    <?php foreach ($data['careerResources'] as $resource): ?>
                        <div class="col-md-6 col-lg-3 mb-4 resource-card-container" 
                             data-type="<?php echo $resource['type']; ?>" 
                             data-level="<?php echo $resource['level']; ?>">
                            <div class="resource-card">
                                <div class="resource-thumbnail">
                                    <img src="<?php echo $resource['thumbnail']; ?>" alt="<?php echo $resource['title']; ?>" class="img-fluid">
                                    <div class="resource-overlay">
                                        <span class="resource-type-badge <?php echo $resource['type']; ?>">
                                            <?php if ($resource['type'] == 'video'): ?>
                                                <i class="fas fa-play"></i> Video
                                            <?php else: ?>
                                                <i class="fas fa-file-alt"></i> Article
                                            <?php endif; ?>
                                        </span>
                                        <span class="resource-duration">
                                            <i class="far fa-clock"></i> <?php echo $resource['duration']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="resource-content">
                                    <h3 class="resource-title"><?php echo $resource['title']; ?></h3>
                                    <p class="resource-description"><?php echo $resource['description']; ?></p>
                                    <div class="resource-meta">
                                        <span class="resource-provider"><i class="fas fa-user"></i> <?php echo $resource['provider']; ?></span>
                                        <span class="resource-level"><i class="fas fa-chart-line"></i> <?php echo $resource['level']; ?></span>
                                    </div>
                                    <a href="<?php echo $resource['url']; ?>" class="btn btn-resource" target="_blank" data-resource-id="career-<?php echo key($data['careerResources']); ?>">
                                        <?php if ($resource['type'] == 'video'): ?>
                                            <i class="fas fa-play-circle me-2"></i> Watch Now
                                        <?php else: ?>
                                            <i class="fas fa-book me-2"></i> Read Now
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- No Results Message -->
            <div id="no-results-message" style="display: none;">
                <div class="empty-state">
                    <img src="<?php echo URLROOT; ?>/public/images/empty-state.svg" alt="No results" class="empty-state-icon">
                    <h3>No matching resources found</h3>
                    <p>Try adjusting your filters or search terms to find what you're looking for.</p>
                    <button id="reset-filters" class="btn btn-primary">Reset Filters</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2>Get Learning Resources Delivered to Your Inbox</h2>
                        <p>Subscribe to our newsletter and receive weekly updates with the latest tutorials, articles, and learning resources.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email address" required>
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- CSS for Learning Resources Page -->
<style>
    :root {
        --primary-color: #108a00;
        --primary-dark: #096d00;
        --primary-light: #27ae60;
        --primary-bg: #e7f7e3;
        --secondary-color: #222325;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --gray-800: #343a40;
        --gray-900: #212529;
        --white: #ffffff;
        --video-badge: #ff5050;
        --document-badge: #2196f3;
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        --transition: all 0.3s ease;
        --spacing-xs: 0.5rem;
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 3rem;
        --spacing-xl: 5rem;
    }

    .learning-resources-page {
        padding-top: 70px;
        background-color: #f9fafb;
        min-height: 100vh;
    }

    /* Hero Section Styles */
    .page-hero {
        padding: var(--spacing-xl) 0;
        background: linear-gradient(135deg, #def7d4 0%, #f0fff0 100%);
        margin-bottom: var(--spacing-lg);
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: var(--spacing-sm);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: var(--gray-700);
        margin-bottom: var(--spacing-md);
        line-height: 1.6;
    }

    .hero-image {
        max-width: 100%;
        height: auto;
    }

    /* Resource Categories Navigation */
    .resource-categories {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-sm);
        border-bottom: 1px solid var(--gray-200);
    }

    .category-btn {
        background: none;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: var(--radius-md);
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .category-btn:hover {
        background-color: rgba(16, 138, 0, 0.1);
    }

    .category-btn.active {
        background-color: var(--primary-color);
        color: white;
    }

    /* Filter Options */
    .filter-options {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: var(--spacing-md);
    }

    .btn-filter {
        background-color: var(--white);
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
        border-radius: var(--radius-md);
        padding: 8px 16px;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .btn-filter:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .search-filter {
        flex-grow: 1;
        max-width: 300px;
    }

    .search-filter input {
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-300);
        padding: 8px 16px;
        transition: var(--transition);
    }

    .search-filter input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 138, 0, 0.15);
    }

    /* Resource Section */
    .resources-section {
        padding: var(--spacing-md) 0 var(--spacing-lg);
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-xs);
        border-bottom: 2px solid var(--primary-light);
        display: inline-block;
        color: var(--secondary-color);
    }

    .resource-category-section {
        margin-bottom: var(--spacing-lg);
    }

    /* Resource Card Styles */
    .resource-card {
        background-color: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .resource-thumbnail {
        position: relative;
        overflow: hidden;
        height: 160px;
    }

    .resource-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .resource-card:hover .resource-thumbnail img {
        transform: scale(1.05);
    }

    .resource-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%);
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .resource-type-badge {
        background-color: var(--document-badge);
        color: white;
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .resource-type-badge.video {
        background-color: var(--video-badge);
    }

    .resource-duration {
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .resource-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .resource-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--secondary-color);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .resource-description {
        color: var(--gray-700);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .resource-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.8rem;
        color: var(--gray-600);
    }

    .resource-provider, .resource-level {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-resource {
        background-color: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: var(--radius-md);
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        transition: var(--transition);
    }

    .btn-resource:hover {
        background-color: var(--primary-dark);
        color: white;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-lg) 0;
        text-align: center;
    }

    .empty-state-icon {
        width: 120px;
        height: auto;
        margin-bottom: var(--spacing-sm);
    }

    .empty-state h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: var(--spacing-xs);
    }

    .empty-state p {
        color: var(--gray-600);
        margin-bottom: var(--spacing-sm);
    }

    /* Newsletter Section */
    .newsletter-section {
        padding: var(--spacing-lg) 0;
        background-color: var(--primary-bg);
    }

    .newsletter-container {
        background-color: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: var(--spacing-lg);
    }

    .newsletter-container h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: var(--spacing-sm);
    }

    .newsletter-container p {
        color: var(--gray-700);
        margin-bottom: var(--spacing-md);
    }

    .newsletter-form .input-group {
        display: flex;
    }

    .newsletter-form .form-control {
        border-radius: var(--radius-md) 0 0 var(--radius-md);
        border: 1px solid var(--gray-300);
        padding: 12px 16px;
    }

    .newsletter-form .btn {
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        transition: var(--transition);
    }

    .newsletter-form .btn:hover {
        background-color: var(--primary-dark);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2rem;
        }

        .newsletter-container {
            padding: var(--spacing-md);
        }

        .newsletter-form {
            margin-top: var(--spacing-sm);
        }
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: var(--spacing-lg) 0;
            text-align: center;
        }

        .hero-image {
            margin-top: var(--spacing-md);
        }

        .filter-options {
            justify-content: center;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .search-filter {
            max-width: 100%;
            width: 100%;
            order: -1;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 1.75rem;
        }

        .category-btn {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        .newsletter-container h2 {
            font-size: 1.5rem;
        }
    }
</style>

<!-- JavaScript for Resource Filtering and Interaction -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const categoryButtons = document.querySelectorAll('.category-btn');
    const typeFilters = document.querySelectorAll('[data-type]');
    const levelFilters = document.querySelectorAll('[data-level]');
    const resourceCards = document.querySelectorAll('.resource-card-container');
    const resourceSections = document.querySelectorAll('.resource-category-section');
    const searchInput = document.getElementById('resourceSearch');
    const noResultsMessage = document.getElementById('no-results-message');
    const resetFiltersBtn = document.getElementById('reset-filters');

    // Current filter state
    let currentCategory = 'all';
    let currentType = 'all';
    let currentLevel = 'all';
    let currentSearch = '';

    // Category filter functionality
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update current category
            currentCategory = this.getAttribute('data-target');
            
            // Apply filters
            applyFilters();
        });
    });

    // Type filter functionality
    typeFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all type filters
            document.querySelectorAll('[data-type]').forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked filter
            this.classList.add('active');
            
            // Update current type
            currentType = this.getAttribute('data-type');
            
            // Update dropdown button text
            document.getElementById('typeFilterBtn').innerHTML = 
                '<i class="fas fa-layer-group me-2"></i> ' + 
                (currentType === 'all' ? 'Type' : (currentType === 'video' ? 'Videos' : 'Articles'));
            
            // Apply filters
            applyFilters();
        });
    });

    // Level filter functionality
    levelFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all level filters
            document.querySelectorAll('[data-level]').forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked filter
            this.classList.add('active');
            
            // Update current level
            currentLevel = this.getAttribute('data-level');
            
            // Update dropdown button text
            document.getElementById('levelFilterBtn').innerHTML = 
                '<i class="fas fa-signal me-2"></i> ' + 
                (currentLevel === 'all' ? 'Level' : currentLevel);
            
            // Apply filters
            applyFilters();
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        currentSearch = this.value.toLowerCase().trim();
        applyFilters();
    });

    // Reset filters
    resetFiltersBtn.addEventListener('click', function() {
        // Reset category
        categoryButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-target') === 'all') {
                btn.classList.add('active');
            }
        });
        currentCategory = 'all';
        
        // Reset type
        document.querySelectorAll('[data-type]').forEach(f => {
            f.classList.remove('active');
            if (f.getAttribute('data-type') === 'all') {
                f.classList.add('active');
            }
        });
        currentType = 'all';
        document.getElementById('typeFilterBtn').innerHTML = '<i class="fas fa-layer-group me-2"></i> Type';
        
        // Reset level
        document.querySelectorAll('[data-level]').forEach(f => {
            f.classList.remove('active');
            if (f.getAttribute('data-level') === 'all') {
                f.classList.add('active');
            }
        });
        currentLevel = 'all';
        document.getElementById('levelFilterBtn').innerHTML = '<i class="fas fa-signal me-2"></i> Level';
        
        // Reset search
        searchInput.value = '';
        currentSearch = '';
        
        // Apply reset filters
        applyFilters();
    });

    // Apply all filters function
    function applyFilters() {
        let visibleCards = 0;
        
        // Show/hide resource sections based on category
        if (currentCategory === 'all') {
            resourceSections.forEach(section => {
                section.style.display = 'block';
            });
        } else {
            resourceSections.forEach(section => {
                if (section.id === currentCategory + '-resources') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }
        
        // Filter individual cards
        resourceCards.forEach(card => {
            const cardType = card.getAttribute('data-type');
            const cardLevel = card.getAttribute('data-level');
            const cardTitle = card.querySelector('.resource-title').textContent.toLowerCase();
            const cardDescription = card.querySelector('.resource-description').textContent.toLowerCase();
            const cardSection = card.closest('.resource-category-section').id;
            
            // Check if card matches all filters
            const matchesCategory = currentCategory === 'all' || cardSection === currentCategory + '-resources';
            const matchesType = currentType === 'all' || cardType === currentType;
            const matchesLevel = currentLevel === 'all' || cardLevel === currentLevel;
            const matchesSearch = !currentSearch || 
                                 cardTitle.includes(currentSearch) || 
                                 cardDescription.includes(currentSearch);
            
            // Show/hide card based on filter match
            if (matchesCategory && matchesType && matchesLevel && matchesSearch) {
                card.style.display = 'block';
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCards === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    // Track resource clicks
    document.querySelectorAll('.btn-resource').forEach(button => {
        button.addEventListener('click', function() {
            const resourceId = this.getAttribute('data-resource-id');
            const resourceUrl = this.getAttribute('href');
            const resourceTitle = this.closest('.resource-card').querySelector('.resource-title').textContent;
            
            // Send tracking data to server (AJAX request)
            fetch('<?php echo URLROOT; ?>/learning/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    resourceId,
                    resourceUrl,
                    resourceTitle
                })
            }).catch(error => console.error('Error tracking resource click:', error));
        });
    });

    // Animate cards on scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    resourceCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.4s ease ${index % 4 * 0.1}s`;
        observer.observe(card);
    });
});
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>