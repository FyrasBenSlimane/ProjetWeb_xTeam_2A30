<?php

/**
 * Main navigation bar component
 * This file contains the main navigation elements including dropdowns
 */
?>

<!-- Navbar Styles -->
<style>
    /* Modern Navbar Styles */
    :root {
        --primary-color: #0a1128;
        --primary-dark: #050914;
        --text-color: #222325;
        --bg-color: #ffffff;
        --hover-bg: rgba(10, 17, 40, 0.05);
        --border-color: #e9e9e9;
        --radius-sm: 6px;
        --radius-md: 12px;
        --radius-lg: 50px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition-fast: 0.2s ease;
        --transition-normal: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --spacing-xs: 0.5rem;
        --spacing-sm: 0.75rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
    }

    .navbar {
        padding: var(--spacing-sm) 5%;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        background-color: var(--bg-color);
        box-shadow: var(--shadow-sm);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1030;
        transition: all var(--transition-normal);
        height: 70px;
    }

    .navbar.scrolled {
        box-shadow: var(--shadow-md);
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        backdrop-filter: blur(8px);
        background-color: rgba(255, 255, 255, 0.95);
    }

    .navbar-brand {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        font-weight: 700;
        color: var(--primary-color) !important;
        font-size: 1.75rem;
        padding: var(--spacing-xs) 0;
        margin-right: var(--spacing-xl);
        text-decoration: none;
        white-space: nowrap;
        letter-spacing: -0.5px;
        transition: transform var(--transition-normal);
    }

    .navbar-brand:hover {
        transform: translateY(-2px);
    }

    .navbar-nav {
        display: flex;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        margin-left: var(--spacing-lg);
    }

    .navbar-nav .nav-item {
        position: relative;
        margin: 0 var(--spacing-xs);
    }

    .navbar-nav .nav-link {
        padding: var(--spacing-xs) var(--spacing-md);
        color: var(--text-color);
        font-weight: 600;
        transition: all var(--transition-normal);
        position: relative;
        display: inline-flex;
        align-items: center;
        border-radius: var(--radius-sm);
    }

    /* Improved dropdown indicator arrow */
    .nav-link.dropdown-toggle::after {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        margin-left: 8px;
        border-right: 2px solid var(--text-color);
        border-bottom: 2px solid var(--text-color);
        transform: rotate(45deg);
        transition: transform var(--transition-fast), margin-top var(--transition-fast);
        position: relative;
        top: -2px;
    }

    .nav-link.dropdown-toggle:hover::after {
        transform: rotate(225deg);
        margin-top: 3px;
        border-color: var(--primary-color);
    }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--primary-color);
        transition: width var(--transition-normal);
        border-radius: 2px;
    }

    .navbar-nav .nav-link:hover::after,
    .navbar-nav .nav-link.active::after {
        width: 100%;
    }

    .navbar-nav .nav-link:hover {
        color: var(--primary-color);
        background-color: var(--hover-bg);
    }

    .navbar-toggler {
        padding: 0.25rem 0.75rem;
        font-size: 1.25rem;
        line-height: 1;
        background-color: transparent;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        transition: all var(--transition-normal);
    }

    .navbar-toggler:hover {
        background-color: var(--hover-bg);
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    .navbar-toggler:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(20, 168, 0, 0.1);
    }

    /* Enhanced dropdown styling */
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        min-width: 14rem;
        padding: var(--spacing-sm) 0;
        margin: 0.125rem 0 0;
        font-size: 0.875rem;
        color: #404040;
        text-align: left;
        list-style: none;
        background-color: rgba(255, 255, 255, 0.98);
        background-clip: padding-box;
        border: none;
        border-radius: var(--radius-md);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15),
            0 10px 20px -10px rgba(0, 0, 0, 0.1);
        transform: translateY(10px);
        opacity: 0;
        visibility: hidden;
        transition: transform var(--transition-fast),
            opacity var(--transition-fast),
            visibility var(--transition-fast);
        pointer-events: none;
        backdrop-filter: blur(5px);
    }

    /* Ensure proper dropdown position for explore menu */
    .explore-dropdown .dropdown-menu {
        min-width: 280px;
        padding: var(--spacing-md);
    }

    /* Module categories in explore dropdown */
    .module-category {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
        padding: 0.25rem 1rem;
    }

    /* Module buttons in explore dropdown */
    .module-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: var(--spacing-md);
    }

    .module-button {
        padding: 6px 14px;
        font-size: 0.85rem;
        background: #f5f5f5;
        border-radius: var(--radius-sm);
        color: var(--text-color);
        text-decoration: none;
        transition: all var(--transition-fast);
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .module-button:hover {
        background: var(--gradient-primary);
        color: white;
        transform: translateY(-2px);
    }

    .module-button i {
        margin-right: 6px;
        font-size: 0.9rem;
    }

    .dropdown-menu.show {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        animation: dropdownFadeIn 0.3s ease-out forwards;
    }

    /* Improved dropdown animation */
    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        70% {
            opacity: 1;
            transform: translateY(-3px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.65rem 1.25rem;
        clear: both;
        font-weight: 600;
        color: var(--text-color);
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        transition: all var(--transition-fast);
        position: relative;
        overflow: hidden;
    }

    /* Improved hover indicator */
    .dropdown-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background-color: var(--primary-color);
        border-radius: 3px;
        transition: height var(--transition-fast);
    }

    .dropdown-item:hover::before {
        height: 70%;
    }

    .dropdown-item i {
        margin-right: 0.6rem;
        font-size: 0.95rem;
        color: var (--text-color);
        transition: all var(--transition-fast);
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        color: var(--primary-color);
        text-decoration: none;
        background-color: rgba(248, 248, 248, 0.7);
        transform: translateX(5px);
    }

    .dropdown-item:hover i,
    .dropdown-item:focus i {
        color: var(--primary-color);
        transform: scale(1.1);
    }

    .dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        overflow: hidden;
        border-top: 1px solid var(--border-color);
    }

    /* Enhanced Category Sub-Navigation */
    .category-subnav {
        background-color: var(--bg-color);
        border-bottom: 1px solid var(--border-color);
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        z-index: 1020;
        transform: translateY(-100%);
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-md);
        opacity: 0;
        visibility: hidden;
    }

    .category-subnav.visible {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    .category-subnav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: thin;
        scrollbar-color: var (--primary-color) transparent;
        -ms-overflow-style: none;
    }

    .category-subnav-list::-webkit-scrollbar {
        height: 4px;
    }

    .category-subnav-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .category-subnav-list::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 4px;
    }

    .category-subnav-list li a {
        padding: 14px 20px;
        display: inline-flex;
        align-items: center;
        color: #404040;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all var(--transition-normal);
        position: relative;
    }

    .category-subnav-list li a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background-color: var(--primary-color);
        transition: width var(--transition-normal);
        border-radius: 2px;
    }

    .category-subnav-list li a:hover {
        color: var(--primary-color);
    }

    .category-subnav-list li a:hover::after {
        width: 70%;
    }

    .category-subnav-list li a i {
        margin-right: 8px;
        font-size: 1rem;
        color: #525252;
        transition: all var(--transition-normal);
    }

    .category-subnav-list li a:hover i {
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    /* Improved Search form */
    .search-form-container {
        position: relative;
        width: 100%;
        max-width: 250px;
        transition: all var(--transition-normal);
    }

    .search-form {
        display: flex;
        position: relative;
        width: 100%;
    }

    .search-form input {
        flex: 1;
        padding: 10px 16px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        background-color: #fafafa;
        transition: all var(--transition-normal);
        color: #262626;
    }

    .search-form input:focus {
        outline: none;
        background-color: var(--bg-color);
        box-shadow: 0 0 0 4px rgba(20, 168, 0, 0.05);
        border-color: var(--primary-color);
        width: 110%;
    }

    .search-form button {
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 5px;
        border: none;
        background: var(--primary-color);
        color: white;
        border-radius: var(--radius-lg);
        padding: 0 15px;
        cursor: pointer;
        font-weight: 500;
        transition: all var(--transition-normal);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .search-form button:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .search-form button i {
        margin-right: 6px;
    }

    /* Enhanced user avatar */
    .avatar-xs {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--bg-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all var(--transition-normal);
    }

    .nav-link:hover .avatar-xs {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Enhanced buttons */
    .nav-link.highlight {
        color: var(--primary-color) !important;
        font-weight: 700;
        position: relative;
    }

    .nav-link.highlight::after {
        background-color: var(--primary-color);
    }

    /* Enhanced notification badge */
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        font-weight: 700;
        transform: scale(0);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(26, 35, 126, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(26, 35, 126, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(26, 35, 126, 0);
        }
    }

    /* User role styling */
    .user-role-tag {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 12px;
        margin-left: 6px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        vertical-align: middle;
    }

    .freelancer-tag {
        background-color: rgba(26, 35, 126, 0.15);
        color: var(--primary-color);
    }

    .client-tag {
        background-color: rgba(26, 35, 126, 0.15);
        color: var(--primary-color);
    }

    .dropdown-header {
        padding: 12px 16px;
        background-color: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-color);
    }

    .user-role {
        font-size: 12px;
        font-weight: 500;
    }

    .freelancer-role {
        color: var(--primary-color);
    }

    .client-role {
        color: var(--primary-color);
    }

    /* Mobile adjustments */
    @media (max-width: 991.98px) {
        .navbar {
            padding-left: 20px;
            padding-right: 20px;
        }

        .navbar-expand-lg .navbar-nav {
            flex-direction: column;
        }

        .navbar-collapse {
            display: none;
            background-color: var(--bg-color);
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            padding: 1rem;
            box-shadow: var(--shadow-md);
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            z-index: 1000;
            max-height: 80vh;
            overflow-y: auto;
        }

        .navbar-collapse.show {
            display: block;
            animation: fadeInDown 0.3s cubic-bezier(0, 0, 0.2, 1);
        }

        .navbar-expand-lg .navbar-toggler {
            display: block;
        }

        .dropdown-menu {
            position: static;
            box-shadow: none;
            padding-left: 1rem;
            animation: none;
            opacity: 1;
            transform: none;
            pointer-events: auto;
            background-color: #f8f8f8;
            min-width: 100%;
        }

        .module-buttons {
            flex-direction: column;
            gap: 10px;
        }

        .module-button {
            width: 100%;
            text-align: left;
            justify-content: flex-start;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            transform: none;
        }

        .search-form-container {
            max-width: 100%;
            margin: 10px 0;
        }

        /* Improved mobile nav organization */
        .navbar-nav.user-nav {
            flex-direction: row;
            justify-content: center;
            gap: var(--spacing-md);
            padding: var(--spacing-md) 0;
            border-top: 1px solid var(--border-color);
            margin-top: var(--spacing-md);
        }

        .nav-divider {
            display: none;
        }
    }

    @media (min-width: 992px) {
        .navbar-expand-lg {
            flex-flow: row nowrap;
            justify-content: flex-start;
        }

        .navbar-expand-lg .navbar-nav {
            flex-direction: row;
        }

        .navbar-expand-lg .navbar-toggler {
            display: none;
        }

        .navbar-expand-lg .navbar-collapse {
            display: flex !important;
            flex-basis: auto;
        }

        .dropdown-menu {
            transform-origin: top;
        }
    }

    /* Animation for dropdown menus */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Theme toggle - aligned to main green */
    .form-check-input:checked {
        background-color: var(--primary-color);
        background-position: right center;
    }

    /* Added spacing between navbar items for better organization */
    .navbar-nav .nav-item {
        position: relative;
        margin: 0 var(--spacing-xs);
    }

    /* Added styles for nav section dividers */
    .nav-divider {
        width: 1px;
        height: 24px;
        background-color: var(--border-color);
        margin: 0 var(--spacing-md);
        opacity: 0.5;
    }

    /* Enhanced notification badges */
    .notification-count {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #ff5722;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        transform: translate(30%, -30%);
        font-weight: 600;
    }

    /* Improved user section */
    .user-section {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    /* Icon-based nav links for logged-in users */
    .icon-nav-link {
        padding: var(--spacing-xs) var(--spacing-xs);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        transition: all var(--transition-normal);
    }

    .icon-nav-link:hover {
        background-color: var(--hover-bg);
        transform: translateY(-2px);
    }

    .icon-nav-link i {
        font-size: 1.2rem;
        color: var(--text-color);
    }
</style>

<?php
// Get the base URL
$URL_ROOT = URL_ROOT;

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userType = $isLoggedIn ? $_SESSION['user_account_type'] : '';
?>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Brand Name -->
        <a class="navbar-brand" href="<?php echo $URL_ROOT; ?>/">
            LenSI
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Nav Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Nav Links - Left Side -->
            <ul class="navbar-nav mr-auto">
                <!-- Explore Dropdown -->
                <li class="nav-item dropdown explore-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="exploreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Explore
                    </a>
                    <div class="dropdown-menu" aria-labelledby="exploreDropdown">
                        <!-- Projects Module -->
                        <span class="module-category">Projects</span>
                        <div class="module-buttons">
                            <a href="<?php echo $URL_ROOT; ?>/projects/browse" class="module-button">
                                <i class="fas fa-project-diagram"></i> Browse Projects
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/projects/new" class="module-button">
                                <i class="fas fa-plus"></i> Create Project
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/projects/featured" class="module-button">
                                <i class="fas fa-star"></i> Featured
                            </a>
                        </div>

                        <!-- Job Offers Module -->
                        <span class="module-category">Job Offers</span>
                        <div class="module-buttons">
                            <a href="<?php echo $URL_ROOT; ?>/jobs/browse" class="module-button">
                                <i class="fas fa-briefcase"></i> Browse Jobs
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/jobs/post" class="module-button">
                                <i class="fas fa-paper-plane"></i> Post a Job
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/jobs/categories" class="module-button">
                                <i class="fas fa-th-list"></i> Categories
                            </a>
                        </div>

                        <!-- Events Module -->
                        <span class="module-category">Events</span>
                        <div class="module-buttons">
                            <a href="<?php echo $URL_ROOT; ?>/events/upcoming" class="module-button">
                                <i class="fas fa-calendar-alt"></i> Upcoming
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/events/past" class="module-button">
                                <i class="fas fa-history"></i> Past Events
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/events/create" class="module-button">
                                <i class="fas fa-plus-circle"></i> Create Event
                            </a>
                        </div>

                        <!-- Support Module -->
                        <span class="module-category">Support</span>
                        <div class="module-buttons">
                            <a href="<?php echo $URL_ROOT; ?>/support" class="module-button">
                                <i class="fas fa-ticket-alt"></i> Support Tickets
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/support/faq" class="module-button">
                                <i class="fas fa-question-circle"></i> FAQs
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/support/contact" class="module-button">
                                <i class="fas fa-envelope"></i> Contact Us
                            </a>
                        </div>

                        <!-- Community Module -->
                        <span class="module-category">Community</span>
                        <div class="module-buttons">
                            <a href="<?php echo $URL_ROOT; ?>/community/forums" class="module-button">
                                <i class="fas fa-comments"></i> Forums
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/community/groups" class="module-button">
                                <i class="fas fa-users"></i> Groups
                            </a>
                            <a href="<?php echo $URL_ROOT; ?>/community/resources" class="module-button">
                                <i class="fas fa-book"></i> Resources
                            </a>
                        </div>
                    </div>
                </li>

                <?php if (!$isLoggedIn): ?>
                    <!-- Only show these links for non-logged in users -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL_ROOT; ?>/services/explore">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL_ROOT; ?>/pages/pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL_ROOT; ?>/pages/about">About</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Search Form - Only visible to logged in users -->
            <?php if ($isLoggedIn): ?>
                <div class="search-form-container mx-lg-auto">
                    <form class="search-form" action="<?php echo $URL_ROOT; ?>/search" method="GET">
                        <input type="text" name="search" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search me-1"></i></button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Nav Links - Right Side -->
            <ul class="navbar-nav ms-auto <?php echo $isLoggedIn ? 'user-nav' : ''; ?>">
                <?php if (!$isLoggedIn): ?>
                    <!-- Public Links for Guest Users -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $URL_ROOT; ?>/users/auth?action=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link highlight" href="<?php echo $URL_ROOT; ?>/users/auth?action=register">Join</a>
                    </li>

                <?php elseif ($userType == 'freelancer'): ?>
                    <!-- Organized Links for Freelancers -->
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/notifications">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">2</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/messages">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/user/apps">
                            <i class="fas fa-th"></i>
                        </a>
                    </li>
                    <div class="nav-divider d-none d-lg-block"></div>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="freelancerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Profile" class="avatar-xs me-2">
                            <span class="d-none d-lg-inline"><?php echo $_SESSION['user_name']; ?></span>
                            <span class="user-role-tag freelancer-tag d-none d-lg-inline">Freelancer</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="freelancerDropdown">
                            <li class="dropdown-header">
                                <div class="user-info">
                                    <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                                    <span class="user-role freelancer-role">Freelancer</span>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/user/profile">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/user/stats">
                                    <i class="fas fa-chart-line me-2"></i> Stats
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/user/connects">
                                    <i class="fas fa-plug me-2"></i> Connects
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/user/membership">
                                    <i class="fas fa-star me-2"></i> Membership
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/support">
                                    <i class="fas fa-ticket-alt me-2"></i> Support Tickets
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <div class="dropdown-item dropdown-theme-toggle">
                                    <div>
                                        <i class="fas fa-moon me-2"></i> Dark Mode
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="themeSwitch">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/users/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php else: ?>
                    <!-- Organized Links for Clients -->
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/notifications">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/messages">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link icon-nav-link" href="<?php echo $URL_ROOT; ?>/jobs/post">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </li>
                    <div class="nav-divider d-none d-lg-block"></div>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="clientDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Profile" class="avatar-xs me-2">
                            <span class="d-none d-lg-inline"><?php echo $_SESSION['user_name']; ?></span>
                            <span class="user-role-tag client-tag d-none d-lg-inline">Client</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="clientDropdown">
                            <li class="dropdown-header">
                                <div class="user-info">
                                    <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                                    <span class="user-role client-role">Client</span>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/dashboard/profile">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/jobs/manage">
                                    <i class="fas fa-briefcase me-2"></i> My Jobs
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/dashboard/billing">
                                    <i class="fas fa-credit-card me-2"></i> Billing
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <div class="dropdown-item dropdown-theme-toggle">
                                    <div>
                                        <i class="fas fa-moon me-2"></i> Dark Mode
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="themeSwitch">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo $URL_ROOT; ?>/users/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Sub-navbar for categories - will be shown when scrolled past categories section -->
<div id="category-subnav" class="category-subnav">
    <div class="container-fluid">
        <ul class="category-subnav-list">
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=programming"><i class="fas fa-code"></i> Programming & Tech</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=design"><i class="fas fa-palette"></i> Graphics & Design</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=digital-marketing"><i class="fas fa-bullhorn"></i> Digital Marketing</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=writing"><i class="fas fa-pen-fancy"></i> Writing & Translation</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=video"><i class="fas fa-film"></i> Video & Animation</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=music"><i class="fas fa-music"></i> Music & Audio</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=business"><i class="fas fa-chart-line"></i> Business</a></li>
            <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=ai-services"><i class="fas fa-robot"></i> AI Services</a></li>
        </ul>
    </div>
</div>

<!-- Mobile search form (visible only on small screens) -->
<div class="d-block d-lg-none container-fluid py-3">
    <form class="search-form" action="<?php echo $URL_ROOT; ?>/search" method="GET">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="flash-container container-fluid mt-3">
    <?php flash('message'); ?>
</div>

<!-- Navbar JavaScript with Improved Animations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navbar scroll behavior
        const navbar = document.querySelector('.navbar');
        const navbarHeight = navbar ? navbar.offsetHeight : 0;
        const categorySubnav = document.getElementById('category-subnav');
        let lastScrollTop = 0;

        function handleNavbarScroll() {
            const scrollTop = window.scrollY;

            // Add/remove scrolled class based on scroll position
            if (scrollTop > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Show/hide category subnav when scrolled past certain point
            if (window.location.pathname === '/' || window.location.pathname.includes('index.php')) {
                const categorySectionTop = document.getElementById('categories-section');
                if (categorySectionTop) {
                    const categorySectionPosition = categorySectionTop.getBoundingClientRect().top + window.scrollY;
                    const categorySectionHeight = categorySectionTop.offsetHeight;

                    if (scrollTop > categorySectionPosition + categorySectionHeight && scrollTop < categorySectionPosition + categorySectionHeight + 800) {
                        categorySubnav.classList.add('visible');
                    } else {
                        categorySubnav.classList.remove('visible');
                    }
                }
            }
        }

        // Initial check and event listener
        handleNavbarScroll();
        window.addEventListener('scroll', handleNavbarScroll);
        window.addEventListener('resize', handleNavbarScroll);

        // Enhanced Dropdown behavior for both desktop and mobile
        const dropdownItems = document.querySelectorAll('.dropdown, .mega-dropdown');

        dropdownItems.forEach(item => {
            const dropdownToggle = item.querySelector('.dropdown-toggle');
            const dropdownMenu = item.querySelector('.dropdown-menu, .mega-dropdown-menu');

            if (!dropdownToggle || !dropdownMenu) return;

            // Handle mouse interactions for desktop
            if (window.matchMedia('(min-width: 992px)').matches) {
                // Desktop: Use hover for open/close
                item.addEventListener('mouseenter', function() {
                    closeAllDropdowns();
                    dropdownMenu.classList.add('show');
                });

                item.addEventListener('mouseleave', function() {
                    setTimeout(() => {
                        // Small delay to make dropdown feel more natural
                        if (!item.matches(':hover')) {
                            dropdownMenu.classList.remove('show');
                        }
                    }, 200);
                });

                // Also support click for accessibility
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    } else {
                        closeAllDropdowns();
                        dropdownMenu.classList.add('show');
                    }
                });
            } else {
                // Mobile: Use click for toggle
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    } else {
                        closeAllDropdowns();
                        dropdownMenu.classList.add('show');
                    }
                });
            }
        });

        // Helper function to close all open dropdowns
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu.show, .mega-dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown, .mega-dropdown')) {
                closeAllDropdowns();
            }
        });

        // Handle keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        // Add active state to current page nav links
        const currentPath = window.location.pathname;
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '/') {
                link.classList.add('active');
            }
        });

        // Auto-dismiss flash messages after 5 seconds
        const flashMessages = document.querySelectorAll('.alert');
        if (flashMessages.length > 0) {
            setTimeout(function() {
                flashMessages.forEach(message => {
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => message.remove(), 300);
                });
            }, 5000);
        }

        // Handle theme switching
        const themeSwitches = document.querySelectorAll('#themeSwitch');
        themeSwitches.forEach(switchEl => {
            // Check if user has previously set theme
            const darkMode = localStorage.getItem('darkMode') === 'enabled';
            switchEl.checked = darkMode;

            if (darkMode) {
                document.body.classList.add('dark-mode');
            }

            switchEl.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });
        });

        // Initialize notification badges with animation
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => {
            badge.style.transform = 'scale(1)';
        });
    });
</script>