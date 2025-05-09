<?php
/**
 * Dashboard main content
 */

// Get project data
$recentProjects = $projectModel->getUserProjects();
$projectStats = $projectModel->getProjectStats();

// Limit to 5 most recent projects
$recentProjects = array_slice($recentProjects, 0, 5);
?>

<!-- Welcome Section -->
<section class="welcome-section">
    <h2 class="welcome-title">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
    <p class="welcome-subtitle">Here's how your projects are doing.</p>
</section>

<!-- Stats Section -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <div class="stat-title">Active Projects</div>
        <div class="stat-value"><?php echo $projectStats['in_progress']; ?></div>
        <div class="stat-change">
            Total: <?php echo $projectStats['total']; ?>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-title">Total Budget</div>
        <div class="stat-value">$<?php echo number_format($projectStats['total_budget'], 2); ?></div>
        <div class="stat-change">
            All projects combined
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-title">Completed</div>
        <div class="stat-value"><?php echo $projectStats['completed']; ?></div>
        <div class="stat-change">
            Finished projects
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-title">Pending</div>
        <div class="stat-value"><?php echo $projectStats['pending']; ?></div>
        <div class="stat-change">
            Projects to start
        </div>
    </div>
</div>

<!-- Recent Projects Section -->
<section class="dashboard-table-section">
    <div class="dashboard-table-header">
        <h3 class="dashboard-table-title">Recent Projects</h3>
        <a href="?page=projects" class="dashboard-table-action">View All</a>
    </div>
    
    <div class="table-responsive">
        <?php if (empty($recentProjects)): ?>
            <div class="text-center p-4">
                <p class="text-muted">No projects found. Start by creating your first project!</p>
                <a href="?page=projects&new=true" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Project
                </a>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Owner</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Budget</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProjects as $project): ?>
                    <tr>
                        <td>
                            <a href="?page=projects&project_id=<?php echo $project['id']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($project['title']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($project['client_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($project['user_email'] ?? 'N/A'); ?></td>
                        <td>
                            <?php 
                            if (!empty($project['end_date'])) {
                                echo date('M d, Y', strtotime($project['end_date']));
                            } else {
                                echo 'Not set';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php 
                                echo match($project['status']) {
                                    'completed' => 'success',
                                    'in-progress' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo ucfirst($project['status']); ?>
                            </span>
                        </td>
                        <td>$<?php echo !empty($project['budget']) ? number_format($project['budget'], 2) : 'N/A'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>