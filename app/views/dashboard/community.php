<?php

/**
 * Admin Dashboard - Community Management View
 * This file provides administrators with tools to manage the community forums
 */

// Start output buffering to capture content for the dashboard layout
ob_start();
?>

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
        <a href="<?php echo URL_ROOT; ?>/community/forums" class="btn-primary" target="_blank">
            <i class='bx bx-link-external'></i>
            <span class="text">Visit Forums</span>
        </a>
    </div>
</div>

<!-- Dashboard Main Container -->
<div class="dashboard-main-container">
    <!-- Section Navigation Tabs -->
    <div class="section-tabs">
        <ul>
            <li class="<?php echo ($data['section'] === 'forums') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums">
                    <i class='bx bxs-conversation'></i> Forums
                </a>
            </li>
            <li class="<?php echo ($data['section'] === 'groups') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=groups">
                    <i class='bx bxs-group'></i> Groups
                </a>
            </li>
            <li class="<?php echo ($data['section'] === 'resources') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=resources">
                    <i class='bx bxs-file'></i> Resources
                </a>
            </li>
        </ul>
    </div>

    <!-- Display alert messages -->
    <?php flash('dashboard_message'); ?>

    <!-- Stats Cards -->
    <ul class="box-info">
        <li class="stat-card topics">
            <i class='bx bxs-conversation'></i>
            <span class="text">
                <h3><?php echo $data['totalTopics']; ?></h3>
                <p>Forum Topics</p>
            </span>
        </li>
        <li class="stat-card replies">
            <i class='bx bxs-message-rounded-detail'></i>
            <span class="text">
                <h3><?php echo $data['totalReplies']; ?></h3>
                <p>Total Replies</p>
            </span>
        </li>
        <li class="stat-card categories">
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
                    <h3><i class='bx bxs-category'></i> Forum Categories</h3>
                    <button type="button" class="btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                        <i class='bx bx-plus'></i> Add Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-container">
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
                                            <td>
                                                <div class="category-name">
                                                    <?php if (!empty($category->icon)): ?>
                                                        <i class='bx <?php echo htmlspecialchars($category->icon); ?>'></i>
                                                    <?php else: ?>
                                                        <i class='bx bx-category'></i>
                                                    <?php endif; ?>
                                                    <span><?php echo htmlspecialchars($category->name); ?></span>
                                                </div>
                                                <?php if (!empty($category->description)): ?>
                                                    <small class="category-description"><?php echo htmlspecialchars($category->description); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-info"><?php echo $category->topic_count ?? 0; ?></span></td>
                                            <td><span class="badge bg-secondary"><?php echo $category->post_count ?? 0; ?></span></td>
                                            <td class="actions">
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/manageCategory/edit/<?php echo $category->id; ?>" class="btn-action edit" title="Edit Category">
                                                    <i class='bx bxs-edit'></i>
                                                </a>
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/manageCategory/delete/<?php echo $category->id; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this category? This will also delete all topics and posts in this category.');" title="Delete Category">
                                                    <i class='bx bxs-trash'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="no-data">
                                            <div class="empty-state">
                                                <i class='bx bx-category'></i>
                                                <p>No categories found</p>
                                                <button type="button" class="btn-sm" data-toggle="modal" data-target="#addCategoryModal">
                                                    <i class='bx bx-plus'></i> Create your first category
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reported Content -->
            <div class="card reported-content">
                <div class="card-header">
                    <h3><i class='bx bxs-flag'></i> Reported Content</h3>
                    <span class="badge-counter"><?php echo count($data['reportedContent'] ?? []); ?></span>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['reportedContent'])): ?>
                        <div class="table-container">
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
                                            <td>
                                                <span class="badge bg-<?php echo $report->content_type === 'topic' ? 'primary' : 'info'; ?>">
                                                    <?php echo ucfirst($report->content_type); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="reporter-info">
                                                    <div class="user-avatar">
                                                        <?php
                                                        $initials = strtoupper(substr($report->reporter_name, 0, 1));
                                                        $nameParts = explode(' ', $report->reporter_name);
                                                        if (count($nameParts) > 1) {
                                                            $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                                        }
                                                        echo $initials;
                                                        ?>
                                                    </div>
                                                    <div class="reporter-name"><?php echo $report->reporter_name; ?></div>
                                                </div>
                                            </td>
                                            <td class="report-reason">
                                                <?php echo htmlspecialchars(substr($report->reason, 0, 30)); ?>
                                                <?php if (strlen($report->reason) > 30): ?>
                                                    <span class="reason-tooltip" title="<?php echo htmlspecialchars($report->reason); ?>">...</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="actions">
                                                <a href="#" class="btn-action view" data-toggle="modal" data-target="#viewReportModal" data-id="<?php echo $report->id; ?>" title="View Report">
                                                    <i class='bx bxs-show'></i>
                                                </a>
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/reportsAction/dismiss/<?php echo $report->id; ?>" class="btn-action dismiss" title="Dismiss Report">
                                                    <i class='bx bx-check'></i>
                                                </a>
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/reportsAction/delete/<?php echo $report->id; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this content?');" title="Delete Content">
                                                    <i class='bx bxs-trash'></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class='bx bx-check-circle'></i>
                            <p>No reported content at this time</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Topics -->
        <div class="card recent-topics">
            <div class="card-header">
                <h3><i class='bx bxs-conversation'></i> Recent Topics</h3>
                <div class="card-actions">
                    <div class="search-container">
                        <div class="form-input">
                            <input type="search" id="topic-search" placeholder="Search topics...">
                            <button type="button" class="search-btn"><i class='bx bx-search'></i></button>
                        </div>
                    </div>
                    <a href="<?php echo URL_ROOT; ?>/community/forums" target="_blank" class="btn-link">
                        <i class='bx bx-link-external'></i> View All
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table topics-table">
                        <thead>
                            <tr>
                                <th class="sortable" data-sort="title">Title <i class='bx bx-sort-alt-2'></i></th>
                                <th class="sortable" data-sort="author">Author <i class='bx bx-sort-alt-2'></i></th>
                                <th class="sortable" data-sort="category">Category <i class='bx bx-sort-alt-2'></i></th>
                                <th class="sortable" data-sort="replies">Replies <i class='bx bx-sort-alt-2'></i></th>
                                <th class="sortable" data-sort="status">Status <i class='bx bx-sort-alt-2'></i></th>
                                <th class="sortable" data-sort="date">Created <i class='bx bx-sort-alt-2'></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['recentTopics'])): ?>
                                <?php foreach ($data['recentTopics'] as $topic): ?>
                                    <tr>
                                        <td class="topic-title-cell">
                                            <a href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $topic->slug; ?>" target="_blank" class="topic-title">
                                                <?php echo htmlspecialchars($topic->title); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?php
                                                    $initials = strtoupper(substr($topic->author_name, 0, 1));
                                                    $nameParts = explode(' ', $topic->author_name);
                                                    if (count($nameParts) > 1) {
                                                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                                    }
                                                    echo $initials;
                                                    ?>
                                                </div>
                                                <span><?php echo $topic->author_name; ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category-badge"><?php echo $topic->category_name; ?></span>
                                        </td>
                                        <td>
                                            <span class="reply-count"><?php echo $topic->reply_count; ?></span>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $topic->status; ?>">
                                                <?php
                                                switch ($topic->status) {
                                                    case 'pinned':
                                                        echo '<i class="bx bxs-pin"></i> ';
                                                        break;
                                                    case 'closed':
                                                        echo '<i class="bx bx-lock-alt"></i> ';
                                                        break;
                                                    default:
                                                        echo '<i class="bx bx-message-square-dots"></i> ';
                                                }
                                                echo ucfirst($topic->status);
                                                ?>
                                            </span>
                                        </td>
                                        <td data-date="<?php echo strtotime($topic->created_at); ?>">
                                            <div class="date-info">
                                                <span class="date"><?php echo date('M j, Y', strtotime($topic->created_at)); ?></span>
                                                <span class="time"><?php echo date('g:i A', strtotime($topic->created_at)); ?></span>
                                            </div>
                                        </td>
                                        <td class="actions">
                                            <div class="dropdown">
                                                <button class="btn-action dropdown-toggle" type="button" id="dropdownTopic<?php echo $topic->id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-dots-vertical-rounded'></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownTopic<?php echo $topic->id; ?>">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $topic->slug; ?>" target="_blank">
                                                            <i class='bx bx-show'></i> View Topic
                                                        </a>
                                                    </li>

                                                    <?php if ($topic->status !== 'pinned'): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/pin/<?php echo $topic->id; ?>">
                                                                <i class='bx bxs-pin'></i> Pin Topic
                                                            </a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/unpin/<?php echo $topic->id; ?>">
                                                                <i class='bx bxs-pin bx-rotate-180'></i> Unpin Topic
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php if ($topic->status !== 'closed'): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/close/<?php echo $topic->id; ?>">
                                                                <i class='bx bx-lock-alt'></i> Close Topic
                                                            </a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li>
                                                            <a class="dropdown-item" href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/open/<?php echo $topic->id; ?>">
                                                                <i class='bx bx-lock-open-alt'></i> Reopen Topic
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?php echo URL_ROOT; ?>/dashboard/manageTopic/delete/<?php echo $topic->id; ?>" onclick="return confirm('Are you sure you want to delete this topic? This action cannot be undone.');">
                                                            <i class='bx bxs-trash'></i> Delete Topic
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="no-data">
                                        <div class="empty-state">
                                            <i class='bx bx-conversation'></i>
                                            <p>No topics found</p>
                                            <a href="<?php echo URL_ROOT; ?>/community/forums" target="_blank" class="btn-sm">
                                                <i class='bx bx-plus'></i> Create New Topic
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="no-topics-found" class="no-results-container" style="display: none;">
                    <div class="no-results-content">
                        <i class='bx bx-search-alt'></i>
                        <h4>No matching topics found</h4>
                        <p>Try adjusting your search criteria</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Replies -->
        <div class="card recent-replies">
            <div class="card-header">
                <h3><i class='bx bxs-message-square-detail'></i> Recent Replies</h3>
                <a href="<?php echo URL_ROOT; ?>/community/forums" target="_blank" class="btn-link">
                    <i class='bx bx-link-external'></i> View All
                </a>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table replies-table">
                        <thead>
                            <tr>
                                <th>Author</th>
                                <th>Reply Content</th>
                                <th>Topic</th>
                                <th class="sortable" data-sort="date">Created <i class='bx bx-sort-alt-2'></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['recentReplies'])): ?>
                                <?php foreach ($data['recentReplies'] as $reply): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <?php
                                                    $initials = strtoupper(substr($reply->author_name, 0, 1));
                                                    $nameParts = explode(' ', $reply->author_name);
                                                    if (count($nameParts) > 1) {
                                                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                                                    }
                                                    echo $initials;
                                                    ?>
                                                </div>
                                                <span><?php echo $reply->author_name; ?></span>
                                            </div>
                                        </td>
                                        <td class="reply-content">
                                            <div class="content-preview">
                                                <?php
                                                $content = strip_tags($reply->content);
                                                echo htmlspecialchars(substr($content, 0, 80));
                                                echo (strlen($content) > 80) ? '...' : '';
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $reply->topic_slug; ?>" target="_blank" class="topic-link">
                                                <?php
                                                echo htmlspecialchars(substr($reply->topic_title, 0, 30));
                                                echo (strlen($reply->topic_title) > 30) ? '...' : '';
                                                ?>
                                            </a>
                                        </td>
                                        <td data-date="<?php echo strtotime($reply->created_at); ?>">
                                            <div class="date-info">
                                                <span class="date"><?php echo date('M j, Y', strtotime($reply->created_at)); ?></span>
                                                <span class="time"><?php echo date('g:i A', strtotime($reply->created_at)); ?></span>
                                            </div>
                                        </td>
                                        <td class="actions">
                                            <div class="action-buttons">
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/manageReply/edit/<?php echo $reply->id; ?>" class="btn-action edit" title="Edit Reply">
                                                    <i class='bx bxs-edit'></i>
                                                </a>
                                                <a href="<?php echo URL_ROOT; ?>/dashboard/manageReply/delete/<?php echo $reply->id; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this reply?');" title="Delete Reply">
                                                    <i class='bx bxs-trash'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="no-data">
                                        <div class="empty-state">
                                            <i class='bx bx-message-square-detail'></i>
                                            <p>No replies found</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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

    <!-- Community Dashboard Styles -->
    <style>
        /* Status Colors */
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

        /* Section Tabs */
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

        /* Card and Table Styling */
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

        /* Actions Styling */
        .actions {
            display: flex;
            gap: 5px;
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

        /* Responsive Design */
        @media screen and (max-width: 992px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 576px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header .btn-sm {
                margin-top: 10px;
            }
        }
    </style>

    <!-- Community Dashboard Enhanced Styles -->
    <style>
        /* General Styles */
        :root {
            --transition-speed: 0.3s;
        }

        /* Stat Cards Enhancement */
        .box-info .stat-card {
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .box-info .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .box-info .stat-card.topics {
            border-bottom: 3px solid #4361ee;
        }

        .box-info .stat-card.topics i {
            color: #4361ee;
        }

        .box-info .stat-card.replies {
            border-bottom: 3px solid #3a86ff;
        }

        .box-info .stat-card.replies i {
            color: #3a86ff;
        }

        .box-info .stat-card.categories {
            border-bottom: 3px solid #7209b7;
        }

        .box-info .stat-card.categories i {
            color: #7209b7;
        }

        /* Section Tabs Enhancement */
        .section-tabs {
            margin-bottom: 24px;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .section-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .section-tabs::-webkit-scrollbar-thumb {
            background-color: var(--grey);
            border-radius: 4px;
        }

        .section-tabs ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            border-bottom: 1px solid var(--grey);
            min-width: max-content;
        }

        .section-tabs ul li a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            font-size: 15px;
            transition: all var(--transition-speed) ease;
        }

        .section-tabs ul li a i {
            font-size: 18px;
        }

        .section-tabs ul li:not(.active) a:hover {
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--primary);
        }

        /* Card Enhancements */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        .card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .card-header h3 i {
            font-size: 22px;
        }

        .card-body {
            padding: 24px;
        }

        .card-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
        }

        .btn-link:hover {
            color: var(--dark);
            text-decoration: underline;
        }

        /* Table Enhancements */
        .table-container {
            overflow-x: auto;
            width: 100%;
            border-radius: 8px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: #f8f9fa;
            color: var(--dark);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        .table th.sortable {
            cursor: pointer;
            transition: background-color var(--transition-speed) ease;
        }

        .table th.sortable:hover {
            background-color: #eaecef;
        }

        .table th:first-child {
            border-top-left-radius: 8px;
        }

        .table th:last-child {
            border-top-right-radius: 8px;
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaecef;
        }

        .table tbody tr {
            transition: background-color var(--transition-speed) ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Category Management */
        .category-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .category-name i {
            font-size: 20px;
            color: #7209b7;
        }

        .category-description {
            display: block;
            color: #6c757d;
            margin-top: 5px;
            font-size: 0.85em;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 50px;
            text-align: center;
            min-width: 24px;
        }

        .badge.bg-info {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .badge.bg-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .badge.bg-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .badge-counter {
            background: #ff4757;
            color: white;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 50px;
        }

        .category-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: rgba(114, 9, 183, 0.1);
            color: #7209b7;
            font-size: 0.85em;
            font-weight: 500;
            border-radius: 50px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .status-badge.open {
            background-color: rgba(46, 213, 115, 0.1);
            color: #2ed573;
        }

        .status-badge.closed {
            background-color: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }

        .status-badge.pinned {
            background-color: rgba(255, 159, 67, 0.1);
            color: #ff9f43;
        }

        /* Reporter Info */
        .reporter-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background-color: #4b6cb7;
            color: white;
            font-weight: 600;
            font-size: 14px;
            border-radius: 50%;
            text-transform: uppercase;
        }

        .reporter-name {
            font-weight: 500;
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .report-reason {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .reason-tooltip {
            cursor: help;
            color: #0d6efd;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Action Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #f8f9fa;
            color: #6c757d;
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            text-decoration: none;
        }

        .btn-action:hover {
            background-color: #e9ecef;
            color: #212529;
        }

        .btn-action.view:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .btn-action.edit:hover {
            background-color: rgba(46, 213, 115, 0.1);
            color: #2ed573;
        }

        .btn-action.dismiss:hover {
            background-color: rgba(255, 159, 67, 0.1);
            color: #ff9f43;
        }

        .btn-action.delete:hover {
            background-color: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }

        /* Topic & Replies */
        .topic-title {
            color: var(--dark);
            font-weight: 500;
            text-decoration: none;
            transition: color var(--transition-speed) ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            max-width: 250px;
        }

        .topic-title:hover {
            color: var(--primary);
        }

        .topic-link {
            color: var(--dark);
            font-weight: 500;
            text-decoration: none;
            transition: color var(--transition-speed) ease;
        }

        .topic-link:hover {
            color: var(--primary);
        }

        .reply-count {
            font-weight: 600;
        }

        .content-preview {
            color: #6c757d;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Date Information */
        .date-info {
            display: flex;
            flex-direction: column;
        }

        .date {
            font-weight: 500;
        }

        .time {
            color: #6c757d;
            font-size: 0.85em;
        }

        /* Dropdown Styling */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            z-index: 1000;
            min-width: 180px;
            padding: 8px 0;
            margin: 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
            list-style: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            color: var(--dark);
            text-decoration: none;
            transition: background-color var(--transition-speed) ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.text-danger {
            color: #ff4757;
        }

        .dropdown-divider {
            height: 0;
            margin: 8px 0;
            overflow: hidden;
            border-top: 1px solid #e9ecef;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            text-align: center;
        }

        .empty-state i {
            font-size: 48px;
            color: #ced4da;
            margin-bottom: 16px;
        }

        .empty-state p {
            color: #6c757d;
            margin: 0 0 16px;
        }

        /* Search Container */
        .search-container {
            position: relative;
            min-width: 200px;
        }

        .form-input {
            position: relative;
        }

        .form-input input {
            width: 100%;
            padding: 8px 36px 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 50px;
            background-color: #fff;
        }

        .form-input input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            font-size: 16px;
            cursor: pointer;
        }

        /* No Results Container */
        .no-results-container {
            padding: 40px 0;
            text-align: center;
            display: none;
        }

        .no-results-content i {
            font-size: 48px;
            color: #ced4da;
            margin-bottom: 16px;
        }

        .no-results-content h4 {
            color: #343a40;
            margin: 0 0 8px;
        }

        .no-results-content p {
            color: #6c757d;
            margin: 0;
        }

        /* Info Message */
        .info-message {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background-color: #e7f3ff;
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .info-message i {
            font-size: 24px;
            color: #0d6efd;
            flex-shrink: 0;
        }

        .placeholder-content {
            text-align: center;
            padding: 40px 0;
        }

        .action-link {
            margin-top: 24px;
        }

        /* Primary Button */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media screen and (max-width: 992px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .card-header {
                flex-wrap: wrap;
                gap: 12px;
            }

            .card-actions {
                width: 100%;
            }

            .category-name {
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        @media screen and (max-width: 768px) {
            .box-info {
                flex-direction: column;
            }

            .box-info li {
                width: 100%;
            }

            .btn-link span {
                display: none;
            }
        }

        @media screen and (max-width: 576px) {
            .search-container {
                width: 100%;
            }

            .card-body {
                padding: 16px;
            }

            .table th,
            .table td {
                padding: 10px;
            }
        }
    </style>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Topic search functionality
            const topicSearch = document.getElementById('topic-search');
            if (topicSearch) {
                topicSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const topicRows = document.querySelectorAll('.topics-table tbody tr');
                    let matchFound = false;

                    topicRows.forEach(row => {
                        const title = row.querySelector('.topic-title')?.textContent.toLowerCase() || '';
                        const author = row.querySelector('.user-info span')?.textContent.toLowerCase() || '';
                        const category = row.querySelector('.category-badge')?.textContent.toLowerCase() || '';

                        if (title.includes(searchTerm) || author.includes(searchTerm) || category.includes(searchTerm)) {
                            row.style.display = '';
                            matchFound = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show no results message if no matches found
                    const noResults = document.getElementById('no-topics-found');
                    if (noResults) {
                        noResults.style.display = matchFound || !searchTerm ? 'none' : 'block';
                    }
                });
            }

            // Table sorting functionality
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const table = this.closest('table');
                    const index = Array.from(this.parentElement.children).indexOf(this);
                    const sortType = this.getAttribute('data-sort');
                    const rows = Array.from(table.querySelectorAll('tbody tr'));
                    const isAscending = !this.classList.contains('sorting-asc');

                    // Reset sort indicators
                    table.querySelectorAll('th').forEach(th => {
                        th.classList.remove('sorting-asc', 'sorting-desc');
                    });

                    // Update this header
                    this.classList.add(isAscending ? 'sorting-asc' : 'sorting-desc');

                    rows.sort((a, b) => {
                        let aValue, bValue;

                        if (sortType === 'date') {
                            aValue = parseFloat(a.querySelectorAll('td')[index].getAttribute('data-date')) || 0;
                            bValue = parseFloat(b.querySelectorAll('td')[index].getAttribute('data-date')) || 0;
                        } else {
                            // For text content
                            aValue = a.querySelectorAll('td')[index].textContent.trim().toLowerCase();
                            bValue = b.querySelectorAll('td')[index].textContent.trim().toLowerCase();

                            // For numeric content if needed
                            if (sortType === 'replies') {
                                aValue = parseInt(aValue) || 0;
                                bValue = parseInt(bValue) || 0;
                            }
                        }

                        if (aValue < bValue) return isAscending ? -1 : 1;
                        if (aValue > bValue) return isAscending ? 1 : -1;
                        return 0;
                    });

                    // Remove all rows
                    rows.forEach(row => row.remove());

                    // Add sorted rows
                    const tbody = table.querySelector('tbody');
                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            // Dropdown toggle functionality
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function(event) {
                    event.stopPropagation();

                    const dropdown = this.nextElementSibling;
                    const isOpen = dropdown.classList.contains('show');

                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });

                    // Toggle this dropdown
                    if (!isOpen) {
                        dropdown.classList.add('show');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            });
        });
    </script>
</div>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>