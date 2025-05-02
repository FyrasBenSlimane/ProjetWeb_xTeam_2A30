<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Custom CSS for enhanced UI elements with improved color scheme -->
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --primary-light: #eef2ff;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --success-light: #ecfdf5;
        --warning-color: #f59e0b;
        --warning-light: #fffbeb;
        --danger-color: #ef4444;
        --danger-light: #fef2f2;
        --info-color: #3b82f6;
        --info-light: #eff6ff;
        --gray-light: #f8fafc;
        --gray-medium: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --radius-sm: 0.375rem;
        --radius: 0.5rem;
        --radius-lg: 0.75rem;
    }

    /* Custom card hover effects */
    .support-card {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid transparent;
    }

    .support-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
        box-shadow: var(--shadow-lg);
    }

    /* Icon box styling with animation */
    .icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    /* Custom animations */
    .support-card:hover .icon-box {
        transform: scale(1.1);
    }

    /* Flash message animation */
    .alert {
        animation: slideDown 0.5s ease-out forwards;
        border-left: 4px solid;
    }

    .alert-success {
        border-color: var(--success-color);
        background-color: var(--success-light);
        color: var(--success-color);
    }

    .alert-danger {
        border-color: var(--danger-color);
        background-color: var(--danger-light);
        color: var(--danger-color);
    }

    .alert-warning {
        border-color: var(--warning-color);
        background-color: var(--warning-light);
        color: var(--warning-color);
    }

    .alert-info {
        border-color: var(--info-color);
        background-color: var(--info-light);
        color: var(--info-color);
    }

    @keyframes slideDown {
        0% {
            transform: translateY(-20px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Enhanced buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Focus states for form elements */
    .form-control:focus,
    .btn:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        border-color: var(--primary-color);
    }

    /* Custom card headers with gradients */
    .gradient-header {
        background: linear-gradient(135deg, var(--primary-color), #818cf8);
        color: white;
        border-top-left-radius: var(--radius);
        border-top-right-radius: var(--radius);
    }

    /* Enhanced priority selector */
    .priority-toggle .btn {
        border-color: var(--gray-medium);
        color: var(--text-dark);
    }

    .priority-toggle .btn-outline-info:hover,
    .priority-toggle .btn-check:checked+.btn-outline-info {
        background-color: var(--info-color);
        border-color: var(--info-color);
        color: white;
    }

    .priority-toggle .btn-outline-warning:hover,
    .priority-toggle .btn-check:checked+.btn-outline-warning {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
        color: white;
    }

    .priority-toggle .btn-outline-danger:hover,
    .priority-toggle .btn-check:checked+.btn-outline-danger {
        background-color: var(--danger-color);
        border-color: var (--danger-color);
        color: white;
    }

    /* Form enhancements */
    textarea.form-control {
        font-size: 0.95rem;
    }

    textarea.form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .form-text {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    /* Custom rounded badges */
    .badge.rounded-pill {
        font-weight: 500;
        padding: 0.35em 0.85em;
    }
</style>

<script>
    // Initialize enhanced select inputs with icons
    document.addEventListener('DOMContentLoaded', function() {
        // Add icons to category options
        const categorySelect = document.getElementById('category');
        if (categorySelect) {
            const options = categorySelect.querySelectorAll('option');
            options.forEach(option => {
                const icon = option.dataset.icon;
                if (icon) {
                    option.innerHTML = `<i class="fas ${icon} me-2"></i> ${option.textContent}`;
                }
            });
        }

        // Pulse animation for priority selection
        const priorityLabels = document.querySelectorAll('.priority-toggle .btn');
        if (priorityLabels.length) {
            priorityLabels.forEach(label => {
                label.addEventListener('click', function() {
                    // Add quick pulse animation when selected
                    this.classList.add('pulse-animation');
                    setTimeout(() => {
                        this.classList.remove('pulse-animation');
                    }, 500);
                });
            });
        }
    });
</script>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>" class="text-decoration-none"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support" class="text-decoration-none"><i class="fas fa-ticket-alt"></i> Support Tickets</a></li>
                    <li class="breadcrumb-item active"><i class="fas fa-plus-circle"></i> Create Ticket</li>
                </ol>
            </nav>

            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header gradient-header py-3 d-flex justify-content-between align-items-center text-white">
                    <h5 class="mb-0"><i class="fas fa-headset me-2"></i>Create Support Ticket</h5>
                    <span class="badge bg-white text-primary rounded-pill">24/7 Support</span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <p class="text-muted">Fill in the form below with details about your issue. Our support team will respond as soon as possible.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end align-items-center">
                                <span class="small text-muted me-2">Average response time:</span>
                                <span class="badge bg-success rounded-pill"><i class="fas fa-clock me-1"></i> 2-4 hours</span>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo URLROOT; ?>/support/create" method="post" id="createTicketForm" class="needs-validation" novalidate enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-pen text-primary"></i></span>
                                    <input type="text" class="form-control form-control-lg border-start-0 ps-0 <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>"
                                        id="subject" name="subject" value="<?php echo $data['subject']; ?>"
                                        placeholder="Brief summary of your issue">
                                    <div class="invalid-feedback"><?php echo $data['subject_err']; ?></div>
                                </div>
                                <div class="form-text"><i class="fas fa-info-circle me-1 text-primary"></i> Be specific to help us route your ticket to the right team.</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag text-primary"></i></span>
                                        <select class="form-select border-start-0 ps-0 <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>"
                                            id="category" name="category">
                                            <option value="" <?php echo empty($data['category']) ? 'selected' : ''; ?>>Select a category</option>
                                            <option value="technical" <?php echo ($data['category'] == 'technical') ? 'selected' : ''; ?> data-icon="fa-wrench">Technical Support</option>
                                            <option value="billing" <?php echo ($data['category'] == 'billing') ? 'selected' : ''; ?> data-icon="fa-credit-card">Billing</option>
                                            <option value="account" <?php echo ($data['category'] == 'account') ? 'selected' : ''; ?> data-icon="fa-user">Account</option>
                                            <option value="connects" <?php echo ($data['category'] == 'connects') ? 'selected' : ''; ?> data-icon="fa-link">Connects</option>
                                            <option value="proposals" <?php echo ($data['category'] == 'proposals') ? 'selected' : ''; ?> data-icon="fa-file-alt">Proposals & Applications</option>
                                            <option value="feature" <?php echo ($data['category'] == 'feature') ? 'selected' : ''; ?> data-icon="fa-lightbulb">Feature Request</option>
                                            <option value="other" <?php echo ($data['category'] == 'other') ? 'selected' : ''; ?> data-icon="fa-question-circle">Other</option>
                                        </select>
                                        <div class="invalid-feedback"><?php echo $data['category_err']; ?></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="priority" class="form-label fw-semibold">Priority</label>
                                    <div class="priority-selector">
                                        <div class="btn-group w-100 priority-toggle" role="group">
                                            <input type="radio" class="btn-check" name="priority" id="priority-low" value="low" autocomplete="off"
                                                <?php echo ($data['priority'] == 'low') ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-info rounded-start" for="priority-low">
                                                <i class="fas fa-thermometer-empty me-1"></i> Low
                                            </label>

                                            <input type="radio" class="btn-check" name="priority" id="priority-medium" value="medium" autocomplete="off"
                                                <?php echo ($data['priority'] == 'medium' || empty($data['priority'])) ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-warning" for="priority-medium">
                                                <i class="fas fa-thermometer-half me-1"></i> Medium
                                            </label>

                                            <input type="radio" class="btn-check" name="priority" id="priority-high" value="high" autocomplete="off"
                                                <?php echo ($data['priority'] == 'high') ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-danger rounded-end" for="priority-high">
                                                <i class="fas fa-thermometer-full me-1"></i> High
                                            </label>
                                        </div>
                                        <small class="form-text text-muted mt-2 d-block">
                                            <i class="fas fa-info-circle me-1"></i> Please select high priority only for urgent issues.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <div class="card border-0 bg-light shadow-sm rounded-3 p-0">
                                <div class="card-header bg-light py-2 px-3">
                                    <small><i class="fas fa-edit me-1 text-primary"></i> Provide detailed information</small>
                                </div>
                                <div class="card-body p-0">
                                    <textarea class="form-control border-0 bg-light shadow-none <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"
                                        id="description" name="description" rows="8"
                                        placeholder="Please provide detailed information about your issue...
• What happened?
• What did you expect to happen?
• Steps to reproduce the issue
• Any error messages you received"><?php echo $data['description']; ?></textarea>
                                </div>
                                <div class="invalid-feedback d-block ps-3 pb-2">
                                    <?php echo $data['description_err']; ?>
                                </div>
                                <div class="card-footer bg-light py-2 px-3">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i> Providing clear details helps us resolve your issue faster.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Add file attachment input -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-semibold">Attachment <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-paperclip text-primary"></i></span>
                                <input type="file" class="form-control border-start-0 <?php echo (!empty($data['attachment_err'])) ? 'is-invalid' : ''; ?>"
                                    id="attachment" name="attachment">
                                <div class="invalid-feedback"><?php echo $data['attachment_err']; ?></div>
                            </div>
                            <div class="form-text"><i class="fas fa-info-circle me-1 text-primary"></i> Accepted file types: PDF, JPG, PNG, ZIP, DOC, DOCX (Max: 10MB)</div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <a href="<?php echo URLROOT; ?>/support" class="btn btn-light px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Cancel
                                </a>
                                <button type="submit" name="save_draft" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-2"></i> Save as Draft
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane me-2"></i> Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 rounded-3 shadow-sm bg-light mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        <h6 class="mb-0">Before submitting a ticket</h6>
                    </div>
                    <p class="small text-muted mb-0">Check if your question has already been answered in our knowledge resources below:</p>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3 support-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mx-auto mb-3 bg-primary bg-opacity-10 text-primary rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-search fa-lg"></i>
                            </div>
                            <h5 class="card-title">Check FAQs</h5>
                            <p class="card-text text-muted">Find quick answers to common questions in our knowledge base.</p>
                            <a href="<?php echo URLROOT; ?>/pages/faqs" class="btn btn-outline-primary w-100">
                                <i class="fas fa-external-link-alt me-1"></i> Browse FAQs
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3 support-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mx-auto mb-3 bg-success bg-opacity-10 text-success rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-comments fa-lg"></i>
                            </div>
                            <h5 class="card-title">Live Chat</h5>
                            <p class="card-text text-muted">Connect with our support team instantly for urgent issues.</p>
                            <button class="btn btn-outline-success w-100" disabled>
                                <i class="fas fa-comment-dots me-1"></i> Coming Soon
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3 support-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mx-auto mb-3 bg-info bg-opacity-10 text-info rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-video fa-lg"></i>
                            </div>
                            <h5 class="card-title">Video Tutorials</h5>
                            <p class="card-text text-muted">Learn how to use platform features through our tutorial videos.</p>
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="fas fa-play me-1"></i> Watch Videos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                const saveDraftButton = form.querySelector('button[name="save_draft"]');
                
                form.addEventListener('submit', function (event) {
                    const clickedButton = event.submitter;
                    
                    // If 'Save as Draft' was clicked, skip validation by removing required attributes temporarily
                    if (clickedButton && clickedButton.name === 'save_draft') {
                        // Mark which fields were originally required, then remove the required attribute
                        form.querySelectorAll('[required]').forEach(el => {
                            el.setAttribute('data-was-required', 'true');
                            el.removeAttribute('required');
                        });
                        
                        // Allow submission without validation for drafts
                        return true;
                    }
                    
                    // Normal submit - validate the form
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>