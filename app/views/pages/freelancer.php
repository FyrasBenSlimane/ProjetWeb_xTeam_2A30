<?php
// Get the requested page from URL or default to 'main'
$page = isset($_GET['page']) ? $_GET['page'] : 'main';

// Determine which view to load based on page parameter
if ($page === 'profile') {
    // Show profile page
    require_once APPROOT . '/views/pages/profile_view.php';
} elseif ($page === 'settings') {
    // Show settings page
    require_once APPROOT . '/views/pages/profile_settings.php';
} else {
    // Default main freelancer content
?>
<style>
/* Apply landing page variables */
:root {
    /* Primary color palette - more professional and minimalist */
    --primary: #2c3e50; /* Dark slate blue-gray, professional */
    --primary-light: #34495e;
    --primary-dark: #1a252f;
    --primary-accent: #ecf0f1;
    
    /* Secondary color palette - neutral and professional */
    --secondary: #222325; /* Dark gray for text */
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
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --transition-fast: 0.2s ease;
    --transition-default: 0.3s ease;

    /* Font */
    --font-primary: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
    --font-weight-base: 400;
    --font-weight-medium: 500;
    --font-weight-bold: 600;
}

body {
    background-color: var(--gray-lighter); /* Use variable */
    font-family: var(--font-primary);
}

.upwork-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
}

.upwork-layout {
    display: flex;
    gap: 20px;
}

.main-content {
    flex: 3;
    background-color: var(--white);
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.sidebar {
    flex: 1;
    /* Styles for sidebar elements like profile, connects, etc. */
}

.freelancer-plus-banner {
    background: linear-gradient(135deg, var(--primary), var(--primary-light)); /* Use primary gradient */
    color: var(--white);
    padding: 25px;
    border-radius: var(--radius-lg);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.freelancer-plus-banner h2 {
    font-size: 1.5rem;
    margin: 0;
}

.freelancer-plus-banner p {
    margin: 5px 0 15px;
}

.freelancer-plus-banner .learn-more-btn {
    background-color: var(--white);
    color: var(--primary);
    padding: 10px 20px;
    border: none;
    border-radius: 25px;
    font-weight: var(--font-weight-medium);
    cursor: pointer;
    text-decoration: none;
    transition: all var(--transition-fast);
}

.freelancer-plus-banner .learn-more-btn:hover {
    background-color: var(--primary-accent);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.freelancer-plus-banner img {
    max-height: 100px;
}

.search-jobs-section {
    padding: 25px;
    border-bottom: 1px solid var(--gray-light);
}

.search-jobs-section input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-sm);
    font-size: 1rem;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.search-jobs-section input[type="text"]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
    outline: none;
}

.jobs-you-might-like {
    padding: 25px;
}

.job-filters {
    margin-bottom: 15px;
}

.job-filters button {
    background: none;
    border: none;
    padding: 8px 12px;
    margin-right: 10px;
    cursor: pointer;
    font-weight: var(--font-weight-medium);
    color: var(--gray-medium);
    border-bottom: 2px solid transparent;
    transition: color var(--transition-fast), border-color var(--transition-fast);
}

.job-filters button:hover {
    color: var(--primary);
}

.job-filters button.active {
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
    font-weight: var(--font-weight-bold);
}

.job-listing {
    border-bottom: 1px solid var(--gray-light);
    padding: 25px 0;
    cursor: pointer;
    transition: background-color var(--transition-fast);
    position: relative;
}

.job-listing:last-child {
    border-bottom: none;
}

.job-listing:hover {
    background-color: var(--gray-lighter); /* Subtle hover */
}

.job-listing::before {
    content: '';
    position: absolute;
    left: -25px; /* Align with padding */
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: var(--primary);
    transform: scaleY(0);
    transition: transform var(--transition-default);
    transform-origin: center;
    border-radius: 0 4px 4px 0;
}

.job-listing:hover::before {
    transform: scaleY(0.6);
}

.job-listing.selected {
    background-color: rgba(var(--primary-rgb), 0.05); /* Use primary color tint */
}

.job-listing.selected::before {
    transform: scaleY(1);
}

.job-title {
    font-size: 1.2rem;
    font-weight: var(--font-weight-medium);
    color: var(--primary);
    margin-bottom: 8px;
    transition: color var(--transition-fast);
}

.job-listing:hover .job-title {
    color: var(--primary-dark);
}

.job-meta {
    font-size: 0.9rem;
    color: var(--gray-medium);
    margin-bottom: 12px;
}

.job-description {
    font-size: 0.95rem;
    color: var(--secondary-light);
    line-height: 1.5;
    margin-bottom: 15px;
}

.job-tags span {
    background-color: var(--primary-accent);
    color: var(--primary-dark);
    padding: 4px 10px;
    border-radius: var(--radius-lg);
    font-size: 0.8rem;
    margin-right: 6px;
    display: inline-block;
    margin-bottom: 6px;
    font-weight: var(--font-weight-medium);
}

.job-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.job-actions button {
    background: none;
    border: 1px solid var(--gray-light);
    border-radius: 20px;
    padding: 6px 12px;
    cursor: pointer;
    color: var(--gray-medium);
    transition: all var(--transition-fast);
}

.job-actions button:hover {
    border-color: var(--gray-medium);
    color: var(--secondary-dark);
    background-color: var(--gray-lighter);
}

.job-actions button.like-btn i {
    transition: color var(--transition-fast), transform var(--transition-fast);
}

.job-actions button.like-btn.liked {
    color: #e74c3c; /* A slightly softer red */
    border-color: #e74c3c;
}

.job-actions button.like-btn.liked i {
    color: #e74c3c;
    transform: scale(1.1);
}

/* Sidebar Styles */
.sidebar-widget {
    background-color: var(--white);
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-md);
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.03);
}

.widget-header {
    padding: 15px 20px;
    border-bottom: 1px solid var(--gray-light);
    font-weight: var(--font-weight-medium);
    color: var(--secondary-dark);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--gray-lighter);
}

.widget-content {
    padding: 20px;
}

.widget-content p {
    margin-bottom: 12px;
    font-size: 0.9rem;
    color: var(--gray-dark);
}

.widget-content .btn-primary {
    background: linear-gradient(to right, var(--primary), var(--primary-dark));
    border: none;
    color: var(--white);
    padding: 10px 18px;
    border-radius: 25px;
    font-weight: var(--font-weight-medium);
    text-decoration: none;
    display: inline-block;
    width: 100%;
    text-align: center;
    margin-bottom: 12px;
    transition: all var(--transition-default);
}

.widget-content .btn-primary:hover {
    background: linear-gradient(to right, var(--primary-dark), var(--primary));
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.2);
}

.widget-content .btn-secondary {
    background-color: var(--white);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 10px 18px;
    border-radius: 25px;
    font-weight: var(--font-weight-medium);
    text-decoration: none;
    display: inline-block;
    width: 100%;
    text-align: center;
    transition: all var(--transition-default);
}

.widget-content .btn-secondary:hover {
    background-color: rgba(var(--primary-rgb), 0.05);
    border-color: var(--primary-dark);
    color: var(--primary-dark);
}

.widget-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-content li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-lighter);
}

.widget-content li:last-child {
    border-bottom: none;
}

.widget-content li span:first-child {
    color: #333;
}

.widget-content li span:last-child {
    font-weight: var(--font-weight-medium);
    color: var(--secondary-light);
}

.widget-content a {
    color: var(--primary);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    display: block;
    margin-top: 12px;
    transition: color var(--transition-fast);
}

.widget-content a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Job Detail Panel (Initially Hidden) */
.job-detail-panel {
    position: fixed;
    top: 0;
    right: -100%; /* Start off-screen */
    width: 60%; /* Wider slider - adjust as needed */
    max-width: 800px; /* Add a max-width for larger screens */
    height: 100vh;
    background-color: var(--white);
    border-left: 1px solid var(--gray-light);
    box-shadow: -4px 0 15px rgba(0,0,0,0.08);
    z-index: 1000;
    transition: right 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smoother transition */
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.job-detail-panel.open {
    right: 0; /* Slide in */
}

.job-detail-header {
    padding: 18px 25px;
    border-bottom: 1px solid var(--gray-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--gray-lighter);
    position: sticky; /* Keep header visible */
    top: 0;
    z-index: 1001;
}

.job-detail-header h3 {
    margin: 0;
    font-size: 1.3rem;
    color: var(--secondary-dark);
    font-weight: var(--font-weight-medium);
}

.job-detail-header .close-btn {
    background: none;
    border: none;
    font-size: 1.6rem;
    cursor: pointer;
    color: var(--gray-medium);
    transition: color var(--transition-fast);
}

.job-detail-header .close-btn:hover {
    color: var(--secondary-dark);
}

.job-detail-body {
    padding: 25px;
    flex-grow: 1;
}

.job-detail-body h4 {
    font-size: 1.15rem;
    color: var(--secondary-dark);
    font-weight: var(--font-weight-medium);
    margin-top: 20px;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid var(--gray-lighter);
}

.job-detail-body p, .job-detail-body li {
    font-size: 0.95rem;
    color: var(--secondary-light);
    line-height: 1.6;
    margin-bottom: 12px;
}

.job-detail-body .job-meta-detail span {
    display: block;
    margin-bottom: 5px;
}

.job-detail-body .skills-list span {
    background-color: var(--primary-accent);
    color: var(--primary-dark);
    padding: 5px 12px;
    border-radius: var(--radius-lg);
    font-size: 0.85rem;
    margin-right: 8px;
    display: inline-block;
    margin-bottom: 8px;
    font-weight: var(--font-weight-medium);
}

.job-detail-footer {
    padding: 18px 25px;
    border-top: 1px solid var(--gray-light);
    background-color: var(--gray-lighter);
    display: flex;
    justify-content: flex-end; /* Align buttons to the right */
    gap: 15px;
    position: sticky; /* Keep footer visible */
    bottom: 0;
    z-index: 1001;
}

.job-detail-footer .btn-apply {
    background: linear-gradient(to right, var(--primary), var(--primary-dark));
    border: none;
    color: var(--white);
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: var(--font-weight-medium);
    text-decoration: none;
    /* flex-grow: 1; Remove flex-grow to allow right alignment */
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-default);
    order: 2; /* Ensure Apply button is on the right */
}

.job-detail-footer .btn-apply:hover {
    background: linear-gradient(to right, var(--primary-dark), var(--primary));
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.2);
}

.job-detail-footer .btn-save {
    background-color: var(--white);
    border: 1px solid var(--primary);
    color: var(--primary);
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: var(--font-weight-medium);
    text-decoration: none;
    cursor: pointer;
    transition: all var(--transition-default);
    order: 1; /* Ensure Save button is to the left of Apply */
}

.job-detail-footer .btn-save:hover {
    background-color: rgba(var(--primary-rgb), 0.05);
    border-color: var(--primary-dark);
    color: var(--primary-dark);
}

.job-detail-footer .btn-flag {
    background: none;
    border: none;
    color: var(--gray-medium);
    cursor: pointer;
    padding: 12px;
    transition: color var(--transition-fast);
    margin-right: auto; /* Push flag button to the far left */
    order: 0;
}

.job-detail-footer .btn-flag:hover {
    color: var(--secondary-dark);
}


</style>

<div class="upwork-container">
    <div class="upwork-layout">

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Freelancer Plus Banner -->
            <div class="freelancer-plus-banner">
                <div>
                    <h2>Freelancer Plus with new perks</h2>
                    <p>100 monthly Connects and full access to Uma, Upwork's Mindful AI.</p>
                    <a href="#" class="learn-more-btn">Learn More</a>
                </div>
                <img src="<?php echo URL_ROOT; ?>/public/img/freelancer_plus_illo.svg" alt="Freelancer Plus Illustration"> <!-- Placeholder image path -->
            </div>

            <!-- Search Bar -->
            <div class="search-jobs-section">
                <input type="text" placeholder="Search for jobs">
            </div>

            <!-- Job Listings -->
            <div class="jobs-you-might-like">
                <div class="job-filters">
                    <button class="active">Best Matches</button>
                    <button>Most Recent</button>
                    <button>Saved Jobs (0)</button> <!-- Dynamic count -->
                </div>

                <!-- Sample Job Listing 1 -->
                <div class="job-listing" data-job-id="1">
                    <div class="job-actions">
                        <button class="like-btn"><i class="far fa-heart"></i></button>
                        <button><i class="fas fa-thumbs-down"></i></button>
                    </div>
                    <h3 class="job-title">Recovering My Python Source Code from EXE</h3>
                    <div class="job-meta">
                        <span>Fixed-price</span> - <span>Intermediate</span> - <span>Est. Budget: $200</span> - <span>Posted 26 minutes ago</span>
                    </div>
                    <p class="job-description">
                        I have a software developed in Python 3.9.0. We were working on adding new functions to the software but the developer I was working with disappeared and I don't have the source code to continue development. I have the old files from January. I need someone to recover my source code from the exe. I can provide all previous files if needed and any help that's necessary for the...
                        <a href="#" class="more-link">more</a>
                    </p>
                    <div class="job-tags">
                        <span>Python</span>
                        <span>Desktop Application</span>
                        <span>Standalone Application</span>
                        <span>Python Script</span>
                    </div>
                </div>

                <!-- Sample Job Listing 2 -->
                 <div class="job-listing" data-job-id="2">
                    <div class="job-actions">
                        <button class="like-btn"><i class="far fa-heart"></i></button>
                        <button><i class="fas fa-thumbs-down"></i></button>
                    </div>
                    <h3 class="job-title">Build a Responsive Landing Page with React</h3>
                     <div class="job-meta">
                        <span>Hourly: $25.00-$45.00</span> - <span>Expert</span> - <span>Est. Time: Less than 1 month, Less than 30 hrs/week</span> - <span>Posted 2 hours ago</span>
                    </div>
                    <p class="job-description">
                        We need an experienced frontend developer to build a pixel-perfect, responsive landing page based on our Figma designs. Must be proficient in React, HTML5, CSS3, and JavaScript. Experience with Tailwind CSS is a plus. Focus on performance and clean code...
                        <a href="#" class="more-link">more</a>
                    </p>
                    <div class="job-tags">
                        <span>React</span>
                        <span>HTML5</span>
                        <span>CSS3</span>
                        <span>JavaScript</span>
                        <span>Frontend Development</span>
                        <span>Responsive Design</span>
                    </div>
                </div>

                 <!-- Sample Job Listing 3 -->
                 <div class="job-listing" data-job-id="3">
                    <div class="job-actions">
                        <button class="like-btn liked"><i class="fas fa-heart"></i></button> <!-- Example Liked -->
                        <button><i class="fas fa-thumbs-down"></i></button>
                    </div>
                    <h3 class="job-title">Develop REST API for Mobile App Backend</h3>
                     <div class="job-meta">
                        <span>Fixed-price</span> - <span>Expert</span> - <span>Est. Budget: $1500</span> - <span>Posted 5 hours ago</span>
                    </div>
                    <p class="job-description">
                        Seeking a backend developer to create a secure and scalable RESTful API using Node.js and Express. The API will handle user authentication, data storage (PostgreSQL), and interactions for our upcoming mobile application. Need clear documentation...
                        <a href="#" class="more-link">more</a>
                    </p>
                    <div class="job-tags">
                        <span>Node.js</span>
                        <span>Express.js</span>
                        <span>REST API</span>
                        <span>PostgreSQL</span>
                        <span>Backend Development</span>
                        <span>Authentication</span>
                    </div>
                </div>

                <!-- Add more job listings as needed -->

            </div>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-widget profile-widget">
                 <div class="widget-header">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Freelancer'); ?>!</div>
                 <div class="widget-content">
                    <p>Connects: <span class="connects-count">120</span></p> <!-- Dynamic count -->
                    <a href="#" class="btn-primary">Earn Free Connects</a>
                    <a href="<?php echo URL_ROOT; ?>/pages/freelancer?page=profile" class="btn-secondary">View Profile</a>
                    <a href="<?php echo URL_ROOT; ?>/pages/freelancer?page=settings" class="btn-secondary">Profile Settings</a>
                 </div>
            </div>

            <div class="sidebar-widget promote-widget">
                 <div class="widget-header">Promote with ads <i class="fas fa-chevron-down"></i></div>
                 <div class="widget-content">
                    <a href="#">Availability badge</a>
                    <a href="#">Boost your profile</a>
                 </div>
            </div>

             <div class="sidebar-widget proposals-widget">
                 <div class="widget-header">Proposals and offers <i class="fas fa-chevron-down"></i></div>
                 <div class="widget-content">
                    <ul>
                        <li><span>Contract offers</span> <span>0</span></li>
                        <li><span>Invites to apply</span> <span>0</span></li>
                        <li><span>Proposals</span> <span>0</span></li>
                    </ul>
                    <a href="#">View all</a>
                 </div>
            </div>

            <div class="sidebar-widget profile-visibility-widget">
                 <div class="widget-header">Your Profile <i class="fas fa-chevron-down"></i></div>
                 <div class="widget-content">
                     <a href="#">Profile Visibility</a>
                     <!-- Add more profile links -->
                 </div>
            </div>
        </aside>

    </div>
</div>

<!-- Job Detail Panel (Initially Hidden) -->
<div class="job-detail-panel" id="jobDetailPanel">
    <div class="job-detail-header">
        <h3 id="jobDetailTitle">Job Details</h3>
        <button class="close-btn" id="closeJobDetail"><i class="fas fa-times"></i></button>
    </div>
    <div class="job-detail-body" id="jobDetailBody">
        <!-- Content will be loaded here by JavaScript -->
        <p>Loading job details...</p>
    </div>
    <div class="job-detail-footer">
        <button class="btn-apply">Apply Now</button>
        <button class="btn-save"><i class="far fa-heart"></i> Save Job</button>
        <button class="btn-flag"><i class="far fa-flag"></i> Flag as inappropriate</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jobListings = document.querySelectorAll('.job-listing');
    const jobDetailPanel = document.getElementById('jobDetailPanel');
    const jobDetailTitle = document.getElementById('jobDetailTitle');
    const jobDetailBody = document.getElementById('jobDetailBody');
    const closeJobDetailBtn = document.getElementById('closeJobDetail');

    // --- Dummy Job Data (Replace with actual data fetching if needed) ---
    const jobData = {
        '1': {
            title: 'Recovering My Python Source Code from EXE',
            posted: '26 minutes ago',
            worldwide: true,
            budget: '$200.00 Fixed-price',
            level: 'Intermediate',
            description: 'I have a software developed in Python 3.9.0. We were working on adding new functions to the software but the developer I was working with disappeared and I don\'t have the source code to continue development. I have the old files from January. I need someone to recover my source code from the exe. I can provide all previous files if needed and any help that\'s necessary for the code recovery.',
            skills: ['Python', 'Desktop Application', 'Standalone Application', 'Python Script'],
            connects: 14, // Example
            client: {
                rating: 5.0,
                reviews: 498,
                country: 'Hungary',
                jobsPosted: 52,
                hireRate: '60%',
                totalSpent: '$11K',
                memberSince: 'Sep 21, 2016'
            }
        },
        '2': {
            title: 'Build a Responsive Landing Page with React',
            posted: '2 hours ago',
            worldwide: false,
            budget: 'Hourly: $25.00-$45.00',
            level: 'Expert',
            description: 'We need an experienced frontend developer to build a pixel-perfect, responsive landing page based on our Figma designs. Must be proficient in React, HTML5, CSS3, and JavaScript. Experience with Tailwind CSS is a plus. Focus on performance and clean code. Please provide examples of previous React projects.',
            skills: ['React', 'HTML5', 'CSS3', 'JavaScript', 'Frontend Development', 'Responsive Design', 'Tailwind CSS'],
            connects: 10,
            client: {
                rating: 4.8,
                reviews: 150,
                country: 'United States',
                jobsPosted: 30,
                hireRate: '75%',
                totalSpent: '$25K',
                memberSince: 'Mar 10, 2018'
            }
        },
        '3': {
            title: 'Develop REST API for Mobile App Backend',
            posted: '5 hours ago',
            worldwide: true,
            budget: '$1500 Fixed-price',
            level: 'Expert',
            description: 'Seeking a backend developer to create a secure and scalable RESTful API using Node.js and Express. The API will handle user authentication, data storage (PostgreSQL), and interactions for our upcoming mobile application. Need clear documentation and unit tests. Experience with AWS is preferred.',
            skills: ['Node.js', 'Express.js', 'REST API', 'PostgreSQL', 'Backend Development', 'Authentication', 'AWS'],
            connects: 16,
            client: {
                rating: 4.9,
                reviews: 320,
                country: 'Canada',
                jobsPosted: 80,
                hireRate: '65%',
                totalSpent: '$50K',
                memberSince: 'Jan 05, 2017'
            }
        }
        // Add data for other jobs...
    };

    // --- Event Listeners ---
    jobListings.forEach(listing => {
        listing.addEventListener('click', function(e) {
            // Don't open panel if clicking on action buttons inside the listing
            if (e.target.closest('.job-actions button') || e.target.closest('.more-link')) {
                return;
            }

            const jobId = this.dataset.jobId;
            const data = jobData[jobId];

            if (data) {
                // Highlight selected job
                jobListings.forEach(l => l.classList.remove('selected'));
                this.classList.add('selected');

                // Populate and open detail panel
                populateJobDetail(data);
                jobDetailPanel.classList.add('open');
            } else {
                console.error('Job data not found for ID:', jobId);
                jobDetailBody.innerHTML = '<p>Error: Could not load job details.</p>';
                jobDetailPanel.classList.add('open');
            }
        });
    });

    closeJobDetailBtn.addEventListener('click', function() {
        jobDetailPanel.classList.remove('open');
        jobListings.forEach(l => l.classList.remove('selected')); // Deselect job
    });

    // Close panel if clicking outside of it (optional)
    document.addEventListener('click', function(event) {
        if (!jobDetailPanel.contains(event.target) && !event.target.closest('.job-listing')) {
             if (jobDetailPanel.classList.contains('open')) {
                jobDetailPanel.classList.remove('open');
                jobListings.forEach(l => l.classList.remove('selected'));
             }
        }
    });

    // Handle like button clicks (visual toggle only)
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent opening the detail panel
            this.classList.toggle('liked');
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
        });
    });

    // --- Helper Function ---
    function populateJobDetail(data) {
        jobDetailTitle.textContent = data.title;

        let skillsHtml = data.skills.map(skill => `<span class="skill-tag">${skill}</span>`).join('');

        jobDetailBody.innerHTML = `
            <p>Posted ${data.posted} ${data.worldwide ? '· Worldwide' : ''}</p>
            <hr>
            <p>${data.description}</p>
            <hr>
            <div class="job-meta-detail">
                <span><i class="fas fa-tag"></i> ${data.budget}</span>
                <span><i class="fas fa-user-tie"></i> ${data.level} Level</span>
                <!-- Add more meta details like duration, hours etc. if available -->
            </div>
            <hr>
            <h4>Skills and Expertise</h4>
            <div class="skills-list">${skillsHtml}</div>
            <hr>
            <h4>Activity on this job</h4>
            <p>Proposals: <span class="text-muted">5 to 10</span></p> <!-- Example -->
            <p>Required Connects to submit a proposal: <span class="text-muted">${data.connects}</span></p>
            <!-- Add more activity details -->
            <hr>
            <h4>About the client</h4>
            <p>Payment method verified · ${data.client.rating.toFixed(1)} <i class="fas fa-star text-warning"></i> ${data.client.reviews} reviews</p>
            <p>${data.client.country}</p>
            <p>${data.client.jobsPosted} jobs posted · ${data.client.hireRate} hire rate</p>
            <p>${data.client.totalSpent} total spent</p>
            <p>Member since ${data.client.memberSince}</p>
        `;

        // Update footer buttons based on job state (e.g., saved status)
        const saveButton = jobDetailPanel.querySelector('.btn-save');
        // Logic to check if job is saved would go here
        // saveButton.innerHTML = '<i class="fas fa-heart"></i> Saved'; // Example if saved
    }
});
</script>
<?php
}
?>