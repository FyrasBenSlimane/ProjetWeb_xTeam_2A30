<?php
// Use buffers to store the dashboard content
ob_start();

// Get projects data from the controller (passed in $data)
$projects = $data['projects'] ?? [];
$candidatures = $data['candidatures'] ?? [];

// Ensure basic variables are set for standalone view or if included in a layout
if (!isset($title)) {
    $title = 'Projects Management';
}
?>

<div class="projects-management-page">
    <style>
        .projects-management-page {
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
        .btn-add-project {
            background-color: #050b1f;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .btn-add-project:hover {
            background-color: #0b1c40;
        }
        
        /* Table Styles */
        .projects-table-container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .projects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .projects-table th,
        .projects-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        .projects-table th {
            background-color: #f9fafb;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            font-size: 13px;
            text-transform: uppercase;
        }
        .projects-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .projects-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Status and Type Styles */
        .project-status, .project-category, .project-remote {
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
        .status-draft {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .status-canceled {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .status-completed {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .category-web-development {
            background-color: #ede9fe;
            color: #6d28d9;
        }
        .category-mobile-app {
            background-color: #ffedd5;
            color: #ea580c;
        }
        .category-design {
            background-color: #e0f2fe;
            color: #0284c7;
        }
        .category-marketing {
            background-color: #fef3c7;
            color: #d97706;
        }
        .category-data-science {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .category-other {
            background-color: #f3f4f6;
            color: #6b7280;
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
            transition: background-color 0.2s ease;
        }
        .btn-action svg {
            width: 16px;
            height: 16px;
            color: #475569;
        }
        .btn-action:hover {
            background-color: #e2e8f0;
        }
        .btn-edit:hover svg {
            color: #0369a1;
        }
        .btn-delete:hover svg {
            color: #dc2626;
        }
        .btn-candidatures:hover svg {
            color: #8b5cf6;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        /* Project Edit Modal */
        .user-edit-modal {
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
        .user-edit-modal.active {
            display: flex !important;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
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
            max-height: 70vh;
            overflow-y: auto;
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
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-col {
            flex: 1;
        }
        
        /* Confirm Modal */
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
        
        /* Candidatures Table */
        .candidatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 14px;
        }
        .candidatures-table th,
        .candidatures-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .candidatures-table th {
            font-weight: 600;
            color: #4b5563;
            background-color: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .status-left {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        .btn-candidature-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-approve {
            background-color: #10b981;
            color: white;
        }
        .btn-approve:hover {
            background-color: #059669;
        }
        .btn-reject {
            background-color: #ef4444;
            color: white;
        }
        .btn-reject:hover {
            background-color: #dc2626;
        }
        .btn-left {
            background-color: #6b7280;
            color: white;
        }
        .btn-left:hover {
            background-color: #4b5563;
        }
        .tab-buttons {
            display: flex;
            margin-bottom: 1rem;
        }
        .tab-button {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-bottom: none;
            margin-right: 0.25rem;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .tab-button.active {
            background-color: #fff;
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }
        
        /* Checkbox styling */
        .form-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .form-checkbox input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        /* Basic Toast Styling */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .toast-notification.show {
            opacity: 1;
        }
        .toast-notification.success {
            background-color: #4CAF50;
        }
        .toast-notification.error {
            background-color: #f44336;
        }
    </style>

    <div class="projects-management-page">
        <h1><?php echo htmlspecialchars($title); ?></h1>

        <!-- Projects Management Header -->    <div class="section-header">        <h2 class="section-title">Projects Management</h2>        <button type="button" class="btn-add-project" id="addProjectBtn">Add New Project</button>    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="projectSearch" placeholder="Search projects by title or category">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="draft">Draft</option>
                <option value="completed">Completed</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="categoryFilter">
                <option value="">All Categories</option>
                <?php
                // Get unique project categories
                if (!empty($projects)) {
                    $projectCategories = array_map(function($project) {
                        return $project->category;
                    }, $projects);
                    $uniqueCategories = array_unique($projectCategories);
                    sort($uniqueCategories);
                    
                    foreach ($uniqueCategories as $category) {
                        echo "<option value=\"{$category}\">" . ucfirst(str_replace('-', ' ', $category)) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>

    <?php flash('project_message'); ?>

    <!-- Projects Table -->
    <div class="projects-table-container">
        <?php if (empty($projects)): ?>
            <div class="empty-state">
                <p>No projects found. Click "Add New Project" to create your first project.</p>
            </div>
        <?php else: ?>
            <table class="projects-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Remote</th>
                    <th scope="col">Status</th>
                    <th scope="col">Candidatures</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?php echo $project->id; ?></td>
                            <td><?php echo $project->title; ?></td>
                            <td>
                                <span class="project-category category-<?php echo $project->category; ?>">
                                    <?php echo ucfirst(str_replace('-', ' ', $project->category)); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($project->start_date)); ?></td>
                            <td><?php echo date('M j, Y', strtotime($project->end_date)); ?></td>
                            <td><?php echo $project->location; ?></td>
                            <td>
                                <?php if ($project->is_remote): ?>
                                    <span class="project-remote status-active">Yes</span>
                                <?php else: ?>
                                    <span class="project-remote status-draft">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="project-status status-<?php echo $project->status; ?>">
                                    <?php echo ucfirst($project->status); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                // Count candidatures for this project
                                $candCount = 0;
                                $approvedCount = 0;
                                foreach ($candidatures as $cand) {
                                    if ($cand->project_id == $project->id) {
                                        $candCount++;
                                        if ($cand->status == 'approved') {
                                            $approvedCount++;
                                        }
                                    }
                                }
                                echo $approvedCount . '/' . ($project->max_participants ?? '∞') . ' (' . $candCount . ' total)';
                                ?>
                            </td>
                            <td class="actions-cell">
                                <button class="btn-action btn-candidatures" title="Manage Candidatures" onclick="showCandidatures(<?php echo $project->id; ?>, '<?php echo htmlspecialchars($project->title); ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                    </svg>
                                </button>
                                <a href="<?php echo URL_ROOT; ?>/dashboard/project_edit/<?php echo $project->id; ?>" class="btn-action btn-edit" title="Edit Project">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <button type="button" class="btn-action btn-delete" data-project-id="<?php echo $project->id; ?>" data-project-title="<?php echo htmlspecialchars($project->title); ?>" title="Delete Project">
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

        <!-- Pagination -->    <div class="pagination">        <div class="pagination-info">Showing <?php echo count($projects); ?> projects</div>    </div>
        
    <!-- Project Edit Modal -->
    <div class="user-edit-modal" id="projectEditModal">
        <div class="modal-content" style="max-width: 700px;">
            <form id="projectForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Add New Project</h3>
                    <button type="button" class="modal-close" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="projectId" name="id" value="">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="title">Project Title</label>
                                <input type="text" class="form-input" id="title" name="title" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-textarea" id="description" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="category">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="web-development">Web Development</option>
                                    <option value="mobile-app">Mobile App</option>
                                    <option value="design">Design</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="data-science">Data Science</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="start_date">Start Date</label>
                                <input type="datetime-local" class="form-input" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="end_date">End Date</label>
                                <input type="datetime-local" class="form-input" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="location">Location</label>
                                <input type="text" class="form-input" id="location" name="location">
                                <small>Leave empty if project is remote only</small>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="max_participants">Max Participants</label>
                                <input type="number" class="form-input" id="max_participants" name="max_participants" min="1" value="5">
                                <small>Leave empty for unlimited</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-checkbox">
                            <input type="checkbox" id="is_remote" name="is_remote" checked>
                            <label for="is_remote">This is a remote project</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="skills_required">Skills Required</label>
                        <input type="text" class="form-input" id="skills_required" name="skills_required">
                        <small>Comma-separated list of skills (e.g., JavaScript, UI/UX, Marketing)</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="image">Project Image URL</label>
                        <input type="url" class="form-input" id="image" name="image">
                        <small>Optional: URL to project cover image</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveProjectBtn">Save Project</button>
                </div>
            </form>
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
                <p id="confirm-delete-message">Are you sure you want to delete this project?</p>
                <form id="deleteProjectForm" action="">
                    <input type="hidden" name="delete_project_id" id="deleteProjectId" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Project</button>
            </div>
        </div>
    </div>
    
    <!-- Candidatures Management Modal -->
    <div class="confirm-modal" id="candidaturesModal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title" id="candidaturesTitle">Project Candidatures</h3>
                <button type="button" class="modal-close" id="closeCandidaturesModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="tab-buttons">
                    <div class="tab-button active" onclick="switchCandidatureTab('pending')">Pending</div>
                    <div class="tab-button" onclick="switchCandidatureTab('approved')">Approved</div>
                    <div class="tab-button" onclick="switchCandidatureTab('rejected')">Rejected</div>
                    <div class="tab-button" onclick="switchCandidatureTab('left')">Left</div>
                </div>
                
                <div id="candidaturesContent">
                    <!-- Candidatures will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="closeCandidaturesBtn">Close</button>
            </div>
    </div>
</div>

<div id="toast-container"></div>

<script>        document.addEventListener('DOMContentLoaded', function() {            // Submit Project form
            document.getElementById('projectForm').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted');
                
                let isValid = true;
                const projectTitle = document.getElementById('title');
                const projectDesc = document.getElementById('description');
                const startDate = document.getElementById('start_date');
                const endDate = document.getElementById('end_date');
                const projectId = document.getElementById('projectId').value;
                
                // Clear previous errors
                clearError(projectTitle);
                clearError(projectDesc);
                clearError(startDate);
                clearError(endDate);
                
                // Validate fields
                if (projectTitle.value.trim() === '') {
                    showError(projectTitle, 'Title is required.');
                    isValid = false;
                }
                
                if (projectDesc.value.trim() === '') {
                    showError(projectDesc, 'Description is required.');
                    isValid = false;
                }
                
                if (startDate.value === '') {
                    showError(startDate, 'Start date is required.');
                    isValid = false;
                }
                
                if (endDate.value === '') {
                    showError(endDate, 'End date is required.');
                    isValid = false;
                } else if (endDate.value < startDate.value) {
                    showError(endDate, 'End date must be after start date.');
                    isValid = false;
                }
                
                if (!isValid) {
                    return; // Prevent submission
                }
                
                // Show loading indicator
                // Either add a loading indicator or use a notification
                console.log('Form is valid, proceeding with submission');
                
                // Get form data
                const formData = new FormData(this);
                
                // Get checkbox value
                formData.set('is_remote', document.getElementById('is_remote').checked ? '1' : '0');
                
                // Determine if it's an add or edit operation
                const isNewProject = !projectId;
                const rootUrl = document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>';
                const endpoint = isNewProject ? 
                    `${rootUrl}/dashboard/add_project` : 
                    `${rootUrl}/dashboard/project_edit/${projectId}`;
                
                console.log('Submitting to endpoint:', endpoint);
                
                // Send AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', endpoint, true);
                xhr.onload = function() {
                    console.log('XHR status:', xhr.status);
                    console.log('XHR response:', xhr.responseText);
                    
                    if (xhr.status === 200) {
                        try {
                            // Close the modal
                            document.getElementById('projectEditModal').classList.remove('active');
                            
                            // Show success message
                            alert(isNewProject ? 'Project added successfully!' : 'Project updated successfully!');
                            
                            // Refresh the page to show updated project list
                            window.location.reload();
                        } catch (e) {
                            console.error('Error handling response:', e);
                            alert('An error occurred while saving the project.');
                        }
                    } else {
                        console.error('Error response:', xhr.responseText);
                        alert('An error occurred while saving the project.');
                    }
                };
                
                xhr.onerror = function(error) {
                    console.error('Network error occurred:', error);
                    alert('Network error occurred');
                };
                
                console.log('Sending form data');
                xhr.send(formData);
            });
            
            // Add Project button click handler
            document.getElementById('addProjectBtn').addEventListener('click', function() {
                console.log('Add Project button clicked');
                
                // Reset the form
                document.getElementById('projectForm').reset();
                document.getElementById('projectId').value = '';
                
                // Set default dates for start and end date fields
                const now = new Date();
                const futureDate = new Date();
                futureDate.setDate(futureDate.getDate() + 30); // 30 days in the future
                
                // Format dates for datetime-local input (YYYY-MM-DDTHH:MM)
                document.getElementById('start_date').value = now.toISOString().slice(0, 16);
                document.getElementById('end_date').value = futureDate.toISOString().slice(0, 16);
                
                // Set modal title
                document.getElementById('modalTitle').textContent = 'Add New Project';
                
                // Show the modal
                const modal = document.getElementById('projectEditModal');
                modal.style.display = 'flex';
                modal.classList.add('active');
                console.log('Modal should be visible now');
            });
            
            // Edit project handler - update the edit buttons to use the modal
            const editBtns = document.querySelectorAll('.btn-edit');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const projectId = this.getAttribute('href').split('/').pop();
                    console.log('Edit project clicked:', projectId);
                    editProject(projectId);
                });
            });
            
            function editProject(projectId) {
                console.log('Editing project ID:', projectId);
                
                // Show loading indicator or message
                
                // Get root URL from meta or fall back to the PHP URL_ROOT constant
                const rootUrl = document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>';
                console.log('Root URL:', rootUrl);
                
                // Send AJAX request to get project data
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `${rootUrl}/dashboard/getProjectData?id=${projectId}`, true);
                
                xhr.onload = function() {
                    console.log('XHR status:', xhr.status);
                    console.log('XHR response:', xhr.responseText);
                    
                    if (xhr.status === 200) {
                        try {
                            const projectData = JSON.parse(xhr.responseText);
                            console.log('Project data:', projectData);
                            
                            // Populate the form
                            document.getElementById('projectId').value = projectData.id;
                            document.getElementById('title').value = projectData.title;
                            document.getElementById('description').value = projectData.description;
                            document.getElementById('category').value = projectData.category;
                            document.getElementById('status').value = projectData.status;
                            
                            // Format dates for the input fields (YYYY-MM-DDTHH:MM)
                            const startDate = new Date(projectData.start_date);
                            const endDate = new Date(projectData.end_date);
                            
                            const formatDate = (date) => {
                                return date.getFullYear() + '-' + 
                                    String(date.getMonth() + 1).padStart(2, '0') + '-' +
                                    String(date.getDate()).padStart(2, '0') + 'T' +
                                    String(date.getHours()).padStart(2, '0') + ':' +
                                    String(date.getMinutes()).padStart(2, '0');
                            };
                            
                            document.getElementById('start_date').value = formatDate(startDate);
                            document.getElementById('end_date').value = formatDate(endDate);
                            
                            document.getElementById('location').value = projectData.location || '';
                            document.getElementById('max_participants').value = projectData.max_participants || '';
                            document.getElementById('is_remote').checked = projectData.is_remote == 1;
                            document.getElementById('skills_required').value = projectData.skills_required || '';
                            document.getElementById('image').value = projectData.image || '';
                            
                            // Set modal title with project name
                            document.getElementById('modalTitle').textContent = `Edit Project: ${projectData.title}`;
                            
                            // Show the modal
                            const modal = document.getElementById('projectEditModal');
                            modal.style.display = 'flex';
                            modal.classList.add('active');
                            console.log('Modal should be visible now');
                        } catch (e) {
                            console.error('Error parsing project data:', e);
                            alert('Failed to load project data');
                        }
                    } else {
                        console.error('Failed to load project data, status:', xhr.status);
                        alert('Failed to load project data');
                    }
                };
                
                xhr.onerror = function(e) {
                    console.error('Network error occurred:', e);
                    alert('Network error occurred');
                };
                
                xhr.send();
            }
            
            function showError(inputElement, message) {
                const formGroup = inputElement.closest('.form-group');
                let errorElement = formGroup.querySelector('.error-message');
                if (!errorElement) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'error-message';
                    errorElement.style.color = 'red';
                    errorElement.style.fontSize = '0.8em';
                    errorElement.style.marginTop = '0.25rem';
                    formGroup.appendChild(errorElement);
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
            
            // Search and filtering
            document.getElementById('projectSearch').addEventListener('input', filterProjects);
            document.getElementById('statusFilter').addEventListener('change', filterProjects);
            document.getElementById('categoryFilter').addEventListener('change', filterProjects);
            
            function filterProjects() {
                const searchTerm = document.getElementById('projectSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const categoryFilter = document.getElementById('categoryFilter').value;
                
                const rows = document.querySelectorAll('.projects-table tbody tr');
                
                rows.forEach(row => {
                    const title = row.cells[1].textContent.toLowerCase();
                    const category = row.cells[2].textContent.trim().toLowerCase();
                    const status = row.cells[7].textContent.trim().toLowerCase();
                    
                    const matchesSearch = title.includes(searchTerm) || category.includes(searchTerm);
                    const matchesStatus = !statusFilter || status.includes(statusFilter.toLowerCase());
                    const matchesCategory = !categoryFilter || category.includes(categoryFilter.toLowerCase());
                    
                    if (matchesSearch && matchesStatus && matchesCategory) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Delete project
            const deleteBtns = document.querySelectorAll('.btn-delete');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const projectId = this.getAttribute('data-project-id');
                    const projectTitle = this.getAttribute('data-project-title');
                    
                    // Set project ID in the delete form
                    document.getElementById('deleteProjectId').value = projectId;
                    document.getElementById('deleteProjectForm').action = `${document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>'}/dashboard/project_delete/${projectId}`;
                    
                    // Update confirmation message
                    document.getElementById('confirm-delete-message').textContent = 
                        `Are you sure you want to delete "${projectTitle}"? This action cannot be undone and will also delete all associated candidatures.`;
                    
                    // Show the confirmation modal
                    document.getElementById('deleteConfirmModal').classList.add('active');
                });
            });
            
            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const form = document.getElementById('deleteProjectForm');
                form.submit();
            });
            
            // Close modals
            document.getElementById('closeDeleteModal').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('closeCandidaturesModal').addEventListener('click', function() {
                document.getElementById('candidaturesModal').classList.remove('active');
            });
            
            document.getElementById('closeCandidaturesBtn').addEventListener('click', function() {
                document.getElementById('candidaturesModal').classList.remove('active');
            });
            
    // Set active sidebar item
        const sidebarItems = document.querySelectorAll('.sidebar .nav-link');
        sidebarItems.forEach(item => item.classList.remove('active'));
        
        const projectsNavItem = document.querySelector('.sidebar .nav-link[href*="projects_management"]');
        if (projectsNavItem) {
            projectsNavItem.classList.add('active');
        }
            
            // Candidatures management
            window.showCandidatures = function(projectId, projectTitle) {
                // Update modal title
                document.getElementById('candidaturesTitle').textContent = `Candidatures: ${projectTitle}`;
                
                // Load candidatures for this project
                loadCandidatures(projectId, 'pending');
                
                // Show the modal
                document.getElementById('candidaturesModal').classList.add('active');
            };
            
            window.loadCandidatures = function(projectId, status) {
                // Get candidatures data from PHP
                const candidatures = <?php echo json_encode($candidatures); ?>;
                
                // Filter candidatures by project ID and status
                const filteredCandidatures = candidatures.filter(cand => {
                    if (cand.project_id != projectId) return false;
                    
                    if (status === 'approved') {
                        return cand.status === 'approved';
                    } else if (status === 'rejected') {
                        return cand.status === 'rejected';
                    } else if (status === 'left') {
                        return cand.status === 'left';
                    } else {
                        // Default to pending
                        return cand.status === 'pending';
                    }
                });
                
                // Get the candidatures container
                const container = document.getElementById('candidaturesContent');
                
                // Clear the container
                container.innerHTML = '';
                
                // Add a table for candidatures
                if (filteredCandidatures.length > 0) {
                    let tableHtml = `
                        <table class="candidatures-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Skills</th>
                                    <th>Applied On</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    
                    filteredCandidatures.forEach(cand => {
                        const appliedDate = new Date(cand.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        
                        // Format skills as badges
                        let skillsHtml = '';
                        if (cand.skills) {
                            const skills = cand.skills.split(',');
                            skills.forEach(skill => {
                                skillsHtml += `<span class="skill-tag">${skill.trim()}</span> `;
                            });
                        }
                        
                        tableHtml += `
                            <tr>
                                <td>${cand.user_name || `User #${cand.user_id}`}</td>
                                <td>${skillsHtml || '-'}</td>
                                <td>${appliedDate}</td>
                                <td>${cand.message || '-'}</td>
                                <td>
                                    <span class="status-badge status-${cand.status}">
                                        ${cand.status.charAt(0).toUpperCase() + cand.status.slice(1)}
                                    </span>
                                </td>
                                <td>
                        `;
                        
                        // Add action buttons based on current status
                        if (cand.status === 'pending') {
                            tableHtml += `
                                <button class="btn-candidature-action btn-approve" onclick="updateCandidatureStatus(${cand.id}, 'approved')">Approve</button>
                                <button class="btn-candidature-action btn-reject" onclick="updateCandidatureStatus(${cand.id}, 'rejected')">Reject</button>
                            `;
                        } else if (cand.status === 'approved') {
                            tableHtml += `
                                <button class="btn-candidature-action btn-left" onclick="updateCandidatureStatus(${cand.id}, 'left')">Mark Left</button>
                                <button class="btn-candidature-action btn-reject" onclick="updateCandidatureStatus(${cand.id}, 'rejected')">Remove</button>
                            `;
                        } else if (cand.status === 'rejected') {
                            tableHtml += `
                                <button class="btn-candidature-action btn-approve" onclick="updateCandidatureStatus(${cand.id}, 'approved')">Approve</button>
                            `;
                        } else if (cand.status === 'left') {
                            tableHtml += `
                                <button class="btn-candidature-action btn-approve" onclick="updateCandidatureStatus(${cand.id}, 'approved')">Re-approve</button>
                            `;
                        }
                        
                        tableHtml += `
                                </td>
                            </tr>
                        `;
                    });
                    
                    tableHtml += `
                            </tbody>
                        </table>
                    `;
                    
                    container.innerHTML = tableHtml;
                } else {
                    container.innerHTML = `<p class="empty-state">No ${status} candidatures found for this project.</p>`;
                }
            };
            
            window.updateCandidatureStatus = function(candidatureId, newStatus) {
                // Send an AJAX request to update the candidature status
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>'}/dashboard/update_candidature_status`, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Update the candidature in the frontend
                                const candidatures = <?php echo json_encode($candidatures); ?>;
                                for (let i = 0; i < candidatures.length; i++) {
                                    if (candidatures[i].id == candidatureId) {
                                        candidatures[i].status = newStatus;
                                        break;
                                    }
                                }
                                
                                // Reload the current tab
                                const tabs = document.querySelectorAll('.tab-button');
                                for (let i = 0; i < tabs.length; i++) {
                                    if (tabs[i].classList.contains('active')) {
                                        const tabName = tabs[i].textContent.toLowerCase();
                                        // Get the project ID from the title
                                        const titleText = document.getElementById('candidaturesTitle').textContent;
                                        const projectId = titleText.match(/\d+/)[0];
                                        loadCandidatures(projectId, tabName);
                                        break;
                                    }
                                }
                            } else {
                                alert('Error updating candidature status: ' + response.message);
                            }
                        } catch (e) {
                            alert('Error updating candidature status');
                        }
                    } else {
                        alert('Error updating candidature status');
                    }
                };
                
                xhr.send(`candidature_id=${candidatureId}&status=${newStatus}`);
            };
            
            window.switchCandidatureTab = function(tabName) {
                // Update active tab
                const tabs = document.querySelectorAll('.tab-button');
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                    if (tab.textContent.toLowerCase() === tabName) {
                        tab.classList.add('active');
                    }
                });
                
                // Get the project ID from the title
                const titleText = document.getElementById('candidaturesTitle').textContent;
                const projectId = titleText.match(/: (.+)$/)[1];
                
                // Load candidatures for this tab
                loadCandidatures(projectId, tabName);
            };
            
            // Close modal events
            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('projectEditModal').classList.remove('active');
            });
            
            document.getElementById('cancelBtn').addEventListener('click', function() {
                document.getElementById('projectEditModal').classList.remove('active');
            });
    });

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) { // Fallback to alert if container not found
            alert(message);
            return;
        }
        
        const toast = document.createElement('div');
        toast.className = 'toast-notification ' + type;
        toast.textContent = message;
        
        container.appendChild(toast);
        
        // Trigger reflow to enable animation
        toast.offsetHeight; 
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                container.removeChild(toast);
            }, 500); // Wait for fade out animation
        }, 3000); // Display toast for 3 seconds
    }

    function updateCandidature(candidatureId, newStatus, buttonElement) {
        const actionsCell = document.getElementById('actions-' + candidatureId);
        const statusCell = document.getElementById('status-' + candidatureId);

        // Disable buttons to prevent multiple clicks
        if (actionsCell) {
            const buttons = actionsCell.getElementsByTagName('button');
            for (let btn of buttons) {
                btn.disabled = true;
            }
        }

        fetch('<?php echo URL_ROOT; ?>/dashboard/update_candidature_status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `candidature_id=${candidatureId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Status updated successfully!', 'success');
                if (statusCell) {
                    statusCell.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    statusCell.className = 'status-' + newStatus; // Update class for styling
                }
                if (actionsCell) {
                    actionsCell.innerHTML = '<span>Processed</span>'; // Update actions cell
                }
            } else {
                showToast(data.message || 'Failed to update status.', 'error');
                // Re-enable buttons if the request failed
                if (actionsCell) {
                    const buttons = actionsCell.getElementsByTagName('button');
                    for (let btn of buttons) {
                        btn.disabled = false;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
            // Re-enable buttons on network error
            if (actionsCell) {
                const buttons = actionsCell.getElementsByTagName('button');
                for (let btn of buttons) {
                    btn.disabled = false;
                }
            }
        });
    }
</script>
</div>

<?php
// Store the dashboard content in the $content variable
$content = ob_get_clean();

// Include the dashboard layout
require_once 'dashboard_layout.php';
?> 