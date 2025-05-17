<?php
// Support FAQ page
// This page displays frequently asked questions organized by categories
?>

<div class="faq-container">
    <div class="faq-header">
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about using our platform</p>
    </div>

    <!-- AI-powered Knowledge Base -->
    <div class="knowledge-base">
        <div class="kb-header">
            <div class="kb-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="kb-title">
                <h2>AI Knowledge Assistant</h2>
                <p>Ask any question and get instant AI-powered answers</p>
            </div>
        </div>
        <div class="kb-input-container">
            <input type="text" id="kb-question-input" placeholder="Ask a question like 'How do I change my password?'" class="kb-input">
            <button id="kb-ask-button" class="kb-button">
                <i class="fas fa-search"></i>
                <span>Ask</span>
            </button>
            <button id="kb-voice-button" class="kb-voice-button">
                <i class="fas fa-microphone"></i>
            </button>
        </div>
        <div id="kb-suggestions" class="kb-suggestions">
            <h3>Popular Questions</h3>
            <div class="suggestion-chips">
                <span class="suggestion-chip" data-question="How do I reset my password?">How do I reset my password?</span>
                <span class="suggestion-chip" data-question="Where can I view my invoices?">Where can I view my invoices?</span>
                <span class="suggestion-chip" data-question="How do I cancel my subscription?">How do I cancel my subscription?</span>
                <span class="suggestion-chip" data-question="How to contact support?">How to contact support?</span>
            </div>
        </div>
        <div id="kb-answer-area" class="kb-answer-area hidden">
            <div class="kb-answer-header">
                <h3>Answer</h3>
                <div class="kb-actions">
                    <button id="kb-text-to-speech" class="kb-action-btn" title="Listen to answer"><i class="fas fa-volume-up"></i></button>
                    <button id="kb-copy-answer" class="kb-action-btn" title="Copy answer"><i class="fas fa-copy"></i></button>
                    <button id="kb-close-answer" class="kb-close-btn"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div id="kb-answer-content" class="kb-answer-content">
                <!-- Answer content will be dynamically inserted here -->
            </div>
            <div id="kb-related-questions" class="kb-related-questions">
                <h4>Related Questions</h4>
                <ul class="related-questions-list">
                    <!-- Related questions will be dynamically inserted here -->
                </ul>
            </div>
            <div class="kb-feedback">
                <p>Was this answer helpful?</p>
                <div class="kb-feedback-buttons">
                    <button class="kb-feedback-btn" data-feedback="yes"><i class="fas fa-thumbs-up"></i> Yes</button>
                    <button class="kb-feedback-btn" data-feedback="no"><i class="fas fa-thumbs-down"></i> No</button>
                </div>
            </div>
        </div>
        <div id="kb-chat-history" class="kb-chat-history hidden">
            <div class="kb-history-header">
                <h3>Recent Questions</h3>
                <button id="kb-clear-history" class="kb-action-btn" title="Clear history"><i class="fas fa-trash"></i></button>
            </div>
            <ul class="kb-history-list">
                <!-- History items will be dynamically inserted here -->
            </ul>
        </div>
    </div>

    <div class="faq-search">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="faq-search-input" placeholder="Search for answers..." class="faq-search-input">
        </div>
    </div>

    <div class="faq-categories">
        <button class="category-btn active" data-category="all">All Questions</button>
        <button class="category-btn" data-category="account">Account</button>
        <button class="category-btn" data-category="payments">Payments</button>
        <button class="category-btn" data-category="projects">Projects</button>
        <button class="category-btn" data-category="communication">Communication</button>
    </div>

    <div class="faq-content">
        <!-- Account Section -->
        <div class="faq-section" id="account">
            <h2 class="section-title">Account Management</h2>

            <div class="faq-accordion">
                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        <h3>How do I change my password?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>To change your password:</p>
                        <ol>
                            <li>Go to your account settings by clicking on your profile picture in the top-right corner</li>
                            <li>Select "Account Settings" from the dropdown menu</li>
                            <li>Click on the "Security" tab</li>
                            <li>Click "Change Password"</li>
                            <li>Enter your current password and your new password</li>
                            <li>Click "Save Changes"</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        <h3>How do I update my profile information?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>You can update your profile information by:</p>
                        <ol>
                            <li>Navigating to your profile by clicking your profile picture in the top-right corner</li>
                            <li>Selecting "View Profile" or "Profile Settings"</li>
                            <li>Click the "Edit Profile" button</li>
                            <li>Update your information in the form provided</li>
                            <li>Click "Save Changes" to apply your updates</li>
                        </ol>
                        <p>Remember to keep your profile up-to-date to increase your chances of getting hired or finding the right talent.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        <h3>How do I verify my account?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Account verification helps build trust in the platform. To verify your account:</p>
                        <ol>
                            <li>Go to "Account Settings"</li>
                            <li>Select the "Verification" tab</li>
                            <li>Follow the instructions to verify your:
                                <ul>
                                    <li>Email address</li>
                                    <li>Phone number</li>
                                    <li>Identity (by uploading a government-issued ID)</li>
                                </ul>
                            </li>
                            <li>Once all verifications are complete, you'll receive a verified badge on your profile</li>
                        </ol>
                        <p>Verified accounts typically receive more opportunities and have higher success rates.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="faq-question">
                        <h3>Can I have multiple accounts?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>No, our platform policy only allows one account per person. Having multiple accounts is a violation of our terms of service and may result in account suspension.</p>
                        <p>If you need to change your account type (e.g., from client to freelancer), you can do so in your account settings rather than creating a new account.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Section -->
        <div class="faq-section" id="payments">
            <h2 class="section-title">Payments & Billing</h2>

            <div class="faq-accordion">
                <div class="faq-item" data-category="payments">
                    <div class="faq-question">
                        <h3>What payment methods are accepted?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>We accept the following payment methods:</p>
                        <ul>
                            <li>Credit/Debit Cards (Visa, Mastercard, American Express)</li>
                            <li>PayPal</li>
                            <li>Bank Transfer (for certain countries)</li>
                            <li>Platform Credit</li>
                        </ul>
                        <p>You can manage your payment methods in your account settings under the "Billing" section.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="payments">
                    <div class="faq-question">
                        <h3>How do freelancers get paid?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Freelancers can withdraw their earnings using the following methods:</p>
                        <ul>
                            <li>Direct Bank Transfer</li>
                            <li>PayPal</li>
                            <li>Payoneer</li>
                        </ul>
                        <p>The withdrawal process works as follows:</p>
                        <ol>
                            <li>Client funds are held in escrow until the project or milestone is completed</li>
                            <li>Once work is approved, funds are released to your account balance</li>
                            <li>You can withdraw funds to your preferred payment method</li>
                            <li>Withdrawals are typically processed within 1-3 business days</li>
                        </ol>
                        <p>Note: There may be minimum withdrawal amounts and processing fees depending on the withdrawal method.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="payments">
                    <div class="faq-question">
                        <h3>What fees apply to transactions?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Our platform charges the following fees:</p>
                        <ul>
                            <li><strong>For Clients:</strong> A 5% service fee is added to the project budget</li>
                            <li><strong>For Freelancers:</strong> A 10% service fee is deducted from your earnings</li>
                        </ul>
                        <p>Additional fees may apply for certain payment methods or currency conversions. These fees are clearly displayed before you confirm any transaction.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="payments">
                    <div class="faq-question">
                        <h3>How do refunds work?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Refunds are handled based on the project stage and circumstances:</p>
                        <ul>
                            <li><strong>Unopened Milestone:</strong> Funds can be refunded fully if work hasn't started</li>
                            <li><strong>In-Progress Work:</strong> For disputes, our mediation team will review the case and decide on a fair resolution</li>
                            <li><strong>Completed Work:</strong> Once work is accepted and funds are released, refunds are generally not available</li>
                        </ul>
                        <p>To request a refund, contact our support team with your project ID and reason for the refund request.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="faq-section" id="projects">
            <h2 class="section-title">Projects & Services</h2>

            <div class="faq-accordion">
                <div class="faq-item" data-category="projects">
                    <div class="faq-question">
                        <h3>How do I create a new project?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>To create a new project as a client:</p>
                        <ol>
                            <li>Click on "Post a Project" in the navigation bar</li>
                            <li>Fill out the project details form, including:
                                <ul>
                                    <li>Project title</li>
                                    <li>Description</li>
                                    <li>Skills required</li>
                                    <li>Budget</li>
                                    <li>Timeline</li>
                                </ul>
                            </li>
                            <li>Upload any relevant files or examples</li>
                            <li>Review your project details</li>
                            <li>Click "Post Project" to publish</li>
                        </ol>
                        <p>Your project will be reviewed and then made available to freelancers who can submit proposals.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="projects">
                    <div class="faq-question">
                        <h3>What are milestones and how do they work?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Milestones are a way to break down larger projects into manageable parts with separate deliverables and payments.</p>
                        <p><strong>For Clients:</strong></p>
                        <ul>
                            <li>You can create milestones when setting up a project or during the project</li>
                            <li>Funds for each milestone are held in escrow until you approve the work</li>
                            <li>This provides security by only releasing payment when work meets your expectations</li>
                        </ul>
                        <p><strong>For Freelancers:</strong></p>
                        <ul>
                            <li>Milestones help you receive payment for completed work throughout a project</li>
                            <li>You can submit deliverables for each milestone separately</li>
                            <li>Once a client approves a milestone, payment is released to your account</li>
                        </ul>
                        <p>Milestones are recommended for projects that will take more than a week to complete.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="projects">
                    <div class="faq-question">
                        <h3>How do I find the right freelancer for my project?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>To find the right freelancer for your project:</p>
                        <ol>
                            <li>Review proposals submitted to your project carefully</li>
                            <li>Check freelancer profiles, including:
                                <ul>
                                    <li>Portfolio of past work</li>
                                    <li>Client reviews and ratings</li>
                                    <li>Skills and certifications</li>
                                    <li>Experience level</li>
                                </ul>
                            </li>
                            <li>Use the "Browse Freelancers" feature to proactively search for talent</li>
                            <li>Interview potential candidates using our messaging system or schedule a video call</li>
                            <li>Consider working on a small test project first for important or long-term work</li>
                        </ol>
                        <p>Taking time to find the right match will save you time and resources in the long run.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="projects">
                    <div class="faq-question">
                        <h3>What if I'm not satisfied with the work?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>If you're not satisfied with the work delivered:</p>
                        <ol>
                            <li>First, communicate clearly with the freelancer about what needs to be improved</li>
                            <li>Most freelancers will revise their work to meet your expectations</li>
                            <li>If you still can't reach an agreement, you can:
                                <ul>
                                    <li>Request revisions (if included in the contract)</li>
                                    <li>Request a partial refund</li>
                                    <li>Open a dispute through our dispute resolution center</li>
                                </ul>
                            </li>
                            <li>Our support team will review the case and help reach a fair resolution</li>
                        </ol>
                        <p>To avoid issues, we recommend providing clear requirements upfront and maintaining open communication throughout the project.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Communication Section -->
        <div class="faq-section" id="communication">
            <h2 class="section-title">Communication Tools</h2>

            <div class="faq-accordion">
                <div class="faq-item" data-category="communication">
                    <div class="faq-question">
                        <h3>How do I communicate with clients/freelancers?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Our platform offers several ways to communicate:</p>
                        <ul>
                            <li><strong>Messaging System:</strong> Send direct messages through our secure messaging system</li>
                            <li><strong>Project Room:</strong> Each project has a dedicated space for discussions and file sharing</li>
                            <li><strong>Video Calls:</strong> Schedule and conduct video meetings right from the platform</li>
                            <li><strong>Comments:</strong> Leave comments on specific deliverables or project milestones</li>
                        </ul>
                        <p>All communications are stored securely and can be referenced later if needed.</p>
                        <p>For your protection, we recommend keeping all project-related communication on the platform rather than using external channels.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="communication">
                    <div class="faq-question">
                        <h3>Can I share files with freelancers/clients?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can share files in several ways:</p>
                        <ul>
                            <li><strong>Project Attachments:</strong> Add files when creating or updating a project</li>
                            <li><strong>Message Attachments:</strong> Attach files to messages in the messaging system</li>
                            <li><strong>Milestone Deliverables:</strong> Submit or receive files as part of milestone deliverables</li>
                            <li><strong>Shared Workspace:</strong> Use the collaborative workspace for larger projects</li>
                        </ul>
                        <p>Supported file types include PDFs, images, documents, spreadsheets, and compressed files. The maximum file size is 25MB per upload.</p>
                        <p>For larger files, we recommend using file sharing links from cloud storage services.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="communication">
                    <div class="faq-question">
                        <h3>How do I schedule a meeting?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>To schedule a meeting with a client or freelancer:</p>
                        <ol>
                            <li>Go to the project workspace or open a conversation with the person</li>
                            <li>Click on the "Schedule Meeting" button</li>
                            <li>Select a date and time that works for you</li>
                            <li>Add meeting details and agenda</li>
                            <li>Click "Send Invitation"</li>
                            <li>The other party will receive a notification and can accept, suggest a different time, or decline</li>
                        </ol>
                        <p>When it's time for the meeting, both parties will receive a reminder notification with a link to join the video call.</p>
                        <p>Meetings can be recorded with the consent of all participants, and recordings are available for 30 days.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="communication">
                    <div class="faq-question">
                        <h3>Is my communication private and secure?</h3>
                        <span class="toggle-icon"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, all communications on our platform are private and secure:</p>
                        <ul>
                            <li>Messages are encrypted end-to-end</li>
                            <li>Files are stored securely with encryption</li>
                            <li>Video calls use secure protocols</li>
                            <li>Only parties involved in the project can access the communications</li>
                            <li>Our support team may access communications only in case of disputes or reported violations</li>
                        </ul>
                        <p>We take your privacy seriously and comply with all applicable data protection regulations. You can review our Privacy Policy for more details on how we handle your data.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="faq-still-need-help">
        <h2>Still Need Help?</h2>
        <p>If you couldn't find the answer you were looking for, our support team is ready to assist you.</p>
        <div class="help-buttons"> <a href="<?php echo URL_ROOT; ?>/support/newTicket" class="help-btn primary">Create Support Ticket</a>
            <a href="<?php echo URL_ROOT; ?>/support/contact" class="help-btn secondary">Contact Us</a>
        </div>
    </div>
</div>

<style>
    .faq-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .faq-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .faq-header h1 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .faq-header p {
        font-size: 18px;
        color: #74767e;
    }

    .faq-search {
        max-width: 600px;
        margin: 0 auto 40px;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        color: #74767e;
        font-size: 18px;
    }

    .faq-search-input {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border: 1px solid #e4e5e7;
        border-radius: 8px;
        font-size: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .faq-search-input:focus {
        border-color: #2c3e50;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        outline: none;
    }

    .faq-categories {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
    }

    .category-btn {
        background: #f5f5f7;
        border: 1px solid #e4e5e7;
        color: #404145;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-btn:hover {
        background: #e4e5e7;
    }

    .category-btn.active {
        background: #2c3e50;
        color: white;
        border-color: #2c3e50;
    }

    .faq-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e4e5e7;
    }

    .faq-accordion {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .faq-item {
        border: 1px solid #e4e5e7;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .faq-question:hover {
        background: #f9f9f9;
    }

    .faq-question h3 {
        font-size: 17px;
        color: #2c3e50;
        margin: 0;
        font-weight: 500;
    }

    .toggle-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 24px;
        height: 24px;
        color: #2c3e50;
        transition: transform 0.3s ease;
    }

    .faq-item.active .toggle-icon {
        transform: rotate(45deg);
    }

    .faq-answer {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #f9f9f9;
    }

    .faq-item.active .faq-answer {
        padding: 20px;
        max-height: 1000px;
        /* Arbitrary large value to accommodate content */
    }

    .faq-answer p {
        margin-top: 0;
        color: #404145;
        line-height: 1.6;
    }

    .faq-answer ul,
    .faq-answer ol {
        color: #404145;
        padding-left: 20px;
    }

    .faq-answer li {
        margin-bottom: 10px;
    }

    .faq-still-need-help {
        text-align: center;
        padding: 40px 20px;
        background: #f5f5f7;
        border-radius: 8px;
        margin-top: 40px;
    }

    .faq-still-need-help h2 {
        font-size: 24px;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .faq-still-need-help p {
        font-size: 16px;
        color: #404145;
        margin-bottom: 25px;
    }

    .help-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .help-btn {
        display: inline-block;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .help-btn.primary {
        background: #2c3e50;
        color: white;
    }

    .help-btn.primary:hover {
        background: #1a252f;
        transform: translateY(-2px);
    }

    .help-btn.secondary {
        background: white;
        color: #2c3e50;
        border: 1px solid #2c3e50;
    }

    .help-btn.secondary:hover {
        background: #f5f5f7;
        transform: translateY(-2px);
    }

    /* Hide non-matching elements when filtering */
    .faq-item.hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .faq-header h1 {
            font-size: 28px;
        }

        .faq-header p {
            font-size: 16px;
        }

        .category-btn {
            font-size: 13px;
            padding: 6px 12px;
        }

        .section-title {
            font-size: 22px;
        }

        .faq-question h3 {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .faq-header h1 {
            font-size: 24px;
        }

        .faq-categories {
            flex-wrap: wrap;
        }

        .category-btn {
            font-size: 12px;
            padding: 5px 10px;
        }

        .help-buttons {
            flex-direction: column;
        }

        .help-btn {
            width: 100%;
        }
    }

    /* Knowledge Base Styles */
    .knowledge-base {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .kb-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .kb-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .kb-title h2 {
        margin: 0;
        font-size: 1.4rem;
        color: #333;
    }

    .kb-title p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .kb-input-container {
        display: flex;
        margin-bottom: 15px;
        position: relative;
    }

    .kb-input {
        flex: 1;
        padding: 15px 20px;
        font-size: 1rem;
        border: 1px solid #e1e5eb;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .kb-input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 3px 10px rgba(78, 115, 223, 0.15);
    }

    .kb-button {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border: none;
        padding: 0 25px;
        border-radius: 30px;
        margin-left: 10px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        transition: all 0.3s;
    }

    .kb-button i {
        margin-right: 8px;
    }

    .kb-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(78, 115, 223, 0.4);
    }

    .kb-voice-button {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #e1e5eb;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .kb-voice-button:hover {
        color: #4e73df;
        border-color: #4e73df;
    }

    .kb-voice-button.listening {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    .kb-suggestions {
        margin-bottom: 20px;
    }

    .kb-suggestions h3 {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .suggestion-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .suggestion-chip {
        background: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: #495057;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #e1e5eb;
    }

    .suggestion-chip:hover {
        background: #f8f9fa;
        color: #4e73df;
        border-color: #4e73df;
    }

    .kb-answer-area {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .kb-answer-area.hidden {
        display: none;
    }

    .kb-answer-area.visible {
        display: block;
        animation: fadeInUp 0.4s forwards;
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

    .kb-answer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .kb-answer-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #333;
    }

    .kb-actions {
        display: flex;
        gap: 8px;
    }

    .kb-action-btn,
    .kb-close-btn {
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        padding: 5px;
        border-radius: 5px;
        transition: all 0.2s;
    }

    .kb-action-btn:hover {
        color: #4e73df;
        background: #f8f9fa;
    }

    .kb-close-btn:hover {
        color: #dc3545;
        background: #f8f9fa;
    }

    .kb-answer-content {
        font-size: 1rem;
        line-height: 1.6;
        color: #495057;
    }

    .kb-answer-content p {
        margin-bottom: 15px;
    }

    .kb-answer-content a {
        color: #4e73df;
        text-decoration: none;
    }

    .kb-answer-content a:hover {
        text-decoration: underline;
    }

    .kb-answer-content ul,
    .kb-answer-content ol {
        padding-left: 20px;
        margin-bottom: 15px;
    }

    .kb-answer-content li {
        margin-bottom: 5px;
    }

    .kb-related-questions {
        margin-top: 20px;
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }

    .kb-related-questions h4 {
        font-size: 1rem;
        color: #343a40;
        margin-bottom: 10px;
    }

    .related-questions-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .related-questions-list li {
        margin-bottom: 8px;
    }

    .related-questions-list a {
        color: #4e73df;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .related-questions-list a i {
        margin-right: 8px;
        font-size: 0.8rem;
    }

    .related-questions-list a:hover {
        text-decoration: underline;
    }

    .kb-feedback {
        margin-top: 20px;
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
        text-align: center;
    }

    .kb-feedback p {
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: #6c757d;
    }

    .kb-feedback-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .kb-feedback-btn {
        background: #f8f9fa;
        border: 1px solid #e1e5eb;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .kb-feedback-btn i {
        margin-right: 8px;
    }

    .kb-feedback-btn[data-feedback="yes"]:hover {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .kb-feedback-btn[data-feedback="no"]:hover {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .kb-chat-history {
        margin-top: 20px;
    }

    .kb-chat-history.hidden {
        display: none;
    }

    .kb-history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .kb-history-header h3 {
        margin: 0;
        font-size: 0.95rem;
        color: #6c757d;
    }

    .kb-history-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .kb-history-item {
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        color: #495057;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }

    .kb-history-item:last-child {
        border-bottom: none;
    }

    .kb-history-item i {
        margin-right: 10px;
        color: #adb5bd;
    }

    .kb-history-item:hover {
        color: #4e73df;
    }

    .kb-history-item:hover i {
        color: #4e73df;
    }

    /* Loading animation */
    .kb-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 0;
    }

    .kb-loading-spinner {
        width: 40px;
        height: 40px;
        position: relative;
    }

    .kb-loading-spinner div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 32px;
        height: 32px;
        margin: 4px;
        border: 4px solid #4e73df;
        border-radius: 50%;
        animation: kb-loading-spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #4e73df transparent transparent transparent;
    }

    .kb-loading-spinner div:nth-child(1) {
        animation-delay: -0.45s;
    }

    .kb-loading-spinner div:nth-child(2) {
        animation-delay: -0.3s;
    }

    .kb-loading-spinner div:nth-child(3) {
        animation-delay: -0.15s;
    }

    @keyframes kb-loading-spinner {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Accordion functionality
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');

            question.addEventListener('click', () => {
                // Check if this item is already active
                const isActive = item.classList.contains('active');

                // Close all items
                faqItems.forEach(faqItem => {
                    faqItem.classList.remove('active');
                });

                // If the clicked item wasn't active before, make it active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });

        // Category filtering
        const categoryButtons = document.querySelectorAll('.category-btn');

        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                button.classList.add('active');

                // Get selected category
                const selectedCategory = button.getAttribute('data-category');

                // Filter FAQ items
                faqItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');

                    if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Show/hide sections based on selection
                const faqSections = document.querySelectorAll('.faq-section');
                faqSections.forEach(section => {
                    if (selectedCategory === 'all' || section.id === selectedCategory) {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        const searchInput = document.getElementById('faq-search-input');

        if (searchInput) {
            // Check for URL search parameter
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');

            // If a search query is in the URL, use it
            if (searchQuery) {
                searchInput.value = searchQuery;
                filterFaqItems(searchQuery);
            }

            searchInput.addEventListener('input', function() {
                filterFaqItems(this.value);
            });
        }

        function filterFaqItems(query) {
            const normalizedQuery = query.toLowerCase().trim();

            if (normalizedQuery === '') {
                // If search is empty, show all items and select "All" category
                faqItems.forEach(item => {
                    item.classList.remove('hidden');
                });

                document.querySelector('.category-btn[data-category="all"]').click();
            } else {
                // Show all sections for search
                const faqSections = document.querySelectorAll('.faq-section');
                faqSections.forEach(section => {
                    section.style.display = 'block';
                });

                // Set "All" category as active
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                document.querySelector('.category-btn[data-category="all"]').classList.add('active');

                // Filter items based on query
                faqItems.forEach(item => {
                    const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-answer').textContent.toLowerCase();

                    if (question.includes(normalizedQuery) || answer.includes(normalizedQuery)) {
                        item.classList.remove('hidden');
                        item.classList.add('active'); // Open matching items
                    } else {
                        item.classList.add('hidden');
                    }
                });
            }
        }

        // Check for hash in URL (for direct links to sections)
        if (window.location.hash) {
            const hash = window.location.hash.substring(1);
            const targetSection = document.getElementById(hash);

            if (targetSection) {
                // Scroll to the section
                targetSection.scrollIntoView({
                    behavior: 'smooth'
                });

                // Set the corresponding category as active
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                document.querySelector(`.category-btn[data-category="${hash}"]`)?.classList.add('active');

                // Show this section and hide others
                const faqSections = document.querySelectorAll('.faq-section');
                faqSections.forEach(section => {
                    if (section.id === hash) {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });

                // Show only items in this category
                faqItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');

                    if (itemCategory === hash) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            }
        }

        // Knowledge Base Functionality
        const kbQuestionInput = document.getElementById('kb-question-input');
        const kbAskButton = document.getElementById('kb-ask-button');
        const kbSuggestions = document.getElementById('kb-suggestions');
        const kbAnswerArea = document.getElementById('kb-answer-area');
        const kbAnswerContent = document.getElementById('kb-answer-content');
        const kbCloseAnswer = document.getElementById('kb-close-answer');
        const suggestionChips = document.querySelectorAll('.suggestion-chip');
        const feedbackButtons = document.querySelectorAll('.kb-feedback-btn');
        const textToSpeechBtn = document.getElementById('kb-text-to-speech');
        const copyAnswerBtn = document.getElementById('kb-copy-answer');
        const chatHistory = document.getElementById('kb-chat-history');
        const historyList = document.querySelector('.kb-history-list');
        const clearHistoryBtn = document.getElementById('kb-clear-history');
        const relatedQuestions = document.getElementById('kb-related-questions');
        const relatedQuestionsList = document.querySelector('.related-questions-list');
        const voiceButton = document.getElementById('kb-voice-button');

        // FAQ Knowledge Base
        const knowledgeBase = {
            'how do i reset my password': {
                answer: `<p>To reset your password:</p>
                        <ol>
                            <li>Click on your profile picture in the top-right corner</li>
                            <li>Select "Account Settings"</li>
                            <li>Go to the "Security" tab</li>
                            <li>Click "Reset Password"</li>
                            <li>Follow the instructions sent to your email</li>
                        </ol>
                        <p>If you don't have access to your email, please contact support.</p>`
            },
            'where can i view my invoices': {
                answer: `<p>You can view and download your invoices by following these steps:</p>
                        <ol>
                            <li>Navigate to your Dashboard</li>
                            <li>Click on "Billing" in the sidebar</li>
                            <li>Select the "Invoices" tab</li>
                            <li>Here you'll see a list of all your invoices</li>
                            <li>Click on any invoice to view details or download as PDF</li>
                        </ol>`
            },
            'how do i cancel my subscription': {
                answer: `<p>To cancel your subscription:</p>
                        <ol>
                            <li>Go to your Dashboard</li>
                            <li>Click "Billing" in the sidebar</li>
                            <li>Select "Subscription"</li>
                            <li>Click "Cancel Subscription"</li>
                            <li>Follow the prompts to confirm cancellation</li>
                        </ol>
                        <p>Note: If you cancel, your subscription will remain active until the end of your current billing period.</p>`
            },
            'how to contact support': {
                answer: `<p>There are several ways to contact our support team:</p>
                        <ul>
                            <li><strong>Submit a ticket</strong> - Go to the Support Center and click "Create New Ticket"</li>
                            <li><strong>Live Chat</strong> - Use the chat widget available on all pages (bottom right)</li>
                            <li><strong>Email</strong> - Send an email to support@example.com</li>
                            <li><strong>Phone</strong> - Call us at 1-800-123-4567 (9am-5pm EST, Mon-Fri)</li>
                        </ul>`
            }
        };

        // Handle asking a question
        function askQuestion(question) {
            kbAnswerArea.classList.remove('hidden');
            kbSuggestions.style.display = 'none';

            // Show loading state
            kbAnswerContent.innerHTML = `
                <div class="kb-loading">
                    <div class="kb-loading-spinner">
                        <div></div><div></div><div></div><div></div>
                    </div>
                </div>
            `;

            // Normalize the question for matching
            const normalizedQuestion = question.toLowerCase().trim();

            // Simulate AI processing delay
            setTimeout(() => {
                let answer = '';

                // Check if we have a direct match
                for (const [key, value] of Object.entries(knowledgeBase)) {
                    if (normalizedQuestion.includes(key)) {
                        answer = value.answer;
                        break;
                    }
                }

                // If no direct match, provide a general response
                if (!answer) {
                    answer = `
                        <p>I don't have a specific answer to that question yet.</p>
                        <p>You might try:</p>
                        <ul>
                            <li>Checking our <a href="#faq">FAQ section</a> below</li>
                            <li>Creating a <a href="${URL_ROOT}/support/newTicket">support ticket</a></li>
                            <li>Contacting us directly via <a href="${URL_ROOT}/support/contact">email or phone</a></li>
                        </ul>
                    `;
                }

                kbAnswerContent.innerHTML = answer;

                // Track this question for analytics (in a real app)
                console.log('Question asked:', question);
            }, 1500);
        }

        // Event listeners
        kbAskButton.addEventListener('click', () => {
            const question = kbQuestionInput.value.trim();
            if (question) {
                askQuestion(question);
            }
        });

        kbQuestionInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const question = kbQuestionInput.value.trim();
                if (question) {
                    askQuestion(question);
                }
            }
        });

        suggestionChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const question = chip.dataset.question;
                kbQuestionInput.value = question;
                askQuestion(question);
            });
        });

        kbCloseAnswer.addEventListener('click', () => {
            kbAnswerArea.classList.add('hidden');
            kbSuggestions.style.display = 'block';
        });

        feedbackButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const feedback = btn.dataset.feedback;

                // In a real app, you would send this feedback to your server
                console.log('Answer feedback:', feedback);

                // Show thank you message
                const feedbackParent = btn.closest('.kb-feedback');
                feedbackParent.innerHTML = `
                    <p>Thank you for your feedback! We'll use it to improve our answers.</p>
                `;
            });
        });

        textToSpeechBtn.addEventListener('click', function() {
            // Get just the answer text (not the question)
            const answerText = answerContent.querySelector('.kb-answer').textContent;

            if ('speechSynthesis' in window) {
                const speech = new SpeechSynthesisUtterance();
                speech.text = answerText;
                speech.volume = 1;
                speech.rate = 1;
                speech.pitch = 1;

                window.speechSynthesis.speak(speech);

                // Visual feedback
                this.innerHTML = '<i class="fas fa-volume-mute"></i>';
                speech.onend = () => {
                    this.innerHTML = '<i class="fas fa-volume-up"></i>';
                };
            } else {
                alert('Sorry, your browser does not support text-to-speech');
            }
        });

        copyAnswerBtn.addEventListener('click', function() {
            // Get just the answer text (not the question)
            const answerText = answerContent.querySelector('.kb-answer').textContent;

            navigator.clipboard.writeText(answerText).then(() => {
                // Visual feedback
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-copy"></i>';
                }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        });

        voiceButton.addEventListener('click', function() {
            if ('webkitSpeechRecognition' in window) {
                const recognition = new webkitSpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;

                // Visual feedback for listening
                voiceButton.classList.add('listening');
                voiceButton.innerHTML = '<i class="fas fa-microphone-slash"></i>';

                recognition.onstart = function() {
                    console.log('Voice recognition started');
                };

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    questionInput.value = transcript;
                    askQuestion(transcript);
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error', event.error);
                    voiceButton.classList.remove('listening');
                    voiceButton.innerHTML = '<i class="fas fa-microphone"></i>';
                };

                recognition.onend = function() {
                    voiceButton.classList.remove('listening');
                    voiceButton.innerHTML = '<i class="fas fa-microphone"></i>';
                };

                recognition.start();
            } else {
                alert('Sorry, your browser does not support voice recognition');
            }
        });

        clearHistoryBtn.addEventListener('click', function() {
            localStorage.removeItem('kbHistory');
            updateHistoryDisplay();
        });

        // Initialize
        updateHistoryDisplay();

        // Store history in local storage
        function addToHistory(question) {
            let history = JSON.parse(localStorage.getItem('kbHistory') || '[]');

            // Avoid duplicates
            if (!history.includes(question)) {
                history.unshift(question); // Add to beginning

                // Limit history to 5 items
                if (history.length > 5) {
                    history = history.slice(0, 5);
                }

                localStorage.setItem('kbHistory', JSON.stringify(history));
            }

            // Update history display
            updateHistoryDisplay();
        }

        // Update history display
        function updateHistoryDisplay() {
            let history = JSON.parse(localStorage.getItem('kbHistory') || '[]');

            // Clear current list
            historyList.innerHTML = '';

            // Add items
            history.forEach(question => {
                const li = document.createElement('li');
                li.className = 'kb-history-item';
                li.innerHTML = `<i class="fas fa-history"></i> ${question}`;
                historyList.appendChild(li);

                // Add event listener
                li.addEventListener('click', function() {
                    askQuestion(question);
                });
            });

            // Show/hide based on content
            if (history.length > 0) {
                chatHistory.classList.remove('hidden');
            } else {
                chatHistory.classList.add('hidden');
            }
        }

        // Search knowledge base for answers
        function searchKnowledgeBase(question) {
            question = question.toLowerCase();
            let bestMatch = null;
            let highestScore = 0;
            let category = null;

            // Check each category
            for (const [cat, data] of Object.entries(knowledgeBase)) {
                // Calculate match score based on keywords
                let score = data.keywords.reduce((total, keyword) => {
                    return question.includes(keyword) ? total + 1 : total;
                }, 0);

                if (score > highestScore) {
                    highestScore = score;
                    category = cat;

                    // Determine best answer type within category
                    let answerType = Object.keys(data.answers)[0]; // Default to first
                    for (const type of Object.keys(data.answers)) {
                        if (question.includes(type)) {
                            answerType = type;
                            break;
                        }
                    }

                    bestMatch = data.answers[answerType];
                }
            }

            // If no good match found
            if (highestScore === 0) {
                return {
                    answer: "I'm sorry, I couldn't find an exact match for your question. Here are some options that might help:<br><br>1. Try rephrasing your question<br>2. Check our FAQ sections below<br>3. Contact our support team for personalized assistance",
                    related: [
                        "How do I contact support?",
                        "Where can I find help resources?",
                        "How do I create a support ticket?"
                    ]
                };
            }

            return {
                answer: bestMatch.replace(/\n\n/g, '<br><br>'),
                related: knowledgeBase[category].related
            };
        }

        // Display answer
        function displayAnswer(result, question) {
            // Clear previous content
            answerContent.innerHTML = '';
            relatedQuestionsList.innerHTML = '';

            // Add question header
            const questionEl = document.createElement('div');
            questionEl.className = 'kb-question';
            questionEl.innerHTML = `<strong>Q: ${question}</strong>`;
            answerContent.appendChild(questionEl);

            // Add answer content
            const answerEl = document.createElement('div');
            answerEl.className = 'kb-answer';
            answerEl.innerHTML = `<p>${result.answer}</p>`;
            answerContent.appendChild(answerEl);

            // Add related questions
            result.related.forEach(relatedQ => {
                const li = document.createElement('li');
                li.innerHTML = `<a href="#" class="related-question"><i class="fas fa-circle-question"></i>${relatedQ}</a>`;
                relatedQuestionsList.appendChild(li);
            });

            // Show answer area
            answerArea.classList.remove('hidden');
            answerArea.classList.add('visible');

            // Add to history
            addToHistory(question);

            // Update related questions event listeners
            document.querySelectorAll('.related-question').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    askQuestion(this.textContent);
                });
            });
        }
    });
</script>