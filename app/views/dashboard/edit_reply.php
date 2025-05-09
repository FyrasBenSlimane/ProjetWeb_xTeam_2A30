<?php
// Set content to be passed to dashboard layout
ob_start();

/**
 * Admin Dashboard - Edit Reply
 * This form allows administrators to edit forum replies
 */

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
    redirect('users/login');
}
?>

<!-- Edit Reply Dashboard Content -->
<!-- Page Header -->
<div class="head-title">
    <div class="left">
        <h1>Edit Reply</h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a href="<?php echo URL_ROOT; ?>/dashboard/community">Community</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Edit Reply</a></li>
        </ul>
    </div>
    <div class="btn-download">
        <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="btn">
            <i class='bx bx-arrow-back'></i>
            <span class="text">Back to Community</span>
        </a>
    </div>
</div>

<!-- Display alert messages -->
<?php flash('dashboard_message'); ?>

<div class="card edit-reply-card">
    <div class="card-header">
        <h3>Edit Reply</h3>
        <div class="reply-meta">
            <span>Posted by: <strong><?php echo $data['reply']->author_name; ?></strong></span>
            <span>Created: <strong><?php echo date('M j, Y g:i A', strtotime($data['reply']->created_at)); ?></strong></span>
        </div>
    </div>
    <div class="card-body">
        <form action="<?php echo URL_ROOT; ?>/dashboard/manageReply/edit/<?php echo $data['reply']->id; ?>" method="POST">
            <div class="form-group">
                <label for="content">Reply Content</label>
                <textarea name="content" id="content" rows="10" class="form-control" required><?php echo htmlspecialchars($data['reply']->content); ?></textarea>
            </div>
            <div class="form-group">
                <input type="hidden" name="reply_id" value="<?php echo $data['reply']->id; ?>">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Reply Information -->
<div class="card reply-info">
    <div class="card-header">
        <h3>Reply Information</h3>
    </div>
    <div class="card-body">
        <div class="info-group">
            <div class="info-item">
                <span class="label">Reply ID:</span>
                <span class="value"><?php echo $data['reply']->id; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Topic:</span>
                <span class="value">
                    <a href="<?php echo URL_ROOT; ?>/community/topic/<?php echo $data['reply']->topic_slug ?? ''; ?>" target="_blank">
                        <?php echo htmlspecialchars($data['reply']->topic_title ?? 'Unknown Topic'); ?>
                    </a>
                </span>
            </div>
            <div class="info-item">
                <span class="label">Author:</span>
                <span class="value"><?php echo $data['reply']->author_name; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Author Role:</span>
                <span class="value"><?php echo ucfirst($data['reply']->account_type); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Created:</span>
                <span class="value"><?php echo date('M j, Y g:i A', strtotime($data['reply']->created_at)); ?></span>
            </div>
            <?php if ($data['reply']->is_edited): ?>
                <div class="info-item">
                    <span class="label">Last Edited:</span>
                    <span class="value"><?php echo date('M j, Y g:i A', strtotime($data['reply']->edited_at)); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <div class="admin-actions">
            <h4>Admin Actions</h4>
            <div class="action-buttons">
                <a href="<?php echo URL_ROOT; ?>/dashboard/manageReply/delete/<?php echo $data['reply']->id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this reply?');">
                    <i class='bx bxs-trash'></i> Delete Reply
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Edit Reply Styles */
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
        flex-wrap: wrap;
    }

    .card-header h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        width: 100%;
    }

    .reply-meta {
        display: flex;
        gap: 20px;
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--grey);
        border-radius: 5px;
        font-size: 14px;
        font-family: var(--font-family);
        resize: vertical;
    }

    .btn-primary {
        padding: 8px 16px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-secondary {
        padding: 8px 16px;
        background-color: var(--grey);
        color: var(--dark);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin-left: 10px;
    }

    .btn-danger {
        padding: 8px 16px;
        background-color: var(--danger);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .info-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item .label {
        font-weight: 600;
        color: #666;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .info-item .value {
        font-size: 15px;
    }

    .info-item .value a {
        color: var(--primary);
        text-decoration: none;
    }

    .info-item .value a:hover {
        text-decoration: underline;
    }

    .admin-actions {
        border-top: 1px solid var(--grey);
        margin-top: 20px;
        padding-top: 20px;
    }

    .admin-actions h4 {
        font-size: 16px;
        margin-bottom: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

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

    @media screen and (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .reply-meta {
            flex-direction: column;
            gap: 5px;
        }

        .info-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>