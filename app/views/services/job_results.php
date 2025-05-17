<!-- Job Search Results Page -->
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
                            <li class="breadcrumb-item active" aria-current="page">Job Search</li>
                        </ol>
                    </nav>
                    
                    <h1 class="browse-title">
                        Job Search Results
                    </h1>
                    
                    <p class="browse-description">
                        <?php if(!empty($data['query'])): ?>
                            Search results for "<?php echo htmlspecialchars($data['query']); ?>"
                        <?php else: ?>
                            Browse available jobs from clients around the world
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" class="search-form shadcn-search">
                        <input type="hidden" name="type" value="jobs">
                        
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search for jobs..." value="<?php echo htmlspecialchars($data['query'] ?? ''); ?>">
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
                                    <a href="<?php echo URL_ROOT; ?>/services/search?type=jobs<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>">All Categories</a>
                                </li>
                                <?php foreach($data['categories'] as $categorySlug => $categoryName): ?>
                                    <li class="<?php echo ($data['activeCategory'] === $categorySlug) ? 'active' : ''; ?>">
                                        <a href="<?php echo URL_ROOT; ?>/services/search?type=jobs&category=<?php echo $categorySlug; ?><?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>">
                                            <?php echo $categoryName; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Budget</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" class="price-filter-form">
                                <input type="hidden" name="type" value="jobs">
                                
                                <?php if(!empty($data['activeCategory'])): ?>
                                    <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                <?php endif; ?>
                                
                                <?php if(!empty($data['query'])): ?>
                                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                <?php endif; ?>
                                
                                <div class="price-ranges">
                                    <div class="price-input-group">
                                        <label for="min_budget">Min Budget</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="min_budget" id="min_budget" value="<?php echo $data['minBudget'] ?? ''; ?>" min="0" max="99999">
                                        </div>
                                    </div>
                                    
                                    <div class="price-input-group">
                                        <label for="max_budget">Max Budget</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control shadcn-input" name="max_budget" id="max_budget" value="<?php echo $data['maxBudget'] ?? ''; ?>" min="1" max="100000">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 shadcn-button">Apply</button>
                            </form>
                        </div>
                        
                        <div class="filter-section">
                            <h4 class="filter-title">Experience Level</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET">
                                <input type="hidden" name="type" value="jobs">
                                
                                <?php if(!empty($data['activeCategory'])): ?>
                                    <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                <?php endif; ?>
                                
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
                            <h4 class="filter-title">Job Type</h4>
                            <form action="<?php echo URL_ROOT; ?>/services/search" method="GET">
                                <input type="hidden" name="type" value="jobs">
                                
                                <?php if(!empty($data['activeCategory'])): ?>
                                    <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                <?php endif; ?>
                                
                                <?php if(!empty($data['query'])): ?>
                                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                <?php endif; ?>
                                
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="job_type" id="fixed" value="fixed" <?php echo (isset($data['jobType']) && $data['jobType'] === 'fixed') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="fixed">
                                        Fixed Price
                                    </label>
                                </div>
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="job_type" id="hourly" value="hourly" <?php echo (isset($data['jobType']) && $data['jobType'] === 'hourly') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="hourly">
                                        Hourly Rate
                                    </label>
                                </div>
                                <div class="form-check shadcn-radio">
                                    <input class="form-check-input" type="radio" name="job_type" id="any_type" value="" <?php echo (!isset($data['jobType']) || empty($data['jobType'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="any_type">
                                        Any Type
                                    </label>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 shadcn-button">Apply</button>
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
                                <p class="results-count"><?php echo $data['resultCount']; ?> jobs found</p>
                            </div>
                            <div class="col-md-6">
                                <div class="sort-controls">
                                    <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" id="sortForm">
                                        <input type="hidden" name="type" value="jobs">
                                        
                                        <?php if(!empty($data['activeCategory'])): ?>
                                            <input type="hidden" name="category" value="<?php echo $data['activeCategory']; ?>">
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($data['query'])): ?>
                                            <input type="hidden" name="q" value="<?php echo htmlspecialchars($data['query']); ?>">
                                        <?php endif; ?>
                                        
                                        <label for="sort">Sort by:</label>
                                        <select name="sort" id="sort" class="form-select form-select-sm shadcn-select" onchange="document.getElementById('sortForm').submit()">
                                            <option value="newest" <?php echo (!isset($data['sort']) || $data['sort'] === 'newest') ? 'selected' : ''; ?>>Newest</option>
                                            <option value="budget_high" <?php echo (isset($data['sort']) && $data['sort'] === 'budget_high') ? 'selected' : ''; ?>>Budget: High to Low</option>
                                            <option value="budget_low" <?php echo (isset($data['sort']) && $data['sort'] === 'budget_low') ? 'selected' : ''; ?>>Budget: Low to High</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Jobs List -->
                    <div class="jobs-list">
                        <?php if(empty($data['results'])): ?>
                            <div class="no-results shadcn-card">
                                <div class="no-results-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3>No jobs found</h3>
                                <p>Try adjusting your search or filter criteria</p>
                                <a href="<?php echo URL_ROOT; ?>/services/search?type=jobs" class="btn shadcn-button mt-3">Reset Filters</a>
                            </div>
                        <?php else: ?>
                            <?php foreach($data['results'] as $job): ?>
                                <div class="job-card shadcn-card">
                                    <div class="job-header">
                                        <div class="job-title-section">
                                            <h3 class="job-title">
                                                <a href="<?php echo URL_ROOT; ?>/jobs/view/<?php echo $job->id; ?>"><?php echo htmlspecialchars($job->title); ?></a>
                                            </h3>
                                            <div class="job-meta">
                                                <span class="job-category"><?php echo htmlspecialchars($job->category); ?></span>
                                                <span class="job-date"><?php echo date('M d, Y', strtotime($job->created_at)); ?></span>
                                            </div>
                                        </div>
                                        <div class="job-budget">
                                            <span class="budget-amount">$<?php echo htmlspecialchars($job->budget); ?></span>
                                            <span class="budget-type"><?php echo ucfirst(htmlspecialchars($job->job_type)); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="job-description">
                                        <?php 
                                        $description = htmlspecialchars($job->description);
                                        echo (strlen($description) > 300) ? substr($description, 0, 300) . '...' : $description; 
                                        ?>
                                    </div>
                                    
                                    <div class="job-skills">
                                        <?php 
                                        $skills = json_decode($job->skills, true);
                                        if(!empty($skills)):
                                            foreach($skills as $skill): 
                                        ?>
                                            <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                                        <?php 
                                            endforeach; 
                                        endif;
                                        ?>
                                    </div>
                                    
                                    <div class="job-footer">
                                        <div class="client-info">
                                            <div class="client-avatar">
                                                <?php if(!empty($job->profile_image)): ?>
                                                    <img src="<?php echo URL_ROOT . '/' . $job->profile_image; ?>" alt="<?php echo htmlspecialchars($job->client_name); ?>">
                                                <?php else: ?>
                                                    <img src="<?php echo URL_ROOT; ?>/public/img/default-avatar.png" alt="Client">
                                                <?php endif; ?>
                                            </div>
                                            <div class="client-details">
                                                <span class="client-name"><?php echo htmlspecialchars($job->client_name); ?></span>
                                                <div class="client-location">
                                                    <i class="fas fa-map-marker-alt"></i> 
                                                    <span>Unknown Location</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="job-actions">
                                            <a href="<?php echo URL_ROOT; ?>/jobs/view/<?php echo $job->id; ?>" class="btn btn-primary btn-sm shadcn-button-sm">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if(!empty($data['results']) && $data['resultCount'] > 10): ?>
                    <div class="services-pagination">
                        <nav aria-label="Page navigation">
                            <ul class="pagination shadcn-pagination justify-content-center">
                                <li class="page-item <?php echo (!isset($data['page']) || $data['page'] <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=jobs<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo (isset($data['page']) ? $data['page'] - 1 : 1); ?>" tabindex="-1" <?php echo (!isset($data['page']) || $data['page'] <= 1) ? 'aria-disabled="true"' : ''; ?>>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                
                                <?php
                                $totalPages = ceil($data['resultCount'] / 10);
                                for($i = 1; $i <= min($totalPages, 5); $i++):
                                ?>
                                <li class="page-item <?php echo ((!isset($data['page']) && $i === 1) || (isset($data['page']) && $data['page'] == $i)) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=jobs<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?php echo (isset($data['page']) && $data['page'] >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo URL_ROOT; ?>/services/search?type=jobs<?php echo !empty($data['query']) ? '&q=' . urlencode($data['query']) : ''; ?>&page=<?php echo (isset($data['page']) ? $data['page'] + 1 : 2); ?>" <?php echo (isset($data['page']) && $data['page'] >= $totalPages) ? 'aria-disabled="true"' : ''; ?>>
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

<!-- Job Search Results Styling -->
<style>
    /* Styling for job cards */
    .job-card {
        margin-bottom: 20px;
        transition: all 0.3s ease;
        padding: 20px;
        border: 1px solid var(--gray-light);
        border-radius: var(--shadcn-radius);
    }
    
    .job-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .job-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .job-title-section {
        flex: 1;
    }
    
    .job-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .job-title a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .job-title a:hover {
        color: var(--primary-light);
    }
    
    .job-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 14px;
        color: var(--gray-medium);
    }
    
    .job-budget {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    
    .budget-amount {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary);
    }
    
    .budget-type {
        font-size: 13px;
        color: var(--gray-medium);
    }
    
    .job-description {
        margin-bottom: 15px;
        font-size: 14px;
        line-height: 1.6;
        color: var(--gray-dark);
    }
    
    .job-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
    }
    
    .skill-tag {
        display: inline-block;
        padding: 4px 10px;
        background-color: var(--primary-accent);
        color: var(--primary);
        font-size: 12px;
        border-radius: 30px;
        font-weight: 500;
    }
    
    .job-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--gray-light);
    }
    
    .client-info {
        display: flex;
        align-items: center;
    }
    
    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 12px;
    }
    
    .client-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .client-details {
        display: flex;
        flex-direction: column;
    }
    
    .client-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .client-location {
        font-size: 12px;
        color: var(--gray-medium);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .shadcn-button-sm {
        font-size: 14px;
        padding: 6px 12px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: var(--shadcn-radius);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .shadcn-button-sm:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
        color: white;
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
</style> 