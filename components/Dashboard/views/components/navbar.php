<?php
/**
 * Navbar component for dashboard
 */

// Get user data from session
$user = $_SESSION['user'];
$userName = $user['first_name'] . ' ' . $user['last_name'];
$userEmail = $user['email'];
$userType = $user['user_type'] ?? 'freelancer';

// Get avatar URL
$avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&size=128&background=random";

// Check if user has a profile image
require_once __DIR__ . '/../../models/UserModel.php';
$db = $GLOBALS['pdo'] ?? null;
if ($db) {
    $userModel = new UserModel($db);
    $profileData = $userModel->getUserProfile($userEmail);
    if ($profileData && !empty($profileData['profile_image'])) {
        $avatarUrl = $profileData['profile_image'];
    }
}

// Get user notifications
require_once __DIR__ . '/../../models/NotificationModel.php';
$notificationModel = new NotificationModel($db);
try {
    // S'assurer que nous obtenons des notifications, même si la table vient d'être créée
    $notifications = $notificationModel->getUserNotifications($userEmail, 5);
    $unreadCount = $notificationModel->getUnreadCount($userEmail);
} catch (Exception $e) {
    error_log("Erreur lors de la récupération des notifications: " . $e->getMessage());
    $notifications = [];
    $unreadCount = 0;
}

// Get page title based on current page
$pageTitle = match($_GET['page'] ?? 'dashboard') {
    'profile' => 'Profile',
    'settings' => 'Settings',
    'projects' => 'Projects',
    'support-tickets' => 'Support',
    default => 'Dashboard'
};
?>

<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <button class="btn menu-toggle" type="button" id="menuToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <h1 class="page-title mb-0"><?php echo htmlspecialchars($pageTitle); ?></h1>

        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn position-relative notification-bell" type="button" data-bs-toggle="dropdown" id="notificationDropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <?php if ($unreadCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        <?php if ($unreadCount > 0): ?>
                        <a href="#" class="text-decoration-none mark-all-read">Mark all as read</a>
                        <?php endif; ?>
                    </li>
                    
                    <?php if (empty($notifications)): ?>
                    <li class="notification-empty">
                        <i class="bi bi-bell-slash d-block"></i>
                        <p class="mb-0">No notifications</p>
                    </li>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): 
                            $iconClass = '';
                            $textClass = '';
                            
                            // Define icon and color class based on notification type
                            switch ($notification['type']) {
                                case 'candidature':
                                    $data = $notification['data'];
                                    if (isset($data['status']) && $data['status'] === 'accepted') {
                                        $iconClass = 'bi-check-circle-fill text-success';
                                    } elseif (isset($data['status']) && $data['status'] === 'rejected') {
                                        $iconClass = 'bi-x-circle-fill text-danger';
                                    } elseif (isset($data['status']) && $data['status'] === 'expired') {
                                        $iconClass = 'bi-clock-history text-secondary';
                                    } else {
                                        $iconClass = 'bi-hourglass-split text-warning';
                                    }
                                    break;
                                case 'project':
                                    $iconClass = 'bi-briefcase-fill text-primary';
                                    break;
                                case 'message':
                                    $iconClass = 'bi-chat-fill text-info';
                                    break;
                                default:
                                    $iconClass = 'bi-info-circle-fill text-secondary';
                            }
                            
                            // Add class for unread notifications
                            if (!$notification['is_read']) {
                                $textClass = 'unread-notification';
                            }

                            // Check if it's a pending application notification with expiration
                            $showExpirationTime = false;
                            $expirationText = '';
                            $isExpired = false;
                            
                            if ($notification['type'] === 'candidature' && 
                                isset($notification['data']['status']) && 
                                $notification['data']['status'] === 'pending' &&
                                isset($notification['data']['expires_at'])) {
                                
                                $showExpirationTime = true;
                                $expiresAt = new DateTime($notification['data']['expires_at']);
                                $now = new DateTime();
                                $isExpired = ($now >= $expiresAt);
                                
                                if (!$isExpired) {
                                    $diff = $now->diff($expiresAt);
                                    $expirationText = sprintf(
                                        "Expires in %dd %dh %dm",
                                        $diff->d,
                                        $diff->h,
                                        $diff->i
                                    );
                                } else {
                                    $expirationText = "Expired";
                                }
                            }

                            // Determine if the message already contains expiration information
                            $messageContainsExpiration = stripos($notification['message'], 'expire') !== false;
                        ?>
                        <li>
                            <a class="dropdown-item notification-item <?php echo $textClass; ?>" 
                               href="#" 
                               data-notification-id="<?php echo $notification['id']; ?>">
                                <div class="notification-icon">
                                    <i class="bi <?php echo $iconClass; ?>"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-text">
                                        <?php 
                                        // If the message already contains expiration information, display it as is
                                        if ($messageContainsExpiration) {
                                            echo htmlspecialchars($notification['message']);
                                        } else {
                                            // Otherwise, extract the project title and status if present
                                            $projectTitle = isset($notification['data']['project_title']) ? $notification['data']['project_title'] : '';
                                            
                                            if ($notification['type'] === 'candidature' && !empty($projectTitle)) {
                                                // More concise format for applications
                                                $statusText = match($notification['data']['status'] ?? 'pending') {
                                                    'accepted' => '<span class="badge bg-success">Accepted</span>',
                                                    'rejected' => '<span class="badge bg-danger">Rejected</span>',
                                                    'expired' => '<span class="badge bg-secondary">Expired</span>',
                                                    default => '<span class="badge bg-warning text-dark">Pending</span>'
                                                };
                                                
                                                echo "Application: <strong>" . htmlspecialchars($projectTitle) . "</strong> " . $statusText;
                                            } else {
                                                // Other notification types
                                                echo htmlspecialchars($notification['message']);
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php if ($showExpirationTime && !$messageContainsExpiration): ?>
                                    <div class="expiration-badge">
                                        <i class="bi bi-clock"></i> <?php echo $expirationText; ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="notification-time">
                                        <?php 
                                        $date = new DateTime($notification['created_at']);
                                        $now = new DateTime();
                                        $diff = $now->diff($date);
                                        
                                        if ($diff->d > 0) {
                                            echo $diff->d . " day" . ($diff->d > 1 ? 's' : '') . " ago";
                                        } elseif ($diff->h > 0) {
                                            echo $diff->h . " hour" . ($diff->h > 1 ? 's' : '') . " ago";
                                        } else {
                                            echo max(1, $diff->i) . " minute" . (max(1, $diff->i) > 1 ? 's' : '') . " ago";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <li class="notification-footer">
                            <a class="small text-primary" href="?page=notifications">View all notifications</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <img src="<?php echo htmlspecialchars($avatarUrl); ?>" 
                         alt="Profile" 
                         class="rounded-circle me-2"
                         width="32"
                         height="32">
                    <span class="d-none d-md-inline"><?php echo htmlspecialchars($userName); ?></span>
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?page=profile">
                        <i class="bi bi-person-circle me-2"></i>Profile
                    </a></li>
                    <li><a class="dropdown-item" href="?page=settings">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../Login/login.php?logout=true">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- JavaScript for handling notifications -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some animation to the notification bell
    const notificationBell = document.querySelector('.notification-bell');
    if (notificationBell) {
        notificationBell.addEventListener('mouseenter', function() {
            this.querySelector('i').classList.add('bell-animation');
        });
        notificationBell.addEventListener('mouseleave', function() {
            this.querySelector('i').classList.remove('bell-animation');
        });
    }
    
    // Handle clicking on a notification
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.notificationId;
            
            // Mark as read via AJAX
            fetch('index.php?page=notifications&action=mark_read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the interface
                    this.classList.remove('fw-bold', 'unread-notification');
                    
                    // Update the notification counter
                    updateNotificationCounter(data.unread_count);
                    
                    // Redirect if necessary
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Handle clicking on "Mark all as read"
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Mark all notifications as read via AJAX
            fetch('index.php?page=notifications&action=mark_all_read', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the interface
                    document.querySelectorAll('.unread-notification').forEach(item => {
                        item.classList.remove('fw-bold', 'unread-notification');
                    });
                    
                    // Update the notification counter
                    updateNotificationCounter(0);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Function to update the notification counter
    function updateNotificationCounter(count) {
        const badge = document.querySelector('#notificationDropdown .badge');
        
        if (count > 0) {
            if (badge) {
                badge.textContent = count;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                newBadge.textContent = count;
                document.querySelector('#notificationDropdown').appendChild(newBadge);
            }
        } else if (badge) {
            badge.remove();
        }
    }
});
</script>

<style>
.notification-dropdown {
    width: 350px;
    max-height: 450px;
    overflow-y: auto;
    padding: 0;
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: background-color 0.2s;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread-notification {
    background-color: rgba(var(--primary-rgb), 0.04);
    position: relative;
}

.notification-item.unread-notification::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: var(--primary);
    border-radius: 0 4px 4px 0;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.03);
}

.notification-content {
    flex: 1;
}

.notification-text {
    margin-bottom: 4px;
    line-height: 1.3;
}

.notification-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.05);
    flex-shrink: 0;
}

.notification-time {
    margin-top: 4px;
    font-size: 0.75rem;
    color: #6c757d;
}

.expiration-badge {
    display: inline-flex;
    align-items: center;
    margin-top: 4px;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    background-color: rgba(255, 193, 7, 0.15);
    color: #856404;
}

.expiration-badge i {
    margin-right: 4px;
}

.dropdown-header {
    padding: 15px 16px;
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px 8px 0 0;
}

.mark-all-read {
    font-size: 0.8rem;
    padding: 2px 6px;
    border-radius: 4px;
    color: var(--primary);
    transition: background-color 0.2s;
}

.mark-all-read:hover {
    background-color: rgba(var(--primary-rgb), 0.1);
    text-decoration: none;
}

.notification-footer {
    text-align: center;
    padding: 12px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    background-color: #f8f9fa;
    border-radius: 0 0 8px 8px;
}

.notification-empty {
    padding: 30px 20px;
    text-align: center;
    color: #6c757d;
}

.notification-empty i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.4;
}

[data-bs-theme="dark"] .notification-dropdown {
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
}

[data-bs-theme="dark"] .notification-item.unread-notification {
    background-color: rgba(255, 255, 255, 0.05);
}

[data-bs-theme="dark"] .notification-item:hover {
    background-color: rgba(255, 255, 255, 0.03);
}

[data-bs-theme="dark"] .notification-icon {
    background-color: rgba(255, 255, 255, 0.1);
}

[data-bs-theme="dark"] .dropdown-header,
[data-bs-theme="dark"] .notification-footer {
    background-color: rgba(255, 255, 255, 0.05);
}

[data-bs-theme="dark"] .expiration-badge {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

/* Animation de la cloche */
@keyframes bellShake {
    0% { transform: rotate(0); }
    15% { transform: rotate(10deg); }
    30% { transform: rotate(-10deg); }
    45% { transform: rotate(5deg); }
    60% { transform: rotate(-5deg); }
    75% { transform: rotate(2deg); }
    100% { transform: rotate(0); }
}

.bell-animation {
    animation: bellShake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    transform-origin: top center;
}
</style>