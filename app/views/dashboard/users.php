<?php

/**
 * Admin Dashboard - User Management Page
 * Allows administrators to view, add, edit, and delete users
 */

// In a real application, you would fetch this data from your database
// For now, we're using sample data
$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'account_type' => 'client', 'status' => 'active', 'created_at' => '2025-04-15'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'account_type' => 'freelancer', 'status' => 'active', 'created_at' => '2025-04-16'],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'account_type' => 'client', 'status' => 'active', 'created_at' => '2025-04-17'],
    ['id' => 4, 'name' => 'Sara Wilson', 'email' => 'sara@example.com', 'account_type' => 'freelancer', 'status' => 'suspended', 'created_at' => '2025-04-18'],
    ['id' => 5, 'name' => 'Mike Brown', 'email' => 'mike@example.com', 'account_type' => 'client', 'status' => 'active', 'created_at' => '2025-04-19'],
    ['id' => 6, 'name' => 'Lisa Davis', 'email' => 'lisa@example.com', 'account_type' => 'freelancer', 'status' => 'active', 'created_at' => '2025-04-20'],
    ['id' => 7, 'name' => 'David Miller', 'email' => 'david@example.com', 'account_type' => 'client', 'status' => 'inactive', 'created_at' => '2025-04-21'],
    ['id' => 8, 'name' => 'Emily Wilson', 'email' => 'emily@example.com', 'account_type' => 'freelancer', 'status' => 'active', 'created_at' => '2025-04-22'],
    ['id' => 9, 'name' => 'Chris Taylor', 'email' => 'chris@example.com', 'account_type' => 'admin', 'status' => 'active', 'created_at' => '2025-04-23'],
    ['id' => 10, 'name' => 'Amanda Garcia', 'email' => 'amanda@example.com', 'account_type' => 'client', 'status' => 'active', 'created_at' => '2025-04-24'],
];

// Pagination settings
$usersPerPage = 5;
$totalUsers = count($users);
$totalPages = ceil($totalUsers / $usersPerPage);
$currentPage = isset($_GET['userPage']) ? (int)$_GET['userPage'] : 1;
$currentPage = max(1, min($currentPage, $totalPages)); // Ensure valid page number
$offset = ($currentPage - 1) * $usersPerPage;
$paginatedUsers = array_slice($users, $offset, $usersPerPage);
?>

<?php
// Start output buffering
ob_start();
?>

<!-- User Management Header -->
<div class="head-title">
    <div class="left">
        <h1>User Management</h1>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="#">Users</a>
            </li>
        </ul>
    </div>
    <button class="btn btn-primary" id="addUserBtn">
        <i class='bx bxs-plus-circle'></i>
        <span class="text">Add New User</span>
    </button>
</div>

<!-- User Management Content -->
<div class="user-management">
    <!-- Search & Filters -->
    <div class="filters">
        <div class="search-container">
            <input type="text" id="userSearch" placeholder="Search users..." class="search-input">
            <i class='bx bx-search'></i>
        </div>
        <div class="filter-container">
            <select id="roleFilter" class="filter-select">
                <option value="all">All Roles</option>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
                <option value="freelancer">Freelancer</option>
            </select>
            <select id="statusFilter" class="filter-select">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <table id="usersTable" class="data-table">
            <thead>
                <tr>
                    <th>ID <i class='bx bx-sort'></i></th>
                    <th>Name <i class='bx bx-sort'></i></th>
                    <th>Email <i class='bx bx-sort'></i></th>
                    <th>Role <i class='bx bx-sort'></i></th>
                    <th>Status <i class='bx bx-sort'></i></th>
                    <th>Joined <i class='bx bx-sort'></i></th>
                    <th>Last Login <i class='bx bx-sort'></i></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($data['users']) && !empty($data['users'])): ?>
                    <?php foreach ($data['users'] as $user): ?>
                        <tr data-id="<?php echo $user['id']; ?>">
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <div class="user-cell">
                                    <?php
                                    $nameArray = explode(' ', $user['name']);
                                    $initials = '';
                                    if (isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                                    if (isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                                    ?>
                                    <div class="user-avatar"><?php echo $initials; ?></div>
                                    <div class="user-name"><?php echo $user['name']; ?></div>
                                </div>
                            </td>
                            <td><?php echo $user['email']; ?></td>
                            <td><span class="badge role-<?php echo strtolower($user['role']); ?>"><?php echo $user['role']; ?></span></td>
                            <td>
                                <span class="badge status-<?php echo strtolower($user['status']); ?>">
                                    <?php echo $user['status']; ?>
                                </span>
                            </td>
                            <td><?php echo (new DateTime($user['registeredDate']))->format('M j, Y'); ?></td>
                            <td><?php echo (new DateTime($user['lastLogin']))->format('M j, Y H:i'); ?></td>
                            <td>
                                <div class="actions">
                                    <button class="action-btn edit-btn" data-id="<?php echo $user['id']; ?>" title="Edit">
                                        <i class='bx bxs-edit'></i>
                                    </button>
                                    <button class="action-btn toggle-status-btn"
                                        data-id="<?php echo $user['id']; ?>"
                                        data-status="<?php echo $user['status']; ?>"
                                        title="<?php echo $user['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>">
                                        <i class='bx <?php echo $user['status'] === 'Active' ? 'bxs-user-x' : 'bxs-user-check'; ?>'></i>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="<?php echo $user['id']; ?>" title="Delete">
                                        <i class='bx bxs-trash'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="no-data">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <span class="pagination-info">Showing <span id="showing-start">1</span> to <span id="showing-end">
                <?php echo count($data['users']) < 10 ? count($data['users']) : 10; ?>
            </span> of <span id="total-records"><?php echo count($data['users']); ?></span> entries</span>
        <div class="pagination-controls">
            <button class="pagination-btn" id="prev-page" disabled><i class='bx bx-chevron-left'></i></button>
            <div class="page-numbers" id="page-numbers">
                <button class="page-number active" data-page="1">1</button>
                <?php
                $totalPages = ceil(count($data['users']) / 10);
                for ($i = 2; $i <= $totalPages; $i++) {
                    echo '<button class="page-number" data-page="' . $i . '">' . $i . '</button>';
                }
                ?>
            </div>
            <button class="pagination-btn" id="next-page" <?php echo $totalPages <= 1 ? 'disabled' : ''; ?>>
                <i class='bx bx-chevron-right'></i>
            </button>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New User</h3>
            <button class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <input type="hidden" id="userId" name="user_id">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="client">Client</option>
                        <option value="freelancer">Freelancer</option>
                    </select>
                </div>

                <div class="form-group password-group">
                    <label for="password">Password</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" autocomplete="new-password">
                        <button type="button" class="toggle-password">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                    <p class="password-help edit-mode-only">Leave blank to keep the current password.</p>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="confirmationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmation</h3>
            <button class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmationMessage">Are you sure you want to delete this user?</p>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelConfirmBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmActionBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Messages -->
<div class="toast-container" id="toastContainer"></div>

<style>
    /* User Management Styles */
    .user-management {
        background: var(--light);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 10px;
    }

    .search-container {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--grey);
        border-radius: 30px;
        font-size: 14px;
        padding-right: 40px;
    }

    .search-container i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dark-grey);
    }

    .filter-container {
        display: flex;
        gap: 10px;
    }

    .filter-select {
        padding: 8px 15px;
        border: 1px solid var(--grey);
        border-radius: 5px;
        background-color: var(--light);
        font-size: 14px;
        color: var(--dark);
        cursor: pointer;
    }

    .table-container {
        overflow-x: auto;
        margin-bottom: 20px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table th {
        padding: 15px 10px;
        text-align: left;
        border-bottom: 2px solid var(--grey);
        font-weight: 600;
        cursor: pointer;
    }

    .data-table th i {
        margin-left: 5px;
        font-size: 14px;
    }

    .data-table td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--grey);
    }

    .data-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .user-cell {
        display: flex;
        align-items: center;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background-color: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
        margin-right: 10px;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .role-admin {
        background-color: var(--light-blue);
        color: var(--blue);
    }

    .role-client {
        background-color: var(--light-yellow);
        color: var(--yellow);
    }

    .role-freelancer {
        background-color: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
    }

    .status-active {
        background-color: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
    }

    .status-inactive {
        background-color: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }

    .actions {
        display: flex;
        gap: 5px;
    }

    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .edit-btn {
        background-color: var(--light-blue);
        color: var(--blue);
    }

    .edit-btn:hover {
        background-color: var(--blue);
        color: white;
    }

    .toggle-status-btn {
        background-color: var(--light-yellow);
        color: var(--yellow);
    }

    .toggle-status-btn:hover {
        background-color: var(--yellow);
        color: white;
    }

    .delete-btn {
        background-color: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
    }

    .delete-btn:hover {
        background-color: #e74c3c;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .pagination-info {
        font-size: 14px;
        color: var(--dark-grey);
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pagination-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--grey);
        background-color: var(--light);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-btn:not(:disabled):hover {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .page-numbers {
        display: flex;
        gap: 5px;
    }

    .page-number {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--grey);
        background-color: var(--light);
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .page-number.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .page-number:not(.active):hover {
        background-color: var(--grey);
    }

    .no-data {
        text-align: center;
        color: var(--dark-grey);
        padding: 30px !important;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal.active {
        display: flex;
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal-content {
        background-color: var(--light);
        border-radius: 10px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid var(--grey);
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--dark-grey);
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--grey);
        border-radius: 5px;
        font-size: 14px;
    }

    .password-input-container {
        position: relative;
    }

    .password-input-container input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--dark-grey);
    }

    .password-help {
        font-size: 12px;
        color: var(--dark-grey);
        margin-top: 5px;
    }

    .edit-mode-only {
        display: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 15px;
        border-radius: 5px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: var(--grey);
        color: var(--dark);
    }

    .btn-secondary:hover {
        background-color: var(--dark-grey);
        color: white;
    }

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    /* Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1100;
    }

    .toast {
        background-color: white;
        border-radius: 5px;
        padding: 15px 20px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        min-width: 250px;
        max-width: 350px;
        animation: slideIn 0.3s, fadeOut 0.5s 2.5s forwards;
        position: relative;
    }

    .toast.success {
        border-left: 4px solid #2ecc71;
    }

    .toast.error {
        border-left: 4px solid #e74c3c;
    }

    .toast.info {
        border-left: 4px solid var(--primary);
    }

    .toast-icon {
        margin-right: 10px;
        font-size: 20px;
    }

    .toast.success .toast-icon {
        color: #2ecc71;
    }

    .toast.error .toast-icon {
        color: #e74c3c;
    }

    .toast.info .toast-icon {
        color: var(--primary);
    }

    .toast-message {
        flex: 1;
        font-size: 14px;
    }

    .toast-close {
        background: none;
        border: none;
        color: var(--dark-grey);
        font-size: 16px;
        cursor: pointer;
        margin-left: 10px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    /* Responsive Adjustments */
    @media screen and (max-width: 768px) {
        .filters {
            flex-direction: column;
        }

        .filter-container {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .pagination {
            flex-direction: column;
            align-items: flex-start;
        }

        .data-table {
            white-space: nowrap;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const userModal = document.getElementById('userModal');
        const confirmationModal = document.getElementById('confirmationModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const userForm = document.getElementById('userForm');
        const usersTable = document.getElementById('usersTable').getElementsByTagName('tbody')[0];
        const modalTitle = document.getElementById('modalTitle');
        const userSearch = document.getElementById('userSearch');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const toastContainer = document.getElementById('toastContainer');

        // Modal Close Buttons
        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                userModal.classList.remove('active');
                confirmationModal.classList.remove('active');
            });
        });

        // Toggle Password Visibility
        const togglePasswordBtn = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type');
            passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
            togglePasswordBtn.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });

        // Add User Button
        addUserBtn.addEventListener('click', function() {
            modalTitle.textContent = 'Add New User';
            userForm.reset();
            document.getElementById('userId').value = '';
            document.querySelector('.password-help').style.display = 'none';
            passwordInput.setAttribute('required', 'required');

            userModal.classList.add('active');
        });

        // Cancel Button
        cancelBtn.addEventListener('click', function() {
            userModal.classList.remove('active');
        });

        // Edit User
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-btn')) {
                const btn = e.target.closest('.edit-btn');
                const userId = btn.getAttribute('data-id');

                // Fetch user data and populate the form
                fetch(`${URL_ROOT}/dashboard/getUserData?user_id=${userId}`)
                    .then(response => response.json())
                    .then(user => {
                        modalTitle.textContent = 'Edit User';
                        document.getElementById('userId').value = user.id;
                        document.getElementById('name').value = user.name;
                        document.getElementById('email').value = user.email;
                        document.getElementById('role').value = user.role.toLowerCase();

                        // Password is not required for edit
                        passwordInput.removeAttribute('required');
                        document.querySelector('.password-help').style.display = 'block';

                        userModal.classList.add('active');
                    })
                    .catch(error => {
                        showToast('Error fetching user data', 'error');
                    });
            }
        });

        // Toggle User Status
        document.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-status-btn')) {
                const btn = e.target.closest('.toggle-status-btn');
                const userId = btn.getAttribute('data-id');
                const currentStatus = btn.getAttribute('data-status');
                const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';

                document.getElementById('confirmationMessage').textContent =
                    `Are you sure you want to ${currentStatus === 'Active' ? 'deactivate' : 'activate'} this user?`;

                const confirmBtn = document.getElementById('confirmActionBtn');
                confirmBtn.textContent = currentStatus === 'Active' ? 'Deactivate' : 'Activate';
                confirmBtn.classList.remove('btn-danger');
                confirmBtn.classList.add(currentStatus === 'Active' ? 'btn-danger' : 'btn-primary');

                confirmBtn.onclick = function() {
                    toggleUserStatus(userId, newStatus);
                    confirmationModal.classList.remove('active');
                };

                confirmationModal.classList.add('active');
            }
        });

        // Delete User
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                const btn = e.target.closest('.delete-btn');
                const userId = btn.getAttribute('data-id');

                document.getElementById('confirmationMessage').textContent = 'Are you sure you want to delete this user?';

                const confirmBtn = document.getElementById('confirmActionBtn');
                confirmBtn.textContent = 'Delete';
                confirmBtn.classList.remove('btn-primary');
                confirmBtn.classList.add('btn-danger');

                confirmBtn.onclick = function() {
                    deleteUser(userId);
                    confirmationModal.classList.remove('active');
                };

                confirmationModal.classList.add('active');
            }
        });

        // Cancel Confirmation
        document.getElementById('cancelConfirmBtn').addEventListener('click', function() {
            confirmationModal.classList.remove('active');
        });

        // Form Submit
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(userForm);
            const userId = document.getElementById('userId').value;

            if (userId) {
                // Update existing user
                updateUser(formData);
            } else {
                // Create new user
                addUser(formData);
            }
        });

        // Search & Filters
        userSearch.addEventListener('input', filterUsers);
        roleFilter.addEventListener('change', filterUsers);
        statusFilter.addEventListener('change', filterUsers);

        function filterUsers() {
            const searchTerm = userSearch.value.toLowerCase();
            const roleValue = roleFilter.value;
            const statusValue = statusFilter.value;

            const rows = usersTable.querySelectorAll('tr');

            rows.forEach(row => {
                if (row.classList.contains('no-data-row')) {
                    row.remove();
                    return;
                }

                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const email = row.cells[2]?.textContent.toLowerCase() || '';
                const role = row.querySelector('.badge.role-admin, .badge.role-client, .badge.role-freelancer')?.textContent.toLowerCase() || '';
                const status = row.querySelector('.badge.status-active, .badge.status-inactive')?.textContent.toLowerCase() || '';

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = roleValue === 'all' || role === roleValue;
                const matchesStatus = statusValue === 'all' || status === statusValue;

                if (matchesSearch && matchesRole && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Check if no visible rows
            let visibleRows = 0;
            rows.forEach(row => {
                if (row.style.display !== 'none') visibleRows++;
            });

            if (visibleRows === 0 && !document.querySelector('.no-data-row')) {
                const noDataRow = document.createElement('tr');
                noDataRow.classList.add('no-data-row');
                const noDataCell = document.createElement('td');
                noDataCell.colSpan = 8;
                noDataCell.className = 'no-data';
                noDataCell.textContent = 'No matching users found';
                noDataRow.appendChild(noDataCell);
                usersTable.appendChild(noDataRow);
            }
        }

        // API Functions
        function toggleUserStatus(userId, newStatus) {
            const formData = new FormData();
            formData.append('userId', userId);
            formData.append('status', newStatus);

            fetch(`${URL_ROOT}/dashboard/toggleUserStatus`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(`User ${newStatus === 'Active' ? 'activated' : 'deactivated'} successfully`, 'success');

                        // Update UI
                        const row = document.querySelector(`tr[data-id="${userId}"]`);
                        if (row) {
                            const statusCell = row.cells[4];
                            const statusBadge = statusCell.querySelector('.badge');
                            statusBadge.className = `badge status-${newStatus.toLowerCase()}`;
                            statusBadge.textContent = newStatus;

                            const toggleBtn = row.querySelector('.toggle-status-btn');
                            toggleBtn.setAttribute('data-status', newStatus);
                            toggleBtn.setAttribute('title', newStatus === 'Active' ? 'Deactivate' : 'Activate');
                            toggleBtn.innerHTML = newStatus === 'Active' ?
                                '<i class="bx bxs-user-x"></i>' :
                                '<i class="bx bxs-user-check"></i>';
                        }
                    } else {
                        showToast('Error updating user status', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error updating user status', 'error');
                });
        }

        function deleteUser(userId) {
            const formData = new FormData();
            formData.append('userId', userId);

            fetch(`${URL_ROOT}/dashboard/deleteUser`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('User deleted successfully', 'success');

                        // Remove row from table
                        const row = document.querySelector(`tr[data-id="${userId}"]`);
                        if (row) row.remove();

                        // Check if table is empty
                        if (usersTable.querySelectorAll('tr').length === 0) {
                            const noDataRow = document.createElement('tr');
                            const noDataCell = document.createElement('td');
                            noDataCell.colSpan = 8;
                            noDataCell.className = 'no-data';
                            noDataCell.textContent = 'No users found';
                            noDataRow.appendChild(noDataCell);
                            usersTable.appendChild(noDataRow);
                        }
                    } else {
                        showToast('Error deleting user', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error deleting user', 'error');
                });
        }

        function addUser(formData) {
            fetch(`${URL_ROOT}/dashboard/addUser`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('User added successfully', 'success');
                        userModal.classList.remove('active');

                        // Reload the page to show the new user
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error adding user', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error adding user', 'error');
                });
        }

        function updateUser(formData) {
            fetch(`${URL_ROOT}/dashboard/updateUser`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('User updated successfully', 'success');
                        userModal.classList.remove('active');

                        // Reload the page to show updated user
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message || 'Error updating user', 'error');
                    }
                })
                .catch(error => {
                    showToast('Error updating user', 'error');
                });
        }

        // Toast Function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;

            let icon = 'bx-info-circle';
            if (type === 'success') icon = 'bx-check-circle';
            if (type === 'error') icon = 'bx-x-circle';

            toast.innerHTML = `
                <i class="bx ${icon} toast-icon"></i>
                <span class="toast-message">${message}</span>
                <button class="toast-close">&times;</button>
            `;

            toastContainer.appendChild(toast);

            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.remove();
            }, 3000);

            // Close button
            toast.querySelector('.toast-close').addEventListener('click', function() {
                toast.remove();
            });
        }
    });
</script>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the dashboard layout with the content
include(APP_PATH . '/views/layouts/dashboard.php');
?>