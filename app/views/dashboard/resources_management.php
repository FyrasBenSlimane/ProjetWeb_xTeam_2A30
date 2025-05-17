<?php
// Use buffers to store the dashboard content
ob_start();

// Get resources data from the controller (passed in $data)
$resources = $data['resources'] ?? [];
?>

<div class="resources-management-page">
    <style>
        .resources-management-page {
            padding: 1.5rem 0;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
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
            height: 40px;
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
            width: 150px;
            position: relative;
        }
        .filter-select select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            height: 40px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 10.5L3.5 6h9z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        .btn-add-resource {
            background-color: #050b1f;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .btn-add-resource:hover {
            background-color: #0b1c40;
        }
        
        /* Table Styles */
        .resources-table-container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .resources-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .resources-table th,
        .resources-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        .resources-table th {
            background-color: #f9fafb;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            font-size: 13px;
            text-transform: uppercase;
        }
        .resources-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .resources-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Status and Category Styles */
        .resource-status, .resource-category {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-active {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .status-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .category-tutorial {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .category-course {
            background-color: #ede9fe;
            color: #6d28d9;
        }
        .category-webinar {
            background-color: #ffedd5;
            color: #ea580c;
        }
        
        /* Thumbnail styles */
        .resource-thumbnail {
            width: 80px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        /* Action Buttons */
        .actions-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: #f1f5f9;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-action svg {
            width: 16px;
            height: 16px;
            color: #475569;
        }
        .btn-action:hover {
            background-color: #e2e8f0;
        }
        .btn-view:hover svg {
            color: #0ea5e9;
        }
        .btn-edit:hover svg {
            color: #0369a1;
        }
        .btn-delete:hover svg {
            color: #dc2626;
        }
        .btn-status {
            width: auto;
            padding: 0 8px;
            font-size: 12px;
            display: inline-flex;
            gap: 4px;
            align-items: center;
        }
        .btn-status.activate {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .btn-status.activate:hover {
            background-color: #bbf7d0;
        }
        .btn-status.deactivate {
            background-color: #fee2e2;
            color: #ef4444;
        }
        .btn-status.deactivate:hover {
            background-color: #fecaca;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        
        /* Delete Confirmation Modal */
        .confirm-modal {
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
            padding: 1rem;
        }
        .confirm-modal.active {
            display: flex;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 500px;
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
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background-color: #fff;
            border-top: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }
        .pagination-info {
            color: #6b7280;
        }
    </style>

    <!-- Resources Management Header -->
    <div class="section-header">
        <h2 class="section-title">Resources Management</h2>
        <button id="addResourceBtn" class="btn-add-resource">Add New Resource</button>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="resourceSearch" placeholder="Search resources by title">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <option value="Tutorial">Tutorial</option>
                <option value="Course">Course</option>
                <option value="Webinar">Webinar</option>
            </select>
        </div>
    </div>

    <?php flash('resource_message'); ?>

    <!-- Resources Table -->
    <div class="resources-table-container">
        <?php if (empty($resources)): ?>
            <div class="empty-state">
                <p>No resources found. Click "Add New Resource" to add your first resource.</p>
            </div>
        <?php else: ?>
            <table class="resources-table">
            <thead>
                <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($resources as $resource): ?>
                        <tr>
                            <td><?php echo $resource->id; ?></td>
                            <td>
                                <img src="<?php echo $resource->thumbnail_url; ?>" alt="<?php echo $resource->title; ?>" class="resource-thumbnail">
                            </td>
                            <td><?php echo $resource->title; ?></td>
                            <td>
                                <span class="resource-category category-<?php echo strtolower($resource->category); ?>">
                                    <?php echo ucfirst($resource->category); ?>
                                </span>
                            </td>
                            <td>
                                <span class="resource-status status-<?php echo strtolower($resource->status); ?>">
                                    <?php echo ucfirst($resource->status); ?>
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="<?php echo $resource->youtube_url; ?>" target="_blank" class="btn-action btn-view" title="View Resource">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <button class="btn-action btn-edit" 
                                        data-resource-id="<?php echo $resource->id; ?>"
                                        data-resource-title="<?php echo htmlspecialchars($resource->title); ?>"
                                        data-resource-url="<?php echo htmlspecialchars($resource->youtube_url); ?>"
                                        data-resource-thumbnail="<?php echo htmlspecialchars($resource->thumbnail_url); ?>"
                                        data-resource-description="<?php echo htmlspecialchars($resource->description); ?>"
                                        data-resource-category="<?php echo $resource->category; ?>"
                                        data-resource-youtube-id="<?php echo $resource->youtube_id; ?>"
                                        title="Edit Resource">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button class="btn-action btn-status <?php echo strtolower($resource->status) === 'active' ? 'deactivate' : 'activate'; ?>" 
                                        data-resource-id="<?php echo $resource->id; ?>" 
                                        data-current-status="<?php echo $resource->status; ?>" 
                                        title="<?php echo strtolower($resource->status) === 'active' ? 'Deactivate Resource' : 'Activate Resource'; ?>">
                                    <?php if (strtolower($resource->status) === 'active'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Deactivate</span>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Activate</span>
                                    <?php endif; ?>
                                </button>
                                <button class="btn-action btn-delete" data-resource-id="<?php echo $resource->id; ?>" data-resource-title="<?php echo htmlspecialchars($resource->title); ?>" title="Delete Resource">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <div class="pagination-info">Showing <?php echo count($resources); ?> resources</div>
    </div>

    <!-- Add Resource Modal -->
    <div class="confirm-modal" id="addResourceModal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h3 class="modal-title">Add YouTube Resource</h3>
                <button type="button" class="modal-close" id="closeAddModal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Tab Navigation -->
                <div class="tab-navigation" style="display: flex; margin-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                    <button id="singleTabBtn" class="tab-button active" style="background: none; border: none; padding: 10px 15px; cursor: pointer; position: relative; font-weight: 500;">
                        Single Resource
                        <span class="active-indicator" style="position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background-color: #0369a1; display: block;"></span>
                    </button>
                    <button id="bulkTabBtn" class="tab-button" style="background: none; border: none; padding: 10px 15px; cursor: pointer; position: relative; font-weight: 500;">
                        Bulk Import
                        <span class="active-indicator" style="position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background-color: #0369a1; display: none;"></span>
                                    </button>
                </div>
                
                <!-- Single Resource Tab Content -->
                <div id="singleTabContent" class="tab-content">
                    <form id="addSingleResourceForm">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="youtubeUrl" style="display: block; margin-bottom: 5px; font-weight: 500;">YouTube URL</label>
                            <input type="url" id="youtubeUrl" name="youtube_url" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" placeholder="https://www.youtube.com/watch?v=..." required>
                        </div>
                        
                        <div id="extractedPreview" style="display: none; margin-top: 20px; background-color: #f9fafb; border-radius: 6px; padding: 15px;">
                            <h4 style="margin-top: 0; margin-bottom: 10px;">Video Details</h4>
                            <div style="display: flex; gap: 15px;">
                                <div style="flex-shrink: 0; width: 160px;">
                                    <img id="previewThumbnail" src="" alt="Video Thumbnail" style="width: 160px; height: 90px; object-fit: cover; border-radius: 4px;">
                                </div>
                                <div style="flex-grow: 1;">
                                    <p><strong>Title:</strong> <span id="previewTitle"></span></p>
                                    <div style="margin-top: 10px;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Category</label>
                                        <select id="resourceCategory" name="category" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                                            <option value="tutorial">Tutorial</option>
                                            <option value="course">Course</option>
                                            <option value="webinar">Webinar</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="youtubeId" name="youtube_id">
                                    <input type="hidden" id="thumbnailUrl" name="thumbnail_url">
                                    <input type="hidden" id="videoTitle" name="title">
                                </div>
                            </div>
                        </div>
                        
                        <div id="extractingMessage" style="display: none; margin-top: 20px; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 6px;">
                            <p>Extracting video details...</p>
                        </div>
                        
                        <div id="errorMessage" style="display: none; margin-top: 20px; padding: 15px; background-color: #fee2e2; border-radius: 6px; color: #dc2626;">
                            <p style="margin: 0;" id="errorText">Error extracting video details. Please check the URL and try again.</p>
                        </div>
                    </form>
                </div>
                
                <!-- Bulk Import Tab Content -->
                <div id="bulkTabContent" class="tab-content" style="display: none;">
                    <form id="addBulkResourceForm">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="bulkYoutubeUrls" style="display: block; margin-bottom: 5px; font-weight: 500;">YouTube URLs (one per line)</label>
                            <textarea id="bulkYoutubeUrls" name="bulk_youtube_urls" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; min-height: 150px; resize: vertical;" placeholder="https://www.youtube.com/watch?v=...&#10;https://www.youtube.com/watch?v=...&#10;https://www.youtube.com/watch?v=..." required></textarea>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="bulkCategory" style="display: block; margin-bottom: 5px; font-weight: 500;">Category (applies to all videos)</label>
                            <select id="bulkCategory" name="bulk_category" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                                <option value="tutorial">Tutorial</option>
                                <option value="course">Course</option>
                                <option value="webinar">Webinar</option>
                            </select>
                        </div>
                        
                        <div id="bulkExtractingMessage" style="display: none; margin-top: 20px; text-align: center; padding: 15px; background-color: #f9fafb; border-radius: 6px;">
                            <p>Processing videos. This may take a moment...</p>
                        </div>
                        
                        <div id="bulkErrorMessage" style="display: none; margin-top: 20px; padding: 15px; background-color: #fee2e2; border-radius: 6px; color: #dc2626;">
                            <p style="margin: 0;" id="bulkErrorText">Error processing videos. Please check the URLs and try again.</p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelAddBtn" style="padding: 8px 16px; border: 1px solid #d1d5db; background-color: #fff; border-radius: 4px; cursor: pointer;">Cancel</button>
                <button type="button" class="btn-add-resource" id="extractVideoBtn" style="padding: 8px 16px;">Extract Video Details</button>
                <button type="button" class="btn-add-resource" id="saveResourceBtn" style="padding: 8px 16px; display: none;">Save Resource</button>
                <button type="button" class="btn-add-resource" id="saveBulkResourcesBtn" style="padding: 8px 16px; display: none;">Import Resources</button>
            </div>
        </div>
                                </div>
                                
                                <!-- Delete Confirmation Modal -->
    <div class="confirm-modal" id="deleteConfirmModal">
                                        <div class="modal-content">
                                            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button type="button" class="modal-close" id="closeDeleteModal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                <p id="confirm-delete-message">Are you sure you want to delete this resource?</p>
                <form id="deleteResourceForm" method="post">
                    <input type="hidden" name="delete_resource_id" id="deleteResourceId" value="">
                </form>
                                            </div>
                                            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Resource</button>
                                            </div>
                                        </div>
    </div>

    <!-- Edit Resource Modal -->
    <div class="confirm-modal" id="editResourceModal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <h3 class="modal-title">Edit Resource</h3>
                <button type="button" class="modal-close" id="closeEditModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editResourceForm">
                    <input type="hidden" id="editResourceId" name="id">
                    <input type="hidden" id="editYoutubeId" name="youtube_id">
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editTitle" style="display: block; margin-bottom: 5px; font-weight: 500;">Title</label>
                        <input type="text" id="editTitle" name="title" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editYoutubeUrl" style="display: block; margin-bottom: 5px; font-weight: 500;">YouTube URL</label>
                        <input type="url" id="editYoutubeUrl" name="youtube_url" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                        <small style="color: #6b7280; display: block; margin-top: 5px;">Changing the URL will update the thumbnail and video ID automatically</small>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editThumbnailUrl" style="display: block; margin-bottom: 5px; font-weight: 500;">Thumbnail URL</label>
                        <input type="url" id="editThumbnailUrl" name="thumbnail_url" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editDescription" style="display: block; margin-bottom: 5px; font-weight: 500;">Description</label>
                        <textarea id="editDescription" name="description" class="form-input" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; min-height: 100px;"></textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editCategory" style="display: block; margin-bottom: 5px; font-weight: 500;">Category</label>
                        <select id="editCategory" name="category" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                            <option value="tutorial">Tutorial</option>
                            <option value="course">Course</option>
                            <option value="webinar">Webinar</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editStatus" style="display: block; margin-bottom: 5px; font-weight: 500;">Status</label>
                        <select id="editStatus" name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                            <option value="active">Active (visible on community page)</option>
                            <option value="inactive">Inactive (hidden from community page)</option>
                        </select>
                    </div>
                    
                    <div id="editErrorMessage" style="display: none; margin-top: 20px; padding: 15px; background-color: #fee2e2; border-radius: 6px; color: #dc2626;">
                        <p style="margin: 0;" id="editErrorText">Error updating resource. Please try again.</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelEditBtn" style="padding: 8px 16px; border: 1px solid #d1d5db; background-color: #fff; border-radius: 4px; cursor: pointer;">Cancel</button>
                <button type="button" class="btn-add-resource" id="saveEditBtn" style="padding: 8px 16px;">Save Changes</button>
                                    </div>
                                </div>
    </div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search and filtering
            document.getElementById('resourceSearch').addEventListener('input', filterResources);
            document.getElementById('statusFilter').addEventListener('change', filterResources);
            document.getElementById('categoryFilter').addEventListener('change', filterResources);
            
            function filterResources() {
                const searchTerm = document.getElementById('resourceSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const categoryFilter = document.getElementById('categoryFilter').value;
                
                const rows = document.querySelectorAll('.resources-table tbody tr');
                
                rows.forEach(row => {
                    const title = row.cells[2].textContent.toLowerCase();
                    const category = row.cells[3].textContent.trim();
                    const status = row.cells[4].textContent.trim();
                    
                    const matchesSearch = title.includes(searchTerm);
                    const matchesStatus = !statusFilter || status === statusFilter;
                    const matchesCategory = !categoryFilter || category === categoryFilter;
                    
                    if (matchesSearch && matchesStatus && matchesCategory) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Toggle resource status
            const statusBtns = document.querySelectorAll('.btn-status');
            statusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const resourceId = this.getAttribute('data-resource-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    
                    // Set the target status based on button appearance
                    let newStatus;
                    if (this.classList.contains('deactivate')) {
                        newStatus = 'inactive';  // If button shows "Deactivate", make resource inactive
                    } else {
                        newStatus = 'active';    // If button shows "Activate", make resource active
                    }
                    
                    // Show confirmation dialog only when deactivating a resource
                    if (newStatus === 'inactive') {
                        if (!confirm(`Are you sure you want to deactivate this resource? It will no longer be visible to users.`)) {
                            return; // User cancelled
                        }
                    }
                    
                    // Send AJAX request to update resource status
                    const xhr = new XMLHttpRequest();
                    const formData = new FormData();
                    formData.append('resourceId', resourceId);
                    formData.append('status', newStatus);
                    
                    xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/toggle-status`, true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                
                                if (response.success) {
                                    // Update the UI
                                    const row = btn.closest('tr');
                                    const statusCell = row.cells[4].querySelector('.resource-status');
                                    
                                    // Remove current status class
                                    statusCell.classList.remove('status-active', 'status-inactive');
                                    // Add new status class
                                    statusCell.classList.add(`status-${newStatus.toLowerCase()}`);
                                    // Update text
                                    statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                                    
                                    // Update button data attribute to reflect new status
                                    btn.setAttribute('data-current-status', newStatus);
                                    
                                    // Toggle button classes and appearance completely
                                    if (newStatus === 'active') {
                                        // If new status is Active, show the Deactivate button
                                        btn.classList.remove('activate');
                                        btn.classList.add('deactivate');
                                        btn.setAttribute('title', 'Deactivate Resource');
                                        btn.innerHTML = `
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Deactivate</span>
                                        `;
                                    } else {
                                        // If new status is Inactive, show the Activate button
                                        btn.classList.remove('deactivate');
                                        btn.classList.add('activate');
                                        btn.setAttribute('title', 'Activate Resource');
                                        btn.innerHTML = `
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Activate</span>
                                        `;
                                    }
                                    
                                    
                                } else {
                                    alert(response.message || 'Failed to update resource status');
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                                alert('Failed to update resource status');
                            }
                        } else {
                            alert('Server error occurred');
                        }
                    };
                    
                    xhr.onerror = function() {
                        alert('Network error occurred');
                    };
                    
                    xhr.send(formData);
                });
            });
            
            // Delete resource
            const deleteBtns = document.querySelectorAll('.btn-delete');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const resourceId = this.getAttribute('data-resource-id');
                    const resourceTitle = this.getAttribute('data-resource-title');
                    
                    // Set resource ID in the delete form
                    document.getElementById('deleteResourceId').value = resourceId;
                    
                    // Update confirmation message
                    document.getElementById('confirm-delete-message').textContent = 
                        `Are you sure you want to delete "${resourceTitle}"? This action cannot be undone.`;
                    
                    // Update form action
                    document.getElementById('deleteResourceForm').action = 
                        `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/delete/${resourceId}`;
                    
                    // Show the confirmation modal
                    document.getElementById('deleteConfirmModal').classList.add('active');
                });
            });
            
            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                // Update the form action to ensure it uses the correct endpoint
                const resourceId = document.getElementById('deleteResourceId').value;
                document.getElementById('deleteResourceForm').action = `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/delete/${resourceId}`;
                document.getElementById('deleteResourceForm').submit();
            });
            
            // Close modals
            document.getElementById('closeDeleteModal').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            // Edit Resource
            const editBtns = document.querySelectorAll('.btn-edit');
            const editResourceModal = document.getElementById('editResourceModal');
            const closeEditModal = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const saveEditBtn = document.getElementById('saveEditBtn');
            
            // Open the edit modal when edit button is clicked
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const resourceId = this.getAttribute('data-resource-id');
                    const resourceTitle = this.getAttribute('data-resource-title');
                    const resourceUrl = this.getAttribute('data-resource-url');
                    const resourceThumbnail = this.getAttribute('data-resource-thumbnail');
                    const resourceDescription = this.getAttribute('data-resource-description');
                    const resourceCategory = this.getAttribute('data-resource-category');
                    const resourceYoutubeId = this.getAttribute('data-resource-youtube-id');
                    const resourceStatus = this.closest('tr').querySelector('.resource-status').textContent.trim().toLowerCase();
                    
                    // Fill the form with resource data
                    document.getElementById('editResourceId').value = resourceId;
                    document.getElementById('editTitle').value = resourceTitle;
                    document.getElementById('editYoutubeUrl').value = resourceUrl;
                    document.getElementById('editThumbnailUrl').value = resourceThumbnail;
                    document.getElementById('editDescription').value = resourceDescription || '';
                    document.getElementById('editYoutubeId').value = resourceYoutubeId;
                    
                    // Set the correct category option
                    const categorySelect = document.getElementById('editCategory');
                    for (let i = 0; i < categorySelect.options.length; i++) {
                        if (categorySelect.options[i].value.toLowerCase() === resourceCategory.toLowerCase()) {
                            categorySelect.selectedIndex = i;
                            break;
                        }
                    }
                    
                    // Set the correct status option
                    const statusSelect = document.getElementById('editStatus');
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].value === resourceStatus) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                    
                    // Show the modal
                    editResourceModal.classList.add('active');
                });
            });
            
            // Close the edit modal
            closeEditModal.addEventListener('click', function() {
                editResourceModal.classList.remove('active');
            });
            
            cancelEditBtn.addEventListener('click', function() {
                editResourceModal.classList.remove('active');
            });
            
            // Save edited resource
            saveEditBtn.addEventListener('click', function() {
                // Get form data
                const resourceId = document.getElementById('editResourceId').value;
                const title = document.getElementById('editTitle').value;
                const youtubeUrl = document.getElementById('editYoutubeUrl').value;
                const thumbnailUrl = document.getElementById('editThumbnailUrl').value;
                const description = document.getElementById('editDescription').value;
                const category = document.getElementById('editCategory').value;
                const status = document.getElementById('editStatus').value;
                const youtubeId = document.getElementById('editYoutubeId').value;
                
                if (!resourceId || !title || !youtubeUrl || !thumbnailUrl || !category || !status || !youtubeId) {
                    showEditError('All fields except description are required');
                    return;
                }
                
                // Create form data
                const formData = new FormData();
                formData.append('id', resourceId);
                formData.append('title', title);
                formData.append('youtube_url', youtubeUrl);
                formData.append('youtube_id', youtubeId);
                formData.append('thumbnail_url', thumbnailUrl);
                formData.append('description', description);
                formData.append('category', category);
                formData.append('status', status);
                
                // Send request to update the resource
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/update`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Close modal and refresh page
                                editResourceModal.classList.remove('active');
                                window.location.reload();
                            } else {
                                showEditError(response.message || 'Failed to update resource');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showEditError('Error processing the server response');
                        }
                    } else {
                        showEditError('Server error occurred');
                    }
                };
                
                xhr.onerror = function() {
                    showEditError('Network error occurred');
                };
                
                xhr.send(formData);
            });
            
            // YouTube URL change handling in edit form
            document.getElementById('editYoutubeUrl').addEventListener('change', function() {
                const newUrl = this.value;
                if (!newUrl) return;
                
                // Call the API to extract video details
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('youtube_url', newUrl);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/extract-video-details`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                document.getElementById('editThumbnailUrl').value = response.data.thumbnail_url;
                                document.getElementById('editYoutubeId').value = response.data.youtube_id;
                                // Don't update the title to preserve user edits
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                    }
                };
                
                xhr.send(formData);
            });
            
            function showEditError(message) {
                const errorMessage = document.getElementById('editErrorMessage');
                const errorText = document.getElementById('editErrorText');
                errorText.textContent = message;
                errorMessage.style.display = 'block';
            }
            
    // Set active sidebar item
        const sidebarItems = document.querySelectorAll('.sidebar .nav-link');
        sidebarItems.forEach(item => item.classList.remove('active'));
        
        const resourcesNavItem = document.querySelector('.sidebar .nav-link[href*="resources"]');
        if (resourcesNavItem) {
            resourcesNavItem.classList.add('active');
        }
            
            // Add Resource Modal Functionality
            const addResourceBtn = document.getElementById('addResourceBtn');
            const addResourceModal = document.getElementById('addResourceModal');
            const closeAddModal = document.getElementById('closeAddModal');
            const cancelAddBtn = document.getElementById('cancelAddBtn');
            const singleTabBtn = document.getElementById('singleTabBtn');
            const bulkTabBtn = document.getElementById('bulkTabBtn');
            const singleTabContent = document.getElementById('singleTabContent');
            const bulkTabContent = document.getElementById('bulkTabContent');
            const extractVideoBtn = document.getElementById('extractVideoBtn');
            const saveResourceBtn = document.getElementById('saveResourceBtn');
            const saveBulkResourcesBtn = document.getElementById('saveBulkResourcesBtn');
            
            // Open the add resource modal
            addResourceBtn.addEventListener('click', function() {
                resetAddResourceForm();
                addResourceModal.classList.add('active');
            });
            
            // Close the add resource modal
            closeAddModal.addEventListener('click', function() {
                addResourceModal.classList.remove('active');
            });
            
            cancelAddBtn.addEventListener('click', function() {
                addResourceModal.classList.remove('active');
            });
            
            // Tab Switching
            singleTabBtn.addEventListener('click', function() {
                // Update tab buttons
                singleTabBtn.classList.add('active');
                bulkTabBtn.classList.remove('active');
                singleTabBtn.querySelector('.active-indicator').style.display = 'block';
                bulkTabBtn.querySelector('.active-indicator').style.display = 'none';
                
                // Show/hide content
                singleTabContent.style.display = 'block';
                bulkTabContent.style.display = 'none';
                
                // Update visible buttons
                extractVideoBtn.style.display = 'inline-block';
                saveResourceBtn.style.display = 'none';
                saveBulkResourcesBtn.style.display = 'none';
            });
            
            bulkTabBtn.addEventListener('click', function() {
                // Update tab buttons
                bulkTabBtn.classList.add('active');
                singleTabBtn.classList.remove('active');
                bulkTabBtn.querySelector('.active-indicator').style.display = 'block';
                singleTabBtn.querySelector('.active-indicator').style.display = 'none';
                
                // Show/hide content
                bulkTabContent.style.display = 'block';
                singleTabContent.style.display = 'none';
                
                // Update visible buttons
                extractVideoBtn.style.display = 'none';
                saveResourceBtn.style.display = 'none';
                saveBulkResourcesBtn.style.display = 'inline-block';
            });
            
            // Extract Video Details
            extractVideoBtn.addEventListener('click', function() {
                const youtubeUrl = document.getElementById('youtubeUrl').value;
                if (!youtubeUrl) {
                    showSingleError('Please enter a YouTube URL');
                    return;
                }
                
                // Show loading state
                document.getElementById('extractingMessage').style.display = 'block';
                document.getElementById('errorMessage').style.display = 'none';
                document.getElementById('extractedPreview').style.display = 'none';
                
                // Call the API to extract video details
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('youtube_url', youtubeUrl);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/extract-video-details`, true);
                xhr.onload = function() {
                    // Hide loading state
                    document.getElementById('extractingMessage').style.display = 'none';
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Fill in the preview and hidden fields
                                document.getElementById('previewThumbnail').src = response.data.thumbnail_url;
                                document.getElementById('previewTitle').textContent = response.data.title;
                                document.getElementById('thumbnailUrl').value = response.data.thumbnail_url;
                                document.getElementById('youtubeId').value = response.data.youtube_id;
                                document.getElementById('videoTitle').value = response.data.title;
                                
                                // Show the preview and save button
                                document.getElementById('extractedPreview').style.display = 'block';
                                extractVideoBtn.style.display = 'none';
                                saveResourceBtn.style.display = 'inline-block';
                            } else {
                                showSingleError(response.message || 'Failed to extract video details');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showSingleError('Error processing the server response');
                        }
                    } else {
                        showSingleError('Server error occurred');
                    }
                };
                
                xhr.onerror = function() {
                    document.getElementById('extractingMessage').style.display = 'none';
                    showSingleError('Network error occurred');
                };
                
                xhr.send(formData);
            });
            
            // Save Single Resource
            saveResourceBtn.addEventListener('click', function() {
                const youtubeUrl = document.getElementById('youtubeUrl').value;
                const youtubeId = document.getElementById('youtubeId').value;
                const thumbnailUrl = document.getElementById('thumbnailUrl').value;
                const title = document.getElementById('videoTitle').value;
                const category = document.getElementById('resourceCategory').value;
                
                if (!youtubeUrl || !youtubeId || !thumbnailUrl || !title || !category) {
                    showSingleError('All fields are required');
                    return;
                }
                
                // Create form data
                const formData = new FormData();
                formData.append('youtube_url', youtubeUrl);
                formData.append('youtube_id', youtubeId);
                formData.append('thumbnail_url', thumbnailUrl);
                formData.append('title', title);
                formData.append('category', category);
                formData.append('status', 'active');
                
                // Send request to save the resource
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/add`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Close modal and refresh page
                                addResourceModal.classList.remove('active');
                                window.location.reload();
                            } else {
                                showSingleError(response.message || 'Failed to save resource');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showSingleError('Error processing the server response');
                        }
                    } else {
                        showSingleError('Server error occurred');
                    }
                };
                
                xhr.onerror = function() {
                    showSingleError('Network error occurred');
                };
                
                xhr.send(formData);
            });
            
            // Save Bulk Resources
            saveBulkResourcesBtn.addEventListener('click', function() {
                const bulkYoutubeUrls = document.getElementById('bulkYoutubeUrls').value;
                const bulkCategory = document.getElementById('bulkCategory').value;
                
                if (!bulkYoutubeUrls || !bulkCategory) {
                    showBulkError('Please enter YouTube URLs and select a category');
                    return;
                }
                
                const urls = bulkYoutubeUrls.split('\n').filter(url => url.trim() !== '');
                if (urls.length === 0) {
                    showBulkError('Please enter at least one valid YouTube URL');
                    return;
                }
                
                // Show loading state
                document.getElementById('bulkExtractingMessage').style.display = 'block';
                document.getElementById('bulkErrorMessage').style.display = 'none';
                
                // Create form data
                const formData = new FormData();
                formData.append('youtube_urls', JSON.stringify(urls));
                formData.append('category', bulkCategory);
                
                // Send request to save bulk resources
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || ''}/resources/add-bulk`, true);
                xhr.onload = function() {
                    // Hide loading state
                    document.getElementById('bulkExtractingMessage').style.display = 'none';
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Close modal and refresh page
                                addResourceModal.classList.remove('active');
                                window.location.reload();
                            } else {
                                showBulkError(response.message || 'Failed to import resources');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showBulkError('Error processing the server response');
                        }
                    } else {
                        showBulkError('Server error occurred');
                    }
                };
                
                xhr.onerror = function() {
                    document.getElementById('bulkExtractingMessage').style.display = 'none';
                    showBulkError('Network error occurred');
                };
                
                xhr.send(formData);
            });
            
            // Helper Functions
            function showSingleError(message) {
                const errorMessage = document.getElementById('errorMessage');
                const errorText = document.getElementById('errorText');
                errorText.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            function showBulkError(message) {
                const errorMessage = document.getElementById('bulkErrorMessage');
                const errorText = document.getElementById('bulkErrorText');
                errorText.textContent = message;
                errorMessage.style.display = 'block';
            }
            
            function resetAddResourceForm() {
                // Reset single tab
                document.getElementById('youtubeUrl').value = '';
                document.getElementById('extractedPreview').style.display = 'none';
                document.getElementById('errorMessage').style.display = 'none';
                document.getElementById('extractingMessage').style.display = 'none';
                
                // Reset bulk tab
                document.getElementById('bulkYoutubeUrls').value = '';
                document.getElementById('bulkErrorMessage').style.display = 'none';
                document.getElementById('bulkExtractingMessage').style.display = 'none';
                
                // Reset buttons
                extractVideoBtn.style.display = 'inline-block';
                saveResourceBtn.style.display = 'none';
                saveBulkResourcesBtn.style.display = 'none';
                
                // Default to single tab
                singleTabBtn.click();
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