<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="freelancer-indicator">
    <div class="indicator-badge">
        <i class="fas fa-laptop-code"></i> Freelancer Account
    </div>
</div>

<main class="main-container">
    <section class="page-header-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="page-title"><?php echo $data['title']; ?></h1>
                    <p class="page-description"><?php echo $data['description']; ?></p>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <div class="date-range-selector">
                        <button class="btn-date-range">
                            <i class="fas fa-calendar me-2"></i> Last 30 Days <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section py-4">
        <div class="container">
            <div class="row">
                <!-- Overview Cards -->
                <div class="col-12 mb-4">
                    <div class="overview-cards-container">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="overview-card">
                                    <div class="overview-card-content">
                                        <div class="overview-card-icon earnings-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="overview-card-info">
                                            <h3>$0</h3>
                                            <p>Total Earnings</p>
                                        </div>
                                    </div>
                                    <div class="overview-card-chart">
                                        <div class="trend trend-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="overview-card">
                                    <div class="overview-card-content">
                                        <div class="overview-card-icon jobs-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="overview-card-info">
                                            <h3>0</h3>
                                            <p>Jobs Completed</p>
                                        </div>
                                    </div>
                                    <div class="overview-card-chart">
                                        <div class="trend trend-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="overview-card">
                                    <div class="overview-card-content">
                                        <div class="overview-card-icon hours-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="overview-card-info">
                                            <h3>0h</h3>
                                            <p>Hours Worked</p>
                                        </div>
                                    </div>
                                    <div class="overview-card-chart">
                                        <div class="trend trend-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="overview-card">
                                    <div class="overview-card-content">
                                        <div class="overview-card-icon rating-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="overview-card-info">
                                            <h3>0.0</h3>
                                            <p>Average Rating</p>
                                        </div>
                                    </div>
                                    <div class="overview-card-chart">
                                        <div class="trend trend-neutral">
                                            <i class="fas fa-minus"></i> 0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="content-card h-100">
                        <div class="card-header">
                            <h5>Earnings Overview</h5>
                            <div class="chart-controls">
                                <button class="btn-chart-period active" data-period="weekly">Weekly</button>
                                <button class="btn-chart-period" data-period="monthly">Monthly</button>
                                <button class="btn-chart-period" data-period="yearly">Yearly</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="earningsChart" height="280"></canvas>
                                <div class="chart-empty-overlay">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h6>No earnings data yet</h6>
                                    <p>Complete jobs to see your earnings visualized here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Breakdown -->
                <div class="col-lg-4 mb-4">
                    <div class="content-card h-100">
                        <div class="card-header">
                            <h5>Earnings by Category</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="categoryChart" height="280"></canvas>
                                <div class="chart-empty-overlay">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-pie-chart"></i>
                                    </div>
                                    <h6>No category data yet</h6>
                                    <p>Complete jobs to see your category breakdown</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Activity -->
                <div class="col-lg-6 mb-4">
                    <div class="content-card">
                        <div class="card-header">
                            <h5>Work Activity</h5>
                            <div class="dropdown">
                                <button class="btn-filter" id="activityFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter <i class="fas fa-chevron-down ms-1"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="activityFilterDropdown">
                                    <li><a class="dropdown-item active" href="#">All Activity</a></li>
                                    <li><a class="dropdown-item" href="#">Completed Jobs</a></li>
                                    <li><a class="dropdown-item" href="#">In Progress</a></li>
                                    <li><a class="dropdown-item" href="#">Cancelled</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h6>No activity yet</h6>
                                <p>Your work activity will appear here once you start working on projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Skills -->
                <div class="col-lg-6 mb-4">
                    <div class="content-card">
                        <div class="card-header">
                            <h5>Top Skills</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="skill-stats-empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <h6>No skill data yet</h6>
                                <p>Complete jobs to see which skills are earning you the most</p>
                                <a href="<?php echo URL_ROOT; ?>/dashboard/profile#skills" class="btn-add-skills">Add Skills to Your Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client Feedback -->
                <div class="col-12 mb-4">
                    <div class="content-card">
                        <div class="card-header">
                            <h5>Client Feedback</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="feedback-empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <h6>No feedback yet</h6>
                                <p>Client feedback will appear here after you complete jobs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    /* Page Header Styles */
    .page-header-section {
        background-color: var(--white);
        padding: 2rem 0 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        margin-top: 70px;
        /* For fixed navbar */
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .page-description {
        font-size: 1rem;
        color: var(--gray-600);
    }

    .btn-date-range {
        background-color: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-date-range:hover {
        background-color: var(--gray-100);
        border-color: var(--gray-400);
    }

    /* Overview Cards Styles */
    .overview-cards-container {
        margin-bottom: 1rem;
    }

    .overview-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 1.25rem;
        height: 100%;
        transition: all var(--transition-normal);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .overview-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .overview-card-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .overview-card-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--white);
    }

    .earnings-icon {
        background: linear-gradient(135deg, #0d47a1, #1a237e);
    }

    .jobs-icon {
        background: linear-gradient(135deg, #1565c0, #0d47a1);
    }

    .hours-icon {
        background: linear-gradient(135deg, #1976d2, #1565c0);
    }

    .rating-icon {
        background: linear-gradient(135deg, #2196f3, #1976d2);
    }

    .overview-card-info h3 {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin: 0 0 0.25rem;
    }

    .overview-card-info p {
        font-size: 0.9rem;
        color: var(--gray-600);
        margin: 0;
    }

    .overview-card-chart {
        display: flex;
        justify-content: flex-end;
    }

    .trend {
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        padding: 0.25rem 0.5rem;
        border-radius: var(--border-radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .trend-up {
        color: #2ecc71;
        background-color: rgba(46, 204, 113, 0.1);
    }

    .trend-down {
        color: #e74c3c;
        background-color: rgba(231, 76, 60, 0.1);
    }

    .trend-neutral {
        color: var(--gray-600);
        background-color: var(--gray-100);
    }

    /* Content Card Styles */
    .content-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        height: 100%;
    }

    .content-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .content-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .content-card .card-body {
        padding: 1.5rem;
    }

    /* Chart Controls */
    .chart-controls {
        display: flex;
        gap: 0.5rem;
    }

    .btn-chart-period {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
        color: var(--gray-600);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-chart-period:hover {
        background-color: var(--gray-100);
    }

    .btn-chart-period.active {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--white);
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .chart-empty-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.9);
        padding: 1.5rem;
        text-align: center;
    }

    .chart-empty-overlay .empty-state-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-circle);
        background-color: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: var(--gray-600);
        font-size: 1.5rem;
    }

    .chart-empty-overlay h6 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .chart-empty-overlay p {
        font-size: 0.9rem;
        color: var(--gray-600);
        margin: 0;
    }

    /* Filter Dropdown */
    .btn-filter {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-filter:hover {
        background-color: var(--gray-100);
    }

    /* Empty States */
    .activity-empty-state,
    .skill-stats-empty-state,
    .feedback-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: var(--border-radius-circle);
        background-color: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        color: var(--gray-600);
        font-size: 1.8rem;
    }

    .activity-empty-state h6,
    .skill-stats-empty-state h6,
    .feedback-empty-state h6 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .activity-empty-state p,
    .skill-stats-empty-state p,
    .feedback-empty-state p {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin-bottom: 1.25rem;
    }

    .btn-add-skills {
        background-color: var(--primary);
        color: var(--white);
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        padding: 0.6rem 1.25rem;
        border-radius: var(--border-radius-md);
        text-decoration: none;
        transition: all var(--transition-normal);
        display: inline-block;
    }

    .btn-add-skills:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: var(--white);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .overview-card-content {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }

        .overview-card-chart {
            justify-content: center;
        }

        .chart-controls {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .date-range-selector {
            margin-top: 1rem;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up empty charts
        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');

        const earningsChart = new Chart(earningsCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Earnings ($)',
                    data: [0, 0, 0, 0],
                    backgroundColor: 'rgba(26, 35, 126, 0.2)',
                    borderColor: '#1a237e',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#1a237e',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.8)',
                        titleColor: '#1e2a4a',
                        bodyColor: '#1e2a4a',
                        borderColor: '#e8eaf6',
                        borderWidth: 1,
                        caretSize: 6,
                        caretPadding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['No Data'],
                datasets: [{
                    data: [100],
                    backgroundColor: ['#e8eaf6'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.8)',
                        titleColor: '#1e2a4a',
                        bodyColor: '#1e2a4a',
                        borderColor: '#e8eaf6',
                        borderWidth: 1,
                        caretSize: 6,
                        caretPadding: 10,
                        cornerRadius: 6,
                        displayColors: true,
                        padding: 10
                    }
                }
            }
        });

        // Chart period buttons functionality
        const periodButtons = document.querySelectorAll('.btn-chart-period');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                periodButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // In a real application, we would update the chart data here
                alert('This button would change the chart data period in a real application.');
            });
        });

        // Date range selector functionality
        const dateRangeButton = document.querySelector('.btn-date-range');
        if (dateRangeButton) {
            dateRangeButton.addEventListener('click', function() {
                alert('This button would open a date range picker in a real application.');
            });
        }

        // Fake dropdown functionality
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                dropdownItems.forEach(item => item.classList.remove('active'));
                this.classList.add('active');

                // In a real application, we would filter the activity data here
                alert('This would filter the activity data in a real application.');
            });
        });

        // Add skills button functionality
        const addSkillsBtn = document.querySelector('.btn-add-skills');
        if (addSkillsBtn) {
            addSkillsBtn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('This button would take you to the skills section of your profile in a real application.');
            });
        }
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>