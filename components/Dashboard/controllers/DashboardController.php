<?php
/**
 * Dashboard Controller - Handles dashboard operations
 */
class DashboardController {
    private $userModel;
    private $projectModel;

    public function __construct() {
        require_once __DIR__ . '/../models/UserModel.php';
        require_once __DIR__ . '/../models/ProjectModel.php';
        
        // Get database connection
        require_once __DIR__ . '/../../../config/database.php';
        $db = $GLOBALS['pdo'];
        
        $this->userModel = new UserModel($db);
        $this->projectModel = new ProjectModel($db);
    }

    /**
     * Display dashboard home page
     */
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['email'])) {
            header('Location: ../Login/login.php');
            exit;
        }

        // Get user data
        $user = $_SESSION['user'];
        $userType = $user['user_type'] ?? 'freelancer';
        
        // Make models and data available to the view
        $projectModel = $this->projectModel;
        
        // Get project statistics for all projects
        $projectStats = $projectModel->getProjectStats();
        
        // Set page title
        $pageTitle = 'Dashboard';
        
        // Include the dashboard content
        include __DIR__ . '/../views/dashboard/content.php';
    }
}