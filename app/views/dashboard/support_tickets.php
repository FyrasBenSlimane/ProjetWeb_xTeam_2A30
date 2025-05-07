<?php
// Use buffers to store the dashboard content
ob_start();
?>

<div class="support-tickets-page">
    <style>
        .support-tickets-page {
            padding: 1.5rem 0;
        }
        .ticket-filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .search-input {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
        .search-input input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            padding-left: 2.25rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .search-input svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            height: 1rem;
            width: 1rem;
            color: #6b7280;
        }
        .filter-select {
            min-width: 120px;
            position: relative;
        }
        .filter-select select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 10.5L3.5 6h9z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        .ticket-reply-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: 1rem;
        }
        .ticket-reply-modal.active {
            display: flex;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }
        .modal-body {
            padding: 1rem;
        }
        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .ticket-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .ticket-header {
            padding: 1rem;
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .ticket-meta {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .ticket-content {
            padding: 1rem;
        }
        .ticket-footer {
            padding: 1rem;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-actions {
            display: flex;
            gap: 0.5rem;
        }
        .ticket-details {
            margin-bottom: 1rem;
        }
        .ticket-message {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .ticket-message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .ticket-message-sender {
            font-weight: 600;
        }
        .ticket-message-time {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .ticket-message-content {
            margin-top: 0.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }
        .pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .pagination-buttons {
            display: flex;
            gap: 0.25rem;
        }
        .pagination-buttons button {
            padding: 0.375rem 0.75rem;
            border: 1px solid #d1d5db;
            background-color: white;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
        }
        .pagination-buttons button:hover {
            background-color: #f3f4f6;
        }
        .pagination-buttons button.active {
            background-color: rgb(5, 11, 31);
            border-color: rgb(5, 11, 31);
            color: white;
        }
        .pagination-buttons button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <!-- Ticket Filter and Search Bar -->
    <div class="ticket-filter-bar">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" placeholder="Search tickets..." id="ticketSearch">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Status</option>
                <option value="Open">Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Resolved">Resolved</option>
                <option value="Closed">Closed</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="priorityFilter">
                <option value="">All Priorities</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
            </select>
        </div>
    </div>

    <!-- Tickets List -->
    <div id="ticketsList">
        <!-- Ticket Item 1 -->
        <div class="ticket-item">
            <div class="ticket-header">
                <div class="ticket-title">
                    <span class="badge badge-red">Urgent</span>
                    <span>Website Down After Latest Update</span>
                </div>
                <div class="ticket-meta">
                    <span class="badge badge-blue">Open</span>
                    <span>ID: #1001</span>
                </div>
            </div>
            <div class="ticket-content">
                <p>After the latest update to the website, the homepage is not loading correctly. Users are experiencing a blank page when accessing the site.</p>
            </div>
            <div class="ticket-footer">
                <div class="ticket-meta">
                    <span>Submitted by: John Doe</span>
                    <span> | </span>
                    <span>Created: 2023-04-25, 10:30 AM</span>
                </div>
                <div class="ticket-actions">
                    <button class="btn btn-primary view-ticket" data-id="1001">View Details</button>
                </div>
            </div>
        </div>

        <!-- Ticket Item 2 -->
        <div class="ticket-item">
            <div class="ticket-header">
                <div class="ticket-title">
                    <span class="badge badge-yellow">Medium</span>
                    <span>Login Authentication Issues</span>
                </div>
                <div class="ticket-meta">
                    <span class="badge badge-green">In Progress</span>
                    <span>ID: #1002</span>
                </div>
            </div>
            <div class="ticket-content">
                <p>Several users are reporting issues with the login system. They are getting "Invalid Credentials" errors even when using correct login information.</p>
            </div>
            <div class="ticket-footer">
                <div class="ticket-meta">
                    <span>Submitted by: Jane Smith</span>
                    <span> | </span>
                    <span>Created: 2023-04-24, 02:15 PM</span>
                </div>
                <div class="ticket-actions">
                    <button class="btn btn-primary view-ticket" data-id="1002">View Details</button>
                </div>
            </div>
        </div>

        <!-- Ticket Item 3 -->
        <div class="ticket-item">
            <div class="ticket-header">
                <div class="ticket-title">
                    <span class="badge badge-gray">Low</span>
                    <span>Typo in About Page</span>
                </div>
                <div class="ticket-meta">
                    <span class="badge badge-gray">Resolved</span>
                    <span>ID: #1003</span>
                </div>
            </div>
            <div class="ticket-content">
                <p>There is a typo in the company description on the About page. The word "experience" is misspelled as "experence".</p>
            </div>
            <div class="ticket-footer">
                <div class="ticket-meta">
                    <span>Submitted by: Robert Johnson</span>
                    <span> | </span>
                    <span>Created: 2023-04-22, 11:45 AM</span>
                </div>
                <div class="ticket-actions">
                    <button class="btn btn-primary view-ticket" data-id="1003">View Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">
            Showing <span id="itemStart">1</span> to <span id="itemEnd">3</span> of <span id="itemTotal">15</span> tickets
        </div>
        <div class="pagination-buttons">
            <button id="prevPage" disabled>&laquo; Previous</button>
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>4</button>
            <button>5</button>
            <button id="nextPage">Next &raquo;</button>
        </div>
    </div>

    <!-- Ticket Detail Modal -->
    <div class="ticket-reply-modal" id="ticketDetailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Ticket #<span id="ticketId">1001</span></h3>
                <button type="button" class="modal-close" id="closeDetailModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="ticket-details">
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <div id="ticketSubject" class="form-input">Website Down After Latest Update</div>
                    </div>
                    <div class="flex" style="gap: 1rem">
                        <div class="form-group" style="flex: 1">
                            <label class="form-label">Status</label>
                            <select id="ticketStatus" class="form-select">
                                <option value="Open">Open</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1">
                            <label class="form-label">Priority</label>
                            <select id="ticketPriority" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assigned To</label>
                        <select id="ticketAssigned" class="form-select">
                            <option value="">Unassigned</option>
                            <option value="1">John Doe</option>
                            <option value="2">Jane Smith</option>
                            <option value="3">Robert Johnson</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="updateTicketBtn">Update Ticket</button>
                </div>

                <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #e5e7eb;">

                <h4 style="margin-bottom: 1rem;">Conversation</h4>
                
                <div id="ticketMessages">
                    <!-- Message 1 -->
                    <div class="ticket-message">
                        <div class="ticket-message-header">
                            <div class="ticket-message-sender">John Doe (Customer)</div>
                            <div class="ticket-message-time">2023-04-25, 10:30 AM</div>
                        </div>
                        <div class="ticket-message-content">
                            <p>After the latest update to the website, the homepage is not loading correctly. Users are experiencing a blank page when accessing the site.</p>
                            <p>I've tried clearing the cache and using different browsers, but the issue persists. Can you please help resolve this as soon as possible?</p>
                        </div>
                    </div>
                    
                    <!-- Message 2 -->
                    <div class="ticket-message">
                        <div class="ticket-message-header">
                            <div class="ticket-message-sender">Admin Support</div>
                            <div class="ticket-message-time">2023-04-25, 11:15 AM</div>
                        </div>
                        <div class="ticket-message-content">
                            <p>Hello John,</p>
                            <p>Thank you for reporting this issue. We're investigating the problem and will update you as soon as we have more information. In the meantime, we've set up a temporary redirect to ensure users can still access the website.</p>
                            <p>Best regards,<br>Support Team</p>
                        </div>
                    </div>
                </div>

                <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #e5e7eb;">

                <h4 style="margin-bottom: 1rem;">Reply</h4>
                <form id="replyForm">
                    <div class="form-group">
                        <textarea id="replyMessage" class="form-textarea" placeholder="Type your reply here..."></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ticket Detail Modal
            const ticketDetailModal = document.getElementById('ticketDetailModal');
            
            // View Ticket Detail
            document.querySelectorAll('.view-ticket').forEach(button => {
                button.addEventListener('click', function() {
                    const ticketId = this.getAttribute('data-id');
                    document.getElementById('ticketId').textContent = ticketId;
                    
                    // Here we would typically fetch ticket data by ID
                    // For this example, we'll just show the modal with some predefined data
                    if (ticketId === '1001') {
                        document.getElementById('ticketSubject').textContent = 'Website Down After Latest Update';
                        document.getElementById('ticketStatus').value = 'Open';
                        document.getElementById('ticketPriority').value = 'Urgent';
                        document.getElementById('ticketAssigned').value = '';
                    } else if (ticketId === '1002') {
                        document.getElementById('ticketSubject').textContent = 'Login Authentication Issues';
                        document.getElementById('ticketStatus').value = 'In Progress';
                        document.getElementById('ticketPriority').value = 'Medium';
                        document.getElementById('ticketAssigned').value = '2';
                    } else if (ticketId === '1003') {
                        document.getElementById('ticketSubject').textContent = 'Typo in About Page';
                        document.getElementById('ticketStatus').value = 'Resolved';
                        document.getElementById('ticketPriority').value = 'Low';
                        document.getElementById('ticketAssigned').value = '3';
                    }
                    
                    ticketDetailModal.classList.add('active');
                });
            });
            
            document.getElementById('closeDetailModal').addEventListener('click', function() {
                ticketDetailModal.classList.remove('active');
            });
            
            // Update Ticket
            document.getElementById('updateTicketBtn').addEventListener('click', function() {
                const ticketId = document.getElementById('ticketId').textContent;
                const ticketStatus = document.getElementById('ticketStatus').value;
                const ticketPriority = document.getElementById('ticketPriority').value;
                const ticketAssigned = document.getElementById('ticketAssigned').value;
                
                // Here we would typically update the ticket data
                console.log('Updating ticket:', { ticketId, ticketStatus, ticketPriority, ticketAssigned });
                
                showToast('Ticket Updated', `Ticket #${ticketId} has been updated`);
            });
            
            // Form Validation for Reply Form
            const replyForm = document.getElementById('replyForm');
            replyForm.addEventListener('submit', function(event) {
                let isValid = true;
                const replyMessage = document.getElementById('replyMessage');

                // Clear previous errors
                clearError(replyMessage);

                if (replyMessage.value.trim() === '') {
                    showError(replyMessage, 'Reply message cannot be empty.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                } else {
                    // If valid, proceed with the existing submission logic
                    // The original event listener handles the submission
                    // No need to call preventDefault() here if the original handler does it
                    // Or remove the preventDefault() from the original handler if submitting normally
                }
            });

            function showError(inputElement, message) {
                const formGroup = inputElement.closest('.form-group');
                let errorElement = formGroup.querySelector('.error-message');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'error-message';
                    errorElement.style.color = 'red';
                    errorElement.style.fontSize = '0.8em';
                    errorElement.style.marginTop = '0.25rem';
                    // Insert error message after the textarea
                    inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
                }
                errorElement.textContent = message;
                inputElement.style.borderColor = 'red';
            }

            function clearError(inputElement) {
                const formGroup = inputElement.closest('.form-group');
                const errorElement = formGroup.querySelector('.error-message');
                if (errorElement) {
                    errorElement.remove();
                }
                inputElement.style.borderColor = ''; // Reset border color
            }

            // Reply Form Submission
            document.getElementById('replyForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const ticketId = document.getElementById('ticketId').textContent;
                const replyMessage = document.getElementById('replyMessage').value;
                
                if (replyMessage.trim() === '') {
                    alert('Please enter a reply message');
                    return;
                }
                
                // Here we would typically send the reply
                console.log('Sending reply:', { ticketId, replyMessage });
                
                // Add the reply to the conversation (in a real app, this would be done after successful API call)
                const messageDiv = document.createElement('div');
                messageDiv.className = 'ticket-message';
                messageDiv.innerHTML = `
                    <div class="ticket-message-header">
                        <div class="ticket-message-sender">Admin Support</div>
                        <div class="ticket-message-time">${new Date().toLocaleString()}</div>
                    </div>
                    <div class="ticket-message-content">
                        <p>${replyMessage.replace(/\n/g, '<br>')}</p>
                    </div>
                `;
                document.getElementById('ticketMessages').appendChild(messageDiv);
                
                // Clear the reply form
                document.getElementById('replyMessage').value = '';
                
                showToast('Reply Sent', 'Your reply has been sent successfully');
            });
            
            // Filtering Functionality
            document.getElementById('ticketSearch').addEventListener('input', filterTickets);
            document.getElementById('statusFilter').addEventListener('change', filterTickets);
            document.getElementById('priorityFilter').addEventListener('change', filterTickets);
            
            function filterTickets() {
                const searchTerm = document.getElementById('ticketSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const priorityFilter = document.getElementById('priorityFilter').value;
                
                const tickets = document.querySelectorAll('.ticket-item');
                
                tickets.forEach(ticket => {
                    const title = ticket.querySelector('.ticket-title').textContent.toLowerCase();
                    const content = ticket.querySelector('.ticket-content').textContent.toLowerCase();
                    const status = ticket.querySelector('.badge-blue, .badge-green, .badge-gray').textContent.trim();
                    const priority = ticket.querySelector('.badge-red, .badge-yellow, .badge-blue, .badge-gray:not(.badge-blue):not(.badge-green)').textContent.trim();
                    
                    const titleMatch = title.includes(searchTerm) || content.includes(searchTerm);
                    const statusMatch = statusFilter === '' || status.includes(statusFilter);
                    const priorityMatch = priorityFilter === '' || priority.includes(priorityFilter);
                    
                    if (titleMatch && statusMatch && priorityMatch) {
                        ticket.style.display = '';
                    } else {
                        ticket.style.display = 'none';
                    }
                });
            }
            
            // Pagination Functionality
            // In a real application, this would be server-side pagination
            document.getElementById('nextPage').addEventListener('click', function() {
                const currentActiveButton = document.querySelector('.pagination-buttons button.active');
                const nextButton = currentActiveButton.nextElementSibling;
                
                if (nextButton && nextButton.id !== 'nextPage') {
                    currentActiveButton.classList.remove('active');
                    nextButton.classList.add('active');
                    document.getElementById('prevPage').disabled = false;
                    
                    // Update pagination info
                    const page = parseInt(nextButton.textContent);
                    document.getElementById('itemStart').textContent = (page - 1) * 3 + 1;
                    document.getElementById('itemEnd').textContent = Math.min(page * 3, 15);
                    
                    if (page === 5) {
                        this.disabled = true;
                    }
                }
            });
            
            document.getElementById('prevPage').addEventListener('click', function() {
                const currentActiveButton = document.querySelector('.pagination-buttons button.active');
                const prevButton = currentActiveButton.previousElementSibling;
                
                if (prevButton && prevButton.id !== 'prevPage') {
                    currentActiveButton.classList.remove('active');
                    prevButton.classList.add('active');
                    document.getElementById('nextPage').disabled = false;
                    
                    // Update pagination info
                    const page = parseInt(prevButton.textContent);
                    document.getElementById('itemStart').textContent = (page - 1) * 3 + 1;
                    document.getElementById('itemEnd').textContent = Math.min(page * 3, 15);
                    
                    if (page === 1) {
                        this.disabled = true;
                    }
                }
            });
            
            document.querySelectorAll('.pagination-buttons button:not(#prevPage):not(#nextPage)').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.classList.contains('active')) {
                        document.querySelector('.pagination-buttons button.active').classList.remove('active');
                        this.classList.add('active');
                        
                        // Update pagination info
                        const page = parseInt(this.textContent);
                        document.getElementById('itemStart').textContent = (page - 1) * 3 + 1;
                        document.getElementById('itemEnd').textContent = Math.min(page * 3, 15);
                        
                        // Update buttons state
                        document.getElementById('prevPage').disabled = page === 1;
                        document.getElementById('nextPage').disabled = page === 5;
                    }
                });
            });
        });

        // Toast notification function (would be implemented globally in a real app)
        function showToast(title, message) {
            console.log('Toast:', title, message);
            // Implementation would depend on how you want to show notifications
        }
    </script>
</div>

<?php
// Store the dashboard content in the $content variable
$content = ob_get_clean();

// Include the dashboard layout
require_once 'dashboard_layout.php';
?>