<?php
// Use buffers to store the dashboard content
ob_start();

// Data is passed from the controller as $data, extract it to make variables directly accessible
extract($data);

// Now variables like $analyticsData, $users, $visitChartData, and $userDistributionData are available
?>

<div class="dashboard-main">
    <style>
        .dashboard-main {
            padding: 1.5rem 0;
        }
        .analytics-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(1, 1fr);
        }
        @media (min-width: 768px) {
            .analytics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .analytics-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .analytics-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }
        .analytics-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .analytics-card-title {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }
        .analytics-card-icon {
            height: 1rem;
            width: 1rem;
            color: #6b7280;
        }
        .analytics-card-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .analytics-card-stats {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .trend-positive {
            color: #10b981;
            margin-right: 0.25rem;
        }
        .trend-negative {
            color: #ef4444;
            margin-right: 0.25rem;
        }
        .charts-row {
            display: grid;
            gap: 1.5rem;
            margin-top: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 1280px) {
            .charts-row {
                grid-template-columns: 2fr 1fr;
            }
        }
        .chart-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            height: 22rem;
        }
        .chart-header {
            margin-bottom: 1rem;
        }
        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        .chart-container {
            height: calc(100% - 3rem);
            width: 100%;
        }
        .users-section {
            margin-top: 1.5rem;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
        }
        .charts-grid {
            display: grid;
            gap: 1.5rem;
            margin-top: 1.5rem;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <!-- Analytics Cards -->
    <div class="analytics-grid">
        <div class="analytics-card">
            <div class="analytics-card-header">
                <div class="analytics-card-title">Total Users</div>
                <div class="analytics-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
            </div>
            <div class="analytics-card-value"><?php echo number_format($analyticsData['totalUsers'] ?? 0); ?></div>
            <div class="analytics-card-stats">Total registered users</div>
        </div>

        <div class="analytics-card">
            <div class="analytics-card-header">
                <div class="analytics-card-title">Active Users</div>
                <div class="analytics-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="analytics-card-value"><?php echo number_format($analyticsData['activeUsers'] ?? 0); ?></div>
            <div class="analytics-card-stats">Currently active users</div>
        </div>

        <div class="analytics-card">
            <div class="analytics-card-header">
                <div class="analytics-card-title">Today's Visits</div>
            </div>
            <div class="analytics-card-value"><?php echo number_format($analyticsData['todayVisits'] ?? 0); ?></div>
            <div class="analytics-card-stats">
                <span class="trend-positive">↑ <?php echo $analyticsData['visitsGrowth'] ?? 0; ?>%</span> vs. previous day
            </div>
        </div>

        <div class="analytics-card">
            <div class="analytics-card-header">
                <div class="analytics-card-title">User Engagement</div>
                <div class="analytics-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </div>
            </div>
            <div class="analytics-card-value">85%</div>
            <div class="analytics-card-stats">Average session time</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">
        <!-- Website Visits Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Website Visits</h3>
            </div>
            <div class="chart-container">
                <canvas id="visits-chart" data-chart='<?php echo $visitChartData; ?>'></canvas>
            </div>
        </div>

        <!-- User Distribution Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">User Distribution by Role</h3>
            </div>
            <div class="chart-container">
                <canvas id="distribution-chart" data-chart='<?php echo $userDistributionData; ?>'></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Grid -->
    <div class="charts-grid">
        <!-- Traffic Sources Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Traffic Sources</h3>
            </div>
            <div class="chart-container">
                <canvas id="traffic-sources-chart"></canvas>
            </div>
        </div>
        
        <!-- Page Performance Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Page Performance</h3>
            </div>
            <div class="chart-container">
                <canvas id="page-performance-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Make chart data available to JavaScript -->
    <script>
        // Set data as JavaScript variables for direct access by dashboard.js
        window.visitChartData = <?php echo $visitChartData; ?>;
        window.userDistributionData = <?php echo $userDistributionData; ?>;
    </script>
</div>

<?php
// Store the dashboard content in the $content variable
$content = ob_get_clean();

// Include the dashboard layout
require_once 'dashboard_layout.php';
?>