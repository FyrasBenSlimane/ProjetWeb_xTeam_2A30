<?php
class UserController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/auth?action=login');
        }
    }

    /**
     * Main user area display
     */
    public function index()
    {
        // Redirect based on account type
        if ($_SESSION['user_account_type'] == 'freelancer') {
            redirect('pages/freelancer');
        } else {
            // Default view for clients
            $this->view('dashboard/index');
        }
    }

    /**
     * Profile page for freelancers
     */
    public function profile()
    {
        // Data to pass to view
        $data = [
            'title' => 'Your Profile',
            'description' => 'View and edit your freelancer profile'
        ];

        $this->view('users/apps/profile', $data);
    }

    /**
     * Stats and trends page for freelancers
     */
    public function stats()
    {
        // Data to pass to view
        $data = [
            'title' => 'Stats and Trends',
            'description' => 'Analyze your freelance performance over time'
        ];

        $this->view('users/apps/stats', $data);
    }

    /**
     * Membership plan page for freelancers
     */
    public function membership()
    {
        // Data to pass to view
        $data = [
            'title' => 'Membership Plan',
            'description' => 'View and manage your freelancer membership'
        ];

        $this->view('users/apps/membership', $data);
    }

    /**
     * Account settings page for all users
     */
    public function settings()
    {
        // Data to pass to view
        $data = [
            'title' => 'Account Settings',
            'description' => 'Manage your account preferences and security options'
        ];

        $this->view('dashboard/settings');
    }

    /**
     * App marketplace page - display available apps and offers
     * Previously in AppsController
     */
    public function apps()
    {
        // Data to pass to view
        $data = [
            'title' => 'Apps and Offers',
            'description' => 'Enhance your freelancing experience with apps and special offers',
            'featured_apps' => [
                [
                    'name' => 'Time Tracker',
                    'description' => 'Track your working hours automatically',
                    'icon' => 'fas fa-clock',
                    'status' => 'free',
                ],
                [
                    'name' => 'Proposal Helper',
                    'description' => 'Create winning job proposals with AI assistance',
                    'icon' => 'fas fa-lightbulb',
                    'status' => 'premium',
                ],
                [
                    'name' => 'Invoice Generator',
                    'description' => 'Create and manage professional invoices',
                    'icon' => 'fas fa-file-invoice-dollar',
                    'status' => 'free',
                ]
            ],
            'special_offers' => [
                [
                    'title' => '50% Off Membership',
                    'description' => 'Limited time offer - 50% off your first month of Premium',
                    'expires' => '2023-05-15'
                ],
                [
                    'title' => 'Free Connects Bundle',
                    'description' => 'Get 10 free connects when you complete your first job',
                    'expires' => 'Ongoing'
                ]
            ]
        ];

        $this->view('users/apps/app_marketplace', $data);
    }

    /**
     * Connects page - display available connects and history
     * Previously in ConnectsController 
     */
    public function connects()
    {
        // Check if user is a freelancer
        if ($_SESSION['user_account_type'] != 'freelancer') {
            redirect('');
        }

        // Data to pass to view
        $data = [
            'title' => 'Your Connects',
            'description' => 'Manage your connects for applying to jobs',
            'connects_available' => 50, // Sample data
            'connects_spent' => 20 // Sample data
        ];

        $this->view('users/apps/connects', $data);
    }

    /**
     * Route handler for apps/index - Redirect to the new apps method
     * This ensures backward compatibility with existing URLs
     */
    public function appsIndex()
    {
        $this->apps();
    }

    /**
     * Route handler for connects/index - Redirect to the new connects method
     * This ensures backward compatibility with existing URLs
     */
    public function connectsIndex()
    {
        $this->connects();
    }
}
