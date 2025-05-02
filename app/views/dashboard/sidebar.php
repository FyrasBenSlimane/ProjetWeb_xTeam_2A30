<?php

/**
 * Dashboard Sidebar Template
 * This file renders the sidebar navigation for the admin dashboard
 */
?>
<!-- Sidebar -->
<section id="sidebar">
    <a href="<?php echo URL_ROOT; ?>" class="brand">
        <i class="bx bxs-dashboard"></i>
        <span class="text"><?php echo SITE_NAME; ?></span>
    </a>

    <ul class="side-menu top">
        <!-- Users Menu Item -->
        <li<?php echo (isset($data['active']) && $data['active'] === 'users') ? ' class="active"' : ''; ?>>
            <a href="<?php echo URL_ROOT; ?>/dashboard/users">
                <i class="bx bxs-group"></i>
                <span class="text">Users</span>
            </a>
            </li>

            <!-- Support Menu Item -->
            <li<?php echo (isset($data['active']) && ($data['active'] === 'support' || $data['active'] === 'faq' || $data['active'] === 'tickets') || isset($data['active_parent']) && $data['active_parent'] === 'support') ? ' class="active has-submenu"' : ' class="has-submenu"'; ?>>
                <a href="<?php echo URL_ROOT; ?>/dashboard/support" class="menu-item">
                    <i class="bx bxs-message-dots"></i>
                    <span class="text">Support</span>
                    <?php
                    // Get ticket counts from the Support model
                    try {
                        $supportModel = new Support();
                        $ticketStats = $supportModel->getTicketStatistics();

                        // Display badge if there are open tickets
                        if ($ticketStats['open'] > 0):
                    ?>
                            <span class="notification" id="sidebar-ticket-count"><?php echo $ticketStats['open']; ?></span>
                    <?php
                        endif;
                    } catch (Exception $e) {
                        // Silent fail if Support model can't be loaded
                    }
                    ?>
                    <i class="bx bx-chevron-right submenu-icon"></i>
                </a>
                <ul class="submenu<?php echo (isset($data['active']) && ($data['active'] === 'support' || $data['active'] === 'faq' || $data['active'] === 'tickets') || isset($data['active_parent']) && $data['active_parent'] === 'support') ? ' show' : ''; ?>">
                    <li<?php echo (isset($data['active']) && ($data['active'] === 'support' || $data['active'] === 'tickets')) ? ' class="active"' : ''; ?>>
                        <a href="<?php echo URL_ROOT; ?>/dashboard/support">
                            <span>Tickets</span>
                            <span class="badge bg-primary"><?php echo isset($ticketStats) ? $ticketStats['total'] : '0'; ?></span>
                        </a>
                        </li>
                        <li<?php echo (isset($data['active']) && $data['active'] === 'faq') ? ' class="active"' : ''; ?>>
                            <a href="<?php echo URL_ROOT; ?>/dashboard/faq">
                                <span>FAQ Management</span>
                            </a>
                            </li>
                </ul>
                </li>

                <!-- Community Menu Item -->
                <li<?php echo (isset($data['active']) && ($data['active'] === 'community' || $data['active'] === 'forums' || $data['active'] === 'groups' || $data['active'] === 'resources') || isset($data['active_parent']) && $data['active_parent'] === 'community') ? ' class="active has-submenu"' : ' class="has-submenu"'; ?>>
                    <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="menu-item">
                        <i class="bx bxs-chat"></i>
                        <span class="text">Community</span>
                        <i class="bx bx-chevron-right submenu-icon"></i>
                    </a>
                    <ul class="submenu<?php echo (isset($data['active']) && ($data['active'] === 'community' || $data['active'] === 'forums' || $data['active'] === 'groups' || $data['active'] === 'resources') || isset($data['active_parent']) && $data['active_parent'] === 'community') ? ' show' : ''; ?>">
                        <li<?php echo (isset($data['active']) && $data['active'] === 'forums') ? ' class="active"' : ''; ?>>
                            <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums">
                                <span>Forums</span>
                            </a>
                            </li>
                            <li<?php echo (isset($data['active']) && $data['active'] === 'groups') ? ' class="active"' : ''; ?>>
                                <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=groups">
                                    <span>Groups</span>
                                </a>
                                </li>
                                <li<?php echo (isset($data['active']) && $data['active'] === 'resources') ? ' class="active"' : ''; ?>>
                                    <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=resources">
                                        <span>Resources</span>
                                    </a>
                                    </li>
                    </ul>
                    </li>
    </ul>

    <!-- Bottom Menu -->
    <ul class="side-menu bottom">
        <li>
            <a href="<?php echo URL_ROOT; ?>/users/logout" class="logout">
                <i class="bx bxs-log-out-circle"></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
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
        transition: .3s ease;
        overflow-x: hidden;
        overflow-y: auto;
        scrollbar-width: none;
    }

    #sidebar.hide {
        width: 60px;
    }

    #sidebar::-webkit-scrollbar {
        display: none;
    }

    #sidebar .brand {
        font-size: 24px;
        font-weight: 700;
        height: 56px;
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
        margin-bottom: 16px;
    }

    #sidebar .brand i {
        min-width: 60px;
        display: flex;
        justify-content: center;
    }

    #sidebar .side-menu {
        width: 100%;
        margin-top: 24px;
        padding: 0;
        list-style: none;
    }

    #sidebar .side-menu.top {
        margin-bottom: auto;
    }

    #sidebar .side-menu li {
        background: transparent;
        margin-left: 6px;
        border-radius: 48px 0 0 48px;
        padding: 4px;
        position: relative;
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
        background: var(--grey);
        position: relative;
    }

    #sidebar .side-menu>li.active::before {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        top: -40px;
        right: 0;
        box-shadow: 20px 20px 0 var(--grey);
        z-index: -1;
    }

    #sidebar .side-menu>li.active::after {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        bottom: -40px;
        right: 0;
        box-shadow: 20px -20px 0 var(--grey);
        z-index: -1;
    }

    #sidebar .side-menu li a {
        width: 100%;
        height: 100%;
        background: var(--light);
        display: flex;
        align-items: center;
        border-radius: 48px;
        font-size: 16px;
        color: var(--dark);
        white-space: nowrap;
        overflow-x: hidden;
        text-decoration: none;
        transition: all .3s ease;
    }

    #sidebar .side-menu li.active>a {
        color: var(--primary);
    }

    #sidebar .side-menu li a:hover {
        color: var(--primary);
    }

    #sidebar .side-menu li a i {
        min-width: 60px;
        font-size: 24px;
        display: flex;
        justify-content: center;
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
    }

    /* Submenu styles */
    #sidebar .submenu {
        display: none;
        list-style: none;
        padding-left: 20px;
        margin-top: 5px;
        margin-bottom: 10px;
        padding-right: 10px;
    }

    #sidebar .submenu.show {
        display: block;
    }

    #sidebar .submenu li {
        height: 40px;
        margin-bottom: 5px;
        border-radius: 8px;
    }

    #sidebar .submenu li a {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 8px;
        font-size: 14px;
    }

    #sidebar .submenu li.active a {
        background: rgba(var(--primary-rgb), 0.1);
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
    }

    #sidebar .has-submenu.active .submenu-icon {
        transform: rotate(90deg);
    }

    #sidebar .side-menu li a.logout {
        color: var(--red);
    }

    /* Badges */
    #sidebar .badge {
        font-size: 11px;
        padding: 3px 6px;
        border-radius: 10px;
        background-color: var(--primary);
        color: white;
    }

    #sidebar .badge.bg-primary {
        background-color: var(--primary);
    }

    /* Sidebar Hide/Show */
    @media screen and (max-width: 768px) {
        #sidebar {
            width: 60px;
        }

        #sidebar .brand span.text {
            display: none;
        }

        #sidebar .side-menu li a .text {
            display: none;
        }

        #sidebar .side-menu li a .submenu-icon {
            display: none;
        }

        #sidebar .submenu {
            position: absolute;
            left: 60px;
            top: 0;
            width: 200px;
            background: var(--light);
            border-radius: 0 6px 6px 0;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 100;
        }

        #sidebar .has-submenu:hover .submenu {
            display: block;
        }

        .dashboard-content {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
    }

    /* On load sidebar should be visible */
    @media screen and (min-width: 769px) {
        .dashboard-content {
            margin-left: 280px;
            width: calc(100% - 280px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create sidebar toggle button for mobile
        const toggle = document.createElement('div');
        toggle.id = 'sidebar-toggle';
        toggle.innerHTML = '<i class="bx bx-menu"></i>';
        toggle.style.cssText = `
        position: fixed;
        top: 16px;
        left: 16px;
        width: 28px;
        height: 28px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        cursor: pointer;
        z-index: 1001;
        display: none;
    `;

        document.body.appendChild(toggle);

        // Show toggle on mobile
        if (window.innerWidth <= 768) {
            toggle.style.display = 'flex';
        }

        // Toggle sidebar on click
        toggle.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hide');
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                toggle.style.display = 'flex';
            } else {
                toggle.style.display = 'none';
                document.getElementById('sidebar').classList.remove('hide');
            }
        });

        // Handle submenu toggle
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const submenu = parent.querySelector('.submenu');
                parent.classList.toggle('active');

                if (submenu.classList.contains('show')) {
                    submenu.classList.remove('show');
                } else {
                    // Close all other open submenus
                    document.querySelectorAll('.submenu.show').forEach(menu => {
                        if (menu !== submenu) {
                            menu.classList.remove('show');
                            menu.parentElement.classList.remove('active');
                        }
                    });
                    submenu.classList.add('show');
                }

                // If this has an href different from #, navigate to it
                if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                    window.location.href = this.getAttribute('href');
                }
            });
        });

        // Initial expansion of active menus
        document.querySelectorAll('.side-menu > li.active.has-submenu').forEach(item => {
            const submenu = item.querySelector('.submenu');
            if (submenu) {
                submenu.classList.add('show');
            }
        });
    });
</script>