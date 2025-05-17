<?php
class FreelanceController extends Controller {
    private $jobModel;
    private $userModel;
    
    public function __construct() {
        // Check if user is logged in and has freelancer account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'freelancer') {
            redirect('users/login');
        }
        
        // Initialize models
        $this->jobModel = $this->model('Job');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Main freelance dashboard
     */
    public function index() {
        // Get search parameters from URL if any
        $search = isset($_GET['job_search']) ? trim($_GET['job_search']) : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        
        // Get jobs data
        $filters = [
            'search' => $search,
            'sort' => $sort,
            'limit' => 10
        ];
        
        $jobs = $this->jobModel->getFilteredJobs($filters);
        
        // Get user info for profile section
        $userId = $_SESSION['user_id'];
        $userInfo = $this->userModel->getUserById($userId);
        
        // Get count of saved jobs
        $savedJobsCount = $this->jobModel->getSavedJobsCount($userId);
        
        // Process jobs data to make it ready for view
        foreach ($jobs as &$job) {
            // Convert skills from JSON to array
            $job->skillsArray = $this->jobModel->formatSkills($job->skills);
            
            // Format posted time
            $job->posted_time = $this->jobModel->getTimeAgo($job->created_at);
            
            // Check if job is saved by this user
            $job->is_saved = $this->jobModel->isJobSaved($job->id, $userId);
        }
        
        $data = [
            'title' => 'Freelance Dashboard',
            'description' => 'Find and manage freelance jobs',
            'jobs' => $jobs,
            'user' => $userInfo,
            'saved_jobs_count' => $savedJobsCount,
            'search' => $search,
            'sort' => $sort
        ];
        
        // Load views with layout
        $this->view('layouts/header', $data);
        $this->view('pages/freelance', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Search jobs via AJAX
     */
    public function search() {
        // This method will be called via AJAX to get filtered jobs
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get search parameters
            $search = isset($_GET['job_search']) ? trim($_GET['job_search']) : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $jobType = isset($_GET['job_type']) ? $_GET['job_type'] : '';
            $experienceLevel = isset($_GET['experience_level']) ? $_GET['experience_level'] : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
            
            // Build filters array
            $filters = [
                'search' => $search,
                'category' => $category,
                'job_type' => $jobType,
                'experience_level' => $experienceLevel,
                'sort' => $sort
            ];
            
            // Get jobs data
            $jobs = $this->jobModel->getFilteredJobs($filters);
            
            // Process jobs data
            $userId = $_SESSION['user_id'];
            foreach ($jobs as &$job) {
                $job->skillsArray = $this->jobModel->formatSkills($job->skills);
                $job->posted_time = $this->jobModel->getTimeAgo($job->created_at);
                $job->is_saved = $this->jobModel->isJobSaved($job->id, $userId);
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($jobs);
        } else {
            // Invalid request method
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    /**
     * Save/unsave a job
     */
    public function toggleSaveJob() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get job ID from POST data
            $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
            $userId = $_SESSION['user_id'];
            
            if ($jobId <= 0) {
                // Invalid job ID
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid job ID']);
                return;
            }
            
            // Initialize database connection
            $db = new Database();
            
            // Check if job is already saved
            $isSaved = $this->jobModel->isJobSaved($jobId, $userId);
            
            if ($isSaved) {
                // Job is already saved, so unsave it
                $db->query("DELETE FROM saved_jobs WHERE job_id = :job_id AND user_id = :user_id");
                $db->bind(':job_id', $jobId);
                $db->bind(':user_id', $userId);
                $success = $db->execute();
                
                $message = 'Job removed from saved jobs';
                $newStatus = false;
            } else {
                // Job is not saved, so save it
                $db->query("INSERT INTO saved_jobs (job_id, user_id, created_at) VALUES (:job_id, :user_id, NOW())");
                $db->bind(':job_id', $jobId);
                $db->bind(':user_id', $userId);
                $success = $db->execute();
                
                $message = 'Job saved successfully';
                $newStatus = true;
            }
            
            // Get updated count of saved jobs
            $savedJobsCount = $this->jobModel->getSavedJobsCount($userId);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $message,
                'is_saved' => $newStatus,
                'saved_jobs_count' => $savedJobsCount
            ]);
        } else {
            // Invalid request method
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    /**
     * Get job details via AJAX
     * 
     * @param int $id Job ID
     */
    public function getJobDetails($id = 0) {
        // Log the request for debugging
        error_log("getJobDetails called with ID: " . $id);
        
        // Prevent any output before headers
        ob_start();
        
        try {
            // Convert to integer
            $id = (int)$id;
            
            // Check if job ID is valid
            if ($id <= 0) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid job ID'
                ]);
                return;
            }
            
            // Get job data
            try {
                $job = $this->jobModel->getJobById($id);
                
                if (!$job) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'Job not found'
                    ]);
                    return;
                }
                
                // Format job data
                $job->skillsArray = $this->jobModel->formatSkills($job->skills);
                $job->posted_time = $this->jobModel->getTimeAgo($job->created_at);
                
                // Get client information
                $clientInfo = null;
                if ($job->user_id) {
                    try {
                        $clientInfo = $this->userModel->getClientInfo($job->user_id);
                    } catch (Exception $e) {
                        error_log('Error fetching client info: ' . $e->getMessage());
                        // Continue without client info - it's not critical
                        $clientInfo = (object)[
                            'name' => $job->client_name,
                            'location' => 'Unknown',
                            'member_since' => 'Unknown',
                            'jobs_posted' => '0',
                            'total_spent' => '0.00'
                        ];
                    }
                }
                
                // Get job activity (e.g., number of proposals, views)
                $activity = $this->getJobActivity($id);
                
                // Clear any existing output buffer
                ob_clean();
                
                // Return JSON response with all job details
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'job' => $job,
                    'clientInfo' => $clientInfo,
                    'activity' => $activity
                ]);
            } catch (Exception $e) {
                // Log the database error
                error_log('Database error fetching job: ' . $e->getMessage());
                
                // Return error response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        } catch (Exception $e) {
            // Clear output buffer
            ob_clean();
            
            // Return error as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error fetching job details: ' . $e->getMessage()
            ]);
        }
        
        // Ensure we end execution after JSON response
        exit();
    }
    
    /**
     * Get job activity data (proposals, views, etc.)
     * 
     * @param int $jobId Job ID
     * @return array Activity data
     */
    private function getJobActivity($jobId) {
        // Initialize database
        $db = new Database();
        
        // Get proposal count
        $db->query("SELECT COUNT(*) as count FROM applications WHERE job_id = :job_id");
        $db->bind(':job_id', $jobId);
        $proposalCount = $db->single()->count ?? 0;
        
        // For demonstration, we'll create some simulated view count
        // In a real application, you'd track this data
        $viewCount = mt_rand(10, 100); // Random number for demo purposes
        
        return [
            'proposals' => $proposalCount,
            'views' => $viewCount
        ];
    }
    
    /**
     * Apply for a job
     */
    public function applyForJob() {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Redirect to freelance dashboard if not POST
            redirect('freelance');
            return;
        }
        
        // Set content type to application/json for AJAX response
        header('Content-Type: application/json');
        
        // Get job ID and cover letter from POST data
        $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
        $coverLetter = isset($_POST['cover_letter']) ? trim($_POST['cover_letter']) : '';
        $bidAmount = isset($_POST['bid_amount']) ? (float)$_POST['bid_amount'] : null;
        
        // Validate input
        $errors = [];
        
        if ($jobId <= 0) {
            $errors[] = 'Invalid job ID';
        }
        
        if (empty($coverLetter)) {
            $errors[] = 'Cover letter is required';
        }
        
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please fix the following errors:',
                'errors' => $errors
            ]);
            return;
        }
        
        // Get the freelancer ID from session
        $freelancerId = $_SESSION['user_id'];
        
        // Apply for the job
        $result = $this->jobModel->applyForJob($jobId, $freelancerId, $coverLetter, $bidAmount);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'application_id' => $result,
                'message' => 'Your application has been submitted successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'You have already applied for this job or there was an error with your application.'
            ]);
        }
    }
} 