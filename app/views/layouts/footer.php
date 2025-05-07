</main>
    
    <!-- Modern Minimalist Footer -->
    <footer class="site-footer">
        <div class="container footer-container">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="footer-widget animation-element mb-4 mb-lg-0 reveal-left">
                        <h4 class="widget-title">lenSi</h4>
                        <p class="footer-description">Connect with talented freelancers and find quality clients. Our platform makes freelancing and hiring simpler and more rewarding.</p>
                        <div class="social-links mt-4">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="footer-widget animation-element mb-4 mb-lg-0 reveal">
                        <h5 class="widget-title">For Clients</h5>
                        <ul class="footer-links">
                            <li><a href="<?php echo URL_ROOT; ?>/services/browse" class="footer-link">Find Freelancers</a></li>
                            <li><a href="<?php echo URL_ROOT; ?>/jobs/post" class="footer-link">Post a Job</a></li>
                            <li><a href="#" class="footer-link">Payment Methods</a></li>
                            <li><a href="#" class="footer-link">Client Reviews</a></li>
                            <li><a href="#" class="footer-link">Success Stories</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="footer-widget animation-element mb-4 mb-lg-0 reveal">
                        <h5 class="widget-title">For Freelancers</h5>
                        <ul class="footer-links">
                            <li><a href="<?php echo URL_ROOT; ?>/jobs" class="footer-link">Find Jobs</a></li>
                            <li><a href="<?php echo URL_ROOT; ?>/services/create" class="footer-link">Create a Service</a></li>
                            <li><a href="#" class="footer-link">Getting Paid</a></li>
                            <li><a href="#" class="footer-link">Growth Tips</a></li>
                            <li><a href="#" class="footer-link">Community</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-12 col-lg-2 mt-4 mt-lg-0">
                    <div class="footer-widget animation-element mb-4 mb-lg-0 reveal-right">
                        <h5 class="widget-title">Contact Us</h5>
                        <p class="contact-info"><i class="fas fa-envelope me-2"></i> support@<?php echo strtolower(SITE_NAME); ?>.com</p>
                        <p class="contact-info"><i class="fas fa-phone-alt me-2"></i> +1 (555) 123-4567</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright mb-0">
                            &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-legal-links">
                            <a href="#" class="legal-link">Terms of Service</a>
                            <a href="#" class="legal-link">Privacy Policy</a>
                            <a href="#" class="legal-link">Accessibility</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer Style -->
    <style>
        /* Minimalist footer styling */
        .site-footer {
            position: relative;
            color: #333333; /* Explicit dark color instead of var(--secondary-color) */
            overflow: hidden;
            padding: 60px 0 0;
            margin-top: 5rem; /* Explicit margin instead of var(--spacing-5xl) */
            background: #ffffff; /* Explicit white background */
            border-top: 1px solid #e9ecef; /* Explicit border color */
        }

        .footer-container {
            padding: 0 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-content {
            position: relative;
        }

        .footer-bottom {
            padding: 1rem 0; /* Explicit padding */
            margin-top: 40px;
            border-top: 1px solid #e9ecef; /* Explicit border color */
            position: relative;
        }

        .widget-title {
            color: #333333; /* Explicit dark color */
            font-weight: 600; /* Explicit font weight */
            margin-bottom: 1rem; /* Explicit margin */
            font-size: 1.25rem; /* Explicit font size */
            position: relative;
            display: inline-block;
        }

        .widget-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: #4e73df; /* Explicit primary color */
        }

        .footer-description {
            color: #6c757d; /* Explicit secondary light color */
            line-height: 1.6; /* Explicit line height */
            margin-bottom: 1rem; /* Explicit margin */
            font-size: 14px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-link {
            color: #6c757d; /* Explicit secondary light color */
            text-decoration: none;
            display: block;
            padding: 8px 0;
            font-size: 14px;
            transition: all 0.3s ease-out; /* Explicit transition */
            position: relative;
        }

        .footer-link:hover {
            color: #4e73df; /* Explicit primary color */
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #ffffff; /* Explicit white color */
            color: #333333; /* Explicit dark color */
            text-decoration: none;
            transition: all 0.3s ease-in-out; /* Explicit transition */
            font-size: 1rem;
            border: 1px solid #e9ecef; /* Explicit border color */
        }

        .social-link:hover {
            background-color: #4e73df; /* Explicit primary color */
            color: #ffffff; /* Explicit white color */
            border-color: #4e73df; /* Explicit primary color */
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .copyright {
            color: #6c757d; /* Explicit secondary light color */
            font-size: 0.875rem; /* Explicit font size */
        }

        .footer-legal-links {
            display: flex;
            gap: 1rem; /* Explicit gap */
            justify-content: flex-end;
        }

        .legal-link {
            color: #6c757d; /* Explicit secondary light color */
            text-decoration: none;
            font-size: 0.875rem; /* Explicit font size */
            transition: all 0.3s ease-in-out; /* Explicit transition */
        }

        .legal-link:hover {
            color: #4e73df; /* Explicit primary color */
        }
        
        .contact-info {
            display: flex;
            align-items: center;
            color: #6c757d; /* Explicit secondary light color */
            margin-bottom: 10px;
            font-size: 14px;
        }

        .contact-info i {
            width: 20px;
            color: #4e73df; /* Explicit primary color */
            font-size: 14px;
        }

        @media (max-width: 767px) {
            .footer-legal-links {
                justify-content: flex-start;
                margin-top: 0.5rem; /* Explicit margin */
                gap: 0.5rem; /* Explicit gap */
            }
            
            .site-footer {
                padding-top: 40px;
            }
            
            .footer-container {
                padding: 0 20px;
            }
        }
    </style>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Animation engine for reveal on scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer initialization for animation on scroll
            const animateElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
            
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            // Unobserve after animation is triggered
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.1
                });
                
                animateElements.forEach(element => {
                    observer.observe(element);
                });
            } else {
                // Fallback for browsers that don't support Intersection Observer
                animateElements.forEach(element => {
                    element.classList.add('active');
                });
            }
            
            // Function to add animation classes to elements with animation-element class
            // Will be delayed based on their position
            function setAnimationDelays() {
                const animationGroups = document.querySelectorAll('.animation-group');
                
                animationGroups.forEach(group => {
                    const elements = group.querySelectorAll('.animation-element');
                    
                    elements.forEach((element, index) => {
                        element.style.animationDelay = `${index * 0.1}s`;
                    });
                });
            }
            
            setAnimationDelays();
        });
    </script>

    <?php if(isset($data['js'])) : ?>
        <script><?php echo $data['js']; ?></script>
    <?php endif; ?>
</body>
</html>
