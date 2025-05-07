<?php
// Use buffers to store the dashboard content
ob_start();

// Get users data from the controller (passed in $data)
$users = $data['users'] ?? [];
?>

<div class="user-management-page">
    <style>
        .user-management-page {
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
        .btn-add-user {
            background-color: #050b1f;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .btn-add-user:hover {
            background-color: #0b1c40;
        }
        
        /* Table Styles */
        .users-table-container {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .users-table th,
        .users-table td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        .users-table th {
            background-color: #f9fafb;
            color: #475569;
            font-weight: 600;
            white-space: nowrap;
            font-size: 13px;
            text-transform: uppercase;
        }
        .users-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .users-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Status and Role Styles */
        .user-status, .user-role {
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
        .role-admin {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .role-client {
            background-color: #ffedd5;
            color: #ea580c;
        }
        .role-freelancer {
            background-color: #ede9fe;
            color: #6d28d9;
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
        .btn-edit:hover svg {
            color: #0369a1;
        }
        .btn-delete:hover svg {
            color: #dc2626;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        /* User Edit Modal */
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
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
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
    </style>

    <!-- User Management Header -->
    <div class="section-header">
        <h2 class="section-title">User Management</h2>
        <button class="btn-add-user" id="addUserBtn">Add New User</button>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters">
        <div class="search-input">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="userSearch" placeholder="Search users by name or email">
        </div>
        <div class="filter-select">
            <select id="statusFilter">
                <option value="">All Statuses</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="filter-select">
            <select id="roleFilter">
                <option value="">All Roles</option>
                <?php
                // Get unique roles from all users
                $allRoles = array_map(function($user) {
                    return $user['role'];
                }, $users);
                $uniqueRoles = array_unique($allRoles);
                sort($uniqueRoles);
                
                foreach ($uniqueRoles as $role) {
                    echo "<option value=\"{$role}\">{$role}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <p>No users found. Click "Add New User" to add your first user.</p>
            </div>
        <?php else: ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="user-role role-<?php echo strtolower($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></span>
                            </td>
                            <td>
                                <span class="user-status status-<?php echo strtolower($user['status']); ?>"><?php echo htmlspecialchars($user['status']); ?></span>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($user['registeredDate'])); ?></td>
                            <td><?php echo date('M j, Y', strtotime($user['lastLogin'])); ?></td>
                            <td class="actions-cell">
                                <button class="btn-action btn-edit" data-user-id="<?php echo $user['id']; ?>" title="Edit User">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button class="btn-action btn-status" data-user-id="<?php echo $user['id']; ?>" data-current-status="<?php echo $user['status']; ?>" title="Toggle Status">
                                    <?php if ($user['status'] === 'Active'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    <?php endif; ?>
                                </button>
                                <button class="btn-action btn-delete" data-user-id="<?php echo $user['id']; ?>" data-user-name="<?php echo htmlspecialchars($user['name']); ?>" title="Delete User">
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
        <div class="pagination-info">Showing <?php echo count($users); ?> users</div>
    </div>

    <!-- User Edit Modal -->
    <div class="user-edit-modal" id="userEditModal">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Edit User</h3>
                    <button type="button" class="modal-close" id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="userId" name="user_id" value="">
                    <div class="form-group">
                        <label class="form-label" for="userName">Name</label>
                        <input type="text" id="userName" name="name" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="userEmail">Email</label>
                        <input type="email" id="userEmail" name="email" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="userPassword">Password</label>
                        <input type="password" id="userPassword" name="password" class="form-input" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="userRole">Role</label>
                        <select id="userRole" name="role" class="form-select">
                            <?php foreach ($uniqueRoles as $role): ?>
                                <option value="<?php echo strtolower($role); ?>"><?php echo $role; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Save Changes</button>
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
                <p id="confirm-delete-message">Are you sure you want to delete this user?</p>
                <form id="deleteUserForm">
                    <input type="hidden" name="delete_user_id" id="deleteUserId" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete User</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search and filtering
            document.getElementById('userSearch').addEventListener('input', filterUsers);
            document.getElementById('statusFilter').addEventListener('change', filterUsers);
            document.getElementById('roleFilter').addEventListener('change', filterUsers);
            
            function filterUsers() {
                const searchTerm = document.getElementById('userSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const roleFilter = document.getElementById('roleFilter').value;
                
                const rows = document.querySelectorAll('.users-table tbody tr');
                
                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const role = row.cells[3].textContent.trim();
                    const status = row.cells[4].textContent.trim();
                    
                    const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                    const matchesStatus = !statusFilter || status === statusFilter;
                    const matchesRole = !roleFilter || role === roleFilter;
                    
                    if (matchesSearch && matchesStatus && matchesRole) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Form Validation for User Edit Modal
            const userForm = document.getElementById('userForm');
            userForm.addEventListener('submit', function(event) {
                let isValid = true;
                const userName = document.getElementById('userName');
                const userEmail = document.getElementById('userEmail');
                const userRole = document.getElementById('userRole');
                const userId = document.getElementById('userId').value;

                // Clear previous errors
                clearError(userName);
                clearError(userEmail);
                clearError(userRole);

                if (userName.value.trim() === '') {
                    showError(userName, 'Name is required.');
                    isValid = false;
                }

                if (userEmail.value.trim() === '') {
                    showError(userEmail, 'Email is required.');
                    isValid = false;
                } else if (!validateEmail(userEmail.value.trim())) {
                    showError(userEmail, 'Please enter a valid email address.');
                    isValid = false;
                }

                if (userRole.value === '') {
                    showError(userRole, 'Role is required.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                } else {
                    // If valid, proceed with the existing AJAX submission logic (or standard form submission if not using AJAX)
                    // The original saveUser function handles the AJAX part
                    event.preventDefault(); // Prevent default only if using AJAX
                    saveUser(); // Call the function that handles AJAX submission
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

            function validateEmail(email) {
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }

            // Edit user
            const editBtns = document.querySelectorAll('.btn-edit');
            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    editUser(userId);
                });
            });
            
            function editUser(userId) {
                // Show loading indicator
                showToast('Processing', 'Loading user data...', 'info');
                
                // Send AJAX request to get user data
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `${document.querySelector('meta[name="root-url"]').content}/dashboard/getUserData?user_id=${userId}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const userData = JSON.parse(xhr.responseText);
                            
                            // Populate the form
                            document.getElementById('userId').value = userData.id;
                            document.getElementById('userName').value = userData.name;
                            document.getElementById('userEmail').value = userData.email;
                            document.getElementById('userPassword').value = ''; // Always empty for security
                            document.getElementById('userRole').value = userData.role.toLowerCase();
                            
                            // Set modal title with user name
                            document.getElementById('modalTitle').textContent = `Edit User: ${userData.name}`;
                            
                            // Show the modal
                            document.getElementById('userEditModal').classList.add('active');
                        } catch (e) {
                            console.error('Error parsing user data:', e);
                            showToast('Error', 'Failed to load user data', 'error');
                        }
                    } else {
                        showToast('Error', 'Failed to load user data', 'error');
                    }
                };
                
                xhr.onerror = function() {
                    showToast('Error', 'Network error occurred', 'error');
                };
                
                xhr.send();
            }
            
            // Toggle user status
            const statusBtns = document.querySelectorAll('.btn-status');
            statusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
                    
                    toggleUserStatus(userId, newStatus, this);
                });
            });
            
            function toggleUserStatus(userId, newStatus, button) {
                // Show loading indicator
                showToast('Processing', `Updating user status to ${newStatus}...`, 'info');
                
                // Send AJAX request to update user status
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('userId', userId);
                formData.append('status', newStatus);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/toggleUserStatus`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Update the UI
                                const row = button.closest('tr');
                                const statusCell = row.cells[4].querySelector('.user-status');
                                
                                // Remove current status class
                                statusCell.classList.remove('status-active', 'status-inactive');
                                // Add new status class
                                statusCell.classList.add(`status-${newStatus.toLowerCase()}`);
                                // Update text
                                statusCell.textContent = newStatus;
                                
                                // Update button data attribute and icon
                                button.setAttribute('data-current-status', newStatus);
                                
                                if (newStatus === 'Active') {
                                    button.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    `;
                                } else {
                                    button.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    `;
                                }
                                
                                showToast('Success', `User status changed to ${newStatus}`, 'success');
                            } else {
                                showToast('Error', response.message || 'Failed to update user status', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to update user status', 'error');
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
            
            // Delete user
            const deleteBtns = document.querySelectorAll('.btn-delete');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    
                    // Set user ID in the delete form
                    document.getElementById('deleteUserId').value = userId;
                    
                    // Update confirmation message
                    document.getElementById('confirm-delete-message').textContent = 
                        `Are you sure you want to delete ${userName}? This action cannot be undone.`;
                    
                    // Show the confirmation modal
                    document.getElementById('deleteConfirmModal').classList.add('active');
                });
            });
            
            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const userId = document.getElementById('deleteUserId').value;
                
                if (!userId) {
                    showToast('Error', 'No user selected for deletion', 'error');
                    return;
                }
                
                // Show loading indicator
                showToast('Processing', 'Deleting user...', 'info');
                
                // Send AJAX request to delete user
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                formData.append('userId', userId);
                
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/deleteUser`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Remove the user from the table
                                const rows = document.querySelectorAll('.users-table tbody tr');
                                for (let i = 0; i < rows.length; i++) {
                                    const row = rows[i];
                                    if (row.cells[0].textContent == userId) {
                                        row.remove();
                                        break;
                                    }
                                }
                                
                                // Close the confirmation modal
                                document.getElementById('deleteConfirmModal').classList.remove('active');
                                
                                showToast('Success', 'User was deleted successfully', 'success');
                                
                                // Check if table is empty
                                const remainingRows = document.querySelectorAll('.users-table tbody tr');
                                if (remainingRows.length === 0) {
                                    const tableContainer = document.querySelector('.users-table-container');
                                    tableContainer.innerHTML = `
                                        <div class="empty-state">
                                            <p>No users found. Click "Add New User" to add your first user.</p>
                                        </div>
                                    `;
                                }
                            } else {
                                showToast('Error', response.message || 'Failed to delete user', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to delete user', 'error');
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
            
            // Add new user
            document.getElementById('addUserBtn').addEventListener('click', function() {
                // Reset the form
                document.getElementById('userForm').reset();
                document.getElementById('userId').value = '';
                
                // Set modal title
                document.getElementById('modalTitle').textContent = 'Add New User';
                
                // Show the modal
                document.getElementById('userEditModal').classList.add('active');
            });
            
            // User form submission
            document.getElementById('userForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading indicator
                showToast('Processing', 'Saving user data...', 'info');
                
                const userId = document.getElementById('userId').value;
                const isNewUser = !userId;
                
                // Get form data
                const formData = new FormData(this);
                
                // Send AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', `${document.querySelector('meta[name="root-url"]').content}/dashboard/${isNewUser ? 'addUser' : 'updateUser'}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                showToast(
                                    'Success',
                                    isNewUser ? 'New user created successfully' : 'User updated successfully',
                                    'success'
                                );
                                
                                // Close the modal
                                document.getElementById('userEditModal').classList.remove('active');
                                
                                // Refresh the page to show updated user list
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                showToast('Error', response.message || 'Failed to save user data', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showToast('Error', 'Failed to save user data', 'error');
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
            
            // Close modals
            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('userEditModal').classList.remove('active');
            });
            
            document.getElementById('cancelBtn').addEventListener('click', function() {
                document.getElementById('userEditModal').classList.remove('active');
            });
            
            document.getElementById('closeDeleteModal').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
            
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                document.getElementById('deleteConfirmModal').classList.remove('active');
            });
        });
    </script>
</div>

<?php
// Store the dashboard content in the $content variable
$content = ob_get_clean();

// Include the dashboard layout
require_once 'dashboard_layout.php';
?>