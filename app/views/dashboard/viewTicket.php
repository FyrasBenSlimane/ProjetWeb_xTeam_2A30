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
                    <h1>View Ticket #<?php echo $data['ticket']->id; ?></h1>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ticket Information</h5>
                        <div class="ticket-actions">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="ticketActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ticketActionsDropdown">
                                    <?php if ($data['ticket']->status == 'open') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/pending">Mark as Pending</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/answered">Mark as Answered</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/closed">Close Ticket</a></li>
                                    <?php elseif ($data['ticket']->status == 'pending' || $data['ticket']->status == 'answered') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open">Mark as Open</a></li>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/closed">Close Ticket</a></li>
                                    <?php elseif ($data['ticket']->status == 'closed') : ?>
                                        <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open">Reopen Ticket</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/dashboard/deleteTicket/<?php echo $data['ticket']->id; ?>" onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.');">Delete Ticket</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h4><?php echo htmlspecialchars($data['ticket']->subject); ?></h4>
                                <div class="ticket-meta">
                                    <span class="badge bg-secondary me-2">Category: <?php echo ucfirst($data['ticket']->category); ?></span>
                                    
                                    <?php
                                    $priorityClass = '';
                                    switch ($data['ticket']->priority) {
                                        case 'high':
                                            $priorityClass = 'bg-danger';
                                            break;
                                        case 'medium':
                                            $priorityClass = 'bg-warning';
                                            break;
                                        case 'low':
                                            $priorityClass = 'bg-success';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $priorityClass; ?> me-2">Priority: <?php echo ucfirst($data['ticket']->priority); ?></span>
                                    
                                    <?php
                                    $statusClass = '';
                                    switch ($data['ticket']->status) {
                                        case 'open':
                                            $statusClass = 'bg-primary';
                                            break;
                                        case 'pending':
                                            $statusClass = 'bg-warning';
                                            break;
                                        case 'answered':
                                            $statusClass = 'bg-info';
                                            break;
                                        case 'closed':
                                            $statusClass = 'bg-secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">Status: <?php echo ucfirst($data['ticket']->status); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="ticket-dates">
                                    <p class="mb-1"><strong>Created:</strong> <?php echo date('M j, Y g:i A', strtotime($data['ticket']->created_at)); ?></p>
                                    <p class="mb-0"><strong>Updated:</strong> <?php echo date('M j, Y g:i A', strtotime($data['ticket']->updated_at)); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="user-info d-flex align-items-center mb-3">
                                    <img src="<?php echo URLROOT; ?>/public/images/default-profile.png" alt="User" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <h6 class="mb-0"><?php echo $data['ticket']->user_name; ?></h6>
                                        <small class="text-muted"><?php echo $data['ticket']->user_email; ?></small>
                                    </div>
                                </div>
                                <div class="ticket-content p-3 bg-light rounded">
                                    <?php echo $data['ticket']->description; ?>
                                </div>
                                <?php if (!empty($data['ticket']->attachment_filename)) : ?>
                                    <div class="ticket-attachment mt-3">
                                        <p><strong>Attachment:</strong> <a href="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" target="_blank"><?php echo $data['ticket']->attachment_filename; ?></a></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <!-- Ticket Replies -->
                        <h5 class="mb-3">Conversation History</h5>
                        
                        <?php if (!empty($data['replies'])) : ?>
                            <div class="ticket-replies">
                                <?php foreach ($data['replies'] as $reply) : ?>
                                    <div class="ticket-reply mb-4 <?php echo $reply->is_admin ? 'admin-reply' : 'user-reply'; ?>">
                                        <div class="user-info d-flex align-items-center mb-2">
                                            <img src="<?php echo !empty($reply->profile_image) ? URLROOT . '/public/uploads/' . $reply->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" alt="User" class="rounded-circle me-2" width="40" height="40">
                                            <div>
                                                <h6 class="mb-0"><?php echo $reply->user_name; ?> <?php echo $reply->is_admin ? '<span class="badge bg-primary">Admin</span>' : ''; ?></h6>
                                                <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($reply->created_at)); ?></small>
                                            </div>
                                        </div>
                                        <div class="reply-content p-3 bg-light rounded">
                                            <?php echo $reply->message; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info">No replies yet.</div>
                        <?php endif; ?>

                        <!-- Reply Form -->
                        <?php if ($data['ticket']->status != 'closed') : ?>
                            <div class="reply-form mt-4">
                                <h5 class="mb-3">Add Reply</h5>
                                <form action="<?php echo URLROOT; ?>/dashboard/addTicketReply/<?php echo $data['ticket']->id; ?>" method="post">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="message" rows="5" placeholder="Type your reply here..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="mark_as_answered" id="markAsAnswered" value="1">
                                            <label class="form-check-label" for="markAsAnswered">
                                                Mark as Answered
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send Reply</button>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-warning mt-4">
                                <i class='bx bx-lock-alt me-2'></i> This ticket is closed. <a href="<?php echo URLROOT; ?>/dashboard/updateTicketStatus/<?php echo $data['ticket']->id; ?>/open">Reopen ticket</a> to add a reply.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </section>
</div>

<!-- Ticket View Styles -->
<style>
    .ticket-details .card {
        border-radius: 10px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .ticket-meta {
        margin-top: 10px;
    }

    .ticket-content, .reply-content {
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .admin-reply .reply-content {
        background-color: #e7f1ff;
        border-left: 4px solid #0d6efd;
    }

    .user-reply .reply-content {
        background-color: #f8f9fa;
        border-left: 4px solid #6c757d;
    }

    .reply-form textarea {
        border-radius: 8px;
        resize: none;
    }

    .ticket-attachment a {
        color: #0d6efd;
        text-decoration: none;
    }

    .ticket-attachment a:hover {
        text-decoration: underline;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>