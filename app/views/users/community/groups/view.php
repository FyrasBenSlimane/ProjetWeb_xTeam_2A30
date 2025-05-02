<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/community/groups">Groups</a></li>
            <li class="breadcrumb-item active"><?php echo $data['group']->name; ?></li>
        </ol>
    </nav>

    <?php flash('post_message'); ?>

    <!-- Group Header -->
    <div class="card shadow-sm mb-4">
        <div class="position-relative">
            <?php if (!empty($data['group']->cover_image)) : ?>
                <img src="<?php echo URLROOT; ?>/public/uploads/group_covers/<?php echo $data['group']->cover_image; ?>" class="card-img-top" alt="Group Cover" style="height: 180px; object-fit: cover;">
            <?php else : ?>
                <div class="bg-light text-center py-5">
                    <i class="bi bi-people fs-1 text-muted"></i>
                </div>
            <?php endif; ?>
            <?php if ($data['group']->is_private) : ?>
                <span class="position-absolute top-0 end-0 badge bg-warning m-3" title="Private Group">
                    <i class="bi bi-lock"></i> Private Group
                </span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0"><?php echo $data['group']->name; ?></h2>
                <?php if (isLoggedIn()) : ?>
                    <?php if ($data['isUserMember']) : ?>
                        <button class="btn btn-success" disabled>
                            <i class="bi bi-check-circle"></i> Member
                        </button>
                    <?php else : ?>
                        <a href="<?php echo URLROOT; ?>/community/joinGroup/<?php echo $data['group']->id; ?>" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Join Group
                        </a>
                    <?php endif; ?>
                <?php else : ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-primary">
                        Login to Join
                    </a>
                <?php endif; ?>
            </div>
            <p class="text-muted"><?php echo $data['group']->description; ?></p>
            <div class="d-flex align-items-center mt-3">
                <div class="me-4">
                    <small class="text-muted d-block">Created by</small>
                    <div class="d-flex align-items-center">
                        <img src="<?php echo !empty($data['group']->creator_image) ? URLROOT . '/public/uploads/' . $data['group']->creator_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-2" width="30" height="30" alt="Creator">
                        <span><?php echo $data['group']->creator_name; ?></span>
                    </div>
                </div>
                <div class="me-4">
                    <small class="text-muted d-block">Members</small>
                    <span class="fw-bold"><?php echo count($data['members']); ?></span>
                </div>
                <div>
                    <small class="text-muted d-block">Created on</small>
                    <span><?php echo date('M j, Y', strtotime($data['group']->created_at)); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Posts Section -->
        <div class="col-lg-8">
            <?php if ($data['isUserMember']) : ?>
                <!-- Post Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Create Post</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/community/addPost/<?php echo $data['group']->id; ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" placeholder="Share something with the group..."></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3">
                                    <label for="attachment" class="form-label visually-hidden">Add Attachment</label>
                                    <input class="form-control form-control-sm" type="file" id="attachment" name="attachment">
                                    <small class="form-text text-muted">Max file size: 5MB</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Posts List -->
            <h4 class="mb-3">Group Posts</h4>
            <?php if (empty($data['posts'])) : ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <p class="mb-0">No posts yet. Be the first to post in this group!</p>
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($data['posts'] as $post) : ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <img src="<?php echo !empty($post->author_image) ? URLROOT . '/public/uploads/' . $post->author_image : URLROOT . '/public/images/default-profile.png'; ?>" alt="Profile" class="rounded-circle me-3" width="50" height="50">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><?php echo $post->author_name; ?></h5>
                                        <small class="text-muted"><?php echo timeElapsed($post->created_at); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content mb-3">
                                <p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                                <?php if (!empty($post->attachment)) : ?>
                                    <div class="attachment mt-3">
                                        <?php
                                        $ext = pathinfo($post->attachment, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                        ?>

                                        <?php if ($isImage) : ?>
                                            <img src="<?php echo URLROOT; ?>/public/uploads/group_attachments/<?php echo $post->attachment; ?>" class="img-fluid rounded" alt="Attachment">
                                        <?php else : ?>
                                            <div class="card">
                                                <div class="card-body d-flex align-items-center">
                                                    <i class="bi bi-file-earmark fs-1 me-3"></i>
                                                    <div>
                                                        <p class="mb-1"><?php echo $post->attachment; ?></p>
                                                        <a href="<?php echo URLROOT; ?>/public/uploads/group_attachments/<?php echo $post->attachment; ?>" class="btn btn-sm btn-outline-primary" download>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-chat"></i>
                                        Comment (<?php echo $post->comment_count; ?>)
                                    </button>
                                </div>

                                <?php if (isLoggedIn() && ($_SESSION['user_id'] == $post->user_id || isAdmin())) : ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/community/deletePost/<?php echo $post->id; ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete Post</a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Members List -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Members</h5>
                    <span class="badge bg-primary"><?php echo count($data['members']); ?></span>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($data['members'] as $member) : ?>
                        <div class="list-group-item d-flex align-items-center">
                            <img src="<?php echo !empty($member->profile_image) ? URLROOT . '/public/uploads/' . $member->profile_image : URLROOT . '/public/images/default-profile.png'; ?>" class="rounded-circle me-3" width="40" height="40" alt="Member">
                            <div class="flex-grow-1">
                                <h6 class="mb-0"><?php echo $member->name; ?></h6>
                                <?php if ($member->role == 'admin') : ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php elseif ($member->role == 'moderator') : ?>
                                    <span class="badge bg-warning text-dark">Moderator</span>
                                <?php endif; ?>
                            </div>
                            <?php if (isLoggedIn() && $_SESSION['user_id'] == $data['group']->creator_id && $_SESSION['user_id'] != $member->user_id) : ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if ($member->role != 'moderator') : ?>
                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/community/makeModerator/<?php echo $member->id; ?>">Make Moderator</a></li>
                                        <?php else : ?>
                                            <li><a class="dropdown-item" href="<?php echo URLROOT; ?>/community/removeModerator/<?php echo $member->id; ?>">Remove as Moderator</a></li>
                                        <?php endif; ?>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/community/removeMember/<?php echo $data['group']->id; ?>/<?php echo $member->user_id; ?>" onclick="return confirm('Are you sure you want to remove this member?')">Remove from Group</a></li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($data['members']) > 10) : ?>
                    <div class="card-footer bg-light text-center">
                        <a href="#" class="btn btn-sm btn-link">View All Members</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Group Actions -->
            <?php if (isLoggedIn() && $_SESSION['user_id'] == $data['group']->creator_id) : ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Group Admin</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo URLROOT; ?>/community/editGroup/<?php echo $data['group']->id; ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-gear me-2"></i> Edit Group Settings
                        </a>
                        <a href="<?php echo URLROOT; ?>/community/manageMembers/<?php echo $data['group']->id; ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-people me-2"></i> Manage Members
                        </a>
                        <?php if ($data['group']->is_private) : ?>
                            <a href="<?php echo URLROOT; ?>/community/pendingRequests/<?php echo $data['group']->id; ?>" class="list-group-item list-group-item-action">
                                <i class="bi bi-person-plus me-2"></i> Pending Join Requests
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo URLROOT; ?>/community/deleteGroup/<?php echo $data['group']->id; ?>" class="list-group-item list-group-item-action text-danger" onclick="return confirm('Are you sure you want to delete this group? This action cannot be undone.')">
                            <i class="bi bi-trash me-2"></i> Delete Group
                        </a>
                    </div>
                </div>
            <?php elseif ($data['isUserMember']) : ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Member Actions</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo URLROOT; ?>/community/leaveGroup/<?php echo $data['group']->id; ?>" class="list-group-item list-group-item-action text-danger" onclick="return confirm('Are you sure you want to leave this group?')">
                            <i class="bi bi-box-arrow-right me-2"></i> Leave Group
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>