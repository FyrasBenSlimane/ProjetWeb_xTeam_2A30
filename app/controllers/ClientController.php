<?php
class ClientController extends Controller {
    private $jobModel;
    private $userModel;
    
    public function __construct() {
        // Check if user is logged in and has client account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'client') {
            redirect('users/login');
        }
        
        // Initialize models
        $this->jobModel = $this->model('Job');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Main client dashboard
     */
    public function index() {
        // Get posted jobs data
        $postedJobs = $this->jobModel->getJobsByUserId($_SESSION['user_id']);
        
        // Get user info
        $userId = $_SESSION['user_id'];
        $userInfo = $this->userModel->getUserById($userId);
        
        // Get stats
        $stats = [
            'total_jobs' => count($postedJobs),
            'active_jobs' => 0,
            'completed_jobs' => 0,
            'total_spent' => 0
        ];
        
        // Calculate stats
        foreach ($postedJobs as $job) {
            if ($job->status === 'active') {
                $stats['active_jobs']++;
            } elseif ($job->status === 'completed') {
                $stats['completed_jobs']++;
            }
            
            // Calculate total spent/committed (if applicable)
            if ($job->budget) {
                $stats['total_spent'] += $job->budget;
            }
        }
        
        $data = [
            'title' => 'Client Dashboard',
            'description' => 'Post and manage your jobs',
            'jobs' => $postedJobs,
            'user' => $userInfo,
            'stats' => $stats
        ];
        
        // Load views with layout
        $this->view('layouts/header', $data);
        $this->view('pages/client', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Show job posting form
     */
    public function postJob() {
        // Get categories for the form
        $categories = $this->getJobCategories();
        
        $data = [
            'title' => 'Post a New Job',
            'description' => 'Create a new job posting',
            'categories' => $categories
        ];
        
        // Load views with layout
        $this->view('layouts/header', $data);
        $this->view('pages/post_job', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Create a new job posting
     */
    public function createJob() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare job data
            $jobData = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => $_POST['category'],
                'skills' => isset($_POST['skills']) ? json_encode($_POST['skills']) : '[]',
                'budget' => (float)$_POST['budget'],
                'job_type' => $_POST['job_type'],
                'experience_level' => $_POST['experience_level'],
                'duration' => $_POST['duration'] ?? null,
                'status' => 'active'
            ];
            
            // Validate data
            $errors = $this->validateJobData($jobData);
            
            if (empty($errors)) {
                // Create job
                if ($this->jobModel->createJob($jobData)) {
                    flash('job_message', 'Job posted successfully');
                    redirect('client');
                } else {
                    // Something went wrong
                    flash('job_message', 'Failed to post job', 'alert alert-danger');
                    
                    // Redisplay form with entered data
                    $data = [
                        'title' => 'Post a New Job',
                        'description' => 'Create a new job posting',
                        'categories' => $this->getJobCategories(),
                        'job_data' => $jobData,
                        'errors' => ['Something went wrong while posting the job']
                    ];
                    
                    $this->view('layouts/header', $data);
                    $this->view('pages/post_job', $data);
                    $this->view('layouts/footer');
                }
            } else {
                // Display form with errors
                $data = [
                    'title' => 'Post a New Job',
                    'description' => 'Create a new job posting',
                    'categories' => $this->getJobCategories(),
                    'job_data' => $jobData,
                    'errors' => $errors
                ];
                
                $this->view('layouts/header', $data);
                $this->view('pages/post_job', $data);
                $this->view('layouts/footer');
            }
        } else {
            // Not a POST request
            redirect('client/postJob');
        }
    }
    
    /**
     * View applications for a job
     * 
     * @param int $jobId Job ID
     */
    public function viewApplications($jobId) {
        // Check if job belongs to this client
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job || $job->user_id != $_SESSION['user_id']) {
            flash('job_message', 'Access denied or job not found', 'alert alert-danger');
            redirect('client');
        }
        
        // Get applications for this job
        $applications = $this->jobModel->getJobApplications($jobId);
        
        $data = [
            'title' => 'Job Applications',
            'description' => 'View and manage applications for your job',
            'job' => $job,
            'applications' => $applications
        ];
        
        // Load views with layout
        $this->view('layouts/header', $data);
        $this->view('pages/applications', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Get job applications as JSON for AJAX requests
     * 
     * @param int $jobId Job ID
     */
    public function getJobApplications($jobId) {
        // Ensure no output before headers
        ob_start();
        
        // Check if AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            // Redirect to normal view if not AJAX
            ob_end_clean();
            $this->viewApplications($jobId);
            return;
        }
        
        // Set content type to application/json before any output
        header('Content-Type: application/json');
        
        try {
            // Check if job belongs to this client
            $job = $this->jobModel->getJobById($jobId);
            
            if (!$job || $job->user_id != $_SESSION['user_id']) {
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Access denied or job not found'
                ]);
                return;
            }
            
            // Get applications for this job
            $applications = $this->jobModel->getJobApplications($jobId);
            
            // Clean output buffer
            ob_end_clean();
            
            // Return JSON response
            echo json_encode([
                'success' => true,
                'job' => $job,
                'applications' => $applications
            ], JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ]);
        }
        exit; // Prevent any other output after JSON
    }
    
    /**
     * Decline a job application
     */
    public function declineApplication() {
        // Check if AJAX request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client');
            return;
        }
        
        // Set content type to application/json before any output
        header('Content-Type: application/json');
        
        // Get application and job IDs
        $applicationId = isset($_POST['application_id']) ? $_POST['application_id'] : null;
        $jobId = isset($_POST['job_id']) ? $_POST['job_id'] : null;
        
        if (!$applicationId || !$jobId) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required information'
            ]);
            exit;
        }
        
        // Check if job belongs to this client
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job || $job->user_id != $_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Access denied or job not found'
            ]);
            exit;
        }
        
        // Decline the application
        // TODO: Implement this method in Job model
        $result = true; // For now, just pretend it worked
        
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Application declined successfully' : 'Failed to decline application'
        ]);
        exit;
    }
    
    /**
     * Hire a freelancer for a job
     */
    public function hireFreelancer() {
        // Check if AJAX request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client');
            return;
        }
        
        // Set content type to application/json before any output
        header('Content-Type: application/json');
        
        // Get application and job IDs
        $applicationId = isset($_POST['application_id']) ? $_POST['application_id'] : null;
        $jobId = isset($_POST['job_id']) ? $_POST['job_id'] : null;
        
        if (!$applicationId || !$jobId) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required information'
            ]);
            exit;
        }
        
        // Check if job belongs to this client
        $job = $this->jobModel->getJobById($jobId);
        
        if (!$job || $job->user_id != $_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Access denied or job not found'
            ]);
            exit;
        }
        
        // Get application to find freelancer user ID
        $application = $this->jobModel->getApplicationById($applicationId);
        
        if (!$application) {
            echo json_encode([
                'success' => false,
                'message' => 'Application not found'
            ]);
            exit;
        }
        
        // Create conversation (if it doesn't exist)
        // For now, just pretend it worked
        $conversationId = 123;
        
        echo json_encode([
            'success' => true,
            'message' => 'Freelancer hired successfully',
            'conversation_id' => $conversationId
        ]);
        exit;
    }
    
    /**
     * Get job categories
     * 
     * @return array Categories
     */
    private function getJobCategories() {
        return [
            'Web Development',
            'Mobile Development',
            'UI/UX Design',
            'Data Science',
            'Machine Learning',
            'Content Writing',
            'Marketing',
            'SEO',
            'Translation',
            'Customer Service',
            'Accounting',
            'Legal Services',
            'Other'
        ];
    }
    
    /**
     * Validate job data
     *
     * @param array $data Job data
     * @return array Array of error messages
     */
    private function validateJobData($data, $isUpdate = false) {
        $errors = [];

        // Validate job title
        if (empty($data['title'])) {
            $errors[] = 'Please enter a job title';
        } elseif (strlen($data['title']) < 5) {
            $errors[] = 'Job title must be at least 5 characters';
        } elseif (strlen($data['title']) > 100) {
            $errors[] = 'Job title cannot exceed 100 characters';
        }

        // Validate job description
        if (empty($data['description'])) {
            $errors[] = 'Please enter a job description';
        } elseif (strlen($data['description']) < 20) {
            $errors[] = 'Job description must be at least 20 characters';
        }

        // Validate category (only for new jobs, not updates)
        if (!$isUpdate && empty($data['category'])) {
            $errors[] = 'Please select a valid category';
        }

        // Validate budget
        if (empty($data['budget']) || !is_numeric($data['budget']) || $data['budget'] <= 0) {
            $errors[] = 'Please enter a valid budget amount greater than zero';
        }

        // Validate job type
        if (empty($data['job_type']) || !in_array($data['job_type'], ['fixed', 'hourly'])) {
            $errors[] = 'Please select a valid job type';
        }

        // Validate experience level
        if (empty($data['experience_level']) || !in_array($data['experience_level'], ['entry', 'intermediate', 'expert'])) {
            $errors[] = 'Please select a valid experience level';
        }

        return $errors;
    }
    
    /**
     * Update an existing job posting
     */
    public function updateJob() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get job ID
            $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
            
            // Check if job exists and belongs to this client
            $existingJob = $this->jobModel->getJobById($jobId);
            if (!$existingJob || $existingJob->user_id != $_SESSION['user_id']) {
                // Return JSON response if it's an AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Job not found or access denied']);
                    exit;
                }
                
                flash('job_message', 'Job not found or access denied', 'alert alert-danger');
                redirect('client');
            }

            // Prepare job data
            $jobData = [
                'id' => $jobId,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'skills' => isset($_POST['skills']) ? $_POST['skills'] : '',
                'budget' => (float)$_POST['budget'],
                'job_type' => $_POST['job_type'],
                'experience_level' => $_POST['experience_level'],
                'duration' => $_POST['duration'] ?? null
            ];

            // Validate data
            $errors = $this->validateJobData($jobData, true);

            if (empty($errors)) {
                // Update job
                if ($this->jobModel->updateJob($jobData)) {
                    // Return JSON response if it's an AJAX request
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true]);
                        exit;
                    }
                    
                    flash('job_message', 'Job updated successfully');
                    redirect('client');
                } else {
                    // Something went wrong
                    // Return JSON response if it's an AJAX request
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Failed to update job']);
                        exit;
                    }
                    
                    flash('job_message', 'Failed to update job', 'alert alert-danger');
                    redirect('client');
                }
            } else {
                // Return JSON response if it's an AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
                    exit;
                }
                
                // Display form with errors
                flash('job_message', implode('<br>', $errors), 'alert alert-danger');
                redirect('client');
            }
        } else {
            // Not a POST request
            redirect('client');
        }
    }

    /**
     * Change job status (active, paused, closed)
     */
    public function changeJobStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get job ID and status
            $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            
            // Validate status
            if (!in_array($status, ['active', 'paused', 'closed'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid job status']);
                exit;
            }
            
            // Check if job exists and belongs to this client
            $existingJob = $this->jobModel->getJobById($jobId);
            if (!$existingJob || $existingJob->user_id != $_SESSION['user_id']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Job not found or access denied']);
                exit;
            }

            // Update job status
            if ($this->jobModel->updateJobStatus($jobId, $status)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to update job status']);
                exit;
            }
        } else {
            // Not a POST request
            redirect('client');
        }
    }

    /**
     * Delete a job
     */
    public function deleteJob() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get job ID
            $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
            
            // Check if job exists and belongs to this client
            $existingJob = $this->jobModel->getJobById($jobId);
            if (!$existingJob || $existingJob->user_id != $_SESSION['user_id']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Job not found or access denied']);
                exit;
            }

            // Check if job has applications or contracts
            $applications = $this->jobModel->getJobApplications($jobId);
            if (count($applications) > 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cannot delete job with existing applications. Consider closing it instead.']);
                exit;
            }

            // Delete job
            if ($this->jobModel->deleteJob($jobId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
                exit;
            }
        } else {
            // Not a POST request
            redirect('client');
        }
    }

    /**
     * Create a new job posting via AJAX
     */
    public function postJobAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Set content type to application/json for AJAX response
            header('Content-Type: application/json');
            
            // Process form data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare skills array from comma-separated string
            $skills = isset($_POST['skills']) && !empty($_POST['skills']) 
                ? json_encode(array_map('trim', explode(',', $_POST['skills']))) 
                : '[]';
            
            // Prepare job data
            $jobData = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => $_POST['category'] ?? 'other',
                'skills' => $skills,
                'budget' => (float)$_POST['budget'],
                'job_type' => $_POST['job_type'] ?? 'fixed',
                'experience_level' => $_POST['experience_level'],
                'duration' => $_POST['duration'] ?? null,
                'status' => 'active'
            ];
            
            // Validate data
            $errors = $this->validateJobData($jobData);
            
            if (empty($errors)) {
                // Create job
                if ($this->jobModel->createJob($jobData)) {
                    // Success - return JSON response
                    echo json_encode([
                        'success' => true,
                        'message' => 'Job posted successfully'
                    ]);
                } else {
                    // Error - return JSON response
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to post job, please try again',
                        'errors' => ['Something went wrong while posting the job']
                    ]);
                }
            } else {
                // Validation error - return JSON response
                echo json_encode([
                    'success' => false,
                    'message' => 'Please correct the errors in the form',
                    'errors' => $errors
                ]);
            }
            
            // End execution
            exit;
        } else {
            // Not a POST request
            redirect('client');
        }
    }

    /**
     * Get job details as JSON for AJAX requests
     * 
     * @param int $jobId Job ID
     */
    public function getJobDetails($jobId) {
        // Ensure no output before headers
        ob_start();
        
        // Set content type to application/json before any output
        header('Content-Type: application/json');
        
        try {
            // Check if job belongs to this client
            $job = $this->jobModel->getJobById($jobId);
            
            if (!$job || $job->user_id != $_SESSION['user_id']) {
                ob_end_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Access denied or job not found'
                ]);
                return;
            }
            
            // Get applications for this job
            $applications = $this->jobModel->getJobApplications($jobId);
            
            // Add time ago for readability
            $job->time_ago = (new \Job())->getTimeAgo($job->created_at);
            
            // Clean output buffer
            ob_end_clean();
            
            // Return JSON response
            echo json_encode([
                'success' => true,
                'job' => $job,
                'applications' => $applications
            ], JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Error processing request: ' . $e->getMessage()
            ]);
        }
        exit; // Prevent any other output after JSON
    }

    /**
     * Update a specific field of a job via AJAX
     */
    public function updateJobField() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client');
            return;
        }

        // Set content type to application/json for AJAX response
        header('Content-Type: application/json');
        
        // Get the job ID, field name, and new value
        $jobId = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
        $field = isset($_POST['field']) ? $_POST['field'] : '';
        $value = isset($_POST['value']) ? $_POST['value'] : '';
        
        // Validate the field name
        $allowedFields = ['title', 'description', 'category', 'budget', 'skills', 'duration', 'status'];
        if (!in_array($field, $allowedFields)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid field'
            ]);
            exit;
        }
        
        // Check if job exists and belongs to this client
        $existingJob = $this->jobModel->getJobById($jobId);
        if (!$existingJob || $existingJob->user_id != $_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Job not found or access denied'
            ]);
            exit;
        }
        
        // Field-specific validation
        $error = '';
        switch ($field) {
            case 'title':
                if (empty($value)) {
                    $error = 'Title cannot be empty';
                } elseif (strlen($value) < 5) {
                    $error = 'Title must be at least 5 characters';
                } elseif (strlen($value) > 100) {
                    $error = 'Title cannot exceed 100 characters';
                }
                break;
                
            case 'description':
                if (empty($value)) {
                    $error = 'Description cannot be empty';
                } elseif (strlen($value) < 20) {
                    $error = 'Description must be at least 20 characters';
                }
                break;
                
            case 'budget':
                if (!is_numeric($value) || (float)$value <= 0) {
                    $error = 'Budget must be a positive number';
                }
                break;
                
            case 'status':
                if (!in_array($value, ['active', 'paused', 'closed'])) {
                    $error = 'Invalid status value';
                }
                break;
        }
        
        if (!empty($error)) {
            echo json_encode([
                'success' => false,
                'message' => $error
            ]);
            exit;
        }
        
        // Prepare data for update - only the single field
        $updateData = [
            'id' => $jobId,
            $field => $value
        ];
        
        // Update the job
        if ($this->jobModel->updateJobField($jobId, $field, $value)) {
            echo json_encode([
                'success' => true,
                'message' => 'Field updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update field'
            ]);
        }
        exit;
    }
} 