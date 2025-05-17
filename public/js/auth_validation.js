document.addEventListener('DOMContentLoaded', function() {
    const formsToValidate = [
        document.getElementById('email-verification-form'),
        document.getElementById('password-form'),
        document.querySelector('form[action="<?php echo URL_ROOT; ?>/users/register"]'), // Register form might not have a specific ID
        document.getElementById('reset-form')
        // Add other form IDs or selectors if needed
    ];

    formsToValidate.forEach(form => {
        if (form) {
            form.setAttribute('novalidate', true); // Disable browser's default validation

            form.addEventListener('submit', function(event) {
                let isValid = true;
                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                // Find required fields within this form
                // We'll identify them by common attributes or perhaps add a data attribute later
                // For now, let's assume inputs/selects that previously had 'required'
                const requiredFields = form.querySelectorAll('input[name="email"], input[name="password"], input[name="first_name"], input[name="last_name"], select[name="country"], input[name="terms"]');

                requiredFields.forEach(field => {
                    const isCheckbox = field.type === 'checkbox';
                    let isEmpty = false;

                    if (isCheckbox) {
                        isEmpty = !field.checked;
                    } else {
                        isEmpty = field.value.trim() === '';
                    }

                    if (isEmpty) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        // Find the associated error message container
                        let errorContainer = field.closest('.mb-3, .mb-4, .name-field').querySelector('.invalid-feedback');
                        if (!errorContainer && field.closest('.form-check')) { // Special case for terms checkbox
                           errorContainer = field.closest('.mb-4').querySelector('.invalid-feedback');
                        }
                        
                        if (errorContainer) {
                            let fieldName = field.previousElementSibling?.textContent || field.id || field.name;
                            if(isCheckbox && field.id === 'terms') {
                                errorContainer.textContent = 'You must agree to the terms of service.';
                            } else if (field.tagName === 'SELECT') {
                                errorContainer.textContent = 'Please select an option.';
                            } else {
                                errorContainer.textContent = 'This field is required.';
                            }
                        } else {
                            console.warn('Could not find error container for field:', field);
                        }
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Stop submission if validation fails
                }
                // If isValid is true, the form will submit naturally
            });
        }
    });

    // Add password toggle functionality (assuming it's not handled elsewhere)
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});