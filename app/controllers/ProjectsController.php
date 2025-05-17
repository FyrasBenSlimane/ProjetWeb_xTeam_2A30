<?php
/**
 * ProjectsController
 * Handles all operations related to collaborative projects
 */
class ProjectsController extends Controller {
    private $projectsModel;
    
    public function __construct() {
        // Initialize the projects model
        $this->projectsModel = $this->model('ProjectsModel');
    }
    
    /**
     * Display projects listing page
     */
    public function index() {
        // Check if user is logged in
        $is_logged_in = isLoggedIn();
        
        // Get all active projects
        $projects = $this->projectsModel->getActiveProjects();

        // Ensure projects is an array. If the model returned false or null (e.g. on DB error),
        // default to an empty array and log the issue.
        if ($projects === false || $projects === null) {
            error_log('ProjectsController: getActiveProjects() returned false or null. Defaulting to empty. Potential DB error.');
            $projects = []; // Ensure it's an empty array for the view
        } elseif (!is_array($projects)) {
            // If it's not false/null but also not an array (e.g. a single object when an array was expected)
            error_log('ProjectsController: getActiveProjects() did not return an array as expected. Defaulting to empty.');
            $projects = [];
        }
        
        // Get user candidatures if logged in
        $candidatures = [];
        if ($is_logged_in) {
            $userId = $_SESSION['user_id'];
            $candidatures = $this->projectsModel->getUserCandidatures($userId);
        }
        
        // Count participants for each project
        if (!empty($projects)) {
            foreach ($projects as &$project) {
                if (!isset($project->participants_count)) {
                    $project->participants_count = $this->projectsModel->countProjectParticipants($project->id);
                }
            }
        }

        // SECOND TEMPORARY DEBUGGING: Output projects before $data array assignment
        // echo '<pre>DEBUG: Projects *before* $data array assignment: '; print_r($projects); echo '</pre>';
        // END SECOND TEMPORARY DEBUGGING
        
        $data = [
            'title' => 'Collaborative Projects',
            'description' => 'Join collaborative projects with other professionals',
            'is_logged_in' => $is_logged_in,
            'projects' => $projects,
            'candidatures' => $candidatures
        ];
        
        // Load view with data
        $this->view('layouts/header', $data);
        $this->view('community/projects', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Display single project details
     * 
     * @param int $id Project ID
     */
    public function viewProject($id = null) {
        // Check if ID is provided
        if (!$id) {
            redirect('projects');
            return;
        }
        
        // Get project by ID
        $project = $this->projectsModel->getProjectById($id);
        
        // Check if project exists
        if (!$project) {
            flash('project_error', 'Project not found', 'alert alert-danger');
            redirect('projects');
            return;
        }
        
        // Check if user is logged in
        $is_logged_in = isLoggedIn();
        
        // Check if user has applied to this project
        $hasApplied = false;
        if ($is_logged_in) {
            $userId = $_SESSION['user_id'];
            $hasApplied = $this->projectsModel->hasUserCandidature($project->id, $userId);
        }
        
        // Get project candidatures
        $candidatures = $this->projectsModel->getProjectCandidatures($project->id);
        $approvedCount = 0;
        foreach ($candidatures as $candidature) {
            if ($candidature->status === 'approved') {
                $approvedCount++;
            }
        }
        
        $data = [
            'title' => $project->title,
            'description' => substr($project->description, 0, 150),
            'is_logged_in' => $is_logged_in,
            'project' => $project,
            'has_applied' => $hasApplied,
            'candidatures' => $candidatures,
            'approved_count' => $approvedCount
        ];
        
        // Load view with data
        $this->view('layouts/header', $data);
        $this->view('projects/view', $data);
        $this->view('layouts/footer');
    }
      /**
     * Handle apply to project
     * 
     * @param int $id Project ID
     */
    public function apply($id = null) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
            return;
        }
        
        // Check if ID is provided
        if (!$id) {
            redirect('projects');
            return;
        }
        
        // Check if project exists
        $project = $this->projectsModel->getProjectById($id);
        if (!$project) {
            flash('project_error', 'Project not found', 'alert alert-danger');
            redirect('projects');
            return;
        }
        
        // Check if already applied
        $userId = $_SESSION['user_id'];
        if ($this->projectsModel->hasUserCandidature($project->id, $userId)) {
            flash('project_error', 'You have already applied to this project', 'alert alert-danger');
            redirect('projects/viewProject/' . $id);
            return;
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare data
            $data = [
                'project_id' => $id,
                'user_id' => $userId,
                'message' => trim($_POST['message']),
                'skills' => trim($_POST['skills']),
                'status' => 'pending'
            ];
            
            // Apply to project
            if ($this->projectsModel->addCandidature($data)) {
                flash('project_success', 'Application submitted successfully', 'alert alert-success');
                redirect('projects/viewProject/' . $id);
            } else {
                flash('project_error', 'Something went wrong', 'alert alert-danger');
                redirect('projects/apply/' . $id);
            }
        } else {
            // Display apply form
            $data = [
                'title' => 'Apply for Project: ' . $project->title,
                'description' => 'Apply to join ' . $project->title,
                'project' => $project
            ];
            
            $this->view('layouts/header', $data);
            $this->view('projects/apply', $data);
            $this->view('layouts/footer');
        }
    }
      /**
     * Handle JSON application to project via AJAX
     * This endpoint accepts POST request with application data in JSON format
     */
    public function applyToProjectJson() {
        // Set the response content type to JSON
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!isLoggedIn()) {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'You must be logged in to apply for projects']);
            return;
        }
        
        // Check for POST and JSON content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        
        if (!$data || !isset($data->project_id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Invalid request data']);
            return;
        }
        
        $projectId = filter_var($data->project_id, FILTER_SANITIZE_NUMBER_INT);
        $message = isset($data->message) ? filter_var($data->message, FILTER_SANITIZE_STRING) : '';
        $skills = isset($data->skills) ? filter_var($data->skills, FILTER_SANITIZE_STRING) : '';
        
        // Check if project exists
        $project = $this->projectsModel->getProjectById($projectId);
        if (!$project) {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'Project not found']);
            return;
        }
        
        // Check if already applied
        $userId = $_SESSION['user_id'];
        if ($this->projectsModel->hasUserCandidature($projectId, $userId)) {
            http_response_code(409); // Conflict
            echo json_encode(['success' => false, 'message' => 'You have already applied to this project']);
            return;
        }
        
        // Validate input
        if (empty($message) || strlen($message) < 10) {
            http_response_code(422); // Unprocessable Entity
            echo json_encode(['success' => false, 'message' => 'Please provide a more detailed message']);
            return;
        }
        
        // Prepare data
        $candidatureData = [
            'project_id' => $projectId,
            'user_id' => $userId,
            'message' => $message,
            'skills' => $skills,
            'status' => 'pending'
        ];
          // Apply to project
        if ($this->projectsModel->addCandidature($candidatureData)) {
            echo json_encode(['success' => true, 'message' => 'Application submitted successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to submit application. Please try again.']);
        }
    }
    
    /**
     * Create a new project
     */
    public function createProject() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
            return;
        }
        
        // Implementation will go here
    }
    
    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
            return;
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Handle checkbox
            $isRemote = isset($_POST['is_remote']) ? 1 : 0;
            
            // Prepare data
            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'category' => trim($_POST['category']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'location' => trim($_POST['location']),
                'is_remote' => $isRemote,
                'max_participants' => !empty($_POST['max_participants']) ? intval($_POST['max_participants']) : null,
                'skills_required' => trim($_POST['skills_required']),
                'image' => trim($_POST['image']),
                'status' => 'active', // Set status as active by default
                'created_by' => $_SESSION['user_id'],
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'category_err' => ''
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
            
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['description_err']) && 
                empty($data['start_date_err']) && empty($data['end_date_err']) && 
                empty($data['category_err'])) {
                
                // Create project
                if ($this->projectsModel->addProject($data)) {
                    flash('project_success', 'Project created successfully', 'alert alert-success');
                    redirect('projects');
                } else {
                    flash('project_error', 'Something went wrong', 'alert alert-danger');
                    $this->view('layouts/header', $data);
                    $this->view('projects/create', $data);
                    $this->view('layouts/footer');
                }
            } else {
                // Load view with errors
                $this->view('layouts/header', $data);
                $this->view('projects/create', $data);
                $this->view('layouts/footer');
            }
        } else {
            // Init data
            $data = [
                'title' => 'Create New Project',
                'description' => 'Create a new collaborative project',
                'title_value' => '',
                'description_value' => '',
                'category' => 'web-development',
                'start_date' => date('Y-m-d\TH:i'),
                'end_date' => date('Y-m-d\TH:i', strtotime('+30 days')),
                'location' => '',
                'is_remote' => 1,
                'max_participants' => 5,
                'skills_required' => '',
                'image' => '',
                'title_err' => '',
                'description_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'category_err' => ''
            ];
            
            $this->view('layouts/header', $data);
            $this->view('projects/create', $data);
            $this->view('layouts/footer');
        }
    }
}
