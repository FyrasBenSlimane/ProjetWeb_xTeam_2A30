<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="root-url" content="<?php echo URLROOT; ?>">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    
    <!-- Load Chart.js first in the head section -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        /* Layout */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            width: 100%;
        }
        
        .main-content {
            flex: 1;
            transition: margin-left 0.3s ease, width 0.3s ease;
            margin-left: 16rem; /* Default when sidebar is open */
            width: calc(100% - 16rem); /* Set initial width */
            position: relative;
        }
        
        .page-content {
            padding: 1.5rem;
            height: calc(100vh - 4rem);
            overflow: auto;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            width: 16rem; /* Default width */
            height: 100vh;
            background-color: #f8f9fa;
            border-right: 1px solid #e2e8f0;
            transition: width 0.3s ease;
            z-index: 40;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 5rem;
        }
        
        .sidebar-header {
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
        }
        
        .logo-icon {
            width: 2rem;
            height: 2rem;
            background-color: rgb(5, 11, 31);
            color: white;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .logo-text {
            margin-left: 0.5rem;
            font-weight: 600;
        }
        
        .sidebar-toggle {
            padding: 0.25rem;
            border-radius: 0.375rem;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #64748b;
        }
        
        .sidebar-toggle:hover {
            background-color: #f1f5f9;
        }
        
        .sidebar.collapsed .chevron-left {
            transform: rotate(180deg);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }
        
        .sidebar-nav ul {
            list-style: none;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 0.375rem;
            margin: 0.25rem 0.75rem;
            transition: background-color 0.2s;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
        }
        
        .nav-link:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        
        .nav-link.active {
            background-color:rgb(5, 11, 31);
            color: white;
        }
        
        .nav-link span {
            margin-left: 0.75rem;
        }
        
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .logout-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #ef4444;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        
        .sidebar.collapsed .logout-link {
            justify-content: center;
        }
        
        .logout-link:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }
        
        .logout-link span {
            margin-left: 0.75rem;
        }
        
        /* Header Styles */
        .dashboard-header {
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .header-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .icon-button {
            position: relative;
            padding: 0.375rem;
            background: transparent;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            color: #64748b;
        }
        
        .icon-button:hover {
            background-color: #f1f5f9;
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 0.625rem;
            height: 0.625rem;
            background-color: #ef4444;
            border-radius: 9999px;
        }
        
        .profile-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem;
            border: none;
            background: transparent;
            cursor: pointer;
        }
        
        .avatar {
            width: 2.25rem;
            height: 2.25rem;
            background-color: #e2e8f0;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }
        
        .user-info {
            display: none;
        }
        
        @media (min-width: 768px) {
            .user-info {
                display: block;
                text-align: left;
            }
        }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        /* Utility Classes */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .text-center { text-align: center; }
        .mt-4 { margin-top: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .p-4 { padding: 1rem; }
        .rounded { border-radius: 0.375rem; }
        .shadow { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        .bg-white { background-color: white; }
        
        /* Cards */
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1rem;
            border-bottom: 1px solid #eaeaea;
        }
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        .card-content {
            padding: 1rem;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            border: 1px solid transparent;
        }
        .btn-primary {
            background-color: rgb(5, 11, 31);
            color: white;
        }
        .btn-primary:hover {
            background-color: rgb(10, 20, 50);
        }
        .btn-outline {
            border-color: #d1d5db;
            background: transparent;
        }
        .btn-outline:hover {
            background-color: #f3f4f6;
        }
        
        /* Form Elements */
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }
        .table th {
            font-weight: 600;
            color: #6b7280;
            background-color: #f9fafb;
        }
        
        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-blue {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .badge-green {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .badge-yellow {
            background-color: #fef9c3;
            color: #ca8a04;
        }
        .badge-red {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .badge-gray {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        
        /* Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            min-width: 10rem;
            z-index: 1;
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .dropdown-content a {
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: block;
            color: #333;
        }
        .dropdown-content a:hover {
            background-color: #f3f4f6;
        }
        .dropdown.active .dropdown-content {
            display: block;
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
        
        .toast {
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1rem;
            margin-bottom: 0.75rem;
            max-width: 24rem;
            animation: slideIn 0.3s ease-out forwards;
        }
        
        .toast-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .toast-title {
            font-weight: 600;
        }
        
        .toast-close {
            background: transparent;
            border: none;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
            color: #9ca3af;
        }
        
        .toast-body {
            color: #6b7280;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-exit {
            animation: fadeOut 0.3s ease-in forwards;
        }
        
        /* Modal styles */
        .modal-dialog {
            max-width: 90%;
            max-height: 90vh;
            margin: 1.75rem auto;
        }
        
        .modal-content {
            max-height: 85vh;
            overflow: auto;
        }
        
        /* Chart styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 5rem;
            }
            
            .main-content {
                margin-left: 5rem;
            }
            
            .sidebar.collapsed {
                width: 0;
                overflow: hidden;
            }
            
            .sidebar.collapsed + .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php require_once 'sidebar.php'; ?>
        
        <div class="main-content" id="main-content">
            <!-- Header -->
            <?php require_once 'header.php'; ?>
            
            <!-- Page Content -->
            <main class="page-content">
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Load main JavaScript file -->
    <script src="<?php echo URLROOT; ?>/public/js/main.js"></script>
    
    <!-- Load dashboard JavaScript file -->
    <script src="<?php echo URLROOT; ?>/public/js/dashboard/dashboard.js"></script>
</body>
</html>