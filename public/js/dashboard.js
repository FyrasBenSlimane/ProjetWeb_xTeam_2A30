/**
 * Dashboard JavaScript functionality
 * Enhanced dashboard interactions and utilities for better UI/UX
 */

// Define base URL for API endpoints only if not already defined
if (typeof BASE_URL === 'undefined') {
    window.BASE_URL = window.location.origin;
}

document.addEventListener('DOMContentLoaded', function () {
    // Initialize dashboard components
    initSidebar();
    initNotifications();
    initDataTables();
    initFormValidations();
    initDeleteConfirmations();
    initSearchFunctionality();
    initCardEffects();

    // Check if Chart.js is available before initializing charts
    if (typeof Chart !== 'undefined') {
        initCharts();
    } else {
        console.warn('Chart.js library not found. Charts will not be displayed.');
        // Optional: Load Chart.js dynamically
        loadChartJs();
    }
});

/**
 * Dynamically load Chart.js if not already available
 */
function loadChartJs() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = function () {
        console.log('Chart.js loaded successfully');
        initCharts();
    };
    script.onerror = function () {
        console.error('Failed to load Chart.js');
    };
    document.head.appendChild(script);
}

/**
 * Initialize sidebar functionality with improved toggle and active state handling
 */
function initSidebar() {
    // Get sidebar element
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) {
        console.error('Sidebar element not found');
        return;
    }

    // Get existing sidebar toggle buttons if available
    const toggleMobile = document.getElementById('sidebar-toggle') || null;
    const toggleDesktop = document.getElementById('sidebar-collapse') || null;

    // If mobile toggle doesn't exist, don't create it - use existing HTML structure instead
    if (toggleMobile) {
        // Toggle sidebar on mobile
        toggleMobile.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    // If desktop collapse button doesn't exist, don't create it - use existing HTML structure instead
    if (toggleDesktop) {
        // Collapse sidebar on desktop
        toggleDesktop.addEventListener('click', function () {
            sidebar.classList.toggle('hide');

            // Save state in localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('hide'));

            // Dispatch event for other components to react to sidebar toggle
            document.dispatchEvent(new CustomEvent('sidebarToggle', {
                detail: { isHidden: sidebar.classList.contains('hide') }
            }));

            // Save sidebar state to session via AJAX
            const isOpen = !sidebar.classList.contains('hide');
            saveSidebarState(isOpen);
        });
    }

    // Check saved state from localStorage
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('hide');
        // Dispatch event to ensure the content area is updated
        document.dispatchEvent(new CustomEvent('sidebarToggle', {
            detail: { isHidden: true }
        }));
    }

    // Handle submenu toggles
    const menuItems = document.querySelectorAll('.menu-item');
    if (menuItems.length > 0) {
        menuItems.forEach(item => {
            item.addEventListener('click', function (e) {
                // Only prevent default if this is a submenu toggle
                if (this.closest('.has-submenu')) {
                    e.preventDefault();
                    e.stopPropagation();

                    const parent = this.closest('.has-submenu');
                    if (parent) {
                        const submenu = parent.querySelector('.submenu');

                        if (submenu) {
                            // Toggle submenu visibility
                            submenu.classList.toggle('show');
                            parent.classList.toggle('active');

                            // If opening this submenu, close others
                            if (submenu.classList.contains('show')) {
                                document.querySelectorAll('.has-submenu .submenu.show').forEach(menu => {
                                    if (menu !== submenu) {
                                        menu.classList.remove('show');
                                        menu.closest('.has-submenu').classList.remove('active');
                                    }
                                });
                            }
                        }
                    }
                }
            });
        });
    }

    // Make submenu links clickable
    const submenuLinks = document.querySelectorAll('.submenu li a');
    if (submenuLinks.length > 0) {
        submenuLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                // Allow the link to work normally - don't prevent default
                e.stopPropagation();
            });
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768 &&
            sidebar &&
            !sidebar.contains(e.target) &&
            e.target.id !== 'sidebar-toggle' &&
            !e.target.closest('#sidebar-toggle')) {
            sidebar.classList.remove('show');
        }
    });

    // Handle sidebar menu active items based on current URL
    highlightActiveMenuItem();
}

/**
 * Set active menu item based on current URL
 */
function highlightActiveMenuItem() {
    const currentPath = window.location.pathname;
    const queryString = window.location.search;
    const menuItems = document.querySelectorAll('#sidebar .side-menu a');

    menuItems.forEach(item => {
        const href = item.getAttribute('href') || '';

        // Check for exact match or if current path starts with the menu item path
        // Also handle query parameters for sections
        if (currentPath === href ||
            (href !== '#' && href !== '' && currentPath.startsWith(href)) ||
            (queryString && href.includes(queryString))) {

            // Add active class to the li parent element
            const parentLi = item.closest('li');
            if (parentLi) {
                parentLi.classList.add('active');

                // If it's in a submenu, also activate parent
                const parentSubmenu = parentLi.closest('.submenu');
                if (parentSubmenu) {
                    const parentMenuItem = parentSubmenu.closest('li');
                    if (parentMenuItem) {
                        parentMenuItem.classList.add('active');
                        parentSubmenu.classList.add('show');
                    }
                }
            }
        }
    });
}

/**
 * Save sidebar state to session
 * @param {boolean} isOpen - Whether sidebar is open
 */
function saveSidebarState(isOpen) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${BASE_URL}/dashboard/toggleSidebar`, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status !== 200) {
            console.error('Failed to save sidebar state:', xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Network error occurred while saving sidebar state');
    };
    xhr.send(`isOpen=${isOpen ? 1 : 0}`);
}

/**
 * Initialize notifications functionality with animations
 */
function initNotifications() {
    // Close notification when close button is clicked
    const notificationCloseButtons = document.querySelectorAll('.notification .close, .alert .close');
    if (notificationCloseButtons.length > 0) {
        notificationCloseButtons.forEach(button => {
            button.addEventListener('click', function () {
                const notification = this.closest('.notification, .alert');
                if (notification) {
                    notification.classList.add('fade-out');

                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            });
        });
    }

    // Auto-close notifications after delay
    const autoCloseNotifications = document.querySelectorAll('.notification.auto-close, .alert.auto-close');
    if (autoCloseNotifications.length > 0) {
        autoCloseNotifications.forEach(notification => {
            const delay = notification.dataset.delay || 5000;
            setTimeout(() => {
                notification.classList.add('fade-out');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, delay);
        });
    }

    // Use Bootstrap's API for dropdowns if available
    if (typeof bootstrap !== 'undefined') {
        // Initialize all dropdowns using Bootstrap 5's API
        const dropdownElements = document.querySelectorAll('.dropdown-toggle');
        dropdownElements.forEach(el => {
            new bootstrap.Dropdown(el);
        });

        // Handle notification bell dropdown
        const notificationBell = document.getElementById('notificationIcon');
        if (notificationBell) {
            notificationBell.addEventListener('click', function (e) {
                e.preventDefault();
                const notificationMenu = document.getElementById('notificationMenu');
                if (notificationMenu) {
                    const dropdownInstance = bootstrap.Dropdown.getInstance(notificationBell);
                    if (dropdownInstance) {
                        dropdownInstance.toggle();
                    } else {
                        new bootstrap.Dropdown(notificationBell).toggle();
                    }
                }
            });
        }
    } else {
        // Fallback for when Bootstrap JS is not loaded
        console.warn('Bootstrap JS not loaded - using fallback dropdown implementation');

        // Handle notification bell dropdown
        const notificationBell = document.getElementById('notificationIcon');
        if (notificationBell) {
            notificationBell.addEventListener('click', function (e) {
                e.preventDefault();
                const notificationMenu = document.getElementById('notificationMenu');
                if (notificationMenu) {
                    notificationMenu.classList.toggle('show');

                    // Close other dropdowns
                    const otherMenus = document.querySelectorAll('.dropdown-menu.show:not(#notificationMenu)');
                    otherMenus.forEach(menu => menu.classList.remove('show'));
                }
            });
        }

        // Close notifications dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.matches('#notificationIcon, #notificationIcon *')) {
                const notificationMenu = document.getElementById('notificationMenu');
                if (notificationMenu && notificationMenu.classList.contains('show')) {
                    notificationMenu.classList.remove('show');
                }
            }
        });
    }
}

/**
 * Initialize data tables with advanced features
 */
function initDataTables() {
    const tables = document.querySelectorAll('.data-table, .dashboard-table');
    if (tables.length > 0) {
        tables.forEach(table => {
            // Add sorting functionality to table headers
            const headers = table.querySelectorAll('th[data-sort]');
            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.cursor = 'pointer';
                    header.innerHTML += ' <i class="bx bx-sort"></i>';

                    header.addEventListener('click', function () {
                        const column = this.getAttribute('data-sort');
                        if (!column) return; // Skip if no data-sort attribute

                        const direction = this.classList.contains('asc') ? 'desc' : 'asc';

                        // Remove sort classes from all headers
                        headers.forEach(h => {
                            h.classList.remove('asc', 'desc');
                            h.querySelector('.bx').className = 'bx bx-sort';
                        });

                        // Add sort class to current header
                        this.classList.add(direction);
                        this.querySelector('.bx').className = direction === 'asc' ?
                            'bx bx-sort-up' : 'bx bx-sort-down';

                        // Sort the table
                        sortTable(table, column, direction);
                    });
                });
            }

            // Add search functionality if table has search input
            const searchInput = document.querySelector(`#${table.id}_search`);
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const searchText = this.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchText) ? '' : 'none';
                    });

                    // Show no results message if all rows are hidden
                    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                    const noResults = table.parentNode.querySelector('.no-results-message');

                    if (visibleRows.length === 0 && searchText !== '') {
                        if (!noResults) {
                            const message = document.createElement('div');
                            message.className = 'no-results-message';
                            message.textContent = 'No matching records found';
                            message.style.padding = '1rem';
                            message.style.textAlign = 'center';
                            message.style.color = 'var(--dark-grey)';
                            table.parentNode.insertBefore(message, table.nextSibling);
                        }
                    } else if (noResults) {
                        noResults.remove();
                    }
                });
            }

            // Add pagination if needed
            if (table.classList.contains('paginated') && table.rows.length > 10) {
                paginateTable(table);
            }
        });
    }
}

/**
 * Initialize form validations for dashboard forms
 */
function initFormValidations() {
    const forms = document.querySelectorAll('form[data-validate]');
    if (forms.length > 0) {
        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                let isValid = true;

                // Find all required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    // Remove any existing error messages
                    const existingError = field.parentNode.querySelector('.field-error');
                    if (existingError) {
                        existingError.remove();
                    }

                    // Add error if field is empty
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');

                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'field-error';
                        errorMessage.textContent = 'This field is required';
                        errorMessage.style.color = 'var(--danger)';
                        errorMessage.style.fontSize = '0.75rem';
                        errorMessage.style.marginTop = '0.25rem';

                        field.parentNode.appendChild(errorMessage);
                    } else {
                        field.classList.remove('error');
                    }
                });

                // Check email fields
                const emailFields = form.querySelectorAll('input[type="email"]');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                emailFields.forEach(field => {
                    if (field.value.trim() && !emailRegex.test(field.value)) {
                        isValid = false;
                        field.classList.add('error');

                        // Remove any existing error message
                        const existingError = field.parentNode.querySelector('.field-error');
                        if (existingError) {
                            existingError.remove();
                        }

                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'field-error';
                        errorMessage.textContent = 'Please enter a valid email address';
                        errorMessage.style.color = 'var(--danger)';
                        errorMessage.style.fontSize = '0.75rem';
                        errorMessage.style.marginTop = '0.25rem';

                        field.parentNode.appendChild(errorMessage);
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                }
            });

            // Live validation on input
            const fields = form.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                field.addEventListener('blur', function () {
                    if (field.hasAttribute('required') && !field.value.trim()) {
                        field.classList.add('error');

                        // Add error message if not already present
                        if (!field.parentNode.querySelector('.field-error')) {
                            const errorMessage = document.createElement('div');
                            errorMessage.className = 'field-error';
                            errorMessage.textContent = 'This field is required';
                            errorMessage.style.color = 'var(--danger)';
                            errorMessage.style.fontSize = '0.75rem';
                            errorMessage.style.marginTop = '0.25rem';

                            field.parentNode.appendChild(errorMessage);
                        }
                    } else {
                        field.classList.remove('error');
                        const existingError = field.parentNode.querySelector('.field-error');
                        if (existingError) {
                            existingError.remove();
                        }
                    }
                });
            });
        });
    }
}

/**
 * Initialize delete confirmations for all delete buttons/actions
 */
function initDeleteConfirmations() {
    // Handle data-confirm elements
    const confirmElements = document.querySelectorAll('[data-confirm]');
    if (confirmElements.length > 0) {
        confirmElements.forEach(element => {
            element.addEventListener('click', function (e) {
                const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                }
            });
        });
    }
}

/**
 * Initialize search functionality for dashboard
 */
function initSearchFunctionality() {
    const searchForm = document.querySelector('.dashboard-search');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            const searchInput = this.querySelector('input[type="search"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Initialize card hover and interaction effects
 */
function initCardEffects() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        // Skip cards with .no-effect class
        if (card.classList.contains('no-effect')) return;

        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = 'var(--shadow-md)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
}

/**
 * Sort a table by column
 * @param {HTMLElement} table - Table to sort
 * @param {string} column - Column to sort by
 * @param {string} direction - Sort direction ('asc' or 'desc')
 */
function sortTable(table, column, direction) {
    if (!table || !column || !direction) {
        console.error('Missing required parameters for sortTable');
        return;
    }

    const tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error('Table body not found');
        return;
    }

    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length === 0) {
        console.warn('No rows found to sort in table');
        return;
    }

    // Get column index
    const headerRow = table.querySelector('thead tr');
    if (!headerRow) {
        console.error('Table header row not found');
        return;
    }

    const headers = Array.from(headerRow.querySelectorAll('th'));
    const columnIndex = headers.findIndex(header => header.getAttribute('data-sort') === column);

    if (columnIndex === -1) {
        console.error(`Column ${column} not found in table headers`);
        return;
    }

    // Sort rows
    const sortedRows = rows.sort((a, b) => {
        if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
            return 0;
        }

        // Get cell text content, considering if there might be an inner element with data value
        function getCellValue(cell) {
            // Check if the cell has a data-value attribute
            const dataValue = cell.getAttribute('data-value');
            if (dataValue !== null) {
                return dataValue;
            }

            // Check if there's an inner element with data-value
            const innerElement = cell.querySelector('[data-value]');
            if (innerElement) {
                return innerElement.getAttribute('data-value');
            }

            // Use text content as fallback
            return cell.textContent.trim();
        }

        const aValue = getCellValue(a.cells[columnIndex]);
        const bValue = getCellValue(b.cells[columnIndex]);

        // Handle date values (in format YYYY-MM-DD or MM/DD/YYYY)
        if (aValue.match(/^\d{4}-\d{2}-\d{2}$/) || aValue.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            const aDate = new Date(aValue);
            const bDate = new Date(bValue);
            if (!isNaN(aDate) && !isNaN(bDate)) {
                return direction === 'asc' ? aDate - bDate : bDate - aDate;
            }
        }

        // Check if values are numbers
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }

        // Sort as strings
        return direction === 'asc'
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });

    // Remove existing rows
    rows.forEach(row => tbody.removeChild(row));

    // Add sorted rows
    sortedRows.forEach(row => tbody.appendChild(row));
}

/**
 * Paginate a table
 * @param {HTMLElement} table - Table to paginate
 * @param {number} [rowsPerPage=10] - Number of rows per page
 */
function paginateTable(table, rowsPerPage = 10) {
    if (!table) {
        console.error('Table element not provided to paginateTable');
        return;
    }

    const tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error('Table body not found in table:', table);
        return;
    }

    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length === 0) {
        console.warn('No rows found in table to paginate');
        return;
    }

    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    if (totalPages <= 1) {
        console.info('Table has only one page, pagination not needed');
        return; // No need for pagination
    }

    let currentPage = 1;

    // Create pagination container
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination-container';
    paginationContainer.style.display = 'flex';
    paginationContainer.style.justifyContent = 'center';
    paginationContainer.style.margin = '1rem 0';
    paginationContainer.style.gap = '0.5rem';

    // Function to update table display
    function updateTable() {
        // Hide all rows
        rows.forEach(row => {
            row.style.display = 'none';
        });

        // Show rows for current page
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        for (let i = start; i < end && i < totalRows; i++) {
            rows[i].style.display = '';
        }

        // Update pagination buttons
        updatePaginationButtons();
    }

    // Function to update pagination buttons
    function updatePaginationButtons() {
        paginationContainer.innerHTML = '';

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.textContent = 'Previous';
        prevBtn.className = 'btn btn-sm btn-outline';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });
        paginationContainer.appendChild(prevBtn);

        // Page buttons
        const maxButtons = 5; // Max number of page buttons to show
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);

        if (endPage - startPage + 1 < maxButtons) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        // First page button if not showing first page
        if (startPage > 1) {
            const firstBtn = document.createElement('button');
            firstBtn.textContent = '1';
            firstBtn.className = 'btn btn-sm ' + (currentPage === 1 ? 'btn-primary' : 'btn-outline');
            firstBtn.addEventListener('click', () => {
                currentPage = 1;
                updateTable();
            });
            paginationContainer.appendChild(firstBtn);

            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.style.margin = '0 0.25rem';
                ellipsis.style.alignSelf = 'center';
                paginationContainer.appendChild(ellipsis);
            }
        }

        // Page buttons
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.textContent = i.toString();
            pageBtn.className = 'btn btn-sm ' + (i === currentPage ? 'btn-primary' : 'btn-outline');
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                updateTable();
            });
            paginationContainer.appendChild(pageBtn);
        }

        // Last page button if not showing last page
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.textContent = '...';
                ellipsis.style.margin = '0 0.25rem';
                ellipsis.style.alignSelf = 'center';
                paginationContainer.appendChild(ellipsis);
            }

            const lastBtn = document.createElement('button');
            lastBtn.textContent = totalPages.toString();
            lastBtn.className = 'btn btn-sm ' + (currentPage === totalPages ? 'btn-primary' : 'btn-outline');
            lastBtn.addEventListener('click', () => {
                currentPage = totalPages;
                updateTable();
            });
            paginationContainer.appendChild(lastBtn);
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Next';
        nextBtn.className = 'btn btn-sm btn-outline';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });
        paginationContainer.appendChild(nextBtn);
    }

    // Insert pagination after table
    table.parentNode.insertBefore(paginationContainer, table.nextSibling);

    // Initialize table
    updateTable();
}

/**
 * Initialize charts if Chart.js is available
 */
function initCharts() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not available');
        return;
    }

    // Set default Chart.js options for consistent styling
    Chart.defaults.font.family = getComputedStyle(document.documentElement).getPropertyValue('--font-family').trim();
    Chart.defaults.color = getComputedStyle(document.documentElement).getPropertyValue('--dark-grey').trim();
    Chart.defaults.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--grey-2').trim();

    // Custom chart elements
    const chartElements = document.querySelectorAll('[data-chart]');
    if (chartElements.length === 0) {
        return; // No charts to initialize
    }

    chartElements.forEach(element => {
        if (!element || !element.getContext) {
            console.error('Invalid chart element:', element);
            return;
        }

        const chartType = element.getAttribute('data-chart');
        if (!chartType) {
            console.error('Chart type not specified');
            return;
        }

        const chartContext = element.getContext('2d');

        // Check if data is provided as JSON
        const chartDataElement = element.nextElementSibling;
        if (chartDataElement && chartDataElement.getAttribute('type') === 'application/json') {
            try {
                const chartData = JSON.parse(chartDataElement.textContent);
                // Create chart with error handling
                try {
                    new Chart(chartContext, {
                        type: chartType,
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuad'
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'white',
                                    titleColor: '#1f2937',
                                    bodyColor: '#1f2937',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1,
                                    padding: 12,
                                    cornerRadius: 6,
                                    boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    });
                } catch (chartErr) {
                    console.error('Error creating chart:', chartErr);
                }
            } catch (e) {
                console.error('Error parsing chart data:', e);
            }
        } else {
            console.warn('No chart data found for element:', element);
        }
    });
}

/**
 * Function to update chart themes for dark mode
 */
function updateChartsTheme(isDark) {
    const chartInstances = Object.values(Chart.instances || {});
    if (chartInstances.length > 0) {
        chartInstances.forEach(chart => {
            // Update grid colors
            if (chart.options.scales && chart.options.scales.y) {
                chart.options.scales.y.grid.color = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                chart.options.scales.y.ticks.color = isDark ? '#a0a0a0' : '#9ca3af';
            }

            if (chart.options.scales && chart.options.scales.x) {
                chart.options.scales.x.ticks.color = isDark ? '#a0a0a0' : '#9ca3af';
            }

            // Update tooltip colors
            if (chart.options.plugins && chart.options.plugins.tooltip) {
                chart.options.plugins.tooltip.backgroundColor = isDark ? '#2a2a2a' : '#ffffff';
                chart.options.plugins.tooltip.titleColor = isDark ? '#e0e0e0' : '#1f2937';
                chart.options.plugins.tooltip.bodyColor = isDark ? '#e0e0e0' : '#1f2937';
                chart.options.plugins.tooltip.borderColor = isDark ? '#3a3a3a' : '#e5e7eb';
            }

            chart.update();
        });
    }
}

/**
 * User management functions
 */
function confirmDelete(userId, userName) {
    // Validate parameters
    if (!userId || !userName) {
        console.error('Missing required parameters for confirmDelete');
        return;
    }

    if (confirm(`Are you sure you want to delete user ${userName}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${BASE_URL}/dashboard/deleteUser`;
        form.style.display = 'none';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'userId';
        input.value = userId;

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleUserStatus(userId, currentStatus) {
    // Validate parameters
    if (!userId || !currentStatus) {
        console.error('Missing required parameters for toggleUserStatus');
        return;
    }

    if (currentStatus !== 'active' && currentStatus !== 'inactive') {
        console.error('Invalid status value. Expected "active" or "inactive"');
        return;
    }

    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    // Show loading state
    const buttonElement = document.querySelector(`button[onclick*="toggleUserStatus(${userId}"]`);
    if (buttonElement) {
        const originalText = buttonElement.innerHTML;
        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating...';

        setTimeout(() => {
            sendStatusUpdateRequest(userId, newStatus, buttonElement, originalText);
        }, 500);
    } else {
        sendStatusUpdateRequest(userId, newStatus);
    }
}

function sendStatusUpdateRequest(userId, newStatus, buttonElement = null, originalText = '') {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${BASE_URL}/dashboard/toggleUserStatus`, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Update UI
                    const statusElement = document.querySelector(`.user-status-${userId}`);
                    if (statusElement) {
                        statusElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        statusElement.className = `user-status-${userId} badge ${newStatus === 'active' ? 'badge-success' : 'badge-secondary'}`;
                    }

                    // Update button
                    if (buttonElement) {
                        buttonElement.textContent = newStatus === 'active' ? 'Deactivate' : 'Activate';
                        buttonElement.className = `btn btn-sm ${newStatus === 'active' ? 'btn-outline-secondary' : 'btn-outline-success'}`;
                        buttonElement.disabled = false;

                        // Show success animation
                        const icon = document.createElement('i');
                        icon.className = 'bx bx-check ml-1';
                        buttonElement.appendChild(icon);

                        setTimeout(() => {
                            if (buttonElement.contains(icon)) {
                                buttonElement.removeChild(icon);
                            }
                        }, 2000);
                    }

                    // Show toast notification
                    showToast(`User status updated to ${newStatus}`, 'success');
                } else {
                    if (buttonElement) {
                        buttonElement.innerHTML = originalText;
                        buttonElement.disabled = false;
                    }
                    showToast('Failed to update user status: ' + (response.message || 'Unknown error'), 'error');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                if (buttonElement) {
                    buttonElement.innerHTML = originalText;
                    buttonElement.disabled = false;
                }
                showToast('An error occurred while updating user status', 'error');
            }
        } else {
            console.error('XHR status error:', xhr.status);
            if (buttonElement) {
                buttonElement.innerHTML = originalText;
                buttonElement.disabled = false;
            }
            showToast('An error occurred while updating user status', 'error');
        }
    };

    xhr.onerror = function () {
        console.error('Network error occurred while updating user status');
        if (buttonElement) {
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
        }
        showToast('A network error occurred. Please try again later.', 'error');
    };

    xhr.send(`userId=${encodeURIComponent(userId)}&status=${encodeURIComponent(newStatus)}`);
}

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} [type='info'] - The type of toast (success, error, warning, info)
 * @param {number} [duration=3000] - How long to show the toast in ms
 */
function showToast(message, type = 'info', duration = 3000) {
    if (!message) {
        console.error('Toast message is required');
        return;
    }

    // Validate type
    if (!['success', 'error', 'warning', 'info'].includes(type)) {
        console.warn(`Invalid toast type: ${type}. Using 'info' as fallback.`);
        type = 'info';
    }

    // Check if toast container exists, create if not
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.backgroundColor = 'white';
    toast.style.color = '#333';
    toast.style.padding = '12px 16px';
    toast.style.borderRadius = '4px';
    toast.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    toast.style.marginBottom = '10px';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.minWidth = '250px';
    toast.style.maxWidth = '400px';
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(50px)';
    toast.style.transition = 'all 0.3s ease';
    toast.style.overflow = 'hidden';
    toast.style.wordBreak = 'break-word';

    // Type-specific colors
    const colors = {
        success: { main: '#10b981', bg: 'rgba(16, 185, 129, 0.1)' },
        error: { main: '#ef4444', bg: 'rgba(239, 68, 68, 0.1)' },
        warning: { main: '#f59e0b', bg: 'rgba(245, 158, 11, 0.1)' },
        info: { main: '#3b82f6', bg: 'rgba(59, 130, 246, 0.1)' }
    };

    // Apply type-specific styling
    toast.style.borderLeft = `4px solid ${colors[type].main}`;
    toast.style.backgroundColor = colors[type].bg;

    // Icon based on type
    const icon = document.createElement('i');
    icon.className = `bx ${type === 'success' ? 'bx-check-circle' :
        type === 'error' ? 'bx-x-circle' :
            type === 'warning' ? 'bx-error' :
                'bx-info-circle'
        }`;
    icon.style.marginRight = '12px';
    icon.style.fontSize = '20px';
    icon.style.color = colors[type].main;

    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    messageSpan.style.flex = '1';

    const closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.marginLeft = '8px';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.fontSize = '20px';
    closeBtn.style.opacity = '0.5';
    closeBtn.addEventListener('mouseenter', function () {
        this.style.opacity = '1';
    });
    closeBtn.addEventListener('mouseleave', function () {
        this.style.opacity = '0.5';
    });
    closeBtn.addEventListener('click', function () {
        removeToast(toast);
    });

    toast.appendChild(icon);
    toast.appendChild(messageSpan);
    toast.appendChild(closeBtn);

    // Limit number of toasts
    const maxToasts = 5;
    while (toastContainer.children.length >= maxToasts) {
        toastContainer.removeChild(toastContainer.firstChild);
    }

    toastContainer.appendChild(toast);

    // Animate toast in
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });
    });

    // Auto remove after duration
    const toastTimeout = setTimeout(() => {
        removeToast(toast);
    }, duration);

    // Store timeout in DOM to clear if manually closed
    toast.dataset.timeoutId = toastTimeout;

    return toast; // Return for potential further manipulation
}

/**
 * Remove a toast notification with animation
 * @param {HTMLElement} toast - The toast element to remove
 */
function removeToast(toast) {
    if (!toast) return;

    // Clear any existing timeout
    if (toast.dataset.timeoutId) {
        clearTimeout(parseInt(toast.dataset.timeoutId));
    }

    // Animate out
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(50px)';

    // Remove from DOM after animation completes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }

        // Remove container if empty
        const container = document.getElementById('toast-container');
        if (container && container.children.length === 0) {
            container.remove();
        }
    }, 300);
}