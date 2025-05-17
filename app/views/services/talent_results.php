<!-- Talent Search Results Page -->
<div class="services-browse-container">
    <!-- Page Header Section -->
    <div class="browse-header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb shadcn-breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>/services/browse">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Talent Search</li>
                        </ol>
                    </nav>
                    
                    <h1 class="browse-title">
                        Talent Search Results
                    </h1>
                    
                    <p class="browse-description">
                        <?php if(!empty($data['query'])): ?>
                            Freelancers matching "<?php echo htmlspecialchars($data['query']); ?>"
                        <?php else: ?>
                            Browse talented freelancers from around the world
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" class="search-form shadcn-search">
                        <input type="hidden" name="type" value="talent">
                        
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search for talent..." value="<?php echo htmlspecialchars($data['query'] ?? ''); ?>">
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
                            <h4 class="filter-title">Experience Level</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET">
                                <input type="hidden" name="type" value="talent">
                                
                                <?php if(!empty($data['query'])): ?>
                                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                <?php endif; ?>
                                
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="experience_level" id="entry" value="entry" <?php echo (isset($data['experienceLevel']) && $data['experienceLevel'] === 'entry') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="entry">
                                        Entry Level
                                    </label>
                                </div>
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="experience_level" id="intermediate" value="intermediate" <?php echo (isset($data['experienceLevel']) && $data['experienceLevel'] === 'intermediate') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="intermediate">
                                        Intermediate
                                    </label>
                                </div>
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="experience_level" id="expert" value="expert" <?php echo (isset($data['experienceLevel']) && $data['experienceLevel'] === 'expert') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="expert">
                                        Expert
                                    </label>
                                </div>
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="experience_level" id="any" value="" <?php echo (!isset($data['experienceLevel']) || empty($data['experienceLevel'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="any">
                                        Any Level
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 shadcn-button">Apply</button>
                            </form>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Hourly Rate</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" class="price-filter-form">
                                <input type="hidden" name="type" value="talent">
                                
                                <?php if(!empty($data['query'])): ?>
                                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                <?php endif; ?>
                                
                                <div class="price-ranges">
                                    <div class="price-input-group">
                                        <label for="min_rate">Min Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="min_rate" id="min_rate" value="<?php echo $data['minRate'] ?? ''; ?>" min="0" max="999">
                                        </div>
                                    </div>
                                    
                                    <div class="price-input-group">
                                        <label for="max_rate">Max Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="max_rate" id="max_rate" value="<?php echo $data['maxRate'] ?? ''; ?>" min="1" max="1000">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 shadcn-button">Apply</button>
                            </form>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Country</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET">
                                <input type="hidden" name="type" value="talent">
                                
                                <?php if(!empty($data['query'])): ?>
                                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                <?php endif; ?>
                                
                                <select name="country" class="form-select shadcn-select mb-3">
                                    <option value="">Any Country</option>
                                    <option value="US" <?php echo (isset($data['country']) && $data['country'] === 'US') ? 'selected' : ''; ?>>United States</option>
                                    <option value="GB" <?php echo (isset($data['country']) && $data['country'] === 'GB') ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="CA" <?php echo (isset($data['country']) && $data['country'] === 'CA') ? 'selected' : ''; ?>>Canada</option>
                                    <option value="AU" <?php echo (isset($data['country']) && $data['country'] === 'AU') ? 'selected' : ''; ?>>Australia</option>
                                    <option value="IN" <?php echo (isset($data['country']) && $data['country'] === 'IN') ? 'selected' : ''; ?>>India</option>
                                    <option value="TN" <?php echo (isset($data['country']) && $data['country'] === 'TN') ? 'selected' : ''; ?>>Tunisia</option>
                                    <!-- Add more countries as needed -->
                                </select>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 shadcn-button">Apply</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
                    <!-- Sort and View Controls -->
                    <div class="browse-controls shadcn-card">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="results-count"><?php echo $data['resultCount']; ?> freelancers found</p>
                            </div>
                            <div class="col-md-6">
                                <div class="sort-controls">
                                    <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" id="sortForm">
                                        <input type="hidden" name="type" value="talent">
                                        
                                        <?php if(!empty($data['query'])): ?>
                                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                        <?php endif; ?>
                                        
                                        <label for="sort">Sort by:</label>
                                        <select name="sort" id="sort" class="form-select form-select-sm shadcn-select" onchange="document.getElementById('sortForm').submit()">
                                            <option value="relevance" <?php echo (!isset($data['sort']) || $data['sort'] === 'relevance') ? 'selected' : ''; ?>>Relevance</option>
                                            <option value="rate_high" <?php echo (isset($data['sort']) && $data['sort'] === 'rate_high') ? 'selected' : ''; ?>>Rate: High to Low</option>
                                            <option value="rate_low" <?php echo (isset($data['sort']) && $data['sort'] === 'rate_low') ? 'selected' : ''; ?>>Rate: Low to High</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Talent Cards Grid -->
                    <div class="talent-cards">
                        <?php if(empty($data['results'])): ?>
                            <div class="no-results shadcn-card">
                                <div class="no-results-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3>No freelancers found</h3>
                                <p>Try adjusting your search or filter criteria</p>
                                <a href="<?php echo URL_ROOT; ?>/services/search?type=talent" class="btn shadcn-button mt-3">Reset Filters</a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach($data['results'] as $talent): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="talent-card shadcn-card">
                                            <div class="talent-header">
                                                <div class="talent-avatar">
                                                    <?php if(!empty($talent->profile_image)): ?>
                                                        <img src="<?php echo URL_ROOT . '/' . $talent->profile_image; ?>" alt="<?php echo htmlspecialchars($talent->name); ?>">
                                                    <?php else: ?>
                                                        <img src="<?php echo URL_ROOT; ?>/public/img/default-avatar.png" alt="Freelancer">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="talent-main-info">
                                                    <h3 class="talent-name">
                                                        <a href="<?php echo URL_ROOT; ?>/user/view/<?php echo $talent->id; ?>"><?php echo htmlspecialchars($talent->name); ?></a>
                                                    </h3>
                                                    <div class="talent-title"><?php echo !empty($talent->professional_title) ? htmlspecialchars($talent->professional_title) : 'Freelancer'; ?></div>
                                                    <div class="talent-location">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span><?php echo !empty($talent->country) ? htmlspecialchars($talent->country) : 'Unknown Location'; ?></span>
                                                    </div>
                                                </div>
                                                <div class="talent-rate">
                                                    <span class="rate-amount">$<?php echo number_format((float)$talent->hourly_rate, 2); ?></span>
                                                    <span class="rate-label">/hr</span>
                                                </div>
                                            </div>
                                            
                                            <div class="talent-bio">
                                                <?php 
                                                $bio = !empty($talent->bio) ? htmlspecialchars($talent->bio) : 'No bio available.';
                                                echo (strlen($bio) > 150) ? substr($bio, 0, 150) . '...' : $bio; 
                                                ?>
                                            </div>
                                            
                                            <div class="talent-skills">
                                                <?php 
                                                $skills = json_decode($talent->skills, true);
                                                if(!empty($skills) && is_array($skills)):
                                                    $count = 0;
                                                    foreach($skills as $skill): 
                                                        if($count < 5): // Limit to 5 skills
                                                ?>
                                                    <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                                                <?php 
                                                        $count++;
                                                        endif;
                                                    endforeach;
                                                    
                                                    if(count($skills) > 5): 
                                                ?>
                                                    <span class="skill-tag more-skills">+<?php echo count($skills) - 5; ?> more</span>
                                                <?php 
                                                    endif;
                                                endif;
                                                ?>
                                            </div>
                                            
                                            <div class="talent-footer">
                                                <div class="talent-experience">
                                                    <span class="experience-level">
                                                        <?php 
                                                        $level = !empty($talent->experience_level) ? $talent->experience_level : 'entry';
                                                        echo ucfirst(htmlspecialchars($level)) . ' Level';
                                                        ?>
                                                    </span>
                                                </div>
                                                
                                                <div class="talent-actions">
                                                    <a href="<?php echo URL_ROOT; ?>/user/view/<?php echo $talent->id; ?>" class="btn btn-primary btn-sm shadcn-button-sm">View Profile</a>
                                                    <a href="<?php echo URL_ROOT; ?>/messages/new/<?php echo $talent->id; ?>" class="btn btn-outline-primary btn-sm shadcn-button-outline-sm">Contact</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if(!empty($data['results']) && $data['resultCount'] > 10): ?>
                    <div class="services-pagination">
                        <nav aria-label="Page navigation">
                            <ul class="pagination shadcn-pagination justify-content-center">
                                <li class="page-item <?php echo (!isset($data['page']) || $data['page'] <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=talent<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo (isset($data['page']) ? $data['page'] - 1 : 1); ?>" tabindex="-1" <?php echo (!isset($data['page']) || $data['page'] <= 1) ? 'aria-disabled="true"' : ''; ?>>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                <?php
                                $totalPages = ceil($data['resultCount'] / 10);
                                for($i = 1; $i <= min($totalPages, 5); $i++):
                                ?>
                                <li class="page-item <?php echo ((!isset($data['page']) && $i === 1) || (isset($data['page']) && $data['page'] == $i)) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=talent<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?php echo (isset($data['page']) && $data['page'] >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=talent<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo (isset($data['page']) ? $data['page'] + 1 : 2); ?>" <?php echo (isset($data['page']) && $data['page'] >= $totalPages) ? 'aria-disabled="true"' : ''; ?>>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Talent Search Results Styling -->
<style>
    /* Styling for talent cards */
    .talent-card {
        margin-bottom: 20px;
        transition: all 0.3s ease;
        padding: 20px;
        border: 1px solid var(--gray-light);
        border-radius: var(--shadcn-radius);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .talent-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .talent-header {
        display: flex;
        margin-bottom: 15px;
        position: relative;
    }
    
    .talent-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--primary-accent);
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .talent-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .talent-main-info {
        flex-grow: 1;
        min-width: 0; /* Allow text truncation */
    }
    
    .talent-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .talent-name a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .talent-name a:hover {
        color: var(--primary-light);
    }
    
    .talent-title {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .talent-location {
        font-size: 13px;
        color: var(--gray-medium);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .talent-rate {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .rate-amount {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary);
    }
    
    .rate-label {
        font-size: 12px;
        color: var(--gray-medium);
    }
    
    .talent-bio {
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.5;
        color: var(--gray-dark);
        /* Allow 3 lines max */
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    
    .talent-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 15px;
    }
    
    .skill-tag {
        display: inline-block;
        padding: 3px 8px;
        background-color: var(--primary-accent);
        color: var(--primary);
        font-size: 11px;
        border-radius: 30px;
        font-weight: 500;
    }
    
    .more-skills {
        background-color: var(--gray-light);
        color: var(--gray-dark);
    }
    
    .talent-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--gray-light);
        margin-top: auto;
    }
    
    .talent-experience {
        font-size: 13px;
    }
    
    .experience-level {
        background-color: var(--primary-accent);
        color: var(--primary);
        padding: 3px 8px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 11px;
    }
    
    .talent-actions {
        display: flex;
        gap: 8px;
    }
    
    .shadcn-button-sm {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: var(--shadcn-radius);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        background-color: var(--primary);
        color: white;
        border: none;
    }
    
    .shadcn-button-sm:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }
    
    .shadcn-button-outline-sm {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: var(--shadcn-radius);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }
    
    .shadcn-button-outline-sm:hover {
        background-color: var(--primary-accent);
        transform: translateY(-2px);
        color: var(--primary-dark);
        text-decoration: none;
    }
    
    /* Empty results styling */
    .no-results {
        padding: 60px 20px;
        text-align: center;
    }
    
    .no-results-icon {
        font-size: 48px;
        color: var(--gray-medium);
        margin-bottom: 20px;
    }
    
    .no-results h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-dark);
    }
    
    .no-results p {
        font-size: 16px;
        color: var(--gray-medium);
        margin-bottom: 20px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .talent-rate {
            position: static;
            margin-top: 10px;
            align-items: flex-start;
        }
        
        .talent-header {
            flex-direction: column;
        }
        
        .talent-avatar {
            margin-bottom: 15px;
        }
        
        .talent-actions {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        
        .shadcn-button-sm, .shadcn-button-outline-sm {
            width: 100%;
            text-align: center;
        }
    }
</style> 