<?php
// Client main page view
// This file displays the main client platform page for logged-in clients
// Use Job model for formatting utilities
require_once(APP_ROOT . '/models/Job.php');
?>

<!-- Modern Client Dashboard -->
<div class="client-dashboard">
    <!-- Dashboard Header -->    
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="welcome-message">
                        Welcome back, <?php echo isset($data['user']->name) ? explode(' ', htmlspecialchars($data['user']->name))[0] : 'Client'; ?>
                    </h1>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn post-job-btn" onclick="openModal('postJobModal')">
                        <i class="fas fa-plus me-2"></i> Post a job
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Header Styles -->
    <style>
        /* Core variables */
        :root {
            --primary-color: #2c3e50; /* Dark slate blue-gray from landing page */
            --primary-hover: #34495e; /* Primary light from landing page */
            --primary-dark: #1a252f;
            --primary-accent: #ecf0f1;
            --dark-color: #222325;
            --text-color: #5e6d55;
            --light-gray: #f9f9f9;
            --border-color: #e4e5e7;
            --badge-active: #2c3e5020;
            --badge-paused: #f5c30020;
            --badge-closed: #9e9e9e20;
            --font-family: 'Roobert', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            --box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            --radius: 8px;
            --container-width: 1140px; /* Set max container width */
            --container-padding: 24px; /* Consistent padding */
        }

        body {
            font-family: var(--font-family);
            color: var(--dark-color);
            background-color: #fff;
            letter-spacing: -0.01em;
        }

        /* Header styles */
        .client-dashboard > .dashboard-header {
            padding: 32px 0;
            margin-bottom: 20px;
            background-color: #fff;
            border-bottom: none;
        }

        .welcome-message {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 0;
            color: var(--dark-color);
            letter-spacing: -0.03em;
        }

        .post-job-btn {
            background-color: #2c3e50;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .post-job-btn:hover {
            background-color: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Container styles */
        .container {
            max-width: var(--container-width);
            padding-left: var(--container-padding);
            padding-right: var(--container-padding);
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
        
        /* Dashboard content */
        .dashboard-content {
            padding: 1.5rem 0;
            background-color: #fff;
        }
        
        .dashboard-sections {
            max-width: var(--container-width);
            margin: 0 auto;
            padding-left: var(--container-padding);
            padding-right: var(--container-padding);
            width: 100%;
        }
        
        /* Make all sections aligned */
        .section-v3, .dashboard-section {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1.5rem;
        }

        /* Add Roobert font */
        @font-face {
            font-family: 'Roobert';
            src: local('Segoe UI'), local('Helvetica Neue'), local('Arial'), local('sans-serif');
            font-weight: 400;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Roobert';
            src: local('Segoe UI'), local('Helvetica Neue'), local('Arial'), local('sans-serif');
            font-weight: 500;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Roobert';
            src: local('Segoe UI'), local('Helvetica Neue'), local('Arial'), local('sans-serif');
            font-weight: 600;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'Roobert';
            src: local('Segoe UI'), local('Helvetica Neue'), local('Arial'), local('sans-serif');
            font-weight: 700;
            font-style: normal;
        }

        /* Section container alignment */
        .dashboard-section .section-header,
        .dashboard-section .section-content,
        .dashboard-section .jobs-grid,
        .dashboard-section .jobs-list,
        .dashboard-section .experts-grid,
        .dashboard-section .help-resources-grid {
            max-width: var(--container-width);
            margin: 0 auto;
            padding-left: 0;
            padding-right: 0;
        }
        
        /* Make sure header aligns with other sections */
        .dashboard-header .container {
            max-width: var(--container-width);
            padding-left: var(--container-padding);
            padding-right: var(--container-padding);
        }

        /* Ensure consistent text alignment */
        .section-title, .welcome-message {
            text-align: left;
            padding-left: 0;
        }

        /* Align dashboard content container with header */
        .dashboard-container {
            max-width: var(--container-width);
            padding-left: 0;
            padding-right: 0;
            margin: 0 auto;
        }

        /* Remove extra padding from section containers */
        .dashboard-section .container {
            padding-left: 0;
            padding-right: 0;
        }

        /* Align section headers with dashboard header */
        .section-header.container {
            padding-left: 0;
            padding-right: 0;
        }
    </style>
    
    
    
    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <div class="container">
            <!-- Main Dashboard Content -->
            <div class="dashboard-sections">
                <!-- Section: Your Jobs -->
                <div class="dashboard-section jobs-section">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h2 class="section-title">Overview</h2>
                    </div>
                                
                    <?php 
                    // Make sure we pass the jobs data correctly
                    $jobs = isset($data['jobs']) ? $data['jobs'] : [];
                    include(APP_ROOT . '/views/jobs/jobs_list.php'); 
                    ?>
                </div>

                <!-- Section: Expert Services -->
                <div class="dashboard-section expert-section">
                    <div class="section-header">
                        <h2 class="section-title">Find experts by category and book consultations</h2>
                        <a href="#" class="section-link">Browse consultations <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="section-content">
                        <div class="experts-grid">
                            <a href="<?php echo URL_ROOT; ?>/services/browse?category=programming&expert=1" class="category-card">
                                <div class="category-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <h3 class="category-title">Web Development</h3>
                                <p class="category-subtitle">Website, app, and software development</p>
                                <p class="category-experts">725 experts available</p>
                            </a>
                            
                            <a href="<?php echo URL_ROOT; ?>/services/browse?category=design&expert=1" class="category-card">
                                <div class="category-icon">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                                <h3 class="category-title">Design & Creative</h3>
                                <p class="category-subtitle">Graphic, web, and UI/UX design</p>
                                <p class="category-experts">542 experts available</p>
                            </a>
                            
                            <a href="<?php echo URL_ROOT; ?>/services/browse?category=digital-marketing&expert=1" class="category-card">
                                <div class="category-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <h3 class="category-title">Marketing</h3>
                                <p class="category-subtitle">SEO, SEM, and content marketing</p>
                                <p class="category-experts">389 experts available</p>
                            </a>
                            
                            <a href="<?php echo URL_ROOT; ?>/services/browse?category=writing&expert=1" class="category-card">
                                <div class="category-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h3 class="category-title">Writing</h3>
                                <p class="category-subtitle">Content, copywriting, and editing</p>
                                <p class="category-experts">651 experts available</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Section: Help & Resources -->
                <div class="dashboard-section help-section">
                    <div class="section-header">
                        <h2 class="section-title">Help and resources</h2>
                        <a href="#" class="section-link">View all resources <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="section-content">
                        <div class="help-resources-grid">
                            <a href="#" class="help-card">
                                <div class="help-card-label">Get started</div>
                                <h3 class="help-card-title">Get started and connect with talent to get work done</h3>
                                <div class="help-card-image">
                                    <img src="https://assets.static-upwork.com/helpcenter/images/rocket.svg" alt="Help">
                                </div>
                            </a>
                            
                            <a href="#" class="help-card">
                                <div class="help-card-label">Payments</div>
                                <h3 class="help-card-title">Everything you need to know about payments</h3>
                                <div class="help-card-image">
                                    <img src="https://assets.static-upwork.com/helpcenter/images/budget.svg" alt="Payments">
                                </div>
                            </a>
                            
                            <a href="#" class="help-card">
                                <div class="help-card-label">Payments</div>
                                <h3 class="help-card-title">How to set up your preferred billing method</h3>
                                <div class="help-card-image">
                                    <img src="https://assets.static-upwork.com/helpcenter/images/creditcard.svg" alt="Billing">
                                </div>
                            </a>

                            <a href="#" class="help-card">
                                <div class="help-card-label">Trust & safety</div>
                                <h3 class="help-card-title">Keep yourself and others safe on Upwork</h3>
                                <div class="help-card-image">
                                    <img src="https://assets.static-upwork.com/helpcenter/images/shield.svg" alt="Safety">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Expert section styles */
    .experts-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .expert-card {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        padding: 16px;
        transition: all 0.25s ease;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.04);
        position: relative;
    }
    
    .expert-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .expert-header {
        margin-bottom: 12px;
    }
    
    .expert-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .expert-avatar {
        width: 48px;
        height: 48px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .expert-name {
        font-size: 16px;
        margin: 0;
        font-weight: 600;
    }
    
    .expert-location {
        font-size: 13px;
        color: #555;
    }
    
    .expert-location:before {
        content: '📍';
        font-size: 11px;
        margin-right: 3px;
    }
    
    .expert-stats {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        margin-bottom: 12px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .expert-title {
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 12px;
        min-height: 2.8em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .expert-meeting {
        padding: 8px;
        border-radius: 6px;
        background-color: #f5f7f9;
        margin-bottom: 12px;
        font-size: 13px;
    }
    
    .expert-meeting i {
        color: #2c3e50;
        margin-right: 8px;
    }
    
    .book-consultation-btn {
        padding: 8px 0;
        font-size: 13px;
        font-weight: 600;
    }
    
    /* Category cards styling - improved */
    .category-card {
        border-radius: 10px;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa, #fff);
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        text-decoration: none;
        color: inherit;
    }
    
    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.1);
        border-color: rgba(44, 62, 80, 0.15);
    }
    
    .category-card:after {
        content: '';
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 20px;
        height: 20px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232c3e50' width='24px' height='24px'%3E%3Cpath d='M0 0h24v24H0V0z' fill='none'/%3E%3Cpath d='M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z'/%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
        opacity: 0.5;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    
    .category-card:hover:after {
        opacity: 1;
        transform: translateX(3px);
    }
    
    .category-icon {
        width: 48px;
        height: 48px;
        background-color: rgba(44, 62, 80, 0.07);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    
    .category-icon i {
        color: #2c3e50;
        font-size: 22px;
    }
    
    .category-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .category-subtitle {
        font-size: 14px;
        color: #555;
        margin-bottom: 14px;
        line-height: 1.4;
    }
    
    .category-experts {
        font-size: 13px;
        color: #2c3e50;
        font-weight: 500;
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
</style>

<!-- Modern box shadows -->
<style>
    .card, .expert-card, .help-card {
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
</style>

<!-- Better typography -->
<style>
    h1, h2, h3, h4, h5 {
        letter-spacing: -0.03em;
    }
</style>

<!-- Improved focus states -->
<style>
    button:focus, a:focus {
        outline: 2px solid rgba(44, 62, 80, 0.4);
        outline-offset: 2px;
    }
</style>

<!-- Updated styles -->
<style>
    @media (max-width: 992px) {
        .experts-grid, .help-resources-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .category-title {
            font-size: 16px;
        }
    }
    
    @media (max-width: 576px) {
        .experts-grid, .help-resources-grid {
            grid-template-columns: 1fr;
        }
        
        .section-title {
            font-size: 22px;
        }
    }
</style>

<!-- Section header alignment styles -->
<style>
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        width: 100%;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .section-title {
        margin: 0;
        padding: 0;
        font-size: 22px;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .section-link {
        color: var(--primary-color);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .section-link:hover {
        color: var(--primary-hover);
        text-decoration: none;
    }
    
    .section-link i {
        margin-left: 4px;
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    
    .section-link:hover i {
        transform: translateX(3px);
    }
    
    .section-content {
        margin-top: 15px;
        padding-top: 20px;
    }
    
    @media (max-width: 576px) {
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-link {
            margin-top: 10px;
        }
    }
</style>

<!-- Help section styles - fixed -->
<style>
    .help-resources-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .help-card {
        border-radius: 10px;
        padding: 20px;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
    }
    
    .help-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        border-color: rgba(44, 62, 80, 0.1);
    }
    
    .help-card-label {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .help-card-title {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0 0 12px;
        color: var(--dark-color);
        max-width: 70%;
    }
    
    .help-card-link {
        font-size: 14px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        margin-top: auto;
        padding-top: 12px;
        display: inline-block;
    }
    
    .help-card-image {
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 60px;
        height: 60px;
        z-index: 1;
    }
    
    .help-card-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .help-card:hover .help-card-image img {
        transform: scale(1.08);
    }
    
    /* Convert help cards to links if they aren't already */
    a.help-card {
        text-decoration: none;
        color: inherit;
    }
    
    @media (max-width: 992px) {
        .help-resources-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .help-card-title {
            font-size: 15px;
            max-width: 65%;
        }
        
        .help-card-image {
            width: 50px;
            height: 50px;
        }
    }
    
    @media (max-width: 576px) {
        .help-resources-grid {
            grid-template-columns: 1fr;
        }
        
        .help-card-title {
            max-width: 70%;
        }
    }
</style>

<!-- Inline editing styles -->
<style>
    /* Editable field styling */
    .editable-field {
        position: relative;
        padding: 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .editable-field:hover {
        background-color: rgba(44, 62, 80, 0.05);
    }
    
    .editable-field .edit-field-btn {
        position: absolute;
        right: 5px;
        top: 5px;
        background: none;
        border: none;
        font-size: 12px;
        color: #6c757d;
        opacity: 0;
        transition: opacity 0.2s ease;
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 3px;
    }
    
    .editable-field:hover .edit-field-btn {
        opacity: 1;
    }
    
    .editable-field .edit-field-btn:hover {
        color: #1976d2;
        background-color: rgba(25, 118, 210, 0.1);
    }
    
    /* Active editing state */
    .editable-field.editing {
        background-color: rgba(25, 118, 210, 0.1);
        box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.3);
    }
    
    .editable-field.editing .editable-content {
        display: none;
    }
    
    .editable-field.editing .edit-field-btn {
        display: none;
    }
    
    .editable-field .edit-form {
        padding: 5px;
    }
    
    .editable-field .field-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }
    
    .editable-field .field-actions button {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
    }
    
    .editable-field .save-btn {
        background-color: #1976d2;
        color: white;
        border: none;
    }
    
    .editable-field .cancel-btn {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
    }
</style>

<!-- Job Details Modal Specific Styles -->
<style>
    /* Job details modal improvements based on image */
    #jobDetailsModal .modal-body {
        padding: 0;
    }
    
    #jobDetailsModal .job-details {
        padding: 20px 24px;
    }
    
    #jobDetailsModal h3.mb-2 {
        font-size: 28px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px !important;
    }
    
    /* Category, budget, duration badges */
    #jobDetailsModal .job-details-header .badge {
        font-size: 14px;
        font-weight: normal;
        padding: 6px 12px;
        margin-right: 10px;
        background: #f5f7fa;
        color: #333;
        border-radius: 4px;
    }
    
    /* Active status badge */
    #jobDetailsModal .badge.bg-success {
        background-color: #28a745 !important;
        color: white;
        font-weight: 500;
        padding: 6px 15px;
        border-radius: 4px;
    }
    
    /* Section headings */
    #jobDetailsModal .job-details-body h5 {
        font-size: 18px;
        font-weight: 600;
        margin: 30px 0 15px 0;
        color: #333;
    }
    
    /* Description text */
    #jobDetailsModal .job-details-body .editable-content {
        line-height: 1.6;
        color: #333;
    }
    
    /* Skills badges */
    #jobDetailsModal .skills-list .badge {
        background-color: #f5f7fa;
        color: #333;
        font-weight: normal;
        font-size: 14px;
        padding: 6px 12px;
        margin: 0 8px 8px 0;
        border-radius: 4px;
    }
    
    /* Separator before footer */
    #jobDetailsModal .job-details-footer {
        border-top: 1px solid #eee;
        margin-top: 25px;
        padding-top: 20px;
    }
    
    /* Job meta info */
    #jobDetailsModal .job-meta {
        color: #555;
    }
    
    #jobDetailsModal .job-meta i {
        color: #777;
        width: 20px;
    }
    
    /* Action buttons */
    #jobDetailsModal .job-actions .btn {
        padding: 8px 16px;
        font-weight: 500;
    }
    
    /* Footer buttons */
    #jobDetailsModal .modal-footer .modal-button {
        min-width: 100px;
    }
    
    /* Make close button more visible */
    #jobDetailsModal .modal-close svg {
        width: 20px;
        height: 20px;
    }
    
    /* Editable fields - hover effect */
    #jobDetailsModal .editable-field:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    
    /* Media queries for better responsiveness */
    @media (max-width: 768px) {
        #jobDetailsModal .modal-container {
            max-height: 95vh;
        }
        
        #jobDetailsModal h3.mb-2 {
            font-size: 22px;
        }
        
        #jobDetailsModal .job-details {
            padding: 15px;
        }
        
        #jobDetailsModal .job-details-header .d-flex {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<!-- Job Details and Applications Styles -->
<style>
    /* Job Detail Cards */
    .job-detail-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .job-detail-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }
    
    .job-status-badge {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        font-weight: 500;
    }
    
    /* Job description formatting */
    .job-description {
        white-space: pre-line;
        line-height: 1.6;
        color: #333;
    }
    
    /* Application cards styling */
    .application-cards {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .application-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .application-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .applicant-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 12px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .applicant-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .applicant-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 15px;
    }
    
    .application-header {
        margin-bottom: 12px;
    }
    
    .application-details {
        padding: 10px 0;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 12px;
    }
    
    .application-bid {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.05);
    }
    
    .bid-amount {
        font-weight: 600;
        color: #2c3e50;
        font-size: 16px;
    }
    
    .bid-duration {
        color: #6c757d;
        font-size: 14px;
    }
    
    .application-message p {
        margin: 0;
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }
    
    .application-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .view-proposal-link {
        color: #2c3e50;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }
    
    .view-proposal-link:hover {
        text-decoration: underline;
    }
    
    .more-applications {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 8px;
        margin-top: 5px;
    }
    
    /* Contact button styling */
    .contact-applicant-btn {
        padding: 4px 10px;
        font-size: 13px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .job-detail-card {
            padding: 15px;
        }
        
        .job-detail-title {
            font-size: 16px;
            margin-bottom: 12px;
            padding-bottom: 10px;
        }
        
        .application-card {
            padding: 12px;
        }
        
        .applicant-avatar {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        
        .applicant-name {
            font-size: 14px;
        }
        
        .bid-amount {
            font-size: 15px;
        }
    }
</style>

<!-- Include modal functionality first -->
<script src="<?php echo URL_ROOT; ?>/public/js/components/modal.js"></script>

<!-- Include job modals -->
<?php include(APP_ROOT . '/views/jobs/job_modals.php'); ?>

<!-- Job interactions JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if all required elements exist
        console.log("DOM loaded, checking for required elements");
        
        // Check if job cards exist
        const jobCards = document.querySelectorAll('.job-card:not(.empty-job-card)');
        console.log(`Found ${jobCards.length} job cards`);
        
        // Check if modal exists
        const jobDetailsModal = document.getElementById('jobDetailsModal');
        if (jobDetailsModal) {
            console.log("jobDetailsModal exists");
        } else {
            console.error("jobDetailsModal NOT FOUND - check job_modals.php inclusion");
        }
        
        // Check if openModal function is available
        if (typeof openModal === 'function') {
            console.log("openModal function is available");
        } else {
            console.error("openModal function NOT FOUND - check modal.js inclusion");
        }
        
        // Initialize job interactions for the jobs list
        initializeJobInteractions();
        
        // Function to initialize all job interactions
        function initializeJobInteractions() {
            // Handle job card clicks to show job details
            const jobCards = document.querySelectorAll('.job-card:not(.empty-job-card)');
            console.log("Found job cards:", jobCards.length);
            jobCards.forEach(card => {
                card.addEventListener('click', function() {
                    console.log("Job card clicked!");
                    const jobId = this.getAttribute('data-job-id');
                    const jobTitle = this.querySelector('.job-title').textContent;
                    
                    console.log("Job ID:", jobId);
                    console.log("Job Title:", jobTitle);
                    
                    // Set the job ID for the edit button in the details modal
                    const editJobBtn = document.querySelector('.edit-job-btn');
                    if (editJobBtn) {
                        editJobBtn.setAttribute('data-job-id', jobId);
                    } else {
                        console.error("Could not find .edit-job-btn element");
                    }
                    
                    // Show job details modal
                    fetchJobDetails(jobId);
                    console.log("Calling openModal('jobDetailsModal')");
                    // Check if modal exists before opening
                    const modal = document.getElementById('jobDetailsModal');
                    if (modal) {
                        console.log("Found jobDetailsModal, opening it");
                        openModal('jobDetailsModal');
                    } else {
                        console.error("Could not find jobDetailsModal element");
                    }
                });
            });

        }

        function fetchJobDetails(jobId) {
            const detailsContainer = document.querySelector('.job-details-content');
            detailsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading job details...</p></div>';
            
            // Real AJAX call to fetch job details
            fetch(`<?php echo URL_ROOT; ?>/client/getJobDetails/${jobId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const job = data.job;
                        const statusClass = job.status === 'active' ? 'success' : job.status === 'paused' ? 'warning' : 'secondary';
                        
                        // Format currency
                        const formattedBudget = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(job.budget);
                        
                        // Format duration to be more readable
                        const formatDuration = (duration) => {
                            const durationMap = {
                                'less_than_1_week': 'Less than 1 week',
                                '1_to_2_weeks': '1-2 weeks',
                                '3_to_4_weeks': '3-4 weeks',
                                '1_to_3_months': '1-3 months',
                                '3_to_6_months': '3-6 months',
                                'more_than_6_months': 'More than 6 months'
                            };
                            return durationMap[duration] || duration || 'Not specified';
                        };
                        
                        // Build the details HTML with editable fields
                        detailsContainer.innerHTML = `
                            <div class="job-details" data-job-id="${job.id}">
                                <div class="job-details-header d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h3 class="mb-2 editable-field" data-field="title" data-original="${job.title}">
                                            <span class="editable-content">${job.title}</span>
                                            <button class="edit-field-btn" title="Edit title"><i class="fas fa-pencil-alt"></i></button>
                                        </h3>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge rounded-pill bg-light text-dark editable-field" data-field="category" data-original="${job.category}">
                                                <i class="fas fa-folder me-1"></i> 
                                                <span class="editable-content">${job.category}</span>
                                                <button class="edit-field-btn" title="Edit category"><i class="fas fa-pencil-alt"></i></button>
                                            </span>
                                            <span class="badge rounded-pill bg-light text-dark editable-field" data-field="budget" data-original="${job.budget}">
                                                <i class="fas fa-dollar-sign me-1"></i> 
                                                <span class="editable-content">${formattedBudget}</span>
                                                <button class="edit-field-btn" title="Edit budget"><i class="fas fa-pencil-alt"></i></button>
                                            </span>
                                            <span class="badge rounded-pill bg-light text-dark editable-field" data-field="duration" data-original="${job.duration || ''}">
                                                <i class="fas fa-clock me-1"></i> 
                                                <span class="editable-content">${formatDuration(job.duration)}</span>
                                                <button class="edit-field-btn" title="Edit duration"><i class="fas fa-pencil-alt"></i></button>
                                            </span>
                                        </div>
                                        <div class="job-meta small text-muted">
                                            <span><i class="fas fa-calendar-alt me-1"></i> Posted on ${new Date(job.created_at).toLocaleDateString()}</span>
                                            <span class="ms-3"><i class="fas fa-users me-1"></i> ${data.applications ? data.applications.length : 0} applications</span>
                                        </div>
                                    </div>
                                    <div class="status-wrapper editable-field" data-field="status" data-original="${job.status}">
                                        <span class="badge rounded-pill bg-${statusClass} job-status-badge">
                                            <span class="editable-content">${job.status.charAt(0).toUpperCase() + job.status.slice(1)}</span>
                                        </span>
                                        <button class="edit-field-btn" title="Change status"><i class="fas fa-pencil-alt"></i></button>
                                    </div>
                                </div>
                                
                                <div class="job-details-body mb-4">
                                    <div class="job-detail-card">
                                        <h5 class="job-detail-title"><i class="fas fa-align-left me-2"></i>Description</h5>
                                    <div class="editable-field" data-field="description" data-original="${job.description}">
                                            <div class="editable-content job-description">${job.description}</div>
                                        <button class="edit-field-btn" title="Edit description"><i class="fas fa-pencil-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="job-details-body mb-4">
                                    <div class="job-detail-card">
                                        <h5 class="job-detail-title"><i class="fas fa-tags me-2"></i>Skills</h5>
                                    <div class="editable-field" data-field="skills" data-original='${job.skills || "[]"}'>
                                        <div class="editable-content skills-list">
                                            ${formatSkills(job.skills)}
                                        </div>
                                        <button class="edit-field-btn" title="Edit skills"><i class="fas fa-pencil-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Applications List Section -->
                                <div class="job-applications-section mb-4">
                                    <div class="job-detail-card">
                                        <h5 class="job-detail-title d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-user-check me-2"></i>Applications (${data.applications ? data.applications.length : 0})</span>
                                            <button type="button" class="btn btn-sm btn-outline-primary view-all-applications-btn" 
                                                data-job-id="${job.id}" 
                                                data-job-title="${job.title}">
                                                View All
                                            </button>
                                        </h5>
                                        <div class="applications-list">
                                            ${formatApplicationsList(data.applications)}
                                    </div>
                                    </div>
                                </div>
                                
                                <div class="job-details-footer d-flex justify-content-end align-items-center pt-3 border-top">
                                    <div class="job-actions">
                                        <button type="button" class="btn btn-outline-primary me-2 view-applications-btn" 
                                            data-job-id="${job.id}" 
                                            data-job-title="${job.title}">
                                            <i class="fas fa-users me-1"></i> View Applications
                                        </button>
                                        <button type="button" class="btn btn-outline-danger delete-job-btn" 
                                            data-job-id="${job.id}" 
                                            data-job-title="${job.title}">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Initialize the buttons and inline editing for this job detail view
                        initializeJobDetailButtons();
                        initializeInlineEditing();
                    } else {
                        detailsContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> ${data.message || 'Failed to load job details'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    detailsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Error loading job details: ${error.message}
                        </div>
                    `;
                    console.error('Error:', error);
                });
        }
        
        // Helper function to format skills list
        function formatSkills(skillsJson) {
            try {
                const skills = JSON.parse(skillsJson || '[]');
                if (!skills.length) return '<span class="text-muted">No skills specified</span>';
                
                return skills.map(skill => 
                    `<span class="badge bg-light text-dark me-2 mb-2">${skill}</span>`
                ).join('');
            } catch (e) {
                console.error('Error parsing skills:', e);
                return '<span class="text-muted">Unable to display skills</span>';
            }
        }
        
        // Helper function to format applications list
        function formatApplicationsList(applications) {
            if (!applications || !applications.length) {
                return '<div class="text-muted py-3 text-center">No applications yet</div>';
            }
            
            // Show only the first 3 applications in the list
            const displayedApplications = applications.slice(0, 3);
            
            let html = '<div class="application-cards">';
            
            displayedApplications.forEach(app => {
                const date = new Date(app.created_at).toLocaleDateString();
                
                html += `
                    <div class="application-card">
                        <div class="application-header d-flex justify-content-between align-items-center">
                            <div class="applicant-info d-flex align-items-center">
                                <div class="applicant-avatar">
                                    <img src="${app.freelancer.avatar || '<?php echo URL_ROOT; ?>/public/img/avatar-placeholder.jpg'}" alt="${app.freelancer.name || 'Freelancer'}">
                                </div>
                                <div>
                                    <h6 class="applicant-name mb-0">${app.freelancer.name || 'Freelancer'}</h6>
                                    <span class="badge bg-light text-dark">${app.freelancer.title || 'Freelancer'}</span>
                                </div>
                            </div>
                            <div class="application-actions">
                                <button class="btn btn-sm btn-primary contact-applicant-btn" data-application-id="${app.id}">Contact</button>
                            </div>
                        </div>
                        <div class="application-details">
                            <div class="application-bid">
                                <span class="bid-amount">${formatCurrency(app.bid)}</span>
                                <span class="bid-duration">${app.duration || 'Not specified'}</span>
                            </div>
                            <div class="application-message">
                                <p>${app.cover_letter ? truncateText(app.cover_letter, 100) : 'No cover letter provided'}</p>
                            </div>
                        </div>
                        <div class="application-footer">
                            <small class="text-muted">Applied on ${date}</small>
                            <a href="#" class="view-proposal-link" data-application-id="${app.id}">View Full Proposal</a>
                        </div>
                    </div>
                `;
            });
            
            // If there are more applications than displayed, show a message
            if (applications.length > displayedApplications.length) {
                html += `
                    <div class="more-applications text-center py-2">
                        <span class="text-muted">+ ${applications.length - displayedApplications.length} more applications</span>
                    </div>
                `;
            }
            
            html += '</div>';
            return html;
        }
        
        // Helper function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount || 0);
        }
        
        // Helper function to truncate text
        function truncateText(text, maxLength) {
            if (!text) return '';
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }
        
        // Initialize buttons in the job details view
        function initializeJobDetailButtons() {
            // View applications button
            const viewApplicationsButtons = document.querySelectorAll('.view-applications-btn');
            viewApplicationsButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    const jobTitle = this.getAttribute('data-job-title');
                    
                    document.querySelector('.job-title-placeholder').textContent = jobTitle;
                    
                    // Close the job details modal and open applications modal
                    closeModal('jobDetailsModal');
                    
                    // Show applications modal
                    setTimeout(() => {
                        loadApplications(jobId);
                        openModal('viewApplicationsModal');
                    }, 300);
                });
            });
            
            // Delete job button
            const deleteButtons = document.querySelectorAll('.delete-job-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    const jobTitle = this.getAttribute('data-job-title');
                    
                    document.getElementById('delete_job_id').value = jobId;
                    document.querySelector('.job-title-to-delete').textContent = jobTitle;
                    
                    // Close the job details modal and open delete confirmation
                    closeModal('jobDetailsModal');
                    
                    // Show delete confirmation modal
                    setTimeout(() => {
                        openModal('deleteJobModal');
                    }, 300);
                });
            });
        }
        
        // Function to initialize inline editing functionality
        function initializeInlineEditing() {
            const editButtons = document.querySelectorAll('.edit-field-btn');
            
            editButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const field = this.closest('.editable-field');
                    const fieldName = field.getAttribute('data-field');
                    const originalValue = field.getAttribute('data-original');
                    const jobId = field.closest('.job-details').getAttribute('data-job-id');
                    
                    // Add editing class
                    field.classList.add('editing');
                    
                    // Create edit form based on field type
                    let formHTML = '';
                    
                    switch(fieldName) {
                        case 'title':
                            formHTML = `
                                <div class="edit-form">
                                    <input type="text" class="form-control" value="${originalValue}" id="edit-${fieldName}">
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'description':
                            formHTML = `
                                <div class="edit-form">
                                    <textarea class="form-control" rows="5" id="edit-${fieldName}">${originalValue}</textarea>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'budget':
                            formHTML = `
                                <div class="edit-form">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" min="1" step="0.01" class="form-control" value="${originalValue}" id="edit-${fieldName}">
                                    </div>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'category':
                            formHTML = `
                                <div class="edit-form">
                                    <select class="form-control" id="edit-${fieldName}">
                                        <option value="Web Development" ${originalValue === 'Web Development' ? 'selected' : ''}>Web Development</option>
                                        <option value="Mobile Development" ${originalValue === 'Mobile Development' ? 'selected' : ''}>Mobile Development</option>
                                        <option value="UI/UX Design" ${originalValue === 'UI/UX Design' ? 'selected' : ''}>UI/UX Design</option>
                                        <option value="Data Science" ${originalValue === 'Data Science' ? 'selected' : ''}>Data Science</option>
                                        <option value="Writing" ${originalValue === 'Writing' ? 'selected' : ''}>Writing</option>
                                        <option value="Marketing" ${originalValue === 'Marketing' ? 'selected' : ''}>Marketing</option>
                                        <option value="Other" ${originalValue === 'Other' ? 'selected' : ''}>Other</option>
                                    </select>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'status':
                            formHTML = `
                                <div class="edit-form">
                                    <select class="form-control" id="edit-${fieldName}">
                                        <option value="active" ${originalValue === 'active' ? 'selected' : ''}>Active</option>
                                        <option value="paused" ${originalValue === 'paused' ? 'selected' : ''}>Paused</option>
                                        <option value="closed" ${originalValue === 'closed' ? 'selected' : ''}>Closed</option>
                                    </select>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'duration':
                            formHTML = `
                                <div class="edit-form">
                                    <select class="form-control" id="edit-${fieldName}">
                                        <option value="less_than_1_week" ${originalValue === 'less_than_1_week' ? 'selected' : ''}>Less than 1 week</option>
                                        <option value="1_to_2_weeks" ${originalValue === '1_to_2_weeks' ? 'selected' : ''}>1-2 weeks</option>
                                        <option value="3_to_4_weeks" ${originalValue === '3_to_4_weeks' ? 'selected' : ''}>3-4 weeks</option>
                                        <option value="1_to_3_months" ${originalValue === '1_to_3_months' ? 'selected' : ''}>1-3 months</option>
                                        <option value="3_to_6_months" ${originalValue === '3_to_6_months' ? 'selected' : ''}>3-6 months</option>
                                        <option value="more_than_6_months" ${originalValue === 'more_than_6_months' ? 'selected' : ''}>More than 6 months</option>
                                    </select>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        case 'skills':
                            let skillsValue = '';
                            try {
                                const skills = JSON.parse(originalValue);
                                skillsValue = skills.join(', ');
                            } catch (e) {
                                console.error('Error parsing skills:', e);
                                skillsValue = '';
                            }
                            
                            formHTML = `
                                <div class="edit-form">
                                    <input type="text" class="form-control" value="${skillsValue}" id="edit-${fieldName}" placeholder="Comma-separated skills">
                                    <small class="text-muted">Separate skills with commas (e.g., PHP, JavaScript, HTML)</small>
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                            break;
                            
                        default:
                            formHTML = `
                                <div class="edit-form">
                                    <input type="text" class="form-control" value="${originalValue}" id="edit-${fieldName}">
                                    <div class="field-actions">
                                        <button type="button" class="cancel-btn">Cancel</button>
                                        <button type="button" class="save-btn">Save</button>
                                    </div>
                                </div>
                            `;
                    }
                    
                    // Add form to the field
                    field.insertAdjacentHTML('beforeend', formHTML);
                    
                    // Focus on the input
                    const input = field.querySelector('input, textarea, select');
                    if (input) input.focus();
                    
                    // Handle cancel button
                    field.querySelector('.cancel-btn').addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Remove editing class and form
                        field.classList.remove('editing');
                        const form = field.querySelector('.edit-form');
                        if (form) form.remove();
                    });
                    
                    // Handle save button
                    field.querySelector('.save-btn').addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Get new value
                        const input = field.querySelector('#edit-' + fieldName);
                        let newValue = input.value.trim();
                        
                        // Special handling for skills
                        if (fieldName === 'skills') {
                            const skillsArray = newValue.split(',')
                                .map(s => s.trim())
                                .filter(s => s !== '');
                            newValue = JSON.stringify(skillsArray);
                        }
                        
                        // Update via AJAX
                        updateJobField(jobId, fieldName, newValue, field);
                    });
                });
            });
        }
        
        // Function to update a job field via AJAX
        function updateJobField(jobId, fieldName, newValue, fieldElement) {
            // Show loading indicator
            fieldElement.classList.add('loading');
            
            const formData = new FormData();
            formData.append('job_id', jobId);
            formData.append('field', fieldName);
            formData.append('value', newValue);
            
            fetch('<?php echo URL_ROOT; ?>/client/updateJobField', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    let displayValue = newValue;
                    
                    // Format values for display
                    if (fieldName === 'budget') {
                        displayValue = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(newValue);
                    } else if (fieldName === 'skills') {
                        try {
                            displayValue = formatSkills(newValue);
                        } catch (e) {
                            console.error('Error formatting skills:', e);
                        }
                    } else if (fieldName === 'status') {
                        displayValue = newValue.charAt(0).toUpperCase() + newValue.slice(1);
                        
                        // Update status badge color
                        const statusBadge = fieldElement.querySelector('.job-status-badge');
                        if (statusBadge) {
                            // Remove existing color classes
                            statusBadge.classList.remove('bg-success', 'bg-warning', 'bg-secondary');
                            
                            // Add new color class
                            if (newValue === 'active') {
                                statusBadge.classList.add('bg-success');
                            } else if (newValue === 'paused') {
                                statusBadge.classList.add('bg-warning');
                            } else {
                                statusBadge.classList.add('bg-secondary');
                            }
                        }
                    } else if (fieldName === 'duration') {
                        // Map duration values to readable text
                        const durationMap = {
                            'less_than_1_week': 'Less than 1 week',
                            '1_to_2_weeks': '1-2 weeks',
                            '3_to_4_weeks': '3-4 weeks',
                            '1_to_3_months': '1-3 months',
                            '3_to_6_months': '3-6 months',
                            'more_than_6_months': 'More than 6 months'
                        };
                        displayValue = durationMap[newValue] || newValue;
                    }
                    
                    // Set content and data attributes
                    fieldElement.querySelector('.editable-content').innerHTML = displayValue;
                    fieldElement.setAttribute('data-original', newValue);
                    
                    // Show success feedback
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success mt-2 mb-0 py-1 px-2';
                    successMessage.innerHTML = '<small>Updated successfully</small>';
                    fieldElement.appendChild(successMessage);
                    
                    setTimeout(() => {
                        if (fieldElement.contains(successMessage)) {
                            fieldElement.removeChild(successMessage);
                        }
                    }, 2000);
                } else {
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'alert alert-danger mt-2 mb-0 py-1 px-2';
                    errorMessage.innerHTML = `<small>${data.message || 'Failed to update'}</small>`;
                    fieldElement.appendChild(errorMessage);
                    
                    setTimeout(() => {
                        if (fieldElement.contains(errorMessage)) {
                            fieldElement.removeChild(errorMessage);
                        }
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'alert alert-danger mt-2 mb-0 py-1 px-2';
                errorMessage.innerHTML = `<small>Error: ${error.message}</small>`;
                fieldElement.appendChild(errorMessage);
                
                setTimeout(() => {
                    if (fieldElement.contains(errorMessage)) {
                        fieldElement.removeChild(errorMessage);
                    }
                }, 3000);
            })
            .finally(() => {
                // Remove loading indicator and editing state
                fieldElement.classList.remove('loading');
                fieldElement.classList.remove('editing');
                
                // Remove form
                const form = fieldElement.querySelector('.edit-form');
                if (form) form.remove();
            });
        }
        
        // Function to load applications for a job
        function loadApplications(jobId) {
            const applicationsContainer = document.querySelector('.applications-container');
            applicationsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading applications...</p></div>';
            
            // Real AJAX call to fetch applications
            fetch(`<?php echo URL_ROOT; ?>/client/getJobApplications/${jobId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.applications && data.applications.length > 0) {
                            let html = '';
                            data.applications.forEach(app => {
                                html += `
                                    <div class="application-card">
                                        <div class="application-header">
                                            <div class="applicant-info">
                                                <img src="${app.profile_image || '<?php echo URL_ROOT; ?>/public/img/default-avatar.png'}" alt="${app.freelancer_name}" class="applicant-avatar">
                                                <div>
                                                    <h4 class="applicant-name">${app.freelancer_name}</h4>
                                                    <p class="applicant-headline">${app.headline || 'Freelancer'}</p>
                                                    <p class="applicant-location">${app.location || 'Location not provided'}</p>
                                                </div>
                                            </div>
                                            <span class="application-date">${formatDate(app.created_at)}</span>
                                        </div>
                                        <div class="application-content">
                                            <p>${app.proposal}</p>
                                        </div>
                                        <div class="application-footer">
                                            <div class="application-attachments">
                                                ${app.attachments ? formatAttachments(app.attachments) : ''}
                                            </div>
                                            <div class="application-actions">
                                                <button class="btn btn-outline-primary btn-sm me-2" data-application-id="${app.id}" data-freelancer-id="${app.freelancer_id}">
                                                    <i class="fas fa-comment-dots me-1"></i> Message
                                                </button>
                                                <button class="btn btn-primary btn-sm hire-btn" data-application-id="${app.id}" data-job-id="${app.job_id}">
                                                    <i class="fas fa-user-check me-1"></i> Hire
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            applicationsContainer.innerHTML = html;
                            
                            // Initialize application buttons
                            initializeApplicationButtons();
                        } else {
                            applicationsContainer.innerHTML = '<div class="no-applications text-center py-4"><p>No applications have been received for this job yet.</p></div>';
                        }
                    } else {
                        applicationsContainer.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> ${data.message || 'Failed to load applications'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    applicationsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Error loading applications: ${error.message}
                        </div>
                    `;
                });
        }
        
        // Helper function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays < 1) {
                return 'Today';
            } else if (diffDays === 1) {
                return 'Yesterday';
            } else if (diffDays < 7) {
                return `${diffDays} days ago`;
            } else if (diffDays < 30) {
                const weeks = Math.floor(diffDays / 7);
                return `${weeks} week${weeks > 1 ? 's' : ''} ago`;
            } else {
                return date.toLocaleDateString();
            }
        }
        
        // Helper function to format attachments
        function formatAttachments(attachmentsJson) {
            try {
                const attachments = JSON.parse(attachmentsJson);
                return attachments.map(att => `
                    <span class="attachment-preview">
                        <i class="fas fa-file-alt"></i> ${att.name || att}
                    </span>
                `).join('');
            } catch (e) {
                console.error('Error parsing attachments:', e);
                return '';
            }
        }
        
        // Initialize application interaction buttons
        function initializeApplicationButtons() {
            // Hire button functionality
            document.querySelectorAll('.hire-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const applicationId = this.getAttribute('data-application-id');
                    const jobId = this.getAttribute('data-job-id');
                    
                    if (confirm('Are you sure you want to hire this freelancer for this job?')) {
                        hireFreelancer(applicationId, jobId, this);
                    }
                });
            });
            
            // Message button functionality - redirect to messages
            document.querySelectorAll('.application-actions .btn-outline-primary').forEach(btn => {
                btn.addEventListener('click', function() {
                    const freelancerId = this.getAttribute('data-freelancer-id');
                    // Redirect to messages with this freelancer
                    window.location.href = `<?php echo URL_ROOT; ?>/messages/view/${freelancerId}`;
                });
            });
        }
        
        // Function to hire a freelancer
        function hireFreelancer(applicationId, jobId, button) {
            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
            
            const formData = new FormData();
            formData.append('application_id', applicationId);
            formData.append('job_id', jobId);
            
            fetch('<?php echo URL_ROOT; ?>/client/hireFreelancer', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then((data) => {
                if (data.success) {
                    // Show success message and redirect to conversation
                    alert('Freelancer hired successfully! You can now communicate about project details.');
                    
                    if (data.conversation_id) {
                        window.location.href = `<?php echo URL_ROOT; ?>/messages/view/${data.conversation_id}`;
                    } else {
                        // Refresh the current page
                        window.location.reload();
                    }
                } else {
                    // Show error and restore button
                    alert(`Error: ${data.message || 'Failed to hire freelancer'}`);
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-user-check me-1"></i> Hire';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-user-check me-1"></i> Hire';
            });
        }
    });
</script>
</body>
</html>