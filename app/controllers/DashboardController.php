<?php
/**
 * DashboardController Class
 * Handles dashboard functionality and data processing
 */

// Include the DashboardService class
require_once APP_ROOT . '/helpers/DashboardService.php';
require_once APP_ROOT . '/config/mail.php'; // Include mail function

class DashboardController extends Controller {
    private $dashboardModel;
    private $db;

    public function __construct() {
        // Initialize dashboard model
        $this->dashboardModel = $this->model('DashboardModel');
        
        // Initialize database connection
        $this->db = new Database;
        
        // Load the AuthMiddleware class
        require_once APP_PATH . '/helpers/AuthMiddleware.php';
        
        // Ensure user is logged in and has admin role
        AuthMiddleware::requireRole('admin');
    }

    // Main dashboard index page
    public function index() {
        // Get analytics data for the dashboard
        $analyticsData = $this->dashboardModel->getAnalyticsData();
        
        // Get recent users for the dashboard table
        $users = $this->dashboardModel->getUsersData();
        
        // Format analytics data for charts
        $visitChartData = [];
        foreach ($analyticsData['visitHistory'] as $visit) {
            $date = new DateTime($visit['date']);
            $visitChartData[] = [
                'day' => $date->format('D'),
                'value' => $visit['visits']
            ];
        }
        
        $userDistributionData = $analyticsData['userDistribution'];
        
        // Get recent activity logs
        $dashboardService = DashboardService::getInstance();
        $activityLogs = $dashboardService->getRecentActivityLogs(5);
        
        // Log this dashboard view
        $dashboardService->logActivity('view', 'dashboard');
        
        // Prepare data for the view
        $data = [
            'title' => 'Admin Dashboard',
            'description' => 'LenSi Admin Dashboard',
            'analyticsData' => $analyticsData,
            'users' => $users,
            'visitChartData' => json_encode($visitChartData),
            'userDistributionData' => json_encode($userDistributionData),
            'activityLogs' => $activityLogs
        ];
        
        $this->view('dashboard/index', $data);
    }
    
    // User Management page
    public function user_management() {
        $users = $this->dashboardModel->getUsersData();
        
        // Log this user management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'user_management');
        
        $data = [
            'title' => 'User Management',
            'description' => 'Manage users',
            'users' => $users
        ];
        
        $this->view('dashboard/user_management', $data);
    }
    
    // Blog Management page
    public function blog_management() {
        $posts = $this->dashboardModel->getBlogPostsData();
        
        // Log this blog management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'blog_management');
        
        $data = [
            'title' => 'Blog Management',
            'description' => 'Manage blog posts',
            'posts' => $posts
        ];
        
        $this->view('dashboard/blog_management', $data);
    }
    
    // Support Tickets page
    public function support_tickets() {
        // Get page from query string
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $perPage = 5; // Items per page
        
        // Get tickets with pagination
        $tickets = $this->dashboardModel->getSupportTicketsData($page, $perPage);
        $totalTickets = $this->dashboardModel->getTotalTicketsCount();
        
        // Log this support tickets view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'support_tickets');
        
        $data = [
            'title' => 'Support Tickets',
            'description' => 'Manage support tickets',
            'tickets' => $tickets,
            'total_tickets' => $totalTickets
        ];
        
        $this->view('dashboard/support_tickets', $data);
    }
    
    // Settings page
    public function settings() {
        // Assuming a Setting model exists at app/models/Setting.php
        $settingModel = $this->model('Setting');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $dataToUpdate = [
                'site_name' => trim($_POST['site_name'] ?? ''),
                'site_description' => trim($_POST['site_description'] ?? ''),
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'contact_phone' => trim($_POST['contact_phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'smtp_host' => trim($_POST['smtp_host'] ?? ''),
                'smtp_port' => trim($_POST['smtp_port'] ?? ''),
                'smtp_user' => trim($_POST['smtp_user'] ?? ''),
                // IMPORTANT: Handle password update carefully. Only update if a new password is provided.
                // Avoid saving an empty string if the password field is left blank.
                // Consider adding a separate mechanism or checkbox for password updates.
                'smtp_pass' => !empty($_POST['smtp_pass']) ? trim($_POST['smtp_pass']) : null, // Example: Update only if not empty
                'smtp_encryption' => trim($_POST['smtp_encryption'] ?? ''),
                'smtp_from_email' => trim($_POST['smtp_from_email'] ?? ''),
                'smtp_from_name' => trim($_POST['smtp_from_name'] ?? ''),
                'sms_provider_key' => trim($_POST['sms_provider_key'] ?? ''),
                'sms_sender_id' => trim($_POST['sms_sender_id'] ?? ''),
                'google_analytics_id' => trim($_POST['google_analytics_id'] ?? ''),
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0, // Handle checkbox
                'default_language' => trim($_POST['default_language'] ?? ''),
                'timezone' => trim($_POST['timezone'] ?? ''),
                'date_format' => trim($_POST['date_format'] ?? ''),
                'time_format' => trim($_POST['time_format'] ?? '')
            ];

            // Remove null password if it wasn't updated
            if (is_null($dataToUpdate['smtp_pass'])) {
                unset($dataToUpdate['smtp_pass']);
            }

            // Validation
            $errors = [];
            if (empty($dataToUpdate['site_name'])) {
                $errors['site_name_err'] = 'Please enter Site Name';
            }
            if (empty($dataToUpdate['contact_email'])) {
                $errors['contact_email_err'] = 'Please enter Contact Email';
            } elseif (!filter_var($dataToUpdate['contact_email'], FILTER_VALIDATE_EMAIL)) {
                $errors['contact_email_err'] = 'Please enter a valid email address';
            }
            // Add validation for other required fields (e.g., SMTP if email features are used)
            if (!empty($dataToUpdate['smtp_host'])) { // Only validate SMTP if host is provided
                if (empty($dataToUpdate['smtp_port'])) {
                    $errors['smtp_port_err'] = 'Please enter SMTP Port';
                } elseif (!filter_var($dataToUpdate['smtp_port'], FILTER_VALIDATE_INT)) {
                    $errors['smtp_port_err'] = 'Please enter a valid port number';
                }
                if (empty($dataToUpdate['smtp_user'])) {
                    $errors['smtp_user_err'] = 'Please enter SMTP Username';
                }
                // Consider validating smtp_pass only if a new one is entered
                if (empty($dataToUpdate['smtp_from_email'])) {
                    $errors['smtp_from_email_err'] = 'Please enter SMTP From Email';
                } elseif (!filter_var($dataToUpdate['smtp_from_email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['smtp_from_email_err'] = 'Please enter a valid SMTP From email address';
                }
                 if (empty($dataToUpdate['smtp_from_name'])) {
                    $errors['smtp_from_name_err'] = 'Please enter SMTP From Name';
                }
            }
            // Add more validation rules as needed for other fields (timezone, formats etc.)
            if (empty($dataToUpdate['default_language'])) {
                $errors['default_language_err'] = 'Please select a default language';
            }
             if (empty($dataToUpdate['timezone'])) {
                $errors['timezone_err'] = 'Please select a timezone';
            }

            if (empty($errors)) {
                // Attempt to update settings
                if ($settingModel->updateSettings($dataToUpdate)) {
                    // Use flash messages if available (e.g., SessionHelper::flash('settings_message', 'Settings Updated Successfully');)
                    redirect('dashboard/settings'); // Redirect after successful update
                } else {
                    die('Something went wrong while updating settings.'); // Basic error handling
                }
            } else {
                 // Load view with errors and submitted data (to repopulate form)
                 $currentSettings = $settingModel->getAllSettings(); // Fetch current settings again
                 $data = [
                    'title' => 'Dashboard Settings',
                    'description' => 'Manage dashboard settings',
                    // Merge submitted data with current settings for repopulation, prioritizing submitted data
                    'settings' => (object)array_merge((array)$currentSettings, $_POST),
                    'errors' => $errors // Pass errors to the view
                 ];
                 $this->view('dashboard/settings', $data);
                 return; // Stop further execution
            }

        } else {
            // GET request: Fetch settings and load view
            $settings = $settingModel->getAllSettings();

            $data = [
                'title' => 'Dashboard Settings',
                'description' => 'Manage dashboard settings',
                'settings' => $settings,
                'errors' => [] // Initialize empty errors array
            ];

            $this->view('dashboard/settings', $data);
        }
    }
    
    // API methods for AJAX operations
    
    // Get user data for AJAX requests
    public function getUserData() {
        // Check if user_id is provided in the request
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        
        if (!$userId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            return;
        }
        
        // Get user from database
        $user = $this->dashboardModel->getUserById($userId);
        
        if ($user) {
            // Return user data as JSON
            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }
    
    // Toggle user status
    public function toggleUserStatus() {
        // Check if user is admin
        if(!isAdmin()) {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check for POST request
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get user ID and new status
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : null;
        
        // Validate data
        if(!$userId || !$newStatus || !in_array($newStatus, ['Active', 'Inactive'])) {
            $this->sendJsonResponse(false, 'Invalid user data');
            return;
        }
        
        // Toggle user status in the database
        $dashboardModel = $this->model('DashboardModel');
        $success = $dashboardModel->updateUserStatus($userId, $newStatus);
        
        if($success) {
            $this->sendJsonResponse(true, 'User status updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to update user status');
        }
    }
    
    // Delete user
    public function deleteUser() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get user ID from POST data
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
        
        if (!$userId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            return;
        }
        
        // Delete user from database
        $success = $this->dashboardModel->deleteUser($userId);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    }
    
    // Get user
    public function getUser($userId = null) {
        if (!$userId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            return;
        }
        
        // Get user from database
        $user = $this->dashboardModel->getUserById($userId);
        
        if ($user) {
            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }
    
    // Add new user
    public function addUser() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get user data from POST
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $role = isset($_POST['role']) ? $_POST['role'] : null;
        
        if (!$name || !$email || !$password || !$role) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Check if email already exists
        $this->db->query("SELECT id FROM users WHERE email = :email");
        
        // Check if email already exists
        $this->db = new Database;
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $existingUser = $this->db->single();
        
        if ($existingUser) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Email already in use']);
            return;
        }
        
        // Add new user to database
        $this->db->query("INSERT INTO users (name, email, password, account_type, terms_accepted) 
                        VALUES (:name, :email, :password, :role, 1)");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT));
        $this->db->bind(':role', strtolower($role));
        
        $success = $this->db->execute();
        
        if ($success) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User added successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to add user']);
        }
    }
    
    // Update existing user
    public function updateUser() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get user data from POST
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $name = isset($_POST['name']) ? $_POST['name'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $role = isset($_POST['role']) ? $_POST['role'] : null;
        
        if (!$userId || !$name || !$email || !$role) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        // Check if email already exists for a different user
        // Check if email already exists for a different user
        $this->db = new Database;
        $this->db->query("SELECT id FROM users WHERE email = :email AND id != :userId");
        $this->db->bind(':email', $email);
        $this->db->bind(':userId', $userId);
        $existingUser = $this->db->single();
        
        if ($existingUser) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Email already in use by another user']);
            return;
        }
        
        // Build query based on whether password is being updated
        if (!empty($password)) {
            $this->db->query("UPDATE users SET name = :name, email = :email, password = :password, account_type = :role 
                            WHERE id = :userId");
            $this->db->bind(':password', password_hash($password, PASSWORD_DEFAULT));
        } else {
            $this->db->query("UPDATE users SET name = :name, email = :email, account_type = :role 
                            WHERE id = :userId");
        }
        
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':role', strtolower($role));
        $this->db->bind(':userId', $userId);
        
        $success = $this->db->execute();
        
        if ($success) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update user']);
        }
    }
    
    // Delete blog post
    public function deleteBlogPost() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get post ID from POST data
        $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
        
        if (!$postId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing post ID']);
            return;
        }
        
        // Delete blog post from database
        $success = $this->dashboardModel->deleteBlogPost($postId);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Blog post deleted successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to delete blog post']);
        }
    }
    
    // Respond to blog comment
    public function respondToBlogComment() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get data from POST
        $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
        $commentId = isset($_POST['commentId']) ? $_POST['commentId'] : null;
        $response = isset($_POST['response']) ? $_POST['response'] : null;
        
        if (!$postId || !$commentId || !$response) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Add response to comment
        $success = $this->dashboardModel->addCommentResponse($postId, $commentId, $response);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Response added successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to add response']);
        }
    }
    
    // Respond to support ticket
    public function respondToTicket() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get data from POST
        $ticketId = isset($_POST['ticketId']) ? $_POST['ticketId'] : null;
        $response = isset($_POST['response']) ? $_POST['response'] : null;
        
        if (!$ticketId || !$response) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Add response to ticket
        $success = $this->dashboardModel->addTicketResponse($ticketId, $response);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Response added successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to add response']);
        }
    }
    
    // Update support ticket
    public function updateSupportTicket() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get ticket ID and updates from POST data
        $ticketId = isset($_POST['ticketId']) ? $_POST['ticketId'] : null;
        
        if (!$ticketId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing ticket ID']);
            return;
        }
        
        // Create updates array from POST data
        $updates = [];
        if (isset($_POST['status'])) $updates['status'] = $_POST['status'];
        if (isset($_POST['priority'])) $updates['priority'] = $_POST['priority'];
        
        // Update ticket in database
        $success = $this->dashboardModel->updateSupportTicket($ticketId, $updates);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Ticket updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to update ticket']);
        }
    }
    
    // Toggle sidebar state in session
    public function toggleSidebar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isOpen'])) {
            $_SESSION['sidebar_open'] = $_POST['isOpen'] == 1;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    // Get a single blog post by ID
    public function getBlogPost() {
        // Check if request has post ID
        $postId = isset($_GET['postId']) ? $_GET['postId'] : null;
        
        if (!$postId) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing post ID']);
            return;
        }
        
        // Get post data from database
        $post = $this->dashboardModel->getBlogPostById($postId);
        
        if ($post) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $post]);
        } else {
            http_response_code(404); // Not Found
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
    }
    
    // Create new blog post
    public function createBlogPost() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get post data from POST
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $content = isset($_POST['content']) ? $_POST['content'] : null;
        $tags = isset($_POST['tags']) ? json_decode($_POST['tags'], true) : [];
        $status = isset($_POST['status']) ? $_POST['status'] : 'draft';
        
        if (!$title || !$content) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Create post data array
        $postData = [
            'title' => $title,
            'content' => $content,
            'tags' => $tags,
            'status' => $status,
            'author_id' => $_SESSION['user_id']
        ];
        
        // Add post to database
        $success = $this->dashboardModel->createBlogPost($postData);
        
        if ($success) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Blog post created successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to create blog post']);
        }
    }
    
    // Update existing blog post (complete update)
    public function updateBlogPost() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get post data from POST
        $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
        
        if (!$postId) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing post ID']);
            return;
        }
        
        // Create updates array from POST data
        $updates = [];
        if (isset($_POST['title'])) $updates['title'] = $_POST['title'];
        if (isset($_POST['content'])) $updates['content'] = $_POST['content'];
        if (isset($_POST['tags'])) $updates['tags'] = json_decode($_POST['tags'], true);
        if (isset($_POST['status'])) $updates['status'] = $_POST['status'];
        
        // Update blog post in database
        $success = $this->dashboardModel->updateBlogPost($postId, $updates);
        
        if ($success) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Blog post updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update blog post']);
        }
    }
    
    // Update blog comment status
    public function updateBlogComment() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get data from POST
        $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
        $commentId = isset($_POST['commentId']) ? $_POST['commentId'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        
        if (!$postId || !$commentId || !$status) {
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Update comment status in database
        $success = $this->dashboardModel->updateCommentStatus($postId, $commentId, $status);
        
        if ($success) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Comment status updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to update comment status']);
        }
    }

    /**
     * Helper method to send consistent JSON responses
     * 
     * @param bool $success Whether the operation was successful
     * @param string $message Message to include in the response
     * @param array $data Optional additional data
     * @return void
     */
    private function sendJsonResponse($success, $message, $data = []) {
        // Set the content type header to ensure correct parsing
        header('Content-Type: application/json');
        
        // Create response array
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        // Add any additional data if provided
        if (!empty($data)) {
            $response['data'] = $data;
        }
        
        // Send the JSON encoded response
        echo json_encode($response);
        exit; // Stop further execution after sending response
    }

    // Get profile data
    public function getProfile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->sendJsonResponse(false, 'Not logged in');
            return;
        }
        
        // Get user data
        $user = $this->dashboardModel->getUserById($_SESSION['user_id']);
        
        if ($user) {
            // Remove sensitive data
            unset($user['password']);
            $this->sendJsonResponse(true, 'Profile data retrieved successfully', $user);
        } else {
            $this->sendJsonResponse(false, 'Error retrieving profile data');
        }
    }
    
    // Update profile
    public function updateProfile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->sendJsonResponse(false, 'Not logged in');
            return;
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get JSON data
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendJsonResponse(false, 'Invalid input data');
            return;
        }
        
        // Validate required fields
        if (empty($input['name']) || empty($input['email'])) {
            $this->sendJsonResponse(false, 'Name and email are required');
            return;
        }
        
        // Validate email format
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->sendJsonResponse(false, 'Invalid email format');
            return;
        }
        
        // Check if email is already in use by another user
        if ($this->dashboardModel->isEmailInUse($input['email'], $_SESSION['user_id'])) {
            $this->sendJsonResponse(false, 'Email is already in use by another user');
            return;
        }
        
        // Prepare update data
        $updateData = [
            'id' => $_SESSION['user_id'],
            'name' => $input['name'],
            'email' => $input['email']
        ];
        
        // Handle password update if provided
        if (!empty($input['current_password']) && !empty($input['new_password'])) {
            // Verify current password
            if (!$this->dashboardModel->verifyPassword($_SESSION['user_id'], $input['current_password'])) {
                $this->sendJsonResponse(false, 'Current password is incorrect');
                return;
            }
            
            // Validate new password
            if (strlen($input['new_password']) < 6) {
                $this->sendJsonResponse(false, 'New password must be at least 6 characters long');
                return;
            }
            
            $updateData['password'] = password_hash($input['new_password'], PASSWORD_DEFAULT);
        }
        
        // Update profile
        if ($this->dashboardModel->updateProfile($updateData)) {
            // Update session data
            $_SESSION['user_name'] = $input['name'];
            $_SESSION['user_email'] = $input['email'];
            
            $this->sendJsonResponse(true, 'Profile updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Error updating profile');
        }
    }

    // Resources Management page
    public function resources_management() {
        // Get all resources
        $resources = $this->dashboardModel->getResources();
        
        // Log this resources management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'resources_management');
        
        $data = [
            'title' => 'Resources Management',
            'description' => 'Manage YouTube resources',
            'resources' => $resources
        ];
        
        // Generate page content using the template
        ob_start();
        $this->view('dashboard/resources_management', $data);
        $content = ob_get_clean();
        
        $data['content'] = $content;
        $this->view('dashboard/dashboard_layout', $data);
    }
    
    // Add Resource page
    public function resource_add() {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Default thumbnail if none provided
            $thumbnailUrl = !empty($_POST['thumbnail_url']) ? $_POST['thumbnail_url'] : '';
            
            // Extract YouTube ID from URL if not provided
            $youtubeId = !empty($_POST['youtube_id']) ? trim($_POST['youtube_id']) : '';
            $youtubeUrl = trim($_POST['youtube_url']);
            
            // Init data
            $data = [
                'title' => trim($_POST['title']),
                'youtube_url' => $youtubeUrl,
                'youtube_id' => $youtubeId,
                'thumbnail_url' => $thumbnailUrl,
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'status' => trim($_POST['status']),
                'title_err' => '',
                'youtube_url_err' => '',
                'category_err' => ''
            ];
            
            // Validate input
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }
            
            if (empty($data['youtube_url'])) {
                $data['youtube_url_err'] = 'Please enter a YouTube URL';
            } else {
                // Try to extract YouTube data
                $youtubeData = $this->dashboardModel->extractYoutubeData($data['youtube_url']);
                if (!$youtubeData) {
                    $data['youtube_url_err'] = 'Invalid YouTube URL';
                } else {
                    // If YouTube data extraction was successful, use it
                    $data['youtube_id'] = $youtubeData['youtube_id'];
                    $data['thumbnail_url'] = $youtubeData['thumbnail_url'];
                    // Only override title if not provided
                    if (empty($data['title'])) {
                        $data['title'] = $youtubeData['title'];
                    }
                }
            }
            
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['youtube_url_err']) && empty($data['category_err'])) {
                // Add resource
                if ($this->dashboardModel->addResource($data)) {
                    flash('resource_message', 'Resource Added');
                    redirect('dashboard/resources_management');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['page_title'] = 'Add Resource';
                ob_start();
                $this->view('dashboard/resources_add', $data);
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $this->view('dashboard/dashboard_layout', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => '',
                'youtube_url' => '',
                'youtube_id' => '',
                'thumbnail_url' => '',
                'description' => '',
                'category' => 'tutorial',
                'status' => 'active',
                'title_err' => '',
                'youtube_url_err' => '',
                'category_err' => '',
                'page_title' => 'Add Resource'
            ];
            
            // Load view
            ob_start();
            $this->view('dashboard/resources_add', $data);
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('dashboard/dashboard_layout', $data);
        }
    }
    
    // Edit Resource page
    public function resource_edit($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check if ID is provided
        if (!$id) {
            flash('resource_message', 'No resource specified', 'alert alert-danger');
            redirect('dashboard/resources_management');
        }
        
        // Get existing resource
        $resource = $this->dashboardModel->getResourceById($id);
        
        // Check if resource exists
        if (!$resource) {
            flash('resource_message', 'Resource not found', 'alert alert-danger');
            redirect('dashboard/resources_management');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Default thumbnail if none provided
            $thumbnailUrl = !empty($_POST['thumbnail_url']) ? $_POST['thumbnail_url'] : $resource->thumbnail_url;
            
            // Extract YouTube ID from URL if not provided
            $youtubeId = !empty($_POST['youtube_id']) ? trim($_POST['youtube_id']) : $resource->youtube_id;
            $youtubeUrl = trim($_POST['youtube_url']);
            
            // Init data
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'youtube_url' => $youtubeUrl,
                'youtube_id' => $youtubeId,
                'thumbnail_url' => $thumbnailUrl,
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'status' => trim($_POST['status']),
                'title_err' => '',
                'youtube_url_err' => '',
                'category_err' => ''
            ];
            
            // Validate input
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }
            
            if (empty($data['youtube_url'])) {
                $data['youtube_url_err'] = 'Please enter a YouTube URL';
            } else if ($data['youtube_url'] !== $resource->youtube_url) {
                // Only try to extract YouTube data if URL has changed
                $youtubeData = $this->dashboardModel->extractYoutubeData($data['youtube_url']);
                if (!$youtubeData) {
                    $data['youtube_url_err'] = 'Invalid YouTube URL';
                } else {
                    // If YouTube data extraction was successful, use it
                    $data['youtube_id'] = $youtubeData['youtube_id'];
                    $data['thumbnail_url'] = $youtubeData['thumbnail_url'];
                    // Only override title if not provided
                    if (empty($data['title'])) {
                        $data['title'] = $youtubeData['title'];
                    }
                }
            }
            
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['youtube_url_err']) && empty($data['category_err'])) {
                // Update resource
                if ($this->dashboardModel->updateResource($data)) {
                    flash('resource_message', 'Resource Updated');
                    redirect('dashboard/resources_management');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['page_title'] = 'Edit Resource';
                ob_start();
                $this->view('dashboard/resources_edit', $data);
                $content = ob_get_clean();
                
                $data['content'] = $content;
                $this->view('dashboard/dashboard_layout', $data);
            }
        } else {
            // Init data with existing resource
            $data = [
                'id' => $resource->id,
                'title' => $resource->title,
                'youtube_url' => $resource->youtube_url,
                'youtube_id' => $resource->youtube_id,
                'thumbnail_url' => $resource->thumbnail_url,
                'description' => $resource->description,
                'category' => $resource->category,
                'status' => $resource->status,
                'title_err' => '',
                'youtube_url_err' => '',
                'category_err' => '',
                'page_title' => 'Edit Resource'
            ];
            
            // Load view
            ob_start();
            $this->view('dashboard/resources_edit', $data);
            $content = ob_get_clean();
            
            $data['content'] = $content;
            $this->view('dashboard/dashboard_layout', $data);
        }
    }
    
    // Delete resource
    public function resource_delete($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing resource
            $resource = $this->dashboardModel->getResourceById($id);
            
            // Check if resource exists
            if (!$resource) {
                flash('resource_message', 'Resource not found', 'alert alert-danger');
                redirect('dashboard/resources_management');
            }
            
            // Delete resource
            if ($this->dashboardModel->deleteResource($id)) {
                flash('resource_message', 'Resource Removed');
            } else {
                flash('resource_message', 'Failed to remove resource', 'alert alert-danger');
            }
        }
        
        redirect('dashboard/resources_management');
    }

    // Resources method - for handling /dashboard/resources route
    public function resources() {
        // Get all resources
        $resources = $this->dashboardModel->getResources();
        
        // Log this resources management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'resources');
        
        $data = [
            'title' => 'Resources Management',
            'description' => 'Manage YouTube resources',
            'resources' => $resources
        ];
        
        // The view will handle including the dashboard layout
        $this->view('dashboard/resources_management', $data);
    }

    // Events Management page
    public function events_management() {
        // Get all events
        $events = $this->dashboardModel->getEvents();
        
        // Get all event registrations
        $eventsModel = $this->model('EventsModel');
        $registrations = [];
        
        // Loop through events and get registrations for each
        foreach ($events as $event) {
            $eventRegistrations = $eventsModel->getEventRegistrations($event->id);
            $registrations = array_merge($registrations, $eventRegistrations);
        }
        
        // Log this events management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'events_management');
        
        $data = [
            'title' => 'Events Management',
            'description' => 'Manage events and webinars',
            'events' => $events,
            'registrations' => $registrations
        ];
        
        $this->view('dashboard/events_management', $data);
    }
    
    // Add Event page
    public function event_add() {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Handle is_virtual checkbox
            $isVirtual = isset($_POST['is_virtual']) ? 1 : 0;
            
            // Init data
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_type' => trim($_POST['event_type']),
                'category' => trim($_POST['category']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'location' => trim($_POST['location']),
                'is_virtual' => $isVirtual,
                'virtual_link' => $isVirtual ? trim($_POST['virtual_link']) : null,
                'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
                'image' => !empty($_POST['image']) ? trim($_POST['image']) : null,
                'status' => trim($_POST['status']),
                'created_by' => $_SESSION['user_id'],
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => '',
                'virtual_link_err' => ''
            ];
            
            // Validate input
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }
            
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter a start date';
            }
            
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter an end date';
            } elseif ($data['end_date'] < $data['start_date']) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            if (empty($data['location']) && !$data['is_virtual']) {
                $data['location_err'] = 'Please enter a location or mark as virtual';
            }
            
            if ($data['is_virtual'] && empty($data['virtual_link'])) {
                $data['virtual_link_err'] = 'Please enter a virtual meeting link';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['description_err']) && 
                empty($data['start_date_err']) && empty($data['end_date_err']) && 
                empty($data['location_err']) && empty($data['virtual_link_err'])) {
                
                // Add event
                if ($this->dashboardModel->addEvent($data)) {
                    flash('event_message', 'Event Added');
                    redirect('dashboard/events_management');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['page_title'] = 'Add Event';
                $this->view('dashboard/events_add', $data);
            }
        } else {
            // Init data
            $data = [
                'title' => '',
                'description' => '',
                'event_type' => 'workshop',
                'category' => 'general',
                'start_date' => '',
                'end_date' => '',
                'location' => '',
                'is_virtual' => 0,
                'virtual_link' => '',
                'max_attendees' => 100,
                'image' => '',
                'status' => 'draft',
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => '',
                'virtual_link_err' => '',
                'page_title' => 'Add Event'
            ];
            
            // Load view
            $this->view('dashboard/events_add', $data);
        }
    }

    // Add new event via AJAX
    public function add_event() {
        // Check if admin
        if (!isAdmin()) {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Process form data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Handle is_virtual checkbox
        $isVirtual = isset($_POST['is_virtual']) ? 1 : 0;
        
        // Prepare data for database
        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'event_type' => trim($_POST['event_type']),
            'category' => trim($_POST['category']),
            'start_date' => trim($_POST['start_date']),
            'end_date' => trim($_POST['end_date']),
            'location' => trim($_POST['location']),
            'is_virtual' => $isVirtual,
            'virtual_link' => $isVirtual ? trim($_POST['virtual_link']) : null,
            'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
            'image' => !empty($_POST['image']) ? trim($_POST['image']) : null,
            'status' => trim($_POST['status']),
            'created_by' => $_SESSION['user_id']
        ];
        
        // Validate input
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Please enter a title';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Please enter a description';
        }
        
        if (empty($data['start_date'])) {
            $errors['start_date'] = 'Please enter a start date';
        }
        
        if (empty($data['end_date'])) {
            $errors['end_date'] = 'Please enter an end date';
        } elseif ($data['end_date'] < $data['start_date']) {
            $errors['end_date'] = 'End date must be after start date';
        }
        
        if (empty($data['location']) && !$data['is_virtual']) {
            $errors['location'] = 'Please enter a location or mark as virtual';
        }
        
        if ($data['is_virtual'] && empty($data['virtual_link'])) {
            $errors['virtual_link'] = 'Please enter a virtual meeting link';
        }
        
        // Check for validation errors
        if (!empty($errors)) {
            // Return errors if this is an AJAX request
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
                return;
            }
            
            // For non-AJAX requests, flash message and redirect
            flash('event_message', 'Please fix the errors in your form', 'alert alert-danger');
            redirect('dashboard/events_management');
            return;
        }
        
        // Add event to database
        if ($this->dashboardModel->addEvent($data)) {
            // If this is an AJAX request, return success response
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true, 
                    'message' => 'Event added successfully'
                ]);
                return;
            }
            
            flash('event_message', 'Event added successfully');
        } else {
            // If this is an AJAX request, return error response
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Failed to add event'
                ]);
                return;
            }
            
            flash('event_message', 'Failed to add event', 'alert alert-danger');
        }
        
        // Redirect for non-AJAX requests
        redirect('dashboard/events_management');
    }

    // Update existing event via AJAX
    public function edit_event() {
        // Check if admin
        if (!isAdmin()) {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Process form data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Check for event ID
        $eventId = isset($_POST['event_id']) ? $_POST['event_id'] : null; // Changed from $_POST['id']
        if (!$eventId) {
            $this->sendJsonResponse(false, 'Missing event ID');
            return;
        }
        
        // Get existing event to verify it exists
        $existingEvent = $this->dashboardModel->getEventById($eventId);
        if (!$existingEvent) {
            $this->sendJsonResponse(false, 'Event not found');
            return;
        }
        
        // Handle is_virtual checkbox
        $isVirtual = isset($_POST['is_virtual']) ? 1 : 0;
        
        // Prepare data for database
        $data = [
            'id' => $eventId,
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'event_type' => trim($_POST['event_type']),
            'category' => trim($_POST['category'] ?? $existingEvent->category), // Safely access category
            'start_date' => trim($_POST['start_date']),
            'end_date' => trim($_POST['end_date']),
            'location' => trim($_POST['location']),
            'is_virtual' => $isVirtual,
            'virtual_link' => $isVirtual ? trim($_POST['virtual_link']) : null,
            'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
            'image' => !empty($_POST['image']) ? trim($_POST['image']) : $existingEvent->image,
            'status' => trim($_POST['status'])
        ];
        
        // Validate input
        $errors = [];
        
        if (empty($data['title'])) {
            $errors['title'] = 'Please enter a title';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Please enter a description';
        }
        
        if (empty($data['start_date'])) {
            $errors['start_date'] = 'Please enter a start date';
        }
        
        if (empty($data['end_date'])) {
            $errors['end_date'] = 'Please enter an end date';
        } elseif ($data['end_date'] < $data['start_date']) {
            $errors['end_date'] = 'End date must be after start date';
        }
        
        if (empty($data['location']) && !$data['is_virtual']) {
            $errors['location'] = 'Please enter a location or mark as virtual';
        }
        
        if ($data['is_virtual'] && empty($data['virtual_link'])) {
            $errors['virtual_link'] = 'Please enter a virtual meeting link';
        }
        
        // Check for validation errors
        if (!empty($errors)) {
            // Return errors as JSON response
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ]);
            } else {
                flash('event_message', 'Validation failed. Please check the form for errors.', 'alert alert-danger');
                // Ideally, redirect back to the edit form with errors and old input
                // For simplicity, redirecting to management view for now.
                redirect('dashboard/events_management');
            }
            return;
        }
        
        // Update event in database
        if ($this->dashboardModel->updateEvent($data)) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(true, 'Event updated successfully');
            } else {
                flash('event_message', 'Event updated successfully');
                redirect('dashboard/events_management');
            }
        } else {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Failed to update event');
            } else {
                flash('event_message', 'Failed to update event', 'alert alert-danger');
                redirect('dashboard/events_management');
            }
        }
    }

    // Edit Event page
    public function event_edit($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check if ID is provided
        if (!$id) {
            flash('event_message', 'No event specified', 'alert alert-danger');
            redirect('dashboard/events_management');
        }
        
        // Get existing event
        $event = $this->dashboardModel->getEventById($id);
        
        // Check if event exists
        if (!$event) {
            flash('event_message', 'Event not found', 'alert alert-danger');
            redirect('dashboard/events_management');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Handle is_virtual checkbox
            $isVirtual = isset($_POST['is_virtual']) ? 1 : 0;
            
            // Init data
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'event_type' => trim($_POST['event_type']),
                'category' => trim($_POST['category']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'location' => trim($_POST['location']),
                'is_virtual' => $isVirtual,
                'virtual_link' => $isVirtual ? trim($_POST['virtual_link']) : null,
                'max_attendees' => !empty($_POST['max_attendees']) ? intval($_POST['max_attendees']) : null,
                'image' => !empty($_POST['image']) ? trim($_POST['image']) : $event->image,
                'status' => trim($_POST['status']),
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => '',
                'virtual_link_err' => ''
            ];
            
            // Validate input
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }
            
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter a start date';
            }
            
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter an end date';
            } elseif ($data['end_date'] < $data['start_date']) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            if (empty($data['location']) && !$data['is_virtual']) {
                $data['location_err'] = 'Please enter a location or mark as virtual';
            }
            
            if ($data['is_virtual'] && empty($data['virtual_link'])) {
                $data['virtual_link_err'] = 'Please enter a virtual meeting link';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['description_err']) && 
                empty($data['start_date_err']) && empty($data['end_date_err']) && 
                empty($data['location_err']) && empty($data['virtual_link_err'])) {
                
                // Update event
                if ($this->dashboardModel->updateEvent($data)) {
                    flash('event_message', 'Event Updated');
                    redirect('dashboard/events_management');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['page_title'] = 'Edit Event';
                $this->view('dashboard/events_edit', $data);
            }
        } else {
            // Init data with existing event
            $data = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_type' => $event->event_type,
                'category' => $event->category,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'location' => $event->location,
                'is_virtual' => $event->is_virtual,
                'virtual_link' => $event->virtual_link,
                'max_attendees' => $event->max_attendees,
                'image' => $event->image,
                'status' => $event->status,
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => '',
                'virtual_link_err' => '',
                'page_title' => 'Edit Event'
            ];
            
            // Load view
            $this->view('dashboard/events_edit', $data);
        }
    }
    
    // Delete event
    public function event_delete($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing event
            $event = $this->dashboardModel->getEventById($id);
            
            // Check if event exists
            if (!$event) {
                flash('event_message', 'Event not found', 'alert alert-danger');
                redirect('dashboard/events_management');
            }
            
            // Delete event
            if ($this->dashboardModel->deleteEvent($id)) {
                flash('event_message', 'Event Removed');
            } else {
                flash('event_message', 'Failed to remove event', 'alert alert-danger');
            }
        }
        
        redirect('dashboard/events_management');
    }

    // Events method - for handling /dashboard/events route
    public function events() {
        // Redirect to events_management
        redirect('dashboard/events_management');
    }

    // Projects Management page
    public function projects_management() {
        // Get all projects
        $allProjects = $this->dashboardModel->getProjects();
        $projectsWithCandidatures = [];

        // Loop through projects and get candidatures for each
        foreach ($allProjects as $project) {
            $project->candidatures = $this->dashboardModel->getProjectCandidatures($project->id);
            $projectsWithCandidatures[] = $project;
        }

        // Log this projects management view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'projects_management');

        $data = [
            'title' => 'Projects Management',
            'description' => 'Manage collaborative projects',
            'projects' => $projectsWithCandidatures
        ];

        $this->view('dashboard/projects_management', $data);
    }
    
    // Add Project method (for modal form submission)
    public function project_add() {
        // Redirect to projects_management by default for GET requests
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('dashboard/projects_management');
            return;
        }

        // This method is now just an alias for add_project
        $this->add_project();
    }
    
    // Edit Project page
    public function project_edit($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check if ID is provided
        if (!$id) {
            flash('project_message', 'No project specified', 'alert alert-danger');
            redirect('dashboard/projects_management');
        }
        
        // Get existing project
        $project = $this->dashboardModel->getProjectById($id);
        
        // Check if project exists
        if (!$project) {
            flash('project_message', 'Project not found', 'alert alert-danger');
            redirect('dashboard/projects_management');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Handle is_remote checkbox
            $isRemote = isset($_POST['is_remote']) ? 1 : 0;
            
            // Init data
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'location' => trim($_POST['location']),
                'is_remote' => $isRemote,
                'max_participants' => !empty($_POST['max_participants']) ? intval($_POST['max_participants']) : null,
                'skills_required' => !empty($_POST['skills_required']) ? trim($_POST['skills_required']) : null,
                'image' => !empty($_POST['image']) ? trim($_POST['image']) : $project->image,
                'status' => trim($_POST['status']),
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => ''
            ];
            
            // Validate input
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }
            
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }
            
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter a start date';
            }
            
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter an end date';
            } elseif ($data['end_date'] < $data['start_date']) {
                $data['end_date_err'] = 'End date must be after start date';
            }
            
            if (empty($data['location']) && !$data['is_remote']) {
                $data['location_err'] = 'Please enter a location or mark as remote';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['description_err']) && 
                empty($data['start_date_err']) && empty($data['end_date_err']) && 
                empty($data['location_err'])) {
                
                // Update project
                if ($this->dashboardModel->updateProject($data)) {
                    // If this is an AJAX request, return success JSON
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Project updated successfully']);
                        return;
                    }
                    
                    flash('project_message', 'Project Updated');
                    redirect('dashboard/projects_management');
                } else {
                    if ($this->isAjaxRequest()) {
                        header('Content-Type: application/json');
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Failed to update project']);
                        return;
                    }
                    
                    die('Something went wrong');
                }
            } else {
                // Handle validation errors
                if ($this->isAjaxRequest()) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Validation failed',
                        'errors' => [
                            'title' => $data['title_err'],
                            'description' => $data['description_err'],
                            'start_date' => $data['start_date_err'],
                            'end_date' => $data['end_date_err'],
                            'location' => $data['location_err']
                        ]
                    ]);
                    return;
                }
                
                // Load view with errors
                $data['page_title'] = 'Edit Project';
                $this->view('dashboard/project_edit', $data);
            }
        } else {
            // For GET requests
            
            // If this is an AJAX request, return the project data as JSON
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode($project);
                return;
            }
            
            // Init data with existing project
            $data = [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'category' => $project->category,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'location' => $project->location,
                'is_remote' => $project->is_remote,
                'max_participants' => $project->max_participants,
                'skills_required' => $project->skills_required,
                'image' => $project->image,
                'status' => $project->status,
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'location_err' => '',
                'page_title' => 'Edit Project'
            ];
            
            // Load view
            $this->view('dashboard/project_edit', $data);
        }
    }
    
    /**
     * Check if the current request is an AJAX request
     * 
     * @return bool True if the request is AJAX
     */
    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    // Delete project
    public function project_delete($id = null) {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing project
            $project = $this->dashboardModel->getProjectById($id);
            
            // Check if project exists
            if (!$project) {
                flash('project_message', 'Project not found', 'alert alert-danger');
                redirect('dashboard/projects_management');
            }
            
            // Delete project
            if ($this->dashboardModel->deleteProject($id)) {
                flash('project_message', 'Project Removed');
            } else {
                flash('project_message', 'Failed to remove project', 'alert alert-danger');
            }
        }
        
        redirect('dashboard/projects_management');
    }

    // Projects method - for handling /dashboard/projects route
    public function projects() {
        // Redirect to projects_management
        redirect('dashboard/projects_management');
    }
    
    // Handle adding a new project from modal form
    public function add_project() {
        // Check if admin
        if (!isAdmin()) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('pages');
            return;
        }
        
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('dashboard/projects_management');
            return;
        }
        
        // Process form data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Handle checkbox field
        $isRemote = isset($_POST['is_remote']) ? 1 : 0;
        
        // Prepare data for database
        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'category' => trim($_POST['category']),
            'start_date' => trim($_POST['start_date']),
            'end_date' => trim($_POST['end_date']),
            'location' => trim($_POST['location']),
            'is_remote' => $isRemote,
            'max_participants' => !empty($_POST['max_participants']) ? intval($_POST['max_participants']) : null,
            'skills_required' => !empty($_POST['skills_required']) ? trim($_POST['skills_required']) : null,
            'image' => !empty($_POST['image']) ? trim($_POST['image']) : null,
            'status' => trim($_POST['status']),
            'created_by' => $_SESSION['user_id']
        ];
        
        // Add project to database
        if ($this->dashboardModel->addProject($data)) {
            flash('project_message', 'Project added successfully');
        } else {
            flash('project_message', 'Failed to add project', 'alert alert-danger');
        }
        
        // Redirect to projects management page
        redirect('dashboard/projects_management');
    }

    /**
     * Update candidature status
     */
    public function update_candidature_status() {
        // Check for ajax request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Check if user is admin
        if (!isAdmin()) {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Get data from POST
        $candidatureId = isset($_POST['candidature_id']) ? $_POST['candidature_id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        
        // Validate data
        if (!$candidatureId || !$status) {
            $this->sendJsonResponse(false, 'Missing required parameters');
            return;
        }
        
        // Validate status
        $validStatuses = ['pending', 'approved', 'rejected', 'left'];
        if (!in_array($status, $validStatuses)) {
            $this->sendJsonResponse(false, 'Invalid status');
            return;
        }
        
        // Update candidature status
        if ($this->dashboardModel->updateCandidatureStatus($candidatureId, $status)) {
            $this->sendJsonResponse(true, 'Candidature status updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to update candidature status');
        }
    }

    /**
     * Update event registration status
     */
    public function update_registration_status() {
        // Check for ajax request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Check if user is admin
        if (!isAdmin()) {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Get data from POST
        $registrationId = isset($_POST['registration_id']) ? $_POST['registration_id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        
        // Validate data
        if (!$registrationId || !$status) {
            $this->sendJsonResponse(false, 'Missing required parameters');
            return;
        }
        
        // Validate status
        $validStatuses = ['pending', 'approved', 'rejected', 'attended', 'no-show'];
        if (!in_array($status, $validStatuses)) {
            $this->sendJsonResponse(false, 'Invalid status');
            return;
        }
        
        // Update registration status
        $eventsModel = $this->model('EventsModel');
        if ($eventsModel->updateRegistrationStatus($registrationId, $status)) {
            // Send email notification if status is approved or rejected
            if ($status == 'approved' || $status == 'rejected') {
                $registrationDetails = $eventsModel->getRegistrationDetailsById($registrationId);
                
                if ($registrationDetails) {
                    // Fetch the full event details to get location/virtual link
                    $eventDetails = $eventsModel->getEventById($registrationDetails->event_id);

                    $userEmail = $registrationDetails->user_email;
                    $userName = $registrationDetails->user_name;
                    $eventTitle = $registrationDetails->event_title;
                    // Format event date for the email
                    $eventDate = date('F j, Y, g:i a', strtotime($registrationDetails->event_start_date));
                    $eventLocation = $eventDetails ? $eventDetails->location : 'N/A';
                    $isVirtual = $eventDetails ? (bool)$eventDetails->is_virtual : false;
                    $eventVirtualLink = $eventDetails ? $eventDetails->virtual_link : '';


                    $emailSubject = '';
                    $emailTemplatePath = '';

                    if ($status == 'approved') {
                        $emailSubject = 'Event Registration Approved: ' . $eventTitle;
                        $emailTemplatePath = APPROOT . '/views/emails/event_registration_approved.php';
                    } else { // rejected
                        $emailSubject = 'Event Registration Update: ' . $eventTitle;
                        $emailTemplatePath = APPROOT . '/views/emails/event_registration_rejected.php';
                    }

                    // Prepare email body using the template
                    ob_start();
                    // Define SITE_NAME if not already defined for email templates
                    if (!defined('SITE_NAME')) {
                        define('SITE_NAME', 'Your Platform Name'); // Or fetch from a config file
                    }
                    include $emailTemplatePath;
                    $emailBody = ob_get_clean();

                    sendEmail($userEmail, $emailSubject, $emailBody);
                }
            }
            $this->sendJsonResponse(true, 'Registration status updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to update registration status');
        }
    }

    /**
     * Get project data for AJAX requests
     */
    public function getProjectData() {
        // Check if project ID is provided in the request
        $projectId = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$projectId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing project ID']);
            return;
        }
        
        // Get project from database
        $project = $this->dashboardModel->getProjectById($projectId);
        
        if ($project) {
            // Return project data as JSON
            header('Content-Type: application/json');
            echo json_encode($project);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'Project not found']);
        }
    }

    /**
     * Get ticket details via AJAX
     * 
     * @return void
     */
    public function getTicketDetails() {
        // Check if ticket_id is provided in the request
        $ticketId = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : null;
        
        if (!$ticketId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing ticket ID']);
            return;
        }
        
        // Get ticket from database
        $ticket = $this->dashboardModel->getSupportTicketById($ticketId);
        
        if ($ticket) {
            // Return ticket data as JSON
            header('Content-Type: application/json');
            echo json_encode($ticket);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'Ticket not found']);
        }
    }
    
    /**
     * Get ticket responses via AJAX
     * 
     * @return void
     */
    public function getTicketResponses() {
        // Check if ticketId is provided in the request
        $ticketId = isset($_GET['ticketId']) ? $_GET['ticketId'] : null;
        
        if (!$ticketId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing ticket ID']);
            return;
        }
        
        // Get responses from database
        $responses = $this->dashboardModel->getTicketResponsesById($ticketId);
        
        // Return responses as JSON
        header('Content-Type: application/json');
        echo json_encode($responses);
    }
}