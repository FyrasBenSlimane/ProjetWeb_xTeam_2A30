<?php
/**
 * Project Controller
 * Handles all project operations
 */
class ProjectController {
    private $projectModel;
    private $userModel;

    public function __construct() {
        require_once __DIR__ . '/../models/ProjectModel.php';
        require_once __DIR__ . '/../models/UserModel.php';
        
        // Get database connection
        require_once __DIR__ . '/../../../config/database.php';
        $db = $GLOBALS['pdo'];
        
        $this->projectModel = new ProjectModel($db);
        $this->userModel = new UserModel($db);
    }

    /**
     * Display user's projects
     */
    public function userProjects() {
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Set page title and content variables used in layout.php
        $pageTitle = 'Projects';
        
        // Get user projects from the model
        $projects = $this->projectModel->getUserProjects($userEmail);
        
        // Handle different views
        if (isset($_GET['view'])) {
            if ($_GET['view'] === 'available-projects') {
                // Get available projects
                $availableProjects = $this->projectModel->getAvailableProjects($userEmail);
                $projects = $availableProjects; // Replace projects with available ones
            } 
            elseif ($_GET['view'] === 'my-applications') {
                // Get user's applications history
                $userCandidatures = $this->projectModel->getUserCandidatures($userEmail);
                // We'll pass this to the view
            }
        }

        // Set current view for the projects page
        $currentView = isset($_GET['project_id']) ? 'view_project' : (isset($_GET['new']) ? 'new_project' : 'list_projects');
        
        // Get single project details if viewing a specific project
        $selectedProject = null;
        $projectTasks = [];
        if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
            $projectId = $_GET['project_id'];
            $selectedProject = $this->projectModel->getProjectById($projectId, $userEmail);
            if ($selectedProject) {
                $projectTasks = $this->projectModel->getProjectTasks($projectId);
            }
        }
        
        // Make the project model available to the view
        $projectModel = $this->projectModel;
        
        // Load view - this will output the content that gets captured by ob_get_clean() in index.php
        include __DIR__ . '/../projects/project.php';
    }

    /**
     * Process project creation
     */
    public function createProject() {
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Validate and sanitize input
        $title = htmlspecialchars($_POST['title'] ?? '');
        $description = htmlspecialchars($_POST['description'] ?? '');
        $clientName = htmlspecialchars($_POST['client_name'] ?? '');
        $budget = filter_var($_POST['budget'] ?? 0, FILTER_VALIDATE_FLOAT);
        $priority = htmlspecialchars($_POST['priority'] ?? 'medium');
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        
        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required";
        }
        if (empty($description)) {
            $errors[] = "Description is required";
        }
        
        if (!empty($errors)) {
            // If there are errors, go back to form
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: index.php?page=projects&new=true');
            exit;
        }
        
        // Create project
        $result = $this->projectModel->createProject($userEmail, $title, $description, $clientName, $budget, $priority, $startDate, $endDate);
        
        if ($result) {
            $_SESSION['success'] = "Your project has been created successfully";
        } else {
            $_SESSION['errors'] = ["Failed to create your project. Please try again."];
        }
        
        header('Location: index.php?page=projects');
        exit;
    }

    /**
     * Process project update
     */
    public function updateProject() {
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Get project ID
        $projectId = $_POST['project_id'] ?? '';
        if (empty($projectId)) {
            $_SESSION['errors'] = ["Project ID is required"];
            header('Location: index.php?page=projects');
            exit;
        }
        
        // Get project data
        $data = [
            'title' => htmlspecialchars($_POST['title'] ?? ''),
            'description' => htmlspecialchars($_POST['description'] ?? ''),
            'client_name' => htmlspecialchars($_POST['client_name'] ?? ''),
            'budget' => filter_var($_POST['budget'] ?? 0, FILTER_VALIDATE_FLOAT),
            'priority' => htmlspecialchars($_POST['priority'] ?? 'medium'),
            'status' => htmlspecialchars($_POST['status'] ?? 'pending'),
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null
        ];
        
        // Update project
        $result = $this->projectModel->updateProject($projectId, $userEmail, $data);
        
        if ($result) {
            $_SESSION['success'] = "Project updated successfully";
        } else {
            $_SESSION['errors'] = ["Failed to update project. Please try again."];
        }
        
        header('Location: index.php?page=projects&project_id=' . $projectId);
        exit;
    }

    /**
     * Process project deletion
     */
    public function deleteProject() {
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Get project ID
        $projectId = $_POST['project_id'] ?? '';
        if (empty($projectId)) {
            $_SESSION['errors'] = ["Project ID is required"];
            header('Location: index.php?page=projects');
            exit;
        }
        
        // Delete project
        $result = $this->projectModel->deleteProject($projectId, $userEmail);
        
        if ($result) {
            $_SESSION['success'] = "Project deleted successfully";
        } else {
            $_SESSION['errors'] = ["Failed to delete project. Please try again."];
        }
        
        header('Location: index.php?page=projects');
        exit;
    }

    /**
     * Process adding a task to a project
     */
    public function addTask() {
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Get project ID
        $projectId = $_POST['project_id'] ?? '';
        if (empty($projectId)) {
            $_SESSION['errors'] = ["Project ID is required"];
            header('Location: index.php?page=projects');
            exit;
        }
        
        // Check if the project belongs to the user
        $project = $this->projectModel->getProjectById($projectId, $userEmail);
        if (!$project) {
            $_SESSION['errors'] = ["Project not found or you don't have permission to access it"];
            header('Location: index.php?page=projects');
            exit;
        }
        
        // Get task data
        $title = htmlspecialchars($_POST['task_title'] ?? '');
        $description = htmlspecialchars($_POST['task_description'] ?? '');
        $assignedTo = htmlspecialchars($_POST['assigned_to'] ?? '');
        $dueDate = $_POST['due_date'] ?? null;
        
        if (empty($title)) {
            $_SESSION['errors'] = ["Task title is required"];
            header('Location: index.php?page=projects&project_id=' . $projectId);
            exit;
        }
        
        // Add task
        $result = $this->projectModel->addTask($projectId, $title, $description, $assignedTo, $dueDate);
        
        if ($result) {
            $_SESSION['success'] = "Task added successfully";
        } else {
            $_SESSION['errors'] = ["Failed to add task. Please try again."];
        }
        
        header('Location: index.php?page=projects&project_id=' . $projectId);
        exit;
    }

    /**
     * Update project status via AJAX
     */
    public function updateProjectStatus() {
        // Set response header
        header('Content-Type: application/json');
        
        // Initialize response array
        $response = [
            'success' => false,
            'message' => 'Invalid request'
        ];
        
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            $response['message'] = 'User not authenticated';
            echo json_encode($response);
            exit;
        }
        
        // Get request data
        $projectId = $_POST['project_id'] ?? null;
        $status = $_POST['status'] ?? null;
        
        if (!$projectId || !$status || !in_array($status, ['pending', 'in-progress', 'completed', 'cancelled'])) {
            $response['message'] = 'Invalid request data';
            echo json_encode($response);
            exit;
        }
        
        // Update project status
        $result = $this->projectModel->updateProjectStatus($projectId, $status, $userEmail);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Project status updated successfully';
        } else {
            $response['message'] = 'Failed to update project status';
        }
        
        echo json_encode($response);
        exit;
    }

    /**
     * Delete project via AJAX
     */
    public function ajaxDeleteProject() {
        // Set response header
        header('Content-Type: application/json');
        
        // Initialize response array
        $response = [
            'success' => false,
            'message' => 'Invalid request'
        ];
        
        // Get logged in user
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            $response['message'] = 'User not authenticated';
            echo json_encode($response);
            exit;
        }
        
        // Get request data
        $projectId = $_POST['project_id'] ?? null;
        
        if (!$projectId) {
            $response['message'] = 'Project ID is required';
            echo json_encode($response);
            exit;
        }
        
        // Delete project
        $result = $this->projectModel->deleteProject($projectId, $userEmail);
        
        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Project deleted successfully';
        } else {
            $response['message'] = 'Failed to delete project';
        }
        
        echo json_encode($response);
        exit;
    }

    /**
     * Handle project application submission
     */
    public function applyToProject() {
        ob_start(); // Moved inside the method

        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];

        try {
            // Vérifier si la requête est valide
            if (!isset($_POST['project_id']) || !isset($_POST['message']) || 
                !isset($_POST['budget_proposal']) || !isset($_FILES['cv_file'])) {
                $response['message'] = 'Données manquantes';
                error_log("applyToProject: Données manquantes - " . json_encode($_POST));
                echo json_encode($response);
                exit;
            }

            // Vérifier l'authentification
            $userEmail = $_SESSION['user']['email'] ?? null;
            if (!$userEmail) {
                $response['message'] = 'Vous devez être connecté pour postuler';
                error_log("applyToProject: Utilisateur non connecté");
                echo json_encode($response);
                exit;
            }
        
            $projectId = (int)($_POST['project_id'] ?? 0);
            $message = trim(htmlspecialchars($_POST['message'] ?? ''));
            $budget = filter_var($_POST['budget_proposal'] ?? 0, FILTER_VALIDATE_FLOAT);

            // Gestion du CV
            $cvPath = null;
            if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = [
                    'application/pdf', 
                    'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if (!in_array($_FILES['cv_file']['type'], $allowedTypes)) {
                    $response['message'] = 'Format de fichier non autorisé. Veuillez utiliser PDF, DOC ou DOCX.';
                    error_log("applyToProject: Format de fichier non autorisé - " . $_FILES['cv_file']['type']);
                    echo json_encode($response);
                    exit;
                }

                if ($_FILES['cv_file']['size'] > $maxSize) {
                    $response['message'] = 'Le fichier est trop volumineux. Taille maximum : 5MB';
                    error_log("applyToProject: Fichier trop volumineux - " . $_FILES['cv_file']['size']);
                    echo json_encode($response);
                    exit;
                }

                $uploadDir = __DIR__ . '/../../../uploads/cvs/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid('cv_') . '_' . basename($_FILES['cv_file']['name']);
                $cvPath = 'Uploads/cvs/' . $fileName;
                $fullPath = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $fullPath)) {
                    $response['message'] = 'Erreur lors du téléchargement du CV';
                    error_log("applyToProject: Échec du téléchargement du CV - " . $_FILES['cv_file']['name']);
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['message'] = 'Le CV est requis';
                error_log("applyToProject: CV manquant ou erreur d'upload - " . ($_FILES['cv_file']['error'] ?? 'inconnu'));
                echo json_encode($response);
                exit;
            }

            // Validation de base
            if ($projectId <= 0) {
                $response['message'] = 'ID du projet invalide';
                error_log("applyToProject: ID projet invalide - $projectId");
                echo json_encode($response);
                exit;
            }

            // Vérifier si le projet existe et récupérer ses informations
            $sql = "SELECT user_email, status FROM projects WHERE id = ?";
            $stmt = $this->projectModel->db->prepare($sql);
            $stmt->execute([$projectId]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$project) {
                $response['message'] = 'Le projet n\'existe pas';
                error_log("applyToProject: Projet non trouvé - ID: $projectId");
                echo json_encode($response);
                exit;
            }

            // Vérifier que le projet est toujours en statut "pending"
            if ($project['status'] !== 'pending') {
                $response['message'] = 'Ce projet n\'est plus ouvert aux candidatures';
                error_log("applyToProject: Projet non ouvert - Statut: {$project['status']}, ID: $projectId");
                echo json_encode($response);
                exit;
            }

            // Check if user is not applying to their own project
            if ($project['user_email'] === $userEmail) {
                $response['message'] = 'You cannot apply to your own project';
                error_log("applyToProject: User trying to apply to their own project - User: $userEmail, Project ID: $projectId");
                echo json_encode($response);
                exit;
            }

            if (empty($message)) {
                $response['message'] = 'Le message de motivation est requis';
                error_log("applyToProject: Message de motivation vide - User: $userEmail");
                echo json_encode($response);
                exit;
            }

            if ($budget === false || $budget <= 0) {
                $response['message'] = 'Le budget proposé doit être un nombre positif';
                error_log("applyToProject: Budget invalide - $budget, User: $userEmail");
                echo json_encode($response);
                exit;
            }

            // Vérifier si l'utilisateur a déjà une candidature active
            $sql = "SELECT status FROM candidatures WHERE project_id = ? AND user_email = ? AND status IN ('pending', 'accepted')";
            $stmt = $this->projectModel->db->prepare($sql);
            $stmt->execute([$projectId, $userEmail]);
            $existingApplication = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingApplication) {
                $statusMessage = match($existingApplication['status']) {
                    'pending' => 'You already have a pending application for this project',
                    'accepted' => 'Your application has already been accepted for this project',
                    default => 'You cannot apply to this project at this time'
                };
                $response['message'] = $statusMessage;
                error_log("applyToProject: Existing application - Status: {$existingApplication['status']}, User: $userEmail, Project ID: $projectId");
                echo json_encode($response);
                exit;
            }

            // Tenter d'ajouter la candidature avec le CV
            $result = $this->projectModel->addCandidature($projectId, $userEmail, $message, $budget, $cvPath);

            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Your application has been successfully submitted. You will be notified when the client reviews your proposal.';
                $response['notification'] = [
                    'message' => 'Your application has been successfully submitted. You will be notified when the client reviews your proposal.',
                    'type' => 'success'
                ];
                $response['redirect'] = '?page=projects&view=available-projects';
                error_log("applyToProject: Candidature ajoutée avec succès - User: $userEmail, Project ID: $projectId, CV: $cvPath");
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'message' => 'Your application has been successfully submitted!'
                ];
                
                // Get project title for the notification
                $stmt = $this->projectModel->db->prepare("SELECT title FROM projects WHERE id = ?");
                $stmt->execute([$projectId]);
                $project = $stmt->fetch(PDO::FETCH_ASSOC);
                $projectTitle = $project ? $project['title'] : "Project #$projectId";
                
                // Add pending application notification
                $this->addCandidatureNotification(
                    $userEmail,
                    $result, // ID of the candidature that was just created
                    $projectTitle,
                    'pending'
                );
                
                error_log("applyToProject: Pending application notification created - User: $userEmail, Project: $projectTitle");
                
                // Send JSON response
                echo json_encode($response);
                // Clear output buffer
                ob_end_flush();
                exit;
            } else {
                // If file upload failed
                if (isset($cvPath) && $cvPath && file_exists(__DIR__ . '/../../../' . $cvPath)) {
                    unlink(__DIR__ . '/../../../' . $cvPath);
                    error_log("applyToProject: CV file deleted after failure - $cvPath");
                }
                $response['message'] = 'An error occurred while submitting your application';
                error_log("applyToProject: Failed to add application - User: $userEmail, Project ID: $projectId");
            }

        } catch (Exception $e) {
            // Delete CV file in case of unexpected error
            if (isset($cvPath) && $cvPath && file_exists(__DIR__ . '/../../../' . $cvPath)) {
                unlink(__DIR__ . '/../../../' . $cvPath);
                error_log("applyToProject: CV file deleted after exception - $cvPath");
            }
            $response['message'] = 'An unexpected error occurred';
            error_log("applyToProject: Exception - " . $e->getMessage() . ", User: " . ($userEmail ?? 'unknown'));
        }

        // Send JSON response
        echo json_encode($response);
        // Clear output buffer
        ob_end_flush();
        exit;
    }

    public function updateCandidatureStatus() {
        ob_start(); // Moved inside the method

        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];

        try {
            // Debug logs
            error_log("updateCandidatureStatus: Request received - Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("updateCandidatureStatus: POST parameters: " . json_encode($_POST));
            error_log("updateCandidatureStatus: GET parameters: " . json_encode($_GET));
            
            // Check authentication
            $userEmail = $_SESSION['user']['email'] ?? null;
            if (!$userEmail) {
                $response['message'] = 'You must be logged in';
                error_log("updateCandidatureStatus: User not logged in");
                echo json_encode($response);
                exit;
            }

            // Get data
            $candidatureId = $_POST['candidature_id'] ?? null;
            $status = $_POST['status'] ?? null;

            error_log("updateCandidatureStatus: candidatureId=$candidatureId, status=$status, userEmail=$userEmail");

            if (!$candidatureId || !in_array($status, ['accepted', 'rejected', 'expired'])) {
                $response['message'] = 'Invalid data';
                error_log("updateCandidatureStatus: Invalid data - Candidature ID: " . ($candidatureId ?? 'unknown') . ", Status: " . ($status ?? 'unknown'));
                echo json_encode($response);
                exit;
            }

            // If it's an update to "expired", anyone can do it (system or user)
            $isExpirationUpdate = ($status === 'expired');
            
            // Check that the user is the owner of the project associated with the application (except for expiration)
            $sql = "SELECT p.user_email, p.title as project_title, c.user_email as candidate_email 
                    FROM candidatures c 
                    JOIN projects p ON c.project_id = p.id 
                    WHERE c.id = ?";
            $stmt = $this->projectModel->db->prepare($sql);
            $stmt->execute([$candidatureId]);
            $candidatureInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log("updateCandidatureStatus: Candidature info - " . json_encode($candidatureInfo));

            if (!$candidatureInfo || (!$isExpirationUpdate && $candidatureInfo['user_email'] !== $userEmail)) {
                $response['message'] = 'You do not have permission to modify this application';
                error_log("updateCandidatureStatus: Unauthorized access - User: $userEmail, Candidature ID: $candidatureId");
                echo json_encode($response);
                exit;
            }

            // Update status
            $result = $this->projectModel->updateCandidatureStatus($candidatureId, $status);
            error_log("updateCandidatureStatus: Update result - " . ($result ? "Success" : "Failure"));

            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Application status successfully updated';
                
                // Add additional information for UI
                $response['candidature'] = [
                    'id' => $candidatureId,
                    'status' => $status,
                    'project_title' => $candidatureInfo['project_title']
                ];
                
                // Store notification in session for the candidate
                // This notification will be displayed when the candidate logs in
                if (isset($candidatureInfo['candidate_email'])) {
                    // Load notification model if not already loaded
                    require_once __DIR__ . '/../models/NotificationModel.php';
                    $notificationModel = new NotificationModel($this->projectModel->db);
                    
                    // Delete previous pending notifications for this candidature
                    try {
                        $sql = "DELETE FROM notifications 
                                WHERE user_email = ? 
                                AND type = 'candidature' 
                                AND linked_id = ? 
                                AND JSON_EXTRACT(data, '$.status') = 'pending'";
                        $deleteStmt = $this->projectModel->db->prepare($sql);
                        $deleteResult = $deleteStmt->execute([$candidatureInfo['candidate_email'], $candidatureId]);
                        
                        if ($deleteResult) {
                            error_log("updateCandidatureStatus: Deleted pending notifications for candidature ID: $candidatureId");
                        }
                    } catch (Exception $e) {
                        error_log("updateCandidatureStatus: Error deleting pending notifications: " . $e->getMessage());
                    }
                    
                    // Prepare notification for the candidate
                    $statusText = match($status) {
                        'accepted' => 'accepted',
                        'rejected' => 'rejected',
                        'expired' => 'expired',
                        default => ''
                    };
                    $notificationMessage = "Your application for project \"{$candidatureInfo['project_title']}\" has been $statusText.";
                    
                    // Store in candidate's session
                    // Note: This simple method stores the notification in the database
                    $notifResult = $this->addCandidatureNotification(
                        $candidatureInfo['candidate_email'], 
                        $candidatureId, 
                        $candidatureInfo['project_title'], 
                        $status
                    );
                    error_log("updateCandidatureStatus: Notification creation - " . ($notifResult ? "Success" : "Failure"));
                }
                
                error_log("updateCandidatureStatus: Status updated - Candidature ID: $candidatureId, Status: $status, User: $userEmail");
            } else {
                $response['message'] = 'Error updating status';
                error_log("updateCandidatureStatus: Update failed - Candidature ID: $candidatureId, Status: $status");
            }

        } catch (Exception $e) {
            $response['message'] = 'An unexpected error occurred';
            error_log("updateCandidatureStatus: Exception - " . $e->getMessage() . ", User: " . ($userEmail ?? 'unknown'));
        }

        error_log("updateCandidatureStatus: Response - " . json_encode($response));

        // Send JSON response
        echo json_encode($response);
        // Clear output buffer
        ob_end_flush();
        exit;
    }
    
    /**
     * Adds an application notification for a user
     */
    private function addCandidatureNotification($userEmail, $candidatureId, $projectTitle, $status) {
        try {
            // Valider le statut
            $validStatuses = ['pending', 'accepted', 'rejected', 'expired'];
            if (!in_array($status, $validStatuses)) {
                error_log("addCandidatureNotification: Invalid status '$status' for candidature: $candidatureId");
                $status = 'unknown';
            }
    
            // Données supplémentaires pour la notification
            $data = [
                'candidature_id' => $candidatureId,
                'project_title' => $projectTitle,
                'status' => $status
            ];
    
            // Définir le message en fonction du statut
            $message = match($status) {
                'pending' => "Votre candidature pour le projet \"$projectTitle\" est en attente d'examen.",
                'accepted' => "Félicitations ! Votre candidature pour le projet \"$projectTitle\" a été acceptée.",
                'rejected' => "Votre candidature pour le projet \"$projectTitle\" n'a pas été retenue.",
                'expired' => "Votre candidature pour le projet \"$projectTitle\" a expiré.",
                default => "Mise à jour de votre candidature pour le projet \"$projectTitle\"."
            };
    
            // Titre de la notification
            $title = match($status) {
                'pending' => "Nouvelle candidature - $projectTitle",
                'accepted' => "Candidature acceptée - $projectTitle",
                'rejected' => "Candidature rejetée - $projectTitle",
                'expired' => "Candidature expirée - $projectTitle",
                default => "Mise à jour de candidature - $projectTitle"
            };
    
            // Définir la date d'expiration (7 jours par défaut, 48 heures pour "pending")
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days')); // Expire dans 7 jours
            if ($status === 'pending') {
                $expiresAt = date('Y-m-d H:i:s', strtotime('+48 hours')); // Expire dans 48 heures pour "pending"
            }
    
            // Utiliser le modèle de notification pour stocker en base de données
            require_once __DIR__ . '/../models/NotificationModel.php';
            $notificationModel = new NotificationModel($this->projectModel->db);
    
            // Ajouter la notification avec allowDuplicates = true
            $result = $notificationModel->addNotification(
                $userEmail,
                'candidature',
                $title,
                $message,
                $candidatureId,
                $data,
                $expiresAt,
                true // Permettre plusieurs notifications pour la même candidature
            );
    
            if ($result === false) {
                error_log("addCandidatureNotification: Failed to add notification for user: $userEmail, candidature: $candidatureId, status: $status");
            } else {
                error_log("addCandidatureNotification: Notification added for user: $userEmail, candidature: $candidatureId, status: $status, notification ID: $result");
            }
    
            return $result;
        } catch (Exception $e) {
            error_log("addCandidatureNotification: Exception for user: $userEmail, candidature: $candidatureId, status: $status - " . $e->getMessage());
            return false;
        }
    }
    /**
     * Récupérer les candidatures d'un utilisateur pour les notifications
     */
    public function getUserCandidatures() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'candidatures' => []];

        try {
            // Vérifier l'authentification
            $userEmail = $_SESSION['user']['email'] ?? null;
            if (!$userEmail) {
                echo json_encode($response);
                exit;
            }

            // Récupérer les candidatures de l'utilisateur
            $candidatures = $this->projectModel->getUserCandidatures($userEmail);
            
            if ($candidatures) {
                $response['success'] = true;
                $response['candidatures'] = $candidatures;
            }
        } catch (Exception $e) {
            error_log("getUserCandidatures: Exception - " . $e->getMessage() . ", User: " . ($userEmail ?? 'inconnu'));
        }

        echo json_encode($response);
        exit;
    }

    /**
     * Stocker une notification de candidature envoyée par le frontend
     */
    public function storeCandidatureNotification() {
        header('Content-Type: application/json');
        $response = ['success' => false];
        
        // Vérifier l'authentification
        $userEmail = $_SESSION['user']['email'] ?? null;
        if (!$userEmail) {
            echo json_encode($response);
            exit;
        }
        
        // Récupérer les données de la requête
        $candidatureId = $_POST['candidature_id'] ?? null;
        $projectTitle = $_POST['project_title'] ?? '';
        $message = $_POST['message'] ?? '';
        $status = $_POST['status'] ?? 'pending';
        $expiresAt = $_POST['expires_at'] ?? null;
        
        if (!$candidatureId || !$message) {
            echo json_encode($response);
            exit;
        }
        
        try {
            // Vérifier si la candidature existe et appartient à l'utilisateur
            $sql = "SELECT id FROM candidatures WHERE id = ? AND user_email = ?";
            $stmt = $this->projectModel->db->prepare($sql);
            $stmt->execute([$candidatureId, $userEmail]);
            
            if (!$stmt->fetch()) {
                echo json_encode($response);
                exit;
            }
            
            // Charger le modèle de notification
            require_once __DIR__ . '/../models/NotificationModel.php';
            $notificationModel = new NotificationModel($this->projectModel->db);
            
            // Créer la notification
            $data = [
                'candidature_id' => $candidatureId,
                'project_title' => $projectTitle,
                'status' => $status,
                'expires_at' => $expiresAt
            ];
            
            // Créer un titre approprié
            $title = "Candidature: $projectTitle";
            
            // Ajouter la notification - correction de l'ordre des paramètres
            // La signature est: addNotification($userEmail, $type, $title, $message, $linkedId, $data, $expiresAt)
            $result = $notificationModel->addNotification(
                $userEmail,
                'candidature',    // type
                $title,           // title
                $message,         // message
                $candidatureId,   // linked_id
                $data,            // data
                $expiresAt        // expiresAt
            );
            
            if ($result) {
                $response['success'] = true;
                $response['notification_id'] = $result;
            }
        } catch (Exception $e) {
            error_log("storeCandidatureNotification: Exception - " . $e->getMessage());
        }
        
        echo json_encode($response);
        exit;
    }

    /**
     * Handle user canceling their application
     */
    public function cancelApplication() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];

        try {
            // Check authentication
            $userEmail = $_SESSION['user']['email'] ?? null;
            if (!$userEmail) {
                $response['message'] = 'You must be logged in';
                echo json_encode($response);
                exit;
            }

            // Get data
            $candidatureId = $_POST['candidature_id'] ?? null;
            
            if (!$candidatureId) {
                $response['message'] = 'Invalid request data';
                echo json_encode($response);
                exit;
            }

            // Verify the user is the owner of the candidature
            $sql = "SELECT user_email, status FROM candidatures WHERE id = ?";
            $stmt = $this->projectModel->db->prepare($sql);
            $stmt->execute([$candidatureId]);
            $candidature = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$candidature) {
                $response['message'] = 'Application not found';
                echo json_encode($response);
                exit;
            }
            
            if ($candidature['user_email'] !== $userEmail) {
                $response['message'] = 'You can only cancel your own applications';
                echo json_encode($response);
                exit;
            }
            
            if ($candidature['status'] !== 'pending') {
                $response['message'] = 'Only pending applications can be cancelled';
                echo json_encode($response);
                exit;
            }

            // Update the application status to cancelled
            $result = $this->projectModel->updateCandidatureStatus($candidatureId, 'cancelled');
            
            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Your application has been cancelled successfully';
            } else {
                $response['message'] = 'Failed to cancel application. Please try again.';
            }
        } catch (Exception $e) {
            error_log("cancelApplication: Exception - " . $e->getMessage());
            $response['message'] = 'An error occurred while processing your request';
        }

        echo json_encode($response);
        exit;
    }

    /**
     * Get project details for AJAX request
     * Returns JSON response with project information
     */
    public function getProjectDetails() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];
        
        // Get project ID from GET parameters
        $projectId = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 0;
        
        if ($projectId <= 0) {
            $response['message'] = 'Invalid project ID';
            echo json_encode($response);
            exit;
        }
        
        try {
            // Get project details
            $project = $this->projectModel->getProjectById($projectId);
            
            if ($project) {
                $response['success'] = true;
                $response['project'] = $project;
            } else {
                $response['message'] = 'Project not found';
            }
        } catch (Exception $e) {
            $response['message'] = 'Error retrieving project details: ' . $e->getMessage();
        }
        
        echo json_encode($response);
        exit;
    }
}