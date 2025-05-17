<?php
// Support - View Ticket page
// This page displays details of a specific support ticket
?>

<div class="ticket-view-container">
    <div class="ticket-header">
        <h1>Ticket #<?php echo htmlspecialchars($data['ticket']->id); ?></h1>
        <div class="ticket-status">
            <span class="status-badge <?php echo strtolower(htmlspecialchars($data['ticket']->status)); ?>">
                <?php echo ucfirst(htmlspecialchars($data['ticket']->status)); ?>
            </span>
            <span class="priority-badge <?php echo strtolower(htmlspecialchars($data['ticket']->priority)); ?>">
                <?php echo ucfirst(htmlspecialchars($data['ticket']->priority)); ?>
            </span>
            <a href="<?php echo URL_ROOT; ?>/support" class="home-link">
                <i class="fas fa-home"></i> Support Home
            </a>
        </div>
    </div>

    <!-- Real-time status indicator -->
    <div class="realtime-indicator">
        <div class="pulse-dot"></div>
        <span class="realtime-text">Live Updates Enabled</span>
    </div>    <!-- Activity Timeline -->
    <div class="activity-timeline">
        <div class="timeline-header">
            <h3><i class="fas fa-history"></i> Ticket Timeline</h3>
            <button class="timeline-toggle">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="timeline-content">
            <div class="timeline-event">
                <div class="event-icon created">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="event-content">
                    <span class="event-title">Ticket Created</span>
                    <span class="event-time"><?php echo date('F j, Y \a\t g:i a', strtotime($data['ticket']->created_at)); ?></span>
                </div>
            </div>

            <?php if ($data['ticket']->status !== 'open'): ?>
                <div class="timeline-event">
                    <div class="event-icon status-change">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="event-content">
                        <span class="event-title">Status Updated to <?php echo ucfirst(htmlspecialchars($data['ticket']->status)); ?></span>
                        <span class="event-time">
                            <?php
                            // Use last activity timestamp if available
                            if (isset($data['ticket']->updated_at)) {
                                echo date('F j, Y \a\t g:i a', strtotime($data['ticket']->updated_at));
                            } else {
                                echo date('F j, Y \a\t g:i a', strtotime('+1 hour', strtotime($data['ticket']->created_at)));
                            }
                            ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <?php 
            // Show admin response events
            $hasResponses = false;
            if (!empty($data['responses'])) {
                foreach ($data['responses'] as $response) {
                    if ($response->is_staff) {
                        $hasResponses = true;
                        ?>
                        <div class="timeline-event">
                            <div class="event-icon response">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <div class="event-content">
                                <span class="event-title">Admin Response</span>
                                <span class="event-time"><?php echo date('F j, Y \a\t g:i a', strtotime($response->created_at)); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>

    <div class="ticket-actions">
        <div class="action-group primary-actions">
            <a href="<?php echo URL_ROOT; ?>/support/tickets" class="action-btn back-btn">
                <i class="fas fa-chevron-left"></i> Back to Tickets
            </a>
        </div>        <div class="action-group secondary-actions">
            <a href="<?php echo URL_ROOT; ?>/support/editTicket/<?php echo $data['ticket']->id; ?>" class="action-btn edit-ticket-btn" title="Edit this ticket">
                <i class="fas fa-edit"></i> Edit
            </a>

            <a href="javascript:void(0);" class="action-btn delete-ticket-btn"
                onclick="confirmDelete(<?php echo $data['ticket']->id; ?>)" title="Delete this ticket">
                <i class="fas fa-trash-alt"></i> Delete
            </a>

            <?php if($_SESSION['user_account_type'] === 'admin'): ?>
            <a href="javascript:void(0);" class="action-btn update-status-btn" title="Update ticket status" onclick="openStatusUpdateModal()">
                <i class="fas fa-exchange-alt"></i> Update Status
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="ticket-details">
        <div class="ticket-subject">
            <h2><?php echo htmlspecialchars($data['ticket']->subject); ?></h2>
            <div class="ticket-meta">
                <span><i class="far fa-calendar-alt"></i> Created: <?php echo date('F j, Y \a\t g:i a', strtotime($data['ticket']->created_at)); ?></span>
                <span><i class="fas fa-user"></i> By: <?php echo htmlspecialchars($data['ticket']->user_name); ?></span>
                <?php if ($data['ticket']->category): ?>
                    <span><i class="fas fa-tag"></i> Category: <?php echo ucfirst(htmlspecialchars($data['ticket']->category)); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="ticket-content">
            <h3>Description:</h3>
            <div class="ticket-message original-message">
                <?php echo nl2br(htmlspecialchars($data['ticket']->description)); ?>
            </div>
        </div>
    </div>    <!-- Ticket Status Panel -->
    <div class="ticket-status-panel">
        <div class="status-header">
            <h3><i class="fas fa-info-circle"></i> Ticket Status</h3>
        </div>
        <div class="status-content">
            <?php 
            $isAdmin = ($_SESSION['user_account_type'] === 'admin');
            $hasAdminResponses = false;
            
            foreach ($data['responses'] as $response) {
                if ($response->is_staff) {
                    $hasAdminResponses = true;
                    break;
                }
            }
            
            if ($data['ticket']->status === 'closed'): ?>
                <div class="status-message status-closed">
                    <i class="fas fa-check-circle"></i>
                    <p>This ticket has been closed. <?php if(!$isAdmin): ?>If you need further assistance, please create a new ticket.<?php endif; ?></p>
                </div>
            <?php elseif ($hasAdminResponses): ?>
                <div class="status-message status-active">
                    <i class="fas fa-sync"></i>
                    <p>This ticket is active. Our support team has responded and is working on your issue.</p>
                </div>
            <?php elseif ($isAdmin): ?>
                <div class="status-message status-pending">
                    <i class="fas fa-clock"></i>
                    <p>This ticket is awaiting an admin response. As an admin, please respond to open the conversation.</p>
                </div>
            <?php else: ?>
                <div class="status-message status-pending">
                    <i class="fas fa-clock"></i>
                    <p>Your ticket has been submitted and is pending review. A support team member will respond shortly.</p>
                    <p class="estimated-time">Estimated response time: within 24 hours</p>
                </div>
            <?php endif; ?>
        </div>
    </div>    <?php
    $isAdmin = ($_SESSION['user_account_type'] === 'admin');
    $hasAdminResponses = false;
    $isTicketCreator = ($data['ticket']->user_id == $_SESSION['user_id']);
    
    foreach ($data['responses'] as $response) {
        if ($response->is_staff) {
            $hasAdminResponses = true;
            break;
        }
    }
    ?>
    <!-- Reply Section -->
    <div class="reply-section">
        <?php if ($isAdmin): // Show admin reply form regardless of ticket state ?>
        <form id="ticket-reply-form" class="reply-form" method="post" action="<?php echo URL_ROOT; ?>/support/addReply">
            <div class="reply-header">
                <h3>Add Admin Response</h3>
                <div class="reply-tools">
                    <button type="button" id="template-btn" class="tool-btn" title="Insert template">
                        <i class="fas fa-file-alt"></i>
                        <span>Templates</span>
                    </button>
                    <button type="button" id="upload-btn" class="tool-btn" title="Upload attachments">
                        <i class="fas fa-paperclip"></i>
                        <span>Attach</span>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <textarea id="reply-message" name="message" class="form-control" placeholder="Type your admin response here..." rows="5" required></textarea>
            </div>

            <input type="hidden" name="ticket_id" value="<?php echo $data['ticket']->id; ?>">

            <div class="form-footer">
                <div class="attachments-preview" id="attachments-preview">
                    <!-- Attachment previews will be inserted here -->
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Admin Response
                    </button>
                </div>
            </div>
        </form>
        <?php elseif ($hasAdminResponses && !$isTicketCreator): // Non-admin, non-ticket creator can reply after admin response ?>
        <form id="ticket-reply-form" class="reply-form" method="post" action="<?php echo URL_ROOT; ?>/support/addReply">
            <div class="reply-header">
                <h3>Add Reply</h3>
            </div>

            <div class="form-group">
                <textarea id="reply-message" name="message" class="form-control" placeholder="Type your reply here..." rows="5" required></textarea>
            </div>

            <input type="hidden" name="ticket_id" value="<?php echo $data['ticket']->id; ?>">

            <div class="form-footer">
                <div class="attachments-preview" id="attachments-preview">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Reply
                    </button>
                </div>
            </div>
        </form>
        <?php elseif ($hasAdminResponses && $isTicketCreator): // Ticket creator can reply after admin response ?>
        <form id="ticket-reply-form" class="reply-form" method="post" action="<?php echo URL_ROOT; ?>/support/addReply">
            <div class="reply-header">
                <h3>Add Reply</h3>
            </div>

            <div class="form-group">
                <textarea id="reply-message" name="message" class="form-control" placeholder="Type your reply here..." rows="5" required></textarea>
            </div>

            <input type="hidden" name="ticket_id" value="<?php echo $data['ticket']->id; ?>">

            <div class="form-footer">
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Reply
                    </button>
                </div>
            </div>
        </form>
        <?php elseif ($isTicketCreator && !$hasAdminResponses): // Ticket creator waiting for admin response ?>
        <div class="waiting-for-admin-reply">
            <div class="waiting-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="waiting-message">
                <h3>Waiting for Admin Response</h3>
                <p>Your ticket is awaiting a response from our support team. You'll be able to reply once an admin has responded.</p>
            </div>
        </div>
        <?php else: // Non-ticket creator and no admin response yet ?>
        <div class="ticket-locked-message">
            <div class="locked-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="locked-message">
                <h3>Ticket Awaiting Admin Response</h3>
                <p>This ticket is currently awaiting an admin response before further replies can be added.</p>
            </div>
        </div>
        <?php endif; ?>
    </div><div class="ticket-conversation">
        <h3>Conversation:</h3>

        <?php if (empty($data['responses'])): ?>
            <div class="no-responses">
                <?php if ($_SESSION['user_account_type'] === 'admin'): ?>
                    <p>No responses yet. As an admin, you need to add the first response to this ticket.</p>
                <?php else: ?>
                    <p>No responses yet. Please wait for a support team member to respond to your ticket.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="responses-list">
                <?php foreach ($data['responses'] as $response): ?>
                    <div class="response-item <?php echo $response->is_staff ? 'staff-response' : 'user-response'; ?>">
                        <div class="response-header">
                            <div class="response-author">
                                <span class="author-name">
                                    <?php echo htmlspecialchars($response->respondent_name); ?>
                                    <?php if ($response->is_staff): ?>
                                        <span class="staff-badge">Staff</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="response-time">
                                <i class="far fa-clock"></i>
                                <?php echo date('F j, Y \a\t g:i a', strtotime($response->created_at)); ?>
                            </div>
                        </div>
                        <div class="response-content">
                            <?php echo nl2br(htmlspecialchars($response->message)); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php
        // Check if user is an admin or if there are already admin responses
        $isAdmin = ($_SESSION['user_account_type'] === 'admin');
        $hasAdminResponses = false;

        foreach ($data['responses'] as $response) {
            if ($response->is_staff) {
                $hasAdminResponses = true;
                break;
            }
        }

        // Show response form if user is admin or if admin has already responded
        if ($data['ticket']->status !== 'closed'): 
            if ($isAdmin): // Admin can always respond ?>
                <div class="add-response">
                    <h3>Add Response</h3>
                    <form action="<?php echo URL_ROOT; ?>/support/addResponse" method="post" id="responseForm">
                        <input type="hidden" name="ticket_id" value="<?php echo $data['ticket']->id; ?>">
                        <div class="form-group">
                            <textarea name="response" id="response" class="form-control" rows="5" placeholder="Type your response here..." required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="submit-response-btn">
                                <i class="fas fa-paper-plane"></i> Send Response
                            </button>
                        </div>
                    </form>
                </div>
            <?php elseif ($hasAdminResponses): // User can respond only if admin responded first ?>
                <div class="add-response">
                    <h3>Add Response</h3>
                    <form action="<?php echo URL_ROOT; ?>/support/addResponse" method="post" id="responseForm">
                        <input type="hidden" name="ticket_id" value="<?php echo $data['ticket']->id; ?>">
                        <div class="form-group">
                            <textarea name="response" id="response" class="form-control" rows="5" placeholder="Type your response here..." required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="submit-response-btn">
                                <i class="fas fa-paper-plane"></i> Send Response
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: // User can't respond yet, waiting for admin ?>
                <div class="waiting-for-admin">
                    <div class="waiting-message">
                        <i class="fas fa-info-circle"></i>
                        <p>You'll be able to reply once a support team member responds to your ticket.</p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Notification Center -->
<div id="notification-center" class="notification-center">
    <div class="notification-center-header">
        <h3>Notifications</h3>
        <button class="close-notification-center">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="notification-list">
        <div class="no-notifications">
            <i class="fas fa-bell-slash"></i>
            <p>No notifications yet</p>
        </div>
        <!-- Notifications will be inserted dynamically here -->
    </div>
</div>

<!-- Notification Trigger Button -->
<div class="notification-trigger">
    <button id="notification-trigger-btn">
        <i class="fas fa-bell"></i>
        <span class="notification-badge">0</span>
    </button>
</div>

<!-- Status Update Modal -->
<div id="statusUpdateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exchange-alt"></i> Update Ticket Status</h3>
            <span class="close-modal" id="closeStatusModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="status-update-form">
                <div class="form-group">
                    <label for="new-status">New Status:</label>
                    <select id="new-status" class="form-control">
                        <option value="open" <?php echo $data['ticket']->status == 'open' ? 'selected' : ''; ?>>Open</option>
                        <option value="in-progress" <?php echo $data['ticket']->status == 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="awaiting-response" <?php echo $data['ticket']->status == 'awaiting-response' ? 'selected' : ''; ?>>Awaiting Response</option>
                        <option value="resolved" <?php echo $data['ticket']->status == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="closed" <?php echo $data['ticket']->status == 'closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status-comment">Add a comment (optional):</label>
                    <textarea id="status-comment" class="form-control" rows="3" placeholder="Add a note about this status change..."></textarea>
                </div>
                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" id="notify-update" checked>
                        <span class="checkmark"></span>
                        Notify the customer about this update
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="cancelStatusUpdate" class="btn-cancel">Cancel</button>
            <button id="confirmStatusUpdate" class="btn-primary">Update Status</button>
        </div>
    </div>
</div>

<style>
    .ticket-view-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .ticket-header h1 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }

    .ticket-status {
        display: flex;
        gap: 10px;
    }

    .status-badge,
    .priority-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        color: white;
    }

    .status-badge.open {
        background-color: #3498db;
    }

    .status-badge.in-progress {
        background-color: #9b59b6;
    }

    .status-badge.awaiting-response {
        background-color: #f39c12;
    }

    .status-badge.resolved {
        background-color: #2ecc71;
    }

    .status-badge.closed {
        background-color: #7f8c8d;
    }

    .priority-badge.low {
        background-color: #95a5a6;
    }

    .priority-badge.medium {
        background-color: #3498db;
    }

    .priority-badge.high {
        background-color: #f39c12;
    }

    .priority-badge.critical {
        background-color: #e74c3c;
    }

    .ticket-actions {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .back-btn,
    .close-ticket-btn,
    .reopen-ticket-btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: background-color 0.2s;
    }

    .back-btn {
        color: #333;
        background-color: #f1f2f6;
    }

    .back-btn:hover {
        background-color: #e5e7eb;
    }

    .close-ticket-btn {
        color: white;
        background-color: #e74c3c;
    }

    .close-ticket-btn:hover {
        background-color: #c0392b;
    }

    .reopen-ticket-btn {
        color: white;
        background-color: #3498db;
    }

    .reopen-ticket-btn:hover {
        background-color: #2980b9;
    }

    .ticket-details {
        background-color: #f9fafb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #e5e7eb;
    }

    .ticket-subject h2 {
        margin-top: 0;
        margin-bottom: 10px;
        color: #333;
    }

    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .ticket-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ticket-meta i {
        color: #9ca3af;
    }

    .ticket-content h3 {
        margin-top: 20px;
        margin-bottom: 10px;
        color: #4b5563;
        font-size: 1.1rem;
    }

    .ticket-message {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        padding: 15px;
        line-height: 1.6;
        color: #4b5563;
    }

    .original-message {
        border-left: 4px solid #3498db;
    }

    .ticket-conversation h3 {
        color: #4b5563;
        margin-bottom: 15px;
    }

    .no-responses {
        background-color: #f9fafb;
        padding: 15px;
        border-radius: 4px;
        text-align: center;
        color: #6b7280;
    }

    .responses-list {
        margin-bottom: 30px;
    }

    .response-item {
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
    }

    .staff-response {
        border-left: 4px solid #2ecc71;
    }

    .user-response {
        border-left: 4px solid #3498db;
    }

    .response-header {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-bottom: none;
    }

    .author-name {
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .staff-badge {
        background-color: #2ecc71;
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .response-time {
        color: #6b7280;
        font-size: 0.85rem;
    }

    .response-content {
        padding: 15px;
        line-height: 1.6;
        background-color: white;
        border: 1px solid #e5e7eb;
    }

    .add-response {
        margin-top: 30px;
    }

    .add-response h3 {
        margin-bottom: 15px;
        color: #4b5563;
    }

    .form-group {
        margin-bottom: 15px;
    }

    textarea.form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-family: inherit;
        font-size: 16px;
    }

    .submit-response-btn {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .submit-response-btn:hover {
        background-color: #2980b9;
    }

    .home-link {
        display: inline-block;
        margin-left: 15px;
        padding: 5px 12px;
        background-color: #f8f9fa;
        color: #2c3e50;
        border: 1px solid rgba(44, 62, 80, 0.2);
        border-radius: 4px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .home-link:hover {
        background-color: #2c3e50;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .home-link i {
        margin-right: 5px;
    }

    /* Enhanced styles for action buttons */
    .ticket-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .action-group {
        display: flex;
        gap: 12px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        min-width: 100px;
    }

    .action-btn i {
        margin-right: 8px;
    }

    .back-btn {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .back-btn:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .edit-ticket-btn {
        background-color: #3498db;
        color: white;
        border: none;
    }

    .edit-ticket-btn:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
    }

    .delete-ticket-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
    }

    .delete-ticket-btn:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3);
    }

    /* Waiting for admin message styles */
    .waiting-for-admin {
        margin-top: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #3498db;
    }

    .waiting-message {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .waiting-message i {
        font-size: 24px;
        color: #3498db;
    }

    .waiting-message p {
        margin: 0;
        color: #495057;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .ticket-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .ticket-status {
            margin-top: 10px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .home-link {
            margin-left: 0;
            margin-top: 10px;
        }
    }

    @media (max-width: 768px) {

        .ticket-header,
        .ticket-actions,
        .ticket-meta {
            flex-direction: column;
            gap: 10px;
        }

        .ticket-status {
            margin-top: 10px;
        }

        .ticket-actions {
            gap: 15px;
        }
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        border-bottom: 1px solid #eaeaea;
    }

    .modal-header h3 {
        margin: 0;
        color: #e74c3c;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
    }

    .modal-header h3 i {
        margin-right: 10px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body p {
        margin-top: 0;
        font-size: 1.1rem;
    }

    .warning-text {
        color: #e74c3c;
        font-size: 0.95rem !important;
    }

    .modal-footer {
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        border-top: 1px solid #eaeaea;
    }

    .close-modal {
        font-size: 1.5rem;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #555;
    }

    .btn-cancel,
    .btn-delete {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .btn-cancel {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .btn-cancel:hover {
        background-color: #e9ecef;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: white;
        border: none;
    }

    .btn-delete:hover {
        background-color: #c0392b;
    }

    /* Real-time indicator styles */
    .realtime-indicator {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 100px;
        padding: 8px 15px;
        margin-bottom: 20px;
        width: fit-content;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .pulse-dot {
        width: 10px;
        height: 10px;
        background-color: #2ecc71;
        border-radius: 50%;
        margin-right: 10px;
        position: relative;
    }

    .pulse-dot:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: #2ecc71;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        z-index: -1;
    }

    .realtime-text {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        100% {
            transform: scale(3);
            opacity: 0;
        }
    }

    /* Activity Timeline styles */
    .activity-timeline {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .timeline-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #343a40;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .timeline-header h3 i {
        margin-right: 8px;
        color: #6c757d;
    }

    .timeline-toggle {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 1rem;
        padding: 5px;
        transition: all 0.2s;
    }

    .timeline-toggle:hover {
        color: #343a40;
    }

    .timeline-content {
        padding: 20px;
        position: relative;
    }

    .timeline-content:before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 35px;
        width: 2px;
        background: #e9ecef;
        z-index: 0;
    }

    .timeline-event {
        position: relative;
        padding-left: 60px;
        margin-bottom: 20px;
        z-index: 1;
    }

    .timeline-event:last-child {
        margin-bottom: 0;
    }

    .event-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 2px solid #dee2e6;
        position: absolute;
        left: 16px;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .event-icon i {
        color: #6c757d;
    }

    .event-icon.created {
        border-color: #28a745;
    }

    .event-icon.created i {
        color: #28a745;
    }

    .event-icon.status-change {
        border-color: #007bff;
    }

    .event-icon.status-change i {
        color: #007bff;
    }

    .event-icon.response {
        border-color: #17a2b8;
    }

    .event-icon.response i {
        color: #17a2b8;
    }

    .event-content {
        background: white;
        border-radius: 8px;
        padding: 12px 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .event-title {
        display: block;
        font-weight: 500;
        color: #343a40;
        margin-bottom: 5px;
    }

    .event-time {
        display: block;
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Collaboration Panel styles */
    .collaboration-panel {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .collab-header {
        padding: 15px 20px;
        background: #e9f7fd;
        border-bottom: 1px solid #ceebf6;
    }

    .collab-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #0c7cd5;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .collab-header h3 i {
        margin-right: 8px;
    }

    .collab-content {
        padding: 20px;
    }

    .active-viewers {
        margin-bottom: 20px;
    }

    .active-viewers h4 {
        font-size: 0.95rem;
        margin: 0 0 10px;
        color: #343a40;
        font-weight: 600;
    }

    .viewer-avatars {
        display: flex;
        align-items: center;
    }

    .viewer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        position: relative;
    }

    .viewer-avatar.customer {
        background: #3498db;
    }

    .viewer-avatar.support {
        background: #9b59b6;
    }

    .viewer-avatar.manager {
        background: #e67e22;
    }

    .viewer-avatar.staff-online:after {
        content: "";
        position: absolute;
        width: 12px;
        height: 12px;
        background: #2ecc71;
        border-radius: 50%;
        bottom: 0;
        right: 0;
        border: 2px solid white;
    }

    .typing-indicator {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .typing-text {
        font-size: 0.9rem;
        color: #343a40;
    }

    .typing-dots {
        display: flex;
    }

    .dot {
        width: 8px;
        height: 8px;
        background: #6c757d;
        border-radius: 50%;
        margin: 0 2px;
        animation: typing-dot 1.4s infinite;
    }

    .dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing-dot {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-5px);
        }
    }

    .estimated-response {
        background: #fff8e1;
        border-radius: 8px;
        padding: 15px;
    }

    .response-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .response-header i {
        margin-right: 10px;
        color: #f39c12;
        font-size: 1.2rem;
    }

    .response-header h4 {
        margin: 0;
        font-size: 0.95rem;
        color: #343a40;
        font-weight: 600;
    }

    .response-time-display {
        margin-left: 30px;
    }

    .time-indicator {
        margin-bottom: 10px;
    }

    .progress-bar {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .progress {
        height: 100%;
        background: #f39c12;
        border-radius: 4px;
    }

    .time-remaining {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .response-note {
        margin: 0;
        font-size: 0.85rem;
        color: #6c757d;
        font-style: italic;
    }

    /* Reply Section styles */
    .reply-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .reply-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #343a40;
        font-weight: 600;
    }

    .reply-tools {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tool-btn {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #495057;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tool-btn:hover {
        background: #e9ecef;
        border-color: #ced4da;
    }

    .tool-btn i {
        font-size: 1rem;
    }

    .smart-replies {
        background: #f0f7ff;
        padding: 15px 20px;
        border-bottom: 1px solid #d0e3ff;
        position: relative;
        display: none;
    }

    .smart-replies.active {
        display: block;
    }

    .smart-replies h4 {
        margin: 0 0 15px;
        font-size: 0.95rem;
        color: #0c7cd5;
        font-weight: 600;
    }

    .suggestion-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 15px;
    }

    .reply-suggestion {
        background: white;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #d0e3ff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .reply-suggestion p {
        margin: 0;
        font-size: 0.9rem;
        color: #343a40;
    }

    .use-suggestion-btn {
        background: #0c7cd5;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .use-suggestion-btn:hover {
        background: #0a6bb3;
    }

    .close-suggestions-btn {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        padding: 0;
        position: absolute;
        top: 15px;
        right: 20px;
    }

    .close-suggestions-btn:hover {
        color: #343a40;
    }

    .reply-form {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        resize: vertical;
        font-family: inherit;
        font-size: 0.95rem;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-footer {
        display: flex;
        flex-direction: column;
    }

    .attachments-preview {
        margin-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .attachment-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #343a40;
    }

    .attachment-item i {
        color: #6c757d;
    }

    .remove-attachment {
        margin-left: 5px;
        color: #dc3545;
        cursor: pointer;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
    }

    .btn-primary {
        background: #0c7cd5;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 12px 20px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: #0a6bb3;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(12, 124, 213, 0.2);
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .timeline-content:before {
            left: 25px;
        }

        .timeline-event {
            padding-left: 50px;
        }

        .event-icon {
            width: 36px;
            height: 36px;
            left: 8px;
        }

        .reply-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .reply-tools {
            margin-top: 10px;
            width: 100%;
            justify-content: space-between;
        }

        .tool-btn span {
            display: none;
        }

        .reply-suggestion {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 576px) {
        .timeline-content {
            padding: 15px;
        }

        .timeline-content:before {
            left: 20px;
        }

        .timeline-event {
            padding-left: 40px;
        }

        .event-icon {
            width: 30px;
            height: 30px;
            left: 6px;
        }

        .event-icon i {
            font-size: 0.8rem;
        }

        .estimated-response {
            padding: 10px;
        }

        .response-time-display {
            margin-left: 15px;
        }
    }

    /* Additional styles for notifications */
    .notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: white;
        border-left: 4px solid #2ecc71;
        border-radius: 8px;
        padding: 15px 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transform: translateX(120%);
        transition: transform 0.5s ease;
        z-index: 1000;
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .notification-content i {
        font-size: 1.5rem;
        color: #2ecc71;
    }

    .notification-content span {
        color: #343a40;
        font-size: 0.95rem;
    }

    /* Notification Center styles */
    .notification-center {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        max-width: 100vw;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        transition: right 0.3s ease;
    }

    .notification-center.active {
        right: 0;
    }

    .notification-center-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-center-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
    }

    .close-notification-center {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        font-size: 1.1rem;
    }

    .close-notification-center:hover {
        color: #333;
    }

    .notification-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px 0;
    }

    .notification-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f3f5;
        cursor: pointer;
        transition: background 0.2s;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item.unread {
        border-left: 4px solid #0c7cd5;
        background: rgba(12, 124, 213, 0.05);
    }

    .notification-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .notification-title {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notification-title i {
        color: #0c7cd5;
    }

    .notification-time {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .notification-content {
        color: #495057;
        font-size: 0.9rem;
    }

    .no-notifications {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        color: #6c757d;
    }

    .no-notifications i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .no-notifications p {
        margin: 0;
        font-size: 0.95rem;
    }

    /* Notification Trigger Button styles */
    .notification-trigger {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
    }

    #notification-trigger-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #0c7cd5;
        color: white;
        border: none;
        font-size: 1.3rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s;
    }

    #notification-trigger-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        background: #0a6bb3;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #e74c3c;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .notification-badge:empty {
        display: none;
    }

    /* Status update button in ticket actions */
    .action-btn.update-status-btn {
        background-color: #3498db;
        color: white;
        border: none;
    }

    .action-btn.update-status-btn:hover {
        background-color: #2980b9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation for response
        const responseForm = document.getElementById('responseForm');
        if (responseForm) {
            responseForm.addEventListener('submit', function(e) {
                const response = document.getElementById('response').value.trim();

                if (!response) {
                    e.preventDefault();
                    alert('Please enter a response before submitting.');
                }
            });
        }

        // Initialize timeline toggle
        const timelineToggle = document.querySelector('.timeline-toggle');
        if (timelineToggle) {
            const timelineContent = document.querySelector('.timeline-content');
            timelineToggle.addEventListener('click', function() {
                timelineContent.style.display = timelineContent.style.display === 'none' ? 'block' : 'none';
                timelineToggle.querySelector('i').classList.toggle('fa-chevron-down');
                timelineToggle.querySelector('i').classList.toggle('fa-chevron-up');
            });
        }

        // Smart reply functionality
        const smartReplyBtn = document.getElementById('smart-reply-btn');
        const smartReplies = document.getElementById('smart-replies');
        if (smartReplyBtn && smartReplies) {
            const closeSuggestionsBtn = document.querySelector('.close-suggestions-btn');
            const suggestionBtns = document.querySelectorAll('.use-suggestion-btn');
            const replyTextarea = document.getElementById('reply-message');

            smartReplyBtn.addEventListener('click', function() {
                smartReplies.classList.toggle('active');
            });

            if (closeSuggestionsBtn) {
                closeSuggestionsBtn.addEventListener('click', function() {
                    smartReplies.classList.remove('active');
                });
            }

            suggestionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (replyTextarea) {
                        const suggestion = this.previousElementSibling.textContent;
                        replyTextarea.value = suggestion;
                        smartReplies.classList.remove('active');
                        replyTextarea.focus();
                    }
                });
            });
        }

        // File upload functionality
        const uploadBtn = document.getElementById('upload-btn');
        const attachmentsPreview = document.getElementById('attachments-preview');

        if (uploadBtn && attachmentsPreview) {
            uploadBtn.addEventListener('click', function() {
                // In a real implementation, this would open a file dialog
                // For demo purposes, we'll simulate adding an attachment
                const fileTypes = ['pdf', 'jpg', 'doc', 'png'];
                const fileType = fileTypes[Math.floor(Math.random() * fileTypes.length)];
                const fileName = `attachment-${Math.floor(Math.random() * 1000)}.${fileType}`;

                const attachmentItem = document.createElement('div');
                attachmentItem.className = 'attachment-item';

                let iconClass = 'fas fa-file';
                switch (fileType) {
                    case 'pdf':
                        iconClass = 'fas fa-file-pdf';
                        break;
                    case 'jpg':
                    case 'png':
                        iconClass = 'fas fa-file-image';
                        break;
                    case 'doc':
                        iconClass = 'fas fa-file-word';
                        break;
                }

                attachmentItem.innerHTML = `
                <i class="${iconClass}"></i>
                <span>${fileName}</span>
                <i class="fas fa-times remove-attachment"></i>
            `;

                attachmentsPreview.appendChild(attachmentItem);

                // Add event listener to remove button
                const removeBtn = attachmentItem.querySelector('.remove-attachment');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        attachmentsPreview.removeChild(attachmentItem);
                    });
                }
            });
        }

        // Template functionality
        const templateBtn = document.getElementById('template-btn');
        const replyTextarea = document.getElementById('reply-message');

        if (templateBtn && replyTextarea) {
            templateBtn.addEventListener('click', function() {
                const templates = [
                    "Thank you for reaching out to our support team. I understand your concern about [issue] and I'm here to help.",
                    "I apologize for the inconvenience you've experienced. Let me resolve this for you as quickly as possible.",
                    "I've looked into your request and here's what I found: [findings]. Based on this, I recommend [recommendation]."
                ];

                // In a real implementation, this would show a template selector
                // For demo purposes, we'll pick a random template
                const randomTemplate = templates[Math.floor(Math.random() * templates.length)];
                replyTextarea.value = randomTemplate;
                replyTextarea.focus();
            });
        }

        // Enhanced Timeline with real-time updates
        initEnhancedTimeline();

        // Initialize notification system
        initNotificationSystem();

        // Initialize status update functionality
        initStatusUpdateFunctionality();
    });

    /**
     * Initialize enhanced timeline with real-time updates
     */
    function initEnhancedTimeline() {
        // Simulate real-time updates
        const timelineEvents = [{
                delay: 3000,
                icon: 'fas fa-user-clock',
                iconClass: 'status-change',
                title: 'Support Agent Assigned',
                notification: 'Support agent Marcus has been assigned to your ticket'
            },
            {
                delay: 8000,
                icon: 'fas fa-eye',
                iconClass: 'response',
                title: 'Ticket Viewed by Support',
                notification: 'Your ticket is being reviewed by our support team'
            },
            {
                delay: 15000,
                icon: 'fas fa-exchange-alt',
                iconClass: 'status-change',
                title: 'Status Updated to In Progress',
                notification: 'Your ticket status has been updated to "In Progress"'
            }
        ];

        timelineEvents.forEach(event => {
            setTimeout(() => {
                addTimelineEvent(event.icon, event.iconClass, event.title);
                showNotification(event.notification);
                addNotificationToCenter(event.title, event.notification);

                // Update notification badge count
                updateNotificationBadge(1);
            }, event.delay);
        });

        // Add typing indicator animation
        const typingIndicator = document.querySelector('.typing-indicator');

        if (typingIndicator) {
            setInterval(() => {
                typingIndicator.style.display = 'none';

                setTimeout(() => {
                    typingIndicator.style.display = 'flex';
                }, 5000);
            }, 15000);
        }
    }

    /**
     * Add new event to the timeline
     */
    function addTimelineEvent(icon, iconClass, title) {
        const timelineContent = document.querySelector('.timeline-content');
        if (timelineContent) {
            const timelineEvent = document.createElement('div');
            timelineEvent.className = 'timeline-event';

            timelineEvent.innerHTML = `
            <div class="event-icon ${iconClass}">
                <i class="${icon}"></i>
            </div>
            <div class="event-content">
                <span class="event-title">${title}</span>
                <span class="event-time">Just now</span>
            </div>
        `;

            timelineContent.appendChild(timelineEvent);
        }
    }

    /**
     * Initialize the notification system
     */
    function initNotificationSystem() {
        const notificationTriggerBtn = document.getElementById('notification-trigger-btn');
        const notificationCenter = document.getElementById('notification-center');
        const closeNotificationCenterBtn = document.querySelector('.close-notification-center');

        if (notificationTriggerBtn && notificationCenter && closeNotificationCenterBtn) {
            notificationTriggerBtn.addEventListener('click', function() {
                notificationCenter.classList.toggle('active');

                // Reset notification badge when opening notification center
                if (notificationCenter.classList.contains('active')) {
                    const badge = notificationTriggerBtn.querySelector('.notification-badge');
                    badge.textContent = '0';

                    // Mark all notifications as read
                    const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                    unreadNotifications.forEach(notification => {
                        notification.classList.remove('unread');
                    });
                }
            });

            closeNotificationCenterBtn.addEventListener('click', function() {
                notificationCenter.classList.remove('active');
            });

            // Close notification center when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('#notification-center') &&
                    !event.target.closest('#notification-trigger-btn') &&
                    notificationCenter.classList.contains('active')) {
                    notificationCenter.classList.remove('active');
                }
            });
        }
    }

    /**
     * Update the notification badge count
     */
    function updateNotificationBadge(increment = 1) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + increment;
        }
    }

    /**
     * Show notification to user
     */
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-headset"></i>
            <span>${message}</span>
        </div>
    `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 500);
        }, 5000);
    }

    /**
     * Add notification to the notification center
     */
    function addNotificationToCenter(title, content) {
        const notificationList = document.querySelector('.notification-list');
        const noNotifications = document.querySelector('.no-notifications');

        if (notificationList) {
            // Remove "no notifications" message if present
            if (noNotifications) {
                noNotifications.style.display = 'none';
            }

            const now = new Date();
            const timeString = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;

            const notificationItem = document.createElement('div');
            notificationItem.className = 'notification-item unread';

            notificationItem.innerHTML = `
            <div class="notification-item-header">
                <div class="notification-title">
                    <i class="fas fa-bell"></i>
                    ${title}
                </div>
                <div class="notification-time">
                    ${timeString}
                </div>
            </div>
            <div class="notification-content">
                ${content}
            </div>
        `;

            // Insert at the top of the list
            notificationList.insertBefore(notificationItem, notificationList.firstChild);

            // Add click event to mark as read
            notificationItem.addEventListener('click', function() {
                this.classList.remove('unread');
            });
        }
    }

    /**
     * Initialize status update functionality
     */    function initStatusUpdateFunctionality() {
        // Only add Update Status button for admin users
        const secondaryActions = document.querySelector('.secondary-actions');
        if (secondaryActions && '<?php echo $_SESSION['user_account_type']; ?>' === 'admin') {
            const updateStatusBtn = document.createElement('a');
            updateStatusBtn.href = 'javascript:void(0);';
            updateStatusBtn.className = 'action-btn update-status-btn';
            updateStatusBtn.title = 'Update ticket status';
            updateStatusBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Update Status';
            updateStatusBtn.onclick = openStatusUpdateModal;

            // Insert at the beginning of secondary actions
            secondaryActions.insertBefore(updateStatusBtn, secondaryActions.firstChild);
        }

        // Status Update Modal functionality
        const statusUpdateModal = document.getElementById('statusUpdateModal');
        const closeStatusModalBtn = document.getElementById('closeStatusModal');
        const cancelStatusUpdateBtn = document.getElementById('cancelStatusUpdate');
        const confirmStatusUpdateBtn = document.getElementById('confirmStatusUpdate');

        if (statusUpdateModal && closeStatusModalBtn && cancelStatusUpdateBtn && confirmStatusUpdateBtn) {
            closeStatusModalBtn.addEventListener('click', closeStatusUpdateModal);
            cancelStatusUpdateBtn.addEventListener('click', closeStatusUpdateModal);

            confirmStatusUpdateBtn.addEventListener('click', function() {
                const newStatus = document.getElementById('new-status').value;
                const statusComment = document.getElementById('status-comment').value;
                const notifyUpdate = document.getElementById('notify-update').checked;

                // Update the status badge in the UI
                updateTicketStatus(newStatus);

                // Add timeline event for status update
                addTimelineEvent('fas fa-exchange-alt', 'status-change', `Status Updated to ${newStatus}`);

                // Show notification if enabled
                if (notifyUpdate) {
                    const message = statusComment ?
                        `Ticket status updated to "${newStatus}": ${statusComment}` :
                        `Ticket status updated to "${newStatus}"`;

                    showNotification(message);
                    addNotificationToCenter('Status Updated', message);
                    updateNotificationBadge(1);
                }

                closeStatusUpdateModal();
            });

            // Close if clicking outside the modal
            statusUpdateModal.addEventListener('click', function(e) {
                if (e.target === statusUpdateModal) {
                    closeStatusUpdateModal();
                }
            });
        }
    }

    /**
     * Open status update modal
     */
    function openStatusUpdateModal() {
        const modal = document.getElementById('statusUpdateModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    /**
     * Close status update modal
     */
    function closeStatusUpdateModal() {
        const modal = document.getElementById('statusUpdateModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    /**
     * Update ticket status in the UI
     */
    function updateTicketStatus(newStatus) {
        const statusBadge = document.querySelector('.status-badge');
        if (statusBadge) {
            // Remove old status class
            statusBadge.className = 'status-badge';
            // Add new status class
            statusBadge.classList.add(newStatus);
            // Update text
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1).replace('-', ' ');
        }
    }

    // Delete confirmation modal functionality
    function confirmDelete(ticketId) {
        const modal = document.getElementById('deleteConfirmationModal');
        const closeBtn = document.querySelector('.close-modal');
        const cancelBtn = document.getElementById('cancelDeleteBtn');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        // Set the delete link
        confirmBtn.href = `<?php echo URL_ROOT; ?>/support/deleteTicket/${ticketId}`;

        // Display the modal
        modal.style.display = 'flex';

        // Close modal functions
        function closeModal() {
            modal.style.display = 'none';
        }

        // Event listeners
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Close if clicking outside the modal
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Delete Ticket</h3>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this ticket?</p>
            <p class="warning-text">This action cannot be undone and all ticket responses will be lost.</p>
        </div>
        <div class="modal-footer">
            <button id="cancelDeleteBtn" class="btn-cancel">Cancel</button>
            <a id="confirmDeleteBtn" href="#" class="btn-delete">Delete Permanently</a>
        </div>
    </div>
</div>

<!-- Form validation scripts -->
<script src="<?php echo URL_ROOT; ?>/js/support-form-validation.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/view-ticket-validation.js"></script>