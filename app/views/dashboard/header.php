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
                <a href="<?php echo URLROOT; ?>/users/profile">My Profile</a>
                <a href="<?php echo URLROOT; ?>/dashboard/settings">Settings</a>
                <a href="<?php echo URLROOT; ?>/users/logout">Logout</a>
            </div>
        </div>
    </div>
</header>