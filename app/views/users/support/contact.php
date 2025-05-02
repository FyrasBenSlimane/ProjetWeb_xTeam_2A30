<?php require APPROOT . '/views/layouts/header.php'; ?>

<section class="contact-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in">
                <h1 class="display-4 fw-bold mb-3">Get In <span class="text-gradient">Touch</span></h1>
                <p class="lead text-muted mb-4">Have questions or need assistance? Our support team is here to help you succeed.</p>

                <div class="contact-stats d-flex flex-wrap mb-4">
                    <div class="contact-stat-item me-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary-light rounded-circle p-2 me-2">
                                <i class="fas fa-headset text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">24/7</h5>
                                <small class="text-muted">Support</small>
                            </div>
                        </div>
                    </div>
                    <div class="contact-stat-item me-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success-light rounded-circle p-2 me-2">
                                <i class="fas fa-clock text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">1 Hour</h5>
                                <small class="text-muted">Avg. Response</small>
                            </div>
                        </div>
                    </div>
                    <div class="contact-stat-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-info-light rounded-circle p-2 me-2">
                                <i class="fas fa-check-circle text-info"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">98%</h5>
                                <small class="text-muted">Resolution Rate</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="quick-actions">
                    <span class="text-muted me-2">Quick links:</span>
                    <a href="#general-inquiries" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">General</a>
                    <a href="#technical-support" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">Technical</a>
                    <a href="#billing-inquiries" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">Billing</a>
                    <a href="#business-inquiries" class="badge rounded-pill bg-light text-dark mb-2 py-2 px-3 quick-link">Business</a>
                </div>
            </div>
            <div class="col-lg-6 animate-fade-in-right">
                <div class="contact-hero-image">
                    <img src="<?php echo URLROOT; ?>/public/images/contact-hero.svg" alt="Contact Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <!-- Contact Options -->
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-id-badge me-2"></i> Contact Options</h5>
                        </div>
                        <div class="list-group list-group-flush contact-options">
                            <a href="#general-inquiries" class="list-group-item list-group-item-action d-flex align-items-center active">
                                <i class="fas fa-question-circle me-3"></i>
                                <div>
                                    <span class="d-block">General Inquiries</span>
                                    <small class="text-muted">Questions about our platform</small>
                                </div>
                            </a>
                            <a href="#technical-support" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-tools me-3"></i>
                                <div>
                                    <span class="d-block">Technical Support</span>
                                    <small class="text-muted">Help with platform issues</small>
                                </div>
                            </a>
                            <a href="#billing-inquiries" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-credit-card me-3"></i>
                                <div>
                                    <span class="d-block">Billing Inquiries</span>
                                    <small class="text-muted">Payment and subscription help</small>
                                </div>
                            </a>
                            <a href="#business-inquiries" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-briefcase me-3"></i>
                                <div>
                                    <span class="d-block">Business Inquiries</span>
                                    <small class="text-muted">Partnerships and collaborations</small>
                                </div>
                            </a>
                            <a href="#support-ticket" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-ticket-alt me-3"></i>
                                <div>
                                    <span class="d-block">Support Ticket</span>
                                    <small class="text-muted">Create a new ticket</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body bg-light">
                            <h5 class="mb-3"><i class="fas fa-address-card me-2"></i> Contact Information</h5>
                            <ul class="list-unstyled contact-info">
                                <li class="mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Address:</strong>
                                            <address class="mb-0">
                                                1234 Innovation Way<br>
                                                Tech District, TH 10101<br>
                                                United States
                                            </address>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-phone-alt text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Phone:</strong><br>
                                            <a href="tel:+15555551234" class="text-decoration-none">+1 (555) 555-1234</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex">
                                        <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Email:</strong><br>
                                            <a href="mailto:support@lensi.com" class="text-decoration-none">support@lensi.com</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex">
                                        <i class="fas fa-clock text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Hours:</strong><br>
                                            Monday - Friday: 8am - 8pm EST<br>
                                            Weekend: 10am - 6pm EST
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="social-links mt-4">
                                <h6>Connect With Us:</h6>
                                <div class="d-flex">
                                    <a href="#" class="social-icon me-2" title="Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="social-icon me-2" title="Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="#" class="social-icon me-2" title="LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" class="social-icon me-2" title="Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="#" class="social-icon" title="YouTube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Contact Form Sections -->
                <div class="contact-forms">
                    <!-- General Inquiries Form -->
                    <div id="general-inquiries" class="contact-section mb-5 reveal">
                        <div class="card border-0 shadow-sm contact-card">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="support-icon-box support-icon-box-primary me-3">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">General Inquiries</h3>
                                        <p class="text-muted mb-0">Questions about our platform or services</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <form id="generalInquiryForm" method="post" action="<?php echo URLROOT; ?>/support/submitContact" class="needs-validation" novalidate>
                                    <input type="hidden" name="inquiry_type" value="general">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="general-name" class="support-form-label">Your Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="general-name" name="name" required>
                                                <div class="invalid-feedback">Please enter your name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="general-email" class="support-form-label">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                                <input type="email" class="form-control support-form-control" id="general-email" name="email" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="general-subject" class="support-form-label">Subject <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                                            <input type="text" class="form-control support-form-control" id="general-subject" name="subject" required>
                                            <div class="invalid-feedback">Please enter a subject.</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="general-message" class="support-form-label">Message <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-comment text-muted"></i></span>
                                            <textarea class="form-control support-form-control" id="general-message" name="message" rows="5" required></textarea>
                                            <div class="invalid-feedback">Please enter your message.</div>
                                        </div>
                                        <div class="form-text">Please be as specific as possible to help us assist you better.</div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="general-copy" name="send_copy">
                                        <label class="form-check-label" for="general-copy">Send me a copy of this message</label>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-lock me-1"></i> Your information is secure and will not be shared
                                        </div>
                                        <button type="submit" class="btn btn-primary px-4 py-2">
                                            <i class="fas fa-paper-plane me-2"></i> Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Support Form with enhanced UX -->
                    <div id="technical-support" class="contact-section mb-5 reveal">
                        <div class="card border-0 shadow-sm contact-card">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="support-icon-box support-icon-box-info me-3">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">Technical Support</h3>
                                        <p class="text-muted mb-0">Get help with technical issues</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-info border-left-info mb-4">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-info-circle fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading">Faster Resolution Tips</h5>
                                            <p class="mb-0">For quicker resolution, please include: steps to reproduce the issue, any error messages you see, and screenshots if possible.</p>
                                        </div>
                                    </div>
                                </div>
                                <form id="technicalSupportForm" method="post" action="<?php echo URLROOT; ?>/support/submitContact" class="needs-validation" novalidate enctype="multipart/form-data">
                                    <input type="hidden" name="inquiry_type" value="technical">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-name" class="support-form-label">Your Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="tech-name" name="name" required>
                                                <div class="invalid-feedback">Please enter your name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-email" class="support-form-label">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                                <input type="email" class="form-control support-form-control" id="tech-email" name="email" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-subject" class="support-form-label">Subject <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="tech-subject" name="subject" required>
                                                <div class="invalid-feedback">Please enter a subject.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-priority" class="support-form-label">Priority</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-flag text-muted"></i></span>
                                                <select class="form-select support-form-control" id="tech-priority" name="priority">
                                                    <option value="low">Low - Minor issues, non-urgent</option>
                                                    <option value="medium" selected>Medium - Standard support request</option>
                                                    <option value="high">High - Significant impact to workflow</option>
                                                    <option value="critical">Critical - System unusable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tech-message" class="support-form-label">Issue Description <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-comment-alt text-muted"></i></span>
                                            <textarea class="form-control support-form-control" id="tech-message" name="message" rows="5" required placeholder="Please provide detailed steps to reproduce the issue and what you expected to happen..."></textarea>
                                            <div class="invalid-feedback">Please describe your issue.</div>
                                        </div>
                                        <div class="form-text">The more details you provide, the faster we can help you.</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-browser" class="support-form-label">Browser & Device Information</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-desktop text-muted"></i></span>
                                                <textarea class="form-control support-form-control" id="tech-browser" name="browser_info" rows="2" placeholder="E.g., Chrome 98 on Windows 10, iPhone 13 with iOS 15, etc."></textarea>
                                            </div>
                                            <div class="form-text">This helps us reproduce your specific issue.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tech-attachment" class="support-form-label">Attach Screenshots (optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-paperclip text-muted"></i></span>
                                                <input type="file" class="form-control support-form-control" id="tech-attachment" name="attachment" accept="image/png, image/jpeg, image/gif">
                                            </div>
                                            <div class="form-text">Maximum file size: 5MB</div>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="tech-copy" name="send_copy">
                                        <label class="form-check-label" for="tech-copy">Send me a copy of this message</label>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-lock me-1"></i> Your information is secure
                                        </div>
                                        <button type="submit" class="btn btn-info text-white px-4 py-2">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Support Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Inquiries Form with enhanced UI/UX -->
                    <div id="billing-inquiries" class="contact-section mb-5 reveal">
                        <div class="card border-0 shadow-sm contact-card">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="support-icon-box support-icon-box-success me-3">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">Billing Inquiries</h3>
                                        <p class="text-muted mb-0">Questions about payments and subscriptions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-success border-left-success mb-4">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-lightbulb fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading">Need Immediate Help?</h5>
                                            <p class="mb-0">For urgent billing matters, please include your account ID or transaction number to help us assist you faster.</p>
                                        </div>
                                    </div>
                                </div>
                                <form id="billingInquiryForm" method="post" action="<?php echo URLROOT; ?>/support/submitContact" class="needs-validation" novalidate>
                                    <input type="hidden" name="inquiry_type" value="billing">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="billing-name" class="support-form-label">Your Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="billing-name" name="name" required>
                                                <div class="invalid-feedback">Please enter your name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="billing-email" class="support-form-label">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                                <input type="email" class="form-control support-form-control" id="billing-email" name="email" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="billing-subject" class="support-form-label">Subject <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="billing-subject" name="subject" required>
                                                <div class="invalid-feedback">Please enter a subject.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="billing-type" class="support-form-label">Billing Issue Type</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-tags text-muted"></i></span>
                                                <select class="form-select support-form-control" id="billing-type" name="billing_type">
                                                    <option value="payment">Payment Issue</option>
                                                    <option value="subscription">Subscription Management</option>
                                                    <option value="refund">Refund Request</option>
                                                    <option value="invoice">Invoice Request</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="billing-message" class="support-form-label">Message <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-comment text-muted"></i></span>
                                            <textarea class="form-control support-form-control" id="billing-message" name="message" rows="5" required placeholder="Please describe your billing issue in detail..."></textarea>
                                            <div class="invalid-feedback">Please enter your message.</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="billing-order" class="support-form-label">Order/Transaction ID (if applicable)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-receipt text-muted"></i></span>
                                            <input type="text" class="form-control support-form-control" id="billing-order" name="order_id" placeholder="e.g. ORD-12345 or TRX-67890">
                                        </div>
                                        <div class="form-text">Adding your transaction ID helps us locate your records faster.</div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="billing-copy" name="send_copy">
                                        <label class="form-check-label" for="billing-copy">Send me a copy of this message</label>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-lock me-1"></i> Your payment information is secure
                                        </div>
                                        <button type="submit" class="btn btn-success px-4 py-2">
                                            <i class="fas fa-paper-plane me-2"></i> Send Inquiry
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Business Inquiries Form with enhanced UI/UX -->
                    <div id="business-inquiries" class="contact-section mb-5 reveal">
                        <div class="card border-0 shadow-sm contact-card">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="support-icon-box support-icon-box-warning me-3">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">Business Inquiries</h3>
                                        <p class="text-muted mb-0">Partnerships, collaborations, and press</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-warning border-left-warning mb-4">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas fa-handshake fa-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="alert-heading">Looking to Partner With Us?</h5>
                                            <p class="mb-0">Please include details about your organization, your goals, and how you envision the partnership to help us evaluate your proposal promptly.</p>
                                        </div>
                                    </div>
                                </div>
                                <form id="businessInquiryForm" method="post" action="<?php echo URLROOT; ?>/support/submitContact" class="needs-validation" novalidate>
                                    <input type="hidden" name="inquiry_type" value="business">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="business-name" class="support-form-label">Your Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="business-name" name="name" required>
                                                <div class="invalid-feedback">Please enter your name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="business-email" class="support-form-label">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                                <input type="email" class="form-control support-form-control" id="business-email" name="email" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="business-company" class="support-form-label">Company Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="business-company" name="company" required>
                                                <div class="invalid-feedback">Please enter your company name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="business-position" class="support-form-label">Your Position</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-id-badge text-muted"></i></span>
                                                <input type="text" class="form-control support-form-control" id="business-position" name="position">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="business-subject" class="support-form-label">Subject <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                                            <input type="text" class="form-control support-form-control" id="business-subject" name="subject" required>
                                            <div class="invalid-feedback">Please enter a subject.</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="business-type" class="support-form-label">Inquiry Type</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-tag text-muted"></i></span>
                                            <select class="form-select support-form-control" id="business-type" name="business_type">
                                                <option value="partnership">Partnership Opportunity</option>
                                                <option value="collaboration">Collaboration Request</option>
                                                <option value="press">Press/Media Inquiry</option>
                                                <option value="enterprise">Enterprise Solutions</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="business-message" class="support-form-label">Message <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-comment text-muted"></i></span>
                                            <textarea class="form-control support-form-control" id="business-message" name="message" rows="5" required placeholder="Please describe your proposal or inquiry in detail..."></textarea>
                                            <div class="invalid-feedback">Please enter your message.</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="business-website" class="support-form-label">Website URL</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-globe text-muted"></i></span>
                                            <input type="url" class="form-control support-form-control" id="business-website" name="website" placeholder="https://yourcompany.com">
                                            <div class="invalid-feedback">Please enter a valid URL (include https://)</div>
                                        </div>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="business-copy" name="send_copy">
                                        <label class="form-check-label" for="business-copy">Send me a copy of this message</label>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-lock me-1"></i> Your information is treated confidentially
                                        </div>
                                        <button type="submit" class="btn btn-warning px-4 py-2">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Inquiry
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Support Ticket -->
                    <div id="support-ticket" class="contact-section mb-5 reveal">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary-light p-3 me-3">
                                        <i class="fas fa-ticket-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">Create a Support Ticket</h3>
                                        <p class="text-muted mb-0">Need more detailed assistance?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4 text-center">
                                <p class="mb-4">For more complex issues that require ongoing communication and tracking, we recommend creating a support ticket.</p>
                                <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-lg btn-primary">
                                    <i class="fas fa-ticket-alt me-2"></i> Create Support Ticket
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Link -->
                    <div class="card border-0 shadow-sm mb-5 reveal">
                        <div class="card-body p-4 bg-light text-center">
                            <div class="py-3">
                                <i class="fas fa-question-circle text-primary fa-3x mb-3"></i>
                                <h4>Have you checked our FAQs?</h4>
                                <p>Find quick answers to common questions in our frequently asked questions section.</p>
                                <a href="<?php echo URLROOT; ?>/support/faq" class="btn btn-outline-primary">
                                    <i class="fas fa-book me-2"></i> Browse FAQs
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Office Locations -->
                    <div class="location-section mb-5 reveal">
                        <h3 class="mb-4"><i class="fas fa-map-marked-alt me-2"></i> Our Offices</h3>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">San Francisco (HQ)</h5>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i> 1234 Innovation Way<br>
                                            San Francisco, CA 94107<br>
                                            United States<br><br>
                                            <i class="fas fa-phone text-primary me-2"></i> +1 (555) 555-1234<br>
                                            <i class="fas fa-envelope text-primary me-2"></i> sf@lensi.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">New York</h5>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i> 567 Broadway<br>
                                            New York, NY 10012<br>
                                            United States<br><br>
                                            <i class="fas fa-phone text-primary me-2"></i> +1 (555) 555-5678<br>
                                            <i class="fas fa-envelope text-primary me-2"></i> nyc@lensi.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">London</h5>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i> 123 Tech Square<br>
                                            London, EC2A 4XY<br>
                                            United Kingdom<br><br>
                                            <i class="fas fa-phone text-primary me-2"></i> +44 20 1234 5678<br>
                                            <i class="fas fa-envelope text-primary me-2"></i> london@lensi.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Singapore</h5>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i> 88 Market Street<br>
                                            Singapore, 048948<br>
                                            Singapore<br><br>
                                            <i class="fas fa-phone text-primary me-2"></i> +65 6123 4567<br>
                                            <i class="fas fa-envelope text-primary me-2"></i> singapore@lensi.com
                                        </p>
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

<!-- Contact specific styles -->
<style>
    /* Hero section styling */
    .contact-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 5rem 0 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -5%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .contact-hero::after {
        content: '';
        position: absolute;
        bottom: -15%;
        left: -5%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .text-gradient {
        background: linear-gradient(90deg, #0a1128, #1c2541);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    /* Contact stats styling */
    .stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon i {
        font-size: 1.25rem;
    }

    /* Quick links styling */
    .quick-link {
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #e9ecef;
    }

    .quick-link:hover {
        background-color: #0a1128;
        color: white !important;
        transform: translateY(-3px);
    }

    /* Contact options styling */
    .contact-options .list-group-item {
        border-left: 3px solid transparent;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .contact-options .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0a1128;
    }

    .contact-options .list-group-item.active {
        background-color: #eef1f5;
        border-left-color: #0a1128;
        color: #0a1128;
        font-weight: 600;
    }

    /* Icon circles */
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    /* Social icons */
    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #0a1128;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        background-color: #0a1128;
        color: white;
        transform: translateY(-3px);
    }

    /* Form styling enhancements */
    .form-control:focus {
        border-color: #0a1128;
        box-shadow: 0 0 0 0.25rem rgba(10, 17, 40, 0.15);
    }

    .form-check-input:checked {
        background-color: #0a1128;
        border-color: #0a1128;
    }

    /* Reveal animations */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .sticky-top {
            position: relative;
            top: 0;
        }
    }

    /* Backgrounds */
    .bg-primary-light {
        background-color: rgba(10, 17, 40, 0.1);
    }

    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }

    .bg-info-light {
        background-color: rgba(59, 130, 246, 0.1);
    }

    /* Fade in animations */
    .animate-fade-in {
        animation: fadeIn 1s ease forwards;
    }

    .animate-fade-in-right {
        animation: fadeInRight 1s ease forwards;
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

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

<!-- Contact specific scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reveal animations on scroll
        const revealElements = document.querySelectorAll('.reveal');

        const revealOnScroll = function() {
            revealElements.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        };

        // Run once to reveal elements in view on load
        revealOnScroll();

        // Add event listener
        window.addEventListener('scroll', revealOnScroll);

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(anchorLink => {
            anchorLink.addEventListener('click', function(e) {
                e.preventDefault();

                // Update active category
                document.querySelectorAll('.contact-options .list-group-item').forEach(item => {
                    item.classList.remove('active');
                });

                this.classList.add('active');

                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                // Additional offset for navbar
                const offset = 100;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            });
        });

        // Update active section on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.contact-section');
            let currentSection = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (pageYOffset >= sectionTop - 200) {
                    currentSection = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.contact-options .list-group-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === '#' + currentSection) {
                    item.classList.add('active');
                }
            });
        });

        // Form validation
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>