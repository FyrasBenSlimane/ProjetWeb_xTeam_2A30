<?php
class PagesController extends Controller {
    public function __construct() {
        // Initialize any needed properties
    }

    public function index($page = null) {
        // Check if requesting a dashboard page
        if ($page && in_array($page, ['user_management', 'blog_management', 'support_tickets', 'settings'])) {
            // Make sure user is admin before loading dashboard pages
            if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
                redirect('users/login');
            }
            
            // Load dashboard data
            $dashboardModel = $this->model('DashboardModel');
            
            // Prepare data based on dashboard page requested
            switch ($page) {
                case 'user_management':
                    $users = $dashboardModel->getUsersData();
                    $data = [
                        'title' => 'User Management',
                        'description' => 'Manage users',
                        'users' => $users
                    ];
                    $this->view('dashboard/user_management', $data);
                    break;
                    
                case 'blog_management':
                    $posts = $dashboardModel->getBlogPostsData();
                    $data = [
                        'title' => 'Blog Management',
                        'description' => 'Manage blog posts',
                        'posts' => $posts
                    ];
                    $this->view('dashboard/blog_management', $data);
                    break;
                    
                case 'support_tickets':
                    $tickets = $dashboardModel->getSupportTicketsData();
                    $data = [
                        'title' => 'Support Tickets',
                        'description' => 'Manage support tickets',
                        'tickets' => $tickets
                    ];
                    $this->view('dashboard/support_tickets', $data);
                    break;
                    
                case 'settings':
                    $data = [
                        'title' => 'Dashboard Settings',
                        'description' => 'Manage dashboard settings'
                    ];
                    $this->view('dashboard/settings', $data);
                    break;
            }
            return;
        }
        
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
                    // Load the admin dashboard - with proper initialization of DashboardModel
                    $dashboardModel = $this->model('DashboardModel');
                    
                    // Get analytics data for the dashboard
                    $analyticsData = $dashboardModel->getAnalyticsData();
                    
                    // Get recent users for the dashboard table
                    $users = $dashboardModel->getUsersData();
                    
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
                    
                    // Prepare data for the view
                    $dashboardData = [
                        'title' => 'Admin Dashboard',
                        'description' => 'Site Guardian Admin Dashboard',
                        'analyticsData' => $analyticsData,
                        'users' => $users,
                        'visitChartData' => json_encode($visitChartData),
                        'userDistributionData' => json_encode($userDistributionData)
                    ];
                    
                    $this->view('dashboard/index', $dashboardData);
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

}
