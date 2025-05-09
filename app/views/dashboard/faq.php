<?php

/**
 * Enhanced FAQ Management Dashboard
 * This file displays the improved FAQ management interface in the admin dashboard
 */

// Set content to be passed to dashboard layout
ob_start();
?>

<!-- Main Content -->
<section id="content">
    <!-- Top Navigation -->
    <nav>
        <i class='bx bx-menu'></i>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search..." id="dashboardSearch">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <a href="#" class="profile">
            <img src="<?php echo URL_ROOT; ?>/public/images/default-profile.png">
        </a>
    </nav>

    <!-- Main Content Title -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>FAQ Management</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a href="<?php echo URL_ROOT; ?>/dashboard/support">Support</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="<?php echo URL_ROOT; ?>/dashboard/faq">FAQ Management</a>
                    </li>
                </ul>
            </div>
            <div class="btn-download">
                <a href="<?php echo URL_ROOT; ?>/dashboard/support" class="btn-link">
                    <i class='bx bx-arrow-back'></i>
                    <span>Back to Support</span>
                </a>
            </div>
        </div>

        <!-- Flash Message -->
        <?php flash('faq_message'); ?>

        <!-- FAQ Management Interface -->
        <div class="faq-management-tabs">
            <!-- FAQ Statistics Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card faq-stat-card">
                        <div class="card-body">
                            <div class="stat-icon" style="background-color: rgba(var(--primary-rgb), 0.1); color: var(--primary);">
                                <i class='bx bx-question-mark'></i>
                            </div>
                            <h3 class="stat-value"><?php echo count($data['faqs']); ?></h3>
                            <p class="stat-label">Total FAQs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card faq-stat-card">
                        <div class="card-body">
                            <div class="stat-icon" style="background-color: rgba(var(--success-rgb), 0.1); color: var(--success);">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <h3 class="stat-value">
                                <?php
                                $publishedCount = 0;
                                foreach ($data['faqs'] as $faq) {
                                    if (isset($faq->is_published) && $faq->is_published) {
                                        $publishedCount++;
                                    }
                                }
                                echo $publishedCount;
                                ?>
                            </h3>
                            <p class="stat-label">Published</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card faq-stat-card">
                        <div class="card-body">
                            <div class="stat-icon" style="background-color: rgba(var(--warning-rgb), 0.1); color: var(--warning);">
                                <i class='bx bx-edit-alt'></i>
                            </div>
                            <h3 class="stat-value">
                                <?php echo count($data['faqs']) - $publishedCount; ?>
                            </h3>
                            <p class="stat-label">Drafts</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card faq-stat-card">
                        <div class="card-body">
                            <div class="stat-icon" style="background-color: rgba(var(--info-rgb), 0.1); color: var(--info);">
                                <i class='bx bx-category'></i>
                            </div>
                            <h3 class="stat-value"><?php echo count($data['categories']); ?></h3>
                            <p class="stat-label">Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Search Bar -->
            <div class="faq-search mb-4">
                <i class='bx bx-search'></i>
                <input type="text" id="faqDashboardSearch" placeholder="Search FAQs...">
            </div>

            <!-- Main Tabs Navigation -->
            <ul class="nav nav-tabs" id="faqTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs" type="button" role="tab" aria-controls="faqs" aria-selected="true">
                        <i class='bx bx-list-ul'></i> All FAQs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                        <i class='bx bx-category-alt'></i> Categories
                    </button>
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
                    <!-- Filter Controls -->
                    <div class="row mb-3 mt-3">
                        <div class="col-md-8 d-flex gap-2">
                            <button class="btn btn-sm btn-outline filter-btn active" data-filter="all">All</button>
                            <button class="btn btn-sm btn-outline filter-btn" data-filter="published">
                                <i class='bx bx-check-circle'></i> Published
                            </button>
                            <button class="btn btn-sm btn-outline filter-btn" data-filter="draft">
                                <i class='bx bx-edit-alt'></i> Drafts
                            </button>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="faqCategoryFilter">
                                <option value="all">All Categories</option>
                                <?php if (!empty($data['categories'])) : ?>
                                    <?php foreach ($data['categories'] as $category) : ?>
                                        <option value="<?php echo htmlspecialchars($category->name); ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- FAQ Table with Responsive Design -->
                    <div class="table-responsive">
                        <table class="faq-table" id="faqTable">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th style="width: 45%;">Question</th>
                                    <th>Category</th>
                                    <th style="width: 80px;">Order</th>
                                    <th>Status</th>
                                    <th style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['faqs'])) : ?>
                                    <?php foreach ($data['faqs'] as $faq) : ?>
                                        <tr data-status="<?php echo isset($faq->is_published) && $faq->is_published ? 'published' : 'draft'; ?>"
                                            data-category="<?php echo htmlspecialchars($faq->category); ?>">
                                            <td>#<?php echo $faq->id; ?></td>
                                            <td class="question-cell"><?php echo htmlspecialchars($faq->question); ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($faq->category); ?></span>
                                            </td>
                                            <td><?php echo isset($faq->display_order) ? $faq->display_order : '0'; ?></td>
                                            <td>
                                                <?php if (isset($faq->is_published) && $faq->is_published) : ?>
                                                    <span class="status status-published">
                                                        <i class='bx bx-check-circle'></i> Published
                                                    </span>
                                                <?php else : ?>
                                                    <span class="status status-draft">
                                                        <i class='bx bx-edit-alt'></i> Draft
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="faq-actions">
                                                    <a href="<?php echo URL_ROOT; ?>/dashboard/editFaq/<?php echo $faq->id; ?>" class="btn btn-sm btn-info" title="Edit FAQ">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-primary view-faq-btn" title="Preview FAQ" data-bs-toggle="modal" data-bs-target="#viewFaqModal" data-id="<?php echo $faq->id; ?>"
                                                        data-question="<?php echo htmlspecialchars($faq->question); ?>"
                                                        data-answer="<?php echo htmlspecialchars($faq->answer); ?>"
                                                        data-category="<?php echo htmlspecialchars($faq->category); ?>">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="<?php echo URL_ROOT; ?>/dashboard/deleteFaq/<?php echo $faq->id; ?>" class="btn btn-sm btn-danger" title="Delete FAQ"
                                                        onclick="return confirm('Are you sure you want to delete this FAQ? This action cannot be undone.');">
                                                        <i class='bx bx-trash'></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            <div class="empty-state">
                                                <i class='bx bx-info-circle'></i>
                                                <p>No FAQs found</p>
                                                <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                                                    <i class='bx bx-plus'></i> Add Your First FAQ
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Categories Tab -->
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <div class="card faq-category-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Add New Category</h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo URL_ROOT; ?>/dashboard/addFaqCategory" method="post" id="addCategoryForm">
                                        <div class="mb-3">
                                            <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="categoryName" name="name" required>
                                            <div class="form-text">Category names should be clear, concise, and descriptive.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoryDescription" class="form-label">Description (Optional)</label>
                                            <textarea class="form-control" id="categoryDescription" name="description" rows="3" placeholder="Briefly describe this category..."></textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class='bx bx-plus'></i> Add Category
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card faq-category-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Existing Categories</h5>
                                    <span class="badge bg-primary"><?php echo count($data['categories']); ?> Categories</span>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($data['categories'])) : ?>
                                        <div class="faq-category-list">
                                            <?php foreach ($data['categories'] as $category) : ?>
                                                <div class="category-item">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($category->name); ?></h6>
                                                        <?php
                                                        // Count FAQs in this category
                                                        $faqCount = 0;
                                                        foreach ($data['faqs'] as $faq) {
                                                            if ($faq->category === $category->name) {
                                                                $faqCount++;
                                                            }
                                                        }
                                                        ?>
                                                        <small class="text-muted"><?php echo $faqCount; ?> FAQs</small>
                                                    </div>
                                                    <div class="category-actions">
                                                        <a href="<?php echo URL_ROOT; ?>/dashboard/editFaqCategory/<?php echo $category->id; ?>"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="Edit Category">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                        <a href="<?php echo URL_ROOT; ?>/dashboard/deleteFaqCategory/<?php echo $category->id; ?>"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete Category"
                                                            onclick="return confirm('Are you sure you want to delete this category? This will not delete the FAQs in this category but will leave them uncategorized.');">
                                                            <i class='bx bx-trash'></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="text-center py-4">
                                            <i class='bx bx-category' style="font-size: 3rem; color: var(--grey-3);"></i>
                                            <p class="mt-2 text-muted">No categories found</p>
                                            <p class="small">Categories help organize your FAQs for better navigation.</p>
                                        </div>
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

<!-- Add FAQ Modal - Enhanced with rich text editor -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFaqModalLabel">
                    <i class='bx bx-plus-circle me-2'></i> Add New FAQ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo URL_ROOT; ?>/dashboard/addFaq" method="post" id="addFaqForm" class="faq-form">
                    <div class="mb-3">
                        <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="question" name="question" required placeholder="Enter a frequently asked question...">
                        <div class="form-text">Make the question clear and specific.</div>
                    </div>

                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
                        <div class="faq-editor">
                            <div class="faq-editor-toolbar">
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="bold" title="Bold">
                                    <i class='bx bx-bold'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="italic" title="Italic">
                                    <i class='bx bx-italic'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="underline" title="Underline">
                                    <i class='bx bx-underline'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="link" title="Add Link">
                                    <i class='bx bx-link'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="list-ul" title="Bullet List">
                                    <i class='bx bx-list-ul'></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline format-btn" data-format="list-ol" title="Numbered List">
                                    <i class='bx bx-list-ol'></i>
                                </button>
                            </div>
                            <textarea class="form-control faq-editor-content" id="answer" name="answer" rows="8" required placeholder="Provide a helpful, detailed answer..."></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <?php if (!empty($data['categories'])) : ?>
                                        <?php foreach ($data['categories'] as $category) : ?>
                                            <option value="<?php echo $category->name; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Categorize your FAQ for better organization.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="order" name="order" value="0" min="0">
                                <div class="form-text">Lower numbers appear first in lists.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked>
                        <label class="form-check-label" for="is_published">Publish immediately</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addFaqForm" class="btn btn-primary">
                    <i class='bx bx-save'></i> Save FAQ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View FAQ Modal -->
<div class="modal fade" id="viewFaqModal" tabindex="-1" aria-labelledby="viewFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFaqModalLabel">
                    <i class='bx bx-info-circle me-2'></i> FAQ Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="faq-preview">
                    <div class="mb-3">
                        <span class="badge category-badge"></span>
                    </div>
                    <h4 class="preview-question mb-3"></h4>
                    <div class="preview-answer"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary edit-link">
                    <i class='bx bx-edit'></i> Edit FAQ
                </a>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Management JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const faqRows = document.querySelectorAll('#faqTable tbody tr');
        const categoryFilter = document.getElementById('faqCategoryFilter');
        const searchInput = document.getElementById('faqDashboardSearch');

        // Handle status filters
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-filter');

                // Update active state
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Apply filters
                applyFilters();
            });
        });

        // Handle category filter changes
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                applyFilters();
            });
        }

        // Handle search input
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                applyFilters();
            });
        }

        // Function to apply all active filters
        function applyFilters() {
            const activeStatusFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            const activeCategoryFilter = categoryFilter ? categoryFilter.value : 'all';
            const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';

            faqRows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowCategory = row.getAttribute('data-category');
                const rowQuestion = row.querySelector('.question-cell').textContent.toLowerCase();

                // Check if row matches all active filters
                const matchesStatus = activeStatusFilter === 'all' || rowStatus === activeStatusFilter;
                const matchesCategory = activeCategoryFilter === 'all' || rowCategory === activeCategoryFilter;
                const matchesSearch = searchQuery === '' || rowQuestion.includes(searchQuery);

                // Show/hide row based on filter matches
                if (matchesStatus && matchesCategory && matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // FAQ Preview Modal
        const viewFaqButtons = document.querySelectorAll('.view-faq-btn');

        viewFaqButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = document.getElementById('viewFaqModal');
                const question = this.getAttribute('data-question');
                const answer = this.getAttribute('data-answer');
                const category = this.getAttribute('data-category');
                const id = this.getAttribute('data-id');

                // Set modal content
                modal.querySelector('.preview-question').textContent = question;
                modal.querySelector('.preview-answer').innerHTML = answer;
                modal.querySelector('.category-badge').textContent = category;
                modal.querySelector('.category-badge').className = 'badge category-badge bg-light text-dark';

                // Update edit link
                modal.querySelector('.edit-link').href = `<?php echo URL_ROOT; ?>/dashboard/editFaq/${id}`;
            });
        });

        // Rich Text Editor Functionality
        const formatButtons = document.querySelectorAll('.format-btn');
        const editor = document.getElementById('answer');

        formatButtons.forEach(button => {
            button.addEventListener('click', function() {
                const format = this.getAttribute('data-format');
                const editorElement = document.getElementById('answer');

                // Get selection
                const start = editorElement.selectionStart;
                const end = editorElement.selectionEnd;
                const selection = editorElement.value.substring(start, end);

                let replacement = '';

                // Apply formatting
                switch (format) {
                    case 'bold':
                        replacement = `<strong>${selection}</strong>`;
                        break;
                    case 'italic':
                        replacement = `<em>${selection}</em>`;
                        break;
                    case 'underline':
                        replacement = `<u>${selection}</u>`;
                        break;
                    case 'link':
                        const url = prompt('Enter URL:', 'https://');
                        if (url) {
                            replacement = `<a href="${url}" target="_blank">${selection || url}</a>`;
                        } else {
                            return; // User cancelled
                        }
                        break;
                    case 'list-ul':
                        if (selection) {
                            const items = selection.split('\n');
                            replacement = '<ul>\n' + items.map(item => `  <li>${item}</li>`).join('\n') + '\n</ul>';
                        } else {
                            replacement = '<ul>\n  <li></li>\n</ul>';
                        }
                        break;
                    case 'list-ol':
                        if (selection) {
                            const items = selection.split('\n');
                            replacement = '<ol>\n' + items.map(item => `  <li>${item}</li>`).join('\n') + '\n</ol>';
                        } else {
                            replacement = '<ol>\n  <li></li>\n</ol>';
                        }
                        break;
                }

                // Insert formatted content
                if (replacement) {
                    const newValue = editorElement.value.substring(0, start) + replacement + editorElement.value.substring(end);
                    editorElement.value = newValue;

                    // Set focus back to editor
                    editorElement.focus();
                    editorElement.setSelectionRange(start + replacement.length, start + replacement.length);
                }
            });
        });
    });
</script>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>