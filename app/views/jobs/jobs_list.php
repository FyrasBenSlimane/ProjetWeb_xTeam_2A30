<?php
/**
 * Jobs list template - used for displaying a list of jobs
 * 
 * Expected variables:
 * - $jobs: Array of job objects from $data['jobs']
 */

// Make sure we have the jobs data from the data array
if (!isset($jobs) && isset($data['jobs'])) {
    $jobs = $data['jobs'];
}
?>

<div class="jobs-carousel-container">
    <!-- Left navigation arrow -->
    <button class="carousel-nav carousel-nav-left">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div id="jobsContainer" class="jobs-list-container">
        <?php if (isset($jobs) && !empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <?php include(APP_ROOT . '/views/jobs/job_item.php'); ?>
            <?php endforeach; ?>
            
            <!-- Add empty job card for adding new job -->
            <div class="job-list-item job-list-item-flexible">
                <div class="job-card empty-job-card no-slider" onclick="openModal('postJobModal')">
                    <div class="empty-job-content">
                        <div class="empty-job-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h3 class="empty-job-title">Post a job</h3>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-jobs-container text-center py-5">
                <div class="empty-jobs-icon mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/3143/3143162.png" alt="No jobs" width="120">
                </div>
                <h3 class="empty-jobs-title mb-2">No job posts or contracts in progress right now</h3>
                <button type="button" class="btn post-job-btn mt-3" onclick="openModal('postJobModal')">
                    <i class="fas fa-plus me-2"></i> Post a job
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right navigation arrow -->
    <button class="carousel-nav carousel-nav-right">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>

<style>
    /* Carousel container and navigation styles */
    .jobs-carousel-container {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        margin: 1.5rem 0;
    }
    
    .jobs-list-container {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 1rem 0.5rem;
        scrollbar-width: none; /* Hide scrollbar for Firefox */
        -ms-overflow-style: none; /* Hide scrollbar for IE/Edge */
        gap: 1.5rem;
        width: 100%;
    }
    
    .jobs-list-container::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Chrome/Safari */
    }
    
    .carousel-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        cursor: pointer;
        position: absolute;
        z-index: 10;
        transition: all 0.2s ease;
        opacity: 0.8;
    }
    
    .carousel-nav:hover {
        background: #f9f9f9;
        transform: translateY(-2px);
        opacity: 1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }
    
    .carousel-nav-left {
        left: -20px;
    }
    
    .carousel-nav-right {
        right: -20px;
    }
    
    .carousel-nav i {
        color: #555;
        font-size: 1rem;
    }
    
    /* Empty job card styles */
    .empty-job-card {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 280px;
        height: 100%;
        background: #ffffff;
        border: 2px dashed rgba(44, 62, 80, 0.15);
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: none;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
    }
    
    /* Use a specific class instead of :has() for better compatibility */
    .job-list-item-flexible {
        flex: 1 1 auto;
        min-width: 280px;
        max-width: none;
    }
    
    .empty-job-card:hover {
        background: rgba(44, 62, 80, 0.02);
        border-color: rgba(44, 62, 80, 0.3);
        transform: translateY(-3px);
    }
    
    .empty-job-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
    }
    
    /* Make job cards have consistent width in carousel */
    .job-list-item {
        flex: 0 0 auto;
        width: 280px;
        max-width: 100%;
        height: 150px;
    }
    
    @media (max-width: 768px) {
        .job-list-item {
            width: 260px;
        }
        
        .carousel-nav {
            width: 36px;
            height: 36px;
        }
        
        .carousel-nav-left {
            left: -10px;
        }
        
        .carousel-nav-right {
            right: -10px;
        }
    }
    
    @media (max-width: 576px) {
        .job-list-item {
            width: 300px;
        }
        
        .carousel-nav {
            display: none; /* Hide arrows on mobile */
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.jobs-list-container');
        const leftNav = document.querySelector('.carousel-nav-left');
        const rightNav = document.querySelector('.carousel-nav-right');
        const flexibleItem = document.querySelector('.job-list-item-flexible');
        
        // Function to adjust the flexible job card width
        function adjustFlexibleCardWidth() {
            if (!flexibleItem || !container) return;
            
            // Get all job cards except the flexible one
            const regularItems = Array.from(
                document.querySelectorAll('.job-list-item:not(.job-list-item-flexible)')
            );
            
            if (regularItems.length === 0) {
                // If no regular items, take full width minus padding
                flexibleItem.style.width = 'calc(100% - 2rem)';
                return;
            }
            
            // Calculate the total width of all regular items plus gaps
            const totalRegularWidth = regularItems.reduce((total, item) => {
                return total + item.offsetWidth;
            }, 0);
            
            // Calculate total gap width (gap is 1.5rem = 24px)
            const gapCount = regularItems.length; // Gaps between items and after last regular item
            const gapWidth = 24 * gapCount;
            
            // Calculate container's visible width
            const containerWidth = container.clientWidth;
            
            // Calculate remaining width for the flexible item
            let remainingWidth = containerWidth - totalRegularWidth - gapWidth;
            
            // Ensure minimum width
            remainingWidth = Math.max(remainingWidth, 280);
            
            // Apply the width
            flexibleItem.style.width = `${remainingWidth}px`;
        }
        
        // Adjust width on load and on resize
        adjustFlexibleCardWidth();
        window.addEventListener('resize', adjustFlexibleCardWidth);
        
        // Also adjust after the content might have changed
        const observer = new MutationObserver(adjustFlexibleCardWidth);
        if (container) {
            observer.observe(container, { 
                childList: true, 
                subtree: true,
                attributes: true,
                attributeFilter: ['style', 'class']
            });
        }
        
        // Navigation arrow click handlers
        if (leftNav && container) {
            leftNav.addEventListener('click', function() {
                container.scrollBy({ left: -300, behavior: 'smooth' });
            });
        }
        
        if (rightNav && container) {
            rightNav.addEventListener('click', function() {
                container.scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
        
        // Function to show/hide arrows based on scroll position
        function updateArrowVisibility() {
            if (!container) return;
            
            // If scrolled to start, hide left arrow
            if (container.scrollLeft <= 10 && leftNav) {
                leftNav.style.opacity = '0.5';
                leftNav.style.cursor = 'default';
            } else if (leftNav) {
                leftNav.style.opacity = '1';
                leftNav.style.cursor = 'pointer';
            }
            
            // If scrolled to end, hide right arrow
            const scrollEnd = container.scrollWidth - container.clientWidth;
            if (container.scrollLeft >= scrollEnd - 10 && rightNav) {
                rightNav.style.opacity = '0.5';
                rightNav.style.cursor = 'default';
            } else if (rightNav) {
                rightNav.style.opacity = '1';
                rightNav.style.cursor = 'pointer';
            }
        }
        
        // Update arrow visibility on load and scroll
        updateArrowVisibility();
        if (container) {
            container.addEventListener('scroll', updateArrowVisibility);
        }
    });
</script>

<!-- Include job modals -->
<?php include(APP_ROOT . '/views/jobs/job_modals.php'); ?> 