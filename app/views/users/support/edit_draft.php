<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Custom CSS (can be moved to support.css later) -->
<style>
    /* Add styles similar to create.php if needed, or rely on support.css */
    .attachment-info {
        background-color: var(--support-gray-100);
        padding: 0.75rem 1rem;
        border-radius: var(--support-radius-sm);
        border: 1px solid var(--support-gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .attachment-info .form-check-label {
        margin-bottom: 0;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-3 rounded shadow-sm mb-4">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>" class="text-decoration-none"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support" class="text-decoration-none"><i class="fas fa-headset me-1"></i> Support Tickets</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support/drafts" class="text-decoration-none"><i class="fas fa-edit me-1"></i> Drafts</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-pencil-alt me-1"></i> Edit Draft</li>
                </ol>
            </nav>

            <?php flash('ticket_message'); ?>

            <div class="card support-card mb-4">
                <div class="card-header gradient-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white"><i class="fas fa-pencil-alt me-2"></i> <?php echo $data['title']; ?></h4>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted mb-4"><?php echo $data['description']; ?></p>

                    <form action="<?php echo URLROOT; ?>/support/editDraft/<?php echo $data['id']; ?>" method="post" id="editDraftForm" class="needs-validation" novalidate enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-pen text-primary"></i></span>
                                    <input type="text" class="form-control form-control-lg border-start-0 ps-0 <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>"
                                        id="subject" name="subject" value="<?php echo htmlspecialchars($data['subject']); ?>"
                                        placeholder="Brief summary of your issue">
                                    <div class="invalid-feedback"><?php echo $data['subject_err']; ?></div>
                                </div>
                            </div>
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
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-lg <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"
                                id="description" name="description" rows="8"
                                placeholder="Provide detailed information about your issue..."><?php echo htmlspecialchars($data['description']); ?></textarea>
                            <div class="invalid-feedback"><?php echo $data['description_err']; ?></div>
                        </div>

                        <!-- Attachment Section -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-semibold">Attachment</label>
                            <?php if (!empty($data['attachment_filename'])): ?>
                                <div class="attachment-info mb-2">
                                    <span>
                                        <i class="fas fa-paperclip me-2 text-muted"></i>
                                        Current file: <strong><?php echo htmlspecialchars($data['attachment_filename']); ?></strong>
                                    </span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="remove_attachment" name="remove_attachment" value="1">
                                        <label class="form-check-label text-danger" for="remove_attachment">Remove</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input class="form-control <?php echo (!empty($data['attachment_err'])) ? 'is-invalid' : ''; ?>" type="file" id="attachment" name="attachment">
                            <div class="invalid-feedback"><?php echo $data['attachment_err']; ?></div>
                            <small class="form-text text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i> Max file size: 5MB. Allowed types: JPG, PNG, GIF.
                            </small>
                        </div>
                        <!-- End Attachment Section -->

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo URLROOT; ?>/support/drafts" class="btn btn-support-outline"><i class="fas fa-arrow-left me-2"></i> Cancel</a>
                            <div>
                                <button type="submit" name="save_draft" class="btn btn-secondary me-2"><i class="fas fa-save me-2"></i> Save Draft</button>
                                <button type="submit" name="submit_ticket" class="btn btn-support-primary"><i class="fas fa-paper-plane me-2"></i> Submit Ticket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap form validation (similar to create.php)
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const clickedButton = event.submitter;
                    const requiredElements = form.querySelectorAll('[data-was-required="true"]');

                    // Always restore required attributes before validation or submission
                    requiredElements.forEach(el => {
                        el.setAttribute('required', '');
                        el.removeAttribute('data-was-required');
                    });

                    // If 'Save Draft' was clicked, skip validation
                    if (clickedButton && clickedButton.name === 'save_draft') {
                        // Temporarily remove required attributes *again* just before submitting as draft
                        form.querySelectorAll('[required]').forEach(el => {
                            el.setAttribute('data-was-required', 'true'); // Mark as originally required
                            el.removeAttribute('required');
                        });
                        // Allow submission without validation for drafts
                        return;
                    }

                    // If 'Submit Ticket' was clicked (or default submit), perform validation
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)

                // Add event listener to 'Save Draft' button to mark required fields before submit listener runs
                const saveDraftButton = form.querySelector('button[name="save_draft"]');
                if (saveDraftButton) {
                    saveDraftButton.addEventListener('click', function() {
                        form.querySelectorAll('[required]').forEach(el => {
                            el.setAttribute('data-was-required', 'true'); // Mark as originally required
                            // Don't remove 'required' here, let the submit handler manage it
                        });
                    });
                }
            })
    })()
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>