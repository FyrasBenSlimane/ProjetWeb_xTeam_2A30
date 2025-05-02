<?php require APPROOT . '/views/layouts/auth_header.php'; ?>

<div class="auth-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-form-container">
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
                                            placeholder="name@example.com" required>
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
                                <button class="auth-provider-btn social-btn">
                                    <i class="fab fa-google me-2"></i> Continue with Google
                                </button>
                                <button class="auth-provider-btn social-btn github-btn">
                                    <i class="fab fa-github me-2"></i> Continue with GitHub
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Password Form -->
                        <div id="step2-form" class="<?php echo (isset($_SESSION['login_email_verified']) ? '' : 'd-none'); ?>">
                            <?php if (isset($_SESSION['login_email_verified'])): ?>
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
                                            placeholder="Enter your password" required>
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
                        <?php if (!isset($_GET['type'])): ?>
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
                                <?php if (isset($_GET['type']) && $_GET['type'] == 'freelancer'): ?>
                                    <h2 class="mb-2">Sign up to find work you love</h2>
                                    <p class="text-muted">Join thousands of freelancers who find success on our platform</p>
                                <?php elseif (isset($_GET['type']) && $_GET['type'] == 'client'): ?>
                                    <h2 class="mb-2">Sign up to hire top talent</h2>
                                    <p class="text-muted">Access our global pool of professional freelancers</p>
                                <?php endif; ?>
                            </div>

                            <div class="auth-options mb-4">
                                <div class="social-buttons-container">
                                    <button class="auth-provider-btn social-btn">
                                        <i class="fab fa-apple me-2"></i> Continue with Apple
                                    </button>
                                    <button class="auth-provider-btn social-btn google-btn">
                                        <img src="https://www.google.com/favicon.ico" alt="Google" width="16" height="16" class="me-2">
                                        Continue with Google
                                    </button>
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
                                            placeholder="First name" required>
                                        <span class="invalid-feedback"><?php echo isset($data['first_name_err']) ? $data['first_name_err'] : ''; ?></span>
                                    </div>

                                    <div class="name-field">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input type="text" name="last_name" id="last_name"
                                            class="form-control <?php echo isset($data['last_name_err']) && !empty($data['last_name_err']) ? 'is-invalid' : ''; ?>"
                                            value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>"
                                            placeholder="Last name" required>
                                        <span class="invalid-feedback"><?php echo isset($data['last_name_err']) ? $data['last_name_err'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="reg_email" class="form-label">Email</label>
                                    <input type="email" name="email" id="reg_email"
                                        class="form-control <?php echo isset($data['email_err']) && !empty($data['email_err']) ? 'is-invalid' : ''; ?>"
                                        value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                                        placeholder="Email" required>
                                    <span class="invalid-feedback"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                                </div>

                                <div class="mb-3">
                                    <label for="reg_password" class="form-label">Password</label>
                                    <div class="password-input-wrapper">
                                        <input type="password" name="password" id="reg_password"
                                            class="form-control <?php echo isset($data['password_err']) && !empty($data['password_err']) ? 'is-invalid' : ''; ?>"
                                            placeholder="Password (8 or more characters)" required>
                                        <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                                </div>

                                <div class="mb-4">
                                    <label for="country" class="form-label">Country</label>
                                    <select name="country" id="country" class="form-select" required>
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
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
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
                                        placeholder="name@example.com" required>
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
    /* Auth pages shared styles */
    :root {
        --ws-mobile-x: 16px;
        --ws-4x: 16px;
        --ws-6x: 24px;
        --ws-8x: 32px;
        --bg-body: #ffffff;
        --text-dark-on-body: #14171f;
        --font-weight-base: 400;
        --letter-spacing-base: normal;
        --font-size-base: 16px;
        --font-family-base: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;

        /* Updated color palette to match darker blue (almost black with blue tint) theme */
        --primary: #0a1128;
        /* Very dark blue/almost black */
        --primary-light: #1c2541;
        --primary-dark: #050914;
        --primary-accent: #e6e9f0;

        /* Secondary colors */
        --secondary: #121a29;
        /* Dark navy/near black */
        --secondary-light: #1d2b3f;
        --secondary-dark: #0a0f17;
        --secondary-accent: #eaecf1;

        /* Accent colors */
        --accent-purple: #273469;
        --accent-pink: #1f2b50;
        --accent-orange: #0d315b;

        /* Neutrals */
        --white: #ffffff;
        --text-dark: #222325;
        --gray-medium: #74767e;
        --gray-light: #e4e5e7;
        --gray-lighter: #fafafa;
        --gray-dark: #404145;
    }

    body {
        background-color: var(--bg-body);
        color: var(--text-dark-on-body);
        font-family: var(--font-family-base), "neue-montreal-fallback", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: var(--font-size-base);
        font-weight: var(--font-weight-base);
        letter-spacing: var(--letter-spacing-base);
        line-height: 1.428571429;
        margin: 0;
    }

    .auth-content {
        padding: 1rem 0;
        background-color: var(--bg-body);
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
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
    }

    @media (min-width: 700px) {
        .container {
            --container-ws-x: var(--ws-8x);
        }
    }

    .auth-form-container {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 1.5rem;
        border: none;
        box-shadow: none;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Form Elements */
    .divider-text {
        position: relative;
        text-align: center;
        margin: 20px 0;
        color: #5e6d55;
    }

    .divider-text:before,
    .divider-text:after {
        content: "";
        position: absolute;
        width: 45%;
        background-color: #e4e5e7;
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
        padding: 0 10px;
        background-color: #ffffff;
        position: relative;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
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
        padding: 0 1rem;
    }

    .social-buttons-container {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin: 0 auto;
        max-width: 450px;
    }

    .social-btn {
        font-size: 15px;
        padding: 10px 16px;
        flex: 1;
        max-width: 220px;
        border-radius: 30px;
    }

    .auth-provider-btn {
        border: 1px solid #e4e5e7;
        background-color: #ffffff;
        color: #14171f;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-provider-btn:hover {
        background-color: #f9f9f9;
        border-color: #c4c5c9;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #14171f;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e4e5e7;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus,
    .form-control:hover,
    .form-select:hover {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 8px rgba(44, 62, 80, 0.3);
    }

    .form-control.is-invalid {
        border-color: #e74c3c;
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

    .password-input-wrapper .icon-left+.form-control {
        padding-left: 45px;
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #656565;
        cursor: pointer;
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
        color: #5e6d55;
    }

    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        padding: 12px 24px;
        font-size: 16px;
        line-height: 1.5;
        border-radius: 8px;
        transition: all 0.15s ease-in-out;
    }

    .btn-primary {
        background-color: var(--primary);
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .d-grid {
        display: grid;
    }

    .login-btn,
    .reset-btn,
    .create-account-btn {
        width: 45%;
        padding: 10px 18px;
        font-size: 16px;
        font-weight: 600;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .auth-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .auth-link:hover {
        text-decoration: underline;
    }

    .forgot-password-link {
        font-size: 14px;
        color: var(--primary);
        text-decoration: none;
    }

    .forgot-password-link:hover {
        text-decoration: underline;
    }

    .verified-email-container {
        background-color: var(--gray-lighter);
        border-radius: 8px;
        padding: 12px 16px;
        border-left: 3px solid var(--primary);
        margin-bottom: 20px;
    }

    .verified-email-icon {
        width: 28px;
        height: 28px;
        background-color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .verified-email-icon i {
        color: #ffffff;
        font-size: 14px;
    }

    .verified-email-info {
        display: flex;
        flex-direction: column;
    }

    .email-text {
        font-weight: 500;
        margin-bottom: 2px;
    }

    .change-email-link {
        color: var(--primary);
        text-decoration: none;
    }

    .change-email-link:hover {
        text-decoration: underline;
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
        gap: 20px;
        margin: 25px 0;
        flex-direction: column;
    }

    .user-type-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 20px;
        border: 2px solid #e4e5e7;
        border-radius: 12px;
        text-decoration: none;
        color: #14171f;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        width: 100%;
    }

    .user-type-box:hover {
        border-color: #000000;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .user-type-box h3 {
        margin: 16px 0 8px;
        font-size: 18px;
        font-weight: 600;
    }

    .icon-container {
        width: 60px;
        height: 60px;
        background-color: #f9f9f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .icon-container i {
        font-size: 24px;
        color: var(--primary);
    }

    .client-box .icon-container {
        background-color: rgba(44, 62, 80, 0.08);
    }

    .freelancer-box .icon-container {
        background-color: rgba(44, 62, 80, 0.15);
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

    /* Responsive Styles */
    @media (min-width: 768px) {
        .user-type-selection {
            flex-direction: row;
        }

        .user-type-box {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .name-fields {
            flex-direction: column;
        }

        .login-btn,
        .reset-btn,
        .create-account-btn {
            width: 70%;
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
</script>

<?php require APPROOT . '/views/layouts/auth_footer.php'; ?>