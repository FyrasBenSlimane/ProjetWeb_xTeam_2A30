/**
 * Dashboard JavaScript functionality
 * Handles sidebar toggling and other dashboard interactions
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize dashboard components
    initSidebar();
    initNotifications();
    initDataTables();
    initCharts();
});

/**
 * Initialize sidebar functionality
 */
function initSidebar() {
    // Get sidebar element
    const sidebar = document.getElementById('sidebar');

    // If sidebar toggle button exists, add event listener
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('hide');

            // Save sidebar state to session via AJAX
            const isOpen = !sidebar.classList.contains('hide');
            saveSidebarState(isOpen);
        });
    }

    // Handle sidebar menu active items
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('#sidebar .side-menu li a');

    menuItems.forEach(item => {
        if (currentPath.includes(item.getAttribute('href'))) {
            item.parentElement.classList.add('active');
        }
    });
}

/**
 * Save sidebar state to session
 * @param {boolean} isOpen - Whether sidebar is open
 */
function saveSidebarState(isOpen) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${window.location.origin}/dashboard/toggleSidebar`, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`isOpen=${isOpen ? 1 : 0}`);
}

/**
 * Initialize notifications functionality
 */
function initNotifications() {
    // Close notification when close button is clicked
    const notificationCloseButtons = document.querySelectorAll('.notification .close');
    if (notificationCloseButtons) {
        notificationCloseButtons.forEach(button => {
            button.addEventListener('click', function () {
                const notification = this.closest('.notification');
                notification.classList.add('fade-out');

                setTimeout(() => {
                    notification.remove();
                }, 300);
            });
        });
    }
}

/**
 * Initialize data tables if they exist
 */
function initDataTables() {
    const tables = document.querySelectorAll('.data-table');
    if (tables.length > 0) {
        tables.forEach(table => {
            // Add sorting functionality to table headers
            const headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                header.addEventListener('click', function () {
                    const column = this.getAttribute('data-sort');
                    const direction = this.classList.contains('asc') ? 'desc' : 'asc';

                    // Remove sort classes from all headers
                    headers.forEach(h => h.classList.remove('asc', 'desc'));

                    // Add sort class to current header
                    this.classList.add(direction);

                    // Sort the table
                    sortTable(table, column, direction);
                });
            });
        });
    }
}

/**
 * Sort a table by column
 * @param {HTMLElement} table - Table to sort
 * @param {string} column - Column to sort by
 * @param {string} direction - Sort direction ('asc' or 'desc')
 */
function sortTable(table, column, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // Get column index
    const headerRow = table.querySelector('thead tr');
    const headers = Array.from(headerRow.querySelectorAll('th'));
    const columnIndex = headers.findIndex(header => header.getAttribute('data-sort') === column);

    // Sort rows
    const sortedRows = rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();

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
 * Initialize charts if Chart.js is available
 */
function initCharts() {
    if (typeof Chart !== 'undefined') {
        // Sample charts initialization
        const chartElements = document.querySelectorAll('[data-chart]');

        chartElements.forEach(element => {
            const chartType = element.getAttribute('data-chart');
            const chartContext = element.getContext('2d');

            // Check if data is provided as JSON
            const chartDataElement = element.nextElementSibling;
            if (chartDataElement && chartDataElement.getAttribute('type') === 'application/json') {
                try {
                    const chartData = JSON.parse(chartDataElement.textContent);
                    // Create chart
                    new Chart(chartContext, {
                        type: chartType,
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                } catch (e) {
                    console.error('Error parsing chart data:', e);
                }
            }
        });
    }
}

/**
 * User management functions
 */
function confirmDelete(userId, userName) {
    if (confirm(`Are you sure you want to delete user ${userName}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${window.location.origin}/dashboard/deleteUser`;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'userId';
        input.value = userId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${window.location.origin}/dashboard/toggleUserStatus`, true);
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
                        statusElement.className = `user-status-${userId} badge ${newStatus === 'active' ? 'bg-success' : 'bg-secondary'}`;
                    }

                    // Update button text
                    const buttonElement = document.querySelector(`button[onclick*="toggleUserStatus(${userId}"]`);
                    if (buttonElement) {
                        buttonElement.textContent = newStatus === 'active' ? 'Deactivate' : 'Activate';
                        buttonElement.className = `btn btn-sm ${newStatus === 'active' ? 'btn-outline-secondary' : 'btn-outline-success'}`;
                    }
                } else {
                    alert('Failed to update user status: ' + response.message);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                alert('An error occurred while updating user status');
            }
        } else {
            alert('An error occurred while updating user status');
        }
    };

    xhr.send(`userId=${userId}&status=${newStatus}`);
}