<?php
// Profile settings page - based on the Upwork settings design
?>

<!-- Back button -->
<div class="back-to-dashboard">
    <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="settings-container">
    <div class="settings-sidebar">
        <h2 class="settings-title">Settings</h2>
        
        <div class="settings-section">
            <h3>Billing</h3>
            <div class="settings-link-container">
                <a href="#" class="settings-link">Billing & Payments</a>
            </div>
        </div>
        
        <div class="settings-section">
            <h3>User Settings</h3>
            <div class="settings-link-container">
                <a href="#" class="settings-link">Membership & Connects</a>
                <a href="#" class="settings-link">Contact Info</a>
                <a href="#" class="settings-link">My Profile</a>
                <a href="#" class="settings-link active">Profile Settings</a>
                <a href="#" class="settings-link">Get Paid</a>
                <a href="#" class="settings-link">My Teams</a>
                <a href="#" class="settings-link">Connected Services</a>
                <a href="#" class="settings-link">Password & Security</a>
                <a href="#" class="settings-link">Notification Settings</a>
            </div>
        </div>
    </div>
    
    <div class="settings-content">
        <form action="<?php echo URL_ROOT; ?>/users/updateProfile" method="POST" class="settings-form">
            <div class="settings-panel">
                <h2 class="panel-title">My profile</h2>
                
                <div class="panel-right">
                    <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>?page=profile" class="view-profile-link">View my profile as others see it</a>
                </div>
            </div>
            
            <div class="settings-panel">
                <h3 class="panel-section-title">Visibility</h3>
                <div class="form-group">
                    <select name="profile_visibility" class="form-control">
                        <option value="public" <?php echo (isset($data['profile_visibility']) && $data['profile_visibility'] == 'public') ? 'selected' : ''; ?>>Public</option>
                        <option value="private" <?php echo (isset($data['profile_visibility']) && $data['profile_visibility'] == 'private') ? 'selected' : ''; ?>>Private</option>
                    </select>
                </div>
            </div>
            
            <div class="settings-panel">
                <h3 class="panel-section-title">Project preference <i class="fas fa-info-circle help-icon"></i></h3>
                <div class="form-group">
                    <select name="project_preference" class="form-control">
                        <option value="hourly" selected>Hourly projects</option>
                        <option value="fixed">Fixed-price projects</option>
                        <option value="both">Both hourly and fixed-price projects</option>
                    </select>
                </div>
            </div>
            
            <div class="settings-panel">
                <h3 class="panel-section-title">Earnings privacy <i class="fas fa-info-circle help-icon"></i></h3>
                <div class="earnings-privacy">
                    <p>Want to keep your earnings private?</p>
                    <p class="upgrade-text">
                        <a href="#" class="upgrade-link">Upgrade to a Freelancer Plus membership</a> to enable this setting.
                    </p>
                </div>
            </div>
            
            <div class="settings-panel">
                <h3 class="panel-section-title">Experience level</h3>
                
                <div class="experience-options">
                    <div class="experience-option">
                        <input type="radio" id="entry_level" name="experience_level" value="entry" <?php echo (!isset($data['experience_level']) || $data['experience_level'] == 'entry') ? 'checked' : ''; ?>>
                        <div class="experience-details">
                            <label for="entry_level">Entry level</label>
                            <p>I am relatively new to this field</p>
                        </div>
                    </div>
                    
                    <div class="experience-option">
                        <input type="radio" id="intermediate" name="experience_level" value="intermediate" <?php echo (isset($data['experience_level']) && $data['experience_level'] == 'intermediate') ? 'checked' : ''; ?>>
                        <div class="experience-details">
                            <label for="intermediate">Intermediate</label>
                            <p>I have substantial experience in this field</p>
                        </div>
                    </div>
                    
                    <div class="experience-option">
                        <input type="radio" id="expert" name="experience_level" value="expert" <?php echo (isset($data['experience_level']) && $data['experience_level'] == 'expert') ? 'checked' : ''; ?>>
                        <div class="experience-details">
                            <label for="expert">Expert</label>
                            <p>I have comprehensive and deep expertise in this field</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="settings-panel">
                <h3 class="panel-section-title">Categories <a href="#" class="edit-icon"><i class="fas fa-pencil-alt"></i></a></h3>
                
                <div class="categories-display">
                    <h4>Web, Mobile & Software Dev</h4>
                    <p class="category-badge">Scripts & Utilities</p>
                </div>
            </div>
            
            <!-- Basic user information -->
            <input type="hidden" name="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
            <input type="hidden" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
            <input type="hidden" name="account_type" value="<?php echo isset($data['account_type']) ? $data['account_type'] : ''; ?>">
            <input type="hidden" name="bio" value="<?php echo isset($data['bio']) ? $data['bio'] : ''; ?>">
            <input type="hidden" name="professional_title" value="<?php echo isset($data['professional_title']) ? $data['professional_title'] : ''; ?>">
            <input type="hidden" name="hourly_rate" value="<?php echo isset($data['hourly_rate']) ? $data['hourly_rate'] : 0; ?>">
            <input type="hidden" name="hours_per_week" value="<?php echo isset($data['hours_per_week']) ? $data['hours_per_week'] : ''; ?>">
            <input type="hidden" name="categories" value="Web, Mobile & Software Dev">
            <input type="hidden" name="location" value="<?php echo isset($data['location']) ? $data['location'] : ''; ?>">
            
            <div class="form-actions">
                <button type="submit" class="btn btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Back button styling */
.back-to-dashboard {
    max-width: 1200px;
    margin: 20px auto 0;
    padding: 0 15px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 15px;
    transition: color 0.2s ease;
}

.back-btn i {
    margin-right: 6px;
}

.back-btn:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

:root {
    --primary-color: #2c3e50;  /* Changed from green to blue */
    --primary-hover: #34495e;  /* Changed from green to darker blue */
    --secondary-color: #1a252f;  /* Darker blue for secondary color */
    --text-color: #222325;
    --text-muted: #6c757d;
    --border-color: #e4e5e7;  /* Adjusted for blue theme */
    --background-light: #f5f7fa;  /* Adjusted for blue theme */
    --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
    --border-radius: 8px;
}

body {
    background-color: #f2f5fa;  /* Adjusted to a light blue-gray background */
    font-family: "Poppins", Arial, sans-serif;
    color: var(--text-color);
    margin: 0;
}

/* Settings Container */
.settings-container {
    max-width: 1200px;
    margin: 20px auto;
    display: flex;
    gap: 24px;
    padding: 0 15px;
}

/* Settings Sidebar */
.settings-sidebar {
    width: 280px;
    flex-shrink: 0;
}

.settings-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--secondary-color);
    margin: 0 0 24px 0;
}

.settings-section {
    margin-bottom: 24px;
}

.settings-section h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--secondary-color);
    margin: 0 0 12px 0;
}

.settings-link-container {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.settings-link {
    display: block;
    padding: 8px 12px;
    font-size: 14px;
    color: var(--text-color);
    text-decoration: none;
    border-radius: 4px;
    transition: var(--transition);
}

.settings-link:hover {
    background-color: var(--background-light);
}

.settings-link.active {
    color: var(--primary-color);
    font-weight: 600;
    border-left: 3px solid var(--primary-color);
    background-color: var(--background-light);
}

/* Settings Content */
.settings-content {
    flex-grow: 1;
}

.settings-form {
    width: 100%;
}

.settings-panel {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 24px;
    padding: 24px;
    position: relative;
}

.panel-title {
    font-size: 24px;
    font-weight: 600;
    color: var(--secondary-color);
    margin: 0;
}

.panel-right {
    position: absolute;
    top: 24px;
    right: 24px;
}

.view-profile-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.view-profile-link:hover {
    text-decoration: underline;
}

.panel-section-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--secondary-color);
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.help-icon {
    color: var(--text-muted);
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    max-width: 400px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    font-size: 14px;
    color: var(--text-color);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
}

.earnings-privacy {
    margin-top: 12px;
}

.upgrade-text {
    margin-top: 8px;
    font-size: 14px;
}

.upgrade-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.upgrade-link:hover {
    text-decoration: underline;
}

/* Experience Options */
.experience-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 600px;
}

.experience-option {
    display: flex;
    gap: 16px;
    padding: 16px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition);
}

.experience-option:hover {
    border-color: var(--primary-color);
    background-color: #f9fafc;  /* Adjusted for blue theme */
}

.experience-option input[type="radio"] {
    margin-top: 4px;
}

.experience-details {
    flex-grow: 1;
}

.experience-details label {
    display: block;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--secondary-color);
}

.experience-details p {
    font-size: 14px;
    margin: 0;
    color: var(--text-muted);
}

/* Categories */
.edit-icon {
    color: var(--text-muted);
    margin-left: auto;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 50%;
}

.edit-icon:hover {
    background-color: var(--background-light);
    color: var(--primary-color);
}

.categories-display h4 {
    font-size: 15px;
    font-weight: 600;
    color: var(--secondary-color);
    margin: 0 0 8px 0;
}

.category-badge {
    display: inline-block;
    background-color: #f2f5f2;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 13px;
    color: var(--text-color);
    margin: 0;
}

/* Form Actions */
.form-actions {
    margin-top: 32px;
    margin-bottom: 16px;
}

.btn-save {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 24px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.btn-save:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-container {
        flex-direction: column;
    }
    
    .settings-sidebar {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make the experience option selections more user-friendly
    const experienceOptions = document.querySelectorAll('.experience-option');
    
    experienceOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Find the radio input inside this option and check it
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Apply selected styling
            experienceOptions.forEach(opt => {
                if (opt.querySelector('input[type="radio"]').checked) {
                    opt.style.borderColor = 'var(--primary-color)';
                    opt.style.backgroundColor = '#f9fafc';
                } else {
                    opt.style.borderColor = 'var(--border-color)';
                    opt.style.backgroundColor = 'white';
                }
            });
        });
        
        // Apply initial styling based on checked state
        if (option.querySelector('input[type="radio"]').checked) {
            option.style.borderColor = 'var(--primary-color)';
            option.style.backgroundColor = '#f9fafc';
        }
    });
});
</script>