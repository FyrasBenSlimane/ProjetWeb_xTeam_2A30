<?php

/**
 * Admin Dashboard - Main Index File
 * This file serves as the base template for the admin dashboard
 * It includes the navbar and dynamically loads content based on user selection
 */

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
    redirect('users/login');
}

// Get the active page (default to 'dashboard')
$activePage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// List of valid pages
$validPages = ['dashboard', 'users', 'services', 'orders', 'settings'];

// Validate requested page
if (!in_array($activePage, $validPages)) {
    $activePage = 'dashboard'; // Default to dashboard if invalid
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar" class="<?php echo isset($_COOKIE['sidebar_collapsed']) && $_COOKIE['sidebar_collapsed'] === 'true' ? 'hide' : ''; ?>">
        <a href="<?php echo URL_ROOT; ?>/pages/admin" class="brand">
            <i class='bx bxs-dashboard bx-lg'></i>
            <span class="text"><?php echo SITE_NAME; ?> Admin</span>
        </a>
        <ul class="side-menu top">
            <li class="<?php echo ($activePage == 'dashboard') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/pages/admin?page=dashboard">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo ($activePage == 'users') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/pages/admin?page=users">
                    <i class='bx bxs-group bx-sm'></i>
                    <span class="text">Users</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu bottom">
            <li class="<?php echo ($activePage == 'settings') ? 'active' : ''; ?>">
                <a href="<?php echo URL_ROOT; ?>/pages/admin?page=settings">
                    <i class='bx bxs-cog bx-sm bx-spin-hover'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="<?php echo URL_ROOT; ?>/users/logout" class="logout">
                    <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu bx-sm toggle-sidebar'></i>
            <a href="#" class="nav-link">
                <?php
                // Dynamic page title
                $pageTitle = ucfirst($activePage);
                echo $pageTitle;
                ?>
            </a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>

            <input type="checkbox" class="checkbox" id="switch-mode" hidden />
            <label class="switch-mode" for="switch-mode">
                <i class="bx bxs-moon"></i>
                <i class="bx bx-sun"></i>
                <div class="ball"></div>
            </label>

            <!-- Notification Bell -->
            <a href="#" class="notification" id="notificationIcon">
                <i class='bx bxs-bell bx-tada-hover'></i>
                <span class="num">3</span>
            </a>
            <div class="notification-menu" id="notificationMenu">
                <ul>
                    <li>New user registered</li>
                    <li>New order placed</li>
                    <li>System update available</li>
                </ul>
            </div>

            <!-- Profile Menu -->
            <a href="#" class="profile" id="profileIcon">
                <div class="profile-img">
                    <?php
                    $nameArray = explode(' ', $_SESSION['user_name']);
                    $initials = '';
                    if (isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                    if (isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                    ?>
                    <span><?php echo $initials; ?></span>
                </div>
            </a>
            <div class="profile-menu" id="profileMenu">
                <ul>
                    <li><a href="#">My Profile</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/pages/admin?page=settings">Settings</a></li>
                    <li><a href="<?php echo URL_ROOT; ?>/users/logout">Log Out</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <?php
            // Dynamically include the active page content
            $contentPath = APP_PATH . '/views/dashboard/' . $activePage . '.php';
            if (file_exists($contentPath)) {
                include($contentPath);
            } else {
                echo '<div class="error-container">';
                echo '<i class="bx bx-error-circle"></i>';
                echo '<h2>Content Not Found</h2>';
                echo '<p>The requested page could not be found. Please select another option from the menu.</p>';
                echo '</div>';
            }
            ?>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Dashboard Styles -->
    <style>
        /* Google Fonts Import */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Root Variables */
        :root {
            --poppins: 'Poppins', sans-serif;
            --light: #F9F9F9;
            --blue: #3C91E6;
            --light-blue: #CFE8FF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
        }

        /* Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--poppins);
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        /* Dark Mode Styles */
        body.dark {
            --light: #0C0C1E;
            --grey: #060714;
            --dark: #FBFBFB;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100%;
            background: var(--light);
            z-index: 1000;
            transition: .3s ease;
            overflow-x: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar.hide {
            width: 60px;
        }

        #sidebar .brand {
            font-size: 24px;
            font-weight: 700;
            height: 56px;
            display: flex;
            align-items: center;
            color: var(--blue);
            position: sticky;
            top: 0;
            left: 0;
            background: var(--light);
            z-index: 500;
            padding: 0 16px;
            box-sizing: content-box;
        }

        #sidebar .brand .bx {
            min-width: 60px;
            display: flex;
            justify-content: center;
        }

        #sidebar .side-menu {
            width: 100%;
            margin-top: 48px;
        }

        #sidebar .side-menu li {
            height: 48px;
            background: transparent;
            margin-left: 6px;
            border-radius: 48px 0 0 48px;
            padding: 4px;
        }

        #sidebar .side-menu li.active {
            background: var(--grey);
            position: relative;
        }

        #sidebar .side-menu li.active::before {
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

        #sidebar .side-menu li.active::after {
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
            padding: 0 12px;
        }

        #sidebar .side-menu.top li.active a {
            color: var(--blue);
        }

        #sidebar.hide .side-menu li a {
            width: calc(48px - (4px * 2));
            transition: width .3s ease;
        }

        #sidebar .side-menu li a.logout {
            color: var(--red);
        }

        #sidebar .side-menu.top li a:hover {
            color: var(--blue);
        }

        #sidebar .side-menu li a .bx {
            min-width: calc(60px - ((4px + 6px) * 2));
            display: flex;
            justify-content: center;
        }

        /* Bottom menu positioning */
        #sidebar .side-menu.bottom {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - 260px);
            left: 260px;
            transition: .3s ease;
        }

        #sidebar.hide~#content {
            width: calc(100% - 60px);
            left: 60px;
        }

        /* NAVBAR */
        #content nav {
            height: 56px;
            background: var(--light);
            padding: 0 24px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 100;
        }

        #content nav::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            bottom: -40px;
            left: 0;
            border-radius: 50%;
            box-shadow: -20px -20px 0 var(--light);
        }

        #content nav a {
            color: var(--dark);
        }

        #content nav .toggle-sidebar {
            cursor: pointer;
            color: var(--dark);
        }

        #content nav .nav-link {
            font-size: 16px;
            transition: .3s ease;
        }

        #content nav .nav-link:hover {
            color: var(--blue);
        }

        #content nav form {
            max-width: 400px;
            width: 100%;
            margin-right: auto;
        }

        #content nav form .form-input {
            display: flex;
            align-items: center;
            height: 36px;
        }

        #content nav form .form-input input {
            flex-grow: 1;
            padding: 0 16px;
            height: 100%;
            border: none;
            background: var(--grey);
            border-radius: 36px 0 0 36px;
            outline: none;
            width: 100%;
            color: var(--dark);
        }

        #content nav form .form-input button {
            width: 36px;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--blue);
            color: var(--light);
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 0 36px 36px 0;
            cursor: pointer;
        }

        #content nav .notification {
            font-size: 20px;
            position: relative;
        }

        #content nav .notification .num {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--light);
            background: var(--red);
            color: var(--light);
            font-weight: 700;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Notification Menu Styles */
        #content nav .notification-menu {
            display: none;
            position: absolute;
            top: 56px;
            right: 0;
            background: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 250px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 100;
        }

        #content nav .notification-menu ul {
            list-style: none;
            padding: 10px;
            margin: 0;
        }

        #content nav .notification-menu li {
            padding: 10px;
            border-bottom: 1px solid var(--grey);
            color: var (--dark);
        }

        #content nav .notification-menu li:hover {
            background-color: var(--light-blue);
            color: var(--dark);
        }

        /* Profile Styles */
        #content nav .profile {
            position: relative;
        }

        #content nav .profile-img {
            width: 36px;
            height: 36px;
            background-color: var(--blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light);
            font-weight: 600;
        }

        #content nav .profile-menu {
            display: none;
            position: absolute;
            top: 56px;
            right: 0;
            background: var(--light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 200px;
            z-index: 100;
        }

        #content nav .profile-menu ul {
            list-style: none;
            padding: 10px;
            margin: 0;
        }

        #content nav .profile-menu li {
            padding: 10px;
            border-bottom: 1px solid var (--grey);
        }

        #content nav .profile-menu li:hover {
            background-color: var(--light-blue);
            color: var(--dark);
        }

        #content nav .profile-menu li a {
            color: var(--dark);
            font-size: 14px;
            display: block;
        }

        /* Active State for Menus */
        #content nav .notification-menu.show,
        #content nav .profile-menu.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dark Mode Switch */
        #content nav .switch-mode {
            display: block;
            min-width: 50px;
            height: 25px;
            border-radius: 25px;
            background: var(--grey);
            cursor: pointer;
            position: relative;
            margin: 0 10px;
        }

        #content nav .switch-mode i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
        }

        #content nav .switch-mode i.bxs-moon {
            left: 5px;
            color: var(--dark);
            display: none;
        }

        #content nav .switch-mode i.bx-sun {
            right: 5px;
            color: var(--yellow);
        }

        #content nav .switch-mode .ball {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 21px;
            height: 21px;
            border-radius: 50%;
            background: var(--blue);
            transition: transform 0.3s ease;
        }

        body.dark #content nav .switch-mode .ball {
            transform: translateX(25px);
        }

        body.dark #content nav .switch-mode i.bxs-moon {
            display: block;
        }

        body.dark #content nav .switch-mode i.bx-sun {
            display: none;
        }

        /* MAIN */
        #content main {
            width: 100%;
            padding: 36px 24px;
            max-height: calc(100vh - 56px);
            overflow-y: auto;
        }

        /* Error Container */
        #content main .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 20px;
            background: var(--light);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #content main .error-container i {
            font-size: 80px;
            color: var(--red);
            margin-bottom: 20px;
        }

        #content main .error-container h2 {
            font-size: 24px;
            color: var(--dark);
            margin-bottom: 10px;
        }

        #content main .error-container p {
            color: var(--dark-grey);
            max-width: 600px;
        }

        /* Responsive Adjustments */
        @media screen and (max-width: 768px) {
            #sidebar {
                width: 60px;
            }

            #sidebar.hide {
                width: 0;
                padding: 0;
            }

            #sidebar .brand .text {
                display: none;
            }

            #content {
                width: calc(100% - 60px);
                left: 60px;
            }

            #content.sidebar-hidden {
                width: 100%;
                left: 0;
            }

            #content nav form {
                display: none;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav form {
                display: none;
            }

            #content nav .notification-menu,
            #content nav .profile-menu {
                width: 200px;
                right: -20px;
            }

            #content nav .nav-link {
                display: none;
            }
        }
    </style>

    <!-- Dashboard Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const toggleSidebar = document.querySelector('.toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            toggleSidebar.addEventListener('click', function() {
                sidebar.classList.toggle('hide');

                // Store sidebar state in cookie
                if (sidebar.classList.contains('hide')) {
                    document.cookie = "sidebar_collapsed=true; path=/";
                } else {
                    document.cookie = "sidebar_collapsed=false; path=/";
                }
            });

            // Notification Menu Toggle
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationMenu = document.getElementById('notificationMenu');

            notificationIcon.addEventListener('click', function(e) {
                e.preventDefault();
                notificationMenu.classList.toggle('show');
                profileMenu.classList.remove('show'); // Close profile menu if open
            });

            // Profile Menu Toggle
            const profileIcon = document.getElementById('profileIcon');
            const profileMenu = document.getElementById('profileMenu');

            profileIcon.addEventListener('click', function(e) {
                e.preventDefault();
                profileMenu.classList.toggle('show');
                notificationMenu.classList.remove('show'); // Close notification menu if open
            });

            // Close menus when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.notification') && !e.target.closest('.notification-menu')) {
                    notificationMenu.classList.remove('show');
                }

                if (!e.target.closest('.profile') && !e.target.closest('.profile-menu')) {
                    profileMenu.classList.remove('show');
                }
            });

            // Dark Mode Toggle
            const switchMode = document.getElementById('switch-mode');

            switchMode.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    document.body.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                }
            });

            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark');
                switchMode.checked = true;
            }

            // Adjust sidebar for small screens on initial load
            function handleResize() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('hide');
                }
            }

            // Run on page load
            handleResize();

            // Also run on window resize
            window.addEventListener('resize', handleResize);
        });
    </script>
</body>

</html>

<?php require APPROOT . '/views/layouts/dashboard.php'; ?>

<div class="dashboard-content">
    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>
            <div class="btn-download">
                <a href="#" class="btn">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download Report</span>
                </a>
            </div>
        </div>

        <ul class="box-info">
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                    <h3><?php echo $data['analytics']['totalUsers'] ?? 0; ?></h3>
                    <p>Total Users</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-user-check'></i>
                <span class="text">
                    <h3><?php echo $data['analytics']['activeUsers'] ?? 0; ?></h3>
                    <p>Active Users</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-bar-chart-alt-2'></i>
                <span class="text">
                    <h3><?php echo $data['analytics']['todayVisits'] ?? 0; ?></h3>
                    <p>Today's Visits</p>
                    <?php if (isset($data['analytics']['visitsGrowth'])): ?>
                        <span class="growth <?php echo $data['analytics']['visitsGrowth'] >= 0 ? 'positive' : 'negative'; ?>">
                            <?php echo $data['analytics']['visitsGrowth'] >= 0 ? '+' : ''; ?><?php echo $data['analytics']['visitsGrowth']; ?>%
                        </span>
                    <?php endif; ?>
                </span>
            </li>
        </ul>

        <div class="dashboard-cards">
            <div class="card visitors-chart">
                <div class="card-header">
                    <h3>Website Visitors</h3>
                    <div class="chart-controls">
                        <button class="period-btn active" data-period="week">Week</button>
                        <button class="period-btn" data-period="month">Month</button>
                        <button class="period-btn" data-period="year">Year</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card user-distribution">
                <div class="card-header">
                    <h3>User Distribution</h3>
                </div>
                <div class="card-body">
                    <div class="pie-chart-container">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-tables">
            <div class="table recent-users">
                <div class="table-header">
                    <h3>Recent Users</h3>
                    <a href="<?php echo URL_ROOT; ?>/dashboard/users" class="btn view-all-btn">
                        <span class="text">View All</span>
                    </a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recentUsers'])): ?>
                            <?php foreach ($data['recentUsers'] as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><span class="role <?php echo strtolower($user['role']); ?>"><?php echo $user['role']; ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($user['registeredDate'])); ?></td>
                                    <td><span class="status <?php echo strtolower($user['status']); ?>"><?php echo $user['status']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table activity-log">
                <div class="table-header">
                    <h3>Recent Activity</h3>
                </div>
                <ul class="activity-list">
                    <?php if (!empty($data['activityLogs'])): ?>
                        <?php foreach ($data['activityLogs'] as $log): ?>
                            <li>
                                <i class='bx bx-checkbox-square activity-icon'></i>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        <span class="user"><?php echo htmlspecialchars($log['userName']); ?></span>
                                        <span class="action"><?php echo htmlspecialchars($log['action']); ?></span>
                                        <span class="module"><?php echo htmlspecialchars($log['module']); ?></span>
                                    </div>
                                    <span class="time"><?php echo $log['formattedDate']; ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-data">No activity found</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </main>
    <!-- MAIN -->
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Visitors Chart
        const visitorsChartCtx = document.getElementById('visitorsChart').getContext('2d');
        const visitorsChartData = <?php echo json_encode($data['visitChartData']); ?>;

        const visitorsChart = new Chart(visitorsChartCtx, {
            type: 'line',
            data: {
                labels: visitorsChartData.map(item => item.day),
                datasets: [{
                    label: 'Visits',
                    data: visitorsChartData.map(item => item.value),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Period buttons event listeners
        document.querySelectorAll('.period-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const period = this.getAttribute('data-period');

                // Remove active class from all buttons
                document.querySelectorAll('.period-btn').forEach(function(b) {
                    b.classList.remove('active');
                });

                // Add active class to clicked button
                this.classList.add('active');

                // Fetch new data via AJAX
                fetch(`${window.location.origin}<?php echo URL_ROOT; ?>/dashboard/getChartData?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update chart data
                        visitorsChart.data.labels = data.map(item => item.day);
                        visitorsChart.data.datasets[0].data = data.map(item => item.value);
                        visitorsChart.update();
                    });
            });
        });

        // User Distribution Chart
        const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
        const distributionData = <?php echo json_encode($data['userDistribution']); ?>;

        // Generate colors for each role
        const backgroundColors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 205, 86, 0.7)',
            'rgba(153, 102, 255, 0.7)'
        ];

        const userDistributionChart = new Chart(userDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: distributionData.map(item => item.role),
                datasets: [{
                    data: distributionData.map(item => item.count),
                    backgroundColor: backgroundColors.slice(0, distributionData.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '60%'
            }
        });
    });
</script>

<style>
    :root {
        --primary: #3C91E6;
        --light: #F9F9F9;
        --grey: #eee;
        --dark: #342E37;
        --danger: #DB504A;
        --success: #4CAF50;
        --warning: #FFC107;
        --font-family: 'Nunito', sans-serif;
    }

    .dashboard-content {
        position: relative;
        width: 100%;
        transition: .3s ease;
    }

    main {
        padding: 24px;
    }

    main .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        grid-gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    main .head-title .left h1 {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }

    main .head-title .left .breadcrumb {
        display: flex;
        align-items: center;
        grid-gap: 16px;
        list-style: none;
    }

    main .head-title .left .breadcrumb li {
        color: var(--dark);
    }

    main .head-title .left .breadcrumb li a {
        color: var(--dark);
        pointer-events: none;
    }

    main .head-title .left .breadcrumb li a.active {
        color: var(--primary);
        pointer-events: unset;
    }

    main .head-title .btn-download {
        height: 36px;
        padding: 0 16px;
        border-radius: 36px;
        background: var(--primary);
        color: var(--light);
        display: flex;
        justify-content: center;
        align-items: center;
        grid-gap: 10px;
        font-weight: 500;
        text-decoration: none;
    }

    main .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        grid-gap: 24px;
        margin-top: 36px;
        margin-bottom: 24px;
        padding: 0;
        list-style-type: none;
    }

    main .box-info li {
        padding: 24px;
        background: var(--light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        grid-gap: 24px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    main .box-info li i {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        font-size: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }

    main .box-info li:nth-child(1) i {
        background: var(--primary);
    }

    main .box-info li:nth-child(2) i {
        background: var(--success);
    }

    main .box-info li:nth-child(3) i {
        background: var(--warning);
    }

    main .box-info li .text h3 {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    main .box-info li .text p {
        color: var(--dark);
        font-size: 14px;
    }

    main .box-info li .text .growth {
        font-size: 12px;
        font-weight: 500;
        margin-top: 5px;
        display: inline-block;
    }

    main .box-info li .text .growth.positive {
        color: var(--success);
    }

    main .box-info li .text .growth.negative {
        color: var(--danger);
    }

    .dashboard-cards {
        display: grid;
        grid-template-columns: 2fr 1fr;
        grid-gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card-header h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .chart-controls .period-btn {
        padding: 5px 10px;
        font-size: 14px;
        border: none;
        background: var(--grey);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .chart-controls .period-btn.active {
        background: var(--primary);
        color: white;
    }

    .chart-container {
        width: 100%;
        height: 300px;
    }

    .pie-chart-container {
        width: 100%;
        height: 300px;
        display: flex;
        justify-content: center;
    }

    .dashboard-tables {
        display: grid;
        grid-template-columns: 2fr 1fr;
        grid-gap: 24px;
    }

    .table {
        background: var(--light);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .table-header h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .view-all-btn {
        padding: 6px 12px;
        background: var(--primary);
        color: var(--light);
        border-radius: 5px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .view-all-btn:hover {
        background: #1B5FBE;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
        color: var(--dark);
        font-weight: 600;
        font-size: 14px;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid var(--grey);
        font-size: 14px;
    }

    table .role,
    table .status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    table .role.admin {
        background: rgba(54, 162, 235, 0.2);
        color: rgba(54, 162, 235, 1);
    }

    table .role.freelancer {
        background: rgba(75, 192, 192, 0.2);
        color: rgba(75, 192, 192, 1);
    }

    table .role.client {
        background: rgba(255, 205, 86, 0.2);
        color: rgba(255, 205, 86, 1);
    }

    table .status.active {
        background: rgba(76, 175, 80, 0.2);
        color: rgba(76, 175, 80, 1);
    }

    table .status.inactive {
        background: rgba(219, 80, 74, 0.2);
        color: rgba(219, 80, 74, 1);
    }

    .activity-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .activity-list li {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid var(--grey);
    }

    .activity-list li:last-child {
        border-bottom: none;
    }

    .activity-icon {
        font-size: 20px;
        color: var(--primary);
        margin-right: 12px;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-size: 14px;
        margin-bottom: 5px;
    }

    .activity-title .user {
        font-weight: 600;
        color: var(--dark);
    }

    .activity-title .action {
        margin: 0 5px;
    }

    .activity-title .module {
        color: var(--primary);
    }

    .activity-content .time {
        font-size: 12px;
        color: #777;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #777;
    }

    @media screen and (max-width: 992px) {

        .dashboard-cards,
        .dashboard-tables {
            grid-template-columns: 1fr;
        }
    }
</style>