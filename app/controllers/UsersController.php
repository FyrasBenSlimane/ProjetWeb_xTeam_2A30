<?php
class UsersController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    /**
     * Consolidated authentication page handling
     * Serves login, registration, and password reset via a single view
     */
    public function auth() {
        // Determine which auth action is being performed
        $action = isset($_GET['action']) ? $_GET['action'] : 'login';
        
        // Initialize default data
        $data = [
            'title' => 'Authentication',
            'description' => 'Login, register, or recover your password',
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
        ];
        
        // Check if this is a reset request (user clicked "Not you?")
        if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
            // Clear the verified email session
            unset($_SESSION['login_email_verified']);
            unset($_SESSION['login_email']);
        }
        
        $this->view('pages/auth', $data);
    }

    /**
     * Email verification for the two-step login process
     * 
     * @return void
     */
    public function verifyEmail() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email address';
            } else {
                // Check email exists
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'No account found with that email address';
                }
            }
            
            // Make sure errors are empty
            if (empty($data['email_err'])) {
                // Email exists and is valid
                // Store email in session for step 2
                $_SESSION['login_email_verified'] = true;
                $_SESSION['login_email'] = $data['email'];
                
                // Redirect to login page to show password form
                redirect('users/auth?action=login');
            } else {
                // Load view with errors
                $this->view('pages/auth', $data);
            }
        } else {
            // Redirect to login page if accessed directly
            redirect('users/auth?action=login');
        }
    }

    /**
     * Login User 
     *
     * @return void
     */
    public function login() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'password_err' => ''
            ];
            
            // Ensure we have a verified email
            if (!isset($_SESSION['login_email_verified']) || $_SESSION['login_email'] != $data['email']) {
                // Redirect to login page for email verification
                redirect('users/auth?action=login');
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter your password';
            }
            
            // Make sure errors are empty
            if (empty($data['password_err'])) {
                // Check and set logged in user
                $user = $this->userModel->login($data['email'], $data['password']);
                
                if ($user) {
                    // Check if user account is inactive
                    if (isset($user->status) && $user->status === 'Inactive') {
                        // Store user email in session to display on inactive account page
                        $_SESSION['inactive_user_email'] = $user->email;
                        
                        // Clean up login session variables
                        unset($_SESSION['login_email_verified']);
                        unset($_SESSION['login_email']);
                        
                        // Redirect to inactive account page and stop execution
                        redirect('users/inactive');
                        exit;
                    }
                    
                    // Create session variables
                    $this->createUserSession($user);
                    
                    // Clean up login session variables
                    unset($_SESSION['login_email_verified']);
                    unset($_SESSION['login_email']);
                    
                    // Check if remember me checkbox was checked
                    if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                        // Set remember me cookie (30 days)
                        $token = bin2hex(random_bytes(32));
                        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                        // Store token in database
                        $this->userModel->storeRememberToken($user->id, $hashedToken);
                        // Set cookie with user id and token
                        setcookie('remember_user', $user->id . ':' . $token, time() + 60*60*24*30, '/');
                    }
                    
                    // Check if we have a redirect URL stored in session
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        redirect($redirect);
                    } else {
                        // No stored redirect, use role-based redirect
                        require_once APP_PATH . '/helpers/AuthMiddleware.php';
                        AuthMiddleware::redirectToDashboard();
                    }
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('pages/auth', $data);
                }
            } else {
                // Load view with errors
                $this->view('pages/auth', $data);
            }
        } else {
            // Init data if not POST
            $data = [
                'email' => '',
                'password' => '',
                'password_err' => ''
            ];
            
            // Load view
            $this->view('pages/auth', $data);
        }
    }

    /**
     * Register user account
     * 
     * @return void
     */
    public function register() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'user_type' => trim($_POST['user_type']),
                'country' => trim($_POST['country']),
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'terms_err' => ''
            ];
            
            // Check for terms agreement
            if (!isset($_POST['terms'])) {
                $data['terms_err'] = 'You must agree to the terms of service';
            }
            
            // Validate name
            if (empty($data['first_name'])) {
                $data['first_name_err'] = 'Please enter your first name';
            }
            
            if (empty($data['last_name'])) {
                $data['last_name_err'] = 'Please enter your last name';
            }
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check if email exists
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            
            // Make sure errors are empty
            if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['terms_err'])) {
                // SUCCESS - Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Combine first and last name
                $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
                
                // Handle account type
                if ($data['user_type'] == 'freelancer') {
                    $data['account_type'] = 'freelancer';
                } elseif ($data['user_type'] == 'client') {
                    $data['account_type'] = 'client';
                } else {
                    $data['account_type'] = 'client'; // Default
                }
                
                // Set terms accepted
                $data['terms_accepted'] = true;
                
                // Initialize JSON fields with empty arrays
                $data['skills'] = json_encode([]);
                $data['education'] = json_encode([]);
                $data['work_history'] = json_encode([]);
                $data['portfolio'] = json_encode([]);
                $data['languages'] = json_encode([array("name" => "English", "level" => "Native")]);
                
                // Set default values for user profile fields
                $data['visibility'] = 'public';
                $data['project_preference'] = 'both';
                $data['professional_title'] = '';
                
                // Register User
                if ($this->userModel->register($data)) {
                    // Send welcome email
                    try {
                        // Require the EmailHelper class
                        require_once APP_ROOT . '/helpers/EmailHelper.php';
                        
                        // Create a new instance of EmailHelper with MailHog for local development
                        // Use MailHog in local environment (XAMPP)
                        $useMailHog = (strpos(URL_ROOT, 'localhost') !== false);
                        $emailHelper = new EmailHelper($useMailHog);
                        
                        // Create verification link (if you implement email verification)
                        // $verificationToken = bin2hex(random_bytes(32));
                        // $this->userModel->storeVerificationToken($data['email'], $verificationToken);
                        // $verificationLink = URL_ROOT . '/users/verify/' . $verificationToken;
                        
                        // Send welcome email without verification for now
                        $emailSent = $emailHelper->sendWelcomeEmail($data['name'], $data['email']);
                        
                        if (!$emailSent) {
                            // Log the error, but don't block registration
                            error_log('Failed to send welcome email to ' . $data['email']);
                        }
                    } catch (Exception $e) {
                        // Log any exceptions from email sending, but continue with registration
                        error_log('Error sending welcome email: ' . $e->getMessage());
                    }
                    
                    flash('register_success', 'You are registered and can now log in. Please check your email for a welcome message.');
                    redirect('users/auth?action=login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('pages/auth', $data);
            }
        } else {
            // Init data
            $data = [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => '',
                'user_type' => '',
                'country' => '',
                'first_name_err' => '',
                'last_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'terms_err' => ''
            ];
            
            // Load view
            $this->view('pages/auth', $data);
        }
    }

    /**
     * Password reset request
     * 
     * @return void
     */
    public function password() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email address';
            } else {
                // Check email exists
                if (!$this->userModel->findUserByEmail($data['email'])) {
                    // In a real app, we would not reveal if an email exists
                    // For security purposes, show a generic success message even if email not found
                    flash('reset_message', 'If your email exists in our system, you will receive a password reset link');
                    redirect('users/auth?action=password');
                }
            }
            
            // Make sure errors are empty
            if (empty($data['email_err'])) {
                // Generate and store reset token (this would be implemented in User model)
                $token = bin2hex(random_bytes(32));
                $this->userModel->storeResetToken($data['email'], $token);
                
                // In a real application, we would send an email with the reset link
                // For this demo, we'll just show a success message
                
                flash('reset_message', 'Password reset link has been sent to your email');
                redirect('users/auth?action=password');
            } else {
                // Load view with errors
                $this->view('pages/auth', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'email_err' => ''
            ];
            
            // Load view
            $this->view('pages/auth', $data);
        }
    }

    /**
     * Create user session
     * 
     * @param object $user User object from database
     * @return void
     */
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_account_type'] = $user->account_type;
        
        // Initialize CurrentUser data
        CurrentUser::initialize();
        
        // Send login notification email
        try {
            // Require the EmailHelper class
            require_once APP_ROOT . '/helpers/EmailHelper.php';
            
            // Create a new instance of EmailHelper with MailHog for local development
            // Use MailHog in local environment (XAMPP)
            $useMailHog = (strpos(URL_ROOT, 'localhost') !== false);
            // Use custom mail.php config
            $useCustomMailConfig = true;
            $emailHelper = new EmailHelper($useMailHog, $useCustomMailConfig);
            
            // Format login time
            $loginTime = date('Y-m-d H:i:s');
            
            // Get IP address
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            
            // Send login notification
            $emailSent = $emailHelper->sendLoginNotification($user->name, $user->email, $loginTime, $ipAddress);
            
            if (!$emailSent) {
                // Log the error, but don't block login
                error_log('Failed to send login notification email to ' . $user->email);
            }
        } catch (Exception $e) {
            // Log any exceptions from email sending, but continue with login
            error_log('Error sending login notification email: ' . $e->getMessage());
        }
        
        // Check if user is an admin and redirect to dashboard
        if ($user->account_type == 'admin') {
            redirect('dashboard');
        } else {
            // Redirect to appropriate page based on account type
            redirect('pages/' . strtolower($user->account_type));
        }
    }

    /**
     * Logout user and destroy session
     * 
     * @return void
     */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_account_type']);
        
        // Clear remember me cookie if it exists
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 3600, '/');
        }
        
        session_destroy();
        redirect('users/auth?action=login');
    }

    /**
     * Get and display user profile 
     *
     * @return void
     */
    public function profile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/auth');
        }

        // Get user profile data using CurrentUser helper
        $profile = CurrentUser::getData();
        
        if (!$profile) {
            // Fallback to direct database query if CurrentUser data is not available
            $profile = $this->userModel->getUserProfile($_SESSION['user_id']);
        }
        
        // Get portfolio items if any
        $portfolio = $this->userModel->getUserPortfolio($_SESSION['user_id']);
        
        // Get work history if any
        $workHistory = $this->userModel->getUserWorkHistory($_SESSION['user_id']);
        
        // Get education if any
        $education = $this->userModel->getUserEducation($_SESSION['user_id']);
        
        // Calculate profile completion
        $profileCompletion = $this->userModel->calculateProfileCompletion($_SESSION['user_id']);
        
        // Prepare data for view
        $data = [
            'title' => 'My Profile',
            'description' => 'View and edit your profile',
            'user' => $profile,
            'portfolio_items' => $portfolio,
            'work_history' => $workHistory,
            'education' => $education,
            'profile_completion' => $profile->profile_completeness ?? $profileCompletion
        ];

        $this->view('pages/profile_view', $data);
    }

    /**
     * Show profile settings page
     *
     * @return void
     */
    public function settings() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/auth');
        }

        // Get user profile data using CurrentUser helper
        $profile = CurrentUser::getData();
        
        if (!$profile) {
            // Fallback to direct database query if CurrentUser data is not available
            $profile = $this->userModel->getUserProfile($_SESSION['user_id']);
        }
        
        // Initialize data
        $data = [
            'title' => 'Profile Settings',
            'description' => 'Update your profile settings',
            'user' => $profile,
            'name' => $profile->name ?? $_SESSION['user_name'],
            'email' => $profile->email ?? $_SESSION['user_email'],
            'account_type' => $profile->account_type ?? $_SESSION['user_account_type'],
            'bio' => $profile->bio ?? '',
            'location' => $profile->location ?? '',
            'professional_title' => $profile->professional_title ?? '',
            'experience_level' => $profile->experience_level ?? 'entry',
            'hourly_rate' => $profile->hourly_rate ?? 0,
            'hours_per_week' => $profile->hours_per_week ?? '',
            'profile_visibility' => $profile->profile_visibility ?? 'public',
            'categories' => $profile->categories ?? '',
            'name_err' => '',
            'email_err' => '',
            'bio_err' => ''
        ];

        $this->view('pages/profile_settings', $data);
    }

    /**
     * Update profile settings
     *
     * @return void
     */
    public function updateProfile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/auth');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get user profile data for comparing
            $existingProfile = $this->userModel->getUserProfile($_SESSION['user_id']);
            
            // Init data
            $data = [
                'id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email'] ?? $_SESSION['user_email'] ?? ''),
                'account_type' => trim($_POST['account_type'] ?? $_SESSION['user_account_type'] ?? 'freelancer'),
                'bio' => trim($_POST['bio'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'professional_title' => trim($_POST['professional_title'] ?? ''),
                'experience_level' => trim($_POST['experience_level'] ?? 'entry'),
                'hourly_rate' => floatval($_POST['hourly_rate'] ?? 0),
                'hours_per_week' => trim($_POST['hours_per_week'] ?? ''),
                'profile_visibility' => trim($_POST['profile_visibility'] ?? 'public'),
                'categories' => trim($_POST['categories'] ?? ''),
                'name_err' => '',
                'email_err' => '',
                'bio_err' => ''
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check if email exists and it's not the current user's email
                if ($this->userModel->findUserByEmail($data['email']) && $data['email'] != $existingProfile->email) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            
            // Check for errors before updating
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['bio_err'])) {
                // Update user profile
                if ($this->userModel->updateProfileSettings($data)) {
                    // Update session data with new values
                    $_SESSION['user_name'] = $data['name'];
                    $_SESSION['user_email'] = $data['email'];
                    $_SESSION['user_account_type'] = $data['account_type'];
                    
                    // Refresh CurrentUser data
                    CurrentUser::refresh();
                    
                    // Set success flash message
                    flash('profile_message', 'Your profile has been updated successfully');
                    
                    // Redirect to appropriate page based on account type
                    redirect('pages/' . strtolower($data['account_type']));
                } else {
                    flash('profile_message', 'Something went wrong while updating your profile', 'alert alert-danger');
                    $this->view('pages/profile_settings', $data);
                }
            } else {
                // Load view with errors
                $this->view('pages/profile_settings', $data);
            }
        } else {
            // If not POST, redirect to settings page
            redirect('users/settings');
        }
    }
    
    /**
     * Ajax update for profile sections
     * 
     * @return void
     */
    public function ajaxUpdateProfile() {
        // Check if user is logged in and request is AJAX
        if (!isLoggedIn() || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        // Get JSON data from request
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        // Sanitize and prepare the data
        $userId = $_SESSION['user_id'];
        $updateData = [
            'user_id' => $userId
        ];

        // Process different field types
        if (isset($data['field']) && isset($data['value'])) {
            $field = $data['field'];
            $value = $data['value'];
            
            // Map fields to their appropriate user_profiles database columns
            switch ($field) {
                case 'professional_title':
                    $updateData['professional_title'] = $value;
                    break;
                case 'bio':
                    $updateData['bio'] = $value;
                    break;
                case 'location':
                    $updateData['location'] = $value;
                    break;
                case 'hourly_rate':
                    $updateData['hourly_rate'] = (float)$value;
                    break;
                case 'hours_per_week':
                    $updateData['hours_per_week'] = $value;
                    break;
                case 'experience_level':
                    if (in_array($value, ['entry', 'intermediate', 'expert'])) {
                        $updateData['experience_level'] = $value;
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Invalid experience level']);
                        return;
                    }
                    break;
                case 'profile_visibility':
                    if (in_array($value, ['public', 'private', 'connections'])) {
                        $updateData['profile_visibility'] = $value;
                    } else {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'Invalid visibility option']);
                        return;
                    }
                    break;
                case 'categories':
                    $updateData['categories'] = $value;
                    break;
                case 'languages':
                    $updateData['languages'] = $value;
                    break;
                case 'skills':
                    $updateData['skills'] = $value;
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Unknown field: ' . $field]);
                    return;
            }
            
            // Update the user profile
            if ($this->userModel->updateUserProfile($updateData)) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
                return;
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
                return;
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing field or value']);
            return;
        }
    }

    /**
     * Add portfolio item
     *
     * @return void
     */
    public function addPortfolio() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/auth');
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle file upload
            if (isset($_FILES['portfolio_image']) && $_FILES['portfolio_image']['error'] === UPLOAD_ERR_OK) {
                $fileUpload = new FileUpload();
                $imageUrl = $fileUpload->uploadImage($_FILES['portfolio_image'], 'portfolio');
                
                if (!$imageUrl) {
                    flash('portfolio_message', 'Error uploading image', 'alert alert-danger');
                    redirect('users/profile');
                }
            } else {
                // No image uploaded or error
                $imageUrl = '';
            }
            
            // Prepare data
            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'image_url' => $imageUrl,
                'project_url' => trim($_POST['project_url'] ?? '')
            ];
            
            // Add portfolio item
            if ($this->userModel->addPortfolioItem($data)) {
                flash('portfolio_message', 'Portfolio item added successfully');
                redirect('users/profile');
            } else {
                flash('portfolio_message', 'Something went wrong while adding portfolio item', 'alert alert-danger');
                redirect('users/profile');
            }
        } else {
            // If not POST, redirect to profile
            redirect('users/profile');
        }
    }

    /**
     * Delete portfolio item
     *
     * @return void
     */
    public function deletePortfolio($id = null) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/auth');
        }

        // Check if id is provided
        if (!$id) {
            flash('portfolio_message', 'Invalid portfolio item', 'alert alert-danger');
            redirect('users/profile');
        }

        // Delete portfolio item
        if ($this->userModel->deletePortfolioItem($id, $_SESSION['user_id'])) {
            flash('portfolio_message', 'Portfolio item deleted successfully');
        } else {
            flash('portfolio_message', 'Something went wrong while deleting portfolio item', 'alert alert-danger');
        }
        
        redirect('users/profile');
    }

    /**
     * Redirects user to GitHub for OAuth authentication.
     * Opens in a popup window rather than redirecting the main page.
     *
     * @return void
     */
    public function githubAuth() {
        // Check if this is a callback request from GitHub
        if (isset($_GET['code'])) {
            // This is a callback, handle the authentication flow
            $this->githubCallback();
            return;
        }
        
        // Generate a random state value for security
        $_SESSION['github_oauth_state'] = bin2hex(random_bytes(16));
        
        // Determine whether this is for login or registration based on query parameter
        $auth_action = isset($_GET['auth_action']) ? $_GET['auth_action'] : 'login';
        $_SESSION['github_auth_action'] = $auth_action;
        
        $params = [
            'client_id' => GITHUB_CLIENT_ID,
            'redirect_uri' => URL_ROOT . '/users/githubAuth',
            'scope' => 'user:email read:user',
            'state' => $_SESSION['github_oauth_state']
        ];

        $authUrl = 'https://github.com/login/oauth/authorize?' . http_build_query($params);
        
        // For popup window, set a flag to indicate this is an OAuth popup
        $_SESSION['github_auth_popup'] = true;
        
        // Redirect to GitHub
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Handles the callback from GitHub after authentication.
     *
     * @return void
     */
    public function githubCallback() {
        // Verify state to prevent CSRF attacks
        if (!isset($_GET['state']) || !isset($_SESSION['github_oauth_state']) || $_GET['state'] !== $_SESSION['github_oauth_state']) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Invalid state. Authentication failed.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Invalid state. Authentication failed.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }
        unset($_SESSION['github_oauth_state']); // Clean up state

        // Check if GitHub returned an error
        if (isset($_GET['error'])) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'GitHub authentication failed: ' . htmlspecialchars($_GET['error_description']), null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'GitHub authentication failed: ' . htmlspecialchars($_GET['error_description']), 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        // Check if code is present
        if (!isset($_GET['code'])) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Authorization code not received from GitHub.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Authorization code not received from GitHub.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        $code = $_GET['code'];

        // --- Step 1: Exchange code for access token ---
        $tokenUrl = 'https://github.com/login/oauth/access_token';
        $tokenParams = [
            'client_id' => GITHUB_CLIENT_ID,
            'client_secret' => GITHUB_CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => GITHUB_CALLBACK_URL
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']); // Request JSON response
        curl_setopt($ch, CURLOPT_USERAGENT, SITE_NAME); // GitHub requires a User-Agent

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Failed to get access token from GitHub.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Failed to get access token from GitHub.', 'alert alert-danger');
                error_log('GitHub Token Error: HTTP ' . $httpCode . ' Response: ' . $response);
                redirect('users/auth?action=login');
                return;
            }
        }

        $tokenData = json_decode($response, true);

        if (!isset($tokenData['access_token'])) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Access token not found in GitHub response.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Access token not found in GitHub response.', 'alert alert-danger');
                error_log('GitHub Token Error: Access token missing. Response: ' . $response);
                redirect('users/auth?action=login');
                return;
            }
        }

        $accessToken = $tokenData['access_token'];

        // --- Step 2: Get user data from GitHub API ---
        $userApiUrl = 'https://api.github.com/user';
        $emailApiUrl = 'https://api.github.com/user/emails'; // To get primary email

        $contextOptions = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: token {$accessToken}\r\n" .
                            "User-Agent: " . SITE_NAME . "\r\n"
            ]
        ];
        $context = stream_context_create($contextOptions);

        // Get user profile data
        $userDataJson = @file_get_contents($userApiUrl, false, $context);
        if ($userDataJson === false) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Failed to fetch user data from GitHub.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Failed to fetch user data from GitHub.', 'alert alert-danger');
                error_log('GitHub API Error: Failed to fetch /user');
                redirect('users/auth?action=login');
                return;
            }
        }
        $githubUser = json_decode($userDataJson, true);

        // Get user emails
        $userEmailsJson = @file_get_contents($emailApiUrl, false, $context);
        if ($userEmailsJson === false) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Failed to fetch user emails from GitHub.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Failed to fetch user emails from GitHub.', 'alert alert-danger');
                error_log('GitHub API Error: Failed to fetch /user/emails');
                redirect('users/auth?action=login');
                return;
            }
        }
        $githubEmails = json_decode($userEmailsJson, true);

        // Find primary email
        $primaryEmail = null;
        foreach ($githubEmails as $email) {
            if ($email['primary'] && $email['verified']) {
                $primaryEmail = $email['email'];
                break;
            }
        }
        // Fallback if no primary verified email found (less ideal)
        if (!$primaryEmail && !empty($githubEmails) && $githubEmails[0]['verified']) {
             $primaryEmail = $githubEmails[0]['email'];
        }
        // Use public email from profile if no verified email found (least ideal)
        if (!$primaryEmail) {
            $primaryEmail = $githubUser['email']; 
        }

        if (empty($primaryEmail)) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Could not retrieve a verified email address from GitHub.', null, null, 'github-auth');
                return;
            } else {
                flash('login_error', 'Could not retrieve a verified email address from GitHub.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        // --- Step 3: Process GitHub Authentication ---
        $authResult = $this->processGitHubAuthentication($githubUser, $primaryEmail);

        if ($authResult['success']) {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(true, $authResult['message'], $authResult['redirect'], $authResult['user'] ?? null, 'github-auth');
                return;
            } else {
                redirect($authResult['redirect']);
            }
        } else {
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close with error
                $this->handlePopupResponse(false, $authResult['message'], null, null, 'github-auth');
                return;
            } else {
                flash('login_error', $authResult['message'], 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }
    }
    
    /**
     * GitHub Authentication Service
     * This provides a centralized handling of GitHub authentication for both login and registration
     * 
     * @return array Authentication result data
     */
    private function processGitHubAuthentication($githubUser, $primaryEmail) {
        // Get the auth action (login or register) from session
        $auth_action = $_SESSION['github_auth_action'] ?? 'login';
        
        // Store primary email in a session variable so it can be used in handlePopupResponse
        $_SESSION['temp_github_email'] = $primaryEmail;
        
        // Check if user exists by GitHub ID first
        $user = $this->userModel->findUserByGithubId($githubUser['id']);
        
        if ($user) {
            // User exists (linked via GitHub ID), log them in regardless of auth_action
            // Only create a session if NOT in a popup window
            if (!isset($_SESSION['github_auth_popup'])) {
                $this->createUserSession($user);
            }
            
            return [
                'success' => true,
                'message' => 'Authentication successful',
                'redirect' => URL_ROOT . '/pages/index?authenticated=true',
                'existing_user' => true,
                'user' => $user
            ];
        } 
        
        // User not found by GitHub ID, check by email
        $user = $this->userModel->findUserByEmail($primaryEmail);
        
        if ($user) {
            // User exists with this email, but GitHub ID not linked yet
            if ($auth_action == 'login' || $auth_action == 'register') {
                // For both login and register actions, link the GitHub ID
                $this->userModel->linkGithubId($user->id, $githubUser['id']);
                
                // We don't need to use the result of linkGithubId, we already have the user object
                // Only create a session if NOT in a popup window
                if (!isset($_SESSION['github_auth_popup'])) {
                    $this->createUserSession($user);
                }
                
                return [
                    'success' => true,
                    'message' => 'An account with this email already exists. You have been logged in.',
                    'redirect' => URL_ROOT . '/pages/index?authenticated=true',
                    'existing_user' => true,
                    'user' => $user,
                    'email' => $primaryEmail
                ];
            }
        } else if ($auth_action == 'register') {
            // User does not exist, proceed with registration if auth_action is register
            // Extract name components from GitHub data
            $nameParts = explode(' ', $githubUser['name'] ?? '', 2);
            $firstName = $nameParts[0] ?? 'GitHub User';
            $lastName = $nameParts[1] ?? '';

            // Prepare user data for registration
            $userData = [
                'github_id' => $githubUser['id'],
                'email' => $primaryEmail,
                'name' => $githubUser['name'] ?? $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'account_type' => 'client', // Default
                'country' => '', // Missing - Need to prompt user
                'profile_image' => $githubUser['avatar_url'] ?? null
            ];

            // Check for required fields missing from GitHub
            $missingFields = [];
            if (empty($userData['country'])) { $missingFields[] = 'country'; }
            
            if (!empty($missingFields)) {
                // Store partial data and redirect to completion form
                $_SESSION['github_incomplete_user'] = $userData;
                $_SESSION['github_missing_fields'] = $missingFields;
                
                return [
                    'success' => true,
                    'message' => 'Please complete your profile',
                    'redirect' => URL_ROOT . '/users/auth?action=github_complete',
                    'existing_user' => false,
                    'incomplete_registration' => true
                ];
            } else {
                // Debug log to verify user data before registration
                error_log('GitHub registration data before saving: ' . print_r($userData, true));

                // All required data is now present, proceed with registration
                $newUser = $this->userModel->registerWithGithub($userData);

                if ($newUser) {
                    // Registration successful, clean up session and log in
                    unset($_SESSION['github_incomplete_user']);
                    unset($_SESSION['github_missing_fields']);
                    unset($_SESSION['github_complete_errors']);
                    unset($_SESSION['selected_user_type']);
                    
                    // Create a proper user session
                    $this->createUserSession($newUser);
                    
                    // Redirect to dashboard or profile setup
                    flash('login_message', 'Your GitHub account has been registered successfully!', 'alert alert-success');
                    redirect('pages/' . strtolower($newUser->account_type)); 
                } else {
                    // Registration failed - add detailed error logging
                    error_log('GitHub Registration Error: Failed to complete registration in processGithubCompletion');
                    
                    flash('register_error', 'Failed to complete registration. Please try again or contact support.', 'alert alert-danger');
                    // Optionally clean up session data here too
                    unset($_SESSION['github_incomplete_user']);
                    unset($_SESSION['github_missing_fields']);
                    redirect('users/auth?action=register');
                }
            }
        } else {
            // Login action but user doesn't exist
            return [
                'success' => false,
                'message' => 'No account found with this GitHub account. Please register first.',
                'existing_user' => false
            ];
        }
    }

    /**
     * Helper method to handle popup responses for OAuth authentication
     * This method will display a page that sends a message to the parent window and closes itself
     * 
     * @param bool $success Whether authentication was successful
     * @param string|null $message Error message if authentication failed
     * @param string|null $redirect URL to redirect to after successful authentication
     * @param object|null $user User object if authentication was successful
     * @param string $source Source identifier for the authentication method ('github-auth' or 'google-auth')
     * @return void
     */
    private function handlePopupResponse($success, $message = null, $redirect = null, $user = null, $source = 'github-auth') {
        // Clean up session
        unset($_SESSION['github_auth_popup']);
        unset($_SESSION['google_auth_popup']);
        
        // Get the user email if a user object is provided
        $email = null;
        if ($user && isset($user->email)) {
            $email = $user->email;
        } elseif (isset($_SESSION['temp_github_email'])) {
            $email = $_SESSION['temp_github_email'];
            unset($_SESSION['temp_github_email']); // Clean up the session
        } elseif (isset($_SESSION['temp_google_email'])) {
            $email = $_SESSION['temp_google_email'];
            unset($_SESSION['temp_google_email']); // Clean up the session
        } elseif (is_array($user) && isset($user['email'])) {
            $email = $user['email'];
        }
        
        // Add debug logging to troubleshoot
        error_log("Auth Popup Response for $source - Success: " . ($success ? 'yes' : 'no') . ", Email: $email, Redirect: $redirect");
        
        // Output HTML that communicates with parent window and closes itself
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Authentication</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding-top: 50px;
                    background-color: #f8f9fa;
                }
                .message {
                    padding: 20px;
                    margin: 0 auto;
                    max-width: 500px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
                .success { color: #28a745; }
                .error { color: #dc3545; }
                button {
                    margin-top: 20px;
                    padding: 8px 16px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                button:hover {
                    background-color: #0069d9;
                }
                .debug-info {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #6c757d;
                    text-align: left;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 4px;
                    max-width: 500px;
                    margin-left: auto;
                    margin-right: auto;
                    word-break: break-all;
                }
            </style>
        </head>
        <body>
            <div class="message">
                <h3 class="' . ($success ? 'success' : 'error') . '">
                    ' . ($success ? 'Authentication Successful!' : 'Authentication Failed') . '
                </h3>
                <p>' . ($message ? htmlspecialchars($message) : ($success ? 'You have been authenticated successfully.' : 'An error occurred during authentication.')) . '</p>
                <button id="closeButton" onclick="closeWindow()">Close Window</button>
            </div>
            
            <div class="debug-info">
                <p>Authentication source: ' . htmlspecialchars($source) . '</p>
                <p>Email: ' . ($email ? htmlspecialchars($email) : 'Not available') . '</p>
            </div>
            
            <script>
                // Function to send message to parent window
                function sendMessageToParent() {
                    try {
                        if (window.opener) {
                            console.log("Sending message to parent window");
                            
                            // Construct the message object
                            const message = {
                                auth: ' . ($success ? '"success"' : '"error"') . ',
                                source: "' . $source . '"
                            };
                            
                            // Add optional properties
                            ' . ($message ? 'message.message = "' . addslashes($message) . '";' : '') . '
                            ' . ($redirect ? 'message.redirect = "' . $redirect . '";' : '') . '
                            ' . ($email ? 'message.email = "' . addslashes($email) . '";' : '') . '
                            
                            // Post message to parent window (using wildcard origin for cross-domain support)
                            window.opener.postMessage(message, "*");
                            
                            console.log("Message sent successfully:", message);
                            
                            // Close the popup after a short delay
                            setTimeout(function() {
                                window.close();
                                // If it doesn\'t close automatically, show close button
                                setTimeout(function() {
                                    document.getElementById("closeButton").style.display = "block";
                                }, 500);
                            }, 1000);
                        } else {
                            console.error("No parent window found");
                            document.getElementById("closeButton").style.display = "block";
                        }
                    } catch (e) {
                        console.error("Error sending message to parent:", e);
                        document.getElementById("closeButton").style.display = "block";
                    }
                }
                
                // Function to manually close window
                function closeWindow() {
                    window.close();
                    // If the window doesn\'t close, suggest user to close it manually
                    setTimeout(function() {
                        document.body.innerHTML = "<p>Please close this window manually to complete the authentication process.</p>";
                    }, 1000);
                }
                
                // Send message as soon as page loads
                window.onload = function() {
                    console.log("Window loaded, sending message to parent");
                    sendMessageToParent();
                };
            </script>
        </body>
        </html>';
        exit;
    }

    /**
     * Renders authentication result for popup windows
     * 
     * @param string $provider The authentication provider (github or google)
     * @param array $resultData The result data to pass back to the opener window
     * @return void
     */
    private function renderAuthResult($provider, $resultData) {
        // Output a simple page with the auth result in a data attribute
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Authentication Result</title>
            <script src="' . URL_ROOT . '/public/js/' . $provider . '-auth.js"></script>
        </head>
        <body>
            <div id="' . $provider . '-auth-result" data-auth-result=\'' . json_encode($resultData) . '\'></div>
            <p>Authentication complete. This window will close automatically.</p>
        </body>
        </html>';
        exit;
    }

    /**
     * Displays a form to collect missing information after GitHub OAuth.
     *
     * @return void
     */
    public function githubComplete() {
        // Check if incomplete user data exists in session
        if (!isset($_SESSION['github_incomplete_user']) || !isset($_SESSION['github_missing_fields'])) {
            // If no data, redirect to login or registration
            redirect('users/auth?action=login');
            return;
        }

        // Debug: Log the data we have in the session
        error_log('GitHub incomplete user data: ' . print_r($_SESSION['github_incomplete_user'], true));

        // Prepare data for the view
        $data = $_SESSION['github_incomplete_user'];
        $data['missing_fields'] = $_SESSION['github_missing_fields'];
        $data['title'] = 'Complete Your Profile';
        $data['description'] = 'Please provide the remaining details to complete your registration.';
        
        // Make sure email is explicitly accessible
        if (isset($_SESSION['github_incomplete_user']['email'])) {
            $data['email'] = $_SESSION['github_incomplete_user']['email'];
        }
        
        // Make sure name is explicitly accessible
        if (isset($_SESSION['github_incomplete_user']['name'])) {
            $data['name'] = $_SESSION['github_incomplete_user']['name'];
        }
        
        // Add error fields if redirected back from processGithubCompletion
        $data['country_err'] = $_SESSION['github_complete_errors']['country_err'] ?? '';
        // Add other potential error fields here
        unset($_SESSION['github_complete_errors']); // Clear errors after displaying

        // Load the auth view with a specific action flag
        $this->view('pages/auth', $data); 
    }

    /**
     * Processes the form submission for completing GitHub registration.
     *
     * @return void
     */
    public function processGithubCompletion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('users/auth?action=github_complete');
            return;
        }

        // Check if incomplete user data exists in session
        if (!isset($_SESSION['github_incomplete_user']) || !isset($_SESSION['github_missing_fields'])) {
            redirect('users/auth?action=login');
            return;
        }

        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Retrieve stored data and merge with POST data
        $userData = $_SESSION['github_incomplete_user'];
        $missingFields = $_SESSION['github_missing_fields'];
        $errors = [];

        // Debug log to help troubleshoot
        error_log('GitHub completion POST data: ' . print_r($_POST, true));

        // Check for required fields
        if (!isset($_POST['terms'])) {
            $errors['terms_err'] = 'You must agree to the terms of service';
        }

        // Update the name from the form input (overrides any auto-generated name)
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $userData['name'] = trim($_POST['name']);
            
            // Also update first_name and last_name based on the full name
            $nameParts = explode(' ', $userData['name'], 2);
            $userData['first_name'] = $nameParts[0];
            $userData['last_name'] = $nameParts[1] ?? '';
        }

        // Validate submitted data for missing fields
        if (in_array('country', $missingFields) || empty($userData['country'])) {
            $userData['country'] = trim($_POST['country'] ?? '');
            if (empty($userData['country'])) {
                $errors['country_err'] = 'Please select your country';
            }
        }
        
        // Process account type (role) selection - ALWAYS update this from the form
        if (isset($_POST['account_type']) && in_array($_POST['account_type'], ['client', 'freelancer'])) {
            $userData['account_type'] = $_POST['account_type'];
        } else {
            // Default to client if not set or invalid
            $userData['account_type'] = 'client';
        }

        // Check if there are validation errors
        if (!empty($errors)) {
            // Store errors in session and redirect back to completion form
            $_SESSION['github_complete_errors'] = $errors;
            redirect('users/auth?action=github_complete');
            return;
        }

        // Set profile picture from GitHub if available
        if (isset($_POST['profile_image']) && !empty($_POST['profile_image'])) {
            $userData['profile_image'] = $_POST['profile_image'];
        }

        // Debug log to verify user data before registration
        error_log('GitHub registration data before saving: ' . print_r($userData, true));

        // All required data is now present, proceed with registration
        $newUser = $this->userModel->registerWithGithub($userData);

        if ($newUser) {
            // Registration successful, clean up session and log in
            unset($_SESSION['github_incomplete_user']);
            unset($_SESSION['github_missing_fields']);
            unset($_SESSION['github_complete_errors']);
            unset($_SESSION['selected_user_type']);
            
            // Create a proper user session
            $this->createUserSession($newUser);
            
            // Redirect to dashboard or profile setup
            flash('login_message', 'Your GitHub account has been registered successfully!', 'alert alert-success');
            redirect('pages/' . strtolower($newUser->account_type)); 
        } else {
            // Registration failed - add detailed error logging
            error_log('GitHub Registration Error: Failed to complete registration in processGithubCompletion');
            
            flash('register_error', 'Failed to complete registration. Please try again or contact support.', 'alert alert-danger');
            // Optionally clean up session data here too
            unset($_SESSION['github_incomplete_user']);
            unset($_SESSION['github_missing_fields']);
            redirect('users/auth?action=register');
        }
    }

    /**
     * Ajax login completion for GitHub auth
     */
    public function completeGithubLogin() {
        // Set content type header upfront to ensure we're always returning JSON
        header('Content-Type: application/json');
        
        // Make sure it's an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit; // Ensure no further processing happens
        }
        
        try {
            // Get JSON POST data
            $json = file_get_contents('php://input');
            
            // Debug: Log raw input
            error_log('Raw github login input: ' . $json);
            
            $data = json_decode($json, true);
            
            // Check for JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data: ' . json_last_error_msg());
            }
            
            // Debug: Log processed data
            error_log('Processed github login data: ' . print_r($data, true));
            
            // Validate email parameter
            if (!isset($data['email']) || empty($data['email'])) {
                throw new Exception('Missing required email parameter');
            }
            
            // Find user by email
            $user = $this->userModel->findUserByEmail($data['email']);
            
            if (!$user) {
                throw new Exception('User not found with email: ' . $data['email']);
            }
            
            // Create user session
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_account_type'] = $user->account_type;
            
            // Initialize CurrentUser data if that function exists
            if (class_exists('CurrentUser') && method_exists('CurrentUser', 'initialize')) {
                CurrentUser::initialize();
            }
            
            // Return success response with redirect destination
            $redirect = URL_ROOT;
            if ($user->account_type == 'client') {
                $redirect .= '/client';
            } elseif ($user->account_type == 'freelancer') {
                $redirect .= '/freelance';
            } else {
                $redirect .= '/dashboard';
            }
            
            $response = [
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $redirect,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type
                ]
            ];
            
            error_log('Successful login response: ' . json_encode($response));
            echo json_encode($response);
            exit; // Ensure no further processing happens
            
        } catch (Exception $e) {
            // Log the error
            error_log('GitHub login error: ' . $e->getMessage());
            
            // Return error response
            $errorResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'error_details' => 'See server logs for more information'
            ];
            
            echo json_encode($errorResponse);
            exit; // Ensure no further processing happens
        }
    }

    /**
     * Handles Google OAuth authentication
     * 
     * @return void
     */
    public function googleAuth() {
        // Check if this is a callback request from Google
        if (isset($_GET['code'])) {
            // This is a callback, handle the authentication flow
            $this->googleCallback();
            return;
        }
        
        // Generate a random state value for security
        $_SESSION['google_oauth_state'] = bin2hex(random_bytes(16));
        
        // Determine whether this is for login or registration based on query parameter
        $auth_action = isset($_GET['auth_action']) ? $_GET['auth_action'] : 'login';
        $_SESSION['google_auth_action'] = $auth_action;
        
        // Configure OAuth parameters
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => URL_ROOT . '/users/googleAuth',
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
            'state' => $_SESSION['google_oauth_state'],
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        
        // For popup window, set a flag to indicate this is an OAuth popup
        $_SESSION['google_auth_popup'] = true;
        
        // Redirect to Google
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Handles the callback from Google after authentication.
     *
     * @return void
     */
    public function googleCallback() {
        // Verify state to prevent CSRF attacks
        if (!isset($_GET['state']) || !isset($_SESSION['google_oauth_state']) || $_GET['state'] !== $_SESSION['google_oauth_state']) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Invalid state. Authentication failed.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Invalid state. Authentication failed.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }
        unset($_SESSION['google_oauth_state']); // Clean up state

        // Check if Google returned an error
        if (isset($_GET['error'])) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Google authentication failed: ' . htmlspecialchars($_GET['error']), null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Google authentication failed: ' . htmlspecialchars($_GET['error']), 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        // Check if code is present
        if (!isset($_GET['code'])) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Authorization code not received from Google.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Authorization code not received from Google.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        $code = $_GET['code'];

        // --- Step 1: Exchange code for access token ---
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $tokenParams = [
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => URL_ROOT . '/users/googleAuth',
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Failed to get access token from Google.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Failed to get access token from Google.', 'alert alert-danger');
                error_log('Google Token Error: HTTP ' . $httpCode . ' Response: ' . $response);
                redirect('users/auth?action=login');
                return;
            }
        }

        $tokenData = json_decode($response, true);

        if (!isset($tokenData['access_token'])) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Access token not found in Google response.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Access token not found in Google response.', 'alert alert-danger');
                error_log('Google Token Error: Access token missing. Response: ' . $response);
                redirect('users/auth?action=login');
                return;
            }
        }

        $accessToken = $tokenData['access_token'];

        // --- Step 2: Get user profile data ---
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Failed to get user data from Google.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Failed to get user data from Google.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        $googleUser = json_decode($response, true);
        
        // Check if email exists
        if (empty($googleUser['email'])) {
            if (isset($_SESSION['google_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(false, 'Email not found in Google profile.', null, null, 'google-auth');
                return;
            } else {
                flash('login_error', 'Email not found in Google profile.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }
        
        // Store primary email in a session variable so it can be used in handlePopupResponse
        $_SESSION['temp_google_email'] = $googleUser['email'];
        error_log('Stored Google email in session: ' . $googleUser['email']);

        // Process Google authentication (similar to GitHub)
        $authResult = $this->processGoogleAuthentication($googleUser);

        // Handle the authentication result
        $action = isset($_SESSION['google_auth_action']) ? $_SESSION['google_auth_action'] : 'login';
        
        if (isset($_SESSION['google_auth_popup'])) {
            // For popup window
            if ($authResult['success']) {
                // Authentication successful
                if ($authResult['needsMoreInfo']) {
                    // User needs to complete registration
                    $this->handlePopupResponse(
                        true, 
                        'Authentication successful, but additional information needed.', 
                        URL_ROOT . '/users/auth?action=google_complete',
                        null,
                        'google-auth'
                    );
                } else {
                    // User fully authenticated
                    $user = $authResult['user'];
                    $this->handlePopupResponse(
                        true, 
                        'Authentication successful.', 
                        URL_ROOT . ($user->account_type == 'client' ? '/client' : '/freelance'),
                        ['email' => $user->email],
                        'google-auth'
                    );
                }
            } else {
                // Authentication failed
                $this->handlePopupResponse(
                    false, 
                    $authResult['message'], 
                    null, 
                    null,
                    'google-auth'
                );
            }
        } else {
            // For direct redirect
            if ($authResult['success']) {
                if ($authResult['needsMoreInfo']) {
                    // Redirect to profile completion page
                    redirect('users/auth?action=google_complete');
                } else {
                    // Log user in and redirect
                    $user = $authResult['user'];
                    $this->createUserSession($user);
                    redirect($user->account_type == 'client' ? 'client' : 'freelance');
                }
            } else {
                // Show error and redirect back to login
                flash('login_error', $authResult['message'], 'alert alert-danger');
                redirect('users/auth?action=login');
            }
        }
    }
    
    /**
     * Process Google authentication data and either login or create a user
     * 
     * @param array $googleUser User data from Google API
     * @return array Authentication result
     */
    private function processGoogleAuthentication($googleUser) {
        // First check if user already exists with this email
        $email = $googleUser['email'];
        
        $userModel = $this->model('User');
        $existingUser = $userModel->findUserByEmail($email);
        
        if ($existingUser) {
            // User exists, update Google profile info if needed
            $updateData = [
                'google_id' => $googleUser['sub'] ?? null,
                'last_login' => date('Y-m-d H:i:s')
                // Removed login_count which doesn't exist
            ];
            
            // Only update profile image if user doesn't have one
            if (empty($existingUser->profile_image) && isset($googleUser['picture'])) {
                $updateData['profile_image'] = $googleUser['picture'];
            }
            
            // Update user in database
            $userModel->updateUser($existingUser->id, $updateData);
            
            // Return success with user - use findUserByEmail instead of non-existent findUserById
            return [
                'success' => true,
                'message' => 'Authentication successful',
                'needsMoreInfo' => false,
                'user' => $existingUser // Use the user we already retrieved
            ];
        } else {
            // New user - store in session for registration completion
            $_SESSION['google_incomplete_user'] = [
                'sub' => $googleUser['sub'] ?? null,
                'email' => $email,
                'name' => $googleUser['name'] ?? '',
                'given_name' => $googleUser['given_name'] ?? '',
                'family_name' => $googleUser['family_name'] ?? '',
                'picture' => $googleUser['picture'] ?? null,
                'locale' => $googleUser['locale'] ?? null
            ];
            
            // Return with needs more info flag
            return [
                'success' => true,
                'message' => 'New user registration required',
                'needsMoreInfo' => true
            ];
        }
    }
    
    /**
     * Ajax login completion for Google auth
     */
    public function completeGoogleLogin() {
        // Set content type header upfront to ensure we're always returning JSON
        header('Content-Type: application/json');
        
        // Make sure it's an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        try {
            // Get JSON POST data
            $json = file_get_contents('php://input');
            
            // Debug: Log raw input
            error_log('Raw Google login input: ' . $json);
            
            $data = json_decode($json, true);
            
            // Check for JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data: ' . json_last_error_msg());
            }
            
            // Debug: Log processed data
            error_log('Processed Google login data: ' . print_r($data, true));
            
            // Validate email parameter
            if (!isset($data['email']) || empty($data['email'])) {
                throw new Exception('Missing required email parameter');
            }
            
            // Find user by email
            $user = $this->userModel->findUserByEmail($data['email']);
            
            if (!$user) {
                throw new Exception('User not found with email: ' . $data['email']);
            }
            
            // Create user session
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_account_type'] = $user->account_type;
            
            // Initialize CurrentUser data if that function exists
            if (class_exists('CurrentUser') && method_exists('CurrentUser', 'initialize')) {
                CurrentUser::initialize();
            }
            
            // Return success response with redirect destination
            $redirect = URL_ROOT;
            if ($user->account_type == 'client') {
                $redirect .= '/client';
            } elseif ($user->account_type == 'freelancer') {
                $redirect .= '/freelance';
            } else {
                $redirect .= '/dashboard';
            }
            
            $response = [
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $redirect,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type
                ]
            ];
            
            error_log('Successful Google login response: ' . json_encode($response));
            echo json_encode($response);
            exit;
            
        } catch (Exception $e) {
            // Log the error
            error_log('Google login error: ' . $e->getMessage());
            
            // Return error response
            $errorResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'error_details' => 'See server logs for more information'
            ];
            
            echo json_encode($errorResponse);
            exit;
        }
    }
}
