<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include custom forum CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/forum.css">
<!-- Include community CSS for consistent styling -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <!-- Forum Header with Enhanced Search -->
    <div class="row mb-4 community-header">
        <div class="col-md-8">
            <h1 class="mb-3">Community Forums</h1>
            <p class="text-muted">Join discussions, ask questions, and share your knowledge with other members of our community.</p>
            <?php flash('topic_message'); ?>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-md-end justify-content-md-between">
            <!-- Enhanced Search Bar with AJAX Dropdown -->
            <div class="forum-search-form w-100 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control forum-search-input" placeholder="Search forums..." aria-label="Search forums">
                    <button class="btn btn-primary" type="button" id="searchButton">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="search-results-dropdown bg-white shadow d-none"></div>
            </div>

            <!-- Create Topic Button -->
            <?php if (isLoggedIn()) : ?>
                <a href="<?php echo URLROOT; ?>/community/createTopic" class="btn btn-primary btn-community-primary w-100 w-md-auto">
                    <i class="bi bi-plus-circle me-1"></i> Create New Topic
                </a>
            <?php else : ?>
                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary btn-community-outline w-100 w-md-auto">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login to Create Topic
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Navigation Tabs -->
    <div class="mb-4">
        <nav class="nav nav-pills nav-fill bg-light rounded p-1">
            <a class="nav-link active" href="<?php echo URLROOT; ?>/community/forums">
                <i class="bi bi-grid-3x3-gap me-1"></i> Categories
            </a>
            <a class="nav-link" href="<?php echo URLROOT; ?>/community/allTopics">
                <i class="bi bi-chat-text me-1"></i> All Topics
            </a>
            <?php if (isLoggedIn()): ?>
                <a class="nav-link" href="<?php echo URLROOT; ?>/community/allTopics?filter=my-topics">
                    <i class="bi bi-person me-1"></i> My Topics
                </a>
                <a class="nav-link" href="<?php echo URLROOT; ?>/community/allTopics?filter=unanswered">
                    <i class="bi bi-question-circle me-1"></i> Unanswered
                </a>
            <?php endif; ?>

            <!-- Add Notifications Bell for logged in users -->
            <?php if (isLoggedIn()): ?>
                <div class="nav-link position-relative notification-bell" style="cursor: pointer;">
                    <i class="bi bi-bell me-1"></i> Notifications
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge d-none">
                        0
                    </span>

                    <!-- Notifications dropdown panel -->
                    <div class="notifications-panel bg-white shadow d-none">
                        <div class="p-3 text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-2 small text-muted">Loading notifications...</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </nav>
    </div>

    <div class="row">
        <!-- Categories with Enhanced UI -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Forum Categories</h5>
                    <span class="badge bg-primary rounded-pill"><?php echo count($data['categories']); ?></span>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($data['categories'] as $category) : ?>
                        <a href="<?php echo URLROOT; ?>/community/forumCategory/<?php echo $category->slug; ?>"
                            class="list-group-item list-group-item-action d-flex align-items-center py-3 px-3">

                            <div class="category-icon">
                                <i class="bi bi-<?php echo $category->icon ?? 'chat-dots'; ?> fs-4 text-primary"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-1"><?php echo $category->name; ?></h5>
                                    <div class="text-muted d-none d-md-block">
                                        <span class="me-2 stats-counter" data-count="<?php echo $category->topic_count; ?>"><?php echo $category->topic_count; ?> topics</span>
                                        <span class="stats-counter" data-count="<?php echo $category->post_count; ?>"><?php echo $category->post_count; ?> posts</span>
                                    </div>
                                </div>
                                <p class="mb-0 text-muted small"><?php echo $category->description; ?></p>

                                <?php if (isset($category->last_activity) && !empty($category->last_activity)): ?>
                                    <div class="mt-1 small text-muted">
                                        <i class="bi bi-clock-history me-1"></i> Latest activity: <?php echo timeElapsed($category->last_activity); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-end d-none d-md-block">
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Forum Stats with animation effects -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Forum Statistics</h5>
                </div>
                <div class="card-body pb-2">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fs-2 fw-bold text-primary mb-1">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="fs-4 stats-counter" data-count="<?php echo isset($data['stats']) ? $data['stats']->user_count : '0'; ?>">
                                <?php echo isset($data['stats']) ? $data['stats']->user_count : '0'; ?>
                            </div>
                            <div class="text-muted">Members</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-2 fw-bold text-success mb-1">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <div class="fs-4 stats-counter" data-count="<?php echo isset($data['stats']) ? $data['stats']->topic_count : '0'; ?>">
                                <?php echo isset($data['stats']) ? $data['stats']->topic_count : '0'; ?>
                            </div>
                            <div class="text-muted">Topics</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-2 fw-bold text-info mb-1">
                                <i class="bi bi-reply"></i>
                            </div>
                            <div class="fs-4 stats-counter" data-count="<?php echo isset($data['stats']) ? $data['stats']->reply_count : '0'; ?>">
                                <?php echo isset($data['stats']) ? $data['stats']->reply_count : '0'; ?>
                            </div>
                            <div class="text-muted">Replies</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar with Recent Topics and User Activity -->
        <div class="col-lg-4">
            <!-- Recent Topics Card with enhanced UI and interactive elements -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Topics</h5>
                    <span class="badge bg-primary rounded-pill"><?php echo count($data['recentTopics']); ?></span>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($data['recentTopics'])) : ?>
                        <div class="list-group-item py-4">
                            <div class="text-center">
                                <i class="bi bi-emoji-neutral fs-1 text-muted mb-2"></i>
                                <p class="mb-0 text-muted">No topics yet</p>
                                <?php if (isLoggedIn()) : ?>
                                    <a href="<?php echo URLROOT; ?>/community/createTopic" class="btn btn-sm btn-primary mt-3">
                                        Start the conversation
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php foreach ($data['recentTopics'] as $topic) : ?>
                            <a href="<?php echo URLROOT; ?>/community/topic/<?php echo $topic->slug; ?>"
                                class="list-group-item list-group-item-action p-3 topic-item position-relative
                                <?php echo ($topic->status == 'pinned') ? 'sticky' : ''; ?>
                                <?php echo (isset($topic->unread) && $topic->unread) ? 'unread' : ''; ?>">

                                <!-- Topic Status Indicator -->
                                <div class="topic-status 
                                    <?php if ($topic->status == 'pinned'): ?>bg-warning
                                    <?php elseif ($topic->status == 'closed'): ?>bg-secondary
                                    <?php elseif (isset($topic->has_solution) && $topic->has_solution): ?>bg-success
                                    <?php elseif (isset($topic->unread) && $topic->unread): ?>bg-primary
                                    <?php endif; ?>">
                                </div>

                                <!-- Topic Content with padding for indicator -->
                                <div class="ps-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="<?php echo !empty($topic->author_image) ? URLROOT . '/public/uploads/' . $topic->author_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                            alt="Profile" class="rounded-circle me-2" width="30" height="30">
                                        <span class="small text-muted"><?php echo $topic->author_name; ?></span>

                                        <?php if ($topic->status == 'pinned') : ?>
                                            <span class="ms-auto badge bg-warning text-dark sticky-icon" title="Pinned Topic">
                                                <i class="bi bi-pin-angle"></i>
                                            </span>
                                        <?php elseif ($topic->status == 'closed') : ?>
                                            <span class="ms-auto badge bg-secondary" title="Closed Topic">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                        <?php elseif (isset($topic->has_solution) && $topic->has_solution): ?>
                                            <span class="ms-auto badge bg-success solved-icon" title="Solved Topic">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h6 class="mb-1 text-truncate"><?php echo $topic->title; ?></h6>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge bg-light text-dark"><?php echo $topic->category_name; ?></span>
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php echo timeElapsed($topic->created_at); ?>
                                        </div>
                                    </div>
                                    <div class="d-flex topic-meta mt-1">
                                        <div class="me-3">
                                            <i class="bi bi-eye me-1"></i><?php echo $topic->views; ?>
                                        </div>
                                        <div>
                                            <i class="bi bi-chat me-1"></i><?php echo $topic->reply_count; ?>
                                        </div>
                                        <?php if (isset($topic->votes)): ?>
                                            <div class="ms-auto">
                                                <i class="bi bi-hand-thumbs-up me-1"></i><?php echo $topic->votes; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="<?php echo URLROOT; ?>/community/allTopics" class="btn btn-sm btn-outline-primary">View All Topics</a>
                </div>
            </div>

            <!-- Active Users with enhanced UI -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Active Members</h5>
                </div>
                <div class="card-body pb-2">
                    <div class="d-flex flex-wrap justify-content-center">
                        <?php if (isset($data['activeUsers']) && !empty($data['activeUsers'])) : ?>
                            <?php foreach ($data['activeUsers'] as $user) : ?>
                                <div class="text-center mx-2 mb-3" style="width: 65px;">
                                    <div class="position-relative">
                                        <img src="<?php echo !empty($user->profile_image) ? URLROOT . '/public/uploads/' . $user->profile_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                            alt="<?php echo $user->name; ?>" class="rounded-circle mb-1" width="45" height="45"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user->name; ?>">
                                        <?php if (isset($user->online) && $user->online): ?>
                                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1" style="width:10px; height:10px;"></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-truncate"><?php echo $user->name; ?></div>
                                    <?php if (isset($user->posts_count)): ?>
                                        <div class="badge bg-light text-dark small"><?php echo $user->posts_count; ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="text-muted text-center w-100">No active users</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Community Guidelines Card -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Community Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Be respectful and kind to others</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Stay on topic and be concise</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Use descriptive titles for topics</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Search before posting new questions</li>
                    </ul>
                </div>

                <!-- Theme Toggle -->
                <div class="card-footer bg-light">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="darkModeToggle">
                        <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include custom JS -->
<script src="<?php echo URLROOT; ?>/public/js/forum.js"></script>
<script>
    // Set the AJAX URL for forum JS
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Forum !== 'undefined') {
            Forum.config.ajaxUrl = '<?php echo URLROOT; ?>/community/';
        }

        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            // Check for saved preference
            if (localStorage.getItem('forum_dark_mode') === 'enabled') {
                document.body.classList.add('enable-dark-mode');
                darkModeToggle.checked = true;
            }

            // Toggle dark mode
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('enable-dark-mode');
                    localStorage.setItem('forum_dark_mode', 'enabled');
                } else {
                    document.body.classList.remove('enable-dark-mode');
                    localStorage.setItem('forum_dark_mode', 'disabled');
                }
            });
        }

        // Animate stats counters
        const animateCounters = () => {
            const counters = document.querySelectorAll('.stats-counter');
            counters.forEach(counter => {
                const targetCount = parseInt(counter.getAttribute('data-count'));
                const startCount = 0;

                // Add animate class
                counter.classList.add('animate');

                // Count up effect
                let currentCount = startCount;
                const duration = 1500; // 1.5 seconds
                const increment = Math.ceil(targetCount / (duration / 50)); // Update every 50ms

                const timer = setInterval(() => {
                    currentCount += increment;
                    if (currentCount >= targetCount) {
                        clearInterval(timer);
                        counter.textContent = counter.hasAttribute('data-text') ?
                            targetCount + ' ' + counter.getAttribute('data-text') :
                            targetCount;

                        // Remove animation class after a delay
                        setTimeout(() => {
                            counter.classList.remove('animate');
                        }, 1000);
                    } else {
                        counter.textContent = counter.hasAttribute('data-text') ?
                            currentCount + ' ' + counter.getAttribute('data-text') :
                            currentCount;
                    }
                }, 50);
            });
        };

        // Run animation when in viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        const statsSection = document.querySelector('.stats-counter');
        if (statsSection) {
            observer.observe(statsSection);
        }
    });
</script>

<!-- Include community enhancements script -->
<script src="<?php echo URLROOT; ?>/public/js/community-enhancements.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>