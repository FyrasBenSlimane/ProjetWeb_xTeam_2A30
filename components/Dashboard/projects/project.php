<?php

/**
 * Projects Dashboard View
 * Allows users to view, create and manage their projects
 */

// Start output buffering to capture content for the layout
ob_start();

// Variables should already be set by the controller:
// - $projects: All user projects
// - $currentView: The current view (list_projects, new_project, view_project)
// - $selectedProject: The selected project (if viewing a project)
// - $projectTasks: Tasks for the selected project
// - $userEmail: The current user's email
// - $projectModel: The project model instance

// Define message variables for displaying alerts
$message = '';
$alertType = '';

// Handle form submissions - this would typically be in the controller
// but we'll keep it here for compatibility
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if projectModel is not defined (happens when form is submitted directly)
    if (!isset($projectModel) || $projectModel === null) {
        // Get database connection and initialize projectModel
        require_once __DIR__ . '/../../../config/database.php';
        $pdo = $GLOBALS['pdo'] ?? getDBConnection();
        require_once __DIR__ . '/../models/ProjectModel.php';
        $projectModel = new ProjectModel($pdo);
    }
    
    // Validation du formulaire
    if (isset($_POST['action']) && $_POST['action'] === 'create_project') {
        // Initialize errors array
        $errors = [];
        
        // Validation du titre
        if (empty($_POST['title'])) {
            $errors['title'] = 'Le titre du projet est obligatoire.';
        } elseif (strlen($_POST['title']) < 3) {
            $errors['title'] = 'Le titre doit contenir au moins 3 caractères.';
        }

        // Validation du nom du client
        if (empty($_POST['client_name'])) {
            $errors['client_name'] = 'Le nom du client est obligatoire.';
        }

        // Validation du budget
        if (empty($_POST['budget'])) {
            $errors['budget'] = 'Le budget est obligatoire.';
        } elseif (!is_numeric($_POST['budget']) || $_POST['budget'] < 0) {
            $errors['budget'] = 'Le budget doit être un nombre positif.';
        }

        // Validation de la date de début
        if (empty($_POST['start_date'])) {
            $errors['start_date'] = 'La date de début est obligatoire.';
        }

        // Validation de la date de fin
        if (empty($_POST['end_date'])) {
            $errors['end_date'] = 'La date de fin est obligatoire.';
        } elseif (!empty($_POST['start_date']) && strtotime($_POST['end_date']) <= strtotime($_POST['start_date'])) {
            $errors['end_date'] = 'La date de fin doit être postérieure à la date de début.';
        }

        // Validation de la priorité
        if (empty($_POST['priority'])) {
            $errors['priority'] = 'La priorité est obligatoire.';
        } elseif (!in_array($_POST['priority'], ['low', 'medium', 'high'])) {
            $errors['priority'] = 'La priorité sélectionnée n\'est pas valide.';
        }

        // Validation de la description
        if (empty($_POST['description'])) {
            $errors['description'] = 'La description est obligatoire.';
        } elseif (strlen($_POST['description']) < 10) {
            $errors['description'] = 'La description doit contenir au moins 10 caractères.';
        }

        if (empty($errors)) {
            $result = $projectModel->createProject(
                $userEmail, 
                $_POST['title'],
                $_POST['description'],
                $_POST['client_name'],
                $_POST['budget'],
                $_POST['priority'],
                $_POST['start_date'],
                $_POST['end_date']
            );
            if ($result) {
                $message = 'Project created successfully.';
                $alertType = 'success';
            } else {
                $message = 'Failed to create project. Please try again.';
                $alertType = 'danger';
            }
        } else {
            // Join all error messages
            $message = implode('<br>', $errors);
            $alertType = 'danger';
        }
    }

    // Update project
    if (isset($_POST['action']) && $_POST['action'] === 'update_project') {
        $projectId = $_POST['project_id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $clientName = $_POST['client_name'] ?? '';
        $budget = filter_var($_POST['budget'] ?? 0, FILTER_VALIDATE_FLOAT);
        $priority = $_POST['priority'] ?? 'medium';
        $status = $_POST['status'] ?? 'pending';
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        
        if (empty($projectId) || empty($title) || empty($description)) {
            $message = 'Please fill all required fields.';
            $alertType = 'danger';
        } else {
            $data = [
                'title' => $title,
                'description' => $description,
                'client_name' => $clientName,
                'budget' => $budget,
                'priority' => $priority,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
            
            $result = $projectModel->updateProject($projectId, $userEmail, $data);
            if ($result) {
                $message = 'Project updated successfully.';
                $alertType = 'success';
            } else {
                $message = 'Failed to update project. Please try again.';
                $alertType = 'danger';
            }
        }
    }

    // Add task to project
    if (isset($_POST['action']) && $_POST['action'] === 'add_task') {
        $projectId = $_POST['project_id'] ?? '';
        $taskTitle = $_POST['task_title'] ?? '';
        $taskDescription = $_POST['task_description'] ?? '';
        $assignedTo = $_POST['assigned_to'] ?? '';
        $dueDate = $_POST['due_date'] ?? null;
        
        if (empty($projectId) || empty($taskTitle)) {
            $message = 'Task title is required.';
            $alertType = 'danger';
        } else {
            $result = $projectModel->addTask($projectId, $taskTitle, $taskDescription, $assignedTo, $dueDate);
            if ($result) {
                $message = 'Task added successfully.';
                $alertType = 'success';
            } else {
                $message = 'Failed to add task. Please try again.';
                $alertType = 'danger';
            }
        }
    }

    // Delete project
    if (isset($_POST['action']) && $_POST['action'] === 'delete_project') {
        $projectId = $_POST['project_id'] ?? '';
        
        if (empty($projectId)) {
            $message = 'Invalid project.';
            $alertType = 'danger';
        } else {
            $result = $projectModel->deleteProject($projectId, $userEmail);
            if ($result) {
                $message = 'Project deleted successfully.';
                $alertType = 'success';
            } else {
                $message = 'Failed to delete project. Please try again.';
                $alertType = 'danger';
            }
        }
    }
}

// Make sure user data is available
$userName = $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
$userType = $_SESSION['user']['user_type'] ?? 'freelancer';

// The HTML content starts below
?>

<!-- Projects Dashboard Content -->
<div class="container-fluid py-4">
    <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <script>
    // Function for displaying notifications
    function showNotification(message, type) {
        // Don't show notification for specific messages like pending application
        if (message.includes('You already have a pending application')) {
            return;
        }
        
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => {
            notification.style.animation = 'slideOut 0.3s forwards';
            setTimeout(() => notification.remove(), 300);
        });

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification-toast alert alert-${type} alert-dismissible`;
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="notification-icon">
                    <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
                </div>
                <div class="notification-message">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="notification-progress"></div>
        `;

        // Add notification to document
        document.body.appendChild(notification);

        // Force reflow to activate animation
        notification.offsetHeight;

        // Show notification with animation
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';

        // Auto-close after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);

        // Handle close button
        const closeButton = notification.querySelector('.btn-close');
        closeButton.addEventListener('click', () => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        });
    }

    // Function to handle application actions (accept/reject)
function handleCandidatureAction(candidatureId, action) {
    const url = `?page=projects&action=update_candidature_status`;
    const data = new FormData();
    data.append('candidature_id', candidatureId);
    data.append('status', action);

    fetch(url, {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                // Update the user interface
            const candidatureCard = document.querySelector(`#candidature-${candidatureId}`);
            if (candidatureCard) {
                const statusBadge = candidatureCard.querySelector('.candidature-status .badge');
                if (statusBadge) {
                    statusBadge.className = `badge bg-${action === 'accepted' ? 'success' : 'danger'}`;
                        statusBadge.textContent = action === 'accepted' ? 'Accepted' : 'Rejected';
                }
                    // Hide action buttons
                const actionsDiv = candidatureCard.querySelector('.candidature-actions');
                if (actionsDiv) {
                    actionsDiv.style.display = 'none';
                }
            }
                // Show notification
            showNotification(data.message, 'success');
        } else {
                showNotification(data.message || 'An error occurred', 'danger');
        }
    })
    .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while processing the request', 'danger');
        });
    }

    // Function to handle canceling an application
    function cancelApplication(applicationId) {
        if (!confirm('Are you sure you want to cancel this application?')) {
            return;
        }
        
        const url = `?page=projects&action=cancel_application`;
        const data = new FormData();
        data.append('candidature_id', applicationId);
        
        fetch(url, {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Find the row and update it
                const applicationRow = document.querySelector(`button[data-application-id="${applicationId}"]`).closest('tr');
                if (applicationRow) {
                    // Update status badge
                    const statusBadge = applicationRow.querySelector('.badge');
                    if (statusBadge) {
                        statusBadge.className = 'badge bg-danger';
                        statusBadge.textContent = 'Cancelled';
                    }
                    
                    // Remove cancel button
                    const cancelBtn = applicationRow.querySelector('.cancel-application');
                    if (cancelBtn) {
                        cancelBtn.remove();
                    }
                }
                
                // Show notification
                showNotification('Your application has been cancelled successfully', 'success');
            } else {
                showNotification(data.message || 'An error occurred', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while processing your request', 'danger');
        });
    }

    // Add a single event listener to handle both types of buttons
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
            // Accept/reject candidature buttons
            const actionButton = event.target.closest('.accept-candidature, .reject-candidature');
            if (actionButton) {
                const candidatureId = actionButton.dataset.candidatureId;
                const action = actionButton.classList.contains('accept-candidature') ? 'accepted' : 'rejected';
            handleCandidatureAction(candidatureId, action);
        }
            
            // Cancel application button
            const cancelButton = event.target.closest('.cancel-application');
            if (cancelButton) {
                const applicationId = cancelButton.dataset.applicationId;
                cancelApplication(applicationId);
            }
    });
});

function updateRemainingTime() {
    document.querySelectorAll('.remaining-time').forEach(element => {
        const createdAt = new Date(element.dataset.createdAt);
        const expirationHours = parseInt(element.dataset.expirationDelay) || 48;

            // Check if expiration delay is valid
        if (isNaN(expirationHours)) {
                element.innerHTML = ' - <span class="text-warning">Undefined delay</span>';
            return;
        }

        const now = new Date();
        const expiresAt = new Date(createdAt.getTime() + (expirationHours * 60 * 60 * 1000));
        
        if (now >= expiresAt) {
                element.innerHTML = ' - <span class="text-danger">Expired</span>';
            const candidatureCard = element.closest('.candidature-card');
            if (candidatureCard) {
                const statusBadge = candidatureCard.querySelector('.candidature-status .badge');
                if (statusBadge && statusBadge.textContent.trim().toLowerCase() === 'pending') {
                    statusBadge.className = 'badge bg-secondary';
                        statusBadge.textContent = 'Expired';
                    const actionsDiv = candidatureCard.querySelector('.candidature-actions');
                    if (actionsDiv) actionsDiv.remove();
                }
            }
        } else {
            const diff = expiresAt - now;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                element.innerHTML = ` - Expires in ${days}d ${hours}h ${minutes}m`;
        }
    });
}

    // Update remaining time every minute
document.addEventListener('DOMContentLoaded', function() {
    updateRemainingTime();
    setInterval(updateRemainingTime, 60000);
});
</script>

    <?php if ($currentView === 'list_projects'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="project-header-section">
                    <h5 class="project-section-title">
                        <i class="bi bi-briefcase-fill"></i> 
                        <?php 
                            if (isset($_GET['view'])) {
                                if ($_GET['view'] === 'available-projects') {
                                    echo 'Available Projects';
                                } elseif ($_GET['view'] === 'my-applications') {
                                    echo 'My Applications';
                                } else {
                                    echo 'My Projects';
                                }
                            } else {
                                echo 'My Projects';
                            }
                        ?>
                    </h5>
                    
                    <!-- Onglets de navigation -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link <?php echo empty($_GET['view']) || $_GET['view'] === 'my-projects' ? 'active' : ''; ?>" 
                               href="?page=projects&view=my-projects">
                                <i class="bi bi-person"></i> My Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($_GET['view']) && $_GET['view'] === 'available-projects' ? 'active' : ''; ?>" 
                               href="?page=projects&view=available-projects">
                                <i class="bi bi-search"></i> Available Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo isset($_GET['view']) && $_GET['view'] === 'my-applications' ? 'active' : ''; ?>" 
                               href="?page=projects&view=my-applications">
                                <i class="bi bi-clipboard-check"></i> My Applications
                            </a>
                        </li>
                    </ul>

                    <?php if (empty($_GET['view']) || $_GET['view'] === 'my-projects'): ?>
                    <a href="?page=projects&new=true" class="create-project-btn">
                        <i class="bi bi-plus-circle"></i> New Project
                    </a>
                    <?php endif; ?>
                </div>
                
                <div class="card-body p-0">
                    <?php if (isset($_GET['view']) && $_GET['view'] === 'my-applications'): ?>
                    <!-- My Applications View -->
                    <?php 
                    if (empty($userCandidatures)): 
                    ?>
                    <div class="no-projects">
                        <i class="bi bi-clipboard-x no-projects-icon"></i>
                        <h4>No Applications Found</h4>
                        <p>You haven't applied to any projects yet.</p>
                        <a href="?page=projects&view=available-projects" class="btn btn-primary">
                            <i class="bi bi-search"></i> Browse Available Projects
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="applications-container">
                        <div class="table-responsive">
                            <table class="table table-hover application-table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Budget</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userCandidatures as $application): 
                                        // Determine status badge class
                                        $statusClass = match($application['status']) {
                                            'accepted' => 'success',
                                            'rejected' => 'danger',
                                            'expired' => 'secondary',
                                            default => 'warning'
                                        };
                                        
                                        // Format date
                                        $submittedDate = date('M d, Y', strtotime($application['created_at']));
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="?page=projects&project_id=<?php echo $application['project_id']; ?>" class="fw-bold text-decoration-none">
                                                <?php echo htmlspecialchars($application['project_title']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo ucfirst($application['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $submittedDate; ?></td>
                                        <td class="fw-bold">$<?php echo number_format($application['budget'], 2); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="?page=projects&project_id=<?php echo $application['project_id']; ?>" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View Project
                                                </a>
                                                <?php if ($application['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-outline-danger cancel-application" data-application-id="<?php echo $application['id']; ?>">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php elseif (empty($projects)): ?>
                    <!-- No Projects View -->
                    <div class="no-projects">
                        <i class="bi bi-briefcase-fill no-projects-icon"></i>
                        <h4>No Projects Found</h4>
                        <?php if (empty($_GET['view']) || $_GET['view'] === 'my-projects'): ?>
                            <p>You haven't created any projects yet. Start by creating your first project.</p>
                            <a href="?page=projects&new=true" class="create-project-btn">
                                <i class="bi bi-plus-circle"></i> Create Your First Project
                            </a>
                        <?php else: ?>
                            <p>There are no available projects at the moment.</p>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <!-- Projects List View -->
                    <div class="projects-container" id="projects-container">
                        <?php foreach ($projects as $project): ?>
                        <div class="project-card" id="project-<?php echo $project['id']; ?>">
                            <div class="project-header">
                                <h5 class="project-title">
                                    <a href="?page=projects&project_id=<?php echo $project['id']; ?>">
                                        <?php echo htmlspecialchars($project['title']); ?>
                                    </a>
                                </h5>
                                <div class="project-badges">
                                    <span class="project-status-badge status-<?php echo $project['status']; ?>">
                                        <i class="bi bi-circle-fill me-1"></i>
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                    <span class="project-status-badge priority-<?php echo $project['priority']; ?>">
                                        <i class="bi bi-flag-fill me-1"></i>
                                        <?php echo ucfirst($project['priority']); ?> Priority
                                    </span>
                                    <?php if (!empty($project['budget'])): ?>
                                    <div class="project-budget-display">
                                        <i class="bi bi-cash"></i>
                                        $<?php echo number_format($project['budget'], 2); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="project-content">
                                <p class="project-excerpt">
                                    <?php 
                                    $excerpt = strlen($project['description']) > 120 ? 
                                        substr($project['description'], 0, 120) . '...' : 
                                        $project['description'];
                                    echo htmlspecialchars($excerpt); 
                                    ?>
                                </p>
                                <?php if (!empty($project['client_name'])): ?>
                                <div class="client-info">
                                    <i class="bi bi-person"></i> Client: <?php echo htmlspecialchars($project['client_name']); ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($_GET['view']) && $_GET['view'] === 'available-projects'): ?>
                                <div class="owner-info">
                                    <i class="bi bi-person-circle"></i> Posted by: 
                                    <?php echo htmlspecialchars($project['first_name'] . ' ' . $project['last_name']); ?>
                                </div>
                                <?php endif; ?>

                                <div class="project-progress">
                                    <?php 
                                    $progressPercent = match($project['status']) {
                                        'completed' => 100,
                                        'in-progress' => 50,
                                        'pending' => 10,
                                        default => 0
                                    };
                                    ?>
                                    <div class="project-progress-bar" style="width: <?php echo $progressPercent; ?>%"></div>
                                </div>
                            </div>
                            <div class="project-footer">
                                <div class="project-meta">
                                    <div class="project-date">
                                        <i class="bi bi-calendar-event"></i> 
                                        <span>Created: <?php echo date('M d, Y', strtotime($project['created_at'])); ?></span>
                                    </div>
                                    <?php if (!empty($project['end_date'])): ?>
                                    <div class="project-date">
                                        <i class="bi bi-calendar-check"></i> 
                                        <span>Due: <?php echo date('M d, Y', strtotime($project['end_date'])); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="project-actions">
                                    <a href="?page=projects&project_id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary" title="View Project">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if ($userEmail === $project['user_email']): ?>
                                    <button class="btn btn-sm btn-danger delete-project" data-project-id="<?php echo $project['id']; ?>" title="Delete Project">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-success apply-project" data-project-id="<?php echo $project['id']; ?>" data-bs-toggle="modal" data-bs-target="#applyProjectModal" title="Apply">
                                        <i class="bi bi-send"></i> Apply
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php elseif ($currentView === 'new_project'): ?>
    <!-- New Project Form -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <div class="card ticket-form-card">
                <div class="card-header ticket-form-header">
                    <h5 class="ticket-form-title"><i class="bi bi-briefcase-fill"></i> Create New Project</h5>
                </div>
                <div class="card-body ticket-form-body">
                    <form method="post" action="" id="newProjectForm">
                        <input type="hidden" name="action" value="create_project">
                        
                        <!-- Inputs without required attribute -->
                        <div class="ticket-form-section">
                            <label for="title" class="ticket-form-label"><i class="bi bi-pencil-square"></i> Title *</label>
                            <input type="text" class="form-control ticket-form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="row ticket-form-section">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="client_name" class="ticket-form-label"><i class="bi bi-person"></i> Client Name *</label>
                                <input type="text" class="form-control ticket-form-control <?php echo isset($errors['client_name']) ? 'is-invalid' : ''; ?>" 
                                    id="client_name" name="client_name" value="<?php echo htmlspecialchars($_POST['client_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="budget" class="ticket-form-label"><i class="bi bi-cash"></i> Budget *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control ticket-form-control <?php echo isset($errors['budget']) ? 'is-invalid' : ''; ?>" 
                                        id="budget" name="budget" value="<?php echo htmlspecialchars($_POST['budget'] ?? ''); ?>" 
                                        step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row ticket-form-section">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="start_date" class="ticket-form-label"><i class="bi bi-calendar-event"></i> Start Date *</label>
                                <input type="date" class="form-control ticket-form-control <?php echo isset($errors['start_date']) ? 'is-invalid' : ''; ?>" 
                                    id="start_date" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="ticket-form-label"><i class="bi bi-calendar-check"></i> Due Date *</label>
                                <input type="date" class="form-control ticket-form-control <?php echo isset($errors['end_date']) ? 'is-invalid' : ''; ?>" 
                                    id="end_date" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="ticket-form-section">
                            <label class="ticket-form-label"><i class="bi bi-flag"></i> Priority *</label>
                            <div class="priority-options <?php echo isset($errors['priority']) ? 'is-invalid' : ''; ?>">
                                <div class="priority-option">
                                    <input type="radio" class="priority-radio" name="priority" id="priority-low" value="low" 
                                        <?php echo ($_POST['priority'] ?? '') === 'low' ? 'checked' : ''; ?>>
                                    <label for="priority-low" class="priority-label priority-low">
                                        <span class="priority-indicator"></span>
                                        <span class="priority-label-text">Low</span>
                                    </label>
                                </div>
                                <div class="priority-option">
                                    <input type="radio" class="priority-radio" name="priority" id="priority-medium" value="medium" 
                                        <?php echo ($_POST['priority'] ?? '') === 'medium' ? 'checked' : ''; ?>>
                                    <label for="priority-medium" class="priority-label priority-medium">
                                        <span class="priority-indicator"></span>
                                        <span class="priority-label-text">Medium</span>
                                    </label>
                                </div>
                                <div class="priority-option">
                                    <input type="radio" class="priority-radio" name="priority" id="priority-high" value="high" 
                                        <?php echo ($_POST['priority'] ?? '') === 'high' ? 'checked' : ''; ?>>
                                    <label for="priority-high" class="priority-label priority-high">
                                        <span class="priority-indicator"></span>
                                        <span class="priority-label-text">High</span>
                                    </label>
                                </div>
                            </div>
                            <?php if (isset($errors['priority'])): ?>
                                <div class="invalid-feedback d-block"><?php echo $errors['priority']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Inputs without required attribute -->
                        <div class="ticket-form-section">
                            <label for="description" class="ticket-form-label"><i class="bi bi-chat-left-text"></i> Description *</label>
                            <textarea class="form-control ticket-form-control ticket-form-textarea <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" 
                                id="description" 
                                name="description" 
                                rows="6"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <div class="invalid-feedback" id="descriptionError">Veuillez remplir ce champ</div>
                            <small class="form-text text-muted">Minimum 10 caractères</small>
                        </div>
                        
                        <div class="ticket-form-footer">
                            <a href="?page=projects" class="btn cancel-btn">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn ticket-submit-btn">
                                <i class="bi bi-save"></i> Create Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php elseif ($currentView === 'view_project' && $selectedProject): ?>
    <!-- View Project Details -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card ticket-view-card">
                <div class="ticket-view-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="ticket-view-title"><?php echo htmlspecialchars($selectedProject['title']); ?></h5>
                        <div class="ticket-view-badges">
                            <span class="badge bg-<?php echo $selectedProject['status'] === 'completed' ? 'success' : ($selectedProject['status'] === 'in-progress' ? 'warning' : ($selectedProject['status'] === 'cancelled' ? 'danger' : 'secondary')); ?>">
                                <?php echo ucfirst($selectedProject['status']); ?>
                            </span>
                            <span class="badge bg-<?php echo $selectedProject['priority'] === 'high' ? 'danger' : ($selectedProject['priority'] === 'medium' ? 'warning' : 'info'); ?>">
                                <?php echo ucfirst($selectedProject['priority']); ?> Priority
                            </span>
                            <?php if (!empty($selectedProject['budget'])): ?>
                            <span class="badge bg-primary">
                                Budget: $<?php echo number_format($selectedProject['budget'], 2); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex">
                        <?php if ($userEmail !== $selectedProject['user_email']): ?>
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#applyProjectModal">
                            <i class="bi bi-send"></i> Apply
                        </button>
                        <?php else: ?>
                        <form method="post" class="me-2">
                            <input type="hidden" name="project_id" value="<?php echo $selectedProject['id']; ?>">
                            <input type="hidden" name="action" value="delete_project">
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                <i class="bi bi-trash"></i> Delete Project
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="?page=projects" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back to Projects
                        </a>
                    </div>
                </div>
                <div class="ticket-view-body">
                    <!-- Project Details Section -->
                    <div class="project-details mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="bi bi-info-circle"></i> Project Information</h6>
                                <div class="project-info-item">
                                    <strong>Client:</strong> <?php echo !empty($selectedProject['client_name']) ? htmlspecialchars($selectedProject['client_name']) : 'N/A'; ?>
                                </div>
                                <div class="project-info-item">
                                    <strong>Created:</strong> <?php echo date('F j, Y', strtotime($selectedProject['created_at'])); ?>
                                </div>
                                <?php if (!empty($selectedProject['start_date'])): ?>
                                <div class="project-info-item">
                                    <strong>Start Date:</strong> <?php echo date('F j, Y', strtotime($selectedProject['start_date'])); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($selectedProject['end_date'])): ?>
                                <div class="project-info-item">
                                    <strong>Due Date:</strong> <?php echo date('F j, Y', strtotime($selectedProject['end_date'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="bi bi-gear"></i> Project Actions</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProjectModal">
                                        <i class="bi bi-pencil"></i> Edit Project
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                        <i class="bi bi-plus-circle"></i> Add Task
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-flag"></i> Change Status
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                            <li><a class="dropdown-item status-option" data-project-id="<?php echo $selectedProject['id']; ?>" data-status="pending" href="#">Pending</a></li>
                                            <li><a class="dropdown-item status-option" data-project-id="<?php echo $selectedProject['id']; ?>" data-status="in-progress" href="#">In Progress</a></li>
                                            <li><a class="dropdown-item status-option" data-project-id="<?php echo $selectedProject['id']; ?>" data-status="completed" href="#">Completed</a></li>
                                            <li><a class="dropdown-item status-option" data-project-id="<?php echo $selectedProject['id']; ?>" data-status="cancelled" href="#">Cancelled</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="project-description mt-4">
                            <h6 class="mb-3"><i class="bi bi-file-text"></i> Description</h6>
                            <div class="p-3 bg-light-gray rounded">
                                <?php echo nl2br(htmlspecialchars($selectedProject['description'])); ?>
                            </div>
                        </div>

                        <?php if ($userEmail === $selectedProject['user_email']): ?>
                        <!-- Section des candidatures -->
                        <div class="project-candidatures mt-4">
                            <h6 class="mb-3"><i class="bi bi-people"></i> Applications</h6>
                            <?php 
                            $candidatures = $projectModel->getProjectCandidatures($selectedProject['id']);
                            if (empty($candidatures)): 
                            ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> No applications received yet.
                            </div>
                            <?php else: ?>
                            <div class="candidatures-list">
                                <?php foreach ($candidatures as $candidature): ?>
                                <div class="candidature-card">
                                    <div class="candidature-header">
                                        <div class="candidature-user">
                                            <img src="<?php echo !empty($candidature['profile_image']) ? htmlspecialchars($candidature['profile_image']) : 'assets/images/default-avatar.png'; ?>" 
                                                 alt="Profile photo" class="rounded-circle">
                                            <div class="candidature-user-info">
                                                <h6><?php echo htmlspecialchars($candidature['first_name'] . ' ' . $candidature['last_name']); ?></h6>
                                                <span class="text-muted small"><?php echo htmlspecialchars($candidature['user_email']); ?></span>
                                            </div>
                                        </div>
                                        <div class="candidature-status">
                                            <span class="badge bg-<?php 
                                                echo match($candidature['status']) {
                                                    'accepted' => 'success',
                                                    'rejected' => 'danger',
                                                    default => 'warning'
                                                };
                                            ?>">
                                                <?php echo ucfirst($candidature['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="candidature-content">
                                        <div class="candidature-message">
                                            <?php echo nl2br(htmlspecialchars($candidature['message'])); ?>
                                        </div>
                                        <div class="candidature-budget">
                                            Proposed budget: <strong>$<?php echo number_format($candidature['budget'], 2); ?></strong>
                                        </div>
                                        <div class="candidature-date text-muted small">
                                            <i class="bi bi-clock"></i> 
                                            Applied on <?php echo date('m/d/Y \a\t H:i', strtotime($candidature['created_at'])); ?>
                                            <span class="remaining-time" data-created-at="<?php echo $candidature['created_at']; ?>" data-expiration-delay="<?php echo $candidature['expiration_delay'] ?? 48; ?>"></span>
                                        </div>
                                    </div>
                                    <?php if ($candidature['status'] === 'pending'): ?>
                                    <div class="candidature-actions">
                                        <button type="button" class="btn btn-success btn-sm accept-candidature" 
                                                data-candidature-id="<?php echo $candidature['id']; ?>">
                                            <i class="bi bi-check-lg"></i> Accept
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm reject-candidature" 
                                                data-candidature-id="<?php echo $candidature['id']; ?>">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Project Tasks Section -->
                    <div class="project-tasks mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="bi bi-list-check"></i> Project Tasks</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                <i class="bi bi-plus-circle"></i> Add Task
                            </button>
                        </div>
                        
                        <?php if (empty($projectTasks)): ?>
                        <div class="empty-tasks-container">
                            <div class="empty-tasks-illustration">
                                <i class="bi bi-check2-square"></i>
                            </div>
                            <h6>No Tasks Yet</h6>
                            <p>Start adding tasks to track progress on this project</p>
                        </div>
                        <?php else: ?>
                        <div class="task-filter-bar mb-3">
                            <div class="btn-group" role="group" aria-label="Task filters">
                                <button type="button" class="btn btn-outline-secondary btn-sm active task-filter" data-filter="all">
                                    All Tasks <span class="task-count"><?php echo count($projectTasks); ?></span>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm task-filter" data-filter="pending">
                                    Pending
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm task-filter" data-filter="in-progress">
                                    In Progress
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm task-filter" data-filter="completed">
                                    Completed
                                </button>
                            </div>
                            <div class="task-sort">
                                <select class="form-select form-select-sm" id="taskSort">
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="due-date">Due Date</option>
                                </select>
                            </div>
                        </div>

                        <div class="task-list">
                            <?php foreach ($projectTasks as $task): ?>
                            <div class="task-item p-3 mb-3 border rounded task-status-<?php echo $task['status']; ?>" data-task-status="<?php echo $task['status']; ?>" data-task-id="<?php echo $task['id']; ?>">
                                <div class="task-header d-flex align-items-start">
                                    <div class="task-checkbox me-2">
                                        <div class="form-check">
                                            <input class="form-check-input task-check" type="checkbox" value="" id="task-<?php echo $task['id']; ?>" <?php echo $task['status'] === 'completed' ? 'checked' : ''; ?> data-task-id="<?php echo $task['id']; ?>">
                                            <label class="form-check-label" for="task-<?php echo $task['id']; ?>"></label>
                                        </div>
                                    </div>
                                    <div class="task-content flex-grow-1">
                                        <h6 class="task-title mb-1 <?php echo $task['status'] === 'completed' ? 'text-decoration-line-through' : ''; ?>"><?php echo htmlspecialchars($task['title']); ?></h6>
                                        <?php if (!empty($task['description'])): ?>
                                        <div class="task-description text-muted mb-2 small">
                                            <?php 
                                            $shortDesc = strlen($task['description']) > 120 ? substr($task['description'], 0, 120) . '...' : $task['description'];
                                            echo nl2br(htmlspecialchars($shortDesc)); 
                                            ?>
                                            <?php if (strlen($task['description']) > 120): ?>
                                            <a href="#" class="view-more-link small" data-bs-toggle="modal" data-bs-target="#taskDetailModal" data-task-id="<?php echo $task['id']; ?>" data-task-title="<?php echo htmlspecialchars($task['title']); ?>" data-task-description="<?php echo htmlspecialchars($task['description']); ?>">Read more</a>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="task-controls ms-2">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" type="button" id="taskDropdown<?php echo $task['id']; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="taskDropdown<?php echo $task['id']; ?>">
                                                <li><a class="dropdown-item task-status-option" href="#" data-task-id="<?php echo $task['id']; ?>" data-status="pending"><i class="bi bi-pause-circle me-2"></i>Mark as Pending</a></li>
                                                <li><a class="dropdown-item task-status-option" href="#" data-task-id="<?php echo $task['id']; ?>" data-status="in-progress"><i class="bi bi-play-circle me-2"></i>Mark as In Progress</a></li>
                                                <li><a class="dropdown-item task-status-option" href="#" data-task-id="<?php echo $task['id']; ?>" data-status="completed"><i class="bi bi-check-circle me-2"></i>Mark as Completed</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item task-edit" href="#" data-task-id="<?php echo $task['id']; ?>"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                                <li><a class="dropdown-item task-delete text-danger" href="#" data-task-id="<?php echo $task['id']; ?>"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="task-meta d-flex flex-wrap gap-3 mt-2">
                                    <?php if (!empty($task['assigned_to'])): ?>
                                    <div class="task-meta-item">
                                        <i class="bi bi-person text-primary"></i>
                                        <span class="small"><?php echo htmlspecialchars($task['assigned_to']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($task['due_date'])): ?>
                                    <div class="task-meta-item">
                                        <i class="bi bi-calendar-check text-danger"></i>
                                        <span class="small"><?php echo date('M d, Y', strtotime($task['due_date'])); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="task-meta-item">
                                        <i class="bi bi-clock-history text-secondary"></i>
                                        <span class="small"><?php echo date('M d, Y', strtotime($task['created_at'])); ?></span>
                                    </div>
                                    
                                    <div class="task-meta-item ms-auto">
                                        <span class="badge bg-<?php echo $task['status'] === 'completed' ? 'success' : ($task['status'] === 'in-progress' ? 'warning' : 'secondary'); ?>">
                                            <?php echo ucfirst($task['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Task pagination and summary -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="task-summary small text-muted">
                                <span id="taskCount"><?php echo count($projectTasks); ?></span> tasks total
                            </div>
                            <div class="task-pagination">
                                <button class="btn btn-sm btn-outline-secondary disabled">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span class="mx-2 small">Page 1</span>
                                <button class="btn btn-sm btn-outline-secondary disabled">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Task Detail Modal -->
                    <div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taskDetailModalLabel">Task Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6 id="taskDetailTitle" class="mb-3"></h6>
                                    <div class="task-detail-description mb-3">
                                        <p id="taskDetailDescription"></p>
                                    </div>
                                    <div class="task-detail-meta">
                                        <!-- Task metadata will be inserted here dynamically -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary task-detail-edit">Edit Task</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="editProjectForm">
                        <input type="hidden" name="action" value="update_project">
                        <input type="hidden" name="project_id" value="<?php echo $selectedProject['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" value="<?php echo htmlspecialchars($selectedProject['title']); ?>" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_client_name" class="form-label">Client Name</label>
                                <input type="text" class="form-control" id="edit_client_name" name="client_name" value="<?php echo htmlspecialchars($selectedProject['client_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_budget" class="form-label">Budget</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="edit_budget" name="budget" value="<?php echo htmlspecialchars($selectedProject['budget'] ?? ''); ?>" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" value="<?php echo $selectedProject['start_date'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_end_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" value="<?php echo $selectedProject['end_date'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status">
                                    <option value="pending" <?php echo $selectedProject['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="in-progress" <?php echo $selectedProject['status'] === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="completed" <?php echo $selectedProject['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $selectedProject['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select class="form-select" id="edit_priority" name="priority">
                                    <option value="low" <?php echo $selectedProject['priority'] === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo $selectedProject['priority'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $selectedProject['priority'] === 'high' ? 'selected' : ''; ?>>High</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="5" required><?php echo htmlspecialchars($selectedProject['description']); ?></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editProjectForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="addTaskForm">
                        <input type="hidden" name="action" value="add_task">
                        <input type="hidden" name="project_id" value="<?php echo $selectedProject['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="task_title" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="task_title" name="task_title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="task_description" class="form-label">Description</label>
                            <textarea class="form-control" id="task_description" name="task_description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign To (Email)</label>
                            <input type="email" class="form-control" id="assigned_to" name="assigned_to">
                        </div>
                        
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addTaskForm" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php elseif ($currentView === 'view_project'): ?>
    <!-- Invalid Project -->
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> The requested project was not found or you don't have permission to view it.
        <a href="?page=projects" class="alert-link">Return to projects list</a>
    </div>
    <?php endif; ?>
</div>

<!-- Modal de candidature -->
<div class="modal fade" id="applyProjectModal" tabindex="-1" aria-labelledby="applyProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyProjectModalLabel">Apply for Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="applyProjectForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="apply_project">
                    <input type="hidden" name="project_id" value="">
                    
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Motivation Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" 
                            placeholder="Describe your experience and why you are the best candidate..."></textarea>
                        <div class="invalid-feedback" id="messageError"></div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="budget_proposal" class="form-label">Proposed Budget *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="budget_proposal" name="budget_proposal">
                        </div>
                        <div class="form-text">Enter the amount you propose for this project.</div>
                        <div class="invalid-feedback" id="budgetError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cv_file" class="form-label">Your Resume (PDF) *</label>
                        <input type="file" class="form-control" id="cv_file" name="cv_file">
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max 5MB)</div>
                        <div class="invalid-feedback" id="cvError"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Project specific styles */
.client-info {
    font-size: 0.85rem;
    color: var(--accent);
    margin-bottom: 0.5rem;
}

.project-info-item {
    margin-bottom: 0.5rem;
}

.bg-light-gray {
    background-color: var(--light-gray);
}

[data-bs-theme="dark"] .bg-light-gray {
    background-color: rgba(255,255,255,0.05);
}

/* Project Cards - Dedicated styling separate from support tickets */
.projects-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.25rem;
    padding: 1.5rem;
}

.project-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.08);
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
}

[data-bs-theme="dark"] .project-card {
    background-color: var(--accent-dark);
    border-color: rgba(255,255,255,0.05);
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

[data-bs-theme="dark"] .project-card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.project-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 40px 40px 0;
    border-color: transparent var(--light-gray) transparent transparent;
    transition: border-color 0.3s ease;
}

.project-card:hover::after {
    border-color: transparent var(--primary) transparent transparent;
}

[data-bs-theme="dark"] .project-card:hover::after {
    border-color: transparent var(--secondary) transparent transparent;
}

.project-header {
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.badge-primary {
    background: linear-gradient(135deg, var(--primary), #4a6f91);
    color: white;
}

.badge-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, #dc3545, #bb2d3b);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, #ffc107, #e5ac06);
    color: #212529;
}

.badge-info {
    background: linear-gradient(135deg, #0dcaf0, #0bacca);
    color: #212529;
}

.project-content {
    padding: 1.25rem;
    flex: 1;
}

.project-excerpt {
    color: var(--accent);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.project-footer {
    padding: 1.25rem;
    border-top: 1px solid rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

[data-bs-theme="dark"] .project-footer {
    border-top-color: rgba(255,255,255,0.05);
}

.project-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.project-date {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: var(--accent);
}

.project-date i {
    color: var(--primary);
    font-size: 0.9rem;
}

[data-bs-theme="dark"] .project-date i {
    color: var(--secondary);
}

.project-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.no-projects {
    text-align: center;
    padding: 3rem 1.5rem;
    background-color: white;
    border-radius: 0 0 var(--radius-md) var(--radius-md);
}

[data-bs-theme="dark"] .no-projects {
    background-color: var(--accent-dark);
}

.no-projects-icon {
    font-size: 3.5rem;
    color: var (--secondary);
    opacity: 0.4;
    margin-bottom: 1rem;
}

.project-header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background-color: white;
    border-radius: var(--radius-md) var (--radius-md) 0 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .project-header-section {
    background-color: var(--accent-dark);
    border-bottom-color: rgba(255,255,255,0.05);
}

.project-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.3rem;
    color: var(--accent-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.project-section-title i {
    margin-right: 0.5rem;
    color: var(--primary);
}

[data-bs-theme="dark"] .project-section-title {
    color: var(--light);
}

[data-bs-theme="dark"] .project-section-title i {
    color: var(--secondary);
}

.create-project-btn {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.95rem;
    box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.25);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.create-project-btn i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.create-project-btn:hover {
    background-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(var(--primary-rgb), 0.3);
    color: white;
}

[data-bs-theme="dark"] .create-project-btn {
    background-color: var(--secondary);
    box-shadow: 0 4px 10px rgba(143, 179, 222, 0.25);
}

[data-bs-theme="dark"] .create-project-btn:hover {
    background-color: var(--primary);
    box-shadow: 0 6px 15px rgba(143, 179, 222, 0.3);
}

/* Progress Bar for projects */
.project-progress {
    height: 8px;
    border-radius: 4px;
    background-color: rgba(0,0,0,0.05);
    margin: 0.75rem 0;
    overflow: hidden;
}

[data-bs-theme="dark"] .project-progress {
    background-color: rgba(255,255,255,0.05);
}

.project-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), #4a6f91);
    transition: width 0.5s ease;
}

[data-bs-theme="dark"] .project-progress-bar {
    background: linear-gradient(90deg, var(--secondary), #4a80b3);
}

/* Enhanced Task UI */
.task-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    margin-bottom: 1.25rem;
}

[data-bs-theme="dark"] .task-filter-bar {
    border-bottom-color: rgba(255,255,255,0.05);
}

.task-sort {
    width: 150px;
}

.task-count {
    background-color: var(--primary);
    color: white;
    border-radius: 50px;
    padding: 0.1rem 0.5rem;
    font-size: 0.7rem;
    margin-left: 0.5rem;
}

[data-bs-theme="dark"] .task-count {
    background-color: var(--secondary);
}

.task-item {
    border-radius: var (--radius-md) !important;
    transition: all 0.3s ease;
    border-color: rgba(0,0,0,0.08) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    position: relative;
    overflow: hidden;
}

.task-item:hover {
    border-color: rgba(var(--primary-rgb), 0.3) !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .task-item {
    border-color: rgba(255,255,255,0.05) !important;
    background-color: var(--accent-dark);
}

[data-bs-theme="dark"] .task-item:hover {
    border-color: rgba(143, 179, 222, 0.3) !important;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.task-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: #6c757d;
}

.task-status-completed::before {
    background-color: #198754;
}

.task-status-in-progress::before {
    background-color: #ffc107;
}

.task-status-completed {
    background-color: rgba(25, 135, 84, 0.05);
}

.task-checkbox .form-check-input {
    border-radius: 50%;
    width: 1.2rem;
    height: 1.2rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.task-checkbox .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.task-checkbox .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.btn-icon {
    background: transparent;
    border: none;
    color: var(--accent);
    padding: 0.25rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-icon:hover {
    background-color: var(--light-gray);
}

[data-bs-theme="dark"] .btn-icon:hover {
    background-color: rgba(255,255,255,0.1);
}

.task-meta-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--accent);
}

.task-meta-item i {
    font-size: 0.85rem;
}

.view-more-link {
    color: var (--primary);
    text-decoration: none;
    white-space: nowrap;
    margin-left: 0.35rem;
}

[data-bs-theme="dark"] .view-more-link {
    color: var(--secondary);
}

.empty-tasks-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 3rem;
    background-color: rgba(0,0,0,0.02);
    border-radius: var (--radius-md);
    margin: 1rem 0;
}

[data-bs-theme="dark"] .empty-tasks-container {
    background-color: rgba(255,255,255,0.02);
}

.empty-tasks-illustration {
    font-size: 3.5rem;
    color: var(--primary);
    opacity: 0.2;
    margin-bottom: 1rem;
}

[data-bs-theme="dark"] .empty-tasks-illustration {
    color: var(--secondary);
}

.task-pagination button {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Task Details Modal styling */
.task-detail-description {
    background-color: var(--light-gray);
    padding: 1rem;
    border-radius: var(--radius-sm);
}

[data-bs-theme="dark"] .task-detail-description {
    background-color: rgba(255,255,255,0.05);
}

/* Add animation for notification toasts */
.notification-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    min-width: 250px;
    z-index: 2000;
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    border-radius: var(--radius-md);
    transition: opacity 0.5s ease;
    animation: slideIn 0.3s ease;
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

/* Form validation styles */
.form-control.is-invalid {
    border-color: #dc3545 !important;
    background-color: rgba(220, 53, 69, 0.05) !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.form-control.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.priority-options.unfilled {
    border: 1px solid #dc3545;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: rgba(220, 53, 69, 0.05);
}

/* Dark theme support */
[data-bs-theme="dark"] .form-control.is-invalid {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

/* End of form validation styles */

/* Styles pour le formulaire de candidature */
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}

#applyProjectForm .form-control {
    border: 1px solid rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

[data-bs-theme="dark"] #applyProjectForm .form-control {
    border-color: rgba(255,255,255,0.1);
    background-color: var(--accent-dark);
}

#applyProjectForm .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
}

[data-bs-theme="dark"] #applyProjectForm .form-control:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 0.2rem rgba(143, 179, 222, 0.25);
}

#applyProjectForm .form-text {
    color: var(--accent);
    font-size: 0.85rem;
}

/* Animation pour le spinner du bouton */
@keyframes spinner {
    to { transform: rotate(360deg); }
}

.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner .75s linear infinite;
}

/* Styles des candidatures */
.project-candidatures {
    border-top: 1px solid rgba(0,0,0,0.05);
    padding-top: 1.5rem;
}

[data-bs-theme="dark"] .project-candidatures {
    border-top-color: rgba(255,255,255,0.05);
}

.candidatures-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Applications table styling */
.applications-container {
    padding: 1.5rem;
}

.application-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: white;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table {
    background-color: var(--accent-dark);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.application-table th {
    background-color: rgba(0,0,0,0.02);
    color: var(--accent-dark);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1rem 1.25rem;
    border-bottom: 2px solid rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table th {
    background-color: rgba(255,255,255,0.05);
    color: var(--light);
    border-bottom-color: rgba(255,255,255,0.1);
}

.application-table td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table td {
    border-bottom-color: rgba(255,255,255,0.05);
}

.application-table tr:last-child td {
    border-bottom: none;
}

.application-table tr:hover td {
    background-color: rgba(0,0,0,0.01);
}

[data-bs-theme="dark"] .application-table tr:hover td {
    background-color: rgba(255,255,255,0.02);
}

.application-table .btn-group {
    white-space: nowrap;
}

.notification-progress::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background: rgba(255, 255, 255, 0.7);
    animation: progress 5s linear forwards;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.notification-content {
    position: relative;
    padding-right: 20px;
}

.notification-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    margin-right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-message {
    font-size: 0.95rem;
    line-height: 1.4;
    font-weight: 500;
    flex-grow: 1;
}

/* Styles spécifiques pour les notifications de candidature */
.candidature-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 320px;
    max-width: 400px;
    padding: 1.25rem;
    border-radius: 0.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease-out forwards;
    opacity: 0;
    transform: translateX(100%);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.candidature-notification.show {
    opacity: 1;
    transform: translateX(0);
}

.candidature-notification .notification-header {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
}

.candidature-notification .notification-title {
    font-weight: 600;
    margin-bottom: 0;
    flex-grow: 1;
}

.candidature-notification .notification-icon {
    font-size: 1.5rem;
    margin-right: 0.75rem;
}

.candidature-notification .notification-content {
    margin-bottom: 0.5rem;
}

.candidature-notification .notification-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.candidature-notification .notification-time {
    color: rgba(0, 0, 0, 0.6);
}

.candidature-notification .badge {
    padding: 0.35em 0.65em;
    font-weight: 600;
}

/* Variantes de style selon le statut */
.candidature-notification.accepted {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.candidature-notification.rejected {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.candidature-notification.pending {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    color: #856404;
}

/* Animation du compte à rebours */
.candidature-notification .countdown {
    font-weight: 700;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

@keyframes slideIn {
    0% { transform: translateX(100%); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    0% { transform: translateX(0); opacity: 1; }
    100% { transform: translateX(100%); opacity: 0; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newProjectForm = document.getElementById('newProjectForm');
    if (!newProjectForm) return;

    // Reset form and errors on page load
    clearAllErrors();
    newProjectForm.reset();

    // Validation rules
    const validationRules = {
        title: {
            required: true,
            minLength: 3,
            message: 'Title must contain at least 3 characters'
        },
        description: {
            required: true,
            minLength: 10,
            message: 'Description must contain at least 10 characters'
        },
        client_name: {
            required: true,
            message: 'Client name is required'
        },
        budget: {
            required: true,
            type: 'number',
            min: 0,
            message: 'Budget must be a positive number'
        },
        start_date: {
            required: true,
            message: 'Start date is required'
        },
        end_date: {
            required: true,
            message: 'End date is required',
            validate: function(value, form) {
                const startDate = new Date(form.querySelector('#start_date').value);
                const endDate = new Date(value);
                return endDate > startDate || 'End date must be after start date';
            }
        }
    };

    // Handle real-time validation
    const formFields = newProjectForm.querySelectorAll('input:not([type="hidden"]), textarea, select');
    formFields.forEach(field => {
        // Validate on blur
        field.addEventListener('blur', () => validateField(field));
        
        // Validate on input with debounce
        field.addEventListener('input', debounce(() => {
            if (field.classList.contains('is-invalid')) {
                validateField(field);
            }
        }, 300));
    });

    // Handle priority radio buttons
    const priorityContainer = newProjectForm.querySelector('.priority-options');
    const priorityRadios = newProjectForm.querySelectorAll('input[name="priority"]');
    
    priorityRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            clearFieldError(priorityContainer);
            priorityContainer.classList.remove('unfilled');
        });
    });

    // Form submission
    newProjectForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearAllErrors();

        let isValid = true;
        const errors = [];

        // Validate all fields
        formFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        // Validate priority
        if (!this.querySelector('input[name="priority"]:checked')) {
            isValid = false;
            errors.push('Please select a priority');
            priorityContainer.classList.add('unfilled');
            displayError(priorityContainer, 'Please select a priority');
        }

        if (isValid) {
            this.submit();
        } else {
            // Show error summary
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            errorDiv.innerHTML = '<ul><li>' + errors.join('</li><li>') + '</li></ul>';
            this.insertBefore(errorDiv, this.firstChild);

            // Scroll to first error
            const firstError = this.querySelector('.is-invalid') || this.querySelector('.unfilled');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Utility functions
    function validateField(field) {
        const fieldName = field.id || field.name;
        const rules = validationRules[fieldName];
        if (!rules) return true;

        let isValid = true;
        let errorMessage = '';

        // Clear previous errors
        clearFieldError(field);

        const value = field.value.trim();

        if (rules.required && !value) {
            isValid = false;
            errorMessage = rules.message;
        }
        else if (rules.minLength && value.length < rules.minLength) {
            isValid = false;
            errorMessage = rules.message;
        }
        else if (rules.type === 'number') {
            const num = parseFloat(value);
            if (isNaN(num) || (rules.min !== undefined && num < rules.min)) {
                isValid = false;
                errorMessage = rules.message;
            }
        }
        else if (rules.validate) {
            const result = rules.validate(value, field.form);
            if (result !== true) {
                isValid = false;
                errorMessage = result;
            }
        }

        if (!isValid) {
            displayError(field, errorMessage);
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }

        return isValid;
    }

    function displayError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const container = field.parentNode;
        const errorDiv = container.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    function clearAllErrors() {
        const invalidInputs = newProjectForm.querySelectorAll('.is-invalid');
        const errorMessages = newProjectForm.querySelectorAll('.invalid-feedback');
        const generalError = newProjectForm.querySelector('.alert-danger');
        
        invalidInputs.forEach(input => input.classList.remove('is-invalid'));
        errorMessages.forEach(error => error.remove());
        if (generalError) generalError.remove();
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>

<script>
// Application form validation 
document.addEventListener('DOMContentLoaded', function() {
    const applyProjectForm = document.getElementById('applyProjectForm');
    if (!applyProjectForm) return;

    const messageField = applyProjectForm.querySelector('#message');
    const budgetField = applyProjectForm.querySelector('#budget_proposal');
    const cvField = document.getElementById('cv_file');
    const submitBtn = applyProjectForm.querySelector('button[type="submit"]');
    const spinner = submitBtn.querySelector('.spinner-border');
    let isSubmitting = false;
    let hasAttemptedSubmit = false;

    function clearErrors() {
        applyProjectForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        applyProjectForm.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
    }

    function validateField(field) {
        let isValid = true;
        let errorMessage = '';

        if (field.id === 'message') {
            if (!field.value.trim()) {
                isValid = false;
                errorMessage = 'Message is required';
            }
        } else if (field.id === 'budget_proposal') {
            const budget = parseFloat(field.value.trim());
            if (!field.value.trim()) {
                isValid = false;
                errorMessage = 'Budget is required';
            } else if (isNaN(budget) || budget <= 0) {
                isValid = false;
                errorMessage = 'Budget must be a positive number';
            }
        } else if (field.id === 'cv_file') {
            const file = field.files[0];
            if (!file) {
                isValid = false;
                errorMessage = 'Resume file is required';
            } else {
                const fileSize = file.size / 1024 / 1024; // Convert to MB
                const fileType = file.type;
                const fileName = file.name.toLowerCase();
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const allowedExtensions = ['.pdf', '.doc', '.docx'];
                
                // Check both MIME type and file extension
                const isValidType = allowedTypes.includes(fileType) || 
                                   allowedExtensions.some(ext => fileName.endsWith(ext));
                
                if (!isValidType) {
                isValid = false;
                    errorMessage = 'File must be PDF, DOC or DOCX format';
                } else if (fileSize > 5) {
                isValid = false;
                    errorMessage = 'File size must be less than 5MB';
                }
            }
        }

        const errorElement = document.getElementById(field.id + 'Error');
        if (errorElement) {
            errorElement.style.display = isValid ? 'none' : 'block';
            errorElement.textContent = errorMessage;
        }

        field.classList.toggle('is-invalid', !isValid);
        return isValid;
    }

    // Real-time validation
    [messageField, budgetField, cvField].forEach(field => {
        if (!field) return;
        
        field.addEventListener('input', () => {
            if (hasAttemptedSubmit) {
                validateField(field);
            }
        });

        field.addEventListener('blur', () => validateField(field));
    });

    // Form submission
    applyProjectForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;

        hasAttemptedSubmit = true;
        clearErrors();

        let isValid = true;
        [messageField, budgetField, cvField].forEach(field => {
            if (field && !validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            const firstError = applyProjectForm.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
            }
            return;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(this);
        formData.append('action', 'apply_project');

        fetch('index.php?page=projects', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('applyProjectModal'));
                modal.hide();
                this.reset();
                hasAttemptedSubmit = false;
                
                // Show success notification
                showNotification('Your application has been submitted successfully!', 'success');
            } else {
                // Show error notification
                showNotification(data.message || 'An error occurred while submitting your application', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while processing your request', 'danger');
        })
        .finally(() => {
            isSubmitting = false;
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });

    // Reset on modal open
    const modal = document.getElementById('applyProjectModal');
    if (modal) {
    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const projectId = button.getAttribute('data-project-id') || new URLSearchParams(window.location.search).get('project_id');
        if (projectId) {
            applyProjectForm.querySelector('input[name="project_id"]').value = projectId;
        }
        clearErrors();
        applyProjectForm.reset();
            hasAttemptedSubmit = false;
    });

        // Reset on modal close
    modal.addEventListener('hidden.bs.modal', function() {
        applyProjectForm.reset();
        clearErrors();
        isSubmitting = false;
            hasAttemptedSubmit = false;
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    });
    }
});
</script>

<style>
/* Applications table styling */
.applications-container {
    padding: 1.5rem;
}

.application-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: white;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table {
    background-color: var(--accent-dark);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.application-table th {
    background-color: rgba(0,0,0,0.02);
    color: var(--accent-dark);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1rem 1.25rem;
    border-bottom: 2px solid rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table th {
    background-color: rgba(255,255,255,0.05);
    color: var(--light);
    border-bottom-color: rgba(255,255,255,0.1);
}

.application-table td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

[data-bs-theme="dark"] .application-table td {
    border-bottom-color: rgba(255,255,255,0.05);
}

.application-table tr:last-child td {
    border-bottom: none;
}

.application-table tr:hover td {
    background-color: rgba(0,0,0,0.01);
}

[data-bs-theme="dark"] .application-table tr:hover td {
    background-color: rgba(255,255,255,0.02);
}

.application-table .btn-group {
    white-space: nowrap;
}
</style>