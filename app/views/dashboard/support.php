<?php

/**
 * Support Tickets Management Dashboard
 * This file displays the support tickets management interface in the admin dashboard
 */

// Set content to be passed to dashboard layout
ob_start();
?>

<!-- Support Management Header -->
<div class="head-title">
    <div class="left">
        <h1>Support Tickets</h1>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="<?php echo URL_ROOT; ?>/dashboard/support">Support</a>
            </li>
        </ul>
    </div>
    <div class="btn-download">
        <a href="<?php echo URL_ROOT; ?>/dashboard/faq" class="btn-primary">
            <i class='bx bx-question-mark'></i>
            <span class="text">Manage FAQs</span>
        </a>
    </div>
</div>

<!-- Dashboard Main Container -->
<div class="dashboard-main-container">
    <?php flash('ticket_message'); ?>

    <!-- Support Ticket Stats -->
    <ul class="box-info">
        <li class="stat-card">
            <i class='bx bxs-message-dots'></i>
            <span class="text">
                <h3 id="total-tickets"><?php echo $data['ticketStats']['total']; ?></h3>
                <p>Total Tickets</p>
            </span>
        </li>
        <li class="stat-card open">
            <i class='bx bxs-hourglass-top'></i>
            <span class="text">
                <h3 id="open-tickets"><?php echo $data['ticketStats']['open']; ?></h3>
                <p>Open Tickets</p>
            </span>
        </li>
        <li class="stat-card pending">
            <i class='bx bxs-time-five'></i>
            <span class="text">
                <h3 id="pending-tickets"><?php echo $data['ticketStats']['pending']; ?></h3>
                <p>Pending Tickets</p>
            </span>
        </li>
        <li class="stat-card answered">
            <i class='bx bxs-check-circle'></i>
            <span class="text">
                <h3 id="answered-tickets"><?php echo $data['ticketStats']['answered']; ?></h3>
                <p>Answered Tickets</p>
            </span>
        </li>
        <li class="stat-card closed">
            <i class='bx bxs-x-circle'></i>
            <span class="text">
                <h3 id="closed-tickets"><?php echo $data['ticketStats']['closed']; ?></h3>
                <p>Closed Tickets</p>
            </span>
        </li>
    </ul>

    <!-- Ticket Management Controls -->
    <div class="ticket-controls">
        <div class="control-row">
            <div class="search-container">
                <div class="form-input">
                    <input type="search" id="ticket-search" placeholder="Search tickets by ID, user, subject...">
                    <button type="button" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </div>
            <div class="filter-wrapper">
                <div class="filter-group">
                    <label for="priority-filter">Priority:</label>
                    <select id="priority-filter" class="form-select">
                        <option value="all">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date-filter">Date Range:</label>
                    <select id="date-filter" class="form-select">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
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
                                <button id="refresh-tickets" class="btn btn-light" title="Refresh Tickets">
                                    <i class='bx bx-refresh'></i> Refresh
                                </button>
                            </div>
                        </div>
                        <div class="ticket-table-container">
                            <table class="ticket-table">
                                <thead>
                                    <tr>
                                        <th class="sortable" data-sort="id">ID <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="user">User <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="subject">Subject <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="category">Category <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="priority">Priority <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="status">Status <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="created">Created <i class='bx bx-sort-alt-2'></i></th>
                                        <th class="sortable" data-sort="updated">Last Updated <i class='bx bx-sort-alt-2'></i></th>
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
                                            <tr data-priority="<?php echo htmlspecialchars($priority); ?>" data-date="<?php echo date('Y-m-d', strtotime($createdAt)); ?>" data-status="<?php echo htmlspecialchars($status); ?>" data-category="<?php echo htmlspecialchars($category); ?>">
                                                <td>#<?php echo $ticketId; ?></td>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar">
                                                            <?php
                                                            $initials = strtoupper(substr($userName, 0, 1));
                                                            $nameParts = explode(' ', $userName);
                                                            if (count($nameParts) > 1) {
                                                                $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                                            }
                                                            echo $initials;
                                                            ?>
                                                        </div>
                                                        <div class="user-details">
                                                            <p class="user-name"><?php echo htmlspecialchars($userName); ?></p>
                                                            <small class="user-email"><?php echo htmlspecialchars($userEmail); ?></small>
                                                        </div>
                                                    </div>
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
                                                    <span class="priority-badge <?php echo $priorityClass; ?>"><?php echo ucfirst(htmlspecialchars($priority)); ?></span>
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
                                                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($createdAt)); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($updatedAt)); ?></td>
                                                <td class="actions">
                                                    <div class="action-buttons">
                                                        <a href="<?php echo URL_ROOT; ?>/dashboard/viewTicket/<?php echo $ticketId; ?>" class="btn btn-sm btn-primary action-btn view-ticket" title="View Ticket">
                                                            <i class='bx bx-show'></i>
                                                        </a>
                                                        <div class="dropdown d-inline-block">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle action-btn" type="button" id="dropdownMenuButton<?php echo $ticketId; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class='bx bx-dots-vertical-rounded'></i>
                                                            </button>
                                                            <ul class="dropdown-menu action-menu" aria-labelledby="dropdownMenuButton<?php echo $ticketId; ?>">
                                                                <?php if ($status == 'open') : ?>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/pending"><i class='bx bx-time'></i> Mark as Pending</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/answered"><i class='bx bx-check-circle'></i> Mark as Answered</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/closed"><i class='bx bx-x-circle'></i> Close Ticket</a></li>
                                                                <?php elseif ($status == 'pending') : ?>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/open"><i class='bx bx-revision'></i> Mark as Open</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/answered"><i class='bx bx-check-circle'></i> Mark as Answered</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/closed"><i class='bx bx-x-circle'></i> Close Ticket</a></li>
                                                                <?php elseif ($status == 'answered') : ?>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/open"><i class='bx bx-revision'></i> Mark as Open</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/pending"><i class='bx bx-time'></i> Mark as Pending</a></li>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/closed"><i class='bx bx-x-circle'></i> Close Ticket</a></li>
                                                                <?php elseif ($status == 'closed') : ?>
                                                                    <li><a class="dropdown-item status-change" href="<?php echo URL_ROOT; ?>/dashboard/updateTicketStatus/<?php echo $ticketId; ?>/open"><i class='bx bx-revision'></i> Reopen Ticket</a></li>
                                                                <?php endif; ?>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li><a class="dropdown-item delete-ticket" href="<?php echo URL_ROOT; ?>/dashboard/deleteTicket/<?php echo $ticketId; ?>"><i class='bx bx-trash'></i> Delete Ticket</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- No results message container -->
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
</div>

<!-- Support Dashboard Styles -->
<style>
    /* Status Colors */
    .status-high {
        background: #ff6b6b;
        color: #fff;
    }

    .status-medium {
        background: #feca57;
        color: #333;
    }

    .status-low {
        background: #1dd1a1;
        color: #fff;
    }

    .status-open {
        background: #54a0ff;
        color: #fff;
    }

    .status-pending {
        background: #ff9f43;
        color: #fff;
    }

    .status-answered {
        background: #5f27cd;
        color: #fff;
    }

    .status-closed {
        background: #576574;
        color: #fff;
    }

    /* Badge styling for status and priority */
    .status-badge,
    .priority-badge {
        padding: 5px 10px;
        border-radius: 20px;
        display: inline-block;
        font-size: 0.85em;
        font-weight: 500;
        text-align: center;
        min-width: 80px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .status-badge:hover,
    .priority-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    /* Loading spinner styling */
    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        color: var(--primary);
    }

    .loading-spinner i {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .loading-spinner span {
        font-size: 1rem;
        color: #6c757d;
    }

    /* Highlight effect for refreshed content */
    @keyframes highlight {
        0% {
            background-color: rgba(var(--primary-rgb), 0.2);
        }

        100% {
            background-color: transparent;
        }
    }

    .highlight-update {
        animation: highlight 2s ease-out;
    }

    /* Stat Cards */
    .box-info li.stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
    }

    .box-info li.stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .box-info li.stat-card.open i {
        color: #54a0ff;
    }

    .box-info li.stat-card.pending i {
        color: #ff9f43;
    }

    .box-info li.stat-card.answered i {
        color: #5f27cd;
    }

    .box-info li.stat-card.closed i {
        color: #576574;
    }

    .box-info li.stat-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background-color: transparent;
    }

    .box-info li.stat-card.open::before {
        background-color: #54a0ff;
    }

    .box-info li.stat-card.pending::before {
        background-color: #ff9f43;
    }

    .box-info li.stat-card.answered::before {
        background-color: #5f27cd;
    }

    .box-info li.stat-card.closed::before {
        background-color: #576574;
    }

    .stat-update {
        animation: pulse 1s ease-in-out;
    }

    @keyframes pulse {
        0% {
            background-color: rgba(0, 123, 255, 0);
        }

        50% {
            background-color: rgba(0, 123, 255, 0.1);
        }

        100% {
            background-color: rgba(0, 123, 255, 0);
        }
    }

    /* Ticket Controls */
    .ticket-controls {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .control-row {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search-container {
        flex: 2;
        min-width: 250px;
    }

    .filter-wrapper {
        flex: 1;
        display: flex;
        gap: 15px;
        min-width: 250px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .filter-group label {
        font-weight: 500;
        white-space: nowrap;
        margin-bottom: 0;
        color: var(--dark);
    }

    .form-input {
        position: relative;
        width: 100%;
    }

    .form-input input {
        width: 100%;
        height: 40px;
        padding: 10px 40px 10px 15px;
        border-radius: 20px;
        border: 1px solid var(--grey);
        transition: all 0.3s ease;
    }

    .form-input input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    /* Animate the search input when searching */
    .form-input input.searching {
        background-color: rgba(var(--primary-rgb), 0.05);
    }

    .form-select {
        padding: 8px 12px;
        border-radius: 5px;
        border: 1px solid var(--grey);
        background-color: #fff;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    /* Animate the select when filtering */
    .form-select.filtering {
        background-color: rgba(var(--primary-rgb), 0.05);
    }

    .search-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-grey);
        cursor: pointer;
        font-size: 18px;
        transition: color 0.3s ease;
    }

    .search-btn:hover {
        color: var(--primary);
    }

    /* Ticket Management Tabs */
    .ticket-management-tabs {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .ticket-management-tabs .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 25px;
        display: flex;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .ticket-management-tabs .nav-tabs::-webkit-scrollbar {
        height: 3px;
    }

    .ticket-management-tabs .nav-tabs::-webkit-scrollbar-thumb {
        background-color: var(--grey);
        border-radius: 10px;
    }

    .ticket-management-tabs .nav-link {
        color: #6c757d;
        font-weight: 500;
        border: none;
        padding: 12px 20px;
        border-radius: 0;
        position: relative;
        white-space: nowrap;
    }

    .ticket-management-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom: 2px solid var(--primary);
        font-weight: 600;
    }

    .ticket-management-tabs .nav-link:hover:not(.active) {
        color: var(--primary);
        background-color: rgba(0, 123, 255, 0.05);
    }

    /* Table Styling */
    .ticket-table-container {
        overflow-x: auto;
        max-width: 100%;
        border-radius: 5px;
    }

    .ticket-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .ticket-table th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--dark);
        border-bottom: 2px solid #dee2e6;
    }

    .ticket-table th.sortable {
        cursor: pointer;
        user-select: none;
    }

    .ticket-table th.sortable:hover {
        background-color: #e9ecef;
    }

    .ticket-table th.sortable i {
        font-size: 14px;
        margin-left: 5px;
        transition: transform 0.2s;
    }

    .ticket-table th.sorting-asc i {
        transform: rotate(180deg);
    }

    .ticket-table td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .ticket-table tbody tr {
        transition: all 0.2s ease;
    }

    .ticket-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .ticket-subject {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* User info styling */
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 500;
        margin: 0;
        line-height: 1.2;
    }

    .user-email {
        color: #6c757d;
        font-size: 0.85em;
    }

    /* Actions Styling */
    .actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 8px;
        height: 36px;
        width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .action-btn i {
        font-size: 18px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-menu .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
    }

    .action-menu .dropdown-item i {
        font-size: 16px;
    }

    /* Refresh button styling */
    .refresh-container {
        display: flex;
        align-items: center;
    }

    #refresh-tickets,
    .refresh-tab {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    #refresh-tickets:hover,
    .refresh-tab:hover {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    #refresh-tickets.refreshing,
    .refresh-tab.refreshing {
        background-color: var(--primary);
        color: white;
        opacity: 0.8;
        cursor: not-allowed;
    }

    /* Table header */
    .table-data .head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .table-data .head h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    /* Empty state styling */
    .empty-state {
        padding: 30px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 50px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 16px;
        margin: 0;
    }

    /* No results styling */
    .no-results-container {
        padding: 3rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 20px;
    }

    .no-results-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .no-results-content i {
        font-size: 4rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }

    .no-results-content h4 {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .no-results-content p {
        color: #6c757d;
    }

    /* Button styles */
    .btn-primary {
        background-color: var(--primary);
        color: white;
        padding: 8px 16px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media screen and (max-width: 992px) {
        .control-row {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container {
            margin-bottom: 15px;
        }

        .filter-wrapper {
            width: 100%;
        }
    }

    @media screen and (max-width: 768px) {
        .filter-wrapper {
            flex-direction: column;
        }

        .filter-group {
            width: 100%;
            margin-bottom: 10px;
        }

        .ticket-table th,
        .ticket-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
        }

        .ticket-management-tabs {
            padding: 15px;
        }
    }
</style>

<!-- Load Support JS -->
<script>
    // Define base URL for API endpoints - needed for support.js
    window.BASE_URL = '<?php echo URL_ROOT; ?>';
</script>
<script src="<?php echo URL_ROOT; ?>/public/js/support/support.js"></script>

<!-- Initialize table sorting -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize table sorting
        initTableSorting();
    });
</script>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>