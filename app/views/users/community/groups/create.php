<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include community CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/groups"><i class="bi bi-people me-1"></i> Groups</a></li>
            <li class="breadcrumb-item active">Create New Group</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex align-items-center">
                    <i class="bi bi-plus-circle text-primary me-2 fs-4"></i>
                    <h3 class="mb-0">Create New Group</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/community/createGroup" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Group Name</label>
                            <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name'] ?? ''; ?>" placeholder="Enter a name for your group" required>
                            <div class="invalid-feedback">
                                <?php echo $data['name_err'] ?? ''; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4" placeholder="Describe what your group is about" required><?php echo $data['description'] ?? ''; ?></textarea>
                            <div class="invalid-feedback">
                                <?php echo $data['description_err'] ?? ''; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image (Optional)</label>
                            <input type="file" class="form-control <?php echo (!empty($data['cover_image_err'])) ? 'is-invalid' : ''; ?>" id="cover_image" name="cover_image">
                            <div class="invalid-feedback">
                                <?php echo $data['cover_image_err'] ?? ''; ?>
                            </div>
                            <small class="form-text text-muted">
                                Recommended size: 1200x300 pixels. Max file size: 2MB.
                            </small>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="is_private" name="is_private" <?php echo (!empty($data['is_private']) && $data['is_private']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_private">Private Group</label>
                            <div class="form-text">
                                Private groups require admin approval for members to join and are not shown in public searches.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URLROOT; ?>/community/groups" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include community enhancements script -->
<script src="<?php echo URLROOT; ?>/public/js/community-enhancements.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>