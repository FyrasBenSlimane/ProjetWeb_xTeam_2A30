<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>" class="text-decoration-none"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support" class="text-decoration-none"><i class="fas fa-ticket-alt"></i> Support Tickets</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $data['id']; ?>" class="text-decoration-none">Ticket #<?php echo $data['id']; ?></a></li>
                    <li class="breadcrumb-item active"><i class="fas fa-edit"></i> Edit Ticket</li>
                </ol>
            </nav>

            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="fas fa-edit me-2"></i>Edit Support Ticket</h5>
                    <span class="badge bg-info rounded-pill">Ticket #<?php echo $data['id']; ?></span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <p class="text-muted">Update the details of your support ticket below.</p>
                        </div>
                    </div>

                    <form action="<?php echo URLROOT; ?>/support/edit/<?php echo $data['id']; ?>" method="post" class="needs-validation">
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-pen"></i></span>
                                <input type="text" class="form-control form-control-lg <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>"
                                    id="subject" name="subject" value="<?php echo $data['subject']; ?>"
                                    placeholder="Brief summary of your issue">
                                <div class="invalid-feedback"><?php echo $data['subject_err']; ?></div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                    <select class="form-select form-select-lg <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>"
                                        id="category" name="category">
                                        <option value="" <?php echo empty($data['category']) ? 'selected' : ''; ?>>Select a category</option>
                                        <option value="technical" <?php echo ($data['category'] == 'technical') ? 'selected' : ''; ?>>Technical Support</option>
                                        <option value="billing" <?php echo ($data['category'] == 'billing') ? 'selected' : ''; ?>>Billing</option>
                                        <option value="account" <?php echo ($data['category'] == 'account') ? 'selected' : ''; ?>>Account</option>
                                        <option value="connects" <?php echo ($data['category'] == 'connects') ? 'selected' : ''; ?>>Connects</option>
                                        <option value="proposals" <?php echo ($data['category'] == 'proposals') ? 'selected' : ''; ?>>Proposals & Applications</option>
                                        <option value="feature" <?php echo ($data['category'] == 'feature') ? 'selected' : ''; ?>>Feature Request</option>
                                        <option value="other" <?php echo ($data['category'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <div class="invalid-feedback"><?php echo $data['category_err']; ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="priority" class="form-label fw-bold">Priority</label>
                                <div class="priority-selector">
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="priority" id="priority-low" value="low" autocomplete="off"
                                            <?php echo ($data['priority'] == 'low') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-info" for="priority-low">
                                            <i class="fas fa-thermometer-empty me-1"></i> Low
                                        </label>

                                        <input type="radio" class="btn-check" name="priority" id="priority-medium" value="medium" autocomplete="off"
                                            <?php echo ($data['priority'] == 'medium') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-warning" for="priority-medium">
                                            <i class="fas fa-thermometer-half me-1"></i> Medium
                                        </label>

                                        <input type="radio" class="btn-check" name="priority" id="priority-high" value="high" autocomplete="off"
                                            <?php echo ($data['priority'] == 'high') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-danger" for="priority-high">
                                            <i class="fas fa-thermometer-full me-1"></i> High
                                        </label>
                                    </div>
                                    <small class="form-text text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i> Please select high priority only for urgent issues.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                            <div class="card border-0 bg-light p-0">
                                <div class="card-header bg-light py-2 px-3">
                                    <small><i class="fas fa-edit me-1"></i> Provide detailed information</small>
                                </div>
                                <div class="card-body p-0">
                                    <textarea class="form-control border-0 bg-light <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"
                                        id="description" name="description" rows="8"
                                        placeholder="Please provide detailed information about your issue..."><?php echo $data['description']; ?></textarea>
                                </div>
                                <div class="invalid-feedback d-block ps-3 pb-2">
                                    <?php echo $data['description_err']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $data['id']; ?>" class="btn btn-light btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Update Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>