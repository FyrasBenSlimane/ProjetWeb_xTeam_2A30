// JavaScript for support_tickets.php
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, initializing ticket view handlers');
    // Add Font Awesome if not already loaded
    if (!document.getElementById('font-awesome-css')) {
        const link = document.createElement('link');
        link.id = 'font-awesome-css';
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css';
        document.head.appendChild(link);
    }

    // Check if user is admin
    const isAdmin = document.querySelector('.admin-dashboard-overview') !== null;

    // Initialize admin-specific functionality if admin
    if (isAdmin) {
        initializeAdminFeatures();
    }

    // Ticket Detail Modal
    const ticketDetailModal = document.getElementById('ticketDetailModal');

    // Define the handler function before adding event listeners
    async function handleViewTicketClick(e) {
        console.log('View ticket button clicked', this);
        const ticketId = this.getAttribute('data-ticket-id');
        if (!ticketId) {
            console.error('No ticket ID found on button');
            showToast('Error', 'Could not determine ticket ID');
            return;
        }

        document.getElementById('ticketId').textContent = ticketId;
        document.getElementById('ticketIdField').value = ticketId;

        // Show loading state with better visual feedback
        document.querySelector('.ticket-details').innerHTML = `
            <div class="loading" style="display: flex; align-items: center; justify-content: center; padding: 2rem;">
                <div class="loading-spinner"></div>
                <p style="margin-left: 1rem; color: #4b5563;">Loading ticket details...</p>
            </div>
        `;

        document.getElementById('ticketMessages').innerHTML = `
            <div class="loading-conversation" style="padding: 2rem; text-align: center; color: #6b7280;">
                <div class="loading-spinner" style="margin: 0 auto 1rem;"></div>
                <p>Loading conversation history...</p>
            </div>
        `;

        // Display the modal while data is being loaded
        ticketDetailModal.classList.add('active');

        try {
            console.log('Fetching ticket data for ID:', ticketId);
            // Load ticket data via AJAX
            const ticket = await loadTicketData(ticketId);
            if (ticket) {
                console.log('Ticket data loaded:', ticket);

                // Update status banner
                updateTicketStatusBanner(ticket.status);

                // Populate ticket details in the modal with enhanced UI
                document.querySelector('.ticket-details').innerHTML = `
                    <div class="ticket-status-banner" id="ticketStatusBanner" style="padding: 0.75rem; margin-bottom: 1rem; border-radius: 0.375rem; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-circle" style="font-size: 0.75rem;"></i>
                        <span id="ticketStatusText">Loading status...</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <div id="ticketSubject" class="form-input" style="font-weight: 600; color: #1f2937; font-size: 1.1rem;">${ticket.subject || 'No subject provided'}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="ticketDescription" class="form-input" style="background-color: #f9fafb; padding: 0.75rem; border-radius: 0.375rem; white-space: pre-wrap; line-height: 1.6;">${ticket.description || 'No description provided'}</div>
                    </div>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label class="form-label">Status</label>
                            <select id="ticketStatus" class="form-select" style="border-radius: 0.375rem;">
                                <option value="open" ${ticket.status === 'open' ? 'selected' : ''}>Open</option>
                                <option value="in-progress" ${ticket.status === 'in-progress' ? 'selected' : ''}>In Progress</option>
                                <option value="resolved" ${ticket.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                                <option value="closed" ${ticket.status === 'closed' ? 'selected' : ''}>Closed</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1; min-width: 200px;">
                            <label class="form-label">Priority</label>
                            <select id="ticketPriority" class="form-select" style="border-radius: 0.375rem;">
                                <option value="low" ${ticket.priority === 'low' ? 'selected' : ''}>Low</option>
                                <option value="medium" ${ticket.priority === 'medium' ? 'selected' : ''}>Medium</option>
                                <option value="high" ${ticket.priority === 'high' ? 'selected' : ''}>High</option>
                                <option value="critical" ${ticket.priority === 'critical' ? 'selected' : ''}>Critical</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <div id="ticketCategory" class="form-input">${ticket.category || 'General'}</div>
                    </div>
                    <div class="ticket-meta-info" style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top: 1rem; padding: 0.75rem; background-color: #f9fafb; border-radius: 0.375rem;">
                        <div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">CREATED</div>
                            <div id="ticketCreatedAt" style="font-weight: 500;">${formatDate(ticket.created_at)}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">UPDATED</div>
                            <div id="ticketUpdatedAt" style="font-weight: 500;">${formatDate(ticket.updated_at)}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">SUBMITTED BY</div>
                            <div id="ticketSubmitter" style="font-weight: 500;">${ticket.user_name || 'Unknown User'}</div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="updateTicketBtn" style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-save"></i> Update Ticket
                    </button>
                `;

                // Update the status banner after creating it
                updateTicketStatusBanner(ticket.status);

                // Load and display ticket responses
                try {
                    console.log('Loading ticket responses for ID:', ticketId);
                    const responses = await loadTicketResponses(ticketId);
                    console.log('Responses loaded:', responses);

                    // Format and display messages using our enhanced formatter
                    document.getElementById('ticketMessages').innerHTML = formatTicketMessages(responses, ticketId);
                } catch (responseError) {
                    console.error('Error loading ticket responses:', responseError);
                    document.getElementById('ticketMessages').innerHTML = `
                        <div class="alert alert-info" style="text-align: center; padding: 1rem; border-radius: 0.5rem; background-color: #f3f4f6; border: 1px solid #e5e7eb;">
                            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem; color: #f97316; font-size: 1.25rem;"></i>
                            <div style="margin: 0.5rem 0;">Failed to load conversation history</div>
                            <button class="retry-btn btn btn-sm btn-outline" style="margin-top: 0.5rem;">
                                <i class="fas fa-sync-alt"></i> Try Again
                            </button>
                        </div>
                    `;

                    // Add event listener for retry button
                    document.querySelector('.retry-btn').addEventListener('click', async function () {
                        document.getElementById('ticketMessages').innerHTML = `
                            <div class="loading-conversation" style="padding: 2rem; text-align: center; color: #6b7280;">
                                <div class="loading-spinner" style="margin: 0 auto 1rem;"></div>
                                <p>Loading conversation history...</p>
                            </div>
                        `;

                        try {
                            const responses = await loadTicketResponses(ticketId);
                            document.getElementById('ticketMessages').innerHTML = formatTicketMessages(responses, ticketId);
                        } catch (retryError) {
                            document.getElementById('ticketMessages').innerHTML = `
                                <div class="alert alert-info" style="text-align: center; padding: 1rem; border-radius: 0.5rem; background-color: #f3f4f6; border: 1px solid #e5e7eb;">
                                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem; color: #ef4444; font-size: 1.25rem;"></i>
                                    <div style="margin: 0.5rem 0;">Could not load conversation history</div>
                                    <p style="font-size: 0.875rem; color: #6b7280;">Please try again later</p>
                                </div>
                            `;
                        }
                    });
                }
            } else {
                document.querySelector('.ticket-details').innerHTML = `
                    <div class="alert alert-info" style="text-align: center; padding: 1.5rem; border-radius: 0.5rem; background-color: #f3f4f6; border: 1px solid #e5e7eb; border-left: 4px solid #ef4444;">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem; color: #ef4444; font-size: 1.75rem; margin-bottom: 0.75rem;"></i>
                        <div style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1.125rem;">Ticket not found</div>
                        <p style="color: #4b5563;">The requested ticket could not be found or may have been deleted.</p>
                    </div>
                `;
                // Clear the messages area
                document.getElementById('ticketMessages').innerHTML = '';
            }
        } catch (error) {
            console.error('Error loading ticket details:', error);
            document.querySelector('.ticket-details').innerHTML = `
                <div class="alert alert-info" style="text-align: center; padding: 1.5rem; border-radius: 0.5rem; background-color: #f3f4f6; border: 1px solid #e5e7eb; border-left: 4px solid #ef4444;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem; color: #ef4444; font-size: 1.75rem; margin-bottom: 0.75rem;"></i>
                    <div style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1.125rem;">Error loading ticket</div>
                    <p style="color: #4b5563;">Failed to load ticket details. Please try again or contact support if the issue persists.</p>
                    <button class="btn btn-primary retry-details-btn" style="margin-top: 1rem;">
                        <i class="fas fa-sync-alt"></i> Try Again
                    </button>
                </div>
            `;

            // Add event listener for retry button
            document.querySelector('.retry-details-btn').addEventListener('click', function () {
                // Simulate clicking the same ticket again
                const ticketId = document.getElementById('ticketId').textContent;
                const button = document.querySelector(`.view-ticket[data-ticket-id="${ticketId}"]`);
                if (button) button.click();
            });
        }
    }

    // Helper functions for AJAX requests
    async function loadTicketData(ticketId) {
        console.log('Loading ticket data for ID:', ticketId);
        try {
            const response = await fetch(`${window.appConfig.urlRoot}/dashboard/getTicketDetails?ticket_id=${ticketId}`);
            if (!response.ok) {
                throw new Error('Failed to load ticket data');
            }
            return await response.json();
        } catch (error) {
            console.error('Error loading ticket data:', error);
            showToast('Error', 'Failed to load ticket data');
            return null;
        }
    }

    async function loadTicketResponses(ticketId) {
        try {
            const response = await fetch(`${window.appConfig.urlRoot}/dashboard/getTicketResponses?ticketId=${ticketId}`);
            if (!response.ok) {
                throw new Error('Failed to load ticket responses');
            }
            return await response.json();
        } catch (error) {
            console.error('Error loading ticket responses:', error);
            showToast('Error', 'Failed to load ticket responses');
            return [];
        }
    }

    // Format date helper
    function formatDate(dateString) {
        if (!dateString) return 'Unknown';

        try {
            const date = new Date(dateString);

            // Check if date is valid
            if (isNaN(date.getTime())) {
                return dateString; // Return original if invalid
            }

            // Format date: May 16, 2025 at 14:30
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) + ' at ' + date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return dateString; // Return original on error
        }
    }

    // Display ticket responses in the conversation area
    function displayTicketResponses(responses) {
        const messagesContainer = document.getElementById('ticketMessages');
        messagesContainer.innerHTML = ''; // Clear existing messages

        if (!responses || responses.length === 0) {
            messagesContainer.innerHTML = `
                <div class="alert alert-info" style="text-align: center; padding: 1.5rem; background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 0.5rem;">
                    <i class="fas fa-info-circle" style="font-size: 1.5rem; color: #6b7280; margin-bottom: 0.5rem;"></i>
                    <p style="color: #4b5563;">No responses yet. You can be the first to respond to this ticket.</p>
                </div>
            `;
            return;
        }

        // Group header to show total responses
        const groupHeader = document.createElement('div');
        groupHeader.style.marginBottom = '1rem';
        groupHeader.style.display = 'flex';
        groupHeader.style.justifyContent = 'space-between';
        groupHeader.style.alignItems = 'center';
        groupHeader.innerHTML = `
            <div style="font-size: 0.875rem; color: #6b7280;">
                <i class="fas fa-comment-dots"></i> ${responses.length} ${responses.length === 1 ? 'response' : 'responses'}
            </div>
            <div style="font-size: 0.875rem; color: #6b7280;">
                <i class="fas fa-sort-amount-down"></i> Newest responses first
            </div>
        `;
        messagesContainer.appendChild(groupHeader);

        // Sort responses to show newest first
        const sortedResponses = [...responses].sort((a, b) => {
            return new Date(b.created_at) - new Date(a.created_at);
        });

        sortedResponses.forEach(response => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'ticket-message';

            // Add styling based on whether it's from staff or customer
            if (response.is_staff) {
                messageDiv.style.borderLeft = '4px solid #10b981'; // Green for staff
                messageDiv.style.backgroundColor = '#f0fdf4'; // Light green background
            } else {
                messageDiv.style.borderLeft = '4px solid #3b82f6'; // Blue for customer
                messageDiv.style.backgroundColor = '#f0f7ff'; // Light blue background
            }

            const senderType = response.is_staff ?
                '<i class="fas fa-headset"></i> Support Staff' :
                '<i class="fas fa-user"></i> Customer';
            const senderName = response.user_name || senderType;

            // Format the date properly
            let responseDate;
            try {
                const date = new Date(response.created_at);

                // Check if it's today
                const today = new Date();
                if (date.toDateString() === today.toDateString()) {
                    // Today - show time only
                    responseDate = `Today at ${date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    })}`;
                } else {
                    // Not today - show full date and time
                    responseDate = date.toLocaleString([], {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            } catch (e) {
                responseDate = response.created_at; // Fallback to raw string
            }

            messageDiv.innerHTML = `
                <div class="ticket-message-header">
                    <div class="ticket-message-sender">${senderName}</div>
                    <div class="ticket-message-time"><i class="far fa-clock"></i> ${responseDate}</div>
                </div>
                <div class="ticket-message-content">
                    ${response.message ? response.message.replace(/\n/g, '<br>') : '<em style="color: #9ca3af;">No content available</em>'}
                </div>
            `;

            messagesContainer.appendChild(messageDiv);
        });
    }

    // Close modal button handlers
    document.getElementById('closeDetailModal').addEventListener('click', function () {
        ticketDetailModal.classList.remove('active');
    });

    document.getElementById('closeReplyModal').addEventListener('click', function () {
        document.getElementById('replyMessage').value = '';
        ticketDetailModal.classList.remove('active');
    });

    // Update Ticket - Using event delegation for dynamically created elements
    document.addEventListener('click', async function (event) {
        // Check if the clicked element or its parent is the update button
        const updateBtn = event.target.id === 'updateTicketBtn' ? event.target :
            event.target.closest('#updateTicketBtn');

        if (updateBtn) {
            console.log('Update ticket button clicked');
            const ticketId = document.getElementById('ticketId').textContent;
            const ticketStatus = document.getElementById('ticketStatus').value;
            const ticketPriority = document.getElementById('ticketPriority').value;

            // Show loading state on button
            const originalBtnText = updateBtn.innerHTML;
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            try {
                console.log('Updating ticket:', ticketId, ticketStatus, ticketPriority);
                // Make an AJAX request to update the ticket
                const formData = new FormData();
                formData.append('ticketId', ticketId);
                formData.append('status', ticketStatus);
                formData.append('priority', ticketPriority);

                // Add CSRF token if available
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    formData.append('csrf_token', csrfToken);
                }

                const response = await fetch(`${window.appConfig.urlRoot}/dashboard/updateSupportTicket`, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    showToast('Success', 'Ticket has been updated successfully');

                    // Update the ticket item in the list if it exists
                    const ticketItem = document.querySelector(`[data-ticket-id="${ticketId}"]`)?.closest('.ticket-item');
                    if (ticketItem) {
                        const statusBadge = ticketItem.querySelector('.ticket-header .badge');
                        const priorityBadge = ticketItem.querySelector('.ticket-footer .badge');

                        // Update badges with animation
                        if (statusBadge) {
                            statusBadge.style.transition = 'all 0.3s ease';
                            statusBadge.style.transform = 'scale(1.1)';
                            statusBadge.className = `badge ${getStatusBadgeClass(ticketStatus)}`;
                            statusBadge.textContent = getStatusText(ticketStatus);

                            setTimeout(() => {
                                statusBadge.style.transform = 'scale(1)';
                            }, 300);
                        }

                        if (priorityBadge) {
                            priorityBadge.style.transition = 'all 0.3s ease';
                            priorityBadge.style.transform = 'scale(1.1)';
                            priorityBadge.className = `badge ${getPriorityBadgeClass(ticketPriority)}`;
                            priorityBadge.textContent = getPriorityText(ticketPriority);

                            setTimeout(() => {
                                priorityBadge.style.transform = 'scale(1)';
                            }, 300);
                        }

                        // Add visual feedback to the ticket item to indicate it was updated
                        ticketItem.style.transition = 'background-color 0.5s ease';
                        ticketItem.style.backgroundColor = '#f0fdf4';
                        setTimeout(() => {
                            ticketItem.style.backgroundColor = 'white';
                        }, 1500);
                    }
                } else {
                    showToast('Error', result.message || 'Failed to update ticket');
                }
            } catch (error) {
                console.error('Error updating ticket:', error);
                showToast('Error', 'Failed to update ticket: ' + (error.message || 'Unknown error'));
            } finally {
                // Restore button state
                updateBtn.disabled = false;
                updateBtn.innerHTML = originalBtnText;
            }
        }
    });

    // Helper functions for status and priority texts and badge classes
    function getStatusText(status) {
        const statusMap = {
            'open': 'Open',
            'in-progress': 'In Progress',
            'resolved': 'Resolved',
            'closed': 'Closed'
        };
        return statusMap[status] || status;
    }

    function getPriorityText(priority) {
        const priorityMap = {
            'low': 'Low',
            'medium': 'Medium',
            'high': 'High',
            'critical': 'Urgent'
        };
        return priorityMap[priority] || priority;
    }

    function getStatusBadgeClass(status) {
        const badgeClasses = {
            'open': 'badge-blue',
            'in-progress': 'badge-green',
            'resolved': 'badge-gray',
            'closed': 'badge-gray'
        };
        return badgeClasses[status] || '';
    }

    function getPriorityBadgeClass(priority) {
        const badgeClasses = {
            'low': 'badge-gray',
            'medium': 'badge-yellow',
            'high': 'badge-orange',
            'critical': 'badge-red'
        };
        return badgeClasses[priority] || '';
    }

    // Form Validation Helper Functions
    function showError(inputElement, message) {
        const formGroup = inputElement.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (errorElement) {
            errorElement.textContent = message;
        } else {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#e53e3e';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            errorDiv.textContent = message;
            formGroup.appendChild(errorDiv);
        }

        inputElement.style.borderColor = '#e53e3e';
    }

    function clearError(inputElement) {
        const formGroup = inputElement.closest('.form-group');
        const errorElement = formGroup.querySelector('.error-message');

        if (errorElement) {
            errorElement.textContent = '';
        }

        inputElement.style.borderColor = '#d1d5db';
    }

    // Reply Form Submission
    const replyForm = document.getElementById('replyForm');
    replyForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const ticketId = document.getElementById('ticketIdField').value;
        const message = document.getElementById('replyMessage').value;

        // Validate input
        if (!message.trim()) {
            showError(document.getElementById('replyMessage'), 'Please enter a reply message');
            return;
        }

        // Clear previous errors
        clearError(document.getElementById('replyMessage'));

        try {
            // Use AJAX to add the response
            const formData = new FormData();
            formData.append('ticketId', ticketId);
            formData.append('response', message);

            const response = await fetch(`${window.appConfig.urlRoot}/dashboard/respondToTicket`, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Failed to add response');
            }

            const result = await response.json();

            if (result.success) {
                // Clear the reply message field
                document.getElementById('replyMessage').value = '';

                // Fetch updated responses
                try {
                    const responsesData = await loadTicketResponses(ticketId);
                    displayTicketResponses(responsesData);
                } catch (error) {
                    console.error('Error loading updated responses:', error);
                }

                showToast('Success', 'Your reply has been sent successfully');
            } else {
                showToast('Error', 'Failed to send reply');
            }
        } catch (error) {
            console.error('Error sending reply:', error);
            showToast('Error', 'Failed to send reply');
        }
    });

    // Filtering Functionality
    document.getElementById('ticketSearch').addEventListener('input', filterTickets);
    document.getElementById('statusFilter').addEventListener('change', filterTickets);
    document.getElementById('priorityFilter').addEventListener('change', filterTickets);

    function filterTickets() {
        const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const priorityFilter = document.getElementById('priorityFilter').value.toLowerCase();

        // Show loading state for better UX when filtering large numbers of tickets
        const ticketsList = document.getElementById('ticketsList');
        let loadingIndicator = document.getElementById('filterLoadingIndicator');

        if (!loadingIndicator && ticketsList) {
            loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'filterLoadingIndicator';
            loadingIndicator.style.position = 'absolute';
            loadingIndicator.style.top = '50%';
            loadingIndicator.style.left = '50%';
            loadingIndicator.style.transform = 'translate(-50%, -50%)';
            loadingIndicator.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
            loadingIndicator.style.padding = '1rem';
            loadingIndicator.style.borderRadius = '0.5rem';
            loadingIndicator.style.display = 'none';
            loadingIndicator.style.alignItems = 'center';
            loadingIndicator.style.zIndex = '10';
            loadingIndicator.innerHTML = `
                <div class="loading-spinner" style="margin-right: 0.75rem;"></div>
                <p style="margin: 0;">Filtering tickets...</p>
            `;
            ticketsList.style.position = 'relative';
            ticketsList.appendChild(loadingIndicator);
        }

        const ticketItems = document.querySelectorAll('#ticketsList .ticket-item');

        // Only show loading for many tickets (more than 20)
        if (ticketItems.length > 20 && loadingIndicator) {
            loadingIndicator.style.display = 'flex';
        }

        // Use setTimeout to prevent UI freezing during filtering
        setTimeout(() => {
            let visibleCount = 0;

            // Track which filter is active for better user feedback
            const isSearchActive = searchTerm !== '';
            const isStatusActive = statusFilter !== '';
            const isPriorityActive = priorityFilter !== '';
            const isFilterActive = isSearchActive || isStatusActive || isPriorityActive;

            // Visual feedback for active filters
            const searchInput = document.getElementById('ticketSearch');
            if (searchInput) {
                if (isSearchActive) {
                    searchInput.style.borderColor = '#3b82f6';
                    searchInput.style.backgroundColor = '#f0f7ff';
                } else {
                    searchInput.style.borderColor = '#d1d5db';
                    searchInput.style.backgroundColor = '#ffffff';
                }
            }

            // Apply similar visual feedback to other filter elements
            updateFilterVisuals('statusFilter', isStatusActive);
            updateFilterVisuals('priorityFilter', isPriorityActive);

            ticketItems.forEach(ticketItem => {
                // Get the ticket content for searching
                const ticketTitle = ticketItem.querySelector('.ticket-title').textContent.toLowerCase();
                const ticketContent = ticketItem.querySelector('.ticket-content').textContent.toLowerCase();
                const ticketMeta = ticketItem.querySelector('.ticket-meta').textContent.toLowerCase();
                const ticketFooter = ticketItem.querySelector('.ticket-footer').textContent.toLowerCase();

                // Comprehensive search index
                const searchIndex = `${ticketTitle} ${ticketContent} ${ticketMeta} ${ticketFooter}`;

                // Get status and priority badges
                const statusBadge = ticketItem.querySelector('.ticket-header .badge');
                const priorityBadge = ticketItem.querySelector('.ticket-footer .badge');

                const status = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';
                const priority = priorityBadge ? priorityBadge.textContent.trim().toLowerCase() : '';

                // Enhanced search matching
                const matchesSearch = !isSearchActive ||
                    searchIndex.includes(searchTerm) ||
                    // Support term-based searching (match individual words)
                    searchTerm.split(' ').every(term => term && searchIndex.includes(term));

                const matchesStatus = !isStatusActive || status.includes(statusFilter);
                const matchesPriority = !isPriorityActive || priority.includes(priorityFilter);

                // Show or hide based on matches with smooth animation
                if (matchesSearch && matchesStatus && matchesPriority) {
                    // Fade in effect for better UX
                    ticketItem.style.transition = 'opacity 0.3s ease';
                    ticketItem.style.opacity = '0';
                    ticketItem.style.display = '';
                    // Force reflow to make transition work
                    void ticketItem.offsetWidth;
                    ticketItem.style.opacity = '1';
                    visibleCount++;
                } else {
                    // Fade out effect
                    ticketItem.style.transition = 'opacity 0.3s ease';
                    ticketItem.style.opacity = '0';
                    setTimeout(() => {
                        if (ticketItem.style.opacity === '0') {
                            ticketItem.style.display = 'none';
                        }
                    }, 300);
                }
            });

            // Update the "no results" message
            let noResultsMsg = document.getElementById('noFilterResults');
            if (visibleCount === 0 && ticketItems.length > 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noFilterResults';
                    noResultsMsg.className = 'alert alert-info';
                    noResultsMsg.style.padding = '1rem';
                    noResultsMsg.style.border = '1px solid #d1d5db';
                    noResultsMsg.style.borderRadius = '0.5rem';
                    noResultsMsg.style.backgroundColor = '#f3f4f6';
                    noResultsMsg.style.display = 'flex';
                    noResultsMsg.style.alignItems = 'center';
                    noResultsMsg.style.justifyContent = 'space-between';

                    let filterDescription = '';
                    if (isSearchActive) filterDescription += `search for "${searchTerm}"`;
                    if (isStatusActive) {
                        if (filterDescription) filterDescription += ', ';
                        filterDescription += `status "${statusFilter}"`;
                    }
                    if (isPriorityActive) {
                        if (filterDescription) filterDescription += ', ';
                        filterDescription += `priority "${priorityFilter}"`;
                    }

                    noResultsMsg.innerHTML = `
                        <div>
                            <p style="margin-bottom: 0.5rem; font-weight: 500;">No tickets match your criteria.</p>
                            <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Try adjusting your filters or clearing them to see all tickets.</p>
                        </div>
                        <button class="btn btn-sm btn-outline clear-all-filters" style="white-space: nowrap;">
                            <i class="fas fa-times"></i> Clear Filters
                        </button>
                    `;

                    const ticketsList = document.getElementById('ticketsList');
                    ticketsList.appendChild(noResultsMsg);

                    // Add event listener to clear filters button
                    noResultsMsg.querySelector('.clear-all-filters').addEventListener('click', clearAllFilters);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }

            // Update pagination info if available
            const itemStart = document.getElementById('itemStart');
            const itemEnd = document.getElementById('itemEnd');
            const itemTotal = document.getElementById('itemTotal');

            if (itemStart && itemEnd && itemTotal) {
                if (visibleCount === 0) {
                    itemStart.textContent = '0';
                    itemEnd.textContent = '0';
                } else {
                    itemStart.textContent = '1';
                    itemEnd.textContent = visibleCount.toString();
                }
                itemTotal.textContent = visibleCount.toString();
            }

            // Show feedback about filter results
            if (isFilterActive) {
                showToast('Filter Applied', `Showing ${visibleCount} matching tickets`);
            }

            // Hide loading indicator
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
        }, 10); // Short delay to allow UI to update
    }

    // Helper function to update filter visual feedback
    function updateFilterVisuals(elementId, isActive) {
        const element = document.getElementById(elementId);
        if (element) {
            if (isActive) {
                element.style.borderColor = '#3b82f6';
                element.style.backgroundColor = '#f0f7ff';
            } else {
                element.style.borderColor = '#d1d5db';
                element.style.backgroundColor = '#ffffff';
            }
        }
    }

    // Function to clear all filters
    function clearAllFilters() {
        document.getElementById('ticketSearch').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('priorityFilter').value = '';

        // Reset visual styles
        updateFilterVisuals('ticketSearch', false);
        updateFilterVisuals('statusFilter', false);
        updateFilterVisuals('priorityFilter', false);

        // Apply filter to show all tickets
        filterTickets();

        // Show feedback
        showToast('Filters Cleared', 'Showing all tickets');
    }

    // Enhanced toast notification function
    function showToast(title, message, type = 'info') {
        // Remove any existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => {
            toast.remove();
        });

        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.style.position = 'fixed';
        toast.style.bottom = '1.5rem';
        toast.style.right = '1.5rem';
        toast.style.backgroundColor = 'white';
        toast.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
        toast.style.padding = '1rem 1.25rem';
        toast.style.borderRadius = '0.5rem';
        toast.style.maxWidth = '350px';
        toast.style.width = '100%';
        toast.style.zIndex = '9999';
        toast.style.animation = 'slideIn 0.3s ease-out forwards';
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';

        // Style based on toast type
        let borderColor, icon;
        switch (type.toLowerCase()) {
            case 'success':
                borderColor = '#10b981'; // Green
                icon = '<i class="fas fa-check-circle" style="color: #10b981;"></i>';
                break;
            case 'error':
                borderColor = '#ef4444'; // Red
                icon = '<i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>';
                break;
            case 'warning':
                borderColor = '#f59e0b'; // Amber
                icon = '<i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>';
                break;
            default: // info
                borderColor = '#3b82f6'; // Blue
                icon = '<i class="fas fa-info-circle" style="color: #3b82f6;"></i>';
                break;
        }

        toast.style.borderLeft = `4px solid ${borderColor}`;

        toast.innerHTML = `
            <div style="display: flex; align-items: flex-start;">
                <div style="margin-right: 0.75rem; font-size: 1.25rem;">${icon}</div>
                <div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem; color: #1f2937;">${title}</div>
                    <div style="color: #4b5563; font-size: 0.875rem;">${message}</div>
                </div>
            </div>
            <button class="close-toast" style="position: absolute; top: 0.5rem; right: 0.5rem; background: transparent; border: none; cursor: pointer; font-size: 1rem; color: #9ca3af;">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(toast);

        // Add animation keyframes
        if (!document.getElementById('toast-animations')) {
            const style = document.createElement('style');
            style.id = 'toast-animations';
            style.innerHTML = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(100%);
                }
            }
            `;
            document.head.appendChild(style);
        }

        // Add close button functionality
        const closeBtn = toast.querySelector('.close-toast');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
        }

        // Auto-remove toast after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease-in forwards';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // Update ticket status banner
    function updateTicketStatusBanner(status) {
        const statusBanner = document.getElementById('ticketStatusBanner');
        const statusText = document.getElementById('ticketStatusText');

        if (!statusBanner || !statusText) return;

        // Remove all status classes
        statusBanner.classList.remove('status-open', 'status-in-progress', 'status-resolved', 'status-closed');

        let icon, text, statusClass;
        switch (status) {
            case 'open':
                icon = '<i class="fas fa-ticket-alt"></i>';
                text = 'Open';
                statusClass = 'status-open';
                break;
            case 'in-progress':
                icon = '<i class="fas fa-spinner fa-spin"></i>';
                text = 'In Progress';
                statusClass = 'status-in-progress';
                break;
            case 'resolved':
                icon = '<i class="fas fa-check-circle"></i>';
                text = 'Resolved';
                statusClass = 'status-resolved';
                break;
            case 'closed':
                icon = '<i class="fas fa-times-circle"></i>';
                text = 'Closed';
                statusClass = 'status-closed';
                break;
            default:
                icon = '<i class="fas fa-question-circle"></i>';
                text = status.charAt(0).toUpperCase() + status.slice(1);
                statusClass = 'status-open';
        }

        statusBanner.classList.add(statusClass);
        statusText.innerHTML = `${icon} Status: ${text}`;
    }

    // Format ticket messages
    function formatTicketMessages(messages, ticketId) {
        if (!messages || messages.length === 0) {
            return `
                <div class="alert alert-info" style="text-align: center; padding: 1.5rem; background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 0.5rem;">
                    <i class="fas fa-info-circle" style="font-size: 1.5rem; color: #6b7280; margin-bottom: 0.5rem;"></i>
                    <p style="color: #4b5563;">No responses yet. You can be the first to respond to this ticket.</p>
                </div>
            `;
        }

        // Sort messages by date - newest first
        const sortedMessages = [...messages].sort((a, b) => {
            return new Date(b.created_at) - new Date(a.created_at);
        });

        let html = `
            <div class="conversation-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div style="font-size: 0.875rem; color: #6b7280;">
                    <i class="fas fa-comment-dots"></i> ${messages.length} ${messages.length === 1 ? 'message' : 'messages'}
                </div>
                <div class="conversation-controls">
                    <button class="btn btn-sm btn-outline sort-messages-btn">
                        <i class="fas fa-sort-amount-down"></i> Newest First
                    </button>
                </div>
            </div>
        `;

        html += '<div class="conversation-messages">';

        sortedMessages.forEach(message => {
            const isAdmin = message.is_staff;
            const messageClass = isAdmin ? 'message-admin' : 'message-user';
            const avatarIcon = isAdmin ?
                '<i class="fas fa-headset" style="color: white; background-color: #10b981; padding: 0.5rem; border-radius: 9999px;"></i>' :
                '<i class="fas fa-user" style="color: white; background-color: #3b82f6; padding: 0.5rem; border-radius: 9999px;"></i>';

            // Format date
            let formattedDate;
            try {
                const messageDate = new Date(message.created_at);
                const now = new Date();
                const isToday = messageDate.toDateString() === now.toDateString();

                if (isToday) {
                    formattedDate = `Today at ${messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                } else {
                    formattedDate = messageDate.toLocaleDateString([], {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    }) + ' at ' + messageDate.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            } catch (e) {
                formattedDate = message.created_at || 'Unknown date';
            }

            // Parse message content - detect and format attachments
            let messageContent = message.message || '';
            const hasAttachments = messageContent.includes('[ATTACHMENT:') || messageContent.includes('[FILE:');

            // Extract attachment info using regex
            let attachmentsHtml = '';
            if (hasAttachments) {
                const attachmentRegex = /\[(ATTACHMENT|FILE):([^\]]+)\]/g;
                let matches;
                const attachments = [];

                // Extract all attachments
                while ((matches = attachmentRegex.exec(messageContent)) !== null) {
                    const fullMatch = matches[0];
                    const attachmentType = matches[1];
                    const attachmentInfo = matches[2];

                    // Parse attachment details - format could be: name.ext|size|url
                    const parts = attachmentInfo.split('|');
                    const fileName = parts[0] || 'attachment';
                    const fileSize = parts[1] || '';
                    const fileUrl = parts[2] || '#';

                    attachments.push({
                        fullMatch,
                        fileName,
                        fileSize,
                        fileUrl
                    });
                }

                // Remove attachment tags from the message
                attachments.forEach(attachment => {
                    messageContent = messageContent.replace(attachment.fullMatch, '');
                });

                // Build attachments HTML
                if (attachments.length > 0) {
                    attachmentsHtml = '<div class="message-attachments">';
                    attachments.forEach(attachment => {
                        attachmentsHtml += `
                            <div class="attachment-item">
                                <i class="fas fa-file attachment-icon"></i>
                                <div class="attachment-name">${attachment.fileName}</div>
                                <div class="attachment-size">${formatFileSize(attachment.fileSize)}</div>
                                <a href="${attachment.fileUrl}" class="attachment-action" download>
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        `;
                    });
                    attachmentsHtml += '</div>';
                }
            }

            // Format message content - convert newlines to <br>
            messageContent = messageContent.trim();
            if (messageContent) {
                messageContent = messageContent.replace(/\n/g, '<br>');
            } else {
                messageContent = '<em style="color: #9ca3af;">No message content</em>';
            }

            html += `
                <div class="conversation-message ${messageClass}" style="margin-bottom: 1.5rem;">
                    <div class="message-header">
                        <div class="message-sender">
                            ${avatarIcon}
                            <span>${message.user_name || (isAdmin ? 'Support Staff' : 'Customer')}</span>
                        </div>
                        <div class="message-time">
                            <i class="far fa-clock"></i> ${formattedDate}
                        </div>
                    </div>
                    <div class="message-content">
                        ${messageContent}
                    </div>
                    ${attachmentsHtml}
                </div>
            `;
        });

        html += '</div>';

        return html;
    }

    // Format file size helper
    function formatFileSize(sizeStr) {
        if (!sizeStr) return '';

        const size = parseInt(sizeStr, 10);
        if (isNaN(size)) return sizeStr;

        if (size < 1024) return size + ' B';
        if (size < 1024 * 1024) return (size / 1024).toFixed(1) + ' KB';
        if (size < 1024 * 1024 * 1024) return (size / (1024 * 1024)).toFixed(1) + ' MB';
        return (size / (1024 * 1024 * 1024)).toFixed(1) + ' GB';
    }

    // Setup event listeners for View Details buttons
    const viewTicketButtons = document.querySelectorAll('.view-ticket');
    console.log(`Found ${viewTicketButtons.length} view ticket buttons`, viewTicketButtons);

    viewTicketButtons.forEach(button => {
        button.addEventListener('click', handleViewTicketClick);
    });

    // Also add event delegation as a backup for dynamically added buttons
    document.getElementById('ticketsList')?.addEventListener('click', function (e) {
        // Find the closest view-ticket button if the click was on the button or a child element
        const viewButton = e.target.closest('.view-ticket');
        if (!viewButton) return; // Not clicking on a view button

        handleViewTicketClick.call(viewButton, e);
    });

    // Check if admin features should be initialized
    const adminDashboard = document.querySelector('.admin-dashboard-overview');
    if (adminDashboard) {
        initializeAdminFeatures();
    }
});

// Admin-specific features initialization
function initializeAdminFeatures() {
    console.log('Initializing admin features');

    // Initialize tab navigation
    initTabNavigation();

    // Initialize admin charts if they exist
    initializeAdminCharts();
}

// Tab navigation for admin dashboard
function initTabNavigation() {
    const adminTabs = document.querySelectorAll('.admin-tab');
    adminTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active class from all tabs
            adminTabs.forEach(t => t.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');

            // Filter tickets based on tab
            filterTicketsByTab(this.getAttribute('data-tab'));
        });
    });
}

// Filter tickets based on admin tab selection
function filterTicketsByTab(tabType) {
    console.log('Filtering tickets by tab:', tabType);
    const ticketItems = document.querySelectorAll('.ticket-item');
    let visibleCount = 0;

    ticketItems.forEach(item => {
        // Get the status, priority and created date
        const statusBadge = item.querySelector('.ticket-header .badge');
        const priorityBadge = item.querySelector('.ticket-footer .badge');
        const metaText = item.querySelector('.ticket-meta')?.textContent || '';
        const createdDateMatch = metaText.match(/Created: ([^,]+)/);
        const createdDate = createdDateMatch ? createdDateMatch[1]?.trim() : '';

        const status = statusBadge ? statusBadge.textContent.trim().toLowerCase() : '';
        const priority = priorityBadge ? priorityBadge.textContent.trim().toLowerCase() : '';

        // Filter based on tab type
        let shouldShow = true;

        switch (tabType) {
            case 'unassigned':
                // Check if ticket mentions assignment
                shouldShow = !item.textContent.includes('Assigned to:');
                break;
            case 'recent':
                // Show tickets created within the last 7 days
                if (createdDate) {
                    try {
                        const ticketDate = new Date(createdDate);
                        const now = new Date();
                        // Ensure valid date before calculating the difference
                        if (!isNaN(ticketDate.getTime())) {
                            const daysDiff = Math.floor((now - ticketDate) / (1000 * 60 * 60 * 24));
                            shouldShow = daysDiff <= 7;
                        } else {
                            console.warn('Invalid date format:', createdDate);
                            shouldShow = false;
                        }
                    } catch (error) {
                        console.error('Error parsing date:', error);
                        shouldShow = false;
                    }
                } else {
                    shouldShow = false;
                }
                break;
            case 'critical':
                // Show only critical/urgent tickets
                shouldShow = priority.toLowerCase() === 'urgent' || priority.toLowerCase() === 'critical';
                break;
            case 'all':
            default:
                // Show all tickets
                shouldShow = true;
                break;
        }

        // Show or hide based on filter
        if (shouldShow) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Update the display message if no tickets match the filter
    let noResultsMsg = document.getElementById('noTabResults');
    if (visibleCount === 0 && ticketItems.length > 0) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'noTabResults';
            noResultsMsg.className = 'alert alert-info';
            noResultsMsg.style.padding = '1rem';
            noResultsMsg.style.border = '1px solid #d1d5db';
            noResultsMsg.style.borderRadius = '0.5rem';
            noResultsMsg.style.backgroundColor = '#f3f4f6';
            noResultsMsg.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <p style="margin: 0;">No tickets match the "${tabType}" filter.</p>
                    <button class="btn btn-sm btn-outline reset-filter">
                        <i class="fas fa-sync-alt"></i> View All
                    </button>
                </div>
            `;

            const ticketsList = document.getElementById('ticketsList');
            ticketsList.appendChild(noResultsMsg);

            // Add event listener to reset button
            const resetBtn = noResultsMsg.querySelector('.reset-filter');
            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    const allTab = document.querySelector('.admin-tab[data-tab="all"]');
                    if (allTab) {
                        allTab.click();
                    }
                });
            }
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }

    // Show feedback toast
    const tabNames = {
        'all': 'All Tickets',
        'unassigned': 'Unassigned Tickets',
        'recent': 'Recent Tickets',
        'critical': 'Critical Tickets'
    };

    const tabName = tabNames[tabType] || tabType;
    showToast('Filter Applied', `Showing ${visibleCount} ${tabName.toLowerCase()}`);
}

// Initialize admin analytics charts
function initializeAdminCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded, skipping chart initialization');
        return;
    }

    // Check if we have chart data
    if (!window.adminChartData) {
        console.warn('No admin chart data available');
        return;
    }

    // Function to destroy existing chart if it exists
    function destroyChartIfExists(chartId) {
        const chartInstance = Chart.getChart(chartId);
        if (chartInstance) {
            console.log(`Destroying existing chart with ID: ${chartId}`);
            chartInstance.destroy();
        }
    }

    // Check for chart containers
    const statusChartEl = document.getElementById('statusChart');
    const priorityChartEl = document.getElementById('priorityChart');
    const responseTimeChartEl = document.getElementById('responseTimeChart');
    const ticketsTimeChartEl = document.getElementById('ticketsTimeChart');

    // Initialize Status Distribution Chart
    if (statusChartEl) {
        // Destroy existing chart if it exists
        destroyChartIfExists('statusChart');

        const statusData = window.adminChartData.ticketStatusCounts;

        new Chart(statusChartEl, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
                datasets: [{
                    data: [statusData.open, statusData.inProgress, statusData.resolved, statusData.closed],
                    backgroundColor: ['#3b82f6', '#10b981', '#6b7280', '#1f2937'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                }
            }
        });
    }

    // Initialize Priority Distribution Chart
    if (priorityChartEl) {
        // Destroy existing chart if it exists
        destroyChartIfExists('priorityChart');

        const priorityData = window.adminChartData.ticketPriorityCounts;

        new Chart(priorityChartEl, {
            type: 'pie',
            data: {
                labels: ['Low', 'Medium', 'High', 'Critical'],
                datasets: [{
                    data: [priorityData.low, priorityData.medium, priorityData.high, priorityData.critical],
                    backgroundColor: ['#6b7280', '#f59e0b', '#f97316', '#ef4444'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                }
            }
        });
    }

    // Initialize Response Time Chart
    if (responseTimeChartEl) {
        // Destroy existing chart if it exists
        destroyChartIfExists('responseTimeChart');

        const responseTimeData = window.adminChartData.responseTimeData;

        new Chart(responseTimeChartEl, {
            type: 'bar',
            data: {
                labels: responseTimeData.labels,
                datasets: [{
                    label: 'Avg. Response Time (hours)',
                    data: responseTimeData.data,
                    backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#6b7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Initialize Tickets Over Time Chart
    if (ticketsTimeChartEl) {
        // Destroy existing chart if it exists
        destroyChartIfExists('ticketsTimeChart');

        const ticketsTimeData = window.adminChartData.ticketsOverTimeData;

        new Chart(ticketsTimeChartEl, {
            type: 'line',
            data: {
                labels: ticketsTimeData.labels,
                datasets: [{
                    label: 'Tickets',
                    data: ticketsTimeData.data,
                    fill: false,
                    borderColor: '#3b82f6',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}
