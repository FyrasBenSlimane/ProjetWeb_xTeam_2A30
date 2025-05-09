<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITE_NAME : SITE_NAME . ' Dashboard'; ?></title>

    <!-- Bootstrap CSS - Must be loaded first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS - BASE STYLES FIRST -->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/style.css">

    <!-- Feature-specific CSS - LOADED AFTER BASE STYLES -->
    <?php
    // Improved CSS loading strategy with better detection

    // Get current URL path segments for better feature detection
    $current_url = $_SERVER['REQUEST_URI'];
    $url_segments = explode('/', trim(parse_url($current_url, PHP_URL_PATH), '/'));
    $current_script = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';

    // Use the last segment as the current page for more accurate matching
    $current_page = end($url_segments);

    // Extract the section (like 'support', 'community', 'faq', etc.)
    $section = isset($url_segments[1]) ? $url_segments[1] : '';

    // Support section styles
    if (
        strpos($current_url, '/support') !== false ||
        strpos($current_url, '/faq') !== false ||
        $current_script == 'support.php' ||
        $section == 'support' ||
        (isset($data['active_parent']) && $data['active_parent'] == 'support')
    ):
    ?>
        <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/support.css">
    <?php endif; ?>

    <?php
    // Community section styles
    if (
        strpos($current_url, '/community') !== false ||
        $current_script == 'community.php' ||
        $section == 'community' ||
        (isset($data['active_parent']) && $data['active_parent'] == 'community')
    ):
    ?>
        <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/community.css">
    <?php endif; ?>

    <?php
    // Forum & FAQ Management styles
    if (
        strpos($current_url, '/forum') !== false ||
        strpos($current_url, '/faq') !== false ||
        $current_page == 'faq' ||
        $current_script == 'forum.php' ||
        (isset($data['active']) && $data['active'] == 'faq') ||
        (isset($data['title']) && stripos($data['title'], 'FAQ') !== false)
    ):
    ?>
        <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/forum.css">
    <?php endif; ?>

    <!-- Dropdown bootstrap compatibility fix -->
    <style>
        /* Fix for dropdown buttons in support section */
        .dropdown-menu {
            position: absolute !important;
            z-index: 1050 !important;
            border: 1px solid rgba(0, 0, 0, .15) !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
            display: none;
            transform: translate3d(0, 0, 0) !important;
            /* Ensure proper rendering */
            min-width: 10rem;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        /* Fix for ticket actions dropdown specific positioning */
        .actions .dropdown-menu {
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
            transform: none !important;
            margin-top: 0.125rem;
        }

        /* Override BS dropdown display */
        .btn-group.show .dropdown-menu,
        .dropdown.show .dropdown-menu {
            display: block !important;
        }

        /* Fix dropdown positioning in dashboard */
        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }

        /* Make dropdown items consistent */
        .dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 0.5rem 1rem !important;
            clear: both !important;
            font-weight: normal !important;
            color: #212529 !important;
            text-align: inherit !important;
            white-space: nowrap !important;
            background-color: transparent !important;
            border: 0 !important;
            text-decoration: none !important;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            color: #1e2125 !important;
            background-color: #f8f9fa !important;
            text-decoration: none !important;
        }

        .dropdown-item.active,
        .dropdown-item:active {
            color: #fff !important;
            background-color: var(--primary) !important;
            text-decoration: none !important;
        }

        /* Fix for dropdown trigger button */
        .action-btn.dropdown-toggle {
            position: relative;
        }

        /* Fix ticket table container to avoid obscuring dropdowns */
        .ticket-table-container {
            overflow-x: auto;
            max-width: 100%;
            border-radius: 5px;
            position: relative;
            z-index: 1;
        }

        /* Set proper positioning context for dropdown parent elements */
        .actions,
        .action-buttons,
        .dropdown {
            position: relative;
        }
    </style>

    <!-- Dashboard specific CSS -->
    <style>
        /* Root Variables */
        :root {
            --primary: #4361ee;
            --primary-rgb: 67, 97, 238;
            --primary-hover: #3a56d4;
            --secondary: #16a34a;
            --secondary-rgb: 22, 163, 74;
            --secondary-hover: #15803d;
            --info: #0ea5e9;
            --info-rgb: 14, 165, 233;
            --success: #10b981;
            --success-rgb: 16, 185, 129;
            --warning: #f59e0b;
            --warning-rgb: 245, 158, 11;
            --danger: #ef4444;
            --danger-rgb: 239, 68, 68;
            --light: #f9fafb;
            --grey: #f3f4f6;
            --grey-2: #e5e7eb;
            --grey-3: #d1d5db;
            --dark-grey: #9ca3af;
            --dark: #1f2937;
            --dark-2: #111827;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --border-radius: 0.5rem;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.2s ease;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            font-size: 0.95rem;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.5;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: var(--primary);
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary-hover);
        }

        /* Card Styles */
        .card {
            background-color: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: var(--transition);
            border: 1px solid var(--grey-2);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--grey-2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2,
        .card-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h2 i,
        .card-header h3 i {
            color: var(--primary);
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--grey-2);
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            transition: var(--transition);
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border-color: var(--grey-3);
            color: var(--dark);
        }

        .btn-outline:hover {
            background-color: var(--grey);
            border-color: var(--grey-3);
            color: var(--primary);
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
            transition: var(--transition);
        }

        .badge-primary {
            background-color: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
        }

        .badge-secondary {
            background-color: rgba(var(--secondary-rgb), 0.1);
            color: var(--secondary);
        }

        .badge-success {
            background-color: rgba(var(--success-rgb), 0.1);
            color: var (--success);
        }

        .badge-info {
            background-color: rgba(var(--info-rgb), 0.1);
            color: var(--info);
        }

        .badge-warning {
            background-color: rgba(var(--warning-rgb), 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background-color: rgba(var(--danger-rgb), 0.1);
            color: var(--danger);
        }

        /* Dashboard Layout Styles */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: #f5f7fb;
            position: relative;
        }

        .dashboard-content {
            flex: 1;
            padding: 1.5rem;
            overflow: auto;
            margin-left: 280px;
            transition: margin 0.3s ease;
            max-width: 100%;
        }

        .dashboard-content.expanded {
            margin-left: 60px;
        }

        /* Dashboard Main Container - For consistent UI across sections */
        .dashboard-main-container {
            padding: 0.25rem 0.5rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Box Info (Stats Container) Styles */
        .box-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .box-info li {
            background: #fff;
            padding: 1.25rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1 1 calc(25% - 1.5rem);
            border: 1px solid var(--grey-2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .box-info li:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .box-info li i {
            width: 3.125rem;
            height: 3.125rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .box-info li:nth-child(1) i {
            background-color: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
        }

        .box-info li:nth-child(2) i {
            background-color: rgba(var(--secondary-rgb), 0.1);
            color: var(--secondary);
        }

        .box-info li:nth-child(3) i {
            background-color: rgba(var(--warning-rgb), 0.1);
            color: var(--warning);
        }

        .box-info li:nth-child(4) i {
            background-color: rgba(var(--success-rgb), 0.1);
            color: var(--success);
        }

        .box-info li .text h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .box-info li .text p {
            color: var(--dark-grey);
            margin-bottom: 0;
        }

        .box-info li .text .growth {
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.25rem;
            display: inline-block;
        }

        .box-info li .text .growth.positive {
            color: var (--success);
        }

        .box-info li .text .growth.negative {
            color: var(--danger);
        }

        /* Breadcrumb Styles */
        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .head-title .left h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .head-title .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0;
            margin: 0;
            background: transparent;
            list-style: none;
        }

        .head-title .breadcrumb li {
            font-size: 0.875rem;
            color: var(--dark-grey);
            display: flex;
            align-items: center;
        }

        .head-title .breadcrumb li a {
            color: var(--dark-grey);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .head-title .breadcrumb li a:hover,
        .head-title .breadcrumb li a.active {
            color: var(--primary);
        }

        .head-title .btn-download {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border-radius: 0.375rem;
            border: none;
            outline: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .head-title .btn-download:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
        }

        .alert-success {
            color: var(--success);
            background-color: rgba(var(--success-rgb), 0.1);
            border-color: rgba(var(--success-rgb), 0.2);
        }

        .alert-warning {
            color: var(--warning);
            background-color: rgba(var(--warning-rgb), 0.1);
            border-color: rgba(var(--warning-rgb), 0.2);
        }

        .alert-danger {
            color: var(--danger);
            background-color: rgba(var(--danger-rgb), 0.1);
            border-color: rgba(var(--danger-rgb), 0.2);
        }

        .alert-info {
            color: var(--info);
            background-color: rgba(var(--info-rgb), 0.1);
            border-color: rgba(var(--info-rgb), 0.2);
        }

        /* Loader */
        .dashboard-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .dashboard-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-spinner {
            width: 3rem;
            height: 3rem;
            border: 0.25rem solid rgba(var(--primary-rgb), 0.2);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
        }

        .tooltip:before {
            content: attr(data-tooltip);
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border-radius: 0.25rem;
            white-space: nowrap;
            font-size: 0.75rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            z-index: 100;
        }

        .tooltip:after {
            content: '';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            z-index: 100;
        }

        .tooltip:hover:before,
        .tooltip:hover:after {
            opacity: 1;
            visibility: visible;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            box-shadow: var(--shadow-md);
            z-index: 99;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--primary-hover);
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            color: var(--dark);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: var(--shadow);
            border: 1px solid var(--grey-2);
            z-index: 99;
            transition: var(--transition);
        }

        .dark-mode-toggle:hover {
            transform: rotate(15deg);
        }

        /* Responsive Styles */
        @media screen and (max-width: 1400px) {
            .box-info li {
                flex: 1 1 calc(50% - 1.5rem);
            }
        }

        @media screen and (max-width: 992px) {
            .dashboard-content {
                margin-left: 60px;
                padding: 1rem;
            }

            .dashboard-content.expanded {
                margin-left: 0;
            }

            .head-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .head-title .right {
                margin-top: 1rem;
                align-self: flex-start;
            }
        }

        @media screen and (max-width: 768px) {
            .box-info li {
                flex: 1 1 100%;
            }

            .dashboard-content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 576px) {
            .dashboard-content {
                padding: 0.75rem;
            }

            .head-title .left h1 {
                font-size: 1.5rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark-mode .dashboard-container {
            background-color: #121212;
        }

        body.dark-mode .dashboard-content {
            background-color: #121212;
        }

        body.dark-mode .card {
            background-color: #1e1e1e;
            border-color: #2a2a2a;
        }

        body.dark-mode .card-header {
            border-bottom-color: #2a2a2a;
        }

        body.dark-mode .card-footer {
            background-color: rgba(255, 255, 255, 0.05);
            border-top-color: #2a2a2a;
        }

        body.dark-mode .box-info li {
            background-color: #1e1e1e;
            border-color: #2a2a2a;
        }

        body.dark-mode .box-info li .text h3 {
            color: #e0e0e0;
        }

        body.dark-mode .head-title .left h1 {
            color: #e0e0e0;
        }

        body.dark-mode .breadcrumb li,
        body.dark-mode .breadcrumb li a {
            color: #a0a0a0;
        }

        body.dark-mode .btn-outline {
            border-color: #2a2a2a;
            color: #e0e0e0;
        }

        body.dark-mode .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        body.dark-mode .dashboard-table th {
            border-bottom-color: #2a2a2a;
            color: #a0a0a0;
        }

        body.dark-mode .dashboard-table td {
            border-bottom-color: #2a2a2a;
        }

        body.dark-mode .dashboard-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        body.dark-mode .dark-mode-toggle {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #2a2a2a;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-content>* {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Print Styles */
        @media print {

            .sidebar,
            .back-to-top,
            .dark-mode-toggle {
                display: none !important;
            }

            .dashboard-content {
                margin: 0 !important;
                padding: 0 !important;
            }

            @page {
                size: A4;
                margin: 0.5cm;
            }

            body {
                font-size: 12pt;
            }

            a {
                text-decoration: none !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- Add CSRF token meta tag for form security -->
    <?php if (function_exists('csrf_token')): ?>
        <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <?php elseif (isset($_SESSION['csrf_token'])): ?>
        <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <?php else: ?>
        <?php
        // Generate a CSRF token if it doesn't exist
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        ?>
        <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <?php endif; ?>
</head>

<body>
    <!-- Page Loader -->
    <div class="dashboard-loader" id="dashboardLoader">
        <div class="loader-spinner"></div>
    </div>

    <div class="dashboard-container">
        <!-- Include sidebar -->
        <?php include APP_ROOT . '/views/dashboard/sidebar.php'; ?>

        <!-- Dashboard Content -->
        <div class="dashboard-content" id="dashboardContent">
            <!-- Load the content passed from the controller -->
            <?php echo isset($content) ? $content : '<div class="alert alert-warning">No content available</div>'; ?>
        </div>
    </div>

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class='bx bx-up-arrow-alt'></i>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="dark-mode-toggle" id="darkModeToggle">
        <i class='bx bx-moon'></i>
    </div>

    <!-- Add BASE_URL before dashboard.js -->
    <script>
        // Define BASE_URL for API endpoints
        const BASE_URL = "<?php echo URL_ROOT; ?>";
    </script>

    <!-- Scripts -->
    <script src="<?php echo URL_ROOT; ?>/public/js/dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader once page is loaded
            const loader = document.getElementById('dashboardLoader');
            if (loader) {
                setTimeout(() => {
                    loader.classList.add('hidden');
                    setTimeout(() => loader.style.display = 'none', 300);
                }, 500);
            }

            // Back to top button functionality
            const backToTopBtn = document.getElementById('backToTop');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopBtn.classList.add('visible');
                    } else {
                        backToTopBtn.classList.remove('visible');
                    }
                });

                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Dark mode toggle functionality
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;
            const isDarkMode = localStorage.getItem('darkMode') === 'true';

            // Set initial state based on localStorage
            if (isDarkMode) {
                body.classList.add('dark-mode');
                if (darkModeToggle) {
                    darkModeToggle.innerHTML = '<i class="bx bx-sun"></i>';
                }
            }

            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    const isDark = body.classList.contains('dark-mode');
                    localStorage.setItem('darkMode', isDark);

                    // Update icon
                    darkModeToggle.innerHTML = isDark ?
                        '<i class="bx bx-sun"></i>' :
                        '<i class="bx bx-moon"></i>';

                    // Notify charts to update if they exist
                    if (typeof updateChartsTheme === 'function') {
                        updateChartsTheme(isDark);
                    }
                });
            }

            // Handle sidebar toggle state and update content margin
            const sidebar = document.getElementById('sidebar');
            const dashboardContent = document.getElementById('dashboardContent');

            // Function to update sidebar state
            function updateSidebarState() {
                if (sidebar && dashboardContent) {
                    if (sidebar.classList.contains('hide')) {
                        dashboardContent.classList.add('expanded');
                    } else {
                        dashboardContent.classList.remove('expanded');
                    }
                }
            }

            // Initial update
            updateSidebarState();

            // Listen for sidebar toggle events
            document.addEventListener('sidebarToggle', updateSidebarState);

            // Add observer to detect sidebar class changes
            if (sidebar) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            updateSidebarState();
                        }
                    });
                });

                observer.observe(sidebar, {
                    attributes: true
                });
            }

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(tooltip => {
                tooltip.classList.add('tooltip');
            });
        });

        // Function to update chart themes for dark mode
        function updateChartsTheme(isDark) {
            const chartInstances = Object.values(Chart.instances);
            if (chartInstances.length > 0) {
                chartInstances.forEach(chart => {
                    // Update grid colors
                    if (chart.options.scales && chart.options.scales.y) {
                        chart.options.scales.y.grid.color = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
                        chart.options.scales.y.ticks.color = isDark ? '#a0a0a0' : '#9ca3af';
                    }

                    if (chart.options.scales && chart.options.scales.x) {
                        chart.options.scales.x.ticks.color = isDark ? '#a0a0a0' : '#9ca3af';
                    }

                    // Update tooltip colors
                    if (chart.options.plugins && chart.options.plugins.tooltip) {
                        chart.options.plugins.tooltip.backgroundColor = isDark ? '#2a2a2a' : '#ffffff';
                        chart.options.plugins.tooltip.titleColor = isDark ? '#e0e0e0' : '#1f2937';
                        chart.options.plugins.tooltip.bodyColor = isDark ? '#e0e0e0' : '#1f2937';
                        chart.options.plugins.tooltip.borderColor = isDark ? '#3a3a3a' : '#e5e7eb';
                    }

                    chart.update();
                });
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>