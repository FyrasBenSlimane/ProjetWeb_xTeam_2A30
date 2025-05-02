<?php

/**
 * Admin Dashboard - Community Management View
 * This file provides administrators with tools to manage the community forums
 */

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
    redirect('users/login');
}
?>

<!-- Community Dashboard Content -->
<div class="dashboard-content">
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Community Management</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="<?php echo URL_ROOT; ?>/dashboard/community">Community</a></li>
            </ul>
        </div>
        <div class="btn-download">
            <a href="<?php echo URL_ROOT; ?>/community/forums" class="btn" target="_blank">
                <i class='bx bx-link-external'></i>
                <span class="text">Visit Forums</span>
            </a>
        </div>
    </div>

    <!-- Section Navigation Tabs -->
    <div class="section-tabs">
        <ul>
            <li class="<?php echo ($data['section'] === 'forums') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums">Forums</a>
            </li>
            <li class="<?php echo ($data['section'] === 'groups') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=groups">Groups</a>
            </li>
            <li class="<?php echo ($data['section'] === 'resources') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=resources">Resources</a>
            </li>
        </ul>
    </div>

    <!-- Display alert messages -->
    <?php flash('dashboard_message'); ?>

    <!-- Stats Cards -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-conversation'></i>
            <span class="text">
                <h3><?php echo $data['totalTopics']; ?></h3>
                <p>Forum Topics</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-message-rounded-detail'></i>
            <span class="text">
                <h3><?php echo $data['totalReplies']; ?></h3>
                <p>Total Replies</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-category'></i>
            <span class="text">
                <h3><?php echo count($data['categories']); ?></h3>
                <p>Categories</p>
            </span>
        </li>
    </ul>

    <?php if ($data['section'] === 'forums'): ?>
        <!-- Forums Section Content -->
        <!-- Category Management -->
        <div class="dashboard-cards">
            <div class="card category-management">
                <div class="card-header">
                    <h3>Forum Categories</h3>
                    <button type="button" class="btn-sm" data-toggle="modal" data-target="#addCategoryModal">
                        <i class='bx bx-plus'></i> Add Category
                    </button>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Topics</th>
                                <th>Posts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['categories'])): ?>
                                <?php foreach ($data['categories'] as $category): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($category->name); ?></td>
                                        <td><?php echo $category->topic_count ?? 0; ?></td>
                                        <td><?php echo $category->post_count ?? 0; ?></td>
                                        <td class="actions">
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageCategory/edit/<?php echo $category->id; ?>" class="btn-sm edit">
                                                <i class='bx bxs-edit'></i>
                                            </a>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageCategory/delete/<?php echo $category->id; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this category? This will also delete all topics and posts in this category.');">
                                                <i class='bx bxs-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="no-data">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reported Content -->
            <div class="card reported-content">
                <div class="card-header">
                    <h3>Reported Content</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['reportedContent'])): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Reported By</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['reportedContent'] as $report): ?>
                                    <tr>
                                        <td><?php echo ucfirst($report->content_type); ?></td>
                                        <td><?php echo $report->reporter_name; ?></td>
                                        <td><?php echo htmlspecialchars(substr($report->reason, 0, 30)); ?>...</td>
                                        <td class="actions">
                                            <a href="#" class="btn-sm view" data-toggle="modal" data-target="#viewReportModal" data-id="<?php echo $report->id; ?>">
                                                <i class='bx bxs-show'></i>
                                            </a>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/reportsAction/dismiss/<?php echo $report->id; ?>" class="btn-sm dismiss">
                                                <i class='bx bx-check'></i>
                                            </a>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/reportsAction/delete/<?php echo $report->id; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this content?');">
                                                <i class='bx bxs-trash'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data">No reported content at this time</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Topics -->
        <div class="card recent-topics">
            <div class="card-header">
                <h3>Recent Topics</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Replies</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recentTopics'])): ?>
                            <?php foreach ($data['recentTopics'] as $topic): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $topic->slug; ?>" target="_blank">
                                            <?php echo htmlspecialchars(substr($topic->title, 0, 40)); ?>
                                            <?php echo (strlen($topic->title) > 40) ? '...' : ''; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $topic->author_name; ?></td>
                                    <td><?php echo $topic->category_name; ?></td>
                                    <td><?php echo $topic->reply_count; ?></td>
                                    <td>
                                        <span class="status <?php echo $topic->status; ?>"><?php echo ucfirst($topic->status); ?></span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($topic->created_at)); ?></td>
                                    <td class="actions">
                                        <?php if ($topic->status !== 'pinned'): ?>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/pin/<?php echo $topic->id; ?>" class="btn-sm pin" title="Pin Topic">
                                                <i class='bx bxs-pin'></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/unpin/<?php echo $topic->id; ?>" class="btn-sm unpin" title="Unpin Topic">
                                                <i class='bx bxs-pin bx-rotate-180'></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($topic->status !== 'closed'): ?>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/close/<?php echo $topic->id; ?>" class="btn-sm close" title="Close Topic">
                                                <i class='bx bx-lock-alt'></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/open/<?php echo $topic->id; ?>" class="btn-sm open" title="Reopen Topic">
                                                <i class='bx bx-lock-open-alt'></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/delete/<?php echo $topic->id; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this topic?');" title="Delete Topic">
                                            <i class='bx bxs-trash'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-data">No topics found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Replies -->
        <div class="card recent-replies">
            <div class="card-header">
                <h3>Recent Replies</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Reply Content</th>
                            <th>Topic</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recentReplies'])): ?>
                            <?php foreach ($data['recentReplies'] as $reply): ?>
                                <tr>
                                    <td><?php echo $reply->author_name; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars(substr(strip_tags($reply->content), 0, 80)); ?>
                                        <?php echo (strlen(strip_tags($reply->content)) > 80) ? '...' : ''; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $reply->topic_slug; ?>" target="_blank">
                                            <?php echo htmlspecialchars(substr($reply->topic_title, 0, 30)); ?>
                                            <?php echo (strlen($reply->topic_title) > 30) ? '...' : ''; ?>
                                        </a>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($reply->created_at)); ?></td>
                                    <td class="actions">
                                        <a href="<?php echo URL_ROOT; ?>/dashboard/manageReply/edit/<?php echo $reply->id; ?>" class="btn-sm edit" title="Edit Reply">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                        <a href="<?php echo URL_ROOT; ?>/dashboard/manageReply/delete/<?php echo $reply->id; ?>" class="btn-sm delete" onclick="return confirm('Are you sure you want to delete this reply?');" title="Delete Reply">
                                            <i class='bx bxs-trash'></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">No replies found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($data['section'] === 'groups'): ?>
        <!-- Groups Section Content -->
        <div class="card">
            <div class="card-header">
                <h3>Community Groups</h3>
                <a href="<?php echo URL_ROOT; ?>/community/groups/create" target="_blank" class="btn-sm">
                    <i class='bx bx-plus'></i> Create Group
                </a>
            </div>
            <div class="card-body">
                <div class="placeholder-content">
                    <div class="info-message">
                        <i class='bx bx-info-circle'></i>
                        <p>Group management functionality is currently being developed. You can create and manage groups from the community frontend.</p>
                    </div>
                    <p class="action-link">
                        <a href="<?php echo URL_ROOT; ?>/community/groups" target="_blank" class="btn">
                            <i class='bx bx-link-external'></i> Go to Groups
                        </a>
                    </p>
                </div>
            </div>
        </div>

    <?php elseif ($data['section'] === 'resources'): ?>
        <!-- Resources Section Content -->
        <div class="card">
            <div class="card-header">
                <h3>Community Resources</h3>
                <a href="<?php echo URL_ROOT; ?>/community/resources/create" target="_blank" class="btn-sm">
                    <i class='bx bx-plus'></i> Add Resource
                </a>
            </div>
            <div class="card-body">
                <div class="placeholder-content">
                    <div class="info-message">
                        <i class='bx bx-info-circle'></i>
                        <p>Resource management functionality is currently being developed. You can add and manage resources from the community frontend.</p>
                    </div>
                    <p class="action-link">
                        <a href="<?php echo URL_ROOT; ?>/community/resources" target="_blank" class="btn">
                            <i class='bx bx-link-external'></i> Go to Resources
                        </a>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Add Category Modal -->
<div class="modal" id="addCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Category</h3>
                <span class="close" data-dismiss="modal">&times;</span>
            </div>
            <div class="modal-body">
                <form action="<?php echo URL_ROOT; ?>/dashboard/createCategory" method="POST">
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon (optional)</label>
                        <input type="text" name="icon" id="icon" placeholder="e.g. bx-chat">
                    </div>
                    <div class="form-group">
                        <label for="display_order">Display Order</label>
                        <input type="number" name="display_order" id="display_order" value="0" min="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-sm cancel" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-sm save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Report Modal -->
<div class="modal" id="viewReportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Report Details</h3>
                <span class="close" data-dismiss="modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="reportContent">
                    <!-- Report content will be loaded here via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm cancel" data-dismiss="modal">Close</button>
                <a href="#" class="btn-sm dismiss" id="dismissReportBtn">Dismiss</a>
                <a href="#" class="btn-sm delete" id="deleteReportBtn">Delete Content</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const modals = document.querySelectorAll('.modal');
        const modalTriggers = document.querySelectorAll('[data-toggle="modal"]');
        const modalClosers = document.querySelectorAll('[data-dismiss="modal"]');

        // Open modals
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const modal = document.querySelector(targetId);
                if (modal) {
                    modal.style.display = 'block';

                    // If this is a report view, load report details
                    if (targetId === '#viewReportModal') {
                        const reportId = this.getAttribute('data-id');
                        // Add report ID to action buttons
                        document.getElementById('dismissReportBtn').href = `<?php echo URL_ROOT; ?>/dashboard/reportsAction/dismiss/${reportId}`;
                        document.getElementById('deleteReportBtn').href = `<?php echo URL_ROOT; ?>/dashboard/reportsAction/delete/${reportId}`;

                        // Load report content via AJAX
                        // Note: This is a placeholder. In a real implementation, you would fetch the data
                        document.getElementById('reportContent').innerHTML = 'Loading report details...';
                    }
                }
            });
        });

        // Close modals with close button or clicking outside
        modalClosers.forEach(closer => {
            closer.addEventListener('click', function() {
                modals.forEach(modal => {
                    modal.style.display = 'none';
                });
            });
        });

        window.addEventListener('click', function(e) {
            modals.forEach(modal => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    });
</script>

<style>
    /* Community Dashboard Styles */
    :root {
        --primary: #3C91E6;
        --light: #F9F9F9;
        --grey: #eee;
        --dark: #342E37;
        --danger: #DB504A;
        --success: #4CAF50;
        --warning: #FFC107;
        --font-family: 'Poppins', sans-serif;
    }

    .dashboard-content {
        width: 100%;
        padding: 20px;
    }

    .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        grid-gap: 24px;
        margin-bottom: 24px;
        padding: 0;
        list-style-type: none;
    }

    .box-info li {
        padding: 24px;
        background: var(--light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        grid-gap: 24px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .box-info li i {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        font-size: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }

    .box-info li:nth-child(1) i {
        background: var(--primary);
    }

    .box-info li:nth-child(2) i {
        background: var(--success);
    }

    .box-info li:nth-child(3) i {
        background: var(--warning);
    }

    .box-info li .text h3 {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .box-info li .text p {
        color: var(--dark);
        font-size: 14px;
    }

    /* Card Styles */
    .dashboard-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card-header h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .btn-sm {
        padding: 5px 10px;
        border-radius: 5px;
        background: var(--primary);
        color: var(--light);
        border: none;
        cursor: pointer;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }

    .btn-sm.edit {
        background: var(--primary);
    }

    .btn-sm.delete {
        background: var(--danger);
    }

    .btn-sm.pin,
    .btn-sm.unpin {
        background: var(--warning);
    }

    .btn-sm.close {
        background: #777;
    }

    .btn-sm.open {
        background: var(--success);
    }

    .btn-sm.dismiss {
        background: var(--primary);
    }

    .btn-sm.view {
        background: #777;
    }

    .btn-sm.cancel {
        background: #777;
    }

    .btn-sm.save {
        background: var(--success);
    }

    .btn-sm i {
        font-size: 16px;
    }

    /* Table Styles */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background-color: var(--grey);
        color: var(--dark);
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid var(--grey);
        font-size: 14px;
    }

    .table .actions {
        display: flex;
        gap: 5px;
    }

    .status {
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 12px;
    }

    .status.open {
        background: rgba(76, 175, 80, 0.1);
        color: var(--success);
    }

    .status.closed {
        background: rgba(219, 80, 74, 0.1);
        color: var(--danger);
    }

    .status.pinned {
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning);
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #777;
        font-style: italic;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-dialog {
        margin: 5% auto;
        width: 500px;
        max-width: 90%;
    }

    .modal-content {
        background-color: var(--light);
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 15px;
        border-bottom: 1px solid var(--grey);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
    }

    .modal-header .close {
        font-size: 24px;
        cursor: pointer;
    }

    .modal-body {
        padding: 15px;
        max-height: 500px;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 15px;
        border-top: 1px solid var(--grey);
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--grey);
        border-radius: 5px;
        font-size: 14px;
    }

    /* Alert Messages */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .alert-success {
        background-color: rgba(76, 175, 80, 0.1);
        border: 1px solid var(--success);
        color: var(--success);
    }

    .alert-danger {
        background-color: rgba(219, 80, 74, 0.1);
        border: 1px solid var(--danger);
        color: var(--danger);
    }

    .alert-warning {
        background-color: rgba(255, 193, 7, 0.1);
        border: 1px solid var(--warning);
        color: #997404;
    }

    /* Section Navigation Tabs Styles */
    .section-tabs {
        margin-bottom: 24px;
    }

    .section-tabs ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-bottom: 1px solid var(--grey);
    }

    .section-tabs ul li {
        margin-right: 5px;
    }

    .section-tabs ul li a {
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        color: var(--dark);
        font-weight: 500;
        border-radius: 5px 5px 0 0;
        border: 1px solid transparent;
        border-bottom: none;
        transition: all 0.3s ease;
    }

    .section-tabs ul li.active a {
        border-color: var(--grey);
        border-bottom-color: #fff;
        background: #fff;
        color: var(--primary);
    }

    .section-tabs ul li:not(.active) a:hover {
        background-color: rgba(var(--primary-rgb), 0.05);
    }

    /* Placeholder Content Styles */
    .placeholder-content {
        text-align: center;
        padding: 40px 20px;
    }

    .info-message {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        background-color: rgba(var(--primary-rgb), 0.05);
        padding: 20px;
        border-radius: 8px;
    }

    .info-message i {
        font-size: 24px;
        margin-right: 15px;
        color: var(--primary);
    }

    .action-link {
        margin-top: 20px;
    }

    .action-link .btn {
        padding: 8px 16px;
        background: var(--primary);
        color: white;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Responsive Design */
    @media screen and (max-width: 992px) {
        .dashboard-cards {
            grid-template-columns: 1fr;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }

    @media screen and (max-width: 576px) {
        .box-info {
            grid-template-columns: 1fr;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .card-header .btn-sm {
            margin-top: 10px;
        }
    }
</style>