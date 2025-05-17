<?php
// Community resources page - educational videos for community members
?>

<style>
    /* Global styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Page layout */
    .resources-container {
        background-color: #f9fafb;
        padding: 3rem 1rem;
    }

    .content-max-width {
        max-width: 1024px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .page-description {
        color: #6b7280;
        margin-bottom: 2rem;
        max-width: 768px;
    }

    /* Filter section */
    .filter-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .filter-heading {
        font-weight: 500;
        color: #4b5563;
        margin-right: 0.5rem;
    }

    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .filter-tag {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        background-color: #f3f4f6;
        color: #4b5563;
        border-radius: 9999px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-tag:hover {
        background-color: #e5e7eb;
    }

    .filter-tag-active {
        background-color: #2c3e50;
        color: white;
    }

    /* Videos grid */
    .videos-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    @media (min-width: 640px) {
        .videos-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .videos-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .video-card {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .video-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .video-thumbnail {
        width: 100%;
        height: 180px;
        object-fit: cover;
        position: relative;
    }

    .video-thumbnail::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
    }

    .video-content {
        padding: 1.25rem;
    }

    .video-category {
        display: inline-block;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
        border-radius: 0.25rem;
        margin-bottom: 0.75rem;
    }

    .video-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #111827;
    }

    .video-description {
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .video-channel {
        display: flex;
        align-items: center;
    }

    .video-duration {
        font-weight: 500;
    }

    .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .play-icon {
        width: 0;
        height: 0;
        border-top: 12px solid transparent;
        border-left: 20px solid #2c3e50;
        border-bottom: 12px solid transparent;
        margin-left: 5px;
    }

    .thumbnail-container {
        position: relative;
    }

    /* CTA section */
    .cta-container {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .cta-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
    }

    .cta-description {
        color: #6b7280;
        margin-bottom: 1.5rem;
        max-width: 32rem;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button {
        display: inline-block;
        background-color: #2c3e50;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        text-decoration: none;
    }

    .cta-button:hover {
        background-color: #34495e;
    }
</style>

<div class="resources-container">
    <div class="container content-max-width">
        <h1 class="page-title">Educational Resources</h1>
        <p class="page-description">
            Explore our curated collection of educational videos to enhance your skills and knowledge.
        </p>
        
        <!-- Category filters -->
        <div class="filter-container">
            <div>
                <span class="filter-heading">Categories:</span>
                <div class="filter-tags">
                    <a href="?category=all" class="filter-tag <?php echo (!isset($_GET['category']) || $_GET['category'] === 'all') ? 'filter-tag-active' : ''; ?>">All</a>
                    <?php
                    // Get unique categories from resources
                    $categories = [];
                    if (!empty($data['resources'])) {
                        foreach ($data['resources'] as $resource) {
                            if (!in_array($resource->category, $categories)) {
                                $categories[] = $resource->category;
                            }
                        }
                        
                        // Display category filters
                        foreach ($categories as $category):
                    ?>
                    <a href="?category=<?php echo urlencode($category); ?>" class="filter-tag <?php echo (isset($_GET['category']) && $_GET['category'] === $category) ? 'filter-tag-active' : ''; ?>"><?php echo $category; ?></a>
                    <?php
                        endforeach;
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <!-- Videos grid -->
        <div class="videos-grid">
            <?php
            // Check if there are any resources available
            if (!empty($data['resources']) && count($data['resources']) > 0):
                foreach ($data['resources'] as $resource):
                    // Extract YouTube video duration if needed
                    $duration = isset($resource->duration) ? $resource->duration : '';
                    // Extract YouTube channel if needed
                    $channel = isset($resource->channel) ? $resource->channel : 'Educational Channel';
            ?>
            <a href="<?php echo $resource->youtube_url; ?>" target="_blank" class="video-card">
                <div class="thumbnail-container">
                    <img src="<?php echo $resource->thumbnail_url; ?>" alt="<?php echo $resource->title; ?>" class="video-thumbnail">
                    <div class="play-overlay">
                        <div class="play-icon"></div>
                    </div>
                </div>
                <div class="video-content">
                    <span class="video-category"><?php echo $resource->category; ?></span>
                    <h3 class="video-title"><?php echo $resource->title; ?></h3>
                    <p class="video-description"><?php echo $resource->description; ?></p>
                    <div class="video-meta">
                        <div class="video-channel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="channel-name"><?php echo $channel; ?></span>
                        </div>
                        <?php if ($duration): ?>
                        <span class="video-duration"><?php echo $duration; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php 
                endforeach;
            else:
            ?>
            <div class="no-resources">
                <style>
                    .no-resources {
                        grid-column: 1 / -1;
                        background-color: white;
                        border: 1px solid #e5e7eb;
                        border-radius: 0.5rem;
                        padding: 2rem;
                        text-align: center;
                    }
                    .no-resources-icon {
                        font-size: 3rem;
                        color: #e5e7eb;
                        margin-bottom: 1rem;
                    }
                    .no-resources-title {
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin-bottom: 0.5rem;
                    }
                    .no-resources-message {
                        color: #6b7280;
                    }
                </style>
                <div class="no-resources-icon">📚</div>
                <h3 class="no-resources-title">No resources available</h3>
                <p class="no-resources-message">Check back later for educational content.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sign in CTA -->
        <?php if (isset($data['is_logged_in']) && $data['is_logged_in']): ?>
        <div class="cta-container">
            <h2 class="cta-title">Have a resource suggestion?</h2>
            <p class="cta-description">
                Know a great educational video that should be in our collection? Submit your suggestion for review.
            </p>
            <a href="#" class="cta-button">
                Suggest a Resource
            </a>
        </div>
        <?php else: ?>
        <div class="cta-container">
            <h2 class="cta-title">Sign in to track your progress</h2>
            <p class="cta-description">
                Sign in to bookmark videos, track your learning progress, and receive personalized recommendations.
            </p>
            <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="cta-button">
                Sign In
            </a>
        </div>
        <?php endif; ?>
    </div>
</div> 