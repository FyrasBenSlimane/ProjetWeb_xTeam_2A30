<?php
// Start output buffering to capture content for the dashboard layout
ob_start();
?>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <!-- Include the sidebar -->
    <?php require APPROOT . '/views/dashboard/sidebar.php'; ?>

    <!-- Main Content -->
    <section id="content">
        <!-- Top Navigation -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <a href="#" class="profile">
                <img src="<?php echo URLROOT; ?>/public/images/default-profile.png">
            </a>
        </nav>

        <!-- Main Content Title -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Ticket #<?php echo $data['ticket']->id; ?></h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a href="<?php echo URLROOT; ?>/dashboard/support">Support</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Ticket #<?php echo $data['ticket']->id; ?></a>
                        </li>
                    </ul>
                </div>
                <div class="btn-download">
                    <a href="<?php echo URLROOT; ?>/dashboard/support" class="btn-link">
                        <i class='bx bx-arrow-back'></i>
                        <span>Back to Tickets</span>
                    </a>
                </div>
            </div>

            <!-- Flash Message -->
            <?php flash('ticket_message'); ?>

            <!-- Ticket Details -->
            <div class="ticket-details">
                <div class="support-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Ticket Information</h3>
                        <div class="ticket-actions">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-support-outline dropdown-toggle" type="button" id="ticketActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-dots-vertical-rounded'></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ticketActionsDropdown">
                                    <?php if ($data['ticket']->status == 'open') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/pending"><i class='bx bx-time me-2'></i> Mark as Pending</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/answered"><i class='bx bx-check me-2'></i> Mark as Answered</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/closed"><i class='bx bx-lock-alt me-2'></i> Close Ticket</a></li>
                                    <?php elseif ($data['ticket']->status == 'pending' || $data['ticket']->status == 'answered') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open"><i class='bx bx-envelope-open me-2'></i> Mark as Open</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/closed"><i class='bx bx-lock-alt me-2'></i> Close Ticket</a></li>
                                    <?php elseif ($data['ticket']->status == 'closed') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open"><i class='bx bx-lock-open-alt me-2'></i> Reopen Ticket</a></li>
                                    <?php endif; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/dashboard/deleteTicket/<?php echo $data['ticket']->id; ?>" onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.');"><i class='bx bx-trash me-2'></i> Delete Ticket</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="ticket-header-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="ticket-subject"><?php echo htmlspecialchars($data['ticket']->subject); ?></h4>
                                    <div class="ticket-meta mt-3">
                                        <?php
                                        $categoryClass = 'badge-support badge-support-info';
                                        $priorityClass = '';
                                        switch ($data['ticket']->priority) {
                                            case 'high':
                                                $priorityClass = 'badge-support badge-support-high';
                                                break;
                                            case 'medium':
                                                $priorityClass = 'badge-support badge-support-medium';
                                                break;
                                            case 'low':
                                                $priorityClass = 'badge-support badge-support-low';
                                                break;
                                        }

                                        $statusClass = '';
                                        switch ($data['ticket']->status) {
                                            case 'open':
                                                $statusClass = 'badge-support badge-support-open';
                                                break;
                                            case 'pending':
                                                $statusClass = 'badge-support badge-support-pending';
                                                break;
                                            case 'answered':
                                                $statusClass = 'badge-support badge-support-answered';
                                                break;
                                            case 'closed':
                                                $statusClass = 'badge-support badge-support-closed';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?php echo $categoryClass; ?>">
                                            <i class='bx bx-category'></i> <?php echo ucfirst($data['ticket']->category); ?>
                                        </span>

                                        <span class="badge <?php echo $priorityClass; ?>">
                                            <i class='bx bx-flag'></i> <?php echo ucfirst($data['ticket']->priority); ?> Priority
                                        </span>

                                        <span class="badge <?php echo $statusClass; ?>">
                                            <i class='bx bx-radio-circle'></i> <?php echo ucfirst($data['ticket']->status); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="ticket-dates">
                                        <div class="date-item">
                                            <i class='bx bx-calendar'></i>
                                            <span class="label">Created:</span>
                                            <span class="value"><?php echo date('M j, Y g:i A', strtotime($data['ticket']->created_at)); ?></span>
                                        </div>
                                        <div class="date-item mt-2">
                                            <i class='bx bx-refresh'></i>
                                            <span class="label">Updated:</span>
                                            <span class="value"><?php echo date('M j, Y g:i A', strtotime($data['ticket']->updated_at)); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="ticket-divider">

                        <!-- Original message -->
                        <div class="support-message original-message mt-4">
                            <div class="support-message-header">
                                <img src="<?php echo URLROOT; ?>/public/images/default-profile.png" alt="User" class="support-message-avatar">
                                <div class="support-message-info">
                                    <div class="support-message-name"><?php echo $data['ticket']->user_name; ?></div>
                                    <div class="support-message-time"><?php echo $data['ticket']->user_email; ?></div>
                                </div>
                            </div>
                            <div class="support-message-content">
                                <div class="support-message-body">
                                    <?php echo nl2br(htmlspecialchars($data['ticket']->description)); ?>
                                </div>
                                <?php if (!empty($data['ticket']->attachment_filename)) : ?>
                                    <div class="ticket-attachment mt-3">
                                        <a href="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" class="attachment-link" target="_blank">
                                            <i class='bx bx-paperclip me-2'></i> <?php echo $data['ticket']->attachment_filename; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="ticket-divider">

                        <!-- Conversation History -->
                        <h5 class="conversation-title mb-3">
                            <i class='bx bx-conversation me-2'></i>Conversation History
                        </h5>

                        <?php if (!empty($data['replies'])) : ?>
                            <div class="support-conversation mt-4">
                                <div class="support-timeline">
                                    <?php foreach ($data['replies'] as $reply) : ?>
                                        <div class="support-timeline-item">
                                            <div class="support-timeline-point"></div>
                                            <div class="support-message <?php echo $reply->is_admin ? 'support-message-admin' : 'support-message-user'; ?>">
                                                <div class="support-message-header">
                                                    <img src="<?php echo !empty($reply->profile_image) ? URLROOT . '/public/uploads/' . $reply->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" alt="User" class="support-message-avatar">
                                                    <div class="support-message-info">
                                                        <div class="support-message-name">
                                                            <?php echo $reply->user_name; ?>
                                                            <?php if ($reply->is_admin) : ?>
                                                                <span class="badge badge-support badge-support-primary">Admin</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="support-message-time"><?php echo date('M j, Y g:i A', strtotime($reply->created_at)); ?></div>
                                                    </div>
                                                </div>
                                                <div class="support-message-content">
                                                    <div class="support-message-body">
                                                        <?php echo nl2br(htmlspecialchars($reply->message)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="support-alert support-alert-info">
                                <i class='bx bx-info-circle me-2'></i> No replies yet.
                            </div>
                        <?php endif; ?>

                        <!-- Reply Form -->
                        <?php if ($data['ticket']->status != 'closed') : ?>
                            <div class="reply-form mt-4">
                                <h5 class="reply-form-title">
                                    <i class='bx bx-reply me-2'></i>Add Reply
                                </h5>
                                <form action="<?php echo URLROOT; ?>/dashboard/addTicketReply/<?php echo $data['ticket']->id; ?>" method="post" class="mt-3">
                                    <div class="mb-3">
                                        <textarea class="form-control support-form-control" name="message" rows="5" placeholder="Type your reply here..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mark_as_answered" id="markAsAnswered" value="1">
                                            <label class="form-check-label" for="markAsAnswered">
                                                <i class='bx bx-check me-1'></i> Mark as Answered
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-support-primary">
                                            <i class='bx bx-send me-1'></i> Send Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                            <div class="support-alert support-alert-warning mt-4">
                                <i class='bx bx-lock-alt me-2'></i> This ticket is closed. <a href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open" class="alert-link">Reopen ticket</a> to add a reply.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </section>
</div>

<!-- Include ticket view JS -->
<script src="<?php echo URLROOT; ?>/public/js/support/ticket-view.js"></script>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>