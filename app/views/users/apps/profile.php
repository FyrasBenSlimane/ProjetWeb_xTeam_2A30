<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="freelancer-indicator">
    <div class="indicator-badge">
        <i class="fas fa-laptop-code"></i> Freelancer Account
    </div>
</div>

<main class="main-container">
    <section class="page-header-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title"><?php echo $data['title']; ?></h1>
                    <p class="page-description"><?php echo $data['description']; ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-section py-4">
        <div class="container">
            <div class="row">
                <!-- Profile Sidebar -->
                <div class="col-lg-4 mb-4">
                    <div class="profile-sidebar">
                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <div class="avatar-placeholder">
                                        <?php
                                        $nameArray = explode(' ', $_SESSION['user_name']);
                                        $initials = '';
                                        if (isset($nameArray[0])) $initials .= substr($nameArray[0], 0, 1);
                                        if (isset($nameArray[1])) $initials .= substr($nameArray[1], 0, 1);
                                        ?>
                                        <span><?php echo $initials; ?></span>
                                    </div>
                                    <button class="change-photo-btn">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                                <h3 class="profile-name"><?php echo $_SESSION['user_name']; ?></h3>
                                <p class="profile-headline">Web Developer & UI Designer</p>
                                <div class="profile-rating">
                                    <div class="stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <span class="rating-value">4.8</span>
                                    <span class="rating-count">(17 reviews)</span>
                                </div>
                            </div>
                            <div class="profile-actions">
                                <button class="btn-profile-action view-public">
                                    <i class="fas fa-eye"></i> View Public Profile
                                </button>
                                <button class="btn-profile-action profile-settings">
                                    <i class="fas fa-cog"></i> Profile Settings
                                </button>
                            </div>
                            <div class="profile-completion">
                                <div class="completion-text">
                                    <span>Profile Completion</span>
                                    <span class="completion-percentage">75%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="profile-tips">
                                <h6>Complete your profile</h6>
                                <ul class="tips-list">
                                    <li><i class="fas fa-check-circle completed"></i> Add a profile photo</li>
                                    <li><i class="fas fa-check-circle completed"></i> Write your bio</li>
                                    <li><i class="fas fa-check-circle completed"></i> Add your skills</li>
                                    <li><i class="fas fa-circle incomplete"></i> Add portfolio items</li>
                                    <li><i class="fas fa-circle incomplete"></i> Complete education details</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="col-lg-8">
                    <div class="profile-tabs-container">
                        <ul class="nav nav-pills profile-nav mb-4" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                                    <i class="fas fa-user"></i> Overview
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="portfolio-tab" data-bs-toggle="pill" data-bs-target="#portfolio" type="button" role="tab" aria-controls="portfolio" aria-selected="false">
                                    <i class="fas fa-briefcase"></i> Portfolio
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="skills-tab" data-bs-toggle="pill" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">
                                    <i class="fas fa-code"></i> Skills
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="education-tab" data-bs-toggle="pill" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">
                                    <i class="fas fa-graduation-cap"></i> Education
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabContent">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>About Me</h5>
                                        <button class="btn-edit-section"><i class="fas fa-pencil-alt"></i></button>
                                    </div>
                                    <div class="card-body">
                                        <p class="about-text">
                                            I'm a passionate web developer with over 5 years of experience creating responsive websites and web applications. I specialize in front-end development with a strong focus on user experience and modern design patterns. My goal is to build digital products that are both beautiful and functional.
                                        </p>
                                        <p class="about-text">
                                            When I'm not coding, you might find me hiking in the mountains or experimenting with new cooking recipes. I believe in continuous learning and try to stay updated with the latest technologies and design trends.
                                        </p>
                                    </div>
                                </div>

                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>Experience</h5>
                                        <button class="btn-edit-section"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <div class="card-body">
                                        <div class="experience-item">
                                            <div class="experience-header">
                                                <h6>Senior Web Developer</h6>
                                                <div class="experience-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <p class="company-name">Tech Solutions Inc.</p>
                                            <p class="experience-date">March 2020 - Present</p>
                                            <p class="experience-description">
                                                Leading front-end development for enterprise clients, managing a team of junior developers, and implementing modern web technologies like React and Vue.js.
                                            </p>
                                        </div>

                                        <div class="experience-item">
                                            <div class="experience-header">
                                                <h6>Web Developer</h6>
                                                <div class="experience-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <p class="company-name">Creative Digital Agency</p>
                                            <p class="experience-date">June 2018 - February 2020</p>
                                            <p class="experience-description">
                                                Developed responsive websites for clients across various industries using HTML, CSS, JavaScript, and PHP. Collaborated with designers to implement pixel-perfect interfaces.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>Languages</h5>
                                        <button class="btn-edit-section"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <div class="card-body">
                                        <div class="language-item">
                                            <div class="language-info">
                                                <span class="language-name">English</span>
                                                <span class="language-level">Fluent</span>
                                            </div>
                                            <div class="language-actions">
                                                <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                        <div class="language-item">
                                            <div class="language-info">
                                                <span class="language-name">Spanish</span>
                                                <span class="language-level">Intermediate</span>
                                            </div>
                                            <div class="language-actions">
                                                <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Portfolio Tab -->
                            <div class="tab-pane fade" id="portfolio" role="tabpanel" aria-labelledby="portfolio-tab">
                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>Portfolio Items</h5>
                                        <button class="btn-add-portfolio"><i class="fas fa-plus"></i> Add Project</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="portfolio-empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <h6>No portfolio items yet</h6>
                                            <p>Show off your best work by adding projects to your portfolio</p>
                                            <button class="btn-add-first-project">Add Your First Project</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills Tab -->
                            <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>Skills</h5>
                                        <button class="btn-add-skill"><i class="fas fa-plus"></i> Add Skill</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="skills-container">
                                            <div class="skill-item">
                                                <div class="skill-info">
                                                    <span class="skill-name">HTML5</span>
                                                    <span class="skill-level">Expert</span>
                                                </div>
                                                <div class="skill-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="skill-item">
                                                <div class="skill-info">
                                                    <span class="skill-name">CSS3</span>
                                                    <span class="skill-level">Expert</span>
                                                </div>
                                                <div class="skill-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="skill-item">
                                                <div class="skill-info">
                                                    <span class="skill-name">JavaScript</span>
                                                    <span class="skill-level">Expert</span>
                                                </div>
                                                <div class="skill-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="skill-item">
                                                <div class="skill-info">
                                                    <span class="skill-name">React</span>
                                                    <span class="skill-level">Intermediate</span>
                                                </div>
                                                <div class="skill-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="skill-item">
                                                <div class="skill-info">
                                                    <span class="skill-name">PHP</span>
                                                    <span class="skill-level">Intermediate</span>
                                                </div>
                                                <div class="skill-actions">
                                                    <button class="btn-edit"><i class="fas fa-pencil-alt"></i></button>
                                                    <button class="btn-delete"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Education Tab -->
                            <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                                <div class="content-card mb-4">
                                    <div class="card-header">
                                        <h5>Education</h5>
                                        <button class="btn-add-education"><i class="fas fa-plus"></i> Add Education</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="education-empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <h6>No education details yet</h6>
                                            <p>Add your educational qualifications to enhance your profile</p>
                                            <button class="btn-add-first-education">Add Education Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    /* Page Header Styles */
    .page-header-section {
        background-color: var(--white);
        padding: 2rem 0 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        margin-top: 70px;
        /* For fixed navbar */
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .page-description {
        font-size: 1rem;
        color: var(--gray-600);
    }

    /* Profile Sidebar Styles */
    .profile-sidebar {
        position: sticky;
        top: 90px;
    }

    .profile-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .profile-header {
        padding: 1.5rem;
        text-align: center;
        border-bottom: 1px solid var(--gray-200);
    }

    .profile-avatar {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1.25rem;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: var(--border-radius-circle);
        background: linear-gradient(135deg, #14a800, #0e7400);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 3rem;
        font-weight: var(--font-weight-light);
    }

    .change-photo-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        border-radius: var(--border-radius-circle);
        background-color: var(--primary);
        color: var(--white);
        border: 2px solid var(--white);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .change-photo-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        margin-bottom: 0.25rem;
    }

    .profile-headline {
        font-size: 0.95rem;
        color: var(--gray-700);
        margin-bottom: 0.75rem;
    }

    .profile-rating {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .stars {
        color: #f39c12;
    }

    .rating-value {
        font-weight: var(--font-weight-semibold);
    }

    .rating-count {
        color: var(--gray-600);
        font-size: 0.85rem;
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 1.25rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .btn-profile-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        transition: all var(--transition-normal);
        cursor: pointer;
        border: none;
    }

    .view-public {
        background-color: var(--gray-100);
        color: var(--secondary);
    }

    .view-public:hover {
        background-color: var(--gray-200);
        transform: translateY(-2px);
    }

    .profile-settings {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .profile-settings:hover {
        background-color: rgba(121, 82, 179, 0.1);
        transform: translateY(-2px);
    }

    .profile-completion {
        padding: 1.25rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .completion-text {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        color: var(--secondary);
        font-size: 0.9rem;
    }

    .completion-percentage {
        font-weight: var(--font-weight-semibold);
        color: var(--primary);
    }

    .progress {
        height: 8px;
        border-radius: var(--border-radius-lg);
        background-color: var(--gray-200);
    }

    .progress-bar {
        background-color: #14a800;
        border-radius: var(--border-radius-lg);
    }

    .profile-tips {
        padding: 1.25rem;
    }

    .profile-tips h6 {
        font-size: 1rem;
        font-weight: var(--font-weight-semibold);
        margin-bottom: 1rem;
    }

    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tips-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        color: var(--gray-700);
    }

    .tips-list li:last-child {
        margin-bottom: 0;
    }

    .tips-list .completed {
        color: #14a800;
    }

    .tips-list .incomplete {
        color: var(--gray-500);
    }

    /* Profile Content Styles */
    .profile-nav {
        display: flex;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        scrollbar-width: thin;
        scrollbar-color: var(--gray-400) transparent;
    }

    .profile-nav::-webkit-scrollbar {
        height: 4px;
    }

    .profile-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .profile-nav::-webkit-scrollbar-thumb {
        background-color: var(--gray-400);
        border-radius: var(--border-radius-md);
    }

    .profile-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        color: var(--secondary);
        font-weight: var(--font-weight-medium);
        border-radius: var(--border-radius-md);
        white-space: nowrap;
        transition: all var(--transition-normal);
    }

    .profile-nav .nav-link:hover {
        background-color: rgba(121, 82, 179, 0.1);
        color: var(--primary);
    }

    .profile-nav .nav-link.active {
        background-color: var(--primary);
        color: var(--white);
    }

    .content-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .content-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .content-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .btn-edit-section,
    .btn-edit,
    .btn-delete,
    .btn-add-portfolio,
    .btn-add-skill,
    .btn-add-education {
        background-color: transparent;
        border: none;
        color: var(--gray-600);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-normal);
        padding: 0.25rem 0.5rem;
        border-radius: var(--border-radius-md);
    }

    .btn-add-portfolio,
    .btn-add-skill,
    .btn-add-education {
        background-color: rgba(121, 82, 179, 0.1);
        color: var(--primary);
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        padding: 0.5rem 0.75rem;
        display: inline-flex;
        gap: 0.5rem;
    }

    .btn-edit:hover,
    .btn-delete:hover,
    .btn-edit-section:hover {
        color: var(--primary);
        background-color: rgba(121, 82, 179, 0.1);
    }

    .btn-delete:hover {
        color: var(--danger);
        background-color: rgba(231, 76, 60, 0.1);
    }

    .btn-add-portfolio:hover,
    .btn-add-skill:hover,
    .btn-add-education:hover {
        background-color: rgba(121, 82, 179, 0.2);
        color: var(--primary-dark);
    }

    .content-card .card-body {
        padding: 1.5rem;
    }

    .about-text {
        font-size: 0.95rem;
        color: var(--gray-700);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .about-text:last-child {
        margin-bottom: 0;
    }

    .experience-item {
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .experience-item:last-child {
        padding-bottom: 0;
        margin-bottom: 0;
        border-bottom: none;
    }

    .experience-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .experience-header h6 {
        font-size: 1.05rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .experience-actions {
        display: flex;
        gap: 0.5rem;
    }

    .company-name {
        font-size: 0.95rem;
        color: var(--gray-700);
        font-weight: var(--font-weight-medium);
        margin-bottom: 0.25rem;
    }

    .experience-date {
        font-size: 0.85rem;
        color: var(--gray-600);
        margin-bottom: 0.75rem;
    }

    .experience-description {
        font-size: 0.9rem;
        color: var(--gray-700);
        line-height: 1.6;
        margin: 0;
    }

    .language-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .language-item:last-child {
        border-bottom: none;
    }

    .language-info {
        display: flex;
        justify-content: space-between;
        flex: 1;
    }

    .language-name {
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        color: var(--secondary);
    }

    .language-level {
        font-size: 0.9rem;
        color: var(--gray-600);
    }

    .language-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Empty States */
    .portfolio-empty-state,
    .education-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        border-radius: var(--border-radius-circle);
        background-color: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        color: var(--gray-600);
        font-size: 1.8rem;
    }

    .portfolio-empty-state h6,
    .education-empty-state h6 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .portfolio-empty-state p,
    .education-empty-state p {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }

    .btn-add-first-project,
    .btn-add-first-education {
        background-color: var(--primary);
        color: var(--white);
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-md);
        border: none;
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-add-first-project:hover,
    .btn-add-first-education:hover {
        background-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* Skills Tab Styles */
    .skills-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .skill-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.75rem 1rem;
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
        transition: all var(--transition-normal);
    }

    .skill-item:hover {
        background-color: var(--gray-200);
        transform: translateY(-2px);
    }

    .skill-info {
        display: flex;
        justify-content: space-between;
        flex: 1;
    }

    .skill-name {
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        color: var(--secondary);
    }

    .skill-level {
        font-size: 0.85rem;
        color: var(--gray-600);
    }

    .skill-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .profile-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }

        .avatar-placeholder {
            font-size: 2.5rem;
        }

        .profile-name {
            font-size: 1.25rem;
        }

        .profile-nav .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .language-info,
        .skill-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .language-item {
            flex-wrap: wrap;
        }

        .language-actions,
        .skill-actions {
            margin-top: 0.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Fake button functionality
        const editButtons = document.querySelectorAll('.btn-edit-section, .btn-edit, .btn-add-portfolio, .btn-add-skill, .btn-add-education, .btn-add-first-project, .btn-add-first-education, .btn-profile-action');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                alert('This is a demo button. In a real application, this would open an edit form or modal.');
            });
        });

        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                alert('This is a demo button. In a real application, this would show a confirmation dialog before deletion.');
            });
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>