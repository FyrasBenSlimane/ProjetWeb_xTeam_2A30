<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support">Support Tickets</a></li>
                    <li class="breadcrumb-item active">Draft Tickets</li>
                </ol>
            </nav>

            <?php echo flash('ticket_message'); ?>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h5 class="mb-0"><i class="fas fa-save me-2"></i>Draft Support Tickets</h5>
                    <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Ticket
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['drafts'])) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Last Update</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['drafts'] as $draft) : ?>
                                        <tr>
                                            <td><?php echo $draft->id; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/support/editDraft/<?php echo $draft->id; ?>" class="text-decoration-none">
                                                    <?php echo !empty($draft->subject) ? htmlspecialchars($draft->subject) : '<em class="text-muted">No subject</em>'; ?>
                                                </a>
                                            </td>
                                            <td><?php echo !empty($draft->category) ? ucfirst(htmlspecialchars($draft->category)) : '<em class="text-muted">Not set</em>'; ?></td>
                                            <td>
                                                <?php
                                                switch ($draft->priority) {
                                                    case 'high':
                                                        echo '<span class="badge bg-danger">High</span>';
                                                        break;
                                                    case 'medium':
                                                        echo '<span class="badge bg-warning">Medium</span>';
                                                        break;
                                                    case 'low':
                                                        echo '<span class="badge bg-info">Low</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">Not set</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($draft->created_at)); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($draft->updated_at)); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo URLROOT; ?>/support/editDraft/<?php echo $draft->id; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <?php if (!empty($draft->subject) && !empty($draft->description) && !empty($draft->category)) : ?>
                                                        <a href="<?php echo URLROOT; ?>/support/submitDraft/<?php echo $draft->id; ?>" class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-paper-plane"></i> Submit
                                                        </a>
                                                    <?php else : ?>
                                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Complete all required fields to submit">
                                                            <i class="fas fa-paper-plane"></i> Submit
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="<?php echo URLROOT; ?>/support/delete/<?php echo $draft->id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this draft ticket? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-4">
                            <i class="fas fa-save fa-3x text-muted mb-3"></i>
                            <p class="lead">You don't have any draft support tickets.</p>
                            <div class="mt-3">
                                <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-primary me-2">
                                    <i class="fas fa-plus me-1"></i> Create New Ticket
                                </a>
                                <a href="<?php echo URLROOT; ?>/support" class="btn btn-outline-secondary">
                                    <i class="fas fa-ticket-alt me-1"></i> View My Tickets
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($data['drafts'])) : ?>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i> Draft tickets are only visible to you and will not be seen by support staff until submitted.
                            </small>
                            <a href="<?php echo URLROOT; ?>/support" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-ticket-alt me-1"></i> View Submitted Tickets
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>