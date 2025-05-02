<?php
class UsersController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * Consolidated authentication page handling
     * Serves login, registration, and password reset via a single view
     */
    public function auth()
    {
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
    public function verifyEmail()
    {
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
    public function login()
    {
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
                        setcookie('remember_user', $user->id . ':' . $token, time() + 60 * 60 * 24 * 30, '/');
                    }

                    redirect('');
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
    public function register()
    {
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
    public function password()
    {
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

    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_account_type'] = $user->account_type;

        // Redirect based on account type
        if ($_SESSION['user_account_type'] == 'freelancer') {
            redirect('pages/freelancer');
        } else {
            // Default redirect for other account types
            redirect('');
        }
    }

    public function logout()
    {
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
}
