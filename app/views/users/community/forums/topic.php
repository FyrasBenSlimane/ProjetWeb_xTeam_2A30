<?php require APPROOT . '/views/layouts/header.php'; ?>

<!-- Include Bootstrap Icons for enhanced UI -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Include custom forum CSS -->
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/forum.css">

<div class="container py-4">
    <?php flash('post_message'); ?>

    <!-- Topic Navigation with breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forums">Forums</a></li>
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/forumCategory/<?php echo $data['topic']->category_slug; ?>"><?php echo $data['topic']->category_name; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $data['topic']->title; ?></li>
        </ol>
    </nav>

    <!-- Topic Header with enhanced UI -->
    <div class="card shadow-sm mb-4 topic-header">
        <div class="card-header bg-light d-flex justify-content-between align-items-center position-relative">
            <!-- Topic Status Badges -->
            <div class="d-flex align-items-center gap-2">
                <h3 class="mb-0"><?php echo $data['topic']->title; ?></h3>

                <?php if ($data['topic']->status == 'pinned') : ?>
                    <span class="badge bg-warning text-dark" title="Pinned Topic">
                        <i class="bi bi-pin-angle me-1"></i> Pinned
                    </span>
                <?php endif; ?>

                <?php if ($data['topic']->status == 'closed') : ?>
                    <span class="badge bg-secondary" title="Closed Topic">
                        <i class="bi bi-lock me-1"></i> Closed
                    </span>
                <?php endif; ?>

                <?php if (isset($data['topic']->has_solution) && $data['topic']->has_solution): ?>
                    <span class="badge bg-success" title="Solved Topic">
                        <i class="bi bi-check-circle me-1"></i> Solved
                    </span>
                <?php endif; ?>
            </div>

            <!-- Topic Actions Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="topicActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="topicActionsDropdown">
                    <?php if (isLoggedIn()) : ?>
                        <li>
                            <a class="dropdown-item bookmark-action" href="#" data-topic-id="<?php echo $data['topic']->id; ?>">
                                <i class="bi bi-bookmark me-2"></i> Bookmark Topic
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item subscribe-action" href="#" data-topic-id="<?php echo $data['topic']->id; ?>">
                                <i class="bi bi-bell me-2"></i> Subscribe to Updates
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    <?php endif; ?>

                    <li>
                        <a class="dropdown-item" href="#" id="shareTopic" data-bs-toggle="modal" data-bs-target="#shareModal">
                            <i class="bi bi-share me-2"></i> Share Topic
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" id="printTopic">
                            <i class="bi bi-printer me-2"></i> Print Topic
                        </a>
                    </li>

                    <?php if (isAdmin() || (isLoggedIn() && $_SESSION['user_id'] == $data['topic']->user_id)) : ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/community/editTopic/<?php echo $data['topic']->id; ?>">
                                <i class="bi bi-pencil-square me-2"></i> Edit Topic
                            </a>
                        </li>
                        <?php if ($data['topic']->status == 'closed') : ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo URLROOT; ?>/community/openTopic/<?php echo $data['topic']->id; ?>">
                                    <i class="bi bi-unlock me-2"></i> Reopen Topic
                                </a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo URLROOT; ?>/community/closeTopic/<?php echo $data['topic']->id; ?>">
                                    <i class="bi bi-lock me-2"></i> Close Topic
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                <i class="bi bi-trash me-2"></i> Delete Topic
                            </a>
                        </li>
                        <?php if (isAdmin()) : ?>
                            <li>
                                <a class="dropdown-item toggle-pin-action" href="#" data-topic-id="<?php echo $data['topic']->id; ?>" data-status="<?php echo $data['topic']->status == 'pinned' ? 'pinned' : 'normal'; ?>">
                                    <i class="bi bi-pin me-2"></i>
                                    <?php echo $data['topic']->status == 'pinned' ? 'Unpin Topic' : 'Pin Topic'; ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item toggle-close-action" href="#" data-topic-id="<?php echo $data['topic']->id; ?>" data-status="<?php echo $data['topic']->status == 'closed' ? 'closed' : 'open'; ?>">
                                    <i class="bi bi-<?php echo $data['topic']->status == 'closed' ? 'unlock' : 'lock'; ?> me-2"></i>
                                    <?php echo $data['topic']->status == 'closed' ? 'Reopen Topic' : 'Close Topic'; ?>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" id="deleteTopic" data-topic-id="<?php echo $data['topic']->id; ?>">
                                    <i class="bi bi-trash me-2"></i> Delete Topic
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Topic Meta Information -->
        <div class="card-body pb-0 pt-3">
            <div class="d-flex justify-content-between flex-wrap mb-2">
                <div>
                    <span class="badge bg-light text-dark me-2">
                        <i class="bi bi-grid me-1"></i> <?php echo $data['topic']->category_name; ?>
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-tag me-1"></i> <?php echo isset($data['topic']->tags) ? $data['topic']->tags : 'No tags'; ?>
                    </span>
                </div>
                <div>
                    <span class="text-muted small">
                        <i class="bi bi-eye me-1"></i> <?php echo $data['topic']->views; ?> views
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Topic starter post with enhanced UI -->
    <div class="card shadow-sm mb-4 post-card" id="post-<?php echo $data['topic']->id; ?>">
        <div class="card-body pb-0">
            <div class="row">
                <!-- Author sidebar -->
                <div class="col-md-2 mb-3 mb-md-0">
                    <div class="text-center">
                        <img src="<?php echo !empty($data['topic']->author_image) ? URLROOT . '/public/uploads/' . $data['topic']->author_image : URLROOT . '/public/images/default-profile.png'; ?>"
                            alt="Profile" class="rounded-circle mb-2" width="64" height="64">
                        <div>
                            <div class="fw-bold"><?php echo $data['topic']->author_name; ?></div>
                            <div class="badge <?php echo isAdmin($data['topic']->user_id) ? 'bg-danger' : 'bg-primary'; ?> mb-1">
                                <?php echo isAdmin($data['topic']->user_id) ? 'Admin' : 'Member'; ?>
                            </div>
                        </div>
                        <div class="text-muted small">
                            Member since:<br>
                            <?php echo date('M Y', strtotime($data['topic']->author_joined)); ?>
                        </div>
                        <?php if (isset($data['topic']->author_posts)): ?>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    <?php echo $data['topic']->author_posts; ?> posts
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Post content -->
                <div class="col-md-10 border-start">
                    <!-- Post header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">
                            Posted: <?php echo date('F j, Y, g:i a', strtotime($data['topic']->created_at)); ?>
                        </span>
                        <div class="dropdown post-actions">
                            <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#post-<?php echo $data['topic']->id; ?>" onclick="copyLink('<?php echo URLROOT; ?>/community/topic/<?php echo $data['topic']->slug; ?>#post-<?php echo $data['topic']->id; ?>')">
                                        <i class="bi bi-link-45deg me-2"></i> Copy Link
                                    </a>
                                </li>
                                <?php if (isLoggedIn() && ($_SESSION['user_id'] == $data['topic']->user_id || isAdmin())): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/community/editTopic/<?php echo $data['topic']->id; ?>">
                                            <i class="bi bi-pencil me-2"></i> Edit
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (isAdmin()): ?>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" id="deletePost" data-post-id="<?php echo $data['topic']->id; ?>">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Post content -->
                    <div class="post-content mb-4">
                        <?php echo htmlspecialchars_decode($data['topic']->content); ?>
                    </div>

                    <!-- Attachments if any -->
                    <?php if (isset($data['topic']->attachments) && !empty($data['topic']->attachments)): ?>
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <i class="bi bi-paperclip me-1"></i> Attachments
                            </div>
                            <div class="card-body py-2">
                                <?php foreach ($data['topic']->attachments as $attachment): ?>
                                    <div class="d-flex align-items-center py-1">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        <a href="<?php echo URLROOT; ?>/public/uploads/forum_attachments/<?php echo $attachment->filename; ?>" target="_blank">
                                            <?php echo $attachment->original_name; ?>
                                        </a>
                                        <span class="ms-2 text-muted small">(<?php echo formatFileSize($attachment->filesize); ?>)</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Post footer with voting and interactions -->
                    <div class="d-flex justify-content-between align-items-center py-3 border-top mt-2">
                        <div class="post-votes d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-success me-1 vote-btn" data-post-id="<?php echo $data['topic']->id; ?>" data-vote="up">
                                <i class="bi bi-hand-thumbs-up"></i>
                                <span class="vote-count upvote-count"><?php echo isset($data['topic']->upvotes) ? $data['topic']->upvotes : 0; ?></span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger me-3 vote-btn" data-post-id="<?php echo $data['topic']->id; ?>" data-vote="down">
                                <i class="bi bi-hand-thumbs-down"></i>
                                <span class="vote-count downvote-count"><?php echo isset($data['topic']->downvotes) ? $data['topic']->downvotes : 0; ?></span>
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if (!$data['topic']->status == 'closed' && isLoggedIn()): ?>
                                <button class="btn btn-sm btn-outline-primary quick-reply-btn" data-post-id="<?php echo $data['topic']->id; ?>">
                                    <i class="bi bi-reply me-1"></i> Reply
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-secondary share-btn" data-post-id="<?php echo $data['topic']->id; ?>">
                                <i class="bi bi-share me-1"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replies Section with enhanced UI -->
    <?php if (!empty($data['replies'])): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-chat-square-text me-2"></i> Replies
                    <span class="badge bg-primary rounded-pill ms-2"><?php echo count($data['replies']); ?></span>
                </h5>

                <!-- Sort options -->
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-sort-down me-1"></i> Sort By
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item sort-replies active" data-sort="newest">Newest First</button></li>
                        <li><button class="dropdown-item sort-replies" data-sort="oldest">Oldest First</button></li>
                        <li><button class="dropdown-item sort-replies" data-sort="votes">Most Votes</button></li>
                    </ul>
                </div>
            </div>

            <!-- Replies list container -->
            <div class="list-group list-group-flush replies-container">
                <?php foreach ($data['replies'] as $reply): ?>
                    <div class="list-group-item p-0 reply-item" id="post-<?php echo $reply->id; ?>" data-reply-id="<?php echo $reply->id; ?>" data-votes="<?php echo isset($reply->votes) ? $reply->votes : 0; ?>" data-date="<?php echo strtotime($reply->created_at); ?>">
                        <div class="card-body pb-0">
                            <div class="row">
                                <!-- Reply author sidebar -->
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <img src="<?php echo !empty($reply->author_image) ? URLROOT . '/public/uploads/' . $reply->author_image : URLROOT . '/public/images/default-profile.png'; ?>"
                                            alt="Profile" class="rounded-circle mb-2" width="48" height="48">
                                        <div>
                                            <div class="fw-bold"><?php echo $reply->author_name; ?></div>
                                            <div class="badge <?php echo isAdmin($reply->user_id) ? 'bg-danger' : 'bg-primary'; ?> mb-1">
                                                <?php echo isAdmin($reply->user_id) ? 'Admin' : 'Member'; ?>
                                            </div>
                                        </div>
                                        <?php if (isset($reply->author_posts)): ?>
                                            <div class="mt-1">
                                                <span class="badge bg-light text-dark">
                                                    <?php echo $reply->author_posts; ?> posts
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Reply content -->
                                <div class="col-md-10 border-start">
                                    <!-- Reply header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted small">
                                            <?php echo date('F j, Y, g:i a', strtotime($reply->created_at)); ?>
                                            <?php if (isset($reply->is_edited) && $reply->is_edited): ?>
                                                <span class="fst-italic">(edited)</span>
                                            <?php endif; ?>

                                            <?php if (isset($reply->is_solution) && $reply->is_solution): ?>
                                                <span class="badge bg-success ms-2">
                                                    <i class="bi bi-check-circle me-1"></i> Solution
                                                </span>
                                            <?php endif; ?>
                                        </span>

                                        <!-- Reply actions -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#post-<?php echo $reply->id; ?>" onclick="copyLink('<?php echo URLROOT; ?>/community/topic/<?php echo $data['topic']->slug; ?>#post-<?php echo $reply->id; ?>')">
                                                        <i class="bi bi-link-45deg me-2"></i> Copy Link
                                                    </a>
                                                </li>

                                                <?php if (isLoggedIn() && $_SESSION['user_id'] == $data['topic']->user_id && !$data['topic']->status == 'closed'): ?>
                                                    <li>
                                                        <a class="dropdown-item mark-solution-action" href="#" data-reply-id="<?php echo $reply->id; ?>" data-is-solution="<?php echo isset($reply->is_solution) && $reply->is_solution ? 'true' : 'false'; ?>">
                                                            <i class="bi bi-check-circle me-2"></i>
                                                            <?php echo isset($reply->is_solution) && $reply->is_solution ? 'Unmark as Solution' : 'Mark as Solution'; ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if (isLoggedIn() && ($_SESSION['user_id'] == $reply->user_id || isAdmin())): ?>
                                                    <li>
                                                        <a class="dropdown-item edit-reply-action" href="#" data-reply-id="<?php echo $reply->id; ?>">
                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-reply-action" href="#" data-reply-id="<?php echo $reply->id; ?>">
                                                            <i class="bi bi-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Reply content -->
                                    <div class="post-content mb-4 reply-content-<?php echo $reply->id; ?>">
                                        <?php echo htmlspecialchars_decode($reply->content); ?>
                                    </div>

                                    <!-- Reply edit form - hidden by default -->
                                    <div class="edit-reply-form-container-<?php echo $reply->id; ?> d-none">
                                        <form class="edit-reply-form" data-reply-id="<?php echo $reply->id; ?>">
                                            <div class="mb-3">
                                                <textarea class="form-control edit-reply-content" rows="5"><?php echo htmlspecialchars($reply->content); ?></textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save me-1"></i> Save Changes
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary cancel-edit" data-reply-id="<?php echo $reply->id; ?>">
                                                    <i class="bi bi-x me-1"></i> Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Reply attachments if any -->
                                    <?php if (isset($reply->attachments) && !empty($reply->attachments)): ?>
                                        <div class="card bg-light mb-3">
                                            <div class="card-header">
                                                <i class="bi bi-paperclip me-1"></i> Attachments
                                            </div>
                                            <div class="card-body py-2">
                                                <?php foreach ($reply->attachments as $attachment): ?>
                                                    <div class="d-flex align-items-center py-1">
                                                        <i class="bi bi-file-earmark me-2"></i>
                                                        <a href="<?php echo URLROOT; ?>/public/uploads/forum_attachments/<?php echo $attachment->filename; ?>" target="_blank">
                                                            <?php echo $attachment->original_name; ?>
                                                        </a>
                                                        <span class="ms-2 text-muted small">(<?php echo formatFileSize($attachment->filesize); ?>)</span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Reply footer with interaction elements -->
                                    <div class="d-flex justify-content-between align-items-center py-3 border-top mt-2">
                                        <div class="post-votes d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-success me-1 vote-btn" data-post-id="<?php echo $reply->id; ?>" data-vote="up">
                                                <i class="bi bi-hand-thumbs-up"></i>
                                                <span class="vote-count upvote-count"><?php echo isset($reply->upvotes) ? $reply->upvotes : 0; ?></span>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger me-3 vote-btn" data-post-id="<?php echo $reply->id; ?>" data-vote="down">
                                                <i class="bi bi-hand-thumbs-down"></i>
                                                <span class="vote-count downvote-count"><?php echo isset($reply->downvotes) ? $reply->downvotes : 0; ?></span>
                                            </button>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <?php if (!$data['topic']->status == 'closed' && isLoggedIn()): ?>
                                                <button class="btn btn-sm btn-outline-primary quote-reply-btn" data-reply-id="<?php echo $reply->id; ?>" data-author="<?php echo $reply->author_name; ?>">
                                                    <i class="bi bi-quote me-1"></i> Quote
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-secondary share-btn" data-post-id="<?php echo $reply->id; ?>">
                                                <i class="bi bi-share me-1"></i> Share
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body py-4 text-center">
                <img src="<?php echo URLROOT; ?>/public/images/empty-state.svg" alt="No Replies" class="mb-3" width="120">
                <h5>No Replies Yet</h5>
                <p class="text-muted">Be the first to respond to this topic!</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Reply Form with enhanced UI -->
    <?php if ($data['topic']->status != 'closed' && isLoggedIn()): ?>
        <div class="card shadow-sm mb-4" id="reply-form-container">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-reply me-2"></i> Reply to this Topic</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/community/addReply/<?php echo $data['topic']->id; ?>" method="post" id="replyForm" enctype="multipart/form-data">
                    <input type="hidden" name="topic_id" value="<?php echo $data['topic']->id; ?>">
                    <input type="hidden" id="replyQuote" name="quote" value="">

                    <!-- Quick reply quote preview - hidden by default -->
                    <div class="quick-reply-quote mb-3 d-none">
                        <div class="card bg-light">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between">
                                    <div class="small fw-bold quote-author"></div>
                                    <button type="button" class="btn-close btn-sm" id="removeQuote"></button>
                                </div>
                                <div class="quote-text small"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control rich-editor" id="replyContent" name="content" rows="6" placeholder="Write your reply here..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments (Optional)</label>
                        <input class="form-control" type="file" id="attachments" name="attachments[]" multiple>
                        <div class="text-muted small mt-1">Maximum 3 files, 2MB each. Allowed types: jpg, png, gif, pdf</div>
                    </div>

                    <div class="d-flex flex-wrap-reverse flex-md-nowrap justify-content-between gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="bold">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="italic">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="link">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="image">
                                <i class="bi bi-image"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="code">
                                <i class="bi bi-code-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm toolbar-btn" data-tag="quote">
                                <i class="bi bi-chat-quote"></i>
                            </button>
                        </div>

                        <div>
                            <button type="button" class="btn btn-outline-secondary preview-btn me-2">
                                <i class="bi bi-eye me-1"></i> Preview
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i> Post Reply
                            </button>
                        </div>
                    </div>

                    <!-- Preview area - hidden by default -->
                    <div class="mt-3 preview-area d-none">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Preview</h6>
                            </div>
                            <div class="card-body preview-content">
                                <!-- Preview content will be inserted here -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif ($data['topic']->status == 'closed'): ?>
        <div class="alert alert-secondary">
            <i class="bi bi-lock me-2"></i> This topic is closed. No new replies can be added.
        </div>
    <?php elseif (!isLoggedIn()): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <i class="bi bi-shield-lock fs-2 text-muted mb-3"></i>
                <h5>Login Required</h5>
                <p class="mb-3">You need to be logged in to reply to this topic.</p>
                <a href="<?php echo URLROOT; ?>/users/login?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                </a>
                <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-outline-primary ms-2">
                    Register
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Related Topics -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Related Topics</h5>
        </div>
        <div class="list-group list-group-flush related-topics">
            <?php if (isset($data['relatedTopics']) && !empty($data['relatedTopics'])): ?>
                <?php foreach ($data['relatedTopics'] as $relatedTopic): ?>
                    <a href="<?php echo URLROOT; ?>/community/topic/<?php echo $relatedTopic->slug; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?php echo $relatedTopic->title; ?></h6>
                            <small class="text-muted"><?php echo timeElapsed($relatedTopic->created_at); ?></small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted"><?php echo $relatedTopic->category_name; ?></small>
                            <small class="text-muted"><i class="bi bi-chat me-1"></i><?php echo $relatedTopic->reply_count; ?> replies</small>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item text-center py-3">No related topics found</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share This Topic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="shareLink" class="form-label">Topic Link</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareLink" value="<?php echo URLROOT . '/community/topic/' . $data['topic']->slug; ?>" readonly>
                        <button class="btn btn-outline-primary copy-link-btn" type="button">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(URLROOT . '/community/topic/' . $data['topic']->slug); ?>" target="_blank" class="btn btn-outline-primary mx-1">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(URLROOT . '/community/topic/' . $data['topic']->slug); ?>&text=<?php echo urlencode($data['topic']->title); ?>" target="_blank" class="btn btn-outline-primary mx-1">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(URLROOT . '/community/topic/' . $data['topic']->slug); ?>&title=<?php echo urlencode($data['topic']->title); ?>" target="_blank" class="btn btn-outline-primary mx-1">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode($data['topic']->title); ?>&body=<?php echo urlencode('Check out this topic: ' . URLROOT . '/community/topic/' . $data['topic']->slug); ?>" class="btn btn-outline-primary mx-1">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteModalBody">
                Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Include custom JS -->
<script src="<?php echo URLROOT; ?>/public/js/forum.js"></script>
<script>
    // Set the AJAX URL for forum JS
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Forum !== 'undefined') {
            Forum.config.ajaxUrl = '<?php echo URLROOT; ?>/community/';
            Forum.initializeTopicPage(<?php echo $data['topic']->id; ?>);
        }

        // Handle copy link function
        window.copyLink = function(url) {
            navigator.clipboard.writeText(url).then(() => {
                // Create a temporary "Copied!" tooltip
                const tooltip = document.createElement('div');
                tooltip.textContent = 'Link copied!';
                tooltip.classList.add('copy-tooltip');
                document.body.appendChild(tooltip);

                setTimeout(() => {
                    tooltip.classList.add('show');
                    setTimeout(() => {
                        tooltip.classList.remove('show');
                        setTimeout(() => {
                            document.body.removeChild(tooltip);
                        }, 300);
                    }, 1500);
                }, 10);
            });
        };

        // Handle copy button in share modal
        document.querySelector('.copy-link-btn')?.addEventListener('click', function() {
            const linkInput = document.getElementById('shareLink');
            linkInput.select();
            navigator.clipboard.writeText(linkInput.value).then(() => {
                const copyBtn = document.querySelector('.copy-link-btn');
                const originalContent = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => {
                    copyBtn.innerHTML = originalContent;
                }, 1500);
            });
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>