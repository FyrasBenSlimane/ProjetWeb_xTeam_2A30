<?php
// Support - New Ticket page
// This page allows users to create a new support ticket
?>

<div class="new-ticket-container">
    <div class="ticket-header">
        <h1>Create New Support Ticket</h1>
        <p>Submit a request for assistance from our support team</p>
    </div>

    <!-- AI-powered Smart Ticket Assistant -->
    <div class="smart-assistant-container">
        <div class="assistant-header">
            <div class="assistant-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="assistant-title">
                <h3>Smart Ticket Assistant</h3>
                <p>Get instant solutions or optimize your ticket for faster resolution</p>
            </div>
            <button class="assistant-toggle" id="toggle-assistant">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div class="assistant-body" id="assistant-body">            <div class="assistant-features">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Find Instant Solutions</h4>
                        <p>Get suggested solutions based on your ticket details before submitting</p>
                        <button class="feature-btn" id="instant-solutions-btn" data-tooltip="Enter a subject and description first">
                            <span class="btn-text">Suggest Solutions</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Smart Form Completion</h4>
                        <p>Let AI help you fill out the form with relevant details</p>
                        <button class="feature-btn" id="smart-completion-btn" data-tooltip="Enter subject to enhance your description">
                            <span class="btn-text">Complete Form</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Priority Recommendation</h4>
                        <p>Get suggestions on appropriate priority level for your issue</p>
                        <button class="feature-btn" id="priority-recommendation-btn" data-tooltip="Based on your ticket details">
                            <span class="btn-text">Recommend Priority</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="assistant-results" class="assistant-results">
                <div class="results-header">
                    <h4><i class="fas fa-robot"></i> Assistant Suggestions</h4>
                    <button class="close-results" id="close-results">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="results-content" class="results-content">
                    <!-- Results will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <div class="ticket-form-container">
        <form id="supportTicketForm" class="support-form" method="post" action="<?php echo URL_ROOT; ?>/support/submitTicketAjax" enctype="multipart/form-data">
            <!-- Form will be submitted via AJAX -->

            <!-- Response message div for Ajax responses -->
            <div id="form-response" class="form-response" style="display: none;"></div>
            
            <!-- Progress indicator -->
            <div id="form-progress" class="form-progress" style="display: none;">
                <div class="progress-bar">
                    <div class="progress-value"></div>
                </div>
                <p class="progress-status">Processing your ticket...</p>
            </div>

            <div class="form-group">
                <label for="ticket-subject">Subject</label>
                <input type="text" id="ticket-subject" name="subject" class="form-control" placeholder="Brief description of your issue" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ticket-category">Category</label>
                    <select id="ticket-category" name="category" class="form-control" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="account">Account Issues</option>
                        <option value="billing">Billing & Payments</option>
                        <option value="technical">Technical Problems</option>
                        <option value="project">Project Issues</option>
                        <option value="communication">Communication Problems</option>
                        <option value="feedback">Platform Feedback</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ticket-priority">Priority</label>
                    <select id="ticket-priority" name="priority" class="form-control" required>
                        <option value="" disabled selected>Select priority</option>
                        <option value="low">Low - General question or feedback</option>
                        <option value="medium">Medium - Issue affecting my work</option>
                        <option value="high">High - Urgent issue blocking my progress</option>
                        <option value="critical">Critical - Security or payment issue</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="ticket-description">Description</label>
                <textarea id="ticket-description" name="description" class="form-control" rows="6" placeholder="Please provide detailed information about your issue. Include any relevant steps to reproduce, error messages, or expected behavior." required></textarea>
                <div class="description-tips">
                    <p><i class="fas fa-lightbulb"></i> <strong>Pro tip:</strong> The more details you provide, the faster we can help you.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="related-order">Related Project/Order (optional)</label>
                <input type="text" id="related-order" name="related_order" class="form-control" placeholder="If this relates to a specific project or order, enter the ID here">
            </div>

            <div class="form-group attachment-container">
                <label for="ticket-attachments">Attachments (optional)</label>
                <div class="file-upload-wrapper">
                    <div class="file-upload-info">
                        <i class="fas fa-paperclip"></i>
                        <span id="attachment-label">Add files (screenshots, documents, etc.)</span>
                    </div>
                    <input type="file" id="ticket-attachments" name="attachments[]" class="ticket-file-input" multiple>
                </div>
                <div class="attachment-help">
                    <small>You can upload up to 3 files (max 5MB each). Supported formats: jpg, png, pdf, doc, docx, txt.</small>
                </div>
                <div id="file-preview-container" class="file-previews"></div>
            </div>

            <div class="form-checkbox">
                <input type="checkbox" id="receive-updates" name="receive_updates" checked>
                <label for="receive-updates">Receive email updates when there are responses to this ticket</label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" id="cancel-button" onclick="window.history.back();">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn-primary" id="submit-button">
                    <i class="fas fa-paper-plane"></i> Submit Ticket
                    <span class="shortcut-hint">(Ctrl+Enter)</span>
                </button>
            </div>
        </form>
    </div>

    <div class="ticket-info-box">
        <h3><i class="fas fa-info-circle"></i> What happens next?</h3>
        <ol>
            <li>Your ticket will be reviewed by our support team</li>
            <li>You'll receive a confirmation email with ticket details</li>
            <li>A support agent will respond within 24-48 hours</li>
            <li>You can view the status of your ticket in the "My Tickets" section</li>
        </ol>
        <p>For urgent issues requiring immediate assistance, please consider using the <a href="<?php echo URL_ROOT; ?>/support/contact">Contact Us</a> page.</p>
    </div>
</div>

<style>
    .new-ticket-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .ticket-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .ticket-header h1 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .ticket-header p {
        font-size: 18px;
        color: #74767e;
    }

    .ticket-form-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .support-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2c3e50;
        outline: none;
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }

    .description-tips {
        margin-top: 10px;
        padding: 10px 15px;
        background: #f8f9fa;
        border-left: 3px solid #2c3e50;
        border-radius: 0 4px 4px 0;
    }

    .description-tips p {
        margin: 0;
        font-size: 14px;
        color: #62646a;
    }

    .file-upload-wrapper {
        position: relative;
        border: 2px dashed #ddd;
        border-radius: 6px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-wrapper:hover {
        border-color: #2c3e50;
        background: rgba(44, 62, 80, 0.02);
    }

    .file-upload-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #62646a;
    }

    .file-upload-info i {
        font-size: 18px;
    }

    .ticket-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .attachment-help {
        margin-top: 8px;
        font-size: 12px;
        color: #74767e;
    }

    .file-previews {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }

    .file-preview {
        display: flex;
        align-items: center;
        background: #f5f5f7;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 14px;
    }

    .file-preview .file-name {
        margin-right: 8px;
        word-break: break-all;
    }

    .file-preview .remove-file {
        color: #62646a;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .file-preview .remove-file:hover {
        color: #e74c3c;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    .form-checkbox label {
        font-size: 15px;
        color: #404145;
    }    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }

    .btn-primary:disabled {
        background-color: #94a3b3;
        cursor: not-allowed;
        transform: none;
    }

    .btn-secondary {
        background-color: white;
        color: #2c3e50;
        border: 1px solid #ddd;
        padding: 12px 24px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        background-color: #f5f5f7;
        border-color: #2c3e50;
    }
    
    .shortcut-hint {
        font-size: 12px;
        opacity: 0.7;
        margin-left: 5px;
    }
    
    .form-progress {
        margin: 20px 0;
        display: none;
    }
    
    .progress-bar {
        height: 6px;
        background-color: #e0e0e0;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    
    .progress-value {
        height: 100%;
        background-color: #2c5282;
        width: 0%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    
    .progress-status {
        font-size: 14px;
        color: #2c5282;
        text-align: center;
        margin: 0;
    }

    .ticket-info-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 25px;
        margin-top: 30px;
    }

    .ticket-info-box h3 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .ticket-info-box ol {
        padding-left: 20px;
        margin-bottom: 15px;
    }

    .ticket-info-box li {
        margin-bottom: 8px;
        color: #404145;
    }

    .ticket-info-box p {
        color: #404145;
        margin-bottom: 0;
    }

    .ticket-info-box a {
        color: #2c3e50;
        text-decoration: underline;
    }

    .ticket-info-box a:hover {
        color: #1a252f;
    }    /* CSS for success/error messages */
    .form-response {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 500;
        animation: fadeIn 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    .form-response.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .form-response.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        animation: shake 0.5s ease-in-out;
    }
    
    .form-response i {
        font-size: 20px;
    }

    .error-message {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .error-message i {
        font-size: 12px;
    }

    .form-group.has-error label {
        color: #e74c3c;
    }
    
    input.error,
    select.error,
    textarea.error {
        border-color: #e74c3c !important;
        background-color: #fff8f8;
    }
    
    .is-valid {
        border-color: #28a745 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .ticket-header h1 {
            font-size: 30px;
        }

        .ticket-header p {
            font-size: 16px;
        }

        .ticket-form-container {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .ticket-header h1 {
            font-size: 26px;
        }

        .ticket-form-container {
            padding: 15px;
        }

        .form-control {
            padding: 10px;
            font-size: 14px;
        }
    }

    /* Smart Ticket Assistant Styles */
    .smart-assistant-container {
        background: white;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .assistant-header {
        display: flex;
        align-items: center;
        padding: 20px 25px;
        background: linear-gradient(135deg, #2c5282, #1e3c5a);
        color: white;
        position: relative;
    }

    .assistant-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .assistant-icon i {
        font-size: 24px;
    }

    .assistant-title h3 {
        margin: 0 0 5px;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .assistant-title p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .assistant-toggle {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .assistant-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .assistant-toggle i {
        transition: transform 0.3s ease;
    }

    .assistant-toggle.active i {
        transform: rotate(180deg);
    }

    .assistant-body {
        padding: 25px;
        display: none;
    }

    .assistant-body.active {
        display: block;
    }

    .assistant-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .feature-card {
        display: flex;
        align-items: flex-start;
        background: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: rgba(44, 82, 130, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #2c5282;
    }

    .feature-content {
        flex: 1;
    }

    .feature-content h4 {
        margin: 0 0 8px;
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .feature-content p {
        margin: 0 0 15px;
        font-size: 0.9rem;
        color: #5a6878;
        line-height: 1.5;
    }

    .feature-btn {
        background: #2c5282;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .feature-btn:hover {
        background: #1a365d;
        transform: translateY(-2px);
    }

    .assistant-results {
        background: #f0f4f9;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e0e8f0;
        margin-top: 20px;
        display: none;
    }

    .assistant-results.active {
        display: block;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .results-header h4 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .close-results {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
    }

    .result-item {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 3px solid #2c5282;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .result-title {
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 10px;
        font-size: 1rem;
    }

    .result-content {
        color: #2c3e50;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0 0 10px;
    }

    .result-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .apply-suggestion {
        background: #2c5282;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .apply-suggestion:hover {
        background: #1a365d;
    }

    .result-feedback {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feedback-btn {
        background: none;
        border: none;
        color: #888;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .feedback-btn:hover {
        color: #2c3e50;
    }

    .feedback-btn.helpful:hover {
        color: #2ecc71;
    }

    .feedback-btn.not-helpful:hover {
        color: #e74c3c;
    }

    /* Loading Animation */
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(44, 82, 130, 0.2);
        border-top-color: #2c5282;
        animation: spin 1s infinite linear;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .assistant-features {
            grid-template-columns: 1fr;
        }

        .assistant-header {
            flex-direction: column;
            text-align: center;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .assistant-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .assistant-toggle {
            top: 20px;
            transform: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle file uploads
        const fileInput = document.getElementById('ticket-attachments');
        const fileLabel = document.getElementById('attachment-label');
        const filePreviewContainer = document.getElementById('file-preview-container');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                // Update the file input label
                if (this.files.length > 0) {
                    fileLabel.textContent = `${this.files.length} file(s) selected`;
                } else {
                    fileLabel.textContent = 'Add files (screenshots, documents, etc.)';
                }

                // Clear previous preview
                filePreviewContainer.innerHTML = '';

                // Create previews for selected files
                Array.from(this.files).forEach(file => {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB

                    const filePreview = document.createElement('div');
                    filePreview.className = 'file-preview';

                    const fileName = document.createElement('span');
                    fileName.className = 'file-name';
                    fileName.textContent = `${file.name} (${fileSize} MB)`;

                    const removeBtn = document.createElement('span');
                    removeBtn.className = 'remove-file';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';

                    // Add event to remove this specific file
                    removeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();

                        // Create a new FileList without this file
                        // Note: FileList is immutable, so this is a workaround
                        const dt = new DataTransfer();
                        Array.from(fileInput.files)
                            .filter(f => f !== file)
                            .forEach(f => dt.items.add(f));

                        fileInput.files = dt.files;

                        // Trigger change event to update preview
                        const event = new Event('change');
                        fileInput.dispatchEvent(event);
                    });

                    filePreview.appendChild(fileName);
                    filePreview.appendChild(removeBtn);
                    filePreviewContainer.appendChild(filePreview);
                });
            });
        }

        // Form validation
        const form = document.getElementById('supportTicketForm');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                let valid = true;
                const subject = document.getElementById('ticket-subject').value.trim();
                const category = document.getElementById('ticket-category').value;
                const priority = document.getElementById('ticket-priority').value;
                const description = document.getElementById('ticket-description').value.trim();

                // Basic validation
                if (subject === '') {
                    showError('ticket-subject', 'Please enter a subject');
                    valid = false;
                }

                if (category === '') {
                    showError('ticket-category', 'Please select a category');
                    valid = false;
                }

                if (priority === '') {
                    showError('ticket-priority', 'Please select a priority');
                    valid = false;
                }

                if (description === '') {
                    showError('ticket-description', 'Please enter a description');
                    valid = false;
                } else if (description.length < 20) {
                    showError('ticket-description', 'Please provide a more detailed description (at least 20 characters)');
                    valid = false;
                }

                // File validation
                if (fileInput.files.length > 3) {
                    alert('You can upload a maximum of 3 files');
                    valid = false;
                }

                Array.from(fileInput.files).forEach(file => {
                    if (file.size > 5 * 1024 * 1024) { // 5MB
                        alert(`File ${file.name} exceeds the 5MB limit`);
                        valid = false;
                    }

                    // Check file extension
                    const ext = file.name.split('.').pop().toLowerCase();
                    const allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'];

                    if (!allowedTypes.includes(ext)) {
                        alert(`File ${file.name} has an unsupported file type`);
                        valid = false;
                    }
                });
                if (valid) {
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalButtonText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Submitting...';

                    // Hide any previous response
                    const formResponse = document.getElementById('form-response');
                    formResponse.style.display = 'none';

                    // Prepare form data for AJAX submission
                    const formData = new FormData(form);

                    // Send AJAX request
                    fetch('<?php echo URL_ROOT; ?>/support/submitTicketAjax', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalButtonText;

                            if (data.success) {
                                // Show success message
                                formResponse.className = 'form-response success';
                                formResponse.innerHTML = '<strong>Success!</strong> ' + (data.message || 'Your ticket has been submitted successfully!');
                                formResponse.style.display = 'block';

                                // Reset form
                                form.reset();
                                filePreviewContainer.innerHTML = '';
                                fileLabel.textContent = 'Add files (screenshots, documents, etc.)';

                                // Redirect after a short delay
                                setTimeout(function() {
                                    window.location.href = data.redirect || '<?php echo URL_ROOT; ?>/support/tickets';
                                }, 1500);
                            } else {
                                // Show error message
                                formResponse.className = 'form-response error';
                                let errorMessage = '<strong>Error:</strong> ' + (data.message || 'There was an error submitting your ticket.');

                                // Show field-specific errors if they exist
                                if (data.errors && Object.keys(data.errors).length > 0) {
                                    errorMessage += '<ul style="margin-top:10px;margin-bottom:0;">';
                                    for (const field in data.errors) {
                                        errorMessage += `<li>${data.errors[field]}</li>`;

                                        // Also show error on field
                                        const fieldId = field === 'subject' ? 'ticket-subject' :
                                            field === 'description' ? 'ticket-description' : field;
                                        showError(fieldId, data.errors[field]);
                                    }
                                    errorMessage += '</ul>';
                                }

                                formResponse.innerHTML = errorMessage;
                                formResponse.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalButtonText;

                            // Show error message
                            formResponse.className = 'form-response error';
                            formResponse.innerHTML = '<strong>Error:</strong> There was a problem connecting to the server. Please try again later.';
                            formResponse.style.display = 'block';
                            console.error('Error submitting form:', error);
                        });
                }
            });
        }

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('error');

            // Check if error message already exists
            let errorElement = field.parentNode.querySelector('.error-message');

            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                field.parentNode.appendChild(errorElement);
            }

            errorElement.textContent = message;

            // Remove error when field is focused
            field.addEventListener('focus', function() {
                this.classList.remove('error');
                errorElement.textContent = '';
            }, {
                once: true
            });
        }

        // Smart Ticket Assistant Functionality
        const toggleAssistant = document.getElementById('toggle-assistant');
        const assistantBody = document.getElementById('assistant-body');
        const instantSolutionsBtn = document.getElementById('instant-solutions-btn');
        const smartCompletionBtn = document.getElementById('smart-completion-btn');
        const priorityRecommendationBtn = document.getElementById('priority-recommendation-btn');
        const assistantResults = document.getElementById('assistant-results');

        // Toggle assistant visibility
        toggleAssistant.addEventListener('click', function() {
            assistantBody.classList.toggle('active');
            toggleAssistant.classList.toggle('active');
        });

        // Mock data for AI responses
        const mockKnowledgeBase = {
            account: [{
                    title: "Password Reset Help",
                    content: "Try resetting your password by clicking 'Forgot Password' on the login page. If you still can't access your account, we'll help troubleshoot."
                },
                {
                    title: "Account Verification",
                    content: "Check your email for a verification link. If you didn't receive it, you can request a new verification email from your account settings."
                }
            ],
            billing: [{
                    title: "Payment Declined",
                    content: "This is often due to insufficient funds or an expired card. Try updating your payment method in your account settings."
                },
                {
                    title: "Invoice Questions",
                    content: "You can view all your invoices in the Billing section. If you need a specific format or have questions about charges, we can help."
                }
            ],
            technical: [{
                    title: "Platform Error Messages",
                    content: "Try clearing your browser cache and cookies, then restart your browser. This resolves many common technical issues."
                },
                {
                    title: "Feature Not Working",
                    content: "Please provide details about the specific feature, what you expected to happen, and what occurred instead."
                }
            ],
            project: [{
                    title: "Milestone Issues",
                    content: "If you're having trouble with project milestones, check that all requirements have been properly set and communicated with all parties."
                },
                {
                    title: "Project Access",
                    content: "Ensure that all team members have the correct permissions for their roles. You can manage this in Project Settings."
                }
            ]
        };

        // Function to show instant solutions
        instantSolutionsBtn.addEventListener('click', function() {
            const subject = document.getElementById('ticket-subject').value;
            const category = document.getElementById('ticket-category').value;
            const description = document.getElementById('ticket-description')?.value || '';

            if (!subject && !category && !description) {
                showResults('Please fill in at least some details in the form so we can suggest relevant solutions.');
                return;
            }

            // Show loading state
            showLoading();

            // Simulate API call delay
            setTimeout(() => {
                // Check if we have relevant solutions based on category
                if (category && mockKnowledgeBase[category]) {
                    const solutions = mockKnowledgeBase[category];

                    let resultsHTML = `
                        <div class="results-header">
                            <h4>Suggested Solutions</h4>
                            <button class="close-results" id="close-results">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p>Based on your ticket details, these solutions might help resolve your issue:</p>
                    `;

                    solutions.forEach(solution => {
                        resultsHTML += `
                            <div class="result-item">
                                <h5 class="result-title">${solution.title}</h5>
                                <p class="result-content">${solution.content}</p>
                                <div class="result-action">
                                    <span></span>
                                    <div class="result-feedback">
                                        <button class="feedback-btn helpful">
                                            <i class="fas fa-thumbs-up"></i> Helpful
                                        </button>
                                        <button class="feedback-btn not-helpful">
                                            <i class="fas fa-thumbs-down"></i> Not helpful
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    resultsHTML += `
                        <p>Did these solutions help? If not, continue submitting your ticket below.</p>
                    `;

                    showResults(resultsHTML);
                } else {
                    // No specific solutions found
                    showResults(`
                        <div class="results-header">
                            <h4>No Specific Solutions Found</h4>
                            <button class="close-results" id="close-results">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p>We couldn't find specific solutions based on your input. Please continue filling out the ticket with more details, and our support team will assist you.</p>
                    `);
                }

                // Add event listener to close button
                document.getElementById('close-results').addEventListener('click', function() {
                    assistantResults.classList.remove('active');
                });

                // Add event listeners to feedback buttons
                document.querySelectorAll('.feedback-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.innerHTML = this.classList.contains('helpful') ?
                            '<i class="fas fa-check"></i> Thanks for your feedback!' :
                            '<i class="fas fa-check"></i> Thanks for your feedback!';
                        this.disabled = true;
                        this.style.color = this.classList.contains('helpful') ? '#2ecc71' : '#e74c3c';

                        // Disable the other button in the same container
                        const container = this.parentElement;
                        container.querySelectorAll('.feedback-btn').forEach(otherBtn => {
                            if (otherBtn !== this) {
                                otherBtn.disabled = true;
                                otherBtn.style.opacity = 0.5;
                            }
                        });
                    });
                });

            }, 1500); // Simulated delay
        });

        // Function to help with smart form completion
        smartCompletionBtn.addEventListener('click', function() {
            const subject = document.getElementById('ticket-subject').value;

            if (!subject) {
                showResults('Please enter a subject first so we can help complete your ticket.');
                return;
            }

            // Show loading state
            showLoading();

            // Simulate API call delay
            setTimeout(() => {
                // Analyze subject to suggest values
                const subjectLower = subject.toLowerCase();
                let category = '';
                let priority = '';
                let description = '';

                if (subjectLower.includes('password') || subjectLower.includes('login') || subjectLower.includes('account')) {
                    category = 'account';
                    priority = 'medium';
                    description = "I'm having trouble with my account. [Please provide more specific details about the issue you're experiencing]";
                } else if (subjectLower.includes('payment') || subjectLower.includes('bill') || subjectLower.includes('invoice')) {
                    category = 'billing';
                    priority = 'high';
                    description = "I'm experiencing an issue with billing or payments. [Please describe what happened and any error messages you received]";
                } else if (subjectLower.includes('error') || subjectLower.includes('bug') || subjectLower.includes('crash')) {
                    category = 'technical';
                    priority = 'high';
                    description = "I encountered a technical issue. [Please provide steps to reproduce, what you expected to happen, and what happened instead]";
                } else if (subjectLower.includes('project') || subjectLower.includes('milestone') || subjectLower.includes('deadline')) {
                    category = 'project';
                    priority = 'medium';
                    description = "I'm having an issue with my project. [Please provide project name/ID and describe the specific problem]";
                }

                if (category || priority || description) {
                    showResults(`
                        <div class="results-header">
                            <h4>Form Completion Suggestions</h4>
                            <button class="close-results" id="close-results">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p>Based on your subject, here are our suggested values for your ticket:</p>
                        ${category ? `
                        <div class="result-item">
                            <h5 class="result-title">Category Suggestion</h5>
                            <p class="result-content">We suggest selecting "${categoryName(category)}" as your ticket category.</p>
                            <div class="result-action">
                                <button class="apply-suggestion" data-field="category" data-value="${category}">Apply Suggestion</button>
                            </div>
                        </div>
                        ` : ''}
                        ${priority ? `
                        <div class="result-item">
                            <h5 class="result-title">Priority Suggestion</h5>
                            <p class="result-content">Based on your issue, we suggest "${priorityName(priority)}" priority.</p>
                            <div class="result-action">
                                <button class="apply-suggestion" data-field="priority" data-value="${priority}">Apply Suggestion</button>
                            </div>
                        </div>
                        ` : ''}
                        ${description ? `
                        <div class="result-item">
                            <h5 class="result-title">Description Template</h5>
                            <p class="result-content">${description}</p>
                            <div class="result-action">
                                <button class="apply-suggestion" data-field="description" data-value="${description}">Use Template</button>
                            </div>
                        </div>
                        ` : ''}
                    `);

                    // Add event listener to close button
                    document.getElementById('close-results').addEventListener('click', function() {
                        assistantResults.classList.remove('active');
                    });

                    // Add event listeners to apply suggestion buttons
                    document.querySelectorAll('.apply-suggestion').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const field = this.dataset.field;
                            const value = this.dataset.value;

                            if (field === 'category') {
                                document.getElementById('ticket-category').value = value;
                            } else if (field === 'priority') {
                                document.getElementById('ticket-priority').value = value;
                            } else if (field === 'description') {
                                const descEl = document.getElementById('ticket-description');
                                if (descEl) {
                                    descEl.value = value;
                                    descEl.focus();
                                }
                            }

                            this.textContent = 'Applied!';
                            this.disabled = true;
                            this.style.backgroundColor = '#2ecc71';
                        });
                    });
                } else {
                    showResults(`
                        <div class="results-header">
                            <h4>No Specific Suggestions</h4>
                            <button class="close-results" id="close-results">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p>We couldn't determine specific suggestions based on your subject. Please continue filling out the form manually, and provide detailed information to help our support team assist you better.</p>
                    `);

                    // Add event listener to close button
                    document.getElementById('close-results').addEventListener('click', function() {
                        assistantResults.classList.remove('active');
                    });
                }
            }, 1500); // Simulated delay
        });

        // Function to recommend priority
        priorityRecommendationBtn.addEventListener('click', function() {
            const subject = document.getElementById('ticket-subject').value;
            const description = document.getElementById('ticket-description')?.value || '';

            if (!subject && !description) {
                showResults('Please fill in at least the subject or description so we can suggest a priority level.');
                return;
            }

            // Show loading state
            showLoading();

            // Simulate API call delay
            setTimeout(() => {
                // Analyze content to suggest priority
                const combinedText = (subject + ' ' + description).toLowerCase();
                let priority = '';
                let explanation = '';

                if (combinedText.includes('urgent') || combinedText.includes('emergency') ||
                    combinedText.includes('critical') || combinedText.includes('security') ||
                    combinedText.includes('hacked') || combinedText.includes('fraud')) {
                    priority = 'critical';
                    explanation = 'This appears to be a critical issue that may involve security concerns or complete blocking of work.';
                } else if (combinedText.includes('broken') || combinedText.includes('not working') ||
                    combinedText.includes('error') || combinedText.includes('can\'t access')) {
                    priority = 'high';
                    explanation = 'This issue seems to be actively blocking your ability to use important features.';
                } else if (combinedText.includes('how to') || combinedText.includes('help with') ||
                    combinedText.includes('problem with')) {
                    priority = 'medium';
                    explanation = 'This appears to be an issue that is affecting your work but may have workarounds.';
                } else {
                    priority = 'low';
                    explanation = 'This seems to be a general question or suggestion that isn\'t urgently impacting your work.';
                }

                showResults(`
                    <div class="results-header">
                        <h4>Priority Recommendation</h4>
                        <button class="close-results" id="close-results">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="result-item">
                        <h5 class="result-title">Recommended Priority: ${priorityName(priority)}</h5>
                        <p class="result-content">${explanation}</p>
                        <div class="result-action">
                            <button class="apply-suggestion" data-field="priority" data-value="${priority}">Apply This Priority</button>
                        </div>
                    </div>
                    <p>Note: You can adjust the priority if you feel it doesn't accurately reflect your situation.</p>
                `);

                // Add event listener to close button
                document.getElementById('close-results').addEventListener('click', function() {
                    assistantResults.classList.remove('active');
                });

                // Add event listener to apply suggestion button
                document.querySelector('.apply-suggestion').addEventListener('click', function() {
                    document.getElementById('ticket-priority').value = this.dataset.value;
                    this.textContent = 'Applied!';
                    this.disabled = true;
                    this.style.backgroundColor = '#2ecc71';
                });
            }, 1500); // Simulated delay
        });

        // Helper functions
        function showResults(content) {
            assistantResults.innerHTML = content;
            assistantResults.classList.add('active');
        }

        function showLoading() {
            assistantResults.innerHTML = `
                <div class="loading">
                    <div class="loading-spinner"></div>
                </div>
            `;
            assistantResults.classList.add('active');
        }

        function categoryName(categoryValue) {
            const categories = {
                'account': 'Account Issues',
                'billing': 'Billing & Payments',
                'technical': 'Technical Problems',
                'project': 'Project Issues',
                'communication': 'Communication Problems',
                'feedback': 'Platform Feedback',
                'other': 'Other'
            };
            return categories[categoryValue] || categoryValue;
        }

        function priorityName(priorityValue) {
            const priorities = {
                'low': 'Low',
                'medium': 'Medium',
                'high': 'High',
                'critical': 'Critical'
            };
            return priorities[priorityValue] || priorityValue;
        } // Show assistant by default
        assistantBody.classList.add('active');
        toggleAssistant.classList.add('active');
    });
</script>

<!-- Form validation scripts -->
<script src="<?php echo URL_ROOT; ?>/js/support-ticket-form.js"></script>