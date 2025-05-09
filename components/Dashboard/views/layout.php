<?php
/**
 * Main layout template for Dashboard
 */
$pageContent = $pageContent ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?php echo htmlspecialchars($initialTheme ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LenSi</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
    /* Root CSS Variables */
    :root {
        --primary: #3E5C76;
        --primary-rgb: 62, 92, 118;
        --secondary: #748CAB;
        --accent: #1D2D44;
        --accent-dark: #0D1B2A;
        --light: #F9F7F0;
        --light-gray: #f5f7fa;
        --dark: #0D1B2A;
        --font-primary: 'Montserrat', sans-serif;
        --font-secondary: 'Inter', sans-serif;
        --font-heading: 'Poppins', sans-serif;
        --transition-default: all 0.3s ease;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.1);
        --shadow-md: 0 5px 15px rgba(0,0,0,0.07);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --sidebar-width: 280px;
        --topbar-height: 70px;
    }
    
    [data-bs-theme="dark"] {
        --light: #121212;
        --dark: #F9F7F0;
        --accent: #A4C2E5;
        --accent-dark: #171821;
        --primary: #5D8BB3;
        --primary-rgb: 93, 139, 179;
        --secondary: #8FB3DE;
        --light-gray: #1a1c24;
    }
    
    body {
        font-family: var(--font-secondary);
        background-color: var(--light-gray);
        color: var(--accent);
        min-height: 100vh;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    
    /* Layout */
    .dashboard-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
        position: relative;
    }
    
    /* Sidebar */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: white;
        box-shadow: var(--shadow-md);
        z-index: 1030;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    [data-bs-theme="dark"] .sidebar {
        background-color: var(--accent-dark);
    }
    
    .sidebar-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    [data-bs-theme="dark"] .sidebar-header {
        border-bottom-color: rgba(255,255,255,0.05);
    }
    
    .sidebar-brand {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--primary);
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    
    .sidebar-brand:hover {
        color: var(--accent);
    }
    
    [data-bs-theme="dark"] .sidebar-brand {
        color: var(--secondary);
    }
    
    [data-bs-theme="dark"] .sidebar-brand:hover {
        color: var(--accent);
    }
    
    .sidebar-logo {
        height: 30px;
        margin-right: 10px;
    }
    
    .sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-menu-item {
        margin-bottom: 0.25rem;
    }
    
    .sidebar-menu-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: var(--accent);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .sidebar-menu-link:hover, .sidebar-menu-link.active {
        background-color: rgba(var(--primary-rgb), 0.05);
        color: var(--primary);
        border-left-color: var(--primary);
    }
    
    [data-bs-theme="dark"] .sidebar-menu-link:hover, 
    [data-bs-theme="dark"] .sidebar-menu-link.active {
        background-color: rgba(143, 179, 222, 0.1);
        color: var (--secondary);
        border-left-color: var(--secondary);
    }
    
    .sidebar-menu-icon {
        font-size: 1.2rem;
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .sidebar-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    [data-bs-theme="dark"] .sidebar-footer {
        border-top-color: rgba(255,255,255,0.05);
    }
    
    .sidebar-toggle-btn {
        display: none;
        position: fixed;
        bottom: 20px;
        left: 20px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        border: none;
        box-shadow: var(--shadow-md);
        z-index: 1040;
        font-size: 1.2rem;
    }
    
    /* Navbar styles */
    .navbar {
        height: var(--topbar-height);
        background-color: white;
        box-shadow: var(--shadow-sm);
        padding: 0 1.5rem;
        position: sticky;
        top: 0;
        z-index: 1020;
    }
    
    [data-bs-theme="dark"] .navbar {
        background-color: var(--accent-dark);
    }
    
    .page-title {
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.4rem;
        color: var(--accent-dark);
        margin: 0;
    }
    
    [data-bs-theme="dark"] .page-title {
        color: var(--light);
    }
    
    .menu-toggle {
        display: none;
        color: var(--accent);
        padding: 0.5rem;
        margin-right: 1rem;
    }
    
    .menu-toggle:hover {
        color: var(--primary);
    }
    
    [data-bs-theme="dark"] .menu-toggle:hover {
        color: var(--secondary);
    }
    
    .notification-badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .navbar .dropdown-menu {
        border: none;
        box-shadow: var(--shadow-md);
        border-radius: var(--radius-sm);
    }
    
    [data-bs-theme="dark"] .navbar .dropdown-menu {
        background-color: var(--accent-dark);
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .navbar .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    [data-bs-theme="dark"] .navbar .dropdown-item {
        color: var(--light);
    }
    
    .navbar .dropdown-item:hover {
        background-color: var(--light-gray);
    }
    
    [data-bs-theme="dark"] .navbar .dropdown-item:hover {
        background-color: rgba(255,255,255,0.05);
    }
    
    .navbar .dropdown-divider {
        border-top-color: rgba(0,0,0,0.05);
    }
    
    [data-bs-theme="dark"] .navbar .dropdown-divider {
        border-top-color: rgba(255,255,255,0.05);
    }
    
    /* Content Area */
    .content {
        flex: 1;
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
        min-height: 100vh;
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    
    /* Main Content Area */
    .main-content {
        padding: 1.5rem;
        min-height: calc(100vh - var(--topbar-height));
    }
    
    .welcome-section {
        background-color: white;
        border-radius: var(--radius-md);
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }
    
    [data-bs-theme="dark"] .welcome-section {
        background-color: var(--accent-dark);
    }
    
    .welcome-title {
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--accent-dark);
        margin-bottom: 0.5rem;
    }
    
    [data-bs-theme="dark"] .welcome-title {
        color: var(--light);
    }
    
    .welcome-subtitle {
        color: var(--accent);
        margin-bottom: 1rem;
    }
    
    /* Dashboard Cards */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background-color: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .stat-card {
        background-color: var(--accent-dark);
    }
    
    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon.blue {
        background-color: rgba(var(--primary-rgb), 0.1);
        color: var(--primary);
    }
    
    .stat-icon.green {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .stat-icon.orange {
        background-color: rgba(255, 153, 0, 0.1);
        color: #ff9900;
    }
    
    .stat-icon.purple {
        background-color: rgba(137, 80, 252, 0.1);
        color: #8950fc;
    }
    
    .stat-title {
        font-family: var(--font-primary);
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--accent);
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.8rem;
        color: var(--accent-dark);
    }
    
    [data-bs-theme="dark"] .stat-value {
        color: var(--light);
    }
    
    .stat-change {
        color: #198754;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }
    
    .stat-change.negative {
        color: #dc3545;
    }
    
    /* Dashboard Tables */
    .dashboard-table-section {
        background-color: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }
    
    [data-bs-theme="dark"] .dashboard-table-section {
        background-color: var(--accent-dark);
    }
    
    .dashboard-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .dashboard-table-title {
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--accent-dark);
    }
    
    [data-bs-theme="dark"] .dashboard-table-title {
        color: var(--light);
    }
    
    .dashboard-table-action {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    [data-bs-theme="dark"] .dashboard-table-action {
        color: var(--secondary);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    [data-bs-theme="dark"] .table {
        color: var(--light);
    }
    
    .table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--accent);
        border-bottom-width: 1px;
    }
    
    .table tbody td {
        vertical-align: middle;
        padding: 0.75rem;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.expanded {
            transform: translateX(0);
        }
        
        .content {
            margin-left: 0;
            width: 100%;
        }
        
        .menu-toggle {
            display: block;
        }
        
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-stats {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
    
    @media (max-width: 576px) {
        .main-content {
            padding: 1rem;
        }
        
        .welcome-section,
        .dashboard-table-section {
            padding: 1.25rem;
        }
        
        .dashboard-stats {
            grid-template-columns: 1fr;
        }
        
        .welcome-title {
            font-size: 1.3rem;
        }
        
        .dashboard-table-section {
            overflow-x: auto;
        }
    }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Include Sidebar -->
        <?php require_once __DIR__ . '/components/sidebar.php'; ?>
        
        <!-- Content Area -->
        <div class="content" id="content">
            <!-- Include Navbar -->
            <?php require_once __DIR__ . '/components/navbar.php'; ?>
            
            <!-- Main Content -->
            <main class="main-content">
                <?php echo $pageContent; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (menuToggle && sidebar) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('expanded');
                });
            }
        });
    </script>
</body>
</html>