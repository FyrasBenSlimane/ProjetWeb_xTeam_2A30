/**
 * Forum Topic Creation Form Validation
 * Provides real-time validation for the forum topic creation form
 */

document.addEventListener('DOMContentLoaded', function () {
    // Get the form element
    const topicForm = document.querySelector('form[action*="createTopic"]');

    if (!topicForm) return; // Exit if form doesn't exist on current page

    // Form elements
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category_id');
    const contentTextarea = document.getElementById('content');
    const submitButton = topicForm.querySelector('button[type="submit"]');

    // Validation state
    let formValid = {
        title: false,
        category: false,
        content: false
    };

    // Character limits
    const TITLE_MIN_LENGTH = 5;
    const TITLE_MAX_LENGTH = 100;
    const CONTENT_MIN_LENGTH = 20;

    // Helper function to show validation feedback
    function showFeedback(element, isValid, message) {
        // Remove existing classes
        element.classList.remove('is-valid', 'is-invalid');

        // Add appropriate class
        element.classList.add(isValid ? 'is-valid' : 'is-invalid');

        // Find or create feedback element
        let feedbackElement = element.nextElementSibling;
        if (!feedbackElement || !feedbackElement.classList.contains('invalid-feedback')) {
            feedbackElement = document.createElement('div');
            feedbackElement.className = isValid ? 'valid-feedback' : 'invalid-feedback';
            element.parentNode.insertBefore(feedbackElement, element.nextSibling);
        }

        // Set feedback message
        feedbackElement.textContent = message;
    }

    // Helper function to display custom alert
    function showCustomAlert(message) {
        // Create alert container if it doesn't exist
        let alertContainer = document.querySelector('.custom-alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'custom-alert-container';
            alertContainer.style.position = 'fixed';
            alertContainer.style.top = '20px';
            alertContainer.style.left = '50%';
            alertContainer.style.transform = 'translateX(-50%)';
            alertContainer.style.zIndex = '1050';
            document.body.appendChild(alertContainer);
        }

        // Create alert element
        const alertElement = document.createElement('div');
        alertElement.className = 'alert alert-danger alert-dismissible fade show';
        alertElement.role = 'alert';
        alertElement.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        alertElement.style.minWidth = '300px';

        // Alert content
        alertElement.innerHTML = `
            <strong>Form Error</strong> - ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Add to container
        alertContainer.appendChild(alertElement);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            alertElement.classList.remove('show');
            setTimeout(() => alertElement.remove(), 150);
        }, 5000);

        // Close button functionality
        const closeButton = alertElement.querySelector('.btn-close');
        closeButton.addEventListener('click', function () {
            alertElement.classList.remove('show');
            setTimeout(() => alertElement.remove(), 150);
        });
    }

    // Helper function to update submit button state
    function updateSubmitButton() {
        const isFormValid = formValid.title && formValid.category && formValid.content;
        submitButton.disabled = !isFormValid;
    }

    // Title validation
    titleInput.addEventListener('input', function () {
        const value = this.value.trim();
        const length = value.length;

        if (length === 0) {
            formValid.title = false;
            showFeedback(this, false, 'Title is required');
        } else if (length < TITLE_MIN_LENGTH) {
            formValid.title = false;
            showFeedback(this, false, `Title must be at least ${TITLE_MIN_LENGTH} characters`);
        } else if (length > TITLE_MAX_LENGTH) {
            formValid.title = false;
            showFeedback(this, false, `Title cannot exceed ${TITLE_MAX_LENGTH} characters`);
        } else {
            formValid.title = true;
            showFeedback(this, true, 'Title looks good!');
        }

        // Show character count
        const remainingChars = TITLE_MAX_LENGTH - length;
        const charCountElement = document.getElementById('title-char-count');
        if (charCountElement) {
            charCountElement.textContent = `${remainingChars} characters remaining`;

            // Update color based on remaining characters
            charCountElement.classList.remove('text-warning', 'text-danger');
            if (remainingChars < 20) {
                charCountElement.classList.add('text-danger');
            } else if (remainingChars < 40) {
                charCountElement.classList.add('text-warning');
            }
        }

        updateSubmitButton();
    });

    // Category validation
    categorySelect.addEventListener('change', function () {
        const value = this.value;

        if (!value) {
            formValid.category = false;
            showFeedback(this, false, 'Please select a category');
        } else {
            formValid.category = true;
            showFeedback(this, true, 'Category selected');
        }

        updateSubmitButton();
    });

    // Content validation
    contentTextarea.addEventListener('input', function () {
        const value = this.value.trim();
        const length = value.length;

        if (length === 0) {
            formValid.content = false;
            showFeedback(this, false, 'Content is required');
        } else if (length < CONTENT_MIN_LENGTH) {
            formValid.content = false;
            showFeedback(this, false, `Content must be at least ${CONTENT_MIN_LENGTH} characters`);
        } else {
            formValid.content = true;
            showFeedback(this, true, 'Content looks good!');
        }

        // Update character count
        const charCountElement = document.getElementById('content-char-count');
        if (charCountElement) {
            charCountElement.textContent = `${length} characters`;

            // Update color based on character count
            charCountElement.classList.remove('text-warning', 'text-danger');
            if (length < CONTENT_MIN_LENGTH) {
                charCountElement.classList.add('text-danger');
            } else if (length < CONTENT_MIN_LENGTH + 10) {
                charCountElement.classList.add('text-warning');
            }
        }

        updateSubmitButton();
    });

    // Initial form validation
    if (titleInput.value.trim()) titleInput.dispatchEvent(new Event('input'));
    if (categorySelect.value) categorySelect.dispatchEvent(new Event('change'));
    if (contentTextarea.value.trim()) contentTextarea.dispatchEvent(new Event('input'));

    // Form submission
    topicForm.addEventListener('submit', function (e) {
        // Trigger validation on all fields
        titleInput.dispatchEvent(new Event('input'));
        categorySelect.dispatchEvent(new Event('change'));
        contentTextarea.dispatchEvent(new Event('input'));

        // Prevent submission if form is invalid
        if (!formValid.title || !formValid.category || !formValid.content) {
            e.preventDefault();

            // Build error message
            let errorMessages = [];
            if (!formValid.title) {
                errorMessages.push('Please enter a valid title');
                titleInput.focus();
            }
            if (!formValid.category) {
                errorMessages.push('Please select a category');
                if (!errorMessages.length === 1) categorySelect.focus();
            }
            if (!formValid.content) {
                errorMessages.push(`Content must be at least ${CONTENT_MIN_LENGTH} characters`);
                if (!errorMessages.length === 1) contentTextarea.focus();
            }

            // Show custom alert with combined errors
            showCustomAlert(errorMessages.join('. '));

            // Scroll to the first invalid field
            const firstInvalidField = topicForm.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});