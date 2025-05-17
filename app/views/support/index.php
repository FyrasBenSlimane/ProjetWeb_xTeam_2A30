<?php
// Support Center index page
// This is the main landing page for support that users see after clicking on support link
?>

<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Support Center</h1>
        <p class="hero-subtitle">Welcome to the <?php echo ucfirst($data['account_type']); ?> Support Center. How can we help you today?</p>
        <div class="support-search">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search for help..." class="support-search-input">
                <button class="support-search-btn"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#fff" fill-opacity="1" d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,42.7C960,43,1056,53,1152,53.3C1248,53,1344,43,1392,37.3L1440,32L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z"></path>
        </svg>
    </div>
</div>

<div class="support-container">

    <div class="section-container">
        <div class="section-header">
            <h2>Common Topics</h2>
            <p>Quick answers to frequently asked questions</p>
        </div>
        <div class="topics-grid">
            <a href="<?php echo URL_ROOT; ?>/support/faq#account" class="topic-card">
                <div class="topic-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="topic-content">
                    <h3>Account Issues</h3>
                    <p>Password reset, profile settings, account verification</p>
                    <span class="topic-link">Read more <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <a href="<?php echo URL_ROOT; ?>/support/faq#payments" class="topic-card">
                <div class="topic-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="topic-content">
                    <h3>Payments</h3>
                    <p>Billing issues, payment methods, refunds</p>
                    <span class="topic-link">Read more <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <a href="<?php echo URL_ROOT; ?>/support/faq#projects" class="topic-card">
                <div class="topic-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="topic-content">
                    <h3>Projects</h3>
                    <p>Creating projects, milestones, deliverables</p>
                    <span class="topic-link">Read more <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <a href="<?php echo URL_ROOT; ?>/support/faq#communication" class="topic-card">
                <div class="topic-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="topic-content">
                    <h3>Communication</h3>
                    <p>Messaging system, video calls, notifications</p>
                    <span class="topic-link">Read more <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
    </div>

    <div class="section-container">
        <div class="section-header">
            <h2>Support Options</h2>
            <p>Choose how you'd like to get help</p>
        </div>
        <div class="support-options-grid">
            <div class="support-option-card">
                <div class="option-header">
                    <div class="option-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>My Support Tickets</h3>
                </div>
                <p>View the status of tickets you've submitted and responses from our support team.</p>
                <a href="<?php echo URL_ROOT; ?>/support/tickets" class="btn-primary">View My Tickets</a>
            </div>
            <div class="support-option-card">
                <div class="option-header">
                    <div class="option-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3>Create New Ticket</h3>
                </div>
                <p>Need help with something specific? Create a new support ticket and we'll respond shortly.</p>
                <a href="<?php echo URL_ROOT; ?>/support/newTicket" class="btn-primary">Create Ticket</a>
            </div>
            <div class="support-option-card">
                <div class="option-header">
                    <div class="option-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>FAQ</h3>
                </div>
                <p>Find answers to commonly asked questions about using our platform.</p>
                <a href="<?php echo URL_ROOT; ?>/support/faq" class="btn-primary">Browse FAQ</a>
            </div>
            <div class="support-option-card">
                <div class="option-header">
                    <div class="option-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Contact Us</h3>
                </div>
                <p>Need to get in touch with our support team directly? Contact us here.</p>
                <a href="<?php echo URL_ROOT; ?>/support/contact" class="btn-primary">Contact Support</a>
            </div>
        </div>
    </div>

    <?php if ($data['account_type'] === 'freelancer'): ?>
        <div class="section-container">
            <div class="section-header">
                <h2>Freelancer Resources</h2>
                <p>Tools and guides to help you succeed</p>
            </div>
            <div class="resources-grid">
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Freelancer Guide</h3>
                        <p>Essential tips and best practices for freelancers</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Tutorial Videos</h3>
                        <p>Step-by-step visual guides for using the platform</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Best Practices</h3>
                        <p>Learn how to provide top-quality service to clients</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Skills Development</h3>
                        <p>Resources to help you expand your skillset</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($data['account_type'] === 'client'): ?>
        <div class="section-container">
            <div class="section-header">
                <h2>Client Resources</h2>
                <p>Tools and guides to help you succeed</p>
            </div>
            <div class="resources-grid">
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Client Guide</h3>
                        <p>Essential tips for working with freelancers</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Tutorial Videos</h3>
                        <p>Step-by-step visual guides for using the platform</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Working with Freelancers</h3>
                        <p>How to build successful freelancer relationships</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                <a href="#" class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="resource-content">
                        <h3>Project Management</h3>
                        <p>Tips for planning and managing your projects</p>
                    </div>
                    <div class="resource-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Live Chat Support Widget -->
<div class="live-chat-widget">
    <div class="chat-button" id="chat-button">
        <i class="fas fa-comments"></i>
        <span>Live Chat</span>
    </div>

    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <div class="chat-title">
                <div class="chat-avatar">
                    <img src="<?php echo URL_ROOT; ?>/public/img/support-avatar.png" alt="Support Agent" onerror="this.src='<?php echo URL_ROOT; ?>/public/img/default-avatar.png'">
                </div>
                <div class="chat-agent-info">
                    <h3>Support Chat</h3>
                    <span class="status online">Agent online</span>
                </div>
            </div>
            <div class="chat-controls">
                <button class="minimize-btn" id="minimize-chat"><i class="fas fa-minus"></i></button>
                <button class="close-btn" id="close-chat"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div class="chat-messages" id="chat-messages">
            <div class="message system-message">
                <div class="message-content">
                    <p>Welcome to our live support chat! How can we help you today?</p>
                </div>
                <div class="message-time">Just now</div>
            </div>

            <!-- Messages will be added here dynamically -->
        </div>

        <div class="chat-input-area">
            <div class="chat-features">
                <button class="feature-btn" title="Send attachment"><i class="fas fa-paperclip"></i></button>
                <button class="feature-btn" title="Send emoji"><i class="fas fa-smile"></i></button>
                <button class="feature-btn" title="Voice message"><i class="fas fa-microphone"></i></button>
            </div>
            <div class="chat-input-wrapper">
                <textarea id="chat-input" placeholder="Type your message..." rows="1"></textarea>
                <button id="send-message"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Support Center Styles */
    body.support-page {
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9fafb;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        background: linear-gradient(135deg, #2c5282, #1e3c5a);
        color: white;
        padding: 4rem 0 6rem;
        margin-top: 0;
        text-align: center;
        overflow: visible;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 40%, rgba(69, 104, 142, 0.4), transparent 50%),
            radial-gradient(circle at 70% 60%, rgba(46, 78, 126, 0.4), transparent 50%);
        z-index: 1;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        opacity: 1;
        letter-spacing: -0.5px;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        font-weight: 400;
        margin-bottom: 2rem;
        opacity: 1;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.4;
    }

    .support-search {
        max-width: 700px;
        margin: 0 auto;
        opacity: 1;
        position: relative;
        z-index: 3;
    }

    .hero-wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        z-index: 2;
        height: 80px;
        line-height: 0;
        pointer-events: none;
    }

    .hero-wave svg {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* Search Bar */
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        color: #7e8c9a;
        font-size: 18px;
        z-index: 3;
    }

    .support-search-input {
        width: 100%;
        padding: 16px 60px 16px 50px;
        border: none;
        border-radius: 500px;
        font-size: 16px;
        background: white;
        transition: all 0.3s ease;
        color: #2c3e50;
        font-weight: 400;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .support-search-input::placeholder {
        color: #7e8c9a;
        font-weight: 400;
    }

    .support-search-input:focus {
        outline: none;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.25);
    }

    .support-search-btn {
        position: absolute;
        right: 5px;
        background: #3a6186;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 500px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
    }

    .support-search-btn i {
        font-size: 14px;
    }

    .support-search-btn:hover {
        background: linear-gradient(135deg, #4a71a6, #2a354f);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    /* Support Container */
    .support-container {
        max-width: 1200px;
        margin: -30px auto 60px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    /* Section Containers */
    .section-container {
        background: white;
        border-radius: 14px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .section-container:first-child {
        margin-top: 0;
    }

    .section-container+.section-container {
        margin-top: 30px;
    }

    /* Section Headers */
    .section-header {
        margin-bottom: 30px;
        position: relative;
    }

    .section-header h2 {
        font-size: 1.8rem;
        color: #2c3e50;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .section-header p {
        font-size: 1.1rem;
        color: #74767e;
        margin: 0;
    }

    /* Common Topics Section */
    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }

    .topic-card {
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 12px;
        padding: 25px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #eaeaea;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    .topic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        border-color: rgba(44, 62, 80, 0.15);
    }

    .topic-icon {
        width: 60px;
        height: 60px;
        background: rgba(44, 62, 80, 0.08);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .topic-card:hover .topic-icon {
        background: rgba(44, 62, 80, 0.15);
    }

    .topic-icon i {
        font-size: 24px;
        color: #2c3e50;
    }

    .topic-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .topic-card h3 {
        font-size: 1.2rem;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .topic-card p {
        font-size: 0.95rem;
        color: #74767e;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    .topic-link {
        display: flex;
        align-items: center;
        color: #2c3e50;
        font-weight: 500;
        font-size: 0.9rem;
        margin-top: auto;
    }

    .topic-link i {
        margin-left: 8px;
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .topic-card:hover .topic-link i {
        transform: translateX(3px);
    }

    /* Support Options Section */
    .support-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }

    .support-option-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .support-option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        border-color: rgba(44, 62, 80, 0.15);
    }

    .option-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .option-icon {
        width: 48px;
        height: 48px;
        background: rgba(44, 62, 80, 0.08);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .option-icon i {
        font-size: 20px;
        color: #2c3e50;
    }

    .option-header h3 {
        font-size: 1.2rem;
        color: #2c3e50;
        margin: 0;
        font-weight: 600;
    }

    .support-option-card p {
        font-size: 0.95rem;
        color: #74767e;
        line-height: 1.6;
        margin-bottom: 25px;
        flex-grow: 1;
    }

    .btn-primary {
        display: inline-block;
        background: #2c3e50;
        color: white !important;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        text-align: center;
        font-weight: 500;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        margin-top: auto;
    }

    .btn-primary:hover {
        background: #1a252f;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Resources Section */
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 25px;
    }

    .resource-card {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #eaeaea;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
    }

    .resource-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: rgba(44, 62, 80, 0.15);
    }

    .resource-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        background: rgba(44, 62, 80, 0.08);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .resource-icon i {
        font-size: 18px;
        color: #2c3e50;
    }

    .resource-content {
        flex-grow: 1;
    }

    .resource-content h3 {
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 5px;
        margin-top: 0;
        font-weight: 600;
    }

    .resource-content p {
        font-size: 0.85rem;
        color: #74767e;
        line-height: 1.5;
        margin: 0;
    }

    .resource-arrow {
        margin-left: 15px;
        color: #2c3e50;
        transition: transform 0.2s ease;
    }

    .resource-card:hover .resource-arrow {
        transform: translateX(3px);
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .section-container {
            padding: 30px;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .support-search-input {
            padding: 15px 60px 15px 45px;
        }

        .support-search-btn {
            padding: 8px 12px;
            min-width: auto;
        }

        .topics-grid,
        .support-options-grid,
        .resources-grid {
            grid-template-columns: 1fr;
        }

        .section-container {
            padding: 25px 20px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 1.8rem;
        }

        .hero-section {
            padding: 3rem 0 5rem;
        }

        .support-search-input {
            padding: 12px 50px 12px 40px;
            font-size: 14px;
        }

        .support-search-btn {
            padding: 6px 10px;
            right: 5px;
        }

        .option-icon {
            width: 40px;
            height: 40px;
        }

        .option-icon i {
            font-size: 16px;
        }

        .option-header h3 {
            font-size: 1.1rem;
        }
    }

    /* Live Chat Widget Styles */
    .live-chat-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .chat-button {
        background: #2c3e50;
        color: white;
        border-radius: 50px;
        padding: 15px 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .chat-button:hover {
        background: #34495e;
        transform: translateY(-2px);
    }

    .chat-button i {
        font-size: 1.2rem;
    }

    .chat-container {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 360px;
        height: 500px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .chat-header {
        background: #2c3e50;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid white;
    }

    .chat-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .chat-agent-info h3 {
        margin: 0;
        font-size: 1rem;
    }

    .status {
        font-size: 0.8rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status.online:before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #2ecc71;
        border-radius: 50%;
    }

    .chat-controls {
        display: flex;
        gap: 10px;
    }

    .chat-controls button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 5px;
        border-radius: 3px;
        transition: background 0.2s ease;
    }

    .chat-controls button:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .message {
        display: flex;
        flex-direction: column;
        max-width: 80%;
    }

    .message.user-message {
        align-self: flex-end;
    }

    .message.agent-message {
        align-self: flex-start;
    }

    .message.system-message {
        align-self: center;
        max-width: 90%;
    }

    .message-content {
        padding: 12px 15px;
        border-radius: 18px;
        position: relative;
    }

    .user-message .message-content {
        background: #3498db;
        color: white;
        border-bottom-right-radius: 5px;
    }

    .agent-message .message-content {
        background: #f1f1f1;
        color: #333;
        border-bottom-left-radius: 5px;
    }

    .system-message .message-content {
        background: #f8f9fa;
        color: #666;
        border-radius: 10px;
        text-align: center;
        font-style: italic;
    }

    .message-content p {
        margin: 0;
    }

    .message-time {
        font-size: 0.7rem;
        opacity: 0.7;
        margin-top: 5px;
    }

    .user-message .message-time {
        align-self: flex-end;
    }

    .agent-message .message-time {
        align-self: flex-start;
    }

    .system-message .message-time {
        align-self: center;
    }

    .chat-input-area {
        border-top: 1px solid #f1f1f1;
        padding: 15px;
    }

    .chat-features {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .feature-btn {
        background: none;
        border: none;
        color: #777;
        cursor: pointer;
        font-size: 1rem;
        transition: color 0.2s ease;
    }

    .feature-btn:hover {
        color: #3498db;
    }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 30px;
        padding: 5px 15px;
    }

    #chat-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 0;
        resize: none;
        max-height: 100px;
        font-family: inherit;
        font-size: 0.95rem;
    }

    #chat-input:focus {
        outline: none;
    }

    #send-message {
        background: none;
        border: none;
        color: #3498db;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 5px;
        transition: transform 0.2s ease;
    }

    #send-message:hover {
        transform: scale(1.1);
    }

    /* Custom scrollbar for chat messages */
    .chat-messages::-webkit-scrollbar {
        width: 5px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }

    /* Animation for chat window */
    .chat-container.active {
        display: flex;
        animation: fadeIn 0.3s forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add support-page class to body
        document.body.classList.add('support-page');

        // Support search functionality
        const searchInput = document.querySelector('.support-search-input');
        const searchBtn = document.querySelector('.support-search-btn');

        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', function() {
                performSearch(searchInput.value);
            });

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch(searchInput.value);
                }
            });
        }

        function performSearch(query) {
            if (query.trim() !== '') {
                // Redirect to FAQ page with search parameter
                window.location.href = `<?php echo URL_ROOT; ?>/support/faq?search=${encodeURIComponent(query)}`;
            }
        }

        // Focus on search input when page loads
        if (searchInput) {
            setTimeout(() => {
                searchInput.focus();
            }, 500);
        }

        // Live Chat Widget Functionality
        const chatWidget = document.getElementById('live-chat-widget');
        const chatButton = document.getElementById('chat-button');
        const minimizeBtn = document.getElementById('minimize-chat');
        const closeBtn = document.getElementById('close-chat');
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('chat-message-input');
        const sendBtn = document.getElementById('send-message');

        // Initial state
        chatWidget.classList.add('closed');

        // Toggle chat open/closed
        chatButton.addEventListener('click', () => {
            chatWidget.classList.remove('closed');
        });

        // Close chat
        closeBtn.addEventListener('click', () => {
            chatWidget.classList.add('closed');
        });

        // Minimize chat
        minimizeBtn.addEventListener('click', () => {
            chatWidget.classList.toggle('minimized');
        });

        // Function to add a new message
        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = isUser ? 'message user-message' : 'message support-message';

            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'message-avatar';

            const icon = document.createElement('i');
            icon.className = isUser ? 'fas fa-user' : 'fas fa-headset';
            avatarDiv.appendChild(icon);

            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';

            const messageP = document.createElement('p');
            messageP.textContent = content;
            contentDiv.appendChild(messageP);

            const timeSpan = document.createElement('span');
            timeSpan.className = 'message-time';
            timeSpan.textContent = 'Just now';
            contentDiv.appendChild(timeSpan);

            messageDiv.appendChild(avatarDiv);
            messageDiv.appendChild(contentDiv);

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Send message
        function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                addMessage(message, true);
                messageInput.value = '';

                // Simulate response (in real app, this would be an API call)
                setTimeout(() => {
                    const responses = [
                        "Thanks for your message. A support agent will respond shortly.",
                        "I understand your concern. Let me check on that for you.",
                        "Thank you for reaching out. Could you provide more details?",
                        "We're here to help. I'm looking into this now."
                    ];
                    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                    addMessage(randomResponse);
                }, 1000);
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Chat elements
        const chatButton = document.getElementById('chat-button');
        const chatContainer = document.getElementById('chat-container');
        const minimizeChat = document.getElementById('minimize-chat');
        const closeChat = document.getElementById('close-chat');
        const chatInput = document.getElementById('chat-input');
        const sendMessage = document.getElementById('send-message');
        const chatMessages = document.getElementById('chat-messages');

        // Sample automated responses
        const botResponses = [
            "Thank you for your message. How else can I help you today?",
            "I understand your concern. Let me check that for you.",
            "Could you please provide more details about your issue?",
            "I'm looking into this for you right now.",
            "That's a great question. Here's what you need to know...",
            "I'd be happy to assist you with that.",
            "Let me connect you with a specialist who can help you further."
        ];

        // Typing indicators
        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message agent-message typing-indicator';
            typingDiv.innerHTML = `
            <div class="message-content">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return typingDiv;
        }

        // Add a bot response with delay
        function addBotResponse() {
            const typingIndicator = showTypingIndicator();

            // Simulate typing delay
            setTimeout(() => {
                chatMessages.removeChild(typingIndicator);

                const randomResponse = botResponses[Math.floor(Math.random() * botResponses.length)];

                const messageDiv = document.createElement('div');
                messageDiv.className = 'message agent-message';
                messageDiv.innerHTML = `
                <div class="message-content">
                    <p>${randomResponse}</p>
                </div>
                <div class="message-time">${getCurrentTime()}</div>
            `;

                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, Math.random() * 1000 + 1000); // Random delay between 1-2 seconds
        }

        // Get current time in HH:MM format
        function getCurrentTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;

            return hours + ':' + minutes + ' ' + ampm;
        }

        // Add a user message
        function addUserMessage(text) {
            if (!text.trim()) return;

            const messageDiv = document.createElement('div');
            messageDiv.className = 'message user-message';
            messageDiv.innerHTML = `
            <div class="message-content">
                <p>${text}</p>
            </div>
            <div class="message-time">${getCurrentTime()}</div>
        `;

            chatMessages.appendChild(messageDiv);
            chatInput.value = '';
            chatInput.style.height = 'auto';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Simulate bot response
            setTimeout(addBotResponse, 500);
        }

        // Chat toggle
        chatButton.addEventListener('click', function() {
            chatContainer.classList.toggle('active');
            chatContainer.style.display = chatContainer.classList.contains('active') ? 'flex' : 'none';
        });

        // Minimize chat
        minimizeChat.addEventListener('click', function() {
            chatContainer.classList.remove('active');
            setTimeout(() => {
                chatContainer.style.display = 'none';
            }, 300);
        });

        // Close chat
        closeChat.addEventListener('click', function() {
            chatContainer.classList.remove('active');
            setTimeout(() => {
                chatContainer.style.display = 'none';
            }, 300);
        });

        // Send message
        sendMessage.addEventListener('click', function() {
            addUserMessage(chatInput.value);
        });

        // Send message on Enter (but allow Shift+Enter for new line)
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                addUserMessage(this.value);
            }
        });

        // Auto-resize textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Add welcome message after a short delay
        setTimeout(function() {
            const welcomeMessage = document.createElement('div');
            welcomeMessage.className = 'message agent-message';
            welcomeMessage.innerHTML = `
            <div class="message-content">
                <p>Hello! I'm your virtual assistant. Feel free to ask any questions about our platform, account setup, or support services.</p>
            </div>
            <div class="message-time">${getCurrentTime()}</div>
        `;

            chatMessages.appendChild(welcomeMessage);
        }, 1000);

        // Custom suggestions after a delay
        setTimeout(function() {
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'message agent-message quick-suggestions';
            suggestionsDiv.innerHTML = `
            <div class="message-content">
                <p>Here are some common questions:</p>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn">How do I reset my password?</button>
                    <button class="suggestion-btn">Can I change my subscription plan?</button>
                    <button class="suggestion-btn">How do I contact a human agent?</button>
                </div>
            </div>
            <div class="message-time">${getCurrentTime()}</div>
        `;

            chatMessages.appendChild(suggestionsDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Add event listeners to suggestion buttons
            const suggestionButtons = document.querySelectorAll('.suggestion-btn');
            suggestionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    addUserMessage(this.textContent);
                });
            });
        }, 2000);
    });
</script>
</div>
</div>
</div>
</div>
</body>

</html>