<?php
/**
 * Admin Dashboard - Main Dashboard Page
 * Displays overview statistics and metrics
 */

// In a real application, you would fetch these statistics from your database
// For now, we're using sample data
$totalUsers = 125;
$totalServices = 87;
$totalOrders = 46;
$totalRevenue = 12580.75;
$recentUsers = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'account_type' => 'client', 'created_at' => '2025-04-25'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'account_type' => 'freelancer', 'created_at' => '2025-04-26'],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'account_type' => 'client', 'created_at' => '2025-04-27'],
    ['id' => 4, 'name' => 'Sara Wilson', 'email' => 'sara@example.com', 'account_type' => 'freelancer', 'created_at' => '2025-04-28'],
    ['id' => 5, 'name' => 'Mike Brown', 'email' => 'mike@example.com', 'account_type' => 'client', 'created_at' => '2025-04-29'],
];
$recentOrders = [
    ['id' => 101, 'service' => 'Logo Design', 'client' => 'John Doe', 'freelancer' => 'Jane Smith', 'amount' => 150.00, 'status' => 'completed', 'date' => '2025-04-27'],
    ['id' => 102, 'service' => 'Website Development', 'client' => 'Mike Brown', 'freelancer' => 'Sara Wilson', 'amount' => 450.00, 'status' => 'process', 'date' => '2025-04-28'],
    ['id' => 103, 'service' => 'Content Writing', 'client' => 'Bob Johnson', 'freelancer' => 'Jane Smith', 'amount' => 80.00, 'status' => 'pending', 'date' => '2025-04-29'],
];
?>

<!-- Dashboard Header -->
<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo URL_ROOT; ?>/pages/admin">Dashboard</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="#">Overview</a>
            </li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
        <span class="text">Export Report</span>
    </a>
</div>

<!-- Dashboard Stat Cards -->
<ul class="box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3><?php echo $totalUsers; ?></h3>
            <p>Total Users</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-shopping-bag-alt'></i>
        <span class="text">
            <h3><?php echo $totalServices; ?></h3>
            <p>Total Services</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-calendar-check'></i>
        <span class="text">
            <h3><?php echo $totalOrders; ?></h3>
            <p>Total Orders</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3>$<?php echo number_format($totalRevenue, 2); ?></h3>
            <p>Total Revenue</p>
        </span>
    </li>
</ul>

<!-- Dashboard Data Tables -->
<div class="table-data">
    <!-- Recent Orders Table -->
    <div class="order">
        <div class="head">
            <h3>Recent Orders</h3>
            <i class='bx bx-search'></i>
            <i class='bx bx-filter'></i>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recentOrders as $order): ?>
                    <tr>
                        <td>
                            <?php 
                                $nameArray = explode(' ', $order['client']);
                                $initials = '';
                                if(isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                                if(isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                            ?>
                            <div class="client-img"><?php echo $initials; ?></div>
                            <p><?php echo $order['client']; ?></p>
                        </td>
                        <td><?php echo $order['service']; ?></td>
                        <td><?php echo $order['date']; ?></td>
                        <td><span class="status <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Todo List -->
    <div class="todo">
        <div class="head">
            <h3>Todo</h3>
            <i class='bx bx-plus'></i>
            <i class='bx bx-filter'></i>
        </div>
        <ul class="todo-list">
            <li class="completed">
                <p>Review new service submissions</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
            <li class="completed">
                <p>Approve freelancer profiles</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
            <li class="not-completed">
                <p>Update service categories</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
            <li class="not-completed">
                <p>Review platform analytics</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
            <li class="not-completed">
                <p>Email newsletter to users</p>
                <i class='bx bx-dots-vertical-rounded'></i>
            </li>
        </ul>
    </div>
</div>

<!-- Charts Row -->
<div class="charts-row">
    <div class="chart-card">
        <div class="head">
            <h3>User Growth</h3>
            <div class="chart-actions">
                <button class="chart-action-btn" data-period="week">Week</button>
                <button class="chart-action-btn active" data-period="month">Month</button>
                <button class="chart-action-btn" data-period="year">Year</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="head">
            <h3>Revenue Overview</h3>
            <div class="chart-actions">
                <button class="chart-action-btn" data-period="week">Week</button>
                <button class="chart-action-btn active" data-period="month">Month</button>
                <button class="chart-action-btn" data-period="year">Year</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Dashboard Additional Styles -->
<style>
    /* Head Title */
    .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        grid-gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .head-title .left h1 {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .head-title .left .breadcrumb {
        display: flex;
        align-items: center;
        grid-gap: 16px;
    }

    .head-title .left .breadcrumb li {
        color: var(--dark);
    }

    .head-title .left .breadcrumb li a {
        color: var(--dark-grey);
        pointer-events: none;
    }

    .head-title .left .breadcrumb li a.active {
        color: var(--blue);
        pointer-events: unset;
    }

    .head-title .btn-download {
        height: 36px;
        padding: 0 16px;
        border-radius: 36px;
        background: var(--blue);
        color: var(--light);
        display: flex;
        justify-content: center;
        align-items: center;
        grid-gap: 10px;
        font-weight: 500;
    }

    /* Box Info (Stat Cards) */
    .box-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        grid-gap: 24px;
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .box-info li {
        padding: 24px;
        background: var(--light);
        border-radius: 20px;
        display: flex;
        align-items: center;
        grid-gap: 24px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .box-info li:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .box-info li i {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        font-size: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .box-info li:nth-child(1) i {
        background: var(--light-blue);
        color: var(--blue);
    }

    .box-info li:nth-child(2) i {
        background: var(--light-yellow);
        color: var(--yellow);
    }

    .box-info li:nth-child(3) i {
        background: var(--light-orange);
        color: var(--orange);
    }

    .box-info li:nth-child(4) i {
        background: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
    }

    .box-info li .text h3 {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark);
    }

    .box-info li .text p {
        color: var(--dark-grey);
    }

    /* Tables */
    .table-data {
        display: flex;
        flex-wrap: wrap;
        grid-gap: 24px;
        margin-top: 24px;
        width: 100%;
        color: var(--dark);
    }

    .table-data > div {
        border-radius: 20px;
        background: var(--light);
        padding: 24px;
        overflow-x: auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .table-data .head {
        display: flex;
        align-items: center;
        grid-gap: 16px;
        margin-bottom: 24px;
    }

    .table-data .head h3 {
        margin-right: auto;
        font-size: 24px;
        font-weight: 600;
    }

    .table-data .head i {
        cursor: pointer;
        color: var(--dark-grey);
        transition: color 0.3s ease;
    }

    .table-data .head i:hover {
        color: var(--dark);
    }

    .table-data .order {
        flex-grow: 1;
        flex-basis: 500px;
    }

    .table-data .order table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-data .order table th {
        padding-bottom: 12px;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .table-data .order table td {
        padding: 16px 0;
    }

    .table-data .order table tr td:first-child {
        display: flex;
        align-items: center;
        grid-gap: 12px;
        padding-left: 6px;
    }

    .table-data .order table td .client-img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--blue);
        color: var(--light);
        font-weight: 600;
    }

    .table-data .order table tbody tr:hover {
        background: var(--grey);
    }

    .table-data .order table tr td .status {
        font-size: 10px;
        padding: 6px 16px;
        color: var(--light);
        border-radius: 20px;
        font-weight: 700;
    }

    .table-data .order table tr td .status.completed {
        background: var(--blue);
    }

    .table-data .order table tr td .status.process {
        background: var(--yellow);
    }

    .table-data .order table tr td .status.pending {
        background: var(--orange);
    }

    /* Todo */
    .table-data .todo {
        flex-grow: 1;
        flex-basis: 300px;
    }

    .table-data .todo .todo-list {
        width: 100%;
    }

    .table-data .todo .todo-list li {
        width: 100%;
        margin-bottom: 16px;
        background: var(--grey);
        border-radius: 10px;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-data .todo .todo-list li .bx {
        cursor: pointer;
    }

    .table-data .todo .todo-list li.completed {
        border-left: 10px solid var(--blue);
    }

    .table-data .todo .todo-list li.not-completed {
        border-left: 10px solid var(--orange);
    }

    .table-data .todo .todo-list li:last-child {
        margin-bottom: 0;
    }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        grid-gap: 24px;
        margin-top: 24px;
        width: 100%;
    }

    .charts-row .chart-card {
        background: var(--light);
        padding: 24px;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .charts-row .chart-card .head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .charts-row .chart-card .head h3 {
        font-size: 18px;
        font-weight: 600;
    }

    .chart-actions {
        display: flex;
        gap: 8px;
    }

    .chart-action-btn {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: var(--grey);
        color: var(--dark);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .chart-action-btn:hover {
        background: var(--dark-grey);
        color: var(--light);
    }

    .chart-action-btn.active {
        background: var(--blue);
        color: var(--light);
    }

    .chart-container {
        height: 250px;
        position: relative;
    }

    /* Responsive Adjustments */
    @media screen and (max-width: 768px) {
        .box-info {
            grid-template-columns: 1fr;
        }
        
        .charts-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Initialize Charts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        const userGrowthChart = new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'New Users',
                    data: [10, 15, 8, 12, 18, 22, 16, 21, 25, 30, 35, 25],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3498db',
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [1200, 1500, 980, 1400, 1800, 2200, 1850, 2100, 2500, 3000, 3500, 2800],
                    backgroundColor: '#2ecc71',
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Chart period toggles
        const chartActionBtns = document.querySelectorAll('.chart-action-btn');
        chartActionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Find all buttons in the same group
                const parentActions = this.closest('.chart-actions');
                const siblingBtns = parentActions.querySelectorAll('.chart-action-btn');
                
                // Remove active class from all buttons
                siblingBtns.forEach(sibling => {
                    sibling.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // In a real app, we would update the chart data here
                const period = this.dataset.period;
                console.log('Selected period:', period);
            });
        });
    });
</script>