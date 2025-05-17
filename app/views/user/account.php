<?php
/**
 * User Account Page
 * Combined profile and settings page with tabs for different sections
 */

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    redirect('users/auth?action=login');
}

// No need to get user information from $data as it's already provided by the controller
// The controller fetches the complete user information from the database
$userInfo = [
    'id' => $data['user']->id,
    'name' => $data['user']->name,
    'email' => $data['user']->email,
    'avatar' => $data['user']->profile_image ?? URL_ROOT . '/public/img/default-avatar.png',
    'role' => $data['user']->account_type,
    'hourly_rate' => $data['user']->hourly_rate ? '$' . number_format($data['user']->hourly_rate, 2) . '/hr' : '$40.00/hr',
    'bio' => $data['user']->bio ?? 'No bio provided yet.',
    'skills' => $data['skills'] ?? [],
    'location' => $data['user']->location ?? 'Not specified',
    'joined_date' => date('F Y', strtotime($data['user']->created_at ?? 'now')),
    'social_links' => $data['social_links'] ?? [
        'website' => $data['user']->website ?? '',
        'linkedin' => $data['user']->linkedin ?? '',
        'github' => $data['user']->github ?? '',
        'twitter' => $data['user']->twitter ?? '',
    ],
    'notifications' => $data['notifications'] ?? [
        'email_updates' => $data['user']->email_updates ?? true,
        'message_alerts' => $data['user']->message_alerts ?? true,
        'job_recommendations' => $data['user']->job_recommendations ?? true,
        'marketing_emails' => $data['user']->marketing_emails ?? false,
    ],
    'language' => $data['language'] ?? $data['user']->language ?? 'en',
    'timezone' => $data['timezone'] ?? $data['user']->timezone ?? 'UTC',
    'currency' => $data['currency'] ?? $data['user']->currency ?? 'USD',
    'country' => $data['user']->country ?? '',
];
?>

<style>
    /* ShadCN UI inspired styles with modern enhancements */
    :root {
        /* Font variables */
        --font-primary: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
        --font-size-base: 15px;
        --font-size-sm: 14px;
        --font-size-xs: 13px;
        --font-weight-base: 400;
        --font-weight-medium: 500;
        --font-weight-semibold: 600;
        --font-weight-bold: 700;
        --line-height-base: 1.5;

        /* Enhanced color palette */
        --primary: #2c3e50;
        --primary-light: #34495e;
        --primary-hover: #1a252f;
        --primary-dark: #1a252f;
        --primary-focus: rgba(44, 62, 80, 0.08);
        --primary-accent: #ecf0f1;
        
        /* Secondary colors */
        --secondary: #222325;
        --secondary-light: #404145;
        --secondary-dark: #0e0e10;
        --secondary-accent: #f1f1f2;
        
        /* Surface colors */
        --surface: #ffffff;
        --surface-hover: #f8f9fa;
        --surface-active: #f1f3f5;
        --surface-border: #e9ecef;
        
        /* Text colors */
        --text-primary: #2c3e50;
        --text-secondary: #62646a;
        --text-muted: #95979d;
        
        /* Status colors */
        --success: #28a745;
        --success-light: #d4edda;
        --warning: #ffc107;
        --warning-light: #fff3cd;
        --danger: #dc3545;
        --danger-light: #f8d7da;
        --info: #17a2b8;
        --info-light: #d1ecf1;
        
        /* Spacing */
        --spacing-xs: 0.25rem;
        --spacing-sm: 0.5rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
        
        /* Border radius */
        --radius-sm: 4px;
        --radius: 6px;
        --radius-lg: 8px;
        --radius-xl: 12px;
        --radius-full: 9999px;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        
        /* Transitions */
        --transition-base: all 0.2s ease-in-out;
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: var(--font-primary);
        color: var(--text-primary);
    }

    h1, h2, h3, h4, h5, h6 {
        font-weight: var(--font-weight-semibold);
    }

    /* Enhanced Card Styles */
    .card {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        transition: var(--transition-smooth);
        background: var(--surface);
        overflow: hidden;
    }

    .card:hover {
        box-shadow: var(--shadow-md);
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid var(--surface-border);
        padding: var(--spacing-lg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin: 0;
    }

    .card-body {
        padding: var(--spacing-lg);
    }

    /* Profile Card Enhancements */
    .profile-card {
        position: relative;
        overflow: visible;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        z-index: 0;
    }

    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
        z-index: 1;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border: 4px solid var(--surface);
        box-shadow: var(--shadow-lg);
        transition: var(--transition-smooth);
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-full);
        background: var(--primary);
        color: white;
        border: 2px solid var(--surface);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-smooth);
        opacity: 0;
    }

    .avatar-container:hover .avatar-edit-btn {
        opacity: 1;
        transform: translateY(-4px);
    }

    .profile-name {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin: var(--spacing-md) 0 var(--spacing-xs);
    }

    .profile-role {
        font-size: var(--font-size-sm);
        color: var(--text-secondary);
    }

    .profile-rate {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--primary);
        background: var(--primary-accent);
        padding: var(--spacing-xs) var(--spacing-md);
        border-radius: var(--radius-full);
        display: inline-block;
        margin: var(--spacing-sm) 0;
    }

    /* Enhanced Tab Navigation */
    .nav-tabs {
        border: none;
        gap: var(--spacing-sm);
        padding: 0 var(--spacing-sm);
        margin-bottom: var(--spacing-xl);
    }

    .nav-tabs .nav-link {
        border: none;
        padding: var(--spacing-md) var(--spacing-lg);
        color: var(--text-secondary);
        font-weight: var(--font-weight-medium);
        border-radius: var(--radius);
        transition: var(--transition-smooth);
        position: relative;
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .nav-tabs .nav-link i {
        font-size: 1.1rem;
        opacity: 0.8;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary);
        background: var(--primary-focus);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary);
        background: var(--primary-focus);
        font-weight: var(--font-weight-semibold);
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background: var(--primary);
        border-radius: var(--radius-full);
    }

    /* Form Controls Enhancement */
    .form-control, .form-select {
        border: 1px solid var(--surface-border);
        border-radius: var(--radius);
        padding: 0.75rem 1rem;
        font-size: var(--font-size-base);
        transition: var(--transition-smooth);
        background-color: var(--surface);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-focus);
    }

    .form-label {
        font-weight: var(--font-weight-medium);
        color: var(--text-primary);
        margin-bottom: var(--spacing-xs);
    }

    .form-text {
        color: var(--text-muted);
        font-size: var(--font-size-sm);
        margin-top: var(--spacing-xs);
    }

    /* Button Enhancements */
    .btn {
        padding: 0.75rem 1.5rem;
        font-weight: var(--font-weight-medium);
        border-radius: var(--radius);
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-sm);
    }

    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-1px);
    }

    /* Switch Enhancement */
    .form-switch {
        padding-left: 3rem;
    }

    .form-switch .form-check-input {
        width: 2.5rem;
        height: 1.25rem;
        margin-left: -3rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(0,0,0,0.25)'/%3e%3c/svg%3e");
        background-position: left center;
        border-radius: 2rem;
        transition: var(--transition-smooth);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    /* Social Links Enhancement */
    .social-links {
        display: flex;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-sm);
    }

    .social-link {
        width: 40px;
        height: 40px;
        border-radius: var(--radius);
        background: var(--surface-hover);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-smooth);
        font-size: 1.1rem;
    }

    .social-link:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Icon Circle Enhancement */
    .icon-circle {
        width: 44px;
        height: 44px;
        border-radius: var(--radius);
        background: var(--surface-hover);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: var(--transition-smooth);
    }

    .icon-circle:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Danger Zone Enhancement */
    .card-danger {
        border: 1px solid var(--danger-light);
    }

    .card-danger .card-header {
        background: var(--danger-light);
        color: var(--danger);
    }

    .card-danger .card-header h5 {
        color: var(--danger);
    }

    /* Modal Enhancements */
    .modal-content {
        border: none;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        border-bottom: 1px solid var(--surface-border);
        padding: var(--spacing-lg);
    }

    .modal-body {
        padding: var(--spacing-lg);
    }

    .modal-footer {
        border-top: 1px solid var(--surface-border);
        padding: var(--spacing-lg);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .nav-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: var(--spacing-sm);
            -webkit-overflow-scrolling: touch;
        }
        
        .nav-tabs .nav-link {
            white-space: nowrap;
        }
        
        .profile-card::before {
            height: 100px;
        }
        
        .avatar-container {
            width: 100px;
            height: 100px;
        }
    }
</style>

<!-- Page Header -->
<div class="container-xl mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URL_ROOT; ?>" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Your Account</li>
                </ol>
            </nav>
            <h1 class="h3 fw-semibold">Account Management</h1>
            <p class="text-muted">View and manage your profile information and account settings</p>
        </div>
    </div>
</div>

<!-- Account Content -->
<div class="container-xl mb-5">
    <div class="row">
        <!-- Sidebar with profile card -->
        <div class="col-lg-3 mb-4">
            <div class="card profile-card">
                <div class="card-body text-center py-4">
                    <div class="avatar-container mb-4">
                        <img src="<?php echo $userInfo['avatar']; ?>" alt="<?php echo $userInfo['name']; ?>" class="rounded-circle profile-avatar">
                        <button class="btn btn-sm avatar-edit-btn" data-bs-toggle="modal" data-bs-target="#avatarModal">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h5 class="profile-name" data-inline-edit data-field="name"><?php echo $userInfo['name']; ?></h5>
                    <p class="profile-role mb-1"><span class="badge bg-light text-dark rounded-pill"><?php echo ucfirst($userInfo['role']); ?></span></p>
                    <?php if($userInfo['role'] == 'freelancer'): ?>
                    <p class="profile-rate" data-inline-edit data-field="hourly_rate" data-value="<?php echo str_replace(['$', '/hr'], '', $userInfo['hourly_rate']); ?>" data-type="number"><?php echo $userInfo['hourly_rate']; ?></p>
                    <?php endif; ?>
                    <p class="text-muted small mb-0 mt-3"><i class="fas fa-calendar-alt me-1"></i> Member since <?php echo $userInfo['joined_date']; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Main Content with tabs -->
        <div class="col-lg-9">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="fas fa-user me-2"></i>Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="false">
                        <i class="fas fa-cog me-2"></i>Account
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">
                        <i class="fas fa-shield-alt me-2"></i>Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </button>
                </li>
            </ul>
            
            <!-- Tabs Content -->
            <div class="tab-content" id="accountTabsContent">
                <!-- Profile Tab Content -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- About Me Card -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">About Me</h5>
                            <button class="btn btn-sm btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <p data-inline-edit data-field="bio" data-type="textarea" class="mb-0"><?php echo $userInfo['bio']; ?></p>
                        </div>
                    </div>
                    
                    <!-- Skills & Experience -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">Skills & Expertise</h5>
                            <button class="btn btn-sm btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#skillsModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($userInfo['skills'])): ?>
                                <div class="skills-container">
                                    <?php foreach($userInfo['skills'] as $skill): ?>
                                        <span class="skill-badge">
                                            <?php echo $skill; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <p class="text-muted">No skills added yet</p>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#skillsModal">
                                            <i class="fas fa-plus me-1"></i> Add Your Skills
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Contact & Social Links -->
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">Contact & Social</h5>
                            <button class="btn btn-sm btn-icon text-primary" data-bs-toggle="modal" data-bs-target="#socialModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-light me-3">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Location</div>
                                            <div data-inline-edit data-field="location"><?php echo $userInfo['location']; ?></div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-light me-3">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Email</div>
                                            <div><?php echo $userInfo['email']; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-2 text-muted small">Connect with me</div>
                                    <div class="social-links">
                                        <?php if(!empty($userInfo['social_links']['website'])): ?>
                                        <a href="<?php echo $userInfo['social_links']['website']; ?>" target="_blank" class="social-link" data-bs-toggle="tooltip" title="Website">
                                            <i class="fas fa-globe"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($userInfo['social_links']['linkedin'])): ?>
                                        <a href="<?php echo $userInfo['social_links']['linkedin']; ?>" target="_blank" class="social-link" data-bs-toggle="tooltip" title="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($userInfo['social_links']['github'])): ?>
                                        <a href="<?php echo $userInfo['social_links']['github']; ?>" target="_blank" class="social-link" data-bs-toggle="tooltip" title="GitHub">
                                            <i class="fab fa-github"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($userInfo['social_links']['twitter'])): ?>
                                        <a href="<?php echo $userInfo['social_links']['twitter']; ?>" target="_blank" class="social-link" data-bs-toggle="tooltip" title="Twitter">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if(empty($userInfo['social_links']['website']) && empty($userInfo['social_links']['linkedin']) && empty($userInfo['social_links']['github']) && empty($userInfo['social_links']['twitter'])): ?>
                                        <div class="text-muted small fst-italic">No social links added</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account Tab Content -->
                <div class="tab-pane fade" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <!-- Account Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Account Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo URL_ROOT; ?>/user/update-account" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $userInfo['name']; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $userInfo['email']; ?>">
                                    <div class="form-text">
                                        This email will be used for account related notifications and communications.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="country">
                                        <option value="" disabled <?php echo empty($userInfo['country']) ? 'selected' : ''; ?>>Select your country</option>
                                        <option value="United States" <?php echo $userInfo['country'] == 'United States' ? 'selected' : ''; ?>>United States</option>
                                        <option value="United Kingdom" <?php echo $userInfo['country'] == 'United Kingdom' ? 'selected' : ''; ?>>United Kingdom</option>
                                        <option value="Canada" <?php echo $userInfo['country'] == 'Canada' ? 'selected' : ''; ?>>Canada</option>
                                        <option value="Australia" <?php echo $userInfo['country'] == 'Australia' ? 'selected' : ''; ?>>Australia</option>
                                        <option value="Germany" <?php echo $userInfo['country'] == 'Germany' ? 'selected' : ''; ?>>Germany</option>
                                        <option value="France" <?php echo $userInfo['country'] == 'France' ? 'selected' : ''; ?>>France</option>
                                        <option value="India" <?php echo $userInfo['country'] == 'India' ? 'selected' : ''; ?>>India</option>
                                        <option value="Brazil" <?php echo $userInfo['country'] == 'Brazil' ? 'selected' : ''; ?>>Brazil</option>
                                        <option value="Japan" <?php echo $userInfo['country'] == 'Japan' ? 'selected' : ''; ?>>Japan</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Account Type</label>
                                    <input type="text" class="form-control" value="<?php echo ucfirst($userInfo['role']); ?>" readonly>
                                    <div class="form-text">
                                        Your account type cannot be changed. If you need to switch account types, please contact support.
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Regional Preferences -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Regional Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo URL_ROOT; ?>/user/update-preferences" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <select class="form-select" id="language" name="language">
                                            <option value="en" <?php echo $userInfo['language'] == 'en' ? 'selected' : ''; ?>>English</option>
                                            <option value="es" <?php echo $userInfo['language'] == 'es' ? 'selected' : ''; ?>>Spanish</option>
                                            <option value="fr" <?php echo $userInfo['language'] == 'fr' ? 'selected' : ''; ?>>French</option>
                                            <option value="de" <?php echo $userInfo['language'] == 'de' ? 'selected' : ''; ?>>German</option>
                                            <option value="pt" <?php echo $userInfo['language'] == 'pt' ? 'selected' : ''; ?>>Portuguese</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="timezone" class="form-label">Timezone</label>
                                        <select class="form-select" id="timezone" name="timezone">
                                            <option value="UTC" <?php echo $userInfo['timezone'] == 'UTC' ? 'selected' : ''; ?>>UTC (Coordinated Universal Time)</option>
                                            <option value="America/New_York" <?php echo $userInfo['timezone'] == 'America/New_York' ? 'selected' : ''; ?>>Eastern Time (US & Canada)</option>
                                            <option value="America/Chicago" <?php echo $userInfo['timezone'] == 'America/Chicago' ? 'selected' : ''; ?>>Central Time (US & Canada)</option>
                                            <option value="America/Denver" <?php echo $userInfo['timezone'] == 'America/Denver' ? 'selected' : ''; ?>>Mountain Time (US & Canada)</option>
                                            <option value="America/Los_Angeles" <?php echo $userInfo['timezone'] == 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time (US & Canada)</option>
                                            <option value="Europe/London" <?php echo $userInfo['timezone'] == 'Europe/London' ? 'selected' : ''; ?>>London</option>
                                            <option value="Europe/Paris" <?php echo $userInfo['timezone'] == 'Europe/Paris' ? 'selected' : ''; ?>>Paris</option>
                                            <option value="Asia/Tokyo" <?php echo $userInfo['timezone'] == 'Asia/Tokyo' ? 'selected' : ''; ?>>Tokyo</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select class="form-select" id="currency" name="currency">
                                            <option value="USD" <?php echo $userInfo['currency'] == 'USD' ? 'selected' : ''; ?>>USD - US Dollar</option>
                                            <option value="EUR" <?php echo $userInfo['currency'] == 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                            <option value="GBP" <?php echo $userInfo['currency'] == 'GBP' ? 'selected' : ''; ?>>GBP - British Pound</option>
                                            <option value="CAD" <?php echo $userInfo['currency'] == 'CAD' ? 'selected' : ''; ?>>CAD - Canadian Dollar</option>
                                            <option value="AUD" <?php echo $userInfo['currency'] == 'AUD' ? 'selected' : ''; ?>>AUD - Australian Dollar</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Preferences</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Danger Zone -->
                    <div class="card border-danger">
                        <div class="card-header bg-danger bg-opacity-10 text-danger">
                            <h5 class="mb-0">Danger Zone</h5>
                        </div>
                        <div class="card-body">
                            <p>Deactivating your account will temporarily hide your profile and all your activity. You can reactivate at any time.</p>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                Deactivate Account
                            </button>
                            
                            <hr class="my-4">
                            
                            <p>Permanently deleting your account will remove all your data and cannot be undone.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete Account Permanently
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Security Tab Content -->
                <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                    <!-- Change Password -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo URL_ROOT; ?>/user/update-password" method="POST">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" oninput="updatePasswordStrength(this.value)">
                                    <div class="mt-2">
                                        <div class="progress" style="height: 6px;">
                                            <div id="passwordStrength" class="progress-bar bg-danger" role="progressbar" 
                                                 style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="form-text">
                                        Password must be at least 8 characters long and include numbers, lowercase and uppercase letters.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Two-Factor Authentication -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Two-Factor Authentication</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <p class="mb-0">Two-factor authentication adds an extra layer of security to your account</p>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="twoFactorSwitch">
                                </div>
                            </div>
                            <button id="setup2fa" class="btn btn-outline-primary" disabled>
                                Setup Two-Factor Authentication
                            </button>
                        </div>
                    </div>
                    
                    <!-- Login Sessions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Active Sessions</h5>
                        </div>
                        <div class="card-body">
                            <p>These are devices that have logged into your account. Revoke any sessions that you don't recognize.</p>
                            
                            <div class="session-item d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-laptop text-muted me-3"></i>
                                        <div>
                                            <strong>Current Device</strong>
                                            <div class="text-muted small">
                                                <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
                                            </div>
                                            <div class="badge bg-success mt-1">Active Now</div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" disabled>Current</button>
                            </div>
                            
                            <button class="btn btn-danger mt-2">Sign Out Of All Other Devices</button>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications Tab Content -->
                <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Notification Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo URL_ROOT; ?>/user/update-notifications" method="POST">
                                <div class="mb-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="emailUpdates" name="email_updates" 
                                            <?php echo $userInfo['notifications']['email_updates'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="emailUpdates">
                                            Email Updates
                                            <div class="text-muted small">Receive updates about your account and activity</div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="messageAlerts" name="message_alerts"
                                            <?php echo $userInfo['notifications']['message_alerts'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="messageAlerts">
                                            Message Alerts
                                            <div class="text-muted small">Get notified when you receive new messages</div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="jobRecommendations" name="job_recommendations"
                                            <?php echo $userInfo['notifications']['job_recommendations'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="jobRecommendations">
                                            Job Recommendations
                                            <div class="text-muted small">Get personalized job recommendations based on your profile</div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="marketingEmails" name="marketing_emails"
                                            <?php echo $userInfo['notifications']['marketing_emails'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="marketingEmails">
                                            Marketing Emails
                                            <div class="text-muted small">Receive marketing communications and special offers</div>
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Preferences</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Edit Your Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/update-profile" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $userInfo['name']; ?>">
                    </div>
                    
                    <?php if($userInfo['role'] == 'freelancer'): ?>
                    <div class="mb-3">
                        <label for="hourly_rate" class="form-label">Hourly Rate</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" 
                                value="<?php echo str_replace(['$', '/hr'], '', $userInfo['hourly_rate']); ?>" 
                                step="0.01" min="0">
                            <span class="input-group-text">/hr</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $userInfo['location']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="bio" class="form-label">About Me</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo $userInfo['bio']; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Skills Modal -->
<div class="modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skillsModalLabel">Edit Skills</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/update-skills" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skills" class="form-label">Skills (comma separated)</label>
                        <input type="text" class="form-control" id="skills" name="skills" 
                            value="<?php echo implode(', ', $userInfo['skills']); ?>"
                            placeholder="e.g. Web Development, JavaScript, PHP, UI Design">
                        <div class="form-text">Add skills that showcase your expertise to potential clients</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Skills</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Social Links Modal -->
<div class="modal fade" id="socialModal" tabindex="-1" aria-labelledby="socialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialModalLabel">Edit Social Links</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/update-social" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            <input type="url" class="form-control" id="website" name="website" 
                                value="<?php echo $userInfo['social_links']['website']; ?>"
                                placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">LinkedIn</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                            <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                value="<?php echo $userInfo['social_links']['linkedin']; ?>"
                                placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="github" class="form-label">GitHub</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-github"></i></span>
                            <input type="url" class="form-control" id="github" name="github" 
                                value="<?php echo $userInfo['social_links']['github']; ?>"
                                placeholder="https://github.com/username">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="twitter" class="form-label">Twitter</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                            <input type="url" class="form-control" id="twitter" name="twitter" 
                                value="<?php echo $userInfo['social_links']['twitter']; ?>"
                                placeholder="https://twitter.com/username">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Links</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Avatar Upload Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Update Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/update-avatar" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img src="<?php echo $userInfo['avatar']; ?>" alt="Current Avatar" id="avatar-preview" 
                            class="rounded-circle avatar-preview">
                    </div>
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Select New Profile Picture</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        <div class="form-text">
                            Recommended size: 400x400 pixels. Max file size: 2MB.<br>
                            Supported formats: JPG, PNG, GIF
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deactivate Account Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateModalLabel">Deactivate Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/deactivate-account" method="POST">
                <div class="modal-body">
                    <p>Are you sure you want to deactivate your account? This will:</p>
                    <ul>
                        <li>Hide your profile from other users</li>
                        <li>Temporarily pause ongoing contracts</li>
                        <li>Suspend your ability to apply for jobs</li>
                    </ul>
                    <p>You can reactivate your account at any time by signing in again.</p>
                    <div class="mb-3">
                        <label for="deactivateReason" class="form-label">Please tell us why you're leaving:</label>
                        <select class="form-select" id="deactivateReason" name="deactivate_reason">
                            <option value="">Select a reason...</option>
                            <option value="taking_break">I'm taking a break</option>
                            <option value="not_finding_work">Not finding suitable work</option>
                            <option value="found_job">Found a full-time job</option>
                            <option value="too_many_emails">Too many emails/notifications</option>
                            <option value="other">Other reason</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Deactivate Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Delete Account Permanently</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URL_ROOT; ?>/user/delete-account" method="POST">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action is irreversible and will permanently delete all your data.
                    </div>
                    <p>By deleting your account:</p>
                    <ul>
                        <li>Your profile, work history, and all personal data will be permanently removed</li>
                        <li>All ongoing contracts will be terminated</li>
                        <li>You will lose access to all messages and files</li>
                        <li>Your account cannot be recovered</li>
                    </ul>
                    <div class="mb-3">
                        <label for="password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" name="confirm_delete" required>
                        <label class="form-check-label" for="confirmDelete">
                            I understand that this action is permanent and cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="deleteAccountBtn" disabled>Delete Account Permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar preview
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        // Enable/disable 2FA setup button based on switch
        const twoFactorSwitch = document.getElementById('twoFactorSwitch');
        const setup2fa = document.getElementById('setup2fa');
        
        if (twoFactorSwitch && setup2fa) {
            twoFactorSwitch.addEventListener('change', function() {
                setup2fa.disabled = !this.checked;
            });
        }
        
        // Enable/disable delete account button
        const confirmDelete = document.getElementById('confirmDelete');
        const deleteAccountBtn = document.getElementById('deleteAccountBtn');
        
        if (confirmDelete && deleteAccountBtn) {
            confirmDelete.addEventListener('change', function() {
                deleteAccountBtn.disabled = !this.checked;
            });
        }

        // Preserve active tab on page refresh using URL hash
        const hash = window.location.hash;
        if (hash) {
            const tabElement = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (tabElement) {
                tabElement.click();
            }
        }

        // Update URL hash when tab changes
        const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('data-bs-target');
                history.replaceState(null, null, targetId);
            });
        });

        // Add inline editing functionality
        setupInlineEditing();

        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Add real-time validation
        setupFormValidation();
    });

    /**
     * Show password strength meter
     */
    function updatePasswordStrength(password) {
        const strengthMeter = document.getElementById('passwordStrength');
        if (!strengthMeter) return;
        
        // Calculate password strength
        let strength = 0;
        
        // Length factor
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        
        // Character variety factors
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
        
        // Set the meter width and color based on strength
        let meterClass = '';
        switch (true) {
            case (strength <= 2):
                meterClass = 'bg-danger';
                strengthMeter.setAttribute('aria-valuenow', '25');
                strengthMeter.style.width = '25%';
                break;
            case (strength <= 4):
                meterClass = 'bg-warning';
                strengthMeter.setAttribute('aria-valuenow', '50');
                strengthMeter.style.width = '50%';
                break;
            case (strength <= 5):
                meterClass = 'bg-info';
                strengthMeter.setAttribute('aria-valuenow', '75');
                strengthMeter.style.width = '75%';
                break;
            case (strength >= 6):
                meterClass = 'bg-success';
                strengthMeter.setAttribute('aria-valuenow', '100');
                strengthMeter.style.width = '100%';
                break;
        }
        
        // Update the meter class
        strengthMeter.className = 'progress-bar ' + meterClass;
    }
    
    /**
     * Set up inline editing for profile fields
     */
    function setupInlineEditing() {
        // Add editable class and data attributes to inline-editable elements
        document.querySelectorAll('[data-inline-edit]').forEach(element => {
            element.classList.add('editable');
            element.setAttribute('title', 'Click to edit');
            
            // Add click event listener
            element.addEventListener('click', function(e) {
                e.preventDefault();
                const field = this.getAttribute('data-field');
                const value = this.getAttribute('data-value') || this.innerText.trim();
                const type = this.getAttribute('data-type') || 'text';
                
                startEditing(this, field, value, type);
            });
        });
    }
    
    /**
     * Start inline editing for a field
     */
    function startEditing(element, field, value, type) {
        // Create input element
        let input;
        
        if (type === 'textarea') {
            input = document.createElement('textarea');
            input.rows = 3;
            input.className = 'form-control inline-edit-input animate__animated animate__fadeIn';
        } else {
            input = document.createElement('input');
            input.type = type;
            input.className = 'form-control inline-edit-input animate__animated animate__fadeIn';
            
            // Add specific attributes based on field type
            if (type === 'number') {
                input.step = field === 'hourly_rate' ? '0.01' : '1';
                input.min = '0';
            }
        }
        
        // Set common attributes
        input.value = value;
        input.dataset.field = field;
        input.dataset.originalValue = value;
        
        // Replace the element with the input
        element.style.display = 'none';
        element.parentNode.insertBefore(input, element.nextSibling);
        
        // Focus the input
        input.focus();
        
        // Add event listeners for save and cancel
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && type !== 'textarea') {
                e.preventDefault();
                saveEdit(input, element);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                cancelEdit(input, element);
            }
        });
        
        input.addEventListener('blur', function() {
            saveEdit(input, element);
        });
        
        // For textareas, add a save button
        if (type === 'textarea') {
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'edit-buttons animate__animated animate__fadeIn';
            
            const saveBtn = document.createElement('button');
            saveBtn.className = 'btn btn-sm btn-primary mt-2 me-2';
            saveBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save';
            saveBtn.addEventListener('click', function() {
                saveEdit(input, element);
            });
            
            const cancelBtn = document.createElement('button');
            cancelBtn.className = 'btn btn-sm btn-outline-secondary mt-2';
            cancelBtn.innerHTML = '<i class="fas fa-times me-1"></i> Cancel';
            cancelBtn.addEventListener('click', function() {
                cancelEdit(input, element);
            });
            
            buttonContainer.appendChild(saveBtn);
            buttonContainer.appendChild(cancelBtn);
            
            input.parentNode.insertBefore(buttonContainer, input.nextSibling);
        }
    }
    
    /**
     * Save the edited value
     */
    function saveEdit(input, element) {
        const field = input.dataset.field;
        const newValue = input.value.trim();
        const originalValue = input.dataset.originalValue;
        
        // Remove any existing inline edit buttons
        const btnContainer = input.parentNode.querySelector('.edit-buttons');
        if (btnContainer) {
            btnContainer.remove();
        }
        
        // Check if value is empty
        if (newValue === '') {
            // Show error message
            const errorMsg = document.createElement('div');
            errorMsg.className = 'invalid-feedback';
            errorMsg.textContent = 'This field cannot be empty';
            errorMsg.style.display = 'block';
            input.classList.add('is-invalid');
            input.parentNode.appendChild(errorMsg);
            
            // Keep focus on the input
            input.focus();
            return;
        }
        
        // If value hasn't changed, just cancel
        if (newValue === originalValue) {
            cancelEdit(input, element);
            return;
        }
        
        // Show loading state
        element.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
        element.style.display = '';
        input.remove();
        
        // Send AJAX request to update the field
        fetch('<?php echo URL_ROOT; ?>/user/updateField', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                field: field,
                value: newValue
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the element with the new value
                if (field === 'hourly_rate') {
                    element.textContent = '$' + parseFloat(newValue).toFixed(2) + '/hr';
                } else {
                    element.textContent = newValue;
                }
                
                // Update data-value attribute
                element.setAttribute('data-value', newValue);
                
                // Show success indicator
                element.classList.add('edit-success');
                setTimeout(() => {
                    element.classList.remove('edit-success');
                }, 1500);
                
                // Show toast notification
                showToast('Field updated successfully', 'success');
            } else {
                // Show error and revert to original value
                element.textContent = originalValue;
                element.classList.add('edit-error');
                setTimeout(() => {
                    element.classList.remove('edit-error');
                }, 1500);
                
                // Show toast notification
                showToast('Error updating field: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            element.textContent = originalValue;
            element.classList.add('edit-error');
            setTimeout(() => {
                element.classList.remove('edit-error');
            }, 1500);
            
            // Show toast notification
            showToast('Error updating field. Please try again.', 'error');
        });
    }
    
    /**
     * Cancel editing and restore the original element
     */
    function cancelEdit(input, element) {
        // Remove any existing inline edit buttons
        const btnContainer = input.parentNode.querySelector('.edit-buttons');
        if (btnContainer) {
            btnContainer.remove();
        }
        
        // Remove the input and restore the original element
        input.remove();
        element.style.display = '';
    }
    
    /**
     * Set up form validation for all forms on the page
     */
    function setupFormValidation() {
        // Get all forms that need validation
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Add validation classes to required inputs
            form.querySelectorAll('input[required], textarea[required], select[required]').forEach(input => {
                input.classList.add('needs-validation');
            });
            
            // Add real-time validation on input
            form.querySelectorAll('input, textarea, select').forEach(input => {
                input.addEventListener('input', function() {
                    validateField(this);
                });
                
                // Also validate on blur
                input.addEventListener('blur', function() {
                    validateField(this);
                });
            });
            
            // Prevent form submission if validation fails
            form.addEventListener('submit', function(e) {
                // Force validate each field
                form.querySelectorAll('input, textarea, select').forEach(field => {
                    // Mark important fields as required
                    if (field.name === 'name' || field.name === 'email' || 
                        field.id === 'bio' || field.id === 'current_password' || 
                        field.id === 'new_password' || field.id === 'confirm_password') {
                        field.required = true;
                    }
                    validateField(field);
                });
                
                if (!validateForm(this)) {
                    e.preventDefault();
                    // Show error toast
                    showToast('Please fill in all required fields correctly', 'error');
                }
            });
        });
    }
    
    /**
     * Validate a single field
     */
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        // Remove existing validation classes
        field.classList.remove('is-invalid', 'is-valid');
        
        // Skip validation if field is not required and empty
        if (!field.required && !value) {
            return true;
        }
        
        // Required field validation
        if ((field.required || field.name === 'name' || field.name === 'email' || 
             field.id === 'bio' || field.id === 'current_password' || 
             field.id === 'new_password' || field.id === 'confirm_password') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
        }
        
        // URL validation for social links
        if (field.type === 'url' && value) {
            try {
                new URL(value);
            } catch (e) {
                isValid = false;
                errorMessage = 'Please enter a valid URL';
            }
        }
        
        // Password validation
        if (field.type === 'password' && value) {
            if (field.id === 'new_password') {
                // Check password strength
                const hasLower = /[a-z]/.test(value);
                const hasUpper = /[A-Z]/.test(value);
                const hasNumber = /[0-9]/.test(value);
                const hasMinLength = value.length >= 8;
                
                if (!hasLower || !hasUpper || !hasNumber || !hasMinLength) {
                    isValid = false;
                    errorMessage = 'Password must be at least 8 characters and include uppercase, lowercase, and numbers';
                }
            } else if (field.id === 'confirm_password') {
                const newPassword = document.getElementById('new_password')?.value;
                if (newPassword && value !== newPassword) {
                    isValid = false;
                    errorMessage = 'Passwords do not match';
                }
            }
        }
        
        // Number validation for hourly rate
        if (field.name === 'hourly_rate' && value) {
            const rate = parseFloat(value);
            if (isNaN(rate) || rate < 0) {
                isValid = false;
                errorMessage = 'Please enter a valid hourly rate';
            }
        }
        
        // Update field appearance
        if (isValid) {
            field.classList.add('is-valid');
            // Remove any existing error message
            const errorElement = field.parentElement.querySelector('.invalid-feedback');
            if (errorElement) {
                errorElement.remove();
            }
        } else {
            field.classList.add('is-invalid');
            // Add or update error message
            let errorElement = field.parentElement.querySelector('.invalid-feedback');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                field.parentElement.appendChild(errorElement);
            }
            errorElement.textContent = errorMessage;
            
            // Make error message visible
            errorElement.style.display = 'block';
        }
        
        return isValid;
    }
    
    /**
     * Validate an entire form
     */
    function validateForm(form) {
        let isValid = true;
        let firstInvalidField = null;
        
        // Validate all fields
        form.querySelectorAll('input, textarea, select').forEach(field => {
            if (!validateField(field)) {
                isValid = false;
                
                // Store the first invalid field to focus on it
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            }
        });
        
        // Focus the first invalid field
        if (firstInvalidField) {
            firstInvalidField.focus();
            
            // Scroll to the field if it's not in view
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return isValid;
    }
    
    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toastEl = document.createElement('div');
        toastEl.id = toastId;
        toastEl.className = `toast align-items-center border-0 ${type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info'}`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        // Create toast content
        const flexDiv = document.createElement('div');
        flexDiv.className = 'd-flex';
        
        const toastBody = document.createElement('div');
        toastBody.className = 'toast-body text-white';
        toastBody.textContent = message;
        
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'btn-close btn-close-white me-2 m-auto';
        closeButton.setAttribute('data-bs-dismiss', 'toast');
        closeButton.setAttribute('aria-label', 'Close');
        
        // Assemble the toast
        flexDiv.appendChild(toastBody);
        flexDiv.appendChild(closeButton);
        toastEl.appendChild(flexDiv);
        
        // Add toast to container
        toastContainer.appendChild(toastEl);
        
        // Initialize Bootstrap toast and show it
        const toast = new bootstrap.Toast(toastEl, {
            delay: 3000,
            animation: true
        });
        
        toast.show();
        
        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }
</script>

<style>
    /* Additional CSS for inline editing */
    .editable {
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
        position: relative;
        display: inline-block;
    }
    
    .editable:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .editable::after {
        content: '✎';
        font-size: 12px;
        margin-left: 5px;
        opacity: 0.5;
        color: var(--primary);
    }
    
    .editable:hover::after {
        opacity: 1;
    }
    
    .inline-edit-input {
        margin-bottom: 10px;
    }
    
    .edit-buttons {
        display: flex;
        gap: 8px;
    }
    
    .edit-success {
        animation: successPulse 1.5s ease;
    }
    
    .edit-error {
        animation: errorPulse 1.5s ease;
    }
    
    @keyframes successPulse {
        0% { background-color: transparent; }
        30% { background-color: rgba(40, 167, 69, 0.2); }
        100% { background-color: transparent; }
    }
    
    @keyframes errorPulse {
        0% { background-color: transparent; }
        30% { background-color: rgba(220, 53, 69, 0.2); }
        100% { background-color: transparent; }
    }
    
    /* Avatar preview styles */
    .avatar-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid var(--white);
        box-shadow: var(--shadow-md);
        border-radius: 50%;
    }
    
    /* Social links */
    .social-links {
        display: flex;
        gap: 10px;
    }
    
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--surface-hover);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .social-link:hover {
        background-color: var(--primary);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Icon circle */
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--surface-hover);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        transition: var(--transition-smooth);
    }
    
    .icon-circle:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Empty state */
    .empty-state {
        padding: 1.5rem;
        text-align: center;
    }
    
    .empty-state-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: var(--surface-hover);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: var(--text-secondary);
        font-size: 1.25rem;
    }
</style>    