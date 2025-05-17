// Global functions for project modal interaction
window.openProjectModal = function(project) {
    try {
        // Make sure project is defined
        if (!project) {
            console.error('No project data provided to openProjectModal');
            return;
        }

        // Parse project data if it's a string
        if (typeof project === 'string') {
            try {
                project = JSON.parse(project);
            } catch (e) {
                console.error('Failed to parse project data:', e);
                return;
            }
        }
        
        // Clear any previous form data and errors
        const applyForm = document.getElementById('apply-project-form');
        if (applyForm) {
            applyForm.reset();
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.remove('has-error');
            });
        }
        
        // Set project data in the modal
        try {
            // Set hidden project ID
            const projectIdField = document.getElementById('modal-project-id');
            if (projectIdField) projectIdField.value = project.id;
            
            // Set text content of various elements
            const elementsToUpdate = {
                'modal-project-title': project.title || 'Project Details',
                'modal-project-category': project.category ? (project.category.charAt(0).toUpperCase() + project.category.slice(1)) : 'N/A',
                'modal-project-status': project.status ? (project.status.charAt(0).toUpperCase() + project.status.slice(1)) : 'N/A',
                'modal-project-description': project.description || 'No description provided',
                'modal-project-participants': project.participants_count || '0',
                'modal-project-max-participants': project.max_participants || '∞',
                'modal-project-skills-required': project.skills_required || 'Not specified',
                'modal-project-location': project.location || 'Not specified',
                'modal-project-remote': project.is_remote == '1' ? 'Remote' : 'On-site'
            };
            
            // Update each element, skipping if not found
            for (const [id, value] of Object.entries(elementsToUpdate)) {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                } else {
                    console.warn(`Element with ID ${id} not found in the modal`);
                }
            }
        } catch (e) {
            console.error('Error setting project data in modal:', e);
        }
        
        // Format dates
        try {
            const elementsToUpdate = {
                'modal-project-start-date': project.start_date ? new Date(project.start_date) : null,
                'modal-project-end-date': project.end_date ? new Date(project.end_date) : null
            };
            
            for (const [id, dateValue] of Object.entries(elementsToUpdate)) {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = dateValue && !isNaN(dateValue.getTime()) ? 
                        dateValue.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not specified';
                } else {
                    console.warn(`Element with ID ${id} not found in the modal`);
                }
            }
        } catch (e) {
            console.error('Error formatting dates:', e);
        }
        
        // Show the modal
        const modal = document.getElementById('project-details-modal');
        if (modal) {
            // Show the modal
            modal.classList.add('is-visible');
            document.body.classList.add('modal-open');
            
            // Dispatch a custom 'shown' event for accessibility
            const event = new CustomEvent('shown');
            modal.dispatchEvent(event);
            
            // Prevent modal from closing when clicking on the modal container
            const modalContainer = modal.querySelector('.modal-container');
            if (modalContainer) {
                modalContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Focus on the first form element after a short delay
            setTimeout(() => {
                const messageTextarea = document.getElementById('application-message');
                if (messageTextarea) {
                    messageTextarea.focus();
                }
            }, 100);
        
            // Make sure form elements are clickable by stopping event propagation
            const formElements = modal.querySelectorAll('input, textarea, button, select');
            formElements.forEach(element => {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        }
    } catch (e) {
        console.error('Error in openProjectModal:', e);
        if (window.showNotification) {
            window.showNotification('Error', 'Could not open project details. Please refresh the page and try again.', 'error');
        }
    }
};

// Close the modal function
window.closeProjectModal = function() {
    try {
        const modal = document.getElementById('project-details-modal');
        if (!modal) return;
        
        // Start fade-out animation
        modal.style.opacity = '0';
        
        // Remove classes and reset after animation completes
        setTimeout(() => {
            modal.classList.remove('is-visible');
            modal.style.opacity = '';
            document.body.classList.remove('modal-open');
            
            // Clean up event listeners to prevent memory leaks
            const modalContainer = modal.querySelector('.modal-container');
            if (modalContainer) {
                const newContainer = modalContainer.cloneNode(true);
                modalContainer.parentNode.replaceChild(newContainer, modalContainer);
            }
        }, 300);
    } catch (error) {
        console.error('Error closing modal:', error);
    }
};

// Handle form submission
window.submitProjectApplication = function() {
    try {
        // Get form values
        const projectIdField = document.getElementById('modal-project-id');
        if (!projectIdField) {
            throw new Error('Project ID field not found');
        }
        
        const projectId = projectIdField.value;
        const messageField = document.getElementById('application-message');
        const skillsField = document.getElementById('application-skills');
        
        if (!messageField || !skillsField) {
            throw new Error('Form fields not found');
        }
        
        const message = messageField.value.trim();
        const skills = skillsField.value.trim();
        
        // Validate form
        let isValid = true;
        
        // Reset previous errors
        document.querySelectorAll('.form-group').forEach(group => {
            group.classList.remove('has-error');
        });
        
        // Validate project ID
        if (!projectId) {
            console.error('Missing project ID');
            window.showNotification('Error', 'An error occurred. Please try again.', 'error');
            return;
        }
        
        // Validate message
        const messageErrorEl = document.getElementById('message-error');
        if (!message || message.length < 10) {
            if (messageErrorEl) {
                messageErrorEl.textContent = 'Please provide a detailed message (at least 10 characters).';
                messageField.parentElement.classList.add('has-error');
                messageField.focus();
            } else {
                window.showNotification('Error', 'Please provide a detailed message (at least 10 characters).', 'error');
            }
            isValid = false;
        }
        
        // Validate skills (optional, but if provided must be valid)
        const skillsErrorEl = document.getElementById('skills-error');
        if (skills && skills.length < 3) {
            if (skillsErrorEl) {
                skillsErrorEl.textContent = 'Please provide valid skills or leave blank.';
                skillsField.parentElement.classList.add('has-error');
                if (isValid) {
                    skillsField.focus();
                }
            } else {
                window.showNotification('Error', 'Please provide valid skills or leave blank.', 'error');
            }
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.modal-footer .btn-primary');
        if (!submitBtn) {
            throw new Error('Submit button not found');
        }
        
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Submitting...';
        submitBtn.classList.add('btn-loading');        // Get API URL - use the direct endpoint if available
        let apiUrl;
        
        if (window.API_ENDPOINT) {
            // Use the direct API endpoint defined in PHP
            apiUrl = window.API_ENDPOINT;
        } else {
            // Fallback in case API_ENDPOINT is not defined
            const urlRoot = window.URL_ROOT || '';
            
            // Avoid duplicating the origin if URL_ROOT already has it
            if (urlRoot.startsWith('http://') || urlRoot.startsWith('https://')) {
                apiUrl = `${urlRoot}/projects/applyToProjectJson`;
            } else {
                apiUrl = `${window.location.origin}${urlRoot}/projects/applyToProjectJson`;
            }
        }
        
        console.log('Submitting application to:', apiUrl);
        
        // Submit application via AJAX
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                project_id: projectId,
                message: message,
                skills: skills
            })
        })
        .then(response => {
            if (!response.ok) {
                // Handle HTTP errors
                let errorMessage = 'Failed to submit application. Please try again.';
                
                if (response.status === 403) {
                    errorMessage = 'You must be logged in to apply for this project.';
                } else if (response.status === 409) {
                    errorMessage = 'You have already applied to this project.';
                } else if (response.status === 422) {
                    errorMessage = 'Please check your application details and try again.';
                }
                
                return response.json().then(data => {
                    throw new Error(data.message || errorMessage);
                }).catch(() => {
                    throw new Error(errorMessage);
                });
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update UI to show application was successful
                const applyBtn = document.querySelector(`.apply-btn[data-project-id="${projectId}"]`);
                if (applyBtn) {
                    applyBtn.outerHTML = `<button class="project-action applied-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Applied
                    </button>`;
                }
                
                // Close modal and show success message
                window.closeProjectModal();
                window.showNotification('Success', 'Your application has been submitted successfully.', 'success');
            } else {
                throw new Error(data.message || 'Failed to submit application. Please try again.');
            }
        })        .catch(error => {
            console.error('Application error:', error);
            let errorMsg = 'Failed to submit application. Please try again.';
            
            if (error.message) {
                errorMsg = error.message;
                // Add more context if it's a network error
                if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                    errorMsg = 'Network error: Please check your connection and try again.';
                    console.log('API URL that failed:', apiUrl);
                }
            }
            
            window.showNotification('Error', errorMsg, 'error');
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                submitBtn.classList.remove('btn-loading');
            }
        });
    } catch (error) {
        console.error('Form submission error:', error);
        window.showNotification('Error', error.message || 'An unexpected error occurred. Please try again.', 'error');
    }
};

// Notification toast function
window.showNotification = function(title, message, type = 'info', duration = 5000) {
    try {
        // Check for required container
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.style.position = 'fixed';
            container.style.bottom = '20px';
            container.style.right = '20px';
            container.style.zIndex = '10000';
            document.body.appendChild(container);
        }
        
        // Create notification elements
        const toast = document.createElement('div');
        toast.className = `notification-toast ${type}`;
        
        const content = document.createElement('div');
        content.className = 'notification-content';
        
        const titleEl = document.createElement('span');
        titleEl.className = 'notification-title';
        titleEl.textContent = title || 'Notification';
        
        const messageEl = document.createElement('div');
        messageEl.className = 'notification-message';
        messageEl.textContent = message || '';
        
        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.innerHTML = '&times;';
        closeButton.addEventListener('click', () => {
            removeToast(toast);
        });
        
        const progress = document.createElement('div');
        progress.className = 'notification-progress';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'notification-progress-bar';
        progressBar.style.transition = `width ${duration}ms linear`;
        progressBar.style.width = '100%';
        
        // Assemble the notification
        content.appendChild(titleEl);
        content.appendChild(messageEl);
        progress.appendChild(progressBar);
        toast.appendChild(content);
        toast.appendChild(closeButton);
        toast.appendChild(progress);
        
        // Add to container
        container.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
            progressBar.style.width = '0%';
        }, 10);
        
        // Set timeout to remove
        const timeout = setTimeout(() => {
            removeToast(toast);
        }, duration);
        
        // Store timeout to clear if closed manually
        toast.dataset.timeout = timeout;
        
        // Function to remove toast
        function removeToast(element) {
            // Clear timeout if it exists
            if (element.dataset.timeout) {
                clearTimeout(element.dataset.timeout);
            }
            
            // Remove show class to trigger fade out
            element.classList.remove('show');
            
            // Remove element after animation
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 300);
        }
    } catch (error) {
        console.error('Error showing notification:', error);
    }
};
