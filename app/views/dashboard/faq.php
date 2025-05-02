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
                    <h1>FAQ Management</h1>
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
                            <a class="active" href="<?php echo URLROOT; ?>/dashboard/faq">FAQ Management</a>
                        </li>
                    </ul>
                </div>
                <div class="btn-download">
                    <a href="<?php echo URLROOT; ?>/dashboard/support" class="btn-link">
                        <i class='bx bx-arrow-back'></i>
                        <span>Back to Support</span>
                    </a>
                </div>
            </div>

            <!-- Flash Message -->
            <?php flash('faq_message'); ?>

            <!-- FAQ Management Tabs -->
            <div class="faq-management-tabs">
                <ul class="nav nav-tabs" id="faqTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs" type="button" role="tab" aria-controls="faqs" aria-selected="true">FAQs</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">Categories</button>
                    </li>
                    <li class="nav-item ms-auto" role="presentation">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                            <i class='bx bx-plus'></i> Add New FAQ
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="faqTabsContent">
                    <!-- FAQs Tab -->
                    <div class="tab-pane fade show active" id="faqs" role="tabpanel" aria-labelledby="faqs-tab">
                        <div class="table-data">
                            <div class="order">
                                <div class="head">
                                    <h3>Frequently Asked Questions</h3>
                                    <i class='bx bx-search'></i>
                                    <i class='bx bx-filter'></i>
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Question</th>
                                            <th>Category</th>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['faqs'])) : ?>
                                            <?php foreach ($data['faqs'] as $faq) : ?>
                                                <tr>
                                                    <td>#<?php echo $faq->id; ?></td>
                                                    <td><?php echo htmlspecialchars($faq->question); ?></td>
                                                    <td><?php echo htmlspecialchars($faq->category); ?></td>
                                                    <td><?php echo $faq->display_order; ?></td>
                                                    <td>
                                                        <?php if ($faq->is_published) : ?>
                                                            <span class="status status-published">Published</span>
                                                        <?php else : ?>
                                                            <span class="status status-draft">Draft</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="actions">
                                                            <a href="<?php echo URLROOT; ?>/dashboard/editFaq/<?php echo $faq->id; ?>" class="btn btn-sm btn-info" title="Edit FAQ"><i class='bx bx-edit'></i></a>
                                                            <a href="<?php echo URLROOT; ?>/dashboard/deleteFaq/<?php echo $faq->id; ?>" class="btn btn-sm btn-danger" title="Delete FAQ" onclick="return confirm('Are you sure you want to delete this FAQ? This action cannot be undone.');"><i class='bx bx-trash'></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No FAQs found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Tab -->
                    <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Add New Category</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?php echo URLROOT; ?>/dashboard/addFaqCategory" method="post">
                                            <div class="mb-3">
                                                <label for="categoryName" class="form-label">Category Name</label>
                                                <input type="text" class="form-control" id="categoryName" name="name" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add Category</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Existing Categories</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($data['categories'])) : ?>
                                            <ul class="list-group">
                                                <?php foreach ($data['categories'] as $category) : ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?php echo htmlspecialchars($category->name); ?>
                                                        <a href="<?php echo URLROOT; ?>/dashboard/deleteFaqCategory/<?php echo $category->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.');"><i class='bx bx-trash'></i></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else : ?>
                                            <p class="text-center">No categories found</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFaqModalLabel">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URLROOT; ?>/dashboard/addFaq" method="post" id="addFaqForm">
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="question" name="question" required>
                    </div>
                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer</label>
                        <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <?php if (!empty($data['categories'])) : ?>
                                <?php foreach ($data['categories'] as $category) : ?>
                                    <option value="<?php echo $category->name; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="order" name="order" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked>
                                <label class="form-check-label" for="is_published">Publish immediately</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addFaqForm" class="btn btn-primary">Add FAQ</button>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Management Styles -->
<style>
    /* Status Colors */
    .status-published { background: #1dd1a1; }
    .status-draft { background: #576574; }

    /* FAQ Management Tabs */
    .faq-management-tabs {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .faq-management-tabs .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 20px;
    }

    .faq-management-tabs .nav-link {
        color: #333;
        font-weight: 500;
        border: none;
        padding: 10px 15px;
        border-radius: 0;
        position: relative;
    }

    .faq-management-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom: 2px solid var(--primary);
    }

    .faq-management-tabs .nav-link:hover {
        color: var(--primary);
    }

    /* Actions Styling */
    .actions {
        display: flex;
        gap: 5px;
    }

    .actions .btn {
        padding: 0.25rem 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .actions .btn i {
        font-size: 1.2rem;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>