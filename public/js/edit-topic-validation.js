/**
 * Forum Topic Edit Form Validation
 * Provides real-time validation for the forum topic edit form
 */

document.addEventListener('DOMContentLoaded', function () {
    // Get the edit form element
    const editForm = document.getElementById('editTopicForm');

    if (!editForm) return; // Exit if form doesn't exist on current page

    console.log('Edit topic form detected, initializing validation');

    // Form elements
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category_id');
    const contentTextarea = document.getElementById('content');
    const submitButton = editForm.querySelector('button[type="submit"]');

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
    if (titleInput.value.trim()) {
        // Set initial state to valid since we're editing an existing topic
        formValid.title = titleInput.value.trim().length >= TITLE_MIN_LENGTH;
        titleInput.dispatchEvent(new Event('input'));
    }

    if (categorySelect.value) {
        formValid.category = true;
        categorySelect.dispatchEvent(new Event('change'));
    }

    if (contentTextarea.value.trim()) {
        formValid.content = contentTextarea.value.trim().length >= CONTENT_MIN_LENGTH;
        contentTextarea.dispatchEvent(new Event('input'));
    }

    console.log('Initial validation state:', formValid);

    // Form submission
    editForm.addEventListener('submit', function (e) {
        console.log('Form submitted, validating...');

        // Trigger validation on all fields
        titleInput.dispatchEvent(new Event('input'));
        categorySelect.dispatchEvent(new Event('change'));
        contentTextarea.dispatchEvent(new Event('input'));

        // Log validation state
        console.log('Validation state before submission:', formValid);

        // Prevent submission if form is invalid
        if (!formValid.title || !formValid.category || !formValid.content) {
            e.preventDefault();
            console.log('Form validation failed, preventing submission');

            // Scroll to the first invalid field
            const firstInvalidField = editForm.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            console.log('Form validation successful, allowing submission');
        }
    });
});