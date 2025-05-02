<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forums">Forums</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Topics</li>
                </ol>
            </nav>
            <h1 class="mb-2">All Forum Topics</h1>
            <p class="text-muted">Browse all discussions in our forum</p>
        </div>
        <div class="col-lg-4 d-flex flex-column align-items-end justify-content-start">
            <!-- Search Bar -->
            <form action="<?php echo URLROOT; ?>/community/searchTopics" method="GET" class="w-100 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search forums..." name="search">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <?php if (isLoggedIn()) : ?>
                <a href="<?php echo URLROOT; ?>/community/createTopic" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create New Topic
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter and Sort Tools -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Navigation Tabs -->
                    <nav class="nav nav-pills nav-fill bg-light rounded p-1">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/community/forums">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Categories
                        </a>
                        <a class="nav-link active" href="<?php echo URLROOT; ?>/community/allTopics">
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
                    </nav>
                </div>
                <div class="col-lg-4 d-flex justify-content-lg-end mt-3 mt-lg-0">
                    <!-- Sort Options -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sort-down"></i>
                            Sort by: <?php
                                        if ($data['sort'] == 'recent') echo 'Most Recent';
                                        else if ($data['sort'] == 'popular') echo 'Most Popular';
                                        else if ($data['sort'] == 'views') echo 'Most Views';
                                        else if ($data['sort'] == 'replies') echo 'Most Replies';
                                        ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item <?php echo $data['sort'] == 'recent' ? 'active' : ''; ?>" href="?sort=recent">Most Recent</a></li>
                            <li><a class="dropdown-item <?php echo $data['sort'] == 'popular' ? 'active' : ''; ?>" href="?sort=popular">Most Popular</a></li>
                            <li><a class="dropdown-item <?php echo $data['sort'] == 'views' ? 'active' : ''; ?>" href="?sort=views">Most Views</a></li>
                            <li><a class="dropdown-item <?php echo $data['sort'] == 'replies' ? 'active' : ''; ?>" href="?sort=replies">Most Replies</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Topics List -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <?php if (empty($data['topics'])) : ?>
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No topics found</h5>
                    <p class="text-muted">Be the first one to start a conversation</p>
                    <?php if (isLoggedIn()) : ?>
                        <a href="<?php echo URLROOT; ?>/community/createTopic" class="btn btn-primary mt-2">Create New Topic</a>
                    <?php else : ?>
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary mt-2">Login to Create Topic</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($data['topics'] as $topic) : ?>
                        <a href="<?php echo URLROOT; ?>/community/topic/<?php echo $topic->slug; ?>"
                            class="list-group-item list-group-item-action p-3 position-relative">
                            <!-- Status Indicator -->
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
                            <div class="row g-0 ps-2">
                                <!-- User Avatar -->
                                <div class="col-auto d-none d-md-block me-3">
                                    <img src="<?php echo !empty($topic->profile_image) ? URLROOT . '/public/uploads/' . $topic->profile_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                        class="rounded-circle" width="48" height="48" alt="User avatar">
                                </div>

                                <!-- Topic Details -->
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo !empty($topic->profile_image) ? URLROOT . '/public/uploads/' . $topic->profile_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                                class="rounded-circle me-2 d-inline d-md-none" width="24" height="24" alt="User avatar">
                                            <span class="small text-muted"><?php echo $topic->author_name; ?></span>
                                            <span class="mx-2 text-muted">•</span>
                                            <span class="small text-muted"><?php echo timeElapsed($topic->created_at); ?></span>
                                        </div>
                                        <div>
                                            <?php if ($topic->status == 'pinned') : ?>
                                                <span class="badge bg-warning" title="Pinned Topic">
                                                    <i class="bi bi-pin-angle"></i> Pinned
                                                </span>
                                            <?php elseif ($topic->status == 'closed') : ?>
                                                <span class="badge bg-secondary" title="Closed Topic">
                                                    <i class="bi bi-lock"></i> Closed
                                                </span>
                                            <?php endif; ?>

                                            <?php if (isset($topic->has_solution) && $topic->has_solution): ?>
                                                <span class="badge bg-success" title="Solved Topic">
                                                    <i class="bi bi-check-circle"></i> Solved
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <h5 class="mb-1"><?php echo $topic->title; ?></h5>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-light text-dark me-2"><?php echo $topic->category_name; ?></span>
                                            <div class="d-flex align-items-center small text-muted">
                                                <div class="me-3">
                                                    <i class="bi bi-eye me-1"></i> <?php echo $topic->views; ?>
                                                </div>
                                                <div class="me-3">
                                                    <i class="bi bi-chat me-1"></i> <?php echo $topic->reply_count; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isset($topic->last_reply_at) && !empty($topic->last_reply_at)): ?>
                                            <div class="small text-muted text-end">
                                                <i class="bi bi-clock-history me-1"></i> Last reply: <?php echo timeElapsed($topic->last_reply_at); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (!empty($data['topics']) && $data['totalPages'] > 1): ?>
        <nav aria-label="Topics pagination">
            <ul class="pagination justify-content-center">
                <!-- Previous Button -->
                <li class="page-item <?php echo ($data['currentPage'] <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $data['currentPage'] - 1; ?>&sort=<?php echo $data['sort']; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                $startPage = max(1, $data['currentPage'] - 2);
                $endPage = min($data['totalPages'], $data['currentPage'] + 2);

                if ($startPage > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1&sort=' . $data['sort'] . '">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                }

                for ($i = $startPage; $i <= $endPage; $i++) {
                    echo '<li class="page-item ' . ($i == $data['currentPage'] ? 'active' : '') . '">
                            <a class="page-link" href="?page=' . $i . '&sort=' . $data['sort'] . '">' . $i . '</a>
                          </li>';
                }

                if ($endPage < $data['totalPages']) {
                    if ($endPage < $data['totalPages'] - 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $data['totalPages'] . '&sort=' . $data['sort'] . '">' . $data['totalPages'] . '</a></li>';
                }
                ?>

                <!-- Next Button -->
                <li class="page-item <?php echo ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $data['currentPage'] + 1; ?>&sort=<?php echo $data['sort']; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<style>
    .topic-status-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
    }

    .card {
        overflow: hidden;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .nav-pills .nav-link {
        color: #495057;
    }

    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>