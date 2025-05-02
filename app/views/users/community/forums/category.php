<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include community CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forums" class="text-decoration-none"><i class="bi bi-chat-square-text me-1"></i> Forums</a></li>
            <li class="breadcrumb-item active"><?php echo $data['category']->name; ?></li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-2">
                <div class="category-icon rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-center" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-<?php echo $data['category']->icon ?? 'chat-dots'; ?> fs-3 text-primary"></i>
                </div>
                <div>
                    <h1 class="mb-1"><?php echo $data['category']->name; ?></h1>
                    <p class="text-muted mb-0"><?php echo $data['category']->description; ?></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex justify-content-lg-end">
                <!-- Search Bar -->
                <div class="input-group me-2 flex-grow-1 flex-lg-grow-0">
                    <input type="text" class="form-control form-control-sm" placeholder="Search in this category" id="category-search">
                    <button class="btn btn-outline-secondary btn-sm" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                <!-- Create Topic Button -->
                <?php if (isLoggedIn()) : ?>
                    <a href="<?php echo URLROOT; ?>/community/createTopic?category=<?php echo $data['category']->id; ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle d-none d-sm-inline-block"></i> New Topic
                    </a>
                <?php else : ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary btn-sm">
                        Login to Post
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filter and Sort Options -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 d-inline-block">Topics</h5>
                    <span class="ms-2 badge bg-primary rounded-pill"><?php echo count($data['topics']); ?></span>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end mt-2 mt-md-0">
                        <!-- Filter Dropdown -->
                        <div class="me-2 d-flex align-items-center">
                            <label for="topic-filter" class="me-2 text-nowrap d-none d-sm-block">Filter:</label>
                            <select class="form-select form-select-sm" id="topic-filter">
                                <option value="all">All Topics</option>
                                <option value="solved">Solved Only</option>
                                <option value="open">Open Only</option>
                                <option value="closed">Closed Only</option>
                            </select>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="d-flex align-items-center">
                            <label for="topic-sort" class="me-2 text-nowrap d-none d-sm-block">Sort:</label>
                            <select class="form-select form-select-sm" id="topic-sort">
                                <option value="recent">Most Recent</option>
                                <option value="popular">Most Popular</option>
                                <option value="replies">Most Replies</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topic List -->
        <div class="list-group list-group-flush" id="topic-list">
            <?php if (empty($data['topics'])) : ?>
                <div class="list-group-item py-5">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                        </div>
                        <h5>No topics in this category yet</h5>
                        <p class="text-muted mb-3">Be the first to start a conversation!</p>
                        <?php if (isLoggedIn()) : ?>
                            <a href="<?php echo URLROOT; ?>/community/createTopic?category=<?php echo $data['category']->id; ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create First Topic
                            </a>
                        <?php else : ?>
                            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login to Post
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($data['topics'] as $topic) : ?>
                    <div class="list-group-item p-0">
                        <div class="row g-0 topic-row position-relative">
                            <!-- Topic Status Indicator -->
                            <?php if ($topic->status == 'pinned'): ?>
                                <div class="topic-status-indicator bg-warning"></div>
                            <?php elseif ($topic->status == 'closed'): ?>
                                <div class="topic-status-indicator bg-secondary"></div>
                            <?php elseif (isset($topic->has_solution) && $topic->has_solution): ?>
                                <div class="topic-status-indicator bg-success"></div>
                            <?php else: ?>
                                <div class="topic-status-indicator bg-info"></div>
                            <?php endif; ?>

                            <!-- Topic Content -->
                            <div class="col-md-8 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <!-- Author Image -->
                                    <img src="<?php echo !empty($topic->profile_image) ? URLROOT . '/public/uploads/' . $topic->profile_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                        alt="Profile" class="rounded-circle me-2" width="40" height="40">

                                    <div class="flex-grow-1">
                                        <!-- Topic Title with Status Icons -->
                                        <h5 class="mb-0">
                                            <a href="<?php echo URLROOT; ?>/community/topic/<?php echo $topic->slug; ?>" class="text-decoration-none stretched-link">
                                                <?php echo $topic->title; ?>
                                            </a>

                                            <?php if ($topic->status == 'pinned') : ?>
                                                <i class="bi bi-pin-angle text-warning ms-1" title="Pinned Topic"></i>
                                            <?php endif; ?>

                                            <?php if ($topic->status == 'closed') : ?>
                                                <i class="bi bi-lock text-secondary ms-1" title="Closed Topic"></i>
                                            <?php endif; ?>

                                            <?php if (isset($topic->has_solution) && $topic->has_solution) : ?>
                                                <i class="bi bi-check-circle-fill text-success ms-1" title="Has Solution"></i>
                                            <?php endif; ?>
                                        </h5>

                                        <!-- Topic Metadata -->
                                        <div class="text-muted small mt-1">
                                            <span>By <?php echo $topic->author_name; ?></span> •
                                            <span><?php echo timeElapsed($topic->created_at); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Topic Excerpt - Limited to first 100 characters -->
                                <p class="text-muted small mb-0 d-none d-md-block">
                                    <?php echo strlen($topic->content) > 100 ? substr(strip_tags($topic->content), 0, 100) . '...' : strip_tags($topic->content); ?>
                                </p>
                            </div>

                            <!-- Topic Stats -->
                            <div class="col-md-4 p-3 bg-light border-start">
                                <div class="d-flex align-items-center h-100">
                                    <div class="text-center me-4 flex-fill">
                                        <div class="fs-4 fw-bold"><?php echo $topic->views; ?></div>
                                        <div class="text-muted small">views</div>
                                    </div>
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold"><?php echo $topic->reply_count; ?></div>
                                        <div class="text-muted small">replies</div>
                                    </div>
                                    <div class="text-center ms-4 flex-fill d-none d-lg-block">
                                        <div class="small">
                                            <i class="bi bi-clock me-1"></i>
                                            <?php
                                            $lastActivity = isset($topic->last_reply_at) && $topic->last_reply_at > $topic->created_at ?
                                                $topic->last_reply_at : $topic->created_at;
                                            echo timeElapsed($lastActivity);
                                            ?>
                                        </div>
                                        <div class="text-muted small">last activity</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination if needed -->
        <?php if (!empty($data['topics']) && count($data['topics']) > 10) : ?>
            <div class="card-footer bg-light">
                <nav aria-label="Topics pagination">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add custom styles -->
<style>
    .topic-row {
        transition: all 0.2s ease;
    }

    .topic-row:hover {
        background-color: rgba(0, 0, 0, 0.015);
    }

    .topic-status-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
</style>

<!-- Topic filtering and searching script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Topic filtering functionality
        const topicFilter = document.getElementById('topic-filter');
        const topicSort = document.getElementById('topic-sort');
        const categorySearch = document.getElementById('category-search');

        if (topicFilter) {
            topicFilter.addEventListener('change', filterTopics);
        }

        if (topicSort) {
            topicSort.addEventListener('change', filterTopics);
        }

        if (categorySearch) {
            categorySearch.addEventListener('input', filterTopics);
        }

        function filterTopics() {
            const filter = topicFilter.value;
            const sort = topicSort.value;
            const search = categorySearch.value.toLowerCase();

            // You'd typically do an AJAX request here to get filtered results
            // For now, we'll just simulate filtering with some UI feedback

            const topicList = document.getElementById('topic-list');
            const topics = topicList.querySelectorAll('.list-group-item:not(.py-5)');

            if (search.length > 0 || filter !== 'all') {
                console.log(`Filtering topics: filter=${filter}, sort=${sort}, search="${search}"`);

                // Show a "loading" state  
                topics.forEach(topic => {
                    topic.style.opacity = '0.6';
                });

                setTimeout(() => {
                    topics.forEach(topic => {
                        topic.style.opacity = '1';
                    });
                }, 500);
            }
        }
    });
</script>

<!-- Include community enhancements script -->
<script src="<?php echo URLROOT; ?>/public/js/community-enhancements.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>