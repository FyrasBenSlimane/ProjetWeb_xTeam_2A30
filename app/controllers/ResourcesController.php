<?php
class ResourcesController extends Controller {
    public function __construct() {
        // Initialize any needed properties
    }

    public function dashboard() {
        // Verify user is admin before loading dashboard pages
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            redirect('users/login');
            return;
        }
        
        // Load dashboard data
        $dashboardModel = $this->model('DashboardModel');
        $resources = $dashboardModel->getResources();
        
        $data = [
            'title' => 'Resources Management',
            'description' => 'Manage YouTube resources',
            'resources' => $resources
        ];
        
        // View will handle including the dashboard layout
        $this->view('dashboard/resources_management', $data);
    }
    
    /**
     * Extract video details from a YouTube URL
     * Used by AJAX to get video information
     */
    public function extractVideoDetails() {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check if request is POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get YouTube URL from POST data
        $youtubeUrl = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
        
        if(empty($youtubeUrl)) {
            $this->sendJsonResponse(false, 'YouTube URL is required');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Extract YouTube video data
        $videoData = $dashboardModel->extractYoutubeData($youtubeUrl);
        
        if(!$videoData) {
            $this->sendJsonResponse(false, 'Failed to extract video details. Please check the URL and try again.');
            return;
        }
        
        // Return success response with video data
        $this->sendJsonResponse(true, 'Video details extracted successfully', $videoData);
    }
    
    /**
     * Add multiple resources at once
     * Used for bulk import
     */
    public function addBulk() {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check if request is POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get data from POST
        $youtubeUrls = isset($_POST['youtube_urls']) ? json_decode($_POST['youtube_urls'], true) : [];
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        
        if(empty($youtubeUrls) || empty($category)) {
            $this->sendJsonResponse(false, 'YouTube URLs and category are required');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Track results
        $successCount = 0;
        $failedCount = 0;
        
        // Process each URL
        foreach($youtubeUrls as $url) {
            $url = trim($url);
            if(empty($url)) continue;
            
            // Extract YouTube video data
            $videoData = $dashboardModel->extractYoutubeData($url);
            
            if(!$videoData) {
                $failedCount++;
                continue;
            }
            
            // Create resource data
            $resourceData = [
                'title' => $videoData['title'],
                'youtube_url' => $url,
                'youtube_id' => $videoData['youtube_id'],
                'thumbnail_url' => $videoData['thumbnail_url'],
                'description' => '',
                'category' => $category,
                'status' => 'active'
            ];
            
            // Add resource to database
            if($dashboardModel->addResource($resourceData)) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }
        
        // Return response
        if($successCount > 0) {
            $message = "{$successCount} resources added successfully";
            if($failedCount > 0) {
                $message .= ", {$failedCount} failed";
            }
            $this->sendJsonResponse(true, $message);
        } else {
            $this->sendJsonResponse(false, 'Failed to add any resources');
        }
    }
    
    /**
     * Update an existing resource
     * Used by AJAX from edit modal
     */
    public function update() {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check if request is POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get data from POST
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $youtubeUrl = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
        $youtubeId = isset($_POST['youtube_id']) ? trim($_POST['youtube_id']) : '';
        $thumbnailUrl = isset($_POST['thumbnail_url']) ? trim($_POST['thumbnail_url']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        
        // Validate required fields
        if(empty($id) || empty($title) || empty($youtubeUrl) || empty($youtubeId) || 
           empty($thumbnailUrl) || empty($category) || empty($status)) {
            $this->sendJsonResponse(false, 'All fields except description are required');
            return;
        }
        
        // Validate status
        if(!in_array($status, ['active', 'inactive'])) {
            $this->sendJsonResponse(false, 'Invalid status value');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Verify resource exists
        $resource = $dashboardModel->getResourceById($id);
        if(!$resource) {
            $this->sendJsonResponse(false, 'Resource not found');
            return;
        }
        
        // Update resource data
        $resourceData = [
            'id' => $id,
            'title' => $title,
            'youtube_url' => $youtubeUrl,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnailUrl,
            'description' => $description,
            'category' => $category,
            'status' => $status
        ];
        
        // Update resource in database
        if($dashboardModel->updateResource($resourceData)) {
            $this->sendJsonResponse(true, 'Resource updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to update resource');
        }
    }
    
    /**
     * Delete a resource
     */
    public function delete($id = 0) {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            redirect('users/login');
            return;
        }
        
        // Check if ID is valid
        $id = intval($id);
        if($id <= 0) {
            flash('resource_message', 'Invalid resource ID', 'alert alert-danger');
            redirect('dashboard/resources');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Delete resource
        if($dashboardModel->deleteResource($id)) {
            flash('resource_message', 'Resource deleted successfully', 'alert alert-success');
        } else {
            flash('resource_message', 'Failed to delete resource', 'alert alert-danger');
        }
        
        redirect('dashboard/resources');
    }
    
    /**
     * Toggle resource status (active/inactive)
     * Used by AJAX
     */
    public function toggleStatus() {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check if request is POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get data from POST
        $resourceId = isset($_POST['resourceId']) ? intval($_POST['resourceId']) : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        
        // Validate data
        if(empty($resourceId) || !in_array($status, ['active', 'inactive'])) {
            $this->sendJsonResponse(false, 'Invalid resource ID or status');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Get current resource
        $resource = $dashboardModel->getResourceById($resourceId);
        if(!$resource) {
            $this->sendJsonResponse(false, 'Resource not found');
            return;
        }
        
        // Update resource data with new status
        $resourceData = [
            'id' => $resourceId,
            'title' => $resource->title,
            'youtube_url' => $resource->youtube_url,
            'youtube_id' => $resource->youtube_id,
            'thumbnail_url' => $resource->thumbnail_url,
            'description' => $resource->description,
            'category' => $resource->category,
            'status' => $status
        ];
        
        // Update resource in database
        if($dashboardModel->updateResource($resourceData)) {
            $this->sendJsonResponse(true, 'Resource status updated successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to update resource status');
        }
    }
    
    /**
     * Add a single resource
     * Used by AJAX
     */
    public function add() {
        // Check if user is admin
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            $this->sendJsonResponse(false, 'Unauthorized access');
            return;
        }
        
        // Check if request is POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Invalid request method');
            return;
        }
        
        // Get data from POST
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $youtubeUrl = isset($_POST['youtube_url']) ? trim($_POST['youtube_url']) : '';
        $youtubeId = isset($_POST['youtube_id']) ? trim($_POST['youtube_id']) : '';
        $thumbnailUrl = isset($_POST['thumbnail_url']) ? trim($_POST['thumbnail_url']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        // Validate required fields
        if(empty($title) || empty($youtubeUrl) || empty($youtubeId) || 
           empty($thumbnailUrl) || empty($category)) {
            $this->sendJsonResponse(false, 'All fields except description are required');
            return;
        }
        
        // Load dashboard model
        $dashboardModel = $this->model('DashboardModel');
        
        // Add resource data
        $resourceData = [
            'title' => $title,
            'youtube_url' => $youtubeUrl,
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnailUrl,
            'description' => $description,
            'category' => $category,
            'status' => $status
        ];
        
        // Add resource to database
        if($dashboardModel->addResource($resourceData)) {
            $this->sendJsonResponse(true, 'Resource added successfully');
        } else {
            $this->sendJsonResponse(false, 'Failed to add resource');
        }
    }
    
    /**
     * Helper method to send JSON responses
     */
    private function sendJsonResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
} 