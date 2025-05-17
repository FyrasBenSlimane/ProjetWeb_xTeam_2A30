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
                <a href="<?php echo URLROOT; ?>/dashboard/events_management" class="nav-link <?php echo ($currentPage == 'events_management' || $currentPage == 'event_add' || $currentPage == 'event_edit' || $currentPage == 'event_registrations') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Event Management</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/projects_management" class="nav-link <?php echo ($currentPage == 'projects_management' || $currentPage == 'project_add' || $currentPage == 'project_edit') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Project Management</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/dashboard/resources" class="nav-link <?php echo ($currentPage == 'dashboard' && strpos($currentUrl, 'resources') !== false) ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 11a9 9 0 0 1 9 9"></path>
                        <path d="M4 4a16 16 0 0 1 16 16"></path>
                        <circle cx="5" cy="19" r="1"></circle>
                    </svg>
                    <span <?php echo $isSidebarOpen ? '' : 'style="display: none;"'; ?>>Resources</span>
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