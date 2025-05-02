<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forums">Forums</a></li>
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/topic/<?php echo $data['topic']->slug; ?>"><?php echo $data['topic']->title; ?></a></li>
            <li class="breadcrumb-item active">Edit Topic</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="mb-0">Edit Topic</h3>
                </div>
                <div class="card-body">
                    <?php flash('topic_message'); ?>

                    <form action="<?php echo URLROOT; ?>/community/editTopic/<?php echo $data['id']; ?>" method="post" id="editTopicForm" novalidate>
                        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                        <div class="mb-3">
                            <label for="title" class="form-label"><i class="bi bi-type-h1 me-1"></i> Topic Title</label>
                            <input type="text" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo $data['title']; ?>" placeholder="Enter a descriptive title">
                            <div class="invalid-feedback">
                                <?php echo $data['title_err'] ?? ''; ?>
                            </div>
                            <small id="title-char-count" class="form-text text-muted">100 characters remaining</small>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label"><i class="bi bi-grid me-1"></i> Category</label>
                            <select class="form-select <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id">
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

                        <div class="mb-4">
                            <label for="content" class="form-label"><i class="bi bi-file-text me-1"></i> Content</label>
                            <textarea class="form-control rich-editor <?php echo (!empty($data['content_err'])) ? 'is-invalid' : ''; ?>" id="content" name="content" rows="10" placeholder="Describe your topic in detail"><?php echo $data['content']; ?></textarea>
                            <div class="invalid-feedback">
                                <?php echo $data['content_err'] ?? ''; ?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">
                                    Be clear and descriptive. Include all necessary details for others to understand your topic.
                                    <span class="text-danger">Minimum 20 characters required.</span>
                                </small>
                                <small id="content-char-count" class="form-text text-muted">0 characters</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URLROOT; ?>/community/topic/<?php echo $data['topic']->slug; ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submit-topic">Update Topic</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include custom edit validation script -->
<script src="<?php echo URLROOT; ?>/public/js/edit-topic-validation.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/form-progress.js"></script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>