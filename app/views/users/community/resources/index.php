<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include community CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <div class="row mb-4 community-header">
        <div class="col-md-8">
            <h1 class="mb-3">Community Resources</h1>
            <p class="text-muted">Discover and share valuable resources with the community.</p>
            <?php flash('resource_message'); ?>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if (isLoggedIn()) : ?>
                <a href="<?php echo URLROOT; ?>/community/createResource" class="btn btn-primary btn-community-primary">
                    <i class="bi bi-plus-circle"></i> Share New Resource
                </a>
            <?php else : ?>
                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary btn-community-outline">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login to Share Resources
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/community/searchResources" method="get" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search resources">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="sort">
                        <option value="newest">Newest</option>
                        <option value="popular">Most Downloaded</option>
                        <option value="rated">Highest Rated</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories -->
    <div class="row mb-4">
        <?php foreach ($data['categories'] as $category) : ?>
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-<?php echo $category->icon ?? 'folder'; ?> fs-1 text-primary"></i>
                        </div>
                        <h5 class="card-title"><?php echo $category->name; ?></h5>
                        <p class="card-text small text-muted"><?php echo $category->description; ?></p>
                        <a href="<?php echo URLROOT; ?>/community/resourceCategory/<?php echo $category->id; ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Featured Resources -->
    <h4 class="mb-3">Featured Resources</h4>
    <div class="row mb-5">
        <?php if (empty($data['featuredResources'])) : ?>
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <p class="mb-0">No featured resources yet. Be the first to share one!</p>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($data['featuredResources'] as $resource) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="position-relative">
                            <?php if (!empty($resource->thumbnail)) : ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/resource_thumbnails/<?php echo $resource->thumbnail; ?>" class="card-img-top" alt="Resource Thumbnail" style="height: 160px; object-fit: cover;">
                            <?php else : ?>
                                <div class="bg-light text-center py-4">
                                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <span class="position-absolute top-0 start-0 badge bg-primary m-2">
                                <?php echo $resource->resource_type; ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo !empty($resource->author_image) ? URLROOT . '/public/uploads/' . $resource->author_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-2" width="30" height="30" alt="Author">
                                <small class="text-muted">By <?php echo $resource->author_name; ?></small>
                            </div>
                            <h5 class="card-title">
                                <a href="<?php echo URLROOT; ?>/community/resource/<?php echo $resource->id; ?>" class="text-decoration-none">
                                    <?php echo $resource->title; ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                <?php echo strlen($resource->description) > 100 ? substr($resource->description, 0, 100) . '...' : $resource->description; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="badge bg-light text-dark">
                                    <?php echo $resource->category_name; ?>
                                </div>
                                <div>
                                    <?php
                                    $rating = $resource->avg_rating ?? 0;
                                    $stars = round($rating * 2) / 2; // Round to nearest 0.5
                                    ?>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($stars >= $i): ?>
                                                <i class="bi bi-star-fill text-warning"></i>
                                            <?php elseif ($stars >= $i - 0.5): ?>
                                                <i class="bi bi-star-half text-warning"></i>
                                            <?php else: ?>
                                                <i class="bi bi-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <small class="ms-1"><?php echo number_format($rating, 1); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-download"></i> <?php echo $resource->downloads; ?> downloads
                            </small>
                            <a href="<?php echo URLROOT; ?>/community/resource/<?php echo $resource->id; ?>" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Recent Resources -->
    <h4 class="mb-3">Recent Resources</h4>
    <div class="row">
        <?php if (empty($data['recentResources'])) : ?>
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <p class="mb-0">No resources available yet. Be the first to share one!</p>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($data['recentResources'] as $resource) : ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <?php if (!empty($resource->thumbnail)) : ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/resource_thumbnails/<?php echo $resource->thumbnail; ?>" class="card-img-top" alt="Resource Thumbnail" style="height: 120px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title fs-6">
                                <a href="<?php echo URLROOT; ?>/community/resource/<?php echo $resource->id; ?>" class="text-decoration-none">
                                    <?php echo $resource->title; ?>
                                </a>
                            </h5>
                            <div class="small text-muted mb-2">
                                <span class="badge bg-light text-dark me-2"><?php echo $resource->resource_type; ?></span>
                                <span><?php echo $resource->category_name; ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <?php echo timeElapsed($resource->created_at); ?>
                                </small>
                                <div class="stars small">
                                    <?php
                                    $rating = $resource->avg_rating ?? 0;
                                    $stars = round($rating * 2) / 2; // Round to nearest 0.5
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($stars >= $i):
                                            echo '<i class="bi bi-star-fill text-warning"></i>';
                                        elseif ($stars >= $i - 0.5):
                                            echo '<i class="bi bi-star-half text-warning"></i>';
                                        else:
                                            echo '<i class="bi bi-star text-warning"></i>';
                                        endif;
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Include community enhancements script -->
<script src="<?php echo URLROOT; ?>/public/js/community-enhancements.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>