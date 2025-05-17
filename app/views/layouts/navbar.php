<?php
/**
 * Main navigation bar component with dynamic content based on the current page
 */

// Determine which page we're on (auth, landing or other)
$currentPage = '';
$isAuthPage = strpos($_SERVER['REQUEST_URI'], 'auth') !== false;

// Simplify landing page detection
$requestUri = $_SERVER['REQUEST_URI'];
$baseUrl = URL_ROOT;
$isLandingPage = ($requestUri == '/' || $requestUri == $baseUrl || $requestUri == $baseUrl . '/' || $requestUri == '/index.php');

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Get current URL path for highlighting active nav items
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Get user info from database if logged in
$userInfo = [];
if ($isLoggedIn) {
    // Initialize User model
    $userModel = new User();
    
    // Get user data from database
    $userData = $userModel->getUserById($_SESSION['user_id']);
    
    if ($userData) {
        // Convert skills from JSON to array if it exists
        $skills = [];
        if (!empty($userData->skills)) {
            $skills = json_decode($userData->skills, true);
        }
        
        $userInfo = [
            'id' => $userData->id,
            'name' => $userData->name,
            'email' => $userData->email,
            'avatar' => $userData->profile_image ?? URL_ROOT . '/public/img/default-avatar.png',
            'role' => $userData->account_type,
            'hourly_rate' => $userData->hourly_rate ? '$' . number_format($userData->hourly_rate, 2) . '/hr' : '$40.00/hr',
            'online_status' => isset($_SESSION['online_status']) ? $_SESSION['online_status'] : true,
            'bio' => $userData->bio ?? 'No bio provided yet.',
            'skills' => $skills,
            'location' => $userData->location ?? 'Not specified',
            'joined_date' => date('F Y', strtotime($userData->created_at ?? 'now')),
            'social_links' => [
                'website' => $userData->website ?? '',
                'linkedin' => $userData->linkedin ?? '',
                'github' => $userData->github ?? '',
                'twitter' => $userData->twitter ?? '',
            ],
        ];
    }
}

// Helper function to check if a given URL path matches the current one
function isActivePath($path) {
    global $currentPath;
    // Check if $path is contained at the beginning of $currentPath
    return strpos($currentPath, $path) === 0;
}

// Helper function to generate nav item CSS class with active state
function getNavItemClass($path) {
    return 'nav-item' . (isActivePath($path) ? ' active' : '');
}
?>

<!-- Navbar Styles -->
<style>
    /* Base styles and variables */
    :root {
        --navbar-height: 56px;
        --navbar-bg: #ffffff;
        --navbar-text: #62646a;
        --navbar-border: #e4e5e7;
        --navbar-hover: #2c3e50;
        --navbar-active: #2c3e50;
        --button-primary: #2c3e50;
        --button-primary-hover: #1a252f;
        --button-text: #ffffff;
        --dropdown-shadow: 0 2px 7px 0 rgba(0,0,0,.1);
        
        /* Color scheme */
        --primary: #2c3e50;
        --primary-light: #34495e;
        --primary-dark: #1a252f;
        --primary-accent: #ecf0f1;
        --secondary: #222325;
        --secondary-light: #404145;
        --secondary-dark: #0e0e10;
        --secondary-accent: #f1f1f2;
        
        /* Font sizes */
        --font-xs: 0.75rem;  /* 12px */
        --font-sm: 0.8125rem; /* 13px */
        --font-md: 0.875rem;  /* 14px */
        --font-base: 0.9375rem; /* 15px */
        --font-lg: 1rem;      /* 16px */
        --font-xl: 1.125rem;  /* 18px */
    }

    body {
        padding-top: var(--navbar-height);
        margin: 0;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: var(--font-base);
        line-height: 1.5;
    }

    /* Navbar container */
    .navbar {
        height: var(--navbar-height);
        background-color: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1030;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-bottom: 1px solid rgba(229, 231, 235, 0.2);
    }

    .navbar-container {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        max-width: 1400px;
        margin: 0 auto;
        height: 100%;
    }

    /* Brand logo */
    .navbar-brand {
        font-size: var(--font-xl);
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        margin-right: auto;
        position: relative;
        letter-spacing: -0.5px;
        transition: color 0.3s ease;
    }

    .navbar-brand:hover {
        color: var(--primary);
        text-decoration: none;
    }

    /* Media query for desktop view */
    @media (min-width: 992px) {
        .navbar {
            width: 96%;
            max-width: 1300px;
            margin: 8px auto;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }
        
        .navbar.transparent {
            background-color: transparent !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: none !important;
            box-shadow: none !important;
        }
        
        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }
        
        body {
            padding-top: calc(var(--navbar-height) + 16px);
        }
        
        /* Landing page specific overrides for desktop */
        body.landing-page .navbar {
            position: fixed !important;
            width: 96% !important;
            max-width: 1300px !important;
            margin: 8px auto !important;
            left: 0 !important;
            right: 0 !important;
            border-radius: 6px !important;
            transition: all 0.3s ease !important;
            z-index: 1030 !important;
        }
        body.landing-page .navbar.transparent {
            background-color: rgba(0, 0, 0, 0.05) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            box-shadow: none !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        body.landing-page .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06) !important;
        }
        body.landing-page .navbar .navbar-brand {
            color: #2c3e50 !important;
            font-size: 1.125rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.5px !important;
            text-decoration: none !important;
        }
        body.landing-page .navbar .navbar-brand:hover {
            color: #2c3e50 !important;
            text-decoration: none !important;
        }
        body.landing-page .navbar .nav-link,
        body.landing-page .navbar .action-link {
            color: #222325 !important;
            font-size: 0.9375rem !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            transition: color 0.3s !important;
        }
        body.landing-page .navbar .nav-link:hover,
        body.landing-page .navbar .action-link:hover {
            color: #2c3e50 !important;
            text-decoration: none !important;
        }
        body.landing-page .navbar .sign-up-button {
            color: #2c3e50 !important;
            border: 1px solid #2c3e50 !important;
            background-color: transparent !important;
            font-weight: 500 !important;
            font-size: 0.875rem !important;
            border-radius: 4px !important;
            padding: 8px 16px !important;
            transition: all 0.3s !important;
        }
        body.landing-page .navbar .sign-up-button:hover {
            background-color: #2c3e50 !important;
            color: #fff !important;
            text-decoration: none !important;
        }
        body.landing-page .navbar .profile-avatar img {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
        }
        body.landing-page .navbar.transparent .nav-link,
        body.landing-page .navbar.transparent .action-link,
        body.landing-page .navbar.transparent .sign-up-button {
            color: #fff !important;
            background: transparent !important;
            border-color: rgba(255,255,255,0.7) !important;
        }
        body.landing-page .navbar.transparent .nav-link:hover,
        body.landing-page .navbar.transparent .action-link:hover {
            color: #fff !important;
            background: transparent !important;
            text-decoration: none !important;
        }
        body.landing-page .navbar.transparent .sign-up-button:hover {
            background: #2c3e50 !important;
            color: #fff !important;
            border-color: #2c3e50 !important;
            text-decoration: none !important;
        }
    }

    /* Right side - Auth buttons */
    .navbar-actions {
        display: flex;
        align-items: center;
        margin-left: auto;
        gap: 16px;
        height: 100%;
    }

    .action-item {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
    }

    /* Sign Up button */
    .sign-up-button {
        background-color: transparent;
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        font-size: var(--font-md);
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid var(--primary);
    }

    /* Mobile menu toggle */
    #mobile-menu-toggle {
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 8px;
        margin-right: 12px;
        display: none;
        width: 32px;
        height: 32px;
        transition: all 0.2s ease;
    }

    #mobile-menu-toggle span {
        display: block;
        width: 18px;
        height: 2px;
        background-color: var(--secondary);
        margin: 3px auto;
        transition: all 0.3s ease;
    }
    
    /* Mobile menu styling for non-auth pages */
    .mobile-nav {
        display: none;
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid var(--navbar-border);
        z-index: 1030;
        padding: 0;
        transform: translateY(-10px);
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-height: calc(100vh - var(--navbar-height));
        overflow-y: auto;
    }
    
    @media (min-width: 992px) {
        .mobile-nav {
            width: 90%;
            max-width: 1200px;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            margin: 0 auto;
            border-radius: 0 0 8px 8px;
            border-top: none;
        }
        
        .mobile-nav.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
    
    .mobile-nav.show {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }
    
    .mobile-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }

    .mobile-btn {
        display: block;
        padding: 12px;
        text-align: center;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .mobile-btn-primary {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
        transition: all 0.3s ease;
    }

    .mobile-btn-primary:hover {
        background-color: var(--primary);
        color: white;
    }

    .mobile-btn-secondary {
        background-color: white;
        color: var(--secondary);
        border: 1px solid var(--secondary);
    }

    .mobile-btn-secondary:hover {
        background-color: #f5f5f5;
        color: var(--secondary);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #mobile-menu-toggle {
            display: block;
        }
        .navbar-container {
            padding: 0 16px;
        }
        .navbar-nav {
            display: none;
        }
    }

    /* User dropdown styling */
    .action-item {
        position: relative;
    }
    
    .profile-avatar {
        cursor: pointer;
    }
    
    .user-dropdown {
        position: absolute;
        top: calc(100% + 5px);
        right: 0;
        width: 280px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        display: none;
        z-index: 1040;
        overflow: hidden;
    }
    
    .user-dropdown.show {
        display: block;
        animation: fadeInDown 0.25s ease-out;
    }
    
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
    
    .user-dropdown-header {
        padding: 16px;
        border-bottom: 1px solid var(--navbar-border);
    }
    
    .user-dropdown-info {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .dropdown-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-dropdown-name {
        margin-left: 12px;
    }
    
    .user-name {
        font-weight: 600;
        font-size: var(--font-md);
        color: var(--secondary);
    }
    
    .user-role {
        font-size: var(--font-sm);
        color: var(--navbar-text);
    }
    
    .online-status-toggle {
        margin-top: 8px;
    }
    
    .online-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        font-size: var(--font-sm);
        color: var(--secondary);
        cursor: pointer;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 20px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 20px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: var(--success);
    }
    
    input:focus + .toggle-slider {
        box-shadow: 0 0 1px var(--success);
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(16px);
    }
    
    .user-dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: var(--secondary);
        text-decoration: none;
        font-size: var(--font-md);
        transition: background-color 0.2s ease;
    }
    
    .dropdown-item:hover {
        background-color: #f5f7fa;
        color: var(--primary);
    }
    
    .dropdown-item i {
        margin-right: 14px;
        width: 20px;
        min-width: 20px;
        font-size: 18px;
        text-align: center;
        color: #7a7a7a;
        opacity: 0.85;
        vertical-align: middle;
        transition: color 0.2s, opacity 0.2s;
        display: inline-block;
    }
    .dropdown-item:hover i,
    .dropdown-item.active i {
        color: #1f7a8c;
        opacity: 1;
    }
    
    /* Mobile menu styling for logged-in users */
    .mobile-menu {
        padding: 12px 0;
    }
    
    .mobile-menu-item {
        display: block;
        padding: 14px 20px;
        color: var(--secondary);
        text-decoration: none;
        font-size: var(--font-lg);
        font-weight: 500;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.2s ease;
    }
    
    .mobile-menu-item:hover {
        background-color: #f5f7fa;
        color: var(--primary);
    }

    /* Active navigation states */
    .nav-item.active .nav-link {
        color: var(--primary);
        font-weight: 600;
    }
    
    .nav-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background-color: var(--primary);
        border-radius: 2px;
    }
    
    .dropdown-item.active {
        background-color: var(--primary-accent);
        color: var(--primary);
    }

    /* Community navigation specific styles */
    .action-item.active .action-link {
        color: var(--primary);
        font-weight: 600;
    }

    .action-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background-color: var(--primary);
        border-radius: 2px;
    }

    /* On community pages, make the navigation more prominent */
    body.community-page .navbar {
        border-bottom: 1px solid rgba(44, 62, 80, 0.1);
    }

    body.community-page .action-item .action-link {
        padding: 0 16px;
        position: relative;
    }

    body.community-page .action-item.active .action-link::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--primary);
    }

    /* Additional navbar styling */
    .freelancer-profile-section {
        display: flex;
        align-items: center;
        margin-right: 16px;
        gap: 16px;
    }
    
    .hourly-rate {
        font-weight: 600;
        font-size: var(--font-md);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 4px;
        background-color: rgba(44, 62, 80, 0.08);
    }
    
    .current-job-indicator {
        display: flex;
        align-items: center;
        font-size: var(--font-sm);
        color: var(--success);
        gap: 6px;
    }
    
    /* Client-specific navbar styling */
    .client-profile-section {
        display: flex;
        align-items: center;
        margin-right: 16px;
        gap: 16px;
    }
    
    .client-status {
        font-weight: 600;
        font-size: var(--font-md);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 4px;
        background-color: rgba(44, 62, 80, 0.08);
    }
    
    .btn-post-job {
        background-color: var(--success);
        border-color: var(--success);
    }
    
    .btn-post-job:hover {
        background-color: var(--success-dark);
        border-color: var(--success-dark);
    }
    
    .current-job-indicator i {
        font-size: 10px;
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            opacity: 0.6;
        }
        50% {
            opacity: 1;
        }
        100% {
            opacity: 0.6;
        }
    }
    
    @media (max-width: 992px) {
        .freelancer-profile-section {
            display: none;
        }
    }

    /* --- Dynamic Navbar Search Bar --- */
    .navbar-search-bar {
        display: flex;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 24px;
        height: 40px;
        width: 100%;
        min-width: 0;
        padding: 0 12px;
        background: #fff;
        box-sizing: border-box;
        max-width: 500px;
        position: relative;
    }
    .search-icon {
        color: #666;
        margin-right: 8px;
        font-size: 18px;
        flex-shrink: 0;
    }
    .navbar-search-input {
        border: none;
        outline: none;
        flex: 1 1 0%;
        min-width: 0;
        font-size: 15px;
        background: transparent;
        color: #222;
        padding: 0;
        height: 100%;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 400 !important;
    }
    .navbar-search-input::placeholder {
        color: #aaa;
        opacity: 1;
    }
    .search-dropdown {
        border-left: 1px solid #e0e0e0;
        margin-left: 10px;
        padding-left: 10px;
        display: flex;
        align-items: center;
        height: 100%;
        position: relative;
        box-sizing: border-box;
        flex-shrink: 0;
        background: #fff;
    }
    .search-dropdown-toggle {
        background: none;
        border: none;
        padding: 0 8px;
        font-weight: 400 !important;
        color: #222;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: 15px;
        height: 100%;
        box-sizing: border-box;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .search-dropdown-toggle i {
        font-size: 14px;
        margin-left: 4px;
    }
    .search-dropdown-menu {
        position: absolute;
        top: 110%;
        left: 0;
        min-width: 180px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        z-index: 1000;
        display: none;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        box-sizing: border-box;
    }
    .search-dropdown-menu.show {
        display: block;
        animation: fadeIn 0.18s ease-out;
    }
    .search-dropdown-item {
        display: block;
        padding: 10px 16px;
        color: #222;
        text-decoration: none;
        font-size: 15px;
        transition: background 0.2s;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .search-dropdown-item:hover,
    .search-dropdown-item.active {
        background: #f5f5f5;
        color: #1f7a8c;
    }
    @media (max-width: 600px) {
        .navbar-search-bar {
            max-width: 100%;
        }
    }

    /* Standard navbar actions for all non-landing pages */
    body:not(.landing-page) .navbar-actions .action-link {
        color: var(--primary);
        background: transparent;
        border: none;
        font-weight: 500;
        font-size: var(--font-md);
        padding: 8px 14px;
        border-radius: 4px;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
        margin-left: 4px;
    }
    body:not(.landing-page) .navbar-actions .action-link:hover,
    body:not(.landing-page) .navbar-actions .action-link.active {
        background: var(--primary-accent);
        color: var(--primary);
        text-decoration: none;
    }
    body:not(.landing-page) .navbar-actions .sign-up-button {
        background: var(--primary);
        color: #fff;
        border: 1px solid var(--primary);
        font-weight: 600;
        font-size: var(--font-md);
        padding: 8px 18px;
        border-radius: 4px;
        margin-left: 8px;
        transition: background 0.2s, color 0.2s, border 0.2s;
        text-decoration: none;
    }
    body:not(.landing-page) .navbar-actions .sign-up-button:hover {
        background: var(--button-primary-hover);
        color: #fff;
        border-color: var(--button-primary-hover);
        text-decoration: none;
    }
</style>

<nav class="navbar">
    <div class="navbar-container">
        <!-- Brand Logo - Always visible -->
        <a class="navbar-brand" href="<?php echo URL_ROOT; ?>" style="display: flex; align-items: center;">
            <svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.56 90.12" width="82" height="26">
                <defs>
                    <style>.cls-1{fill:#022b3a !important;}.cls-2{fill:#1f7a8c !important;}</style>
                </defs>
                <path class="cls-1" d="M146.56,330.77v-89H169v89Z" transform="translate(-146.56 -241.73)"/>
                <path class="cls-1" d="M205.84,331.85a43.65,43.65,0,0,1-20.1-4.38,32.9,32.9,0,0,1-13.32-12,31.93,31.93,0,0,1-4.74-17.22,32.37,32.37,0,0,1,4.68-17.4A33,33,0,0,1,185.14,269a38,38,0,0,1,18.18-4.32,38.62,38.62,0,0,1,17.52,4,30.82,30.82,0,0,1,12.6,11.46q4.68,7.5,4.68,18.3,0,1.32-.12,2.82t-.24,2.7H186.28V292h39.48l-8.52,3.48a15,15,0,0,0-1.68-7.74,13.13,13.13,0,0,0-4.8-5.1,13.74,13.74,0,0,0-7.2-1.8,14.06,14.06,0,0,0-7.26,1.8,12.58,12.58,0,0,0-4.8,5.1,16.64,16.64,0,0,0-1.74,7.86v3.48a16.09,16.09,0,0,0,2,8.28,13.83,13.83,0,0,0,5.82,5.4,19.23,19.23,0,0,0,8.82,1.92,22,22,0,0,0,8.4-1.44,23.93,23.93,0,0,0,6.72-4.32l11.88,12.48A29.82,29.82,0,0,1,222,329.15,44.15,44.15,0,0,1,205.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                <path class="cls-1" d="M278.92,264.65a30.06,30.06,0,0,1,13.74,3.06,22.38,22.38,0,0,1,9.6,9.48q3.54,6.42,3.54,16.38v37.2H283.24v-33.6q0-6.84-2.88-10.08a10.05,10.05,0,0,0-7.92-3.24,13.91,13.91,0,0,0-6.72,1.62,11.11,11.11,0,0,0-4.68,5,20,20,0,0,0-1.68,8.88v31.44H236.92v-65h21.36v18.48l-4.08-5.4a24.75,24.75,0,0,1,10.08-10.56A29.63,29.63,0,0,1,278.92,264.65Z" transform="translate(-146.56 -241.73)"/>
                <path class="cls-1" d="M331.84,331.85a67.53,67.53,0,0,1-16.2-1.92,46.12,46.12,0,0,1-12.6-4.8l6.84-15.48A38.64,38.64,0,0,0,320.44,314a46.78,46.78,0,0,0,12,1.62q5.76,0,8-1.2a3.69,3.69,0,0,0,2.28-3.36,3,3,0,0,0-2-2.76,20.66,20.66,0,0,0-5.46-1.5q-3.42-.54-7.5-1.08a67.78,67.78,0,0,1-8.22-1.62A28.92,28.92,0,0,1,312,301a15.56,15.56,0,0,1-5.52-5.64,18.16,18.16,0,0,1-2.1-9.24A17.54,17.54,0,0,1,308,275.21a24.93,24.93,0,0,1,10.68-7.68q7-2.88,17.22-2.88a68.84,68.84,0,0,1,13.86,1.44,42.1,42.1,0,0,1,11.82,4.08l-6.72,15.48a31.53,31.53,0,0,0-9.72-3.84,46.56,46.56,0,0,0-9.12-1q-5.76,0-8.16,1.32t-2.4,3.36a3.12,3.12,0,0,0,2,2.82,19.15,19.15,0,0,0,5.52,1.56q3.48.54,7.56,1.14a73.87,73.87,0,0,1,8.16,1.68,30.1,30.1,0,0,1,7.56,3.12,15.08,15.08,0,0,1,5.52,5.58,18.25,18.25,0,0,1,2,9.18,17.38,17.38,0,0,1-3.6,10.68q-3.6,4.8-10.8,7.68T331.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                <path class="cls-1" d="M362,330.77v-65H384.4v65Z" transform="translate(-146.56 -241.73)"/>
                <path class="cls-2" d="M419.55,331.85a13.34,13.34,0,0,1-9.66-3.78,13,13,0,0,1-3.89-9.78,12.47,12.47,0,0,1,3.89-9.54,14.5,14.5,0,0,1,19.26,0,12.4,12.4,0,0,1,4,9.54,12.92,12.92,0,0,1-4,9.78A13.39,13.39,0,0,1,419.55,331.85Z" transform="translate(-146.56 -241.73)"/>
            </svg>
        </a>
        
        <?php if(!$isAuthPage): // Only show navigation for non-auth pages ?>
            <?php if(!$isLoggedIn): // For not logged in users - show across all non-auth pages ?>
                <!-- Login/signup buttons for non-logged users -->
                <div style="flex-grow: 1;"></div>
                
                <div class="navbar-actions">
                    <?php if (strpos($currentPath, '/pages/community') !== false): ?>
                    <!-- When on community pages, show community navigation items -->
                    <div class="action-item <?php echo isActivePath('/pages/community') && $currentPath === '/pages/community' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community" class="action-link">
                            Home
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/resources') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="action-link">
                            Resources
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/forum') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="action-link">
                            Forum
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/events') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="action-link">
                            Events
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- On non-community pages, show community dropdown -->
                    <div class="action-item <?php echo isActivePath('/pages/community') ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community" class="action-link">
                            Community
                        </a>
                        <!-- Community Dropdown -->
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-content">
                                <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="dropdown-item">Forum</a>
                                <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="dropdown-item">Resources</a>
                                <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="dropdown-item">Events</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="action-item">
                        <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="action-link">
                            Log In
                        </a>
                    </div>
                    <div class="action-item">
                        <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="sign-up-button">
                            Sign Up
                        </a>
                    </div>
                </div>
                
                <!-- Mobile menu toggle for landing page -->
                <button id="mobile-menu-toggle" class="d-md-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            <?php elseif($isLoggedIn): // For logged-in users ?>
                <!-- Navigation for logged in users -->
                <ul class="navbar-nav">
                    <?php if (strpos($currentPath, '/pages/community') === false && strpos($currentPath, '/pages/support') === false): // Only show main nav items when NOT on community or support pages ?>
                    <!-- Navigation items removed as requested -->
                    <?php endif; ?>
                </ul>
                
                <div style="flex-grow: 1;"></div>
                
                <div class="navbar-actions">
                    <?php if (strpos($currentPath, '/pages/community') !== false): ?>
                    <!-- When on community pages, show community navigation items for logged-in users -->
                    <div class="action-item <?php echo isActivePath('/pages/community') && $currentPath === '/pages/community' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community" class="action-link">
                            Home
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/forum') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="action-link">
                            Forum
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/resources') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="action-link">
                            Resources
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/projects') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/projects" class="action-link">
                            Projects
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/pages/community/events') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="action-link">
                            Events
                        </a>
                    </div>
                    <?php elseif (strpos($currentPath, '/pages/support') !== false): ?>
                    <!-- When on support pages, show support navigation items for logged-in users -->
                    <div class="action-item <?php echo isActivePath('/pages/support') && $currentPath === '/pages/support' ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/pages/support" class="action-link">
                            Support Home
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/support/faq') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/support/faq" class="action-link">
                            FAQ
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/support/tickets') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/support/tickets" class="action-link">
                            My Tickets
                        </a>
                    </div>
                    <div class="action-item <?php echo strpos($currentPath, '/support/contact') !== false ? 'active' : ''; ?>">
                        <a href="<?php echo URL_ROOT; ?>/support/contact" class="action-link">
                            Contact Us
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- On non-community and non-support pages, show regular logged-in user actions -->
                    <div class="action-item search-container">
                        <form action="<?php echo URL_ROOT; ?>/services/search" method="GET" id="navbarSearchForm" class="navbar-search-bar">
                            <div class="search-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" name="search" placeholder="Search for jobs, talent, or projects..." class="navbar-search-input">
                            <input type="hidden" name="category" id="searchCategory" value="all">
                            <div class="search-dropdown">
                                <button type="button" class="search-dropdown-toggle" id="searchDropdownToggle">
                                    All Categories <i class="fas fa-chevron-down"></i>
                                </button>
                                <!-- Dropdown menu -->
                                <div class="search-dropdown-menu" id="searchDropdownMenu">
                                    <a href="#" class="search-dropdown-item active" data-value="all">All Categories</a>
                                    <a href="#" class="search-dropdown-item" data-value="web-development">Web Development</a>
                                    <a href="#" class="search-dropdown-item" data-value="mobile-app">Mobile App</a>
                                    <a href="#" class="search-dropdown-item" data-value="design">Design</a>
                                    <a href="#" class="search-dropdown-item" data-value="digital-marketing">Digital Marketing</a>
                                    <a href="#" class="search-dropdown-item" data-value="writing">Writing</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    <div class="action-item dropdown-wrapper">
                        <a href="javascript:void(0)" class="action-link profile-avatar" id="profileDropdownToggle">
                            <?php if(!empty($userInfo['avatar'])): ?>
                                <img src="<?php echo $userInfo['avatar']; ?>" 
                                    alt="Profile" class="avatar-img" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?php echo URL_ROOT; ?>/public/img/default-avatar.png" 
                                    alt="Profile" class="avatar-img" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            <?php endif; ?>
                        </a>
                        <!-- User dropdown menu -->
                        <div class="user-dropdown" id="userDropdown">
                            <div class="user-dropdown-header">
                                <div class="user-dropdown-info">
                                    <?php if(!empty($userInfo['avatar'])): ?>
                                        <img src="<?php echo $userInfo['avatar']; ?>" 
                                            alt="Profile" class="dropdown-avatar-img">
                                    <?php else: ?>
                                        <img src="<?php echo URL_ROOT; ?>/public/img/default-avatar.png" 
                                            alt="Profile" class="dropdown-avatar-img">
                                    <?php endif; ?>
                                    <div class="user-dropdown-name">
                                        <div class="user-name"><?php echo $userInfo['name']; ?></div>
                                        <div class="user-role"><?php echo ucfirst($userInfo['role']); ?></div>
                                    </div>
                                </div>

                            </div>
                            <div class="user-dropdown-menu">
                                <a href="<?php echo URL_ROOT; ?>/user/account" class="dropdown-item <?php echo isActivePath('/user/account') ? 'active' : ''; ?>">
                                    <i class="fas fa-user-circle"></i> My Account
                                </a>
                                <a href="<?php echo URL_ROOT; ?>/pages/support" class="dropdown-item <?php echo isActivePath('/pages/support') ? 'active' : ''; ?>">
                                    <i class="fas fa-headset"></i> Support
                                </a>
                                <a href="<?php echo URL_ROOT; ?>/pages/community" class="dropdown-item <?php echo isActivePath('/pages/community') ? 'active' : ''; ?>">
                                    <i class="fas fa-users"></i> Community
                                </a>
                                <a href="<?php echo URL_ROOT; ?>/users/logout" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Log out
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu toggle for logged in users -->
                <button id="mobile-menu-toggle" class="d-md-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</nav>

<!-- Mobile navigation - only for non-auth pages -->
<?php if(!$isAuthPage && !$isLoggedIn): ?>
<div class="mobile-nav" id="mobile-nav">
    <div class="mobile-buttons">
        <?php if (strpos($currentPath, '/pages/community') !== false): ?>
        <!-- When on community pages, show community navigation items -->
        <a href="<?php echo URL_ROOT; ?>/pages/community" class="mobile-btn <?php echo isActivePath('/pages/community') && $currentPath === '/pages/community' ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-home"></i> Community Home</a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="mobile-btn <?php echo strpos($currentPath, '/pages/community/forum') !== false ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-comments"></i> Forum</a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="mobile-btn <?php echo strpos($currentPath, '/pages/community/resources') !== false ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-book"></i> Resources</a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/projects" class="mobile-btn <?php echo strpos($currentPath, '/pages/community/projects') !== false ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-project-diagram"></i> Projects</a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="mobile-btn <?php echo strpos($currentPath, '/pages/community/events') !== false ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-calendar-alt"></i> Events</a>
        <?php elseif (strpos($currentPath, '/pages/support') !== false): ?>
        <!-- When on support pages, show support navigation items -->
        <a href="<?php echo URL_ROOT; ?>/pages/support" class="mobile-btn mobile-btn-primary">Support Home</a>
        <a href="<?php echo URL_ROOT; ?>/support/faq" class="mobile-btn mobile-btn-primary">FAQ</a>
        <a href="<?php echo URL_ROOT; ?>/support/tickets" class="mobile-btn mobile-btn-primary">My Tickets</a>
        <a href="<?php echo URL_ROOT; ?>/support/contact" class="mobile-btn mobile-btn-primary">Contact Us</a>
        <?php else: ?>
        <!-- On non-community pages, show community with dropdown -->
        <a href="<?php echo URL_ROOT; ?>/pages/community" class="mobile-btn <?php echo isActivePath('/pages/community') ? 'mobile-btn-primary' : 'mobile-btn-secondary'; ?>"><i class="fas fa-users"></i> Community</a>
        
        <!-- Community Submenu -->
        <div class="mobile-submenu">
            <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="mobile-submenu-item"><i class="fas fa-comments"></i> Forum</a>
            <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="mobile-submenu-item"><i class="fas fa-book"></i> Resources</a>
            <a href="<?php echo URL_ROOT; ?>/pages/community/projects" class="mobile-submenu-item"><i class="fas fa-project-diagram"></i> Projects</a>
            <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="mobile-submenu-item"><i class="fas fa-calendar-alt"></i> Events</a>
        </div>
        <?php endif; ?>

        <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="mobile-btn mobile-btn-secondary">Log In</a>
        <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="mobile-btn mobile-btn-primary">Sign Up</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        
        // Add landing-page class to body when on landing page
        const isLandingPage = <?php echo $isLandingPage ? 'true' : 'false'; ?>;
        if (isLandingPage) {
            document.body.classList.add('landing-page');
        }
        
        // Add community-page class to body when on community pages
        const isCommunityPage = <?php echo strpos($currentPath, '/pages/community') !== false ? 'true' : 'false'; ?>;
        if (isCommunityPage) {
            document.body.classList.add('community-page');
        }
        
        if (mobileMenuToggle && mobileNav) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileNav.classList.toggle('show');
                this.classList.toggle('active');
            });
        }
        
        // Handle escape key to close mobile menu
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (mobileNav && mobileNav.classList.contains('show')) {
                    mobileNav.classList.remove('show');
                    if (mobileMenuToggle) {
                        mobileMenuToggle.classList.remove('active');
                    }
                }
            }
        });
        
        // Click outside to close mobile menu
        document.addEventListener('click', function(e) {
            if (mobileNav && mobileNav.classList.contains('show') && 
                !e.target.closest('.mobile-nav') && 
                !e.target.closest('#mobile-menu-toggle')) {
                mobileNav.classList.remove('show');
                if (mobileMenuToggle) {
                    mobileMenuToggle.classList.remove('active');
                }
            }
        });
        
        // Navbar scroll behavior for glassmorphism effect
        const navbar = document.querySelector('.navbar');
        const isLandingPage = <?php echo $isLandingPage ? 'true' : 'false'; ?>;
        
        if (navbar) {
            // Initially transparent on landing page
            if (isLandingPage) {
                navbar.classList.add('transparent');
            }
            
            // Handle scroll behavior
            const handleScroll = () => {
                const scrollPosition = window.scrollY;
                
                if (isLandingPage) {
                    // Make navbar transparent at the top, add blur when scrolling
                    if (scrollPosition > 50) {
                        navbar.classList.add('scrolled');
                        navbar.classList.remove('transparent');
                    } else {
                        navbar.classList.remove('scrolled');
                        navbar.classList.add('transparent');
                    }
                }
            };
            
            // Listen for scroll events
            window.addEventListener('scroll', handleScroll);
            
            // Initial check on page load
            handleScroll();
        }
    });
</script>
<?php endif; ?>

<!-- Mobile navigation for logged-in users -->
<?php if(!$isAuthPage && $isLoggedIn): ?>
<div class="mobile-nav" id="mobile-nav">
    <div class="mobile-user-header">
        <div class="mobile-user-info">
            <img src="<?php echo $userInfo['avatar']; ?>" alt="Profile" class="mobile-avatar">
            <div class="mobile-user-details">
                <div class="mobile-user-name"><?php echo $userInfo['name']; ?></div>
                <div class="mobile-user-role"><?php echo ucfirst($userInfo['role']); ?></div>
                <?php if($userInfo['role'] == 'freelancer'): ?>
                <div class="mobile-user-rate"><?php echo $userInfo['hourly_rate']; ?></div>
                <?php elseif($userInfo['role'] == 'client'): ?>
                <div class="mobile-user-rate">
                    <i class="fas fa-briefcase me-1"></i>
                    <?php echo isset($_SESSION['active_contracts']) ? $_SESSION['active_contracts'] : 0; ?> Active contracts
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mobile-profile-actions">
                            <a href="<?php echo URL_ROOT; ?>/user/account" class="mobile-profile-btn">My Account</a>
                <?php if($userInfo['role'] == 'client'): ?>
                <a href="<?php echo URL_ROOT; ?>/client/post-job" class="mobile-profile-btn ms-2 btn-post-job">Post Job</a>
                <?php endif; ?>
        </div>
    </div>
    <div class="mobile-menu">
        <div class="mobile-menu-divider"></div>
        <?php if (strpos($currentPath, '/pages/community') !== false): ?>
        <!-- When on community pages, show community navigation items -->
        <a href="<?php echo URL_ROOT; ?>/pages/community" class="mobile-menu-item <?php echo isActivePath('/pages/community') && $currentPath === '/pages/community' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Community Home
        </a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="mobile-menu-item <?php echo strpos($currentPath, '/pages/community/forum') !== false ? 'active' : ''; ?>">
            <i class="fas fa-comments"></i> Forum
        </a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/resources" class="mobile-menu-item <?php echo strpos($currentPath, '/pages/community/resources') !== false ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> Resources
        </a>
        <a href="<?php echo URL_ROOT; ?>/pages/community/events" class="mobile-menu-item <?php echo strpos($currentPath, '/pages/community/events') !== false ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
        <?php elseif (strpos($currentPath, '/pages/support') !== false): ?>
        <!-- When on support pages, show support navigation items -->
        <a href="<?php echo URL_ROOT; ?>/pages/support" class="mobile-menu-item <?php echo isActivePath('/pages/support') && $currentPath === '/pages/support' ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i> Support Home
        </a>
        <a href="<?php echo URL_ROOT; ?>/support/faq" class="mobile-menu-item <?php echo strpos($currentPath, '/support/faq') !== false ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i> FAQ
        </a>
        <a href="<?php echo URL_ROOT; ?>/support/tickets" class="mobile-menu-item <?php echo strpos($currentPath, '/support/tickets') !== false ? 'active' : ''; ?>">
            <i class="fas fa-ticket-alt"></i> My Tickets
        </a>
        <a href="<?php echo URL_ROOT; ?>/support/contact" class="mobile-menu-item <?php echo strpos($currentPath, '/support/contact') !== false ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i> Contact Us
        </a>
        <?php else: ?>
        <!-- Regular navigation items for non-community pages -->
        <a href="<?php echo URL_ROOT; ?>/user/account" class="mobile-menu-item <?php echo isActivePath('/user/account') ? 'active' : ''; ?>">
            <i class="fas fa-user-circle"></i> My Account
        </a>
        <a href="<?php echo URL_ROOT; ?>/pages/support" class="mobile-menu-item <?php echo isActivePath('/pages/support') ? 'active' : ''; ?>">
            <i class="fas fa-headset"></i> Support
        </a>
        <a href="<?php echo URL_ROOT; ?>/pages/community" class="mobile-menu-item <?php echo isActivePath('/pages/community') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Community
        </a>
        <?php endif; ?>
        <div class="mobile-menu-divider"></div>
        <a href="<?php echo URL_ROOT; ?>/users/logout" class="mobile-menu-item">
            <i class="fas fa-sign-out-alt"></i> Log Out
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        
        if (mobileMenuToggle && mobileNav) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileNav.classList.toggle('show');
                this.classList.toggle('active');
                
                // Prevent body scrolling when mobile menu is open
                if (mobileNav.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Handle escape key to close mobile menu
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (mobileNav && mobileNav.classList.contains('show')) {
                    mobileNav.classList.remove('show');
                    document.body.style.overflow = '';
                    if (mobileMenuToggle) {
                        mobileMenuToggle.classList.remove('active');
                    }
                }
                
                // Also close user dropdown if open
                const userDropdown = document.getElementById('userDropdown');
                if (userDropdown && userDropdown.classList.contains('show')) {
                    userDropdown.classList.remove('show');
                }
            }
        });
        
        // Click outside to close mobile menu
        document.addEventListener('click', function(e) {
            if (mobileNav && mobileNav.classList.contains('show') && 
                !e.target.closest('.mobile-nav') && 
                !e.target.closest('#mobile-menu-toggle')) {
                mobileNav.classList.remove('show');
                document.body.style.overflow = '';
                if (mobileMenuToggle) {
                    mobileMenuToggle.classList.remove('active');
                }
            }
        });
        
        // User dropdown toggle
        const profileDropdownToggle = document.getElementById('profileDropdownToggle');
        const userDropdown = document.getElementById('userDropdown');
        
        if (profileDropdownToggle && userDropdown) {
            profileDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                userDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#profileDropdownToggle') && 
                    !e.target.closest('#userDropdown') && 
                    userDropdown.classList.contains('show')) {
                    userDropdown.classList.remove('show');
                }
            });
        }
        
        // Online status toggle functionality
        const onlineStatusToggle = document.getElementById('onlineStatusToggle');
        if (onlineStatusToggle) {
            onlineStatusToggle.addEventListener('change', function() {
                // Send AJAX request to update online status
                fetch('<?php echo URL_ROOT; ?>/api/update-online-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        status: this.checked ? 1 : 0
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Online status updated successfully');
                    } else {
                        console.error('Failed to update online status');
                        // Revert toggle if update failed
                        this.checked = !this.checked;
                    }
                })
                .catch(error => {
                    console.error('Error updating online status:', error);
                    // Revert toggle if update failed
                    this.checked = !this.checked;
                });
            });
        }
        
        // Theme toggle functionality removed - light mode only
    });
</script>

<style>
    /* Mobile user header */
    .mobile-user-header {
        padding: 16px;
        background-color: #f7f9fc;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .mobile-user-info {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .mobile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .mobile-user-details {
        margin-left: 12px;
    }
    
    .mobile-user-name {
        font-weight: 600;
        font-size: var(--font-lg);
        color: var(--secondary);
    }
    
    .mobile-user-role, .mobile-user-rate {
        font-size: var(--font-sm);
        color: var(--navbar-text);
        margin-top: 2px;
    }
    
    .mobile-profile-actions {
        margin-top: 8px;
        display: flex;
        gap: 8px;
    }
    
    .mobile-profile-btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        background-color: var(--primary);
        color: white;
        font-size: var(--font-sm);
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    
    .mobile-profile-btn.btn-post-job {
        background-color: #28a745;
    }
    
    .mobile-profile-btn.btn-post-job:hover {
        background-color: #218838;
    }
    
    .mobile-profile-btn:hover {
        background-color: var(--primary-dark);
        color: white;
    }
    
    /* Mobile menu styling */
    .mobile-menu {
        padding: 8px 0;
    }
    
    .mobile-menu-item {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: var(--secondary);
        text-decoration: none;
        font-size: var(--font-md);
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    
    .mobile-menu-item.active {
        background-color: rgba(0,0,0,0.03);
        color: var(--primary);
    }
    
    .mobile-menu-item:hover {
        background-color: #f5f7fa;
        color: var(--primary);
    }
    
    .mobile-menu-item i {
        width: 20px;
        margin-right: 12px;
        font-size: 16px;
        text-align: center;
        color: var(--navbar-text);
    }
    
    .mobile-menu-divider {
        height: 1px;
        background-color: rgba(0,0,0,0.05);
        margin: 8px 0;
    }
    
    /* Light mode only - dark theme styles removed */
</style><?php endif; ?>

<!-- Space for flash messages -->
<div style="margin-top: 20px;">
    <?php flash('message'); ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search dropdown functionality
        const searchDropdownToggle = document.getElementById('searchDropdownToggle');
        const searchDropdownMenu = document.getElementById('searchDropdownMenu');
        const searchDropdownItems = document.querySelectorAll('.search-dropdown-item');
        const searchForm = document.getElementById('navbarSearchForm');
        
        if (searchDropdownToggle && searchDropdownMenu) {
            // Toggle dropdown on click
            searchDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchDropdownMenu.classList.toggle('show');
                searchDropdownToggle.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchDropdownToggle.contains(e.target) && 
                    !searchDropdownMenu.contains(e.target) && 
                    searchDropdownMenu.classList.contains('show')) {
                    searchDropdownMenu.classList.remove('show');
                    searchDropdownToggle.classList.remove('active');
                }
            });
            
            // Handle dropdown item selection
            searchDropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.getAttribute('data-value');
                    let displayText = this.textContent.trim();
                    
                    // Update toggle button text
                    searchDropdownToggle.innerHTML = displayText + ' <i class="fas fa-chevron-down"></i>';
                    
                    // Update active state
                    searchDropdownItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update category value in the form
                    if (searchForm) {
                        // Update the hidden category input
                        const categoryInput = document.getElementById('searchCategory');
                        if (categoryInput) {
                            categoryInput.value = value;
                        }
                    }
                    
                    // Close dropdown
                    searchDropdownMenu.classList.remove('show');
                    searchDropdownToggle.classList.remove('active');
                });
            });
        }
    });
</script>

<!-- Custom script for navbar transition on landing page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only run on landing page
    const isLandingPage = document.body.classList.contains('landing-page');
    if (!isLandingPage) return;
    
    const navbar = document.querySelector('.navbar');
    const heroSection = document.querySelector('.modern-hero-section');
    if (!navbar || !heroSection) return;
    
    // Get actual hero height
    const getHeroHeight = () => heroSection.offsetHeight;
    
    // Handler for scroll effect
    function handleScroll() {
        // Determine scrolled state
        const scrollY = window.scrollY;
        const heroHeight = getHeroHeight();
        const triggerPoint = heroHeight - 50; // Change at almost the end of hero section
        
        if (scrollY < triggerPoint) {
            // Transparent mode when in hero section
            navbar.classList.add('transparent');
            navbar.classList.remove('scrolled');
            
            // Make the logo white
            const svgPaths = navbar.querySelectorAll('.cls-1, .cls-2');
            svgPaths.forEach(path => {
                if (path.classList.contains('cls-1')) {
                    path.style.setProperty('fill', 'white', 'important');
                } else if (path.classList.contains('cls-2')) {
                    path.style.setProperty('fill', 'rgba(255, 255, 255, 0.8)', 'important');
                }
            });
            
            // Make text elements white
            navbar.querySelectorAll('.navbar-brand, .action-link, .sign-up-button').forEach(el => {
                if (el.classList.contains('sign-up-button')) {
                    el.style.color = 'white';
                    el.style.borderColor = 'white';
                } else {
                    el.style.color = 'white';
                }
            });
            
            // Make menu toggle white
            const toggleSpans = navbar.querySelectorAll('#mobile-menu-toggle span');
            toggleSpans.forEach(span => {
                span.style.backgroundColor = 'white';
            });
        } else {
            // Blurred mode when scrolled past hero
            navbar.classList.remove('transparent');
            navbar.classList.add('scrolled');
            
            // Reset SVG logo colors to blue/black
            const svgPaths = navbar.querySelectorAll('.cls-1, .cls-2');
            svgPaths.forEach(path => {
                if (path.classList.contains('cls-1')) {
                    path.style.setProperty('fill', '#022b3a', 'important'); // Blue color
                } else if (path.classList.contains('cls-2')) {
                    path.style.setProperty('fill', '#1f7a8c', 'important'); // Light blue color
                }
            });
            
            // Reset text colors to default
            navbar.querySelectorAll('.navbar-brand, .action-link, .sign-up-button').forEach(el => {
                el.style.color = '';
                if (el.classList.contains('sign-up-button')) {
                    el.style.borderColor = '';
                }
            });
            
            // Reset menu toggle color
            const toggleSpans = navbar.querySelectorAll('#mobile-menu-toggle span');
            toggleSpans.forEach(span => {
                span.style.backgroundColor = '';
            });
        }
    }
    
    // Force navbar to transparent on page load
    navbar.classList.add('transparent');
    
    // Add scroll listener with passive option for better performance
    window.addEventListener('scroll', handleScroll, { passive: true });
    
    // Handle resize events
    window.addEventListener('resize', () => {
        setTimeout(handleScroll, 100);
    });
    
    // Run once on initial load with a short delay to ensure all elements are loaded
    setTimeout(handleScroll, 100);
    
    console.log("Enhanced navbar with dynamic logo color initialized");
});
</script>
