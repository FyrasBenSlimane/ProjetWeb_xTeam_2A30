<?php
// Freelance main page view
// This file displays the main freelance platform page for logged-in freelancers

// Header with navbar is handled by the controller
?>

<!-- Main Content Container -->
<div class="container-fluid px-0">
    <div class="container">
        <div class="row">
            <!-- Left Content Column -->
            <div class="col-lg-8">
                <!-- Promotional Banner Section - Clean Shadcn UI Style -->
                <div class="promo-banners-wrapper mb-4">
                    <div class="shadcn-card promo-banner-container">
                        <div class="promo-carousel">
                            <!-- Feature Banner 1 -->
                            <div class="promo-banner-content" data-banner-id="1">
                                <div class="promo-banner-background" style="background-color: hsl(164, 76%, 46%);">
                                    <div class="promo-banner-grid">
                                        <div class="promo-banner-text">
                                            <div class="shadcn-badge shadcn-badge-secondary mb-3 promo-badge">Featured Offer</div>
                                            <h2 class="promo-banner-title">Freelancer Plus with new perks</h2>
                                            <p class="promo-banner-description">100 monthly Connects and full access to Uma, Upwork's Mindful AI.</p>
                                            
                                            <button class="shadcn-btn shadcn-btn-secondary">Learn more</button>
                                        </div>
                                        <div class="promo-banner-image">
                                            <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Freelancer Plus" class="promo-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Feature Banner 2 -->
                            <div class="promo-banner-content" data-banner-id="2" style="display: none;">
                                <div class="promo-banner-background" style="background-color: hsl(221, 83%, 53%);">
                                    <div class="promo-banner-grid">
                                        <div class="promo-banner-text">
                                            <div class="shadcn-badge shadcn-badge-secondary mb-3 promo-badge">Limited Time</div>
                                            <h2 class="promo-banner-title">Double Your Visibility</h2>
                                            <p class="promo-banner-description">Get featured placement in search results with Boosted Proposals.</p>
                                            
                                            <button class="shadcn-btn shadcn-btn-secondary">Boost now</button>
                                        </div>
                                        <div class="promo-banner-image">
                                            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Boosted Proposals" class="promo-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Feature Banner 3 -->
                            <div class="promo-banner-content" data-banner-id="3" style="display: none;">
                                <div class="promo-banner-background" style="background-color: hsl(168, 80%, 40%);">
                                    <div class="promo-banner-grid">
                                        <div class="promo-banner-text">
                                            <div class="shadcn-badge shadcn-badge-secondary mb-3 promo-badge">Professional Growth</div>
                                            <h2 class="promo-banner-title">Expert Certification Program</h2>
                                            <p class="promo-banner-description">Showcase verified skills and earn 50% more on average.</p>
                                            
                                            <button class="shadcn-btn shadcn-btn-secondary">Get certified</button>
                                        </div>
                                        <div class="promo-banner-image">
                                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Expert Certification" class="promo-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Banner Navigation - Fixed visibility arrows -->
                            <div class="promo-banner-nav">
                                <button class="promo-arrow-btn" id="promo-prev" aria-label="Previous banner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"></path></svg>
                                </button>
                                
                                <button class="promo-arrow-btn" id="promo-next" aria-label="Next banner">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <!-- Search Bar with Shadcn Command component style -->
                <div class="mb-4">
                    <div class="shadcn-command job-search-command">
                        <form id="job-search-form" action="<?php echo URL_ROOT; ?>/freelance" method="get">
                            <div class="shadcn-command-input">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shadcn-command-input-icon"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                <input 
                                    type="text" 
                                    class="shadcn-command-input-field" 
                                    name="job_search" 
                                    id="job-search-input" 
                                    placeholder="Search for jobs" 
                                    aria-label="Search for jobs" 
                                    value="<?php echo isset($data['search']) ? htmlspecialchars($data['search']) : ''; ?>"
                                >
                                <div class="shadcn-kbd">⌘K</div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Jobs Section -->
                <h2 class="section-title mb-4" id="jobs-listing">Jobs you might like</h2>
                
                <!-- Tab Navigation - Shadcn UI Style -->
                <div class="shadcn-tabs mb-3">
                    <div class="shadcn-tabs-list">
                        <button class="shadcn-tabs-trigger <?php echo (!isset($data['sort']) || $data['sort'] == 'newest') ? 'active' : ''; ?>" data-state="<?php echo (!isset($data['sort']) || $data['sort'] == 'newest') ? 'active' : 'inactive'; ?>" onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?sort=newest<?php echo isset($data['search']) && !empty($data['search']) ? '&job_search=' . urlencode($data['search']) : ''; ?>'">
                                Best Matches
                        </button>
                        <button class="shadcn-tabs-trigger <?php echo (isset($data['sort']) && $data['sort'] == 'oldest') ? 'active' : ''; ?>" data-state="<?php echo (isset($data['sort']) && $data['sort'] == 'oldest') ? 'active' : 'inactive'; ?>" onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?sort=oldest<?php echo isset($data['search']) && !empty($data['search']) ? '&job_search=' . urlencode($data['search']) : ''; ?>'">
                                Most Recent
                        </button>
                        <button class="shadcn-tabs-trigger <?php echo (isset($data['sort']) && $data['sort'] == 'saved') ? 'active' : ''; ?>" data-state="<?php echo (isset($data['sort']) && $data['sort'] == 'saved') ? 'active' : 'inactive'; ?>" onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?sort=saved'">
                            Saved Jobs <span class="tab-badge"><?php echo isset($data['saved_jobs_count']) ? $data['saved_jobs_count'] : 0; ?></span>
                        </button>
                    </div>
                </div>
                
                <!-- Job Listings -->
                <div class="job-listings" id="job-listings-container">
                    <?php if (isset($data['jobs']) && !empty($data['jobs'])): ?>
                        <?php foreach ($data['jobs'] as $job): ?>
                            <!-- Job Item with Shadcn UI Card styling -->
                            <div class="shadcn-card job-card" data-job-id="<?php echo $job->id; ?>" onclick="openJobDetails(<?php echo $job->id; ?>)">
                                <div class="job-card-content">
                                    <div class="job-card-header">
                                        <div class="job-meta-info">
                                            <span class="job-posted-time">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-icon"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <?php echo $job->posted_time; ?>
                                            </span>
                                        <?php if (isset($job->is_featured) && $job->is_featured): ?>
                                                <span class="shadcn-badge shadcn-badge-secondary featured-badge">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-icon"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                    Featured
                                                </span>
                                        <?php endif; ?>
                            </div>
                            <div class="job-actions">
                                            <button class="shadcn-btn shadcn-btn-ghost job-action-btn btn-dislike" data-job-id="<?php echo $job->id; ?>" onclick="event.stopPropagation(); dislikeJob(<?php echo $job->id; ?>);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 14V2"></path><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"></path></svg>
                                </button>
                                            <button class="shadcn-btn shadcn-btn-ghost job-action-btn btn-save-job <?php echo $job->is_saved ? 'saved' : ''; ?>" data-job-id="<?php echo $job->id; ?>" onclick="event.stopPropagation(); toggleSaveJob(<?php echo $job->id; ?>);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $job->is_saved ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <h3 class="job-title">
                                        <?php echo htmlspecialchars($job->title); ?>
                                    <?php if (isset($job->client_rating) && $job->client_rating >= 4.5): ?>
                                            <span class="shadcn-badge shadcn-badge-primary top-rated-badge" title="Top-rated client with excellent feedback">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-icon"><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"></path><path d="M15 7a3 3 0 1 0-5.999.001A3 3 0 0 0 15 7z"></path><path d="m10.5 7 2-4 2 4"></path><path d="M13.5 7h-7l5 5"></path><path d="M13.5 7h7l-5 5"></path></svg>
                                                Top rated
                                            </span>
                                    <?php endif; ?>
                        </h3>
                        
                                    <div class="job-badges">
                                        <span class="shadcn-badge shadcn-badge-outline">
                                            <?php echo ucfirst($job->job_type); ?> Price
                                        </span>
                                        <span class="shadcn-badge shadcn-badge-outline">
                                            <?php echo ucfirst($job->experience_level); ?>
                                        </span>
                                        <span class="shadcn-badge shadcn-badge-outline job-budget">
                                        <?php if ($job->job_type == 'fixed'): ?>
                                            Est. Budget: $<?php echo number_format($job->budget, 2); ?>
                                        <?php else: ?>
                                            $<?php echo number_format($job->budget, 2); ?>/hr
                                        <?php endif; ?>
                                    </span>
                                    <?php if (isset($job->experience_level) && $job->experience_level == 'expert'): ?>
                                            <span class="shadcn-badge shadcn-badge-primary">
                                                Expert level
                                            </span>
                                    <?php endif; ?>
                        </div>
                        
                        <p class="job-description">
                                    <?php 
                                    $description = htmlspecialchars($job->description);
                                    $shortDesc = (strlen($description) > 200) ? substr($description, 0, 200) . '...' : $description;
                                    echo $shortDesc;
                                    
                                    if (strlen($description) > 200): 
                                    ?>
                                        <button class="more-link">more</button>
                                    <?php endif; ?>
                        </p>
                        
                        <div class="job-skills">
                                    <?php if (isset($job->skillsArray) && !empty($job->skillsArray)): ?>
                                        <?php foreach ($job->skillsArray as $skill): ?>
                                                <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                        </div>
                                    
                                        <div class="job-footer">
                            <div>
                                <?php if (isset($job->proposal_count)): ?>
                                            <div class="job-footer-stat">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-icon"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                <?php echo $job->proposal_count; ?> proposals
                                            </div>
                                <?php endif; ?>
                                <?php if (isset($job->client_location)): ?>
                                                <div class="job-footer-stat">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="job-icon"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                                    <?php echo htmlspecialchars($job->client_location); ?>
                                                </div>
                                <?php endif; ?>
                            </div>
                            <button class="shadcn-btn shadcn-btn-primary job-apply-btn" data-job-id="<?php echo $job->id; ?>" onclick="event.stopPropagation();">
                                <i class="fas fa-paper-plane me-1"></i> Apply
                            </button>
                        </div>
                                </div>
                    </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-jobs-found shadcn-card empty-state-card">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                </div>
                                <h4 class="empty-state-title">No jobs found</h4>
                                <p class="empty-state-description">Try adjusting your search criteria or explore other job categories.</p>
                                <button class="shadcn-btn shadcn-btn-outline refresh-btn" id="refresh-jobs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path><path d="M16 16h5v5"></path></svg>
                                    Refresh Job Search
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination Controls with Shadcn UI styling -->
                <?php if (isset($data['pagination']) && $data['pagination']['total_pages'] > 1): ?>
                <div class="shadcn-pagination">
                    <div class="shadcn-pagination-content">
                        <button class="shadcn-btn shadcn-btn-outline shadcn-btn-sm pagination-btn <?php echo ($data['pagination']['current_page'] <= 1) ? 'disabled' : ''; ?>" 
                            <?php if ($data['pagination']['current_page'] > 1): ?>
                                onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?page=<?php echo $data['pagination']['current_page']-1; ?><?php echo isset($data['sort']) ? '&sort='.$data['sort'] : ''; ?>'"
                            <?php endif; ?>
                            <?php echo ($data['pagination']['current_page'] <= 1) ? 'disabled' : ''; ?>
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            <span class="sr-only">Previous</span>
                        </button>
                        
                        <div class="shadcn-pagination-list">
                            <?php for($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                                <button class="shadcn-pagination-link <?php echo ($i == $data['pagination']['current_page']) ? 'active' : ''; ?>" 
                                    onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?page=<?php echo $i; ?><?php echo isset($data['sort']) ? '&sort='.$data['sort'] : ''; ?>'">
                                        <?php echo $i; ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                        
                        <button class="shadcn-btn shadcn-btn-outline shadcn-btn-sm pagination-btn <?php echo ($data['pagination']['current_page'] >= $data['pagination']['total_pages']) ? 'disabled' : ''; ?>" 
                            <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                                onclick="window.location.href='<?php echo URL_ROOT; ?>/freelance?page=<?php echo $data['pagination']['current_page']+1; ?><?php echo isset($data['sort']) ? '&sort='.$data['sort'] : ''; ?>'"
                            <?php endif; ?>
                            <?php echo ($data['pagination']['current_page'] >= $data['pagination']['total_pages']) ? 'disabled' : ''; ?>
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
                        
            <!-- Right Sidebar - Profile and Connects -->
            <div class="col-lg-4">
                <!-- Profile Card - Enhanced with Shadcn UI -->
                <div class="shadcn-card profile-card mb-4">
                    <div class="profile-card-content">
                            <?php 
                            $profileImage = isset($data['user']->profile_image) && !empty($data['user']->profile_image) 
                                ? $data['user']->profile_image 
                                : URL_ROOT . '/public/img/default-avatar.png';
                            $onlineStatus = isset($data['user']->online_status) ? $data['user']->online_status : 'online';
                        $initials = isset($data['user']->name) ? substr($data['user']->name, 0, 1) : 'U';
                        ?>
                        
                        <div class="profile-header">
                            <div class="profile-info">
                                <div class="shadcn-avatar shadcn-avatar-lg">
                                    <?php if ($profileImage): ?>
                                        <img src="<?php echo $profileImage; ?>" alt="Profile" class="shadcn-avatar-image">
                                    <?php else: ?>
                                        <span class="shadcn-avatar-fallback"><?php echo $initials; ?></span>
                                    <?php endif; ?>
                                    <span class="shadcn-status-badge <?php echo $onlineStatus; ?>"></span>
                                </div>
                                
                                <div class="profile-details">
                                    <h4 class="profile-name"><?php echo isset($data['user']->name) ? htmlspecialchars($data['user']->name) : 'Freelancer'; ?></h4>
                                    <p class="profile-title"><?php echo isset($data['user']->title) ? htmlspecialchars($data['user']->title) : 'Python Developer'; ?></p>
                                    <div class="profile-rating">
                                        <?php
                                        $rating = isset($data['user']->rating) ? (float)$data['user']->rating : 4.8;
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5;
                                        
                                        echo '<div class="rating-stars">';
                                        for($i = 1; $i <= 5; $i++) {
                                            if($i <= $fullStars) {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="star-icon"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                            } elseif($i == $fullStars + 1 && $halfStar) {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="star-icon"><path d="M12 2L8.91 8.26L2 9.27L7 14.14L5.82 21.02L12 17.77L18.18 21.02L17 14.14L22 9.27L15.09 8.26L12 2Z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 2L8.91 8.26L2 9.27L7 14.14L5.82 21.02L12 17.77L12 2Z" fill="currentColor"/></svg>';
                                            } else {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="star-icon"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                            }
                                        }
                                        echo '</div>';
                                        ?>
                                        <span class="rating-count">(<?php echo isset($data['user']->review_count) ? $data['user']->review_count : '42'; ?> reviews)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Stats with Shadcn UI styling -->
                            <div class="profile-stats">
                                <div class="stats-grid">
                                    <div class="stats-card">
                                        <div class="stat-value"><?php echo isset($data['user']->jobs_completed) ? $data['user']->jobs_completed : '12'; ?></div>
                                        <div class="stat-label">Jobs</div>
                                    </div>
                                    <div class="stats-card">
                                        <div class="stat-value">$<?php echo isset($data['user']->total_earnings) ? number_format($data['user']->total_earnings) : '1,250'; ?></div>
                                        <div class="stat-label">Earned</div>
                                    </div>
                                    <div class="stats-card">
                                        <div class="shadcn-radial-progress" style="--size: 3rem; --value: <?php echo isset($data['user']->success_rate) ? $data['user']->success_rate : '95'; ?>;">
                                            <svg aria-hidden="true" width="100%" height="100%" viewBox="0 0 100 100">
                                                <circle class="shadcn-radial-progress-bg" cx="50" cy="50" r="45" />
                                                <circle class="shadcn-radial-progress-indicator" cx="50" cy="50" r="45" stroke-dasharray="283" stroke-dashoffset="<?php echo (283 - (283 * (isset($data['user']->success_rate) ? $data['user']->success_rate : 95) / 100)); ?>" />
                                            </svg>
                                            <div class="radial-progress-label"><?php echo isset($data['user']->success_rate) ? $data['user']->success_rate : '95'; ?>%</div>
                                        </div>
                                        <div class="stat-label">Success</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Available Balance -->
                            <div class="balance-box">
                                <div class="balance-header">
                                    <div>
                                        <div class="balance-label">Available balance</div>
                                        <div class="balance-amount">$<?php echo isset($data['user']->available_balance) ? number_format($data['user']->available_balance, 2) : '895.40'; ?></div>
                                    </div>
                                    <button class="shadcn-btn shadcn-btn-outline shadcn-btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 12c0 3.31-2.69 6-6 6H9a6 6 0 0 1-6-6c0-3.31 2.69-6 6-6h6c3.31 0 6 2.69 6 6z"/><path d="M9 12h6"/><path d="m11 14 2-2-2-2"/></svg>
                                        Withdraw
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Connects Section - Redesigned with Shadcn UI -->
                <div class="shadcn-card sidebar-card connects-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M4 9h16"/><path d="M4 15h16"/><path d="m8 5 4 14 4-14"/></svg>
                            Your Connects
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="connects-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="connects-body">
                            <div class="connects-display">
                            <div class="connects-count-box">
                                <div class="connects-count">
                                    <?php echo isset($data['user']->connects) ? (int)$data['user']->connects : 110; ?>
                                </div>
                                <div class="connects-label">Available Connects</div>
                                <div class="connects-refresh">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                    Refreshes on 1st of every month
                                </div>
                            </div>
                        </div>
                    
                        <div class="connects-actions">
                            <button class="shadcn-btn shadcn-btn-primary" id="buy-connects-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Buy More Connects
                            </button>
                            <div class="connects-links">
                                <a href="#" class="shadcn-link" id="connects-details-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                    Connects FAQ
                                </a>
                                <div class="divider"></div>
                                <a href="#" class="shadcn-link" id="free-connects-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                    Get Free Connects
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Availability Status - with Shadcn UI Switch -->
                <div class="shadcn-card sidebar-card status-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M16 22h2a2 2 0 0 0 2-2V7.5L14.5 2H6a2 2 0 0 0-2 2v4"/><polyline points="14 2 14 8 20 8"/><path d="M7.5 15.5A1.5 1.5 0 0 0 9 17H6.5a1.5 1.5 0 0 0 0 3H9"/><path d="M13.5 20.5A1.5 1.5 0 0 0 12 19h2.5a1.5 1.5 0 1 0 0-3H12"/><path d="M3 15.5h2.5"/><path d="M7 13v8"/></svg>
                            Availability Status
                        </h5>
                    </div>
                    <div class="shadcn-card-content collapsed" id="status-body">
                        <div class="availability-status">
                            <div class="switch-item">
                                <div class="switch-label">
                                    <label for="availabilityToggle">Available for work</label>
                                    <p class="switch-description">When enabled, you'll appear in client searches and can receive job invitations</p>
                            </div>
                                <div class="shadcn-switch" data-state="checked" role="switch" aria-checked="true" id="availabilityToggle">
                                    <div class="shadcn-switch-thumb"></div>
                                </div>
                        </div>
                        
                            <div class="workload-selector">
                                <label class="workload-label">Current workload</label>
                                <div class="shadcn-select">
                                    <button class="shadcn-select-trigger" id="workloadTrigger">
                                        <span>30-40 hours/week</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <select class="shadcn-select-native" id="workloadSelect">
                                <option value="less-than-10">Less than 10 hours/week</option>
                                <option value="10-20">10-20 hours/week</option>
                                <option value="20-30">20-30 hours/week</option>
                                <option value="30-40" selected>30-40 hours/week</option>
                                <option value="more-than-40">More than 40 hours/week</option>
                            </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Job Preferences Section - Standardized with Shadcn UI -->
                <div class="shadcn-card sidebar-card preferences-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="M12 2v2"/><path d="M12 22v-2"/><path d="m17 20.66-1-1.73"/><path d="M11 10.27 7 3.34"/><path d="m20.66 17-1.73-1"/><path d="m3.34 7 1.73 1"/><path d="M14 12h8"/><path d="M2 12h2"/><path d="m20.66 7-1.73 1"/><path d="m3.34 17 1.73-1"/><path d="m17 3.34-1 1.73"/><path d="m7 20.66-1-1.73"/></svg>
                            Preferences
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="preferences-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="preferences-body">
                        <!-- Job notifications toggle -->
                        <div class="preference-item d-flex justify-content-between align-items-center mb-3">
                            <div class="preference-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                Job notifications
                            </div>
                            <div class="shadcn-switch" data-state="checked" role="switch" aria-checked="true" id="jobNotificationsSwitch">
                                <div class="shadcn-switch-thumb"></div>
                            </div>
                        </div>
                        
                        <!-- Job visibility option -->
                        <div class="preference-item d-flex justify-content-between align-items-center mb-3">
                            <div class="preference-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                Profile visibility
                            </div>
                            <div class="shadcn-switch" data-state="checked" role="switch" aria-checked="true" id="profileVisibilitySwitch">
                                <div class="shadcn-switch-thumb"></div>
                            </div>
                        </div>
                        
                        <!-- Email notifications -->
                        <div class="preference-item d-flex justify-content-between align-items-center">
                            <div class="preference-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                Email updates
                            </div>
                            <div class="shadcn-switch" data-state="checked" role="switch" aria-checked="true" id="emailNotificationSwitch">
                                <div class="shadcn-switch-thumb"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Skills Showcase - Standardized with Shadcn UI -->
                <div class="shadcn-card sidebar-card skills-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                            Top Skills
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="skills-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="skills-body">
                        <div class="skills-showcase">
                            <?php
                            $topSkills = isset($data['user']->skills) ? $data['user']->skills : ['PHP', 'JavaScript', 'React', 'MySQL', 'HTML/CSS'];
                            if(is_string($topSkills)) {
                                $topSkills = explode(',', $topSkills);
                            }
                            foreach($topSkills as $skill):
                            ?>
                            <div class="skill-item">
                                <div class="d-flex justify-content-between">
                                    <span class="skill-name"><?php echo htmlspecialchars($skill); ?></span>
                                    <span class="skill-match"><?php echo rand(5, 30); ?> matching jobs</span>
                                </div>
                                <div class="skill-level-bar">
                                    <div class="skill-level" style="width: <?php echo rand(75, 98); ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <button class="shadcn-btn shadcn-btn-outline skill-test-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                Take skill tests
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Job Matching - Standardized with Shadcn UI -->
                <div class="shadcn-card sidebar-card job-matching-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            Job Matching
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="job-matching-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="job-matching-body">
                        <div class="matching-analysis">
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <div>
                                    <h6 class="mb-0">Profile Strength</h6>
                                    <p class="text-muted small mb-0">Improves job matching</p>
                                </div>
                                <div class="shadcn-badge shadcn-badge-success">Strong</div>
                            </div>
                            
                            <div class="matching-stats">
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <div class="shadcn-stat-card">
                                            <div class="stat-number"><?php echo rand(85, 95); ?>%</div>
                                            <div class="stat-label small">Profile Match</div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="shadcn-stat-card">
                                            <div class="stat-number"><?php echo rand(75, 120); ?></div>
                                            <div class="stat-label small">Job Alerts</div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="shadcn-stat-card">
                                            <div class="stat-number"><?php echo rand(5, 15); ?></div>
                                            <div class="stat-label small">Invitations</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="improve-matching mt-3">
                                <h6 class="mb-2">Improve your matching</h6>
                                <ul class="shadcn-list ps-3 mb-0 small">
                                    <li class="shadcn-list-item">Complete your profile introduction</li>
                                    <li class="shadcn-list-item">Add portfolio samples</li>
                                    <li class="shadcn-list-item">Take skill certification tests</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Earnings Tracker - Standardized with Shadcn UI -->
                <div class="shadcn-card sidebar-card earnings-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                            Earnings Tracker
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="earnings-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="earnings-body">
                        <div class="earnings-chart-container mb-3">
                            <canvas id="earningsChart" width="100%" height="200"></canvas>
                        </div>
                        
                        <div class="earnings-summary">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="shadcn-summary-label">This Week</div>
                                    <div class="shadcn-summary-value">$<?php echo rand(300, 800); ?></div>
                                </div>
                                <div>
                                    <div class="shadcn-summary-label">This Month</div>
                                    <div class="shadcn-summary-value">$<?php echo rand(1200, 3500); ?></div>
                                </div>
                                <div>
                                    <div class="shadcn-summary-label">Year to Date</div>
                                    <div class="shadcn-summary-value">$<?php echo rand(15000, 50000); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="shadcn-btn shadcn-btn-outline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                                View full report
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Deadlines - Standardized with Shadcn UI -->
                <div class="shadcn-card sidebar-card deadlines-card mb-4">
                    <div class="shadcn-card-header">
                        <h5 class="shadcn-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Upcoming Deadlines
                        </h5>
                        <button class="shadcn-btn shadcn-btn-ghost shadcn-btn-sm" data-collapser="deadlines-body" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="collapser-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <div class="shadcn-card-content collapsed" id="deadlines-body">
                        <?php if(isset($data['upcoming_deadlines']) && !empty($data['upcoming_deadlines'])): ?>
                            <ul class="shadcn-list deadlines-list">
                                <?php foreach($data['upcoming_deadlines'] as $deadline): ?>
                                    <li class="deadline-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="deadline-title"><?php echo htmlspecialchars($deadline->title); ?></div>
                                                <div class="deadline-project small text-muted"><?php echo htmlspecialchars($deadline->project); ?></div>
                                            </div>
                                            <div class="deadline-date <?php echo strtotime($deadline->date) < strtotime('+2 days') ? 'text-danger' : 'text-primary'; ?>">
                                                <?php echo date('M d', strtotime($deadline->date)); ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2 text-primary"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/><path d="m9 14 2 2 4-4"/></svg>
                                <p class="mb-0">No upcoming deadlines</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Project Stats - New Feature -->
                <div class="card sidebar-card project-stats-card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--light-bg);">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i>Project Statistics
                        </h5>
                        <button class="btn btn-sm btn-link p-0 card-toggle" data-target="project-stats-body" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="card-body collapsed" id="project-stats-body" style="background-color: var(--light-bg);">
                        <div class="project-stats-grid">
                            <div class="stats-card mb-3 p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="stats-label">Win Rate</span>
                                    <span class="badge bg-success">Above Average</span>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <span class="stats-value"><?php echo rand(60, 85); ?>%</span>
                                    <span class="small ms-2 text-success"><i class="fas fa-arrow-up"></i> <?php echo rand(3, 8); ?>%</span>
                                </div>
                                <div class="stats-description small text-muted">
                                    Based on your last <?php echo rand(15, 30); ?> proposals
                                </div>
                            </div>
                            
                            <div class="stats-card mb-3 p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="stats-label">Avg. Response Time</span>
                                    <span class="badge bg-success">Excellent</span>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <span class="stats-value"><?php echo rand(1, 3); ?>h</span>
                                    <span class="small ms-2 text-success"><i class="fas fa-arrow-down"></i> 15min</span>
                                </div>
                                <div class="stats-description small text-muted">
                                    Better than <?php echo rand(80, 95); ?>% of freelancers
                                </div>
                            </div>
                            
                            <div class="stats-card mb-3 p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="stats-label">Client Retention</span>
                                </div>
                                <div class="d-flex align-items-baseline">
                                    <span class="stats-value"><?php echo rand(75, 90); ?>%</span>
                                </div>
                                <div class="stats-description small text-muted">
                                    <?php echo rand(4, 8); ?> returning clients this month
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chart-line me-1"></i> View detailed analytics
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Bid Recommendation Tool - New Feature -->
                <div class="card sidebar-card bid-recommendation-card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--light-bg);">
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave text-primary me-2"></i>Bid Assistant
                        </h5>
                        <span class="badge bg-primary">New</span>
                    </div>
                    <div class="card-body collapsed" id="bid-recommendation-body" style="background-color: var(--light-bg);">
                        <p class="small mb-3">Get AI-powered bid recommendations based on job details, market rates, and your profile.</p>
                        
                        <div class="bid-recommendation-tool p-3 rounded">
                            <div class="input-group mb-3">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="hourlyRateInput" placeholder="Your hourly rate" value="35">
                                <span class="input-group-text">/hr</span>
                            </div>
                            
                            <div class="mb-3">
                                <label for="jobComplexitySelect" class="form-label small fw-bold">Job complexity</label>
                                <select class="form-select form-select-sm" id="jobComplexitySelect">
                                    <option value="simple">Simple - Straightforward task</option>
                                    <option value="medium" selected>Medium - Some complexity</option>
                                    <option value="complex">Complex - Specialized expertise</option>
                                    <option value="expert">Expert - High-level expertise</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-sm btn-primary w-100" id="generateBidBtn">
                                <i class="fas fa-calculator me-1"></i> Generate bid recommendation
                            </button>
                        </div>
                        
                        <div class="bid-result mt-3 p-3 rounded d-none" id="bidResult">
                            <div class="mb-3">
                                <div class="small fw-bold mb-1">Recommended bid range:</div>
                                <div class="d-flex justify-content-between">
                                    <span class="bid-range-value">$30 - $45/hr</span>
                                    <span class="text-success">Competitive</span>
                                </div>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="small text-muted">Too low</span>
                                    <span class="small text-muted">Too high</span>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <div class="small fw-bold">Rationale:</div>
                                <p class="small mb-0">This rate positions you competitively for this job type while reflecting your expertise level and profile strength.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Add CSS for hover effect and collapsed state -->
                <style>
                    /* Shadcn UI styling for all cards */
                    .shadcn-card {
                        border-radius: 0.5rem;
                        background-color: white;
                        border: 1px solid rgba(80, 102, 144, 0.15);
                        overflow: hidden;
                        box-shadow: 0 1px 3px rgba(80, 102, 144, 0.06);
                    }
                    
                    .shadcn-card-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 1rem;
                        background-color: rgba(247, 250, 252, 0.8);
                        border-bottom: 1px solid rgba(80, 102, 144, 0.08);
                    }
                    
                    .shadcn-card-title {
                        display: flex;
                        align-items: center;
                        font-size: 1rem;
                        font-weight: 600;
                        margin: 0;
                        color: #384968;
                    }
                    
                    .shadcn-card-title svg,
                    .shadcn-card-title .mr-2 {
                        margin-right: 0.5rem;
                        color: #506690;
                    }
                    
                    .shadcn-card-content {
                        padding: 1rem;
                    }
                    
                    .shadcn-btn {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 0.375rem;
                        font-weight: 500;
                        padding: 0.5rem 1rem;
                        transition: all 0.2s ease;
                        cursor: pointer;
                    }
                    
                    .shadcn-btn-ghost {
                        background: transparent;
                        border: none;
                    }
                    
                    .shadcn-btn-ghost:hover {
                        background-color: rgba(0, 0, 0, 0.05);
                    }
                    
                    .shadcn-btn-sm {
                        padding: 0.25rem 0.5rem;
                        font-size: 0.875rem;
                    }
                    
                    .shadcn-btn-outline {
                        background: transparent;
                        border: 1px solid #506690;
                        color: #506690;
                        padding: 0.5rem 1rem;
                        font-size: 0.875rem;
                    }
                    
                    .shadcn-btn-outline:hover {
                        background-color: rgba(80, 102, 144, 0.05);
                    }
                    
                    .shadcn-btn-primary {
                        background-color: #5a67d8;
                        color: white;
                        border: none;
                    }
                    
                    .shadcn-btn-primary:hover {
                        background-color: #4c51bf;
                    }
                    
                    .shadcn-switch {
                        width: 42px;
                        height: 24px;
                        background-color: rgba(0, 0, 0, 0.2);
                        border-radius: 999px;
                        position: relative;
                        transition: background-color 0.2s ease;
                        cursor: pointer;
                    }
                    
                    .shadcn-switch[data-state="checked"] {
                        background-color: #5a67d8;
                    }
                    
                    .shadcn-switch-thumb {
                        width: 20px;
                        height: 20px;
                        background-color: white;
                        border-radius: 50%;
                        position: absolute;
                        top: 2px;
                        left: 2px;
                        transition: transform 0.2s ease;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                    }
                    
                    .shadcn-switch[data-state="checked"] .shadcn-switch-thumb {
                        transform: translateX(18px);
                    }
                    
                    .shadcn-badge {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 9999px;
                        padding: 0.25rem 0.5rem;
                        font-size: 0.75rem;
                        font-weight: 500;
                    }
                    
                    .shadcn-badge-success {
                        background-color: rgba(104, 211, 145, 0.1);
                        color: #3c9d50;
                    }
                    
                    .shadcn-stat-card {
                        background-color: rgba(247, 250, 252, 0.8);
                        border-radius: 0.5rem;
                        padding: 0.75rem;
                        text-align: center;
                        box-shadow: 0 1px 2px rgba(80, 102, 144, 0.08);
                        border: 1px solid rgba(80, 102, 144, 0.12);
                    }
                    
                    .shadcn-stat-card .stat-number {
                        font-size: 1.25rem;
                        font-weight: 600;
                        color: #384968;
                    }
                    
                    .shadcn-list {
                        list-style-type: none;
                        padding-left: 0;
                    }
                    
                    .shadcn-list-item {
                        position: relative;
                        padding-left: 1.5rem;
                        margin-bottom: 0.5rem;
                    }
                    
                    .shadcn-list-item:before {
                        content: "";
                        position: absolute;
                        left: 0;
                        top: 0.5rem;
                        width: 0.5rem;
                        height: 0.5rem;
                        border-radius: 50%;
                        background-color: #506690;
                    }
                    
                    .shadcn-summary-label {
                        font-size: 0.875rem;
                        color: #6b7280;
                    }
                    
                    .shadcn-summary-value {
                        font-size: 1.125rem;
                        font-weight: 600;
                        color: #384968;
                    }
                    
                    /* Grid utilities */
                    .grid {
                        display: grid;
                    }
                    
                    .grid-cols-3 {
                        grid-template-columns: repeat(3, minmax(0, 1fr));
                    }
                    
                    .gap-3 {
                        gap: 0.75rem;
                    }
                    
                    /* Collapsed state styling */
                    .collapsed {
                        max-height: 0;
                        opacity: 0;
                        overflow: hidden;
                        transform: translateY(-10px);
                        margin: 0;
                        padding-top: 0 !important;
                        padding-bottom: 0 !important;
                    }
                    
                    /* Hover effect for cards */
                    .sidebar-card:hover .collapsed,
                    .shadcn-card:hover .collapsed {
                        max-height: 1000px; /* Large enough for all content */
                        opacity: 1;
                        transform: translateY(0);
                        margin: inherit;
                        padding: 1rem !important;
                    }
                    
                    /* Fix for focus states */
                    .sidebar-card:focus-within .collapsed,
                    .shadcn-card:focus-within .collapsed {
                        max-height: 1000px;
                        opacity: 1;
                        transform: translateY(0);
                        margin: inherit;
                        padding: 1rem !important;
                    }
                    
                    /* Dynamic transition */
                    .shadcn-card-content {
                        transition: max-height 400ms cubic-bezier(0.4, 0, 0.2, 1),
                                  opacity 350ms cubic-bezier(0.4, 0, 0.2, 1),
                                  transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1),
                                  margin 350ms ease,
                                  padding 350ms ease;
                        will-change: max-height, opacity, transform;
                    }
                    
                    /* Add subtle shadow effect on hover */
                    .shadcn-card:hover {
                        box-shadow: 0 10px 25px -5px rgba(80, 102, 144, 0.15);
                        transform: translateY(-2px);
                        transition: transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1),
                                  box-shadow 400ms cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    
                    /* Card transition */
                    .shadcn-card {
                        transition: transform 400ms cubic-bezier(0.4, 0, 0.2, 1),
                                  box-shadow 400ms cubic-bezier(0.4, 0, 0.2, 1);
                        position: relative;
                        z-index: 1;
                    }
                    
                    /* Animation for cards that are pinned open */
                    .card-pinned .collapsed {
                        max-height: 1000px !important;
                        opacity: 1 !important;
                        transform: translateY(0) !important;
                        margin: inherit !important;
                        padding: 1rem !important;
                    }
                    
                    /* Add initial entrance animation */
                    .card-animated {
                        animation: cardEntrance 600ms cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                    }
                    
                    @keyframes cardEntrance {
                        from {
                            opacity: 0;
                            transform: translateY(20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    
                    /* Chevron animation */
                    .collapser-chevron {
                        transition: transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1);
                    }
                </style>

                <!-- Add JavaScript to handle toggle buttons correctly -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Update toggle buttons to work with hover effect
                        const toggleButtons = document.querySelectorAll('.card-toggle, [data-collapser]');
                        
                        toggleButtons.forEach(button => {
                            button.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                const targetId = this.getAttribute('data-target') || this.getAttribute('data-collapser');
                                const targetElement = document.getElementById(targetId);
                                
                                if (targetElement) {
                                    // Toggle expanded class instead of just collapsed
                                    targetElement.classList.toggle('collapsed');
                                    
                                    // For toggle buttons that pin the card open
                                    const parentCard = this.closest('.sidebar-card, .shadcn-card');
                                    if (parentCard) {
                                        parentCard.classList.toggle('card-pinned');
                                    }
                                    
                                    // Update button aria state and icon
                                    const isExpanded = !targetElement.classList.contains('collapsed');
                                    this.setAttribute('aria-expanded', isExpanded);
                                    
                                    // Update chevron icon with animation
                                    const chevronIcon = this.querySelector('i, svg');
                                    if (chevronIcon) {
                                        chevronIcon.style.transition = 'transform 400ms cubic-bezier(0.34, 1.56, 0.64, 1)';
                                        
                                        if (isExpanded) {
                                            chevronIcon.classList.remove('fa-chevron-down');
                                            chevronIcon.classList.add('fa-chevron-up');
                                            chevronIcon.style.transform = 'rotate(180deg)';
                                        } else {
                                            chevronIcon.classList.remove('fa-chevron-up');
                                            chevronIcon.classList.add('fa-chevron-down');
                                            chevronIcon.style.transform = 'rotate(0deg)';
                                        }
                                    }
                                }
                            });
                        });
                        
                        // Add animation when cards first load
                        setTimeout(() => {
                            document.querySelectorAll('.sidebar-card, .shadcn-card').forEach(card => {
                                card.classList.add('card-animated');
                            });
                        }, 100);
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Job Details Side Panel - Enhanced with Shadcn UI design -->
<div id="job-details-panel" class="job-details-panel">
    <div class="job-details-content">
        <div id="job-details-loading" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading job details...</p>
        </div>
        <div id="job-details-body" class="d-none">
            <div class="job-details-card shadcn-card">
                <!-- Header with close button -->
                <div class="job-details-header d-flex align-items-center justify-content-between">
                    <h3 id="job-details-title" class="mb-0">Website Development</h3>
                    <button type="button" class="btn-close" id="close-job-details" aria-label="Close"></button>
                </div>

                <!-- Main content area with two columns -->
                <div class="job-details-main">
                    <!-- Left column - Main job information -->
                    <div class="job-details-primary">
                        <!-- Job tags/badges -->
                        <div class="job-tags mb-4">
                            <span class="shadcn-badge shadcn-badge-outline me-2" id="job-type-badge">Fixed Price</span>
                            <span class="shadcn-badge shadcn-badge-outline me-2" id="job-level-badge">Expert</span>
                            <span class="shadcn-badge shadcn-badge-primary me-2" id="job-budget-badge">$1,000-$2,000</span>
                            <span class="shadcn-badge shadcn-badge-outline" id="job-duration-badge">2-4 weeks</span>
                        </div>
                        
                        <!-- Posted time and location -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span id="job-posted-time">Posted 2 days ago</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span id="job-location">Worldwide</span>
                            </div>
                        </div>
                        
                        <!-- Job description area -->
                        <div class="job-description mb-4">
                            <h4 class="mb-3">Job Description</h4>
                            <div id="job-description-content">
                                <p>Looking for an experienced web developer to build a responsive e-commerce website using modern technologies. The ideal candidate will have proven experience with React, Node.js, and database design.</p>
                                
                                <h5 class="mt-4 mb-3">Requirements:</h5>
                                <ul>
                                    <li>5+ years of web development experience</li>
                                    <li>Proficiency in React, Node.js, and MongoDB</li>
                                    <li>Experience with payment gateway integration</li>
                                    <li>Strong portfolio of e-commerce websites</li>
                                    <li>Excellent communication skills</li>
                                </ul>
                                
                                <h5 class="mt-4 mb-3">Deliverables:</h5>
                                <ul>
                                    <li>Fully responsive website with admin dashboard</li>
                                    <li>Payment processing integration</li>
                                    <li>User authentication system</li>
                                    <li>Product catalog with search and filter functionality</li>
                                    <li>Shopping cart and checkout process</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Skills required -->
                        <div class="job-skills mb-4">
                            <h4 class="mb-3">Skills Required</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="skill-badge">React</span>
                                <span class="skill-badge">Node.js</span>
                                <span class="skill-badge">MongoDB</span>
                                <span class="skill-badge">JavaScript</span>
                                <span class="skill-badge">Responsive Design</span>
                                <span class="skill-badge">E-commerce</span>
                            </div>
                        </div>
                        
                        <!-- Client information -->
                        <div class="client-info mb-4">
                            <h4 class="mb-3">About the Client</h4>
                            <div class="client-stats">
                                <div class="client-stat">
                                    <div class="stat-label">Member since</div>
                                    <div class="stat-value">March 2021</div>
                                </div>
                                <div class="client-stat">
                                    <div class="stat-label">Jobs posted</div>
                                    <div class="stat-value">24 jobs</div>
                                </div>
                                <div class="client-stat">
                                    <div class="stat-label">Hire rate</div>
                                    <div class="stat-value">85%</div>
                                </div>
                                <div class="client-stat">
                                    <div class="stat-label">Total spent</div>
                                    <div class="stat-value">$15,200</div>
                                </div>
                            </div>
                            
                            <div class="client-rating d-flex align-items-center mt-3">
                                <div class="rating-stars me-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                </div>
                                <span class="rating-value">4.5</span>
                                <span class="rating-count text-muted ms-2">(18 reviews)</span>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="job-actions d-flex gap-3">
                            <button class="shadcn-btn shadcn-btn-outline" id="job-save-btn" data-job-id="1">
                                <i class="far fa-heart me-2"></i> Save Job
                            </button>
                            <button class="shadcn-btn shadcn-btn-outline" id="job-report-btn" data-job-id="1">
                                <i class="far fa-flag me-2"></i> Report
                            </button>
                        </div>
                    </div>
                    
                    <!-- Right column - Sidebar with additional info -->
                    <div class="job-details-sidebar">
                        <!-- Similar Jobs Section -->
                        <div class="sidebar-section">
                            <h4 class="mb-3">Similar Jobs</h4>
                            <div class="similar-jobs">
                                <div class="similar-job-item">
                                    <a href="#" class="similar-job-title">WordPress Developer for E-commerce Site</a>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="text-muted small">Fixed Price</span>
                                        <span class="text-primary fw-medium">$800</span>
                                    </div>
                                </div>
                                <div class="similar-job-item">
                                    <a href="#" class="similar-job-title">Front-end Developer for Website Redesign</a>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="text-muted small">Hourly</span>
                                        <span class="text-primary fw-medium">$25-35/hr</span>
                                    </div>
                                </div>
                                <div class="similar-job-item">
                                    <a href="#" class="similar-job-title">E-commerce Platform Migration Expert</a>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="text-muted small">Fixed Price</span>
                                        <span class="text-primary fw-medium">$1,200</span>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="#" class="shadcn-link">View More Similar Jobs</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Proposal Tips Section -->
                        <div class="sidebar-section">
                            <h4 class="mb-3">Proposal Tips</h4>
                            <ul class="tips-list ps-3 mb-0">
                                <li class="mb-2">Highlight relevant experience for this job</li>
                                <li class="mb-2">Include specific examples of past work</li>
                                <li class="mb-2">Address client requirements directly</li>
                                <li>Ask clarifying questions if needed</li>
                            </ul>
                        </div>
                        
                        <!-- AI Proposal Assistant Section -->
                        <div class="sidebar-section">
                            <div class="ai-proposal-assistant p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">AI Proposal Assistant</h4>
                                    <span class="shadcn-badge shadcn-badge-primary">New</span>
                                </div>
                                <p>Let AI help you craft a winning proposal based on your skills and the job requirements.</p>
                                <button class="shadcn-btn shadcn-btn-primary w-100">
                                    <i class="fas fa-magic me-2"></i> Generate proposal draft
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Overlay -->
<div id="panel-overlay" class="panel-overlay"></div>

<!-- Toast Container -->
<div class="shadcn-toast-viewport" id="toast-viewport"></div>

<!-- Custom Styles -->
<style>
    /* Shadcn UI Color System - HSL Color Variables */
    :root {
        /* Base colors */
        --background: 0 0% 100%;
        --foreground: 222.2 84% 4.9%;
        
        /* Card colors */
        --card: 0 0% 100%;
        --card-foreground: 222.2 84% 4.9%;
        
        /* Popover colors */
        --popover: 0 0% 100%;
        --popover-foreground: 222.2 84% 4.9%;
        
        /* Primary colors */
        --primary: 221.2 83.2% 53.3%;
        --primary-foreground: 210 40% 98%;
        
        /* Secondary colors */
        --secondary: 210 40% 96.1%;
        --secondary-foreground: 222.2 47.4% 11.2%;
        
        /* Muted colors */
        --muted: 210 40% 96.1%;
        --muted-foreground: 215.4 16.3% 46.9%;
        
        /* Accent colors */
        --accent: 210 40% 96.1%;
        --accent-foreground: 222.2 47.4% 11.2%;
        
        /* Destructive colors */
        --destructive: 0 84.2% 60.2%;
        --destructive-foreground: 210 40% 98%;
        
        /* Border & Input colors */
        --border: 214.3 31.8% 91.4%;
        --input: 214.3 31.8% 91.4%;
        
        /* Ring color */
        --ring: 221.2 83.2% 53.3%;
        
        /* Radius values */
        --radius: 0.5rem;
        

        
        /* Animation */
        --transition-fast: 0.15s;
        --transition-normal: 0.3s;
        --transition-slow: 0.5s;
        
        /* Shadow values */
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --shadow-inner: inset 0 2px 4px 0 rgb(0 0 0 / 0.05);
        
        /* Legacy variables - mapping to new system */
        --primary-color: hsl(var(--primary));
        --primary-hover: hsl(var(--primary) / 0.9);
        --secondary-color: hsl(var(--secondary));
        --secondary-hover: hsl(var(--secondary) / 0.9);
        --accent-color: hsl(var(--accent));
        --accent-hover: hsl(var(--accent) / 0.9);
        --light-bg: hsl(var(--background));
        --border-color: hsl(var(--border));
        --dark-blue: hsl(217, 60%, 16%);
        --medium-blue: hsl(var(--primary));
        --text-dark: hsl(var(--foreground));
        --text-muted: hsl(var(--muted-foreground));
    }

    /* Dark Mode has been removed */

    /* Global Typography & Font System */
    @font-face {
        font-family: 'Inter var';
        font-weight: 100 900;
        font-display: swap;
        font-style: normal;
        font-named-instance: 'Regular';
        src: url('<?php echo URL_ROOT; ?>/public/fonts/inter/Inter-roman.var.woff2') format('woff2');
    }
    
    html {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    
    body {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: hsl(var(--foreground));
        background-color: hsl(var(--background));
        transition: background-color 0.3s ease, color 0.3s ease;
    }


    
    /* Shadcn UI Card Component */
    .shadcn-card {
        border-radius: var(--radius);
        background-color: hsl(var(--card));
        color: hsl(var(--card-foreground));
        border: 1px solid hsl(var(--border));
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .shadcn-card:hover {
        box-shadow: var(--shadow-md);
    }
    
    .shadcn-card-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .shadcn-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--card-foreground));
        margin: 0;
    }
    
    .shadcn-card-description {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        margin-top: 0.25rem;
    }
    
    .shadcn-card-content {
        padding: 1.5rem;
    }
    
    .shadcn-card-footer {
        padding: 1.5rem;
        border-top: 1px solid hsl(var(--border));
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Shadcn UI Button Component */
    .shadcn-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: calc(var(--radius) - 2px);
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1.25rem;
        padding: 0.5rem 1rem;
        transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }
    
    .shadcn-btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .shadcn-btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .shadcn-btn-primary {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
        border: 1px solid transparent;
    }
    
    .shadcn-btn-primary:hover {
        background-color: hsl(var(--primary) / 0.9);
    }
    
    .shadcn-btn-secondary {
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        border: 1px solid transparent;
    }
    
    .shadcn-btn-secondary:hover {
        background-color: hsl(var(--secondary) / 0.9);
    }
    
    .shadcn-btn-outline {
        background-color: transparent;
        border: 1px solid hsl(var(--border));
        color: hsl(var(--foreground));
    }
    
    .shadcn-btn-outline:hover {
        background-color: hsl(var(--muted));
        color: hsl(var(--muted-foreground));
    }
    
    .shadcn-btn-ghost {
        background-color: transparent;
        border: none;
        color: hsl(var(--foreground));
    }
    
    .shadcn-btn-ghost:hover {
        background-color: hsl(var(--muted));
    }
    
    .shadcn-btn-destructive {
        background-color: hsl(var(--destructive));
        color: hsl(var(--destructive-foreground));
        border: 1px solid transparent;
    }
    
    .shadcn-btn-destructive:hover {
        background-color: hsl(var(--destructive) / 0.9);
    }
    
    /* Shadcn UI Badge Component */
    .shadcn-badge {
        display: inline-flex;
        align-items: center;
        border-radius: calc(var(--radius) / 2);
        font-weight: 500;
        font-size: 0.75rem;
        line-height: 1rem;
        padding: 0.125rem 0.5rem;
    }
    
    .shadcn-badge-primary {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
    }
    
    .shadcn-badge-secondary {
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
    }
    
    .shadcn-badge-outline {
        background-color: transparent;
        border: 1px solid hsl(var(--border));
        color: hsl(var(--foreground));
    }
    
    .shadcn-badge-destructive {
        background-color: hsl(var(--destructive));
        color: hsl(var(--destructive-foreground));
    }
    
    /* Shadcn Input Component */
    .shadcn-input {
        display: flex;
        height: 2.5rem;
        width: 100%;
        border-radius: var(--radius);
        border: 1px solid hsl(var(--input));
        background-color: transparent;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: hsl(var(--foreground));
        transition: border-color 0.15s ease;
    }
    
    .shadcn-input:focus {
        outline: none;
        box-shadow: 0 0 0 2px hsl(var(--background)), 0 0 0 4px hsl(var(--ring));
        border-color: hsl(var(--ring));
    }
    
    .shadcn-input-sm {
        height: 2rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .shadcn-input-lg {
        height: 3rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    /* Shadcn UI Avatar Component */
    .shadcn-avatar {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 9999px;
        width: 2.5rem;
        height: 2.5rem;
        background-color: hsl(var(--muted));
    }
    
    .shadcn-avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .shadcn-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1;
        color: hsl(var(--foreground));
    }
    
    .shadcn-avatar-sm {
        width: 1.5rem;
        height: 1.5rem;
    }
    
    .shadcn-avatar-lg {
        width: 3.5rem;
        height: 3.5rem;
    }
    
    .shadcn-avatar-xl {
        width: 5rem;
        height: 5rem;
    }
    
    /* Shadcn UI Tabs Component */
    .shadcn-tabs {
        display: flex;
        flex-direction: column;
    }
    
    .shadcn-tabs-list {
        display: flex;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .shadcn-tabs-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.5rem;
        padding: 0 1rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
        color: hsl(var(--muted-foreground));
        transition: color 0.15s ease, border-color 0.15s ease;
        border-bottom: 2px solid transparent;
        cursor: pointer;
    }
    
    .shadcn-tabs-trigger:hover {
        color: hsl(var(--foreground));
    }
    
    .shadcn-tabs-trigger[data-state="active"] {
        color: hsl(var(--foreground));
        border-bottom-color: hsl(var(--primary));
    }
    
    .shadcn-tabs-content {
        padding: 1.5rem 0;
    }
    
    /* Shadcn UI Switch Component */
    .shadcn-switch {
        display: inline-flex;
        align-items: center;
        width: 2.25rem;
        height: 1.25rem;
        background-color: hsl(var(--input));
        border-radius: 9999px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-switch[data-state="checked"] {
        background-color: hsl(var(--primary));
    }
    
    .shadcn-switch-thumb {
        display: block;
        width: 0.875rem;
        height: 0.875rem;
        background-color: hsl(var(--background));
        border-radius: 9999px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: transform 0.15s ease;
        transform: translateX(0.225rem);
    }
    
    .shadcn-switch[data-state="checked"] .shadcn-switch-thumb {
        transform: translateX(1.125rem);
    }
    
    /* Shadcn UI Command Component */
    .shadcn-command {
        display: flex;
        flex-direction: column;
        border-radius: var(--radius);
        background-color: hsl(var(--popover));
        border: 1px solid hsl(var(--border));
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    
    .shadcn-command-input {
        display: flex;
        align-items: center;
        border-bottom: 1px solid hsl(var(--border));
        padding: 0.75rem 1rem;
    }
    
    .shadcn-command-input-icon {
        color: hsl(var(--muted-foreground));
        margin-right: 0.5rem;
    }
    
    .shadcn-command-input-field {
        flex: 1;
        background-color: transparent;
        border: none;
        color: hsl(var(--foreground));
        font-size: 0.875rem;
        line-height: 1.25rem;
        outline: none;
    }
    
    .shadcn-command-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .shadcn-command-group {
        padding: 0.5rem;
    }
    
    .shadcn-command-group-heading {
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 500;
        color: hsl(var(--muted-foreground));
        padding: 0.25rem 0.75rem;
    }
    
    .shadcn-command-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius);
        color: hsl(var(--foreground));
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-command-item:hover {
        background-color: hsl(var(--secondary));
    }
    
    .shadcn-command-item[data-selected="true"] {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
    }
    
    /* Shadcn UI Dialog Component */
    .shadcn-dialog-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        animation: fadeIn 0.15s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .shadcn-dialog-content {
        position: relative;
        width: 100%;
        max-width: 28rem;
        max-height: 85vh;
        overflow-y: auto;
        border-radius: var(--radius);
        background-color: hsl(var(--card));
        color: hsl(var(--card-foreground));
        box-shadow: var(--shadow-lg);
        animation: dialogShow 0.15s ease;
    }
    
    @keyframes dialogShow {
        from { 
            opacity: 0; 
            transform: translate(-50%, -50%) scale(0.95);
        }
        to { 
            opacity: 1; 
            transform: translate(-50%, -50%) scale(1);
        }
    }
    
    .shadcn-dialog-close {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 9999px;
        background-color: transparent;
        border: none;
        color: hsl(var(--muted-foreground));
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-dialog-close:hover {
        background-color: hsl(var(--secondary));
    }
    
    .shadcn-dialog-header {
        padding: 1.5rem 1.5rem 0 1.5rem;
    }
    
    .shadcn-dialog-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--foreground));
    }
    
    .shadcn-dialog-description {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        margin-top: 0.25rem;
    }
    
    .shadcn-dialog-body {
        padding: 1.5rem;
    }
    
    .shadcn-dialog-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 0 1.5rem 1.5rem 1.5rem;
    }
    
    /* Shadcn UI Toast Component */
    .shadcn-toast-viewport {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 100;
        max-width: 24rem;
    }
    
    .shadcn-toast {
        display: flex;
        flex-direction: column;
        border-radius: var(--radius);
        background-color: hsl(var(--card));
        color: hsl(var(--card-foreground));
        border: 1px solid hsl(var(--border));
        box-shadow: var(--shadow-lg);
        animation: toastSlideIn 0.15s ease;
        overflow: hidden;
    }
    
    @keyframes toastSlideIn {
        from { 
            opacity: 0; 
            transform: translateX(100%);
        }
        to { 
            opacity: 1; 
            transform: translateX(0);
        }
    }
    
    .shadcn-toast-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem 0.5rem 1rem;
    }
    
    .shadcn-toast-title {
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: hsl(var(--foreground));
    }
    
    .shadcn-toast-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        background-color: transparent;
        border: none;
        color: hsl(var(--muted-foreground));
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-toast-close:hover {
        background-color: hsl(var(--muted));
    }
    
    .shadcn-toast-body {
        padding: 0 1rem 0.75rem 1rem;
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
    }
    
    /* Shadcn UI Progress Component */
    .shadcn-progress {
        position: relative;
        height: 0.5rem;
        width: 100%;
        background-color: hsl(var(--secondary));
        border-radius: 9999px;
        overflow: hidden;
    }
    
    .shadcn-progress-indicator {
        height: 100%;
        background-color: hsl(var(--primary));
        transition: width 0.15s ease;
    }
    
    /* Shadcn UI Hover Card Component */
    .shadcn-hover-card {
        position: relative;
    }
    
    .shadcn-hover-card-trigger {
        display: inline-block;
    }
    
    .shadcn-hover-card-content {
        position: absolute;
        z-index: 50;
        width: 300px;
        border-radius: var(--radius);
        background-color: hsl(var(--card));
        color: hsl(var(--card-foreground));
        border: 1px solid hsl(var(--border));
        box-shadow: var(--shadow-lg);
        animation: hoverCardSlideDown 0.15s ease;
        padding: 1rem;
        overflow: hidden;
    }
    
    @keyframes hoverCardSlideDown {
        from { 
            opacity: 0; 
            transform: translateY(-10px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    /* Shadcn UI Drawer Component */
    .shadcn-drawer-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 50;
        animation: fadeIn 0.15s ease;
    }
    
    .shadcn-drawer {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: 24rem;
        max-width: 100vw;
        background-color: hsl(var(--card));
        color: hsl(var(--card-foreground));
        box-shadow: var(--shadow-lg);
        z-index: 60;
        animation: drawerSlideIn 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    @keyframes drawerSlideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    
    .shadcn-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .shadcn-drawer-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--foreground));
    }
    
    .shadcn-drawer-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 9999px;
        background-color: transparent;
        border: none;
        color: hsl(var(--muted-foreground));
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-drawer-close:hover {
        background-color: hsl(var(--secondary));
    }
    
    .shadcn-drawer-body {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
    }
    
    .shadcn-drawer-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 1.5rem;
        border-top: 1px solid hsl(var(--border));
    }
    
    /* Shadcn UI Scroll Area Component */
    .shadcn-scroll-area {
        position: relative;
        overflow: hidden;
        height: 100%;
        width: 100%;
    }
    
    .shadcn-scroll-area-viewport {
        height: 100%;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .shadcn-scroll-area-scrollbar {
        position: absolute;
        top: 0;
        right: 0;
        width: 10px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: opacity 0.15s ease;
    }
    
    .shadcn-scroll-area-thumb {
        position: relative;
        width: 6px;
        margin: 0 auto;
        background-color: hsl(var(--muted-foreground) / 0.3);
        border-radius: 9999px;
    }
    
    /* Radial Progress Component */
    .shadcn-radial-progress {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 1rem;
        line-height: 1;
        color: hsl(var(--foreground));
    }
    
    .shadcn-radial-progress svg {
        position: absolute;
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
    }
    
    .shadcn-radial-progress-bg {
        stroke: hsl(var(--secondary));
        stroke-width: 4;
        fill: none;
    }
    
    .shadcn-radial-progress-indicator {
        stroke: hsl(var(--primary));
        stroke-width: 4;
        fill: none;
        stroke-linecap: round;
        transition: stroke-dashoffset 0.3s ease;
    }
    
    /* Aspect Ratio Component */
    .shadcn-aspect-ratio {
        position: relative;
        width: 100%;
    }
    
    .shadcn-aspect-ratio > * {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    
    /* Accordion Component */
    .shadcn-accordion {
        width: 100%;
        border-radius: var(--radius);
        border: 1px solid hsl(var(--border));
        overflow: hidden;
    }
    
    .shadcn-accordion-item {
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .shadcn-accordion-item:last-child {
        border-bottom: none;
    }
    
    .shadcn-accordion-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 1rem;
        background-color: transparent;
        border: none;
        text-align: left;
        font-weight: 500;
        color: hsl(var(--foreground));
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    
    .shadcn-accordion-trigger:hover {
        background-color: hsl(var(--muted));
    }
    
    .shadcn-accordion-content {
        overflow: hidden;
        padding: 0 1rem;
        height: 0;
        transition: height 0.3s ease, padding 0.3s ease;
    }
    
    .shadcn-accordion-content[data-state="open"] {
        padding: 1rem;
        height: auto;
    }
    
    .shadcn-accordion-icon {
        transition: transform 0.3s ease;
    }
    
    .shadcn-accordion-trigger[data-state="open"] .shadcn-accordion-icon {
        transform: rotate(180deg);
    }
    
    /* Base Styles and Utilities */
    :root {
        --primary-color: #0c3b6b;
        --primary-hover: #06284a;
        --secondary-color: #1e88e5;
        --secondary-hover: #1565c0;
        --accent-color: #00bcd4;
        --accent-hover: #00a5bb;
        --light-bg: #f8fafc;
        --border-color: #e0e7ee;
        --dark-blue: #102a43;
        --medium-blue: #1d4ed8;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 8px rgba(0,0,0,0.1);
        --shadow-lg: 0 8px 16px rgba(0,0,0,0.1);
        --transition-fast: all 0.2s ease;
        --transition-normal: all 0.3s ease;
        --transition-slow: all 0.5s ease;
    }
    
    .card-hover-effect {
        transition: var(--transition-normal);
    }
    
    .card-hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .animate-on-hover {
        transition: var(--transition-fast);
    }
    
    .animate-on-hover:hover {
        transform: scale(1.08);
    }
    
    /* Banner Styles */
    .promo-banner-container {
        width: 100%;
        background-color: #fff;
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .promo-banner-carousel {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .promo-banner {
        background: linear-gradient(135deg, var(--dark-blue), #1e488f);
        color: white;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: var(--shadow-lg);
        transition: var(--transition-slow);
    }
    
    .banner-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
        color: #ffffff;
    }
    
    .banner-subtitle {
        font-size: 1.125rem;
        font-weight: 400;
        margin-bottom: 1.25rem;
        opacity: 0.85;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.95);
    }
    
    .boosted-image {
        max-height: 220px;
        transform: scale(1);
        transition: var(--transition-slow);
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        object-fit: cover;
        filter: brightness(1.05);
    }
    
    .promo-banner:hover .boosted-image {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
    }
    
    .promo-banner .btn {
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        transition: var(--transition-normal);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    .promo-banner .btn-light {
        background: #ffffff;
        border: none;
        color: #164E63;
    }
    
    .promo-banner .btn-light:hover {
        background: #f0f9ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Button Glow Effect */
    .btn-glow {
        position: relative;
        overflow: hidden;
    }
    
    .btn-glow:after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(30deg);
        transition: var(--transition-slow);
        opacity: 0;
    }
    
    .btn-glow:hover:after {
        opacity: 1;
        transform: rotate(30deg) translate(10%, 0%);
    }
    
    /* Image Shine Effect */
    .image-wrapper {
        position: relative;
        z-index: 2;
        overflow: hidden;
    }
    
    .image-shine {
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 100%);
        transform: skewX(-25deg);
        animation: shine 5s infinite;
    }
    
    @keyframes shine {
        0%, 100% { left: -100%; }
        50% { left: 150%; }
    }
    
    .image-wrapper:after {
        content: '';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 80%;
        height: 80%;
        border-radius: 8px;
        background: rgba(0,0,0,0.08);
        filter: blur(20px);
        z-index: -1;
    }
    
    /* Banner Content Animation */
    .banner-content .animate-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s forwards;
    }
    
    .banner-content .animate-in:nth-child(1) { animation-delay: 0.1s; }
    .banner-content .animate-in:nth-child(2) { animation-delay: 0.2s; }
    .banner-content .animate-in:nth-child(3) { animation-delay: 0.3s; }
    .banner-content .animate-in:nth-child(4) { animation-delay: 0.4s; }
    .banner-content .animate-in:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .banner-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding-bottom: 16px;
        position: relative;
        z-index: 10;
    }
    
    .progress-dots {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.35);
        display: inline-block;
        cursor: pointer;
        transition: var(--transition-normal);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .dot:hover {
        background-color: rgba(255, 255, 255, 0.7);
    }
    
    .dot.active {
        background-color: white;
        transform: scale(1.4);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.15);
    }
    
    .banner-nav-buttons {
        display: flex;
        gap: 20px;
    }
    
    .btn-prev-banner, 
    .btn-next-banner {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-normal);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    
    .btn-prev-banner:hover, 
    .btn-next-banner:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Timer styling */
    .promo-timer {
        background: rgba(0, 0, 0, 0.15);
        padding: 10px 18px;
        border-radius: 30px;
        font-weight: 500;
        letter-spacing: 0.5px;
        display: inline-block;
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .promo-timer span {
        background: rgba(255, 255, 255, 0.15);
        padding: 4px 8px;
        border-radius: 6px;
        margin: 0 2px;
        font-weight: 700;
        font-family: monospace;
        font-size: 1rem;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    /* Additional Banner Enhancement Styles */
    .promo-banner:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.45rem 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.7rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    
    /* Job Search - Upwork style */
    .job-search-container {
        max-width: 100%;
        margin: 0 auto;
    }
    
    .job-search-container .input-group {
        position: relative;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: var(--transition-fast);
    }
    
    .job-search-container .input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(20, 168, 0, 0.1);
    }
    
    .search-icon-wrapper {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        color: #6f757b;
    }
    
    .search-input {
        height: 48px;
        padding-left: 45px;
        border: none;
        box-shadow: none;
    }
    
    .search-input:focus {
        box-shadow: none;
        border: none;
        outline: none;
    }
    
    /* Section Title */
    .section-title {
        font-size: 1.5rem;
        font-weight: 500;
    }
    
    /* Tab Navigation */
    .nav-tabs .nav-link {
        color: #5e6d77;
        border: none;
        padding: 0.5rem 0;
        margin-right: 1.5rem;
        font-weight: 500;
        position: relative;
        transition: var(--transition-fast);
    }
    
    .nav-tabs .nav-link:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--primary-color);
        transition: var(--transition-fast);
    }
    
    .nav-tabs .nav-link:hover:after {
        width: 100%;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: transparent;
        border-bottom: none;
    }
    
    .nav-tabs .nav-link.active:after {
        width: 100%;
    }
    
    /* Job Items */
    .job-item {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 0.5rem;
        transition: var(--transition-fast);
    }
    
    .job-item:hover {
        background-color: #f9f9f9;
        box-shadow: var(--shadow-sm);
    }
    
    .job-posted-time {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    .job-title {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        color: var(--text-dark);
    }
    
    .job-title span {
        color: var(--text-dark);
        text-decoration: none;
        transition: var(--transition-fast);
    }
    
    .job-item:hover .job-title span {
        color: var(--primary-color);
    }
    
    .job-details .badge {
        font-weight: normal;
        padding: 0.5rem 0.75rem;
        margin-right: 0.5rem;
        transition: var(--transition-fast);
    }
    
    .job-item:hover .badge {
        background-color: #f2f2f2;
    }
    
    .job-description {
        color: #5e6d77;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .job-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .skill-badge {
        font-weight: normal;
        padding: 0.4rem 0.75rem;
        background-color: #f2f2f2;
        color: #656565;
        transition: var(--transition-fast);
    }
    
    .skill-badge:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Featured Badge */
    .featured-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.7; }
        50% { opacity: 1; }
        100% { opacity: 0.7; }
    }
    
    /* Top Rated Badge */
    .top-rated-badge {
        animation: shine-badge 3s infinite;
        background: linear-gradient(45deg, #28a745, #218838, #28a745);
        background-size: 200% 200%;
    }
    
    @keyframes shine-badge {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    /* Search Animation */
    .job-listings.searching {
        position: relative;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .job-listings.searching::after {
        content: 'Searching...';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 500;
        color: var(--primary-color);
    }
    
    /* Profile Card */
    .profile-card {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-normal);
        background-color: var(--light-bg) !important;
    }
    
    .profile-card .card-body, 
    .profile-card .profile-header {
        background-color: var(--light-bg) !important;
    }
    
    .profile-header {
        background: var(--light-bg);
        color: var(--text-dark);
        border-radius: 0.75rem 0.75rem 0 0;
    }
    
    .profile-name {
        color: var(--text-dark);
        font-weight: 600;
    }
    
    .profile-title {
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .profile-completion-text {
        color: var(--text-muted);
    }
    
    .profile-completion-link {
        color: var(--primary-color);
    }
    
    .profile-completion-link:hover {
        color: var(--primary-hover);
        text-decoration: underline !important;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .progress-bar {
        background-color: var(--primary-color);
    }
    
    .profile-img-lg {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }
    
    /* Online Status Indicator */
    .profile-status-indicator {
        position: absolute;
        bottom: 3px;
        right: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }
    
    .profile-status-indicator.online {
        background-color: #28a745;
    }
    
    .profile-status-indicator.away {
        background-color: #ffc107;
    }
    
    .profile-status-indicator.busy {
        background-color: #dc3545;
    }
    
    .profile-status-indicator.offline {
        background-color: #6c757d;
    }
    
    /* Profile Stats */
    .profile-stats {
        background-color: rgba(13, 59, 107, 0.05);
        padding: 12px;
        border-radius: 8px;
    }
    
    .stat-value {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--primary-color);
    }
    
    .stat-label {
        color: var(--text-muted);
        font-size: 0.8rem;
    }
    
    /* Available Balance */
    .available-balance {
        background: linear-gradient(135deg, rgba(13, 59, 107, 0.05), rgba(29, 78, 216, 0.05));
        border-left: 3px solid var(--primary-color);
    }
    
    .balance-amount {
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--primary-color);
    }
    
    .withdraw-btn {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .withdraw-btn:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    /* Connects Display */
    .connects-display {
        padding: 15px;
        background: linear-gradient(135deg, rgba(13, 59, 107, 0.05), rgba(29, 78, 216, 0.05));
        border-radius: 12px;
        text-align: center;
    }
    
    .connects-count {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1;
        margin-bottom: 5px;
    }
    
    .connects-label {
        font-size: 1rem;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    
    .connects-refresh {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .buy-connects-btn {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .buy-connects-btn:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }
    
    .connects-links a {
        color: var(--primary-color);
        transition: var(--transition-fast);
    }
    
    .connects-links a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
    
    /* Skills Showcase */
    .skills-showcase {
        margin-bottom: 1rem;
    }
    
    .skill-item {
        margin-bottom: 15px;
    }
    
    .skill-name {
        font-size: 0.9rem;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .skill-match {
        font-size: 0.75rem;
        color: var(--primary-color);
    }
    
    .skill-level-bar {
        height: 6px;
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 3px;
        overflow: hidden;
        position: relative;
    }
    
    .skill-level {
        height: 100%;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        border-radius: 3px;
    }
    
    .skill-test-btn {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .skill-test-btn:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    /* Job Matching */
    .matching-stat-card {
        background-color: rgba(13, 59, 107, 0.05);
        border-radius: 8px;
    }
    
    .stat-number {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .improve-list {
        color: var(--text-muted);
    }
    
    .improve-list li {
        margin-bottom: 5px;
    }
    
    /* Earnings Tracker */
    .earnings-summary .summary-value {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    /* Availability Status */
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Air3 Card Styling */
    .air3-card {
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        background-color: #fff;
    }
    
    .air3-card-section {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .air3-card-section:last-child {
        border-bottom: none;
    }
    
    /* Activity Items */
    .activity-item {
        display: flex;
        padding: 10px 0;
    }
    
    .activity-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 59, 107, 0.08);
        border-radius: 50%;
    }
    
    /* Client Info */
    .client-info-item {
        padding: 8px 0;
    }
    
    .info-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 59, 107, 0.08);
        border-radius: 50%;
    }
    
    .info-label {
        font-size: 0.8rem;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    /* Similar Jobs */
    .similar-job-item {
        padding: 10px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .similar-job-item:last-child {
        border-bottom: none;
    }
    
    .similar-job-title {
        color: var(--primary-color);
        font-weight: 500;
        margin-bottom: 5px;
        transition: var(--transition-fast);
        text-decoration: none;
    }
    
    .similar-job-title:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
    
    /* Job Details Panel */
    .job-details-panel {
        position: fixed;
        top: 0;
        right: -800px;
        width: 800px;
        max-width: 90vw;
        height: 100vh;
        background-color: #fff;
        z-index: 1050;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
        transition: right 0.3s ease-in-out;
        overflow-y: auto;
        padding: 0;
    }
    
    .job-details-content {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .job-details-card {
        padding: 2rem;
        border-radius: 0;
        border: none;
        box-shadow: none;
        height: 100%;
        overflow-y: auto;
    }
    
    .job-details-header {
        padding-bottom: 1.5rem;
        border-bottom: 1px solid hsl(var(--border));
        margin-bottom: 1.5rem;
    }
    
    .job-details-main {
        display: flex;
        gap: 2rem;
    }
    
    .job-details-primary {
        flex: 1;
        padding-right: 2rem;
        border-right: 1px solid hsl(var(--border));
    }
    
    .job-details-sidebar {
        width: 300px;
    }
    
    .sidebar-section {
        padding-bottom: 2rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .sidebar-section:last-child {
        padding-bottom: 0;
        margin-bottom: 0;
        border-bottom: none;
    }
    
    .similar-job-item {
        padding: 1rem 0;
        border-bottom: 1px solid hsl(var(--border) / 0.5);
    }
    
    .similar-job-item:last-child {
        border-bottom: none;
    }
    
    .similar-job-title {
        font-weight: 500;
        color: hsl(var(--foreground));
        text-decoration: none;
        display: block;
        transition: color 0.2s ease;
    }
    
    .similar-job-title:hover {
        color: hsl(var(--primary));
    }
    
    .client-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .client-stat {
        min-width: 120px;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-weight: 600;
        color: hsl(var(--foreground));
    }
    
    .skill-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        border-radius: var(--radius);
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    /* Responsive styles for job details panel */
    @media (max-width: 992px) {
        .job-details-main {
            flex-direction: column;
        }
        
        .job-details-primary {
            padding-right: 0;
            border-right: none;
            border-bottom: 1px solid hsl(var(--border));
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        
        .job-details-sidebar {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .job-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .job-actions .shadcn-btn {
            width: 100%;
        }
        
        .client-stats {
            gap: 1rem;
        }
        
        .client-stat {
            min-width: 45%;
        }
    }
    
    .job-details-panel.active {
        right: 0;
    }
    
    .panel-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
    }
    
    .panel-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    /* Pagination */
    .pagination .page-link {
        color: var(--primary-color);
        border-color: var(--border-color);
        transition: var(--transition-fast);
    }
    
    .pagination .page-link:hover {
        background-color: rgba(13, 59, 107, 0.05);
        border-color: var(--border-color);
        color: var(--primary-color);
    }
    
    .pagination .active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* AI Proposal Assistant */
    .ai-proposal-assistant {
        background-color: rgba(13, 59, 107, 0.03);
        border-radius: 8px;
        padding: 15px;
    }
    
    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-container {
        background-color: hsl(var(--background));
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    
    .modal-large {
        max-width: 800px;
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .modal-close {
        background: transparent;
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }
    
    .modal-close:hover {
        background-color: hsl(var(--secondary));
    }
    
    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        border-top: 1px solid hsl(var(--border));
    }
    
    .modal-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .modal-button-primary {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
        border: 1px solid transparent;
    }
    
    .modal-button-primary:hover {
        background-color: hsl(var(--primary) / 0.9);
        transform: translateY(-1px);
    }
    
    .modal-button-secondary {
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        border: 1px solid hsl(var(--border));
    }
    
    .modal-button-secondary:hover {
        background-color: hsl(var(--secondary) / 0.8);
    }
    
    .modal-button-danger {
        background-color: hsl(var(--destructive));
        color: hsl(var(--destructive-foreground));
        border: 1px solid transparent;
    }
    
    .modal-button-danger:hover {
        background-color: hsl(var(--destructive) / 0.9);
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    /* Card Styling */
    .card {
        border: none;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-normal);
    }
    
    .card-header {
        background-color: var(--light-bg);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 0.75rem 1.25rem;
    }
    
    .card-toggle {
        color: var(--primary-color);
        transition: transform 0.3s ease;
        background: transparent;
        border: none;
    }
    
    .card-toggle:hover {
        color: var(--primary-hover);
    }
    
    .card-toggle[aria-expanded="false"] i {
        transform: rotate(180deg);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .banner-title {
            font-size: 1.5rem;
        }
        
        .banner-subtitle {
            font-size: 1rem;
        }
        
        .connects-count {
            font-size: 2rem;
        }
        
        .job-details-panel {
            width: 100vw;
            right: -100vw;
        }
    }
    
    /* Shadcn UI Promo Banner - Clean, Compact Design */
    .promo-banners-wrapper {
        margin-bottom: 2rem;
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        max-width: 100%;
        height: 320px; /* Fixed height for all banners */
    }
    
    .promo-banner-container {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius);
        box-shadow: var(--shadow-md);
        height: 100%;
        background-color: hsl(var(--card));
        border: 1px solid hsl(var(--border));
    }
    
    .promo-carousel {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .promo-banner-content {
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .promo-banner-content[data-banner-id="1"] {
        opacity: 1;
    }
    
    .promo-banner-background {
        position: relative;
        height: 100%;
        background-color: hsl(var(--primary));
        overflow: hidden;
    }
    
    .promo-banner-grid {
        display: grid;
        grid-template-columns: 3fr 2fr;
        height: 100%;
        padding: 0;
        align-items: center;
        margin: 0 auto;
        max-width: 1200px;
    }
    
    .promo-banner-text {
        color: hsl(var(--primary-foreground));
        padding: 2rem;
        z-index: 2;
    }
    
    .promo-banner-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        line-height: 1.2;
        color: hsl(var(--primary-foreground));
    }
    
    .promo-banner-description {
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
        opacity: 0.95;
    }
    
    .promo-badge {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
    }
    
    .promo-timer {
        background: rgba(0, 0, 0, 0.15);
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius);
        display: inline-flex;
        align-items: center;
        font-weight: 500;
        gap: 0.25rem;
        margin-bottom: 1.25rem;
    }
    
    .timer-icon {
        margin-right: 0.25rem;
    }
    
    .timer-unit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.25rem 0.5rem;
        border-radius: calc(var(--radius) / 2);
        font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, monospace;
        font-weight: 600;
        min-width: 2rem;
        text-align: center;
    }
    
    .promo-banner-image {
        position: relative;
        height: 100%;
        overflow: hidden;
    }
    
    .promo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    
    .promo-carousel {
        position: relative;
    }
    
    .promo-banner-nav {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        width: 100%;
        padding: 0 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        z-index: 10;
        opacity: 0; /* Hidden by default */
        pointer-events: none; /* Don't capture events when hidden */
        transition: opacity 0.3s ease;
    }
    
    /* Show navigation when hovering over the carousel */
    .promo-carousel:hover .promo-banner-nav {
        opacity: 1;
        pointer-events: all;
    }
    
    .promo-arrow-btn {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        color: #000; /* Solid black color */
        transition: all 0.25s ease;
        background: rgba(255, 255, 255, 0.7); /* Light background */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 0, 0, 0.1);
        opacity: 0.9;
    }
    
    .promo-arrow-btn svg {
        filter: drop-shadow(0px 1px 1px rgba(0, 0, 0, 0.3)); /* Subtle shadow to the SVG */
        stroke-width: 2.5; /* Make the SVG stroke thicker */
        width: 22px;
        height: 22px;
    }
    
    .promo-arrow-btn:hover,
    .promo-arrow-btn:active,
    .promo-arrow-btn:focus {
        background-color: rgba(255, 255, 255, 0.9);
        color: #000;
        transform: scale(1.05);
        opacity: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border-color: rgba(0, 0, 0, 0.1);
    }
    
    /* Ensure color doesn't change on press */
    .promo-arrow-btn:active {
        transform: scale(1);
    }
    
    /* Add button fade-in animation when carousel is hovered */
    .promo-carousel:hover .promo-arrow-btn {
        animation: fadeIn 0.3s ease forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    /* Responsive styles for promo banner */
    @media (max-width: 992px) {
        .promo-banners-wrapper {
            height: 380px;
        }
        
        .promo-banner-grid {
            grid-template-columns: 1fr;
        }
        
        .promo-banner-text {
            padding: 1.5rem 1.5rem 0;
            text-align: center;
        }
        
        .promo-banner-image {
            height: 180px;
            margin-top: 1rem;
        }
        
        .promo-banner-nav {
            padding: 0 0.75rem;
        }
        
        .promo-arrow-btn {
            width: 2.75rem;
            height: 2.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .promo-banners-wrapper {
            height: 420px;
        }
        
        .promo-banner-title {
            font-size: 1.5rem;
        }
        
        .promo-banner-description {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .promo-timer {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
            gap: 0.125rem;
        }
        
        .timer-unit {
            padding: 0.125rem 0.375rem;
            min-width: 1.5rem;
        }
        
        .promo-banner-nav {
            padding: 0 0.5rem;
        }
        
        .promo-arrow-btn {
            width: 2.25rem;
            height: 2.25rem;
        }
        
                    /* Adjust arrows styling for mobile */
            .promo-arrow-btn {
                width: 2.5rem;
                height: 2.5rem;
                background: rgba(255, 255, 255, 0.8);
            }
            
            .promo-arrow-btn svg {
                width: 18px;
                height: 18px;
            }
            
            /* Always keep arrows visible on mobile for better UX */
            .promo-banner-nav {
                opacity: 0;
            }
            
            /* Only show on hover for devices that support hover */
            @media (hover: hover) {
                .promo-carousel:hover .promo-banner-nav {
                    opacity: 1;
                }
            }
    }
    
    /* Shadcn UI Job Search Command */
    .job-search-command {
        margin-bottom: 1.5rem;
    }
    
    .shadcn-kbd {
        background-color: hsl(var(--muted));
        color: hsl(var(--muted-foreground));
        padding: 0.25rem 0.5rem;
        border-radius: calc(var(--radius) / 2);
        font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, monospace;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    /* Shadcn UI Tabs */
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.5rem;
        height: 1.5rem;
        padding: 0 0.25rem;
        font-size: 0.75rem;
        border-radius: 9999px;
        font-weight: 500;
        background-color: hsl(var(--muted));
        color: hsl(var(--muted-foreground));
        margin-left: 0.5rem;
    }
    
    /* Card hover effect with improved shadow transition */
    .card-hover-effect {
        transition: var(--transition-normal);
    }
    
    .card-hover-effect:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    /* Job Card Shadcn UI Styling */
    .job-card {
        margin-bottom: 1rem;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, border-color 0.2s ease-in-out;
        cursor: pointer;
    }
    
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: hsl(var(--border));
    }
    
    .job-card-content {
        padding: 1.5rem;
    }
    
    .job-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    
    .job-meta-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .job-posted-time {
        display: flex;
        align-items: center;
        color: hsl(var(--muted-foreground));
        font-size: 0.875rem;
        gap: 0.25rem;
    }
    
    .job-icon {
        display: inline-block;
        margin-right: 0.25rem;
    }
    
    .job-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .job-action-btn {
        color: hsl(var(--muted-foreground));
        transition: color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        border-radius: 9999px;
        padding: 0.25rem;
        width: 2rem;
        height: 2rem;
    }
    
    .job-action-btn:hover {
        color: hsl(var(--foreground));
        background-color: hsl(var(--muted));
        transform: translateY(-2px);
    }
    
    .btn-save-job.saved {
        color: hsl(var(--primary));
    }
    
    .job-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: hsl(var(--foreground));
    }
    
    .top-rated-badge {
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .job-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .job-budget {
        font-weight: 500;
    }
    
    .job-description {
        color: hsl(var(--muted-foreground));
        font-size: 0.9375rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .more-link {
        background: none;
        border: none;
        padding: 0;
        color: hsl(var(--primary));
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: color 0.15s ease;
    }
    
    .more-link:hover {
        color: hsl(var(--primary) / 0.8);
        text-decoration: underline;
    }
    
    .job-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .skill-badge {
        display: inline-flex;
        align-items: center;
        border-radius: calc(var(--radius) / 2);
        font-weight: 500;
        font-size: 0.75rem;
        line-height: 1rem;
        padding: 0.25rem 0.5rem;
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
    }
    
    .skill-badge:hover {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
        transform: translateY(-1px);
    }
    
    .job-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid hsl(var(--border));
        padding-top: 1rem;
        margin-top: 0.5rem;
    }
    
    .job-footer .job-apply-btn {
        font-size: 0.875rem;
        padding: 0.4rem 1rem;
    }
    
    .job-footer-stat {
        display: flex;
        align-items: center;
        color: hsl(var(--muted-foreground));
        font-size: 0.875rem;
        gap: 0.375rem;
        margin-right: 1.5rem;
    }
    
    .job-footer > div:first-child {
        display: flex;
    }
    
    /* Empty State */
    .empty-state-card {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .empty-state-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        background-color: hsl(var(--muted));
        color: hsl(var(--muted-foreground));
        border-radius: 9999px;
        margin-bottom: 1.5rem;
    }
    
    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: hsl(var(--foreground));
    }
    
    .empty-state-description {
        color: hsl(var(--muted-foreground));
        margin-bottom: 1.5rem;
        max-width: 30rem;
    }
    
    .refresh-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .mr-2 {
        margin-right: 0.5rem;
    }
    
    /* Pagination */
    .shadcn-pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    .shadcn-pagination-content {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .shadcn-pagination-list {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .shadcn-pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2rem;
        width: 2rem;
        border-radius: var(--radius);
        background-color: transparent;
        color: hsl(var(--foreground));
        font-size: 0.875rem;
        font-weight: 500;
        transition: background-color 0.15s ease, color 0.15s ease;
        border: none;
        cursor: pointer;
    }
    
    .shadcn-pagination-link:hover {
        background-color: hsl(var(--muted));
    }
    
    .shadcn-pagination-link.active {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
    }
    
    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        padding: 0;
        border-radius: var(--radius);
    }
    
    .pagination-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    
    /* Profile Card Shadcn UI Styling */
    .profile-card {
        margin-bottom: 1.5rem;
    }
    
    .profile-card-content {
        padding: 0;
    }
    
    .profile-header {
        padding: 1.5rem;
    }
    
    .profile-info {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .shadcn-avatar {
        position: relative;
        border-radius: 9999px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        background-color: hsl(var(--secondary));
        flex-shrink: 0;
    }
    
    .shadcn-avatar-lg {
        width: 4rem;
        height: 4rem;
    }
    
    .shadcn-avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .shadcn-avatar-fallback {
        font-weight: 600;
        font-size: 1.5rem;
        color: hsl(var(--foreground));
    }
    
    .shadcn-status-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 9999px;
        border: 2px solid hsl(var(--card));
    }
    
    .shadcn-status-badge.online {
        background-color: hsl(142, 76%, 36%);
    }
    
    .shadcn-status-badge.away {
        background-color: hsl(48, 96%, 53%);
    }
    
    .shadcn-status-badge.busy {
        background-color: hsl(0, 72%, 51%);
    }
    
    .shadcn-status-badge.offline {
        background-color: hsl(215, 14%, 34%);
    }
    
    .profile-details {
        flex: 1;
    }
    
    .profile-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        color: hsl(var(--foreground));
    }
    
    .profile-title {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        margin: 0 0 0.5rem 0;
    }
    
    .profile-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .rating-stars {
        display: flex;
        align-items: center;
        color: hsl(41, 92%, 56%);
    }
    
    .star-icon {
        margin-right: 0.125rem;
    }
    
    .rating-count {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
    }
    
    .profile-stats {
        margin-bottom: 1.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    
    .stats-card {
        background-color: hsl(var(--secondary) / 0.5);
        padding: 1rem;
        border-radius: var(--radius);
        text-align: center;
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    
    .stats-card:hover {
        background-color: hsl(var(--secondary));
        transform: translateY(-2px);
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--foreground));
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
    }
    
    .shadcn-radial-progress {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        width: var(--size);
        height: var(--size);
    }
    
    .radial-progress-label {
        position: absolute;
        font-size: 0.75rem;
        font-weight: 600;
        color: hsl(var(--foreground));
    }
    
    .balance-box {
        background: hsl(var(--secondary) / 0.3);
        border-radius: var(--radius);
        padding: 1rem;
        border-left: 3px solid hsl(var(--primary));
    }
    
    .balance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .balance-label {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin-bottom: 0.25rem;
    }
    
    .balance-amount {
        font-size: 1.25rem;
        font-weight: 600;
        color: hsl(var(--foreground));
    }
    
    /* Sidebar Cards */
    .sidebar-card {
        margin-bottom: 1.5rem;
    }
    
    .shadcn-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem;
        border-bottom: 1px solid hsl(var(--border));
    }
    
    .shadcn-card-title {
        display: flex;
        align-items: center;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        color: hsl(var(--foreground));
    }
    
    .shadcn-card-content {
        padding: 1.25rem;
    }
    
    .collapser-chevron {
        transition: transform 0.2s ease;
    }
    
    [aria-expanded="false"] .collapser-chevron {
        transform: rotate(180deg);
    }
    
    /* Connects Display */
    .connects-display {
        margin-bottom: 1.5rem;
    }
    
    .connects-count-box {
        background: hsl(var(--secondary) / 0.3);
        padding: 1.5rem;
        border-radius: var(--radius);
        text-align: center;
    }
    
    .connects-count {
        font-size: 2.5rem;
        font-weight: 700;
        color: hsl(var(--primary));
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    .connects-label {
        font-size: 0.875rem;
        color: hsl(var(--foreground));
        margin-bottom: 0.5rem;
    }
    
    .connects-refresh {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
    }
    
    .connects-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .connects-links {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .shadcn-link {
        display: inline-flex;
        align-items: center;
        font-size: 0.75rem;
        color: hsl(var(--primary));
        text-decoration: none;
        transition: color 0.15s ease, text-decoration 0.15s ease;
    }
    
    .shadcn-link:hover {
        color: hsl(var(--primary) / 0.8);
        text-decoration: underline;
    }
    
    .divider {
        height: 1rem;
        width: 1px;
        background-color: hsl(var(--border));
    }
    
    /* Switch Component */
    .switch-item {
        margin-bottom: 1.5rem;
    }
    
    .switch-label {
        margin-bottom: 0.75rem;
    }
    
    .switch-label label {
        font-weight: 500;
        color: hsl(var(--foreground));
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .switch-description {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin: 0.25rem 0 0 0;
    }
    
    .shadcn-switch {
        position: relative;
        display: inline-flex;
        align-items: center;
        width: 2.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        background-color: hsl(var(--muted));
        transition: background-color 0.15s ease;
        cursor: pointer;
    }
    
    .shadcn-switch[data-state="checked"] {
        background-color: hsl(var(--primary));
    }
    
    .shadcn-switch-thumb {
        height: 0.75rem;
        width: 0.75rem;
        background-color: hsl(var(--background));
        border-radius: 9999px;
        transition: transform 0.15s ease;
        pointer-events: none;
        transform: translateX(0.25rem);
    }
    
    .shadcn-switch[data-state="checked"] .shadcn-switch-thumb {
        transform: translateX(1.25rem);
    }
    
    /* Select Component */
    .workload-selector {
        margin-top: 1.5rem;
    }
    
    .workload-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: hsl(var(--foreground));
        margin-bottom: 0.5rem;
    }
    
    .shadcn-select {
        position: relative;
    }
    
    .shadcn-select-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        background-color: transparent;
        border: 1px solid hsl(var(--border));
        border-radius: var(--radius);
        color: hsl(var(--foreground));
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }
    
    .shadcn-select-trigger:hover {
        border-color: hsl(var(--ring) / 0.3);
    }
    
    .shadcn-select-trigger:focus {
        outline: none;
        border-color: hsl(var(--ring));
        box-shadow: 0 0 0 2px hsl(var(--ring) / 0.2);
    }
    
    .chevron-down {
        transition: transform 0.15s ease;
    }
    
    .shadcn-select-native {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 1;
    }
</style>

<!-- JavaScript for Enhanced UI Features -->
<script>
// Define base URL for API calls
const BASE_URL = '<?php echo URL_ROOT; ?>';

document.addEventListener('DOMContentLoaded', function() {
    // Dark mode has been removed
    
    // Improved Banner carousel functionality
    let currentBanner = 1;
    const totalBanners = document.querySelectorAll('.promo-banner-content').length;
    const banners = document.querySelectorAll('.promo-banner-content');
    
    // Timer functionality removed
    
    // Banner navigation
    const nextBtn = document.getElementById('promo-next');
    const prevBtn = document.getElementById('promo-prev');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            showBanner(currentBanner + 1);
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            showBanner(currentBanner - 1);
        });
    }
    
    // Auto rotate banners every 8 seconds with subtle fade transition
    let bannerInterval = setInterval(() => {
        if (banners.length > 0) {
            showBanner(currentBanner + 1);
        }
    }, 8000);
    
    function showBanner(n) {
        // Reset auto interval when manually changed
        clearInterval(bannerInterval);
        
        // Make sure n is within limits
        if (n > totalBanners) n = 1;
        if (n < 1) n = totalBanners;
        
        // Don't do anything if we're already showing this banner
        if (n === currentBanner) {
            return;
        }
        
        // Get the current active banner and the new banner to show
        const currentActiveBanner = document.querySelector(`.promo-banner-content[data-banner-id="${currentBanner}"]`);
        const newBanner = document.querySelector(`.promo-banner-content[data-banner-id="${n}"]`);
        
        if (currentActiveBanner && newBanner) {
            // Set up fade out animation
            currentActiveBanner.style.opacity = '0';
            currentActiveBanner.style.zIndex = '1';
            
            // After current banner fades out, show new banner
            setTimeout(() => {
                currentActiveBanner.style.display = 'none';
                
                // Set up and show new banner
                newBanner.style.opacity = '0';
                newBanner.style.display = 'block';
                newBanner.style.zIndex = '2';
                
                // Force a reflow to ensure transition works properly
                newBanner.offsetHeight;
                
                // Start fade in animation
                setTimeout(() => {
                    newBanner.style.opacity = '1';
                    
                    // Reset animations inside the new banner
                    const animatedElements = newBanner.querySelectorAll('.promo-badge, .promo-banner-title, .promo-banner-description, .promo-banner-image');
                    animatedElements.forEach(el => {
                        // Reset animation by removing and re-adding the element
                        el.style.animation = 'none';
                        el.offsetHeight; // Force reflow
                        el.style.animation = '';
                    });
                }, 50);
            }, 300);
        }
        
        // Update current banner
        currentBanner = n;
        
        // Restart auto interval with slightly longer time for the active banner
        bannerInterval = setInterval(() => {
            if (banners.length > 0) {
                showBanner(currentBanner + 1);
            }
        }, 10000); // Increased from 8000 to 10000 ms
    }
    
    // Initialize with the first banner
    if (banners.length > 0) {
        // Initial setup for fade transition
        banners.forEach((banner, index) => {
            if (index === 0) {
                banner.style.opacity = '1';
                banner.style.display = 'block';
            } else {
                banner.style.opacity = '0';
                banner.style.display = 'none';
            }
            
            // Add transition for smooth fade
            banner.style.transition = 'opacity 0.3s ease';
        });
    }
    
    // Timer countdown functionality removed
    
    // Shadcn Toast Functionality
    window.showShadcnToast = function(options) {
        const { title, description, variant = 'default', duration = 5000 } = options;
        const toastId = 'toast-' + Date.now();
        const toastViewport = document.getElementById('toast-viewport');
        
        if (!toastViewport) return;
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `shadcn-toast ${variant ? 'shadcn-toast-' + variant : ''}`;
        toast.id = toastId;
        toast.setAttribute('role', 'alert');
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        
        // Toast content
        toast.innerHTML = `
            <div class="shadcn-toast-header">
                <div class="shadcn-toast-title">${title}</div>
                <button class="shadcn-toast-close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            ${description ? `<div class="shadcn-toast-body">${description}</div>` : ''}
        `;
        
        // Add toast to viewport
        toastViewport.appendChild(toast);
        
        // Trigger animation after a small delay (for DOM to update)
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Handle close button
        const closeBtn = toast.querySelector('.shadcn-toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                removeToast(toast);
            });
        }
        
        // Auto remove after duration
        setTimeout(() => {
            removeToast(toast);
        }, duration);
    };
    
    function removeToast(toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }
    
    // Keyboard shortcut for command menu
    document.addEventListener('keydown', function(e) {
        // Command+K or Control+K to focus search
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('job-search-input');
            if (searchInput) {
                searchInput.focus();
                
                // Show toast notification
                showShadcnToast({
                    title: "Keyboard Shortcut",
                    description: "You can use ⌘K or Ctrl+K anytime to quickly search for jobs.",
                    variant: "default"
                });
            }
        }
    });
    
    // Card toggle functionality
    document.querySelectorAll('.card-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const targetId = this.getAttribute('data-target');
            const targetBody = targetId ? document.getElementById(targetId) : this.closest('.card').querySelector('.card-body');
            
            if (targetBody) {
                if (isExpanded) {
                    targetBody.style.display = 'none';
                    this.setAttribute('aria-expanded', 'false');
                    if (this.querySelector('i.fa-chevron-up')) {
                        this.querySelector('i.fa-chevron-up').classList.remove('fa-chevron-up');
                        this.querySelector('i').classList.add('fa-chevron-down');
                    }
                } else {
                    targetBody.style.display = 'block';
                    this.setAttribute('aria-expanded', 'true');
                    if (this.querySelector('i.fa-chevron-down')) {
                        this.querySelector('i.fa-chevron-down').classList.remove('fa-chevron-down');
                        this.querySelector('i').classList.add('fa-chevron-up');
                    }
                }
            }
        });
    });
    
    // Job details panel functionality
    window.openJobDetails = function(jobId) {
        const detailsPanel = document.getElementById('job-details-panel');
        const overlay = document.getElementById('panel-overlay');
        const loading = document.getElementById('job-details-loading');
        const body = document.getElementById('job-details-body');
        
        if (detailsPanel && overlay && loading && body) {
            detailsPanel.classList.add('active');
            overlay.classList.add('active');
            loading.classList.remove('d-none');
            body.classList.add('d-none');
            
            console.log('Fetching job details for ID:', jobId);
            console.log('Fetch URL:', `${BASE_URL}/index.php?url=freelance/getJobDetails/${jobId}`);
            // Fetch job details from the server - fix the URL format
            fetch(`${BASE_URL}/index.php?url=freelance/getJobDetails/${jobId}`)
                .then(response => {
                    console.log('Response status:', response.status);
                    
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    // Check content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Response is not JSON:', contentType);
                        return response.text().then(text => {
                            console.error('Response text:', text);
                            throw new Error('Response is not JSON');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Job details data:', data);
                    // Hide loading and show content
                loading.classList.add('d-none');
                body.classList.remove('d-none');
                    
                    if (data.success && data.job) {
                        // Update job details in the panel
                        updateJobDetailsPanel(data.job, data.clientInfo, data.activity);
                
                // Update buttons
                const applyBtn = document.getElementById('job-apply-btn');
                const saveBtn = document.getElementById('job-save-btn');
                        const reportBtn = document.getElementById('job-report-btn');
                
                if (applyBtn) applyBtn.setAttribute('data-job-id', jobId);
                if (saveBtn) saveBtn.setAttribute('data-job-id', jobId);
                        if (reportBtn) reportBtn.setAttribute('data-job-id', jobId);
                
                        // Show success toast notification
                showShadcnToast({
                    title: "Job Details",
                            description: "Viewing details for " + data.job.title,
                    variant: "default"
                });
                    } else {
                        // Show error message
                        showShadcnToast({
                            title: "Error",
                            description: data.message || "Failed to load job details",
                            variant: "destructive"
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching job details:', error);
                    
                    // Hide loading spinner
                    loading.classList.add('d-none');
                    
                    // Show error toast
                    showShadcnToast({
                        title: "Error",
                        description: "Failed to fetch job details. Please try again.",
                        variant: "destructive"
                    });
                });
        }
    };
    
    // Function to update job details panel with fetched data
    function updateJobDetailsPanel(job, clientInfo, activity) {
        console.log('Updating job details with:', job);
        
        // Update job title
        const jobTitle = document.getElementById('job-details-title');
        if (jobTitle) {
            jobTitle.textContent = job.title;
            // Make it accessible for other functions
            jobTitle.className = "mb-0 job-details-title";
        } else {
            console.error('Could not find job-details-title element');
        }
        
        // Update job badges/tags
        const jobTypeBadge = document.getElementById('job-type-badge');
        if (jobTypeBadge) {
            jobTypeBadge.textContent = job.job_type === 'hourly' ? 'Hourly' : 'Fixed Price';
        }
        
        const jobLevelBadge = document.getElementById('job-level-badge');
        if (jobLevelBadge) {
            jobLevelBadge.textContent = job.experience_level || 'Intermediate';
        }
        
        const jobBudgetBadge = document.getElementById('job-budget-badge');
        if (jobBudgetBadge) {
            jobBudgetBadge.textContent = job.job_type === 'hourly' 
                ? `$${job.hourly_rate_min || 15}-${job.hourly_rate_max || 30}/hr` 
                : `$${job.budget}`;
            // Make it accessible for bid calculator
            jobBudgetBadge.className = "shadcn-badge shadcn-badge-primary me-2 job-detail-budget";
        }
        
        const jobDurationBadge = document.getElementById('job-duration-badge');
        if (jobDurationBadge) {
            let durationText = 'Unknown';
            
            switch(job.duration) {
                case 'less_than_1_week':
                    durationText = 'Less than 1 week';
                    break;
                case '1_to_2_weeks':
                    durationText = '1-2 weeks';
                    break;
                case '3_to_4_weeks':
                    durationText = '3-4 weeks';
                    break;
                case '1_to_3_months':
                    durationText = '1-3 months';
                    break;
                case '3_to_6_months':
                    durationText = '3-6 months';
                    break;
                case 'more_than_6_months':
                    durationText = '6+ months';
                    break;
            }
            
            jobDurationBadge.textContent = durationText;
        }
        
        // Update posted time and location
        const jobPostedTime = document.getElementById('job-posted-time');
        if (jobPostedTime) {
            jobPostedTime.textContent = `Posted ${job.posted_time || job.getTimeAgo || '3 days ago'}`;
        }
        
        const jobLocation = document.getElementById('job-location');
        if (jobLocation) {
            jobLocation.textContent = job.location || 'Worldwide';
        }
        
        // Update job description
        const jobDescriptionContent = document.getElementById('job-description-content');
        if (jobDescriptionContent) {
            // Safely set the HTML content
            jobDescriptionContent.innerHTML = '';
            
            // Add main description paragraph
            const descriptionPara = document.createElement('p');
            descriptionPara.textContent = job.description;
            jobDescriptionContent.appendChild(descriptionPara);
            
            // Add requirements section if available
            if (job.requirements) {
                const requirementsTitle = document.createElement('h5');
                requirementsTitle.className = 'mt-4 mb-3';
                requirementsTitle.textContent = 'Requirements:';
                jobDescriptionContent.appendChild(requirementsTitle);
                
                const requirementsList = document.createElement('ul');
                const requirements = job.requirements.split('\n');
                requirements.forEach(req => {
                    if (req.trim()) {
                        const li = document.createElement('li');
                        li.textContent = req.trim();
                        requirementsList.appendChild(li);
                    }
                });
                
                jobDescriptionContent.appendChild(requirementsList);
            }
        }
        
        // Update skills
        const jobSkillsContainer = document.querySelector('.job-skills .d-flex');
        if (jobSkillsContainer && job.skillsArray) {
            jobSkillsContainer.innerHTML = '';
            
            job.skillsArray.forEach(skill => {
                const skillBadge = document.createElement('span');
                skillBadge.className = 'skill-badge';
                skillBadge.textContent = skill;
                jobSkillsContainer.appendChild(skillBadge);
            });
        }
        
        // Update client information
        if (clientInfo) {
            // Member since
            const memberSinceValue = document.querySelector('.client-stat:nth-child(1) .stat-value');
            if (memberSinceValue) {
                memberSinceValue.textContent = clientInfo.member_since || 'Unknown';
            }
            
            // Jobs posted
            const jobsPostedValue = document.querySelector('.client-stat:nth-child(2) .stat-value');
            if (jobsPostedValue) {
                jobsPostedValue.textContent = `${clientInfo.jobs_posted || 0} jobs`;
            }
            
            // Hire rate
            const hireRateValue = document.querySelector('.client-stat:nth-child(3) .stat-value');
            if (hireRateValue) {
                hireRateValue.textContent = `${clientInfo.hire_rate || 0}%`;
            }
            
            // Total spent
            const totalSpentValue = document.querySelector('.client-stat:nth-child(4) .stat-value');
            if (totalSpentValue) {
                totalSpentValue.textContent = `$${clientInfo.total_spent || 0}`;
            }
            
            // Client rating
            const ratingValue = document.querySelector('.client-rating .rating-value');
            if (ratingValue) {
                ratingValue.textContent = clientInfo.rating || '0.0';
            }
            
            const ratingCount = document.querySelector('.client-rating .rating-count');
            if (ratingCount) {
                ratingCount.textContent = `(${clientInfo.review_count || 0} reviews)`;
            }
            
            // Update stars
            updateRatingStars(clientInfo.rating || 0);
        }
        
        // Update activity metrics if provided
        if (activity) {
            // For future use - proposal count, view count, etc.
        }
    }
    
    // Helper function to update rating stars
    function updateRatingStars(rating) {
        const starsContainer = document.querySelector('.rating-stars');
        if (!starsContainer) return;
        
        starsContainer.innerHTML = '';
        
        // Convert rating to number
        const ratingNum = parseFloat(rating) || 0;
        
        // Add full stars
        for (let i = 1; i <= Math.floor(ratingNum); i++) {
            const star = document.createElement('i');
            star.className = 'fas fa-star text-warning';
            starsContainer.appendChild(star);
        }
        
        // Add half star if needed
        if (ratingNum % 1 >= 0.5) {
            const halfStar = document.createElement('i');
            halfStar.className = 'fas fa-star-half-alt text-warning';
            starsContainer.appendChild(halfStar);
        }
        
        // Add empty stars
        const emptyStarsCount = 5 - Math.ceil(ratingNum);
        for (let i = 0; i < emptyStarsCount; i++) {
            const emptyStar = document.createElement('i');
            emptyStar.className = 'far fa-star text-warning';
            starsContainer.appendChild(emptyStar);
        }
    };
    
    // Close job details panel
    const closeDetailsBtn = document.getElementById('close-job-details');
    const panelOverlay = document.getElementById('panel-overlay');
    const detailsPanel = document.getElementById('job-details-panel');
    
    if (closeDetailsBtn && panelOverlay && detailsPanel) {
        closeDetailsBtn.addEventListener('click', function() {
            detailsPanel.classList.remove('active');
            panelOverlay.classList.remove('active');
        });
        
        panelOverlay.addEventListener('click', function() {
            detailsPanel.classList.remove('active');
            panelOverlay.classList.remove('active');
        });
    }
    
    // Modernized event listeners for updated UI
    
    // Availability toggle enhanced with Shadcn toast
    const availabilityToggle = document.getElementById('availabilityToggle');
    if (availabilityToggle) {
        availabilityToggle.addEventListener('change', function() {
            const message = this.checked ? 
                'You are now visible to clients and can receive job invitations.' : 
                'You are now invisible to clients and won\'t receive job invitations.';
            
            showShadcnToast({
                title: this.checked ? "Available for Work" : "Not Available",
                description: message,
                variant: this.checked ? "default" : "destructive"
            });
        });
    }
    
    // Workload select
    const workloadSelect = document.getElementById('workloadSelect');
    if (workloadSelect) {
        workloadSelect.addEventListener('change', function() {
            showShadcnToast({
                title: "Workload Updated",
                description: "Your workload preference has been set to " + this.options[this.selectedIndex].text,
                variant: "default"
            });
        });
    }
    
    // Buy connects button
    const buyConnectsBtn = document.getElementById('buy-connects-btn');
    if (buyConnectsBtn) {
        buyConnectsBtn.addEventListener('click', function() {
            // In a real implementation, this would open a modal to buy connects
            showShadcnToast({
                title: "Coming Soon",
                description: "Purchase additional connects to apply for more jobs.",
                variant: "default"
            });
        });
    }
    
    // Skill test button
    const skillTestBtn = document.querySelector('.skill-test-btn');
    if (skillTestBtn) {
        skillTestBtn.addEventListener('click', function() {
            showShadcnToast({
                title: "Skill Tests",
                description: "Skill test feature will be available soon!",
                variant: "default"
            });
        });
    }
    
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (tooltipTriggerList.length > 0) {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Initialize earnings chart if element exists
    const earningsChartEl = document.getElementById('earningsChart');
    if (earningsChartEl && typeof Chart !== 'undefined') {
        new Chart(earningsChartEl, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Earnings',
                    data: [1200, 1900, 1500, 2800, 2200, 3000],
                    borderColor: 'hsl(var(--primary))',
                    backgroundColor: 'hsl(var(--primary) / 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Job save and dislike functionality with enhanced feedback
    window.toggleSaveJob = function(jobId) {
        const saveBtn = document.querySelector(`.btn-save-job[data-job-id="${jobId}"]`);
        
        if (saveBtn) {
            const isSaved = saveBtn.classList.contains('saved');
            
            if (isSaved) {
                saveBtn.classList.remove('saved');
                const icon = saveBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
                showShadcnToast({
                    title: "Job Removed",
                    description: "Job has been removed from your saved jobs.",
                    variant: "default"
                });
            } else {
                saveBtn.classList.add('saved');
                const icon = saveBtn.querySelector('i');
                if (icon) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                }
                showShadcnToast({
                    title: "Job Saved",
                    description: "Job has been added to your saved jobs.",
                    variant: "default"
                });
            }
        }
    };
    
    window.dislikeJob = function(jobId) {
        const jobItem = document.querySelector(`.job-item[data-job-id="${jobId}"]`);
        
        if (jobItem) {
            // Animate job removal
            jobItem.style.transition = 'all 0.3s ease';
            jobItem.style.opacity = '0';
            jobItem.style.transform = 'translateX(30px)';
            
            setTimeout(() => {
                jobItem.remove();
                showShadcnToast({
                    title: "Job Hidden",
                    description: "Job hidden from your feed. We'll show fewer jobs like this.",
                    variant: "default"
                });
            }, 300);
        }
    };
    
    // Card collapsers & toggle functionality
    document.querySelectorAll('[data-collapser]').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const targetId = this.getAttribute('data-collapser');
            const targetBody = document.getElementById(targetId);
            
            if (targetBody) {
                if (isExpanded) {
                    targetBody.style.display = 'none';
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    targetBody.style.display = 'block';
                    this.setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
    
    // Switch components
    document.querySelectorAll('.shadcn-switch').forEach(switchEl => {
        switchEl.addEventListener('click', function() {
            const isChecked = this.getAttribute('data-state') === 'checked';
            if (isChecked) {
                this.setAttribute('data-state', 'unchecked');
                this.setAttribute('aria-checked', 'false');
            } else {
                this.setAttribute('data-state', 'checked');
                this.setAttribute('aria-checked', 'true');
            }
            
            // If this is the availability toggle, show toast notification
            if (this.id === 'availabilityToggle') {
                const isNowChecked = this.getAttribute('data-state') === 'checked';
                const message = isNowChecked ? 
                    'You are now visible to clients and can receive job invitations.' : 
                    'You are now invisible to clients and won\'t receive job invitations.';
                
                showShadcnToast({
                    title: isNowChecked ? "Available for Work" : "Not Available",
                    description: message,
                    variant: isNowChecked ? "default" : "destructive"
                });
            }
        });
    });
    
    // Job apply button functionality
    document.querySelectorAll('.job-apply-btn').forEach(applyBtn => {
        applyBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent job card click from triggering
            const jobId = this.getAttribute('data-job-id');
            
            // Create modal HTML - we'll create it dynamically to make sure it's up to date
            const modalHtml = `
                <div class="modal-overlay" id="applyJobModal" style="display: flex; align-items: center; justify-content: center;">
                    <div class="modal-container modal-large" style="max-width: 800px;">
                        <div class="modal-header">
                            <h3 class="modal-title">Apply for Job</h3>
                            <button class="modal-close" onclick="document.getElementById('applyJobModal').remove()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="jobApplicationForm">
                                <input type="hidden" name="job_id" value="${jobId}">
                                
                                <div class="mb-4">
                                    <label for="bid_amount" class="form-label">Your Bid (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="bid_amount" name="bid_amount" placeholder="Enter your bid amount">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="cover_letter" class="form-label">Cover Letter / Proposal</label>
                                    <textarea class="form-control" id="cover_letter" name="cover_letter" rows="8" placeholder="Introduce yourself and explain why you're a good fit for this job..." data-required="true"></textarea>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i> Make your proposal stand out by addressing the client's specific needs and highlighting your relevant experience.
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-lightbulb fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading">Tips for a Successful Proposal</h5>
                                            <ul class="mb-0">
                                                <li>Be specific about how your skills match the job requirements</li>
                                                <li>Share relevant examples from your previous work</li>
                                                <li>Explain your approach to completing the project</li>
                                                <li>Be clear about your availability and timeline</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="modal-button modal-button-secondary" onclick="document.getElementById('applyJobModal').remove()">Cancel</button>
                            <button type="submit" form="jobApplicationForm" class="modal-button modal-button-primary" id="submitApplicationBtn">Submit Application</button>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Set up form submission
            const applicationForm = document.getElementById('jobApplicationForm');
            applicationForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate the form
                if (!validateForm(this)) {
                    return;
                }
                
                // Get form data
                const formData = new FormData(this);
                
                // Submit application via AJAX
                fetch(`${BASE_URL}/freelance/applyForJob`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Remove the modal
                    document.getElementById('applyJobModal').remove();
                    
                    if (data.success) {
                        // Show success toast
                        showShadcnToast({
                            title: "Application Submitted",
                            description: data.message,
                            variant: "default"
                        });
                    } else {
                        // Show error toast
                        showShadcnToast({
                            title: "Application Failed",
                            description: data.message,
                            variant: "destructive"
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Show error toast
                    showShadcnToast({
                        title: "Application Error",
                        description: "There was an error submitting your application. Please try again.",
                        variant: "destructive"
                    });
                });
            });
        });
    });
    
    // AI proposal draft button functionality
    const proposalDraftBtn = document.querySelector('.ai-proposal-assistant .shadcn-btn-primary');
    if (proposalDraftBtn) {
        console.log('Found proposal draft button:', proposalDraftBtn);
        proposalDraftBtn.addEventListener('click', function(e) {
            console.log('Proposal draft button clicked');
            e.preventDefault();
            
            // Show loading state on the button
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';
            this.disabled = true;
            
            // Simulate AI processing time (2-3 seconds)
            setTimeout(() => {
                // Reset button state
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Pre-made proposal draft based on the job details
                const jobTitle = document.querySelector('.job-details-title')?.textContent || 'this position';
                const premadeProposal = `Dear Hiring Manager,

I'm excited to apply for ${jobTitle}. With my strong background in this field and successful completion of similar projects, I believe I am uniquely qualified to help you achieve your goals.

Based on your requirements, I propose the following approach:
- Initial consultation to fully understand your specific needs
- Developing a detailed project plan with clear milestones
- Regular progress updates and revisions as needed
- Final delivery with comprehensive documentation

My relevant experience includes:
• Successfully delivered 5+ similar projects on time and within budget
• Expert proficiency in all required technical skills
• Strong problem-solving abilities and attention to detail

I'm confident that my expertise aligns perfectly with your needs, and I'm eager to discuss how I can contribute to your project's success.

Looking forward to your response!`;

                // Create a modal to display the generated proposal
                const modalHtml = `
                    <div class="modal-overlay" id="proposalDraftModal">
                        <div class="modal-container modal-large">
                            <div class="modal-header">
                                <h3 class="modal-title">AI-Generated Proposal Draft</h3>
                                <button class="modal-close" onclick="document.getElementById('proposalDraftModal').remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i> This is a starting point - customize it to highlight your specific skills and address the client's needs.
                                </div>
                                <textarea class="form-control" rows="15" id="proposalDraftText">${premadeProposal}</textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="modal-button modal-button-secondary" onclick="document.getElementById('proposalDraftModal').remove()">Close</button>
                                <button class="modal-button modal-button-primary" onclick="copyProposalDraft()">Copy to Clipboard</button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove any existing modals
                const existingModal = document.getElementById('proposalDraftModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // Log for debugging
                console.log('Modal created:', document.getElementById('proposalDraftModal'));
                
                // Show success toast
                showShadcnToast({
                    title: "Proposal Generated",
                    description: "Your proposal draft has been created based on the job details.",
                    variant: "default"
                });
            }, 2500);
        });
    }
    
    // Function to copy the proposal draft to clipboard
    window.copyProposalDraft = function() {
        const proposalText = document.getElementById('proposalDraftText');
        
        if (proposalText) {
            proposalText.select();
            document.execCommand('copy');
            
            // Show success message
            showShadcnToast({
                title: "Copied to Clipboard",
                description: "The proposal draft has been copied to your clipboard.",
                variant: "default"
            });
        }
    };
    
    // Bid recommendation tool functionality
    const generateBidBtn = document.getElementById('generateBidBtn');
    if (generateBidBtn) {
        generateBidBtn.addEventListener('click', function(e) {
            console.log('Generate bid button clicked');
            e.preventDefault();
            
            // Show loading state on the button
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Calculating...';
            this.disabled = true;
            
            // Simulate processing time (1.5-2 seconds)
            setTimeout(() => {
                // Reset button state
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Get job details for more personalized recommendation
                const budget = document.querySelector('.job-detail-budget')?.textContent.trim() || '$500 - $1000';
                const jobTitle = document.querySelector('.job-details-title')?.textContent || 'this job';
                
                // Extract budget values
                let minBudget = 500;
                let maxBudget = 1000;
                
                // Try to parse the budget string
                const budgetMatch = budget.match(/\$(\d+(?:,\d+)?)\s*-\s*\$(\d+(?:,\d+)?)/);
                if (budgetMatch) {
                    minBudget = parseInt(budgetMatch[1].replace(',', ''));
                    maxBudget = parseInt(budgetMatch[2].replace(',', ''));
                }
                
                // Calculate recommended bid (80-90% of the max budget)
                const recommendedBid = Math.round(maxBudget * (Math.random() * 0.1 + 0.8));
                const lowestBid = Math.round(minBudget * (Math.random() * 0.1 + 0.75));
                const highestBid = Math.round(maxBudget * (Math.random() * 0.05 + 0.95));
                
                // Create a modal to display the bid recommendation
                const modalHtml = `
                    <div class="modal-overlay" id="bidRecommendationModal">
                        <div class="modal-container">
                            <div class="modal-header">
                                <h3 class="modal-title">Bid Recommendation</h3>
                                <button class="modal-close" onclick="document.getElementById('bidRecommendationModal').remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i> These recommendations are based on the job's budget and market rates.
                                </div>
                                
                                <div class="text-center mb-4">
                                    <h2 class="display-4 text-primary mb-0">$${recommendedBid}</h2>
                                    <p class="text-muted">Recommended Bid</p>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-6 text-center">
                                        <h4 class="mb-0">$${lowestBid}</h4>
                                        <p class="text-muted small">Minimum Viable Bid</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h4 class="mb-0">$${highestBid}</h4>
                                        <p class="text-muted small">Maximum Effective Bid</p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Bid Analysis</h5>
                                        <p>For jobs like ${jobTitle}, our analysis shows:</p>
                                        <ul>
                                            <li>Most winning bids fall within ${Math.round((maxBudget-minBudget)*0.4 + minBudget)} - ${Math.round(maxBudget*0.95)} range</li>
                                            <li>Bids under $${lowestBid} are often rejected for quality concerns</li>
                                            <li>Bids over $${highestBid} typically face strong competition</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="fas fa-lightbulb me-2"></i> <strong>Pro Tip:</strong> Consider the job complexity, timeline, and your experience level when finalizing your bid.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="modal-button modal-button-secondary" onclick="document.getElementById('bidRecommendationModal').remove()">Close</button>
                                <button class="modal-button modal-button-primary" onclick="useRecommendedBid(${recommendedBid})">Use This Bid</button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove any existing modals
                const existingModal = document.getElementById('bidRecommendationModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // Show success toast
                showShadcnToast({
                    title: "Bid Recommendation Ready",
                    description: "We've calculated an optimal bid based on the job details.",
                    variant: "default"
                });
            }, 1800);
        });
    }
    
    // Function to use the recommended bid in the application form
    window.useRecommendedBid = function(bidAmount) {
        // Find the bid input field in the application form
        const bidInput = document.getElementById('bid_amount');
        if (bidInput) {
            bidInput.value = bidAmount;
        }
        
        // Close the modal
        const modal = document.getElementById('bidRecommendationModal');
        if (modal) {
            modal.remove();
        }
        
        // Show confirmation toast
        showShadcnToast({
            title: "Bid Applied",
            description: "The recommended bid has been applied to your application form.",
            variant: "default"
        });
    };
});
</script>

<!-- Add this CSS right after the main Shadcn UI section -->
<style>
    /* Enhanced Shadcn UI Tabs Component */
    .shadcn-tabs {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    .shadcn-tabs-list {
        display: flex;
        align-items: center;
        border-bottom: 1px solid hsl(var(--border));
        position: relative;
        gap: 2px;
    }
    
    .shadcn-tabs-list::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background-color: hsl(var(--border));
        z-index: 0;
    }
    
    .shadcn-tabs-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 2.75rem;
        padding: 0 1.25rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: hsl(var(--muted-foreground));
        background-color: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        border-top-left-radius: var(--radius);
        border-top-right-radius: var(--radius);
        position: relative;
        transition: all 0.2s ease;
        cursor: pointer;
        white-space: nowrap;
        -webkit-user-select: none;
        user-select: none;
        z-index: 1;
    }
    
    .shadcn-tabs-trigger:hover {
        color: hsl(var(--foreground));
        background-color: hsl(var(--accent) / 0.1);
    }
    
    .shadcn-tabs-trigger::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: hsl(var(--primary));
        transform: scaleX(0);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
    }
    
    .shadcn-tabs-trigger.active, 
    .shadcn-tabs-trigger[data-state="active"] {
        color: hsl(var(--foreground));
        font-weight: 600;
    }
    
    .shadcn-tabs-trigger.active::after, 
    .shadcn-tabs-trigger[data-state="active"]::after {
        transform: scaleX(1);
    }
    
    .shadcn-tabs-content {
        padding: 1.5rem 0;
    }
    
    .shadcn-tabs-panel {
        width: 100%;
    }
    
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 1.25rem;
        min-width: 1.25rem;
        padding: 0 0.375rem;
        margin-left: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        background-color: hsl(var(--secondary) / 0.1);
        color: hsl(var(--secondary-foreground));
        transform-origin: center;
        transition: all 0.2s ease;
    }
    
    .shadcn-tabs-trigger.active .tab-badge,
    .shadcn-tabs-trigger[data-state="active"] .tab-badge {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
        transform: scale(1.1);
    }
    
    /* Enhanced Button Styles */
    .shadcn-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: calc(var(--radius) - 2px);
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1.25rem;
        padding: 0.5rem 1rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
        -webkit-tap-highlight-color: transparent; /* Prevents the blue highlight on mobile tap */
        user-select: none; /* Prevents text selection */
    }
    
    .shadcn-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5rem;
        height: 5rem;
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 60%);
        transform: translate(-50%, -50%) scale(0);
        opacity: 0;
        transition: transform 0.5s ease, opacity 0.5s ease;
        pointer-events: none;
    }
    
    .shadcn-btn:active::after {
        transform: translate(-50%, -50%) scale(3);
        opacity: 0.4;
        transition: 0s;
    }
    
    .shadcn-btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        border-radius: calc(var(--radius) - 3px);
    }
    
    .shadcn-btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        border-radius: var(--radius);
    }
    
    .shadcn-btn-primary {
        background-color: hsl(var(--primary));
        color: hsl(var(--primary-foreground));
        border: 1px solid transparent;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .shadcn-btn-primary:hover {
        background-color: hsl(var(--primary) / 0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .shadcn-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .shadcn-btn-secondary {
        background-color: hsl(var(--secondary));
        color: hsl(var(--secondary-foreground));
        border: 1px solid transparent;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .shadcn-btn-secondary:hover {
        background-color: hsl(var(--secondary) / 0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .shadcn-btn-outline {
        background-color: transparent;
        border: 1px solid hsl(var(--border));
        color: hsl(var(--foreground));
    }
    
    .shadcn-btn-outline:hover {
        background-color: hsl(var(--accent) / 0.1);
        border-color: hsl(var(--accent) / 0.2);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
    
    .shadcn-btn-ghost {
        background-color: transparent;
        border: none;
        color: hsl(var(--foreground));
    }
    
    .shadcn-btn-ghost:hover {
        background-color: hsl(var(--muted) / 0.7);
    }
    
    .shadcn-btn-destructive {
        background-color: hsl(var(--destructive));
        color: hsl(var(--destructive-foreground));
        border: 1px solid transparent;
    }
    
    .shadcn-btn-destructive:hover {
        background-color: hsl(var(--destructive) / 0.9);
        transform: translateY(-1px);
    }
    
    .shadcn-btn-disabled,
    .shadcn-btn[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .shadcn-btn svg {
        height: 1rem;
        width: 1rem;
    }
    
    .shadcn-btn svg:first-child {
        margin-right: 0.5rem;
    }
    
    .shadcn-btn svg:last-child {
        margin-left: 0.5rem;
    }
    
    .shadcn-btn-icon {
        padding: 0.5rem;
    }
    
    .shadcn-btn-icon svg {
        margin: 0;
    }
    
    /* Make content display flex to align items */
    .shadcn-btn-with-icon {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<!-- Add this JavaScript right before the closing body tag -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.shadcn-btn');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const circle = document.createElement('span');
                circle.style.position = 'absolute';
                circle.style.top = y + 'px';
                circle.style.left = x + 'px';
                circle.style.width = '0';
                circle.style.height = '0';
                circle.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
                circle.style.borderRadius = '50%';
                circle.style.transform = 'translate(-50%, -50%)';
                circle.style.pointerEvents = 'none';
                
                this.appendChild(circle);
                
                const size = Math.max(this.offsetWidth, this.offsetHeight) * 2;
                
                // Set animation properties
                circle.style.animation = 'ripple 0.6s linear';
                circle.style.width = size + 'px';
                circle.style.height = size + 'px';
                
                // Remove the span after animation completes
                setTimeout(() => {
                    circle.remove();
                }, 600);
            });
        });
        
        // Add animation keyframes for ripple effect if they don't exist
        if (!document.querySelector('#ripple-animation')) {
            const style = document.createElement('style');
            style.id = 'ripple-animation';
            style.textContent = `
                @keyframes ripple {
                    0% {
                        opacity: 1;
                        transform: translate(-50%, -50%) scale(0);
                    }
                    100% {
                        opacity: 0;
                        transform: translate(-50%, -50%) scale(1);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Animate the active tab indicator
        const tabTriggers = document.querySelectorAll('.shadcn-tabs-trigger');
        const tabsList = document.querySelector('.shadcn-tabs-list');
        
        if (tabsList) {
            // Create active indicator element
            const activeIndicator = document.createElement('span');
            activeIndicator.className = 'active-tab-indicator';
            activeIndicator.style.position = 'absolute';
            activeIndicator.style.bottom = '-1px';
            activeIndicator.style.height = '2px';
            activeIndicator.style.backgroundColor = 'hsl(var(--primary))';
            activeIndicator.style.transition = 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
            activeIndicator.style.zIndex = '2';
            
            tabsList.appendChild(activeIndicator);
            
            // Position the indicator on the active tab
            function positionIndicator() {
                const activeTab = document.querySelector('.shadcn-tabs-trigger.active') || 
                                document.querySelector('.shadcn-tabs-trigger[data-state="active"]');
                
                if (activeTab) {
                    activeIndicator.style.left = `${activeTab.offsetLeft}px`;
                    activeIndicator.style.width = `${activeTab.offsetWidth}px`;
                } else {
                    // Hide indicator if no active tab
                    activeIndicator.style.width = '0px';
                }
            }
            
            // Position on load
            setTimeout(positionIndicator, 50);
            
            // Reposition on window resize
            window.addEventListener('resize', positionIndicator);
            
            // Add hover effect
            tabTriggers.forEach(trigger => {
                trigger.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active') && this.getAttribute('data-state') !== 'active') {
                        activeIndicator.style.left = `${this.offsetLeft}px`;
                        activeIndicator.style.width = `${this.offsetWidth}px`;
                        activeIndicator.style.opacity = '0.5';
                    }
                });
                
                trigger.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && this.getAttribute('data-state') !== 'active') {
                        positionIndicator();
                        activeIndicator.style.opacity = '1';
                    }
                });
            });
        }
    });
</script>

<?php
// Footer is handled by the controller
?>