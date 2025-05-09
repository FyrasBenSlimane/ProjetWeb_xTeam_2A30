<?php

/**
 * Admin Dashboard - Main Dashboard Page
 * Modern redesigned dashboard with improved UI/UX
 */

// Use controller-provided data instead of hardcoded values
$analyticsData = $data['analyticsData'] ?? [];
$totalUsers = $analyticsData['totalUsers'] ?? 0;
$activeUsers = $analyticsData['activeUsers'] ?? 0;
$visitsGrowth = $analyticsData['visitsGrowth'] ?? 0;
$users = $data['users'] ?? [];
$recentUsers = array_slice($users, 0, 5); // Take only the first 5 users

// Get ticket statistics from data
$ticketStats = $data['ticketStats'] ?? [
    'open' => 0,
    'total' => 0
];
$openTickets = $ticketStats['open'] ?? 0;

// Get community statistics
$communityStats = $data['communityStats'] ?? [
    'topics' => 0,
    'groups' => 0,
    'resources' => 0
];

// Quick tasks - this could be loaded from a database in a real application
$quickTasks = $data['quickTasks'] ?? [
    ['id' => 1, 'title' => 'Review new service submissions', 'completed' => true],
    ['id' => 2, 'title' => 'Approve freelancer profiles', 'completed' => true],
    ['id' => 3, 'title' => 'Update service categories', 'completed' => false],
    ['id' => 4, 'title' => 'Review platform analytics', 'completed' => false],
    ['id' => 5, 'title' => 'Email newsletter to users', 'completed' => false],
];
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-header">
        <div class="welcome-text">
            <h1>Welcome back, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin'; ?>!</h1>
            <p class="subtitle">Here's what's happening with your platform today.</p>
        </div>
        <div class="welcome-actions">
            <a href="#" class="btn btn-primary">
                <i class='bx bxs-file-export'></i>
                <span>Export Report</span>
            </a>
        </div>
    </div>
</div>

<!-- Dashboard Overview Cards -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <p>Total Users</p>
                <h3><?php echo number_format($totalUsers); ?></h3>
                <p class="stat-change <?php echo $analyticsData['userGrowth'] ?? 0 >= 0 ? 'positive' : 'negative'; ?>">
                    <?php echo ($analyticsData['userGrowth'] ?? 0) >= 0 ? '+' : ''; ?><?php echo $analyticsData['userGrowth'] ?? 0; ?>%
                    <span>from last month</span>
                </p>
            </div>
            <div class="stat-card-icon users">
                <i class='bx bxs-user'></i>
            </div>
        </div>
        <div class="stat-card-footer">
            <a href="<?php echo URL_ROOT; ?>/dashboard/users">View all users</a>
            <i class='bx bx-right-arrow-alt'></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <p>Active Users</p>
                <h3><?php echo number_format($activeUsers); ?></h3>
                <p class="stat-change positive">
                    <?php echo round(($activeUsers / ($totalUsers ?: 1)) * 100); ?>% <span>of total users</span>
                </p>
            </div>
            <div class="stat-card-icon services">
                <i class='bx bxs-user-check'></i>
            </div>
        </div>
        <div class="stat-card-footer">
            <a href="<?php echo URL_ROOT; ?>/dashboard/users">View active users</a>
            <i class='bx bx-right-arrow-alt'></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <p>Support Tickets</p>
                <h3><?php echo number_format($ticketStats['total'] ?? 0); ?></h3>
                <p class="stat-change <?php echo ($openTickets > 0) ? 'negative' : 'positive'; ?>">
                    <?php echo $openTickets; ?> <span>open tickets</span>
                </p>
            </div>
            <div class="stat-card-icon orders">
                <i class='bx bxs-message-rounded-dots'></i>
            </div>
        </div>
        <div class="stat-card-footer">
            <a href="<?php echo URL_ROOT; ?>/dashboard/support">View all tickets</a>
            <i class='bx bx-right-arrow-alt'></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <p>Page Visits</p>
                <h3><?php echo number_format($analyticsData['todayVisits'] ?? 0); ?></h3>
                <p class="stat-change <?php echo ($visitsGrowth >= 0) ? 'positive' : 'negative'; ?>">
                    <?php echo ($visitsGrowth >= 0) ? '+' : ''; ?><?php echo $visitsGrowth; ?>% <span>from yesterday</span>
                </p>
            </div>
            <div class="stat-card-icon revenue">
                <i class='bx bxs-bar-chart-alt-2'></i>
            </div>
        </div>
        <div class="stat-card-footer">
            <a href="#">View analytics</a>
            <i class='bx bx-right-arrow-alt'></i>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <div class="card chart-card">
        <div class="card-header">
            <h2>User Growth</h2>
            <div class="chart-actions">
                <button class="chart-action-btn" data-period="week">Week</button>
                <button class="chart-action-btn active" data-period="month">Month</button>
                <button class="chart-action-btn" data-period="year">Year</button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card chart-card">
        <div class="card-header">
            <h2>Page Visits</h2>
            <div class="chart-actions">
                <button class="chart-action-btn" data-period="week">Week</button>
                <button class="chart-action-btn active" data-period="month">Month</button>
                <button class="chart-action-btn" data-period="year">Year</button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="visitsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="card feature-card support-overview">
        <div class="card-header">
            <h2><i class='bx bxs-help-circle'></i> Support Overview</h2>
            <a href="<?php echo URL_ROOT; ?>/dashboard/support" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <div class="feature-content">
                <div class="feature-stats">
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo $openTickets; ?></span>
                        <span class="stat-label">Open Tickets</span>
                    </div>
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo ($ticketStats['total'] ?? 0) - $openTickets; ?></span>
                        <span class="stat-label">Resolved</span>
                    </div>
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo $ticketStats['total'] ?? 0; ?></span>
                        <span class="stat-label">Total</span>
                    </div>
                </div>
                <div class="feature-action">
                    <a href="<?php echo URL_ROOT; ?>/dashboard/support" class="btn btn-primary btn-block">
                        <i class='bx bxs-message-square-detail'></i>
                        <span>Manage Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card feature-card community-overview">
        <div class="card-header">
            <h2><i class='bx bxs-chat'></i> Community Overview</h2>
            <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <div class="feature-content">
                <div class="feature-stats">
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo $communityStats['topics'] ?? 0; ?></span>
                        <span class="stat-label">Topics</span>
                    </div>
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo $communityStats['groups'] ?? 0; ?></span>
                        <span class="stat-label">Groups</span>
                    </div>
                    <div class="feature-stat-item">
                        <span class="stat-value"><?php echo $communityStats['resources'] ?? 0; ?></span>
                        <span class="stat-label">Resources</span>
                    </div>
                </div>
                <div class="feature-action">
                    <a href="<?php echo URL_ROOT; ?>/dashboard/community" class="btn btn-primary btn-block">
                        <i class='bx bxs-group'></i>
                        <span>Manage Community</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card feature-card quick-ttasks">
        <div class="card-header">
            <h2><i class='bx bxs-check-square'></i> Quick Tasks</h2>
            <button class="btn btn-sm btn-outline" id="add-task-btn">
                <i class='bx bx-plus'></i> Add Task
            </button>
        </div>
        <div class="card-body">
            <ul class="task-list">
                <?php foreach ($quickTasks as $task): ?>
                    <li class="task-item<?php echo $task['completed'] ? ' completed' : ''; ?>">
                        <div class="task-checkbox">
                            <input type="checkbox" id="task-<?php echo $task['id']; ?>" <?php echo $task['completed'] ? 'checked' : ''; ?>>
                            <label for="task-<?php echo $task['id']; ?>"></label>
                        </div>
                        <span class="task-text"><?php echo htmlspecialchars($task['title']); ?></span>
                        <div class="task-actions">
                            <button class="task-action-btn edit-task"><i class='bx bxs-edit'></i></button>
                            <button class="task-action-btn delete-task"><i class='bx bxs-trash'></i></button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Tables Section -->
<div class="tables-section">
    <div class="card table-card">
        <div class="card-header">
            <h2>Latest Registered Users</h2>
            <a href="<?php echo URL_ROOT; ?>/dashboard/users" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Account Type</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentUsers)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentUsers as $user): ?>
                                <tr>
                                    <td>
                                        <div class="table-user">
                                            <div class="user-avatar">
                                                <?php
                                                $initials = '';
                                                $name = is_array($user) ? ($user['name'] ?? '') : ($user->name ?? '');
                                                $nameParts = explode(' ', $name);
                                                if (count($nameParts) >= 2) {
                                                    $initials = substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1);
                                                } else {
                                                    $initials = substr($name, 0, 2);
                                                }
                                                echo strtoupper($initials);
                                                ?>
                                            </div>
                                            <div class="user-name"><?php echo htmlspecialchars($name); ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars(is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '')); ?></td>
                                    <td>
                                        <?php
                                        $accountType = is_array($user) ? ($user['account_type'] ?? $user['role'] ?? '') : ($user->account_type ?? $user->role ?? '');
                                        $badgeClass = $accountType === 'admin' ? 'danger' : ($accountType === 'client' ? 'primary' : 'success');
                                        ?>
                                        <span class="badge badge-<?php echo $badgeClass; ?>"><?php echo ucfirst(htmlspecialchars($accountType)); ?></span>
                                    </td>
                                    <td><?php echo is_array($user) ? ($user['created_at'] ?? $user['registeredDate'] ?? '') : ($user->created_at ?? $user->registeredDate ?? ''); ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/users/view/<?php echo is_array($user) ? ($user['id'] ?? '') : ($user->id ?? ''); ?>" class="action-btn"><i class='bx bxs-show'></i></a>
                                            <a href="<?php echo URL_ROOT; ?>/dashboard/users/edit/<?php echo is_array($user) ? ($user['id'] ?? '') : ($user->id ?? ''); ?>" class="action-btn"><i class='bx bxs-edit'></i></a>
                                            <a href="#" class="action-btn delete" data-id="<?php echo is_array($user) ? ($user['id'] ?? '') : ($user->id ?? ''); ?>"><i class='bx bxs-trash'></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Additional Styles -->
<style>
    /* Main Dashboard Styles */

    /* Welcome Section */
    .welcome-section {
        margin-bottom: 2rem;
    }

    .welcome-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .welcome-text h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .subtitle {
        color: var(--dark-grey);
        font-size: 1rem;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--light);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-card-content {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-info p {
        color: var(--dark-grey);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .stat-card-info h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .stat-change {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .stat-change.positive {
        color: var(--success);
    }

    .stat-change.negative {
        color: var(--danger);
    }

    .stat-change span {
        color: var(--dark-grey);
        font-weight: 400;
    }

    .stat-card-icon {
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.75rem;
    }

    .stat-card-icon.users {
        background-color: rgba(79, 70, 229, 0.1);
        color: var(--primary);
    }

    .stat-card-icon.services {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--secondary);
    }

    .stat-card-icon.orders {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .stat-card-icon.revenue {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .stat-card-footer {
        padding: 0.75rem 1.5rem;
        background-color: var(--grey);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-footer a {
        color: var(--primary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .stat-card-footer a:hover {
        color: var(--primary-dark);
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        height: 100%;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .chart-actions {
        display: flex;
        gap: 0.5rem;
    }

    .chart-action-btn {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 1rem;
        background: var(--grey);
        color: var(--dark);
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .chart-action-btn:hover {
        background: var(--grey-3);
    }

    .chart-action-btn.active {
        background: var(--primary);
        color: var(--light);
    }

    /* Features Section */
    .features-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .feature-card {
        height: 100%;
    }

    .feature-content {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .feature-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .feature-stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--dark-grey);
    }

    .feature-action {
        margin-top: auto;
    }

    .btn-block {
        display: flex;
        width: 100%;
        justify-content: center;
    }

    /* Task List */
    .task-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .task-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--grey-2);
        transition: background-color 0.2s ease;
    }

    .task-item:last-child {
        border-bottom: none;
    }

    .task-item:hover {
        background-color: var(--grey);
    }

    .task-checkbox {
        margin-right: 1rem;
        position: relative;
    }

    .task-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .task-checkbox label {
        position: relative;
        display: block;
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--grey-3);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .task-checkbox input:checked+label {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .task-checkbox input:checked+label:after {
        content: '\e92e';
        font-family: 'boxicons';
        position: absolute;
        top: -2px;
        left: 0;
        color: var(--light);
        font-size: 1rem;
        line-height: 1;
    }

    .task-text {
        flex: 1;
        transition: color 0.3s ease;
    }

    .task-item.completed .task-text {
        color: var(--dark-grey);
        text-decoration: line-through;
    }

    .task-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .task-item:hover .task-actions {
        opacity: 1;
    }

    .task-action-btn {
        background: transparent;
        border: none;
        color: var(--dark-grey);
        font-size: 1rem;
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s ease;
    }

    .task-action-btn:hover {
        color: var(--dark);
    }

    .task-action-btn.delete-task:hover {
        color: var(--danger);
    }

    /* Table styles */
    .tables-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .table-card {
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark-grey);
        border-bottom: 1px solid var(--grey-2);
    }

    .dashboard-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--grey-2);
    }

    .dashboard-table tr:last-child td {
        border-bottom: none;
    }

    .dashboard-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .dashboard-table tbody tr:hover {
        background-color: var(--grey);
    }

    .table-user {
        display: flex;
        align-items: center;
    }

    .table-user .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: var(--primary);
        color: var(--light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        margin-right: 0.75rem;
    }

    .table-user .user-name {
        font-weight: 500;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 1rem;
    }

    .badge-primary {
        background-color: rgba(79, 70, 229, 0.1);
        color: var(--primary-dark);
    }

    .badge-success {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .badge-info {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--grey);
        color: var(--dark);
        text-decoration: none;
        transition: var(--transition);
    }

    .action-btn:hover {
        background-color: var(--primary);
        color: var(--light);
    }

    .action-btn.delete:hover {
        background-color: var(--danger);
    }

    /* Buttons */
    .btn-sm {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .charts-section {
            grid-template-columns: 1fr;
        }

        .features-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .welcome-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Initialize Charts - Consolidated Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Charts with modern styling
        try {
            // Check if charts are already initialized to prevent duplicate initialization
            if (window.dashboardChartsInitialized) {
                console.log('Dashboard charts already initialized. Skipping initialization.');
                return;
            }

            // Parse chart data from PHP
            let visitChartData = [];
            let userDistributionData = [];

            try {
                visitChartData = <?php echo isset($data['visitChartData']) && !empty($data['visitChartData']) ? $data['visitChartData'] : '[]'; ?>;
            } catch (e) {
                console.warn('Error parsing visitChartData:', e);
                visitChartData = [];
            }

            try {
                userDistributionData = <?php echo isset($data['userDistributionData']) && !empty($data['userDistributionData']) ? $data['userDistributionData'] : '[]'; ?>;
            } catch (e) {
                console.warn('Error parsing userDistributionData:', e);
                userDistributionData = [];
            }

            // User Growth Chart
            const userGrowthCanvas = document.getElementById('userGrowthChart');
            const visitsCanvas = document.getElementById('visitsChart');

            // Only initialize if canvas elements exist and aren't already in use
            if (userGrowthCanvas && !userGrowthCanvas.__chartjs) {
                const userGrowthCtx = userGrowthCanvas.getContext('2d');

                // Extract data from the user distribution data
                const labels = userDistributionData.map ? userDistributionData.map(item => item.role) : ['Admin', 'Client', 'Freelancer'];
                const data = userDistributionData.map ? userDistributionData.map(item => item.count) : [5, 15, 10];

                const userGrowthChart = new Chart(userGrowthCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'New Users',
                            data: [10, 15, 8, 12, 18, 22, 16, 21, 25, 30, 35, 28], // Default data if actual data not available
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: 'rgb(79, 70, 229)',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: '#ffffff',
                                titleColor: '#1f2937',
                                bodyColor: '#1f2937',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 4,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                callbacks: {
                                    label: function(context) {
                                        return `New Users: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            }
                        }
                    }
                });

                // Store chart instance for period toggles
                window.userGrowthChart = userGrowthChart;
            }

            // Visits Chart
            if (visitsCanvas && !visitsCanvas.__chartjs) {
                const visitsCtx = visitsCanvas.getContext('2d');

                // Extract labels and data from visit chart data
                const visitLabels = visitChartData.map ? visitChartData.map(item => item.day) : [];
                const visitValues = visitChartData.map ? visitChartData.map(item => item.value) : [];

                const visitsChart = new Chart(visitsCtx, {
                    type: 'bar',
                    data: {
                        labels: visitLabels.length > 0 ? visitLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Page Visits',
                            data: visitValues.length > 0 ? visitValues : [10, 15, 8, 12, 18, 22, 16],
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderRadius: 5,
                            barThickness: 20,
                            maxBarThickness: 25
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: '#ffffff',
                                titleColor: '#1f2937',
                                bodyColor: '#1f2937',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 4,
                                callbacks: {
                                    label: function(context) {
                                        return `Visits: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            }
                        }
                    }
                });

                // Store chart instance for period toggles
                window.visitsChart = visitsChart;
            }

            // Mark charts as initialized
            window.dashboardChartsInitialized = true;

            // Chart period toggles
            const chartActionBtns = document.querySelectorAll('.chart-action-btn');
            chartActionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Find all buttons in the same group
                    const parentActions = this.closest('.chart-actions');
                    if (!parentActions) return;

                    const siblingBtns = parentActions.querySelectorAll('.chart-action-btn');

                    // Remove active class from all buttons
                    siblingBtns.forEach(sibling => {
                        sibling.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Get the period from the button's data attribute
                    const period = this.dataset.period;

                    // Update chart data based on the period
                    updateChartData(this.closest('.card'), period);
                });
            });

            // Function to update chart data based on period
            function updateChartData(cardElement, period) {
                if (!cardElement) return;

                // Identify which chart to update
                const chartCanvas = cardElement.querySelector('canvas');
                if (!chartCanvas) return;

                const chartId = chartCanvas.id;

                if (chartId === 'userGrowthChart' && window.userGrowthChart) {
                    // Update user growth chart data based on period
                    const newLabels = getLabelsForPeriod(period);
                    const newData = getRandomData(newLabels.length);

                    window.userGrowthChart.data.labels = newLabels;
                    window.userGrowthChart.data.datasets[0].data = newData;
                    window.userGrowthChart.update();
                }

                if (chartId === 'visitsChart' && window.visitsChart) {
                    // Update visits chart data based on period
                    const newLabels = getLabelsForPeriod(period);
                    const newData = getRandomData(newLabels.length);

                    window.visitsChart.data.labels = newLabels;
                    window.visitsChart.data.datasets[0].data = newData;
                    window.visitsChart.update();
                }
            }

            // Helper function to get labels for different periods
            function getLabelsForPeriod(period) {
                switch (period) {
                    case 'week':
                        return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    case 'month':
                        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    case 'year':
                        return ['2021', '2022', '2023', '2024', '2025'];
                    default:
                        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                }
            }

            // Helper function to generate random data for demos
            function getRandomData(count) {
                return Array.from({
                    length: count
                }, () => Math.floor(Math.random() * 30) + 5);
            }

            // Task checkbox functionality
            const taskCheckboxes = document.querySelectorAll('.task-checkbox input');
            taskCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const taskItem = this.closest('.task-item');
                    if (!taskItem) return;

                    if (this.checked) {
                        taskItem.classList.add('completed');
                    } else {
                        taskItem.classList.remove('completed');
                    }

                    // In a real app, we would make an AJAX request to update the task status
                    console.log('Task status changed:', {
                        id: this.id.replace('task-', ''),
                        completed: this.checked
                    });
                });
            });

            // Delete task button functionality
            const deleteButtons = document.querySelectorAll('.delete-task');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const taskItem = this.closest('.task-item');
                    if (!taskItem) return;

                    const taskCheckbox = taskItem.querySelector('.task-checkbox input');
                    if (!taskCheckbox) return;

                    const taskId = taskCheckbox.id.replace('task-', '');

                    // Simple animation for removal
                    taskItem.style.opacity = '0';
                    setTimeout(() => {
                        taskItem.style.height = '0';
                        taskItem.style.padding = '0';
                        taskItem.style.overflow = 'hidden';

                        setTimeout(() => {
                            taskItem.remove();
                        }, 300);
                    }, 300);

                    // In a real app, we would make an AJAX request to delete the task
                    console.log('Task deleted:', taskId);
                });
            });

            // For demonstration purposes - Add Task button
            const addTaskBtn = document.getElementById('add-task-btn');
            if (addTaskBtn) {
                addTaskBtn.addEventListener('click', function() {
                    alert('In a real app, this would open a dialog to add a new task!');
                });
            }
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    });
</script>