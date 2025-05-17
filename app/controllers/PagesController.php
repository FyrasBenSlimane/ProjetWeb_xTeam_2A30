<?php
class PagesController extends Controller {
    public function __construct() {
        // Initialize any needed properties
    }

    public function index($page = null) {
        // Load the AuthMiddleware class
        require_once APP_PATH . '/helpers/AuthMiddleware.php';
        
        // If user is logged in, redirect to appropriate dashboard
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_account_type'])) {
            AuthMiddleware::redirectToDashboard();
            return;
        }
        
        // For now, only handle admin routes
        // Check if requesting a dashboard page
        if ($page && in_array($page, ['user_management', 'blog_management', 'support_tickets', 'settings', 'resources_management', 'events_management'])) {
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
                
                case 'resources_management':
                    $resources = $dashboardModel->getResources();
                    $data = [
                        'title' => 'Resources Management',
                        'description' => 'Manage YouTube resources',
                        'resources' => $resources
                    ];
                    
                    // View will handle including the dashboard layout
                    $this->view('dashboard/resources_management', $data);
                    break;
                
                case 'events_management':
                    $events = $dashboardModel->getEvents();
                    $data = [
                        'title' => 'Events Management',
                        'description' => 'Manage events',
                        'events' => $events
                    ];
                    
                    // View will handle including the dashboard layout
                    $this->view('dashboard/events_management', $data);
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
        
        // For admin users, load the admin dashboard without navbar
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'admin') {
            // Load the admin dashboard
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
            
            // Load dashboard directly (using its own layout without navbar)
            $this->view('dashboard/index', $dashboardData);
        } else {
            // For non-logged in users - use navbar layout
            // Using direct include for head content
            $this->view('layouts/header', $data);
            // Then load the landing page
            $this->view('pages/landing', $data);
            // And finally load the footer
            $this->view('layouts/footer');
        }
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
        
        // Load the dashboard view directly (uses its own layout without navbar)
        $this->view('dashboard/index', $data);
    }
    
    // Freelance dashboard page
    public function freelance() {
        // Verify user is logged in and has freelancer account type
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'freelancer') {
            redirect('users/login');
            return;
        }
        
        // Redirect to the dedicated FreelanceController
        redirect('freelance');
    }
    
    // Support page handling
    public function support($section = 'index') {
        // Verify user is logged in as either freelancer or client
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || 
           !in_array($_SESSION['user_account_type'], ['freelancer', 'client'])) {
            redirect('users/login');
            return;
        }
        
        // Create a model instance for support if needed
        // $supportModel = $this->model('SupportModel');
        
        // Handle different support sections/pages
        switch ($section) {
            case 'tickets':
                $data = [
                    'title' => 'My Support Tickets',
                    'description' => 'View and manage your support tickets'
                    // Add more data as needed from the model
                ];
                break;
                
            case 'new-ticket':
                $data = [
                    'title' => 'Create New Ticket',
                    'description' => 'Submit a new support request'
                ];
                break;
                
            case 'faq':
                $data = [
                    'title' => 'Frequently Asked Questions',
                    'description' => 'Find answers to common questions'
                ];
                break;
                
            case 'contact':
                $data = [
                    'title' => 'Contact Support',
                    'description' => 'Get in touch with our support team'
                ];
                break;
                
            default: // index
                $data = [
                    'title' => 'Support Center',
                    'description' => 'Get help and support for your account',
                    'account_type' => $_SESSION['user_account_type']
                ];
                break;
        }
        
        // Load the appropriate view with header and footer
        $this->view('layouts/header', $data);
        $this->view('support/'.$section, $data);
        $this->view('layouts/footer');
    }
    
    // Community Section
    public function community($page = 'index') {
        // Check for logged in user
        $is_logged_in = isLoggedIn();
        
        // Process the community pages
        switch ($page) {
            case 'index':
                // Community home page
                $data = [
                    'title' => 'Community Hub',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/index', $data);
                $this->view('layouts/footer');
                break;
                
            case 'discussions':
                // Community discussions page
                $data = [
                    'title' => 'Community Discussions',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/discussions', $data);
                $this->view('layouts/footer');
                break;
                
            case 'forum':
                // Community forum
                // Initialize the Forum model to get required data
                $forumModel = $this->model('Forum');
                
                // Get categories
                $categories = $forumModel->getCategories();
                
                // Get featured threads
                $featuredThreads = $forumModel->getFeaturedThreads(2);
                
                // Get recent threads, excluding featured ones
                $featuredIds = array_map(function($thread) {
                    return $thread->id;
                }, $featuredThreads);
                
                $recentThreads = $forumModel->getRecentThreads(4, $featuredIds);
                
                $data = [
                    'title' => 'Community Forum',
                    'description' => 'Join discussions, ask questions, and share knowledge with other professionals.',
                    'is_logged_in' => $is_logged_in,
                    'categories' => $categories,
                    'featuredThreads' => $featuredThreads,
                    'recentThreads' => $recentThreads
                ];
                $this->view('layouts/header', $data);
                $this->view('community/forum', $data);
                $this->view('layouts/footer');
                break;
                
            case 'articles':
                // Articles
                $data = [
                    'title' => 'Articles',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/articles', $data);
                $this->view('layouts/footer');
                break;
                
            case 'help':
                // Help center
                $data = [
                    'title' => 'Help Center',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/help', $data);
                $this->view('layouts/footer');
                break;
                
            case 'events':
                    // Community events - Use the EventsController to handle this
                    // Create an instance of the EventsController
                    $eventsController = new EventsController();
                    
                    // Call the index method to display the events list
                    $eventsController->index();
                    break;
                    
            case 'projects':
                // Community projects - Use the ProjectsController to handle this
                $projectsController = new ProjectsController();
                
                // Call the index method to display the projects list
                $projectsController->index();
                break;
            
            case 'members':
                // Community members
                $data = [
                    'title' => 'Community Members',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/members', $data);
                $this->view('layouts/footer');
                break;
                
            case 'resources':
                // Community resources
                // Load resources from database
                $dashboardModel = $this->model('DashboardModel');
                
                // Check if a category filter is applied
                $category = isset($_GET['category']) ? $_GET['category'] : 'all';
                
                // Get resources based on category filter
                if ($category !== 'all') {
                    $resources = $dashboardModel->getResourcesByCategory($category);
                } else {
                    $resources = $dashboardModel->getResources('active');
                }
                
                $data = [
                    'title' => 'Educational Resources',
                    'is_logged_in' => $is_logged_in,
                    'resources' => $resources,
                    'current_category' => $category
                ];
                $this->view('layouts/header', $data);
                $this->view('community/resources', $data);
                $this->view('layouts/footer');
                break;
                
            case 'content':
                // Community content
                $data = [
                    'title' => 'Community Content',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/content', $data);
                $this->view('layouts/footer');
                break;
                
            case 'people':
                // Community people
                $data = [
                    'title' => 'People',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/people', $data);
                $this->view('layouts/footer');
                break;
                
            case 'albums':
                // Community albums
                $data = [
                    'title' => 'Photo Albums',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/albums', $data);
                $this->view('layouts/footer');
                break;
                
            case 'messages':
                // Community messages
                $data = [
                    'title' => 'Messages',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/messages', $data);
                $this->view('layouts/footer');
                break;
                
            default:
                // Default to community index
                $data = [
                    'title' => 'Community Hub',
                    'is_logged_in' => $is_logged_in
                ];
                $this->view('layouts/header', $data);
                $this->view('community/index', $data);
                $this->view('layouts/footer');
                break;
        }
    }

    // Handle resources dashboard page
    public function resources() {
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

}
