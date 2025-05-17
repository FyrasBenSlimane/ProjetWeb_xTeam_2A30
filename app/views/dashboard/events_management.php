<?php
// Use buffers to store the dashboard content
ob_start();

// Get events data from the controller (passed in $data)
$events = $data['events'] ?? [];
$registrations = $data['registrations'] ?? [];
?>

<div class="events-management-page">
    <style>
        .events-management-page {
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
        .btn-add-event {
            background-color: #050b1f;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .btn-add-event:hover {
            background-color: #0b1c40;
        }
        
        /* Table Styles */
        .events-table-container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .events-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .events-table th,
        .events-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        .events-table th {
            background-color: #f9fafb;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            font-size: 13px;
            text-transform: uppercase;
        }
        .events-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .events-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Status and Type Styles */
        .event-status, .event-type, .event-virtual {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-published {
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
        .type-workshop {
            background-color: #ede9fe;
            color: #6d28d9;
        }
        .type-conference {
            background-color: #ffedd5;
            color: #ea580c;
        }
        .type-webinar {
            background-color: #e0f2fe;
            color: #0284c7;
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
        .btn-registrations:hover svg {
            color: #8b5cf6;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        /* Event Edit Modal */
        .event-edit-modal {
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
        .event-edit-modal.active {
            display: flex;
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
        
        /* Registrations Table */
        .registrations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 14px;
        }
        .registrations-table th,
        .registrations-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .registrations-table th {
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
        .status-attended {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-no-show {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        .btn-registration-action {
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
        .btn-attended {
            background-color: #3b82f6;
            color: white;
        }
        .btn-attended:hover {
            background-color: #2563eb;
        }
        .btn-no-show {
            background-color: #6b7280;
            color: white;
        }
        .btn-no-show:hover {
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

        /* Add Event Modal Styles */
        .btn-primary {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #1a252f;
        }

        .btn-outline {
            background-color: white;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-outline:hover {
            background-color: #f9fafb;
        }

        #addEventForm .form-input,
        #addEventForm .form-select,
        #addEventForm .form-textarea {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        #addEventForm .form-input:focus,
        #addEventForm .form-select:focus,
        #addEventForm .form-textarea:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
        }

        #addEventForm .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        #addEventForm .form-checkbox input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
        }
    </style>

    <!-- Events Management Header -->
    <div class="section-header">
        <h2 class="section-title">Events Management</h2>
        <a href="javascript:void(0)" class="btn-add-event" id="addEventBtn">Add New Event</a>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="eventSearch" placeholder="Search events by title or location">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="canceled">Canceled</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="typeFilter">
                <option value="">All Types</option>
                <?php
                // Get unique event types
                if (!empty($events)) {
                    $eventTypes = array_map(function($event) {
                        return $event->event_type;
                    }, $events);
                    $uniqueTypes = array_unique($eventTypes);
                    sort($uniqueTypes);
                    
                    foreach ($uniqueTypes as $type) {
                        echo "<option value=\"{$type}\">" . ucfirst($type) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>

    <?php flash('event_message'); ?>

    <!-- Events Table -->
    <div class="events-table-container">
        <?php if (empty($events)): ?>
            <div class="empty-state">
                <p>No events found. Click "Add New Event" to create your first event.</p>
            </div>
        <?php else: ?>
            <table class="events-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Location</th>
                        <th scope="col">Virtual</th>
                        <th scope="col">Status</th>
                        <th scope="col">Registrations</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event->id; ?></td>
                            <td><?php echo htmlspecialchars($event->title); ?></td>
                            <td>
                                <span class="event-type type-<?php echo strtolower($event->event_type); ?>">
                                    <?php echo ucfirst($event->event_type); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($event->start_date)); ?></td>
                            <td><?php echo htmlspecialchars($event->location); ?></td>
                            <td>
                                <span class="event-virtual">
                                    <?php echo $event->is_virtual ? 'Yes' : 'No'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="event-status status-<?php echo strtolower($event->status); ?>">
                                    <?php echo ucfirst($event->status); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $registrationCount = 0;
                                foreach ($registrations as $registration) {
                                    if ($registration->event_id == $event->id) {
                                        $registrationCount++;
                                    }
                                }
                                echo $registrationCount;
                                ?>
                                <button type="button" class="btn-action btn-registrations" 
                                        title="View Registrations" 
                                        onclick="showRegistrations('<?php echo $event->id; ?>', '<?php echo htmlspecialchars($event->title); ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                                </button>
                            </td>
                            <td class="actions-cell">
                                <button type="button" class="btn-action btn-edit" 
                                        title="Edit Event" 
                                        onclick="openEditEventModal(<?php echo $event->id; ?>)" 
                                        data-event-id="<?php echo $event->id; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </button>
                                <button type="button" class="btn-action btn-delete" 
                                        title="Delete Event" 
                                        data-event-id="<?php echo $event->id; ?>" 
                                        data-event-title="<?php echo htmlspecialchars($event->title); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
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
        <div class="pagination-info">Showing <?php echo count($events); ?> events</div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="confirm-modal" id="deleteConfirmModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button type="button" class="modal-close" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirm-delete-message">Are you sure you want to delete this event?</p>
                <form id="deleteEventForm" action="" method="POST">
                    <input type="hidden" id="deleteEventId" name="event_id" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Event</button>
            </div>
        </div>
    </div>
    
    <!-- Registrations Management Modal -->
    <div class="confirm-modal" id="registrationsModal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title" id="registrationsTitle">Event Registrations</h3>
                <button type="button" class="modal-close" id="closeRegistrationsModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="tab-buttons">
                    <button type="button" class="tab-button active" onclick="switchRegistrationTab('pending')">Pending</button>
                    <button type="button" class="tab-button" onclick="switchRegistrationTab('approved')">Approved</button>
                    <button type="button" class="tab-button" onclick="switchRegistrationTab('rejected')">Rejected</button>
                    <button type="button" class="tab-button" onclick="switchRegistrationTab('attended')">Attended</button>
                    <button type="button" class="tab-button" onclick="switchRegistrationTab('no-show')">No-show</button>
                </div>
                
                <div id="registrationsContent">
                    <!-- Registration data will be loaded here dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="closeRegistrationsBtn">Close</button>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="confirm-modal" id="addEventModal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title">Create New Event</h3>
                <button type="button" class="modal-close" id="closeAddEventModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addEventForm" action="<?php echo URL_ROOT; ?>/dashboard/add_event" method="POST">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="title">Event Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" class="form-input" required>
                                <div class="invalid-feedback" id="title-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Event Description <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-textarea" rows="4" required></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="event_type">Event Type</label>
                                <select id="event_type" name="event_type" class="form-select">
                                    <option value="workshop">Workshop</option>
                                    <option value="webinar">Webinar</option>
                                    <option value="conference">Conference</option>
                                    <option value="meetup">Meetup</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="category">Category</label>
                                <select id="category" name="category" class="form-select">
                                    <option value="general">General</option>
                                    <option value="technology">Technology</option>
                                    <option value="business">Business</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="design">Design</option>
                                    <option value="development">Development</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="start_date">Start Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="start_date" name="start_date" class="form-input" required>
                                <div class="invalid-feedback" id="start_date-error"></div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="end_date">End Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="end_date" name="end_date" class="form-input" required>
                                <div class="invalid-feedback" id="end_date-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-checkbox">
                        <input type="checkbox" id="is_virtual" name="is_virtual" value="1">
                        <label for="is_virtual">This is a virtual event</label>
                    </div>

                    <div class="form-row" id="locationFields">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="location">Location <span class="text-danger">*</span></label>
                                <input type="text" id="location" name="location" class="form-input" required>
                                <div class="invalid-feedback" id="location-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row" id="virtualFields" style="display: none;">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="virtual_link">Virtual Meeting Link <span class="text-danger">*</span></label>
                                <input type="text" id="virtual_link" name="virtual_link" class="form-input">
                                <div class="invalid-feedback" id="virtual_link-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="max_attendees">Maximum Attendees</label>
                                <input type="number" id="max_attendees" name="max_attendees" class="form-input" min="0" value="100">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="status">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="canceled">Canceled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Featured Image URL (Optional)</label>
                        <input type="text" id="image" name="image" class="form-input">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelAddEventBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitAddEventBtn">Create Event</button>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="event-edit-modal" id="editEventModal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title">Edit Event</h3>
                <button type="button" class="modal-close" id="closeEditEventModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editEventForm" action="<?php echo URL_ROOT; ?>/dashboard/edit_event" method="POST">
                    <input type="hidden" id="edit_event_id" name="event_id">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_event_title" class="form-label">Event Title</label>
                                <input type="text" id="edit_event_title" name="title" class="form-input" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_event_type" class="form-label">Event Type</label>
                                <select id="edit_event_type" name="event_type" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="conference">Conference</option>
                                    <option value="webinar">Webinar</option>
                                    <option value="meetup">Meetup</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_event_category" class="form-label">Category</label>
                                <select id="edit_event_category" name="category" class="form-select" required>
                                    <option value="general">General</option>
                                    <option value="technology">Technology</option>
                                    <option value="business">Business</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="design">Design</option>
                                    <option value="development">Development</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_start_date" class="form-label">Start Date & Time</label>
                                <input type="datetime-local" id="edit_start_date" name="start_date" class="form-input" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_end_date" class="form-label">End Date & Time</label>
                                <input type="datetime-local" id="edit_end_date" name="end_date" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_location" class="form-label">Location</label>
                                <input type="text" id="edit_location" name="location" class="form-input" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_max_attendees" class="form-label">Max Attendees</label>
                                <input type="number" id="edit_max_attendees" name="max_attendees" class="form-input" min="1">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea id="edit_description" name="description" class="form-textarea" rows="4" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <div class="form-checkbox">
                                    <input type="checkbox" id="edit_is_virtual" name="is_virtual" value="1">
                                    <label for="edit_is_virtual">Virtual Event</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="edit_status" class="form-label">Status</label>
                                <select id="edit_status" name="status" class="form-select" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelEditEventBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitEditEventBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search and filtering
            document.getElementById('eventSearch').addEventListener('input', filterEvents);
            document.getElementById('statusFilter').addEventListener('change', filterEvents);
            document.getElementById('typeFilter').addEventListener('change', filterEvents);
            
            function filterEvents() {
                const searchTerm = document.getElementById('eventSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const typeFilter = document.getElementById('typeFilter').value;
                
                const rows = document.querySelectorAll('.events-table tbody tr');
                
                rows.forEach(row => {
                    const title = row.cells[1].textContent.toLowerCase();
                    const location = row.cells[4].textContent.toLowerCase();
                    const type = row.cells[2].textContent.trim().toLowerCase();
                    const status = row.cells[6].textContent.trim().toLowerCase();
                    
                    const matchesSearch = title.includes(searchTerm) || location.includes(searchTerm);
                    const matchesStatus = !statusFilter || status.includes(statusFilter.toLowerCase());
                    const matchesType = !typeFilter || type.includes(typeFilter.toLowerCase());
                    
                    if (matchesSearch && matchesStatus && matchesType) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Delete event
            const deleteBtns = document.querySelectorAll('.btn-delete');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-event-id');
                    const eventTitle = this.getAttribute('data-event-title');
                    
                    // Set event ID in the delete form
                    document.getElementById('deleteEventId').value = eventId;
                    document.getElementById('deleteEventForm').action = `${document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>'}/dashboard/event_delete/${eventId}`;
                    
                    // Update confirmation message
                    document.getElementById('confirm-delete-message').textContent = 
                        `Are you sure you want to delete "${eventTitle}"? This action cannot be undone.`;
                    
                    // Show the confirmation modal
                    document.getElementById('deleteConfirmModal').classList.add('active');
                });
            });
            
            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const form = document.getElementById('deleteEventForm');
                form.submit();
            });
            
            // Close modals
            document.getElementById('closeDeleteModal').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('closeRegistrationsModal').addEventListener('click', function() {
                document.getElementById('registrationsModal').classList.remove('active');
            });
            
            document.getElementById('closeRegistrationsBtn').addEventListener('click', function() {
                document.getElementById('registrationsModal').classList.remove('active');
            });
            
    // Set active sidebar item
        const sidebarItems = document.querySelectorAll('.sidebar .nav-link');
        sidebarItems.forEach(item => item.classList.remove('active'));
        
        const eventsNavItem = document.querySelector('.sidebar .nav-link[href*="events_management"]');
        if (eventsNavItem) {
            eventsNavItem.classList.add('active');
        }
            
            // Registrations management
            let currentEventIdForModal = null; // Variable to store the current event ID for the modal

            window.showRegistrations = function(eventId, eventTitle) {
                currentEventIdForModal = eventId; // Store the event ID
                
                // Update modal title
                document.getElementById('registrationsTitle').textContent = `Registrations: ${eventTitle}`;
                
                // Set 'pending' tab as active visually and load its content
                const modalTabButtons = document.querySelectorAll('#registrationsModal .tab-button');
                modalTabButtons.forEach(btn => btn.classList.remove('active'));
                const pendingTabButton = document.querySelector('#registrationsModal .tab-button[onclick="switchRegistrationTab(\'pending\')"]');
                if (pendingTabButton) {
                    pendingTabButton.classList.add('active');
                }
                loadRegistrations(eventId, 'pending');
                
                // Show the modal
                document.getElementById('registrationsModal').classList.add('active');
            };
            
            window.loadRegistrations = function(eventId, status) {
                // Get registrations data from PHP
                const registrationsData = <?php echo json_encode($registrations); ?>;
                
                // Filter registrations by event ID and status
                const filteredRegistrations = registrationsData.filter(reg => {
                    if (String(reg.event_id) !== String(eventId)) return false; // Compare as strings
                    
                    // Explicit filtering for each status tab
                    if (status === 'pending') return reg.status === 'pending';
                    if (status === 'approved') return reg.status === 'approved';
                    if (status === 'rejected') return reg.status === 'rejected';
                    if (status === 'attended') return reg.status === 'attended';
                    if (status === 'no-show') return reg.status === 'no-show';
                    
                    return false; // Default: don't show if status doesn't match a known tab
                });
                
                // Get the registrations container
                const container = document.getElementById('registrationsContent');
                
                // Clear the container
                container.innerHTML = '';
                
                // Add a table for registrations
                if (filteredRegistrations.length > 0) {
                    let tableHtml = `
                        <table class="registrations-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Registered On</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    
                    filteredRegistrations.forEach(reg => {
                        const registeredDate = new Date(reg.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        
                        tableHtml += `
                            <tr>
                                <td>${reg.user_name || `User #${reg.user_id}`}</td>
                                <td>${reg.attendance_type === 'virtual' ? 'Virtual' : 'In-Person'}</td>
                                <td>${registeredDate}</td>
                                <td>${reg.notes || '-'}</td>
                                <td>
                                    <span class="status-badge status-${reg.status}">
                                        ${reg.status.charAt(0).toUpperCase() + reg.status.slice(1)}
                                    </span>
                                </td>
                                <td>
                        `;
                        
                        // Add action buttons based on current status
                        if (reg.status === 'pending') {
                            tableHtml += `
                                <button class="btn-registration-action btn-approve" onclick="updateRegistrationStatus(${reg.id}, 'approved')">Approve</button>
                                <button class="btn-registration-action btn-reject" onclick="updateRegistrationStatus(${reg.id}, 'rejected')">Reject</button>
                            `;
                        } else if (reg.status === 'approved') {
                            tableHtml += `
                                <button class="btn-registration-action btn-attended" onclick="updateRegistrationStatus(${reg.id}, 'attended')">Mark Attended</button>
                                <button class="btn-registration-action btn-no-show" onclick="updateRegistrationStatus(${reg.id}, 'no-show')">No-Show</button>
                            `;
                        } else if (reg.status === 'rejected') {
                            tableHtml += `
                                <button class="btn-registration-action btn-approve" onclick="updateRegistrationStatus(${reg.id}, 'approved')">Approve</button>
                            `;
                        } else if (reg.status === 'no-show') {
                            tableHtml += `
                                <button class="btn-registration-action btn-attended" onclick="updateRegistrationStatus(${reg.id}, 'attended')">Mark Attended</button>
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
                    container.innerHTML = `<p class="empty-state">No registrations found for this event with status: ${status}.</p>`;
                }
            };
            
            window.updateRegistrationStatus = function(registrationId, newStatus) {
                // Send an AJAX request to update the registration status
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]')?.content || '<?php echo URL_ROOT; ?>'}/dashboard/update_registration_status`, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Reload the current tab using currentEventIdForModal
                                const activeTabButton = document.querySelector('#registrationsModal .tab-button.active');
                                if (activeTabButton && currentEventIdForModal !== null) {
                                    const onclickAttr = activeTabButton.getAttribute('onclick');
                                    const tabNameMatch = onclickAttr.match(/switchRegistrationTab\(\'([^\']+)\'\)/);
                                    if (tabNameMatch && tabNameMatch[1]) {
                                        // Fetch updated registrations data or update in-memory array
                                        // For simplicity, we are assuming $data['registrations'] will be updated on next full load
                                        // or you might need to make another AJAX call to get fresh $registrations.
                                        // For now, just re-call loadRegistrations which uses the PHP-echoed (potentially stale until page refresh) $registrations variable.
                                        // To fix this properly, an AJAX call to fetch updated registrations for the specific eventId is needed here.
                                        // Temporarily, we'll call loadRegistrations which will show the current state from the last page load.
                                        // A full solution would be to update the `registrationsData` javascript variable.
                                        loadRegistrations(currentEventIdForModal, tabNameMatch[1]);
                                        // Consider a more robust update of the `registrationsData` js variable here or refetching.
                                         // For a quick fix to refresh the list with potentially new data from server, you could reload the main registrations list:
                                        // location.reload(); // or make an ajax call to refresh $registrations variable and then call loadRegistrations
                                    }
                                }
                            } else {
                                alert('Error updating registration status: ' + response.message);
                            }
                        } catch (e) {
                            alert('Error processing server response after updating registration status.');
                            console.error("Error parsing JSON response: ", e, xhr.responseText);
                        }
                    } else {
                        alert('Server error while updating registration status.');
                    }
                };
                xhr.onerror = function() {
                    alert('Network error while trying to update registration status.');
                };
                
                xhr.send(`registration_id=${registrationId}&status=${newStatus}`);
            };
            
            window.switchRegistrationTab = function(tabName) {
                // Update active tab styling
                const modalTabButtons = document.querySelectorAll('#registrationsModal .tab-button');
                modalTabButtons.forEach(tab => tab.classList.remove('active'));
                
                const currentTabButton = document.querySelector(`#registrationsModal .tab-button[onclick="switchRegistrationTab('${tabName}')"]`);
                if (currentTabButton) {
                    currentTabButton.classList.add('active');
                }
                
                // Load registrations for this tab using the stored event ID
                if (currentEventIdForModal !== null) {
                    loadRegistrations(currentEventIdForModal, tabName);
                } else {
                    console.error('currentEventIdForModal is not set. Cannot load registrations for tab.');
                    document.getElementById('registrationsContent').innerHTML = '<p class="empty-state">Error: Event ID not found.</p>';
                }
            };

            // Add Event Modal Functionality
            const addEventBtn = document.getElementById('addEventBtn');
            const addEventModal = document.getElementById('addEventModal');
            const closeAddEventModal = document.getElementById('closeAddEventModal');
            const cancelAddEventBtn = document.getElementById('cancelAddEventBtn');
            const submitAddEventBtn = document.getElementById('submitAddEventBtn');
            const addEventForm = document.getElementById('addEventForm');

            // Open modal when Add New Event button is clicked
            if (addEventBtn) {
                addEventBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Add Event button clicked');
                    if (addEventModal) {
                        addEventModal.classList.add('active');
                        // Reset form
                        addEventForm.reset();
                        // Set default dates
                        const now = new Date();
                        const defaultStart = new Date(now.getTime() + 24 * 60 * 60 * 1000); // Tomorrow
                        const defaultEnd = new Date(defaultStart.getTime() + 2 * 60 * 60 * 1000); // 2 hours later
                        
                        document.getElementById('start_date').value = defaultStart.toISOString().slice(0, 16);
                        document.getElementById('end_date').value = defaultEnd.toISOString().slice(0, 16);
                    } else {
                        console.error('addEventModal element not found');
                    }
                });
            } else {
                console.error('addEventBtn element not found');
            }

            // Close modal handlers
            if (closeAddEventModal) {
                closeAddEventModal.addEventListener('click', function() {
                    addEventModal.classList.remove('active');
                });
            }

            if (cancelAddEventBtn) {
                cancelAddEventBtn.addEventListener('click', function() {
                    addEventModal.classList.remove('active');
                });
            }

            // Form submission
            if (submitAddEventBtn && addEventForm) {
                submitAddEventBtn.addEventListener('click', function() {
                    console.log('Submit button clicked');
                    if (addEventForm.checkValidity()) {
                        // Validate dates
                        const startDate = new Date(document.getElementById('start_date').value);
                        const endDate = new Date(document.getElementById('end_date').value);
                        
                        if (endDate <= startDate) {
                            alert('End date must be after start date');
                            return;
                        }
                        
                        console.log('Form valid, submitting...');
                        // Submit form
                        addEventForm.submit();
                    } else {
                        console.log('Form validation failed');
                        // Trigger browser's native form validation
                        addEventForm.reportValidity();
                    }
                });
            } else {
                console.error('submitAddEventBtn or addEventForm element not found');
            }

            // Close modal when clicking outside
            if (addEventModal) {
                addEventModal.addEventListener('click', function(e) {
                    if (e.target === addEventModal) {
                        addEventModal.classList.remove('active');
                    }
                });
            }

            // Edit Event Modal Functionality
            const editEventModal = document.getElementById('editEventModal');
            const closeEditEventModal = document.getElementById('closeEditEventModal');
            const cancelEditEventBtn = document.getElementById('cancelEditEventBtn');
            const submitEditEventBtn = document.getElementById('submitEditEventBtn');
            const editEventForm = document.getElementById('editEventForm');

            // Open edit modal
            window.openEditEventModal = function(eventId) {
                // Find the event data
                const eventData = <?php echo json_encode($events); ?>.find(event => event.id == eventId);
                
                if (eventData) {
                    // Fill the form with event data
                    document.getElementById('edit_event_id').value = eventData.id;
                    document.getElementById('edit_event_title').value = eventData.title;
                    document.getElementById('edit_event_type').value = eventData.event_type;
                    document.getElementById('edit_event_category').value = eventData.category;
                    document.getElementById('edit_start_date').value = new Date(eventData.start_date).toISOString().slice(0, 16);
                    document.getElementById('edit_end_date').value = new Date(eventData.end_date).toISOString().slice(0, 16);
                    document.getElementById('edit_location').value = eventData.location;
                    document.getElementById('edit_max_attendees').value = eventData.max_attendees;
                    document.getElementById('edit_description').value = eventData.description;
                    document.getElementById('edit_is_virtual').checked = eventData.is_virtual == 1;
                    document.getElementById('edit_status').value = eventData.status;
                    
                    // Show the modal
                    editEventModal.classList.add('active');
                } else {
                    console.error('Event not found:', eventId);
                }
            };

            // Close edit modal
            if (closeEditEventModal) {
                closeEditEventModal.addEventListener('click', function() {
                    editEventModal.classList.remove('active');
                });
            }

            // Cancel edit
            if (cancelEditEventBtn) {
                cancelEditEventBtn.addEventListener('click', function() {
                    editEventModal.classList.remove('active');
                });
            }

            // Submit edit form
            if (submitEditEventBtn && editEventForm) {
                submitEditEventBtn.addEventListener('click', function() {
                    console.log('Edit submit button clicked');
                    if (editEventForm.checkValidity()) {
                        // Validate dates
                        const startDate = new Date(document.getElementById('edit_start_date').value);
                        const endDate = new Date(document.getElementById('edit_end_date').value);
                        
                        if (endDate <= startDate) {
                            alert('End date must be after start date');
                            return;
                        }
                        
                        console.log('Edit form valid, submitting...');
                        // Submit form
                        editEventForm.submit();
                    } else {
                        console.log('Edit form validation failed');
                        // Trigger browser's native form validation
                        editEventForm.reportValidity();
                    }
                });
            } else {
                console.error('submitEditEventBtn or editEventForm element not found');
            }

            // Close edit modal when clicking outside
            if (editEventModal) {
                editEventModal.addEventListener('click', function(e) {
                    if (e.target === editEventModal) {
                        editEventModal.classList.remove('active');
                    }
                });
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