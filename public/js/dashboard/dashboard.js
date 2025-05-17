/**
 * Dashboard JavaScript functionality
 * Handles dashboard UI interactions and functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar functionality
    initSidebar();
    
    // Initialize dropdown functionality
    initDropdowns();
    
    // Initialize other interactive elements
    initToasts();
    
    // Initialize dashboard charts if we're on the main dashboard page
    if (window.location.pathname.endsWith('/dashboard') || window.location.pathname.endsWith('/dashboard/')) {
        initDashboardCharts();
    }
});

/**
 * Initialize sidebar functionality including toggle button
 */
function initSidebar() {
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const chevronIcon = document.querySelector('.sidebar-toggle .chevron-left');
    
    if (toggleSidebarBtn && sidebar && mainContent) {
        // Initialize main content margin based on current sidebar state when page loads
        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginLeft = window.innerWidth <= 768 ? '0' : '5rem';
            if (chevronIcon) {
                chevronIcon.style.transform = 'rotate(180deg)';
            }
            toggleSidebarContent(true);
        } else {
            mainContent.style.marginLeft = '16rem';
            if (chevronIcon) {
                chevronIcon.style.transform = 'rotate(0deg)';
            }
            toggleSidebarContent(false);
        }
        
        toggleSidebarBtn.addEventListener('click', function() {
            // Toggle collapsed class
            const isCollapsed = sidebar.classList.toggle('collapsed');
            
            // Rotate chevron icon based on sidebar state
            if (chevronIcon) {
                if (isCollapsed) {
                    chevronIcon.style.transform = 'rotate(180deg)';
                } else {
                    chevronIcon.style.transform = 'rotate(0deg)';
                }
            }
            
            // Update main content margin
            if (isCollapsed) {
                mainContent.style.marginLeft = window.innerWidth <= 768 ? '0' : '5rem';
                mainContent.style.width = 'calc(100% - 5rem)';
                updateSidebarState(false);
            } else {
                mainContent.style.marginLeft = '16rem';
                mainContent.style.width = 'calc(100% - 16rem)';
                updateSidebarState(true);
            }
            
            // Update the sidebar content dynamically based on new state
            toggleSidebarContent(isCollapsed);
        });
    }
    
    // Handle responsive behavior
    window.addEventListener('resize', function() {
        if (!sidebar || !mainContent) return;
        
        const isCollapsed = sidebar.classList.contains('collapsed');
        
        if (window.innerWidth <= 768) {
            if (isCollapsed) {
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
            } else {
                mainContent.style.marginLeft = '5rem';
                mainContent.style.width = 'calc(100% - 5rem)';
            }
        } else {
            if (isCollapsed) {
                mainContent.style.marginLeft = '5rem';
                mainContent.style.width = 'calc(100% - 5rem)';
            } else {
                mainContent.style.marginLeft = '16rem';
                mainContent.style.width = 'calc(100% - 16rem)';
            }
        }
    });
}

/**
 * Toggle sidebar content visibility based on collapsed state
 */
function toggleSidebarContent(isCollapsed) {
    // Toggle visibility of all text elements in the sidebar
    const logoText = document.querySelector('.sidebar-logo .logo-text');
    if (logoText) {
        logoText.style.display = isCollapsed ? 'none' : 'block';
    }
    
    // Toggle visibility of text in navigation links
    const navLinkTexts = document.querySelectorAll('.sidebar-nav .nav-link span');
    navLinkTexts.forEach(span => {
        span.style.display = isCollapsed ? 'none' : 'block';
    });
    
    // Toggle visibility of text in footer links (like logout)
    const footerLinkTexts = document.querySelectorAll('.sidebar-footer a span');
    footerLinkTexts.forEach(span => {
        span.style.display = isCollapsed ? 'none' : 'block';
    });
}

/**
 * Initialize dropdown functionality
 */
function initDropdowns() {
    // Dropdown toggle functionality
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            dropdown.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown.active');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    });
}

/**
 * Initialize toast notification functionality
 */
function initToasts() {
    // The toast functionality is kept in global scope to be called from anywhere
    window.showToast = function(title, message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = 'toast';
        
        // Set border color based on type
        if (type === 'error') {
            toast.style.borderLeft = '4px solid #ef4444';
        } else if (type === 'success') {
            toast.style.borderLeft = '4px solid #10b981';
        } else {
            toast.style.borderLeft = '4px solid #3b82f6';
        }
        
        toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-title">${title}</div>
                <button class="toast-close">&times;</button>
            </div>
            <div class="toast-body">${message}</div>
        `;
        
        container.appendChild(toast);
        
        // Add close functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', function() {
            toast.classList.add('toast-exit');
            setTimeout(() => {
                if (container.contains(toast)) {
                    container.removeChild(toast);
                }
            }, 300);
        });
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (container.contains(toast)) {
                toast.classList.add('toast-exit');
                setTimeout(() => {
                    if (container.contains(toast)) {
                        container.removeChild(toast);
                    }
                }, 300);
            }
        }, 5000);
    };
    
    // Function to show notification (used by the bell icon)
    window.showNotification = function(message) {
        window.showToast('Notifications', message);
    };
}

/**
 * Update sidebar state in session via AJAX
 */
function updateSidebarState(isOpen) {
    const xhr = new XMLHttpRequest();
    const rootUrl = document.querySelector('meta[name="root-url"]')?.getAttribute('content') || '';
    xhr.open('POST', `${rootUrl}/dashboard/toggleSidebar`, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('isOpen=' + (isOpen ? 1 : 0));
}

/**
 * Initialize dashboard charts
 */
function initDashboardCharts() {
    console.log('Initializing dashboard charts...');
    
    // Initialize Website Visits chart
    initVisitsChart();
    
    // Initialize User Distribution chart
    initUserDistributionChart();
    
    // Initialize Traffic Sources chart
    initTrafficSourcesChart();
    
    // Initialize Page Performance chart
    initPagePerformanceChart();
}

/**
 * Initialize Website Visits chart
 */
function initVisitsChart() {
    const visitsChartEl = document.getElementById('visits-chart');
    if (!visitsChartEl) return;
    
    let visitChartData;
    try {
        if (window.visitChartData) {
            visitChartData = window.visitChartData;
        } else {
            const dataStr = visitsChartEl.getAttribute('data-chart') || '[]';
            visitChartData = JSON.parse(dataStr);
        }
    } catch (err) {
        console.error('Error parsing visits chart data:', err);
        visitChartData = [];
    }
    
    console.log('Visit chart data:', visitChartData);
    
    try {
        new Chart(visitsChartEl, {
            type: 'line',
            data: {
                labels: visitChartData.map(item => item.day),
                datasets: [{
                    label: 'Daily Visits',
                    data: visitChartData.map(item => item.value),
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: '#3b82f6',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
        console.log('Visits chart initialized');
    } catch (err) {
        console.error('Error creating visits chart:', err);
    }
}

/**
 * Initialize User Distribution chart
 */
function initUserDistributionChart() {
    const distributionChartEl = document.getElementById('distribution-chart');
    if (!distributionChartEl) return;
    
    let userDistributionData;
    try {
        if (window.userDistributionData) {
            userDistributionData = window.userDistributionData;
        } else {
            const dataStr = distributionChartEl.getAttribute('data-chart') || '[]';
            userDistributionData = JSON.parse(dataStr);
        }
    } catch (err) {
        console.error('Error parsing user distribution data:', err);
        userDistributionData = [];
    }
    
    console.log('User distribution data:', userDistributionData);
    
    try {
        new Chart(distributionChartEl, {
            type: 'doughnut',
            data: {
                labels: userDistributionData.map(item => item.role),
                datasets: [{
                    data: userDistributionData.map(item => item.count),
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
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
                            padding: 20,
                            boxWidth: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        console.log('User distribution chart initialized');
    } catch (err) {
        console.error('Error creating user distribution chart:', err);
    }
}

/**
 * Initialize Traffic Sources chart
 */
function initTrafficSourcesChart() {
    const trafficSourcesEl = document.getElementById('traffic-sources-chart');
    if (!trafficSourcesEl) return;
    
    try {
        new Chart(trafficSourcesEl, {
            type: 'pie',
            data: {
                labels: ['Direct', 'Search', 'Social', 'Email', 'Referral'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
                    ],
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
                            padding: 20,
                            boxWidth: 15
                        }
                    }
                }
            }
        });
        console.log('Traffic sources chart initialized');
    } catch (err) {
        console.error('Error creating traffic sources chart:', err);
    }
}

/**
 * Initialize Page Performance chart
 */
function initPagePerformanceChart() {
    const pagePerformanceEl = document.getElementById('page-performance-chart');
    if (!pagePerformanceEl) return;
    
    try {
        new Chart(pagePerformanceEl, {
            type: 'bar',
            data: {
                labels: ['Home', 'Services', 'Pricing', 'Contact', 'Blog'],
                datasets: [{
                    label: 'Page Views',
                    data: [4200, 3800, 2100, 1500, 3200],
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                    barThickness: 25
                },
                {
                    label: 'Avg. Time (sec)',
                    data: [95, 85, 60, 40, 120],
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    barThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
        console.log('Page performance chart initialized');
    } catch (err) {
        console.error('Error creating page performance chart:', err);
    }
}

/**
 * User Management specific functionality
 */
function initUserManagement() {
    // Edit user functionality
    const editUserBtns = document.querySelectorAll('.edit-user-btn');
    editUserBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const form = document.getElementById('edit-user-form');
            
            if (userId && form) {
                // Set the user ID in the form
                const userIdInput = form.querySelector('input[name="user_id"]');
                if (userIdInput) {
                    userIdInput.value = userId;
                }
                
                // You might load user data via AJAX here to populate the form
                loadUserData(userId, form);
            }
        });
    });
    
    // Delete user functionality
    const deleteUserBtns = document.querySelectorAll('.delete-user-btn');
    deleteUserBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userIdInput = document.querySelector('input[name="delete_user_id"]');
            
            if (userId && userIdInput) {
                userIdInput.value = userId;
                
                // Confirmation message could include user info
                const userName = this.getAttribute('data-user-name') || 'this user';
                const confirmMessage = document.getElementById('confirm-delete-message');
                if (confirmMessage) {
                    confirmMessage.textContent = `Are you sure you want to delete ${userName}?`;
                }
            }
        });
    });
}

/**
 * Load user data for editing
 */
function loadUserData(userId, form) {
    const xhr = new XMLHttpRequest();
    const rootUrl = document.querySelector('meta[name="root-url"]')?.getAttribute('content') || '';
    xhr.open('GET', `${rootUrl}/dashboard/getUserData?user_id=${userId}`, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const userData = JSON.parse(xhr.responseText);
                
                // Populate form fields with user data
                const nameInput = form.querySelector('input[name="name"]');
                const emailInput = form.querySelector('input[name="email"]');
                const roleSelect = form.querySelector('select[name="role"]');
                
                if (nameInput) nameInput.value = userData.name || '';
                if (emailInput) emailInput.value = userData.email || '';
                if (roleSelect) roleSelect.value = userData.role || '';
                
                // Show modal title with user name
                const modalTitle = document.querySelector('#editUserModal .modal-title');
                if (modalTitle) {
                    modalTitle.textContent = `Edit User: ${userData.name}`;
                }
            } catch (e) {
                console.error('Error parsing user data:', e);
                window.showToast('Error', 'Failed to load user data', 'error');
            }
        } else {
            window.showToast('Error', 'Failed to load user data', 'error');
        }
    };
    
    xhr.onerror = function() {
        window.showToast('Error', 'Network error occurred', 'error');
    };
    
    xhr.send();
}

/**
 * Support Tickets functionality
 */
function initSupportTickets() {
    // View ticket details functionality
    const viewTicketBtns = document.querySelectorAll('.view-ticket-btn');
    viewTicketBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            const modal = document.getElementById('ticket-details-modal');
            
            if (ticketId && modal) {
                loadTicketDetails(ticketId, modal);
            }
        });
    });
    
    // Fix modal size and make it responsive
    const ticketModal = document.getElementById('ticket-details-modal');
    if (ticketModal) {
        const modalDialog = ticketModal.querySelector('.modal-dialog');
        if (modalDialog) {
            modalDialog.style.maxWidth = '90%';
            modalDialog.style.maxHeight = '90vh';
            modalDialog.style.margin = '1.75rem auto';
        }
        
        const modalContent = ticketModal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.maxHeight = '85vh';
            modalContent.style.overflow = 'auto';
        }
        
        // Ensure the close button is visible and working
        const closeButtons = ticketModal.querySelectorAll('[data-dismiss="modal"], .close-modal');
        closeButtons.forEach(button => {
            button.style.position = 'absolute';
            button.style.right = '10px';
            button.style.top = '10px';
            button.style.zIndex = '1050';
            button.style.fontSize = '1.5rem';
            button.style.fontWeight = 'bold';
            button.style.cursor = 'pointer';
            
            button.addEventListener('click', function() {
                // Close the modal
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const bsModal = bootstrap.Modal.getInstance(ticketModal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                } else {
                    // Fallback if Bootstrap is not available
                    ticketModal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop && backdrop.parentNode) {
                        backdrop.parentNode.removeChild(backdrop);
                    }
                }
            });
        });
    }
}

/**
 * Load ticket details via AJAX
 */
function loadTicketDetails(ticketId, modal) {
    const xhr = new XMLHttpRequest();
    const rootUrl = document.querySelector('meta[name="root-url"]')?.getAttribute('content') || '';
    xhr.open('GET', `${rootUrl}/dashboard/getTicketDetails?ticket_id=${ticketId}`, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const ticketData = JSON.parse(xhr.responseText);
                
                // Update modal content with ticket details
                const title = modal.querySelector('.modal-title');
                const body = modal.querySelector('.modal-body');
                
                if (title) {
                    title.textContent = `Ticket #${ticketId}: ${ticketData.subject || 'No Subject'}`;
                }
                
                if (body) {
                    body.innerHTML = `
                        <div class="ticket-details">
                            <div class="ticket-header">
                                <div class="ticket-info">
                                    <p><strong>Submitted by:</strong> ${ticketData.user_name || 'Unknown'}</p>
                                    <p><strong>Date:</strong> ${ticketData.created_at || 'Unknown'}</p>
                                    <p><strong>Status:</strong> <span class="badge badge-${getStatusClass(ticketData.status)}">${ticketData.status || 'Unknown'}</span></p>
                                </div>
                            </div>
                            <div class="ticket-content">
                                <h5>Description:</h5>
                                <p>${ticketData.description || 'No description provided.'}</p>
                            </div>
                            <div class="ticket-responses">
                                <h5>Responses:</h5>
                                ${renderTicketResponses(ticketData.responses)}
                            </div>
                            <div class="ticket-reply mt-4">
                                <h5>Add Response:</h5>
                                <form id="ticket-reply-form">
                                    <input type="hidden" name="ticket_id" value="${ticketId}">
                                    <div class="form-group">
                                        <textarea name="response" class="form-input" rows="3" placeholder="Type your response here..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <select name="status" class="form-input">
                                            <option value="open" ${ticketData.status === 'open' ? 'selected' : ''}>Open</option>
                                            <option value="in-progress" ${ticketData.status === 'in-progress' ? 'selected' : ''}>In Progress</option>
                                            <option value="closed" ${ticketData.status === 'closed' ? 'selected' : ''}>Closed</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Response</button>
                                </form>
                            </div>
                        </div>
                    `;
                    
                    // Initialize the reply form submission
                    const replyForm = body.querySelector('#ticket-reply-form');
                    if (replyForm) {
                        replyForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            submitTicketResponse(this);
                        });
                    }
                }
                
            } catch (e) {
                console.error('Error parsing ticket data:', e);
                window.showToast('Error', 'Failed to load ticket details', 'error');
            }
        } else {
            window.showToast('Error', 'Failed to load ticket details', 'error');
        }
    };
    
    xhr.onerror = function() {
        window.showToast('Error', 'Network error occurred', 'error');
    };
    
    xhr.send();
}

/**
 * Helper function to render ticket responses
 */
function renderTicketResponses(responses) {
    if (!responses || responses.length === 0) {
        return '<p>No responses yet.</p>';
    }
    
    let html = '<div class="ticket-response-list">';
    
    responses.forEach(response => {
        html += `
            <div class="ticket-response">
                <div class="response-header">
                    <strong>${response.user_name || 'Staff'}</strong>
                    <span class="response-date">${response.created_at || ''}</span>
                </div>
                <div class="response-content">
                    <p>${response.content || ''}</p>
                </div>
                <div class="response-status">
                    <span class="badge badge-${getStatusClass(response.status)}">Status: ${response.status || 'unknown'}</span>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    return html;
}

/**
 * Helper function to get appropriate status class for badges
 */
function getStatusClass(status) {
    switch(status) {
        case 'open':
            return 'yellow';
        case 'in-progress':
            return 'blue';
        case 'closed':
            return 'green';
        case 'pending':
            return 'gray';
        default:
            return 'gray';
    }
}

/**
 * Submit ticket response
 */
function submitTicketResponse(form) {
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    const rootUrl = document.querySelector('meta[name="root-url"]')?.getAttribute('content') || '';
    
    xhr.open('POST', `${rootUrl}/dashboard/submitTicketResponse`, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    window.showToast('Success', 'Response submitted successfully', 'success');
                    
                    // Reload ticket details to show the new response
                    const ticketId = formData.get('ticket_id');
                    const modal = document.getElementById('ticket-details-modal');
                    if (ticketId && modal) {
                        loadTicketDetails(ticketId, modal);
                    }
                } else {
                    window.showToast('Error', response.message || 'Failed to submit response', 'error');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                window.showToast('Error', 'Failed to submit response', 'error');
            }
        } else {
            window.showToast('Error', 'Failed to submit response', 'error');
        }
    };
    
    xhr.onerror = function() {
        window.showToast('Error', 'Network error occurred', 'error');
    };
    
    xhr.send(formData);
}

// Initialize specific functionality based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    
    // Check which page we're on and initialize specific functionality
    if (currentPath.includes('/dashboard/user_management')) {
        initUserManagement();
    } else if (currentPath.includes('/dashboard/support_tickets')) {
        initSupportTickets();
    }
});