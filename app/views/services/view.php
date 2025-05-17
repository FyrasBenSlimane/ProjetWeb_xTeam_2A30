<!-- Single Service View Page - Shadcn UI-inspired design -->
<div class="service-view-container">
    <!-- Service Header Area -->
    <div class="service-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb shadcn-breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/services/browse">Services</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/services/browse?category=<?php echo $data['service']['category']; ?>"><?php echo $data['service']['categoryName']; ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service Details</li>
                </ol>
            </nav>
            
            <h1 class="service-title"><?php echo $data['service']['title']; ?></h1>
        </div>
    </div>
    
    <!-- Service Main Content -->
    <div class="service-content-area">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- Service Gallery -->
                    <div class="service-gallery shadcn-card">
                        <div class="main-image">
                            <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="<?php echo $data['service']['title']; ?>" class="img-fluid main-gallery-image">
                            <img src="" alt="" class="img-fluid img-transition">
                        </div>
                        <div class="thumbnails">
                            <?php 
                            // Array of known working Unsplash photo IDs for tech/business/professional services
                            $unsplashIds = [
                                'photo-1531297484001-80022131f5a1',
                                'photo-1498050108023-c5249f4df085',
                                'photo-1504868584819-f8e8b4b6d7e3',
                                'photo-1517245386807-bb43f82c33c4',
                                'photo-1517694712202-14dd9538aa97',
                                'photo-1573495612937-f02b92423487',
                                'photo-1555066931-4365d14bab8c',
                                'photo-1496171367470-9ed9a91ea931'
                            ];
                            
                            // Shuffle array to get different images each time
                            shuffle($unsplashIds);
                            
                            // Use first ID for main image
                            $mainImageId = $unsplashIds[0];
                            
                            // Use next 4 for thumbnails
                            for($i = 0; $i < 4; $i++): 
                                $thumbId = $unsplashIds[$i + 1 > count($unsplashIds) - 1 ? 0 : $i + 1];
                                $isActive = ($i === 0);
                            ?>
                                <div class="thumbnail <?php echo $isActive ? 'active' : ''; ?>" data-src="https://images.unsplash.com/<?php echo $thumbId; ?>?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80">
                                    <img src="https://images.unsplash.com/<?php echo $thumbId; ?>?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Thumbnail <?php echo $i + 1; ?>">
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- About This Service -->
                    <div class="service-description-box shadcn-card">
                        <h2>About This Service</h2>
                        <div class="service-description">
                            <p><?php echo $data['service']['description']; ?></p>
                            
                            <div class="service-highlights">
                                <h3>Why choose this service?</h3>
                                <ul class="service-benefits-list">
                                    <li>
                                        <span class="benefit-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="benefit-text">Professional and high-quality results</span>
                                    </li>
                                    <li>
                                        <span class="benefit-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="benefit-text">Quick turnaround time - delivered in <?php echo $data['service']['deliveryTime']; ?> days</span>
                                    </li>
                                    <li>
                                        <span class="benefit-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="benefit-text"><?php echo $data['service']['revisions']; ?> revision rounds included</span>
                                    </li>
                                    <li>
                                        <span class="benefit-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="benefit-text">Experienced seller with proven track record</span>
                                    </li>
                                    <li>
                                        <span class="benefit-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <span class="benefit-text">Dedicated customer support throughout the process</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- About The Seller -->
                    <div class="seller-profile-box shadcn-card">
                        <h2>About The Seller</h2>
                        <div class="seller-profile">
                            <div class="seller-header">
                                <div class="seller-avatar">
                                    <img src="<?php echo URL_ROOT; ?>/<?php echo $data['service']['seller']['avatar']; ?>" alt="<?php echo $data['service']['seller']['name']; ?>">
                                </div>
                                <div class="seller-info">
                                    <h3 class="seller-name"><?php echo $data['service']['seller']['name']; ?></h3>
                                    <p class="seller-title">Professional <?php echo $data['service']['categoryName']; ?> Expert</p>
                                    <div class="seller-rating">
                                        <div class="stars">
                                            <?php 
                                            $fullStars = floor($data['service']['rating']);
                                            $halfStar = $data['service']['rating'] - $fullStars >= 0.5;
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
                                        <span><?php echo $data['service']['rating']; ?></span>
                                        <span class="rating-count">(<?php echo $data['service']['reviewCount']; ?>)</span>
                                    </div>
                                    <button class="btn shadcn-button-sm">Contact Me</button>
                                </div>
                            </div>
                            
                            <div class="seller-stats">
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $data['service']['seller']['level']; ?></div>
                                    <div class="stat-label">Seller Level</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo $data['service']['seller']['responseTime']; ?></div>
                                    <div class="stat-label">Response Time</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value"><?php echo rand(90, 99); ?>%</div>
                                    <div class="stat-label">Order Completion</div>
                                </div>
                            </div>
                            
                            <div class="seller-description">
                                <p>I'm a passionate <?php echo $data['service']['categoryName']; ?> professional with over <?php echo rand(3, 10); ?> years of experience. I've worked with clients from around the world to deliver high-quality solutions that exceed expectations.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews Section -->
                    <div class="reviews-box shadcn-card">
                        <div class="reviews-header">
                            <h2>Reviews</h2>
                            <div class="review-summary">
                                <div class="rating-average">
                                    <span class="average"><?php echo $data['service']['rating']; ?></span>
                                    <div class="stars">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= floor($data['service']['rating'])): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif($i - 0.5 <= $data['service']['rating']): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="count"><?php echo $data['service']['reviewCount']; ?> reviews</span>
                                </div>
                                
                                <div class="rating-breakdown">
                                    <div class="breakdown-item">
                                        <span class="breakdown-label">5 Stars</span>
                                        <div class="progress shadcn-progress">
                                            <div class="progress-bar" style="width: <?php echo rand(70, 90); ?>%"></div>
                                        </div>
                                        <span class="breakdown-percent"><?php echo rand(70, 90); ?>%</span>
                                    </div>
                                    <div class="breakdown-item">
                                        <span class="breakdown-label">4 Stars</span>
                                        <div class="progress shadcn-progress">
                                            <div class="progress-bar" style="width: <?php echo rand(10, 25); ?>%"></div>
                                        </div>
                                        <span class="breakdown-percent"><?php echo rand(10, 25); ?>%</span>
                                    </div>
                                    <div class="breakdown-item">
                                        <span class="breakdown-label">3 Stars</span>
                                        <div class="progress shadcn-progress">
                                            <div class="progress-bar" style="width: <?php echo rand(1, 5); ?>%"></div>
                                        </div>
                                        <span class="breakdown-percent"><?php echo rand(1, 5); ?>%</span>
                                    </div>
                                    <div class="breakdown-item">
                                        <span class="breakdown-label">2 Stars</span>
                                        <div class="progress shadcn-progress">
                                            <div class="progress-bar" style="width: <?php echo rand(0, 2); ?>%"></div>
                                        </div>
                                        <span class="breakdown-percent"><?php echo rand(0, 2); ?>%</span>
                                    </div>
                                    <div class="breakdown-item">
                                        <span class="breakdown-label">1 Star</span>
                                        <div class="progress shadcn-progress">
                                            <div class="progress-bar" style="width: <?php echo rand(0, 1); ?>%"></div>
                                        </div>
                                        <span class="breakdown-percent"><?php echo rand(0, 1); ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sample Reviews -->
                        <div class="reviews-list">
                            <?php 
                            $reviewerNames = ['Michael Johnson', 'Sarah Williams', 'David Lee', 'Jessica Brown', 'Robert Smith'];
                            $reviewContents = [
                                "Excellent service! The work was delivered on time and exceeded my expectations. Very professional and easy to work with.",
                                "Great communication throughout the project. The quality of work was outstanding and I'll definitely be using this service again.",
                                "Prompt, professional, and produced high-quality work. Highly recommended!",
                                "I'm very satisfied with the results. The seller was responsive and made all the requested changes quickly.",
                                "Incredible work and attention to detail. The process was smooth and the end product is exactly what I needed."
                            ];
                            
                            // Show 3 sample reviews
                            for($i = 0; $i < 3; $i++): 
                                $reviewerIndex = rand(0, count($reviewerNames) - 1);
                                $reviewContentIndex = rand(0, count($reviewContents) - 1);
                                $daysAgo = rand(1, 30);
                                $rating = rand(4, 5);
                            ?>
                                <div class="review-item">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            <img src="<?php echo URL_ROOT; ?>/public/img/avatars/user<?php echo rand(1, 10); ?>.jpg" alt="<?php echo $reviewerNames[$reviewerIndex]; ?>">
                                        </div>
                                        <div class="reviewer-details">
                                            <h4><?php echo $reviewerNames[$reviewerIndex]; ?></h4>
                                            <div class="reviewer-rating">
                                                <?php for($j = 1; $j <= 5; $j++): ?>
                                                    <i class="fas fa-star <?php echo ($j <= $rating) ? 'active' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <div class="review-date"><?php echo $daysAgo; ?> days ago</div>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p><?php echo $reviewContents[$reviewContentIndex]; ?></p>
                                    </div>
                                </div>
                            <?php endfor; ?>
                            
                            <div class="reviews-footer">
                                <a href="#" class="btn shadcn-button-outline">View All Reviews</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="service-pricing-box shadcn-card">
                        <div class="pricing-header">
                            <h3>Service Package</h3>
                        </div>
                        
                        <div class="pricing-content">
                            <div class="package-price">
                                <span class="currency">$</span>
                                <span class="amount"><?php echo $data['service']['price']; ?></span>
                            </div>
                            
                            <p class="package-description">
                                <?php echo substr($data['service']['description'], 0, 120); ?>...
                            </p>
                            
                            <div class="package-details">
                                <div class="detail-item">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo $data['service']['deliveryTime']; ?> Day Delivery</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-redo"></i>
                                    <span><?php echo $data['service']['revisions']; ?> Revisions</span>
                                </div>
                                <?php 
                                $features = [
                                    'Source Files', 
                                    'Commercial Use', 
                                    'Professional Quality', 
                                    'Responsive Design',
                                    'Delivery Options',
                                    'Project Consultation'
                                ];
                                $includedCount = rand(3, 6);
                                for($i = 0; $i < $includedCount; $i++): 
                                ?>
                                    <div class="detail-item">
                                        <i class="fas fa-check"></i>
                                        <span><?php echo $features[$i]; ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            
                            <div class="package-actions">
                                <button class="btn btn-primary btn-block shadcn-button-primary contact-seller-btn">Contact Seller</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="service-tags-box shadcn-card">
                        <h3>Tags</h3>
                        <div class="tags-list">
                            <?php foreach($data['service']['tags'] as $tag): ?>
                                <a href="<?php echo URL_ROOT; ?>/services/browse?search=<?php echo urlencode($tag); ?>" class="tag-item"><?php echo $tag; ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Services -->
    <div class="related-services-section">
        <div class="container">
            <h2>You May Also Like</h2>
            <div class="row">
                <?php foreach($data['relatedServices'] as $relatedService): ?>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="service-card shadcn-service-card">
                            <a href="<?php echo URL_ROOT; ?>/services/viewService/<?php echo $relatedService['id']; ?>" class="service-image-wrapper">
                                <?php if($relatedService['featured']): ?>
                                    <span class="featured-badge">Featured</span>
                                <?php endif; ?>
                                <div class="service-image" style="background-image: url('<?php echo URL_ROOT; ?>/public/img/services/<?php echo $relatedService['category']; ?>/<?php echo $relatedService['id'] % 7 + 1; ?>.jpg');"></div>
                            </a>
                            
                            <div class="service-content">
                                <div class="seller-info">
                                    <a href="#" class="seller-avatar">
                                        <img src="<?php echo URL_ROOT; ?>/<?php echo $relatedService['seller']['avatar']; ?>" alt="<?php echo $relatedService['seller']['name']; ?>">
                                    </a>
                                    <div class="seller-details">
                                        <a href="#" class="seller-name"><?php echo $relatedService['seller']['name']; ?></a>
                                        <span class="seller-level"><?php echo $relatedService['seller']['level']; ?></span>
                                    </div>
                                </div>
                                
                                <a href="<?php echo URL_ROOT; ?>/services/viewService/<?php echo $relatedService['id']; ?>" class="service-title">
                                    <?php echo substr($relatedService['title'], 0, 60); echo (strlen($relatedService['title']) > 60) ? '...' : ''; ?>
                                </a>
                                
                                <div class="service-rating">
                                    <div class="stars">
                                        <?php 
                                        $fullStars = floor($relatedService['rating']);
                                        $halfStar = $relatedService['rating'] - $fullStars >= 0.5;
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
                                    <span class="rating-value"><?php echo $relatedService['rating']; ?></span>
                                    <span class="review-count">(<?php echo $relatedService['reviewCount']; ?>)</span>
                                </div>
                                
                                <div class="service-footer">
                                    <button class="btn-favorite" aria-label="Add to favorites">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <div class="service-price">
                                        <span class="price-label">Starting at</span>
                                        <span class="price-value">$<?php echo $relatedService['price']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Shadcn UI-inspired Styles -->
<style>
    /* Reset and Base Styles */
    :root {
        --shadcn-background: 0 0% 100%;
        --shadcn-foreground: 222.2 84% 4.9%;
        --shadcn-card: 0 0% 100%;
        --shadcn-card-foreground: 222.2 84% 4.9%;
        --shadcn-primary: 222.2 47.4% 11.2%;
        --shadcn-primary-foreground: 210 40% 98%;
        --shadcn-secondary: 210 40% 96.1%;
        --shadcn-secondary-foreground: 222.2 47.4% 11.2%;
        --shadcn-muted: 210 40% 96.1%;
        --shadcn-muted-foreground: 215.4 16.3% 46.9%;
        --shadcn-accent: 210 40% 96.1%;
        --shadcn-accent-foreground: 222.2 47.4% 11.2%;
        --shadcn-border: 214.3 31.8% 91.4%;
        --shadcn-input: 214.3 31.8% 91.4%;
        --shadcn-ring: 222.2 84% 4.9%;
        --shadcn-radius: 0.5rem;
        
        --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    /* Modern card styling */
    .shadcn-card {
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        transition: box-shadow 0.3s ease;
    }
    
    .shadcn-card:hover {
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    
    /* General page styling */
    .service-view-container {
        padding-bottom: 3rem;
        background-color: hsl(var(--shadcn-background) / 0.5);
    }
    
    /* Header Section */
    .service-header {
        padding: 2rem 0;
        background-color: hsl(var(--shadcn-background));
        border-bottom: 1px solid hsl(var(--shadcn-border));
        margin-bottom: 2rem;
    }
    
    .service-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: hsl(var(--shadcn-foreground));
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        letter-spacing: -0.025em;
        text-wrap: balance;
        position: relative;
        transition: color 0.3s ease;
    }
    
    .service-title:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: hsl(var(--shadcn-primary));
        border-radius: 3px;
    }
    
    /* Breadcrumbs */
    .shadcn-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: hsl(var(--shadcn-muted-foreground));
        margin-bottom: 1rem;
    }
    
    .shadcn-breadcrumb .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    
    .shadcn-breadcrumb .breadcrumb-item + .breadcrumb-item:before {
        content: "/";
        margin-right: 0.5rem;
        opacity: 0.5;
    }
    
    .shadcn-breadcrumb a {
        color: hsl(var(--shadcn-foreground));
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .shadcn-breadcrumb a:hover {
        color: hsl(var(--shadcn-primary));
    }
    
    /* Gallery Section - Enhanced */
    .service-gallery.shadcn-card {
        margin-bottom: 2rem;
        overflow: hidden;
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        padding: 0;
        background-color: hsl(var(--shadcn-background));
        position: relative;
    }
    
    .service-gallery.shadcn-card:hover {
        box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        border-radius: var(--shadcn-radius) var(--shadcn-radius) 0 0;
        overflow: hidden;
        position: relative;
    }
    
    .main-image::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 85%, rgba(0, 0, 0, 0.03) 100%);
        z-index: 1;
        pointer-events: none;
    }
    
    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.7s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform, opacity;
    }
    
    .main-image .img-transition {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        z-index: 0;
    }
    
    .service-gallery:hover .main-image img {
        transform: scale(1.03);
    }
    
    .thumbnails {
        display: flex;
        gap: 0.5rem;
        padding: 1.25rem;
        background-color: hsl(var(--shadcn-background));
        border-top: 1px solid hsl(var(--shadcn-border) / 0.7);
        position: relative;
        z-index: 2;
    }
    
    .thumbnail {
        flex: 1;
        max-width: 100px;
        height: 72px;
        border-radius: 0.25rem;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.8;
        border: 2px solid transparent;
        transition: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
    }
    
    .thumbnail::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 0.25rem;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.2);
        pointer-events: none;
        transition: box-shadow 0.25s ease;
    }
    
    .thumbnail.active {
        opacity: 1;
        border-color: hsl(var(--shadcn-primary));
        transform: translateY(-2px);
        box-shadow: 0 4px 8px -2px rgba(0, 0, 0, 0.12);
    }
    
    .thumbnail:hover:not(.active) {
        opacity: 0.9;
        border-color: hsl(var(--shadcn-border));
        transform: translateY(-1px);
        box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0.15rem;
        transition: transform 0.35s ease;
    }
    
    .thumbnail:hover img {
        transform: scale(1.08);
    }
    
    .thumbnail.active img {
        transform: scale(1.05);
    }
    
    /* Service Description Box */
    .service-description-box {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .service-description-box h2,
    .service-pricing-box h3,
    .service-tags-box h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: hsl(var(--shadcn-foreground));
    }
    
    .service-description p {
        color: hsl(var(--shadcn-foreground) / 0.8);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    
    .service-highlights h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: hsl(var(--shadcn-foreground));
    }
    
    .service-benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .service-benefits-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        color: hsl(var(--shadcn-foreground) / 0.8);
    }
    
    .benefit-icon {
        margin-right: 0.75rem;
        color: hsl(var(--shadcn-primary));
        font-size: 1.125rem;
        flex-shrink: 0;
    }
    
    .benefit-text {
        flex-grow: 1;
        line-height: 1.5;
    }
    
    /* Pricing Box */
    .service-pricing-box {
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .pricing-header {
        padding: 1.25rem;
        border-bottom: 1px solid hsl(var(--shadcn-border));
        background-color: hsl(var(--shadcn-background));
    }
    
    .pricing-header h3 {
        margin-bottom: 0;
    }
    
    .pricing-content {
        padding: 1.5rem;
    }
    
    .package-price {
        margin-bottom: 1rem;
        display: flex;
        align-items: baseline;
    }
    
    .currency {
        font-size: 1.25rem;
        font-weight: 600;
        margin-right: 0.25rem;
        color: hsl(var(--shadcn-foreground));
    }
    
    .amount {
        font-size: 2rem;
        font-weight: 700;
        color: hsl(var(--shadcn-foreground));
        line-height: 1;
    }
    
    .package-description {
        color: hsl(var(--shadcn-foreground) / 0.8);
        margin-bottom: 1.5rem;
        line-height: 1.6;
        font-size: 0.9375rem;
    }
    
    .package-details {
        margin-bottom: 1.5rem;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.9375rem;
    }
    
    .detail-item i {
        width: 1.5rem;
        margin-right: 0.75rem;
        color: hsl(var(--shadcn-primary));
    }
    
    .detail-item span {
        color: hsl(var(--shadcn-foreground) / 0.8);
    }
    
    .package-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    /* Button styling */
    .shadcn-button-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--shadcn-radius);
        font-weight: 500;
        font-size: 0.9375rem;
        line-height: 1.5;
        transition: all 0.2s ease;
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border: 1px solid hsl(var(--shadcn-primary));
        padding: 0.75rem 1.5rem;
        height: auto;
        width: 100%;
    }
    
    .shadcn-button-primary:hover {
        background-color: hsl(var(--shadcn-primary) / 0.9);
        color: hsl(var(--shadcn-primary-foreground));
    }
    
    .shadcn-button-primary:focus {
        outline: none;
        box-shadow: 0 0 0 2px hsl(var(--shadcn-background)), 0 0 0 4px hsl(var(--shadcn-primary) / 0.3);
    }
    
    .shadcn-button-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--shadcn-radius);
        font-weight: 500;
        font-size: 0.9375rem;
        line-height: 1.5;
        transition: all 0.2s ease;
        background-color: transparent;
        color: hsl(var(--shadcn-primary));
        border: 1px solid hsl(var(--shadcn-border));
        padding: 0.75rem 1.5rem;
        height: auto;
        width: 100%;
    }
    
    .shadcn-button-outline:hover {
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-primary));
    }
    
    .shadcn-button-outline:focus {
        outline: none;
        box-shadow: 0 0 0 2px hsl(var(--shadcn-background)), 0 0 0 4px hsl(var(--shadcn-border));
    }
    
    /* Tags styling */
    .service-tags-box {
        padding: 1.5rem;
    }
    
    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .tag-item {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .tag-item:hover {
        background-color: hsl(var(--shadcn-accent));
        color: hsl(var(--shadcn-accent-foreground));
    }
    
    /* Related Services */
    .related-services-section {
        background-color: hsl(var(--shadcn-background));
        padding: 3rem 0;
        border-top: 1px solid hsl(var(--shadcn-border));
    }
    
    .related-services-section h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: hsl(var(--shadcn-foreground));
    }
    
    /* Service Cards (same as in browse.php) */
    .shadcn-service-card {
        border-radius: var(--shadcn-radius);
        background-color: hsl(var(--shadcn-card));
        border: 1px solid hsl(var(--shadcn-border));
        overflow: hidden;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    }
    
    .shadcn-service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    
    .service-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: var(--shadcn-radius) var(--shadcn-radius) 0 0;
    }
    
    .featured-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        z-index: 10;
    }
    
    .service-image {
        height: 180px;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
    }
    
    .shadcn-service-card:hover .service-image {
        transform: scale(1.05);
    }
    
    .service-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .seller-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .seller-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
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
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        text-decoration: none;
    }
    
    .seller-level {
        font-size: 12px;
        color: var(--gray-medium);
    }
    
    .service-title {
        font-size: 15px;
        font-weight: 500;
        line-height: 1.4;
        margin-bottom: 10px;
        color: var(--text-dark);
        text-decoration: none;
        display: block;
        flex-grow: 1;
    }
    
    .service-title:hover {
        color: var(--primary);
    }
    
    .service-rating {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .service-rating i {
        color: #ffb33e;
        margin-right: 5px;
        font-size: 14px;
    }
    
    .rating-value {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
    }
    
    .review-count {
        font-size: 13px;
        color: var(--gray-medium);
        margin-left: 5px;
    }
    
    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-favorite {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #f5f5f5;
        color: var(--gray-dark);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-favorite:hover {
        background-color: #ffe9e9;
        color: #ff4b4b;
    }
    
    .service-price {
        font-size: 13px;
        color: var(--gray-medium);
    }
    
    .price-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .service-title {
            font-size: 24px;
        }
        
        .service-pricing-box {
            position: static;
            margin-top: 30px;
        }
    }
    
    /* About The Seller - Improved */
    .seller-profile-box.shadcn-card {
        padding: 2rem;
        margin-bottom: 2rem;
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }
    
    .seller-profile-box.shadcn-card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }
    
    .seller-profile-box h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.75rem;
        color: hsl(var(--shadcn-foreground));
        position: relative;
        letter-spacing: -0.01em;
    }
    
    .seller-profile-box h2:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 40px;
        height: 3px;
        background-color: hsl(var(--shadcn-primary));
        border-radius: 2px;
    }
    
    .seller-header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.75rem;
        gap: 1.5rem;
    }
    
    .seller-avatar {
        width: 90px;
        height: 90px;
        flex-shrink: 0;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid hsl(var(--shadcn-background));
        box-shadow: 0 0 0 2px hsl(var(--shadcn-border)), 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }
    
    .seller-avatar:hover {
        transform: scale(1.04);
        box-shadow: 0 0 0 2px hsl(var(--shadcn-primary) / 0.4), 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .seller-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .seller-avatar:hover img {
        transform: scale(1.08);
    }
    
    .seller-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .seller-name {
        font-size: 1.35rem;
        font-weight: 600;
        margin-bottom: 0.35rem;
        color: hsl(var(--shadcn-foreground));
        letter-spacing: -0.01em;
        line-height: 1.2;
    }
    
    .seller-title {
        color: hsl(var(--shadcn-muted-foreground));
        font-size: 0.95rem;
        margin-bottom: 0.85rem;
        font-weight: 500;
    }
    
    .seller-rating {
        display: flex;
        align-items: center;
        margin-bottom: 1.25rem;
        gap: 0.5rem;
    }
    
    .seller-rating .stars {
        display: flex;
        align-items: center;
        color: #ffb33e;
    }
    
    .seller-rating .stars i {
        margin-right: 0.2rem;
        font-size: 1rem;
    }
    
    .seller-rating span {
        font-weight: 600;
        color: hsl(var(--shadcn-foreground));
        font-size: 0.95rem;
    }
    
    .seller-rating .rating-count {
        color: hsl(var(--shadcn-muted-foreground));
        font-weight: normal;
        font-size: 0.9rem;
    }
    
    .shadcn-button-sm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.5rem;
        padding: 0 1.25rem;
        border-radius: var(--shadcn-radius);
        font-size: 0.9rem;
        font-weight: 500;
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border: 1px solid hsl(var(--shadcn-primary));
        transition: all 0.2s ease;
        letter-spacing: 0.01em;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        max-width: fit-content;
    }
    
    .shadcn-button-sm:hover {
        background-color: hsl(var(--shadcn-primary) / 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .shadcn-button-sm:active {
        transform: translateY(0);
    }
    
    .seller-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        background-color: hsl(var(--shadcn-muted) / 0.5);
        border-radius: var(--shadcn-radius);
        padding: 1.5rem;
        margin-bottom: 1.75rem;
        border: 1px solid hsl(var(--shadcn-border));
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0.75rem;
        border-radius: var(--shadcn-radius);
        transition: background-color 0.2s ease;
    }
    
    .stat-item:hover {
        background-color: hsl(var(--shadcn-muted));
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: hsl(var(--shadcn-foreground));
        margin-bottom: 0.35rem;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: hsl(var(--shadcn-muted-foreground));
        font-weight: 500;
    }
    
    .seller-description {
        color: hsl(var(--shadcn-foreground) / 0.8);
        line-height: 1.65;
        font-size: 0.95rem;
        background-color: hsl(var(--shadcn-muted) / 0.2);
        padding: 1.25rem;
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border) / 0.7);
    }
    
    /* Reviews Section - Improved */
    .reviews-box.shadcn-card {
        padding: 2rem;
        margin-bottom: 2rem;
        background-color: hsl(var(--shadcn-card));
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }
    
    .reviews-box.shadcn-card:hover {
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }
    
    .reviews-header {
        margin-bottom: 2rem;
    }
    
    .reviews-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.75rem;
        color: hsl(var(--shadcn-foreground));
        position: relative;
        letter-spacing: -0.01em;
    }
    
    .reviews-header h2:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 40px;
        height: 3px;
        background-color: hsl(var(--shadcn-primary));
        border-radius: 2px;
    }
    
    .review-summary {
        display: flex;
        align-items: stretch;
        gap: 2rem;
        padding: 1.75rem;
        background-color: hsl(var(--shadcn-muted) / 0.5);
        border-radius: var(--shadcn-radius);
        border: 1px solid hsl(var(--shadcn-border));
    }
    
    .rating-average {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
        padding-right: 2rem;
        border-right: 1px solid hsl(var(--shadcn-border));
        justify-content: center;
        min-width: 120px;
    }
    
    .rating-average .average {
        font-size: 3rem;
        font-weight: 700;
        color: hsl(var(--shadcn-foreground));
        line-height: 1;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    
    .rating-average .stars {
        margin-bottom: 0.75rem;
        color: #ffb33e;
        display: flex;
    }
    
    .rating-average .stars i {
        margin-right: 0.2rem;
        font-size: 1.1rem;
    }
    
    .rating-average .count {
        font-size: 0.9rem;
        color: hsl(var(--shadcn-muted-foreground));
        text-align: center;
    }
    
    .rating-breakdown {
        flex-grow: 1;
    }
    
    .breakdown-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.9rem;
    }
    
    .breakdown-item:last-child {
        margin-bottom: 0;
    }
    
    .breakdown-label {
        width: 4.25rem;
        font-size: 0.9rem;
        color: hsl(var(--shadcn-foreground));
        font-weight: 500;
    }
    
    .shadcn-progress {
        flex-grow: 1;
        height: 0.5rem;
        margin: 0 1rem;
        background-color: hsl(var(--shadcn-border));
        border-radius: 9999px;
        overflow: hidden;
    }
    
    .shadcn-progress .progress-bar {
        height: 100%;
        background-color: hsl(var(--shadcn-primary));
        border-radius: 9999px;
    }
    
    .breakdown-percent {
        width: 2.5rem;
        font-size: 0.9rem;
        color: hsl(var(--shadcn-foreground));
        text-align: right;
        font-weight: 500;
    }
    
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .review-item {
        padding: 1.5rem;
        border-radius: var(--shadcn-radius);
        background-color: hsl(var(--shadcn-muted) / 0.3);
        transition: transform 0.3s ease, background-color 0.3s ease;
        border: 1px solid hsl(var(--shadcn-border) / 0.6);
    }
    
    .review-item:hover {
        background-color: hsl(var(--shadcn-muted) / 0.5);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        border: 2px solid hsl(var(--shadcn-background));
        box-shadow: 0 0 0 1px hsl(var(--shadcn-border)), 0 3px 8px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease;
    }
    
    .reviewer-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 0 0 1px hsl(var(--shadcn-primary) / 0.3), 0 5px 12px rgba(0, 0, 0, 0.09);
    }
    
    .reviewer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .reviewer-avatar:hover img {
        transform: scale(1.08);
    }
    
    .reviewer-details {
        flex-grow: 1;
    }
    
    .reviewer-details h4 {
        font-size: 1.05rem;
        font-weight: 600;
        color: hsl(var(--shadcn-foreground));
        margin-bottom: 0.35rem;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }
    
    .reviewer-rating {
        display: flex;
        align-items: center;
        margin-bottom: 0.35rem;
    }
    
    .reviewer-rating i {
        color: #e0e0e0;
        font-size: 0.9rem;
        margin-right: 0.125rem;
    }
    
    .reviewer-rating i.active {
        color: #ffb33e;
    }
    
    .review-date {
        font-size: 0.8rem;
        color: hsl(var(--shadcn-muted-foreground));
        font-weight: 500;
    }
    
    .review-content p {
        font-size: 0.95rem;
        line-height: 1.65;
        color: hsl(var(--shadcn-foreground) / 0.8);
        margin: 0;
        padding: 0.25rem 0;
    }
    
    .reviews-footer {
        margin-top: 2rem;
        text-align: center;
    }
    
    .shadcn-button-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.75rem; 
        padding: 0 1.5rem;
        border-radius: var(--shadcn-radius);
        font-size: 0.95rem;
        font-weight: 500;
        background-color: transparent;
        color: hsl(var(--shadcn-primary));
        border: 1px solid hsl(var(--shadcn-border));
        transition: all 0.2s ease;
        letter-spacing: 0.01em;
    }
    
    .shadcn-button-outline:hover {
        background-color: hsl(var(--shadcn-secondary));
        border-color: hsl(var(--shadcn-primary) / 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .shadcn-button-outline:active {
        transform: translateY(0);
    }
</style>

<!-- Service View Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle thumbnail clicks for gallery
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.querySelector('.main-gallery-image');
        const transitionImage = document.querySelector('.img-transition');
        
        // Set the first (active) thumbnail's image as the main image initially
        if (thumbnails.length > 0) {
            const activeThumb = document.querySelector('.thumbnail.active');
            if (activeThumb) {
                const firstThumbnailSrc = activeThumb.getAttribute('data-src');
                if (firstThumbnailSrc && mainImage) {
                    mainImage.src = firstThumbnailSrc;
                    // Update alt text to match
                    mainImage.alt = activeThumb.querySelector('img').alt;
                }
            }
        }
        
        // Track the currently displayed image to prevent unnecessary transitions
        let currentImageSrc = mainImage ? mainImage.src : '';
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Prevent transition if clicking the already active thumbnail
                if (this.classList.contains('active')) return;
                
                // Remove active class from all thumbnails
                thumbnails.forEach(thumb => thumb.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Get the full size image URL from data-src attribute
                const fullSizeImg = this.getAttribute('data-src');
                const thumbAlt = this.querySelector('img').alt;
                
                // Skip if it's the same image already showing
                if (fullSizeImg === currentImageSrc) return;
                
                // Create a smooth cross-fade transition
                if (mainImage && transitionImage && fullSizeImg) {
                    // Set the transition image to the current main image
                    transitionImage.src = mainImage.src;
                    transitionImage.alt = mainImage.alt;
                    transitionImage.style.opacity = '1';
                    transitionImage.style.zIndex = '1';
                    
                    // Load the new image in the main image element
                    mainImage.style.opacity = '0';
                    
                    // After a short delay, start showing the new image
                    setTimeout(() => {
                        mainImage.src = fullSizeImg;
                        mainImage.alt = thumbAlt;
                        
                        // Once the new image is loaded, fade it in and fade out the transition image
                        mainImage.onload = () => {
                            mainImage.style.opacity = '1';
                            transitionImage.style.opacity = '0';
                            
                            // Update current image tracker
                            currentImageSrc = fullSizeImg;
                            
                            // Reset z-index after transition completes
                            setTimeout(() => {
                                transitionImage.style.zIndex = '0';
                            }, 500);
                        };
                        
                        // Fallback in case onload doesn't fire
                        setTimeout(() => {
                            mainImage.style.opacity = '1';
                            transitionImage.style.opacity = '0';
                            transitionImage.style.zIndex = '0';
                        }, 500);
                    }, 50);
                }
            });
        });
        
        // Handle favorite button clicks with animation
        const favoriteButtons = document.querySelectorAll('.btn-favorite');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Add a small "pop" animation
                this.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
                
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('active');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('active');
                }
            });
        });
        
        // Enhance animations with GSAP if available
        if (typeof gsap !== 'undefined') {
            // Main image hover effect
            const serviceGallery = document.querySelector('.service-gallery');
            const mainImageContainer = document.querySelector('.main-image');
            
            if (serviceGallery && mainImageContainer) {
                serviceGallery.addEventListener('mouseenter', () => {
                    gsap.to([mainImageContainer.querySelector('.main-gallery-image'), mainImageContainer.querySelector('.img-transition')], {
                        scale: 1.03,
                        duration: 0.7,
                        ease: 'power2.out'
                    });
                });
                
                serviceGallery.addEventListener('mouseleave', () => {
                    gsap.to([mainImageContainer.querySelector('.main-gallery-image'), mainImageContainer.querySelector('.img-transition')], {
                        scale: 1,
                        duration: 0.7,
                        ease: 'power2.out'
                    });
                });
            }
            
            // Thumbnail hover animations
            thumbnails.forEach(thumb => {
                thumb.addEventListener('mouseenter', () => {
                    if (!thumb.classList.contains('active')) {
                        gsap.to(thumb, {
                            y: -4,
                            boxShadow: '0 4px 8px -2px rgba(0, 0, 0, 0.15)',
                            duration: 0.3
                        });
                        gsap.to(thumb.querySelector('img'), {
                            scale: 1.08,
                            duration: 0.4
                        });
                    }
                });
                
                thumb.addEventListener('mouseleave', () => {
                    if (!thumb.classList.contains('active')) {
                        gsap.to(thumb, {
                            y: 0,
                            boxShadow: 'none',
                            duration: 0.3
                        });
                        gsap.to(thumb.querySelector('img'), {
                            scale: 1,
                            duration: 0.4
                        });
                    }
                });
            });
            
            // Add custom animation for image transitions
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    if (!this.classList.contains('active') && mainImage && transitionImage) {
                        // Use GSAP for smoother transitions
                        gsap.to(transitionImage, {
                            opacity: 0,
                            duration: 0.6,
                            ease: "power3.inOut",
                            delay: 0.3
                        });
                        
                        gsap.fromTo(mainImage, 
                            { opacity: 0, scale: 1.02 },
                            { 
                                opacity: 1, 
                                scale: 1,
                                duration: 0.6, 
                                ease: "power3.out", 
                                delay: 0.2,
                                clearProps: "scale"
                            }
                        );
                    }
                });
            });
        }
    });
</script>

<!-- Chat Modal -->
<div class="modal fade" id="contactSellerModal" tabindex="-1" aria-labelledby="contactSellerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadcn-card">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="seller-avatar me-3">
                        <img src="<?php echo URL_ROOT; ?>/<?php echo $data['service']['seller']['avatar']; ?>" alt="<?php echo $data['service']['seller']['name']; ?>">
                    </div>
                    <div>
                        <h5 class="modal-title" id="contactSellerModalLabel">Chat with <?php echo $data['service']['seller']['name']; ?></h5>
                        <p class="seller-status"><span class="status-dot"></span> Online now</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="chat-messages" id="chatMessages">
                    <div class="message seller-message">
                        <div class="message-content">
                            <p>👋 Hi there! Thanks for your interest in my service. How can I help you today?</p>
                        </div>
                        <div class="message-time">Just now</div>
                    </div>
                </div>
                
                <div class="quick-responses">
                    <div class="quick-response-title">Quick responses:</div>
                    <div class="quick-response-options">
                        <button class="quick-response-btn" data-message="Hi! I'm interested in your service. Can you tell me more about the deliverables?">
                            Ask about deliverables
                        </button>
                        <button class="quick-response-btn" data-message="How soon can you start? I have a project with a tight deadline.">
                            Ask about timeline
                        </button>
                        <button class="quick-response-btn" data-message="Can you customize this service to fit my specific requirements?">
                            Ask about customization
                        </button>
                        <button class="quick-response-btn" data-message="Do you offer any discounts for larger projects or long-term collaboration?">
                            Ask about pricing
                        </button>
                    </div>
                </div>
                
                <div class="chat-input-container">
                    <textarea class="chat-input" id="chatInput" placeholder="Type your message..." rows="2"></textarea>
                    <button class="send-message-btn" id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal Styles -->
<style>
    /* Chat Modal Styling */
    #contactSellerModal .modal-dialog {
        max-width: 500px;
    }
    
    #contactSellerModal .modal-content {
        border: none;
        border-radius: var(--shadcn-radius);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    #contactSellerModal .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid hsl(var(--shadcn-border));
        background-color: hsl(var(--shadcn-background));
    }
    
    #contactSellerModal .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--shadcn-foreground));
        margin-bottom: 0.25rem;
    }
    
    #contactSellerModal .seller-status {
        font-size: 0.875rem;
        color: hsl(var(--shadcn-muted-foreground));
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #4CAF50;
        border-radius: 50%;
        margin-right: 6px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            opacity: 0.6;
            transform: scale(0.9);
        }
        50% {
            opacity: 1;
            transform: scale(1.1);
        }
        100% {
            opacity: 0.6;
            transform: scale(0.9);
        }
    }
    
    #contactSellerModal .seller-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid white;
        box-shadow: 0 0 0 1px hsl(var(--shadcn-border));
    }
    
    #contactSellerModal .seller-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    #contactSellerModal .modal-body {
        padding: 1.5rem;
        background-color: hsl(var(--shadcn-background) / 0.97);
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    /* Chat Messages */
    .chat-messages {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: 250px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }
    
    .chat-messages::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .chat-messages::-webkit-scrollbar-thumb {
        background-color: hsl(var(--shadcn-border));
        border-radius: 3px;
    }
    
    .message {
        display: flex;
        flex-direction: column;
        max-width: 85%;
    }
    
    .seller-message {
        align-self: flex-start;
    }
    
    .user-message {
        align-self: flex-end;
    }
    
    .message-content {
        padding: 0.75rem 1rem;
        border-radius: 1.25rem;
        position: relative;
    }
    
    .seller-message .message-content {
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        border-bottom-left-radius: 0.25rem;
    }
    
    .user-message .message-content {
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border-bottom-right-radius: 0.25rem;
    }
    
    .message-content p {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.5;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: hsl(var(--shadcn-muted-foreground));
        margin-top: 0.25rem;
        align-self: flex-start;
    }
    
    .user-message .message-time {
        align-self: flex-end;
    }
    
    /* Quick Response Options */
    .quick-responses {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .quick-response-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: hsl(var(--shadcn-muted-foreground));
    }
    
    .quick-response-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .quick-response-btn {
        padding: 0.5rem 0.75rem;
        background-color: hsl(var(--shadcn-secondary));
        color: hsl(var(--shadcn-secondary-foreground));
        border: 1px solid hsl(var(--shadcn-border));
        border-radius: var(--shadcn-radius);
        font-size: 0.8125rem;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    
    .quick-response-btn:hover {
        background-color: hsl(var(--shadcn-accent));
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    /* Chat Input */
    .chat-input-container {
        display: flex;
        align-items: center;
        background-color: white;
        border: 1px solid hsl(var(--shadcn-border));
        border-radius: var(--shadcn-radius);
        padding: 0.25rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    
    .chat-input-container:focus-within {
        border-color: hsl(var(--shadcn-ring));
        box-shadow: 0 0 0 2px hsl(var(--shadcn-ring) / 0.25);
    }
    
    .chat-input {
        flex-grow: 1;
        border: none;
        padding: 0.75rem;
        resize: none;
        font-size: 0.9375rem;
        color: hsl(var(--shadcn-foreground));
        background-color: transparent;
    }
    
    .chat-input:focus {
        outline: none;
    }
    
    .send-message-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: hsl(var(--shadcn-primary));
        color: hsl(var(--shadcn-primary-foreground));
        border: none;
        border-radius: var(--shadcn-radius);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .send-message-btn:hover {
        background-color: hsl(var(--shadcn-primary) / 0.9);
        transform: translateY(-1px);
    }
    
    .send-message-btn:active {
        transform: scale(0.97);
    }
</style>

<!-- Chat Modal Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chat Modal functionality
        const contactButtons = document.querySelectorAll('.contact-seller-btn, button.btn.shadcn-button-sm');
        const chatModal = new bootstrap.Modal(document.getElementById('contactSellerModal'));
        const chatInput = document.getElementById('chatInput');
        const sendButton = document.getElementById('sendMessageBtn');
        const chatMessages = document.getElementById('chatMessages');
        const quickResponseButtons = document.querySelectorAll('.quick-response-btn');
        
        // Open chat modal when contact buttons are clicked
        contactButtons.forEach(button => {
            button.addEventListener('click', function() {
                chatModal.show();
                // Focus the input after modal is shown
                setTimeout(() => {
                    chatInput.focus();
                }, 500);
            });
        });
        
        // Function to add a message to the chat
        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = isUser ? 'message user-message' : 'message seller-message';
            
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    <p>${content}</p>
                </div>
                <div class="message-time">${time}</div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // If this is a user message, simulate a response from the seller
            if (isUser) {
                setTimeout(() => {
                    const responseMessages = [
                        "Thanks for your message! I'd be happy to help with that.",
                        "Great question! I'll provide more details shortly.",
                        "I appreciate your interest in my service. Let me check this for you.",
                        "That's a common request and I definitely can accommodate it!"
                    ];
                    
                    const randomResponse = responseMessages[Math.floor(Math.random() * responseMessages.length)];
                    addMessage(randomResponse, false);
                    
                    // Add a more specific response
                    setTimeout(() => {
                        let detailedResponse;
                        
                        if (content.toLowerCase().includes("deliverable") || content.toLowerCase().includes("include")) {
                            detailedResponse = "This service includes all source files, commercial usage rights, and unlimited revisions until you're completely satisfied. You'll receive the initial draft within 48 hours of order placement.";
                        } else if (content.toLowerCase().includes("timeline") || content.toLowerCase().includes("start") || content.toLowerCase().includes("deadline")) {
                            detailedResponse = "I can start immediately! My current turnaround time is 3-5 days, but for urgent projects, I can deliver in 48 hours with the express delivery add-on.";
                        } else if (content.toLowerCase().includes("custom") || content.toLowerCase().includes("specific")) {
                            detailedResponse = "Yes, I can definitely customize this service to your specific needs! Let me know the details of what you're looking for, and I'll create a custom package just for you.";
                        } else if (content.toLowerCase().includes("discount") || content.toLowerCase().includes("price") || content.toLowerCase().includes("pricing")) {
                            detailedResponse = "For larger projects, I offer a 15% discount on orders over $500. I also provide a 10% discount for returning clients. Would you like me to create a custom package for you?";
                        } else {
                            detailedResponse = "Would you like to discuss the project details further? I'm happy to schedule a quick call to understand your requirements better. Alternatively, I can create a custom offer based on what you've shared.";
                        }
                        
                        addMessage(detailedResponse, false);
                    }, 2000);
                }, 1000);
            }
        }
        
        // Handle send button click
        sendButton.addEventListener('click', function() {
            const message = chatInput.value.trim();
            if (message) {
                addMessage(message, true);
                chatInput.value = '';
                chatInput.focus();
            }
        });
        
        // Handle enter key in textarea
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendButton.click();
            }
        });
        
        // Handle quick response buttons
        quickResponseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const message = this.getAttribute('data-message');
                addMessage(message, true);
                chatInput.focus();
            });
        });
    });
</script> 