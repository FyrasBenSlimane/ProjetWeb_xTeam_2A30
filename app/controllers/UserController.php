<?php
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        // Check if user is logged in for all methods in this controller
        if (!isset($_SESSION['user_id'])) {
            // Store the requested URL to redirect back after login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            redirect('users/auth?action=login');
        }
        
        // Initialize user role if not set (for backward compatibility)
        if (!isset($_SESSION['user_role']) && isset($_SESSION['user_account_type'])) {
            $_SESSION['user_role'] = $_SESSION['user_account_type'];
        }
        
        $this->userModel = $this->model('User');
    }

    /**
     * Display combined profile and settings page
     */
    public function account() {
        // Get user profile data directly from database
        $userProfile = $this->userModel->getUserById($_SESSION['user_id']);
        
        if (!$userProfile) {
            flash('account_error', 'Unable to load profile data', 'alert alert-danger');
            redirect('dashboard');
        }
        
        // Convert JSON skills to array
        $skills = [];
        if (!empty($userProfile->skills)) {
            $skills = json_decode($userProfile->skills, true);
        }
        
        // Prepare data for the view
        $data = [
            'title' => 'Your Account',
            'description' => 'Manage your profile, account settings, and preferences',
            'user' => $userProfile,
            'skills' => $skills,
            'social_links' => [
                'website' => $userProfile->website ?? '',
                'linkedin' => $userProfile->linkedin ?? '',
                'github' => $userProfile->github ?? '',
                'twitter' => $userProfile->twitter ?? '',
            ],
            'notifications' => [
                'email_updates' => $userProfile->email_updates ?? true,
                'message_alerts' => $userProfile->message_alerts ?? true,
                'job_recommendations' => $userProfile->job_recommendations ?? true,
                'marketing_emails' => $userProfile->marketing_emails ?? false,
            ],
            'language' => $userProfile->language ?? 'en',
            'timezone' => $userProfile->timezone ?? 'UTC',
            'currency' => $userProfile->currency ?? 'USD',
        ];
        
        $this->view('layouts/header', $data);
        $this->view('user/account', $data);
        $this->view('layouts/footer');
    }

    /**
     * Handles redirecting old profile and settings URLs to the new account page
     */
    public function profile() {
        redirect('user/account#profile');
    }

    /**
     * Handles redirecting old profile and settings URLs to the new account page
     */
    public function settings() {
        redirect('user/account#account');
    }

    /**
     * Update user profile information
     * Handles form submission from the profile page
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/profile');
            return;
        }
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'name' => trim($_POST['name']),
            'location' => trim($_POST['location']),
            'bio' => trim($_POST['bio'])
        ];
        if ($_SESSION['user_role'] == 'freelancer' && isset($_POST['hourly_rate'])) {
            $data['hourly_rate'] = trim($_POST['hourly_rate']);
        }
        // Validation
        $errors = [];
        if (empty($data['name'])) $errors[] = 'Name cannot be empty.';
        if (empty($data['location'])) $errors[] = 'Location cannot be empty.';
        if (empty($data['bio'])) $errors[] = 'Bio cannot be empty.';
        if (isset($data['hourly_rate']) && (!is_numeric($data['hourly_rate']) || $data['hourly_rate'] < 0)) $errors[] = 'Hourly rate must be a positive number.';
        if ($errors) {
            flash('profile_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/profile');
            return;
        }
        // Update profile in database
        if ($this->userModel->updateProfile($data)) {
            // No need to update session variables since we're now fetching from the database
            flash('profile_success', 'Your profile has been updated successfully');
        } else {
            flash('profile_error', 'There was an error updating your profile', 'alert alert-danger');
        }
        
        redirect('user/profile');
    }

    /**
     * Update user skills
     * Handles form submission from the skills modal
     */
    public function updateSkills() {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/profile');
            return;
        }
        
        // Process form data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Prepare data
        $skills = [];
        if (!empty($_POST['skills'])) {
            // Split comma-separated skills and trim whitespace
            $skillsArray = explode(',', $_POST['skills']);
            foreach ($skillsArray as $skill) {
                $trimmedSkill = trim($skill);
                if (!empty($trimmedSkill)) {
                    $skills[] = $trimmedSkill;
                }
            }
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'skills' => $skills
        ];
        
        // Update skills in database
        if ($this->userModel->updateSkills($data)) {
            // No need to update session variable since we're now fetching from the database
            flash('profile_success', 'Your skills have been updated successfully');
        } else {
            flash('profile_error', 'There was an error updating your skills', 'alert alert-danger');
        }
        
        redirect('user/profile');
    }

    /**
     * Update user social links
     * Handles form submission from the social links modal
     */
    public function updateSocial() {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/profile');
            return;
        }
        
        // Process form data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_URL);
        
        // Prepare data
        $data = [
            'user_id' => $_SESSION['user_id'],
            'website' => trim($_POST['website']),
            'linkedin' => trim($_POST['linkedin']),
            'github' => trim($_POST['github']),
            'twitter' => trim($_POST['twitter'])
        ];
        
        // Validate URLs if provided
        $errors = [];
        foreach (['website', 'linkedin', 'github', 'twitter'] as $site) {
            if (!empty($data[$site]) && !filter_var($data[$site], FILTER_VALIDATE_URL)) {
                $errors[] = ucfirst($site) . ' URL is not valid.';
            }
        }
        
        if ($errors) {
            flash('profile_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/profile');
            return;
        }
        
        // Update social links in database
        if ($this->userModel->updateSocialLinks($data)) {
            // No need to update session variables since we're now fetching from the database
            flash('profile_success', 'Your social links have been updated successfully');
        } else {
            flash('profile_error', 'There was an error updating your social links', 'alert alert-danger');
        }
        
        redirect('user/profile');
    }

    /**
     * Update user avatar
     * Handles form submission from the avatar modal
     */
    public function updateAvatar() {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/profile');
            return;
        }
        
        // Check for uploaded file
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] != 0) {
            flash('profile_error', 'No file uploaded or an error occurred', 'alert alert-danger');
            redirect('user/profile');
            return;
        }
        
        // Get file info
        $file = $_FILES['avatar'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        
        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');
        
        // Validate file extension
        if (!in_array($fileExt, $allowedExt)) {
            flash('profile_error', 'File type not allowed. Please upload a JPG, PNG or GIF image.', 'alert alert-danger');
            redirect('user/profile');
            return;
        }
        
        // Validate file size (2MB max)
        if ($fileSize > 2097152) {
            flash('profile_error', 'File size too large. Maximum size is 2MB.', 'alert alert-danger');
            redirect('user/profile');
            return;
        }
        
        // Generate unique file name
        $newFileName = uniqid() . '.' . $fileExt;
        
        // Set upload path
        $uploadDir = PUBLIC_PATH . '/uploads/avatars/';
        $uploadPath = $uploadDir . $newFileName;
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Upload file
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Generate URL path
            $avatarUrl = URL_ROOT . '/public/uploads/avatars/' . $newFileName;
            
            // Prepare data
            $data = [
                'user_id' => $_SESSION['user_id'],
                'avatar' => $avatarUrl
            ];
            
            // Update avatar in database
            if ($this->userModel->updateAvatar($data)) {
                // No need to update session variable since we're now fetching from the database
                flash('profile_success', 'Your profile picture has been updated successfully');
            } else {
                flash('profile_error', 'There was an error updating your profile picture', 'alert alert-danger');
            }
        } else {
            flash('profile_error', 'There was an error uploading your file', 'alert alert-danger');
        }
        
        redirect('user/profile');
    }

    /**
     * Update account information
     * Handles form submission from account settings page
     */
    public function updateAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'country' => trim($_POST['country'] ?? '')
        ];
        // Validation
        $errors = [];
        if (empty($data['name'])) $errors[] = 'Name cannot be empty.';
        if (empty($data['email'])) $errors[] = 'Email cannot be empty.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
        if (empty($data['country'])) $errors[] = 'Country cannot be empty.';
        $existingUser = $this->userModel->findUserByEmail($data['email']);
        if ($existingUser && $existingUser->id !== $_SESSION['user_id']) {
            $errors[] = 'Email is already in use by another account.';
        }
        if ($errors) {
            flash('settings_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/settings');
            return;
        }
        // Update account in database
        if ($this->userModel->updateAccount($data)) {
            // Update session variables
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['user_country'] = $data['country'];
            
            flash('settings_success', 'Your account information has been updated successfully');
        } else {
            flash('settings_error', 'There was an error updating your account information', 'alert alert-danger');
        }
        
        redirect('user/settings');
    }

    /**
     * Update password
     * Handles form submission from the settings page
     */
    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'current_password' => trim($_POST['current_password']),
            'new_password' => trim($_POST['new_password']),
            'confirm_password' => trim($_POST['confirm_password'])
        ];
        
        // Validation
        $errors = [];
        if (empty($data['current_password'])) $errors[] = 'Current password is required.';
        if (empty($data['new_password'])) $errors[] = 'New password is required.';
        if (empty($data['confirm_password'])) $errors[] = 'Please confirm your new password.';
        if ($data['new_password'] != $data['confirm_password']) $errors[] = 'Passwords do not match.';
        
        // Password strength validation
        if (strlen($data['new_password']) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!preg_match('/[A-Z]/', $data['new_password'])) $errors[] = 'Password must include at least one uppercase letter.';
        if (!preg_match('/[a-z]/', $data['new_password'])) $errors[] = 'Password must include at least one lowercase letter.';
        if (!preg_match('/\d/', $data['new_password'])) $errors[] = 'Password must include at least one number.';
        
        // Verify current password
        $userInfo = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$userInfo || !password_verify($data['current_password'], $userInfo->password)) {
            $errors[] = 'Current password is incorrect.';
        }
        
        if ($errors) {
            flash('settings_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/settings');
            return;
        }
        
        // Update password in database
        $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
        if ($this->userModel->updatePassword($data)) {
            flash('settings_success', 'Your password has been updated successfully');
        } else {
            flash('settings_error', 'There was an error updating your password', 'alert alert-danger');
        }
        
        redirect('user/settings');
    }

    /**
     * Update notification preferences
     * Handles form submission from the settings page
     */
    public function updateNotifications() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'email_updates' => isset($_POST['email_updates']) ? 1 : 0,
            'message_alerts' => isset($_POST['message_alerts']) ? 1 : 0,
            'job_recommendations' => isset($_POST['job_recommendations']) ? 1 : 0,
            'marketing_emails' => isset($_POST['marketing_emails']) ? 1 : 0
        ];
        
        // Update notification settings in database
        if ($this->userModel->updateNotificationPreferences($data)) {
            flash('settings_success', 'Your notification preferences have been updated');
        } else {
            flash('settings_error', 'There was an error updating your notification preferences', 'alert alert-danger');
        }
        
        redirect('user/settings');
    }

    /**
     * Update regional preferences
     * Handles form submission from the settings page
     */
    public function updatePreferences() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'language' => trim($_POST['language']),
            'timezone' => trim($_POST['timezone']),
            'currency' => trim($_POST['currency'])
        ];
        
        // Validation
        $errors = [];
        if (empty($data['language'])) $errors[] = 'Language selection is required.';
        if (empty($data['timezone'])) $errors[] = 'Timezone selection is required.';
        if (empty($data['currency'])) $errors[] = 'Currency selection is required.';
        
        if ($errors) {
            flash('settings_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/settings');
            return;
        }
        
        // Update preferences in database
        if ($this->userModel->updatePreferences($data)) {
            flash('settings_success', 'Your preferences have been updated');
        } else {
            flash('settings_error', 'There was an error updating your preferences', 'alert alert-danger');
        }
        
        redirect('user/settings');
    }

    /**
     * Update privacy settings
     * Handles form submission from the settings page
     */
    public function updatePrivacy() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'profile_visibility' => trim($_POST['profile_visibility']),
            'data_usage' => isset($_POST['data_usage']) ? 1 : 0
        ];
        
        // Validation
        $errors = [];
        $validVisibilityOptions = ['public', 'limited', 'private'];
        if (!in_array($data['profile_visibility'], $validVisibilityOptions)) {
            $errors[] = 'Invalid profile visibility option.';
        }
        
        if ($errors) {
            flash('settings_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/settings');
            return;
        }
        
        // Update privacy settings in database
        if ($this->userModel->updatePrivacy($data)) {
            flash('settings_success', 'Your privacy settings have been updated');
        } else {
            flash('settings_error', 'There was an error updating your privacy settings', 'alert alert-danger');
        }
        
        redirect('user/settings');
    }

    /**
     * Deactivate user account
     * Handles form submission from the deactivate account modal
     */
    public function deactivateAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'user_id' => $_SESSION['user_id'],
            'deactivate_reason' => trim($_POST['deactivate_reason'])
        ];
        
        // Update account status in database
        if ($this->userModel->deactivateAccount($data)) {
            // Log the deactivation reason
            if (!empty($data['deactivate_reason'])) {
                $this->userModel->logAccountAction($_SESSION['user_id'], 'deactivation', $data['deactivate_reason']);
            }
            
            // Destroy the session
            session_destroy();
            
            // Redirect to login with message
            flash('login_message', 'Your account has been deactivated. You can reactivate it by logging in again.');
            redirect('users/auth?action=login');
        } else {
            flash('settings_error', 'There was an error deactivating your account', 'alert alert-danger');
            redirect('user/settings');
        }
    }

    /**
     * Delete user account permanently
     * Handles form submission from the delete account modal
     */
    public function deleteAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/settings');
            return;
        }
        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $password = trim($_POST['password']);
        $confirmDelete = isset($_POST['confirm_delete']);
        
        // Validation
        $errors = [];
        if (empty($password)) $errors[] = 'Password is required to confirm account deletion.';
        if (!$confirmDelete) $errors[] = 'You must confirm that you understand this action is permanent.';
        
        // Verify password
        $userInfo = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$userInfo || !password_verify($password, $userInfo->password)) {
            $errors[] = 'Password is incorrect.';
        }
        
        if ($errors) {
            flash('settings_error', implode(' ', $errors), 'alert alert-danger');
            redirect('user/settings');
            return;
        }
        
        // Delete account from database
        if ($this->userModel->deleteAccount($_SESSION['user_id'])) {
            // Destroy the session
            session_destroy();
            
            // Redirect to homepage with message
            flash('message', 'Your account has been permanently deleted.');
            redirect('');
        } else {
            flash('settings_error', 'There was an error deleting your account', 'alert alert-danger');
            redirect('user/settings');
        }
    }

    /**
     * Handle legacy URLs redirecting to appropriate method
     * This is used to handle old-style URLs like /user/profile when accessed from NavBar
     * 
     * @param string $page The page name
     * @return void
     */
    public function index($page = null) {
        // If no page provided, default to profile
        if (!$page) {
            $this->profile();
            return;
        }
        
        // Check which page to load
        switch($page) {
            case 'profile':
                $this->profile();
                break;
            case 'settings':
                $this->settings();
                break;
            // Handle hyphenated method names (from URLs) to camelCase methods
            case 'update-profile':
                $this->updateProfile();
                break;
            case 'update-account':
                $this->updateAccount();
                break;
            case 'update-skills':
                $this->updateSkills();
                break;
            case 'update-social':
                $this->updateSocial();
                break;
            case 'update-avatar':
                $this->updateAvatar();
                break;
            case 'update-password':
                $this->updatePassword();
                break;
            case 'update-notifications':
                $this->updateNotifications();
                break;
            case 'update-preferences':
                $this->updatePreferences();
                break;
            case 'update-privacy':
                $this->updatePrivacy();
                break;
            case 'deactivate-account':
                $this->deactivateAccount();
                break;
            case 'delete-account':
                $this->deleteAccount();
                break;
            default:
                // Default to profile if unknown page
                $this->profile();
                break;
        }
    }

    /**
     * Update a single field in the user's profile via AJAX
     * Used for inline editing of profile fields
     * 
     * @return void
     */
    public function updateField() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'You must be logged in to perform this action']);
            exit;
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        // Get JSON data or regular POST data
        $inputData = file_get_contents('php://input');
        $data = json_decode($inputData, true);
        
        // Fallback to $_POST if JSON parsing fails
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $_POST;
        }
        
        // Check if required fields are present
        if (!isset($data['field']) || !isset($data['value'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields (field, value)']);
            exit;
        }
        
        // Process the field update
        $field = trim($data['field']);
        $value = $data['value'];
        $userId = $_SESSION['user_id'];
        
        // Special handling for skills field - needs to be JSON
        if ($field === 'skills' && is_array($value)) {
            $value = json_encode($value);
        }
        
        // Update the field
        if ($this->userModel->updateSingleField($userId, $field, $value)) {
            // Also update session variables if applicable
            switch ($field) {
                case 'name':
                    $_SESSION['user_name'] = $value;
                    break;
                case 'email':
                    $_SESSION['user_email'] = $value;
                    break;
                case 'bio':
                    $_SESSION['user_bio'] = $value;
                    break;
                case 'location':
                    $_SESSION['user_location'] = $value;
                    break;
                case 'hourly_rate':
                    $_SESSION['hourly_rate'] = '$' . $value . '/hr';
                    break;
            }
            
            // Return success response
            echo json_encode([
                'success' => true, 
                'message' => 'Field updated successfully',
                'field' => $field,
                'value' => $value
            ]);
        } else {
            // Return error response
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to update field'
            ]);
        }
        exit;
    }
} 