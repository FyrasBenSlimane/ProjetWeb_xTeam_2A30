/**
 * Support Ticket System JS
 * Handles all dynamic functionality for the support ticket system
 */

// Define BASE_URL if not already defined
if (typeof BASE_URL === 'undefined') {
    window.BASE_URL = window.location.origin;
}

document.addEventListener('DOMContentLoaded', function () {
    initializeSupportTabs();
    initializeSearchAndFilters();
    initializeRefreshActions();
    initializeTicketControls();
    setupAutomaticStatsUpdate();
    initializeDropdownButtons();
    addFilterFunctionality();
    setupFormValidation();
    setupRichTextEditing();
});

/**
 * Initialize support section tabs with better content handling
 */
function initializeSupportTabs() {
    // Filter tickets by status when clicking on tabs
    const ticketTabs = document.querySelectorAll('#ticketTabs .nav-link');

    ticketTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const status = this.textContent.toLowerCase();
            if (status !== 'all tickets') {
                filterTicketsByStatus(status);
            }
        });
    });

    // Initially populate all tabs
    ['open', 'pending', 'answered', 'closed'].forEach(status => {
        filterTicketsByStatus(status);
    });
}

/**
 * Filter tickets by status and update tab content
 * @param {string} status - Ticket status to filter by
 */
function filterTicketsByStatus(status) {
    // Show loading spinner
    const tabContent = document.getElementById(status + '-tickets-tab-content');
    if (tabContent) {
        tabContent.innerHTML = '<div class="loading-spinner"><i class="bx bx-loader-alt bx-spin"></i><span>Loading tickets...</span></div>';
    }

    // Clone the all tickets table
    const allTicketsTable = document.querySelector('#all-tickets .ticket-table').cloneNode(true);
    const tbody = allTicketsTable.querySelector('tbody');
    const rows = document.querySelectorAll('#all-tickets .ticket-table tbody tr');

    // Clear existing rows
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }

    // Filter rows by status
    let filteredRows = [];
    rows.forEach(row => {
        // Get the status from the status badge
        const statusBadge = row.querySelector('.status-badge');
        if (!statusBadge) return;

        const rowStatus = statusBadge.textContent.toLowerCase().trim();

        if (rowStatus === status) {
            filteredRows.push(row.cloneNode(true));
        }
    });

    // Add filtered rows or show no results message
    if (filteredRows.length > 0) {
        filteredRows.forEach(row => {
            tbody.appendChild(row);
        });
    } else {
        const noResultsRow = document.createElement('tr');
        const noResultsCell = document.createElement('td');
        noResultsCell.setAttribute('colspan', '9');
        noResultsCell.classList.add('text-center');

        noResultsCell.innerHTML = `
            <div class="empty-state">
                <i class='bx bx-inbox'></i>
                <p>No ${status} tickets found</p>
            </div>
        `;

        noResultsRow.appendChild(noResultsCell);
        tbody.appendChild(noResultsRow);
    }

    // Update the tab content
    setTimeout(() => {
        if (tabContent) {
            tabContent.innerHTML = '';

            const tableData = document.createElement('div');
            tableData.classList.add('table-data');

            const order = document.createElement('div');
            order.classList.add('order');

            const head = document.createElement('div');
            head.classList.add('head');
            head.innerHTML = `
                <h3>${status.charAt(0).toUpperCase() + status.slice(1)} Support Tickets</h3>
                <div class="refresh-container">
                    <button class="refresh-tab btn btn-sm btn-light" title="Refresh Tickets">
                        <i class='bx bx-refresh'></i> Refresh
                    </button>
                </div>
            `;

            const tableContainer = document.createElement('div');
            tableContainer.classList.add('ticket-table-container');
            tableContainer.appendChild(allTicketsTable);

            order.appendChild(head);
            order.appendChild(tableContainer);
            tableData.appendChild(order);
            tabContent.appendChild(tableData);

            // Add event listener to the refresh button
            const refreshButton = tabContent.querySelector('.refresh-tab');
            if (refreshButton) {
                refreshButton.addEventListener('click', function () {
                    filterTicketsByStatus(status);
                });
            }

            // Make sure dropdown buttons and other interactive elements work
            makeTabActionButtonsWork(tabContent);

            // Apply current filters to this tab
            applyCurrentFiltersToTab(status);
        }
    }, 300);
}

/**
 * Make sure tab action buttons work properly in specified tab content
 * This fixes the issue with dropdown action buttons in tabs
 * @param {HTMLElement} tabContent - The tab content element where buttons should be fixed
 */
function makeTabActionButtonsWork(tabContent) {
    if (!tabContent) {
        // If no tab specified, fix all tabs
        document.querySelectorAll('.tab-content .tab-pane').forEach(tab => {
            fixTabButtons(tab);
        });
    } else {
        // Fix buttons in the specified tab
        fixTabButtons(tabContent);
    }

    function fixTabButtons(tab) {
        // Fix dropdown toggles
        tab.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            if (!toggle.hasAttribute('data-bs-toggle')) {
                toggle.setAttribute('data-bs-toggle', 'dropdown');

                // Initialize bootstrap dropdowns if available
                if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                    new bootstrap.Dropdown(toggle);
                }
            }
        });

        // Fix status change links
        tab.querySelectorAll('.status-change').forEach(link => {
            if (!link.hasAttribute('data-listener-added')) {
                link.setAttribute('data-listener-added', 'true');
                link.addEventListener('click', function () {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating...';
                });
            }
        });

        // Fix delete links
        tab.querySelectorAll('.delete-ticket').forEach(link => {
            if (!link.hasAttribute('data-listener-added')) {
                link.setAttribute('data-listener-added', 'true');
                link.addEventListener('click', function (e) {
                    if (!confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
                        e.preventDefault();
                    } else {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Deleting...';
                    }
                });
            }
        });
    }
}

/**
 * Make sure tab action buttons work properly
 * This fixes the issue with dropdown action buttons in tabs
 */
function makeTabActionButtonsWork() {
    // Fix dropdown toggles in filtered tabs
    document.querySelectorAll('.tab-content .dropdown-toggle').forEach(toggle => {
        if (!toggle.hasAttribute('data-bs-toggle')) {
            toggle.setAttribute('data-bs-toggle', 'dropdown');

            // Initialize dropdown
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Dropdown(toggle);
            }
        }
    });

    // Fix delete confirmation dialogs
    document.querySelectorAll('.tab-content a[onclick*="confirm"]').forEach(link => {
        if (!link.hasAttribute('data-listener-added')) {
            link.setAttribute('data-listener-added', 'true');
            link.addEventListener('click', function (e) {
                if (!confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        }
    });
}

/**
 * Initialize search and filter functionality
 */
function initializeSearchAndFilters() {
    const searchInput = document.getElementById('ticket-search');
    const noResults = document.getElementById('no-results');

    if (!searchInput || !noResults) return;

    // Add click event to search button
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', searchTickets);
    }

    // Search on Enter press and input change
    if (searchInput) {
        // Search when pressing Enter
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchTickets();
            }
        });

        // Debounced search on input change
        let debounceTimer;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(searchTickets, 300);
        });
    }

    // Priority filter functionality
    const priorityFilter = document.getElementById('priority-filter');
    if (priorityFilter) {
        priorityFilter.addEventListener('change', applyFilters);
    }

    // Date filter functionality
    const dateFilter = document.getElementById('date-filter');
    if (dateFilter) {
        dateFilter.addEventListener('change', applyFilters);
    }
}

/**
 * Search tickets based on search input
 */
function searchTickets() {
    const searchInput = document.getElementById('ticket-search');
    const noResults = document.getElementById('no-results');

    if (!searchInput || !noResults) return;

    const searchTerm = searchInput.value.toLowerCase().trim();
    const allRows = document.querySelectorAll('#all-tickets tbody tr');
    const activeTabContent = document.querySelector('.tab-pane.active');
    let visibleCount = 0;

    // Add loading class to search input
    searchInput.classList.add('searching');

    // Add delay to show loading effect
    setTimeout(() => {
        // Process each row
        allRows.forEach(row => {
            // Skip empty state rows
            if (row.querySelector('.empty-state')) return;

            const ticketId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const userName = row.querySelector('td:nth-child(2) p')?.textContent.toLowerCase() || '';
            const userEmail = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
            const subject = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

            if (
                ticketId.includes(searchTerm) ||
                userName.includes(searchTerm) ||
                userEmail.includes(searchTerm) ||
                subject.includes(searchTerm) ||
                category.includes(searchTerm)
            ) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no results message in the active tab
        if (activeTabContent.id === 'all-tickets') {
            noResults.style.display = visibleCount === 0 && searchTerm !== '' ? 'block' : 'none';
        } else {
            const tabNoResults = activeTabContent.querySelector('.no-results-container') ||
                document.createElement('div');

            if (!tabNoResults.classList.contains('no-results-container')) {
                tabNoResults.className = 'no-results-container';
                tabNoResults.innerHTML = `
                    <div class="no-results-content">
                        <i class='bx bx-search-alt'></i>
                        <h4>No matching tickets found</h4>
                        <p>Try adjusting your search or filter criteria</p>
                    </div>
                `;
                activeTabContent.appendChild(tabNoResults);
            }

            const tabRows = activeTabContent.querySelectorAll('tbody tr');
            let tabVisibleCount = 0;
            tabRows.forEach(row => {
                if (row.style.display !== 'none' && !row.querySelector('.empty-state')) tabVisibleCount++;
            });

            tabNoResults.style.display = tabVisibleCount === 0 && searchTerm !== '' ? 'block' : 'none';
        }

        // Remove loading class
        searchInput.classList.remove('searching');
    }, 300);
}

/**
 * Apply combined filters (priority, date, search)
 */
function applyFilters() {
    const priorityFilter = document.getElementById('priority-filter');
    const dateFilter = document.getElementById('date-filter');
    const searchInput = document.getElementById('ticket-search');
    const noResults = document.getElementById('no-results');

    if (!priorityFilter || !dateFilter || !searchInput || !noResults) return;

    const priority = priorityFilter.value;
    const dateRange = dateFilter.value;
    const searchTerm = searchInput.value.toLowerCase().trim();
    const allRows = document.querySelectorAll('#all-tickets tbody tr');
    let visibleCount = 0;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const weekAgo = new Date(today);
    weekAgo.setDate(today.getDate() - 7);

    const monthAgo = new Date(today);
    monthAgo.setMonth(today.getMonth() - 1);

    // Add loading class to filters
    priorityFilter.classList.add('filtering');
    dateFilter.classList.add('filtering');

    // Add delay to show loading effect
    setTimeout(() => {
        allRows.forEach(row => {
            // Skip empty state rows
            if (row.querySelector('.empty-state')) return;

            let showByPriority = true;
            let showByDate = true;

            // Apply priority filter
            if (priority !== 'all') {
                const ticketPriority = row.getAttribute('data-priority');
                showByPriority = ticketPriority === priority;
            }

            // Apply date filter
            if (dateRange !== 'all') {
                const ticketDate = new Date(row.getAttribute('data-date'));

                switch (dateRange) {
                    case 'today':
                        showByDate = ticketDate >= today;
                        break;
                    case 'week':
                        showByDate = ticketDate >= weekAgo;
                        break;
                    case 'month':
                        showByDate = ticketDate >= monthAgo;
                        break;
                }
            }

            // Apply search filter
            const ticketId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const userName = row.querySelector('td:nth-child(2) p')?.textContent.toLowerCase() || '';
            const userEmail = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
            const subject = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

            let showBySearch = true;
            if (searchTerm !== '') {
                showBySearch =
                    ticketId.includes(searchTerm) ||
                    userName.includes(searchTerm) ||
                    userEmail.includes(searchTerm) ||
                    subject.includes(searchTerm) ||
                    category.includes(searchTerm);
            }

            // Display row if it passes all filters
            const shouldShow = showByPriority && showByDate && showBySearch;
            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visibleCount++;
        });

        // Show/hide no results message in All Tickets tab
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';

        // Apply filters to other tabs as well
        document.querySelectorAll('#ticketTabs .nav-link.active').forEach(tab => {
            const status = tab.textContent.toLowerCase().trim();
            if (status !== 'all tickets') {
                applyFiltersToTab(status);
            }
        });

        // Remove loading classes
        priorityFilter.classList.remove('filtering');
        dateFilter.classList.remove('filtering');
    }, 300);
}

/**
 * Apply filters to a specific tab
 * @param {string} status - Tab status to filter
 */
function applyFiltersToTab(status) {
    const tabContent = document.getElementById(status + '-tickets-tab-content');
    if (!tabContent) return;

    const tabRows = tabContent.querySelectorAll('tbody tr');
    let visibleCount = 0;

    tabRows.forEach(row => {
        if (row.style.display !== 'none' && !row.querySelector('.empty-state')) visibleCount++;
    });

    const tabNoResults = tabContent.querySelector('.no-results-container') ||
        document.createElement('div');

    if (!tabNoResults.classList.contains('no-results-container')) {
        tabNoResults.className = 'no-results-container';
        tabNoResults.innerHTML = `
            <div class="no-results-content">
                <i class='bx bx-search-alt'></i>
                <h4>No matching tickets found</h4>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        `;
        tabContent.appendChild(tabNoResults);
    }

    tabNoResults.style.display = visibleCount === 0 ? 'block' : 'none';
}

/**
 * Initialize refresh actions for tickets
 */
function initializeRefreshActions() {
    const refreshBtn = document.getElementById('refresh-tickets');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function () {
            // Show refresh animation
            this.classList.add('refreshing');
            this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Refreshing...';

            // Reload page after short delay to show animation
            setTimeout(() => {
                location.reload();
            }, 800);
        });
    }
}

/**
 * Initialize ticket action controls and improve ux
 */
function initializeTicketControls() {
    // Add hover effects to action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-3px)';
        });

        btn.addEventListener('mouseleave', function () {
            this.style.transform = '';
        });
    });

    // Animate status changes
    const ticketStatusLinks = document.querySelectorAll('a[href*="updateTicketStatus"]');
    ticketStatusLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Don't prevent default - let the link work normally

            // Get the button and add loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating...';

            // Get the dropdown menu and close it
            const dropdownMenu = btn.closest('.dropdown-menu');
            if (dropdownMenu) {
                // Use Bootstrap's dropdown method if available
                const dropdownButton = document.querySelector('[data-bs-toggle="dropdown"][aria-expanded="true"]');
                if (dropdownButton && typeof bootstrap !== 'undefined') {
                    const dropdown = bootstrap.Dropdown.getInstance(dropdownButton);
                    if (dropdown) dropdown.hide();
                } else {
                    // Fallback to manually hiding
                    dropdownMenu.classList.remove('show');
                }
            }
        });
    });

    // Initialize status filters
    const statusFilters = document.querySelectorAll('.status-filter');
    statusFilters.forEach(filter => {
        filter.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove active class from all filters
            statusFilters.forEach(f => f.classList.remove('active'));

            // Add active class to clicked filter
            this.classList.add('active');

            // Get status value
            const status = this.getAttribute('data-status');

            // Filter tickets based on status
            filterTickets(status);
        });
    });
}

/**
 * Setup automatic stats update at regular intervals
 */
function setupAutomaticStatsUpdate() {
    // Update ticket statistics every 30 seconds
    updateTicketStats(); // Initial update
    setInterval(updateTicketStats, 30000);
}

/**
 * Update ticket statistics via AJAX
 */
function updateTicketStats() {
    fetch(BASE_URL + '/dashboard/getTicketStats')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Helper function to update with animation
            const updateWithAnimation = (elementId, newValue) => {
                const element = document.getElementById(elementId);
                if (element) {
                    const currentValue = parseInt(element.textContent);

                    if (currentValue !== newValue) {
                        // Add pulse animation
                        element.classList.add('stat-update');

                        // Update value
                        element.textContent = newValue;

                        // Remove animation class after animation completes
                        setTimeout(() => {
                            element.classList.remove('stat-update');
                        }, 1000);
                    }
                }
            };

            // Update all stats with animation if they changed
            updateWithAnimation('total-tickets', data.total);
            updateWithAnimation('open-tickets', data.open);
            updateWithAnimation('pending-tickets', data.pending);
            updateWithAnimation('answered-tickets', data.answered);
            updateWithAnimation('closed-tickets', data.closed);

            // Update notification counter in sidebar if it exists
            const sidebarCounter = document.getElementById('sidebar-ticket-count');
            if (sidebarCounter) {
                sidebarCounter.textContent = data.open;

                // Add pulse animation if count changed
                const currentValue = parseInt(sidebarCounter.textContent);
                if (currentValue !== data.open) {
                    sidebarCounter.classList.add('pulse');

                    setTimeout(() => {
                        sidebarCounter.classList.remove('pulse');
                    }, 1000);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching ticket stats:', error);
        });
}

/**
 * Table sorting functionality
 */
function initTableSorting() {
    const tables = document.querySelectorAll('.ticket-table');
    tables.forEach(table => {
        const headers = table.querySelectorAll('th.sortable');

        headers.forEach(header => {
            header.addEventListener('click', function () {
                const column = this.dataset.sort;
                if (!column) return;

                const isAsc = !this.classList.contains('asc');

                // Remove sort classes from all headers
                headers.forEach(h => {
                    h.classList.remove('asc', 'desc');
                    const icon = h.querySelector('.bx');
                    if (icon) icon.className = 'bx bx-sort-alt-2';
                });

                // Add sort class to current header
                this.classList.add(isAsc ? 'asc' : 'desc');
                const icon = this.querySelector('.bx');
                if (icon) icon.className = isAsc ? 'bx bx-sort-up' : 'bx bx-sort-down';

                // Sort the table
                sortTable(table, column, isAsc);
            });
        });
    });
}

/**
 * Sort table by column
 * @param {HTMLElement} table - Table to sort
 * @param {string} column - Column index or name to sort by
 * @param {boolean} isAsc - Whether to sort ascending
 */
function sortTable(table, column, isAsc) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // Get column index based on header data-sort attribute
    const headers = Array.from(table.querySelectorAll('thead th'));
    const columnIndex = headers.findIndex(h => h.dataset.sort === column);

    if (columnIndex === -1) return;

    // Sort the rows
    const sortedRows = rows.sort((a, b) => {
        // Skip empty state rows
        if (a.querySelector('.empty-state') || b.querySelector('.empty-state')) {
            return 0;
        }

        const aCell = a.cells[columnIndex];
        const bCell = b.cells[columnIndex];

        if (!aCell || !bCell) return 0;

        let aValue, bValue;

        // Handle special columns
        if (column === 'id') {
            // Remove # from ID
            aValue = aCell.textContent.replace('#', '');
            bValue = bCell.textContent.replace('#', '');

            return isAsc
                ? parseInt(aValue) - parseInt(bValue)
                : parseInt(bValue) - parseInt(aValue);
        }
        else if (column === 'priority') {
            // Priority order: high, medium, low
            const priorityOrder = { 'high': 0, 'medium': 1, 'low': 2 };
            aValue = aCell.textContent.trim().toLowerCase();
            bValue = bCell.textContent.trim().toLowerCase();

            return isAsc
                ? priorityOrder[aValue] - priorityOrder[bValue]
                : priorityOrder[bValue] - priorityOrder[aValue];
        }
        else if (column === 'status') {
            // Status order: open, pending, answered, closed
            const statusOrder = { 'open': 0, 'pending': 1, 'answered': 2, 'closed': 3 };
            aValue = aCell.textContent.trim().toLowerCase();
            bValue = bCell.textContent.trim().toLowerCase();

            return isAsc
                ? statusOrder[aValue] - statusOrder[bValue]
                : statusOrder[bValue] - statusOrder[aValue];
        }
        else if (column === 'created' || column === 'updated') {
            // Date sorting
            aValue = new Date(aCell.textContent);
            bValue = new Date(bCell.textContent);

            return isAsc
                ? aValue - bValue
                : bValue - aValue;
        }
        else {
            // Default string sorting
            aValue = aCell.textContent.trim().toLowerCase();
            bValue = bCell.textContent.trim().toLowerCase();

            return isAsc
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        }
    });

    // Remove existing rows and add sorted rows
    rows.forEach(row => tbody.removeChild(row));
    sortedRows.forEach(row => tbody.appendChild(row));
}

/**
 * Make sure dropdown buttons work correctly throughout the support section
 */
function initializeDropdownButtons() {
    // Fix dropdowns by properly initializing them with Bootstrap
    document.querySelectorAll('.dropdown-toggle').forEach(dropdown => {
        if (!dropdown.hasAttribute('data-initialized')) {
            // Add required attributes
            if (!dropdown.hasAttribute('data-bs-toggle')) {
                dropdown.setAttribute('data-bs-toggle', 'dropdown');
            }
            if (!dropdown.hasAttribute('aria-expanded')) {
                dropdown.setAttribute('aria-expanded', 'false');
            }

            // Fix dropdown menu alignment for action buttons
            const parentAction = dropdown.closest('.action-buttons');
            if (parentAction) {
                const dropdownMenu = dropdown.nextElementSibling;
                if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                    dropdownMenu.classList.add('dropdown-menu-end');
                }
            }

            // Initialize with Bootstrap if available
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Dropdown(dropdown);
                dropdown.setAttribute('data-initialized', 'true');
            }
        }
    });

    // Apply correct positioning for ticket action dropdowns
    document.querySelectorAll('.actions .dropdown').forEach(container => {
        const dropdownMenu = container.querySelector('.dropdown-menu');
        const containerRect = container.getBoundingClientRect();
        const tableContainer = container.closest('.ticket-table-container');

        if (dropdownMenu && tableContainer) {
            // Always align dropdown to the right for action buttons
            if (!dropdownMenu.classList.contains('dropdown-menu-end')) {
                dropdownMenu.classList.add('dropdown-menu-end');
            }

            // Ensure action dropdowns are always on top
            dropdownMenu.style.zIndex = "1060";
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (event) {
        const isDropdownButton = event.target.matches('.dropdown-toggle') ||
            event.target.closest('.dropdown-toggle');

        if (!isDropdownButton) {
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                // Only close if click was outside this dropdown menu
                if (!openMenu.contains(event.target)) {
                    const dropdownToggle = document.querySelector(`[data-bs-toggle="dropdown"][aria-expanded="true"]`);
                    if (dropdownToggle && typeof bootstrap !== 'undefined') {
                        const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                        if (dropdown) dropdown.hide();
                    } else {
                        openMenu.classList.remove('show');
                    }
                }
            });
        }
    });

    // Fix dropdown positioning after tab switch
    document.querySelectorAll('#ticketTabs .nav-link').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
            // Re-initialize dropdowns in the newly activated tab
            setTimeout(() => {
                const activeTabId = this.getAttribute('data-bs-target');
                const activeTabContent = document.querySelector(activeTabId);
                if (activeTabContent) {
                    const dropdownButtons = activeTabContent.querySelectorAll('.dropdown-toggle');
                    dropdownButtons.forEach(btn => {
                        btn.removeAttribute('data-initialized');
                    });
                    initializeDropdownButtons();
                }
            }, 100);
        });
    });
}

/**
 * Add filter functionality 
 */
function addFilterFunctionality() {
    const searchInput = document.getElementById('ticketSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const ticketRows = document.querySelectorAll('.ticket-row');

            ticketRows.forEach(row => {
                const ticketText = row.textContent.toLowerCase();

                if (ticketText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counter
            updateVisibleCounter();
        });
    }

    // Category filter
    const categoryFilter = document.getElementById('categoryFilter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function () {
            const category = this.value;
            const ticketRows = document.querySelectorAll('.ticket-row');

            ticketRows.forEach(row => {
                if (category === 'all' || row.getAttribute('data-category') === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counter
            updateVisibleCounter();
        });
    }

    // Date range filter
    const dateFilterBtn = document.getElementById('dateFilterBtn');
    if (dateFilterBtn) {
        dateFilterBtn.addEventListener('click', function () {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                end.setHours(23, 59, 59); // Include the whole end day

                const ticketRows = document.querySelectorAll('.ticket-row');

                ticketRows.forEach(row => {
                    const ticketDate = new Date(row.getAttribute('data-date'));

                    if (ticketDate >= start && ticketDate <= end) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update counter
                updateVisibleCounter();
            }
        });
    }
}

/**
 * Set up form validation for ticket submission
 */
function setupFormValidation() {
    const ticketForm = document.getElementById('supportTicketForm');
    if (ticketForm) {
        ticketForm.addEventListener('submit', function (e) {
            const subjectField = document.getElementById('subject');
            const messageField = document.getElementById('message');
            const categoryField = document.getElementById('category');
            let isValid = true;

            // Validate required fields
            if (subjectField && subjectField.value.trim() === '') {
                isValid = false;
                highlightError(subjectField, 'Please enter a subject');
            }

            if (messageField && messageField.value.trim() === '') {
                isValid = false;
                highlightError(messageField, 'Please enter a message');
            }

            if (categoryField && categoryField.value === '') {
                isValid = false;
                highlightError(categoryField, 'Please select a category');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Add event listeners to clear error state on input
        const formInputs = ticketForm.querySelectorAll('input, textarea, select');
        formInputs.forEach(input => {
            input.addEventListener('input', function () {
                this.classList.remove('is-invalid');
                const feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        });
    }
}

/**
 * Highlight form field with error
 * @param {HTMLElement} field The field with the error
 * @param {string} message The error message to display
 */
function highlightError(field, message) {
    field.classList.add('is-invalid');

    // Look for existing feedback element
    let feedback = field.nextElementSibling;
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentNode.insertBefore(feedback, field.nextSibling);
    }

    feedback.textContent = message;
    feedback.style.display = 'block';
}

/**
 * Set up rich text editing for message fields
 */
function setupRichTextEditing() {
    const richTextAreas = document.querySelectorAll('.rich-text-editor');
    richTextAreas.forEach(editor => {
        // Simple formatting buttons
        const toolbar = editor.querySelector('.formatting-toolbar');
        const textarea = editor.querySelector('textarea');

        if (toolbar && textarea) {
            const buttons = toolbar.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const format = this.getAttribute('data-format');
                    applyFormatting(textarea, format);
                });
            });
        }
    });
}

/**
 * Apply formatting to textarea text
 * @param {HTMLTextAreaElement} textarea The textarea element
 * @param {string} format The format to apply (bold, italic, etc.)
 */
function applyFormatting(textarea, format) {
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    let replacement = '';

    switch (format) {
        case 'bold':
            replacement = `**${selectedText}**`;
            break;
        case 'italic':
            replacement = `_${selectedText}_`;
            break;
        case 'link':
            replacement = `[${selectedText}](url)`;
            break;
        case 'list':
            replacement = `\n- ${selectedText.split('\n').join('\n- ')}`;
            break;
        default:
            replacement = selectedText;
    }

    textarea.value =
        textarea.value.substring(0, start) +
        replacement +
        textarea.value.substring(end);

    textarea.focus();

    // Reset cursor position appropriately
    if (selectedText.length > 0) {
        textarea.selectionStart = start;
        textarea.selectionEnd = start + replacement.length;
    } else {
        const cursorPos = start + replacement.length;
        textarea.selectionStart = cursorPos;
        textarea.selectionEnd = cursorPos;
    }
}

/**
 * Apply current filters to a specific tab
 * @param {string} status - Tab status to apply filters to
 */
function applyCurrentFiltersToTab(status) {
    const tabContent = document.getElementById(status + '-tickets-tab-content');
    if (!tabContent) return;

    const priorityFilter = document.getElementById('priority-filter');
    const dateFilter = document.getElementById('date-filter');
    const searchInput = document.getElementById('ticket-search');

    if (!priorityFilter || !dateFilter || !searchInput) return;

    const priority = priorityFilter.value;
    const dateRange = dateFilter.value;
    const searchTerm = searchInput.value.toLowerCase().trim();

    // Get all rows in the current tab
    const tabRows = tabContent.querySelectorAll('tbody tr');
    let visibleCount = 0;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const weekAgo = new Date(today);
    weekAgo.setDate(today.getDate() - 7);

    const monthAgo = new Date(today);
    monthAgo.setMonth(today.getMonth() - 1);

    tabRows.forEach(row => {
        // Skip empty state rows
        if (row.querySelector('.empty-state')) return;

        let showByPriority = true;
        let showByDate = true;
        let showBySearch = true;

        // Apply priority filter
        if (priority !== 'all') {
            const ticketPriority = row.getAttribute('data-priority');
            showByPriority = ticketPriority === priority;
        }

        // Apply date filter
        if (dateRange !== 'all') {
            const ticketDate = new Date(row.getAttribute('data-date'));

            switch (dateRange) {
                case 'today':
                    showByDate = ticketDate >= today;
                    break;
                case 'week':
                    showByDate = ticketDate >= weekAgo;
                    break;
                case 'month':
                    showByDate = ticketDate >= monthAgo;
                    break;
            }
        }

        // Apply search filter
        if (searchTerm !== '') {
            const ticketId = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
            const userName = row.querySelector('td:nth-child(2) p')?.textContent.toLowerCase() || '';
            const userEmail = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
            const subject = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';

            showBySearch =
                ticketId.includes(searchTerm) ||
                userName.includes(searchTerm) ||
                userEmail.includes(searchTerm) ||
                subject.includes(searchTerm) ||
                category.includes(searchTerm);
        }

        // Display row if it passes all filters
        const shouldShow = showByPriority && showByDate && showBySearch;
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleCount++;
    });

    // Show/hide no results message
    const noResults = tabContent.querySelector('.no-results-container');
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    } else if (visibleCount === 0) {
        // Create no results message if it doesn't exist
        const noResultsContainer = document.createElement('div');
        noResultsContainer.className = 'no-results-container';
        noResultsContainer.innerHTML = `
            <div class="no-results-content">
                <i class='bx bx-search-alt'></i>
                <h4>No matching tickets found</h4>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        `;
        tabContent.appendChild(noResultsContainer);
    }

    // Make sure table sorting works after filtering
    const table = tabContent.querySelector('.ticket-table');
    if (table) {
        const headers = table.querySelectorAll('th.sortable');
        headers.forEach(header => {
            if (!header.hasAttribute('data-listener-added')) {
                header.setAttribute('data-listener-added', 'true');
                header.addEventListener('click', function () {
                    const column = this.dataset.sort;
                    if (!column) return;

                    const isAsc = !this.classList.contains('asc');

                    // Remove sort classes from all headers in this table
                    headers.forEach(h => {
                        h.classList.remove('asc', 'desc');
                        const icon = h.querySelector('.bx');
                        if (icon) icon.className = 'bx bx-sort-alt-2';
                    });

                    // Add sort class to current header
                    this.classList.add(isAsc ? 'asc' : 'desc');
                    const icon = this.querySelector('.bx');
                    if (icon) icon.className = isAsc ? 'bx bx-sort-up' : 'bx bx-sort-down';

                    // Sort the table
                    sortTable(table, column, isAsc);
                });
            }
        });
    }
}