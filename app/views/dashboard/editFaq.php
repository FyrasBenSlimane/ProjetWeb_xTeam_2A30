<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <!-- Include the sidebar -->
    <?php require APPROOT . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content -->
    <section id="content">
        <!-- Top Navigation -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="profile">
                <img src="<?php echo URLROOT; ?>/public/images/default-profile.png">
            </a>
        </nav>

        <!-- Main Content Title -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Edit FAQ</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard/support">Support</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard/faq">FAQ Management</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Edit FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="btn-download">
                    <a href="<?php echo URLROOT; ?>/dashboard/faq" class="btn-link">
                        <i class='bx bx-arrow-back'></i>
                        <span>Back to FAQs</span>
                    </a>
                </div>
            </div>

            <!-- Flash Message -->
            <?php flash('faq_message'); ?>

            <!-- Edit FAQ Form -->
            <div class="edit-faq-form">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit FAQ #<?php echo $data['id']; ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/dashboard/editFaq/<?php echo $data['id']; ?>" method="post">
                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <input type="text" class="form-control <?php echo (!empty($data['question_err'])) ? 'is-invalid' : ''; ?>" id="question" name="question" value="<?php echo $data['question']; ?>" required>
                                <div class="invalid-feedback"><?php echo $data['question_err'] ?? ''; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea class="form-control <?php echo (!empty($data['answer_err'])) ? 'is-invalid' : ''; ?>" id="answer" name="answer" rows="5" required><?php echo $data['answer']; ?></textarea>
                                <div class="invalid-feedback"><?php echo $data['answer_err'] ?? ''; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php if (!empty($data['categories'])) : ?>
                                        <?php foreach ($data['categories'] as $category) : ?>
                                            <option value="<?php echo $category->name; ?>" <?php echo ($data['category'] == $category->name) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback"><?php echo $data['category_err'] ?? ''; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="order" class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="order" name="order" value="<?php echo $data['order']; ?>" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" <?php echo ($data['is_published']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_published">Published</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo URLROOT; ?>/dashboard/faq" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update FAQ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </section>
</div>

<!-- Edit FAQ Styles -->
<style>
    .edit-faq-form {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .edit-faq-form .card {
        border: none;
        box-shadow: none;
    }

    .edit-faq-form .card-header {
        background-color: transparent;
        border-bottom: 1px solid #eee;
        padding-left: 0;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>