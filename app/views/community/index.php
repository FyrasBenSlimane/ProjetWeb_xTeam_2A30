<?php
// Community Hub - Main landing page for the community section
?>

<style>
    /* Global styles */
    .community-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Hero section */
    .hero-section {
        position: relative;
        background: linear-gradient(to bottom right, #2c3e50, #1a252f);
        color: white;
        padding: 3rem 0 4rem;
    }

    .hero-content {
        max-width: 768px;
        margin: 0 auto;
        text-align: center;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }

    /* Buttons */
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #2c3e50;
        color: white;
        margin-right: 1rem;
    }

    .btn-primary:hover {
        background-color: #34495e;
    }

    .btn-secondary {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* Main content */
    .main-content {
        padding: 3rem 0;
        background-color: #f9fafb;
    }

    /* Feature cards */
    .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .feature-card {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
        transition: box-shadow 0.3s ease;
    }

    .feature-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 3rem;
        height: 3rem;
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .feature-description {
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .feature-link {
        color: #2c3e50;
        display: inline-flex;
        align-items: center;
        font-weight: 500;
        text-decoration: none;
    }

    .feature-link:hover {
        color: #34495e;
    }

    .feature-link svg {
        margin-left: 0.25rem;
    }

    /* Recent activity */
    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
    }

    .activity-list {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 3rem;
    }

    .activity-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: flex-start;
    }

    .activity-item:hover {
        background-color: #f9fafb;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        height: 2.5rem;
        width: 2.5rem;
        background-color: rgba(44, 62, 80, 0.1);
        color: #2c3e50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-title {
        font-weight: 500;
    }

    .activity-meta {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* CTA section */
    .cta-section {
        background: linear-gradient(to right, #2c3e50, #1a252f);
        border-radius: 0.75rem;
        color: white;
        padding: 2rem;
        text-align: center;
        margin-bottom: 3rem;
    }

    .cta-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .cta-description {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        max-width: 42rem;
        margin-left: auto;
        margin-right: auto;
    }

    /* Guidelines section */
    .guidelines-section {
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.5rem;
    }

    .guidelines-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .guidelines-description {
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .guidelines-list {
        list-style-type: none;
        padding: 0;
    }

    .guidelines-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .guidelines-icon {
        color: #2c3e50;
        margin-right: 0.5rem;
        margin-top: 0.125rem;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-description {
            font-size: 1.125rem;
        }

        .btn {
            display: block;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            margin-right: 0;
        }
    }
</style>

<div class="main-wrapper">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="community-container">
            <div class="hero-content">
                <h1 class="hero-title">Community Hub</h1>
                <p class="hero-description">
                    Connect with other professionals, join discussions, share knowledge and grow your network.
                </p>
                <?php if (!$data['is_logged_in']): ?>
                <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="btn btn-primary">
                    Join Our Community
                </a>
                <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="btn btn-secondary">
                    Sign In
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Decorative wave -->
        <div class="hero-wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                <path fill="#F9FAFB" fill-opacity="1" d="M0,96L60,85.3C120,75,240,53,360,58.7C480,64,600,96,720,96C840,96,960,64,1080,48C1200,32,1320,32,1380,32L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="community-container">
            <!-- Feature Cards -->
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h2 class="feature-title">Discussions</h2>
                    <p class="feature-description">
                        Join conversations, ask questions, and share your expertise with fellow professionals.
                    </p>
                    <a href="<?php echo URL_ROOT; ?>/pages/community/forum" class="feature-link">
                        Browse Discussions
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h2 class="feature-title">Articles & Resources</h2>
                    <p class="feature-description">
                        Discover helpful articles, guides, and resources to help you grow professionally.
                    </p>
                    <a href="<?php echo URL_ROOT; ?>/pages/community/content" class="feature-link">
                        Explore Content
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="feature-title">Community Members</h2>
                    <p class="feature-description">
                        Connect with professionals, find collaborators, and expand your network.
                    </p>
                    <a href="<?php echo URL_ROOT; ?>/pages/community/people" class="feature-link">
                        Meet Members
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="recent-activity">
                <h2 class="section-title">Recent Activity</h2>
                
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New discussion: "Best practices for client communication"</div>
                            <div class="activity-meta">Posted by Sarah J. • 15 minutes ago</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New member: Michael Chen joined the community</div>
                            <div class="activity-meta">1 hour ago</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New article: "10 Ways to Improve Your Portfolio"</div>
                            <div class="activity-meta">By Alex T. • 3 hours ago</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">New replies in: "How to price your freelance services"</div>
                            <div class="activity-meta">12 new replies • 5 hours ago</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Join Community CTA -->
            <?php if (!$data['is_logged_in']): ?>
            <div class="cta-section">
                <h2 class="cta-title">Ready to join our community?</h2>
                <p class="cta-description">
                    Connect with other professionals, access exclusive resources, and grow your network.
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo URL_ROOT; ?>/users/auth?action=register" class="btn btn-primary">
                        Sign Up Now
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/users/auth?action=login" class="btn btn-secondary">
                        Log In
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Community Guidelines -->
            <div class="guidelines-section">
                <h2 class="guidelines-title">Community Guidelines</h2>
                <p class="guidelines-description">
                    Our community is built on respect, professionalism, and mutual support. When participating, please:
                </p>
                
                <ul class="guidelines-list">
                    <li class="guidelines-item">
                        <span class="guidelines-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Be respectful and considerate of others</span>
                    </li>
                    <li class="guidelines-item">
                        <span class="guidelines-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Share knowledge and help others when you can</span>
                    </li>
                    <li class="guidelines-item">
                        <span class="guidelines-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Provide constructive feedback rather than criticism</span>
                    </li>
                    <li class="guidelines-item">
                        <span class="guidelines-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>No self-promotion or spam outside of designated areas</span>
                    </li>
                    <li class="guidelines-item">
                        <span class="guidelines-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span>Report any inappropriate behavior to our moderation team</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show cookie consent if not previously accepted
        if (!localStorage.getItem('cookie-consent')) {
            document.getElementById('cookie-consent').classList.remove('hidden');
        }
    });
    
    function acceptCookies() {
        localStorage.setItem('cookie-consent', 'true');
        document.getElementById('cookie-consent').classList.add('hidden');
    }
</script> 