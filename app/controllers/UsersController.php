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
                    
                    redirect('pages/index');
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
                
                // Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can now log in');
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

        // Get user profile data
        $profile = $this->userModel->getUserProfile($_SESSION['user_id']);
        
        // Get portfolio items if any
        $portfolio = $this->userModel->getUserPortfolio($_SESSION['user_id']);
        
        // Get work history if any
        $workHistory = $this->userModel->getUserWorkHistory($_SESSION['user_id']);
        
        // Calculate profile completion
        $profileCompletion = $this->userModel->calculateProfileCompletion($_SESSION['user_id']);
        
        // Prepare data for view
        $data = [
            'title' => 'My Profile',
            'description' => 'View and edit your profile',
            'user' => $profile,
            'portfolio_items' => $portfolio,
            'work_history' => $workHistory,
            'profile_completion' => $profileCompletion,
            // Include any additional profile fields here
            'professional_title' => $profile->professional_title ?? '',
            'user_bio' => $profile->bio ?? '',
            'experience_level' => $profile->experience_level ?? 'entry',
            'hourly_rate' => $profile->hourly_rate ?? 0,
            'hours_per_week' => $profile->hours_per_week ?? '',
            'categories' => $profile->categories ?? ''
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

        // Get user profile data
        $profile = $this->userModel->getUserProfile($_SESSION['user_id']);
        
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
                'email' => trim($_POST['email']),
                'account_type' => trim($_POST['account_type']),
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
                    
                    // Set success flash message
                    flash('profile_message', 'Your profile has been updated successfully');
                    
                    // Redirect to profile
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
                $this->handlePopupResponse(false, 'Invalid state. Authentication failed.');
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
                $this->handlePopupResponse(false, 'GitHub authentication failed: ' . htmlspecialchars($_GET['error_description']));
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
                $this->handlePopupResponse(false, 'Authorization code not received from GitHub.');
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
                $this->handlePopupResponse(false, 'Failed to get access token from GitHub.');
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
                $this->handlePopupResponse(false, 'Access token not found in GitHub response.');
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
                $this->handlePopupResponse(false, 'Failed to fetch user data from GitHub.');
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
                $this->handlePopupResponse(false, 'Failed to fetch user emails from GitHub.');
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
                $this->handlePopupResponse(false, 'Could not retrieve a verified email address from GitHub.');
                return;
            } else {
                flash('login_error', 'Could not retrieve a verified email address from GitHub.', 'alert alert-danger');
                redirect('users/auth?action=login');
                return;
            }
        }

        // --- Step 3: Check if user exists / Register / Login ---
        $user = $this->userModel->findUserByGithubId($githubUser['id']);

        // Get the auth action (login or register) from session
        $auth_action = $_SESSION['github_auth_action'] ?? 'login';

        if ($user) {
            // User exists (linked via GitHub ID), log them in regardless of auth_action
            $this->createUserSession($user);
            
            if (isset($_SESSION['github_auth_popup'])) {
                // In popup window, notify parent and close
                $this->handlePopupResponse(true, null, URL_ROOT . '/pages/index');
                return;
            } else {
                redirect('pages/index');
            }
        } else {
            // User not found by GitHub ID, check by email
            $user = $this->userModel->findUserByEmail($primaryEmail);
            
            if ($user) {
                // User exists with this email, but GitHub ID not linked yet
                if ($auth_action == 'login') {
                    // If login action, link the GitHub ID to the existing account and login
                    // TODO: Add logic to link github_id to existing user in UserModel
                    // $this->userModel->linkGithubId($user->id, $githubUser['id']); 
                    $this->createUserSession($user);
                    
                    if (isset($_SESSION['github_auth_popup'])) {
                        // In popup window, notify parent and close
                        $this->handlePopupResponse(true, null, URL_ROOT . '/pages/index');
                        return;
                    } else {
                        redirect('pages/index');
                    }
                } else {
                    // If register action but email already exists, show error
                    if (isset($_SESSION['github_auth_popup'])) {
                        // In popup window, notify parent and close with error
                        $this->handlePopupResponse(false, 'An account with this email already exists. Please log in instead.');
                        return;
                    } else {
                        flash('register_error', 'An account with this email already exists. Please log in instead.', 'alert alert-danger');
                        redirect('users/auth?action=login');
                        return;
                    }
                }
            } else {
                // User does not exist, proceed with registration if auth_action is register
                if ($auth_action == 'register') {
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
                        'profile_picture' => $githubUser['avatar_url'] ?? null
                    ];

                    // Check for required fields missing from GitHub
                    $missingFields = [];
                    if (empty($userData['country'])) { $missingFields[] = 'country'; }
                    // Add other required fields here if needed

                    if (!empty($missingFields)) {
                        // Store partial data and redirect to completion form
                        $_SESSION['github_incomplete_user'] = $userData;
                        $_SESSION['github_missing_fields'] = $missingFields;
                        
                        if (isset($_SESSION['github_auth_popup'])) {
                            // In popup window, redirect to completion form
                            $completionUrl = URL_ROOT . '/users/auth?action=github_complete';
                            header('Location: ' . $completionUrl);
                            exit;
                        } else {
                            redirect('users/auth?action=github_complete');
                        }
                    } else {
                        // All required data present, register user directly
                        $newUser = $this->userModel->registerWithGithub($userData);
                        if ($newUser) {
                            $this->createUserSession($newUser);
                            
                            if (isset($_SESSION['github_auth_popup'])) {
                                // In popup window, notify parent and close
                                $this->handlePopupResponse(true, null, URL_ROOT . '/pages/index');
                                return;
                            } else {
                                redirect('pages/index');
                            }
                        } else {
                            if (isset($_SESSION['github_auth_popup'])) {
                                // In popup window, notify parent and close
                                $this->handlePopupResponse(false, 'Failed to register user with GitHub data.');
                                return;
                            } else {
                                flash('login_error', 'Failed to register user with GitHub data.', 'alert alert-danger');
                                redirect('users/auth?action=register');
                            }
                        }
                    }
                } else {
                    // If login action but user doesn't exist, show error
                    if (isset($_SESSION['github_auth_popup'])) {
                        // In popup window, notify parent and close with error
                        $this->handlePopupResponse(false, 'No account found with this GitHub account. Please register first.');
                        return;
                    } else {
                        flash('login_error', 'No account found with this GitHub account. Please register first.', 'alert alert-danger');
                        redirect('users/auth?action=register');
                        return;
                    }
                }
            }
        }
    }
    
    /**
     * Helper method to handle GitHub OAuth popup responses
     * This method will display a page that sends a message to the parent window and closes itself
     * 
     * @param bool $success Whether authentication was successful
     * @param string|null $message Error message if authentication failed
     * @param string|null $redirect URL to redirect to after successful authentication
     * @return void
     */
    private function handlePopupResponse($success, $message = null, $redirect = null) {
        // Clean up session
        unset($_SESSION['github_auth_popup']);
        
        // Output HTML that communicates with parent window and closes itself
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>GitHub Authentication</title>
            <script>
                // Send message to parent window
                if (window.opener && !window.opener.closed) {
                    window.opener.postMessage({
                        auth: ' . ($success ? '"success"' : '"error"') . ',
                        ' . ($message ? 'message: "' . addslashes($message) . '",' : '') . '
                        ' . ($redirect ? 'redirect: "' . $redirect . '",' : '') . '
                        source: "github-auth"
                    }, "' . URL_ROOT . '");
                    
                    // Close the popup window after sending the message
                    window.close();
                } else {
                    document.body.innerHTML = "<p>' . ($success 
                        ? 'Authentication successful. You may close this window and return to the application.' 
                        : 'Authentication failed: ' . addslashes($message ?: 'Unknown error')) . '</p>";
                }
            </script>
        </head>
        <body>
            <p>' . ($success 
                ? 'Authentication successful. Closing window...' 
                : 'Authentication failed: ' . htmlspecialchars($message ?: 'Unknown error')) . '</p>
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

        // Prepare data for the view
        $data = $_SESSION['github_incomplete_user'];
        $data['missing_fields'] = $_SESSION['github_missing_fields'];
        $data['title'] = 'Complete Your Profile';
        $data['description'] = 'Please provide the remaining details to complete your registration.';
        
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

        // Validate submitted data for missing fields
        if (in_array('country', $missingFields)) {
            $userData['country'] = trim($_POST['country'] ?? '');
            if (empty($userData['country'])) {
                $errors['country_err'] = 'Please select your country';
            }
        }
        // Add validation for other missing fields here
        // e.g., if user_type was missing:
        // if (in_array('user_type', $missingFields)) {
        //     $userData['account_type'] = trim($_POST['user_type'] ?? '');
        //     if (empty($userData['account_type']) || !in_array($userData['account_type'], ['client', 'freelancer'])) {
        //         $errors['user_type_err'] = 'Please select an account type';
        //     }
        // }

        // Check if there are validation errors
        if (!empty($errors)) {
            // Store errors in session and redirect back to completion form
            $_SESSION['github_complete_errors'] = $errors;
            redirect('users/auth?action=github_complete');
            return;
        }

        // All required data is now present, proceed with registration
        $newUser = $this->userModel->registerWithGithub($userData);

        if ($newUser) {
            // Registration successful, clean up session and log in
            unset($_SESSION['github_incomplete_user']);
            unset($_SESSION['github_missing_fields']);
            unset($_SESSION['github_complete_errors']);
            
            $this->createUserSession($newUser);
            // Redirect to dashboard or profile setup
            redirect('pages/index'); 
        } else {
            // Registration failed
            flash('register_error', 'Failed to complete registration. Please try again or contact support.', 'alert alert-danger');
            // Optionally clean up session data here too
            unset($_SESSION['github_incomplete_user']);
            unset($_SESSION['github_missing_fields']);
            redirect('users/auth?action=register');
        }
    }
}
