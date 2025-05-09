<?php

/**
 * Dashboard Sidebar Template
 * This file renders the sidebar navigation for the admin dashboard
 */

/**
 * Helper function to get ticket statistics safely
 */
if (!function_exists('getTicketStats')) {
    function getTicketStats()
    {
        // Default empty stats array
        $ticketStats = [
            'open' => 0,
            'total' => 0,
            'pending' => 0,
            'answered' => 0,
            'closed' => 0
        ];

        // Only try to use the Support class if it's been loaded
        if (class_exists('Support')) {
            try {
                $supportModel = new Support();
                $ticketStats = $supportModel->getTicketStatistics();
            } catch (Exception $e) {
                // Silent fail if there's any error with Support model
                error_log('Error loading Support model in sidebar: ' . $e->getMessage());
            }
        }

        return $ticketStats;
    }
}

/**
 * Helper function to generate menu active class
 */
if (!function_exists('getMenuActiveClass')) {
    function getMenuActiveClass($data, $activeValues, $parentValue)
    {
        $isActive = false;

        // Check if any of the active values match the current page
        if (isset($data['active']) && in_array($data['active'], $activeValues)) {
            $isActive = true;
        }

        // Check if parent value matches the active parent
        if (isset($data['active_parent']) && $data['active_parent'] === $parentValue) {
            $isActive = true;
        }

        return $isActive ? ' class="active has-submenu"' : ' class="has-submenu"';
    }
}

/**
 * Helper function to determine submenu visibility
 */
if (!function_exists('getSubmenuVisibility')) {
    function getSubmenuVisibility($data, $activeValues, $parentValue)
    {
        $isVisible = false;

        // Check if any of the active values match the current page
        if (isset($data['active']) && in_array($data['active'], $activeValues)) {
            $isVisible = true;
        }

        // Check if parent value matches the active parent
        if (isset($data['active_parent']) && $data['active_parent'] === $parentValue) {
            $isVisible = true;
        }

        return $isVisible ? ' show' : '';
    }
}

?>
<!-- Sidebar -->
<section id="sidebar">
    <a href="<?php echo URL_ROOT; ?>" class="brand">
        <i class="bx bxs-dashboard"></i>
        <span class="text"><?php echo SITE_NAME; ?></span>
    </a>

    <!-- Mobile Toggle Button -->
    <div class="mobile-toggle" id="sidebar-toggle">
        <i class='bx bx-menu'></i>
    </div>

    <!-- Desktop Toggle Button -->
    <div class="desktop-toggle" id="sidebar-collapse">
        <i class='bx bx-chevron-left'></i>
    </div>

    <ul class="side-menu top">
        <!-- Dashboard Menu Item -->
        <li<?php echo (!isset($data['active']) || $data['active'] === 'dashboard') ? ' class="active"' : ''; ?>>
            <a href="<?php echo URL_ROOT; ?>/dashboard">
                <i class="bx bxs-home-circle"></i>
                <span class="text">Dashboard</span>
            </a>
            </li>

            <!-- Users Menu Item -->
            <li<?php echo (isset($data['active']) && $data['active'] === 'users') ? ' class="active"' : ''; ?>>
                <a href="<?php echo URL_ROOT; ?>/dashboard/users">
                    <i class="bx bxs-user-account"></i>
                    <span class="text">Users</span>
                </a>
                </li>

                <!-- Support Menu Item -->
                <li<?php echo getMenuActiveClass($data, ['support', 'faq', 'tickets'], 'support'); ?>>
                    <a href="<?php echo URL_ROOT; ?>/dashboard/support" class="menu-item">
                        <i class="bx bxs-message-rounded-dots"></i>
                        <span class="text">Support</span>
                        <?php
                        // Get ticket stats from data if available (should be passed from controller)
                        $ticketStats = $data['ticketStats'] ?? getTicketStats();

                        // Display badge if there are open tickets
                        if (isset($ticketStats['open']) && $ticketStats['open'] > 0):
                        ?>
                            <span class="notification pulse" id="sidebar-ticket-count"><?php echo $ticketStats['open']; ?></span>
                        <?php endif; ?>
                        <i class="bx bx-chevron-right submenu-icon"></i>
                    </a>
                    <ul class="submenu<?php echo getSubmenuVisibility($data, ['support', 'faq', 'tickets'], 'support'); ?>">
                        <li<?php echo (isset($data['active']) && ($data['active'] === 'support' || $data['active'] === 'tickets')) ? ' class="active"' : ''; ?>>
                            <a href="<?php echo URL_ROOT; ?>/dashboard/support">
                                <i class="bx bxs-inbox"></i>
                                <span>All Tickets</span>
                                <span class="badge"><?php echo isset($ticketStats['total']) ? $ticketStats['total'] : '0'; ?></span>
                            </a>
                            </li>
                            <li<?php echo (isset($data['active']) && $data['active'] === 'faq') ? ' class="active"' : ''; ?>>
                                <a href="<?php echo URL_ROOT; ?>/dashboard/faq">
                                    <i class="bx bxs-help-circle"></i>
                                    <span>FAQ Management</span>
                                </a>
                                </li>
                    </ul>
                    </li>

                    <!-- Community Menu Item -->
                    <li<?php echo getMenuActiveClass($data, ['community', 'forums', 'groups', 'resources'], 'community'); ?>>
                        <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="menu-item">
                            <i class="bx bxs-chat"></i>
                            <span class="text">Community</span>
                            <i class="bx bx-chevron-right submenu-icon"></i>
                        </a>
                        <ul class="submenu<?php echo getSubmenuVisibility($data, ['community', 'forums', 'groups', 'resources'], 'community'); ?>">
                            <li<?php echo (isset($data['active']) && $data['active'] === 'forums') ? ' class="active"' : ''; ?>>
                                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums">
                                    <i class="bx bxs-conversation"></i>
                                    <span>Forums</span>
                                </a>
                                </li>
                                <li<?php echo (isset($data['active']) && $data['active'] === 'groups') ? ' class="active"' : ''; ?>>
                                    <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=groups">
                                        <i class="bx bxs-group"></i>
                                        <span>Groups</span>
                                    </a>
                                    </li>
                                    <li<?php echo (isset($data['active']) && $data['active'] === 'resources') ? ' class="active"' : ''; ?>>
                                        <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=resources">
                                            <i class="bx bxs-file-doc"></i>
                                            <span>Resources</span>
                                        </a>
                                        </li>
                        </ul>
                        </li>
    </ul>

    <!-- Bottom Menu -->
    <ul class="side-menu bottom">
        <li>
            <a href="<?php echo URL_ROOT; ?>/users/settings" class="tooltip-menu">
                <i class="bx bxs-cog"></i>
                <span class="text">Settings</span>
                <span class="tooltip">Settings</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URL_ROOT; ?>/users/logout" class="logout tooltip-menu">
                <i class="bx bxs-log-out-circle"></i>
                <span class="text">Logout</span>
                <span class="tooltip">Logout</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">
                <?php
                // Get user initials
                $user = $_SESSION['user_name'] ?? 'Admin User';
                $initials = strtoupper(substr($user, 0, 1));
                $nameParts = explode(' ', $user);
                if (count($nameParts) > 1) {
                    $initials .= strtoupper(substr($nameParts[1], 0, 1));
                }
                echo $initials;
                ?>
            </div>
            <div class="admin-details">
                <span class="admin-name"><?php echo $_SESSION['user_name'] ?? 'Admin User'; ?></span>
                <span class="admin-role">Administrator</span>
            </div>
        </div>
    </div>
</section>

<style>
    /* Sidebar Styles */
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100%;
        background: var(--light);
        z-index: 1000;
        font-family: var(--font-family);
        transition: all 0.3s ease;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-width: thin;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        max-height: 100vh;
        /* Ensure sidebar doesn't exceed viewport height */
    }

    #sidebar::-webkit-scrollbar {
        width: 5px;
    }

    #sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: var(--grey);
        border-radius: 10px;
    }

    #sidebar.hide {
        width: 60px;
    }

    #sidebar .brand {
        font-size: 22px;
        font-weight: 700;
        height: 70px;
        display: flex;
        align-items: center;
        color: var(--primary);
        position: sticky;
        top: 0;
        left: 0;
        background: var(--light);
        z-index: 500;
        padding: 0 16px;
        box-sizing: content-box;
        text-decoration: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    #sidebar .brand i {
        min-width: 60px;
        display: flex;
        justify-content: center;
        font-size: 28px;
    }

    #sidebar .side-menu {
        width: 100%;
        margin-top: 16px;
        padding: 0;
        list-style: none;
    }

    #sidebar .side-menu.top {
        margin-bottom: auto;
        flex-grow: 1;
    }

    #sidebar .side-menu.bottom {
        margin-top: auto;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 16px;
    }

    #sidebar .side-menu li {
        background: transparent;
        margin: 4px 10px;
        border-radius: 10px;
        position: relative;
        transition: all 0.2s ease;
    }

    /* Regular menu items */
    #sidebar .side-menu>li {
        height: 48px;
    }

    /* For menu items with submenu */
    #sidebar .side-menu>li.has-submenu {
        height: auto;
        min-height: 48px;
    }

    #sidebar .side-menu li.active {
        background: rgba(var(--primary-rgb), 0.1);
    }

    #sidebar .side-menu li:hover {
        background: rgba(0, 0, 0, 0.03);
    }

    #sidebar .side-menu li a {
        width: 100%;
        height: 100%;
        background: transparent;
        display: flex;
        align-items: center;
        border-radius: 10px;
        font-size: 15px;
        color: var(--dark);
        white-space: nowrap;
        overflow-x: hidden;
        text-decoration: none;
        transition: all .3s ease;
        padding: 0 10px;
    }

    #sidebar .side-menu li.active>a {
        color: var(--primary);
        font-weight: 600;
    }

    #sidebar .side-menu li a:hover {
        color: var(--primary);
    }

    #sidebar .side-menu li a i {
        min-width: 40px;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    #sidebar .side-menu li a .text {
        margin-left: 8px;
    }

    #sidebar .side-menu li a .notification {
        margin-left: auto;
        margin-right: 16px;
        background: var(--red);
        color: white;
        min-width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    /* Notification pulse */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.7);
        }

        70% {
            box-shadow: 0 0 0 7px rgba(255, 82, 82, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 82, 82, 0);
        }
    }

    .notification.pulse {
        animation: pulse 1.5s infinite;
    }

    /* Submenu styles */
    #sidebar .submenu {
        display: none;
        list-style: none;
        padding: 5px;
        margin-top: 0;
        margin-bottom: 10px;
        border-radius: 8px;
        background-color: rgba(var(--grey-rgb), 0.3);
    }

    #sidebar .submenu.show {
        display: block;
        animation: slideDown 0.3s ease forwards;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #sidebar .submenu li {
        height: 40px;
        margin: 5px;
        border-radius: 8px;
    }

    #sidebar .submenu li a {
        padding: 0 10px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        border-radius: 8px;
        font-size: 14px;
    }

    #sidebar .submenu li a i {
        min-width: 30px;
        font-size: 16px;
    }

    #sidebar .submenu li a span:not(.badge) {
        margin-left: 8px;
        flex-grow: 1;
    }

    #sidebar .submenu li.active a {
        background: rgba(var(--primary-rgb), 0.08);
        font-weight: 600;
    }

    #sidebar .menu-item {
        cursor: pointer;
    }

    #sidebar .submenu-icon {
        transition: transform 0.3s ease;
        margin-left: auto;
        margin-right: 10px;
        font-size: 18px;
        opacity: 0.6;
    }

    #sidebar .has-submenu.active .submenu-icon {
        transform: rotate(90deg);
    }

    #sidebar .side-menu li a.logout {
        color: var(--red);
    }

    #sidebar .side-menu li a.logout:hover {
        background: rgba(var(--red-rgb), 0.08);
    }

    /* Badges */
    #sidebar .badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        margin-left: auto;
    }

    /* Tooltip for collapsed sidebar */
    .tooltip-menu .tooltip {
        position: absolute;
        left: 80px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .tooltip-menu .tooltip::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 50%;
        transform: translateY(-50%);
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-right: 6px solid rgba(0, 0, 0, 0.8);
    }

    #sidebar.hide .tooltip-menu:hover .tooltip {
        opacity: 1;
        visibility: visible;
    }

    /* Sidebar footer */
    .sidebar-footer {
        padding: 15px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        margin-top: auto;
    }

    .admin-info {
        display: flex;
        align-items: center;
        padding: 10px;
        background: rgba(var(--primary-rgb), 0.05);
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .admin-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        margin-right: 12px;
    }

    .admin-details {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .admin-name {
        font-weight: 600;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .admin-role {
        font-size: 12px;
        color: var(--dark-grey);
    }

    /* Collapsed sidebar styles */
    #sidebar.hide .brand span.text,
    #sidebar.hide .side-menu li a span.text,
    #sidebar.hide .side-menu li a .submenu-icon,
    #sidebar.hide .admin-details,
    #sidebar.hide .notification {
        display: none;
    }

    #sidebar.hide .admin-avatar {
        margin-right: 0;
        margin: 0 auto;
    }

    #sidebar.hide .admin-info {
        padding: 10px 0;
        display: flex;
        justify-content: center;
    }

    /* Sidebar toggle button */
    #sidebar-toggle {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1002;
        width: 40px;
        height: 40px;
        background-color: var(--primary);
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    #sidebar-toggle:hover {
        background-color: var(--blue);
        transform: scale(1.05);
    }

    #sidebar-toggle i {
        font-size: 20px;
    }

    /* Responsive Styles */
    @media screen and (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
            width: 280px;
        }

        #sidebar.show {
            transform: translateX(0);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }

        #sidebar-toggle {
            display: flex;
        }

        .dashboard-content {
            margin-left: 0 !important;
            width: 100% !important;
        }

        .admin-info {
            display: flex;
        }

        #sidebar .brand span.text,
        #sidebar .side-menu li a span.text,
        #sidebar .side-menu li a .submenu-icon,
        #sidebar .admin-details {
            display: block;
        }
    }

    /* On load sidebar should be visible on desktop */
    @media screen and (min-width: 769px) {
        .dashboard-content {
            margin-left: 280px;
            width: calc(100% - 280px);
        }

        #sidebar.hide+.dashboard-content {
            margin-left: 60px;
            width: calc(100% - 60px);
        }

        #sidebar-collapse {
            display: flex;
        }
    }

    /* Sidebar collapse button */
    #sidebar-collapse {
        position: fixed;
        bottom: 20px;
        left: 240px;
        z-index: 1002;
        width: 30px;
        height: 30px;
        background-color: var(--light);
        color: var(--dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    #sidebar-collapse:hover {
        background-color: var(--grey);
    }

    #sidebar-collapse i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    #sidebar.hide #sidebar-collapse {
        left: 20px;
    }

    #sidebar.hide #sidebar-collapse i {
        transform: rotate(180deg);
    }

    @media screen and (max-width: 768px) {
        #sidebar-collapse {
            display: none !important;
        }
    }

    /* Dark mode compatibility */
    body.dark-mode #sidebar {
        background: #1e1e1e;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    body.dark-mode #sidebar .brand {
        background: #1e1e1e;
        border-bottom-color: #2a2a2a;
    }

    body.dark-mode #sidebar .side-menu li a {
        color: #e0e0e0;
    }

    body.dark-mode #sidebar .side-menu li:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    body.dark-mode #sidebar .side-menu li.active {
        background: rgba(var(--primary-rgb), 0.2);
    }

    body.dark-mode #sidebar .side-menu.bottom {
        border-top-color: #2a2a2a;
    }

    body.dark-mode .sidebar-footer {
        border-top-color: #2a2a2a;
    }

    body.dark-mode .admin-info {
        background: rgba(255, 255, 255, 0.05);
    }

    body.dark-mode .admin-name {
        color: #e0e0e0;
    }

    body.dark-mode .admin-role {
        color: #a0a0a0;
    }

    body.dark-mode #sidebar-collapse {
        background-color: #2a2a2a;
        color: #e0e0e0;
        border-color: #3a3a3a;
    }

    /* Sidebar toggle buttons */
    .mobile-toggle {
        position: fixed;
        top: 20px;
        left: 300px;
        z-index: 1005;
        width: 40px;
        height: 40px;
        background-color: var(--primary);
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .mobile-toggle i {
        font-size: 22px;
    }

    .desktop-toggle {
        position: absolute;
        top: 20px;
        right: -15px;
        z-index: 1001;
        width: 30px;
        height: 30px;
        background-color: var(--light);
        color: var(--dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .desktop-toggle:hover {
        background-color: var(--primary);
        color: white;
    }

    .desktop-toggle i {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    #sidebar.hide .desktop-toggle i {
        transform: rotate(180deg);
    }

    /* Adjust for mobile */
    @media screen and (max-width: 768px) {
        .mobile-toggle {
            display: flex;
            left: 20px;
        }

        .desktop-toggle {
            display: none;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sidebar toggle buttons
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarCollapse = document.getElementById('sidebar-collapse');

        // Toggle sidebar on mobile
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Collapse sidebar on desktop
        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('hide');

                // Save state in localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('hide'));

                // Dispatch event for other components to react
                document.dispatchEvent(new CustomEvent('sidebarToggle', {
                    detail: {
                        isHidden: sidebar.classList.contains('hide')
                    }
                }));
            });
        }

        // Check saved state from localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('hide');
        }

        // Improved handling of menu items with submenus
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Get the main href from the anchor
                const href = this.getAttribute('href');

                // Only prevent default if this is a placeholder link
                if (href === '#' || href === 'javascript:void(0);') {
                    e.preventDefault();
                } else {
                    // Allow a small delay to show the submenu animation before navigating
                    // This improves the UX by showing that there is a submenu
                    if (item.closest('.has-submenu') && !e.ctrlKey && !e.metaKey) {
                        e.preventDefault();

                        // Toggle submenu visibility
                        const parent = item.closest('.has-submenu');
                        if (parent) {
                            const submenu = parent.querySelector('.submenu');
                            if (submenu && !submenu.classList.contains('show')) {
                                submenu.classList.add('show');
                                parent.classList.add('active');

                                // Navigate after a brief delay to show submenu
                                setTimeout(() => {
                                    window.location.href = href;
                                }, 150);
                                return;
                            }
                        }

                        // If submenu is already visible or no submenu exists, navigate directly
                        window.location.href = href;
                    }
                    // Otherwise, let the default behavior happen (navigation)
                }

                // Toggle submenu visibility regardless of navigation
                const parent = this.closest('.has-submenu');
                if (parent) {
                    const submenu = parent.querySelector('.submenu');
                    if (submenu) {
                        submenu.classList.toggle('show');
                        parent.classList.toggle('active');

                        // Close other open submenus
                        document.querySelectorAll('.has-submenu .submenu.show').forEach(menu => {
                            if (menu !== submenu && menu.classList.contains('show')) {
                                menu.classList.remove('show');
                                menu.closest('.has-submenu').classList.remove('active');
                            }
                        });
                    }
                }
            });
        });

        // Make submenu links work correctly
        const submenuLinks = document.querySelectorAll('.submenu li a');
        submenuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Allow the link to work normally - don't prevent default
                e.stopPropagation();
            });
        });
    });
</script>