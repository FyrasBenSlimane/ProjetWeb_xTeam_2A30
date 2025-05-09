<?php
/**
 * Dashboard Main Controller
 * This file serves as the entry point for the dashboard and implements MVC architecture
 */

// Allow CORS for same-origin requests
header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// If it's a preflight OPTIONS request, respond with success
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user'])) {
    // Only redirect if not a login status check
    if (isset($_GET['check_login'])) {
        header('Content-Type: application/json');
        echo json_encode(['logged_in' => false]);
        exit;
    }
    header('Location: ../Login/login.php');
    exit;
}

// Check login status and return JSON if requested
if (isset($_GET['check_login'])) {
    header('Content-Type: application/json');
    echo json_encode(['logged_in' => true]);
    exit;
}

// Get user data from session
$userName = $_SESSION['user']['name'] ?? '';
$userType = $_SESSION['user']['user_type'] ?? 'freelancer'; // Default to freelancer if not set
$user = $_SESSION['user'] ?? [];

// Get theme preference from cookies or system preference
$savedTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : null;
$systemTheme = isset($_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME']) ? $_SERVER['HTTP_SEC_CH_PREFERS_COLOR_SCHEME'] : null;
$initialTheme = $savedTheme ?: ($systemTheme ?: 'light');

// Include database connection
require_once __DIR__ . '/../../config/database.php';

// Include controllers
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/SupportController.php';
require_once __DIR__ . '/controllers/ProjectController.php';
require_once __DIR__ . '/controllers/NotificationController.php';

// Initialize controllers
$dashboardController = new DashboardController();
$userController = new UserController();
$supportController = new SupportController();
$projectController = new ProjectController();
$notificationController = new NotificationController();

// Handle page routing
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Process form submissions first
$formResult = [];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Handle AJAX requests for support tickets
    if ($page === 'support-tickets') {
        $supportController = new SupportController();
        
        if ($_POST['action'] === 'update_status') {
            // Handle status update via AJAX
            $supportController->updateTicketStatus();
            exit; // Stop execution after AJAX response
        } 
        else if ($_POST['action'] === 'delete_ticket') {
            // Handle ticket deletion via AJAX
            $supportController->ajaxDeleteTicket();
            exit; // Stop execution after AJAX response
        }
    }
    
    // Handle AJAX requests for projects
    if ($page === 'projects') {
        if ($_POST['action'] === 'update_status') {
            $projectController->updateProjectStatus();
            exit;
        }
        else if ($_POST['action'] === 'delete_project') {
            $projectController->ajaxDeleteProject();
            exit;
        }
        else if ($_POST['action'] === 'apply_project') {
            $projectController->applyToProject();
            exit;
        }
        else if ($_POST['action'] === 'update_candidature_status') {
            $projectController->updateCandidatureStatus();
            exit;
        }
    }
    
    // Handle AJAX requests for notifications
    if ($page === 'notifications') {
        if ($_POST['action'] === 'mark_read') {
            $notificationController->markAsRead();
            exit;
        }
        else if ($_POST['action'] === 'mark_all_read') {
            $notificationController->markAllAsRead();
            exit;
        }
        else if ($_POST['action'] === 'delete') {
            $notificationController->deleteNotification();
            exit;
        }
    }
}

// Gérer les requêtes AJAX GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($page === 'projects') {
        switch ($_GET['action']) {
            case 'get_user_candidatures':
                $projectController->getUserCandidatures();
                exit;
            case 'get_project_details':
                $projectController->getProjectDetails();
                exit;
        }
    }
    
    // Gérer les actions GET pour les notifications
    if ($page === 'notifications') {
        switch ($_GET['action'] ?? '') {
            case 'mark_read':
                $notificationController->markAsRead();
                exit;
            case 'mark_all_read':
                $notificationController->markAllAsRead();
                exit;
            case 'delete':
                $notificationController->deleteNotification();
                exit;
        }
    }
}

// Gérer les requêtes AJAX pour les notifications de candidature
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'projects' && isset($_GET['action'])) {
    if ($_GET['action'] === 'store_candidature_notification') {
        $projectController->storeCandidatureNotification();
        exit;
    }
    
    if ($_GET['action'] === 'update_candidature_status') {
        $projectController->updateCandidatureStatus();
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($page) {
        case 'profile':
            $formResult = $userController->updateProfile();
            break;
        case 'settings':
            if (isset($_POST['change_password'])) {
                $formResult = $userController->updatePassword();
            } elseif (isset($_POST['update_notifications'])) {
                $formResult = $userController->updateNotifications();
            } elseif (isset($_POST['update_privacy'])) {
                $formResult = $userController->updatePrivacy();
            }
            break;
        case 'support-tickets':
            if (isset($_POST['create_ticket'])) {
                $supportController->createTicket();
            } elseif (isset($_POST['update_ticket'])) {
                $supportController->updateTicket();
            }
            break;
    }
}

// Start output buffering to capture the page content
ob_start();

// Route to the appropriate controller/action based on the page parameter
switch ($page) {
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'profile':
        $userController->profile();
        break;
    case 'settings':
        $userController->settings();
        break;
    case 'projects':
        // Check for AJAX actions for projects
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete_project_ajax':
                    $projectController->ajaxDeleteProject();
                    break;
                case 'apply_project':
                    $projectController->applyToProject();
                    break;
                case 'update_project_status':
                    $projectController->updateProjectStatus();
                    break;
                case 'update_candidature_status':
                    $projectController->updateCandidatureStatus();
                    break;
                case 'cancel_application':
                    $projectController->cancelApplication();
                    break;
                case 'get_project_details':
                    $projectController->getProjectDetails();
                    break;
                case 'check_login':
                    // Return JSON response with login status
                    header('Content-Type: application/json');
                    echo json_encode(['logged_in' => isset($_SESSION['user'])]);
                    exit;
                    break;
                default:
                    $projectController->userProjects();
                    break;
            }
        } else {
            $projectController->userProjects();
        }
        break;
    case 'support-tickets':
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        
        switch($action) {
            case 'create':
                $supportController->createTicketForm();
                break;
            case 'view':
                $supportController->viewTicket();
                break;
            case 'admin':
                $supportController->adminTickets();
                break;
            default:
                $supportController->userTickets();
                break;
        }
        break;
    case 'notifications':
        // Vérifier et mettre à jour les notifications expirées
        $notificationController->updateExpiredNotifications();
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action === 'index') {
            $notificationController->index();
        }
        break;
    default:
        // Default to dashboard if page not found
        $dashboardController->index();
        break;
}

// Get the content generated by the controller action
$pageContent = ob_get_clean();

// Make sure $content is also defined for any view that might use it instead
$content = $pageContent;

// Now include the layout template which will use $pageContent
include_once __DIR__ . '/views/layout.php';
?>
