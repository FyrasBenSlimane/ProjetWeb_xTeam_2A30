<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include community CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <div class="row mb-4 community-header">
        <div class="col-md-8">
            <h1 class="mb-3">Community Groups</h1>
            <p class="text-muted">Join groups based on your interests and connect with like-minded people.</p>
            <?php flash('group_message'); ?>
        </div>
        <div class="col-md-4 text-md-end">
            <?php if (isLoggedIn()) : ?>
                <a href="<?php echo URLROOT; ?>/community/createGroup" class="btn btn-primary btn-community-primary">
                    <i class="bi bi-plus-circle"></i> Create New Group
                </a>
            <?php else : ?>
                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary btn-community-outline">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login to Create Group
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/community/searchGroups" method="get" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search groups by name or description">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="sort">
                        <option value="newest">Newest First</option>
                        <option value="popular">Most Members</option>
                        <option value="active">Most Active</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Groups -->
    <h4 class="mb-3">Featured Groups</h4>
    <div class="row mb-5">
        <?php if (empty($data['featuredGroups'])) : ?>
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <p class="mb-0">No featured groups yet. Be the first to create one!</p>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($data['featuredGroups'] as $group) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="position-relative">
                            <?php if (!empty($group->cover_image)) : ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/group_covers/<?php echo $group->cover_image; ?>" class="card-img-top" alt="Group Cover" style="height: 120px; object-fit: cover;">
                            <?php else : ?>
                                <div class="bg-light text-center py-4">
                                    <i class="bi bi-people fs-1 text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <?php if ($group->is_private) : ?>
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2" title="Private Group">
                                    <i class="bi bi-lock"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo !empty($group->creator_image) ? URLROOT . '/public/uploads/' . $group->creator_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-2" width="30" height="30" alt="Creator">
                                <small class="text-muted">Created by <?php echo $group->creator_name; ?></small>
                            </div>
                            <h5 class="card-title">
                                <a href="<?php echo URLROOT; ?>/community/group/<?php echo $group->slug; ?>" class="text-decoration-none">
                                    <?php echo $group->name; ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                <?php echo strlen($group->description) > 100 ? substr($group->description, 0, 100) . '...' : $group->description; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-people-fill"></i> <?php echo $group->member_count; ?> members
                            </span>
                            <a href="<?php echo URLROOT; ?>/community/group/<?php echo $group->slug; ?>" class="btn btn-sm btn-outline-primary">View Group</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Recent Groups -->
    <h4 class="mb-3">Recent Groups</h4>
    <div class="row">
        <?php if (empty($data['recentGroups'])) : ?>
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-4">
                        <p class="mb-0">No groups available yet. Be the first to create one!</p>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php foreach ($data['recentGroups'] as $group) : ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="position-relative">
                            <?php if (!empty($group->cover_image)) : ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/group_covers/<?php echo $group->cover_image; ?>" class="card-img-top" alt="Group Cover" style="height: 100px; object-fit: cover;">
                            <?php else : ?>
                                <div class="bg-light text-center py-3">
                                    <i class="bi bi-people fs-2 text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <?php if ($group->is_private) : ?>
                                <span class="position-absolute top-0 end-0 badge bg-warning m-2" title="Private Group">
                                    <i class="bi bi-lock"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fs-6">
                                <a href="<?php echo URLROOT; ?>/community/group/<?php echo $group->slug; ?>" class="text-decoration-none">
                                    <?php echo $group->name; ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-0">
                                <?php echo strlen($group->description) > 60 ? substr($group->description, 0, 60) . '...' : $group->description; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white small">
                            <span class="text-muted">
                                <i class="bi bi-people-fill"></i> <?php echo $group->member_count; ?> members
                            </span>
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