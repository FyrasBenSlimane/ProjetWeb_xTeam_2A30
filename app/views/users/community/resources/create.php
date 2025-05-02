<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include community CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/community.css">

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/resources"><i class="bi bi-archive me-1"></i> Resources</a></li>
            <li class="breadcrumb-item active">Share New Resource</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex align-items-center">
                    <i class="bi bi-plus-circle text-primary me-2 fs-4"></i>
                    <h3 class="mb-0">Share New Resource</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/community/createResource" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo $data['title'] ?? ''; ?>" required>
                            <div class="invalid-feedback">
                                <?php echo $data['title_err'] ?? ''; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                            <?php echo $category->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?php echo $data['category_err'] ?? ''; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="resource_type" class="form-label">Resource Type</label>
                                <select class="form-select" id="resource_type" name="resource_type">
                                    <option value="article" <?php echo ($data['resource_type'] == 'article') ? 'selected' : ''; ?>>Article</option>
                                    <option value="tutorial" <?php echo ($data['resource_type'] == 'tutorial') ? 'selected' : ''; ?>>Tutorial</option>
                                    <option value="template" <?php echo ($data['resource_type'] == 'template') ? 'selected' : ''; ?>>Template</option>
                                    <option value="tool" <?php echo ($data['resource_type'] == 'tool') ? 'selected' : ''; ?>>Tool</option>
                                    <option value="other" <?php echo ($data['resource_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3" required><?php echo $data['description'] ?? ''; ?></textarea>
                            <div class="invalid-feedback">
                                <?php echo $data['description_err'] ?? ''; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content (Optional)</label>
                            <textarea class="form-control" id="content" name="content" rows="5"><?php echo $data['content'] ?? ''; ?></textarea>
                            <div class="form-text">
                                If your resource includes text content, enter it here. For templates, tutorials, or code snippets.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="external_link" class="form-label">External Link (Optional)</label>
                            <input type="url" class="form-control" id="external_link" name="external_link" value="<?php echo $data['external_link'] ?? ''; ?>" placeholder="https://">
                            <div class="form-text">
                                If this resource references an external website, enter the URL here.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">File Upload (Optional)</label>
                            <input type="file" class="form-control <?php echo (!empty($data['file_err'])) ? 'is-invalid' : ''; ?>" id="file" name="file">
                            <div class="invalid-feedback">
                                <?php echo $data['file_err'] ?? ''; ?>
                            </div>
                            <div class="form-text">
                                Upload files such as PDFs, documents, templates or code files. Max size: 5MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail Image (Optional)</label>
                            <input type="file" class="form-control <?php echo (!empty($data['thumbnail_err'])) ? 'is-invalid' : ''; ?>" id="thumbnail" name="thumbnail" accept="image/*">
                            <div class="invalid-feedback">
                                <?php echo $data['thumbnail_err'] ?? ''; ?>
                            </div>
                            <div class="form-text">
                                Add a thumbnail image to make your resource stand out. Recommended size: 800x400 pixels. Max size: 2MB.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tags (Optional)</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($data['tags'] as $tag) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="<?php echo $tag->id; ?>" id="tag<?php echo $tag->id; ?>" <?php echo (in_array($tag->id, $data['selected_tags'])) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tag<?php echo $tag->id; ?>">
                                            <?php echo $tag->name; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URLROOT; ?>/community/resources" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Share Resource</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include form validation script -->
<script src="<?php echo URLROOT; ?>/public/js/resource-validation.js"></script>
<!-- Include community enhancements script -->
<script src="<?php echo URLROOT; ?>/public/js/community-enhancements.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>