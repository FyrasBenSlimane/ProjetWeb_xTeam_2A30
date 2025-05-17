<?php
// Support - Edit Ticket page
// This page allows user to edit an existing ticket
?>

<div class="edit-ticket-container">
    <div class="edit-ticket-header">
        <div class="header-content">
            <h1>Edit Ticket #<?php echo htmlspecialchars($data['id']); ?></h1>
            <p class="subtitle">Make changes to your support request details</p>
        </div>
        <div class="header-actions">
            <a href="<?php echo URL_ROOT; ?>/support/viewTicket/<?php echo htmlspecialchars($data['id']); ?>" class="action-btn back-btn">
                <i class="fas fa-arrow-left"></i> Back to Ticket
            </a>
        </div>
    </div>

    <?php flash('ticket_message'); ?>

    <div class="edit-ticket-form">
        <form action="<?php echo URL_ROOT; ?>/support/editTicket/<?php echo htmlspecialchars($data['id']); ?>" method="post" id="editTicketForm">
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-pencil-alt"></i> Ticket Information</h3>
                </div>

                <div class="form-card-body">
                    <div class="form-group">
                        <label for="subject">Subject <span class="required">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-control <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($data['subject']); ?>" required>
                        <span class="invalid-feedback"><?php echo $data['subject_err']; ?></span>
                        <small class="form-text">Clear and concise summary of your issue</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="category">Category</label>
                            <div class="select-wrapper">
                                <select id="category" name="category" class="form-control">
                                    <option value="technical" <?php echo ($data['category'] == 'technical') ? 'selected' : ''; ?>>Technical</option>
                                    <option value="billing" <?php echo ($data['category'] == 'billing') ? 'selected' : ''; ?>>Billing</option>
                                    <option value="general" <?php echo ($data['category'] == 'general') ? 'selected' : ''; ?>>General</option>
                                    <option value="feedback" <?php echo ($data['category'] == 'feedback') ? 'selected' : ''; ?>>Feedback</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="priority">Priority</label>
                            <div class="select-wrapper">
                                <select id="priority" name="priority" class="form-control">
                                    <option value="low" <?php echo ($data['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo ($data['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo ($data['priority'] == 'high') ? 'selected' : ''; ?>>High</option>
                                    <option value="critical" <?php echo ($data['priority'] == 'critical') ? 'selected' : ''; ?>>Critical</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                            <div class="priority-indicators">
                                <span class="priority-dot low" title="Low priority"></span>
                                <span class="priority-dot medium" title="Medium priority"></span>
                                <span class="priority-dot high" title="High priority"></span>
                                <span class="priority-dot critical" title="Critical priority"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" rows="8" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" required><?php echo htmlspecialchars($data['description']); ?></textarea>
                        <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        <small class="form-text">Provide detailed information about your issue</small>
                        <div class="description-controls">
                            <span class="char-count">0 characters</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo URL_ROOT; ?>/support/viewTicket/<?php echo htmlspecialchars($data['id']); ?>" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-ticket-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .edit-ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-content h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 8px;
        margin-top: 0;
    }

    .subtitle {
        color: #7f8c8d;
        font-size: 1.1rem;
        margin-top: 0;
    }

    .header-actions .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-actions .back-btn {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .header-actions .back-btn:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header-actions .back-btn i {
        margin-right: 8px;
    }

    .edit-ticket-form {
        margin-top: 20px;
    }

    .form-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
        border: 1px solid #eaeaea;
    }

    .form-card-header {
        padding: 18px 25px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
    }

    .form-card-header h3 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
    }

    .form-card-header h3 i {
        margin-right: 10px;
        color: #3498db;
    }

    .form-card-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-row {
        display: flex;
        margin: 0 -12px;
    }

    .form-row>.form-group {
        padding: 0 12px;
        flex: 1;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
        font-size: 0.95rem;
    }

    .required {
        color: #e74c3c;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.25s ease;
        background-color: #fafafa;
        color: #2c3e50;
    }

    .form-control:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        background-color: white;
    }

    .form-text {
        display: block;
        margin-top: 6px;
        color: #7f8c8d;
        font-size: 0.85rem;
    }

    .select-wrapper {
        position: relative;
    }

    .select-arrow {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
        pointer-events: none;
    }

    select.form-control {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding-right: 30px;
        cursor: pointer;
    }

    textarea.form-control {
        min-height: 180px;
        resize: vertical;
    }

    .form-control.is-invalid {
        border-color: #e74c3c;
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 6px;
        display: block;
    }

    .priority-indicators {
        display: flex;
        margin-top: 8px;
        gap: 8px;
    }

    .priority-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: block;
        transition: transform 0.2s ease;
    }

    .priority-dot:hover {
        transform: scale(1.2);
    }

    .priority-dot.low {
        background-color: #95a5a6;
    }

    .priority-dot.medium {
        background-color: #3498db;
    }

    .priority-dot.high {
        background-color: #f39c12;
    }

    .priority-dot.critical {
        background-color: #e74c3c;
    }

    .description-controls {
        display: flex;
        justify-content: flex-end;
        color: #7f8c8d;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background-color: #e9ecef;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 28px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.25);
    }

    @media (max-width: 768px) {
        .edit-ticket-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .form-row {
            flex-direction: column;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-submit {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descriptionField = document.getElementById('description');
    const charCount = document.querySelector('.char-count');

    function updateCharCount() {
        const count = descriptionField.value.length;
        charCount.textContent = `${count} characters`;
    }

    descriptionField.addEventListener('input', updateCharCount);
    updateCharCount(); // Initialize on page load

    // Form validation
    const editTicketForm = document.getElementById('editTicketForm');

    editTicketForm.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate subject
        const subject = document.getElementById('subject');
        if (!subject.value.trim()) {
            isValid = false;
            subject.classList.add('is-invalid');
        } else {
            subject.classList.remove('is-invalid');
        }

        // Validate description
        if (!descriptionField.value.trim()) {
            isValid = false;
            descriptionField.classList.add('is-invalid');
        } else {
            descriptionField.classList.remove('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            window.scrollTo(0, 0);
        }
    });

    // Priority selector visual indicator
    const prioritySelect = document.getElementById('priority');
    const priorityDots = document.querySelectorAll('.priority-dot');

    function updateSelectedPriority() {
        const selectedValue = prioritySelect.value;

        priorityDots.forEach(dot => {
            if (dot.classList.contains(selectedValue)) {
                dot.style.transform = 'scale(1.3)';
                dot.style.boxShadow = '0 0 0 2px white, 0 0 0 4px ' + getComputedStyle(dot).backgroundColor;
            } else {
                dot.style.transform = 'scale(1)';
                dot.style.boxShadow = 'none';
            }
        });
    }

    prioritySelect.addEventListener('change', updateSelectedPriority);
    updateSelectedPriority(); // Initialize on page load

    // Click on priority dots to select priority
    priorityDots.forEach(dot => {
        dot.addEventListener('click', function() {
            const clickedPriority = this.classList[1]; // low, medium, high, or critical
            prioritySelect.value = clickedPriority;
            updateSelectedPriority();
        });
    });
    });
</script>

<!-- Form validation scripts -->
<script src="<?php echo URL_ROOT; ?>/js/support-form-validation.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/edit-ticket-validation.js"></script>