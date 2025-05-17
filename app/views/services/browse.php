<!-- Services Browse Page - Professional listing similar to Fiverr -->
<div class="services-browse-container">
    <!-- Page Header Section -->
    <div class="browse-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb shadcn-breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>">Home</a></li>
                            <?php if(isset($data['activeCategory']) && !empty($data['activeCategory'])): ?>
                                <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/services/browse">Services</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo isset($data['categories'][$data['activeCategory']]) ? $data['categories'][$data['activeCategory']] : ucfirst($data['activeCategory']); ?>
                                </li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page">Services</li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                    
                    <h1 class="browse-title">
                        <?php echo !empty($data['activeCategory']) ? 
                            (isset($data['categories'][$data['activeCategory']]) ? 
                                $data['categories'][$data['activeCategory']] : 
                                ucfirst($data['activeCategory'])) : 
                            'Services Marketplace'; ?>
                    </h1>
                    
                    <p class="browse-description">
                        <?php if(!empty($data['search'])): ?>
                            Search results for "<?php echo $data['search']; ?>"
                        <?php elseif(!empty($data['activeCategory'])): ?>
                            Find top <?php echo strtolower(isset($data['categories'][$data['activeCategory']]) ? 
                                $data['categories'][$data['activeCategory']] : 
                                $data['activeCategory']); ?> services from expert freelancers
                        <?php else: ?>
                            Browse thousands of services from freelancers around the world
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <form action="<?php echo URL_ROOT; ?>/services/browse" method="GET" class="search-form shadcn-search">
                        <?php if(!empty($data['activeCategory'])): ?>
                            <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                        <?php endif; ?>
                        
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search for services..." value="<?php echo $data['search']; ?>">
                            <button class="btn btn-search" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="browse-content-section">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 col-md-4">
                    <div class="filters-sidebar shadcn-card">
                        <div class="filter-section">
                            <h4 class="filter-title">Categories</h4>
                            <ul class="category-list">
                                <li class="<?php echo empty($data['activeCategory']) ? 'active' : ''; ?>">
                                    <a href="<?php echo URL_ROOT; ?>/services/browse<?php echo !empty($data['search']) ? '?search=' . $data['search'] : ''; ?>">All Categories</a>
                                </li>
                                <?php foreach($data['categories'] as $categorySlug => $categoryName): ?>
                                    <li class="<?php echo ($data['activeCategory'] === $categorySlug) ? 'active' : ''; ?>">
                                        <a href="<?php echo URL_ROOT; ?>/services/browse?category=<?php echo $categorySlug; ?><?php echo !empty($data['search']) ? '&search=' . $data['search'] : ''; ?>">
                                            <?php echo $categoryName; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Budget</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/browse" method="GET" class="price-filter-form">
                                <?php if(!empty($data['activeCategory'])): ?>
                                    <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                <?php endif; ?>
                                
                                <?php if(!empty($data['search'])): ?>
                                    <input type="hidden" name="search" value="<?php echo $data['search']; ?>">
                                <?php endif; ?>
                                
                                <?php if(!empty($data['sort'])): ?>
                                    <input type="hidden" name="sort" value="<?php echo $data['sort']; ?>">
                                <?php endif; ?>
                                
                                <?php if(!empty($data['rating'])): ?>
                                    <input type="hidden" name="rating" value="<?php echo $data['rating']; ?>">
                                <?php endif; ?>
                                
                                <div class="price-ranges">
                                    <div class="price-input-group">
                                        <label for="min_price">Min Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="min_price" id="min_price" value="<?php echo $data['minPrice']; ?>" min="0" max="999">
                                        </div>
                                    </div>
                                    
                                    <div class="price-input-group">
                                        <label for="max_price">Max Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="max_price" id="max_price" value="<?php echo $data['maxPrice']; ?>" min="1" max="1000">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 shadcn-button">Apply</button>
                            </form>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Seller Level</h4>
                            <div class="form-check shadcn-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="level1">
                                <label class="form-check-label" for="level1">
                                    Level 1
                                </label>
                            </div>
                            <div class="form-check shadcn-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="level2">
                                <label class="form-check-label" for="level2">
                                    Level 2
                                </label>
                            </div>
                            <div class="form-check shadcn-checkbox">
                                <input class="form-check-input" type="checkbox" value="" id="topRated">
                                <label class="form-check-label" for="topRated">
                                    Top Rated
                                </label>
                            </div>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Delivery Time</h4>
                            <div class="form-check shadcn-radio">
                                <input class="form-check-input" type="radio" name="deliveryTime" id="delivery1" value="1">
                                <label class="form-check-label" for="delivery1">
                                    Up to 1 day
                                </label>
                            </div>
                            <div class="form-check shadcn-radio">
                                <input class="form-check-input" type="radio" name="deliveryTime" id="delivery3" value="3">
                                <label class="form-check-label" for="delivery3">
                                    Up to 3 days
                                </label>
                            </div>
                            <div class="form-check shadcn-radio">
                                <input class="form-check-input" type="radio" name="deliveryTime" id="delivery7" value="7">
                                <label class="form-check-label" for="delivery7">
                                    Up to 7 days
                                </label>
                            </div>
                            <div class="form-check shadcn-radio">
                                <input class="form-check-input" type="radio" name="deliveryTime" id="deliveryAny" value="any" checked>
                                <label class="form-check-label" for="deliveryAny">
                                    Any
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                                                            <!-- Sort and View Controls -->                    <div class="browse-controls shadcn-card">                        <div class="row align-items-center">                            <div class="col-md-6">                                <?php if($data['expertMode']): ?>                                    <p class="results-count"><?php echo $data['totalExperts']; ?> experts available</p>                                <?php else: ?>                                    <p class="results-count"><?php echo $data['totalServices']; ?> services available</p>                                <?php endif; ?>                            </div>
                            <div class="col-md-6">
                                <div class="sort-controls">
                                    <form action="<?php echo URL_ROOT; ?>/services/browse" method="GET" id="sortForm">
                                        <?php if(!empty($data['activeCategory'])): ?>
                                            <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($data['search'])): ?>
                                            <input type="hidden" name="search" value="<?php echo $data['search']; ?>">
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($data['minPrice'])): ?>
                                            <input type="hidden" name="min_price" value="<?php echo $data['minPrice']; ?>">
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($data['maxPrice'])): ?>
                                            <input type="hidden" name="max_price" value="<?php echo $data['maxPrice']; ?>">
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($data['rating'])): ?>
                                            <input type="hidden" name="rating" value="<?php echo $data['rating']; ?>">
                                        <?php endif; ?>
                                        
                                        <label for="sort">Sort by:</label>
                                        <select name="sort" id="sort" class="form-select form-select-sm shadcn-select" onchange="document.getElementById('sortForm').submit()">
                                            <option value="popular" <?php echo ($data['sort'] === 'popular') ? 'selected' : ''; ?>>Recommended</option>
                                            <option value="rating" <?php echo ($data['sort'] === 'rating') ? 'selected' : ''; ?>>Best Rating</option>
                                            <option value="price_low" <?php echo ($data['sort'] === 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                                            <option value="price_high" <?php echo ($data['sort'] === 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services Grid -->
                    <div class="services-grid">
                        <div class="row">
                            <?php if(empty($data['services'])): ?>
                                <div class="col-12">
                                    <div class="no-results shadcn-card">
                                        <div class="no-results-icon">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <h3>No services found</h3>
                                        <p>Try adjusting your search or filter criteria</p>
                                        <a href="<?php echo URL_ROOT; ?>/services/browse" class="btn shadcn-button mt-3">Reset Filters</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach($data['services'] as $service): ?>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="service-card shadcn-service-card">
                                            <a href="<?php echo URL_ROOT; ?>/services/viewService/<?php echo $service['id']; ?>" class="service-image-wrapper">
                                                <?php if($service['featured']): ?>
                                                    <span class="featured-badge">Featured</span>
                                                <?php endif; ?>
                                                <div class="service-image" style="background-image: url('https://images.unsplash.com/photo-<?php echo rand(1500000000, 1600000000); ?>?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');"></div>
                                            </a>
                                            
                                            <div class="service-content">
                                                <div class="seller-info">
                                                    <a href="#" class="seller-avatar">
                                                        <img src="https://randomuser.me/api/portraits/<?php echo rand(0, 1) ? 'men' : 'women'; ?>/<?php echo $service['id'] % 100; ?>.jpg" alt="<?php echo $service['seller']['name']; ?>">
                                                    </a>
                                                    <div class="seller-details">
                                                        <a href="#" class="seller-name"><?php echo $service['seller']['name']; ?></a>
                                                        <span class="seller-level"><?php echo $service['seller']['level']; ?></span>
                                                    </div>
                                                </div>
                                                
                                                <a href="<?php echo URL_ROOT; ?>/services/viewService/<?php echo $service['id']; ?>" class="service-title">
                                                    <?php echo substr($service['title'], 0, 70); echo (strlen($service['title']) > 70) ? '...' : ''; ?>
                                                </a>
                                                
                                                <div class="service-rating">
                                                    <div class="stars">
                                                        <?php 
                                                        $fullStars = floor($service['rating']);
                                                        $halfStar = $service['rating'] - $fullStars >= 0.5;
                                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                                        
                                                        for ($i = 0; $i < $fullStars; $i++) {
                                                            echo '<i class="fas fa-star"></i>';
                                                        }
                                                        
                                                        if ($halfStar) {
                                                            echo '<i class="fas fa-star-half-alt"></i>';
                                                        }
                                                        
                                                        for ($i = 0; $i < $emptyStars; $i++) {
                                                            echo '<i class="far fa-star"></i>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <span class="rating-value"><?php echo $service['rating']; ?></span>
                                                    <span class="review-count">(<?php echo $service['reviewCount']; ?>)</span>
                                                </div>
                                                
                                                <div class="service-footer">
                                                    <button class="btn-favorite" aria-label="Add to favorites">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                    <div class="service-price">
                                                        <span class="price-label">Starting at</span>
                                                        <span class="price-value">$<?php echo $service['price']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="services-pagination">
                        <nav aria-label="Page navigation">
                            <ul class="pagination shadcn-pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Browse Page Styling -->
<style>
    /* Shadcn UI Variables from Landing */
    :root {
        /* Font variables */
        --font-primary: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
        --font-size-base-sm: 14px;
        --font-weight-base: 400;
        --font-weight-medium: 500;
        --font-weight-bold: 600;
        --line-height-base: 1.5;

        /* Primary color palette */
        --primary: #2c3e50;
        --primary-light: #34495e;
        --primary-dark: #1a252f;
        --primary-accent: #ecf0f1;

        /* Secondary color palette */
        --secondary: #222325;
        --secondary-light: #404145;
        --secondary-dark: #0e0e10;
        --secondary-accent: #f1f1f2;

        /* Accent colors */
        --accent-purple: #74767e;
        --accent-pink: #62646a;
        --accent-orange: #404145;

        /* Neutrals */
        --white: #ffffff;
        --text-dark: #222325;
        --gray-medium: #74767e;
        --gray-light: #e4e5e7;
        --gray-lighter: #fafafa;
        --gray-dark: #404145;

        /* UI elements */
        --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
        --shadow-glow: 0 0 15px rgba(29, 191, 115, 0.3);
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --transition-fast: 0.2s ease;
        --transition-default: 0.3s ease;
        --container-max-width: 1400px;
        --container-padding: 32px;

        /* RGB values for opacity manipulations */
        --primary-rgb: 44, 62, 80;
        --secondary-rgb: 34, 35, 37;
        --accent-rgb: 116, 118, 126;

        /* Shadcn UI specific variables for components */
        --shadcn-radius: 0.5rem;
        --shadcn-border: 220 13% 91%; /* hsl value for border */
        --shadcn-input: 220 13% 91%;
        --shadcn-ring: 224 71.4% 40%;
        --shadcn-background: 0 0% 100%;
        --shadcn-foreground: 224 71.4% 4.1%;
        --shadcn-primary: 220.9 39.3% 11%;
        --shadcn-primary-foreground: 210 20% 98%;
        --shadcn-secondary: 220 14.3% 95.9%;
        --shadcn-secondary-foreground: 220.9 39.3% 11%;
        --shadcn-muted: 220 14.3% 95.9%;
        --shadcn-muted-foreground: 220 8.9% 46.1%;
        --shadcn-accent: 220 14.3% 95.9%;
        --shadcn-accent-foreground: 220.9 39.3% 11%;
        --shadcn-card: 0 0% 100%;
        --shadcn-card-foreground: 224 71.4% 4.1%;
    }

    /* General Section Styles */
    .services-browse-container {
        font-family: var(--font-primary);
        padding-top: 20px;
        padding-bottom: 60px;
        background-color: hsl(var(--shadcn-background) / 0.97);
        color: hsl(var(--shadcn-foreground));
    }
    
    .browse-header-section {
        padding: 30px 0;
        background-color: white;
        border-bottom: 1px solid hsl(var(--shadcn-border));
        margin-bottom: 30px;
    }
    
    .browse-content-section {
        margin-bottom: 40px;
    }
    
    /* Typography */
    .browse-title {
        font-size: 32px;
        font-weight: var(--font-weight-bold);
        margin-bottom: 10px;
        color: var(--primary);
    }
    
    .browse-description {
        font-size: 16px;
        color: var(--gray-dark);
        margin-bottom: 20px;
    }
    
    /* Breadcrumb */
    .shadcn-breadcrumb {
        margin-bottom: 15px;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .shadcn-breadcrumb .breadcrumb-item {
        display: inline-flex;
        align-items: center;
        font-size: 14px;
        color: var(--gray-medium);
    }
    
    .shadcn-breadcrumb .breadcrumb-item a {
        color: var(--gray-dark);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .shadcn-breadcrumb .breadcrumb-item a:hover {
        color: var(--primary);
    }
    
    .shadcn-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: '/';
        padding: 0 8px;
        color: var(--gray-light);
    }
    
    .shadcn-breadcrumb .breadcrumb-item.active {
        color: var(--primary);
        font-weight: var(--font-weight-medium);
    }
    
    /* Search Form */
    .shadcn-search {
        max-width: 600px;
        margin-left: auto;
    }
    
    .shadcn-search .form-control {
        height: 48px;
        border-radius: var(--shadcn-radius) 0 0 var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        padding: 0 16px;
        font-size: 15px;
        background-color: hsl(var(--shadcn-background));
        color: hsl(var(--shadcn-foreground));
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }
    
    .shadcn-search .form-control:focus {
        border-color: hsl(var(--shadcn-ring));
        box-shadow: 0 0 0 2px hsl(var(--shadcn-ring) / 0.2);
        outline: none;
    }
    
    .shadcn-search .btn-search {
        border-radius: 0 var(--shadcn-radius) var(--shadcn-radius) 0;
        padding: 0 20px;
        background-color: var(--primary);
        border-color: var(--primary);
        color: #fff;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color var(--transition-fast);
    }
    
    .shadcn-search .btn-search:hover {
        background-color: var(--primary-light);
    }
    
    /* Cards & UI Components */
    .shadcn-card {
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
    }
    
    /* Filters Sidebar */
    .filters-sidebar {
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .filter-section {
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid hsl(var(--shadcn-border));
    }
    
    .filter-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .filter-title {
        font-size: 16px;
        font-weight: var(--font-weight-bold);
        margin-bottom: 15px;
        color: hsl(var(--shadcn-foreground));
    }
    
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .category-list li {
        margin-bottom: 4px;
    }
    
    .category-list li a {
        color: hsl(var(--shadcn-muted-foreground));
        text-decoration: none;
        font-size: 14px;
        transition: all var(--transition-fast);
        display: block;
        padding: 6px 10px;
        border-radius: var(--shadcn-radius);
    }
    
    .category-list li a:hover {
        color: hsl(var(--shadcn-foreground));
        background-color: hsl(var(--shadcn-accent) / 0.7);
        transform: translateX(3px);
    }
    
    .category-list li.active a {
        color: hsl(var(--shadcn-primary));
        font-weight: var(--font-weight-medium);
        background-color: hsl(var(--shadcn-accent));
    }
    
    /* Price Range Filter */
    .price-ranges {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .price-input-group {
        flex: 1;
    }
    
    .price-input-group label {
        display: block;
        font-size: 13px;
        margin-bottom: 5px;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .shadcn-input {
        border: 1px solid hsl(var(--shadcn-border));
        border-radius: var(--shadcn-radius);
        font-size: 14px;
        padding: 8px 12px;
        height: 38px;
        background-color: hsl(var(--shadcn-background));
        color: hsl(var(--shadcn-foreground));
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        width: 100%;
    }
    
    .shadcn-input:focus {
        border-color: hsl(var(--shadcn-ring));
        box-shadow: 0 0 0 2px hsl(var(--shadcn-ring) / 0.2);
        outline: none;
    }
    
    .input-group-text {
        border: 1px solid hsl(var(--shadcn-border));
        background-color: hsl(var(--shadcn-muted));
        color: hsl(var(--shadcn-muted-foreground));
        border-right: none;
        border-top-left-radius: var(--shadcn-radius);
        border-bottom-left-radius: var(--shadcn-radius);
        font-size: 14px;
        padding: 0 10px;
        display: flex;
        align-items: center;
    }
    
    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    /* Buttons */
    .shadcn-button {
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border: none;
        border-radius: var(--shadcn-radius);
        padding: 8px 16px;
        font-size: 14px;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: background-color var(--transition-fast), box-shadow var(--transition-fast), transform var(--transition-fast);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        line-height: 1.5;
    }
    
    .shadcn-button:hover {
        background-color: hsl(var(--shadcn-primary) / 0.9);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px hsl(var(--shadcn-primary) / 0.2);
    }
    
    .shadcn-button:active {
        transform: translateY(0);
    }
    
    .shadcn-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px hsl(var(--shadcn-ring) / 0.5);
    }
    
    /* Form Controls */
    .shadcn-checkbox, .shadcn-radio {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .shadcn-checkbox .form-check-input, 
    .shadcn-radio .form-check-input {
        margin-right: 10px;
        cursor: pointer;
        width: 16px;
        height: 16px;
        border: 1px solid hsl(var(--shadcn-border));
        background-color: hsl(var(--shadcn-background));
        transition: background-color var(--transition-fast), border-color var(--transition-fast);
    }
    
    .shadcn-checkbox .form-check-input:checked {
        background-color: hsl(var(--shadcn-primary));
        border-color: hsl(var(--shadcn-primary));
    }
    
    .shadcn-radio .form-check-input {
        border-radius: 50%;
    }
    
    .shadcn-radio .form-check-input:checked {
        background-color: hsl(var(--shadcn-primary));
        border-color: hsl(var(--shadcn-primary));
        background-size: 65%;
    }
    
    .shadcn-checkbox .form-check-label,
    .shadcn-radio .form-check-label {
        font-size: 14px;
        color: hsl(var(--shadcn-foreground));
        user-select: none;
        cursor: pointer;
    }
    
    /* Browse Controls */
    .browse-controls {
        margin-bottom: 20px;
    }
    
    .results-count {
        margin-bottom: 0;
        font-size: 14px;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .sort-controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .sort-controls label {
        font-size: 14px;
        margin-bottom: 0;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .shadcn-select {
        border: 1px solid hsl(var(--shadcn-border));
        border-radius: var(--shadcn-radius);
        font-size: 14px;
        padding: 6px 30px 6px 12px;
        height: 36px;
        background-color: hsl(var(--shadcn-background));
        color: hsl(var(--shadcn-foreground));
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 16px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    
    .shadcn-select:focus {
        border-color: hsl(var(--shadcn-ring));
        box-shadow: 0 0 0 2px hsl(var(--shadcn-ring) / 0.2);
        outline: none;
    }
    
    /* Service Cards */
    .services-grid {
        margin-bottom: 40px;
    }
    
    .shadcn-service-card {
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        overflow: hidden;
        height: 100%;
        transition: all var(--transition-default);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }
    
    .shadcn-service-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: hsl(var(--shadcn-ring) / 0.2);
    }
    
    .service-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px;
    }
    
    .featured-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        font-size: 12px;
        font-weight: var(--font-weight-medium);
        padding: 4px 12px;
        border-radius: 9999px;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .service-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
    }
    
    .shadcn-service-card:hover .service-image {
        transform: scale(1.05);
    }
    
    .service-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .seller-info {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .seller-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 12px;
        border: 1px solid hsl(var(--shadcn-border));
        display: block;
    }
    
    .seller-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .seller-details {
        display: flex;
        flex-direction: column;
    }
    
    .seller-name {
        font-size: 14px;
        font-weight: var(--font-weight-medium);
        color: hsl(var(--shadcn-foreground));
        text-decoration: none;
        line-height: 1.2;
    }
    
    .seller-name:hover {
        color: hsl(var(--shadcn-primary));
        text-decoration: none;
    }
    
    .seller-level {
        font-size: 12px;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .service-title {
        font-size: 16px;
        font-weight: var(--font-weight-medium);
        line-height: 1.4;
        margin-bottom: 12px;
        color: hsl(var(--shadcn-foreground));
        text-decoration: none;
        display: block;
        flex-grow: 1;
    }
    
    .service-title:hover {
        color: hsl(var(--shadcn-primary));
        text-decoration: none;
    }
    
    .service-rating {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }
    
    .stars {
        display: flex;
        margin-right: 8px;
    }
    
    .stars i {
        color: #FFB800;
        font-size: 14px;
        margin-right: 2px;
    }
    
    .stars i.far {
        color: #e4e5e7;
    }
    
    .rating-value {
        font-weight: var(--font-weight-medium);
        font-size: 14px;
        color: hsl(var(--shadcn-foreground));
    }
    
    .review-count {
        font-size: 14px;
        color: hsl(var(--shadcn-muted-foreground));
        margin-left: 4px;
    }
    
    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid hsl(var(--shadcn-border));
    }
    
    .btn-favorite {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        border: none;
        transition: all var(--transition-fast);
    }
    
    .btn-favorite:hover, .btn-favorite:focus {
        background-color: #FEE2E2;
        color: #EF4444;
        outline: none;
        transform: scale(1.1);
    }
    
    .btn-favorite.active {
        background-color: #FEE2E2;
        color: #EF4444;
    }
    
    .service-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .price-label {
        font-size: 12px;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .price-value {
        font-size: 18px;
        font-weight: var(--font-weight-bold);
        color: hsl(var(--shadcn-foreground));
        line-height: 1;
    }
    
    /* No Results Styling */
    .no-results {
        text-align: center;
        padding: 60px 30px;
    }
    
    .no-results-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        margin-bottom: 20px;
        font-size: 24px;
    }
    
    .no-results h3 {
        font-size: 24px;
        font-weight: var(--font-weight-bold);
        margin-bottom: 10px;
        color: hsl(var(--shadcn-foreground));
    }
    
    .no-results p {
        font-size: 16px;
        color: hsl(var(--shadcn-muted-foreground));
        margin-bottom: 20px;
    }
    
    /* Pagination */
    .shadcn-pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        margin: 30px 0;
        padding: 0;
    }
    
    .shadcn-pagination .page-item {
        margin: 0 4px;
    }
    
    .shadcn-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        border-radius: var(--shadcn-radius);
        font-size: 14px;
        font-weight: var(--font-weight-medium);
        background-color: hsl(var(--shadcn-background));
        color: hsl(var(--shadcn-foreground));
        border: 1px solid hsl(var(--shadcn-border));
        transition: all var(--transition-fast);
    }
    
    .shadcn-pagination .page-link:hover {
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        text-decoration: none;
        z-index: 1;
    }
    
    .shadcn-pagination .page-item.active .page-link {
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border-color: hsl(var(--shadcn-primary));
        z-index: 2;
    }
    
    .shadcn-pagination .page-item.disabled .page-link {
        background-color: hsl(var(--shadcn-background));
        color: hsl(var(--shadcn-muted-foreground) / 0.6);
        pointer-events: none;
    }
    
    /* Media queries for better responsiveness */
    @media (max-width: 991px) {
        .browse-title {
            font-size: 28px;
        }
        
        .browse-description {
            font-size: 15px;
        }
    }
    
    @media (max-width: 767px) {
        .browse-header-section {
            padding: 20px 0;
        }
        
        .browse-title {
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .browse-description {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .shadcn-search .form-control {
            height: 44px;
        }
        
        .shadcn-search .btn-search {
            height: 44px;
        }
        
        .service-image-wrapper {
            height: 180px;
        }
        
        .service-content {
            padding: 15px;
        }
        
        .price-value {
            font-size: 16px;
        }
    }
    
    @media (max-width: 575px) {
        .browse-title {
            font-size: 22px;
        }
        
        .service-image-wrapper {
            height: 160px;
        }
        
        .service-title {
            font-size: 15px;
        }
        
        .shadcn-pagination .page-link {
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            font-size: 13px;
        }
    }
</style>

<!-- JavaScript for enhanced interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle favorite button clicks with modern animation
        const favoriteButtons = document.querySelectorAll('.btn-favorite');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                
                // Add a small "pop" animation
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('active');
                    this.style.backgroundColor = '#FEE2E2';
                    this.style.color = '#EF4444';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('active');
                    this.style.backgroundColor = '';
                    this.style.color = '';
                }
            });
        });
        
        // Enhance hover effects for service cards if GSAP is available
        if (typeof gsap !== 'undefined') {
            const serviceCards = document.querySelectorAll('.shadcn-service-card');
            
            serviceCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        y: -5,
                        boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                    
                    const image = card.querySelector('.service-image');
                    gsap.to(image, {
                        scale: 1.05,
                        duration: 0.5,
                        ease: 'power1.out'
                    });
                });
                
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        y: 0,
                        boxShadow: '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                    
                    const image = card.querySelector('.service-image');
                    gsap.to(image, {
                        scale: 1,
                        duration: 0.5,
                        ease: 'power1.out'
                    });
                });
            });
        }
        
        // Initialize tooltips if Bootstrap is available
        if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        // Add lazy loading to images if Intersection Observer is supported
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        if (src) {
                            img.src = src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    });
</script> 