<?php
// Support - My Tickets page
// This page displays all support tickets created by the user

// Extract data passed from controller
if (isset($data['tickets'])) {
    $tickets = $data['tickets'];
}
?>

<div class="tickets-container">
    <?php flash('error_message'); ?>
    <?php flash('ticket_message'); ?>

    <!-- Add Ticket Status Tracker at the top of the page -->
    <div class="ticket-tracking-overview">
        <div class="tracking-header">
            <h2>Ticket Status Overview</h2>
            <p>Track the status of all your support tickets</p>
        </div>

        <div class="status-cards">
            <div class="status-card" data-status="open">
                <div class="status-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="status-details">
                    <h3 class="status-count" id="open-count">0</h3>
                    <p class="status-label">Open</p>
                </div>
            </div>

            <div class="status-card" data-status="awaiting-response">
                <div class="status-icon">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="status-details">
                    <h3 class="status-count" id="awaiting-count">0</h3>
                    <p class="status-label">Awaiting Response</p>
                </div>
            </div>

            <div class="status-card" data-status="in-progress">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-details">
                    <h3 class="status-count" id="progress-count">0</h3>
                    <p class="status-label">In Progress</p>
                </div>
            </div>

            <div class="status-card" data-status="resolved">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-details">
                    <h3 class="status-count" id="resolved-count">0</h3>
                    <p class="status-label">Resolved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add notification preference panel -->
    <div class="notification-preferences">
        <div class="notification-header">
            <h3>Notification Preferences</h3>
            <button id="toggle-notifications" class="toggle-button">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>

        <div id="notification-options" class="notification-options hidden">
            <div class="notification-option">
                <div class="option-label">
                    <i class="fas fa-bell"></i>
                    <span>Email Notifications</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="email-notifications" checked>
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="notification-option">
                <div class="option-label">
                    <i class="fas fa-mobile-alt"></i>
                    <span>SMS Notifications</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="sms-notifications">
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="notification-option">
                <div class="option-label">
                    <i class="fas fa-desktop"></i>
                    <span>Browser Notifications</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="browser-notifications" checked>
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="notification-settings">
                <h4>Notify me when:</h4>
                <div class="notification-checkboxes">
                    <label class="checkbox-container">
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                        A ticket's status changes
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                        Support responds to my ticket
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                        A ticket is close to its SLA deadline
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox">
                        <span class="checkmark"></span>
                        A new announcement is posted
                    </label>
                </div>
                <button id="save-notifications" class="save-button">Save Preferences</button>
            </div>
        </div>
    </div>

    <div class="tickets-header">
        <h1>My Support Tickets</h1>
        <p>View and manage your support requests</p>
    </div>

    <div class="tickets-controls">
        <div class="tickets-filter">
            <label for="ticket-status-filter">Filter by status:</label>
            <select id="ticket-status-filter" class="filter-select">
                <option value="all">All Tickets</option>
                <option value="open">Open</option>
                <option value="in-progress">In Progress</option>
                <option value="awaiting-response">Awaiting Your Response</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div class="tickets-search">
            <input type="text" id="ticket-search" class="search-input" placeholder="Search by subject or ID...">
            <button id="search-btn" class="search-btn"><i class="fas fa-search"></i></button>
        </div> <a href="<?php echo URL_ROOT; ?>/support/newTicket" class="new-ticket-btn">
            <i class="fas fa-plus"></i> New Ticket
        </a>
    </div> <!-- Display user tickets from the database -->
    <div class="tickets-list">
        <?php if (empty($tickets) || count($tickets) === 0): // No tickets found 
        ?>
            <div class="no-tickets">
                <div class="no-tickets-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>No tickets found</h3>
                <p>You haven't created any support tickets yet.</p>
                <a href="<?php echo URL_ROOT; ?>/support/newTicket" class="create-ticket-btn">Create Your First Ticket</a>
            </div>
        <?php else: // Display actual tickets from database 
        ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-card" data-status="<?php echo htmlspecialchars($ticket->status); ?>">
                    <div class="ticket-info">
                        <div class="ticket-title-row">
                            <h3 class="ticket-subject"><?php echo htmlspecialchars($ticket->subject); ?></h3>
                            <span class="ticket-status <?php echo htmlspecialchars($ticket->status); ?>"><?php echo ucfirst(str_replace('-', ' ', htmlspecialchars($ticket->status))); ?></span>
                        </div>
                        <div class="ticket-meta">
                            <span class="ticket-id">#<?php echo htmlspecialchars($ticket->id); ?></span>
                            <span class="ticket-date">Opened: <?php echo date('M d, Y', strtotime($ticket->created_at)); ?></span>
                            <span class="ticket-category">Category: <?php echo ucfirst(htmlspecialchars($ticket->category)); ?></span>
                        </div>
                        <p class="ticket-preview"><?php echo htmlspecialchars(substr($ticket->description, 0, 150)) . (strlen($ticket->description) > 150 ? '...' : ''); ?></p>
                    </div>
                    <div class="ticket-actions">
                        <a href="<?php echo URL_ROOT; ?>/support/viewTicket/<?php echo htmlspecialchars($ticket->id); ?>" class="view-ticket-btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="tickets-pagination">
        <button class="pagination-btn prev" disabled><i class="fas fa-chevron-left"></i> Previous</button>
        <span class="pagination-info">Page 1 of 1</span>
        <button class="pagination-btn next" disabled>Next <i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="tickets-help-box">
        <h3>Need More Help?</h3>
        <p>If you're experiencing an urgent issue or need help with something else, you can:</p>
        <div class="help-options"> <a href="<?php echo URL_ROOT; ?>/support/faq" class="help-option">
                <i class="fas fa-question-circle"></i>
                <span>Browse FAQ</span>
            </a> <a href="<?php echo URL_ROOT; ?>/support/contact" class="help-option">
                <i class="fas fa-phone-alt"></i>
                <span>Contact Support</span>
            </a> <a href="<?php echo URL_ROOT; ?>/support" class="help-option">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Support</span>
            </a>
        </div>
    </div>
</div>

<!-- Add our notification center -->
<div id="notification-center" class="notification-center">
    <div class="notification-item new">
        <div class="notification-dot"></div>
        <i class="fas fa-comment notification-icon"></i>
        <div class="notification-content">
            <p class="notification-text">Support has responded to your ticket #1234</p>
            <span class="notification-time">Just now</span>
        </div>
    </div>
</div>

<style>
    .tickets-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .tickets-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .tickets-header h1 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .tickets-header p {
        font-size: 18px;
        color: #74767e;
    }

    .tickets-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .tickets-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tickets-filter label {
        font-weight: 500;
        color: #2c3e50;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        color: #404145;
        background-color: white;
        min-width: 180px;
    }

    .tickets-search {
        display: flex;
        align-items: center;
        position: relative;
        flex: 1;
        max-width: 350px;
    }

    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #2c3e50;
        cursor: pointer;
        font-size: 16px;
        padding: 5px 10px;
    }

    .new-ticket-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #2c3e50;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .new-ticket-btn:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }

    .tickets-list {
        margin-bottom: 30px;
    }

    .no-tickets {
        background-color: white;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .no-tickets-icon {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-tickets h3 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .no-tickets p {
        color: #74767e;
        margin-bottom: 20px;
    }

    .create-ticket-btn {
        display: inline-block;
        background-color: #2c3e50;
        color: white;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .create-ticket-btn:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }

    .ticket-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ticket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .ticket-info {
        flex: 1;
    }

    .ticket-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .ticket-subject {
        font-size: 18px;
        color: #2c3e50;
        margin: 0;
        font-weight: 500;
    }

    .ticket-status {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 500;
    }

    .ticket-status.open {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .ticket-status.in-progress {
        background-color: #fff8e1;
        color: #f57c00;
    }

    .ticket-status.awaiting-response {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .ticket-status.resolved {
        background-color: #f1f8e9;
        color: #558b2f;
    }

    .ticket-status.closed {
        background-color: #eeeeee;
        color: #757575;
    }

    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 10px;
        font-size: 13px;
        color: #74767e;
    }

    .ticket-preview {
        color: #62646a;
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .ticket-actions {
        margin-left: 20px;
    }

    .view-ticket-btn {
        display: inline-block;
        background-color: white;
        color: #2c3e50;
        border: 1px solid #ddd;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .view-ticket-btn:hover {
        background-color: #f5f5f7;
        border-color: #2c3e50;
    }

    .tickets-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
    }

    .pagination-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        background-color: white;
        color: #2c3e50;
        border: 1px solid #ddd;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pagination-btn:hover:not(:disabled) {
        background-color: #f5f5f7;
        border-color: #2c3e50;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-info {
        margin: 0 15px;
        font-size: 14px;
        color: #74767e;
    }

    .tickets-help-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 25px;
        margin-top: 40px;
        text-align: center;
    }

    .tickets-help-box h3 {
        font-size: 20px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .tickets-help-box p {
        color: #62646a;
        margin-bottom: 20px;
    }

    .help-options {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .help-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 15px 20px;
        background-color: white;
        border-radius: 8px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        min-width: 120px;
    }

    .help-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .help-option i {
        font-size: 24px;
    }

    .help-option span {
        font-weight: 500;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .tickets-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .tickets-filter {
            width: 100%;
        }

        .tickets-search {
            width: 100%;
            max-width: none;
        }

        .new-ticket-btn {
            width: 100%;
            justify-content: center;
        }

        .ticket-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .ticket-title-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .ticket-actions {
            margin-left: 0;
            margin-top: 15px;
            align-self: flex-end;
        }

        .help-options {
            gap: 10px;
        }

        .help-option {
            min-width: 100px;
            padding: 12px 15px;
        }
    }

    @media (max-width: 480px) {
        .ticket-meta {
            flex-direction: column;
            gap: 5px;
        }

        .pagination-btn {
            padding: 6px 12px;
            font-size: 13px;
        }

        .pagination-info {
            font-size: 13px;
        }
    }

    /* Ticket Tracking Overview Styles */
    .ticket-tracking-overview {
        background-color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
    }

    .tracking-header {
        margin-bottom: 20px;
    }

    .tracking-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .tracking-header p {
        color: #7a8ca0;
        font-size: 0.95rem;
        margin: 0;
    }

    .status-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .status-card {
        background-color: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border-left: 5px solid transparent;
    }

    .status-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .status-card[data-status="open"] {
        border-left-color: #e74c3c;
    }

    .status-card[data-status="open"] .status-icon {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .status-card[data-status="awaiting-response"] {
        border-left-color: #f39c12;
    }

    .status-card[data-status="awaiting-response"] .status-icon {
        background-color: rgba(243, 156, 18, 0.1);
        color: #f39c12;
    }

    .status-card[data-status="in-progress"] {
        border-left-color: #3498db;
    }

    .status-card[data-status="in-progress"] .status-icon {
        background-color: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .status-card[data-status="resolved"] {
        border-left-color: #2ecc71;
    }

    .status-card[data-status="resolved"] .status-icon {
        background-color: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 20px;
    }

    .status-details {
        flex: 1;
    }

    .status-count {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
        color: #2c3e50;
    }

    .status-label {
        color: #7a8ca0;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Notification Preferences Styles */
    .notification-preferences {
        background-color: white;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .notification-header {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
        background-color: #f9fafb;
    }

    .notification-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #2c3e50;
    }

    .toggle-button {
        background: none;
        border: none;
        color: #7a8ca0;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .toggle-button:hover {
        color: #2c3e50;
    }

    .toggle-button i {
        transition: transform 0.3s ease;
    }

    .toggle-button.active i {
        transform: rotate(180deg);
    }

    .notification-options {
        padding: 25px;
        transition: all 0.3s ease;
    }

    .notification-options.hidden {
        display: none;
    }

    .notification-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .notification-option:last-child {
        border-bottom: none;
    }

    .option-label {
        display: flex;
        align-items: center;
    }

    .option-label i {
        margin-right: 10px;
        color: #3498db;
        width: 20px;
        text-align: center;
    }

    .option-label span {
        color: #2c3e50;
        font-weight: 500;
    }

    /* Switch Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.3s;
    }

    input:checked+.slider {
        background-color: #2c5282;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 24px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .notification-settings {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .notification-settings h4 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2c3e50;
        font-size: 1rem;
    }

    .notification-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }

    /* Checkbox Container */
    .checkbox-container {
        display: flex;
        align-items: center;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 0.95rem;
        user-select: none;
        color: #2c3e50;
    }

    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #eee;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .checkbox-container:hover input~.checkmark {
        background-color: #ccc;
    }

    .checkbox-container input:checked~.checkmark {
        background-color: #2c5282;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-container input:checked~.checkmark:after {
        display: block;
    }

    .checkbox-container .checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .save-button {
        background-color: #2c5282;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .save-button:hover {
        background-color: #1a365d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 82, 130, 0.2);
    }

    /* Filter Tools Styles */
    .tickets-filter-tools {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
    }

    .filter-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
        flex: 1;
    }

    .filter-group {
        display: flex;
        align-items: center;
    }

    .filter-group label {
        margin-right: 10px;
        color: #7a8ca0;
        font-size: 0.9rem;
    }

    .filter-select {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #2c3e50;
        background: #f9fafb;
        min-width: 150px;
    }

    .filter-search {
        display: flex;
        flex: 1;
    }

    .search-input {
        flex: 1;
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 6px 0 0 6px;
        font-size: 0.9rem;
    }

    .search-btn {
        background: #2c5282;
        color: white;
        border: none;
        padding: 0 15px;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
    }

    .action-buttons {
        margin-left: 20px;
    }

    .action-btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .action-btn.primary {
        background-color: #2c5282;
        color: white;
        border: none;
    }

    .action-btn.primary:hover {
        background-color: #1a365d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(44, 82, 130, 0.2);
    }

    /* Notification Center */
    .notification-center {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 360px;
        max-height: 400px;
        overflow-y: auto;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
    }

    .notification-center.show {
        display: block;
        animation: slideInUp 0.5s forwards;
    }

    .notification-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .notification-item.new {
        background-color: #f0f8ff;
    }

    .notification-dot {
        position: absolute;
        top: 20px;
        left: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #3498db;
    }

    .notification-icon {
        margin-right: 15px;
        color: #3498db;
        margin-top: 3px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-text {
        margin: 0 0 5px;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .notification-time {
        color: #7a8ca0;
        font-size: 0.8rem;
    }

    @keyframes slideInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .status-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .tickets-filter-tools {
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-wrapper {
            margin-bottom: 15px;
            width: 100%;
        }

        .action-buttons {
            margin-left: 0;
            width: 100%;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .notification-checkboxes {
            grid-template-columns: 1fr;
        }

        .notification-center {
            width: calc(100% - 30px);
            max-width: 400px;
        }
    }

    @media (max-width: 576px) {
        .status-cards {
            grid-template-columns: 1fr;
        }

        .filter-group {
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .filter-group label {
            margin-bottom: 5px;
        }

        .filter-select {
            width: 100%;
        }

        .filter-search {
            width: 100%;
        }
    }

    /* Ticket Status Overview */
    .ticket-tracking-overview {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 30px;
    }

    .tracking-header {
        margin-bottom: 20px;
    }

    .tracking-header h2 {
        margin: 0 0 5px;
        font-size: 1.4rem;
        color: #333;
    }

    .tracking-header p {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
    }

    .status-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
    }

    .status-card {
        flex: 1;
        min-width: 200px;
        background: #f9fafb;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .status-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .status-card[data-status="open"] {
        border-left: 4px solid #e74c3c;
    }

    .status-card[data-status="awaiting-response"] {
        border-left: 4px solid #f39c12;
    }

    .status-card[data-status="in-progress"] {
        border-left: 4px solid #3498db;
    }

    .status-card[data-status="resolved"] {
        border-left: 4px solid #2ecc71;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .status-card[data-status="open"] .status-icon {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .status-card[data-status="awaiting-response"] .status-icon {
        background: rgba(243, 156, 18, 0.1);
        color: #f39c12;
    }

    .status-card[data-status="in-progress"] .status-icon {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .status-card[data-status="resolved"] .status-icon {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .status-details {
        flex: 1;
    }

    .status-count {
        font-size: 1.8rem;
        margin: 0;
        line-height: 1.2;
        font-weight: 700;
    }

    .status-label {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
    }

    /* Notification Preferences */
    .notification-preferences {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .notification-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
    }

    .toggle-button {
        background: none;
        border: none;
        font-size: 1rem;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        transition: all 0.2s;
    }

    .toggle-button:hover {
        color: #333;
    }

    .notification-options {
        padding: 20px;
        border-top: 1px solid #e9ecef;
    }

    .notification-options.hidden {
        display: none;
    }

    .notification-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f3f5;
    }

    .option-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .option-label i {
        color: #6c757d;
    }

    /* Toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #0c7cd5;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #0c7cd5;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .notification-settings {
        margin-top: 20px;
    }

    .notification-settings h4 {
        margin: 0 0 15px;
        font-size: 1rem;
        color: #333;
    }

    .notification-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }

    /* Checkbox styles */
    .checkbox-container {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 10px;
        cursor: pointer;
        font-size: 0.9rem;
        user-select: none;
    }

    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #f1f3f5;
        border: 1px solid #dee2e6;
        border-radius: 3px;
    }

    .checkbox-container:hover input~.checkmark {
        background-color: #e9ecef;
    }

    .checkbox-container input:checked~.checkmark {
        background-color: #0c7cd5;
        border-color: #0c7cd5;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-container input:checked~.checkmark:after {
        display: block;
    }

    .checkbox-container .checkmark:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .save-button {
        background: #0c7cd5;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .save-button:hover {
        background: #0a6bb3;
    }

    .save-button:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }

    /* In-app notifications */
    .in-app-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        max-width: 350px;
        width: 100%;
        z-index: 9999;
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .in-app-notification.notification-visible {
        transform: translateX(0);
        opacity: 1;
    }

    .in-app-notification.notification-hiding {
        transform: translateX(120%);
        opacity: 0;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .notification-content i {
        color: #0c7cd5;
        font-size: 1.2rem;
    }

    .notification-content span {
        color: #333;
        font-size: 0.9rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 0;
        font-size: 0.9rem;
    }

    .notification-close:hover {
        color: #333;
    }

    /* Responsive styles for mobile */
    @media (max-width: 768px) {
        .status-cards {
            flex-direction: column;
        }

        .status-card {
            min-width: 100%;
        }

        .notification-checkboxes {
            grid-template-columns: 1fr;
        }

        .in-app-notification {
            max-width: 90%;
            left: 5%;
            right: 5%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() { // Filter tickets by status
        const statusFilter = document.getElementById('ticket-status-filter');
        const ticketCards = document.querySelectorAll('.ticket-card');

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const selectedStatus = this.value;
                let visibleCount = 0;

                ticketCards.forEach(card => {
                    if (selectedStatus === 'all' || card.getAttribute('data-status') === selectedStatus) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show "no tickets" message if no tickets match the filter
                const noTickets = document.querySelector('.no-tickets');
                const ticketsList = document.querySelector('.tickets-list');

                if (visibleCount === 0 && ticketCards.length > 0) {
                    // Create a temporary "no tickets matching filter" message
                    let tempNoMatch = document.querySelector('.no-matches-filter');
                    if (!tempNoMatch) {
                        tempNoMatch = document.createElement('div');
                        tempNoMatch.className = 'no-tickets no-matches-filter';
                        tempNoMatch.innerHTML = `
                            <div class="no-tickets-icon">
                                <i class="fas fa-filter"></i>
                            </div>
                            <h3>No matching tickets</h3>
                            <p>No tickets match your current filter.</p>
                            <button class="create-ticket-btn reset-filter-btn">Reset Filter</button>
                        `;
                        ticketsList.appendChild(tempNoMatch);

                        // Add event listener to reset filter button
                        tempNoMatch.querySelector('.reset-filter-btn').addEventListener('click', function() {
                            statusFilter.value = 'all';
                            statusFilter.dispatchEvent(new Event('change'));
                        });
                    }
                } else {
                    // Remove the temporary message if it exists
                    const tempNoMatch = document.querySelector('.no-matches-filter');
                    if (tempNoMatch) {
                        tempNoMatch.remove();
                    }
                }

                // Update pagination info
                updatePaginationInfo();
            });
        }

        // Search functionality
        const searchInput = document.getElementById('ticket-search');
        const searchBtn = document.getElementById('search-btn');

        if (searchInput && searchBtn) {
            // Search on button click
            searchBtn.addEventListener('click', function() {
                searchTickets(searchInput.value);
            });

            // Search on Enter key
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchTickets(this.value);
                }
            });
        }

        function searchTickets(query) {
            const normalizedQuery = query.toLowerCase().trim();

            ticketCards.forEach(card => {
                const subject = card.querySelector('.ticket-subject').textContent.toLowerCase();
                const ticketId = card.querySelector('.ticket-id').textContent.toLowerCase();
                const preview = card.querySelector('.ticket-preview').textContent.toLowerCase();

                if (subject.includes(normalizedQuery) ||
                    ticketId.includes(normalizedQuery) ||
                    preview.includes(normalizedQuery)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });

            // Reset status filter
            statusFilter.value = 'all';

            // Update pagination info
            updatePaginationInfo();
        }

        // Update pagination info based on visible tickets
        function updatePaginationInfo() {
            const visibleTickets = document.querySelectorAll('.ticket-card[style*="display: flex"]').length;
            const paginationInfo = document.querySelector('.pagination-info');

            if (paginationInfo) {
                if (visibleTickets === 0) {
                    paginationInfo.textContent = 'No tickets found';
                } else {
                    paginationInfo.textContent = `Page 1 of 1`;
                }
            }
        }

        // Add animation effects to tickets
        ticketCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });

        // Initialize
        updatePaginationInfo();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize ticket counts and update the UI
        function initTicketCounts() {
            // In a real application, these would be fetched from the server
            // For demo purposes, we'll count tickets from the DOM
            const tickets = document.querySelectorAll('.ticket-item');
            let openCount = 0;
            let awaitingCount = 0;
            let progressCount = 0;
            let resolvedCount = 0;

            tickets.forEach(ticket => {
                const status = ticket.dataset.status;
                switch (status) {
                    case 'open':
                        openCount++;
                        break;
                    case 'awaiting-response':
                        awaitingCount++;
                        break;
                    case 'in-progress':
                        progressCount++;
                        break;
                    case 'resolved':
                        resolvedCount++;
                        break;
                }
            });

            // Update the UI
            document.getElementById('open-count').textContent = openCount;
            document.getElementById('awaiting-count').textContent = awaitingCount;
            document.getElementById('progress-count').textContent = progressCount;
            document.getElementById('resolved-count').textContent = resolvedCount;
        }

        // Toggle notification preferences panel
        const toggleBtn = document.getElementById('toggle-notifications');
        const notificationOptions = document.getElementById('notification-options');

        toggleBtn.addEventListener('click', function() {
            notificationOptions.classList.toggle('hidden');
            toggleBtn.classList.toggle('active');
        });

        // Save notification preferences
        const saveBtn = document.getElementById('save-notifications');

        saveBtn.addEventListener('click', function() {
            // In a real app, we would save these preferences to the server
            const emailChecked = document.getElementById('email-notifications').checked;
            const smsChecked = document.getElementById('sms-notifications').checked;
            const browserChecked = document.getElementById('browser-notifications').checked;

            console.log('Saved preferences:', {
                email: emailChecked,
                sms: smsChecked,
                browser: browserChecked
            });

            // Show a notification to confirm preferences were saved
            showNotification('Notification preferences saved successfully!');
        });

        // Filter functionality
        const statusFilter = document.getElementById('status-filter');
        const dateFilter = document.getElementById('date-filter');
        const sortFilter = document.getElementById('sort-filter');
        const searchInput = document.getElementById('ticket-search');
        const searchBtn = document.getElementById('search-btn');

        function applyFilters() {
            const status = statusFilter.value;
            const date = dateFilter.value;
            const sort = sortFilter.value;
            const searchTerm = searchInput.value.toLowerCase().trim();

            const tickets = document.querySelectorAll('.ticket-item');

            tickets.forEach(ticket => {
                let visible = true;

                // Status filter
                if (status !== 'all' && ticket.dataset.status !== status) {
                    visible = false;
                }

                // Date filter would be implemented here with actual dates
                // For demo purposes, we'll skip this

                // Search filter
                if (searchTerm && !ticket.textContent.toLowerCase().includes(searchTerm)) {
                    visible = false;
                }

                // Apply visibility
                ticket.style.display = visible ? 'flex' : 'none';
            });

            // Sort functionality would be implemented here
            // This requires manipulating the DOM order, which is complex for this demo
        }

        // Add event listeners for filters
        statusFilter.addEventListener('change', applyFilters);
        dateFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
        searchBtn.addEventListener('click', applyFilters);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Create new ticket button
        const createTicketBtn = document.getElementById('create-ticket-btn');

        if (createTicketBtn) {
            createTicketBtn.addEventListener('click', function() {
                window.location.href = '<?php echo URL_ROOT; ?>/support/newTicket';
            });
        }

        // Notification functionality
        function showNotification(message, isNew = true) {
            const notificationCenter = document.getElementById('notification-center');
            const notificationItem = document.createElement('div');
            notificationItem.className = isNew ? 'notification-item new' : 'notification-item';

            const dot = document.createElement('div');
            dot.className = 'notification-dot';

            const icon = document.createElement('i');
            icon.className = 'fas fa-info-circle notification-icon';

            const content = document.createElement('div');
            content.className = 'notification-content';

            const text = document.createElement('p');
            text.className = 'notification-text';
            text.textContent = message;

            const time = document.createElement('span');
            time.className = 'notification-time';
            time.textContent = 'Just now';

            content.appendChild(text);
            content.appendChild(time);

            if (isNew) {
                notificationItem.appendChild(dot);
            }
            notificationItem.appendChild(icon);
            notificationItem.appendChild(content);

            notificationCenter.prepend(notificationItem);
            notificationCenter.classList.add('show');

            // Auto-hide after 5 seconds
            setTimeout(() => {
                notificationCenter.classList.remove('show');
            }, 5000);
        }

        // Demo notification for a new response
        setTimeout(() => {
            showNotification('Support has responded to your ticket #1234');
        }, 3000);

        // Initialize ticket counts
        initTicketCounts();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Ticket Status Tracking functionality
        initTicketTracking();

        // Initialize Notification Preferences panel
        initNotificationPreferences();

        // Initialize ticket filtering
        initTicketFiltering();

        // Initialize real-time updates
        initRealTimeUpdates();
    });

    /**
     * Initialize Ticket Status Tracking
     */
    function initTicketTracking() {
        // Count tickets by status and update the status cards
        updateTicketCounters();

        // Add click event to status cards to filter tickets by status
        const statusCards = document.querySelectorAll('.status-card');
        statusCards.forEach(card => {
            card.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                const filterSelect = document.getElementById('ticket-status-filter');

                // Map status card data-status to filter select value
                const statusMap = {
                    'open': 'open',
                    'awaiting-response': 'awaiting-response',
                    'in-progress': 'in-progress',
                    'resolved': 'resolved'
                };

                if (statusMap[status]) {
                    filterSelect.value = statusMap[status];
                    // Trigger the change event
                    const event = new Event('change');
                    filterSelect.dispatchEvent(event);
                }
            });
        });
    }

    /**
     * Update ticket counters in status cards
     */
    function updateTicketCounters() {
        const tickets = document.querySelectorAll('.ticket-card');
        let counts = {
            'open': 0,
            'awaiting-response': 0,
            'in-progress': 0,
            'resolved': 0
        };

        // Count tickets by status
        tickets.forEach(ticket => {
            const status = ticket.getAttribute('data-status');
            if (counts[status] !== undefined) {
                counts[status]++;
            }
        });

        // Update the counter in each status card
        for (const status in counts) {
            const counter = document.getElementById(`${status.replace('-', '-')}-count`);
            if (counter) {
                counter.textContent = counts[status];
            }
        }
    }

    /**
     * Initialize Notification Preferences panel
     */
    function initNotificationPreferences() {
        const toggleButton = document.getElementById('toggle-notifications');
        const notificationOptions = document.getElementById('notification-options');
        const saveButton = document.getElementById('save-notifications');

        // Toggle notification options panel
        toggleButton.addEventListener('click', function() {
            notificationOptions.classList.toggle('hidden');

            // Toggle icon
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            } else {
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            }
        });

        // Handle saving notification preferences
        saveButton.addEventListener('click', function() {
            const emailEnabled = document.getElementById('email-notifications').checked;
            const smsEnabled = document.getElementById('sms-notifications').checked;
            const browserEnabled = document.getElementById('browser-notifications').checked;

            // Collect notification checkboxes
            const checkboxes = document.querySelectorAll('.notification-checkboxes input[type="checkbox"]');
            const notificationTypes = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    notificationTypes.push(checkbox.parentElement.textContent.trim());
                }
            });

            // Send notification preferences to server (AJAX)
            saveNotificationPreferences(emailEnabled, smsEnabled, browserEnabled, notificationTypes);
        });

        // If browser notifications are enabled, request permission
        const browserNotificationsToggle = document.getElementById('browser-notifications');
        browserNotificationsToggle.addEventListener('change', function() {
            if (this.checked) {
                requestNotificationPermission();
            }
        });
    }

    /**
     * Save notification preferences via AJAX
     */
    function saveNotificationPreferences(email, sms, browser, types) {
        // Create a notification about saving preferences
        showNotification('Notification preferences saved successfully!');

        // In a real implementation, this would send an AJAX request to the server
        console.log('Saving notification preferences:', {
            email,
            sms,
            browser,
            types
        });

        // Simulate successful save
        const saveButton = document.getElementById('save-notifications');
        const originalText = saveButton.textContent;

        saveButton.textContent = 'Saved!';
        saveButton.disabled = true;

        setTimeout(() => {
            saveButton.textContent = originalText;
            saveButton.disabled = false;
        }, 2000);
    }

    /**
     * Initialize the ticket filtering functionality
     */
    function initTicketFiltering() {
        const statusFilter = document.getElementById('ticket-status-filter');
        const searchInput = document.getElementById('ticket-search');
        const searchBtn = document.getElementById('search-btn');

        // Filter by status
        statusFilter.addEventListener('change', function() {
            filterTickets();
        });

        // Filter by search query
        searchBtn.addEventListener('click', function() {
            filterTickets();
        });

        // Filter when pressing Enter in search input
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                filterTickets();
            }
        });
    }

    /**
     * Filter tickets based on status and search query
     */
    function filterTickets() {
        const status = document.getElementById('ticket-status-filter').value;
        const query = document.getElementById('ticket-search').value.toLowerCase();
        const tickets = document.querySelectorAll('.ticket-card');

        let visibleCount = 0;

        tickets.forEach(ticket => {
            const ticketStatus = ticket.getAttribute('data-status');
            const ticketSubject = ticket.querySelector('.ticket-subject').textContent.toLowerCase();
            const ticketId = ticket.querySelector('.ticket-id').textContent.toLowerCase();

            // Check if ticket matches both status and search query
            const matchesStatus = status === 'all' || ticketStatus === status;
            const matchesQuery = query === '' ||
                ticketSubject.includes(query) ||
                ticketId.includes(query);

            if (matchesStatus && matchesQuery) {
                ticket.style.display = 'flex';
                visibleCount++;
            } else {
                ticket.style.display = 'none';
            }
        });

        // Show or hide "no tickets" message
        const noTicketsEl = document.querySelector('.no-tickets');
        if (noTicketsEl) {
            if (visibleCount === 0) {
                noTicketsEl.style.display = 'flex';
            } else {
                noTicketsEl.style.display = 'none';
            }
        }
    }

    /**
     * Initialize real-time updates for tickets
     */
    function initRealTimeUpdates() {
        // For demonstration purposes, simulate a ticket update after 15 seconds
        setTimeout(() => {
            // Get a random ticket
            const tickets = document.querySelectorAll('.ticket-card');
            if (tickets.length > 0) {
                const randomIndex = Math.floor(Math.random() * tickets.length);
                const randomTicket = tickets[randomIndex];

                // Update the ticket status (simulate a status change)
                const ticketSubject = randomTicket.querySelector('.ticket-subject').textContent;
                const ticketId = randomTicket.querySelector('.ticket-id').textContent;

                // Change status to "in progress" and update display
                randomTicket.setAttribute('data-status', 'in-progress');

                const statusBadge = randomTicket.querySelector('.ticket-status');
                statusBadge.textContent = 'In Progress';
                statusBadge.className = 'ticket-status in-progress';

                // Show notification for status change
                showNotification(`Ticket ${ticketId} "${ticketSubject}" has been updated to "In Progress"`);

                // Update ticket counters
                updateTicketCounters();
            }
        }, 15000);

        // Real implementation would use WebSockets or Server-Sent Events
        // This is just a simulation for the demo
    }

    /**
     * Request permission for browser notifications
     */
    function requestNotificationPermission() {
        if ('Notification' in window) {
            if (Notification.permission !== 'granted') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showNotification('Browser notifications enabled!');
                    }
                });
            }
        }
    }

    /**
     * Show notification to user (browser or custom)
     */
    function showNotification(message) {
        // Check if browser notifications are enabled and permission granted
        if ('Notification' in window && Notification.permission === 'granted' &&
            document.getElementById('browser-notifications').checked) {
            // Show browser notification
            new Notification('Support Ticket Update', {
                body: message,
                icon: window.location.origin + '/public/img/logo.png' // Adjust path as needed
            });
        }

        // Always show in-app notification
        const notification = document.createElement('div');
        notification.className = 'in-app-notification';
        notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-bell"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;

        document.body.appendChild(notification);

        // Add close button functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.classList.add('notification-hiding');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        });

        // Show notification with animation
        setTimeout(() => {
            notification.classList.add('notification-visible');
        }, 10);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            notification.classList.add('notification-hiding');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 5000);
    }
</script>