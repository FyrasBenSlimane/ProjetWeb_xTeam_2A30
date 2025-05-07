<?php
/**
 * DashboardController Class
 * Handles dashboard functionality and data processing
 */

// Include the DashboardService class
require_once APP_ROOT . '/helpers/DashboardService.php';

class DashboardController extends Controller {
    private $dashboardModel;

    public function __construct() {
        // Initialize dashboard model
        $this->dashboardModel = $this->model('DashboardModel');
        
        // Check if user is authorized to access the dashboard
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            redirect('users/login');
        }
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
        $tickets = $this->dashboardModel->getSupportTicketsData();
        
        // Log this support tickets view
        $dashboardService = DashboardService::getInstance();
        $dashboardService->logActivity('view', 'support_tickets');
        
        $data = [
            'title' => 'Support Tickets',
            'description' => 'Manage support tickets',
            'tickets' => $tickets
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
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get user ID and new status from POST data
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : null; // Changed from 'newStatus' to 'status'
        
        if (!$userId || !$newStatus) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }
        
        // Update user status in database
        $success = $this->dashboardModel->updateUserStatus($userId, $newStatus);
        
        if ($success) {
            // Return success response with content type header
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User status updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
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
}