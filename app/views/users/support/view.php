<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-9">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support">Support Center</a></li>
                    <li class="breadcrumb-item active">Ticket #<?php echo $data['ticket']->id; ?></li>
                </ol>
            </nav>
        
            <!-- Flash Message -->
            <?php flash('ticket_message'); ?>
            
            <!-- Ticket Header Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white p-4 border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                        <h4 class="mb-0"><?php echo htmlspecialchars($data['ticket']->subject); ?></h4>
                        
                        <!-- Ticket Status Badge -->
                        <?php
                        $statusClass = '';
                        switch ($data['ticket']->status) {
                            case 'open':
                                $statusClass = 'badge-support-open';
                                break;
                            case 'pending':
                                $statusClass = 'badge-support-pending';
                                break;
                            case 'answered':
                                $statusClass = 'badge-support-answered';
                                break;
                            case 'closed':
                                $statusClass = 'badge-support-closed';
                                break;
                        }
                        ?>
                        <div>
                            <span class="badge badge-support <?php echo $statusClass; ?>">
                                <i class="fas fa-circle me-1 fs-xs"></i>
                                <?php echo ucfirst($data['ticket']->status); ?>
                            </span>

                            <!-- Ticket Actions Dropdown -->
                            <div class="btn-group ms-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions <i class="fas fa-caret-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/support/edit/<?php echo $data['ticket']->id; ?>"><i class="fas fa-edit me-2 text-primary"></i> Edit Ticket</a></li>
                                    <?php if ($data['ticket']->status !== 'closed'): ?>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/support/closeTicket/<?php echo $data['ticket']->id; ?>"><i class="fas fa-times-circle me-2 text-danger"></i> Close Ticket</a></li>
                                    <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/support/reopenTicket/<?php echo $data['ticket']->id; ?>"><i class="fas fa-redo-alt me-2 text-success"></i> Reopen Ticket</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/support/delete/<?php echo $data['ticket']->id; ?>" onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.');"><i class="fas fa-trash me-2"></i> Delete Ticket</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ticket Meta Information -->
                    <div class="ticket-meta d-flex flex-wrap gap-3 mb-3">
                        <div class="ticket-meta-item">
                            <span class="text-muted"><i class="far fa-calendar-alt me-1"></i> Created:</span>
                            <span class="ticket-meta-value"><?php echo date('M j, Y g:i A', strtotime($data['ticket']->created_at)); ?></span>
                        </div>
                        <div class="ticket-meta-item">
                            <span class="text-muted"><i class="fas fa-tag me-1"></i> Category:</span>
                            <span class="ticket-meta-value badge bg-light text-dark"><?php echo ucfirst(htmlspecialchars($data['ticket']->category)); ?></span>
                        </div>
                        <div class="ticket-meta-item">
                            <?php
                            $priorityClass = '';
                            $priorityIcon = '';
                            
                            switch ($data['ticket']->priority) {
                                case 'high':
                                    $priorityClass = 'text-danger';
                                    $priorityIcon = 'fas fa-arrow-up';
                                    break;
                                case 'medium':
                                    $priorityClass = 'text-warning';
                                    $priorityIcon = 'fas fa-equals';
                                    break;
                                case 'low':
                                    $priorityClass = 'text-info';
                                    $priorityIcon = 'fas fa-arrow-down';
                                    break;
                            }
                            ?>
                            <span class="text-muted"><i class="fas fa-flag me-1"></i> Priority:</span>
                            <span class="ticket-meta-value <?php echo $priorityClass; ?>">
                                <i class="<?php echo $priorityIcon; ?> me-1"></i> <?php echo ucfirst($data['ticket']->priority); ?>
                            </span>
                        </div>
                        <?php if (!empty($data['ticket']->updated_at) && $data['ticket']->updated_at != $data['ticket']->created_at): ?>
                        <div class="ticket-meta-item">
                            <span class="text-muted"><i class="fas fa-sync-alt me-1"></i> Last Updated:</span>
                            <span class="ticket-meta-value"><?php echo date('M j, Y g:i A', strtotime($data['ticket']->updated_at)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body p-4 pt-0">
                    <!-- Ticket Description -->
                    <div class="ticket-description mb-4">
                        <h6 class="text-muted mb-3">Description</h6>
                        <div class="ticket-content p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($data['ticket']->description)); ?>
                        </div>
                    </div>
                    
                    <!-- Ticket Attachment (if exists) -->
                    <?php if (!empty($data['ticket']->attachment_filename)): ?>
                        <?php 
                        $fileHelper = new FileUpload('public/uploads/support_attachments/'); 
                        $fileExtension = $fileHelper->getFileExtension($data['ticket']->attachment_filename);
                        $fileIconClass = $fileHelper->getFileTypeIcon($data['ticket']->attachment_filename);
                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                        $isPdf = $fileExtension == 'pdf';
                        ?>
                        
                        <div class="ticket-attachment mb-4">
                            <h6 class="text-muted mb-3">Attachment</h6>
                            
                            <div class="attachment-wrapper p-3 bg-light rounded">
                                <?php if ($isImage): ?>
                                    <!-- Image Preview -->
                                    <div class="attachment-preview mb-3">
                                        <img src="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" 
                                             alt="Attachment" class="img-fluid rounded" style="max-height: 300px;">
                                    </div>
                                <?php elseif ($isPdf): ?>
                                    <!-- PDF Preview with embedded viewer -->
                                    <div class="attachment-preview mb-3">
                                        <div class="pdf-preview rounded border overflow-hidden">
                                            <object data="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" 
                                                    type="application/pdf" width="100%" height="400px" class="d-none d-md-block">
                                                <p class="p-3 bg-white">
                                                    It appears you don't have a PDF plugin for this browser. 
                                                    You can <a href="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" target="_blank">click here to download the PDF file</a>.
                                                </p>
                                            </object>
                                            <!-- For mobile devices, show download button instead of embed -->
                                            <div class="d-block d-md-none text-center p-4 bg-white">
                                                <i class="far fa-file-pdf fa-3x text-danger mb-3"></i>
                                                <p>PDF preview not available on mobile.</p>
                                                <a href="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" 
                                                   class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-eye me-1"></i> Open PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- File info and download button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="attachment-icon me-3">
                                            <i class="<?php echo $fileIconClass; ?> fa-2x <?php echo $isPdf ? 'text-danger' : ($isImage ? 'text-primary' : 'text-secondary'); ?>"></i>
                                        </div>
                                        <div class="attachment-info">
                                            <h6 class="mb-1"><?php echo htmlspecialchars(basename($data['ticket']->attachment_filename)); ?></h6>
                                            <span class="text-muted small"><?php echo strtoupper($fileExtension); ?> file</span>
                                        </div>
                                    </div>
                                    <a href="<?php echo URLROOT; ?>/public/uploads/support_attachments/<?php echo $data['ticket']->attachment_filename; ?>" 
                                       class="btn btn-sm btn-primary" download>
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Replies Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white p-4 border-bottom-0">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i> Conversation</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Replies List -->
                    <div class="ticket-replies">
                        <?php if (empty($data['replies'])): ?>
                            <div class="no-replies text-center py-5">
                                <div class="no-replies-icon mb-3">
                                    <i class="far fa-comments fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-1">No Replies Yet</h6>
                                <p class="text-muted">Be the first to reply to this ticket.</p>
                            </div>
                        <?php else: ?>
                            <div class="replies-list">
                                <?php foreach ($data['replies'] as $reply): ?>
                                    <?php 
                                    $isAdmin = $reply->is_admin; 
                                    $replyClass = $isAdmin ? 'admin-reply' : 'user-reply';
                                    ?>
                                    <div class="reply <?php echo $replyClass; ?> p-4 border-bottom">
                                        <div class="d-flex">
                                            <div class="reply-user-avatar me-3">
                                                <?php if ($reply->profile_image): ?>
                                                    <img src="<?php echo URLROOT; ?>/public/uploads/profile_images/<?php echo $reply->profile_image; ?>" 
                                                         alt="User" class="rounded-circle" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder rounded-circle 
                                                        <?php echo $isAdmin ? 'bg-primary text-white' : 'bg-secondary text-white'; ?>"
                                                         style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas <?php echo $isAdmin ? 'fa-headset' : 'fa-user'; ?>"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="reply-content w-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="reply-user">
                                                        <h6 class="mb-0 d-flex align-items-center">
                                                            <?php echo htmlspecialchars($reply->user_name); ?>
                                                            <?php if ($isAdmin): ?>
                                                                <span class="badge bg-primary ms-2">Support Staff</span>
                                                            <?php endif; ?>
                                                        </h6>
                                                    </div>
                                                    <div class="reply-time">
                                                        <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($reply->created_at)); ?></small>
                                                    </div>
                                                </div>
                                                <div class="reply-message">
                                                    <?php echo nl2br(htmlspecialchars($reply->message)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Reply Form -->
            <?php if ($data['ticket']->status !== 'closed'): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-4 border-bottom-0">
                        <h5 class="mb-0"><i class="fas fa-reply me-2"></i> Your Response</h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form action="<?php echo URLROOT; ?>/support/addReply/<?php echo $data['ticket']->id; ?>" method="POST" class="reply-form">
                            <div class="mb-3">
                                <textarea class="form-control" name="message" rows="4" placeholder="Type your reply here..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-text">Your reply will be visible to support staff and added to this conversation.</div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <!-- Ticket Closed Notice -->
                <div class="alert alert-light border shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-lock fa-2x text-muted"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">This ticket is closed</h5>
                            <p class="mb-2">You can no longer reply to this conversation as it has been marked as resolved.</p>
                            <a href="<?php echo URLROOT; ?>/support/reopenTicket/<?php echo $data['ticket']->id; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-redo-alt me-1"></i> Reopen Ticket
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="sticky-top" style="top: 100px;">
                <!-- Quick Actions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white p-3">
                        <h6 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo URLROOT; ?>/support" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-arrow-left me-3 text-muted"></i> Back to Tickets
                        </a>
                        <a href="<?php echo URLROOT; ?>/support/edit/<?php echo $data['ticket']->id; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-edit me-3 text-primary"></i> Edit Ticket
                        </a>
                        <?php if ($data['ticket']->status !== 'closed'): ?>
                            <a href="<?php echo URLROOT; ?>/support/closeTicket/<?php echo $data['ticket']->id; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-times-circle me-3 text-danger"></i> Close Ticket
                            </a>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/support/reopenTicket/<?php echo $data['ticket']->id; ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-redo-alt me-3 text-success"></i> Reopen Ticket
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Similar Tickets -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white p-3">
                        <h6 class="mb-0"><i class="fas fa-ticket-alt me-2"></i> Related Issues</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item py-3 px-3">
                                <h6 class="mb-1">
                                    <a href="#" class="text-reset text-decoration-none">Login authentication issue</a>
                                </h6>
                                <span class="badge badge-support badge-support-closed">Closed</span>
                            </li>
                            <li class="list-group-item py-3 px-3">
                                <h6 class="mb-1">
                                    <a href="#" class="text-reset text-decoration-none">Password reset not working</a>
                                </h6>
                                <span class="badge badge-support badge-support-answered">Answered</span>
                            </li>
                            <li class="list-group-item py-3 px-3">
                                <h6 class="mb-1">
                                    <a href="#" class="text-reset text-decoration-none">Account security question</a>
                                </h6>
                                <span class="badge badge-support badge-support-open">Open</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Help Resources -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white p-3">
                        <h6 class="mb-0"><i class="fas fa-question-circle me-2"></i> Help Resources</h6>
                    </div>
                    <div class="card-body">
                        <ul class="resource-links list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="<?php echo URLROOT; ?>/support/faq" class="text-decoration-none d-flex align-items-center">
                                    <span class="resource-icon me-2 bg-light rounded-circle p-1">
                                        <i class="fas fa-book text-primary"></i>
                                    </span>
                                    <span>Browse Knowledge Base</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="<?php echo URLROOT; ?>/support/contact" class="text-decoration-none d-flex align-items-center">
                                    <span class="resource-icon me-2 bg-light rounded-circle p-1">
                                        <i class="fas fa-headset text-success"></i>
                                    </span>
                                    <span>Contact Support</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-decoration-none d-flex align-items-center">
                                    <span class="resource-icon me-2 bg-light rounded-circle p-1">
                                        <i class="fas fa-video text-danger"></i>
                                    </span>
                                    <span>Video Tutorials</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ticket header styling */
    .ticket-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Badge styling for ticket status */
    .badge-support {
        padding: 0.5em 0.75em;
        font-weight: 500;
        border-radius: 30px;
    }
    
    .badge-support-open {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
    }
    
    .badge-support-pending {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }
    
    .badge-support-answered {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }
    
    .badge-support-closed {
        background-color: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }
    
    .badge-support-high {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }
    
    .badge-support-medium {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }
    
    .badge-support-low {
        background-color: rgba(13, 202, 240, 0.15);
        color: #0dcaf0;
    }
    
    /* Attachment preview styling */
    .attachment-wrapper {
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .attachment-preview {
        background-color: #f8f9fa;
        text-align: center;
    }
    
    .pdf-preview {
        width: 100%;
    }
    
    /* Reply styling */
    .user-reply {
        background-color: #ffffff;
    }
    
    .admin-reply {
        background-color: #f8f9fb;
    }
    
    /* Resource links styling */
    .resource-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>