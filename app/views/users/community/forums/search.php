<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forums">Forums</a></li>
                    <li class="breadcrumb-item active">Search Results</li>
                </ol>
            </nav>

            <h1 class="mb-2">Search Results</h1>
            <p class="text-muted">
                <span class="fw-bold"><?php echo count($data['topics']); ?> results</span> for:
                <span class="fst-italic">"<?php echo htmlspecialchars($data['search']); ?>"</span>
            </p>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-end justify-content-start">
            <!-- Search Bar -->
            <form action="<?php echo URLROOT; ?>/community/searchTopics" method="GET" class="w-100 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search forums..."
                        name="search" value="<?php echo htmlspecialchars($data['search']); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between">
                        <!-- Sort Options -->
                        <div class="mb-3 mb-md-0">
                            <label for="sortOptions" class="form-label fw-bold">Sort by:</label>
                            <div class="btn-group" role="group" aria-label="Sort options">
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=relevance&filter=<?php echo $data['filter']; ?>"
                                    class="btn btn-<?php echo $data['sort'] == 'relevance' ? 'primary' : 'outline-secondary'; ?>">
                                    Relevance
                                </a>
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=recent&filter=<?php echo $data['filter']; ?>"
                                    class="btn btn-<?php echo $data['sort'] == 'recent' ? 'primary' : 'outline-secondary'; ?>">
                                    Recent
                                </a>
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=popular&filter=<?php echo $data['filter']; ?>"
                                    class="btn btn-<?php echo $data['sort'] == 'popular' ? 'primary' : 'outline-secondary'; ?>">
                                    Popular
                                </a>
                            </div>
                        </div>

                        <!-- Filter Options -->
                        <div>
                            <label for="filterOptions" class="form-label fw-bold">Filter by:</label>
                            <div class="btn-group" role="group" aria-label="Filter options">
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=<?php echo $data['sort']; ?>&filter=all"
                                    class="btn btn-<?php echo $data['filter'] == 'all' ? 'primary' : 'outline-secondary'; ?>">
                                    All Topics
                                </a>
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=<?php echo $data['sort']; ?>&filter=solved"
                                    class="btn btn-<?php echo $data['filter'] == 'solved' ? 'primary' : 'outline-secondary'; ?>">
                                    Solved
                                </a>
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=<?php echo $data['sort']; ?>&filter=open"
                                    class="btn btn-<?php echo $data['filter'] == 'open' ? 'primary' : 'outline-secondary'; ?>">
                                    Open
                                </a>
                                <a href="?search=<?php echo urlencode($data['search']); ?>&sort=<?php echo $data['sort']; ?>&filter=closed"
                                    class="btn btn-<?php echo $data['filter'] == 'closed' ? 'primary' : 'outline-secondary'; ?>">
                                    Closed
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($data['topics'])) : ?>
                        <div class="text-center py-5">
                            <i class="bi bi-search fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No results found</h5>
                            <p class="text-muted">Try different keywords or filters</p>
                            <a href="<?php echo URLROOT; ?>/community/forums" class="btn btn-outline-primary mt-2">
                                Back to Forums
                            </a>
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
                                    <div class="row ps-2">
                                        <div class="col-12">
                                            <!-- Category and Status -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light text-dark"><?php echo $topic->category_name; ?></span>
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

                                            <!-- Topic Title with Highlighted Search Term -->
                                            <h5 class="mb-2">
                                                <?php
                                                $highlightedTitle = str_ireplace(
                                                    $data['search'],
                                                    '<span class="highlight-text">' . $data['search'] . '</span>',
                                                    htmlspecialchars($topic->title)
                                                );
                                                echo $highlightedTitle;
                                                ?>
                                            </h5>

                                            <!-- Topic Excerpt with Highlighted Search Term -->
                                            <p class="mb-2 text-muted">
                                                <?php
                                                // Create excerpt from topic content
                                                $content = strip_tags($topic->content ?? '');
                                                $searchPos = stripos($content, $data['search']);

                                                if ($searchPos !== false) {
                                                    // Get excerpt centered around search term
                                                    $start = max(0, $searchPos - 50);
                                                    $length = 150;
                                                    $excerpt = substr($content, $start, $length);

                                                    // Add ellipsis if needed
                                                    if ($start > 0) {
                                                        $excerpt = '...' . $excerpt;
                                                    }
                                                    if (strlen($content) > $start + $length) {
                                                        $excerpt .= '...';
                                                    }

                                                    // Highlight search term
                                                    $highlightedExcerpt = str_ireplace(
                                                        $data['search'],
                                                        '<span class="highlight-text">' . $data['search'] . '</span>',
                                                        htmlspecialchars($excerpt)
                                                    );

                                                    echo $highlightedExcerpt;
                                                } else {
                                                    // If search term not found in content, show beginning of content
                                                    echo htmlspecialchars(substr($content, 0, 150)) . (strlen($content) > 150 ? '...' : '');
                                                }
                                                ?>
                                            </p>

                                            <!-- User and Stats -->
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo !empty($topic->profile_image) ? URLROOT . '/public/uploads/' . $topic->profile_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                                        class="rounded-circle me-2" width="24" height="24" alt="User avatar">
                                                    <span class="small text-muted"><?php echo $topic->author_name; ?></span>
                                                    <span class="mx-2 text-muted">•</span>
                                                    <span class="small text-muted"><?php echo timeElapsed($topic->created_at); ?></span>
                                                </div>
                                                <div class="d-flex align-items-center small text-muted">
                                                    <div class="me-3">
                                                        <i class="bi bi-eye me-1"></i> <?php echo $topic->views; ?>
                                                    </div>
                                                    <div>
                                                        <i class="bi bi-chat me-1"></i> <?php echo $topic->reply_count; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .topic-status-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
    }

    .highlight-text {
        background-color: rgba(255, 230, 0, 0.4);
        font-weight: bold;
        padding: 0 2px;
        border-radius: 2px;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>