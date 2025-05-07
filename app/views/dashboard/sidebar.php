<?php
// Get sidebar state from session or set default to open
if (!isset($_SESSION['sidebar_open'])) {
    $_SESSION['sidebar_open'] = true;
}
$isSidebarOpen = $_SESSION['sidebar_open'];

// Get current page for highlighting active nav item
$currentUrl = $_SERVER['REQUEST_URI'];
$currentPage = basename(parse_url($currentUrl, PHP_URL_PATH));
if ($currentPage == 'dashboard' || empty($currentPage)) {
    $currentPage = 'index';
}
?>

<div id="sidebar" class="sidebar <?php echo $isSidebarOpen ? '' : 'collapsed'; ?>">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon">LS</div>
            <span class="logo-text" <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>><?php echo SITE_NAME; ?> Dashboard</span>
        </div>
        <button id="toggle-sidebar" class="sidebar-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron-left" <?php echo $isSidebarOpen ? '' : 'style="transform: rotate(180deg);"'; ?>>
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard" class="nav-link <?php echo ($currentPage == 'index') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/user_management" class="nav-link <?php echo ($currentPage == 'user_management') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>User Management</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/support_tickets" class="nav-link <?php echo ($currentPage == 'support_tickets') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Support Tickets</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/blog_management" class="nav-link <?php echo ($currentPage == 'blog_management') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Blog & Community</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/settings" class="nav-link <?php echo ($currentPage == 'settings') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1-2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?php echo URLROOT; ?>/users/logout" class="logout-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Logout</span>
        </a>
    </div>
</div>