<?php
/**
 * Main navigation bar component
 * Redesigned to match exact Upwork style from reference
 */
?>

<!-- Navbar Styles -->
<style>
    /* Base styles and variables */
    :root {
        --navbar-height: 56px;
        --navbar-bg: #ffffff;
        --navbar-text: #001e00;
        --navbar-border: #e4ebe4;
        --navbar-hover: #108a00;
        --navbar-active: #108a00;
        --button-green: #108a00;
        --button-green-hover: #14a800;
    }

    body {
        padding-top: var(--navbar-height);
        margin: 0;
        font-family: 'Neue Montreal', Arial, sans-serif;
    }

    /* Navbar container */
    .navbar {
        height: var(--navbar-height);
        background-color: var(--navbar-bg);
        border-bottom: 1px solid var(--navbar-border);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1030;
        display: flex;
        align-items: center;
    }

    .navbar-container {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 0 24px;
    }

    /* Left side - Brand and navigation */
    .navbar-brand {
        font-size: 22px;
        font-weight: 700;
        color: var(--navbar-text);
        text-decoration: none;
        margin-right: 24px;
    }

    /* Navigation items */
    .navbar-nav {
        display: flex;
        flex-direction: row;
        list-style: none;
        padding: 0;
        margin: 0;
        align-items: center;
    }

    .nav-item {
        position: relative;
        margin-right: 12px;
        display: inline-flex;
    }

    .nav-link {
        font-weight: 500;
        font-size: 14px;
        color: var(--navbar-text);
        text-decoration: none;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        border-radius: 4px;
        transition: background-color 0.15s ease;
    }

    .nav-link:hover {
        background-color: rgba(0, 30, 0, 0.05);
    }

    .nav-link.dropdown-toggle::after {
        content: '';
        width: 8px;
        height: 8px;
        margin-left: 8px;
        border: none;
        border-right: 1.5px solid currentColor;
        border-bottom: 1.5px solid currentColor;
        transform: rotate(45deg);
        display: inline-block;
    }
    
    /* Nav dropdown specific styling to match Upwork image */
    .navbar-nav .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        min-width: 240px;
        padding: 0;
        margin-top: 0;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }
    
    /* Nav dropdown arrow indicator that matches Upwork dropdown */
    .navbar-nav .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid white;
        z-index: 2;
    }
    
    /* Divider line in dropdown */
    .navbar-nav .dropdown-menu .dropdown-divider {
        height: 1px;
        background-color: #e0e0e0;
        margin: 0;
    }
    
    /* Dropdown item styling specifically for nav to match profile dropdown */
    .navbar-nav .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f2f2f2;
    }
    
    .navbar-nav .dropdown-item i {
        margin-right: 8px;
        width: 16px;
        text-align: center;
        color: #5e6d55;
    }

    .navbar-nav .dropdown-item:last-child {
        border-bottom: none;
    }

    /* Center - Search */
    .search-container {
        flex: 1;
        max-width: 720px;
        margin: 0 16px;
    }

    .search-form {
        position: relative;
        width: 100%;
    }

    .search-input {
        width: 100%;
        height: 36px;
        padding: 8px 16px 8px 40px;
        border: 1px solid var(--navbar-border);
        border-radius: 20px;
        font-size: 14px;
        background-color: #f7f9f7;
        transition: all 0.15s ease;
    }

    .search-input:focus {
        outline: none;
        background-color: #ffffff;
        border-color: var(--navbar-active);
        box-shadow: 0 0 0 2px rgba(16, 138, 0, 0.2);
    }

    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #5e6d55;
    }

    /* Right side - Actions area */
    .navbar-actions {
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .action-item {
        margin-left: 8px;
        position: relative;
    }

    .action-link {
        color: var(--navbar-text);
        padding: 6px 12px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease;
    }

    .action-link:hover {
        background-color: rgba(0, 30, 0, 0.05);
    }

    .action-link i {
        font-size: 16px;
    }

    .action-link .caret {
        margin-left: 4px;
        font-size: 10px;
    }

    /* Notification icons */
    .notification-badge {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #e54d42;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* User avatar */
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #bde5aa;
        color: var(--button-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        border: 1px solid rgba(16, 138, 0, 0.2);
        cursor: pointer;
    }

    /* Dropdown menus */
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        background-color: #fff;
        min-width: 240px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 1000;
        padding: 8px 0;
        border: 1px solid var(--navbar-border);
        left: 50%;
        transform: translateX(-50%);
    }
    
    /* For the search dropdown specifically - align to trigger rather than center */
    #search-categories-dropdown {
        right: 10px;
        left: auto;
        transform: none;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 8px 16px;
        color: var(--navbar-text);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.15s ease;
    }
    
    .dropdown-item i {
        margin-right: 8px;
        width: 16px;
        text-align: center;
        color: #5e6d55;
    }
    
    .dropdown-item:hover {
        background-color: #f9f9f9;
    }

    .dropdown-header {
        padding: 8px 16px;
        font-size: 12px;
        color: #5e6d55;
        font-weight: 600;
    }

    .dropdown-divider {
        height: 1px;
        background-color: var(--navbar-border);
        margin: 8px 0;
    }

    /* Account indicators */
    .freelancer-dot {
        width: 8px;
        height: 8px;
        background-color: #14a800;
        border-radius: 50%;
        margin-right: 8px;
    }

    .client-dot {
        width: 8px;
        height: 8px;
        background-color: #0073de;
        border-radius: 50%;
        margin-right: 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .navbar-nav {
            display: none;
        }
        
        .search-container {
            max-width: none;
        }
    }

    /* Mobile menu (initially hidden) */
    #mobile-menu-toggle {
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 8px;
        margin-right: 8px;
        display: none;
    }

    #mobile-menu-toggle span {
        display: block;
        width: 22px;
        height: 2px;
        background-color: var(--navbar-text);
        margin: 4px 0;
    }

    .mobile-nav {
        display: none;
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid var(--navbar-border);
        border-bottom: 1px solid var(--navbar-border);
        z-index: 1000;
        padding: 16px;
    }

    @media (max-width: 768px) {
        #mobile-menu-toggle {
            display: block;
        }
        
        .navbar-brand {
            margin-right: 8px;
        }
        
        .mobile-nav.show {
            display: block;
        }
    }
</style>

<!-- Styles specifically for non-logged-in navbar -->
<style>
    /* These styles only apply to the non-logged-in state */
    .guest-navbar-styles {
        font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    
    .guest-navbar-styles .navbar {
        height: 70px;
        background-color: rgba(255, 255, 255, 0.98);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(229, 231, 235, 0.7);
    }
    
    .guest-navbar-styles .navbar.scrolled {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.95);
    }
    
    .guest-navbar-styles .navbar-brand {
        font-family: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.75rem;
        letter-spacing: -0.5px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .guest-navbar-styles .navbar-brand:hover {
        transform: translateY(-2px);
    }
    
    .guest-navbar-styles .navbar-nav {
        margin-left: 2rem;
    }
    
    .guest-navbar-styles .nav-item {
        margin: 0 0.5rem;
    }
    
    .guest-navbar-styles .nav-link {
        padding: 0.5rem 1rem;
        color: #222325;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        font-size: 15px;
    }
    
    .guest-navbar-styles .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #2c3e50;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2px;
    }
    
    .guest-navbar-styles .nav-link:hover {
        color: #2c3e50;
        background-color: transparent;
    }
    
    .guest-navbar-styles .nav-link:hover::after, 
    .guest-navbar-styles .nav-link.active::after {
        width: 100%;
    }
    
    .guest-navbar-styles .navbar-actions {
        display: flex;
        align-items: center;
    }
    
    .guest-navbar-styles .action-link {
        color: #222325;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 15px;
    }
    
    .guest-navbar-styles .action-link:hover {
        color: #2c3e50;
        background-color: rgba(10, 17, 40, 0.05);
        border-radius: 6px;
    }
    
    .guest-navbar-styles .action-link.sign-up-btn {
        background: linear-gradient(to right, #2c3e50, #34495e);
        color: white;
        border-radius: 8px;
        padding: 12px 28px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .guest-navbar-styles .action-link.sign-up-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .guest-navbar-styles .action-link.sign-up-btn::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: -100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: 0.6s;
    }
    
    .guest-navbar-styles .action-link.sign-up-btn:hover::after {
        left: 100%;
    }
</style>

<nav class="navbar">
    <div class="navbar-container">
        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Navigation for logged-in users -->
            <a class="navbar-brand" href="<?php echo URL_ROOT; ?>">
                <?php echo SITE_NAME; ?>
            </a>
            
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-dropdown="findwork">Find work</a>
                    <div class="dropdown-menu vertical-aligned" id="findwork-dropdown">
                        <a href="<?php echo URL_ROOT; ?>/jobs" class="dropdown-item">
                            <i class="fas fa-search"></i> Find Jobs
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/jobs/saved" class="dropdown-item">
                            <i class="fas fa-bookmark"></i> Saved Jobs
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/proposals" class="dropdown-item">
                            <i class="fas fa-paper-plane"></i> Proposals
                        </a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-dropdown="deliverwork">Deliver work</a>
                    <div class="dropdown-menu vertical-aligned" id="deliverwork-dropdown">
                        <a href="<?php echo URL_ROOT; ?>/services/manage" class="dropdown-item">
                            <i class="fas fa-clipboard-list"></i> My Services
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/orders/active" class="dropdown-item">
                            <i class="fas fa-tasks"></i> Active Orders
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/services/create" class="dropdown-item">
                            <i class="fas fa-plus-circle"></i> Create Service
                        </a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-dropdown="finances">Manage finances</a>
                    <div class="dropdown-menu vertical-aligned" id="finances-dropdown">
                        <a href="<?php echo URL_ROOT; ?>/wallet" class="dropdown-item">
                            <i class="fas fa-wallet"></i> My Wallet
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/earnings" class="dropdown-item">
                            <i class="fas fa-chart-line"></i> Earnings
                        </a>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="<?php echo URL_ROOT; ?>/messages" class="nav-link">Messages</a>
                </li>
            </ul>
            
            <div class="navbar-actions">
                <div class="search-container">
                    <form class="search-form" action="<?php echo URL_ROOT; ?>/search" method="GET">
                        <div style="position: relative; display: flex; align-items: center; width: 100%; border: 1px solid #e4ebe4; border-radius: 24px; background-color: #fff; overflow: hidden;">
                            <div style="padding: 0 16px;">
                                <i class="fas fa-search search-icon" style="position: static; transform: none;"></i>
                            </div>
                            <input type="text" class="search-input" name="q" placeholder="Search" style="border: none; background: transparent; box-shadow: none; flex-grow: 1; padding: 10px 0;">
                            <div style="border-left: 1px solid #e4ebe4; padding: 8px 16px; position: relative;">
                                <a href="#" style="display: flex; align-items: center; text-decoration: none; color: #001e00; font-weight: 500;" data-dropdown="search-categories">
                                    Jobs <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>
                                </a>
                                <div class="dropdown-menu" id="search-categories-dropdown" style="margin-top: 8px; position: absolute; top: 100%; right: 0; width: 180px;">
                                    <a href="<?php echo URL_ROOT; ?>/jobs/browse" class="dropdown-item">
                                        <i class="fas fa-search"></i> Browse Jobs
                                    </a>
                                    <a href="<?php echo URL_ROOT; ?>/jobs/post" class="dropdown-item">
                                        <i class="fas fa-plus"></i> Post a Job
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="action-item dropdown">
                    <a href="#" class="action-link" data-dropdown="help">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <div class="dropdown-menu" id="help-dropdown">
                        <a href="<?php echo URL_ROOT; ?>/help" class="dropdown-item">
                            <i class="fas fa-question-circle"></i> Help Center
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/support" class="dropdown-item">
                            <i class="fas fa-headset"></i> Support
                        </a>
                    </div>
                </div>
                
                <div class="action-item dropdown">
                    <a href="#" class="action-link" data-dropdown="notifications">
                        <i class="fas fa-bell"></i>
                        <?php if(isset($notificationCount) && $notificationCount > 0): ?>
                            <span class="notification-badge"><?php echo $notificationCount > 9 ? '9+' : $notificationCount; ?></span>
                        <?php else: ?>
                            <span class="notification-badge">3</span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu" id="notifications-dropdown">
                        <div class="dropdown-header">Notifications</div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-comment"></i> New message from David
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-check-circle"></i> Your proposal was accepted
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo URL_ROOT; ?>/notifications" class="dropdown-item">
                            <i class="fas fa-bell"></i> See all notifications
                        </a>
                    </div>
                </div>
                
                <div class="action-item dropdown">
                    <a href="#" class="action-link" data-dropdown="messages">
                        <i class="fas fa-envelope"></i>
                        <?php if(isset($messageCount) && $messageCount > 0): ?>
                            <span class="notification-badge"><?php echo $messageCount > 9 ? '9+' : $messageCount; ?></span>
                        <?php else: ?>
                            <span class="notification-badge">2</span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu" id="messages-dropdown">
                        <div class="dropdown-header">Messages</div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i> John: Can you help with...
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i> Amy: Thank you for the...
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo URL_ROOT; ?>/messages" class="dropdown-item">
                            <i class="fas fa-envelope"></i> See all messages
                        </a>
                    </div>
                </div>
                
                <div class="action-item dropdown">
                    <div class="user-avatar" data-dropdown="profile">
                        <?php 
                            $nameArray = explode(' ', $_SESSION['user_name']);
                            $initials = '';
                            if(isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                            if(isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                            echo strtoupper($initials);
                        ?>
                    </div>
                    <div class="dropdown-menu" id="profile-dropdown">
                        <div class="dropdown-header">
                            <?php echo $_SESSION['user_name']; ?>
                            <?php if(isset($_SESSION['user_account_type'])): ?>
                                <div style="display: flex; align-items: center; margin-top: 4px;">
                                    <span class="<?php echo $_SESSION['user_account_type'] == 'freelancer' ? 'freelancer-dot' : 'client-dot'; ?>"></span>
                                    <span style="font-size: 12px; font-weight: normal;"><?php echo ucfirst($_SESSION['user_account_type']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo URL_ROOT; ?>/dashboard" class="dropdown-item">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>?page=profile" class="dropdown-item">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>?page=settings" class="dropdown-item">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo URL_ROOT; ?>/users/logout" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Navigation for guests -->
            <button id="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <a class="navbar-brand" href="<?php echo URL_ROOT; ?>" style="padding-left: 40px; font-size: 24px; font-weight: 700; color: #000; text-decoration: none;">
                LenSi
            </a>
            
            <div class="navbar-actions" style="display: flex; align-items: center; margin-left: auto; padding-right: 40px;">
                <div class="action-item">
                    <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="action-link" style="color: #001e00; font-weight: 500; text-decoration: none; font-size: 14px; transition: color 0.2s;">Log In</a>
                </div>
                <div class="action-item" style="margin-left: 24px;">
                    <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="action-link sign-up-btn">Sign Up</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div class="mobile-nav" id="mobile-nav">
    <form class="search-form" style="margin-bottom: 16px" action="<?php echo URL_ROOT; ?>/search" method="GET">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" name="q" placeholder="Search...">
    </form>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="#" class="mobile-dropdown-toggle">Find work</a>
        <div class="mobile-dropdown-content" style="padding-left: 16px; margin: 8px 0;">
            <p><a href="<?php echo URL_ROOT; ?>/jobs">Find Jobs</a></p>
            <p><a href="<?php echo URL_ROOT; ?>/jobs/saved">Saved Jobs</a></p>
        </div>
        
        <a href="#" class="mobile-dropdown-toggle">Deliver work</a>
        <div class="mobile-dropdown-content" style="padding-left: 16px; margin: 8px 0;">
            <p><a href="<?php echo URL_ROOT; ?>/services/manage">My Services</a></p>
            <p><a href="<?php echo URL_ROOT; ?>/orders/active">Active Orders</a></p>
        </div>
        
        <a href="<?php echo URL_ROOT; ?>/messages">Messages</a>
    <?php else: ?>
        <p><a href="<?php echo URL_ROOT; ?>/services">Find Talent</a></p>
        <p><a href="<?php echo URL_ROOT; ?>/jobs">Find Work</a></p>
        <p><a href="<?php echo URL_ROOT; ?>/why">Why Us</a></p>
        <div style="margin-top: 16px;">
            <p><a href="<?php echo URL_ROOT; ?>/users/login">Log In</a></p>
            <p><a href="<?php echo URL_ROOT; ?>/users/register" style="display: inline-block; background-color: var(--button-green); color: white; border-radius: 20px; padding: 8px 16px; text-decoration: none;">Sign Up</a></p>
        </div>
    <?php endif; ?>
</div>

<!-- Space for flash messages -->
<div style="margin-top: var(--navbar-height);">
    <?php flash('message'); ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdowns with hover instead of click
        const dropdownContainers = document.querySelectorAll('.dropdown');
        
        dropdownContainers.forEach(container => {
            // Show dropdown menu on hover
            container.addEventListener('mouseenter', function() {
                const trigger = container.querySelector('[data-dropdown], .user-avatar[data-dropdown]');
                if (trigger) {
                    const dropdownId = trigger.getAttribute('data-dropdown');
                    const dropdownMenu = document.getElementById(`${dropdownId}-dropdown`);
                    
                    if (dropdownMenu) {
                        // Hide all other dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            if (menu !== dropdownMenu) {
                                menu.style.display = 'none';
                            }
                        });
                        
                        // Show this dropdown
                        dropdownMenu.style.display = 'block';
                    }
                }
            });
            
            // Add a small delay before hiding the dropdown to give users time to move mouse to the dropdown content
            let timeout;
            
            container.addEventListener('mouseleave', function() {
                const trigger = container.querySelector('[data-dropdown], .user-avatar[data-dropdown]');
                if (trigger) {
                    const dropdownId = trigger.getAttribute('data-dropdown');
                    const dropdownMenu = document.getElementById(`${dropdownId}-dropdown`);
                    
                    if (dropdownMenu) {
                        // Set a timeout before hiding the dropdown
                        timeout = setTimeout(() => {
                            dropdownMenu.style.display = 'none';
                        }, 200); // 200ms delay gives enough time to move to the dropdown
                    }
                }
            });
            
            // Cancel the hiding if the user moves back to the dropdown
            container.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
            });
        });
        
        // Also make the dropdown menus themselves interactive
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
                this.style.display = 'block';
            });
            
            menu.addEventListener('mouseleave', function() {
                const dropdownId = this.id.replace('-dropdown', '');
                const container = document.querySelector(`[data-dropdown="${dropdownId}"]`).closest('.dropdown');
                
                if (!container.matches(':hover')) {
                    this.style.display = 'none';
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        
        if (mobileMenuToggle && mobileNav) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileNav.classList.toggle('show');
            });
        }
        
        // Mobile dropdowns
        const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
        
        mobileDropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const content = this.nextElementSibling;
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                } else {
                    content.style.display = 'block';
                }
            });
        });
        
        // Handle escape key to close dropdowns
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
                
                if (mobileNav && mobileNav.classList.contains('show')) {
                    mobileNav.classList.remove('show');
                }
            }
        });
        
        // Auto-dismiss flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.alert');
            if (flashMessages.length > 0) {
                flashMessages.forEach(message => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => message.remove(), 500);
                });
            }
        }, 5000);

        // Add scroll event to change navbar appearance when scrolling down
        if (!document.body.classList.contains('guest-navbar-styles')) {
            document.body.classList.add('guest-navbar-styles');
        }
        
        const navbar = document.querySelector('.navbar');
        
        function checkScroll() {
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        
        // Initial check in case page is loaded scrolled down
        checkScroll();
        
        // Listen for scroll events
        window.addEventListener('scroll', checkScroll);
    });
</script>