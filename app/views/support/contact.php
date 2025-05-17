<?php
// Support - Contact page
// This page provides various ways to contact support
?>

<div class="contact-container">
    <div class="contact-header">
        <h1>Contact Support</h1>
        <p>Get in touch with our support team - we're here to help</p>
    </div>

    <div class="contact-grid">
        <div class="contact-info-column">
            <div class="contact-card">
                <div class="contact-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Before You Contact Us</h3>
                </div>
                <p>For faster resolution, please check if your question has already been answered in our resources:</p>
                <ul class="contact-links">
                    <li>
                        <a href="<?php echo URL_ROOT; ?>/pages/support/faq">
                            <i class="fas fa-question-circle"></i>
                            <span>Frequently Asked Questions</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URL_ROOT; ?>/pages/support/new-ticket">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Submit a Support Ticket</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo URL_ROOT; ?>/help/getting-started">
                            <i class="fas fa-book"></i>
                            <span>Help Center & Guides</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="contact-card">
                <div class="contact-card-header">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Support</h3>
                </div>
                <p>Send us an email and we'll get back to you within 24-48 hours.</p>
                <div class="contact-method">
                    <a href="mailto:support@lensi.com" class="contact-email">support@lensi.com</a>
                </div>
                <div class="contact-note">
                    <p><strong>Note:</strong> Please include your account email and any relevant details in your message.</p>
                </div>
            </div>

            <div class="contact-card">
                <div class="contact-card-header">
                    <i class="fas fa-phone-alt"></i>
                    <h3>Phone Support</h3>
                </div>
                <p>For urgent issues, our phone support is available:</p>
                <div class="support-hours">
                    <div class="hours-row">
                        <span class="days">Monday - Friday:</span>
                        <span class="time">9:00 AM - 8:00 PM EST</span>
                    </div>
                    <div class="hours-row">
                        <span class="days">Saturday:</span>
                        <span class="time">10:00 AM - 6:00 PM EST</span>
                    </div>
                    <div class="hours-row">
                        <span class="days">Sunday:</span>
                        <span class="time">Closed</span>
                    </div>
                </div>
                <div class="contact-method">
                    <a href="tel:+18005551234" class="contact-phone">1-800-555-1234</a>
                </div>
            </div>

            <div class="contact-card">
                <div class="contact-card-header">
                    <i class="fas fa-comment"></i>
                    <h3>Social Media</h3>
                </div>
                <p>Follow us on social media for updates and support:</p>
                <div class="social-links">
                    <a href="https://twitter.com/lensi" class="social-link twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61576228714380" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="https://instagram.com/lensi" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="https://linkedin.com/company/lensi" class="social-link linkedin">
                        <i class="fab fa-linkedin-in"></i>
                        <span>LinkedIn</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="contact-form-column">
            <div class="contact-form-card">
                <div class="form-card-header">
                    <h3>Send a Message</h3>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                </div>

                <form id="contactForm" class="contact-form" method="post" action="<?php echo URL_ROOT; ?>/pages/support/submit-contact">
                    <div class="form-group">
                        <label for="contact-name">Your Name</label>
                        <input type="text" id="contact-name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-email">Email Address</label>
                        <input type="email" id="contact-email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-subject">Subject</label>
                        <input type="text" id="contact-subject" name="subject" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-category">Category</label>
                        <select id="contact-category" name="category" class="form-control" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="account">Account Issues</option>
                            <option value="billing">Billing & Payments</option>
                            <option value="technical">Technical Problems</option>
                            <option value="feedback">General Feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="message" class="form-control" rows="6" required></textarea>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="contact-terms" name="terms" required>
                        <label for="contact-terms">I agree to the processing of my data as outlined in the <a href="<?php echo URL_ROOT; ?>/pages/privacy-policy" target="_blank">Privacy Policy</a>.</label>
                    </div>

                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>

            <div class="faq-preview-card">
                <h3>Frequently Asked Questions</h3>
                <div class="faq-preview">
                    <div class="faq-preview-item">
                        <h4>What's the typical response time?</h4>
                        <p>Our support team typically responds within 24-48 hours for email and form submissions. For urgent matters, please use our phone support.</p>
                    </div>
                    <div class="faq-preview-item">
                        <h4>How do I track my support ticket?</h4>
                        <p>Once you submit a ticket, you'll receive a confirmation email with a tracking ID. You can view the status of your tickets in the "My Tickets" section.</p>
                    </div>
                    <div class="faq-preview-item">
                        <h4>Is weekend support available?</h4>
                        <p>We offer limited support on Saturdays from 10AM to 6PM EST. For urgent weekend issues, please use our phone support.</p>
                    </div>
                </div>
                <a href="<?php echo URL_ROOT; ?>/pages/support/faq" class="view-all-faq">View All FAQs</a>
            </div>
        </div>
    </div>

    <div class="office-locations">
        <h3>Our Offices</h3>
        <div class="locations-grid">
            <div class="location-card">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>North America</h4>
                <p>123 Tech Square<br>Boston, MA 02129<br>United States</p>
            </div>
            <div class="location-card">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Europe</h4>
                <p>45 Innovation Street<br>London, EC2A 4BQ<br>United Kingdom</p>
            </div>
            <div class="location-card">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Asia Pacific</h4>
                <p>88 Digital Avenue<br>Singapore, 018956<br>Singapore</p>
            </div>
        </div>
    </div>
</div>

<style>
    .contact-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .contact-header h1 {
        font-size: 36px;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .contact-header p {
        font-size: 18px;
        color: #74767e;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 30px;
        margin-bottom: 50px;
    }

    .contact-info-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .contact-card {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .contact-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .contact-card-header i {
        font-size: 22px;
        color: #2c3e50;
        margin-right: 15px;
    }

    .contact-card-header h3 {
        font-size: 20px;
        color: #2c3e50;
        margin: 0;
    }

    .contact-card p {
        color: #62646a;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .contact-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .contact-links li {
        margin-bottom: 12px;
    }

    .contact-links a {
        display: flex;
        align-items: center;
        color: #2c3e50;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .contact-links a:hover {
        color: #1a252f;
        transform: translateX(3px);
    }

    .contact-links a i {
        margin-right: 10px;
        font-size: 16px;
    }

    .contact-method {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        text-align: center;
    }

    .contact-email,
    .contact-phone {
        font-size: 18px;
        font-weight: 500;
        color: #2c3e50;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .contact-email:hover,
    .contact-phone:hover {
        color: #1a252f;
        transform: scale(1.02);
    }

    .contact-note {
        background: #fff8e1;
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 14px;
    }

    .contact-note p {
        margin: 0;
        color: #f57c00;
    }

    .support-hours {
        margin-bottom: 15px;
    }

    .hours-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .days {
        font-weight: 500;
        color: #404145;
    }

    .time {
        color: #62646a;
    }

    .social-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .social-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        transform: translateY(-3px);
    }

    .social-link.twitter {
        background-color: #1da1f2;
    }

    .social-link.facebook {
        background-color: #4267b2;
    }

    .social-link.instagram {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    }

    .social-link.linkedin {
        background-color: #0077b5;
    }

    .contact-form-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .contact-form-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    .form-card-header {
        margin-bottom: 25px;
    }

    .form-card-header h3 {
        font-size: 22px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 10px;
    }

    .form-card-header p {
        color: #62646a;
        margin: 0;
    }

    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        margin-bottom: 5px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2c3e50;
        outline: none;
        box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .form-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-checkbox input[type="checkbox"] {
        margin-top: 4px;
    }

    .form-checkbox label {
        font-size: 14px;
        color: #62646a;
    }

    .form-checkbox a {
        color: #2c3e50;
        text-decoration: underline;
    }

    .submit-btn {
        background-color: #2c3e50;
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-end;
    }

    .submit-btn:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }

    .faq-preview-card {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .faq-preview-card h3 {
        font-size: 20px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .faq-preview {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .faq-preview-item {
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .faq-preview-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .faq-preview-item h4 {
        font-size: 16px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 8px;
    }

    .faq-preview-item p {
        color: #62646a;
        font-size: 14px;
        line-height: 1.5;
        margin: 0;
    }

    .view-all-faq {
        display: inline-block;
        color: #2c3e50;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .view-all-faq:hover {
        color: #1a252f;
        text-decoration: underline;
    }

    .office-locations {
        margin-top: 40px;
    }

    .office-locations h3 {
        font-size: 22px;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 25px;
    }

    .locations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .location-card {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: all 0.3s ease;
    }

    .location-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .location-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        background: rgba(44, 62, 80, 0.1);
        border-radius: 50%;
        margin: 0 auto 15px;
    }

    .location-icon i {
        font-size: 22px;
        color: #2c3e50;
    }

    .location-card h4 {
        font-size: 18px;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 10px;
    }

    .location-card p {
        color: #62646a;
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .contact-header h1 {
            font-size: 30px;
        }

        .contact-header p {
            font-size: 16px;
        }

        .contact-grid {
            gap: 20px;
        }

        .social-links {
            justify-content: space-between;
        }

        .social-link {
            flex: 1;
            min-width: 110px;
            justify-content: center;
        }

        .locations-grid {
            grid-template-columns: 1fr;
        }

        .submit-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .contact-header h1 {
            font-size: 26px;
        }

        .contact-method {
            padding: 12px 10px;
        }

        .contact-email,
        .contact-phone {
            font-size: 16px;
        }

        .social-links {
            flex-wrap: wrap;
        }

        .social-link {
            flex: 0 0 calc(50% - 5px);
        }

        .contact-form-card {
            padding: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const contactForm = document.getElementById('contactForm');

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                let valid = true;
                const name = document.getElementById('contact-name').value.trim();
                const email = document.getElementById('contact-email').value.trim();
                const subject = document.getElementById('contact-subject').value.trim();
                const category = document.getElementById('contact-category').value;
                const message = document.getElementById('contact-message').value.trim();
                const terms = document.getElementById('contact-terms').checked;

                // Basic validation
                if (name === '') {
                    showError('contact-name', 'Please enter your name');
                    valid = false;
                }

                if (email === '') {
                    showError('contact-email', 'Please enter your email address');
                    valid = false;
                } else if (!isValidEmail(email)) {
                    showError('contact-email', 'Please enter a valid email address');
                    valid = false;
                }

                if (subject === '') {
                    showError('contact-subject', 'Please enter a subject');
                    valid = false;
                }

                if (category === '') {
                    showError('contact-category', 'Please select a category');
                    valid = false;
                }

                if (message === '') {
                    showError('contact-message', 'Please enter your message');
                    valid = false;
                } else if (message.length < 20) {
                    showError('contact-message', 'Please provide a more detailed message (at least 20 characters)');
                    valid = false;
                }

                if (!terms) {
                    alert('Please agree to the privacy policy before submitting.');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('error');

            // Check if error message already exists
            let errorElement = field.parentNode.querySelector('.error-message');

            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.style.color = '#e74c3c';
                errorElement.style.fontSize = '12px';
                errorElement.style.marginTop = '5px';
                field.parentNode.appendChild(errorElement);
            }

            errorElement.textContent = message;

            // Remove error when field is focused
            field.addEventListener('focus', function() {
                this.classList.remove('error');
                errorElement.textContent = '';
            }, {
                once: true
            });
        }

        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        // Add animation to cards
        const cards = document.querySelectorAll('.contact-card, .contact-form-card, .faq-preview-card, .location-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + (index * 100));
        });
    });
</script>

<!-- Form validation scripts -->
<script src="<?php echo URL_ROOT; ?>/js/support-form-validation.js"></script>
<script src="<?php echo URL_ROOT; ?>/js/contact-validation.js"></script>