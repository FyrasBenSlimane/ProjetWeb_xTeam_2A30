<?php
// Define page title based on current URL
$currentUrl = $_SERVER['REQUEST_URI'];
$currentPage = basename(parse_url($currentUrl, PHP_URL_PATH));

$pageTitle = 'Dashboard';
switch ($currentPage) {
    case 'user_management':
        $pageTitle = 'User Management';
        break;
    case 'support_tickets':
        $pageTitle = 'Support Tickets';
        break;
    case 'blog_management':
        $pageTitle = 'Blog Management';
        break;
    case 'settings':
        $pageTitle = 'Settings';
        break;
    default:
        $pageTitle = 'Dashboard';
}
?>

<header class="dashboard-header">
    <h1 class="header-title"><?php echo $pageTitle; ?></h1>
    <div class="header-actions">
        <div class="notification-button">
            <button class="icon-button" onclick="showNotification('You have no new notifications')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge"></span>
            </button>
        </div>
        
        <div class="user-profile dropdown">
            <button class="profile-button dropdown-toggle">
                <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></div>
                    <div class="user-role">Administrator</div>
                </div>
            </button>
            <div class="dropdown-content">
                <a href="#" onclick="openProfileModal(); return false;">My Profile</a>
                <a href="<?php echo URLROOT; ?>/users/logout">Logout</a>
            </div>
        </div>
    </div>
</header>

<!-- Profile Edit Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Profile</h2>
            <button class="close-modal" onclick="closeProfileModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="profileForm" onsubmit="updateProfile(event)">
                <div class="form-group">
                    <label for="name">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required minlength="2">
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control">
                    <small class="form-text text-muted">Required only if changing password</small>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" minlength="6">
                    <small class="form-text text-muted">Minimum 6 characters</small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.modal-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    color: #6b7280;
}

.modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
}

.form-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}
</style>

<script>
// Modal functions
function openProfileModal() {
    document.getElementById('profileModal').style.display = 'block';
    // Fetch current profile data
    fetchProfileData();
}

function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
    // Reset form when closing
    document.getElementById('profileForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('profileModal');
    if (event.target == modal) {
        modal.style.display = 'none';
        // Reset form when closing
        document.getElementById('profileForm').reset();
    }
}

// Fetch profile data
function fetchProfileData() {
    fetch('<?php echo URLROOT; ?>/dashboard/getProfile')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('name').value = data.data.name;
                document.getElementById('email').value = data.data.email;
                // Store original values for comparison
                document.getElementById('profileForm').dataset.originalName = data.data.name;
                document.getElementById('profileForm').dataset.originalEmail = data.data.email;
            } else {
                showNotification(data.message || 'Error fetching profile data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error fetching profile data');
        });
}

// Validate form data
function validateForm(formData) {
    const name = formData.get('name').trim();
    const email = formData.get('email').trim();
    const currentPassword = formData.get('current_password').trim();
    const newPassword = formData.get('new_password').trim();
    const confirmPassword = formData.get('confirm_password').trim();
    
    // Get original values
    const originalName = document.getElementById('profileForm').dataset.originalName;
    const originalEmail = document.getElementById('profileForm').dataset.originalEmail;
    
    // Check if any changes were made
    if (name === originalName && email === originalEmail && !newPassword) {
        showNotification('No changes were made to update');
        return false;
    }
    
    // Validate required fields
    if (!name) {
        showNotification('Name is required');
        return false;
    }
    
    if (!email) {
        showNotification('Email is required');
        return false;
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showNotification('Please enter a valid email address');
        return false;
    }
    
    // Password validation
    if (newPassword) {
        if (!currentPassword) {
            showNotification('Current password is required to set a new password');
            return false;
        }
        
        if (newPassword.length < 6) {
            showNotification('New password must be at least 6 characters long');
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            showNotification('New passwords do not match');
            return false;
        }
    }
    
    return true;
}

// Update profile
function updateProfile(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    // Validate form data
    if (!validateForm(formData)) {
        return;
    }
    
    const data = {
        name: formData.get('name').trim(),
        email: formData.get('email').trim(),
        current_password: formData.get('current_password').trim(),
        new_password: formData.get('new_password').trim(),
        confirm_password: formData.get('confirm_password').trim()
    };
    
    // Show loading state
    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = 'Updating...';
    
    fetch('<?php echo URLROOT; ?>/dashboard/updateProfile', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Profile updated successfully');
            closeProfileModal();
            // Refresh the page to update the header
            location.reload();
        } else {
            showNotification(data.message || 'Error updating profile');
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating profile');
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

// Notification function
function showNotification(message) {
    // You can implement your preferred notification system here
    alert(message);
}
</script>