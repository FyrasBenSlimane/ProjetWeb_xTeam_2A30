<?php
class PagesController extends Controller {
    public function __construct() {
        // Initialize any needed properties
    }

    public function index() {
        $data = [
            'title' => 'Welcome to lenSi',
            'description' => 'A professional platform for connecting talent'
        ];
        
        // Check if user is logged in
        if(isset($_SESSION['user_id'])) {
            // User is logged in - route based on user_account_type
            if(isset($_SESSION['user_account_type'])) {
                if($_SESSION['user_account_type'] === 'freelancer') {
                    // Load the freelancer dashboard
                    $this->view('layouts/header', $data);
                    $this->view('pages/freelancer', $data);
                    $this->view('layouts/footer');
                } elseif($_SESSION['user_account_type'] === 'admin') {
                    // Load the admin dashboard directly without header/footer
                    $this->view('dashboard/index', $data);
                } else {
                    // Load the client dashboard (default for all other user types)
                    $this->view('layouts/header', $data);
                    $this->view('pages/client', $data);
                    $this->view('layouts/footer');
                }
            } else {
                // Default to client if account type not set
                $this->view('layouts/header', $data);
                $this->view('pages/client', $data);
                $this->view('layouts/footer');
            }
        } else {
            // User is not logged in - load the landing page
            $this->view('layouts/header', $data);
            $this->view('pages/landing', $data);
            $this->view('layouts/footer');
        }
    }

    // Specific method to handle freelancer page requests
    public function freelancer() {
        // Verify user is logged in and has freelancer account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'freelancer') {
            redirect('users/login');
        }
        
        $data = [
            'title' => 'Freelancer Dashboard',
            'description' => 'Manage your freelancer account'
        ];

        $this->view('layouts/header', $data);
        $this->view('pages/freelancer', $data);
        $this->view('layouts/footer');
    }

    // Specific method to handle client page requests
    public function client() {
        // Verify user is logged in and has client account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'client') {
            redirect('users/login');
        }
        
        $data = [
            'title' => 'Client Dashboard',
            'description' => 'Manage your client account'
        ];
        
        $this->view('layouts/header', $data);
        $this->view('pages/client', $data);
        $this->view('layouts/footer');
    }

    // Specific method to handle admin page requests
    public function admin() {
        // Verify user is logged in and has admin account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            redirect('users/login');
        }
        
        $data = [
            'title' => 'Admin Dashboard',
            'description' => 'Manage your admin account'
        ];
        
        // Load the dashboard view directly instead of the page/admin view
        $this->view('dashboard/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About lenSi',
            'description' => 'Learn more about our platform'
        ];

        $this->view('layouts/header', $data);
        $this->view('pages/about', $data);
        $this->view('layouts/footer');
    }
}
