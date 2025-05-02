<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <!-- Include the sidebar -->
    <?php require APPROOT . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content -->
    <section id="content">
        <!-- Top Navigation -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#" id="support-search-form">
                <div class="form-input">
                    <input type="search" id="ticket-search" placeholder="Search tickets...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="profile">
                <img src="<?php echo URLROOT; ?>/public/images/default-profile.png">
            </a>
        </nav>

        <!-- Main Content Title -->
        <main>
            <?php flash('ticket_message'); ?>

            <div class="head-title">
                <div class="left">
                    <h1>Support Tickets</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="<?php echo URLROOT; ?>/dashboard/support">Support</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Support Ticket Stats -->
            <div class="box-info">
                <li>
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">
                        <h3 id="total-tickets"><?php echo $data['ticketStats']['total']; ?></h3>
                        <p>Total Tickets</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-hourglass-top'></i>
                    <span class="text">
                        <h3 id="open-tickets"><?php echo $data['ticketStats']['open']; ?></h3>
                        <p>Open Tickets</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-time-five'></i>
                    <span class="text">
                        <h3 id="pending-tickets"><?php echo $data['ticketStats']['pending']; ?></h3>
                        <p>Pending Tickets</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-check-circle'></i>
                    <span class="text">
                        <h3 id="answered-tickets"><?php echo $data['ticketStats']['answered']; ?></h3>
                        <p>Answered Tickets</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-x-circle'></i>
                    <span class="text">
                        <h3 id="closed-tickets"><?php echo $data['ticketStats']['closed']; ?></h3>
                        <p>Closed Tickets</p>
                    </span>
                </li>
            </div>

            <!-- Ticket Management Controls -->
            <div class="ticket-controls">
                <div class="control-row">
                    <div class="filter-group">
                        <label for="priority-filter">Priority:</label>
                        <select id="priority-filter" class="form-select form-select-sm">
                            <option value="all">All</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date-filter">Date Range:</label>
                        <select id="date-filter" class="form-select form-select-sm">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                    <div class="ms-auto">
                        <a href="<?php echo URLROOT; ?>/dashboard/faq" class="btn btn-primary"><i class='bx bx-question-mark'></i> Manage FAQs</a>
                    </div>
                </div>
            </div>

            <!-- Ticket Management Tabs -->
            <div class="ticket-management-tabs">
                <ul class="nav nav-tabs" id="ticketTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tickets-tab" data-bs-toggle="tab" data-bs-target="#all-tickets" type="button" role="tab" aria-controls="all-tickets" aria-selected="true">All Tickets</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="open-tickets-tab" data-bs-toggle="tab" data-bs-target="#open-tickets-tab-content" type="button" role="tab" aria-controls="open-tickets-tab-content" aria-selected="false">Open</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tickets-tab" data-bs-toggle="tab" data-bs-target="#pending-tickets-tab-content" type="button" role="tab" aria-controls="pending-tickets-tab-content" aria-selected="false">Pending</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="answered-tickets-tab" data-bs-toggle="tab" data-bs-target="#answered-tickets-tab-content" type="button" role="tab" aria-controls="answered-tickets-tab-content" aria-selected="false">Answered</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="closed-tickets-tab" data-bs-toggle="tab" data-bs-target="#closed-tickets-tab-content" type="button" role="tab" aria-controls="closed-tickets-tab-content" aria-selected="false">Closed</button>
                    </li>
                </ul>

                <div class="tab-content" id="ticketTabsContent">
                    <!-- All Tickets Tab -->
                    <div class="tab-pane fade show active" id="all-tickets" role="tabpanel" aria-labelledby="all-tickets-tab">
                        <div class="table-data">
                            <div class="order">
                                <div class="head">
                                    <h3>All Support Tickets</h3>
                                    <div class="refresh-container">
                                        <button id="refresh-tickets" class="btn btn-sm btn-light" title="Refresh Tickets"><i class='bx bx-refresh'></i></button>
                                    </div>
                                </div>
                                <div class="ticket-table-container">
                                    <table class="ticket-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Subject</th>
                                                <th>Category</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Last Updated</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($data['tickets'])) : ?>
                                                <?php foreach ($data['tickets'] as $ticket) : ?>
                                                    <?php
                                                    // Support both object and array access
                                                    $ticketId = is_array($ticket) ? $ticket['id'] : $ticket->id;
                                                    $userName = is_array($ticket) ? $ticket['userName'] : (isset($ticket->userName) ? $ticket->userName : $ticket->user_name);
                                                    $userEmail = is_array($ticket) ? $ticket['userEmail'] : (isset($ticket->userEmail) ? $ticket->userEmail : $ticket->user_email);
                                                    $subject = is_array($ticket) ? $ticket['subject'] : $ticket->subject;
                                                    $category = is_array($ticket) ? (isset($ticket['category']) ? $ticket['category'] : '') : (isset($ticket->category) ? $ticket->category : '');
                                                    $priority = is_array($ticket) ? $ticket['priority'] : $ticket->priority;
                                                    $status = is_array($ticket) ? $ticket['status'] : $ticket->status;
                                                    $createdAt = is_array($ticket) ? $ticket['createdAt'] : (isset($ticket->createdAt) ? $ticket->createdAt : $ticket->created_at);
                                                    $updatedAt = is_array($ticket) ? $ticket['updatedAt'] : (isset($ticket->updatedAt) ? $ticket->updatedAt : $ticket->updated_at);
                                                    ?>
                                                    <tr data-priority="<?php echo htmlspecialchars($priority); ?>" data-date="<?php echo date('Y-m-d', strtotime($createdAt)); ?>">
                                                        <td>#<?php echo $ticketId; ?></td>
                                                        <td>
                                                            <p><?php echo htmlspecialchars($userName); ?></p>
                                                            <small><?php echo htmlspecialchars($userEmail); ?></small>
                                                        </td>
                                                        <td class="ticket-subject"><?php echo htmlspecialchars($subject); ?></td>
                                                        <td><?php echo $category ? ucfirst(htmlspecialchars($category)) : 'General'; ?></td>
                                                        <td>
                                                            <?php
                                                            $priorityClass = '';
                                                            switch ($priority) {
                                                                case 'high':
                                                                case 'critical':
                                                                    $priorityClass = 'status-high';
                                                                    break;
                                                                case 'medium':
                                                                    $priorityClass = 'status-medium';
                                                                    break;
                                                                case 'low':
                                                                    $priorityClass = 'status-low';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="status <?php echo $priorityClass; ?>"><?php echo ucfirst(htmlspecialchars($priority)); ?></span>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $statusClass = '';
                                                            switch ($status) {
                                                                case 'open':
                                                                    $statusClass = 'status-open';
                                                                    break;
                                                                case 'pending':
                                                                    $statusClass = 'status-pending';
                                                                    break;
                                                                case 'answered':
                                                                    $statusClass = 'status-answered';
                                                                    break;
                                                                case 'closed':
                                                                    $statusClass = 'status-closed';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="status <?php echo $statusClass; ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></span>
                                                        </td>
                                                        <td><?php echo date('M j, Y', strtotime($createdAt)); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($updatedAt)); ?></td>
                                                        <td>
                                                            <div class="actions">
                                                                <a href="<?php echo URLROOT; ?>/dashboard/viewTicket/<?php echo $ticketId; ?>" class="btn btn-sm btn-info" title="View Ticket"><i class='bx bx-show'></i></a>
                                                                <div class="dropdown d-inline-block">
                                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $ticketId; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class='bx bx-dots-vertical-rounded'></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $ticketId; ?>">
                                                                        <?php if ($status == 'open') : ?>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/pending">Mark as Pending</a></li>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/answered">Mark as Answered</a></li>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/closed">Close Ticket</a></li>
                                                                        <?php elseif ($status == 'pending' || $status == 'answered') : ?>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/open">Mark as Open</a></li>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/closed">Close Ticket</a></li>
                                                                        <?php elseif ($status == 'closed') : ?>
                                                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/open">Reopen Ticket</a></li>
                                                                        <?php endif; ?>
                                                                        <li>
                                                                            <hr class="dropdown-divider">
                                                                        </li>
                                                                        <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/dashboard/deleteTicket/<?php echo $ticketId; ?>" onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.');">Delete Ticket</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No tickets found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="no-results" class="no-results-container" style="display: none;">
                                    <div class="no-results-content">
                                        <i class='bx bx-search-alt'></i>
                                        <h4>No matching tickets found</h4>
                                        <p>Try adjusting your search or filter criteria</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other tabs will be populated via JavaScript -->
                    <div class="tab-pane fade" id="open-tickets-tab-content" role="tabpanel" aria-labelledby="open-tickets-tab"></div>
                    <div class="tab-pane fade" id="pending-tickets-tab-content" role="tabpanel" aria-labelledby="pending-tickets-tab"></div>
                    <div class="tab-pane fade" id="answered-tickets-tab-content" role="tabpanel" aria-labelledby="answered-tickets-tab"></div>
                    <div class="tab-pane fade" id="closed-tickets-tab-content" role="tabpanel" aria-labelledby="closed-tickets-tab"></div>
                </div>
            </div>
        </main>
    </section>
</div>

<!-- Support Dashboard Styles -->
<style>
    /* Status Colors */
    .status-high {
        background: #ff6b6b;
    }

    .status-medium {
        background: #feca57;
    }

    .status-low {
        background: #1dd1a1;
    }

    .status-open {
        background: #54a0ff;
    }

    .status-pending {
        background: #ff9f43;
    }

    .status-answered {
        background: #5f27cd;
    }

    .status-closed {
        background: #576574;
    }

    /* Ticket Controls */
    .ticket-controls {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .control-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group label {
        margin-bottom: 0;
    }

    /* Ticket Management Tabs */
    .ticket-management-tabs {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .ticket-management-tabs .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 20px;
    }

    .ticket-management-tabs .nav-link {
        color: #333;
        font-weight: 500;
        border: none;
        padding: 10px 15px;
        border-radius: 0;
        position: relative;
    }

    .ticket-management-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom: 2px solid var(--primary);
    }

    .ticket-management-tabs .nav-link:hover {
        color: var(--primary);
    }

    /* Table Styling */
    .ticket-table-container {
        overflow-x: auto;
        max-width: 100%;
    }

    .ticket-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ticket-table th {
        position: sticky;
        top: 0;
        background-color: #f9f9f9;
        z-index: 10;
    }

    .ticket-subject {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Actions Styling */
    .actions {
        display: flex;
        gap: 5px;
    }

    .actions .btn {
        padding: 0.25rem 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .actions .btn i {
        font-size: 1.2rem;
    }

    /* Refresh button styling */
    .refresh-container {
        display: flex;
        align-items: center;
    }

    #refresh-tickets {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #refresh-tickets i {
        font-size: 1.2rem;
    }

    /* No results styling */
    .no-results-container {
        padding: 2rem;
        text-align: center;
    }

    .no-results-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .no-results-content i {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 1rem;
    }

    .no-results-content h4 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        color: #666;
    }

    .no-results-content p {
        color: #888;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        .control-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-group {
            margin-bottom: 10px;
            width: 100%;
        }

        .filter-group select {
            width: 100%;
        }
    }
</style>

<!-- Support Dashboard Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter tickets by status when clicking on tabs
        const ticketTabs = document.querySelectorAll('#ticketTabs .nav-link');

        ticketTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const status = this.textContent.toLowerCase();
                if (status !== 'all tickets') {
                    filterTicketsByStatus(status);
                }
            });
        });

        function filterTicketsByStatus(status) {
            // Clone the all tickets table
            const allTicketsTable = document.querySelector('#all-tickets table').cloneNode(true);
            const tbody = allTicketsTable.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr');

            // Clear existing rows
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Filter rows by status
            let filteredRows = [];
            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(6) .status');
                if (statusCell && statusCell.textContent.toLowerCase().trim() === status) {
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
                noResultsCell.textContent = 'No ' + status + ' tickets found';
                noResultsRow.appendChild(noResultsCell);
                tbody.appendChild(noResultsRow);
            }

            // Update the tab content
            const tabContent = document.getElementById(status + '-tickets-tab-content');
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
                    <button class="refresh-tab btn btn-sm btn-light" title="Refresh Tickets"><i class='bx bx-refresh'></i></button>
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
            refreshButton.addEventListener('click', function() {
                filterTicketsByStatus(status);
            });
        }

        // Search functionality
        const searchForm = document.getElementById('support-search-form');
        const searchInput = document.getElementById('ticket-search');
        const noResults = document.getElementById('no-results');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            searchTickets();
        });

        searchInput.addEventListener('input', searchTickets);

        function searchTickets() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const allRows = document.querySelectorAll('#all-tickets tbody tr');
            const activeTabContent = document.querySelector('.tab-pane.active');
            let visibleCount = 0;

            allRows.forEach(row => {
                const ticketId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const userName = row.querySelector('td:nth-child(2) p').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(2) small').textContent.toLowerCase();
                const subject = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const category = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

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
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
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
                    if (row.style.display !== 'none') tabVisibleCount++;
                });

                tabNoResults.style.display = tabVisibleCount === 0 ? 'block' : 'none';
            }
        }

        // Priority filter functionality
        const priorityFilter = document.getElementById('priority-filter');
        priorityFilter.addEventListener('change', applyFilters);

        // Date filter functionality
        const dateFilter = document.getElementById('date-filter');
        dateFilter.addEventListener('change', applyFilters);

        function applyFilters() {
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

            allRows.forEach(row => {
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
                const userName = row.querySelector('td:nth-child(2) p').textContent.toLowerCase();
                const userEmail = row.querySelector('td:nth-child(2) small').textContent.toLowerCase();
                const subject = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const category = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

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

            // Show/hide no results message
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';

            // Apply filters to other tabs as well
            document.querySelectorAll('#ticketTabs .nav-link.active').forEach(tab => {
                const status = tab.textContent.toLowerCase().trim();
                if (status !== 'all tickets') {
                    applyFiltersToTab(status);
                }
            });
        }

        function applyFiltersToTab(status) {
            const tabContent = document.getElementById(status + '-tickets-tab-content');
            const tabRows = tabContent.querySelectorAll('tbody tr');
            let visibleCount = 0;

            tabRows.forEach(row => {
                if (row.style.display !== 'none') visibleCount++;
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

        // Refresh tickets functionality
        document.getElementById('refresh-tickets').addEventListener('click', function() {
            location.reload();
        });

        // Real-time ticket statistics update
        function updateTicketStats() {
            fetch('<?php echo URLROOT; ?>/dashboard/getTicketStats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-tickets').textContent = data.total;
                    document.getElementById('open-tickets').textContent = data.open;
                    document.getElementById('pending-tickets').textContent = data.pending;
                    document.getElementById('answered-tickets').textContent = data.answered;
                    document.getElementById('closed-tickets').textContent = data.closed;
                })
                .catch(error => console.error('Error fetching ticket stats:', error));
        }

        // Update stats every 30 seconds
        setInterval(updateTicketStats, 30000);

        // Initially populate all tabs
        ['open', 'pending', 'answered', 'closed'].forEach(status => {
            filterTicketsByStatus(status);
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>