<?php
// Community Forum page
?>

<script>
// Global function for handling fetch responses with JSON parsing
function handleFetchResponse(response) {
    // Check if response is OK
    if (!response.ok) {
        throw new Error(`Server returned ${response.status}: ${response.statusText}`);
    }
    
    // Try to parse JSON, but handle potential errors
    return response.text().then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Invalid JSON response:', text.substring(0, 100));
            throw new Error('Server returned invalid JSON response');
        }
    });
}

// Global function for opening thread modals - defined outside DOMContentLoaded
function openThreadModal(threadUrl) {
    console.log("Opening modal for thread:", threadUrl);
    // Get the modal element
    const threadModal = document.getElementById('thread-modal');
    if (!threadModal) {
        console.error('Thread modal element not found');
        return;
    }
    
    // Show modal
    threadModal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Show loading state
    document.getElementById('thread-modal-title').textContent = 'Loading...';
    document.getElementById('thread-modal-content').innerHTML = '<div class="thread-loading" style="text-align: center; padding: 2rem;"><p>Loading thread...</p></div>';
    document.getElementById('thread-modal-replies-container').innerHTML = '';
    
    // Ensure threadUrl has format=json parameter
    const url = new URL(threadUrl, window.location.origin);
    url.searchParams.set('format', 'json');
    
    // Fetch thread data
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(handleFetchResponse)
    .then(data => {
        console.log("Thread data received:", data);
        
        // Debug: Log the replies to check is_own_reply property
        console.log("Replies received:", data.replies);
        if (data.replies && data.replies.length > 0) {
            console.log("First reply is_own_reply:", data.replies[0].is_own_reply);
        }
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to load thread');
        }
        
        // Update thread title
        document.getElementById('thread-modal-title').textContent = data.thread.title;
        
        // Create thread content HTML
        const threadContentHtml = `
            <div class="thread-view-content">
                <div class="thread-meta" style="margin-bottom: 1rem;">
                    <div class="thread-author" style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                        <img src="${data.thread.author_avatar || 'https://randomuser.me/api/portraits/men/1.jpg'}" 
                             alt="${data.thread.author_name}" class="author-avatar" style="margin-right: 0.5rem;">
                        <span class="author-name">${data.thread.author_name}</span>
                        <span class="thread-time" style="margin-left: 0.5rem; color: #6b7280;">
                            ${new Date(data.thread.created_at).toLocaleString()}
                        </span>
                    </div>
                    <div class="thread-category" style="margin-bottom: 0.75rem;">
                        <span class="thread-tag">
                            ${data.thread.category_name}
                        </span>
                    </div>
                </div>
                <div class="thread-body" style="line-height: 1.6; margin-bottom: 1.5rem;">
                    ${data.thread.content.replace(/\n/g, '<br>')}
                </div>
            </div>
            <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #e5e7eb;">
        `;
        
        document.getElementById('thread-modal-content').innerHTML = threadContentHtml;
        
        // Update hidden thread ID for reply form
        if (document.getElementById('thread-modal-thread-id')) {
            document.getElementById('thread-modal-thread-id').value = data.thread.id;
        }
        
        // Create replies HTML
        if (data.replies && data.replies.length > 0) {
            const repliesHtml = data.replies.map(reply => `
                <div class="reply-item" style="padding: 1rem; margin-bottom: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background-color: white;" data-reply-id="${reply.id}">
                    <div class="reply-meta" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <div class="reply-author" style="display: flex; align-items: center;">
                            <img src="${reply.author_avatar || 'https://randomuser.me/api/portraits/men/1.jpg'}" 
                                 alt="${reply.author_name}" class="author-avatar" style="margin-right: 0.5rem;">
                            <span class="author-name">${reply.author_name}</span>
                            <span class="reply-time" style="margin-left: 0.5rem; color: #6b7280;">
                                ${new Date(reply.created_at).toLocaleString()}
                            </span>
                        </div>
                        <div class="reply-actions">
                            ${reply.is_own_reply ? `
                                <button class="edit-reply-btn" data-reply-id="${reply.id}" style="background: none; border: none; color: #4b5563; margin-right: 0.5rem; cursor: pointer; font-size: 0.875rem; padding: 5px 8px; border-radius: 4px; transition: all 0.2s ease;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <button class="delete-reply-btn" data-reply-id="${reply.id}" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.875rem; padding: 5px 8px; border-radius: 4px; transition: all 0.2s ease;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            ` : ''}
                            ${reply.is_solution ? `
                                <div class="solution-badge" style="background-color: #10b981; color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                                    Solution
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="reply-content" style="line-height: 1.6;">
                        ${reply.content.replace(/\n/g, '<br>')}
                    </div>
                    <div class="reply-edit-form" style="display: none; margin-top: 1rem;">
                        <textarea class="edit-reply-content form-control" rows="3">${reply.content}</textarea>
                        <div class="form-error edit-reply-error" style="margin-top: 0.25rem;"></div>
                        <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <button class="cancel-edit-btn btn-secondary" style="font-size: 0.875rem;">Cancel</button>
                            <button class="save-edit-btn btn-primary" style="font-size: 0.875rem;">Save Changes</button>
                        </div>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('thread-modal-replies-container').innerHTML = repliesHtml;
            
            // Add event listeners for edit and delete buttons
            document.querySelectorAll('.edit-reply-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const replyId = this.getAttribute('data-reply-id');
                    const replyItem = document.querySelector(`.reply-item[data-reply-id="${replyId}"]`);
                    const replyContent = replyItem.querySelector('.reply-content');
                    const editForm = replyItem.querySelector('.reply-edit-form');
                    
                    // Toggle visibility
                    replyContent.style.display = 'none';
                    editForm.style.display = 'block';
                    
                    // Focus the textarea
                    const textarea = editForm.querySelector('.edit-reply-content');
                    textarea.focus();
                });
            });
            
            document.querySelectorAll('.cancel-edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const replyItem = this.closest('.reply-item');
                    const replyContent = replyItem.querySelector('.reply-content');
                    const editForm = replyItem.querySelector('.reply-edit-form');
                    
                    // Toggle visibility
                    replyContent.style.display = 'block';
                    editForm.style.display = 'none';
                    
                    // Reset any error messages
                    editForm.querySelector('.edit-reply-error').textContent = '';
                });
            });
            
            document.querySelectorAll('.save-edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const replyItem = this.closest('.reply-item');
                    const replyId = replyItem.getAttribute('data-reply-id');
                    const textarea = replyItem.querySelector('.edit-reply-content');
                    const errorElement = replyItem.querySelector('.edit-reply-error');
                    
                    // Validate content
                    const content = textarea.value.trim();
                    if (!content) {
                        errorElement.textContent = 'Reply cannot be empty';
                        return;
                    }
                    
                    if (content.length < 10) {
                        errorElement.textContent = 'Reply must be at least 10 characters';
                        return;
                    }
                    
                    // Disable button while saving
                    this.disabled = true;
                    this.textContent = 'Saving...';
                    
                    // Send update request
                    fetch(`<?php echo URL_ROOT; ?>/forum/editReply/${replyId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            'content': content
                        })
                    })
                    .then(handleFetchResponse)
                    .then(data => {
                        // Re-enable button
                        this.disabled = false;
                        this.textContent = 'Save Changes';
                        
                        if (data.success) {
                            // Update the content display
                            const replyContent = replyItem.querySelector('.reply-content');
                            replyContent.innerHTML = data.reply.content.replace(/\n/g, '<br>');
                            
                            // Hide edit form
                            replyContent.style.display = 'block';
                            replyItem.querySelector('.reply-edit-form').style.display = 'none';
                            
                            // Show success message
                            const successMessage = document.createElement('div');
                            successMessage.style.padding = '0.5rem';
                            successMessage.style.marginTop = '0.5rem';
                            successMessage.style.backgroundColor = '#d1fae5';
                            successMessage.style.color = '#065f46';
                            successMessage.style.borderRadius = '0.375rem';
                            successMessage.textContent = 'Reply updated successfully!';
                            
                            replyContent.after(successMessage);
                            
                            // Remove success message after 3 seconds
                            setTimeout(() => {
                                successMessage.remove();
                            }, 3000);
                        } else {
                            errorElement.textContent = data.message || 'Failed to update reply';
                        }
                    })
                    .catch(error => {
                        console.error('Error updating reply:', error);
                        this.disabled = false;
                        this.textContent = 'Save Changes';
                        errorElement.textContent = 'An error occurred. Please try again.';
                    });
                });
            });
            
            document.querySelectorAll('.delete-reply-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const replyId = this.getAttribute('data-reply-id');
                    
                    if (confirm('Are you sure you want to delete this reply? This cannot be undone.')) {
                        // Send delete request
                        fetch(`<?php echo URL_ROOT; ?>/forum/deleteReply/${replyId}`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(handleFetchResponse)
                        .then(data => {
                            if (data.success) {
                                // Remove the reply from the DOM
                                const replyItem = document.querySelector(`.reply-item[data-reply-id="${replyId}"]`);
                                replyItem.remove();
                                
                                // If no replies left, show no replies message
                                const repliesContainer = document.getElementById('thread-modal-replies-container');
                                if (repliesContainer.children.length === 0) {
                                    repliesContainer.innerHTML = `
                                        <div class="no-replies" style="text-align: center; padding: 2rem; color: #6b7280;">
                                            <p>No replies yet. Be the first to respond!</p>
                                        </div>
                                    `;
                                }
                            } else {
                                alert(data.message || 'Failed to delete reply');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting reply:', error);
                            alert('An error occurred while deleting the reply. Please try again.');
                        });
                    }
                });
            });
        } else {
            document.getElementById('thread-modal-replies-container').innerHTML = `
                <div class="no-replies" style="text-align: center; padding: 2rem; color: #6b7280;">
                    <p>No replies yet. Be the first to respond!</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading thread:', error);
        document.getElementById('thread-modal-content').innerHTML = `
            <div class="error" style="text-align: center; padding: 2rem; color: #ef4444;">
                <p>${error.message || 'Error loading thread content. Please try again later.'}</p>
            </div>
        `;
    });
}

// Safely check and set isLandingPage only once at global scope
if (typeof window.isLandingPage === 'undefined') {
    window.isLandingPage = false;
}
</script>

<style>
    /* Global styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .content-max-width {
        max-width: 1024px;
        margin: 0 auto;
    }

    /* Page layout */
    .page-container {
        background-color: #f9fafb;
        padding: 3rem 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .page-description {
        color: #6b7280;
        margin-bottom: 2rem;
        max-width: 768px;
    }

    /* Search and filter */
    .search-filter-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .search-container {
        position: relative;
        flex-grow: 1;
    }

    .search-input {
        width: 100%;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }

    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 1rem;
        height: 1rem;
    }

    .filter-container {
        display: flex;
        gap: 0.5rem;
    }

    .filter-button {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
    }

    .filter-button-primary {
        background-color: #2c3e50;
        color: white;
        border: none;
    }

    .filter-button-secondary {
        background-color: white;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    /* Categories */
    .categories-container {
        margin-bottom: 2rem;
    }

    .categories-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .categories-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .category-tag {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 9999px;
    }

    .category-tag-active {
        background-color: #2c3e50;
        color: white;
    }

    .category-tag-inactive {
        background-color: #f3f4f6;
        color: #4b5563;
    }

    .category-tag-inactive:hover {
        background-color: #e5e7eb;
    }

    /* Thread items */
    .thread-container {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .thread-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .thread-item {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        transition: all 0.2s ease;
    }

    .thread-item:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .thread-pinned {
        border-left: 4px solid #2c3e50;
    }

    .thread-content {
        display: flex;
        gap: 0.75rem;
    }

    .vote-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 2.5rem;
    }

    .vote-button {
        color: #6b7280;
    }

    .vote-button:hover {
        color: #2c3e50;
    }

    .vote-count {
        font-weight: 600;
        color: #1f2937;
    }

    .thread-main {
        flex-grow: 1;
    }

    .thread-tags {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .thread-tag {
        background-color: #f3f4f6;
        color: #4b5563;
        font-size: 0.75rem;
        padding: 0.125rem 0.5rem;
        border-radius: 0.25rem;
    }

    .thread-tag-pinned {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .thread-title {
        font-weight: 500;
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .thread-title a {
        color: #111827;
        text-decoration: none;
    }

    .thread-title a:hover {
        color: #2c3e50;
    }

    .thread-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .thread-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .author-avatar {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 9999px;
        object-fit: cover;
    }

    .author-name {
        color: #4b5563;
    }

    .thread-time {
        color: #6b7280;
    }

    .thread-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #6b7280;
    }

    .thread-stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* CTA section */
    .cta-container {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
    }

    .cta-title {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .cta-description {
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .cta-button {
        background-color: #2c3e50;
        color: white;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        border-radius: 0.375rem;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }

    .cta-button:hover {
        background-color: #34495e;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pagination-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination-link {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        color: #4b5563;
        text-decoration: none;
    }

    .pagination-link:hover {
        background-color: #f3f4f6;
    }

    .pagination-link-active {
        background-color: #2c3e50;
        color: white;
    }

    .pagination-arrow {
        padding: 0.5rem;
        border-radius: 0.375rem;
        color: #6b7280;
    }

    .pagination-arrow:hover {
        background-color: #f3f4f6;
    }

    .pagination-text {
        padding: 0.5rem 1rem;
        color: #6b7280;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background-color: white;
        border-radius: 0.5rem;
        width: 100%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-20px);
        transition: all 0.3s;
    }

    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .modal-close {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        font-size: 1.5rem;
        line-height: 1;
    }

    .modal-close:hover {
        color: #1f2937;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #2c3e50;
        box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        background-color: white;
    }

    .form-error {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
    }

    .btn-primary {
        background-color: #2c3e50;
        color: white;
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #34495e;
    }

    /* Additional styles for reply buttons */
    .edit-reply-btn:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    .delete-reply-btn:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    .edit-reply-btn:active, .delete-reply-btn:active {
        transform: translateY(1px);
    }

    /* Responsive */
    @media (min-width: 768px) {
        .search-filter-container {
            flex-direction: row;
        }
    }
</style>

<div class="page-container">
    <div class="container content-max-width">
        <h1 class="page-title">Community Forum</h1>
        <p class="page-description">
            Join discussions, ask questions, and share knowledge with other professionals.
        </p>
        
        <!-- Search and Filter -->
        <div class="search-filter-container">
            <div class="search-container">
                <form action="<?php echo URL_ROOT; ?>/forum/search" method="GET">
                    <input type="text" name="q" placeholder="Search discussions..." class="search-input">
                    <svg xmlns="http://www.w3.org/2000/svg" class="search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>
            
            <div class="filter-container">
                <button class="filter-button filter-button-primary">Latest</button>
                <button class="filter-button filter-button-secondary">Top</button>
                <button class="filter-button filter-button-secondary">Categories</button>
            </div>
        </div>
        
        <!-- Forum Categories -->
        <div class="categories-container">
            <h2 class="categories-title">Categories</h2>
            <div class="categories-list">
                <a href="<?php echo URL_ROOT; ?>/forum" class="category-tag category-tag-active">All</a>
                
                <?php if(isset($data['categories']) && is_array($data['categories'])): ?>
                    <?php foreach ($data['categories'] as $category): ?>
                    <a href="<?php echo URL_ROOT; ?>/forum/category/<?php echo $category->slug; ?>" class="category-tag category-tag-inactive">
                        <?php echo $category->name; ?>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Featured Discussions -->
        <div class="thread-container">
            <h2 class="section-title">Featured Discussions</h2>
            
            <div class="thread-list">
                <?php if(isset($data['featuredThreads']) && is_array($data['featuredThreads']) && count($data['featuredThreads']) > 0): ?>
                    <?php foreach ($data['featuredThreads'] as $thread): ?>
                    <div class="thread-item thread-pinned">
                        <div class="thread-content">
                            <div class="vote-container">
                                <div class="vote-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </div>
                                <div class="vote-count"><?php echo $thread->reply_count; ?></div>
                                <div class="vote-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="thread-main">
                                <div class="thread-tags">
                                    <span class="thread-tag">
                                        <?php echo $thread->category_name; ?>
                                    </span>
                                    <?php if ($thread->is_pinned): ?>
                                    <span class="thread-tag thread-tag-pinned">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        Pinned
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 class="thread-title">
                                    <a href="#" onclick="openThreadModal('<?php echo URL_ROOT; ?>/forum/thread/<?php echo $thread->slug; ?>'); return false;" class="thread-link">
                                        <?php echo $thread->title; ?>
                                    </a>
                                </h3>
                                
                                <div class="thread-meta">
                                    <div class="thread-author">
                                        <img src="<?php echo !empty($thread->author_avatar) ? $thread->author_avatar : 'https://randomuser.me/api/portraits/men/1.jpg'; ?>" 
                                             alt="<?php echo $thread->author_name; ?>" class="author-avatar">
                                        <span class="author-name"><?php echo $thread->author_name; ?></span>
                                        <span class="thread-time"><?php echo timeAgo($thread->created_at); ?></span>
                                    </div>
                                    
                                    <div class="thread-stats">
                                        <span class="thread-stat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            <?php echo $thread->reply_count; ?>
                                        </span>
                                        <span class="thread-stat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <?php echo $thread->view_count; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-threads" style="text-align: center; padding: 2rem; background-color: white; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                        <p style="color: #6b7280;">No featured discussions available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Discussions -->
        <div class="thread-container">
            <h2 class="section-title">Recent Discussions</h2>
            
            <div class="thread-list">
                <?php if(isset($data['recentThreads']) && is_array($data['recentThreads']) && count($data['recentThreads']) > 0): ?>
                    <?php foreach ($data['recentThreads'] as $thread): ?>
                    <div class="thread-item">
                        <div class="thread-content">
                            <div class="vote-container">
                                <div class="vote-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </div>
                                <div class="vote-count"><?php echo $thread->reply_count; ?></div>
                                <div class="vote-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="thread-main">
                                <div class="thread-tags">
                                    <span class="thread-tag">
                                        <?php echo $thread->category_name; ?>
                                    </span>
                                </div>
                                
                                <h3 class="thread-title">
                                    <a href="#" onclick="openThreadModal('<?php echo URL_ROOT; ?>/forum/thread/<?php echo $thread->slug; ?>'); return false;" class="thread-link">
                                        <?php echo $thread->title; ?>
                                    </a>
                                </h3>
                                
                                <div class="thread-meta">
                                    <div class="thread-author">
                                        <img src="<?php echo !empty($thread->author_avatar) ? $thread->author_avatar : 'https://randomuser.me/api/portraits/men/1.jpg'; ?>" 
                                             alt="<?php echo $thread->author_name; ?>" class="author-avatar">
                                        <span class="author-name"><?php echo $thread->author_name; ?></span>
                                        <span class="thread-time"><?php echo timeAgo($thread->created_at); ?></span>
                                    </div>
                                    
                                    <div class="thread-stats">
                                        <span class="thread-stat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            <?php echo $thread->reply_count; ?>
                                        </span>
                                        <span class="thread-stat">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <?php echo $thread->view_count; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-threads" style="text-align: center; padding: 2rem; background-color: white; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                        <p style="color: #6b7280;">No recent discussions available. Start a new discussion to get the conversation going!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Start Discussion CTA -->
        <?php if ($data['is_logged_in']): ?>
        <div class="cta-container">
            <h2 class="cta-title">Have a question or something to share?</h2>
            <p class="cta-description">Start a new discussion and get answers from the community.</p>
            <button id="open-thread-modal" class="cta-button">
                Start a New Discussion
            </button>
        </div>
        <?php else: ?>
        <div class="cta-container">
            <h2 class="cta-title">Join the conversation</h2>
            <p class="cta-description">Sign in to start discussions and participate in the community.</p>
            <a href="<?php echo URL_ROOT; ?>/users/login" class="cta-button">
                Sign In to Participate
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <div class="pagination">
            <nav class="pagination-nav" aria-label="Pagination">
                <a href="#" class="pagination-arrow">
                    <span class="sr-only">Previous</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="pagination-link pagination-link-active">1</a>
                <a href="#" class="pagination-link">2</a>
                <a href="#" class="pagination-link">3</a>
                <span class="pagination-text">...</span>
                <a href="#" class="pagination-link">8</a>
                <a href="#" class="pagination-arrow">
                    <span class="sr-only">Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
        </div>
    </div>
</div>

<!-- Create Thread Modal -->
<?php if ($data['is_logged_in']): ?>
<div id="create-thread-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Create New Discussion</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="create-thread-form">
                <div class="form-group">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="What's your discussion about?">
                    <div class="form-error" id="title-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Select a category</option>
                        <?php if(isset($data['categories']) && is_array($data['categories'])): ?>
                            <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="form-error" id="category-error"></div>
                </div>
                
                <div class="form-group">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="6" placeholder="Describe your question or discussion topic..."></textarea>
                    <div class="form-error" id="content-error"></div>
                </div>
            </form>
            <div id="create-thread-status" style="margin-top: 1rem; display: none;"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" id="close-thread-modal">Cancel</button>
            <button type="button" class="btn-primary" id="submit-thread">Create Thread</button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Thread View Modal - Now outside the conditional block so it's always available -->
<div id="thread-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 800px;">
        <div class="modal-header">
            <h3 class="modal-title" id="thread-modal-title">Thread Title</h3>
            <button class="modal-close" id="close-thread-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="thread-modal-content">
                <!-- Thread content will be loaded here -->
                <div class="thread-loading" style="text-align: center; padding: 2rem;">
                    <p>Loading thread...</p>
                </div>
            </div>
            
            <div id="thread-modal-replies" class="mt-4">
                <h4 class="replies-title" style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Replies</h4>
                <div id="thread-modal-replies-container">
                    <!-- Replies will be loaded here -->
                </div>
            </div>
            
            <?php if ($data['is_logged_in']): ?>
            <div id="thread-modal-reply-form" class="mt-4">
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Add Your Reply</h4>
                <form id="thread-modal-reply-form-element" class="mt-2">
                    <input type="hidden" id="thread-modal-thread-id" name="thread_id" value="">
                    <div class="form-group">
                        <textarea id="thread-modal-reply-content" name="content" class="form-control" rows="4" placeholder="Share your thoughts or answer the question..."></textarea>
                        <div class="form-error" id="thread-modal-reply-error"></div>
                    </div>
                    <button type="submit" class="btn-primary">Post Reply</button>
                </form>
            </div>
            <?php else: ?>
            <div class="mt-4 text-center p-3" style="background-color: #f3f4f6; border-radius: 0.375rem;">
                <p>You need to be logged in to reply to this thread.</p>
                <a href="<?php echo URL_ROOT; ?>/users/login" class="btn-primary" style="display: inline-block; margin-top: 0.5rem;">Sign In to Participate</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create Thread Modal
        const createModal = document.getElementById('create-thread-modal');
        const openCreateModalBtn = document.getElementById('open-thread-modal');
        const closeCreateModalBtn = document.getElementById('close-thread-modal');
        const closeCreateModalX = document.querySelector('#create-thread-modal .modal-close');
        const createForm = document.getElementById('create-thread-form');
        const submitThreadBtn = document.getElementById('submit-thread');
        const createThreadStatus = document.getElementById('create-thread-status');
        
        // Form field references
        const titleField = document.getElementById('title');
        const categoryField = document.getElementById('category_id');
        const contentField = document.getElementById('content');
        
        // Validation rules
        const validation = {
            title: {
                min: 10,
                max: 255,
                required: true,
                errorMsg: {
                    required: 'Please enter a title',
                    min: 'Title must be at least 10 characters',
                    max: 'Title must be less than 255 characters'
                }
            },
            category: {
                required: true,
                errorMsg: {
                    required: 'Please select a category'
                }
            },
            content: {
                min: 20,
                required: true,
                errorMsg: {
                    required: 'Please enter content',
                    min: 'Content must be at least 20 characters'
                }
            },
            reply: {
                min: 10,
                required: true,
                errorMsg: {
                    required: 'Please enter a reply',
                    min: 'Reply must be at least 10 characters'
                }
            }
        };
        
        // Input event listeners for real-time validation
        if (titleField) {
            titleField.addEventListener('input', function() {
                validateField('title', this.value.trim());
            });
        }
        
        if (categoryField) {
            categoryField.addEventListener('change', function() {
                validateField('category', this.value);
            });
        }
        
        if (contentField) {
            contentField.addEventListener('input', function() {
                validateField('content', this.value.trim());
            });
        }
        
        // Validation function
        function validateField(field, value) {
            let errorElement;
            let isValid = true;
            let errorMessage = '';
            
            switch(field) {
                case 'title':
                    errorElement = document.getElementById('title-error');
                    
                    if (validation.title.required && value === '') {
                        isValid = false;
                        errorMessage = validation.title.errorMsg.required;
                    } else if (value.length < validation.title.min) {
                        isValid = false;
                        errorMessage = validation.title.errorMsg.min;
                    } else if (validation.title.max && value.length > validation.title.max) {
                        isValid = false;
                        errorMessage = validation.title.errorMsg.max;
                    }
                    break;
                    
                case 'category':
                    errorElement = document.getElementById('category-error');
                    
                    if (validation.category.required && value === '') {
                        isValid = false;
                        errorMessage = validation.category.errorMsg.required;
                    }
                    break;
                    
                case 'content':
                    errorElement = document.getElementById('content-error');
                    
                    if (validation.content.required && value === '') {
                        isValid = false;
                        errorMessage = validation.content.errorMsg.required;
                    } else if (value.length < validation.content.min) {
                        isValid = false;
                        errorMessage = validation.content.errorMsg.min;
                    }
                    break;
                    
                case 'reply':
                    errorElement = document.getElementById('reply-content-error');
                    
                    if (validation.reply.required && value === '') {
                        isValid = false;
                        errorMessage = validation.reply.errorMsg.required;
                    } else if (value.length < validation.reply.min) {
                        isValid = false;
                        errorMessage = validation.reply.errorMsg.min;
                    }
                    break;
            }
            
            if (errorElement) {
                errorElement.textContent = errorMessage;
            }
            
            return isValid;
        }
        
        // Open create modal
        if (openCreateModalBtn) {
            openCreateModalBtn.addEventListener('click', function() {
                createModal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });
        }
        
        // Close create modal with button
        if (closeCreateModalBtn) {
            closeCreateModalBtn.addEventListener('click', function() {
                createModal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Close create modal with X
        if (closeCreateModalX) {
            closeCreateModalX.addEventListener('click', function() {
                createModal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Close create modal when clicking overlay
        createModal.addEventListener('click', function(e) {
            if (e.target === createModal) {
                createModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Thread View Modal functionality
        const threadModal = document.getElementById('thread-modal');
        const closeThreadModalBtn = document.getElementById('close-thread-modal');
        const replyForm = document.getElementById('thread-modal-reply-form-element');
        
        // Close modal when clicking the close button
        if (closeThreadModalBtn) {
            closeThreadModalBtn.addEventListener('click', function() {
                threadModal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Close modal when clicking outside
        if (threadModal) {
            threadModal.addEventListener('click', function(e) {
                if (e.target === threadModal) {
                    threadModal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Handle reply form submission
        if (replyForm) {
            replyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const replyContent = document.getElementById('thread-modal-reply-content');
                const replyError = document.getElementById('thread-modal-reply-error');
                const threadId = document.getElementById('thread-modal-thread-id').value;
                
                console.log('Submitting reply for thread ID:', threadId);
                
                // Reset error
                replyError.textContent = '';
                
                // Get content and validate
                const content = replyContent.value.trim();
                if (!content) {
                    replyError.textContent = 'Please enter a reply';
                    return;
                }
                
                if (content.length < 10) {
                    replyError.textContent = 'Reply must be at least 10 characters';
                    return;
                }
                
                // Disable submit button
                const submitBtn = replyForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Posting...';
                
                // Create form data
                const formData = new URLSearchParams();
                formData.append('content', content);
                
                // Submit reply
                fetch(`<?php echo URL_ROOT; ?>/forum/reply/${threadId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(handleFetchResponse)
                .then(data => {
                    console.log('Reply response data:', data);
                    console.log('Reply success?', data.success);
                    console.log('Reply message:', data.message);
                    console.log('Reply object present?', !!data.reply);
                    if (data.reply) {
                        console.log('Reply object:', data.reply);
                    }
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Post Reply';
                    
                    if (data.success) {
                        // Clear form
                        replyContent.value = '';
                        
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.style.padding = '0.5rem';
                        successMessage.style.marginTop = '0.5rem';
                        successMessage.style.backgroundColor = '#d1fae5';
                        successMessage.style.color = '#065f46';
                        successMessage.style.borderRadius = '0.375rem';
                        successMessage.textContent = data.message || 'Reply posted successfully!';
                        
                        replyForm.after(successMessage);
                        
                        // Remove success message after 3 seconds
                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                        
                        // Add new reply to the list
                        if (data.reply && typeof data.reply === 'object') {
                            try {
                                const newReply = data.reply;
                                const replyHtml = `
                                    <div class="reply-item" style="padding: 1rem; margin-bottom: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background-color: white;">
                                        <div class="reply-meta" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                                            <div class="reply-author" style="display: flex; align-items: center;">
                                                <img src="${newReply.author_avatar || 'https://randomuser.me/api/portraits/men/1.jpg'}" 
                                                     alt="${newReply.author_name}" class="author-avatar" style="margin-right: 0.5rem;">
                                                <span class="author-name">${newReply.author_name}</span>
                                                <span class="reply-time" style="margin-left: 0.5rem; color: #6b7280;">
                                                    Just now
                                                </span>
                                            </div>
                                        </div>
                                        <div class="reply-content" style="line-height: 1.6;">
                                            ${newReply.content.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>
                                `;
                                
                                // Update replies container
                                const repliesContainer = document.getElementById('thread-modal-replies-container');
                                const noReplies = repliesContainer.querySelector('.no-replies');
                                if (noReplies) {
                                    repliesContainer.innerHTML = replyHtml;
                                } else {
                                    repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
                                }
                                
                                // Scroll to the new reply
                                const newReplyElement = repliesContainer.lastElementChild;
                                if (newReplyElement) {
                                    newReplyElement.scrollIntoView({ behavior: 'smooth' });
                                }
                            } catch (err) {
                                console.error('Error rendering new reply:', err);
                                // Fallback: refresh the thread
                                refreshThread();
                            }
                        } else {
                            console.warn('Reply was successful but no valid reply object was returned');
                            // Refresh the thread to get updated replies
                            refreshThread();
                        }
                    } else {
                        // Show error message
                        replyError.textContent = data.message || 'Failed to submit reply. Please try again.';
                        // Still try to refresh after a delay in case the reply was actually saved
                        setTimeout(refreshThread, 2000);
                    }
                })
                .catch(error => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Post Reply';
                    
                    console.error('Error submitting reply:', error);
                    replyError.textContent = 'An error occurred. Please try again later.';
                    
                    // Still try to refresh the thread as the reply might have been saved despite the error
                    setTimeout(refreshThread, 2000);
                });
                
                // Helper function to refresh the thread
                function refreshThread() {
                    const threadUrl = `<?php echo URL_ROOT; ?>/forum/thread/${threadId}`;
                    console.log('Refreshing thread data from URL:', threadUrl);
                    openThreadModal(threadUrl);
                }
            });
        }

        // Make sure isLandingPage is only defined once
        if (typeof window.checkedLandingPage === 'undefined') {
            window.checkedLandingPage = true;
            // Only define isLandingPage if it's not already defined
            if (typeof window.isLandingPage === 'undefined') {
                window.isLandingPage = false;
            }
        }

        // Add submit event handler for create thread form
        if (submitThreadBtn) {
            submitThreadBtn.addEventListener('click', function() {
                // Validate all fields
                const isValidTitle = validateField('title', titleField.value.trim());
                const isValidCategory = validateField('category', categoryField.value);
                const isValidContent = validateField('content', contentField.value.trim());
                
                if (!isValidTitle || !isValidCategory || !isValidContent) {
                    return; // Stop if validation fails
                }
                
                // Submit form
                const formData = new URLSearchParams();
                formData.append('title', titleField.value.trim());
                formData.append('category_id', categoryField.value);
                formData.append('content', contentField.value.trim());
                
                // Disable submit button
                submitThreadBtn.disabled = true;
                submitThreadBtn.innerHTML = 'Creating...';
                
                // Submit form
                fetch('<?php echo URL_ROOT; ?>/forum/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(handleFetchResponse)
                .then(response => {
                    // Reset submit button
                    submitThreadBtn.disabled = false;
                    submitThreadBtn.innerHTML = 'Create Thread';
                    
                    if (response.success) {
                        // Show success message
                        createThreadStatus.style.display = 'block';
                        createThreadStatus.innerHTML = `
                            <div style="padding: 0.75rem; border-radius: 0.375rem; background-color: #d1fae5; color: #065f46;">
                                Thread created successfully!
                            </div>
                        `;
                        
                        // Clear form
                        createForm.reset();
                        
                        // Close modal after a delay
                        setTimeout(() => {
                            createModal.classList.remove('active');
                            document.body.style.overflow = '';
                            
                            // Reset status
                            setTimeout(() => {
                                createThreadStatus.style.display = 'none';
                                createThreadStatus.innerHTML = '';
                            }, 500);
                            
                            // Redirect to the thread
                            window.location.href = '<?php echo URL_ROOT; ?>/forum/thread/' + response.thread.slug;
                        }, 1500);
                    } else {
                        // Show errors
                        if (response.errors) {
                            if (response.errors.title) {
                                document.getElementById('title-error').textContent = response.errors.title;
                            }
                            if (response.errors.category_id) {
                                document.getElementById('category-error').textContent = response.errors.category_id;
                            }
                            if (response.errors.content) {
                                document.getElementById('content-error').textContent = response.errors.content;
                            }
                        } else {
                            // Generic error
                            createThreadStatus.style.display = 'block';
                            createThreadStatus.innerHTML = `
                                <div style="padding: 0.75rem; border-radius: 0.375rem; background-color: #fee2e2; color: #b91c1c;">
                                    ${response.message || 'An error occurred. Please try again.'}
                                </div>
                            `;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error creating thread:', error);
                    
                    // Reset submit button
                    submitThreadBtn.disabled = false;
                    submitThreadBtn.innerHTML = 'Create Thread';
                    
                    // Show error message
                    createThreadStatus.style.display = 'block';
                    createThreadStatus.innerHTML = `
                        <div style="padding: 0.75rem; border-radius: 0.375rem; background-color: #fee2e2; color: #b91c1c;">
                            An error occurred while creating your thread. Please try again.
                        </div>
                    `;
                });
            });
        }
    });
</script> 