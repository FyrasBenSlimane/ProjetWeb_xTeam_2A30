<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/resources">Resources</a></li>
            <li class="breadcrumb-item active"><?php echo $data['resource']->title; ?></li>
        </ol>
    </nav>

    <?php flash('rating_message'); ?>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo !empty($data['author']->profile_image) ? URLROOT . '/public/uploads/' . $data['author']->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-2" width="40" height="40" alt="Author">
                            <div>
                                <h6 class="mb-0">Shared by <?php echo $data['author']->name; ?></h6>
                                <small class="text-muted"><?php echo date('F j, Y', strtotime($data['resource']->created_at)); ?></small>
                            </div>
                        </div>

                        <?php if (isLoggedIn() && ($_SESSION['user_id'] == $data['resource']->user_id || isAdmin())) : ?>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="resourceActions" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="resourceActions">
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/community/editResource/<?php echo $data['resource']->id; ?>">Edit Resource</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/community/deleteResource/<?php echo $data['resource']->id; ?>" onclick="return confirm('Are you sure you want to delete this resource?')">Delete Resource</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Resource Header -->
                    <div class="text-center mb-4">
                        <?php if (!empty($data['resource']->thumbnail)) : ?>
                            <img src="<?php echo URLROOT; ?>/public/uploads/resource_thumbnails/<?php echo $data['resource']->thumbnail; ?>" class="img-fluid rounded mb-3" alt="Resource Thumbnail" style="max-height: 300px;">
                        <?php endif; ?>
                        <h1 class="display-5"><?php echo $data['resource']->title; ?></h1>
                        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                            <span class="badge bg-primary"><?php echo $data['resource']->resource_type; ?></span>
                            <span class="badge bg-light text-dark"><?php echo $data['resource']->category_name; ?></span>
                            <?php if (!empty($data['tags'])) : ?>
                                <?php foreach ($data['tags'] as $tag) : ?>
                                    <span class="badge bg-secondary"><?php echo $tag->name; ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Ratings -->
                        <div class="mb-3">
                            <?php
                            $rating = $data['resource']->avg_rating ?? 0;
                            $stars = round($rating * 2) / 2; // Round to nearest 0.5
                            ?>
                            <div class="stars d-inline-block">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($stars >= $i): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php elseif ($stars >= $i - 0.5): ?>
                                        <i class="bi bi-star-half text-warning"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="ms-2">
                                <?php echo number_format($rating, 1); ?>
                                (<?php echo $data['resource']->rating_count ?? 0; ?> ratings)
                            </span>
                        </div>
                    </div>

                    <!-- Resource Description -->
                    <div class="mb-4">
                        <h4>Description</h4>
                        <p><?php echo nl2br(htmlspecialchars($data['resource']->description)); ?></p>
                    </div>

                    <!-- Resource Content -->
                    <?php if (!empty($data['resource']->content)) : ?>
                        <div class="mb-4">
                            <h4>Content</h4>
                            <div class="content-area p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($data['resource']->content)); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (!empty($data['resource']->file_path)) : ?>
                            <a href="<?php echo URLROOT; ?>/community/downloadResource/<?php echo $data['resource']->id; ?>" class="btn btn-primary">
                                <i class="bi bi-download"></i> Download
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($data['resource']->external_link)) : ?>
                            <a href="<?php echo htmlspecialchars($data['resource']->external_link); ?>" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-link-45deg"></i> Visit External Link
                            </a>
                        <?php endif; ?>

                        <?php if (isLoggedIn() && $_SESSION['user_id'] != $data['resource']->user_id) : ?>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#rateResourceModal">
                                <i class="bi bi-star"></i> Rate This Resource
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between text-muted">
                    <div>
                        <i class="bi bi-download"></i> <?php echo $data['resource']->downloads; ?> downloads
                    </div>
                    <div>
                        <i class="bi bi-eye"></i> <?php echo $data['resource']->views; ?> views
                    </div>
                </div>
            </div>

            <!-- Ratings & Reviews Section -->
            <h4 class="mb-3">Ratings & Reviews</h4>
            <?php if (empty($data['ratings'])) : ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <p class="mb-0">No ratings yet. Be the first to rate this resource.</p>
                    </div>
                </div>
            <?php else : ?>
                <div class="card shadow-sm mb-4">
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['ratings'] as $rating) : ?>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="<?php echo !empty($rating->profile_image) ? URLROOT . '/public/uploads/' . $rating->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-2" width="32" height="32" alt="User">
                                    <div>
                                        <h6 class="mb-0"><?php echo $rating->name; ?></h6>
                                        <div class="d-flex align-items-center">
                                            <div class="stars small">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($rating->rating >= $i): ?>
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-star text-warning"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="ms-2 small text-muted"><?php echo timeElapsed($rating->created_at); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($rating->comment)) : ?>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($rating->comment)); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">About the Author</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo !empty($data['author']->profile_image) ? URLROOT . '/public/uploads/' . $data['author']->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-3" width="64" height="64" alt="Author">
                        <div>
                            <h5 class="mb-1"><?php echo $data['author']->name; ?></h5>
                            <p class="text-muted mb-0">Member since <?php echo date('M Y', strtotime($data['author']->created_at)); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="<?php echo URLROOT; ?>/community/userResources/<?php echo $data['author']->id; ?>" class="btn btn-sm btn-outline-primary w-100">
                        View All Resources by This User
                    </a>
                </div>
            </div>

            <!-- Resource Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Resource Details</h5>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Type:</span>
                        <span class="fw-bold"><?php echo ucfirst($data['resource']->resource_type); ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Category:</span>
                        <span class="fw-bold"><?php echo $data['resource']->category_name; ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Published:</span>
                        <span class="fw-bold"><?php echo date('M j, Y', strtotime($data['resource']->created_at)); ?></span>
                    </div>
                    <?php if (!empty($data['resource']->file_path)) : ?>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Downloads:</span>
                            <span class="fw-bold"><?php echo $data['resource']->downloads; ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Views:</span>
                        <span class="fw-bold"><?php echo $data['resource']->views; ?></span>
                    </div>
                </div>
            </div>

            <!-- Related Resources -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Related Resources</h5>
                </div>
                <div class="list-group list-group-flush">
                    <!-- This would be populated from controller with related resources -->
                    <div class="list-group-item text-center p-3">
                        <p class="mb-0 text-muted">No related resources found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rating Modal -->
<?php if (isLoggedIn()) : ?>
    <div class="modal fade" id="rateResourceModal" tabindex="-1" aria-labelledby="rateResourceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo URLROOT; ?>/community/rateResource/<?php echo $data['resource']->id; ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rateResourceModalLabel">Rate This Resource</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>
                            <div class="rating-selector d-flex gap-2 fs-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                        <label class="form-check-label star-label" for="rating<?php echo $i; ?>">
                                            <i class="bi bi-star"></i>
                                        </label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Review (Optional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Write your review here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Star rating functionality
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-label');

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                for (let i = 0; i <= index; i++) {
                    stars[i].innerHTML = '<i class="bi bi-star-fill"></i>';
                }
                for (let i = index + 1; i < stars.length; i++) {
                    stars[i].innerHTML = '<i class="bi bi-star"></i>';
                }
            });

            star.addEventListener('click', () => {
                const radioBtn = document.getElementById('rating' + (index + 1));
                radioBtn.checked = true;

                for (let i = 0; i <= index; i++) {
                    stars[i].innerHTML = '<i class="bi bi-star-fill"></i>';
                }
            });
        });

        const ratingSelector = document.querySelector('.rating-selector');
        if (ratingSelector) {
            ratingSelector.addEventListener('mouseout', () => {
                stars.forEach((star, index) => {
                    const radioBtn = document.getElementById('rating' + (index + 1));
                    if (radioBtn.checked) {
                        for (let i = 0; i <= index; i++) {
                            stars[i].innerHTML = '<i class="bi bi-star-fill"></i>';
                        }
                        for (let i = index + 1; i < stars.length; i++) {
                            stars[i].innerHTML = '<i class="bi bi-star"></i>';
                        }
                    } else if (!document.querySelector('input[name="rating"]:checked')) {
                        star.innerHTML = '<i class="bi bi-star"></i>';
                    }
                });
            });
        }
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>