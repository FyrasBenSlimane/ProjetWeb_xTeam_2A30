<?php require APPROOT . '/views/layouts/header.php'; ?>

<?php
// Add special classes based on the current action to help with styling
$isRegisterPage = isset($_GET['action']) && $_GET['action'] == 'register';
$bodyClass = $isRegisterPage ? 'register-page' : 'login-page';
?>

<div class="auth-content <?php echo $isRegisterPage ? 'register-content' : ''; ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-form-container <?php echo $isRegisterPage ? 'signup-form' : ''; ?>">
                    <?php if (!isset($_GET['action']) || $_GET['action'] == 'login'): ?>
                        <!-- Login Form -->
                        <div class="text-center mb-4">
                            <h2 class="mb-2">Welcome Back</h2>
                            <p class="text-muted">Sign in to your account to continue</p>
                        </div>
                        
                        <?php flash('login_error'); ?>
                        <?php flash('register_success'); ?>
                        <?php flash('login_message'); ?>
                        
                        <!-- Step 1: Email Verification Form -->
                        <div id="step1-form" class="<?php echo (!isset($_SESSION['login_email_verified']) ? '' : 'd-none'); ?>">
                            <form action="<?php echo URL_ROOT; ?>/users/verifyEmail" method="POST" id="email-verification-form">
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope icon-left"></i>
                                        <input type="email" name="email" id="email" 
                                            class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                            value="<?php echo $data['email'] ?? ''; ?>"
                                            placeholder="name@example.com">
                                    </div>
                                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                                </div>
                                
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary login-btn">
                                        <span class="btn-text">Continue</span>
                                        <div class="btn-loader spinner-border spinner-border-sm d-none" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="auth-divider">
                                <span>OR</span>
                            </div>
                            
                            <div class="social-buttons-container">
                                <a href="javascript:void(0)" onclick="openGoogleAuth('login', '<?php echo URL_ROOT; ?>')" class="auth-provider-btn social-btn google-btn">
                                    <i class="fab fa-google me-2"></i> Continue with Google
                                </a>
                                <a href="javascript:void(0)" onclick="openGitHubAuth('login', '<?php echo URL_ROOT; ?>')" class="auth-provider-btn social-btn github-btn">
                                    <i class="fab fa-github me-2"></i> Continue with GitHub
                                </a>
                            </div>
                            
                            <!-- Initialize Authentication Listeners -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    setupGitHubAuthListener('<?php echo URL_ROOT; ?>');
                                    setupGoogleAuthListener('<?php echo URL_ROOT; ?>');
                                });
                            </script>
                        </div>
                        
                        <!-- Step 2: Password Form -->
                        <div id="step2-form" class="<?php echo (isset($_SESSION['login_email_verified']) ? '' : 'd-none'); ?>">
                            <?php if(isset($_SESSION['login_email_verified'])): ?>
                                <div class="verified-email-container mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="verified-email-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="verified-email-info">
                                            <span class="email-text"><?php echo $_SESSION['login_email']; ?></span>
                                            <a href="<?php echo URL_ROOT; ?>/users/auth?action=login&reset=true" class="change-email-link">
                                                <small>Not you?</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo URL_ROOT; ?>/users/login" method="POST" id="password-form">
                                <input type="hidden" name="email" value="<?php echo $_SESSION['login_email'] ?? ''; ?>">
                                
                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-input-wrapper">
                                        <i class="fas fa-lock icon-left"></i>
                                        <input type="password" name="password" id="password" 
                                            class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                            placeholder="Enter your password">
                                        <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                                </div>
                                
                                <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="<?php echo URL_ROOT; ?>/users/auth?action=password" class="forgot-password-link">Forgot Password?</a>
                                </div>
                                
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary login-btn">
                                        <span class="btn-text">Sign In</span>
                                        <div class="btn-loader spinner-border spinner-border-sm d-none" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="auth-nav mt-4 text-center">
                            <p class="mb-0">
                                Don't have an account? <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="auth-link">Sign Up</a>
                            </p>
                        </div>
                    <?php elseif (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
                        <!-- Register Form -->
                        <?php if(!isset($_GET['type'])): ?>
                            <!-- Step 1: User Type Selection -->
                            <div class="text-center mb-4">
                                <h2 class="mb-2">Join our Community</h2>
                                <p class="text-muted">Choose how you want to use our platform</p>
                            </div>
                            
                            <div class="user-type-selection">
                                <a href="<?php echo URL_ROOT; ?>/users/auth?action=register&type=client" class="user-type-box client-box">
                                    <div class="icon-container">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h3>I want to hire talent</h3>
                                </a>
                                
                                <a href="<?php echo URL_ROOT; ?>/users/auth?action=register&type=freelancer" class="user-type-box freelancer-box">
                                    <div class="icon-container">
                                        <i class="fas fa-laptop-code"></i>
                                    </div>
                                    <h3>I want to work as a freelancer</h3>
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Step 2: Registration Form -->
                            <div class="text-center mb-4">
                                <?php if(isset($_GET['type']) && $_GET['type'] == 'freelancer'): ?>
                                    <h2 class="mb-2">Sign up to find work you love</h2>
                                    <p class="text-muted">Join thousands of freelancers who find success on our platform</p>
                                <?php elseif(isset($_GET['type']) && $_GET['type'] == 'client'): ?>
                                    <h2 class="mb-2">Sign up to hire top talent</h2>
                                    <p class="text-muted">Access our global pool of professional freelancers</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="auth-options mb-4">
                                <div class="social-buttons-container">
                                    <a href="javascript:void(0)" onclick="openGoogleAuth('register', '<?php echo URL_ROOT; ?>')" class="auth-provider-btn social-btn google-btn">
                                        <i class="fab fa-google me-2"></i> Continue with Google
                                    </a>
                                    <a href="javascript:void(0)" onclick="openGitHubAuth('register', '<?php echo URL_ROOT; ?>')" class="auth-provider-btn social-btn github-btn">
                                        <i class="fab fa-github me-2"></i> Continue with GitHub
                                    </a>
                                </div>
                            </div>
                            
                            <div class="text-center mb-4">
                                <p class="divider-text"><span>or</span></p>
                            </div>
                            
                            <form action="<?php echo URL_ROOT; ?>/users/register" method="POST">
                                <!-- Hidden field to track user type -->
                                <input type="hidden" name="user_type" value="<?php echo isset($_GET['type']) ? $_GET['type'] : 'general'; ?>">
                                
                                <div class="name-fields">
                                    <div class="name-field">
                                        <label for="first_name" class="form-label">First name</label>
                                        <input type="text" name="first_name" id="first_name" 
                                            class="form-control <?php echo isset($data['first_name_err']) && !empty($data['first_name_err']) ? 'is-invalid' : ''; ?>" 
                                            value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>"
                                            placeholder="First name">
                                        <span class="invalid-feedback"><?php echo isset($data['first_name_err']) ? $data['first_name_err'] : ''; ?></span>
                                    </div>
                                    
                                    <div class="name-field">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input type="text" name="last_name" id="last_name" 
                                            class="form-control <?php echo isset($data['last_name_err']) && !empty($data['last_name_err']) ? 'is-invalid' : ''; ?>" 
                                            value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>"
                                            placeholder="Last name">
                                        <span class="invalid-feedback"><?php echo isset($data['last_name_err']) ? $data['last_name_err'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="reg_email" class="form-label">Email</label>
                                    <input type="email" name="email" id="reg_email" 
                                        class="form-control <?php echo isset($data['email_err']) && !empty($data['email_err']) ? 'is-invalid' : ''; ?>" 
                                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                                        placeholder="Email">
                                    <span class="invalid-feedback"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reg_password" class="form-label">Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password" id="reg_password" 
                                            class="form-control <?php echo isset($data['password_err']) && !empty($data['password_err']) ? 'is-invalid' : ''; ?>" 
                                            placeholder="Password (8 or more characters)">
                                        <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="country" class="form-label">Country</label>
                                    <select name="country" id="country" class="form-select">
                                        <option value="">Select your country</option>
                                        <option value="US">United States</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="CA">Canada</option>
                                        <option value="AU">Australia</option>
                                        <option value="IN">India</option>
                                        <option value="TN" selected>Tunisia</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="promo_emails" name="promo_emails" checked>
                                        <label class="form-check-label" for="promo_emails">
                                            Send me helpful emails to find rewarding work and job leads.
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms">
                                        <label class="form-check-label" for="terms">
                                            Yes, I understand and agree to the <a href="#" class="terms-link">Upwork Terms of Service</a>, including the <a href="#" class="terms-link">User Agreement</a> and <a href="#" class="terms-link">Privacy Policy</a>.
                                        </label>
                                    </div>
                                    <span class="invalid-feedback"><?php echo isset($data['terms_err']) ? $data['terms_err'] : ''; ?></span>
                                </div>
                                
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary create-account-btn">
                                        Create my account
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                        
                        <div class="auth-nav mt-4 text-center">
                            <p class="mb-0">
                                Already have an account? <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="auth-link">Log In</a>
                            </p>
                        </div>
                    <?php elseif (isset($_GET['action']) && $_GET['action'] == 'github_complete'): ?>
                        <!-- GitHub Registration Completion Form -->
                        <div class="text-center mb-4">
                            <h2 class="mb-2">Complete Your Profile</h2>
                            <p class="text-muted">Please provide the following details to finish setting up your account with GitHub.</p>
                        </div>

                        <?php flash('register_error'); ?>
                        <?php flash('github_auth_error'); ?>

                        <form action="<?php echo URL_ROOT; ?>/users/processGithubCompletion" method="POST">
                            <?php 
                            // Debug output to help diagnose session data
                            if (isset($_SESSION['github_incomplete_user']) && is_array($_SESSION['github_incomplete_user'])):
                                // Session data is available - use it
                                $githubData = $_SESSION['github_incomplete_user'];
                                $email = $githubData['email'] ?? '';
                                $name = $githubData['name'] ?? '';
                                $profilePic = $githubData['avatar_url'] ?? '';
                                // Get user type from session if available
                                $userType = $githubData['account_type'] ?? '';
                            else:
                                // No session data found
                                $email = $data['email'] ?? '';
                                $name = $data['name'] ?? '';
                                $profilePic = '';
                                $userType = '';
                            endif;
                            
                            // Use data from github or passed form data
                            $email = $email ?: ($data['email'] ?? '');
                            $userType = $userType ?: ($data['user_type'] ?? '');
                            
                            // Extract first and last name from email if no name is available
                            if (empty($name) && !empty($email)) {
                                // Get part before @ symbol
                                $namePart = explode('@', $email)[0];
                                
                                // Replace dots, underscores, hyphens with spaces and capitalize
                                $formattedName = ucwords(str_replace(['.', '_', '-'], ' ', $namePart));
                                
                                // Set as name
                                $name = $formattedName;
                                
                                // Debug output to verify name extraction
                                echo "<!-- Name extracted from email: {$name} -->";
                            }
                            
                            // Make sure we have the name and email in data array
                            $data['name'] = $name ?: ($data['name'] ?? '');
                            $data['email'] = $email;
                            $data['profile_image'] = $profilePic ?: ($data['profile_image'] ?? '');
                            $data['user_type'] = $userType;
                            
                            // Store selected user type in session so it persists
                            if (!empty($userType)) {
                                $_SESSION['selected_user_type'] = $userType;
                            }
                            ?>
                            
                            <div class="mb-4">
                                <label for="display_name" class="form-label">Your Name</label>
                                <input type="text" name="name" id="display_name" 
                                    class="form-control <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>"
                                    value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>"
                                    required>
                                <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                            </div>
                            
                            <?php if (empty($data['email'])): ?>
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" 
                                        class="form-control <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>"
                                        required>
                                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                                <div class="mb-4">
                                    <label class="form-label">Email Address</label>
                                    <div class="form-control bg-light"><?php echo htmlspecialchars($data['email']); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-4">
                                <label for="country" class="form-label">Country</label>
                                <select name="country" id="country" class="form-select <?php echo !empty($data['country_err']) ? 'is-invalid' : ''; ?>" required>
                                    <option value="">Select your country</option>
                                    <option value="US" <?php echo (isset($data['country']) && $data['country'] == 'US') ? 'selected' : ''; ?>>United States</option>
                                    <option value="GB" <?php echo (isset($data['country']) && $data['country'] == 'GB') ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="CA" <?php echo (isset($data['country']) && $data['country'] == 'CA') ? 'selected' : ''; ?>>Canada</option>
                                    <option value="AU" <?php echo (isset($data['country']) && $data['country'] == 'AU') ? 'selected' : ''; ?>>Australia</option>
                                    <option value="IN" <?php echo (isset($data['country']) && $data['country'] == 'IN') ? 'selected' : ''; ?>>India</option>
                                    <option value="TN" <?php echo (isset($data['country']) && $data['country'] == 'TN') ? 'selected' : ''; ?>>Tunisia</option>
                                    <!-- Add more countries as needed -->
                                </select>
                                <span class="invalid-feedback"><?php echo $data['country_err'] ?? ''; ?></span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Account Type</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_type" id="client_type" value="client" 
                                    <?php echo (isset($_SESSION['selected_user_type']) && $_SESSION['selected_user_type'] == 'client') || 
                                             (isset($data['user_type']) && $data['user_type'] == 'client') || 
                                             (!isset($_SESSION['selected_user_type']) && !isset($data['user_type'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="client_type">I want to hire talent</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_type" id="freelancer_type" value="freelancer" 
                                    <?php echo (isset($_SESSION['selected_user_type']) && $_SESSION['selected_user_type'] == 'freelancer') || 
                                             (isset($data['user_type']) && $data['user_type'] == 'freelancer') ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="freelancer_type">I want to work as a freelancer</label>
                                </div>
                                <span class="invalid-feedback d-block"><?php echo $data['user_type_err'] ?? ''; ?></span>
                            </div>

                            <?php if (!empty($profilePic)): ?>
                                <div class="mb-4 text-center">
                                    <label class="form-label">Profile Picture</label>
                                    <div class="d-flex justify-content-center">
                                        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile picture" class="rounded-circle" style="width: 80px; height: 80px;">
                                        <input type="hidden" name="profile_image" value="<?php echo htmlspecialchars($profilePic); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I understand and agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a>.
                                    </label>
                                    <span class="invalid-feedback d-block"><?php echo $data['terms_err'] ?? ''; ?></span>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary create-account-btn">
                                    Complete Registration
                                </button>
                            </div>
                        </form>
                    <?php elseif (isset($_GET['action']) && $_GET['action'] == 'password'): ?>
                        <!-- Password Reset Form -->
                        <div class="text-center mb-4">
                            <h2 class="mb-2">Reset Your Password</h2>
                            <p class="text-muted">Enter your email to receive a password reset link</p>
                        </div>
                        
                        <?php flash('reset_message'); ?>
                        <?php flash('reset_error'); ?>
                        
                        <form action="<?php echo URL_ROOT; ?>/users/password" method="POST" id="reset-form">
                            <div class="mb-4">
                                <label for="reset_email" class="form-label">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope icon-left"></i>
                                    <input type="email" name="email" id="reset_email" 
                                        class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                        value="<?php echo $data['email'] ?? ''; ?>"
                                        placeholder="name@example.com">
                                </div>
                                <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                            </div>
                            
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary reset-btn">
                                    <span class="btn-text">Send Reset Link</span>
                                    <div class="btn-loader spinner-border spinner-border-sm d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            </div>
                        </form>
                        
                        <div class="auth-nav mt-4 text-center">
                            <p class="mb-0">
                                Remember your password? <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="auth-link">Log In</a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    /* Base spacing variables */
    --ws-mobile-x: 16px;
    --ws-4x: 16px;
    --ws-6x: 24px;
    --ws-8x: 32px;
    --footer-height: 80px; /* Define footer height for spacing calculations */
    
    /* Base font variables from navbar/landing */
    --font-primary: "Macan", "Helvetica Neue", Helvetica, Arial, sans-serif;
    --font-size-base: 16px;
    --font-weight-base: 400;
    --font-weight-medium: 500;
    --font-weight-bold: 600;
    --line-height-base: 1.5;
    --letter-spacing-base: normal;
    
    /* Primary color palette from landing page */
    --primary: #2c3e50;
    --primary-light: #34495e;
    --primary-dark: #1a252f;
    --primary-accent: #ecf0f1;

    /* Secondary colors */
    --secondary: #222325;
    --secondary-light: #404145;
    --secondary-dark: #0e0e10;
    --secondary-accent: #f1f1f2;
    
    /* Neutrals */
    --white: #ffffff;
    --text-dark: #222325;
    --gray-medium: #74767e;
    --gray-light: #e4e5e7;
    --gray-lighter: #fafafa;
    --gray-dark: #404145;
    
    /* UI elements */
    --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --transition-fast: 0.2s ease;
    --transition-default: 0.3s ease;
    
    /* Background colors */
    --bg-body: #ffffff;
    --bg-gradient-light: linear-gradient(170deg, #f9fafc, #f5f7fa);
}

body {
    background-color: var(--bg-body);
    color: var(--text-dark);
    font-family: var(--font-primary);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-base);
    letter-spacing: var(--letter-spacing-base);
    line-height: var(--line-height-base);
    margin: 0;
}

.auth-content {
    min-height: calc(100vh - 80px - var(--footer-height)); /* Account for navbar and footer */
    padding: 3rem 0;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: visible; /* Changed from hidden to visible to allow for longer content */
    margin-bottom: var(--footer-height); /* Ensure content doesn't overlap footer */
}

/* Specific adjustment for signup pages with longer forms */
.register-content {
    min-height: auto;
    padding-bottom: 120px; /* Extra padding for longer forms */
    align-items: flex-start; /* Align to top instead of center for longer forms */
}

/* Create subtle background decoration similar to landing page */
.auth-content::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.03) 0%, rgba(34, 35, 37, 0.03) 100%);
    top: -200px;
    left: -200px;
    z-index: 0;
    filter: blur(50px);
}

.auth-content::after {
    content: '';
    position: absolute;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.03) 0%, rgba(116, 118, 126, 0.03) 100%);
    bottom: -150px;
    right: -150px;
    z-index: 0;
    filter: blur(40px);
}

.container {
    --container-width: 1280px;
    --container-ws-x: var(--ws-mobile-x);
    --container-ws-y: var(--ws-mobile-x);
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--container-ws-x) !important;
    padding-right: var(--container-ws-x) !important;
    position: relative;
    width: min(100%, calc(var(--container-width) + var(--container-ws-x) * 2));
    z-index: 1;
}

@media (min-width: 700px) {
    .container {
        --container-ws-x: var(--ws-8x);
    }
}

.auth-form-container {
    background-color: var(--white);
    border-radius: 8px;
    padding: 48px 32px;
    border: 1px solid var(--gray-light);
    box-shadow: var(--shadow-md);
    max-width: 480px;
    width: 100%;
    margin: 0 auto;
    position: relative;
    transition: transform var(--transition-default), box-shadow var(--transition-default);
    margin-top: 1rem;
    margin-bottom: 1rem;
}

/* Form Elements - Updated to match Upwork sizing */
.divider-text {
    position: relative;
    text-align: center;
    margin: 20px 0;
    color: var(--gray-medium);
    font-size: 14px;
}

.divider-text:before,
.divider-text:after {
    content: "";
    position: absolute;
    width: 45%;
    background-color: var(--gray-light);
    height: 1px;
    top: 50%;
}

.divider-text:before {
    left: 0;
}

.divider-text:after {
    right: 0;
}

.divider-text span {
    display: inline-block;
    padding: 0 12px;
    background-color: var(--white);
    position: relative;
}

.auth-divider {
    display: flex;
    align-items: center;
    margin: 24px 0;
    color: var(--gray-medium);
    font-size: 14px;
}

.auth-divider::before, 
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background-color: var(--gray-light);
}

.auth-divider span {
    padding: 0 16px;
}

.social-buttons-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin: 0 auto;
    max-width: 100%;
}

.social-btn {
    font-size: 16px;
    padding: 12px 16px;
    height: 48px;
    width: 100%;
    border-radius: 6px;
    transition: all var(--transition-default);
    text-decoration: none;
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.auth-provider-btn {
    border: 1px solid var(--gray-light);
    background-color: var(--white);
    color: var(--text-dark);
    border-radius: 6px;
    font-weight: var(--font-weight-medium);
    cursor: pointer;
    transition: all var(--transition-default);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.auth-provider-btn:hover {
    background-color: var(--gray-lighter);
    border-color: var(--gray-medium);
    color: var(--primary);
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: var(--font-weight-medium);
    color: var(--text-dark);
    font-size: 14px;
}

.form-control, .form-select {
    width: 100%;
    padding: 8px 16px;
    height: 40px;
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    font-size: 16px;
    transition: all var(--transition-default);
    font-family: var(--font-primary);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.2);
}

.input-with-icon {
    position: relative;
}

.icon-left {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-medium);
    transition: color var(--transition-default);
}

.input-with-icon .form-control:focus + .icon-left {
    color: var(--primary);
}

.input-with-icon .form-control {
    padding-left: 45px;
}

.password-input-wrapper {
    position: relative;
}

.password-input-wrapper .form-control {
    padding-right: 45px;
}

.password-input-wrapper .icon-left + .form-control {
    padding-left: 45px;
}

.password-toggle {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-medium);
    cursor: pointer;
    transition: color var(--transition-default);
}

.password-toggle:hover {
    color: var(--primary);
}

.invalid-feedback {
    color: #e74c3c;
    font-size: 14px;
    margin-top: 4px;
    display: block;
}

.form-check {
    display: flex;
    align-items: flex-start;
    margin-bottom: 8px;
}

.form-check-input {
    margin-top: 3px;
    margin-right: 8px;
}

.form-check-label {
    font-size: 14px;
    color: var(--gray-dark);
    line-height: 1.4;
}

.btn {
    display: inline-block;
    font-weight: var(--font-weight-medium);
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 8px 24px;
    font-size: 16px;
    line-height: 1.5;
    border-radius: 6px;
    transition: all var(--transition-default);
    text-decoration: none;
}

.btn-primary {
    background-color: var(--primary);
    color: var(--white);
    font-weight: var(--font-weight-medium);
}

.btn-primary:hover {
    background-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
}

.d-grid {
    display: grid;
}

.login-btn, .reset-btn, .create-account-btn {
    width: 100%;
    padding: 8px 24px;
    height: 40px;
    font-size: 16px;
    font-weight: var(--font-weight-medium);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: 6px;
    transition: all var(--transition-default);
    text-decoration: none;
}

.login-btn:hover, .reset-btn:hover, .create-account-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
}

.auth-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: all var(--transition-default);
}

.auth-link:hover {
    color: var(--primary-light);
}

.forgot-password-link {
    font-size: 14px;
    color: var(--primary);
    text-decoration: none;
    transition: all var(--transition-default);
}

.forgot-password-link:hover {
    color: var(--primary-light);
}

.verified-email-container {
    background-color: var(--primary-accent);
    border-radius: 8px;
    padding: 12px 16px;
    border-left: 3px solid var(--primary);
    margin-bottom: 20px;
    transition: all var(--transition-fast);
}

.verified-email-icon {
    width: 24px;
    height: 24px;
    background-color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.verified-email-icon i {
    color: var(--white);
    font-size: 12px;
}

.verified-email-info {
    display: flex;
    flex-direction: column;
}

.email-text {
    font-weight: var(--font-weight-medium);
    margin-bottom: 2px;
    color: var(--text-dark);
    font-size: 14px;
}

.change-email-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 12px;
    transition: all var(--transition-default);
}

.change-email-link:hover {
    color: var(--primary-light);
}

/* Registration specific styles */
.name-fields {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.name-field {
    flex: 1;
}

/* User Type Selection Step */
.user-type-selection {
    display: flex;
    gap: 16px;
    margin: 24px 0;
    flex-direction: column;
}

.user-type-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px;
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    background-color: var(--white);
    text-decoration: none;
    color: var(--text-dark);
    transition: all var(--transition-default);
    cursor: pointer;
    text-align: center;
    width: 100%;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.user-type-box:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
    text-decoration: none;
}

.user-type-box:hover .icon-container {
    background-color: rgba(44, 62, 80, 0.2);
}

.user-type-box:hover .icon-container i {
    color: var(--primary-dark);
}

.user-type-box h3 {
    margin: 16px 0 8px;
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    font-family: "Macan", "Helvetica Neue", Arial, sans-serif;
    letter-spacing: -0.02em;
    line-height: 1.4;
}

.icon-container {
    width: 48px;
    height: 48px;
    background-color: rgba(44, 62, 80, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    transition: all var(--transition-default);
}

.icon-container i {
    font-size: 20px;
    color: var(--primary);
    transition: all var(--transition-default);
}

/* Terms links */
.terms-link {
    color: var(--primary);
    text-decoration: none;
    transition: color var(--transition-default);
}

.terms-link:hover {
    color: var(--primary-light);
}

/* Utility classes */
.d-none {
    display: none !important;
}

.d-flex {
    display: flex !important;
}

.align-items-center {
    align-items: center !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

/* Auth footer styles update - with detection for longer forms */
.auth-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    padding: 20px 0;
    background-color: #ffffff;
    border-top: 1px solid #e9ecef;
    text-align: center;
    height: var(--footer-height);
    box-sizing: border-box;
    z-index: 10;
}

/* Loading overlay for authentication */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* For pages with longer registration forms, change footer positioning */
.register-page .auth-footer {
    position: relative;
    margin-top: 30px;
}

/* Responsive Styles */
@media (min-width: 768px) {
    .user-type-selection {
        flex-direction: row;
    }
    
    .user-type-box {
        flex: 1;
    }
    
    .social-buttons-container {
        gap: 8px;
    }
    
    .auth-content {
        padding: 4rem 0;
    }
}

@media (max-width: 576px) {
    .name-fields {
        flex-direction: column;
        gap: 12px;
    }
    
    .auth-form-container {
        padding: 40px 24px;
    }
    
    .social-buttons-container {
        flex-direction: column;
    }
    
    .social-btn {
        max-width: 100%;
    }
    
    .auth-content {
        padding: 2rem 0;
    }
}

/* Adjustments for tall screens */
@media (min-height: 900px) {
    .auth-content {
        min-height: calc(100vh - 80px - var(--footer-height));
        padding: 6rem 0;
    }
    
    .auth-form-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
}

/* Adjustments for short screens */
@media (max-height: 700px) {
    .auth-content {
        min-height: auto;
        padding: 2rem 0 calc(2rem + var(--footer-height));
    }
    
    .auth-form-container {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 32px 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordField = this.closest('.password-input-wrapper').querySelector('input');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form submission loading indicators
    const emailForm = document.getElementById('email-verification-form');
    const passwordForm = document.getElementById('password-form');
    const resetForm = document.getElementById('reset-form');
    
    if (emailForm) {
        emailForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('.login-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            
            btnText.textContent = 'Verifying...';
            btnLoader.classList.remove('d-none');
        });
    }
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('.login-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            
            btnText.textContent = 'Signing in...';
            btnLoader.classList.remove('d-none');
        });
    }
    
    if (resetForm) {
        resetForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('.reset-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            
            btnText.textContent = 'Sending...';
            btnLoader.classList.remove('d-none');
        });
    }
    
});

// Social Auth Popup Functions
/**
 * Opens the GitHub authentication popup window
 * @param {string} action - The authentication action (login or register)
 * @param {string} urlRoot - The URL root of the application
 */
function openGitHubAuth(action, urlRoot) {
    // Set popup dimensions and position it in the center
    const width = 600;
    const height = 700;
    const left = (window.innerWidth - width) / 2;
    const top = (window.innerHeight - height) / 2;
    
    // Create the URL for GitHub authentication
    const redirectUrl = `${urlRoot}/users/githubAuth?auth_action=${action}`;
    
    // Open the popup window
    const authWindow = window.open(
        redirectUrl, 
        'github-auth', 
        `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no`
    );
    
    // Focus the popup (may not work in all browsers due to security restrictions)
    if (authWindow) {
        authWindow.focus();
    } else {
        alert('Popup blocked. Please allow popups for this site to use social login.');
    }
}

/**
 * Opens the Google authentication popup window
 * @param {string} action - The authentication action (login or register)
 * @param {string} urlRoot - The URL root of the application
 */
function openGoogleAuth(action, urlRoot) {
    // Set popup dimensions and position it in the center
    const width = 600;
    const height = 700;
    const left = (window.innerWidth - width) / 2;
    const top = (window.innerHeight - height) / 2;
    
    // Create the URL for Google authentication
    const redirectUrl = `${urlRoot}/users/googleAuth?auth_action=${action}`;
    
    // Open the popup window
    const authWindow = window.open(
        redirectUrl, 
        'google-auth', 
        `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no`
    );
    
    // Focus the popup (may not work in all browsers due to security restrictions)
    if (authWindow) {
        authWindow.focus();
    } else {
        alert('Popup blocked. Please allow popups for this site to use social login.');
    }
}

/**
 * Sets up event listener for GitHub authentication response
 * @param {string} urlRoot - The URL root of the application
 */
function setupGitHubAuthListener(urlRoot) {
    // Add event listener for messages from the popup window
    window.addEventListener('message', function(event) {
        // Check if the message is from the GitHub auth popup
        if (event.data && event.data.source === 'github-auth') {
            console.log('Received GitHub auth message:', event.data);
            
            // Handle success or failure
            if (event.data.auth === 'success') {
                // Determine which action is being performed (login or register)
                const action = document.querySelector('.user-type-box.client-box') ? 'register' : 'login';
                
                // If registration requires completing profile
                if (event.data.redirect && event.data.redirect.includes('github_complete')) {
                    window.location.href = event.data.redirect;
                    return;
                }
                
                // For login or completed registration, make AJAX request to complete login
                if (event.data.email) {
                    const email = event.data.email;
                    // Show loading indicator
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.className = 'loading-overlay';
                    loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                    document.body.appendChild(loadingOverlay);
                    
                    // Make an AJAX request to complete the login process
                    fetch(`${urlRoot}/users/completeGithubLogin`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            email: email,
                            action: action
                        })
                    })
                    .then(response => {
                        // First check if the response can be parsed as JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, throw an error with the text response
                            return response.text().then(text => {
                                throw new Error('Received non-JSON response: ' + text);
                            });
                        }
                    })
                    .then(data => {
                        document.body.removeChild(loadingOverlay);
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Show error message
                            alert(data.message || 'Authentication failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        document.body.removeChild(loadingOverlay);
                        console.error('Error:', error);
                        alert('An error occurred during authentication. Please try again.');
                    });
                } else if (event.data.redirect) {
                    // Direct redirect if no email (fallback)
                    window.location.href = event.data.redirect;
                }
            } else if (event.data.auth === 'error') {
                // Show error message for authentication failure
                alert(event.data.message || 'GitHub authentication failed. Please try again.');
            }
        }
    });
}

/**
 * Sets up event listener for Google authentication response
 * @param {string} urlRoot - The URL root of the application
 */
function setupGoogleAuthListener(urlRoot) {
    // Add event listener for messages from the popup window
    window.addEventListener('message', function(event) {
        // Check if the message is from the Google auth popup
        if (event.data && event.data.source === 'google-auth') {
            console.log('Received Google auth message:', event.data);
            
            // Handle success or failure
            if (event.data.auth === 'success') {
                // Determine which action is being performed (login or register)
                const action = document.querySelector('.user-type-box.client-box') ? 'register' : 'login';
                
                // If registration requires completing profile
                if (event.data.redirect && event.data.redirect.includes('google_complete')) {
                    window.location.href = event.data.redirect;
                    return;
                }
                
                // For login or completed registration, make AJAX request to complete login
                if (event.data.email) {
                    const email = event.data.email;
                    // Show loading indicator
                    const loadingOverlay = document.createElement('div');
                    loadingOverlay.className = 'loading-overlay';
                    loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                    document.body.appendChild(loadingOverlay);
                    
                    // Make an AJAX request to complete the login process
                    fetch(`${urlRoot}/users/completeGoogleLogin`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            email: email,
                            action: action
                        })
                    })
                    .then(response => {
                        // First check if the response can be parsed as JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, throw an error with the text response
                            return response.text().then(text => {
                                throw new Error('Received non-JSON response: ' + text);
                            });
                        }
                    })
                    .then(data => {
                        document.body.removeChild(loadingOverlay);
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Show error message
                            alert(data.message || 'Authentication failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        document.body.removeChild(loadingOverlay);
                        console.error('Error:', error);
                        alert('An error occurred during authentication. Please try again.');
                    });
                } else if (event.data.redirect) {
                    // Direct redirect if no email (fallback)
                    window.location.href = event.data.redirect;
                }
            } else if (event.data.auth === 'error') {
                // Show error message for authentication failure
                alert(event.data.message || 'Google authentication failed. Please try again.');
            }
        }
    });
}
</script>
<?php require APPROOT . '/views/layouts/footer.php'; ?>