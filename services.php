<?php
// Set up basic error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the components with absolute paths
$componentsPath = __DIR__ . '/components';
$components = [
    'navbar' => $componentsPath . '/navbar.php',
    'footer' => $componentsPath . '/footer.php'
];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=5.0, minimum-scale=1.0">
    <meta name="description" content="Explore Services - LenSi Freelance Marketplace">
    <meta name="theme-color" content="#3E5C76">
    <title>Services | LenSi</title>
    <link rel="icon" type="image/svg+xml" href="assets/images/logo_white.svg" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Preload critical images -->
    <link rel="preload" as="image" href="assets/images/logo_white.svg">
    <link rel="preload" as="image" href="assets/images/logo_dark.svg">
    
    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" as="style">
    
    <style>
    /* Enhanced Styles */
    :root {
        --primary: #3E5C76;
        --primary-rgb: 62, 92, 118;
        --secondary: #748CAB;
        --accent: #1D2D44;
        --accent-dark: #0D1B2A;
        --light: #F9F7F0;
        --dark: #0D1B2A;
        --font-primary: 'Montserrat', sans-serif;
        --font-secondary: 'Inter', sans-serif;
        --font-heading: 'Poppins', sans-serif;
        --transition-default: all 0.3s ease;
        --transition-bounce: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .services-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        padding: calc(2rem + 70px) 0 3rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .services-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
    }

    .search-filters {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: -2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }

    .search-container {
        position: relative;
        flex: 1;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: var(--transition-default);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(var(--primary-rgb), 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary);
        font-size: 1.2rem;
    }

    .filters-panel {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        margin: 1rem 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
        transform: translateZ(0);
        will-change: transform;
        contain: layout style paint;
        content-visibility: auto;
        contain-intrinsic-size: 400px;
    }

    .service-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: var(--transition-bounce);
        text-decoration: none;
        color: inherit;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        position: relative;
        contain: content;
        will-change: transform, opacity;
        content-visibility: auto;
        contain-intrinsic-size: 350px;
        backface-visibility: hidden;
        transform: translateZ(0);
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .service-image-container {
        position: relative;
        aspect-ratio: 16/9;
        overflow: hidden;
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    .service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        content-visibility: auto;
    }

    .service-card:hover .service-image {
        transform: scale(1.05);
    }

    .featured-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .service-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .service-provider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .provider-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .provider-info {
        flex: 1;
    }

    .provider-name {
        font-weight: 600;
        font-size: 1rem;
        color: var(--accent);
        margin: 0;
    }

    .provider-level {
        font-size: 0.85rem;
        color: var(--secondary);
        margin: 0;
    }

    .service-title {
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 1rem;
        color: var(--accent);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .service-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .star-icon {
        color: #FFD700;
    }

    .rating-count {
        font-size: 0.9rem;
        color: var(--secondary);
    }

    .service-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .price-label {
        font-size: 0.9rem;
        color: var (--secondary);
    }

    .service-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
    }

    .filter-group {
        margin-bottom: 2rem;
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var (--accent);
    }

    .filter-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: var(--transition-default);
    }

    .filter-checkbox:hover {
        background: rgba(var(--primary-rgb), 0.05);
    }

    .price-range {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .price-input {
        width: 100px;
        padding: 0.5rem;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.5rem;
    }

    /* Dark mode adjustments */
    [data-bs-theme="dark"] {
        .service-card {
            background: rgba(31, 32, 40, 0.8);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .service-title,
        .provider-name,
        .filter-title {
            color: var(--light);
        }

        .service-price {
            color: var(--secondary);
        }

        .filter-checkbox:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .search-filters,
        .filters-panel {
            background: rgba(31, 32, 40, 0.8);
            border-color: rgba(255, 255, 255, 0.05);
        }
    }

    @media (max-width: 768px) {
        .services-grid {
            grid-template-columns: 1fr;
        }

        .filter-options {
            grid-template-columns: 1fr;
        }

        .search-filters {
            flex-direction: column;
            gap: 1rem;
        }
    }

    /* Add progressive loading animation */
    .service-card[data-loaded="false"] {
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
    }

    @keyframes shine {
        to {
            background-position-x: -200%;
        }
    }

    /* Optimize animations */
    @media (prefers-reduced-motion: reduce) {
        .service-card {
            transition: none !important;
        }
    }

    /* Performance optimizations */
    .services-grid {
        content-visibility: auto;
        contain-intrinsic-size: 400px;
    }
    
    .service-card {
        content-visibility: auto;
        contain-intrinsic-size: 350px;
        backface-visibility: hidden;
        transform: translateZ(0);
        will-change: transform, opacity;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .service-card {
            transition: opacity 0.2s ease-out;
        }
        
        .services-grid {
            transition: none;
        }
    }
    
    /* Add loading state */
    .service-card[data-loading="true"] {
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
    }
    
    [data-bs-theme="dark"] .service-card[data-loading="true"] {
        background: linear-gradient(110deg, #2a2a2a 8%, #353535 18%, #2a2a2a 33%);
    }
    
    /* Optimize paint operations */
    .service-image-container {
        transform: translateZ(0);
        backface-visibility: hidden;
    }
    
    /* Add container queries for better performance */
    @container (max-width: 768px) {
        .service-card {
            contain-intrinsic-size: 300px;
        }
    }
    </style>

    <?php include $components['navbar']; ?>

    <div class="services-header">
        <div class="services-container">
            <h1 class="display-4 fw-bold mb-4">Explore Services</h1>
            <p class="lead mb-0">Find the perfect service for your business needs</p>
        </div>
    </div>

    <div class="services-container">
        <div class="search-filters d-flex gap-3 align-items-center">
            <div class="search-container">
                <i class="bi bi-search search-icon"></i>
                <input type="search" class="search-input" placeholder="Search for services..." id="searchInput">
            </div>
            <button class="filter-btn" id="filterToggle">
                <i class="bi bi-sliders"></i>
                Filters
            </button>
            <select class="form-select" style="width: auto;" id="sortSelect">
                <option value="recommended">Recommended</option>
                <option value="rating">Top Rated</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
            </select>
        </div>

        <div class="filters-panel" id="filtersPanel" style="display: none;">
            <div class="filter-group">
                <h3 class="filter-title">Category</h3>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="all" checked>
                        All Categories
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="web">
                        Web Development
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="design">
                        Design & Creative
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="marketing">
                        Digital Marketing
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="writing">
                        Writing & Translation
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="video">
                        Video & Animation
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="data">
                        Data & Analytics
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="mobile">
                        Mobile Development
                    </label>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="music">
                        Music & Audio
                    </label>
                </div>
            </div>

            <div class="filter-group">
                <h3 class="filter-title">Price Range</h3>
                <div class="price-range">
                    <input type="number" class="price-input" placeholder="Min" min="0" id="minPrice">
                    <span>to</span>
                    <input type="number" class="price-input" placeholder="Max" min="0" id="maxPrice">
                </div>
            </div>
        </div>

        <div class="services-grid">
            <!-- Web Development -->
            <a href="#" class="service-card" data-category="web">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1587440871875-191322ee64b0" class="service-image" alt="Website Development" loading="lazy">
                    <span class="featured-badge">Featured</span>
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Alex Mitchell</p>
                            <p class="provider-level">Level 2 Seller</p>
                        </div>
                    </div>
                    <h3 class="service-title">Professional Website Development with Modern Technologies</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.9</span>
                        <span class="rating-count">(128)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$299</div>
                    </div>
                </div>
            </a>

            <!-- Design & Creative -->
            <a href="#" class="service-card" data-category="design">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5" class="service-image" alt="UI/UX Design" loading="lazy">
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/women/28.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Emma Davis</p>
                            <p class="provider-level">Top Rated Plus</p>
                        </div>
                    </div>
                    <h3 class="service-title">Modern UI/UX Design for Web and Mobile Applications</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.9</span>
                        <span class="rating-count">(256)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$199</div>
                    </div>
                </div>
            </a>

            <!-- Digital Marketing -->
            <a href="#" class="service-card" data-category="marketing">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1542744173-05336fcc7ad4" class="service-image" alt="Digital Marketing" loading="lazy">
                    <span class="featured-badge">Featured</span>
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Sarah Kennedy</p>
                            <p class="provider-level">Top Rated</p>
                        </div>
                    </div>
                    <h3 class="service-title">Complete Digital Marketing Strategy and Implementation</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.8</span>
                        <span class="rating-count">(93)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$399</div>
                    </div>
                </div>
            </a>

            <!-- Writing & Translation -->
            <a href="#" class="service-card" data-category="writing">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a" class="service-image" alt="Content Writing" loading="lazy">
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">James Wilson</p>
                            <p class="provider-level">Level 2 Seller</p>
                        </div>
                    </div>
                    <h3 class="service-title">Professional Content Writing and Translation Services</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.7</span>
                        <span class="rating-count">(145)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$49</div>
                    </div>
                </div>
            </a>

            <!-- Video & Animation -->
            <a href="#" class="service-card" data-category="video">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1536240478700-b869070f9279" class="service-image" alt="Video Editing" loading="lazy">
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/women/62.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Linda Chen</p>
                            <p class="provider-level">Rising Talent</p>
                        </div>
                    </div>
                    <h3 class="service-title">Professional Video Editing and Motion Graphics</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.6</span>
                        <span class="rating-count">(78)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$149</div>
                    </div>
                </div>
            </a>

            <!-- Data & Analytics -->
            <a href="#" class="service-card" data-category="data">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71" class="service-image" alt="Data Analysis" loading="lazy">
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/men/92.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">David Kumar</p>
                            <p class="provider-level">Level 2 Seller</p>
                        </div>
                    </div>
                    <h3 class="service-title">Data Analysis and Visualization Services</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.9</span>
                        <span class="rating-count">(167)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$199</div>
                    </div>
                </div>
            </a>

            <!-- Mobile Development -->
            <a href="#" class="service-card" data-category="mobile">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1522125670776-3c7422c47495" class="service-image" alt="Mobile App Development" loading="lazy">
                    <span class="featured-badge">Featured</span>
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/men/55.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Mike Zhang</p>
                            <p class="provider-level">Top Rated</p>
                        </div>
                    </div>
                    <h3 class="service-title">iOS and Android Mobile App Development</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.8</span>
                        <span class="rating-count">(203)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$499</div>
                    </div>
                </div>
            </a>

            <!-- Music & Audio -->
            <a href="#" class="service-card" data-category="music">
                <div class="service-image-container">
                    <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d" class="service-image" alt="Music Production" loading="lazy">
                </div>
                <div class="service-content">
                    <div class="service-provider">
                        <img src="https://randomuser.me/api/portraits/women/75.jpg" class="provider-avatar" alt="Provider">
                        <div class="provider-info">
                            <p class="provider-name">Sofia Rodriguez</p>
                            <p class="provider-level">Level 2 Seller</p>
                        </div>
                    </div>
                    <h3 class="service-title">Professional Music Production and Sound Design</h3>
                    <div class="service-rating">
                        <i class="bi bi-star-fill star-icon"></i>
                        <span>4.7</span>
                        <span class="rating-count">(89)</span>
                    </div>
                    <div class="service-footer">
                        <span class="price-label">Starting at</span>
                        <div class="service-price">$99</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <?php include $components['footer']; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterToggle = document.getElementById('filterToggle');
        const filtersPanel = document.getElementById('filtersPanel');
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const serviceCards = document.querySelectorAll('.service-card');
        const servicesGrid = document.querySelector('.services-grid');
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const categoryInputs = document.querySelectorAll('input[name="category"]');
        
        // Show/hide filters panel
        filterToggle?.addEventListener('click', () => {
            filtersPanel.style.display = filtersPanel.style.display === 'none' ? 'block' : 'none';
        });

        // Function to filter and sort services
        function filterAndSortServices() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = document.querySelector('input[name="category"]:checked')?.value || 'all';
            const minPrice = parseFloat(minPriceInput.value) || 0;
            const maxPrice = parseFloat(maxPriceInput.value) || Infinity;
            const sortValue = sortSelect.value;

            // Convert NodeList to Array for easier manipulation
            const cardsArray = Array.from(serviceCards);

            // Filter cards
            const filteredCards = cardsArray.filter(card => {
                const title = card.querySelector('.service-title').textContent.toLowerCase();
                const category = card.getAttribute('data-category');
                const price = parseFloat(card.querySelector('.service-price').textContent.replace('$', ''));
                
                const matchesSearch = title.includes(searchTerm);
                const matchesCategory = selectedCategory === 'all' || category === selectedCategory;
                const matchesPrice = price >= minPrice && price <= maxPrice;
                
                return matchesSearch && matchesCategory && matchesPrice;
            });

            // Sort filtered cards
            filteredCards.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.service-price').textContent.replace('$', ''));
                const priceB = parseFloat(b.querySelector('.service-price').textContent.replace('$', ''));
                const ratingA = parseFloat(a.querySelector('.service-rating span').textContent);
                const ratingB = parseFloat(b.querySelector('.service-rating span').textContent);
                
                switch(sortValue) {
                    case 'price_low': return priceA - priceB;
                    case 'price_high': return priceB - priceA;
                    case 'rating': return ratingB - ratingA;
                    default: return 0;
                }
            });

            // Update UI with filtered and sorted cards
            cardsArray.forEach(card => card.style.display = 'none');
            filteredCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.display = '';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    
                    requestAnimationFrame(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, index * 50);
            });

            // Show message if no results
            const noResultsMessage = document.querySelector('.no-results');
            if (filteredCards.length === 0) {
                if (!noResultsMessage) {
                    const message = document.createElement('div');
                    message.className = 'no-results text-center py-5';
                    message.innerHTML = `
                        <h3 class="mb-2">No services found</h3>
                        <p class="text-muted">Try adjusting your filters or search term</p>
                    `;
                    servicesGrid.appendChild(message);
                }
            } else {
                noResultsMessage?.remove();
            }
        }

        // Event listeners
        searchInput?.addEventListener('input', filterAndSortServices);
        sortSelect?.addEventListener('change', filterAndSortServices);
        minPriceInput?.addEventListener('input', filterAndSortServices);
        maxPriceInput?.addEventListener('input', filterAndSortServices);
        categoryInputs.forEach(input => {
            input.addEventListener('change', filterAndSortServices);
        });

        // Initialize IntersectionObserver for lazy loading
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        // Observe each service card
        serviceCards.forEach(card => observer.observe(card));
    });
    </script>
</html>