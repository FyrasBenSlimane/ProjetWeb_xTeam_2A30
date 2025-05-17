<?php

$projects = $data['projects'] ?? [];
$is_logged_in = $data['is_logged_in'] ?? (isset($_SESSION['user_id']));
$candidatures = $data['candidatures'] ?? [];

?>

<div class="projects-page">
    <style>
        .projects-page {
            padding: 2rem 0;
            background-color: #f9fafb;
            min-height: calc(100vh - 80px);
        }
        .projects-page .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .projects-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        .projects-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .projects-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto;
        }
        .projects-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding: 0 1rem;
        }
        .projects-filters {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .filter-select {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            background-color: white;
            font-size: 0.875rem;
            color: #475569;
        }
        .projects-search {
            position: relative;
            width: 250px;
        }
        .projects-search input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .projects-search svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1rem;
            height: 1rem;
            color: #94a3b8;
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
        }
        .project-card {
            background-color: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .project-image {
            height: 160px;
            background-color: #e2e8f0;
            position: relative;
            overflow: hidden;
        }
        .project-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .project-status {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
        }
        .status-active {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .status-draft {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .status-completed {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .status-canceled {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .project-content {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .project-category {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .project-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .project-description {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
            flex-grow: 1;
        }
        .project-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #64748b;
            margin-top: auto;
        }
        .project-date {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .project-participants {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .project-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .project-skills {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .skill-tag {
            padding: 0.25rem 0.5rem;
            background-color: #f1f5f9;
            border-radius: 9999px;
            font-size: 0.75rem;
            color: #475569;
        }        .project-action {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .apply-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .apply-btn:hover {
            background-color: #1e293b;
            transform: translateY(-1px);
        }
        .apply-btn:active {
            transform: translateY(0);
        }
        .applied-btn {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }
        .empty-state-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .empty-state-description {
            max-width: 500px;
            margin: 0 auto 1.5rem;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
            padding: 0 1rem;
        }
        .pagination-item {
            padding: 0.35rem 0.6rem;
            margin: 0 0.2rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .pagination-item:hover {
            background-color: #f1f5f9;
        }
        .pagination-item.active {
            background-color: #2c3e50;
            color: white;
        }
        @media (max-width: 768px) {
            .projects-actions {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            .projects-filters {
                width: 100%;
                flex-wrap: wrap;
            }
            .projects-search {
                width: 100%;
            }
            .create-project-btn {
                width: 100%;
                justify-content: center;
            }
        }    /* Class for body when modal is open */
        body.modal-open {
            overflow: hidden;
        }        /* New Modal Styles - Complete rewrite */        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 9999;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: auto; /* Allow click events */
        }
        
        .modal-overlay.is-visible {
            display: flex;
            opacity: 1;
            pointer-events: auto; /* Explicitly ensure click events work */
        }
          .modal-container {
            background-color: #fff;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            border-radius: 8px;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            position: relative;
            padding: 0;
            display: flex;
            flex-direction: column;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            margin: 1.5rem;
            outline: none;
            z-index: 10000; /* Ensure this is higher than the overlay */
            pointer-events: auto; /* Ensures click events are captured */
        }
        
        .modal-overlay.is-visible .modal-container {
            transform: scale(1);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            position: relative;
            z-index: 1;
        }
        
        .modal-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.75rem;
            line-height: 1;
            color: #777;
            padding: 0;
            z-index: 2; /* Ensure clickable */
        }
        
        .modal-close:hover {
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
            flex-grow: 1;
            overflow-y: auto;
            position: relative;
            z-index: 1;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eaeaea;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        
        /* Form specific styles inside modal */
        .form-group {
            margin-bottom: 20px;
            position: relative;
            z-index: 2; /* Ensure inputs are on top */
        }
        
        .form-control {
            display: block;
            width: 100%;
            padding: 10px 15px;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            position: relative;
            z-index: 2; /* Ensure inputs are on top */
        }
        
        .form-control:focus {
            border-color: #4f46e5;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
            z-index: 3; /* Higher z-index when focused */
        }
        
        textarea.form-control {
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            font-weight: 500;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 4px;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            position: relative;
            z-index: 2; /* Ensure buttons are on top */
        }
        
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .btn-primary:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }
        
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-secondary:hover {
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }
        .modal-overlay.fade-out .modal-container {
            transform: translateY(-20px);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .modal-title { margin: 0; font-size: 1.5rem; }
        .modal-close {
            background: none; border: none; font-size: 1.75rem; font-weight: bold; cursor: pointer;
        }        .modal-body {
            flex-grow: 1; /* Let the body expand to fill available space */
            overflow-y: auto; /* This will make the body scrollable if its content is too tall */
            padding: 15px 0; /* Add some padding between header and body */
            position: relative; /* Create a new stacking context */
            z-index: 1; /* Ensure elements inside can be interacted with */
        }
        .modal-body .form-group { 
            margin-bottom: 1rem;
            position: relative; /* Ensure form groups create their own stacking context */
        }
        .modal-body .form-control { 
            width: 100%; 
            padding: .5rem .75rem; 
            border: 1px solid #ced4da; 
            border-radius: .25rem;
            font-size: 1rem;
            line-height: 1.5;
            position: relative; /* Help with stacking context */
            z-index: 2; /* Ensure inputs are clickable */
        }        .modal-footer {
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
            margin-top: 15px;
            position: relative;
            z-index: 3; /* Ensure footer is above other content */
        }
        .modal-footer .btn { 
            margin-left: 0.5rem; 
            padding: 0.5rem 1rem; 
            border-radius: 0.25rem; 
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            position: relative;
            z-index: 4; /* Ensure buttons are clickable */
        }
        .modal-footer .btn-primary { 
            background-color: #007bff; 
            color: white; 
            border: 1px solid #007bff;
        }
        .modal-footer .btn-primary:hover { 
            background-color: #0069d9; 
            border-color: #0062cc;
        }
        .modal-footer .btn-primary:disabled {
            background-color: #80bdff;
            border-color: #80bdff;
            cursor: not-allowed;
        }
        .modal-footer .btn-secondary { 
            background-color: #6c757d; 
            color: white; 
            border: 1px solid #6c757d;
        }
        .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        /* Additional style for the project details modal */
        .project-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .details-description {
            margin-top: 1rem;
        }
        #modal-project-description {
            white-space: pre-wrap;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 10px;
            margin-top: 5px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        #apply-project-form label {
            font-weight: 500;
            margin-bottom: 0.25rem;
            display: block;
        }
        #apply-project-form small {
            color: #64748b;
            display: block;
            margin-top: 0.25rem;
        }
        .form-control:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
        .btn-primary, .btn-secondary {
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        .btn-loading:after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-top: -8px;
            margin-left: -8px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .form-error {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }
        .form-group.has-error .form-control {
            border-color: #dc3545;
        }
        .form-group.has-error .form-error {
            display: block;
        }
        @media (max-width: 768px) {
            .project-details-grid {
                grid-template-columns: 1fr;
            }
        }
        /* Toast notification styles */
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            max-width: 350px;
            background-color: white;
            color: #333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            padding: 16px;
            z-index: 10000;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
            display: flex;
            align-items: flex-start;
            overflow: hidden;
        }
        .notification-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .notification-toast.success {
            border-left: 4px solid #10b981;
        }
        .notification-toast.error {
            border-left: 4px solid #ef4444;
        }
        .notification-toast.info {
            border-left: 4px solid #3b82f6;
        }
        .notification-content {
            flex: 1;
        }
        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }
        .notification-message {
            font-size: 0.9rem;
            color: #4b5563;
        }
        .notification-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            margin-left: 12px;
            line-height: 1;
        }
        .notification-close:hover {
            color: #4b5563;
        }
        .notification-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }
        .notification-progress-bar {
            height: 100%;
            width: 100%;
        }
        .notification-toast.success .notification-progress-bar {
            background-color: #10b981;
        }
        .notification-toast.error .notification-progress-bar {
            background-color: #ef4444;
        }
        .notification-toast.info .notification-progress-bar {
            background-color: #3b82f6;
        }

        /* Form interaction fixes */
        #apply-project-form input,
        #apply-project-form textarea,
        #apply-project-form button,
        #apply-project-form select,
        #apply-project-form label {
            position: relative;
            z-index: 10; /* Higher z-index to ensure interactivity */
        }
        
        .modal-container {
            isolation: isolate; /* Creates a new stacking context */
        }
        
        .modal-footer .btn {
            position: relative;
            z-index: 10; /* Higher z-index to ensure button clicks work */
        }
        
        .form-control {
            position: relative;
            z-index: 5;
        }
        
        .form-control:focus {
            z-index: 6;
        }        /* Modal Focus and Accessibility Improvements */
        .modal-container:focus {
            outline: none;
        }

        .modal-container *:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }

        /* Ensure interactive form elements are visible and have proper stacking context */
        .modal-body input, 
        .modal-body textarea, 
        .modal-body select, 
        .modal-body button {
            position: relative;
            z-index: 5; /* Higher value to ensure interactivity */
        }

        /* Improved focus indication */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            z-index: 10; /* Even higher when focused */
        }

        /* Ensure modal containers don't interfere with input focus */
        .modal-overlay.is-visible {
            display: flex !important;
            opacity: 1 !important;
        }

        /* Fix interactive elements to ensure they're clickable */
        .modal-footer .btn {
            position: relative;
            z-index: 20; /* Higher to ensure buttons are on top */
            pointer-events: auto;
        }
    </style>

    <div class="container">
        <div class="projects-header">
            <h1 class="projects-title">Collaborative Projects</h1>
            <p class="projects-subtitle">Join forces with talented professionals on exciting projects. Collaborate, learn, and build amazing things together.</p>
        </div>

        <div class="projects-actions">
            <div class="projects-filters">
                <select class="filter-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="web-development">Web Development</option>
                    <option value="mobile-app">Mobile App</option>
                    <option value="design">Design</option>
                    <option value="marketing">Marketing</option>
                    <option value="data-science">Data Science</option>
                    <option value="other">Other</option>
                </select>
                <select class="filter-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
                <div class="projects-search">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    <input type="text" id="projectSearch" placeholder="Search projects...">
                </div>
            </div>
        </div>

        <?php if(isset($projects) && !empty($projects)): 
        ?>
        <div class="projects-grid">
            <?php foreach($projects as $project): ?>
                <div class="project-card">
                    <div class="project-image">
                        <?php if(!empty($project->image)): ?>
                            <img src="<?php echo $project->image; ?>" alt="<?php echo $project->title; ?>">
                        <?php else: ?>
                            <img src="<?php echo URL_ROOT; ?>/public/img/project-placeholder.jpg" alt="Project image">
                        <?php endif; ?>
                        <div class="project-status status-<?php echo $project->status; ?>">
                            <?php echo ucfirst($project->status); ?>
                        </div>
                    </div>
                    <div class="project-content">
                        <div class="project-category"><?php echo ucfirst($project->category); ?></div>
                        <h3 class="project-title"><?php echo $project->title; ?></h3>
                        <p class="project-description">
                            <?php echo substr($project->description, 0, 100) . (strlen($project->description) > 100 ? '...' : ''); ?>
                        </p>
                        <div class="project-meta">
                            <div class="project-date">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                </svg>
                                <?php echo date('M j, Y', strtotime($project->start_date)); ?>
                            </div>
                            <div class="project-participants">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16.5h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16.5z" />
                                </svg>
                                <?php
                                // Count approved members
                                $approved = isset($project->participants_count) ? $project->participants_count : 0;
                                $max = $project->max_participants ? $project->max_participants : '∞';
                                echo $approved . '/' . $max;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="project-footer">
                        <div class="project-skills">
                            <?php
                            // Show skills tags
                            if(!empty($project->skills_required)) {
                                $skills = explode(',', $project->skills_required);
                                $displaySkills = array_slice($skills, 0, 2); // Show only 2 skills
                                foreach($displaySkills as $skill) {
                                    echo '<span class="skill-tag">' . trim($skill) . '</span>';
                                }
                                if(count($skills) > 2) {
                                    echo '<span class="skill-tag">+' . (count($skills) - 2) . '</span>';
                                }
                            }
                            ?>
                        </div>
                        <?php if($is_logged_in): ?>
                            <?php
                            $hasApplied = false;
                            if(isset($candidatures) && !empty($candidatures)) {
                                foreach($candidatures as $candidature) {
                                    if($candidature->project_id == $project->id) {
                                        $hasApplied = true;
                                        break;
                                    }
                                }
                            }                            if($hasApplied): ?>
                                <button class="project-action applied-btn" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                    Applied
                                </button>
                            <?php else: 
                                $projectJson = htmlspecialchars(json_encode($project), ENT_QUOTES, 'UTF-8');
                                echo '<button class="project-action apply-btn" data-project-id="' . $project->id . '" onclick="openProjectModal(' . $projectJson . ')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                    View Details & Apply
                                </button>';
                            endif;
                            ?>
                        <?php else: ?>
                            <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="project-action apply-btn">Login to Apply</a>
                        <?php endif; // End of if($is_logged_in) ?>
                    </div>
                </div>
            <?php endforeach; // End of foreach($projects as $project) ?>
        </div>
        <div class="pagination">
            <div class="pagination-item active">1</div>
            <div class="pagination-item">2</div>
            <div class="pagination-item">3</div>
            <div class="pagination-item">...</div>
            <div class="pagination-item">Next</div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <h3 class="empty-state-title">No projects found</h3>
            <p class="empty-state-description">There are no active projects at the moment. Check back later or create your own project to start collaborating.</p>
            <?php if($is_logged_in): ?>
            <a href="<?php echo URL_ROOT; ?>/projects/create" class="create-project-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Create Project
            </a>
            <?php else: ?>
            <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="create-project-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M6 10a.75.75 0 01.75-.75h9.546l-1.048-.943a.75.75 0 111.004-1.114l2.5 2.25a.75.75 0 010 1.114l-2.5 2.25a.75.75 0 11-1.004-1.114l1.048-.943H6.75A.75.75 0 016 10z" clip-rule="evenodd" />
                </svg>
                Login to Create
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Project Details Modal -->
<div id="project-details-modal" class="modal-overlay">
    <div class="modal-container" tabindex="-1">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-project-title">Project Title</h3>
            <button type="button" class="modal-close" onclick="closeProjectModal()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <div id="modal-project-details">
                <div class="project-details-grid">
                    <div class="details-left">
                        <p><strong>Category:</strong> <span id="modal-project-category"></span></p>
                        <p><strong>Status:</strong> <span id="modal-project-status"></span></p>
                        <p><strong>Start Date:</strong> <span id="modal-project-start-date"></span></p>
                        <p><strong>End Date:</strong> <span id="modal-project-end-date"></span></p>
                    </div>
                    <div class="details-right">
                        <p><strong>Participants:</strong> <span id="modal-project-participants"></span>/<span id="modal-project-max-participants"></span></p>
                        <p><strong>Location:</strong> <span id="modal-project-location"></span> (<span id="modal-project-remote"></span>)</p>
                        <p><strong>Skills Required:</strong> <span id="modal-project-skills-required"></span></p>
                    </div>
                </div>
                <div class="details-description">
                    <p><strong>Description:</strong></p>
                    <div id="modal-project-description"></div>
                </div>
            </div>
            <hr>
            <h4>Apply for this Project</h4>
            <form id="apply-project-form">
                <input type="hidden" id="modal-project-id" name="project_id">
                <div class="form-group">
                    <label for="application-message">Why do you want to join this project?</label>
                    <textarea id="application-message" name="message" class="form-control" rows="3" placeholder="Tell the project creator why you'd be a good fit..."></textarea>
                    <div class="form-error" id="message-error">Please provide some information about why you want to join.</div>
                </div>
                <div class="form-group">
                    <label for="application-skills">Your Relevant Skills:</label>
                    <input type="text" id="application-skills" name="skills" class="form-control" placeholder="e.g., PHP, JavaScript, Project Management">
                    <div class="form-error" id="skills-error">Please list some relevant skills.</div>
                    <small>Separate skills with commas. Leave blank to use your profile skills.</small>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeProjectModal()">Close</button>
            <button type="button" class="btn btn-primary" onclick="submitProjectApplication()">Submit Application</button>
        </div>
    </div>
</div>

<!-- Toast notification container -->
<div id="notification-container"></div>

<style>
    /* Additional style for the project details modal */
    .project-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .details-description {
        margin-top: 1rem;
    }
    #modal-project-description {
        white-space: pre-wrap;
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #eee;
        padding: 10px;
        margin-top: 5px;
        background-color: #f9fafb;
        border-radius: 4px;
    }
    #apply-project-form label {
        font-weight: 500;
        margin-bottom: 0.25rem;
        display: block;
    }
    #apply-project-form small {
        color: #64748b;
        display: block;
        margin-top: 0.25rem;
    }
    .form-control:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
    .btn-primary, .btn-secondary {
        transition: all 0.2s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    .btn-loading {
        position: relative;
        color: transparent !important;
    }
    .btn-loading:after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-top: -8px;
        margin-left: -8px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .form-error {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: none;
    }
    .form-group.has-error .form-control {
        border-color: #dc3545;
    }
    .form-group.has-error .form-error {
        display: block;
    }
    @media (max-width: 768px) {
        .project-details-grid {
            grid-template-columns: 1fr;
        }
    }
    /* Toast notification styles */
    .notification-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        max-width: 350px;
        background-color: white;
        color: #333;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        padding: 16px;
        z-index: 10000;
        transform: translateY(100px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        display: flex;
        align-items: flex-start;
        overflow: hidden;
    }
    .notification-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .notification-toast.success {
        border-left: 4px solid #10b981;
    }
    .notification-toast.error {
        border-left: 4px solid #ef4444;
    }
    .notification-toast.info {
        border-left: 4px solid #3b82f6;
    }
    .notification-content {
        flex: 1;
    }
    .notification-title {
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }
    .notification-message {
        font-size: 0.9rem;
        color: #4b5563;
    }
    .notification-close {
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0;
        margin-left: 12px;
        line-height: 1;
    }
    .notification-close:hover {
        color: #4b5563;
    }
    .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.1);
    }
    .notification-progress-bar {
        height: 100%;
        width: 100%;
    }
    .notification-toast.success .notification-progress-bar {
        background-color: #10b981;
    }
    .notification-toast.error .notification-progress-bar {
        background-color: #ef4444;
    }
    .notification-toast.info .notification-progress-bar {
        background-color: #3b82f6;
    }
</style>

<script>
// Add event listeners after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Initialize the project application form
        const applyForm = document.getElementById('apply-project-form');
        if (applyForm) {
            // Reset the form on page load
            applyForm.reset();
            
            // Clear any previous form data and errors
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.remove('has-error');
            });
            
            // Enable form submission with CTRL+Enter
            applyForm.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' && (event.ctrlKey || event.metaKey)) {
                    event.preventDefault();
                    submitProjectApplication();
                }
            });
        }
        
        // Enable pressing ESC to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && 
                document.getElementById('project-details-modal') && 
                document.getElementById('project-details-modal').classList.contains('is-visible')) {
                closeProjectModal();
            }
        });
        
        // Set up filter functionality if elements exist
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('projectSearch');
        
        // Initialize any filter listeners
        if (categoryFilter) {
            categoryFilter.addEventListener('change', filterProjects);
        }
        
        if (statusFilter) {
            statusFilter.addEventListener('change', filterProjects);
        }
          if (searchInput) {
            searchInput.addEventListener('input', filterProjects);
        }
        
        // Additional event listeners to improve modal interactivity
        // Get all form inputs in the modal
        const formInputs = document.querySelectorAll('#apply-project-form input, #apply-project-form textarea');
        
        // For each input, add focus and blur event handlers
        formInputs.forEach(input => {
            // On focus, increase z-index to make sure the element is on top
            input.addEventListener('focus', function() {
                this.style.position = 'relative';
                this.style.zIndex = '100';
            });
            
            // Reset z-index on blur
            input.addEventListener('blur', function() {
                this.style.zIndex = '2';
            });
        });
        
        // Ensure the modal container catches all events
        const modalContainer = document.querySelector('.modal-container');
        if (modalContainer) {
            modalContainer.addEventListener('click', function(e) {
                // This prevents the click from bubbling up to the overlay
                e.stopPropagation();
            });
        }
        
        // Improve focus management for accessibility
        const modal = document.getElementById('project-details-modal');
        if (modal) {
            modal.addEventListener('shown', function() {
                // Get all focusable elements in the modal
                const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (focusableElements.length > 0) {
                    // Focus the first form element
                    focusableElements[0].focus();
                    
                    // Set up focus trap
                    const firstFocusableElement = focusableElements[0];
                    const lastFocusableElement = focusableElements[focusableElements.length - 1];
                    
                    // Handle Tab key to keep focus within modal
                    modal.addEventListener('keydown', function(e) {
                        if (e.key === 'Tab') {
                            if (e.shiftKey && document.activeElement === firstFocusableElement) {
                                e.preventDefault();
                                lastFocusableElement.focus();
                            } else if (!e.shiftKey && document.activeElement === lastFocusableElement) {
                                e.preventDefault();
                                firstFocusableElement.focus();
                            }
                        }
                    });
                }
            });
        }
    } catch (error) {
        console.error('Error in DOM ready event listener:', error);
    }
});
    } catch (error) {
        console.error('Error initializing event listeners:', error);
    }
});
    
    if (categoryFilter || statusFilter || searchInput) {
        function filterProjects() {
            const category = categoryFilter ? categoryFilter.value.toLowerCase() : '';
            const status = statusFilter ? statusFilter.value.toLowerCase() : '';
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            
            document.querySelectorAll('.project-card').forEach(card => {
                const projectCategory = card.querySelector('.project-category')?.textContent.toLowerCase() || '';
                const projectStatus = card.querySelector('.project-status')?.textContent.toLowerCase() || '';
                const projectTitle = card.querySelector('.project-title')?.textContent.toLowerCase() || '';
                const projectDescription = card.querySelector('.project-description')?.textContent.toLowerCase() || '';
                
                const matchesCategory = !category || projectCategory.includes(category);
                const matchesStatus = !status || projectStatus.includes(status);
                const matchesSearch = !searchTerm || 
                    projectTitle.includes(searchTerm) || 
                    projectDescription.includes(searchTerm);
                
                card.style.display = (matchesCategory && matchesStatus && matchesSearch) ? '' : 'none';
            });
            
            // Check if there are any visible projects
            const hasVisibleProjects = Array.from(document.querySelectorAll('.project-card')).some(
                card => card.style.display !== 'none'
            );
            
            // Show/hide empty state
            const emptyState = document.querySelector('.empty-state');
            if (emptyState) {
                emptyState.style.display = hasVisibleProjects ? 'none' : 'block';
            }
        }
        
        // Add event listeners to filters
        if (categoryFilter) categoryFilter.addEventListener('change', filterProjects);
        if (statusFilter) statusFilter.addEventListener('change', filterProjects);
        if (searchInput) searchInput.addEventListener('input', filterProjects);
    }
    
    // Additional event listeners to improve modal interactivity
    // Get all form inputs in the modal
    const formInputs = document.querySelectorAll('#apply-project-form input, #apply-project-form textarea');
    
    // For each input, add focus and blur event handlers
    formInputs.forEach(input => {
        // On focus, increase z-index to make sure the element is on top
        input.addEventListener('focus', function() {
            this.style.position = 'relative';
            this.style.zIndex = '100';
        });
        
        // Reset z-index on blur
        input.addEventListener('blur', function() {
            this.style.zIndex = '2';
        });
    });
    
    // Ensure the modal container catches all events
    const modalContainer = document.querySelector('.modal-container');
    if (modalContainer) {
        modalContainer.addEventListener('click', function(e) {
            // This prevents the click from bubbling up to the overlay
            e.stopPropagation();
        });
    }
    
    // Improve focus management for accessibility
    const modal = document.getElementById('project-details-modal');
    if (modal) {
        modal.addEventListener('shown', function() {
            // Get all focusable elements in the modal
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                // Focus the first form element
                focusableElements[0].focus();
                
                // Set up focus trap
                const firstFocusableElement = focusableElements[0];
                const lastFocusableElement = focusableElements[focusableElements.length - 1];
                
                // Handle Tab key to keep focus within modal
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey && document.activeElement === firstFocusableElement) {
                            e.preventDefault();
                            lastFocusableElement.focus();
                        } else if (!e.shiftKey && document.activeElement === lastFocusableElement) {
                            e.preventDefault();
                            firstFocusableElement.focus();
                        }
                    }
                });
            }
        });
    }
});

// Define custom event for modal shown
function showModal(modalEl) {
    if (!modalEl) return;
    
    modalEl.classList.add('is-visible');
    document.body.classList.add('modal-open');
    
    // Dispatch custom event
    const event = new CustomEvent('shown');
    modalEl.dispatchEvent(event);
}

// Global function for opening the project modal
// This must be defined at the global scope to be accessible from HTML
window.openProjectModal = function(project) {
    try {
        // Make sure project is defined
        if (!project) {
            console.error('No project data provided to openProjectModal');
            return;
        }

        // Parse project data if it's a string
        if (typeof project === 'string') {
            try {
                project = JSON.parse(project);
            } catch (e) {
                console.error('Failed to parse project data:', e);
                return;
            }
        }
        
        // Clear any previous form data and errors
        const applyForm = document.getElementById('apply-project-form');
        if (applyForm) {
            applyForm.reset();
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.remove('has-error');
            });
        }
        
        // Set project data in the modal
        try {
            // Set hidden project ID
            const projectIdField = document.getElementById('modal-project-id');
            if (projectIdField) projectIdField.value = project.id;
            
            // Set text content of various elements
            const elementsToUpdate = {
                'modal-project-title': project.title || 'Project Details',
                'modal-project-category': project.category ? (project.category.charAt(0).toUpperCase() + project.category.slice(1)) : 'N/A',
                'modal-project-status': project.status ? (project.status.charAt(0).toUpperCase() + project.status.slice(1)) : 'N/A',
                'modal-project-description': project.description || 'No description provided',
                'modal-project-participants': project.participants_count || '0',
                'modal-project-max-participants': project.max_participants || '∞',
                'modal-project-skills-required': project.skills_required || 'Not specified',
                'modal-project-location': project.location || 'Not specified',
                'modal-project-remote': project.is_remote == '1' ? 'Remote' : 'On-site'
            };
            
            // Update each element, skipping if not found
            for (const [id, value] of Object.entries(elementsToUpdate)) {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                } else {
                    console.warn(`Element with ID ${id} not found in the modal`);
                }
            }
        } catch (e) {
            console.error('Error setting project data in modal:', e);
        }
            
            // Update each element, skipping if not found
            for (const [id, value] of Object.entries(elementsToUpdate)) {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                } else {
                    console.warn(`Element with ID ${id} not found in the modal`);
                }
            }
        } catch (e) {
            console.error('Error setting project data in modal:', e);
        }
          // Format dates
        try {
            const elementsToUpdate = {
                'modal-project-start-date': project.start_date ? new Date(project.start_date) : null,
                'modal-project-end-date': project.end_date ? new Date(project.end_date) : null
            };
            
            for (const [id, dateValue] of Object.entries(elementsToUpdate)) {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = dateValue && !isNaN(dateValue.getTime()) ? 
                        dateValue.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'Not specified';
                } else {
                    console.warn(`Element with ID ${id} not found in the modal`);
                }
            }
        } catch (e) {
            console.error('Error formatting dates:', e);
        }
        
        // Show the modal
        const modal = document.getElementById('project-details-modal');
        if (modal) {
            // Show the modal
            modal.classList.add('is-visible');
            document.body.classList.add('modal-open');
            
            // Dispatch a custom 'shown' event for accessibility
            const event = new CustomEvent('shown');
            modal.dispatchEvent(event);
            
            // Prevent modal from closing when clicking on the modal container
            const modalContainer = modal.querySelector('.modal-container');
            if (modalContainer) {
                modalContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
            
            // Focus on the first form element after a short delay
            setTimeout(() => {
                const messageTextarea = document.getElementById('application-message');
                if (messageTextarea) {
                    messageTextarea.focus();
                }
            }, 100);
        
            // Make sure form elements are clickable by stopping event propagation
            const formElements = modal.querySelectorAll('input, textarea, button, select');
            formElements.forEach(element => {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        }
    } catch (e) {
        console.error('Error in openProjectModal:', e);
        showNotification('Error', 'Could not open project details. Please refresh the page and try again.', 'error');
    }
};
        showNotification('Error', 'Could not open project details. Please refresh the page and try again.', 'error');
    }
};
// Close the modal function
window.closeProjectModal = function() {
    try {
        const modal = document.getElementById('project-details-modal');
        if (!modal) return;
        
        // Start fade-out animation
        modal.style.opacity = '0';
        
        // Remove classes and reset after animation completes
        setTimeout(() => {
            modal.classList.remove('is-visible');
            modal.style.opacity = '';
            document.body.classList.remove('modal-open');
            
            // Clean up event listeners to prevent memory leaks
            const modalContainer = modal.querySelector('.modal-container');
            if (modalContainer) {
                const newContainer = modalContainer.cloneNode(true);
                modalContainer.parentNode.replaceChild(newContainer, modalContainer);
            }
        }, 300);
    } catch (error) {
        console.error('Error closing modal:', error);
    }
};

// Handle form submission
window.submitProjectApplication = function() {
    try {
        // Get form values
        const projectIdField = document.getElementById('modal-project-id');
        if (!projectIdField) {
            throw new Error('Project ID field not found');
        }
        
        const projectId = projectIdField.value;
        const messageField = document.getElementById('application-message');
        const skillsField = document.getElementById('application-skills');
        
        if (!messageField || !skillsField) {
            throw new Error('Form fields not found');
        }
        
        const message = messageField.value.trim();
        const skills = skillsField.value.trim();
        
        // Validate form
        let isValid = true;
        
        // Reset previous errors
        document.querySelectorAll('.form-group').forEach(group => {
            group.classList.remove('has-error');
        });
        
        // Validate project ID
        if (!projectId) {
            console.error('Missing project ID');
            showNotification('Error', 'An error occurred. Please try again.', 'error');
            return;
        }
        
        // Validate message
        const messageErrorEl = document.getElementById('message-error');
        if (!message || message.length < 10) {
            if (messageErrorEl) {
                messageErrorEl.textContent = 'Please provide a detailed message (at least 10 characters).';
                messageField.parentElement.classList.add('has-error');
                messageField.focus();
            } else {
                showNotification('Error', 'Please provide a detailed message (at least 10 characters).', 'error');
            }
            isValid = false;
        }
        
        // Validate skills (optional, but if provided must be valid)
        const skillsErrorEl = document.getElementById('skills-error');
        if (skills && skills.length < 3) {
            if (skillsErrorEl) {
                skillsErrorEl.textContent = 'Please provide valid skills or leave blank.';
                skillsField.parentElement.classList.add('has-error');
                if (isValid) {
                    skillsField.focus();
                }
            } else {
                showNotification('Error', 'Please provide valid skills or leave blank.', 'error');
            }
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.modal-footer .btn-primary');
        if (!submitBtn) {
            throw new Error('Submit button not found');
        }
        
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Submitting...';
        submitBtn.classList.add('btn-loading');
        
        // Submit application via AJAX
        fetch(`${window.location.origin}${URL_ROOT}/projects/applyToProjectJson`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                project_id: projectId,
                message: message,
                skills: skills
            })
        })
        .then(response => {
            if (!response.ok) {
                // Handle HTTP errors
                let errorMessage = 'Failed to submit application. Please try again.';
                
                if (response.status === 403) {
                    errorMessage = 'You must be logged in to apply for this project.';
                } else if (response.status === 409) {
                    errorMessage = 'You have already applied to this project.';
                } else if (response.status === 422) {
                    errorMessage = 'Please check your application details and try again.';
                }
                
                return response.json().then(data => {
                    throw new Error(data.message || errorMessage);
                }).catch(() => {
                    throw new Error(errorMessage);
                });
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update UI to show application was successful
                const applyBtn = document.querySelector(`.apply-btn[data-project-id="${projectId}"]`);
                if (applyBtn) {
                    applyBtn.outerHTML = `<button class="project-action applied-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Applied
                    </button>`;
                }
                
                // Close modal and show success message
                closeProjectModal();
                showNotification('Success', 'Your application has been submitted successfully.', 'success');
            } else {
                throw new Error(data.message || 'Failed to submit application. Please try again.');
            }
        })
        .catch(error => {
            console.error('Application error:', error);
            showNotification('Error', error.message || 'Failed to submit application. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                submitBtn.classList.remove('btn-loading');
            }
        });
    } catch (error) {
        console.error('Form submission error:', error);
        showNotification('Error', error.message || 'An unexpected error occurred. Please try again.', 'error');
    }
}

// Notification toast function
function showNotification(title, message, type = 'info', duration = 5000) {
    try {
        // Check for required container
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.style.position = 'fixed';
            container.style.bottom = '20px';
            container.style.right = '20px';
            container.style.zIndex = '10000';
            document.body.appendChild(container);
        }
        
        // Create notification elements
        const toast = document.createElement('div');
        toast.className = `notification-toast ${type}`;
        
        const content = document.createElement('div');
        content.className = 'notification-content';
        
        const titleEl = document.createElement('span');
        titleEl.className = 'notification-title';
        titleEl.textContent = title || 'Notification';
        
        const messageEl = document.createElement('div');
        messageEl.className = 'notification-message';
        messageEl.textContent = message || '';
        
        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.innerHTML = '&times;';
        closeButton.addEventListener('click', () => {
            removeToast(toast);
        });
        
        const progress = document.createElement('div');
        progress.className = 'notification-progress';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'notification-progress-bar';
        progressBar.style.transition = `width ${duration}ms linear`;
        progressBar.style.width = '100%';
        
        // Assemble the notification
        content.appendChild(titleEl);
        content.appendChild(messageEl);
        progress.appendChild(progressBar);
        toast.appendChild(content);
        toast.appendChild(closeButton);
        toast.appendChild(progress);
        
        // Add to container
        container.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
            progressBar.style.width = '0%';
        }, 10);
        
        // Set timeout to remove
        const timeout = setTimeout(() => {
            removeToast(toast);
        }, duration);
        
        // Store timeout to clear if closed manually
        toast.dataset.timeout = timeout;
        
        // Function to remove toast
        function removeToast(element) {
            // Clear timeout if it exists
            if (element.dataset.timeout) {
                clearTimeout(element.dataset.timeout);
            }
            
            // Remove show class to trigger fade out
            element.classList.remove('show');
            
            // Remove element after animation
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 300);
        }
    } catch (error) {        console.error('Error showing notification:', error);
    }
}
</script>

<!-- Include the global Project Modal functions -->
<script>
// Define URL_ROOT for use in the project-modal.js file
window.URL_ROOT = "<?php echo URL_ROOT; ?>";
// Also define the API endpoint directly
window.API_ENDPOINT = "<?php echo URL_ROOT; ?>/projects/applyToProjectJson";
</script>
<script src="<?php echo URL_ROOT; ?>/public/js/components/project-modal.js"></script>