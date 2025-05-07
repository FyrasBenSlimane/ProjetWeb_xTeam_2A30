<?php
// Use buffers to store the dashboard content
ob_start();

// Get blog posts data from the controller (passed in $data)
$blogPosts = $data['posts'] ?? [];
?>

<div class="blog-management-page">
    <style>
        .blog-management-page {
            padding: 1.5rem 0;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .search-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .search-input {
            flex: 1;
            min-width: 200px;
            position: relative;
        }
        .search-input input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            padding-left: 2.25rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .search-input svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            height: 1rem;
            width: 1rem;
            color: #6b7280;
        }
        .filter-select {
            min-width: 150px;
        }
        .filter-select select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 10.5L3.5 6h9z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        .btn-create-post {
            background-color: rgb(5, 11, 31);
            color: white;
        }
        .btn-create-post:hover {
            background-color: rgb(10, 20, 50);
        }
        .blog-posts {
            margin-bottom: 2rem;
        }
        .blog-post {
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
            background-color: white;
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: #f9fafb;
            cursor: pointer;
        }
        .post-header-left {
            display: flex;
            flex-direction: column;
        }
        .post-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .post-header-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .post-status {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-published {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .status-draft {
            background-color: #fef9c3;
            color: #ca8a04;
        }
        .status-archived {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .post-content {
            display: none;
            padding: 1rem;
        }
        .post-content.active {
            display: block;
        }
        .content-preview {
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            max-height: 200px;
            overflow-y: auto;
        }
        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .tag {
            padding: 0.25rem 0.5rem;
            background-color: #e5e7eb;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        .post-management {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
        }
        .management-item {
            flex: 1;
            min-width: 200px;
        }
        .management-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .comments-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
        }
        .comments-header {
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .no-comments {
            color: #6b7280;
            font-style: italic;
        }
        .comment-item {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .comment-author {
            font-weight: 500;
        }
        .comment-status {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #ca8a04;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .comment-content {
            margin-bottom: 1rem;
        }
        .comment-actions {
            display: flex;
            gap: 0.5rem;
        }
        .comment-responses {
            margin-top: 1rem;
            margin-left: 1.5rem;
        }
        .response-title {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .response-item {
            background-color: #e0f2fe;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 0.75rem;
        }
        .response-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        .response-author {
            font-weight: 500;
        }
        .response-time {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .response-form {
            margin-top: 1rem;
        }
        .response-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            min-height: 80px;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .post-edit-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: 1rem;
        }
        .post-edit-modal.active {
            display: flex;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }
        .modal-body {
            padding: 1rem;
        }
        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-textarea {
            min-height: 200px;
        }
        .tag-input-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
        }
        .tag-chip {
            display: inline-flex;
            align-items: center;
            background-color: #e5e7eb;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }
        .tag-remove {
            margin-left: 0.25rem;
            cursor: pointer;
        }
        .tag-input {
            flex: 1;
            min-width: 100px;
            border: none;
            outline: none;
            font-size: 0.875rem;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .pagination button {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            background-color: white;
            border-radius: 0.375rem;
            cursor: pointer;
        }
        .pagination button.active {
            background-color: rgb(5, 11, 31);
            color: white;
            border-color: rgb(5, 11, 31);
        }
        .pagination button:hover:not(.active) {
            background-color: #f3f4f6;
        }
    </style>

    <!-- Blog Management Header -->
    <div class="section-header">
        <h2 class="section-title">Blog Management</h2>
        <button class="btn btn-primary btn-create-post" id="createPostBtn">Create New Post</button>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="postSearch" placeholder="Search posts by title or content">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="tagFilter">
                <option value="">All Tags</option>
                <?php
                // Get unique tags from all posts
                $allTags = [];
                foreach ($blogPosts as $post) {
                    $allTags = array_merge($allTags, $post['tags']);
                }
                $uniqueTags = array_unique($allTags);
                sort($uniqueTags);
                
                foreach ($uniqueTags as $tag) {
                    echo "<option value=\"{$tag}\">{$tag}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Blog Posts List -->
    <div class="blog-posts" id="blogPosts">
        <?php if (empty($blogPosts)): ?>
            <div class="empty-state">
                <p>No blog posts found. Click "Create New Post" to add your first post.</p>
            </div>
        <?php else: ?>
            <?php foreach ($blogPosts as $index => $post): ?>
                <div class="blog-post">
                    <div class="post-header" onclick="togglePost(<?php echo $post['id']; ?>)">
                        <div class="post-header-left">
                            <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                            <div class="post-meta">
                                <span>By <?php echo htmlspecialchars($post['authorName']); ?></span>
                                <span>
                                    <?php if ($post['status'] === 'published'): ?>
                                        Published: <?php echo date('F j, Y', strtotime($post['publishedAt'])); ?>
                                    <?php elseif ($post['status'] === 'draft'): ?>
                                        Draft created: <?php echo date('F j, Y', strtotime($post['createdAt'])); ?>
                                    <?php else: ?>
                                        Archived: <?php echo date('F j, Y', strtotime($post['updatedAt'])); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <div class="post-header-right">
                            <span class="post-status status-<?php echo $post['status']; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="post-content" id="post-<?php echo $post['id']; ?>">
                        <!-- Content Preview -->
                        <div>
                            <h4 class="management-label">Content Preview</h4>
                            <div class="content-preview">
                                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                            </div>
                        </div>
                        
                        <!-- Tags -->
                        <div>
                            <h4 class="management-label">Tags</h4>
                            <div class="tag-list">
                                <?php foreach ($post['tags'] as $tag): ?>
                                    <span class="tag"><?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Post Management -->
                        <div class="post-management">
                            <div class="management-item">
                                <label class="management-label" for="post-<?php echo $post['id']; ?>-status">Status</label>
                                <select class="form-select" id="post-<?php echo $post['id']; ?>-status" onchange="updatePostStatus(<?php echo $post['id']; ?>, this.value)">
                                    <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                    <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="archived" <?php echo $post['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                </select>
                            </div>
                            <div class="management-item">
                                <button class="btn btn-outline" onclick="editPost(<?php echo $post['id']; ?>)">Edit Post</button>
                            </div>
                            <div class="management-item">
                                <button class="btn btn-danger" onclick="deletePost(<?php echo $post['id']; ?>)">Delete Post</button>
                            </div>
                        </div>
                        
                        <!-- Comments Section -->
                        <div class="comments-section">
                            <h4 class="comments-header">Comments (<?php echo count($post['comments']); ?>)</h4>
                            <?php if (empty($post['comments'])): ?>
                                <p class="no-comments">No comments yet. Comments will appear once the post is published.</p>
                            <?php else: ?>
                                <div class="comment-list">
                                    <?php foreach ($post['comments'] as $commentIndex => $comment): ?>
                                        <div class="comment-item">
                                            <div class="comment-header">
                                                <span class="comment-author"><?php echo htmlspecialchars($comment['userName']); ?></span>
                                                <span class="comment-status status-<?php echo $comment['status']; ?>">
                                                    <?php echo ucfirst($comment['status']); ?>
                                                </span>
                                            </div>
                                            <div class="comment-content">
                                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                            </div>
                                            <div class="comment-actions">
                                                <button class="btn btn-sm" onclick="updateCommentStatus(<?php echo $post['id']; ?>, <?php echo $comment['id']; ?>, 'approved')">Approve</button>
                                                <button class="btn btn-sm btn-outline" onclick="updateCommentStatus(<?php echo $post['id']; ?>, <?php echo $comment['id']; ?>, 'rejected')">Reject</button>
                                            </div>
                                            
                                            <!-- Responses to this comment -->
                                            <?php if (!empty($comment['responses'])): ?>
                                                <div class="comment-responses">
                                                    <h5 class="response-title">Responses</h5>
                                                    <?php foreach ($comment['responses'] as $response): ?>
                                                        <div class="response-item">
                                                            <div class="response-header">
                                                                <span class="response-author"><?php echo htmlspecialchars($response['userName']); ?></span>
                                                                <span class="response-time">
                                                                    <?php echo date('F j, Y, g:i A', strtotime($response['createdAt'])); ?>
                                                                </span>
                                                            </div>
                                                            <div class="response-content">
                                                                <?php echo nl2br(htmlspecialchars($response['content'])); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Response Form -->
                                            <div class="response-form">
                                                <textarea class="response-textarea" id="response-<?php echo $post['id']; ?>-<?php echo $comment['id']; ?>" placeholder="Write a response..."></textarea>
                                                <button class="btn btn-sm" onclick="respondToComment(<?php echo $post['id']; ?>, <?php echo $comment['id']; ?>)">Respond</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <!-- Pagination will be added with JavaScript if needed based on the number of posts -->
    </div>

    <!-- Post Edit Modal -->
    <div class="post-edit-modal" id="postEditModal">
        <div class="modal-content">
            <form id="postForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Edit Post</h3>
                    <button type="button" class="modal-close" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="postId" value="">
                    <div class="form-group">
                        <label class="form-label" for="postTitle">Title</label>
                        <input type="text" id="postTitle" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="postContent">Content</label>
                        <textarea id="postContent" class="form-textarea"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="postTags">Tags</label>
                        <div class="tag-input-container" id="tagContainer">
                            <input type="text" id="tagInput" class="tag-input" placeholder="Add a tag and press Enter">
                        </div>
                        <input type="hidden" id="postTags" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="postStatus">Status</label>
                        <select id="postStatus" class="form-select">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="savePostBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle post content visibility
            window.togglePost = function(postId) {
                const content = document.getElementById(`post-${postId}`);
                
                if (content.classList.contains('active')) {
                    content.classList.remove('active');
                } else {
                    // Close any open posts first (optional)
                    document.querySelectorAll('.post-content.active').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    content.classList.add('active');
                }
            };
            
            // Post status update
            window.updatePostStatus = function(postId, status) {
                // Show loading indicator
                showToast('Processing', 'Updating post status...', 'info');
                
                // Create the updates object with only the status property
                const updates = {
                    status: status
                };
                
                // Send AJAX request to update post status
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('postId', postId);
                formData.append('status', status);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/updateBlogPost`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Update the UI
                                const postItem = document.getElementById(`post-${postId}`).closest('.blog-post');
                                const statusBadge = postItem.querySelector('.post-status');
                                
                                // Remove all status classes
                                statusBadge.classList.remove('status-published', 'status-draft', 'status-archived');
                                // Add the new status class
                                statusBadge.classList.add(`status-${status}`);
                                // Update the text
                                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                                
                                // Update post meta if publishing
                                if (status === 'published') {
                                    const metaInfo = postItem.querySelector('.post-meta');
                                    const today = new Date();
                                    const dateString = today.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                    
                                    // Find the second span (date info) and update it
                                    const dateSpan = metaInfo.querySelector('span:nth-child(2)');
                                    if (dateSpan) {
                                        dateSpan.textContent = `Published: ${dateString}`;
                                    }
                                }
                                
                                showToast('Success', `Post status changed to ${status}`, 'success');
                            } else {
                                showToast('Error', response.message || 'Failed to update post status', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to update post status', 'error');
                        }
                    } else {
                        showToast('Error', 'Server error occurred', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                xhr.send(formData);
            };
            
            // Comment status update
            window.updateCommentStatus = function(postId, commentId, status) {
                // Show loading indicator
                showToast('Processing', 'Updating comment status...', 'info');
                
                // Send AJAX request to update comment status
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/updateBlogComment`, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Get the comment item
                                const postContent = document.getElementById(`post-${postId}`);
                                const commentItems = postContent.querySelectorAll('.comment-item');
                                let commentItem = null;
                                
                                // Find the comment by ID (more reliable than index)
                                commentItems.forEach(item => {
                                    const approveBtn = item.querySelector(`button[onclick*="updateCommentStatus(${postId}, ${commentId}"]`);
                                    if (approveBtn) {
                                        commentItem = item;
                                    }
                                });
                                
                                if (commentItem) {
                                    const statusBadge = commentItem.querySelector('.comment-status');
                                    
                                    // Remove all status classes
                                    statusBadge.classList.remove('status-approved', 'status-pending', 'status-rejected');
                                    // Add the new status class
                                    statusBadge.classList.add(`status-${status}`);
                                    // Update the text
                                    statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                                    
                                    showToast('Success', `Comment status changed to ${status}`, 'success');
                                }
                            } else {
                                showToast('Error', response.message || 'Failed to update comment status', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to update comment status', 'error');
                        }
                    } else {
                        showToast('Error', 'Server error occurred', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                // Format data for urlencoded form submission
                const data = `postId=${postId}&commentId=${commentId}&status=${status}`;
                xhr.send(data);
            };
            
            // Form Validation for Post Edit Modal
            const postForm = document.getElementById('postForm');
            postForm.addEventListener('submit', function(event) {
                let isValid = true;
                const postTitle = document.getElementById('postTitle');
                const postContent = document.getElementById('postContent');
                const postStatus = document.getElementById('postStatus');

                // Clear previous errors
                clearError(postTitle);
                clearError(postContent);
                clearError(postStatus);

                if (postTitle.value.trim() === '') {
                    showError(postTitle, 'Title is required.');
                    isValid = false;
                }

                if (postContent.value.trim() === '') {
                    showError(postContent, 'Content is required.');
                    isValid = false;
                }

                if (postStatus.value === '') {
                    showError(postStatus, 'Status is required.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                } else {
                    // If valid, proceed with the existing AJAX submission logic (or standard form submission if not using AJAX)
                    // The original savePost function handles the AJAX part
                    event.preventDefault(); // Prevent default only if using AJAX
                    savePost(); // Call the function that handles AJAX submission
                }
            });

            function showError(inputElement, message) {
                const formGroup = inputElement.closest('.form-group');
                let errorElement = formGroup.querySelector('.error-message');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'error-message';
                    errorElement.style.color = 'red';
                    errorElement.style.fontSize = '0.8em';
                    errorElement.style.marginTop = '0.25rem';
                    // Insert error message after the input/textarea/select
                    inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
                }
                errorElement.textContent = message;
                inputElement.style.borderColor = 'red';
            }

            function clearError(inputElement) {
                const formGroup = inputElement.closest('.form-group');
                const errorElement = formGroup.querySelector('.error-message');
                if (errorElement) {
                    errorElement.remove();
                }
                inputElement.style.borderColor = ''; // Reset border color
            }

            // Respond to comment
            window.respondToComment = function(postId, commentId) {
                const responseText = document.getElementById(`response-${postId}-${commentId}`).value.trim();
                
                if (!responseText) {
                    showToast('Error', 'Response cannot be empty', 'error');
                    return;
                }
                
                // Show loading indicator
                showToast('Processing', 'Sending response...', 'info');
                
                // Send AJAX request to add comment response
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('postId', postId);
                formData.append('commentId', commentId);
                formData.append('response', responseText);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/respondToBlogComment`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Get the comment item
                                const postContent = document.getElementById(`post-${postId}`);
                                const commentItems = postContent.querySelectorAll('.comment-item');
                                let commentItem = null;
                                
                                // Find the comment by ID (more reliable than index)
                                commentItems.forEach(item => {
                                    const approveBtn = item.querySelector(`button[onclick*="updateCommentStatus(${postId}, ${commentId}"]`);
                                    if (approveBtn) {
                                        commentItem = item;
                                    }
                                });
                                
                                if (commentItem) {
                                    // Check if responses container exists, if not create it
                                    let responsesContainer = commentItem.querySelector('.comment-responses');
                                    if (!responsesContainer) {
                                        responsesContainer = document.createElement('div');
                                        responsesContainer.className = 'comment-responses';
                                        responsesContainer.innerHTML = '<h5 class="response-title">Responses</h5>';
                                        
                                        // Insert before the response form
                                        const responseForm = commentItem.querySelector('.response-form');
                                        commentItem.insertBefore(responsesContainer, responseForm);
                                    }
                                    
                                    // Create new response
                                    const now = new Date();
                                    const timeString = now.toLocaleString('en-US', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                    
                                    const responseItem = document.createElement('div');
                                    responseItem.className = 'response-item';
                                    responseItem.innerHTML = `
                                        <div class="response-header">
                                            <span class="response-author">Admin</span>
                                            <span class="response-time">${timeString}</span>
                                        </div>
                                        <div class="response-content">
                                            ${responseText}
                                        </div>
                                    `;
                                    
                                    // Add before the response form
                                    responsesContainer.appendChild(responseItem);
                                    
                                    // Clear the textarea
                                    document.getElementById(`response-${postId}-${commentId}`).value = '';
                                    
                                    showToast('Success', 'Your response has been added', 'success');
                                }
                            } else {
                                showToast('Error', response.message || 'Failed to add response', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to add response', 'error');
                        }
                    } else {
                        showToast('Error', 'Server error occurred', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                xhr.send(formData);
            };
            
            // Delete post
            window.deletePost = function(postId) {
                if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                    // Show loading indicator
                    showToast('Processing', 'Deleting post...', 'info');
                    
                    // Send AJAX request to delete post
                    const xhr = new XMLHttpRequest();
                    const formData = new FormData();
                    formData.append('postId', postId);
                    
                    xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/deleteBlogPost`, true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                
                                if (response.success) {
                                    // Remove from the UI
                                    const postItem = document.getElementById(`post-${postId}`).closest('.blog-post');
                                    postItem.remove();
                                    
                                    showToast('Success', 'The post has been removed', 'success');
                                    
                                    // If no more posts, show empty state
                                    if (document.querySelectorAll('.blog-post').length === 0) {
                                        const emptyState = document.createElement('div');
                                        emptyState.className = 'empty-state';
                                        emptyState.innerHTML = '<p>No blog posts found. Click "Create New Post" to add your first post.</p>';
                                        document.getElementById('blogPosts').appendChild(emptyState);
                                    }
                                } else {
                                    showToast('Error', response.message || 'Failed to delete post', 'error');
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                                showToast('Error', 'Failed to delete post', 'error');
                            }
                        } else {
                            showToast('Error', 'Server error occurred', 'error');
                        }
                    };
                    
                    xhr.onerror = function() {
                        showToast('Error', 'Network error occurred', 'error');
                    };
                    
                    xhr.send(formData);
                }
            };
            
            // Edit post
            window.editPost = function(postId) {
                // Show loading indicator
                showToast('Processing', 'Loading post data...', 'info');
                
                // Send AJAX request to get post data
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `${document.querySelector('meta[name="root-url"]').content}/dashboard/getBlogPost?postId=${postId}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const postData = JSON.parse(xhr.responseText);
                            
                            if (postData.success) {
                                const post = postData.data;
                                
                                // Populate the form
                                document.getElementById('postId').value = post.id;
                                document.getElementById('postTitle').value = post.title;
                                document.getElementById('postContent').value = post.content;
                                document.getElementById('postStatus').value = post.status;
                                
                                // Clear existing tags and add new ones
                                const tagContainer = document.getElementById('tagContainer');
                                // Remove all child nodes except the last one (input)
                                while (tagContainer.childNodes.length > 1) {
                                    tagContainer.removeChild(tagContainer.firstChild);
                                }
                                
                                // Add tags
                                post.tags.forEach(tag => {
                                    const tagChip = document.createElement('span');
                                    tagChip.className = 'tag-chip';
                                    tagChip.innerHTML = `${tag} <span class="tag-remove" onclick="removeTag(this)">×</span>`;
                                    tagContainer.insertBefore(tagChip, document.getElementById('tagInput'));
                                });
                                
                                // Update the hidden input
                                document.getElementById('postTags').value = post.tags.join(',');
                                
                                // Set modal title
                                document.getElementById('modalTitle').textContent = 'Edit Post';
                                
                                // Show the modal
                                document.getElementById('postEditModal').classList.add('active');
                            } else {
                                showToast('Error', postData.message || 'Failed to load post data', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing post data:', e);
                            showToast('Error', 'Failed to load post data', 'error');
                        }
                    } else {
                        showToast('Error', 'Server error occurred', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                xhr.send();
            };
            
            // Create new post
            document.getElementById('createPostBtn').addEventListener('click', function() {
                // Reset the form
                document.getElementById('postForm').reset();
                document.getElementById('postId').value = '';
                
                // Clear tags
                const tagContainer = document.getElementById('tagContainer');
                while (tagContainer.childNodes.length > 1) {
                    tagContainer.removeChild(tagContainer.firstChild);
                }
                document.getElementById('postTags').value = '';
                
                // Set modal title
                document.getElementById('modalTitle').textContent = 'Create New Post';
                
                // Show the modal
                document.getElementById('postEditModal').classList.add('active');
            });
            
            // Close modal
            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('postEditModal').classList.remove('active');
            });
            
            document.getElementById('cancelBtn').addEventListener('click', function() {
                document.getElementById('postEditModal').classList.remove('active');
            });
            
            // Save post
            document.getElementById('postForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading indicator
                showToast('Processing', 'Saving post...', 'info');
                
                const postId = document.getElementById('postId').value;
                const isNew = !postId;
                
                // Get form values
                const title = document.getElementById('postTitle').value;
                const content = document.getElementById('postContent').value;
                const tags = document.getElementById('postTags').value.split(',').filter(tag => tag.trim() !== '');
                const status = document.getElementById('postStatus').value;
                
                // Send AJAX request to save post
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                
                if (!isNew) {
                    formData.append('postId', postId);
                }
                
                formData.append('title', title);
                formData.append('content', content);
                formData.append('tags', JSON.stringify(tags));
                formData.append('status', status);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/${isNew ? 'createBlogPost' : 'updateBlogPost'}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                showToast(
                                    'Success',
                                    isNew ? 'Your new post has been created' : 'Your post has been updated',
                                    'success'
                                );
                                
                                // Close the modal
                                document.getElementById('postEditModal').classList.remove('active');
                                
                                // Refresh the page to show the updated post list
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                showToast('Error', response.message || 'Failed to save post', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to save post', 'error');
                        }
                    } else {
                        showToast('Error', 'Server error occurred', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                xhr.send(formData);
            });
            
            // Tag input handling
            document.getElementById('tagInput').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    
                    const tagText = this.value.trim();
                    if (tagText) {
                        // Create a new tag chip
                        const tagChip = document.createElement('span');
                        tagChip.className = 'tag-chip';
                        tagChip.innerHTML = `${tagText} <span class="tag-remove" onclick="removeTag(this)">×</span>`;
                        
                        // Add it before the input
                        const tagContainer = document.getElementById('tagContainer');
                        tagContainer.insertBefore(tagChip, this);
                        
                        // Clear the input
                        this.value = '';
                        
                        // Update the hidden input with all tags
                        updateTagsInput();
                    }
                }
            });
            
            // Remove tag function
            window.removeTag = function(element) {
                // Remove the parent tag chip
                element.parentNode.remove();
                
                // Update the hidden input
                updateTagsInput();
            };
            
            // Update tags hidden input
            function updateTagsInput() {
                const tagChips = document.querySelectorAll('.tag-chip');
                const tags = Array.from(tagChips).map(chip => {
                    // Get the text content without the × character
                    return chip.textContent.trim().slice(0, -1).trim();
                });
                
                document.getElementById('postTags').value = tags.join(',');
            }
            
            // Post filtering
            document.getElementById('postSearch').addEventListener('input', filterPosts);
            document.getElementById('statusFilter').addEventListener('change', filterPosts);
            document.getElementById('tagFilter').addEventListener('change', filterPosts);
            
            function filterPosts() {
                const searchTerm = document.getElementById('postSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                const tagFilter = document.getElementById('tagFilter').value.toLowerCase();
                
                const posts = document.querySelectorAll('.blog-post');
                let visibleCount = 0;
                
                posts.forEach(post => {
                    const title = post.querySelector('.post-title').textContent.toLowerCase();
                    const content = post.querySelector('.content-preview') ? 
                                  post.querySelector('.content-preview').textContent.toLowerCase() : '';
                    const statusElement = post.querySelector('.post-status');
                    const status = statusElement ? statusElement.textContent.toLowerCase() : '';
                    
                    // Check if post has the tag (if filter is active)
                    let hasTag = true;
                    if (tagFilter) {
                        const tagElements = post.querySelectorAll('.tag');
                        hasTag = Array.from(tagElements).some(tag => 
                            tag.textContent.toLowerCase() === tagFilter
                        );
                    }
                    
                    const contentMatch = title.includes(searchTerm) || content.includes(searchTerm);
                    const statusMatch = !statusFilter || status.includes(statusFilter);
                    
                    if (contentMatch && statusMatch && hasTag) {
                        post.style.display = '';
                        visibleCount++;
                    } else {
                        post.style.display = 'none';
                    }
                });
                
                // Show a message if no posts are visible
                const emptyState = document.querySelector('.empty-state');
                const blogPosts = document.getElementById('blogPosts');
                
                if (visibleCount === 0 && !emptyState) {
                    const noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'empty-state';
                    noResultsMsg.innerHTML = '<p>No posts match your search filters. Try adjusting your search criteria.</p>';
                    blogPosts.appendChild(noResultsMsg);
                } else if (visibleCount > 0 && emptyState) {
                    emptyState.remove();
                }
            }
        });
    </script>
</div>

<?php
// Store the dashboard content in the $content variable
$content = ob_get_clean();

// Include the dashboard layout
require_once 'dashboard_layout.php';
?>