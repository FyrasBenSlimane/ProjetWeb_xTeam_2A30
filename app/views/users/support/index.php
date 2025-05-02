<?php require APPROOT . '/views/layouts/header.php'; ?>

<section class="support-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in">
                <h1 class="display-4 fw-bold mb-3">Support <span class="text-gradient">Center</span></h1>
                <p class="lead text-muted mb-4">Get the help you need with our comprehensive support system. Track and manage all your support requests in one place.</p>

                <div class="support-stats d-flex flex-wrap mb-4">
                    <div class="support-stat-item me-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary-light rounded-circle p-2 me-2">
                                <i class="fas fa-ticket-alt text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo $data['stats']['total']; ?></h5>
                                <small class="text-muted">Total Tickets</small>
                            </div>
                        </div>
                    </div>
                    <div class="support-stat-item me-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success-light rounded-circle p-2 me-2">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo $data['stats']['open']; ?></h5>
                                <small class="text-muted">Open Tickets</small>
                            </div>
                        </div>
                    </div>
                    <div class="support-stat-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-info-light rounded-circle p-2 me-2">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo $data['stats']['pending']; ?></h5>
                                <small class="text-muted">Pending Tickets</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="quick-actions">
                    <span class="text-muted me-2">Quick links:</span>
                    <a href="<?php echo URLROOT; ?>/support/create" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">New Ticket</a>
                    <a href="<?php echo URLROOT; ?>/support/drafts" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">My Drafts</a>
                    <a href="<?php echo URLROOT; ?>/support/faq" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">FAQs</a>
                    <a href="<?php echo URLROOT; ?>/support/contact" class="badge rounded-pill bg-light text-dark mb-2 py-2 px-3 quick-link">Contact Us</a>
                </div>
            </div>
            <div class="col-lg-6 animate-fade-in-right">
                <div class="support-hero-image">
                    <img src="<?php echo URLROOT; ?>/public/images/support-hero.svg" alt="Support Center Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="support-content">
    <div class="container">
        <!-- Flash messages with animation -->
        <div class="flash-messages mb-4 support-fade-in">
            <?php echo flash('ticket_message'); ?>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <!-- Sticky support navigation -->
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-compass me-2"></i> Support Navigation</h5>
                        </div>
                        <div class="list-group list-group-flush support-nav">
                            <a href="#my-tickets" class="list-group-item list-group-item-action d-flex align-items-center active">
                                <i class="fas fa-ticket-alt me-3"></i>
                                <div>
                                    <span class="d-block">My Tickets</span>
                                    <small class="text-muted">View all your tickets</small>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT; ?>/support/create" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-plus-circle me-3"></i>
                                <div>
                                    <span class="d-block">New Ticket</span>
                                    <small class="text-muted">Create a support request</small>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT; ?>/support/drafts" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-save me-3"></i>
                                <div>
                                    <span class="d-block">Draft Tickets</span>
                                    <small class="text-muted">Continue saved tickets</small>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT; ?>/support/import" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-file-import me-3"></i>
                                <div>
                                    <span class="d-block">Import Tickets</span>
                                    <small class="text-muted">Bulk upload from CSV/Excel</small>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT; ?>/support/faq" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-question-circle me-3"></i>
                                <div>
                                    <span class="d-block">FAQs</span>
                                    <small class="text-muted">Find quick answers</small>
                                </div>
                            </a>
                            <a href="<?php echo URLROOT; ?>/support/contact" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-envelope me-3"></i>
                                <div>
                                    <span class="d-block">Contact Us</span>
                                    <small class="text-muted">Get direct assistance</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Help box -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body bg-primary-light">
                            <div class="d-flex align-items-center">
                                <div class="help-icon me-3">
                                    <i class="fas fa-lightbulb fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Need a Quick Answer?</h5>
                                    <p class="small mb-2">Check our comprehensive knowledge base</p>
                                    <a href="<?php echo URLROOT; ?>/support/faq" class="btn btn-sm btn-primary">
                                        <i class="fas fa-book-open me-1"></i> Browse FAQs
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div id="my-tickets" class="support-section mb-5">
                    <!-- Enhanced filter and search controls -->
                    <div class="card shadow-sm border-0 mb-4 support-filters support-fade-in">
                        <div class="card-body p-3">
                            <form class="row g-3 align-items-center" id="ticketFilterForm">
                                <div class="col-lg-4 col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 support-form-control" id="searchTickets" placeholder="Search tickets..." aria-label="Search tickets">
                                        <button type="button" class="btn btn-primary d-md-none" id="mobileSearch">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-6">
                                    <select class="form-select support-form-control" id="filterStatus" aria-label="Filter by status">
                                        <option value="">All Statuses</option>
                                        <option value="open">Open</option>
                                        <option value="pending">Pending</option>
                                        <option value="answered">Answered</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <select class="form-select support-form-control" id="filterPriority" aria-label="Filter by priority">
                                        <option value="">All Priorities</option>
                                        <option value="high">High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <select class="form-select support-form-control" id="filterCategory" aria-label="Filter by category">
                                        <option value="">All Categories</option>
                                        <option value="technical">Technical</option>
                                        <option value="billing">Billing</option>
                                        <option value="account">Account</option>
                                        <option value="feature">Feature Request</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 text-end">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="resetFilters" aria-label="Reset filters">
                                        <i class="fas fa-sync-alt me-1"></i> Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tickets card with improved table design -->
                    <div class="card shadow-sm border-0 mb-4 support-fade-in">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <h5 class="mb-0"><i class="fas fa-ticket-alt me-2 text-primary"></i> My Support Tickets</h5>
                            <div class="d-flex align-items-center gap-3">
                                <!-- Export button -->
                                <div class="dropdown d-none d-md-block">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                                        <li><button class="dropdown-item" type="button"><i class="far fa-file-excel me-2"></i> Excel</button></li>
                                        <li><button class="dropdown-item" type="button"><i class="far fa-file-pdf me-2"></i> PDF</button></li>
                                        <li><button class="dropdown-item" type="button"><i class="far fa-file-csv me-2"></i> CSV</button></li>
                                    </ul>
                                </div>
                                
                                <!-- View switching -->
                                <div class="btn-group" role="group" aria-label="View switching">
                                    <button type="button" class="btn btn-outline-primary active" data-view="table" id="tableViewBtn" aria-pressed="true">
                                        <i class="fas fa-list me-1"></i> <span class="d-none d-sm-inline">Table</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" data-view="cards" id="cardsViewBtn" aria-pressed="false">
                                        <i class="fas fa-th-large me-1"></i> <span class="d-none d-sm-inline">Cards</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php if (!empty($data['tickets'])) : ?>
                                <!-- Enhanced Table View - Removed close/open ticket options -->
                                <div id="tableView" class="view-section">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="ticketsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="ps-4">#</th>
                                                    <th scope="col" style="min-width: 250px;">Subject</th>
                                                    <th scope="col">Category</th>
                                                    <th scope="col">Priority</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col" class="d-none d-md-table-cell">Created</th>
                                                    <th scope="col" class="d-none d-lg-table-cell">Last Update</th>
                                                    <th scope="col" class="text-end pe-4">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['tickets'] as $ticket) : ?>
                                                    <tr class="ticket-row"
                                                        data-status="<?php echo $ticket->status; ?>"
                                                        data-priority="<?php echo $ticket->priority; ?>"
                                                        data-category="<?php echo $ticket->category; ?>">
                                                        <td class="ps-4"><?php echo $ticket->id; ?></td>
                                                        <td>
                                                            <a href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $ticket->id; ?>" class="fw-medium text-decoration-none">
                                                                <?php echo htmlspecialchars($ticket->subject); ?>
                                                            </a>
                                                            <?php if (strtotime($ticket->updated_at) > strtotime('-24 hours')): ?>
                                                                <span class="badge bg-danger ms-2">New</span>
                                                            <?php endif; ?>
                                                            <?php if (!empty($ticket->attachment_filename)): ?>
                                                                <i class="fas fa-paperclip text-muted ms-1" title="Has attachment"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge rounded-pill text-bg-light text-capitalize">
                                                                <?php echo htmlspecialchars($ticket->category); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            switch ($ticket->priority) {
                                                                case 'high':
                                                                    echo '<div class="d-flex align-items-center"><span class="priority-dot bg-danger me-2"></span> <span class="text-danger">High</span></div>';
                                                                    break;
                                                                case 'medium':
                                                                    echo '<div class="d-flex align-items-center"><span class="priority-dot bg-warning me-2"></span> <span class="text-warning">Medium</span></div>';
                                                                    break;
                                                                case 'low':
                                                                    echo '<div class="d-flex align-items-center"><span class="priority-dot bg-info me-2"></span> <span class="text-info">Low</span></div>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            switch ($ticket->status) {
                                                                case 'open':
                                                                    echo '<span class="badge-support badge-support-open">Open</span>';
                                                                    break;
                                                                case 'pending':
                                                                    echo '<span class="badge-support badge-support-pending">Pending</span>';
                                                                    break;
                                                                case 'answered':
                                                                    echo '<span class="badge-support badge-support-answered">Answered</span>';
                                                                    break;
                                                                case 'closed':
                                                                    echo '<span class="badge-support badge-support-closed">Closed</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="d-none d-md-table-cell"><?php echo date('M j, Y', strtotime($ticket->created_at)); ?></td>
                                                        <td class="d-none d-lg-table-cell"><?php echo date('M j, Y', strtotime($ticket->updated_at)); ?></td>
                                                        <td class="text-end pe-4">
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="ticketActions<?php echo $ticket->id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ticketActions<?php echo $ticket->id; ?>">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $ticket->id; ?>">
                                                                            <i class="fas fa-eye me-2 text-primary"></i> View
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/support/edit/<?php echo $ticket->id; ?>">
                                                                            <i class="fas fa-edit me-2 text-info"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/support/delete/<?php echo $ticket->id; ?>" 
                                                                           onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                                                                            <i class="fas fa-trash me-2"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Card view - Removed close/open ticket options -->
                                <div id="cardsView" class="view-section p-3" style="display: none;">
                                    <div class="row">
                                        <?php foreach ($data['tickets'] as $ticket) : ?>
                                            <div class="col-md-6 col-lg-4 mb-3 ticket-card"
                                                data-status="<?php echo $ticket->status; ?>"
                                                data-priority="<?php echo $ticket->priority; ?>"
                                                data-category="<?php echo $ticket->category; ?>">
                                                <div class="support-ticket-card card h-100">
                                                    <div class="card-header bg-transparent border-bottom-0 pb-0">
                                                        <div class="support-ticket-header">
                                                            <span class="badge bg-light text-dark rounded-pill">Ticket #<?php echo $ticket->id; ?></span>
                                                            <?php
                                                            switch ($ticket->status) {
                                                                case 'open':
                                                                    echo '<span class="badge-support badge-support-open">Open</span>';
                                                                    break;
                                                                case 'pending':
                                                                    echo '<span class="badge-support badge-support-pending">Pending</span>';
                                                                    break;
                                                                case 'answered':
                                                                    echo '<span class="badge-support badge-support-answered">Answered</span>';
                                                                    break;
                                                                case 'closed':
                                                                    echo '<span class="badge-support badge-support-closed">Closed</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pb-0">
                                                        <h5 class="support-ticket-title">
                                                            <a href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $ticket->id; ?>" class="text-decoration-none">
                                                                <?php echo htmlspecialchars($ticket->subject); ?>
                                                            </a>
                                                            <?php if (strtotime($ticket->updated_at) > strtotime('-24 hours')): ?>
                                                                <span class="badge bg-danger ms-1">New</span>
                                                            <?php endif; ?>
                                                        </h5>
                                                        <div class="d-flex mb-3 text-muted small">
                                                            <div class="me-3"><i class="far fa-calendar-alt me-1"></i> <?php echo date('M j, Y', strtotime($ticket->created_at)); ?></div>
                                                            <div><i class="fas fa-sync-alt me-1"></i> <?php echo date('M j, Y', strtotime($ticket->updated_at)); ?></div>
                                                        </div>
                                                        <div class="support-ticket-meta">
                                                            <span class="badge rounded-pill text-bg-light text-capitalize">
                                                                <i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($ticket->category); ?>
                                                            </span>
                                                            <?php
                                                            switch ($ticket->priority) {
                                                                case 'high':
                                                                    echo '<span class="badge-support badge-support-high">High Priority</span>';
                                                                    break;
                                                                case 'medium':
                                                                    echo '<span class="badge-support badge-support-medium">Medium Priority</span>';
                                                                    break;
                                                                case 'low':
                                                                    echo '<span class="badge-support badge-support-low">Low Priority</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                            <?php if (!empty($ticket->attachment_filename)): ?>
                                                                <span class="badge bg-light text-dark rounded-pill">
                                                                    <i class="fas fa-paperclip me-1"></i> Attachment
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent support-ticket-footer">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Actions
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $ticket->id; ?>">
                                                                        <i class="fas fa-eye me-2 text-primary"></i> View
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/support/edit/<?php echo $ticket->id; ?>">
                                                                        <i class="fas fa-edit me-2 text-info"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/support/delete/<?php echo $ticket->id; ?>" 
                                                                       onclick="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                                                                        <i class="fas fa-trash me-2"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <a href="<?php echo URLROOT; ?>/support/viewTicket/<?php echo $ticket->id; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye me-1"></i> View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <!-- Enhanced empty state with illustration -->
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <img src="<?php echo URLROOT; ?>/public/images/empty-state.svg" alt="No tickets" style="max-width: 200px;" class="img-fluid">
                                    </div>
                                    <h4 class="text-muted mb-3">No Support Tickets Yet</h4>
                                    <p class="text-muted mb-4 col-md-6 mx-auto">You haven't created any support tickets yet. If you need assistance, create your first support ticket.</p>
                                    <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-plus me-2"></i> Create Your First Ticket
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($data['tickets'])) : ?>
                            <div class="card-footer bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div class="text-muted small">
                                        Showing <span id="visibleTickets"><?php echo count($data['tickets']); ?></span> of <?php echo count($data['tickets']); ?> ticket<?php echo count($data['tickets']) != 1 ? 's' : ''; ?>
                                    </div>
                                    <nav aria-label="Pagination" id="paginationContainer" class="d-none">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Support categories with enhanced design -->
                    <h5 class="mb-3 reveal"><i class="fas fa-question-circle me-2"></i> Common Support Topics</h5>
                    <div class="row mb-5">
                        <div class="col-md-4 mb-3 reveal" style="--delay: 0.1s">
                            <div class="card h-100 border-0 rounded-3 shadow-sm support-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-circle mx-auto mb-3 bg-primary-light text-primary" style="width: 64px; height: 64px;">
                                        <i class="fas fa-wrench"></i>
                                    </div>
                                    <h5 class="card-title">Technical Support</h5>
                                    <p class="card-text text-muted mb-4">Having issues with the platform? Our technical team is ready to assist you.</p>
                                    <a href="<?php echo URLROOT; ?>/support/create?category=technical" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-headset me-2"></i> Get Technical Help
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 reveal" style="--delay: 0.2s">
                            <div class="card h-100 border-0 rounded-3 shadow-sm support-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-circle mx-auto mb-3 bg-success-light text-success" style="width: 64px; height: 64px;">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h5 class="card-title">Billing & Payments</h5>
                                    <p class="card-text text-muted mb-4">Questions about your account billing, invoices, payments or connects?</p>
                                    <a href="<?php echo URLROOT; ?>/support/create?category=billing" class="btn btn-outline-success w-100">
                                        <i class="fas fa-credit-card me-2"></i> Billing Support
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 reveal" style="--delay: 0.3s">
                            <div class="card h-100 border-0 rounded-3 shadow-sm support-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-circle mx-auto mb-3 bg-warning-light text-warning" style="width: 64px; height: 64px;">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <h5 class="card-title">Feature Requests</h5>
                                    <p class="card-text text-muted mb-4">Have suggestions to improve our platform? We'd love to hear your ideas!</p>
                                    <a href="<?php echo URLROOT; ?>/support/create?category=feature" class="btn btn-outline-warning w-100">
                                        <i class="fas fa-star me-2"></i> Suggest Features
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help resources section -->
                    <div class="row mb-5">
                        <div class="col-12 reveal" style="--delay: 0.4s">
                            <div class="card border-0 rounded-3 shadow-sm bg-light">
                                <div class="card-body p-4">
                                    <h5 class="mb-4"><i class="fas fa-info-circle me-2"></i> More Support Options</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <div class="d-flex">
                                                <div class="me-3 mt-1">
                                                    <i class="fas fa-book text-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Knowledge Base</h6>
                                                    <p class="mb-0 small text-muted">Browse through our extensive knowledge base for answers to common questions.</p>
                                                    <a href="<?php echo URLROOT; ?>/support/faq" class="btn btn-link p-0 mt-2">Visit Knowledge Base <i class="fas fa-arrow-right ms-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <div class="d-flex">
                                                <div class="me-3 mt-1">
                                                    <i class="fas fa-comments text-success fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Community Forums</h6>
                                                    <p class="mb-0 small text-muted">Join our community forum to get help from other users and experts.</p>
                                                    <a href="#" class="btn btn-link p-0 mt-2">Browse Forums <i class="fas fa-arrow-right ms-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex">
                                                <div class="me-3 mt-1">
                                                    <i class="fas fa-video text-danger fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Video Tutorials</h6>
                                                    <p class="mb-0 small text-muted">Watch our video tutorials to learn how to use our platform effectively.</p>
                                                    <a href="#" class="btn btn-link p-0 mt-2">Watch Tutorials <i class="fas fa-arrow-right ms-1"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact us section -->
                    <div class="still-need-help mt-5 p-4 bg-light rounded-3 shadow-sm reveal" style="--delay: 0.5s">
                        <div class="row align-items-center">
                            <div class="col-lg-9">
                                <h3 class="mb-2">Still have questions?</h3>
                                <p class="mb-lg-0">If you couldn't find what you're looking for, our support team is here to help.</p>
                            </div>
                            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                                <a href="<?php echo URLROOT; ?>/support/contact" class="btn btn-primary">Contact Support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS for the Support Center -->
<style>
    /* Hero section styling */
    .support-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 5rem 0 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .support-hero::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -5%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .support-hero::after {
        content: '';
        position: absolute;
        bottom: -15%;
        left: -5%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .text-gradient {
        background: linear-gradient(90deg, #0a1128, #1c2541);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    /* Support stats styling */
    .stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    /* Quick links styling */
    .quick-link {
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #e9ecef;
    }

    .quick-link:hover {
        background-color: #0a1128;
        color: white !important;
        transform: translateY(-3px);
    }

    /* Support navigation styling */
    .support-nav .list-group-item {
        border-left: 3px solid transparent;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .support-nav .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0a1128;
    }

    .support-nav .list-group-item.active {
        background-color: #eef1f5;
        border-left-color: #0a1128;
        color: #0a1128;
        font-weight: 600;
    }

    /* Icon circles */
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    /* Priority dot indicator */
    .priority-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Support card hover effects */
    .support-card {
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .support-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Table row hover effect */
    #ticketsTable tbody tr {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #ticketsTable tbody tr:hover {
        background-color: rgba(10, 17, 40, 0.05);
    }

    /* Reveal animations on scroll */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
        transition-delay: var(--delay, 0s);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Background colors */
    .bg-primary-light {
        background-color: rgba(10, 17, 40, 0.1);
    }

    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }

    .bg-info-light {
        background-color: rgba(59, 130, 246, 0.1);
    }

    /* Fade in animations */
    .animate-fade-in {
        animation: fadeIn 1s ease forwards;
    }

    .animate-fade-in-right {
        animation: fadeInRight 1s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Flash message animation */
    .alert {
        animation: slideDown 0.5s ease-out forwards;
        border-left: 4px solid;
    }

    .alert-success {
        border-color: #10b981;
        background-color: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }

    .alert-danger {
        border-color: #ef4444;
        background-color: rgba(239, 68, 68, 0.1);
        color: #b91c1c;
    }

    .alert-warning {
        border-color: #f59e0b;
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .alert-info {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }

    @keyframes slideDown {
        0% {
            transform: translateY(-20px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sticky-top {
            position: relative;
            top: 0;
        }
    }
</style>

<!-- Support Center specific scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });

        // View switching between table and card views
        const tableViewBtn = document.getElementById('tableViewBtn');
        const cardsViewBtn = document.getElementById('cardsViewBtn');
        const tableView = document.getElementById('tableView');
        const cardsView = document.getElementById('cardsView');

        if (tableViewBtn && cardsViewBtn) {
            tableViewBtn.addEventListener('click', function() {
                cardsViewBtn.classList.remove('active');
                tableViewBtn.classList.add('active');
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                localStorage.setItem('preferredSupportView', 'table');
            });

            cardsViewBtn.addEventListener('click', function() {
                tableViewBtn.classList.remove('active');
                cardsViewBtn.classList.add('active');
                tableView.style.display = 'none';
                cardsView.style.display = 'block';
                localStorage.setItem('preferredSupportView', 'cards');
            });

            // Load user's preferred view from localStorage
            const preferredView = localStorage.getItem('preferredSupportView');
            if (preferredView === 'cards') {
                cardsViewBtn.click();
            } else {
                tableViewBtn.click();
            }
        }

        // Filter functionality
        const filterStatus = document.getElementById('filterStatus');
        const filterPriority = document.getElementById('filterPriority');
        const filterCategory = document.getElementById('filterCategory');
        const searchInput = document.getElementById('searchTickets');
        const resetButton = document.getElementById('resetFilters');

        function applyFilters() {
            const statusFilter = filterStatus ? filterStatus.value.toLowerCase() : '';
            const priorityFilter = filterPriority ? filterPriority.value.toLowerCase() : '';
            const categoryFilter = filterCategory ? filterCategory.value.toLowerCase() : '';
            const searchFilter = searchInput ? searchInput.value.toLowerCase() : '';

            // Filter table rows
            const rows = document.querySelectorAll('.ticket-row');
            rows.forEach(row => {
                const status = row.getAttribute('data-status').toLowerCase();
                const priority = row.getAttribute('data-priority').toLowerCase();
                const category = row.getAttribute('data-category').toLowerCase();
                const text = row.textContent.toLowerCase();

                const statusMatch = !statusFilter || status === statusFilter;
                const priorityMatch = !priorityFilter || priority === priorityFilter;
                const categoryMatch = !categoryFilter || category === categoryFilter;
                const searchMatch = !searchFilter || text.includes(searchFilter);

                if (statusMatch && priorityMatch && categoryMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter card items
            const cards = document.querySelectorAll('.ticket-card');
            cards.forEach(card => {
                const status = card.getAttribute('data-status').toLowerCase();
                const priority = card.getAttribute('data-priority').toLowerCase();
                const category = card.getAttribute('data-category').toLowerCase();
                const text = card.textContent.toLowerCase();

                const statusMatch = !statusFilter || status === statusFilter;
                const priorityMatch = !priorityFilter || priority === priorityFilter;
                const categoryMatch = !categoryFilter || category === categoryFilter;
                const searchMatch = !searchFilter || text.includes(searchFilter);

                if (statusMatch && priorityMatch && categoryMatch && searchMatch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Add event listeners for filters
        if (filterStatus) filterStatus.addEventListener('change', applyFilters);
        if (filterPriority) filterPriority.addEventListener('change', applyFilters);
        if (filterCategory) filterCategory.addEventListener('change', applyFilters);
        if (searchInput) searchInput.addEventListener('input', applyFilters);

        // Reset filters
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                if (filterStatus) filterStatus.value = '';
                if (filterPriority) filterPriority.value = '';
                if (filterCategory) filterCategory.value = '';
                if (searchInput) searchInput.value = '';
                applyFilters();
            });
        }

        // Make table rows clickable
        const ticketRows = document.querySelectorAll('#ticketsTable tbody tr');
        ticketRows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicked on a button or link
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                    return;
                }

                // Get ticket URL from the view link
                const viewLink = row.querySelector('a[href*="/support/viewTicket/"]');
                if (viewLink) {
                    window.location.href = viewLink.getAttribute('href');
                }
            });
        });

        // Reveal animations on scroll
        const revealElements = document.querySelectorAll('.reveal');

        const revealOnScroll = function() {
            revealElements.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        };

        // Run once to reveal elements in view on load
        revealOnScroll();

        // Add event listener
        window.addEventListener('scroll', revealOnScroll);
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>