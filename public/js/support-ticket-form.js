/**
 * Support Ticket Form Handler
 * Enhanced form submission and validation for the support ticket system
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('supportTicketForm');
    const subjectInput = document.getElementById('ticket-subject');
    const categorySelect = document.getElementById('ticket-category');
    const prioritySelect = document.getElementById('ticket-priority');
    const descriptionText = document.getElementById('ticket-description');
    const fileInput = document.getElementById('ticket-attachments');
    const fileLabel = document.getElementById('attachment-label');
    const filePreviewContainer = document.getElementById('file-preview-container');
    const formResponse = document.getElementById('form-response');
    const progressBar = document.getElementById('form-progress');
    const progressValue = document.querySelector('.progress-value');
    const submitButton = document.getElementById('submit-button');
    
    // Form validation variables
    let formIsValid = false;
    const validFields = {
        subject: false,
        category: false,
        priority: false,
        description: false
    };
    
    // Character counters
    const addCharacterCounter = (element, maxLength = 300) => {
        const container = element.parentElement;
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.innerHTML = `<span class="current-count">0</span>/${maxLength}`;
        
        container.appendChild(counter);
        
        element.addEventListener('input', () => {
            const currentLength = element.value.length;
            counter.querySelector('.current-count').textContent = currentLength;
            
            if (currentLength > maxLength) {
                counter.classList.add('limit-exceeded');
            } else {
                counter.classList.remove('limit-exceeded');
            }
        });
    };
    
    // Add character counter to appropriate fields
    if (subjectInput) addCharacterCounter(subjectInput, 100);
    if (descriptionText) addCharacterCounter(descriptionText, 2000);
    
    // Form field validation functions
    const validators = {
        subject: (value) => {
            if (!value.trim()) return 'Please enter a subject';
            if (value.length < 5) return 'Subject must be at least 5 characters';
            if (value.length > 100) return 'Subject cannot exceed 100 characters';
            return null;
        },
        category: (value) => {
            if (!value || value === '') return 'Please select a category';
            return null;
        },
        priority: (value) => {
            if (!value || value === '') return 'Please select a priority level';
            return null;
        },
        description: (value) => {
            if (!value.trim()) return 'Please provide a description';
            if (value.length < 20) return 'Description must be at least 20 characters';
            if (value.length > 2000) return 'Description cannot exceed 2000 characters';
            return null;
        }
    };
    
    // Function to validate a field and update UI
    const validateField = (fieldName, element) => {
        const value = element.value;
        const errorMessage = validators[fieldName](value);
        const formGroup = element.closest('.form-group');
        const existingError = formGroup.querySelector('.error-message');
        
        if (existingError) {
            existingError.remove();
        }
        
        if (errorMessage) {
            element.classList.add('error');
            element.classList.remove('is-valid');
            formGroup.classList.add('has-error');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMessage}`;
            
            formGroup.appendChild(errorDiv);
            validFields[fieldName] = false;
        } else {
            element.classList.remove('error');
            element.classList.add('is-valid');
            formGroup.classList.remove('has-error');
            validFields[fieldName] = true;
        }
        
        updateFormValidity();
    };
    
    // Update form validity status and submit button state
    const updateFormValidity = () => {
        formIsValid = Object.values(validFields).every(isValid => isValid);
        submitButton.disabled = !formIsValid;
    };
    
    // Live validation for inputs
    if (subjectInput) {
        subjectInput.addEventListener('blur', () => validateField('subject', subjectInput));
        subjectInput.addEventListener('input', () => validateField('subject', subjectInput));
    }
    
    if (categorySelect) {
        categorySelect.addEventListener('change', () => validateField('category', categorySelect));
    }
    
    if (prioritySelect) {
        prioritySelect.addEventListener('change', () => validateField('priority', prioritySelect));
    }
    
    if (descriptionText) {
        descriptionText.addEventListener('blur', () => validateField('description', descriptionText));
        descriptionText.addEventListener('input', () => {
            if (descriptionText.value.length >= 20) {
                validateField('description', descriptionText);
            }
        });
    }
    
    // Handle file upload previews
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            filePreviewContainer.innerHTML = '';
            
            if (this.files.length > 0) {
                fileLabel.textContent = `${this.files.length} file(s) selected`;
                
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    const isValidSize = fileSize <= 5; // Max 5MB per file
                    
                    const filePreview = document.createElement('div');
                    filePreview.className = `file-preview ${isValidSize ? '' : 'invalid-file'}`;
                    
                    const fileIcon = getFileIcon(file.name);
                    
                    filePreview.innerHTML = `
                        <span class="file-icon">${fileIcon}</span>
                        <span class="file-name">${file.name} (${fileSize} MB)</span>
                        <span class="remove-file" data-index="${i}">
                            <i class="fas fa-times"></i>
                        </span>
                        ${!isValidSize ? '<span class="file-error">File too large (max 5MB)</span>' : ''}
                    `;
                    
                    filePreviewContainer.appendChild(filePreview);
                    
                    // Add event listener to remove button
                    filePreview.querySelector('.remove-file').addEventListener('click', function() {
                        // Note: We can't directly modify FileList, so we'll recreate the input
                        filePreviewContainer.removeChild(filePreview);
                        
                        // Update the label
                        const remainingFiles = filePreviewContainer.querySelectorAll('.file-preview').length;
                        fileLabel.textContent = remainingFiles > 0 
                            ? `${remainingFiles} file(s) selected` 
                            : 'Add files (screenshots, documents, etc.)';
                    });
                }
            } else {
                fileLabel.textContent = 'Add files (screenshots, documents, etc.)';
            }
        });
    }
    
    // Function to get appropriate icon for file type
    function getFileIcon(filename) {
        const extension = filename.split('.').pop().toLowerCase();
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            return '<i class="fas fa-file-image" style="color: #3498db;"></i>';
        } else if (['pdf'].includes(extension)) {
            return '<i class="fas fa-file-pdf" style="color: #e74c3c;"></i>';
        } else if (['doc', 'docx'].includes(extension)) {
            return '<i class="fas fa-file-word" style="color: #2b579a;"></i>';
        } else if (['txt'].includes(extension)) {
            return '<i class="fas fa-file-alt" style="color: #95a5a6;"></i>';
        } else {
            return '<i class="fas fa-file"></i>';
        }
    }
    
    // Form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Final validation before submit
            validateField('subject', subjectInput);
            validateField('category', categorySelect);
            validateField('priority', prioritySelect);
            validateField('description', descriptionText);
            
            if (!formIsValid) {
                showFormMessage('error', '<i class="fas fa-exclamation-triangle"></i> Please fix the errors in the form before submitting');
                return;
            }
            
            // Disable submit button and show progress
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            progressBar.style.display = 'block';
            
            // Simulate progress (in a real app, this would track actual AJAX progress)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                progressValue.style.width = `${Math.min(progress, 90)}%`;
                
                if (progress >= 90) {
                    clearInterval(progressInterval);
                }
            }, 200);
            
            // Create form data
            const formData = new FormData(form);
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressValue.style.width = '100%';
                
                if (data.success) {
                    showFormMessage('success', `<i class="fas fa-check-circle"></i> ${data.message}`);
                    form.reset();
                    
                    // Reset validation states
                    resetValidation();
                    
                    // Redirect after a delay if a redirect URL was provided
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                } else {
                    showFormMessage('error', `<i class="fas fa-exclamation-circle"></i> ${data.message}`);
                    
                    // Show field-specific errors if provided
                    if (data.errors) {
                        for (const [field, msg] of Object.entries(data.errors)) {
                            const element = document.getElementById(`ticket-${field}`);
                            if (element) {
                                const formGroup = element.closest('.form-group');
                                element.classList.add('error');
                                
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message';
                                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${msg}`;
                                
                                formGroup.appendChild(errorDiv);
                            }
                        }
                    }
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                console.error('Error submitting form:', error);
                showFormMessage('error', '<i class="fas fa-exclamation-triangle"></i> Network or server error. Please try again.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Ticket <span class="shortcut-hint">(Ctrl+Enter)</span>';
                
                setTimeout(() => {
                    progressBar.style.display = 'none';
                    progressValue.style.width = '0%';
                }, 1000);
            });
        });
    }
    
    // Function to show response messages
    function showFormMessage(type, message) {
        formResponse.className = `form-response ${type}`;
        formResponse.innerHTML = message;
        formResponse.style.display = 'block';
        
        // Scroll to message
        formResponse.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Hide message after some time if it's a success message
        if (type === 'success') {
            setTimeout(() => {
                formResponse.style.display = 'none';
            }, 5000);
        }
    }
    
    // Reset validation states
    function resetValidation() {
        const errorMessages = form.querySelectorAll('.error-message');
        const invalidInputs = form.querySelectorAll('.error');
        const validInputs = form.querySelectorAll('.is-valid');
        
        errorMessages.forEach(msg => msg.remove());
        invalidInputs.forEach(input => input.classList.remove('error'));
        validInputs.forEach(input => input.classList.remove('is-valid'));
        
        // Reset file preview
        if (filePreviewContainer) filePreviewContainer.innerHTML = '';
        if (fileLabel) fileLabel.textContent = 'Add files (screenshots, documents, etc.)';
        
        // Reset validation state
        Object.keys(validFields).forEach(key => {
            validFields[key] = false;
        });
        
        updateFormValidity();
    }
    
    // Add keyboard shortcut (Ctrl+Enter to submit)
    document.addEventListener('keydown', function(e) {
        // Check if Ctrl+Enter was pressed and form exists and is valid
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && form && formIsValid) {
            e.preventDefault();
            submitButton.click();
        }
    });
    
    // Initialize validation on page load
    if (subjectInput && subjectInput.value) validateField('subject', subjectInput);
    if (categorySelect && categorySelect.value) validateField('category', categorySelect);
    if (prioritySelect && prioritySelect.value) validateField('priority', prioritySelect);
    if (descriptionText && descriptionText.value) validateField('description', descriptionText);
});
