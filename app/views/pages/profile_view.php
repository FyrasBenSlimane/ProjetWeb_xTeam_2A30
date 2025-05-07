<?php
// Profile view page that displays the user profile
// Based on the Upwork profile design
?>

<!-- Back button -->
<div class="back-to-dashboard">
    <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="profile-page-container">
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                <?php 
                    $nameArray = explode(' ', $_SESSION['user_name']);
                    $initials = '';
                    if(isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                    if(isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                    echo strtoupper($initials);
                ?>
            </div>
            <div class="profile-details">
                <div class="profile-name-location">
                    <h1><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User Name'; ?></h1>
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo isset($_SESSION['user_location']) ? htmlspecialchars($_SESSION['user_location']) : 'Location not set'; ?>
                    </div>
                </div>
                <div class="profile-actions">
                    <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>?page=profile" class="btn-outline">See public view</a>
                    <a href="<?php echo URL_ROOT; ?>/pages/<?php echo strtolower($_SESSION['user_account_type']); ?>?page=settings" class="btn-primary">Profile settings</a>
                </div>
            </div>
            <div class="profile-share">
                <button class="share-btn"><i class="fas fa-share-square"></i> Share</button>
            </div>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-main">
            <div class="profile-section">
                <div class="section-header">
                    <h2>Professional Title</h2>
                    <button class="edit-btn"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="section-content">
                    <p class="professional-title">
                        <?php echo isset($data['professional_title']) ? htmlspecialchars($data['professional_title']) : 'Add your professional title'; ?>
                    </p>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h2>About Me</h2>
                    <button class="edit-btn"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="section-content">
                    <p class="about-text">
                        <?php 
                        if(isset($data['user_bio']) && !empty($data['user_bio'])) {
                            echo htmlspecialchars($data['user_bio']);
                        } else {
                            echo 'Hello, I\'m a professional with expertise in my field. I\'m passionate about delivering high-quality work and exceeding client expectations.';
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h2>Portfolio</h2>
                    <button class="add-btn"><i class="fas fa-plus"></i></button>
                </div>
                <div class="section-content portfolio-content">
                    <?php if(empty($data['portfolio_items'])): ?>
                    <div class="empty-portfolio">
                        <div class="empty-portfolio-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>Add a project</h3>
                        <p>Talent are hired 9x more often if they've published a portfolio.</p>
                    </div>
                    <?php else: ?>
                        <div class="portfolio-grid">
                            <?php foreach($data['portfolio_items'] as $item): ?>
                            <div class="portfolio-item">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                                <div class="portfolio-item-details">
                                    <h3><?php echo $item['title']; ?></h3>
                                    <p><?php echo $item['description']; ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h2>Work History</h2>
                </div>
                <div class="section-content">
                    <?php if(empty($data['work_history'])): ?>
                    <div class="empty-history">
                        <p>No work history yet. Start bidding on projects to build your reputation.</p>
                    </div>
                    <?php else: ?>
                        <div class="work-history-list">
                            <?php foreach($data['work_history'] as $job): ?>
                            <div class="work-item">
                                <div class="work-details">
                                    <h3><?php echo $job['title']; ?></h3>
                                    <div class="work-meta">
                                        <span><i class="fas fa-star"></i> <?php echo $job['rating']; ?></span>
                                        <span><?php echo $job['date']; ?></span>
                                        <span><?php echo $job['price']; ?></span>
                                    </div>
                                    <p><?php echo $job['description']; ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-sidebar">
            <div class="sidebar-section">
                <div class="section-header">
                    <h3>Profile Completion</h3>
                </div>
                <div class="section-content">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo isset($data['profile_completion']) ? $data['profile_completion'] . '%' : '40%'; ?>"></div>
                    </div>
                    <p class="progress-text"><?php echo isset($data['profile_completion']) ? $data['profile_completion'] : '40'; ?>% complete</p>
                </div>
            </div>

            <div class="sidebar-section connects-section">
                <div class="section-header">
                    <h3>Connects</h3>
                </div>
                <div class="section-content">
                    <div class="connects-count">
                        <span class="count">110</span>
                        <span class="label">Available</span>
                    </div>
                    <div class="connects-buttons">
                        <a href="#" class="btn btn-primary">Get Free Connects</a>
                        <a href="#" class="btn btn-outline">Buy Connects</a>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="section-header">
                    <h3>Video introduction</h3>
                </div>
                <div class="section-content empty-section">
                    <div class="empty-state">
                        <i class="fas fa-video"></i>
                        <a href="#" class="add-link">Add video</a>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="section-header">
                    <h3>Hours per week</h3>
                    <button class="edit-btn small"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="section-content">
                    <p>More than 30 hrs/week</p>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="section-header">
                    <h3>Languages</h3>
                    <button class="add-btn small"><i class="fas fa-plus"></i></button>
                </div>
                <div class="section-content">
                    <div class="language-item">
                        <p class="language">English</p>
                        <p class="language-level">Fluent</p>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="section-header">
                    <h3>Education</h3>
                    <button class="add-btn small"><i class="fas fa-plus"></i></button>
                </div>
                <div class="section-content empty-section">
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <a href="#" class="add-link">Add education</a>
                    </div>
                </div>
            </div>
        </div>
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

/* Existing profile page styles */
:root {
    --primary-color: #2c3e50;  /* Changed from green to blue */
    --primary-hover: #34495e;  /* Changed from green to darker blue */
    --secondary-color: #1a252f;  /* Changed from dark green to dark blue */
    --text-color: #222325;
    --text-muted: #6c757d;
    --border-color: #e4e5e7;  /* Adjusted for blue theme */
    --background-light: #f5f7fa;  /* Adjusted for blue theme */
    --box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
    --border-radius: 8px;
}

body {
    background-color: #f2f5fa;  /* Changed from light green to light blue-gray */
    font-family: "Poppins", Arial, sans-serif;
    color: var(--text-color);
    margin: 0;
}

/* Profile Container */
.profile-page-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
}

/* Profile Header */
.profile-header {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 24px;
    margin-bottom: 24px;
}

.profile-header-content {
    display: flex;
    align-items: center;
    gap: 24px;
    width: 100%;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #b0c4de;  /* Changed from light green to light blue */
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 36px;
    text-transform: uppercase;
    border: 2px solid rgba(44, 62, 80, 0.2);  /* Changed from green to blue */
    flex-shrink: 0;
}

.profile-details {
    flex-grow: 1;
}

.profile-name-location h1 {
    font-size: 26px;
    font-weight: 600;
    color: var(--secondary-color);
    margin: 0 0 8px 0;
}

.location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 16px;
}

.profile-actions {
    display: flex;
    gap: 12px;
}

.btn-primary, .btn-outline {
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 500;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    transition: var(--transition);
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
}

.btn-outline {
    border: 1px solid var(--border-color);
    color: var(--text-color);
    background-color: transparent;
}

.btn-outline:hover {
    border-color: var(--text-color);
    transform: translateY(-2px);
}

.profile-share {
    margin-left: auto;
}

.share-btn {
    background: none;
    border: none;
    color: var(--primary-color);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.share-btn:hover {
    color: var(--primary-hover);
}

/* Profile Content */
.profile-content {
    display: flex;
    gap: 24px;
}

.profile-main {
    flex: 2;
}

.profile-sidebar {
    flex: 1;
}

/* Section Styles */
.profile-section, .sidebar-section {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 24px;
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}

.section-header h2, .section-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: var(--secondary-color);
}

.section-header h3 {
    font-size: 16px;
}

.edit-btn, .add-btn {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 16px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.edit-btn:hover, .add-btn:hover {
    background-color: var(--background-light);
    color: var(--secondary-color);
}

.edit-btn.small, .add-btn.small {
    width: 24px;
    height: 24px;
    font-size: 12px;
}

.section-content {
    padding: 24px;
}

/* Professional Title Section */
.professional-title {
    font-size: 20px;
    color: var(--secondary-color);
    font-weight: 500;
    margin: 0;
}

/* About Text Section */
.about-text {
    font-size: 16px;
    line-height: 1.6;
    color: var(--text-color);
}

/* Portfolio Section */
.portfolio-content {
    padding: 0;
}

.empty-portfolio {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
    text-align: center;
}

.empty-portfolio-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background-color: var(--background-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: var(--text-muted);
    margin-bottom: 16px;
}

.empty-portfolio h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.empty-portfolio p {
    color: var(--text-muted);
    font-size: 14px;
    max-width: 300px;
    margin: 0;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    padding: 24px;
}

.portfolio-item {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--box-shadow);
}

.portfolio-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.portfolio-item-details {
    padding: 12px;
}

.portfolio-item-details h3 {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: var(--secondary-color);
}

.portfolio-item-details p {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
}

/* Work History Section */
.empty-history {
    color: var(--text-muted);
    font-size: 14px;
}

.work-history-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.work-item {
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.work-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.work-item h3 {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: var(--secondary-color);
}

.work-meta {
    display: flex;
    gap: 16px;
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 8px;
}

.work-meta i {
    color: #f4b400;
}

/* Sidebar Sections */
.progress-bar {
    height: 8px;
    background-color: var(--border-color);
    border-radius: 4px;
    margin-bottom: 8px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: var(--primary-color);
    border-radius: 4px;
}

.progress-text {
    font-size: 14px;
    color: var(--text-muted);
    margin: 0;
    text-align: right;
}

/* Connects Section */
.connects-section {
    background-color: #f5f7fa;  /* Changed from light green to light blue */
    border: 1px solid var(--border-color);
}

.connects-count {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 16px;
}

.count {
    font-size: 28px;
    font-weight: 600;
    color: var(--secondary-color);
}

.label {
    font-size: 14px;
    color: var(--text-muted);
}

.connects-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn {
    padding: 10px 0;
    border-radius: 20px;
    font-weight: 500;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    width: 100%;
    transition: var(--transition);
}

/* Empty Sections */
.empty-section {
    padding: 16px 24px;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 16px;
    text-align: center;
}

.empty-state i {
    font-size: 24px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.add-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 14px;
    transition: var(--transition);
}

.add-link:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

/* Language Section */
.language-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.language {
    font-size: 14px;
    margin: 0;
}

.language-level {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .profile-share {
        margin: 16px 0 0 0;
        align-self: flex-end;
    }
    
    .profile-content {
        flex-direction: column;
    }
    
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .profile-actions {
        flex-direction: column;
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit button clicks to toggle editable content
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const section = this.closest('.profile-section, .sidebar-section');
                const content = section.querySelector('.section-content');
                
                // Get the editable element within the section content
                const editableElem = content.querySelector('p:not(.empty-section)');
                
                if (editableElem) {
                    const currentText = editableElem.textContent.trim();
                    
                    // Create input based on type of section
                    const input = document.createElement('textarea');
                    input.value = currentText;
                    input.className = 'editable-input';
                    input.rows = 3;
                    input.style.width = '100%';
                    input.style.padding = '8px';
                    input.style.borderRadius = '4px';
                    input.style.border = '1px solid var(--border-color)';
                    
                    // Replace paragraph with input
                    editableElem.replaceWith(input);
                    input.focus();
                    
                    // Add save button
                    const saveBtn = document.createElement('button');
                    saveBtn.textContent = 'Save';
                    saveBtn.className = 'save-btn';
                    saveBtn.style.backgroundColor = 'var(--primary-color)';
                    saveBtn.style.color = 'white';
                    saveBtn.style.border = 'none';
                    saveBtn.style.borderRadius = '4px';
                    saveBtn.style.padding = '8px 16px';
                    saveBtn.style.marginTop = '8px';
                    saveBtn.style.cursor = 'pointer';
                    
                    // Add cancel button
                    const cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Cancel';
                    cancelBtn.className = 'cancel-btn';
                    cancelBtn.style.backgroundColor = 'transparent';
                    cancelBtn.style.border = '1px solid var(--border-color)';
                    cancelBtn.style.borderRadius = '4px';
                    cancelBtn.style.padding = '8px 16px';
                    cancelBtn.style.marginTop = '8px';
                    cancelBtn.style.marginLeft = '8px';
                    cancelBtn.style.cursor = 'pointer';
                    
                    const buttonContainer = document.createElement('div');
                    buttonContainer.appendChild(saveBtn);
                    buttonContainer.appendChild(cancelBtn);
                    content.appendChild(buttonContainer);
                    
                    // Handle cancel
                    cancelBtn.addEventListener('click', function() {
                        const paragraph = document.createElement('p');
                        paragraph.textContent = currentText;
                        paragraph.className = editableElem.className;
                        input.replaceWith(paragraph);
                        buttonContainer.remove();
                    });
                    
                    // Handle save
                    saveBtn.addEventListener('click', function() {
                        const newValue = input.value.trim();
                        const paragraph = document.createElement('p');
                        paragraph.textContent = newValue || 'No information provided';
                        paragraph.className = editableElem.className;
                        input.replaceWith(paragraph);
                        buttonContainer.remove();
                        
                        // Here you would typically call an AJAX function to save to server
                        console.log('Value saved:', newValue);
                    });
                }
            });
        });
        
        // Handle add buttons
        document.querySelectorAll('.add-btn').forEach(button => {
            button.addEventListener('click', function() {
                const section = this.closest('.profile-section, .sidebar-section');
                const sectionType = section.querySelector('.section-header h2, .section-header h3').textContent;
                
                alert(`Add new ${sectionType} functionality would open a modal here`);
            });
        });
    });
</script>